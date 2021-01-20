<?php

use App\Modules\Orfeo\src\Controllers\FiledController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->prefix('api/orfeo')->group( function () {
    Route::get('excel', [FiledController::class, 'excel']);
    Route::get('dependencies', [FiledController::class, 'dependencies']);
    Route::get('users', [FiledController::class, 'users']);
    Route::get('folders', [FiledController::class, 'folders']);
    Route::get('document-types', [FiledController::class, 'documentTypes']);
    Route::get('calendar', [FiledController::class, 'calendar']);
    Route::get('counters/months', [FiledController::class, 'countByMonth']);
    Route::get('counters/dependencies', [FiledController::class, 'countByDependency']);
    Route::get('counters/folder', [FiledController::class, 'countByFolder']);
    Route::get('counters/read', [FiledController::class, 'countByRead']);
    Route::get('counters/filed-type', [FiledController::class, 'countByFileType']);
    Route::get('counters/status/{status}', [FiledController::class, 'countByStatus']);
    Route::get('filed/{filed}/informed', [FiledController::class, 'informed']);
    Route::get('filed/{filed}/history', [FiledController::class, 'history']);
    Route::resource( 'filed',FiledController::class, [
        'only'    =>  ['index', 'show']
    ]);
});
