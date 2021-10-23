<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Bill
 *
 * @method static Builder|Bill newModelQuery()
 * @method static Builder|Bill newQuery()
 * @method static Builder|Bill query()
 * @mixin Eloquent
 * @property int $id
 * @property int $resident_id
 * @property int $period_id
 * @property float $amount_rub
 * @method static Builder|Bill whereAmountRub($value)
 * @method static Builder|Bill whereId($value)
 * @method static Builder|Bill wherePeriodId($value)
 * @method static Builder|Bill whereResidentId($value)
 * @property-read Period $period
 * @property-read Resident $resident
 */
class Bill extends Model
{
    use HasFactory;

    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }
}
