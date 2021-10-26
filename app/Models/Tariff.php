<?php

namespace App\Models;

use Database\Factories\TariffFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Tariff
 *
 * @property int $id
 * @property int $period_id
 * @property float $cost
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static TariffFactory factory(...$parameters)
 * @method static Builder|Tariff newModelQuery()
 * @method static Builder|Tariff newQuery()
 * @method static Builder|Tariff query()
 * @method static Builder|Tariff whereCost($value)
 * @method static Builder|Tariff whereCreatedAt($value)
 * @method static Builder|Tariff whereId($value)
 * @method static Builder|Tariff wherePeriodId($value)
 * @method static Builder|Tariff whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read Period $period
 */
class Tariff extends Model
{
    use HasFactory;

    protected $fillable = ['cost'];

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }
}
