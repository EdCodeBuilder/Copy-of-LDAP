<?php

use App\Modules\PaymentGateway\src\Controllers\DocumentPseController;
use App\Modules\PaymentGateway\src\Controllers\ParkPseController;
use App\Modules\PaymentGateway\src\Controllers\PseController;
use Illuminate\Support\Facades\Route;

Route::prefix('payment-gateway')->group(function () {
      // Public routes
      Route::get('parks', [ParkPseController::class, 'index']);
      Route::get('documents', [DocumentPseController::class, 'index']);
      Route::post('services/{id}', [ParkPseController::class, 'services']);
      Route::get('banks', [PseController::class, 'banks']);
      Route::post('transferBank', [PseController::class, 'transferBank']);
      Route::get('status/{codePayment}', [PseController::class, 'status']);
      Route::get('transaccions/{document}', [PseController::class, 'transaccions']);
      Route::post('webhook', [PseController::class, 'webHook']);
});
