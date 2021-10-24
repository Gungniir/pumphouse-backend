<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResidentCollection;
use App\Http\Resources\ResidentResource;
use App\Models\Resident;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
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
     * @return ResidentCollection
     */
    public function index(): ResidentCollection
    {
        return new ResidentCollection(Resident::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return ResidentResource
     */
    public function store(Request $request): ResidentResource
    {
        $request->validate([
            'fio' => 'required',
            'area' => 'required|numeric',
            'start_date' => 'required|date_format:Y.m.d H:i:s',
        ]);

        return new ResidentResource(Resident::create([
            'fio' => $request->input('fio'),
            'area' => number_format($request->input('area'), 2),
            'start_date' => $request->input('start_date'),
        ]));
    }

    /**
     * Display the specified resource.
     *
     * @param Resident $resident
     * @return ResidentResource
     */
    public function show(Resident $resident): ResidentResource
    {
        return new ResidentResource($resident);
    }

    /**
     * Display the specified resource.
     */
    public function showMe()
    {
        if (is_null(Auth::user()->resident)) {
            return Response::json('You are not a resident', 404);
        }

        return new ResidentResource(Auth::user()->resident);
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
