<?php

use App\Http\Controllers\Api\Auth\AuthController;
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
