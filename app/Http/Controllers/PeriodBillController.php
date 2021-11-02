<?php

namespace App\Http\Controllers;

use App\Http\Resources\BillCollection;
use App\Http\Resources\BillResource;
use App\Models\Bill;
use App\Models\Period;
use App\Models\Resident;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PeriodBillController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws AuthorizationException
     */
    public function index(Period $period)
    {
        $this->authorize('viewAny', Bill::class);

        if ($period->bills()->count() === 0) {
            return response()->json([
                'data' => []
            ]);
        }

        return new BillCollection($period->bills);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Period $period
     * @return BillResource|JsonResponse
     * @throws AuthorizationException
     */
    public function store(Request $request, Period $period)
    {
        $this->authorize('update', Bill::class);

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
     * Remove all bills in period.
     *
     * @param Period $period
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroyAll(Period $period): JsonResponse
    {
        $this->authorize('forceDelete', Bill::class);

        $period->bills()->delete();

        return response()->json('success');
    }
}
