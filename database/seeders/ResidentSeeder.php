<?php

namespace Database\Seeders;

use App\Models\Resident;
use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;

class ResidentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    final public function run(): void
    {
        Resident::factory()->count(400)->create();
        Resident::factory()
            ->has(User::factory())
            ->count(50)->create();
    }
}
