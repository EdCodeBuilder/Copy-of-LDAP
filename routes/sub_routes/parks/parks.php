<?php

use App\Modules\Parks\src\Controllers\AuditController;
use App\Modules\Parks\src\Controllers\EnclosureController;
use App\Modules\Parks\src\Controllers\NeighborhoodController;
use App\Modules\Parks\src\Controllers\PermissionsController;
use App\Modules\Parks\src\Controllers\RoleController;
use App\Modules\Parks\src\Controllers\RolePermissionsController;
use App\Modules\Parks\src\Controllers\RupiController;
use App\Modules\Parks\src\Controllers\EquipmentController;
use App\Modules\Parks\src\Controllers\LocationController;
use App\Modules\Parks\src\Controllers\ParkController;
use App\Modules\Parks\src\Controllers\ScaleController;
use App\Modules\Parks\src\Controllers\StageTypeController;
use App\Modules\Parks\src\Controllers\StatsController;
use App\Modules\Parks\src\Controllers\StatusController;
use App\Modules\Parks\src\Controllers\StoryController;
use App\Modules\Parks\src\Controllers\UpzController;
use App\Modules\Parks\src\Controllers\UserController;
use App\Modules\Parks\src\Controllers\VocationController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group( function () {
    Route::resource('localities', LocationController::class, [
        'only'     =>     ['index', 'store', 'update'],
        'parameters' =>     ['localities' => 'location']
    ]);
    Route::resource('localities.upz', LocationController::class, [
        'only'     =>     ['index', 'store', 'update'],
        'parameters' =>     ['localities' => 'location', 'upz' => 'upz']
    ]);
    Route::resource('localities.upz', UpzController::class, [
        'only'     =>     ['index', 'store', 'update'],
        'parameters' =>     ['localities' => 'location', 'upz' => 'upz']
    ]);
    Route::resource('localities.upz.neighborhoods', NeighborhoodController::class, [
        'only'     =>  ['index', 'store', 'update'],
        'parameters' =>  ['localities' => 'location', 'upz' => 'upz', 'neighborhoods' => 'neighborhood']
    ]);

    Route::resource('stage-types', StageTypeController::class, [
        'only'     =>  ['index', 'store', 'update'],
        'parameters' =>  ['stage-types' => 'stage']
    ]);

    Route::resource('scales', ScaleController::class, [
        'only'     =>  ['index', 'store', 'update'],
        'parameters' =>  ['scales' => 'scale']
    ]);

    Route::resource('enclosures', EnclosureController::class, [
        'only'     =>  ['index', 'store', 'update'],
        'parameters' =>  ['enclosures' => 'enclosure']
    ]);

    Route::resource('vocations', VocationController::class, [
        'only'     =>  ['index', 'store', 'update'],
        'parameters' =>  ['vocations' => 'vocation']
    ]);

    Route::get('statuses', [StatusController::class, 'index']);
    Route::get('type-zones', [StatusController::class, 'type_zones']);
    Route::get('concerns', [StatusController::class, 'concerns']);
    Route::get('vigilance', [StatusController::class, 'vigilance']);

    Route::prefix('parks')->group( function () {
        Route::prefix('stats')->group( function () {
            Route::get('/', [StatsController::class, 'stats']);
            Route::get('/count', [StatsController::class, 'count']);
            Route::get('/enclosure', [StatsController::class, 'enclosure']);
            Route::get('/certified', [StatsController::class, 'certified']);
            Route::get('/localities', [StatsController::class, 'localities']);
            Route::get('/upz', [StatsController::class, 'upz']);
            Route::get('/excel', [StatsController::class, 'excel']);
        });
        Route::get('audits', [AuditController::class, 'index'])->middleware('auth:api');

        Route::post('excel', [ParkController::class, 'excel']);

        Route::get('synthetic-fields', [ParkController::class, 'synthetic']);
        Route::get('equipments', [EquipmentController::class, 'index']);
        Route::get('diagrams', [ParkController::class, 'diagrams']);

        Route::get('owned-keys', [ParkController::class, 'ownedKeys'])->middleware('auth:api');
        Route::get('owned', [ParkController::class, 'owned'])->middleware('auth:api');
        Route::get('owned/{user}', [ParkController::class, 'showOwned'])->middleware('auth:api');
        Route::post('owned', [ParkController::class, 'assignParks'])->middleware('auth:api');
        Route::delete('owned/{user}/{park}', [ParkController::class, 'destroyOwned'])->middleware('auth:api');
        Route::delete('destroy-all-owned/{user}', [ParkController::class, 'destroyAllOwned'])->middleware('auth:api');


        Route::get('{park}/economic-use', [ParkController::class, 'economic']);
        Route::get('{park}/sectors', [ParkController::class, 'sectors']);
        Route::get('{park}/equipment/{equipment}', [ParkController::class, 'fields']);

        Route::middleware(['auth:api', 'can:manage-users-parks,App\Modules\Parks\src\Models\Park'])->prefix('users')->group(function () {
            Route::get('', [UserController::class, 'index']);
            Route::get('/roles', [UserController::class, 'roles']);
            Route::post('/roles/{user}', [UserController::class, 'store']);
            Route::delete('/roles/{user}', [UserController::class, 'destroy']);
            Route::get('/find', [UserController::class, 'findUsers']);
        });
        Route::prefix('user')->middleware('auth:api')->group( function () {
            Route::get('/menu', [UserController::class, 'menu']);
            Route::get('/permissions', [UserController::class, 'permissions']);
        });
        Route::prefix('admin')->middleware('auth:api')->group( function () {
            Route::get('models', [PermissionsController::class, 'models']);
            Route::resource('roles', RoleController::class, [
                'only'  => ['index', 'store', 'update', 'destroy'],
                'parameters' => [ 'roles' => 'role' ]
            ]);
            Route::resource('permissions', PermissionsController::class, [
                'only'  => ['index', 'store', 'update', 'destroy'],
                'parameters' => [ 'permissions' => 'permission' ]
            ]);
            Route::resource('roles.permissions', RolePermissionsController::class, [
                'only'  => ['index', 'update', 'destroy'],
                'parameters' => [ 'roles' => 'role', 'permissions' => 'permission' ]
            ]);
        });
    });

    Route::resource('parks', ParkController::class, [
        'only'  => ['index', 'show', 'store', 'update', 'destroy'],
        'parameters' => [ 'parks' => 'park' ]
    ]);

    Route::resource('parks.rupis', RupiController::class, [
        'only'  => ['index', 'store', 'update', 'destroy'],
        'parameters' => [ 'parks' => 'park', 'rupis' => 'rupi' ]
    ]);
    Route::resource('parks.stories', StoryController::class, [
        'only'  => ['index', 'store', 'update', 'destroy'],
        'parameters' => [ 'parks' => 'park', 'stories' => 'story' ]
    ]);
});
