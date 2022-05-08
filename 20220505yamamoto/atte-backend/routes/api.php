<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\RestController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return Auth::user();
});

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout']);

Route::group(['middleware'=> ['auth:sanctum']], function () {
    Route::get('/work/index/{date}', [WorkController::class, 'index']);
    Route::post('/work/start', [WorkController::class, 'start']);
    Route::post('/work/end', [WorkController::class, 'end']);
    Route::post('/rest/start', [RestController::class, 'start']);
    Route::post('/rest/end', [RestController::class, 'end']);
});
