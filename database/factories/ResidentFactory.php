<?php

namespace Database\Factories;

use App\Models\Resident;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResidentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Resident::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'fio' => $this->faker->lastName . ' ' . $this->faker->firstName,
            'area' => $this->faker->randomFloat(2, 10, 100),
            'start_date' => $this->faker->dateTimeBetween('-2 months')
        ];
    }
}
