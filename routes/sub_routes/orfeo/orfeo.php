<?php

use App\Modules\Orfeo\src\Controllers\FiledController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->prefix('api/orfeo')->group( function () {
    Route::get('dependencies', [FiledController::class, 'dependencies']);
    Route::get('calendar', [FiledController::class, 'calendar']);
    Route::get('counters/months', [FiledController::class, 'countByMonth']);
    Route::get('counters/dependencies', [FiledController::class, 'countByDependency']);
    Route::get('counters/status/{status}', [FiledController::class, 'countByStatus']);
    Route::get('filed/{filed}/informed', [FiledController::class, 'informed']);
    Route::get('filed/{filed}/history', [FiledController::class, 'history']);
    Route::resource( 'filed',FiledController::class, [
        'only'    =>  ['index', 'show']
    ]);
});