<?php

use App\Modules\Passport\src\Controllers\AgreementController;
use App\Modules\Passport\src\Controllers\CompanyController;
use App\Modules\Passport\src\Controllers\DashboardController;
use App\Modules\Passport\src\Controllers\EpsController;
use App\Modules\Passport\src\Controllers\HobbyController;
use App\Modules\Passport\src\Controllers\LandingController;
use App\Modules\Passport\src\Controllers\PassportController;
use App\Modules\Passport\src\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('vital-passport')->group(function () {
    // Public routes
    Route::get('landing', [LandingController::class, 'index']);
    Route::get('faq', [LandingController::class, 'faq']);
    Route::get('background', [LandingController::class, 'background']);
    Route::get('portfolio', [LandingController::class, 'portfolio']);
    Route::post('rate/{agreement}', [LandingController::class, 'rate']);
    Route::post('comments/{agreement}', [LandingController::class, 'comment']);
    Route::get('eps', [EpsController::class, 'index']);
    Route::get('hobbies', [HobbyController::class, 'index']);
    Route::post('create', [PassportController::class, 'store']);
    Route::post('show', [PassportController::class, 'show']);
    Route::post('download/{id}', [PassportController::class, 'download']);
    // Private routes
    Route::post('login', [UserController::class, 'login']);
    Route::prefix('user')->middleware('auth:api')->group( function () {
        Route::get('/menu', [UserController::class, 'drawer']);
        Route::get('/permissions', [UserController::class, 'permissions']);
    });
    Route::middleware(['auth:api'])->prefix('users')->group(function () {
        Route::get('', [UserController::class, 'index']);
        Route::get('/roles', [UserController::class, 'roles']);
        Route::post('/roles/{user}', [UserController::class, 'store']);
        Route::delete('/roles/{user}', [UserController::class, 'destroy']);
        Route::get('/find', [UserController::class, 'findUsers']);
    });
    Route::resource('companies', CompanyController::class, [
        'only'    => ['index', 'store', 'update', 'destroy'],
        'parameters' => ['companies' => 'company']
    ]);
    Route::post('agreements/{agreement}/images', [AgreementController::class, 'image'])
        ->middleware('auth:api');
    Route::delete('images/{image}', [AgreementController::class, 'destroyImage'])
        ->middleware('auth:api');
    Route::delete('comments/{comment}', [AgreementController::class, 'destroyComment'])
        ->middleware('auth:api');
    Route::resource('agreements', AgreementController::class, [
        'only'    => ['index', 'store', 'update', 'destroy'],
        'parameters' => ['agreements' => 'agreement']
    ])->middleware('auth:api');
    Route::get('stats', [ DashboardController::class, 'stats' ])->middleware('auth:api');
    Route::post('background/{dashboard}', [ DashboardController::class, 'background' ])->middleware('auth:api');
    Route::put('landing/{dashboard}', [ DashboardController::class, 'landing' ])->middleware('auth:api');
    Route::put('banner/{dashboard}', [ DashboardController::class, 'banner' ])->middleware('auth:api');
    Route::delete('banner/{dashboard}', [ DashboardController::class, 'destroyBanner' ])->middleware('auth:api');
});
