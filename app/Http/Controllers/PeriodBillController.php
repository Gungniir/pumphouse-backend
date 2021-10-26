<?php

namespace App\Http\Controllers;

use App\Http\Resources\BillResource;
use App\Models\Bill;
use App\Models\Period;
use App\Models\Resident;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class PeriodBillController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Bill::class, 'bill');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Bill::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Period $period
     * @return BillResource|JsonResponse
     */
    public function store(Request $request, Period $period)
    {
        $request->validate([
            'resident_id' => 'required|integer',
            'amount_rub' => 'required|numeric',
        ]);

        $resident = Resident::find($request->input('resident_id'));

        if (is_null($resident)) {
            return response()->json('Unknown resident', 404);
        }

        $bill = Bill::make(['amount_rub' => $request->input('amount_rub')]);
        $bill->resident_id = $resident->id;

        return new BillResource($period->bills()->save(
            $bill
        ));

    }

    /**
     * Display the specified resource.
     *
     * @param Bill $bill
     * @return BillResource
     */
    public function show(Bill $bill): BillResource
    {
        return new BillResource($bill);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Bill $bill
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(Request $request, Bill $bill): JsonResponse
    {
        $request->validate([
            'amount_rub' => 'required|numeric',
        ]);

        $bill->amount_rub = number_format($request->input('amount_rub'), 2, '.', '');

        $bill->saveOrFail();

        return response()->json('success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Bill $bill
     * @return bool
     */
    public function destroy(Bill $bill): bool
    {
        return $bill->forceDelete();
    }
}
