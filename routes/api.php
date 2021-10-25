<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PeriodBillController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\PeriodPumpMeterRecordController;
use App\Http\Controllers\PumpMeterRecordController;
use App\Http\Controllers\ResidentController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/auth', [LoginController::class, 'authenticate']);
Route::delete('/auth', [LoginController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/residents/me', [ResidentController::class, 'showMe']);
    Route::apiResource('/residents', ResidentController::class);

    Route::get('bills', [PeriodBillController::class, 'index']);
    Route::apiResource('periods.bills', PeriodBillController::class)->shallow();

    Route::apiResource('periods.pump-meter-records', PeriodPumpMeterRecordController::class)->except('show');
    Route::apiResource('pump-meter-records', PumpMeterRecordController::class)->only(['index', 'show', 'update', 'delete']);

    Route::post('/period/{period}/calculate', [PeriodController::class, 'calculate']);
    Route::apiResource('/periods', PeriodController::class)->only(['index', 'show']);
});

Route::get('/ping', function () {
    return 'Pong!';
});
