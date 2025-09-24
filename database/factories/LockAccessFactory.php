<?php

namespace Database\Factories;

use App\Models\LockAccess;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LockAccess>
 */
class LockAccessFactory extends Factory
{
    protected $model = LockAccess::class;

    public function definition(): array
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        $locks = ['LCK-101', 'LCK-102', 'LCK-201', 'LCK-305', 'LCK-401'];
        $locations = ['Pääovi', 'Varasto', 'Toimisto A', 'Lastaus', 'Yläkerta'];

        return [
            'user_id' => $user->id,
            'badge_number' => $user->badge_number ?? strtoupper(fake()->bothify('ID###??')),
            'lock_number' => fake()->randomElement($locks),
            'location' => fake()->randomElement($locations),
            'opened_at' => fake()->dateTimeBetween('-30 days', 'now'),
            'result' => fake()->boolean(90) ? 'granted' : 'denied',
            'meta' => [
                'controller' => fake()->bothify('CTL-###'),
                'reader' => fake()->bothify('RDR-##'),
            ],
        ];
    }
}

