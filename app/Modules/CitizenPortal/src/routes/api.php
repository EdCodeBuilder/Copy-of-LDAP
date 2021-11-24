<?php

use App\Modules\CitizenPortal\src\Controllers\ActivityController;
use App\Modules\CitizenPortal\src\Controllers\AgeGroupController;
use App\Modules\CitizenPortal\src\Controllers\AuditController;
use App\Modules\CitizenPortal\src\Controllers\DashboardController;
use App\Modules\CitizenPortal\src\Controllers\FileController;
use App\Modules\CitizenPortal\src\Controllers\FileTypeController;
use App\Modules\CitizenPortal\src\Controllers\HourController;
use App\Modules\CitizenPortal\src\Controllers\ObservationController;
use App\Modules\CitizenPortal\src\Controllers\ProfileController;
use App\Modules\CitizenPortal\src\Controllers\ProfileScheduleController;
use App\Modules\CitizenPortal\src\Controllers\ProfileTypeController;
use App\Modules\CitizenPortal\src\Controllers\ProgramController;
use App\Modules\CitizenPortal\src\Controllers\RoleController;
use App\Modules\CitizenPortal\src\Controllers\PermissionController;
use App\Modules\CitizenPortal\src\Controllers\RolePermissionsController;
use App\Modules\CitizenPortal\src\Controllers\ScheduleController;
use App\Modules\CitizenPortal\src\Controllers\StageController;
use App\Modules\CitizenPortal\src\Controllers\StatusController;
use App\Modules\CitizenPortal\src\Controllers\UserController;
use App\Modules\CitizenPortal\src\Controllers\WeekDayController;
use Illuminate\Support\Facades\Route;

Route::prefix('citizen-portal')->group(function () {
    Route::get('public-schedules', [ScheduleController::class, 'publicApi']);
    Route::post('login', [UserController::class, 'login']);
    Route::middleware('auth:api')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index']);
        Route::put('profiles/{profile}/validator', [ProfileController::class, 'validator']);
        Route::put('profiles/{profile}/status', [ProfileController::class, 'status']);
        Route::get('profiles/excel', [ProfileController::class, 'excel']);
        Route::get('profiles/{profile}/schedules', [ProfileScheduleController::class, 'activities']);
        Route::resource('schedules.profiles', ProfileScheduleController::class, [
            'only'     =>  ['index', 'store', 'update', 'show'],
            'parameters' =>  ['schedules' => 'schedule', 'profiles' => 'profile']
        ]);
        Route::resource('profiles.observations', ObservationController::class, [
            'only'     =>  ['index', 'show'],
            'parameters' =>  ['profiles' => 'profile', 'observations' => 'observation']
        ]);
        Route::resource('profiles.files', FileController::class, [
            'only'     =>  ['index', 'show', 'update', 'destroy'],
            'parameters' =>  ['profiles' => 'profile', 'files' => 'file']
        ]);
        Route::resource('profiles', ProfileController::class, [
            'only'     =>  ['index', 'show'],
            'parameters' =>  ['profiles' => 'profile']
        ]);
        Route::resource('status', StatusController::class, [
            'only'     =>  ['index', 'store', 'update', 'destroy'],
            'parameters' =>  ['status' => 'status']
        ]);
        Route::resource('stages', StageController::class, [
            'only'     =>  ['index', 'store', 'update', 'destroy'],
            'parameters' =>  ['stages' => 'stage']
        ]);
        Route::resource('programs', ProgramController::class, [
            'only'     =>  ['index', 'store', 'update', 'destroy'],
            'parameters' =>  ['programs' => 'program']
        ]);
        Route::resource('age-groups', AgeGroupController::class, [
            'only'     =>  ['index', 'store', 'update', 'destroy'],
            'parameters' =>  ['age-groups' => 'group']
        ]);
        Route::resource('activities', ActivityController::class, [
            'only'     =>  ['index', 'store', 'update', 'destroy'],
            'parameters' =>  ['activities' => 'activity']
        ]);
        Route::resource('week-days', WeekDayController::class, [
            'only'     =>  ['index', 'store', 'update', 'destroy'],
            'parameters' =>  ['week-days' => 'day']
        ]);
        Route::resource('daily-hours', HourController::class, [
            'only'     =>  ['index', 'store', 'update', 'destroy'],
            'parameters' =>  ['daily-hours' => 'hour']
        ]);
        Route::resource('file-types', FileTypeController::class, [
            'only'     =>  ['index', 'store', 'update', 'destroy'],
            'parameters' =>  ['file-types' => 'type']
        ]);
        Route::resource('profile-types', ProfileTypeController::class, [
            'only'     =>  ['index', 'store', 'update', 'destroy'],
            'parameters' =>  ['profile-types' => 'type']
        ]);
        Route::get('schedules/template', [ScheduleController::class, 'template']);
        Route::post('schedules/import', [ScheduleController::class, 'import']);
        Route::resource('schedules', ScheduleController::class, [
            'only'     =>  ['index', 'store', 'show', 'update', 'destroy'],
            'parameters' =>  ['schedules' => 'schedule']
        ]);
        Route::prefix('admin')->group( function () {
            Route::get('models', [PermissionController::class, 'models']);
            Route::resource('roles', RoleController::class, [
                'only'  => ['index', 'store', 'update', 'destroy'],
                'parameters' => [ 'roles' => 'role' ]
            ]);
            Route::resource('permissions', PermissionController::class, [
                'only'  => ['index', 'store', 'update', 'destroy'],
                'parameters' => [ 'permissions' => 'permission' ]
            ]);
            Route::resource('roles.permissions', RolePermissionsController::class, [
                'only'  => ['index', 'update', 'destroy'],
                'parameters' => [ 'roles' => 'role', 'permissions' => 'permission' ]
            ]);
        });
        Route::prefix('users')->group(function () {
            Route::get('', [UserController::class, 'index']);
            Route::get('/roles', [UserController::class, 'roles']);
            Route::post('/roles/{user}', [UserController::class, 'store']);
            Route::delete('/roles/{user}', [UserController::class, 'destroy']);
            Route::get('/find', [UserController::class, 'findUsers']);
            Route::get('/assignors', [UserController::class, 'assignors']);
            Route::get('/validators', [UserController::class, 'validators']);
        });
        Route::prefix('user')->group( function () {
            Route::get('/menu', [UserController::class, 'menu']);
            Route::get('/permissions', [UserController::class, 'permissions']);
        });
        Route::get('audits', [AuditController::class, 'index']);
    });
});
