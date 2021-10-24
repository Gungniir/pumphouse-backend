<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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
     * @return Model|Bill
     */
    public function store(Request $request)
    {
        $request->validate([
            'resident_id' => 'required|integer',
            'period_id' => 'required|integer',
            'amount_rub' => 'required|numeric',
        ]);

        return Bill::create([
            'resident_id' => $request->input('resident_id'),
            'period_id' => $request->input('period_id'),
            'amount_rub' => $request->input('amount_rub'),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Bill $bill
     * @return Bill
     */
    public function show(Bill $bill): Bill
    {
        return $bill;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Bill $bill
     * @return bool
     */
    public function update(Request $request, Bill $bill): bool
    {
        $request->validate([
            'resident_id' => 'required|integer',
            'period_id' => 'required|integer',
            'amount_rub' => 'required|numeric',
        ]);

        $bill->resident_id = $request->input('resident_id');
        $bill->period_id = $request->input('period_id');
        $bill->amount_rub = $request->input('amount_rub');

        $bill->saveOrFail();

        return 'Successful';
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
