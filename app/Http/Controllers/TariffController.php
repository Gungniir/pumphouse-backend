<?php

namespace App\Http\Controllers;

use App\Http\Resources\TariffResource;
use App\Models\Tariff;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class TariffController extends Controller
{

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Tariff $tariff
     * @return TariffResource
     * @throws AuthorizationException
     * @throws Throwable
     */
    public function update(Request $request, Tariff $tariff): TariffResource
    {
        $this->authorize('update', $tariff);

        $request->validate([
            'cost' => 'required|numeric'
        ]);

        $tariff->cost = number_format((double)$request->input('cost'), 2, '.', '');

        $tariff->saveOrFail();

        return new TariffResource($tariff);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Tariff $tariff
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Tariff $tariff): JsonResponse
    {
        $this->authorize('forceDelete', $tariff);

        $tariff->forceDelete();

        return response()->json("success");
    }
}
