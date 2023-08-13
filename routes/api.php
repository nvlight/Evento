<?php

use App\Http\Controllers\EventoController;
use App\Http\Controllers\Onepass\CategoryController;
use App\Http\Controllers\Onepass\EntryController;
use App\Http\Controllers\OpenaiController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Storage;

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

Route::middleware(['auth:sanctum'])->group(function ()
{
    Route::get('/user', function (Request $request) {
        $user = $request->user();
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)){
            $fullPath = Storage::disk('public')->url($user->avatar);
            $user->full_avatar = $fullPath;
        }
        return $user;
    });
    Route::match(['post'], '/logout', [AuthController::class, 'logout']);

    Route::apiResource('/tag', TagController::class);

    Route::delete('/evento/destroyIds', [EventoController::class, 'destroyIds']);
    Route::post('/evento/copy/{evento}', [EventoController::class, 'copy']);
    Route::get('/evento/filter', [EventoController::class, 'filter']);
    Route::get('/evento/diagram', [EventoController::class, 'diagram']);
    Route::get('/evento/diagram-years', [EventoController::class, 'getDiagramYears']);
    Route::apiResource('/evento', EventoController::class);

    Route::post('user/profile/avatar', [UserController::class, 'setAvatar']);
    Route::delete('user/profile/avatar', [UserController::class, 'delAvatar']);

    Route::patch('/onepass/category/image_validator/{category}', [CategoryController::class, 'image_validator']);
    Route::apiResource('/onepass/category', CategoryController::class);
    Route::post('/onepass/entry/copy/{entry}', [EntryController::class, 'copy']);
    Route::get('/onepass/entry/filter', [EntryController::class, 'filter']);
    Route::apiResource('/onepass/entry', EntryController::class);

    // test api section
    Route::group(
        [
            'prefix' => 'test',
            'name' => 'test.'

        ], function(){

        Route::get('distinct', [TestController::class, 'distinct']);
    });

    Route::post('test/distinct2', [TestController::class, 'distinct']);
    Route::post('test/customRule', [TestController::class, 'customRule']);
});

Route::match(['post'], '/register', [AuthController::class, 'register']);
Route::match(['post'], '/login',    [AuthController::class, 'login']);

Route::get('test/eloquent/base', [TestController::class, 'eloquent_base']);
Route::get('test/openai/foobar', [OpenaiController::class, 'foobar']);
Route::get('test', function (){
    return response()->json(['message' => 'yes, off course']);
});

