<?php

use App\Enums\Role\Role;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\DeviceController;
use App\Http\Controllers\Admin\FactoryController;
use App\Http\Controllers\Admin\FactoryFloorController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Auth\AuthController;
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

            Route::apiResource('device', DeviceController::class)->middleware(Role::adminModerator());

            Route::apiResource('factory-device', FactoryDeviceController::class);

            Route::apiResource('application', ApplicationController::class)->middleware(Role::directorModeratorWorker());

            Route::get('attribute', [AttributeController::class, 'index'])->name('attribute.index');
        });
});
