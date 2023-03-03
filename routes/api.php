<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\ChatController;
use App\Http\Controllers\api\ChatMessageController;
use App\Http\Controllers\api\SessionController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\DoctorController;
use App\Http\Controllers\api\AppointmentController;
use App\Http\Controllers\api\PatientController;
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
 * Test Public API
 */
Route::get('/public_test', function(){
    return 'Hello world from public API';
});
/**
 * Test Private API
 */
Route::get('/private_test', function(){
    return 'Hello world from private API';
})->middleware('auth:sanctum');



/**
 * Public API
 */
Route::get('/sessions', [SessionController::class, 'index'])->name('api.sessions.index');
Route::get('/sessions/{id}', [SessionController::class, 'show'])->name('api.sessions.show');
Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/login_with_token', [AuthController::class, 'loginWithToken'])->name('api.loginWithToken');



/**
 * Private API
 */
Route::group(['middleware' => ['auth:sanctum']], function(){

    /**
     *  GET     : /appointments
     *  POST    : /appointments
     *  PUT     : /appointments/{id}
     *  DELETE  : /appointments/{id}
     */
    Route::resource('/appointments', AppointmentController::class);
    Route::get('/patients/appointments/{id}', [AppointmentController::class, 'patientAppointment']);

    /**
     *  GET     : /patients
     *  POST    : /patients
     *  PUT     : /patients/{id}
     *  DELETE  : /patients/{id}
     */
    Route::resource('/patients', PatientController::class);

    Route::get('/logout', [AuthController::class, 'logout'])->name('api.logout');

    // Chat
    Route::apiResource('chat', ChatController::class)->only(['index', 'store', 'show']);
    Route::apiResource('chat_message', ChatMessageController::class)->only(['index', 'store']);
    Route::apiResource('user', UserController::class)->only(['index']);

    // Doctor

    Route::get('/doctors', [DoctorController::class, 'index'])->name('api.doctors.index');
    Route::post('/doctors', [DoctorController::class, 'store'])->name('api.doctors.store');
    Route::get('/doctors/{id}', [DoctorController::class, 'Show'])->name('api.doctors.show');
    Route::put('/doctors/{id}', [DoctorController::class, 'update'])->name('api.doctors.update');
    Route::delete('/doctors/{id}', [DoctorController::class, 'destroy'])->name('api.doctors.destroy');
    Route::get('/doctors/search/{name}', [DoctorController::class, 'search'])->name('api.doctors.search');
});
