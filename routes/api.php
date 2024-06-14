<?php

use App\Enums\Role\Role;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\DeviceController;
use App\Http\Controllers\Admin\FactoryController;
use App\Http\Controllers\Admin\FactoryFloorController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CalibrationController;
use App\Http\Controllers\Moderator\FactoryDeviceController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('v1')->name('v1.')->group(function () {
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('login', [AuthController::class, 'login'])->name('login');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    });

    Route::middleware('auth:sanctum')
        ->group(function () {
            Route::apiResource('factory', FactoryController::class);

            Route::apiResource('factory-floor', FactoryFloorController::class)->middleware(Role::adminDirector());

            Route::apiResource('user', UserController::class)->middleware(Role::adminDirector());

            Route::apiResource('device', DeviceController::class)->middleware(Role::adminDirectorModerator());

            Route::apiResource('factory-device', FactoryDeviceController::class);

            Route::apiResource('calibration', CalibrationController::class)->middleware(Role::directorModeratorWorker());

            Route::prefix('application')->name('application.')->middleware(Role::tester())->group(function () {
                Route::get('', [ApplicationController::class, 'index'])->name('index');
                Route::patch('accept/{calibration}', [ApplicationController::class, 'accept'])->name('accept');
                Route::patch('approve/{calibration}', [ApplicationController::class, 'approve'])->name('approve');
                Route::patch('reject/{calibration}', [ApplicationController::class, 'reject'])->name('reject');
            });

            Route::get('attribute', [AttributeController::class, 'index'])->name('attribute.index');
        });
});
