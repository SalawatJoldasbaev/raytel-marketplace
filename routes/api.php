<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;

Route::post('/signIn/admin', [AuthController::class, 'login']);
Route::post('/signIn/mobile/device', [AuthController::class, 'mobileDevice']);

Route::post('/signIn/mobile/phone', [AuthController::class, 'mobilePhone']);
Route::post('/signUp/mobile/phone', [AuthController::class, 'signUpMobile']);

Route::middleware(['auth:sanctum', 'ability:admin,mobile'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/files', [FileController::class, 'upload']);
    Route::get('/files/{file_name}', [FileController::class, 'getFile']);
    Route::get('/stores', [StoreController::class, 'index']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/random', [ProductController::class, 'RandomProduct']);
    Route::post('/products/view/{product}', [ProductController::class, 'viewProduct']);
    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'show']);
    Route::post('/newPayment', [\App\Http\Controllers\NewPaymentController::class, 'newPayment']);
});

Route::middleware(['auth:sanctum', 'abilities:admin'])->group(function () {

    Route::prefix('/employees')
        ->controller(EmployeeController::class)
        ->group(function () {
            Route::post('/', 'createEmployee');
            Route::get('/', 'index');
            Route::patch('/', 'update');
            Route::delete('/{employee}', 'destroy');
        });

    Route::prefix('/stores')
        ->controller(StoreController::class)
        ->group(function () {
            Route::post('/', 'store');
            Route::patch('/', 'update');
            Route::delete('/clear/{store}', 'clear');
        });

    Route::prefix('/products')
        ->controller(ProductController::class)
        ->group(function () {
            Route::post('/', 'store');
            Route::patch('/', 'update');
            Route::delete('/{product}', 'destroy');
        });

    Route::prefix('/reports')
        ->controller(ReportController::class)
        ->group(function () {
            Route::get('/', 'index');
            Route::post('/approveReport', 'approveReport');
            Route::post('/declinedReport', 'declinedReport');
        });

    Route::prefix('/users')
        ->controller(UserController::class)
        ->group(function () {
            Route::get('/', 'index');
        });
    Route::patch('/settings', [\App\Http\Controllers\SettingsController::class, 'update']);
});
