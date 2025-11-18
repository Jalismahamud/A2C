<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\backend\UserController;
use App\Http\Controllers\backend\DashboardController;
use App\Http\Controllers\backend\CustomerController;
use App\Http\Controllers\backend\BannerController;
use App\Http\Controllers\backend\VideoUrlController;
use App\Http\Controllers\backend\MissionVisionController;
use App\Http\Controllers\backend\AboutUsController;
use App\Http\Controllers\backend\AgentWalletController;
use App\Http\Controllers\backend\TermsCondtionController;
use App\Http\Controllers\backend\NewsFeedPostController;
use App\Http\Controllers\SettingController;
use App\Models\User;
use Illuminate\Http\Request;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/check-role', function (Request $request) {
    $user = User::where('phone', $request->phone)->first();

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    return response()->json(['role' => $user->role]);
})->name('check.role');


Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('agent-or-incharge/dashboard', [DashboardController::class, 'indexAgent'])->middleware(['auth', 'verified'])->name('agent-or-incharge.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/user-update-password', [ProfileController::class, 'updatePassword'])->name('user.update-password');

    //UserController for admin, incharge, agent
    Route::resource('users', UserController::class);
    Route::get('/agent-list', [UserController::class, 'agentList'])->name('users.agent-list');
    Route::get('/package-balance/{id}', [UserController::class, 'packageBalance'])->name('users.package-sheet');
    Route::get('/customer-list', [UserController::class, 'customerList'])->name('users.customer-list');
    Route::get('/customer-edit/{id}', [UserController::class, 'customerEdit'])->name('users.customer-edit');
    Route::post('/customer-update/{id}', [UserController::class, 'customerUpdate'])->name('users.customer-update');
    Route::post('/customer-delete/{id}', [UserController::class, 'customerDelete'])->name('users.customer-delete');
    Route::get('/change-customer-status/{id}', [UserController::class, 'changeStatus'])->name('users.change-customer-status');
    Route::get('/change-agent-status/{id}', [UserController::class, 'changeAgentStatus'])->name('users.change-agent-status');
    Route::post('/users/balance-add/{id}', [UserController::class, 'addBalance'])->name('users.balance-add');



    //Customer
    Route::resource('customers', CustomerController::class);
    Route::get('/agent-lists', [CustomerController::class, 'agentList'])->name('customers.agent-index');


    //Banner
    Route::prefix('banners/')->group(function () {
        Route::get('create', [BannerController::class, 'create'])->name('banner.create');
        Route::post('store', [BannerController::class, 'store'])->name('banner.store');
        Route::get('list', [BannerController::class, 'index'])->name('banner.index');
        Route::get('edit/{id}', [BannerController::class, 'edit'])->name('banner.edit');
        Route::post('update/{id}', [BannerController::class, 'update'])->name('banner.update');
        Route::post('delete/{id}', [BannerController::class, 'delete'])->name('banner.delete');
    });

    Route::resource('video-url', VideoUrlController::class);

    Route::get('/mission/{id}/edit', [MissionVisionController::class, 'editMission'])->name('mission.edit');
    Route::post('/mission/{id}/update', [MissionVisionController::class, 'updateMission'])->name('mission.update');

    Route::get('/vision/{id}/edit', [MissionVisionController::class, 'editVision'])->name('vision.edit');
    Route::post('/vision/{id}/update', [MissionVisionController::class, 'updateVision'])->name('vision.update');

    Route::get('/about-us/{id}/edit', [AboutUsController::class, 'editContact'])->name('about-us.edit');
    Route::post('/about-us/{id}/update', [AboutUsController::class, 'updateContact'])->name('about-us.update');

    Route::get('/terms-and-condition/{id}/edit', [TermsCondtionController::class, 'editTerms'])->name('terms-and-condition.edit');
    Route::post('/terms-and-condition/{id}/update', [TermsCondtionController::class, 'updateTerms'])->name('terms-and-condition.update');

    Route::get('/privacy-policy/{id}/edit', [TermsCondtionController::class, 'editPolicy'])->name('privacy-policy.edit');
    Route::post('/privacy-policy/{id}/update', [TermsCondtionController::class, 'updatePolicy'])->name('privacy-policy.update');

    Route::resource('posts', NewsFeedPostController::class);
    Route::get('/requested-post', [NewsFeedPostController::class, 'requestList'])->name('posts.request_list');
    Route::get('/change-post-status/{id}', [NewsFeedPostController::class, 'changeStatus'])->name('posts.change-status');
    Route::get('/post-approved/{id}', [NewsFeedPostController::class, 'approvePost'])->name('posts.approved');

    Route::get('withdraw-pending-list', [AgentWalletController::class, 'pendingList'])->name('withdraw.pendinglist');
    Route::get('withdraw-approve-list', [AgentWalletController::class, 'approveList'])->name('withdraw.approvelist');
    // Route::post('withdraw-request', [AgentWalletController::class, 'withdrawRequest'])->name('withdraw.request');
    Route::get('approve-withdraw/{id}', [AgentWalletController::class, 'approveWithdraw'])->name('withdraw.approve');

    Route::get('settings', [SettingController::class, 'index'])->name('setting.index');
    Route::get('setting/edit', [SettingController::class, 'edit'])->name('setting.edit');
    Route::post('settings', [SettingController::class, 'update'])->name('setting.update');
});

require __DIR__ . '/auth.php';
