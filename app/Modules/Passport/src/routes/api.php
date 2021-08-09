<?php

use App\Modules\Passport\src\Controllers\AgreementController;
use App\Modules\Passport\src\Controllers\AuditController;
use App\Modules\Passport\src\Controllers\CardController;
use App\Modules\Passport\src\Controllers\CompanyController;
use App\Modules\Passport\src\Controllers\DashboardController;
use App\Modules\Passport\src\Controllers\EpsController;
use App\Modules\Passport\src\Controllers\FaqController;
use App\Modules\Passport\src\Controllers\HobbyController;
use App\Modules\Passport\src\Controllers\LandingController;
use App\Modules\Passport\src\Controllers\PassportController;
use App\Modules\Passport\src\Controllers\PrintController;
use App\Modules\Passport\src\Controllers\RenewalController;
use App\Modules\Passport\src\Controllers\SuperCadeController;
use App\Modules\Passport\src\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('vital-passport')->group(function () {
    // Public routes
    Route::get('landing', [LandingController::class, 'index']);
    Route::get('card-image', [LandingController::class, 'passport']);
    Route::get('template', [LandingController::class, 'template'])->name('passport.template');
    Route::get('current-template', [LandingController::class, 'templateFile'])->name('passport.current.template');
    Route::get('faq', [FaqController::class, 'index']);
    Route::get('background', [LandingController::class, 'background']);
    Route::get('portfolio', [LandingController::class, 'portfolio']);
    Route::post('rate/{agreement}', [LandingController::class, 'rate']);
    Route::post('comments/{agreement}', [LandingController::class, 'comment']);
    Route::get('eps', [EpsController::class, 'index']);
    Route::get('hobbies', [HobbyController::class, 'index']);

    Route::get('passports', [PassportController::class, 'index'])->middleware('auth:api');
    Route::get('passports/{passport}', [PassportController::class, 'show'])
        ->middleware('auth:api');
    Route::delete('passports/{passport}', [PassportController::class, 'destroy'])
        ->middleware('auth:api');
    Route::get('passports/storage/{file}', [PassportController::class, 'file'])->name('passport.files');
    Route::post('passports', [PassportController::class, 'store']);
    Route::post('passports-admin', [PassportController::class, 'adminStore'])->middleware('auth:api');
    Route::post('query', [PassportController::class, 'query']);
    Route::post('excel', [PassportController::class, 'excel'])->middleware('auth:api');
    Route::post('download/{id}', [PassportController::class, 'download']);
    // Private routes
    Route::get('renewals', [RenewalController::class, 'index'])->middleware('auth:api');
    Route::post('renewals', [RenewalController::class, 'excel'])->middleware('auth:api');
    Route::delete('renewals/{renewal}', [RenewalController::class, 'destroy'])->middleware('auth:api');
    Route::post('printer', [PrintController::class, 'index'])->middleware('auth:api');
    Route::post('login', [UserController::class, 'login']);
    Route::prefix('user')->middleware('auth:api')->group( function () {
        Route::get('/menu', [UserController::class, 'drawer']);
        Route::get('/permissions', [UserController::class, 'permissions']);
    });
    Route::resource('dashboard.cards', CardController::class, [
        'only'    => ['index', 'store', 'update', 'destroy'],
        'parameters' => ['dashboard' => 'dashboard', 'cards' => 'card']
    ])->middleware('auth:api');
    Route::middleware(['auth:api'])->prefix('users')->group(function () {
        Route::get('', [UserController::class, 'index']);
        Route::get('/roles', [UserController::class, 'roles']);
        Route::post('/roles/{user}', [UserController::class, 'store']);
        Route::delete('/roles/{user}', [UserController::class, 'destroy']);
        Route::get('/find', [UserController::class, 'findUsers']);
    });
    Route::get('hobbies-table', [HobbyController::class, 'table'])->middleware('auth:api');
    Route::resource('hobbies', HobbyController::class, [
        'only'    => ['store', 'update', 'destroy'],
        'parameters' => ['hobbies' => 'hobby']
    ])->middleware('auth:api');
    Route::get('eps-table', [EpsController::class, 'table'])->middleware('auth:api');
    Route::resource('eps', HobbyController::class, [
        'only'    => ['store', 'update', 'destroy'],
        'parameters' => ['eps' => 'eps']
    ])->middleware('auth:api');
    Route::get('cades-table', [SuperCadeController::class, 'table'])->middleware('auth:api');
    Route::post('cades/{cade}/status', [SuperCadeController::class, 'status'])->middleware('auth:api');
    Route::resource('cades', SuperCadeController::class, [
        'only'    => ['index', 'store', 'update', 'destroy'],
        'parameters' => ['cades' => 'cade']
    ])->middleware('auth:api');
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
    Route::post('card-config', [ DashboardController::class, 'updateCardImage' ])->middleware('auth:api');
    Route::post('card-template', [ DashboardController::class, 'updateCardTemplate' ])->middleware('auth:api');
    Route::post('faq', [FaqController::class, 'store'])->middleware('auth:api');
    Route::put('faq/{faq}', [FaqController::class, 'update'])->middleware('auth:api');
    Route::delete('faq/{faq}', [FaqController::class, 'destroy'])->middleware('auth:api');
    Route::get('audits', [AuditController::class, 'index'])->middleware('auth:api');
});
