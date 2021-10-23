<?php

use App\Http\Controllers\BillController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\ResidentController;
use Illuminate\Http\Request;
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
    Route::get('/resident/me', [ResidentController::class, 'showMe']);
    Route::apiResource('/resident', ResidentController::class);

    Route::apiResource('/bill', BillController::class);

    Route::apiResource('/period', PeriodController::class)->only(['index', 'show']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::get('/ping', function () {
    return 'Pong!';
});
