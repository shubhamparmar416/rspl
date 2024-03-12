<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\LoginController as UserLoginController;
use App\Http\Controllers\User\RegisterController;
use App\Http\Controllers\User\OTPController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\ForgotController;
use App\Http\Controllers\User\ReferralController;




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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('user')->group(function () {
    Route::post('/login', [UserLoginController::class, 'login']);
    Route::post('/registration', [RegisterController::class, 'register']);
    Route::post('/otp-submit', [OTPController::class, 'otp']);
    Route::post('/dashboard', [UserController::class, 'index']);
    Route::post('/profile/update', [UserController::class, 'profileupdate']);
    Route::post('/forgot', [ForgotController::class, 'showforgotform']);
    Route::post('/forgot/submit', [ForgotController::class, 'forgot']);
    Route::post('/password/update', [UserController::class, 'changePassword']);
    Route::get('/referrers', [ReferralController::class, 'referred']);
    Route::get('/referral-commissions', [ReferralController::class, 'commissions']);

});

