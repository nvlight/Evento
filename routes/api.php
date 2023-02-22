<?php

use App\Http\Controllers\EventoController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
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

    Route::apiResource('/tag', TagController::class);

    Route::delete('/evento/destroyIds', [EventoController::class, 'destroyIds']);
    Route::post('/evento/copy/{evento}', [EventoController::class, 'copy']);
    Route::get('/evento/filter', [EventoController::class, 'filter']);
    Route::get('/evento/diagram', [EventoController::class, 'diagram']);
    Route::get('/evento/diagram-years', [EventoController::class, 'getDiagramYears']);
    Route::apiResource('/evento', EventoController::class);


    Route::group(
        [
            'prefix' => 'test',
            'name' => 'test.'

        ], function(){

        Route::get('distinct', [TestController::class, 'distinct']);
    });

    Route::post('user/profile/avatar', [UserController::class, 'setAvatar']);
    Route::delete('user/profile/avatar', [UserController::class, 'delAvatar']);

    Route::post('test/distinct2', [TestController::class, 'distinct']);
    Route::post('test/customRule', [TestController::class, 'customRuse']);
});

Route::match(['post'], '/register', [AuthController::class, 'register']);
Route::match(['post'], '/login',    [AuthController::class, 'login']);

Route::get('test/eloquent/base', [TestController::class, 'eloquent_base']);
