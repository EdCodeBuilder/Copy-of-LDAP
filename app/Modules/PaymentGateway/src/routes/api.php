<?php

use App\Modules\PaymentGateway\src\Controllers\ParkPseController;
use Illuminate\Support\Facades\Route;

Route::prefix('payment-gateway')->group(function () {
    // Public routes
    Route::get('parks', [ParkPseController::class, 'index']);

});
