<?php

namespace Database\Factories;

use App\Models\Period;
use App\Models\Tariff;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method Tariff|Collection create($attributes = [], ?Model $parent = null)
 * @method Tariff|Collection make($attributes = [], ?Model $parent = null)
 */
class TariffFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tariff::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'period_id' => Period::factory(),
            'cost' => $this->faker->randomFloat(2, 40, 100),
        ];
    }
}
