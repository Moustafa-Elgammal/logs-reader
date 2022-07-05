<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


use App\Http\Controllers\ApiFileParserController;

Route::get('file',[ApiFileParserController::class, 'read']);
Route::get('head',[ApiFileParserController::class, 'read']);
Route::get('tail',[ApiFileParserController::class, 'tail']);
Route::get('next',[ApiFileParserController::class, 'next']);
Route::get('previous',[ApiFileParserController::class, 'previous']);

