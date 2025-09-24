<?php

namespace Database\Seeders;

use App\Models\LockAccess;
use Illuminate\Database\Seeder;

class LockAccessSeeder extends Seeder
{
    public function run(): void
    {
        LockAccess::factory()->count(50)->create();
    }
}

