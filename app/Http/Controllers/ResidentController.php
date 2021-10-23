<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class ResidentController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Resident::class, 'resident');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Collection|Resident[]
     */
    public function index()
    {
        return Resident::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Model|Resident
     */
    public function store(Request $request)
    {
        $request->validate([
            'fio' => 'required',
            'area' => 'required|numeric',
            'start_date' => 'required|date_format:Y.m.d H:i:s',
        ]);

        return Resident::create([
            'fio' => $request->input('fio'),
            'area' => $request->input('area'),
            'start_date' => $request->input('start_date'),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Resident $resident
     * @return Resident
     */
    public function show(Resident $resident): Resident
    {
        return $resident;
    }

    /**
     * Display the specified resource.
     *
     * @return Resident|mixed|null
     */
    public function showMe()
    {
        if (is_null(Auth::user()->resident)) {
            return \response('You are not a resident', 404);
        }

        return Auth::user()->resident;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Resident $resident
     * @return bool
     * @throws Throwable
     */
    public function update(Request $request, Resident $resident): bool
    {
        $request->validate([
            'fio' => 'required',
            'area' => 'required|numeric',
            'start_date' => 'required|date_format:Y.m.d H:i:s',
        ]);

        $resident->fio = $request->input('fio');
        $resident->area = $request->input('area');
        $resident->start_date = $request->input('start_date');

        return $resident->saveOrFail();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Resident $resident
     * @return bool
     */
    public function destroy(Resident $resident): bool
    {
        return $resident->forceDelete();
    }
}
