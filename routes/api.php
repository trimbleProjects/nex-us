<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\v1\RatesController;

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
    return $request->user();
});


Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\API\v1', 'middleware' => ['auth:sanctum']], function(){

    Route::get('rate', [RatesController::class, 'BasicRate']);
    // Route::get('ratetracker', [RatesController::class, 'RateTracker']);

});
Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\API\v1',  'middleware' => ['auth:sanctum']], function(){
    Route::get('ratetracker', [RatesController::class, 'RateTracker']);
});