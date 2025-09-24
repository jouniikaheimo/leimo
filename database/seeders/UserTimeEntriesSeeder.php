<?php

namespace Database\Seeders;

use App\Enums\TimeEntryType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserTimeEntriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $periodStart = $now->copy()->subWeek()->startOfWeek();
        $periodEnd = $now->copy()->endOfWeek();

        $days = [];
        for ($date = $periodStart->copy(); $date->lte($periodEnd); $date->addDay()) {
            $days[] = $date->copy();
        }

        $minuteIntervals = [0, 15, 30, 45];

        User::factory()
            ->count(10)
            ->create()
            ->each(function (User $user) use ($days, $minuteIntervals): void {
                $workingDays = collect($days)
                    ->filter(fn (Carbon $day) => $day->isWeekday())
                    ->shuffle()
                    ->take(random_int(6, 8));

                $workingDays->each(function (Carbon $day) use ($user, $minuteIntervals): void {
                    $shiftStart = $day->copy()->setTime(random_int(7, 9), $minuteIntervals[array_rand($minuteIntervals)]);
                    $shiftBaseHours = random_int(7, 8);
                    $shiftExtraMinutes = $minuteIntervals[array_rand($minuteIntervals)];
                    $expectedShiftEnd = $shiftStart->copy()->addHours(8);
                    $actualShiftEnd = $shiftStart->copy()->addHours($shiftBaseHours)->addMinutes($shiftExtraMinutes);

                    $hasClockOut = random_int(0, 5) !== 0; // roughly 1/6 days missing a clock-out

                    // Work start entry
                    $user->timeEntries()->create([
                        'clocked_in_at' => $shiftStart->copy(),
                        'clocked_out_at' => null,
                        'entry_type' => TimeEntryType::WorkStart,
                        'notes' => null,
                    ]);

                    // Morning coffee break
                    $coffeeStart = $shiftStart->copy()->addHours(2)->setMinute($minuteIntervals[array_rand($minuteIntervals)]);
                    $coffeeEnd = $coffeeStart->copy()->addMinutes(15);
                    $user->timeEntries()->create([
                        'clocked_in_at' => $coffeeStart,
                        'clocked_out_at' => $coffeeEnd,
                        'entry_type' => TimeEntryType::CoffeeBreak,
                        'notes' => fake()->optional(0.3)->sentence(),
                    ]);

                    // Lunch break
                    $lunchStart = $coffeeEnd->copy()->addHours(1 + random_int(1, 2));
                    $lunchEnd = $lunchStart->copy()->addMinutes(30);
                    $user->timeEntries()->create([
                        'clocked_in_at' => $lunchStart,
                        'clocked_out_at' => $lunchEnd,
                        'entry_type' => TimeEntryType::MealBreak,
                        'notes' => fake()->optional(0.2)->sentence(),
                    ]);

                    // Optional afternoon break
                    if (random_int(0, 1) === 1) {
                        $breakStart = $lunchEnd->copy()->addHours(random_int(1, 2))->setMinute($minuteIntervals[array_rand($minuteIntervals)]);
                        $breakEnd = $breakStart->copy()->addMinutes(15);
                        if ($breakEnd->lt($actualShiftEnd)) {
                            $user->timeEntries()->create([
                                'clocked_in_at' => $breakStart,
                                'clocked_out_at' => $breakEnd,
                                'entry_type' => TimeEntryType::Break,
                                'notes' => fake()->optional(0.2)->sentence(),
                            ]);
                        }
                    }

                    if ($hasClockOut) {
                        $user->timeEntries()->create([
                            'clocked_in_at' => $actualShiftEnd,
                            'clocked_out_at' => null,
                            'entry_type' => TimeEntryType::WorkEnd,
                            'notes' => $actualShiftEnd->greaterThan($expectedShiftEnd)
                                ? 'Ylityötä ' . $expectedShiftEnd->diffInMinutes($actualShiftEnd) . ' min'
                                : null,
                        ]);
                    }
                });
            });
    }
}
