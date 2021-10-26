<?php

namespace App\Http\Controllers;

use App\Http\Resources\PumpMeterRecordResource;
use App\Models\Period;
use App\Models\PumpMeterRecord;
use Illuminate\Auth\Access\AuthorizationException;
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
     * @throws AuthorizationException
     */
    public function index(Period $period): PumpMeterRecordResource
    {
        $this->authorize('viewAny', PumpMeterRecord::class);
        return new PumpMeterRecordResource($period->pumpMeterRecord);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Period $period
     * @return Application|ResponseFactory|Response|PumpMeterRecordResource
     * @throws AuthorizationException
     */
    public function store(Request $request, Period $period)
    {
        $this->authorize('create', PumpMeterRecord::class);
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
     * @noinspection PhpUnusedParameterInspection
     */
    public function update(Request $request, Period $period, PumpMeterRecord $pumpMeterRecord)
    {
        $this->authorize('update', $pumpMeterRecord);
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
     * @param Period $period
     * @param PumpMeterRecord $pumpMeterRecord
     * @return string
     * @throws AuthorizationException
     * @noinspection PhpUnusedParameterInspection
     */
    public function destroy(Period $period, PumpMeterRecord $pumpMeterRecord): string
    {
        $this->authorize('forceDelete', $pumpMeterRecord);
        // Если убрать $period из параметров, то laravel не будет искать $pumpMeterRecord
        $pumpMeterRecord->forceDelete();

        return response('success');
    }
}
