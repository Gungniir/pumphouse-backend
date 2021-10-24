<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Period;
use App\Models\Resident;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Response;

class PeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Collection|Period[]
     */
    public function index()
    {
        return Period::all();
    }

    /**
     * Display the specified resource.
     *
     * @param Period $period
     * @return Period
     */
    public function show(Period $period): Period
    {
        return $period;
    }

    /**
     * Calculate bills for period
     *
     * @param Period $period
     * @return JsonResponse
     */
    public function calculate(Period $period)
    {
        if (is_null($period->pumpMeterRecord)) {
            return Response::json('You must put record first', 400); // Bad request
        }

        if (count($period->bills) > 0) {
            return Response::json('Bills is already generated', 409); // Conflict
        }

        // TODO: Получение цены
        $cost = 2;

        $beginDate = new DateTime($period->begin_date);
        $endDate = new DateTime($period->end_date);
        $amountVolume = (double)$period->pumpMeterRecord->amount_volume;

        $totalSeconds = 0;
        $totalArea = 0.;
        $result = [];

        $residents = Resident::all();

        foreach ($residents as $resident){
            $date = new DateTime($resident->start_date);
            $registeredInSeconds = 0;

            // Если резидент был зарегистрирован раньше начала периода, то тарифицируем по полному тарифу
            if ($date->getTimestamp() <= $beginDate->getTimestamp()) {
                $registeredInSeconds = $endDate->getTimestamp() - $beginDate->getTimestamp();
            } else {
                $registeredInSeconds = $endDate->getTimestamp() - $date->getTimestamp();
            }

            $totalSeconds += $registeredInSeconds;
            $totalArea += (double)$resident->area;

            $result[$resident->id] = [
                'seconds' =>  $registeredInSeconds,
                'area' => $resident->area
            ];
        }

        foreach ($result as $residentID => $data) {
            Bill::create([
                'resident_id' => $residentID,
                'period_id' => $period->id,
                'amount_rub' => $amountVolume * $cost / $totalArea / $totalSeconds * $data['seconds'] * $data['area'],
            ]);
        }

        return Response::json('Success');
    }
}
