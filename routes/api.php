<?php

declare(strict_types=1);

use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Support\Facades\Route;
use Lightit\Users\App\Controllers\DeleteUserController;
use Lightit\Users\App\Controllers\GetUserAppointmentsController;
use Lightit\Users\App\Controllers\GetUserController;
use Lightit\Users\App\Controllers\ListUserController;
use Lightit\Users\App\Controllers\StoreUserController;
use Lightit\Users\App\Controllers\UpdateUserController;
use Lightit\Clinic\App\Controllers\DeleteClinicController;
use Lightit\Clinic\App\Controllers\GetClinicController;
use Lightit\Clinic\App\Controllers\ListClinicController;
use Lightit\Clinic\App\Controllers\StoreClinicController;
use Lightit\Clinic\App\Controllers\UpdateClinicController;
use Lightit\Doctor\App\Controllers\DeleteDoctorController;
use Lightit\Doctor\App\Controllers\GetDoctorAvailabilityController;
use Lightit\Doctor\App\Controllers\GetDoctorController;
use Lightit\Doctor\App\Controllers\ListDoctorController;
use Lightit\Doctor\App\Controllers\StoreDoctorController;
use Lightit\Doctor\App\Controllers\UpdateDoctorController;
use Lightit\Appointment\App\Controllers\DeleteAppointmentController;
use Lightit\Appointment\App\Controllers\GetAppointmentController;
use Lightit\Appointment\App\Controllers\ListAppointmentController;
use Lightit\Appointment\App\Controllers\StoreAppointmentController;
use Lightit\Appointment\App\Controllers\UpdateAppointmentController;
use Lightit\Authentication\App\Controllers\{LoginController, LogoutController, RefreshController};


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::prefix('auth')->group(static function (): void {
    Route::post('login', LoginController::class);
    Route::middleware(['auth:api'])->group(static function (): void {
            Route::post('logout', LogoutController::class);
            Route::post('refresh', RefreshController::class);
        });
    });

/*
|--------------------------------------------------------------------------
| Users Routes
|--------------------------------------------------------------------------
*/
Route::prefix('users')
    ->group(static function (): void {
        Route::get('/', ListUserController::class);
        Route::post('/', StoreUserController::class);
        Route::prefix('{user}')->group(static function (): void {
            Route::get('/', GetUserController::class)->withTrashed();
            Route::put('/', UpdateUserController::class);
            Route::delete('/', DeleteUserController::class);
            Route::get('/appointments', GetUserAppointmentsController::class);
        })->whereNumber('user');
    });

Route::prefix('clinics')
    ->group(static function (): void {
        Route::get('/', ListClinicController::class);
        Route::post('/', StoreClinicController::class);
        Route::prefix('{clinic}')->group(static function (): void {
            Route::get('/', GetClinicController::class)->withTrashed();
            Route::put('/', UpdateClinicController::class);
            Route::delete('/', DeleteClinicController::class);
        })->whereNumber('clinic');
    });

Route::prefix('doctors')
    ->group(static function (): void {
        Route::get('/', ListDoctorController::class);
        Route::post('/', StoreDoctorController::class);
        Route::prefix('{doctor}')->group(static function (): void {
            Route::get('/', GetDoctorController::class)->withTrashed();
            Route::put('/', UpdateDoctorController::class);
            Route::delete('/', DeleteDoctorController::class);
        })->whereNumber('doctor');
    });

Route::prefix('appointments')
    ->middleware('auth:api')
    ->group(static function (): void {
        Route::get('/', ListAppointmentController::class);
        Route::post('/', StoreAppointmentController::class);
        Route::prefix('{appointment}')->group(static function (): void {
            Route::get('/', GetAppointmentController::class)->withTrashed();
            Route::put('/', UpdateAppointmentController::class);
            Route::delete('/', DeleteAppointmentController::class);
        })->whereNumber('appointment');
    });
