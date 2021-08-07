<?php

use App\Http\Controllers\ApiController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('dashboard', [ApiController::class, 'dashboardApi']);
Route::get('location', [ApiController::class, 'locationApi']);
Route::get('history', [ApiController::class, 'historyApi']);
Route::get('stats', [ApiController::class, 'statsApi']);
