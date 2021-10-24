<?php

namespace App\Http\Controllers;

use App\Models\Period;
use App\Models\PumpMeterRecord;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Throwable;

class PumpMeterRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Collection|PumpMeterRecord[]
     */
    public function index()
    {
        return PumpMeterRecord::all();
    }

    /**
     * Display the specified resource.
     *
     * @param PumpMeterRecord $pumpMeterRecord
     * @return PumpMeterRecord
     */
    public function show(PumpMeterRecord $pumpMeterRecord): PumpMeterRecord
    {
        return $pumpMeterRecord;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param PumpMeterRecord $pumpMeterRecord
     * @return string
     * @throws Throwable
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
