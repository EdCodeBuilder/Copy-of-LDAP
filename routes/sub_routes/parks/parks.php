<?php

use App\Modules\Parks\src\Controllers\AuditController;
use App\Modules\Parks\src\Controllers\EnclosureController;
use App\Modules\Parks\src\Controllers\RupiController;
use App\Modules\Parks\src\Controllers\EquipmentController;
use App\Modules\Parks\src\Controllers\LocationController;
use App\Modules\Parks\src\Controllers\ParkController;
use App\Modules\Parks\src\Controllers\ScaleController;
use App\Modules\Parks\src\Controllers\StageTypeController;
use App\Modules\Parks\src\Controllers\StatsController;
use App\Modules\Parks\src\Controllers\StatusController;
use App\Modules\Parks\src\Controllers\StoryController;
use App\Modules\Parks\src\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group( function () {
    Route::get('localities/{location}/upz', [LocationController::class, 'upz']);
    Route::get('localities/{location}/upz/{upz}/neighborhoods', [LocationController::class, 'neighborhoods']);
    Route::resource('localities', LocationController::class, [
        'only'     =>     ['index'],
        'parameters' =>     ['localities' => 'location']
    ]);
    Route::get('stage-types', [StageTypeController::class, 'index']);
    Route::get('scales', [ScaleController::class, 'index']);
    Route::get('enclosures', [EnclosureController::class, 'index']);
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
        });
        Route::get('audits', [AuditController::class, 'index']);

        Route::get('synthetic-fields', [ParkController::class, 'synthetic']);
        Route::get('equipments', [EquipmentController::class, 'index']);
        Route::get('diagrams', [ParkController::class, 'diagrams']);

        Route::get('{park}/economic-use', [ParkController::class, 'economic']);
        Route::get('{park}/sectors', [ParkController::class, 'sectors']);
        Route::get('{park}/equipment/{equipment}', [ParkController::class, 'fields']);
        Route::prefix('user')->group( function () {
            Route::get('/menu', [UserController::class, 'menu']);
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
