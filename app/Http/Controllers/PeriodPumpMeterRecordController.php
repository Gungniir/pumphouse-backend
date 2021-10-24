<?php

namespace App\Http\Controllers;

use App\Models\Period;
use App\Models\PumpMeterRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PeriodPumpMeterRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Period $period
     * @return PumpMeterRecord
     */
    public function index(Period $period): PumpMeterRecord
    {
        return $period->pumpMeterRecord;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Period $period
     * @return JsonResponse
     */
    public function store(Request $request, Period $period): JsonResponse
    {
        $request->validate([
            'amount_volume' => 'required|numeric',
        ]);

        if ($period->hasOne(PumpMeterRecord::class)) {
            return Response::json('Record is already set', 409); // Conflict
        }

        return Response::json($period->pumpMeterRecord()->save(
            new PumpMeterRecord(['amount_volume' => $request->input('amount_volume')])
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param PumpMeterRecord $pumpMeterRecord
     * @return string
     */
    public function update(Request $request, PumpMeterRecord $pumpMeterRecord): string
    {
        $request->validate([
            'period_id' => 'required|integer',
            'amount_volume' => 'required|numeric',
        ]);

        $pumpMeterRecord->period_id = $request->input('period_id');
        $pumpMeterRecord->amount_volume = $request->input('amount_volume');

        $pumpMeterRecord->saveOrFail();

        return 'success';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param PumpMeterRecord $pumpMeterRecord
     * @return string
     */
    public function destroy(PumpMeterRecord $pumpMeterRecord): string
    {
        $pumpMeterRecord->forceDelete();

        return 'Success';
    }
}
