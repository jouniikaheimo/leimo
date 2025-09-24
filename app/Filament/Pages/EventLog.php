<?php

namespace App\Filament\Pages;

use App\Enums\TimeEntryType;
use App\Models\TimeEntry;
use App\Models\User;
use BackedEnum;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use UnitEnum;

class EventLog extends Page
{
    private const STANDARD_SHIFT_HOURS = 8;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?string $navigationLabel = 'Kellokortti';

    protected static string | UnitEnum | null $navigationGroup = 'Työaika';

    protected static ?string $title = 'Kellokortin tapahtumat';

    protected string $view = 'filament.pages.event-log';

    protected function getViewData(): array
    {
        $timezone = config('app.timezone');
        // Ensure Carbon formats (translatedFormat) render in Finnish for this page
        Carbon::setLocale('fi');

        // Persist & restore filters
        $persisted = session('eventlog.filters', []);

        // Optional range filters – defaults to last used or current month
        $fromInput = request('from', $persisted['from'] ?? null);
        $untilInput = request('until', $persisted['until'] ?? null);
        $from = $fromInput ? Carbon::parse($fromInput)->startOfDay() : now()->startOfMonth();
        $until = $untilInput ? Carbon::parse($untilInput)->endOfDay() : now()->endOfDay();

        // Standard shift length in minutes (supports 7.5h etc.)
        $shiftHours = (float) request('shift_hours', $persisted['shift_hours'] ?? config('timeclock.standard_shift_hours', self::STANDARD_SHIFT_HOURS));
        $shiftMinutes = (int) round($shiftHours * 60);

        $users = User::query()
            ->with(['timeEntries' => fn (Relation $query) => $query
                ->whereNotNull('clocked_in_at')
                ->whereBetween('clocked_in_at', [$from, $until])
                ->orderBy('clocked_in_at')
            ])
            ->orderBy('name')
            ->get();

        $userSummaries = $users->map(function (User $user) use ($timezone): array {
            $shiftMinutes = (int) round((float) request('shift_hours', \App\Models\Setting::get('standard_shift_hours', config('timeclock.standard_shift_hours', self::STANDARD_SHIFT_HOURS))) * 60);
            $days = $this->buildDailyData($user->timeEntries, $timezone, $shiftMinutes);
            $monthly = $this->buildMonthlySummary($days);
            $hasOpenOvertime = $days->contains(fn ($d) => ($d['shift']['is_open_overtime'] ?? false) === true);
            $hasOpenOvertimeToday = $days->contains(fn ($d) => ($d['shift']['is_open_overtime'] ?? false) === true && $d['date']->isToday());

            $entriesCount = $days->sum(fn (array $day) => count($day['entries']));
            $totalMinutes = $days->sum(fn (array $day) => $day['shift']['duration_minutes'] ?? 0);

            return [
                'user' => $user,
                'days' => $days,
                'monthly' => $monthly,
                'flags' => [
                    'open_overtime' => $hasOpenOvertime,
                    'open_overtime_today' => $hasOpenOvertimeToday,
                ],
                'statistics' => [
                    'entries_count' => $entriesCount,
                    'total_minutes' => $totalMinutes,
                    'total_display' => $totalMinutes ? $this->formatMinutes($totalMinutes) : '0 min',
                ],
            ];
        });

        // Save current filters
        session([
            'eventlog.filters' => [
                'from' => $from->toDateString(),
                'until' => $until->toDateString(),
                'shift_hours' => $shiftHours,
                'entry_group' => request('entry_group', $persisted['entry_group'] ?? 'all'),
            ],
        ]);

        return [
            'userSummaries' => $userSummaries,
            'firstUserId' => $userSummaries->first()['user']->id ?? null,
            'filters' => [
                'from' => $from->toDateString(),
                'until' => $until->toDateString(),
                'shift_hours' => $shiftHours,
                'entry_group' => request('entry_group', $persisted['entry_group'] ?? 'all'),
            ],
        ];
    }

    /**
     * Close an open shift for a user on a given date.
     * @param  int    $userId
     * @param  string $date      Y-m-d (user's local day)
     * @param  string $endedAt   ISO datetime string
     * @param  string|null $notes
     */
    public function closeShift(int $userId, string $date, string $endedAt, ?string $notes = null): void
    {
        $user = auth()->user();
        $admins = (array) config('timeclock.admin_emails', []);
        $bypass = (bool) config('timeclock.dev_bypass_admin');

        if (! $user || (! $bypass && ! in_array($user->email, $admins, true))) {
            return; // no permission
        }

        $endedAtDt = Carbon::parse($endedAt);

        // Find an open WorkStart (same date, no WorkEnd yet)
        $start = TimeEntry::query()
            ->where('user_id', $userId)
            ->where('entry_type', TimeEntryType::WorkStart)
            ->whereDate('clocked_in_at', $date)
            ->orderBy('clocked_in_at')
            ->first();

        if (! $start) {
            return;
        }

        $hasEnd = TimeEntry::query()
            ->where('user_id', $userId)
            ->where('entry_type', TimeEntryType::WorkEnd)
            ->whereDate('clocked_in_at', $date)
            ->exists();

        if ($hasEnd) {
            return;
        }

        if ($endedAtDt->lessThanOrEqualTo($start->clocked_in_at)) {
            return;
        }

        TimeEntry::create([
            'user_id' => $userId,
            'clocked_in_at' => $endedAtDt,
            'clocked_out_at' => null,
            'entry_type' => TimeEntryType::WorkEnd,
            'notes' => $notes,
        ]);

        // Audit log
        \App\Models\TimeEntryAudit::create([
            'acted_by_id' => $user->id,
            'user_id' => $userId,
            'action' => 'close_shift',
            'payload' => [
                'date' => $date,
                'ended_at' => $endedAtDt->toIso8601String(),
                'notes' => $notes,
            ],
        ]);

        // Refresh page content
        $this->dispatch('refresh-page');
    }

    /**
     * @param  Collection<int, TimeEntry>  $entries
     * @return Collection<int, array{
     *   date: Carbon,
     *   entries: Collection<int, array<string, mixed>>,
     *   shift: array<string, mixed>
     * }>
     */
    private function buildDailyData(Collection $entries, string $timezone, int $shiftMinutes): Collection
    {
        return $entries
            ->groupBy(fn (TimeEntry $entry) => $entry->clocked_in_at?->clone()->timezone($timezone)->toDateString())
            ->map(function (Collection $entries, string $date) use ($timezone): array {
                $entries = $entries->sortByDesc('clocked_in_at');

                /** @var TimeEntry|null $shiftStartEntry */
                $shiftStartEntry = $entries
                    ->where('entry_type', TimeEntryType::WorkStart)
                    ->sortBy('clocked_in_at')
                    ->first();

                /** @var TimeEntry|null $shiftEndEntry */
                $shiftEndEntry = $entries
                    ->where('entry_type', TimeEntryType::WorkEnd)
                    ->sortByDesc('clocked_in_at')
                    ->first();

                $shiftStart = $shiftStartEntry?->clocked_in_at?->clone()->timezone($timezone);
                $expectedShiftEnd = $shiftStart?->copy()->addMinutes($this->getStandardShiftMinutes());
                $shiftEnd = $shiftEndEntry?->clocked_in_at?->clone()->timezone($timezone);
                $isOpenShift = $shiftStart && ! $shiftEnd;
                $isOpenOvertime = $isOpenShift && $expectedShiftEnd && now($timezone)->greaterThan($expectedShiftEnd);

                $overtimeMinutes = ($shiftEnd && $expectedShiftEnd && $shiftEnd->greaterThan($expectedShiftEnd))
                    ? $expectedShiftEnd->diffInMinutes($shiftEnd)
                    : null;

                $shiftDurationMinutes = null;
                if ($shiftStart) {
                    $endForDuration = $shiftEnd ?? $expectedShiftEnd;

                    if ($endForDuration) {
                        $shiftDurationMinutes = $shiftStart->diffInMinutes($endForDuration);
                    }
                }

                $entriesData = $entries->map(function (TimeEntry $entry) use ($timezone, $expectedShiftEnd, $shiftEnd, $shiftDurationMinutes, $overtimeMinutes, $isOpenShift) {
                    $type = $entry->entry_type;
                    $start = $entry->clocked_in_at?->clone()->timezone($timezone);
                    $end = $entry->clocked_out_at?->clone()->timezone($timezone);

                    $timeDisplay = $start ? $start->format('H.i') : '-';
                    $isIncomplete = false;

                    if ($type->requiresEndTime()) {
                        if ($start && $end) {
                            $timeDisplay .= ' - ' . $end->format('H.i');
                        } else {
                            $isIncomplete = true;
                        }
                    } elseif ($type === TimeEntryType::WorkStart && $expectedShiftEnd) {
                        $timeDisplay .= ' (' . $expectedShiftEnd->format('H.i') . ')';
                    }

                    $durationDisplay = null;

                    if ($type->requiresEndTime() && $start && $end) {
                        $durationDisplay = $this->formatMinutes($start->diffInMinutes($end));
                    }

                    if ($type === TimeEntryType::WorkStart && $shiftDurationMinutes) {
                        $durationDisplay = $this->formatMinutes($shiftDurationMinutes);
                    }

                    if ($type === TimeEntryType::WorkEnd) {
                        $durationDisplay = $overtimeMinutes
                            ? '+' . $this->formatMinutes($overtimeMinutes)
                            : '-';
                    }

                    return [
                        'id' => $entry->id,
                        'type' => $type->value,
                        'group' => $type->filterGroup(),
                        'label_key' => $type->translationKey(),
                        'label' => $type->label(),
                        'badge_color' => $type->badgeColor(),
                        'time_display' => $timeDisplay,
                        'duration_display' => $durationDisplay,
                        'notes' => $entry->notes,
                        'status' => [
                            'is_open_shift' => $type === TimeEntryType::WorkStart && $isOpenShift,
                            'is_overtime_exit' => $type === TimeEntryType::WorkEnd && ($overtimeMinutes ?? 0) > 0,
                            'is_incomplete' => $isIncomplete,
                        ],
                    ];
                })->values();

                $entriesArray = $entriesData->all();

                // Quality flags
                $hasIncomplete = $entriesData->contains(fn ($e) => ($e['status']['is_incomplete'] ?? false));
                $intervals = [];
                foreach ($entries as $e) {
                    if ($e->entry_type->requiresEndTime() && $e->clocked_in_at && $e->clocked_out_at) {
                        $intervals[] = [$e->clocked_in_at->clone(), $e->clocked_out_at->clone()];
                    }
                }
                $hasOverlap = false;
                for ($i = 0; $i < count($intervals); $i++) {
                    for ($j = $i + 1; $j < count($intervals); $j++) {
                        [$aStart, $aEnd] = $intervals[$i];
                        [$bStart, $bEnd] = $intervals[$j];
                        if ($aStart->lt($bEnd) && $bStart->lt($aEnd)) {
                            $hasOverlap = true;
                            break 2;
                        }
                    }
                }

                    return [
                        'date' => Carbon::parse($date),
                        'entries' => $entriesArray,
                        'shift' => [
                            'start' => $shiftStart,
                            'expected_end' => $expectedShiftEnd,
                            'actual_end' => $shiftEnd,
                            'is_open' => $isOpenShift,
                            'is_open_overtime' => $isOpenOvertime,
                            'overtime_minutes' => $overtimeMinutes,
                            'duration_minutes' => $shiftDurationMinutes,
                            'duration_display' => $shiftDurationMinutes ? $this->formatMinutes($shiftDurationMinutes) : null,
                            'overtime_display' => $overtimeMinutes ? $this->formatMinutes($overtimeMinutes) : null,
                        ],
                        'flags' => [
                            'incomplete' => $hasIncomplete,
                            'overlap' => $hasOverlap,
                        ],
                    ];
                })
                ->sortByDesc('date')
                ->values();
    }

    private function getStandardShiftMinutes(): int
    {
        $shiftHours = (float) request('shift_hours', config('timeclock.standard_shift_hours', self::STANDARD_SHIFT_HOURS));

        return (int) round($shiftHours * 60);
    }

    /**
     * @param  Collection<int, array{date: Carbon, shift: array<string, mixed>}>  $days
     * @return Collection<int, array<string, mixed>>
     */
    private function buildMonthlySummary(Collection $days): Collection
    {
        return collect($days)
            ->groupBy(fn (array $day) => $day['date']->format('Y-m'))
            ->map(function (Collection $monthDays): array {
                /** @var array{date: Carbon, shift: array<string, mixed>} $firstDay */
                $firstDay = $monthDays->first();
                $monthLabel = $firstDay['date']->copy()->translatedFormat('F Y');

                $totalMinutes = $monthDays->sum(fn (array $day) => $day['shift']['duration_minutes'] ?? 0);
                $overtimeMinutes = $monthDays->sum(fn (array $day) => $day['shift']['overtime_minutes'] ?? 0);
                $weekendMinutes = $monthDays->sum(function (array $day) {
                    /** @var Carbon $date */
                    $date = $day['date'];
                    return $date->isWeekend() ? ($day['shift']['duration_minutes'] ?? 0) : 0;
                });
                $weekdayMinutes = max($totalMinutes - $weekendMinutes, 0);

                $openShifts = $monthDays->count(fn (array $day) => $day['shift']['is_open']);
                $workedDays = $monthDays->count(fn (array $day) => ($day['shift']['duration_minutes'] ?? 0) > 0);

                // Simple payable overtime rule: all weekend minutes + weekday overtime minutes
                $payableOvertimeMinutes = $weekendMinutes + $overtimeMinutes;

                return [
                    'label' => ucfirst($monthLabel),
                    'total_minutes' => $totalMinutes,
                    'total_display' => $totalMinutes ? $this->formatMinutes($totalMinutes) : '0 min',
                    'overtime_minutes' => $overtimeMinutes,
                    'overtime_display' => $overtimeMinutes ? $this->formatMinutes($overtimeMinutes) : null,
                    'weekday_minutes' => $weekdayMinutes,
                    'weekday_display' => $weekdayMinutes ? $this->formatMinutes($weekdayMinutes) : '0 min',
                    'weekend_minutes' => $weekendMinutes,
                    'weekend_display' => $weekendMinutes ? $this->formatMinutes($weekendMinutes) : '0 min',
                    'payable_overtime_minutes' => $payableOvertimeMinutes,
                    'payable_overtime_display' => $payableOvertimeMinutes ? $this->formatMinutes($payableOvertimeMinutes) : '0 min',
                    'worked_days' => $workedDays,
                    'open_shifts' => $openShifts,
                ];
            })
            ->sortKeysDesc()
            ->values();
    }

    private function formatMinutes(int $minutes): string
    {
        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        if ($hours > 0 && $remainingMinutes > 0) {
            return sprintf('%d h %02d min', $hours, $remainingMinutes);
        }

        if ($hours > 0) {
            return sprintf('%d h', $hours);
        }

        return sprintf('%d min', $remainingMinutes);
    }

    /**
     * Header actions (e.g., CSV export)
     * @return array<int, Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportCsv')
                ->label('Vie CSV')
                ->action(fn () => $this->exportCsv()),
        ];
    }

    public function exportCsv()
    {
        $timezone = config('app.timezone');
        Carbon::setLocale('fi');

        // Use persisted or current filters
        $filters = session('eventlog.filters', []);
        $from = isset($filters['from']) ? Carbon::parse($filters['from'])->startOfDay() : now()->startOfMonth();
        $until = isset($filters['until']) ? Carbon::parse($filters['until'])->endOfDay() : now()->endOfDay();

        $users = User::query()
            ->with(['timeEntries' => fn (Relation $query) => $query
                ->whereNotNull('clocked_in_at')
                ->whereBetween('clocked_in_at', [$from, $until])
                ->orderBy('clocked_in_at')
            ])
            ->orderBy('name')
            ->get();

        $rows = [];
        foreach ($users as $user) {
            $days = $this->buildDailyData($user->timeEntries, $timezone, $this->getStandardShiftMinutes());
            foreach ($days as $day) {
                foreach ($day['entries'] as $e) {
                    $rows[] = [
                        'user' => $user->name,
                        'date' => $day['date']->format('Y-m-d'),
                        'type' => $e['label'],
                        'start' => $e['time_display'],
                        'duration' => $e['duration_display'] ?? '',
                        'notes' => $e['notes'] ?? '',
                    ];
                }
            }
        }

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Käyttäjä', 'Päivä', 'Tyyppi', 'Aika', 'Kesto', 'Huomiot'], ';');
            foreach ($rows as $r) {
                fputcsv($out, [$r['user'], $r['date'], $r['type'], $r['start'], $r['duration'], $r['notes']], ';');
            }
            fclose($out);
        }, 'event-log-' . now()->format('Ymd_His') . '.csv');
    }
}
