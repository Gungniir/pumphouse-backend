<?php

namespace Database\Factories;

use App\Models\Period;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeriodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Period::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $now = new DateTime();
        $previousMonth = (new DateTime())->setDate(
            (int)$now->format('Y'),
            (int)$now->format('m') - 1,
            1,
        );

        return [
            'begin_date' => $previousMonth->format('Y.m.1 0:0:0'),
            'end_date' => $previousMonth->format('Y.m.t 0:0:0'),
        ];
    }
}
