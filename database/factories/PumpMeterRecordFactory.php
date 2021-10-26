<?php

namespace Database\Factories;

use App\Models\Period;
use App\Models\PumpMeterRecord;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method PumpMeterRecord|Collection make($attributes = [], ?Model $parent = null)
 * @method PumpMeterRecord|Collection create($attributes = [], ?Model $parent = null)
 */
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
