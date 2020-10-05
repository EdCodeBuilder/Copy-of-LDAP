<?php

use App\Modules\Orfeo\src\Controllers\FiledController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->prefix('api/orfeo')->group( function () {
    Route::resource( 'filed',FiledController::class, [
        'only'    =>  ['index', 'show']
    ]);
});