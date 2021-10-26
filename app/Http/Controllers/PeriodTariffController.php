<?php

namespace App\Http\Controllers;

use App\Http\Resources\TariffResource;
use App\Models\Period;
use App\Models\Tariff;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PeriodTariffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Period $period
     * @return TariffResource|JsonResponse
     * @throws AuthorizationException
     */
    public function index(Period $period)
    {
        $this->authorize('view', Tariff::class);

        if (is_null($period->tariff)) {
            return response()->json("Not found", 404);
        }

        return new TariffResource($period->tariff);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Period $period
     * @return TariffResource|JsonResponse
     * @throws AuthorizationException
     */
    public function store(Request $request, Period $period)
    {
        $this->authorize('create', Tariff::class);

        $request->validate([
            'cost' => 'required|numeric',
        ]);

        if (!is_null($period->tariff)) {
            return response()->json("Tariff is already set", 409);
        }

        $tariff = new Tariff([
            'cost' => number_format((double)$request->input('cost'), 2, '.', '')
        ]);

        return new TariffResource($period->tariff()->save($tariff));
    }
}
