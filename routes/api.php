<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AgentInchargeLoginController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\CustomerAuthController;
use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\AgentWalletController;
use App\Http\Controllers\Api\HomeApiController;
use App\Http\Controllers\Api\NewsFeedPostApiController;



Route::get('/login', function () {
    return response()->json(['message' => 'Unauthorized'], 401);
})->name('login');


// Route::get('/check-role', [AdminAuthController::class, 'checkUrl'])->name('check.role');


Route::post('admin/login', [AdminAuthController::class, 'login']);

Route::post('agent-or-incharge/login', [AgentInchargeLoginController::class, 'agentInchargeLogin']);


//Customer Auth
Route::prefix('customer')->group(function () {
    Route::post('registration', [CustomerAuthController::class, 'CustomerRegistration'])->name('customer.registration');
    Route::post('login', [CustomerAuthController::class, 'CustomerLogin'])->name('customer.login');
});







Route::middleware('auth:sanctum')->group(function () {


    //    Route::prefix('admin/')->group(function () {
    //        Route::post('store', [AdminAuthController::class, 'store']);
    //        Route::get('list', [AdminAuthController::class, 'adminList']);
    //        Route::get('inactive-list', [AdminAuthController::class, 'userInactive']);
    //        Route::get('agent-or-incharge-list', [AdminAuthController::class, 'agentInchargeList']);
    //        Route::get('request-agent-list', [AdminAuthController::class, 'requestAgentList']);
    //        Route::get('approved-agent-request/{id}', [AdminAuthController::class, 'requestAgentApproved']);
    //        Route::get('status-change/{id}', [AdminAuthController::class, 'statusChange']);
    //        Route::post('update/{id}', [AdminAuthController::class, 'update']);
    //        Route::get('logout', [AdminAuthController::class, 'logout']);
    //
    //    });

    Route::prefix('customer/')->group(function () {
        Route::get('list', [CustomerController::class, 'customerList']);
        Route::get('request-list', [CustomerController::class, 'customerRequestsList']);
        Route::get('approve-request/{id}', [CustomerController::class, 'approveRequest']);

        Route::post('store', [CustomerController::class, 'store']);
    });

    Route::prefix('agent/')->group(function () {
        Route::get('get-profile', [AgentInchargeLoginController::class, 'getProfile']);
        Route::post('update-profile', [AgentInchargeLoginController::class, 'updateProfile']);

        Route::get('wallet-balance', [AgentWalletController::class, 'walletBalace']);
        Route::get('all-transaction', [AgentWalletController::class, 'allTransaction']);
        Route::post('withdraw-request', [AgentWalletController::class, 'withdrawRequest']);
        Route::get('approve-withdraw/{id}', [AgentWalletController::class, 'approveWithdraw']);

        //post create
        Route::post('create-post', [NewsFeedPostApiController::class, 'store']);
        Route::get('post-list', [NewsFeedPostApiController::class, 'postList']);

        Route::get('logout', [AgentInchargeLoginController::class, 'logout']);
    });
});

Route::get('get-banner-data', [HomeApiController::class, 'getBanner']);
Route::get('get-url-data', [HomeApiController::class, 'getUrl']);
Route::get('get-mission-data', [HomeApiController::class, 'getMission']);
Route::get('get-vision-data', [HomeApiController::class, 'getVision']);
Route::get('get-about-us-data', [HomeApiController::class, 'getAboutUs']);
Route::get('get-privacy-policy-data', [HomeApiController::class, 'getPolicy']);
Route::get('get-terms-and-condition-data', [HomeApiController::class, 'getTerms']);

Route::middleware(['customer.auth'])->prefix('customer')->group(function () {
    Route::get('get-post-data', [HomeApiController::class, 'getPost']);
    Route::get('get-profile', [CustomerAuthController::class, 'getProfile']);
    Route::post('update-profile', [CustomerAuthController::class, 'updateProfile']);
    Route::post('logout', [CustomerAuthController::class, 'CustomerLogout']);
});

Route::get('/customer/{id}', [CustomerController::class, 'getCustomer']);
Route::get('get-post-data', [HomeApiController::class, 'getPost']);
