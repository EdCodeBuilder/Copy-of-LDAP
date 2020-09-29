<?php

use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API global routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [ LoginController::class, 'login' ])->name('passport.login');

Route::middleware('auth:api')->prefix('api')->group( function () {
    Route::get('user', [LoginController::class, 'user'])->name('passport.user');
    Route::post('change-password', [LoginController::class, 'changePassword'])->name('ldap.change.password');
    Route::post('logout', [LoginController::class, 'logout'])->name('passport.logout');
    Route::post('logout-all-devices', [LoginController::class, 'logoutAllDevices'])->name('passport.logout.all');
});