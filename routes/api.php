<?php

use App\Http\Controllers\EventoController;
use App\Http\Controllers\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::middleware('auth:sanctum')->group(function ()
{
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::match(['post'], '/logout',    [AuthController::class, 'logout']);

    Route::resource('/tag', TagController::class);

    Route::get('/evento/filter', [EventoController::class, 'filter']);
    Route::resource('/evento', EventoController::class);
});

Route::match(['post'], '/register', [AuthController::class, 'register']);
Route::match(['post'], '/login',    [AuthController::class, 'login']);
