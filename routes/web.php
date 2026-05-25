<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AccessProfileController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\GlController;
use App\Http\Controllers\FixedAssetController;
use App\Http\Controllers\FdController;
use App\Http\Controllers\ChequeController;
use App\Http\Controllers\PassbookController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\TranslationKeyController;
use App\Http\Controllers\NationalityController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\InterestController;
use App\Http\Controllers\CollateralController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\SocialAuthController;

// ============================================================
// GUEST ROUTES (Not Authenticated)
// ============================================================
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Registration
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Password Reset
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

    // Social Authentication
    Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');
    Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');
    Route::get('/auth/telegram/callback', [SocialAuthController::class, 'telegramCallback'])->name('social.telegram.callback');
});

// Logout (requires auth)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Social Account Linking (requires auth)
Route::middleware('auth')->prefix('social')->group(function () {
    Route::get('/{provider}/link', [SocialAuthController::class, 'link'])->name('social.link');
    Route::get('/{provider}/link/callback', [SocialAuthController::class, 'linkCallback'])->name('social.link.callback');
    Route::delete('/{provider}/unlink', [SocialAuthController::class, 'unlink'])->name('social.unlink');
});

// ============================================================
// PUBLIC ROUTES
// ============================================================
Route::get('/', function () {
    return view('admin.layouts.admin_layout');
    // return redirect()->route('dashboard');
});

// Language switching
Route::post('/language/switch', [LanguageController::class, 'switch'])->name('language.switch');
Route::get('/language/current', [LanguageController::class, 'current'])->name('language.current');
Route::get('/test/language', function () {
    return view('test.language');
})->name('test.language');

// Language Management CRUD
Route::prefix('languages')->group(function () {
    Route::get('/', [LanguageController::class, 'index'])->name('languages.index');
    Route::get('/data', [LanguageController::class, 'getData'])->name('languages.data');
    Route::post('/', [LanguageController::class, 'store'])->name('languages.store');
    Route::get('/{id}', [LanguageController::class, 'show'])->name('languages.show');
    Route::put('/{id}', [LanguageController::class, 'update'])->name('languages.update');
    Route::delete('/{id}', [LanguageController::class, 'destroy'])->name('languages.destroy');
    Route::get('/all', [LanguageController::class, 'all'])->name('languages.all');
});

// Translation Keys Management (with Auto-Translation)
Route::prefix('translation-keys')->group(function () {
    Route::get('/', [TranslationKeyController::class, 'index'])->name('translation-keys.index');
    Route::get('/data', [TranslationKeyController::class, 'getData'])->name('translation-keys.data');
    Route::post('/', [TranslationKeyController::class, 'store'])->name('translation-keys.store');
    Route::get('/statistics', [TranslationKeyController::class, 'getStatistics'])->name('translation-keys.statistics');
    Route::get('/scan-lang-files', [TranslationKeyController::class, 'scanLangFiles'])->name('translation-keys.scan');
    Route::post('/load-from-lang', [TranslationKeyController::class, 'loadFromLangFiles'])->name('translation-keys.load-from-lang');
    Route::post('/sync-with-lang', [TranslationKeyController::class, 'syncWithLangFiles'])->name('translation-keys.sync');
    Route::post('/bulk-auto-translate', [TranslationKeyController::class, 'bulkAutoTranslate'])->name('translation-keys.bulk-auto-translate');
    Route::post('/import', [TranslationKeyController::class, 'importFromLangFiles'])->name('translation-keys.import');
    Route::post('/export', [TranslationKeyController::class, 'exportGroup'])->name('translation-keys.export');
    Route::get('/{id}', [TranslationKeyController::class, 'show'])->name('translation-keys.show');
    Route::put('/{id}', [TranslationKeyController::class, 'update'])->name('translation-keys.update');
    Route::delete('/{id}', [TranslationKeyController::class, 'destroy'])->name('translation-keys.destroy');
    Route::post('/{id}/auto-translate', [TranslationKeyController::class, 'autoTranslate'])->name('translation-keys.auto-translate');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Customer Management
Route::prefix('customers')->group(function () {
    Route::get('/', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/data', [CustomerController::class, 'getData'])->name('customers.data');
    Route::get('/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/{id}/edit/', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::get('/{id}/show', [CustomerController::class, 'show'])->name('customers.show');
    Route::put('/{id}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');
});

// Loan Management
Route::prefix('loans')->group(function () {
    Route::get('/', [LoanController::class, 'index'])->name('loans.index');
    Route::get('/data', [LoanController::class, 'getData'])->name('loans.data');
    Route::get('/create', [LoanController::class, 'create'])->name('loans.create');
    Route::post('/', [LoanController::class, 'store'])->name('loans.store');
    Route::get('/statistics', [LoanController::class, 'statistics'])->name('loans.statistics');
    Route::get('/{id}', [LoanController::class, 'show'])->name('loans.show');
    Route::get('/{id}/edit', [LoanController::class, 'edit'])->name('loans.edit');
    Route::put('/{id}', [LoanController::class, 'update'])->name('loans.update');
    Route::delete('/{id}', [LoanController::class, 'destroy'])->name('loans.destroy');
    Route::post('/{id}/payment', [LoanController::class, 'makePayment'])->name('loans.payment');
    Route::get('/{id}/schedule', [LoanController::class, 'getPaymentSchedule'])->name('loans.schedule');
    Route::post('/{id}/approve', [LoanController::class, 'approve'])->name('loans.approve');
});

// Account Management
Route::prefix('accounts')->group(function () {
    Route::get('/', [AccountController::class, 'index'])->name('accounts.index');
    Route::get('/data', [AccountController::class, 'getData'])->name('accounts.data');
    Route::get('/create', [AccountController::class, 'create'])->name('accounts.create');
    Route::post('/', [AccountController::class, 'store'])->name('accounts.store');
    Route::get('/{id}', [AccountController::class, 'show'])->name('accounts.show');
    Route::get('/{id}/edit', [AccountController::class, 'edit'])->name('accounts.edit');
    Route::put('/{id}', [AccountController::class, 'update'])->name('accounts.update');
    Route::delete('/{id}', [AccountController::class, 'destroy'])->name('accounts.destroy');
    Route::get('/{id}/transactions', [AccountController::class, 'transactions'])->name('accounts.transactions');
    Route::post('/{id}/deposit', [AccountController::class, 'deposit'])->name('accounts.deposit');
    Route::post('/{id}/withdraw', [AccountController::class, 'withdraw'])->name('accounts.withdraw');
});

// Branch Management
Route::prefix('branches')->group(function () {
    Route::get('/', [BranchController::class, 'index'])->name('branches.index');
    Route::get('/data', [BranchController::class, 'getData'])->name('branches.data');
    Route::post('/', [BranchController::class, 'store'])->name('branches.store');
    Route::get('/{id}', [BranchController::class, 'show'])->name('branches.show');
    Route::put('/{id}', [BranchController::class, 'update'])->name('branches.update');
    Route::delete('/{id}', [BranchController::class, 'destroy'])->name('branches.destroy');
    Route::post('/{id}/day-start', [BranchController::class, 'dayStart'])->name('branches.day-start');
    Route::post('/{id}/day-end', [BranchController::class, 'dayEnd'])->name('branches.day-end');
    Route::get('/{id}/transactions', [BranchController::class, 'getTransactions'])->name('branches.transactions');
});

// Staff Management
Route::prefix('staff')->group(function () {
    Route::get('/', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/data', [StaffController::class, 'getData'])->name('staff.data');
    Route::post('/', [StaffController::class, 'store'])->name('staff.store');
    Route::get('/search', [StaffController::class, 'search'])->name('staff.search');
    Route::get('/{id}', [StaffController::class, 'show'])->name('staff.show');
    Route::put('/{id}', [StaffController::class, 'update'])->name('staff.update');
    Route::delete('/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');
});

// User Management
Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/data', [UserController::class, 'getData'])->name('users.data');
    Route::post('/', [UserController::class, 'store'])->name('users.store');
    Route::get('/{id}', [UserController::class, 'show'])->name('users.show');
    Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/{id}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::post('/{id}/suspend', [UserController::class, 'suspend'])->name('users.suspend');
    Route::post('/{id}/activate', [UserController::class, 'activate'])->name('users.activate');
});

// Access Profile Management
Route::prefix('access-profiles')->group(function () {
    Route::get('/', [AccessProfileController::class, 'index'])->name('access-profiles.index');
    Route::get('/data', [AccessProfileController::class, 'getData'])->name('access-profiles.data');
    Route::post('/', [AccessProfileController::class, 'store'])->name('access-profiles.store');
    Route::get('/{id}', [AccessProfileController::class, 'show'])->name('access-profiles.show');
    Route::put('/{id}', [AccessProfileController::class, 'update'])->name('access-profiles.update');
    Route::delete('/{id}', [AccessProfileController::class, 'destroy'])->name('access-profiles.destroy');
    Route::get('/{id}/permissions', [AccessProfileController::class, 'getPermissions'])->name('access-profiles.permissions');
    Route::post('/{id}/permissions', [AccessProfileController::class, 'updatePermissions'])->name('access-profiles.update-permissions');
});

// Currency Management
Route::prefix('currencies')->group(function () {
    Route::get('/', [CurrencyController::class, 'index'])->name('currencies.index');
    Route::get('/data', [CurrencyController::class, 'getData'])->name('currencies.data');
    Route::post('/', [CurrencyController::class, 'store'])->name('currencies.store');
    Route::get('/all', [CurrencyController::class, 'all'])->name('currencies.all');
    Route::get('/{id}', [CurrencyController::class, 'show'])->name('currencies.show');
    Route::put('/{id}', [CurrencyController::class, 'update'])->name('currencies.update');
    Route::delete('/{id}', [CurrencyController::class, 'destroy'])->name('currencies.destroy');
    Route::get('/{id}/rates', [CurrencyController::class, 'getRates'])->name('currencies.rates');
    Route::post('/{id}/rates', [CurrencyController::class, 'storeRate'])->name('currencies.store-rate');
});

// General Ledger Management
Route::prefix('gl')->group(function () {
    Route::get('/', [GlController::class, 'index'])->name('gl.index');
    Route::get('/data', [GlController::class, 'getData'])->name('gl.data');
    Route::post('/', [GlController::class, 'store'])->name('gl.store');
    Route::get('/tree', [GlController::class, 'getTree'])->name('gl.tree');
    Route::get('/all', [GlController::class, 'all'])->name('gl.all');
    Route::get('/{id}', [GlController::class, 'show'])->name('gl.show');
    Route::put('/{id}', [GlController::class, 'update'])->name('gl.update');
    Route::delete('/{id}', [GlController::class, 'destroy'])->name('gl.destroy');
    // GL Levels
    Route::get('/levels/{level}', [GlController::class, 'getLevel'])->name('gl.level');
    Route::post('/levels/{level}', [GlController::class, 'storeLevel'])->name('gl.store-level');
    // GL Mapping
    Route::get('/maps/data', [GlController::class, 'getMapsData'])->name('gl.maps-data');
    Route::post('/maps', [GlController::class, 'storeMap'])->name('gl.store-map');
    Route::put('/maps/{id}', [GlController::class, 'updateMap'])->name('gl.update-map');
});

// Fixed Asset Management
Route::prefix('fixed-assets')->group(function () {
    Route::get('/', [FixedAssetController::class, 'index'])->name('fixed-assets.index');
    Route::get('/data', [FixedAssetController::class, 'getData'])->name('fixed-assets.data');
    Route::post('/', [FixedAssetController::class, 'store'])->name('fixed-assets.store');
    Route::get('/types', [FixedAssetController::class, 'typesIndex'])->name('fixed-assets.types');
    Route::get('/types/data', [FixedAssetController::class, 'getTypesData'])->name('fixed-assets.types-data');
    Route::post('/types', [FixedAssetController::class, 'storeType'])->name('fixed-assets.store-type');
    Route::put('/types/{id}', [FixedAssetController::class, 'updateType'])->name('fixed-assets.update-type');
    Route::delete('/types/{id}', [FixedAssetController::class, 'destroyType'])->name('fixed-assets.destroy-type');
    Route::get('/{id}', [FixedAssetController::class, 'show'])->name('fixed-assets.show');
    Route::put('/{id}', [FixedAssetController::class, 'update'])->name('fixed-assets.update');
    Route::delete('/{id}', [FixedAssetController::class, 'destroy'])->name('fixed-assets.destroy');
    Route::post('/{id}/depreciate', [FixedAssetController::class, 'depreciate'])->name('fixed-assets.depreciate');
    Route::post('/{id}/dispose', [FixedAssetController::class, 'dispose'])->name('fixed-assets.dispose');
    Route::get('/{id}/depreciation-history', [FixedAssetController::class, 'getDepreciationHistory'])->name('fixed-assets.depreciation-history');
});

// Fixed Deposit Management
Route::prefix('fixed-deposits')->group(function () {
    Route::get('/', [FdController::class, 'index'])->name('fixed-deposits.index');
    Route::get('/data', [FdController::class, 'getData'])->name('fixed-deposits.data');
    Route::post('/', [FdController::class, 'store'])->name('fixed-deposits.store');

    // FD Terms management
    Route::get('/terms', [FdController::class, 'getTerms'])->name('fixed-deposits.terms');
    Route::get('/terms/data', [FdController::class, 'getTermsData'])->name('fixed-deposits.terms-data');
    Route::post('/terms', [FdController::class, 'storeTerm'])->name('fixed-deposits.store-term');
    Route::put('/terms/{id}', [FdController::class, 'updateTerm'])->name('fixed-deposits.update-term');
    Route::delete('/terms/{id}', [FdController::class, 'destroyTerm'])->name('fixed-deposits.destroy-term');

    // FD Options management
    Route::get('/options', [FdController::class, 'getOptions'])->name('fixed-deposits.options');
    Route::get('/options/data', [FdController::class, 'getOptionsData'])->name('fixed-deposits.options-data');
    Route::post('/options', [FdController::class, 'storeOption'])->name('fixed-deposits.store-option');
    Route::put('/options/{id}', [FdController::class, 'updateOption'])->name('fixed-deposits.update-option');
    Route::delete('/options/{id}', [FdController::class, 'destroyOption'])->name('fixed-deposits.destroy-option');

    Route::get('/{id}', [FdController::class, 'show'])->name('fixed-deposits.show');
    Route::put('/{id}', [FdController::class, 'update'])->name('fixed-deposits.update');
    Route::post('/{id}/rollover', [FdController::class, 'rollover'])->name('fixed-deposits.rollover');
    Route::post('/{id}/withdraw', [FdController::class, 'withdraw'])->name('fixed-deposits.withdraw');
    Route::get('/{id}/transactions', [FdController::class, 'getTransactions'])->name('fixed-deposits.transactions');
});

// Cheque Management
Route::prefix('cheques')->group(function () {
    Route::get('/', [ChequeController::class, 'index'])->name('cheques.index');
    Route::get('/issues/data', [ChequeController::class, 'getIssuesData'])->name('cheques.issues-data');
    Route::post('/issues', [ChequeController::class, 'storeIssue'])->name('cheques.store-issue');
    Route::post('/issues/{id}/approve', [ChequeController::class, 'approveIssue'])->name('cheques.approve-issue');
    Route::get('/maintenance', [ChequeController::class, 'maintenanceIndex'])->name('cheques.maintenance');
    Route::get('/maintenance/data', [ChequeController::class, 'getMaintenanceData'])->name('cheques.maintenance-data');
    Route::post('/maintenance', [ChequeController::class, 'storeMaintenance'])->name('cheques.store-maintenance');
    Route::post('/maintenance/{id}/approve', [ChequeController::class, 'approveMaintenance'])->name('cheques.approve-maintenance');
    Route::get('/stops', [ChequeController::class, 'stopsIndex'])->name('cheques.stops');
    Route::get('/stops/data', [ChequeController::class, 'getStopsData'])->name('cheques.stops-data');
    Route::post('/stops', [ChequeController::class, 'storeStop'])->name('cheques.store-stop');
    Route::post('/stops/{id}/release', [ChequeController::class, 'releaseStop'])->name('cheques.release-stop');
    Route::get('/clearing', [ChequeController::class, 'clearingIndex'])->name('cheques.clearing');
    Route::get('/clearing/data', [ChequeController::class, 'getClearingData'])->name('cheques.clearing-data');
    Route::post('/clearing', [ChequeController::class, 'storeClear'])->name('cheques.store-clear');
});

// Passbook Management
Route::prefix('passbooks')->group(function () {
    Route::get('/', [PassbookController::class, 'index'])->name('passbooks.index');
    Route::get('/issues/data', [PassbookController::class, 'getIssuesData'])->name('passbooks.issues-data');
    Route::post('/issues', [PassbookController::class, 'storeIssue'])->name('passbooks.store-issue');
    Route::post('/issues/{id}/approve', [PassbookController::class, 'approveIssue'])->name('passbooks.approve-issue');
    Route::get('/maintenance', [PassbookController::class, 'maintenanceIndex'])->name('passbooks.maintenance');
    Route::get('/maintenance/data', [PassbookController::class, 'getMaintenanceData'])->name('passbooks.maintenance-data');
    Route::post('/maintenance', [PassbookController::class, 'storeMaintenance'])->name('passbooks.store-maintenance');
    Route::post('/maintenance/{id}/approve', [PassbookController::class, 'approveMaintenance'])->name('passbooks.approve-maintenance');
    Route::get('/list', [PassbookController::class, 'passbooksList'])->name('passbooks.list');
    Route::get('/list/data', [PassbookController::class, 'getPassbooksData'])->name('passbooks.list-data');
    Route::put('/{id}/print-status', [PassbookController::class, 'updatePrintStatus'])->name('passbooks.update-print-status');
});

// Group Management
Route::prefix('groups')->group(function () {
    Route::get('/', [GroupController::class, 'index'])->name('groups.index');
    Route::get('/data', [GroupController::class, 'getData'])->name('groups.data');
    Route::post('/', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/loans/search', [GroupController::class, 'searchLoans'])->name('groups.search-loans');
    Route::get('/{id}', [GroupController::class, 'show'])->name('groups.show');
    Route::put('/{id}', [GroupController::class, 'update'])->name('groups.update');
    Route::delete('/{id}', [GroupController::class, 'destroy'])->name('groups.destroy');
    Route::get('/{id}/members', [GroupController::class, 'getMembers'])->name('groups.members');
    Route::post('/{id}/members', [GroupController::class, 'addMember'])->name('groups.add-member');
    Route::delete('/{groupId}/members/{detailId}', [GroupController::class, 'removeMember'])->name('groups.remove-member');
});

// Configuration Management
Route::prefix('config')->group(function () {
    Route::get('/', [ConfigController::class, 'index'])->name('config.index');
    Route::get('/all', [ConfigController::class, 'allConfigs'])->name('config.all');
    Route::post('/', [ConfigController::class, 'storeConfig'])->name('config.store');
    Route::post('/save-all', [ConfigController::class, 'saveAllConfigs'])->name('config.save-all');
    Route::post('/save-oauth', [ConfigController::class, 'saveOAuthCredentials'])->name('config.save-oauth');
    Route::put('/{id}', [ConfigController::class, 'updateConfig'])->name('config.update');
    Route::get('/show/{name}', [ConfigController::class, 'showConfig'])->name('config.show');
    // Holidays
    Route::get('/holidays', [ConfigController::class, 'holidaysIndex'])->name('config.holidays');
    Route::get('/holidays/data', [ConfigController::class, 'getHolidaysData'])->name('config.holidays-data');
    Route::post('/holidays', [ConfigController::class, 'storeHoliday'])->name('config.store-holiday');
    Route::get('/holidays/{id}', [ConfigController::class, 'showHoliday'])->name('config.show-holiday');
    Route::put('/holidays/{id}', [ConfigController::class, 'updateHoliday'])->name('config.update-holiday');
    Route::delete('/holidays/{id}', [ConfigController::class, 'destroyHoliday'])->name('config.destroy-holiday');
    // Non-Working Days
    Route::get('/non-working-days', [ConfigController::class, 'nonWorkingDaysIndex'])->name('config.non-working-days');
    Route::post('/non-working-days', [ConfigController::class, 'updateNonWorkingDays'])->name('config.update-non-working-days');
    // Modules
    Route::get('/modules', [ConfigController::class, 'modulesIndex'])->name('config.modules');
    Route::get('/modules/data', [ConfigController::class, 'getModulesData'])->name('config.modules-data');
    Route::post('/modules', [ConfigController::class, 'storeModule'])->name('config.store-module');
    Route::put('/modules/{id}', [ConfigController::class, 'updateModule'])->name('config.update-module');
});

// Nationality Management
Route::prefix('nationalities')->group(function () {
    Route::get('/', [NationalityController::class, 'index'])->name('nationalities.index');
    Route::get('/data', [NationalityController::class, 'getData'])->name('nationalities.data');
    Route::get('/all', [NationalityController::class, 'all'])->name('nationalities.all');
    Route::post('/', [NationalityController::class, 'store'])->name('nationalities.store');
    Route::get('/{id}', [NationalityController::class, 'show'])->name('nationalities.show');
    Route::put('/{id}', [NationalityController::class, 'update'])->name('nationalities.update');
    Route::delete('/{id}', [NationalityController::class, 'destroy'])->name('nationalities.destroy');
});

// Transaction Management
Route::prefix('transactions')->group(function () {
    Route::get('/', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/data', [TransactionController::class, 'getData'])->name('transactions.data');
    Route::post('/', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/summary', [TransactionController::class, 'summary'])->name('transactions.summary');
    Route::get('/daily-summary', [TransactionController::class, 'getDailySummary'])->name('transactions.daily-summary');
    Route::get('/{id}/print', [TransactionController::class, 'print'])->name('transactions.print');
    Route::get('/{id}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/{id}/details', [TransactionController::class, 'getDetails'])->name('transactions.details');
    Route::get('/account/{accountId}', [TransactionController::class, 'getAccountTransactions'])->name('transactions.account');
});

// Cash Management
Route::prefix('cash')->group(function () {
    Route::get('/', [CashController::class, 'index'])->name('cash.index');
    Route::get('/data', [CashController::class, 'getData'])->name('cash.data');
    Route::post('/', [CashController::class, 'store'])->name('cash.store');
    Route::get('/balances', [CashController::class, 'getBalances'])->name('cash.balances');
    Route::get('/balance/{ccyId}', [CashController::class, 'getCurrentBalance'])->name('cash.balance');
    Route::get('/{id}', [CashController::class, 'show'])->name('cash.show')->where('id', '[0-9]+');
    Route::get('/transfers', [CashController::class, 'transfersIndex'])->name('cash.transfers');
    Route::get('/transfers/data', [CashController::class, 'getTransfersData'])->name('cash.transfers-data');
    Route::get('/transfers/summary', [CashController::class, 'getTransfersSummary'])->name('cash.transfers-summary');
    Route::post('/transfers', [CashController::class, 'storeTransfer'])->name('cash.store-transfer');
    Route::get('/transfers/{id}', [CashController::class, 'showTransfer'])->name('cash.show-transfer');
    Route::post('/transfers/{id}/receive', [CashController::class, 'receiveTransfer'])->name('cash.receive-transfer');
});

// Interest Management
Route::prefix('interest')->group(function () {
    Route::get('/rates', [InterestController::class, 'ratesIndex'])->name('interest.rates');
    Route::get('/rates/data', [InterestController::class, 'getRatesData'])->name('interest.rates-data');
    Route::get('/rates/all', [InterestController::class, 'allRates'])->name('interest.rates-all');
    Route::post('/rates', [InterestController::class, 'storeRate'])->name('interest.store-rate');
    Route::get('/rates/{id}', [InterestController::class, 'showRate'])->name('interest.show-rate');
    Route::put('/rates/{id}', [InterestController::class, 'updateRate'])->name('interest.update-rate');
    Route::delete('/rates/{id}', [InterestController::class, 'destroyRate'])->name('interest.destroy-rate');
    Route::get('/accrued', [InterestController::class, 'accruedIndex'])->name('interest.accrued');
    Route::get('/accrued/data', [InterestController::class, 'getAccruedData'])->name('interest.accrued-data');
    Route::post('/accrued/calculate', [InterestController::class, 'calculateAccrued'])->name('interest.calculate-accrued');
    Route::get('/accrued/summary', [InterestController::class, 'accruedSummary'])->name('interest.accrued-summary');
});

// Collateral Management
Route::prefix('collaterals')->group(function () {
    Route::get('/', [CollateralController::class, 'index'])->name('collaterals.index');
    Route::get('/data', [CollateralController::class, 'getData'])->name('collaterals.data');
    Route::post('/', [CollateralController::class, 'store'])->name('collaterals.store');
    Route::get('/summary', [CollateralController::class, 'summary'])->name('collaterals.summary');
    Route::get('/{id}', [CollateralController::class, 'show'])->name('collaterals.show');
    Route::put('/{id}', [CollateralController::class, 'update'])->name('collaterals.update');
    Route::post('/{id}/release', [CollateralController::class, 'release'])->name('collaterals.release');
    Route::get('/loan/{loanScheduleId}', [CollateralController::class, 'byLoan'])->name('collaterals.by-loan');
    Route::get('/{id}/releases', [CollateralController::class, 'releasesData'])->name('collaterals.releases');
});

// Location Management
Route::prefix('locations')->group(function () {
    // Countries
    Route::get('/countries/all', [LocationController::class, 'allCountries'])->name('locations.countries-all');

    // Provinces
    Route::get('/provinces', [LocationController::class, 'provincesIndex'])->name('locations.provinces');
    Route::get('/provinces/data', [LocationController::class, 'getProvincesData'])->name('locations.provinces-data');
    Route::get('/provinces/all', [LocationController::class, 'allProvinces'])->name('locations.provinces-all');
    Route::get('/provinces/by-country/{countryId}', [LocationController::class, 'provincesByCountry'])->name('locations.provinces-by-country');
    Route::get('/provinces/{id}', [LocationController::class, 'showProvince'])->name('locations.show-province');
    Route::post('/provinces', [LocationController::class, 'storeProvince'])->name('locations.store-province');
    Route::put('/provinces/{id}', [LocationController::class, 'updateProvince'])->name('locations.update-province');
    Route::delete('/provinces/{id}', [LocationController::class, 'destroyProvince'])->name('locations.destroy-province');
    // Districts
    Route::get('/districts/data', [LocationController::class, 'getDistrictsData'])->name('locations.districts-data');
    Route::get('/districts/by-province/{provinceId}', [LocationController::class, 'districtsByProvince'])->name('locations.districts-by-province');
    Route::get('/districts/{id}', [LocationController::class, 'showDistrict'])->name('locations.show-district');
    Route::post('/districts', [LocationController::class, 'storeDistrict'])->name('locations.store-district');
    Route::put('/districts/{id}', [LocationController::class, 'updateDistrict'])->name('locations.update-district');
    Route::delete('/districts/{id}', [LocationController::class, 'destroyDistrict'])->name('locations.destroy-district');
    // Communes
    Route::get('/communes/data', [LocationController::class, 'getCommunesData'])->name('locations.communes-data');
    Route::get('/communes/by-district/{districtId}', [LocationController::class, 'communesByDistrict'])->name('locations.communes-by-district');
    Route::get('/communes/{id}', [LocationController::class, 'showCommune'])->name('locations.show-commune');
    Route::post('/communes', [LocationController::class, 'storeCommune'])->name('locations.store-commune');
    Route::put('/communes/{id}', [LocationController::class, 'updateCommune'])->name('locations.update-commune');
    Route::delete('/communes/{id}', [LocationController::class, 'destroyCommune'])->name('locations.destroy-commune');
    // Villages
    Route::get('/villages/data', [LocationController::class, 'getVillagesData'])->name('locations.villages-data');
    Route::get('/villages/by-commune/{communeId}', [LocationController::class, 'villagesByCommune'])->name('locations.villages-by-commune');
    Route::get('/villages/search', [LocationController::class, 'searchVillages'])->name('locations.search-villages');
    Route::get('/villages/{id}', [LocationController::class, 'showVillage'])->name('locations.show-village');
    Route::post('/villages', [LocationController::class, 'storeVillage'])->name('locations.store-village');
    Route::put('/villages/{id}', [LocationController::class, 'updateVillage'])->name('locations.update-village');
    Route::delete('/villages/{id}', [LocationController::class, 'destroyVillage'])->name('locations.destroy-village');
});

// Reports
Route::prefix('reports')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/loans', [ReportController::class, 'loanReport'])->name('reports.loans');
    Route::get('/loan-summary', [ReportController::class, 'loanSummary'])->name('reports.loan-summary');
    Route::get('/loan-arrears', [ReportController::class, 'loanArrears'])->name('reports.loan-arrears');
    Route::get('/disbursements', [ReportController::class, 'disbursementReport'])->name('reports.disbursements');
    Route::get('/accounts', [ReportController::class, 'accountSummary'])->name('reports.accounts');
    Route::get('/transactions', [ReportController::class, 'transactionSummary'])->name('reports.transactions');
    Route::get('/daily', [ReportController::class, 'dailyReport'])->name('reports.daily');
    Route::get('/customers', [ReportController::class, 'customerReport'])->name('reports.customers');
    Route::get('/trial-balance', [ReportController::class, 'trialBalance'])->name('reports.trial-balance');
    Route::get('/export', [ReportController::class, 'exportCsv'])->name('reports.export');
});

// API Routes for AJAX
Route::prefix('api')->group(function () {
    Route::get('/provinces', [LocationController::class, 'getProvinces'])->name('api.provinces');
    Route::get('/districts/{provinceId}', [LocationController::class, 'getDistricts'])->name('api.districts');
    Route::get('/communes/{districtId}', [LocationController::class, 'getCommunes'])->name('api.communes');
    Route::get('/villages/{communeId}', [LocationController::class, 'getVillages'])->name('api.villages');
    Route::get('/villages/search', [LocationController::class, 'searchVillages'])->name('api.villages.search');
    Route::get('/nationalities', [NationalityController::class, 'all'])->name('api.nationalities');
    Route::get('/customers/search', [CustomerController::class, 'searchCustomers'])->name('api.customers.search');
    Route::get('/customers/search-by-cid', [CustomerController::class, 'searchByCid'])->name('api.customers.search-by-cid');
    Route::get('/customers/{id}', [CustomerController::class, 'getCustomer'])->name('api.customers.get');
    Route::get('/customers', [AccountController::class, 'getCustomers'])->name('api.customers');
    Route::get('/accounts/search', [AccountController::class, 'searchAccounts'])->name('api.accounts.search');
    Route::get('/accounts/{id}/details', [AccountController::class, 'details'])->name('api.accounts.details');
    Route::get('/account-types', [AccountController::class, 'getAccountTypes'])->name('api.account-types');
    Route::get('/currencies', [CurrencyController::class, 'all'])->name('api.currencies');
    Route::get('/branches', [BranchController::class, 'all'])->name('api.branches');
    Route::get('/staff/search', [StaffController::class, 'search'])->name('api.staff.search');
    Route::get('/gl/all', [GlController::class, 'all'])->name('api.gl.all');
    Route::get('/interest-rates', [InterestController::class, 'allRates'])->name('api.interest-rates');
    Route::get('/transactions/search', [TransactionController::class, 'search'])->name('api.transactions.search');
    Route::get('/collateral-types', [CollateralController::class, 'getCollateralTypes'])->name('api.collateral-types');
    Route::get('/loans/active', [CollateralController::class, 'getActiveLoans'])->name('api.loans.active');
});
