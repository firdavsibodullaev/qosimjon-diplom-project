<?php

use App\Enums\Role\Role;
use App\Http\Controllers\Admin\FactoryController;
use App\Http\Controllers\Admin\FactoryFloorController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
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

    Route::prefix('admin')->name('admin.')
        ->middleware(['auth:sanctum', Role::admin()])
        ->group(function () {
            Route::apiResource('factory', FactoryController::class);
            Route::apiResource('factory-floor', FactoryFloorController::class);
            Route::apiResource('user', UserController::class);

            Route::get('role', RoleController::class)->name('role.index');
        });
});
