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

//Client
Route::middleware(['auth', 'client'])->name('client.')->group(function () {
	//Dashboard
	Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
	
	//Account
	Route::get('/account', [DashboardController::class, 'account'])->name('account');
		
	//Verification
	Route::get('/verification', [VerificationController::class, 'index'])->name('verification');
	Route::post('/verification', [VerificationController::class, 'save'])->name('client.verification');
		
	//Withdraw
	Route::get('/withdraw', [DashboardController::class, 'withdraw'])->name('withdraw');
		
});
//Admin	
Route::middleware(['auth', 'admin'])->group(function () {
	//User-Accounts
	Route::get('/users', [UserController::class, 'index'])->name('users');
	
	//Challenges
	Route::get('/challenges', [ChallengesController::class, 'index'])->name('challenges');
	
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
