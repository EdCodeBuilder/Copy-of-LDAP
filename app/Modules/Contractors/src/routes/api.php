<?php

use App\Modules\Contractors\src\Controllers\AdminController;
use App\Modules\Contractors\src\Controllers\CareerController;
use App\Modules\Contractors\src\Controllers\ContractController;
use App\Modules\Contractors\src\Controllers\ContractorController;
use App\Modules\Contractors\src\Controllers\ContractTypeController;
use App\Modules\Contractors\src\Controllers\FileController;
use App\Modules\Contractors\src\Controllers\FileTypeController;
use App\Modules\Contractors\src\Controllers\PeaceAndSafeController;
use App\Modules\Contractors\src\Controllers\UserController;
use App\Modules\Contractors\src\Controllers\Certificados_TributariosController;
use Illuminate\Support\Facades\Route;

Route::prefix('contractors-portal')->group(function () {
    Route::get('/academic-level', [CareerController::class, 'levels']);
    Route::get('/academic-level/{level}/careers', [CareerController::class, 'index']);
    // Route::get('/sample', [PeaceAndSafeController::class, 'sample']);
    Route::post('/peace-and-save', [PeaceAndSafeController::class, 'index']);
    Route::post('/warehouse-peace-and-save', [PeaceAndSafeController::class, 'wareHouse']);
    Route::post('/warehouse-peace-and-save/notification', [PeaceAndSafeController::class, 'sendWareHouseNotification']);
    Route::post('/warehouse-peace-and-save/validate-code/{code}', [PeaceAndSafeController::class, 'validateCode']);
    Route::post('/warehouse-peace-and-save/excel', [PeaceAndSafeController::class, 'excelWare']);
    Route::post('/warehouse-peace-and-save/more-data', [PeaceAndSafeController::class, 'paginateData']);
    Route::get('/peace-and-save/{token}', [PeaceAndSafeController::class, 'show']);
    Route::post('/generate-certificate', [PeaceAndSafeController::class, 'validation']);
    Route::get('/enable-ldap/{username}-{ous?}', [PeaceAndSafeController::class, 'enableLDAP']);
    Route::get('/disable-ldap/{username}', [PeaceAndSafeController::class, 'disableLDAPManual']);
    Route::post('/oracle', [UserController::class, 'oracle']);
    Route::post('/oracle-count', [UserController::class, 'countOracle']);
    Route::post('/oracle-excel', [UserController::class, 'excelOracle']);
    Route::post('/login', [UserController::class, 'login'])->name('contractors.login');
    Route::get('/users', [AdminController::class, 'index'])->middleware('auth:api');
    Route::get('/excel', [ContractorController::class, 'excel'])->middleware('auth:api');
    Route::get('/roles', [AdminController::class, 'roles'])->middleware('auth:api');
    Route::post('/roles/{user}/user', [AdminController::class, 'store'])->middleware('auth:api');
    Route::delete('/roles/{user}/user', [AdminController::class, 'destroy'])->middleware('auth:api');
    Route::get('/find-users', [AdminController::class, 'findUsers'])->middleware('auth:api');
    Route::get('/menu', [UserController::class, 'drawer'])->middleware('auth:api');
    Route::get('/permissions', [UserController::class, 'permissions'])->middleware('auth:api');
    Route::get('/counter', [ContractorController::class, 'counter'])->middleware('auth:api');
    Route::get('/stats', [ContractorController::class, 'stats']);//->middleware('auth:api');
    Route::get('/contract-types', [ContractTypeController::class, 'index']);
    Route::get('/file-types', [FileTypeController::class, 'index']);
    Route::post('/find-contractor', [ContractorController::class, 'find'])->middleware('auth:api');
    Route::get('/user/contract/{payload}', [ContractorController::class, 'user']);
    Route::put('contractors/{contractor}', [ContractorController::class, 'update']);
    Route::get('resend-notification/{contractor}', [ContractorController::class, 'resendNotification'])->middleware('auth:api');
    Route::put('basic-data/{contractor}', [ContractorController::class, 'updateBasicData'])->middleware('auth:api');
    Route::resource('contractors', ContractorController::class, [
        'only'     =>     ['index', 'show', 'store'],
        'parameters' =>     ['contractors' => 'contractor']
    ])->middleware('auth:api');
    Route::get('storage/file/{file}-{name?}', [FileController::class, 'file'])->name('file.resource');
    Route::get('contractors/rut/{contractor}-{name?}', [ContractorController::class, 'rut'])->name('file.contractors.rut');
    Route::get('contractors/bank/{contractor}-{name?}', [ContractorController::class, 'bank'])->name('file.contractors.bank');
    Route::put('contractors/third-party/{contractor}', [ContractorController::class, 'thirdParty'])->name('file.contractors.third.party')->middleware('auth:api');
    Route::resource('contracts.files', FileController::class, [
        'only'     =>     ['index', 'store', 'destroy'],
        'parameters' =>     ['contracts' => 'contract', 'files' => 'file'],
    ])->middleware('auth:api');
    Route::resource('contractors.contracts', ContractController::class, [
        'only'     =>     ['index', 'store', 'update'],
        'parameters' =>     ['contractors' => 'contractor', 'contracts' => 'contract'],
    ])->middleware('auth:api');
    Route::post('certificado-tributario', [Certificados_TributariosController::class, "index"]);
    Route::post('certificado-tributario/token', [Certificados_TributariosController::class, "validarUsuario"]);
    Route::post('certificado-tributario/oracle', [Certificados_TributariosController::class, "consultaSV"]);
});
