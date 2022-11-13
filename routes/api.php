<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\ChatController;
use App\Http\Controllers\api\ChatMessageController;
use App\Http\Controllers\api\SessionController;
use App\Http\Controllers\api\UserController;
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

/**
 * Test API
 */
Route::get('/test', function(){
    return 'Hello world!';
});


/**
 * Public API
 */
Route::get('/sessions', [SessionController::class, 'index'])->name('sessions.index');
Route::get('/sessions/{id}', [SessionController::class, 'show'])->name('sessions.show');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login_with_token', [AuthController::class, 'loginWithToken'])->name('loginWithToken');



/**
 * Private API
 */
Route::group(['middleware' => ['auth:sanctum']], function(){
        
    Route::post('/sessions', [SessionController::class, 'store'])->name('sessions.store');
    Route::put('/sessions/{id}', [SessionController::class, 'update'])->name('sessions.update');
    Route::delete('/sessions/{id}', [SessionController::class, 'destroy'])->name('sessions.destroy');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // Chat
    Route::apiResource('chat', ChatController::class)->only(['index', 'store', 'show']);
    Route::apiResource('chat_message', ChatMessageController::class)->only(['index', 'store']);
    Route::apiResource('user', UserController::class)->only(['index']);

});
