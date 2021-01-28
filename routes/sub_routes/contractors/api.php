<?php

use App\Modules\Contractors\src\Controllers\AdminController;
use App\Modules\Contractors\src\Controllers\ContractController;
use App\Modules\Contractors\src\Controllers\ContractorController;
use App\Modules\Contractors\src\Controllers\ContractTypeController;
use App\Modules\Contractors\src\Controllers\FileController;
use App\Modules\Contractors\src\Controllers\FileTypeController;
use App\Modules\Contractors\src\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('contractors-portal')->group(function () {
    Route::post('/login', [UserController::class, 'login']);
    Route::get('/users', [AdminController::class, 'index'])->middleware('auth:api');
    Route::get('/excel', [ContractorController::class, 'excel'])->middleware('auth:api');
    Route::get('/roles', [AdminController::class, 'roles'])->middleware('auth:api');
    Route::post('/roles/{user}/user', [AdminController::class, 'store'])->middleware('auth:api');
    Route::delete('/roles/{user}/user', [AdminController::class, 'destroy'])->middleware('auth:api');
    Route::get('/find-users', [AdminController::class, 'findUsers'])->middleware('auth:api');
    Route::get('/menu', [UserController::class, 'drawer'])->middleware('auth:api');
    Route::get('/permissions', [UserController::class, 'permissions'])->middleware('auth:api');
    Route::get('/counter', [ContractorController::class, 'counter'])->middleware('auth:api');
    Route::get('/contract-types', [ContractTypeController::class, 'index']);
    Route::get('/file-types', [FileTypeController::class, 'index']);
    Route::post('/find-contractor', [ContractorController::class, 'find'])->middleware('auth:api');
    Route::get('/user/contract/{payload}', [ContractorController::class, 'user']);
    Route::put('contractors/{contractor}', [ContractorController::class, 'update']);
    Route::resource('contractors', ContractorController::class, [
        'only'     =>     ['index', 'show', 'store'],
        'parameters' =>     ['contractors' => 'contractor']
    ])->middleware('auth:api');
    Route::get('storage/file/{file}-{name?}', [FileController::class, 'file'])->name('file.resource');
    Route::resource('contracts.files', FileController::class, [
        'only'     =>     ['index', 'store', 'destroy'],
        'parameters' =>     ['contracts' => 'contract', 'files' => 'file'],
    ])->middleware('auth:api');
    Route::resource('contractors.contracts', ContractController::class, [
        'only'     =>     ['index', 'store', 'update'],
        'parameters' =>     ['contractors' => 'contractor', 'contracts' => 'contract'],
    ])->middleware('auth:api');
});
