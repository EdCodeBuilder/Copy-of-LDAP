<?php

use App\Http\Controllers\Auth\ExportController;
use App\Http\Controllers\Auth\JobStatusController;
use App\Http\Controllers\Auth\NotificationController;
use App\Http\Controllers\Auth\UserModuleController;
use App\Http\Controllers\GlobalData\AnimationsController;
use App\Http\Controllers\GlobalData\AreaController;
use App\Http\Controllers\GlobalData\BloodTypeController;
use App\Http\Controllers\GlobalData\ContributionController;
use App\Http\Controllers\GlobalData\CountryStateCityController;
use App\Http\Controllers\GlobalData\DisabilitiesController;
use App\Http\Controllers\GlobalData\DocumentTypeController;
use App\Http\Controllers\GlobalData\EthnicGroupController;
use App\Http\Controllers\GlobalData\GenderIdentityController;
use App\Http\Controllers\GlobalData\PopulationGroupController;
use App\Http\Controllers\GlobalData\SexController;
use App\Http\Controllers\GlobalData\SexualOrientationController;
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
    Route::post('forgot', [ ForgotPasswordController::class, 'sendResetLinkEmail' ])->name('password.forgot');
    Route::post('reset', [ ResetPasswordController::class, 'reset' ])->name('password.reset');
});

Route::prefix('api')->group(function () {
    Route::get('animations', [AnimationsController::class, 'index']);
    Route::get('animations/{file}', [AnimationsController::class, 'show'])->name('animations.show');

    Route::get('document-types', [DocumentTypeController::class, 'index']);
    Route::get('countries', [CountryStateCityController::class, 'countries']);
    Route::get('countries/{country}/states', [CountryStateCityController::class, 'states']);
    Route::get('countries/{country}/states/{state}/cities', [CountryStateCityController::class, 'cities']);

    Route::get('offices', [AreaController::class, 'office']);
    Route::get('offices/{office}/areas', [AreaController::class, 'areas']);

    Route::get('sex', [SexController::class, 'index']);
    Route::get('gender-identities', [GenderIdentityController::class, 'index']);
    Route::get('sexual-orientation', [SexualOrientationController::class, 'index']);
    Route::get('ethnic-groups', [EthnicGroupController::class, 'index']);
    Route::get('population-groups', [PopulationGroupController::class, 'index']);
    Route::get('blood-types', [BloodTypeController::class, 'index']);
    Route::get('disabilities', [DisabilitiesController::class, 'index']);

    Route::get('arl', [ContributionController::class, 'arl']);
    Route::get('eps', [ContributionController::class, 'eps']);
    Route::get('afp', [ContributionController::class, 'afp']);
    Route::get('ccf', [ContributionController::class, 'ccf']);
    Route::get('parafiscal', [ContributionController::class, 'parafiscal']);
});

Route::middleware('auth:api')->prefix('api')->group( function () {
    Route::post('animations', [AnimationsController::class, 'store']);
    Route::get('user', [LoginController::class, 'user'])->name('passport.user');
    Route::get('job-status', [JobStatusController::class, 'index'])->name('jobs.status.index');
    Route::get('job-status/{job}', [JobStatusController::class, 'show'])->name('jobs.status.show');
    Route::get('exports/{file}', [ExportController::class, 'index'])->name('exports.queued');
    Route::get('notifications', [NotificationController::class, 'index']);
    Route::post('notifications', [NotificationController::class, 'markAllAsRead']);
    Route::get('notifications/{id}', [NotificationController::class, 'markAsRead']);
    Route::delete('notifications/{id}', [NotificationController::class, 'destroy']);
    Route::get('my-modules', [UserModuleController::class, 'index'])->name('passport.modules');
    Route::post('change-password', [LoginController::class, 'changePassword'])->name('password.change');
    Route::post('logout', [LoginController::class, 'logout'])->name('passport.logout');
    Route::post('logout-all-devices', [LoginController::class, 'logoutAllDevices'])->name('passport.logout.all');
    Route::prefix('admin')->group( function () {
        Route::post('enable-users', [ActiveDirectoryController::class, 'enableLDAPUser'])
            ->middleware('can:enable-users')
            ->name('admin.enable.ldap_users');
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
