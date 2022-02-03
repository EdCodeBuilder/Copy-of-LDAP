<?php

use App\Modules\PaymentGateway\src\Controllers\DocumentPseController;
use App\Modules\PaymentGateway\src\Controllers\ParkController;
use App\Modules\PaymentGateway\src\Controllers\ParkServiceController;
use App\Modules\PaymentGateway\src\Controllers\PseController;
use App\Modules\PaymentGateway\src\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::prefix('payment-gateway')->group(function () {
      // Public routes
      Route::get('parks', [ParkController::class, 'index']);
      Route::get('services', [ServiceController::class, 'index']);
      Route::post('services/{id}', [ParkController::class, 'services']);
      Route::get('documents', [DocumentPseController::class, 'index']);

      //Rutas paymentez
      Route::get('banks', [PseController::class, 'banks']);
      Route::post('transferBank', [PseController::class, 'transferBank']);
      Route::get('status/{codePayment}', [PseController::class, 'status']);
      Route::get('transaccions/{document}', [PseController::class, 'transaccions']);
      Route::post('webhook', [PseController::class, 'webHook']);

      //Private routes park
      Route::post('create_park', [ParkController::class, 'create']);
      Route::post('update_park/{id}', [ParkController::class, 'update']);
      Route::delete('delete_park/{id}', [ParkController::class, 'delete']);

      //Private routes services
      Route::post('create_service', [ServiceController::class, 'create']);
      Route::post('update_service/{id}', [ServiceController::class, 'update']);
      Route::delete('delete_service/{id}', [ServiceController::class, 'delete']);

      //private routes park-services
      Route::get('park_services', [ParkServiceController::class, 'index']);
      Route::post('assign_services', [ParkServiceController::class, 'assign']);
      Route::post('update_assign/{id}', [ParkServiceController::class, 'update']);
      Route::delete('delete_assign/{id}', [ParkServiceController::class, 'delete']);

});
