<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserDetailController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\InternetTvController;
use App\Http\Controllers\Api\EnergyController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\Api\SettingsController;
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
//Basic Auth
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [RegisterController::class, 'login'])->name('customer-login');
Route::post('/forgot-password', [RegisterController::class, 'forgotPassword']);
Route::post('/reset-password/{token}', [RegisterController::class, 'resetPassword'])->name('reset-password');

//Frontend Guest
//Internet Tv
    Route::post('internet-tv', [InternetTvController::class, 'index']);
    Route::post('internet-tv-compare', [InternetTvController::class, 'internetCompare']);
//API routs for energy
    Route::post('energy', [EnergyController::class, 'index']);
    Route::post('energy-compare', [EnergyController::class, 'energyCompare']);
    Route::get('suppliers', [SettingsController::class, 'getSupliers']);
    Route::get('house-type', [SettingsController::class, 'houseTypes']);
//Frontent === Auth    
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

});
