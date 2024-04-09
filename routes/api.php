<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserDetailController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\InternetTvController;
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
// Route::post('/login', function (Request $request) {
//     $credentials = $request->only('email', 'password');
//     \Log::info('Login attempt with credentials:', $credentials);

//     // Authentication logic...
// });
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::middleware('web')->get('/csrf-token', function() {
//     return response()->json(['csrf_token' => csrf_token()]);
// });
// Route::post('/logout', [UserController::class, 'logout']);
// Route::post('/login', [UserController::class, 'login']);
// Route::controller(RegisterController::class, 'api')->group(function(){
//     Route::post('register', 'register');
//     Route::post('login', 'login');
// });
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [RegisterController::class, 'login'])->name('customer-login');
Route::post('/forgot-password', [RegisterController::class, 'forgotPassword']);
Route::post('/reset-password/{token}', [RegisterController::class, 'resetPassword'])->name('reset-password');
// Route::group(['middleware' => ['api','cors'],'prefix' => 'api'], function () {
//     Route::post('register', 'RegisterController@register');
//     Route::post('login', 'RegisterController@login');
//     Route::group(['middleware' => 'jwt-auth'], function () {
//     	Route::post('get_user_details', 'RegisterController@get_user_details');
//     });
// });
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
