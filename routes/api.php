<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
/*
Route::middleware('auth:sanctum')->group(function(){
    Route::get('user', [\App\Http\Controllers\AuthorizationController::class, 'getCurrentUser']);
    Route::post('logout',[\App\Http\Controllers\AuthorizationController::class, 'logout']);
});*/

Route::get('user', [\App\Http\Controllers\AuthorizationController::class, 'getCurrentUser']);
Route::post('logout',[\App\Http\Controllers\AuthorizationController::class, 'logout']);

Route::post('login',[\App\Http\Controllers\AuthorizationController::class, 'login']);
Route::post('register',[\App\Http\Controllers\RegistrationController::class, 'register']);

Route::post('email/verification', [\App\Http\Controllers\EmailVerificationController::class, 'sendVerificationEmail'])
    ->middleware('auth:sanctum');
Route::get('verify-email/{id}/{hash}', [\App\Http\Controllers\EmailVerificationController::class, 'verify'])
    ->name('verification.verify')->middleware('auth:sanctum');
