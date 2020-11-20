<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActiveDirectory\ActiveDirectoryController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;

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
Route::prefix('password')->group( function () {
    Route::get('glpi/{user}', [ ForgotPasswordController::class, 'sendResetLinkEmail' ])->name('glpi');
    Route::post('forgot', [ ForgotPasswordController::class, 'sendResetLinkEmail' ])->name('password.forgot');
    Route::post('reset', [ ResetPasswordController::class, 'reset' ])->name('password.reset');
});

Route::middleware('auth:api')->prefix('api')->group( function () {
    Route::get('user', [LoginController::class, 'user'])->name('passport.user');
    Route::post('change-password', [LoginController::class, 'changePassword'])->name('password.change');
    Route::post('logout', [LoginController::class, 'logout'])->name('passport.logout');
    Route::post('logout-all-devices', [LoginController::class, 'logoutAllDevices'])->name('passport.logout.all');
    Route::prefix('admin')->group( function () {
        Route::post('sync-users', [ActiveDirectoryController::class, 'import'])
            ->middleware('can:sync-users')
            ->name('admin.sync.ldap_users');
        Route::post('sync-sim', [ActiveDirectoryController::class, 'sync'])
            ->middleware('can:sync-users')
            ->name('admin.sync.sim_users');
        Route::resource('modules', 'Auth\ModuleController', [
            'except'     =>     ['create', 'edit'],
            'parameters' => [ 'modules' => 'module' ]
        ]);
    });
});