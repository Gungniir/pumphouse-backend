<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $period_id
 * @property float $cost
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class TariffResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'period_id' => $this->period_id,
            'cost' => $this->cost,
        ];
    }
}
