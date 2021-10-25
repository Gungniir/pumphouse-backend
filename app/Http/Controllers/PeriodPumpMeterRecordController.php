<?php

namespace App\Http\Controllers;

use App\Http\Resources\PumpMeterRecordResource;
use App\Models\Period;
use App\Models\PumpMeterRecord;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class PeriodPumpMeterRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Period $period
     * @return PumpMeterRecordResource
     */
    public function index(Period $period): PumpMeterRecordResource
    {
        return new PumpMeterRecordResource($period->pumpMeterRecord);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Period $period
     * @return Application|ResponseFactory|Response|PumpMeterRecordResource
     */
    public function store(Request $request, Period $period)
    {
        $request->validate([
            'amount_volume' => 'required|numeric',
        ]);

        if (!is_null($period->pumpMeterRecord)) {
            return response('Record is already set', 409); // Conflict
        }

        return new PumpMeterRecordResource($period->pumpMeterRecord()->save(
            new PumpMeterRecord(['amount_volume' => $request->input('amount_volume')])
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Period $period
     * @param PumpMeterRecord $pumpMeterRecord
     * @return Application|ResponseFactory|Response
     * @throws Throwable
     */
    public function update(Request $request, Period $period, PumpMeterRecord $pumpMeterRecord)
    {
        // Если убрать $period из параметров, то laravel не будет искать $pumpMeterRecord

        $request->validate([
            'amount_volume' => 'required|numeric',
        ]);

        $pumpMeterRecord->amount_volume = $request->input('amount_volume');

        $pumpMeterRecord->saveOrFail();

        return response('success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param PumpMeterRecord $pumpMeterRecord
     * @return string
     */
    public function destroy(Period $period, PumpMeterRecord $pumpMeterRecord): string
    {
        // Если убрать $period из параметров, то laravel не будет искать $pumpMeterRecord
        $pumpMeterRecord->forceDelete();

        return response('success');
    }
}
