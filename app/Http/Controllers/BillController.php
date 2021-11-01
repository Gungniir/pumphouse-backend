<?php

namespace App\Http\Controllers;

use App\Http\Resources\BillResource;
use App\Models\Bill;
use App\Models\Period;
use App\Models\Resident;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('viewAny', Bill::class);
        return Bill::all();
    }

    /**
     * Display the specified resource.
     *
     * @param Bill $bill
     * @return BillResource
     * @throws AuthorizationException
     */
    public function show(Bill $bill): BillResource
    {
        $this->authorize('view', $bill);
        return new BillResource($bill);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Bill $bill
     * @return BillResource
     * @throws Throwable
     */
    public function update(Request $request, Bill $bill): BillResource
    {
        $this->authorize('update', $bill);

        $request->validate([
            'amount_rub' => 'required|numeric',
        ]);

        $bill->amount_rub = number_format($request->input('amount_rub'), 2, '.', '');

        $bill->saveOrFail();

        return new BillResource($bill);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Bill $bill
     * @return bool
     * @throws AuthorizationException
     */
    public function destroy(Bill $bill): bool
    {
        $this->authorize('forceDelete', $bill);

        return $bill->forceDelete();
    }
}
