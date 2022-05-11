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

Route::get('test', [\App\Http\Controllers\AuthorizationController::class, 'test']);

Route::middleware('guest')->group(function (){
    Route::get('petitions',[\App\Http\Controllers\PetitionController::class, 'index']);
    Route::get('petitions/{id}',[\App\Http\Controllers\PetitionController::class, 'show']);
    Route::get('users/{id}', [\App\Http\Controllers\UserController::class, 'show']);
});


Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('user', [\App\Http\Controllers\AuthorizationController::class, 'getCurrentUser']);
    Route::post('logout',[\App\Http\Controllers\AuthorizationController::class, 'logout']);
    Route::post('user-delete',[\App\Http\Controllers\UserController::class, 'destroy']);
    Route::post('user-update',[\App\Http\Controllers\UserController::class, 'update']);
});


Route::middleware(['auth:sanctum'])->group(function (){
    Route::post('make-petition',[\App\Http\Controllers\PetitionController::class, 'store']);
    Route::post('update-petition/{id}',[\App\Http\Controllers\PetitionController::class, 'update']);
    Route::post('delete-petition/{id}',[\App\Http\Controllers\PetitionController::class, 'destroy']);
});

Route::post('login',[\App\Http\Controllers\AuthorizationController::class, 'login']);
Route::post('register',[\App\Http\Controllers\RegistrationController::class, 'register']);


Route::middleware('guest')->post('reset-password', [\App\Http\Controllers\PasswordController::class, 'resetPassword'])
    ->name('password.update');

Route::middleware('guest')->get('/reset-password/{token}', function ($token) {
    return ['token' => $token];

})->name('password.reset');
Route::middleware('guest')->post('forgot-password', [\App\Http\Controllers\PasswordController::class, 'sendPasswordChangeEmail'])
    ->name('password.email');


Route::post('email/verification', [\App\Http\Controllers\EmailVerificationController::class, 'resendNotification']);
Route::get('verify-email/{id}/{hash}', [\App\Http\Controllers\EmailVerificationController::class, 'verify'])
    ->name('verification.verify');

