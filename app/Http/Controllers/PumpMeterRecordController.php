<?php

namespace App\Http\Controllers;

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
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Model|PumpMeterRecord
     */
    public function store(Request $request)
    {
        $request->validate([
            'period_id' => 'required|integer',
            'amount_volume' => 'required|numeric',
        ]);

        return PumpMeterRecord::create([
            'period_id' => $request->input('period_id'),
            'amount_volume' => $request->input('amount_volume'),
        ]);
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
