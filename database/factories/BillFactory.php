<?php

namespace Database\Factories;

use App\Models\Bill;
use App\Models\Period;
use App\Models\Resident;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method Bill|Collection make($attributes = [], ?Model $parent = null)
 * @method Bill|Collection create($attributes = [], ?Model $parent = null)
 */
class BillFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Bill::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'resident_id' => Resident::factory(),
            'period_id' => Period::factory(),
            'amount_rub' => $this->faker->randomFloat(2, 100, 10000)
        ];
    }
}
