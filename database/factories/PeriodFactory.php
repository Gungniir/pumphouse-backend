<?php

namespace Database\Factories;

use App\Models\Period;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method Period|Collection make($attributes = [], ?Model $parent = null)
 */
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
            'begin_date' => $previousMonth->format('Y.m.01 00:00:00'),
            'end_date' => $previousMonth->format('Y.m.t 23:59:59'),
        ];
    }

    public function fromDate(int $year, int $month): PeriodFactory
    {
        $date = new DateTime();
        $date->setDate($year, $month, 1);

        return $this->state([
            'begin_date' => $date->format('Y.m.01 00:00:00'),
            'end_date' => $date->format('Y.m.t 23:59:59'),
        ]);
    }
}
