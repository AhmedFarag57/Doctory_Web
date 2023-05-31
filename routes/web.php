<?php

use App\Http\Controllers\api\UserController;
use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\AssignRoleController;
use App\Http\Controllers\DoctorRequestedController;
use App\Http\Controllers\DoctorsController;
use App\Http\Controllers\PatientsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
Route::get('/profile/edit', [HomeController::class, 'profileEdit'])->name('profile.edit');
Route::put('/profile/update', [HomeController::class, 'profileUpdate'])->name('profile.update');
Route::get('/profile/changepassword', [HomeController::class, 'changePassword'])->name('profile.change.password');
Route::post('/profile/changepassword', [HomeController::class, 'updatePassword'])->name('profile.changepassword');


// Routes for Admin
Route::group(['middleware' => ['auth', 'role:Admin']], function(){

    // ....

    // Roles & Permissions
    Route::get('/roles-permissions', [RolePermissionController::class, 'rolesIndex'])->name('roles-permissions');

    // Roles
    Route::get('/roles/create', [RolePermissionController::class, 'rolesCreate'])->name('roles.create');
    Route::post('/roles/store', [RolePermissionController::class, 'rolesStore'])->name('roles.store');
    Route::get('/roles/{id}/edit', [RolePermissionController::class, 'rolesEdit'])->name('roles.edit');
    Route::put('/roles/{id}/update', [RolePermissionController::class, 'rolesUpdate'])->name('roles.update');

    // Permissons
    Route::get('/permissions/create', [RolePermissionController::class, 'permissionsCreate'])->name('permissions.create');
    Route::post('/permissions/store', [RolePermissionController::class, 'permissionsStore'])->name('permissions.store');
    Route::get('/permissions/{id}/edit', [RolePermissionController::class, 'permissionsEdit'])->name('permissions.edit');
    Route::put('/permissions/{id}/update', [RolePermissionController::class, 'permissionsUpdate'])->name('permissions.update');

    // Assign Role
    Route::get('/assign-role', [AssignRoleController::class, 'index'])->name('assign-role.index');
    Route::get('/assign-role/{id}', [AssignRoleController::class, 'show'])->name('assign-role.show')->where('id', '[0-9]+');
    Route::get('/assign-role/create', [AssignRoleController::class, 'create'])->name('assign-role.create');
    Route::post('/assign-role/store', [AssignRoleController::class, 'store'])->name('assign-role.store');
    Route::get('/assign-role/{id}/edit', [AssignRoleController::class, 'edit'])->name('assign-role.edit')->where('id', '[0-9]+');
    Route::put('/assign-role/{id}/update', [AssignRoleController::class, 'update'])->name('assign-role.update')->where('id', '[0-9]+');
    Route::delete('/assign-role/{id}/destroy', [AssignRoleController::class, 'destroy'])->name('assign-role.destroy')->where('id', '[0-9]+');

    // Users
    Route::get('/users/{id}/block', [UserController::class, 'blockUser'])->name('users.block')->where('id', '[0-9]+');

    // Doctors
    Route::get('/doctors', [DoctorsController::class, 'index'])->name('doctors.index');
    Route::get('/doctors/{id}', [DoctorsController::class, 'show'])->name('doctors.show')->where('id', '[0-9]+');
    Route::get('/doctors/create', [DoctorsController::class, 'create'])->name('doctors.create');
    Route::post('/doctors/store', [DoctorsController::class, 'store'])->name('doctors.store');
    Route::get('/doctors/{id}/edit', [DoctorsController::class, 'edit'])->name('doctors.edit')->where('id', '[0-9]+');
    Route::put('/doctors/{id}/update', [DoctorsController::class, 'update'])->name('doctors.update')->where('id', '[0-9]+');
    Route::delete('/doctors/{id}/destroy', [DoctorsController::class, 'destroy'])->name('doctors.destroy')->where('id', '[0-9]+');

    // Doctor Request
    Route::get('/doctors/request', [DoctorRequestedController::class, 'index'])->name('doctors.request.index');
    Route::get('/doctors/request/{id}', [DoctorRequestedController::class, 'show'])->name('doctors.request.show')->where('id', '[0-9]+');
    Route::get('/doctors/request/{id}/accept', [DoctorRequestedController::class, 'acceptRequest'])->name('doctors.request.accept')->where('id', '[0-9]+');

    // Patients
    Route::get('/patients', [PatientsController::class, 'index'])->name('patients.index');
    Route::get('/patients/{id}', [PatientsController::class, 'show'])->name('patients.show')->where('id', '[0-9]+');
    Route::get('/patients/create', [PatientsController::class, 'create'])->name('patients.create');
    Route::post('/patients/store', [PatientsController::class, 'store'])->name('patients.store');
    Route::get('/patients/{id}/edit', [PatientsController::class, 'edit'])->name('patients.edit')->where('id', '[0-9]+');
    Route::put('/patients/{id}/update', [PatientsController::class, 'update'])->name('patients.update')->where('id', '[0-9]+');
    Route::delete('/patients/{id}/destroy', [PatientsController::class, 'destroy'])->name('patients.destroy')->where('id', '[0-9]+');

    // Appointments
    Route::get('/appointments', [AppointmentsController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/{id}', [AppointmentsController::class, 'show'])->name('appointments.show')->where('id', '[0-9]+');
    Route::get('/appointments/create', [AppointmentsController::class, 'create'])->name('appointments.create');
    Route::post('/appointments/store', [AppointmentsController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/{id}/edit', [AppointmentsController::class, 'edit'])->name('appointments.edit')->where('id', '[0-9]+');
    Route::put('/appointments/{id}/update', [AppointmentsController::class, 'update'])->name('appointments.update')->where('id', '[0-9]+');
    Route::delete('/appointments/{id}/destroy', [AppointmentsController::class, 'destroy'])->name('appointments.destroy')->where('id', '[0-9]+');

    // Points
    Route::get('/points', [PointController::class, 'index'])->name('points.request.index');
    Route::get('/points/request/{id}', [PointController::class, 'show'])->name('points.request.show')->where('id', '[0-9]+');
    Route::get('/points/request/{id}/accept', [PointController::class, 'acceptRequest'])->name('points.request.accept')->where('id', '[0-9]+');

});

// Routes for Doctor
Route::group(['middleware' => ['auth', 'role:Doctor']], function(){
    // ...
});

// Routes for Patient
Route::group(['middleware' => ['auth', 'role:Patient']], function(){
    // ...
});
