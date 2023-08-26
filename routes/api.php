<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Dashboard\v1\DashboardController;
use App\Http\Controllers\Api\Note\v1\NoteController;
use App\Http\Controllers\Api\Notification\v1\ClientNotificationController;
use App\Http\Controllers\Api\Notification\v1\NotificationController;
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

// Auth routes
Route::controller(AuthController::class)->prefix('users/v1')->group(function () {
    Route::post('/create', 'register');
    Route::post('auth/login', 'login');
    Route::post('auth/admins/login', 'login')->middleware('role.check');
    Route::post('auth/refresh', 'refresh');
    Route::post('auth/logout', 'logout');
});

// Notes routes
Route::controller(NoteController::class)->prefix('notes/v1')->group(function () {
    Route::get('/public-notes', 'index');
    Route::get('/user-notes', 'userNotes');
    Route::post('/create', 'store');
    Route::get('/show/{id}', 'show');
    Route::post('/update/{id}', 'update');
    Route::delete('/delete/{id}', 'destroy');
});

// Dashboard routes
Route::controller(DashboardController::class)
    ->prefix('dashboard/v1')
    ->middleware(['role.check'])->group(function () {
        Route::get('/clients/index', 'allClients');
        Route::get('/clients/notes/index', 'allNotes');
    });

// Notifications routes.
Route::controller(NotificationController::class)
    ->prefix('notifications/public/v1')
    ->middleware(['role.check'])->group(
        function () {
            Route::get('/index', 'index');
            Route::post('/create', 'store');
            Route::delete('/delete/{id}', 'destroy');
        }
    );

// Client notifications routes.
Route::controller(ClientNotificationController::class)
    ->prefix('notifications/private/v1')->group(
        function () {
            Route::get('/index', 'index');
            Route::delete('/delete/{id}', 'destroy');
        }
    );
