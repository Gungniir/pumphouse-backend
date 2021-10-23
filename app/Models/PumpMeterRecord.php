<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PumpMeterRecord
 *
 * @property int $id
 * @property int $period_id
 * @property float $amount_volume
 * @method static Builder|PumpMeterRecord newModelQuery()
 * @method static Builder|PumpMeterRecord newQuery()
 * @method static Builder|PumpMeterRecord query()
 * @method static Builder|PumpMeterRecord whereAmountVolume($value)
 * @method static Builder|PumpMeterRecord whereId($value)
 * @method static Builder|PumpMeterRecord wherePeriodId($value)
 * @mixin Eloquent
 * @property-read Period $period
 */
class PumpMeterRecord extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }
}
