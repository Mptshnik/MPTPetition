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

Route::middleware('guest')->group(function (){
    Route::get('test', [\App\Http\Controllers\AuthorizationController::class, 'test']);
    Route::post('login',[\App\Http\Controllers\AuthorizationController::class, 'login']);
    Route::get('users/{id}', [\App\Http\Controllers\UserController::class, 'show']);
    Route::get('petitions',[\App\Http\Controllers\PetitionController::class, 'index']);
    Route::get('petitions/most-popular',[\App\Http\Controllers\PetitionController::class, 'indexDesc']);
    Route::get('petitions/{name}', [\App\Http\Controllers\PetitionController::class, 'search']);
    Route::get('recent-petitions',[\App\Http\Controllers\PetitionController::class, 'indexRecent']);
    Route::get('petitions/{id}',[\App\Http\Controllers\PetitionController::class, 'show']);
    Route::post('register',[\App\Http\Controllers\RegistrationController::class, 'register']);
});


Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('user', [\App\Http\Controllers\AuthorizationController::class, 'getCurrentUser']);
    Route::post('logout',[\App\Http\Controllers\AuthorizationController::class, 'logout']);
    Route::post('user-delete',[\App\Http\Controllers\UserController::class, 'destroy']);
    Route::post('user-update',[\App\Http\Controllers\UserController::class, 'update']);
    Route::post('change-password', [\App\Http\Controllers\PasswordController::class, 'changePassword']);
});



Route::middleware(['auth:sanctum'])->group(function (){
    Route::post('make-petition',[\App\Http\Controllers\PetitionController::class, 'store']);
    Route::post('update-petition/{id}',[\App\Http\Controllers\PetitionController::class, 'update']);
    Route::post('delete-petition/{id}',[\App\Http\Controllers\PetitionController::class, 'destroy']);

});

Route::middleware(['auth:sanctum'])->group(function (){
    Route::post('petitions/{id}/sign',[\App\Http\Controllers\SignaturesController::class, 'store']);
    Route::post('petitions/{id}/check-if-signed',[\App\Http\Controllers\SignaturesController::class, 'checkIfSigned']);
    Route::post('petitions/{id}/unsign',[\App\Http\Controllers\SignaturesController::class, 'destroy']);
});




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

