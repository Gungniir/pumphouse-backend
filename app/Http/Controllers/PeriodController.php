<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Database\Eloquent\Collection;

class PeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Collection|Period[]
     */
    public function index()
    {
        return Period::all();
    }

    /**
     * Display the specified resource.
     *
     * @param Period $period
     * @return Period
     */
    public function show(Period $period): Period
    {
        return $period;
    }
}
