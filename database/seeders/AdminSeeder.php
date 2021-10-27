<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    final public function run(): void
    {
        User::factory()->state([
            'login' => config('admin.login'),
            'password' => Hash::make(config('admin.password'))
        ])->create();
    }
}
