<?php

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\LangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmailManagementController;
use App\Http\Controllers\EmailSettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChangePasswordController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ChallengesController;
use App\Http\Controllers\PayoutsController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\VerificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/
Route::get('clear-cache', function () {
    \Artisan::call('config:cache');
    \Artisan::call('cache:clear');
	\Artisan::call('cache:clear');
    // \Artisan::call('route:cache');
    \Artisan::call('view:clear');
    \Artisan::call('config:cache');
    \Artisan::call('optimize:clear');
	Log::info('Clear all cache');
    dd("Cache is cleared");
});
Route::get('db-migrate', function () {
    \Artisan::call('migrate');
    dd("Database migrated");
});
Route::get('db-seed', function () {
    \Artisan::call('db:seed');
    dd("Database seeded");
});
Route::get('/', [ProfileController::class, 'welcome']);

Route::get('lang/home', [LangController::class, 'index']);
Route::get('lang/change', [LangController::class, 'change'])->name('changeLang');

Route::post('/delete-kyc', [VerificationController::class, 'delete_kyc_doc'])->name('client.delete-kyc');
//Client
Route::middleware(['auth', 'client'])->name('client.')->group(function () {
	//Dashboard
	Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
	
	//Account
	Route::get('/account', [DashboardController::class, 'account'])->name('account');
		
	//Verification
	Route::get('/verification', [VerificationController::class, 'index'])->name('verification');
	Route::post('/verification', [VerificationController::class, 'save'])->name('client.verification'); 
	
	//Route::post('/delete-kyc', [VerificationController::class, 'delete_kyc_doc'])->name('client.delete-kyc');
		
	//Withdraw
	Route::name('withdraw.')->group(function () {
		Route::get('/withdraw', [DashboardController::class, 'withdraw'])->name('index');
		Route::post('/withdraw-request', [DashboardController::class, 'withdraw_request'])->name('withdraw-request');
	});
	
});
//Admin	
Route::middleware(['auth', 'admin'])->group(function () {
	//User-Accounts
	Route::name('users.')->group(function () {
		Route::get('/users', [UserController::class, 'index'])->name('index');
		Route::post('/user-update-data', [UserController::class, 'update_data'])->name('user-update-data');
		Route::post('/user-data-submit', [UserController::class, 'submit_data'])->name('user-data-submit');
		Route::post('/user-update-status', [UserController::class, 'update_status'])->name('user-update-status');
		Route::post('/get_delete_data', [UserController::class, 'get_delete_data'])->name('get_delete_data');
		Route::post('/final_delete_submit', [UserController::class, 'final_delete_submit'])->name('final_delete_submit');
		Route::post('/adjust-balance', [UserController::class, 'adjust_balance'])->name('adjust-balance');
	});
	
	//Challenges
	Route::name('challenges.')->group(function () {
		Route::get('/challenges', [ChallengesController::class, 'index'])->name('index');
		Route::post('/challenges/check-email', [ChallengesController::class, 'check_email'])->name('check-email');
		Route::post('/challenges/trader-challenge-amount', [ChallengesController::class, 'trader_challenge_amount'])->name('trader-challenge-amount');
		Route::post('/challenges/challenge-submit', [ChallengesController::class, 'challenge_submit'])->name('challenge-submit');
	});
	
	//Payouts
	Route::get('/payouts', [PayoutsController::class, 'index'])->name('payouts');
	
	//Kyc
	Route::get('/kyc', [KycController::class, 'index'])->name('kyc');
	Route::post('/kyc-document', [KycController::class, 'kyc_document'])->name('kyc-document');
	Route::post('/kyc-doc-status-update', [KycController::class, 'kyc_document_status_update'])->name('kyc-doc-status-update');
	
	//ChangePassword
	Route::get('/change-password', [ChangePasswordController::class, 'index'])->name('change-password');
	Route::post('/change-password', [ChangePasswordController::class, 'save_data'])->name('change-password-save');

	//EmailSettings
	Route::get('/email-settings', [EmailSettingsController::class, 'index'])->name('user.email-settings');
	Route::post('/email-settings', [EmailSettingsController::class, 'save_data'])->name('email-settings-save');

	// Email Management Routes
	Route::get('email-management', [EmailManagementController::class,'index'])->name('email-management');
	Route::get('/email-management-edit/{id}', [EmailManagementController::class, 'email_management_edit'])->name('email-management-edit');
	Route::post('/email-management-edit-save',[EmailManagementController::class,'manage_email_management_process'])->name('email-management-edit-save');
});



require __DIR__.'/auth.php';
