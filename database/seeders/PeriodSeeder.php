<?php

namespace Database\Seeders;

use App\Models\Period;
use App\Models\PumpMeterRecord;
use App\Models\Tariff;
use Illuminate\Database\Seeder;

class PeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        for ($i = 0; $i < 14; $i++) {
            Period::factory()
                ->fromDate(2021, $i - 4)
                ->has(PumpMeterRecord::factory())
                ->has(Tariff::factory())
                ->create();
        }
    }
}
