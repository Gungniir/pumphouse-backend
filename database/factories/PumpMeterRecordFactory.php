<?php

namespace Database\Factories;

use App\Models\Period;
use App\Models\PumpMeterRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

class PumpMeterRecordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PumpMeterRecord::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'period_id' => Period::factory(),
            'amount_volume' => $this->faker->randomFloat(2, 400, 1000)
        ];
    }
}
