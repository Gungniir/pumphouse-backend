<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
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
 * @method static Builder|Period newModelQuery()
 * @method static Builder|Period newQuery()
 * @method static Builder|Period query()
 * @method static Builder|Period whereBeginDate($value)
 * @method static Builder|Period whereEndDate($value)
 * @method static Builder|Period whereId($value)
 * @mixin Eloquent
 */
class Period extends Model
{
    use HasFactory;

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    public function pumpMeterRecord(): HasOne
    {
        return $this->hasOne(PumpMeterRecord::class);
    }
}
