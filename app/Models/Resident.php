<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
}
