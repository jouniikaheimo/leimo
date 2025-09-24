<?php

namespace Database\Factories;

use App\Enums\TimeEntryType;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TimeEntry>
 */
class TimeEntryFactory extends Factory
{
    protected $model = TimeEntry::class;

    public function definition(): array
    {
        $entryType = fake()->randomElement(TimeEntryType::cases());

        $clockedIn = fake()->dateTimeBetween('-1 month', 'now');
        $clockedOut = $entryType->requiresEndTime()
            ? (clone $clockedIn)->modify('+' . fake()->numberBetween(15, 150) . ' minutes')
            : null;

        return [
            'user_id' => User::factory(),
            'clocked_in_at' => $clockedIn,
            'clocked_out_at' => $clockedOut,
            'notes' => fake()->optional()->sentence(),
            'entry_type' => $entryType,
        ];
    }
}
