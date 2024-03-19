<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController as UserLoginController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\OTPController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ForgotController;
use App\Http\Controllers\Api\PricingPlanController;
use App\Http\Controllers\Api\ReferralController;
use App\Http\Controllers\Api\KYCUPDATEDController;
use App\Http\Controllers\Api\UserLoanController;
use App\Http\Controllers\Api\UserDpsController;
use App\Http\Controllers\Api\UserFdrController;
use App\Http\Controllers\Api\WithdrawController;
use App\Http\Controllers\Api\WireTransferController;
use App\Http\Controllers\Api\TransferLogController;
use App\Http\Controllers\Api\SendController;
use App\Http\Controllers\Api\BeneficiaryController;
use App\Http\Controllers\Api\MoneyRequestController;
use App\Http\Controllers\Api\OtherBankController as UserOtherBankController;


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

Route::middleware('auth:api')->get('/user/api', function (Request $request) {
    return $request->user();
});

Route::prefix('/user')->group(function () {

    // AUTH ROUTES
    Route::post('/login', [UserLoginController::class, 'login']);
    Route::get('/logout', [UserLoginController::class, 'logout']);
    Route::post('/registration', [RegisterController::class, 'register']);
    // Route::get('/refresh/verify/{token}', [RegisterController::class, 'token']);
    Route::post('/otp-submit', [OTPController::class, 'otp']);
    // Route::get('/forgot', [ForgotController::class, 'showforgotform']);
    Route::post('/forgot', [ForgotController::class, 'forgot']);

     // 2fa VERIFICATION
     Route::get('/two-factor', [UserController::class, 'showTwoFactorForm']);
     Route::post('/createTwoFactor', [UserController::class, 'createTwoFactor']);
     Route::post('/disableTwoFactor', [UserController::class, 'disableTwoFactor']);

    // AFTER LOGIN TOKEN BASE
    Route::middleware('auth:api')->group(function () {
    
        // USER BASIC
        Route::get('/details', [UserController::class, 'profile']);
        Route::post('/profile/update', [UserController::class, 'profileupdate']);
        Route::get('/dashboard', [UserController::class, 'index']);
        Route::post('/change-password', [UserController::class, 'changePassword']);

        // PLAN
        Route::get('/package/subscription/{id}', [PricingPlanController::class, 'subscription']);

        // REFERRERS
        Route::get('/referrers', [ReferralController::class, 'referred']);
        Route::get('/referrer-commissions', [ReferralController::class, 'commissions']);
        
        // KYC
        Route::get('/kyc-form', [KYCUPDATEDController::class, 'index']);

        // LOAN
        Route::get('/loan-plan', [UserLoanController::class, 'loanPlan']);
        Route::post('/loan-request', [UserLoanController::class, 'loanRequest']);
        Route::get('/all-loans', [UserLoanController::class, 'index']);    
        Route::get('/pending-loans', [UserLoanController::class, 'pending']);
        Route::get('/running-loans', [UserLoanController::class, 'running']);
        Route::get('/paid-loans', [UserLoanController::class, 'paid']);
        Route::get('/rejected-loans', [UserLoanController::class, 'rejected']);
        Route::post('/apply-for-loan', [UserLoanController::class, 'loanAmount']);

        // DPS
        Route::get('/all-dps', [UserDpsController::class, 'index']);
        Route::get('/running-dps', [UserDpsController::class, 'running']);
        Route::get('/matured-dps', [UserDpsController::class, 'matured']);
        Route::get('/dps-plans', [UserDpsController::class, 'dpsPlan']);
        Route::post('/dps-submit', [UserDpsController::class, 'dpsSubmit']);

        // FDR
        Route::get('/all-fdr', [UserFdrController::class, 'index']);
        Route::get('/running-fdr', [UserFdrController::class, 'running']);
        Route::get('/closed-fdr', [UserFdrController::class, 'closed']);
        Route::get('/fdr-plans', [UserFdrController::class, 'fdrPlan']);
        Route::post('/apply-for-fdr', [UserFdrController::class, 'fdrAmount']);
        Route::post('/fdr-apply', [UserFdrController::class, 'fdrRequest']);

        // DEPOSIT

        //WIRE TRANSFER
        Route::get('wire-transfer', [WireTransferController::class, 'index']);
        Route::get('wire-transfer/banks', [WireTransferController::class, 'create']);
        
        // WITHDRAW
        Route::get('/withdraws', [WithdrawController::class, 'index']);
        Route::get('/withdraw-methods', [WithdrawController::class, 'create']);
        Route::post('withdraw/store', [WithdrawController::class, 'store']);
        
        // TRANSACTIONS
        Route::get('tranfer-logs', [TransferLogController::class, 'index']);
        Route::get('/other-bank-transfer', [UserOtherBankController::class, 'index']);
        Route::post('/save-account/list', [SendController::class, 'saveAccount']);
        Route::get('/transactions', [UserController::class, 'transaction']);
        Route::post('/other-bank/send', [UserOtherBankController::class, 'store']);

        // BENEFICIARIES
        Route::get('/beneficiaries', [BeneficiaryController::class, 'index']);
        Route::get('/beneficiaries/create', [BeneficiaryController::class, 'create']);
        Route::post('/beneficiary', [BeneficiaryController::class, 'store']);

        // MONEY REQUEST
        Route::get('/money-request/history', [MoneyRequestController::class, 'index']);
        Route::get('/receive-request-money/history', [MoneyRequestController::class, 'receive']);
        // Route::get('/money-request/create', [MoneyRequestController::class, 'create']);
        Route::post('/request-money', [MoneyRequestController::class, 'store']);
        Route::post('/money-send/{id}', [MoneyRequestController::class, 'send']);
        
       
  

    });


});
