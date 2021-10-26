<?php

namespace App\Models;

use Database\Factories\PeriodFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Period
 *
 * @property int $id
 * @property string $begin_date
 * @property string $end_date
 * @method static PeriodFactory factory(...$parameters)
 * @method static Builder|Period newModelQuery()
 * @method static Builder|Period newQuery()
 * @method static Builder|Period query()
 * @method static Builder|Period whereBeginDate($value)
 * @method static Builder|Period whereEndDate($value)
 * @method static Builder|Period whereId($value)
 * @mixin Eloquent
 * @property-read Collection|Bill[] $bills
 * @property-read int|null $bills_count
 * @property-read PumpMeterRecord|null $pumpMeterRecord
 * @property-read Tariff|null $tariff
 */
class Period extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    public function pumpMeterRecord(): HasOne
    {
        return $this->hasOne(PumpMeterRecord::class);
    }

    public function tariff(): HasOne
    {
        return $this->hasOne(Tariff::class);
    }
}
