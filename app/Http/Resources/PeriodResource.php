<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Nette\Utils\DateTime;

class PeriodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $beginDate = DateTime::createFromFormat('Y.m.d H:i:s', $this->begin_date);
        $endDate = DateTime::createFromFormat('Y.m.d H:i:s', $this->end_date);

        if (!$beginDate) {
            $beginDate = new DateTime($this->begin_date);
        }
        if (!$endDate) {
            $endDate = new DateTime($this->end_date);
        }

        return [
            'id' => $this->id,
            'begin_date' => $beginDate->format('Y.m.d H:i:s'),
            'end_date' => $endDate->format('Y.m.d H:i:s'),
        ];
    }
}
