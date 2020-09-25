<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [ LoginController::class, 'login' ]);
Route::middleware('auth:api')->prefix('api')->group( function () {
    Route::get('/user', [LoginController::class, 'user']);
});
