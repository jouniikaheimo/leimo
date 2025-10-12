<?php

namespace App\Filament\Resources\Users\UserResource\RelationManagers;

use App\Enums\TimeEntryType;
use App\Models\TimeEntry;
use App\Models\Setting;
use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TimeEntriesRelationManager extends RelationManager
{
    protected static string $relationship = 'timeEntries';

    protected static ?string $title = 'Kellokirjaukset';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Select::make('entry_type')
                    ->label('Kirjauksen tyyppi')
                    ->options(TimeEntryType::options())
                    ->default(TimeEntryType::WorkStart->value)
                    ->required()
                    ->live(),
                DateTimePicker::make('clocked_in_at')
                    ->label('Sisään')
                    ->required()
                    ->native(false)
                    ->closeOnDateSelection(),
                DateTimePicker::make('clocked_out_at')
                    ->label('Ulos')
                    ->native(false)
                    ->closeOnDateSelection()
                    ->after('clocked_in_at')
                    ->nullable()
                    ->visible(function (Get $get): bool {
                        $type = $get('entry_type');
                        $typeInstance = $type instanceof TimeEntryType ? $type : TimeEntryType::tryFrom((string) $type);

                        return $typeInstance?->requiresEndTime() ?? false;
                    })
                    ->required(function (Get $get): bool {
                        $type = $get('entry_type');
                        $typeInstance = $type instanceof TimeEntryType ? $type : TimeEntryType::tryFrom((string) $type);

                        return $typeInstance?->requiresEndTime() ?? false;
                    }),
                Textarea::make('notes')
                    ->label('Huomiot')
                    ->rows(3)
                    ->maxLength(65535),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('clocked_in_at', 'desc')
            ->columns([
                TextColumn::make('entry_type')
                    ->label('Tyyppi')
                    ->formatStateUsing(fn (TimeEntry $record): string => $record->entry_type->label())
                    ->badge()
                    ->color(fn (TimeEntry $record): string => $record->entry_type->badgeColor()),
                TextColumn::make('clocked_in_at')
                    ->label('Sisään')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                TextColumn::make('clocked_out_at')
                    ->label('Ulos')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('duration')
                    ->label('Kesto')
                    ->state(function (TimeEntry $record): ?string {
                        $start = $record->clocked_in_at;
                        $end = $record->clocked_out_at;

                        if ($record->entry_type->requiresEndTime()) {
                            if (! $start || ! $end) {
                                return 'Kesken';
                            }

                            $minutes = $start->diffInMinutes($end);

                            return $this->formatMinutes($minutes);
                        }

                        return null;
                    }),
                TextColumn::make('notes')
                    ->label('Huomiot')
                    ->wrap()
                    ->toggleable()
                    ->limit(80),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Uusi kirjaus'),
                Action::make('closeOpenShift')
                    ->label('Leimaa työpäivä päättyneeksi')
                    ->icon('heroicon-o-hand-thumb-up')
                    ->visible(function (): bool {
                        $user = auth()->user();
                        $isAdmin = config('timeclock.dev_bypass_admin') || in_array($user?->email, (array) config('timeclock.admin_emails', []), true);

                        if (! $isAdmin) {
                            return false;
                        }

                        $owner = $this->getOwnerRecord();
                        $openStart = TimeEntry::query()
                            ->where('user_id', $owner->id)
                            ->where('entry_type', TimeEntryType::WorkStart)
                            ->orderByDesc('clocked_in_at')
                            ->get()
                            ->first(function (TimeEntry $start) use ($owner): bool {
                                return ! TimeEntry::query()
                                    ->where('user_id', $owner->id)
                                    ->where('entry_type', TimeEntryType::WorkEnd)
                                    ->whereDate('clocked_in_at', $start->clocked_in_at->toDateString())
                                    ->exists();
                            });

                        return (bool) $openStart;
                    })
                    ->form([
                        DateTimePicker::make('ended_at')
                            ->label('Lopetusaika')
                            ->native(false)
                            ->closeOnDateSelection()
                            ->default(now())
                            ->required(),
                        Textarea::make('notes')->label('Huomio (valinnainen)')->rows(2),
                    ])
                    ->action(function (array $data): void {
                        $owner = $this->getOwnerRecord();

                        /** Find latest open WorkStart without matching WorkEnd for the same day */
                        $openStart = TimeEntry::query()
                            ->where('user_id', $owner->id)
                            ->where('entry_type', TimeEntryType::WorkStart)
                            ->orderByDesc('clocked_in_at')
                            ->get()
                            ->first(function (TimeEntry $start) use ($owner): bool {
                                return ! TimeEntry::query()
                                    ->where('user_id', $owner->id)
                                    ->where('entry_type', TimeEntryType::WorkEnd)
                                    ->whereDate('clocked_in_at', $start->clocked_in_at->toDateString())
                                    ->exists();
                            });

                        if (! $openStart) {
                            return; // nothing to close
                        }

                        $endedAt = Carbon::parse($data['ended_at']);

                        // Ensure end is after start
                        if ($endedAt->lessThanOrEqualTo($openStart->clocked_in_at)) {
                            throw \Illuminate\Validation\ValidationException::withMessages([
                                'ended_at' => 'Lopetusajan tulee olla työpäivän aloituksen jälkeen.',
                            ]);
                        }

                        TimeEntry::create([
                            'user_id' => $owner->id,
                            'clocked_in_at' => $endedAt,
                            'clocked_out_at' => null,
                            'entry_type' => TimeEntryType::WorkEnd,
                            'notes' => $data['notes'] ?? null,
                        ]);

                        \App\Models\TimeEntryAudit::create([
                            'acted_by_id' => auth()->id(),
                            'user_id' => $owner->id,
                            'action' => 'close_shift',
                            'payload' => [
                                'ended_at' => $endedAt->toIso8601String(),
                                'notes' => $data['notes'] ?? null,
                            ],
                        ]);
                    }),
            ])
            ->recordActions([
                EditAction::make()->label('Muokkaa'),
                DeleteAction::make()->label('Poista'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Poista valitut'),
                ]),
            ]);
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
}
