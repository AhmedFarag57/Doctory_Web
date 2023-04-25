<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\ChatController;
use App\Http\Controllers\api\ChatMessageController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\DoctorController;
use App\Http\Controllers\api\AppointmentController;
use App\Http\Controllers\api\PatientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
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


/**
 * Route for broadcasting
 */
Broadcast::routes(['middleware' => ['auth:sanctum']]);

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
Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/login_with_token', [AuthController::class, 'loginWithToken'])->name('api.loginWithToken');
// Register for Doctor
Route::post('/doctors', [DoctorController::class, 'store'])->name('api.doctors.store');
// Register for Patient
Route::post('/patients', [PatientController::class, 'store'])->name('api.patients.store');

/**
 * Routes for Admin
 */
Route::group(['middleware' => ['auth:sanctum', 'role:Admin']], function(){
    // ..
});

/**
 * Routes for Doctor
 */
Route::group(['middleware' => ['auth:sanctum', 'role:Doctor']], function(){
    // ..
    Route::post('/doctors/{id}/times', [DoctorController::class, 'doctorTimeStore']);
});

/**
 * Routes for Patient
 */
Route::group(['middleware' => ['auth:sanctum', 'role:Patient']], function(){
    // ..

    Route::get('/patients/{id}/appointments', [AppointmentController::class, 'patientAppointment']);
});

/**
 * Private API
 */
Route::group(['middleware' => ['auth:sanctum']], function(){
    
    // Logout Route
    Route::get('/logout', [AuthController::class, 'logout'])->name('api.logout');

    /**
     *  GET     : /appointments
     *  POST    : /appointments
     *  PUT     : /appointments/{id}
     *  DELETE  : /appointments/{id}
     */
    Route::resource('/appointments', AppointmentController::class);
    
    /**
     *  GET     : /patients
     *  POST    : /patients
     *  PUT     : /patients/{id}
     *  DELETE  : /patients/{id}
     */
    Route::resource('/patients', PatientController::class);

    // Chat
    Route::apiResource('chat', ChatController::class)->only(['index', 'store', 'show']);
    Route::apiResource('chat_message', ChatMessageController::class)->only(['index', 'store']);
    Route::apiResource('user', UserController::class)->only(['index']);

    Route::get('/appointments/chat/messages', [ChatMessageController::class, 'getMessages']);

    // Doctor
    Route::get('/doctors/{id}/times', [DoctorController::class, 'doctortime']);
    Route::post('/doctors/{id}/times', [DoctorController::class, 'doctortimestore']);
    Route::get('/doctors', [DoctorController::class, 'index'])->name('api.doctors.index');

    Route::get('/doctors', [DoctorController::class, 'index'])->name('api.doctors.index');
    Route::get('/doctors/{id}', [DoctorController::class, 'Show'])->name('api.doctors.show');
    Route::put('/doctors/{id}', [DoctorController::class, 'update'])->name('api.doctors.update');
    Route::delete('/doctors/{id}', [DoctorController::class, 'destroy'])->name('api.doctors.destroy');
    Route::get('/doctors/search/{name}', [DoctorController::class, 'search'])->name('api.doctors.search');

    Route::get('/doctors/{id}/appointments/count', [DoctorController::class, 'appointmentsCount']);
    Route::get('/doctors/{id}/appointments', [AppointmentController::class, 'doctorAppointments']);
    Route::get('/doctors/{id}/appointments/request', [AppointmentController::class, 'appointmentsRequest']);
    Route::get('/doctors/{id}/appointments/today', [AppointmentController::class, 'todayAppointments']);
    Route::post('/doctors/appointments/request', [AppointmentController::class, 'appointmentsAction']);
    Route::get('/test/{id}', [AppointmentController::class, 'labtest']);

});
