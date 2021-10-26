<?php

namespace App\Http\Controllers;

use App\Http\Resources\PeriodCollection;
use App\Http\Resources\PeriodResource;
use App\Models\Bill;
use App\Models\Period;
use App\Models\Resident;
use DateTime;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return PeriodCollection
     */
    public function index(): PeriodCollection
    {
        $this->authorize('viewAny', Period::class);
        return new PeriodCollection(Period::all());
    }

    /**
     * Display the specified resource.
     *
     * @param Period $period
     * @return PeriodResource
     */
    public function show(Period $period): PeriodResource
    {
        $this->authorize('view', $period);
        return new PeriodResource($period);
    }

    public function create(Request $request)
    {
        $this->authorize('create', Period::class);

        $request->validate([
            'year' => ['required', 'integer'],
            'month' => ['required', 'integer']
        ]);

        $period = Period::factory()->fromDate(
            (int)$request->input('year'),
            (int)$request->input('month'),
        )->make();

        // Поиск дубликата
        $duplicate = Period::whereBeginDate($period->begin_date)->whereEndDate($period->end_date)->first();

        if (!is_null($duplicate)) {
            return response("Duplicate found: {$duplicate->id}", 409);
        }

        $period->save();

        return new PeriodResource($period);
    }

    /**
     * Calculate bills for period
     *
     * @param Period $period
     * @return Application|Response|ResponseFactory
     * @throws AuthorizationException
     */
    public function calculate(Period $period)
    {
        $this->authorize('calculate', Period::class);

        if (is_null($period->pumpMeterRecord)) {
            return response('You must put record first', 400); // Bad request
        }

        if (is_null($period->tariff)) {
            return response('You must put tariff first', 400); // Bad request
        }

        if (count($period->bills) > 0) {
            return response('Bills is already generated', 409); // Conflict
        }

        $cost = $period->tariff->cost;

        $beginDate = new DateTime($period->begin_date);
        $endDate = new DateTime($period->end_date);
        $amountVolume = (double)$period->pumpMeterRecord->amount_volume;
        $total = 0.;
        $result = [];

        $residents = Resident::where('start_date', '<', $endDate)->get();

        foreach ($residents as $resident){
            $date = new DateTime($resident->start_date);

            // Если резидент был зарегистрирован раньше начала периода, то тарифицируем по полному тарифу
            if ($date->getTimestamp() <= $beginDate->getTimestamp()) {
                $registeredInSeconds = $endDate->getTimestamp() - $beginDate->getTimestamp();
            } else {
                $registeredInSeconds = $endDate->getTimestamp() - $date->getTimestamp();
            }

            $total += (double)$registeredInSeconds*$resident->area;

            $result[$resident->id] = (double)$registeredInSeconds*$resident->area;
        }

        foreach ($result as $residentID => $piece) {
            Bill::create([
                'resident_id' => $residentID,
                'period_id' => $period->id,
                'amount_rub' => (double)$amountVolume * $cost * $piece / $total ,
            ]);
        }

        return response('Success');
    }
}
