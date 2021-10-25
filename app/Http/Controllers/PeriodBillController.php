<?php

namespace App\Http\Controllers;

use App\Http\Resources\BillResource;
use App\Models\Bill;
use App\Models\Period;
use App\Models\Resident;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
     * @return Application|BillResource|Response|ResponseFactory
     */
    public function store(Request $request, Period $period)
    {
        $request->validate([
            'resident_id' => 'required|integer',
            'amount_rub' => 'required|numeric',
        ]);

        $resident = Resident::find($request->input('resident_id'));

        if (is_null($resident)) {
            return response('Unknown resident', 404);
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
     * @return Application|ResponseFactory|Response
     */
    public function update(Request $request, Bill $bill)
    {
        $request->validate([
            'amount_rub' => 'required|numeric',
        ]);

        $bill->amount_rub = number_format($request->input('amount_rub'), 2, '.', '');

        $bill->saveOrFail();

        return response('success');
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
