<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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


Route::post('login',[\App\Http\Controllers\AuthorizationController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('user', [\App\Http\Controllers\AuthorizationController::class, 'getCurrentUser']);
    Route::post('logout',[\App\Http\Controllers\AuthorizationController::class, 'logout']);
});


Route::middleware(['auth:sanctum'])->group(function (){
    Route::post('petition',[\App\Http\Controllers\PetitionController::class, 'create']);
});

Route::post('register',[\App\Http\Controllers\RegistrationController::class, 'register']);

Route::post('email/verification', [\App\Http\Controllers\EmailVerificationController::class, 'resendNotification']);
Route::get('verify-email/{id}/{hash}', [\App\Http\Controllers\EmailVerificationController::class, 'verify'])
    ->name('verification.verify');

