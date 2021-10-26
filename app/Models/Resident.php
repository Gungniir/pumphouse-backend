<?php

namespace App\Models;

use Database\Factories\ResidentFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Resident
 *
 * @property int $id
 * @property string $fio
 * @property float $area
 * @property string $start_date
 * @method static Builder|Resident newModelQuery()
 * @method static Builder|Resident newQuery()
 * @method static Builder|Resident query()
 * @method static Builder|Resident whereArea($value)
 * @method static Builder|Resident whereFio($value)
 * @method static Builder|Resident whereId($value)
 * @method static Builder|Resident whereStartDate($value)
 * @mixin Eloquent
 * @property-read User|null $user
 * @property-read Collection|Bill[] $bills
 * @property-read int|null $bills_count
 * @method static ResidentFactory factory(...$parameters)
 */
class Resident extends Model
{
    use HasFactory;

    protected $fillable = ['fio', 'area', 'start_date'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }
}
