<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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

Route::get('/product/{id}', [HomeController::class, 'show']);
Route::post('/product/add', [HomeController::class, 'store']);
Route::post('/product/edit/{id}', [HomeController::class, 'update']);
Route::get('/product/delete/{id}', [HomeController::class, 'destroy']);
