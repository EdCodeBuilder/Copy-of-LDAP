<?php

use App\Modules\Payroll\src\Controllers\UserSevenController;
// use App\Modules\Contractors\src\Controllers\ContractController;
use Illuminate\Support\Facades\Route;

Route::prefix('payroll')->group(function () {
    Route::post('/getUserSevenList', [UserSevenController::class, 'getUserSevenList']);
    Route::post('/consultUserSevenList', [UserSevenController::class, 'consultUserSevenList']);
    Route::post('/certificate-compliance-excel', [UserSevenController::class, 'excelCertificateCompliance']);
    Route::post('/loadExcelContractors', [UserSevenController::class, 'loadExcelContractors']);
    Route::post('/getPerson', [UserSevenController::class, 'getPerson']);
    //Route::post('/find-contractor', [ContractorController::class, 'find'])->middleware('auth:api');
});
