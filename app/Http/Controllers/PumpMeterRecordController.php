<?php

namespace App\Http\Controllers;

use App\Http\Resources\PumpMeterRecordCollection;
use App\Http\Resources\PumpMeterRecordResource;
use App\Models\PumpMeterRecord;
use Illuminate\Auth\Access\AuthorizationException;

class PumpMeterRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return PumpMeterRecordCollection
     * @throws AuthorizationException
     */
    public function index(): PumpMeterRecordCollection
    {
        $this->authorize('viewAny', PumpMeterRecord::class);
        return new PumpMeterRecordCollection(PumpMeterRecord::all());
    }

    /**
     * Display the specified resource.
     *
     * @param PumpMeterRecord $pumpMeterRecord
     * @return PumpMeterRecordResource
     * @throws AuthorizationException
     */
    public function show(PumpMeterRecord $pumpMeterRecord): PumpMeterRecordResource
    {
        $this->authorize('view', $pumpMeterRecord);
        return new PumpMeterRecordResource($pumpMeterRecord);
    }
}
