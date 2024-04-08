<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserDetailController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\InternetTvController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('web')->get('/csrf-token', function() {
    return response()->json(['csrf_token' => csrf_token()]);
});
// Route::post('/logout', [UserController::class, 'logout']);
// Route::post('/login', [UserController::class, 'login']);
// Route::controller(RegisterController::class, 'api')->group(function(){
//     Route::post('register', 'register');
//     Route::post('login', 'login');
// });
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [RegisterController::class, 'login']);
Route::post('/forgot-password', [RegisterController::class, 'forgotPassword']);
Route::post('/reset-password/{token}', [RegisterController::class, 'resetPassword'])->name('reset-password');
Route::get('internet-tv', [InternetTvController::class, 'index']);  
Route::middleware('auth:sanctum')->group( function () {
	Route::post('/logout', [RegisterController::class, 'logout']);
    Route::get('/profile-details', [UserDetailController::class, 'index']);
    Route::post('/profile-update', [UserDetailController::class, 'update']);
    Route::post('/change-password', [UserDetailController::class, 'changepassword']);
    Route::post('/reset-password', [UserDetailController::class, 'resetpassword']);
    Route::post('/email-update', [UserDetailController::class, 'emailUpdate']);
});
Route::group(['middleware' => 'api_secure'], function () {
    //Route::post('/register', [UserController::class, 'register']);

    //API route for user profile

    Route::post('/profile-details', [UserDetailController::class, 'index']);
    Route::post('/profile-update', [UserDetailController::class, 'update']);
    Route::post('/change-password', [UserDetailController::class, 'changepassword']);
    Route::post('/reset-password', [UserDetailController::class, 'resetpassword']);
    Route::post('/email-update', [UserDetailController::class, 'emailUpdate']);
});
