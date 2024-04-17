<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserDetailController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\InternetTvController;
use App\Http\Controllers\Api\EnergyController;
use App\Http\Controllers\ProviderController;
use Illuminate\Support\Facades\Auth;

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


Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [RegisterController::class, 'login'])->name('customer-login');
Route::post('/forgot-password', [RegisterController::class, 'forgotPassword']);
Route::post('/reset-password/{token}', [RegisterController::class, 'resetPassword'])->name('reset-password');


Route::group(['middleware' => 'auth:sanctum'], function () {   
    Route::post('/logout', [RegisterController::class, 'logout']);
    //API route for user profile
    Route::get('/profile-details', [UserDetailController::class, 'index']);
    Route::post('/profile-update', [UserDetailController::class, 'update']);
    Route::post('/change-password', [UserDetailController::class, 'changepassword']);
    Route::post('/reset-password', [UserDetailController::class, 'resetpassword']);
    Route::post('/email-update', [UserDetailController::class, 'emailUpdate']);
//API route for Customer Creadential
    Route::post('/update-credentials', [UserDetailController::class, 'updateCredentials']);
    Route::get('/get-credentials', [UserDetailController::class, 'getCredentials']);
//Internet Tv
    Route::get('internet-tv', [InternetTvController::class, 'index']);
//API routs for energy
    Route::get('energy', [EnergyController::class, 'index']);
    Route::get('suppliers', [EnergyController::class, 'getSupliers']);
});
