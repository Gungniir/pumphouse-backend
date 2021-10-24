<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Nette\Utils\DateTime;

class ResidentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $startDate = DateTime::createFromFormat('Y.m.d H:i:s', $this->start_date);

        if (!$startDate) {
            $startDate = new DateTime($this->start_date);
        }

        return [
            'id' => $this->id,
            'fio' => $this->fio,
            'area' => (double)$this->area,
            'start_date' => $startDate->format('Y.m.d H:i:s'),
        ];
    }
}
