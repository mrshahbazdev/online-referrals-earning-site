<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Auth\AdminPasswordResetController;
use App\Http\Controllers\Admin\UserActivityController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\KycController;
use App\Http\Controllers\Admin\InvestmentController;
use App\Http\Controllers\Admin\WithdrawalController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\User\UserTaskController;
use App\Http\Controllers\User\TeamController;
use App\Http\Controllers\User\TransactionController;
use App\Http\Controllers\User\DepositController;
use App\Http\Controllers\User\UserWithdrawalController;
use App\Http\Controllers\User\UserKycController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\User\MineController;
use App\Http\Controllers\User\UserLevelController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\DepositSettingController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin\DepositMethodController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Middleware\CheckScriptActivation;
// --- Public Routes ---
// These routes can be accessed by anyone.
// This file includes routes for login, registration, password reset, etc.
require __DIR__.'/auth.php';

// --- Authenticated User Routes ---
// Users must be logged in to access these routes.
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [UserDashboardController::class, 'index'])->name('home');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/image', [ProfileController::class, 'updateProfileImage'])->name('profile.image.update'); // Yeh line add karein
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/tasks', [UserTaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks/{task}/complete', [UserTaskController::class, 'complete'])->name('tasks.complete');
    Route::get('/my-team', [TeamController::class, 'index'])->name('team.index');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/deposit', [DepositController::class, 'create'])->name('deposit.create');
    Route::post('/deposit', [DepositController::class, 'store'])->name('deposit.store');
    Route::get('/withdraw', [UserWithdrawalController::class, 'create'])->name('withdraw.create');
    Route::post('/withdraw', [UserWithdrawalController::class, 'store'])->name('withdraw.store');
    Route::get('/kyc', [UserKycController::class, 'create'])->name('kyc.create');
    Route::post('/kyc', [UserKycController::class, 'store'])->name('kyc.store');
    Route::get('/about-us', [PageController::class, 'about'])->name('about');
    Route::get('/terms-and-conditions', [PageController::class, 'terms'])->name('terms');
    Route::get('/mine', [MineController::class, 'index'])->name('mine.index');
    Route::get('/levels', [UserLevelController::class, 'index'])->name('levels.index');
    Route::post('/levels/{level}/upgrade', [UserLevelController::class, 'upgrade'])->name('levels.upgrade');

    Route::get('/app-download', function() {
        return view('pages.app-download');
    })->name('app.download');

});


// --- Admin Routes ---
// Admin Login Routes (Public)
Route::get('/admin', [AdminLoginController::class, 'create']);
Route::get('/admin/login', [AdminLoginController::class, 'create'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'store']);
Route::get('admin/forgot-password', [AdminPasswordResetController::class, 'create'])->name('admin.password.request');
Route::post('admin/forgot-password', [AdminPasswordResetController::class, 'store'])->name('admin.password.email');
Route::get('admin/reset-password/{token}', [AdminPasswordResetController::class, 'edit'])->name('admin.password.reset');
Route::post('admin/reset-password', [AdminPasswordResetController::class, 'update'])->name('admin.password.update');
Route::post('/admin/logout', [AdminLoginController::class, 'destroy'])->name('admin.logout');
// Admin Protected Routes
Route::middleware(['auth:admin', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserManagementController::class)->except(['show', 'create', 'edit']);
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity_logs.index');
    Route::get('/user-activity', [UserActivityController::class, 'index'])->name('user_activity.index');
    Route::resource('levels', LevelController::class)->except(['show', 'create', 'edit']);
    Route::resource('tasks', TaskController::class)->except(['show', 'create', 'edit']);
    Route::get('/kyc-submissions', [KycController::class, 'index'])->name('kyc.index');
    Route::patch('/kyc-submissions/{kycSubmission}', [KycController::class, 'update'])->name('kyc.update');
    Route::get('/investments', [InvestmentController::class, 'index'])->name('investments.index');
    Route::patch('/investments/{investmentRequest}', [InvestmentController::class, 'update'])->name('investments.update');
    Route::get('/withdrawals', [WithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::patch('/withdrawals/{withdrawalRequest}', [WithdrawalController::class, 'update'])->name('withdrawals.update');
    Route::resource('announcements', AnnouncementController::class)->except(['show', 'create', 'edit']);
    Route::patch('/announcements/{announcement}/toggle', [AnnouncementController::class, 'toggleStatus'])->name('announcements.toggle');
    Route::resource('/admins', AdminManagementController::class)->only(['index', 'store', 'destroy']);
    Route::get('/deposit-settings', [DepositSettingController::class, 'index'])->name('deposit.settings.index');
    Route::post('/deposit-settings', [DepositSettingController::class, 'store'])->name('deposit.settings.store');
    Route::resource('/deposit-methods', DepositMethodController::class)->except(['show']);
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');
});


Route::get('/run-deployment-commands', function () {
    // Zaroori: Is route ko kaam karne ke baad foran delete kar dein!

    // Pehle cache clear karein
    Artisan::call('optimize:clear');

    // Database migrate karein
    Artisan::call('migrate', ['--force' => true]);

    // Storage link banayein
    //Artisan::call('storage:link');

    // Application key generate karein (agar zaroorat ho)
    // Artisan::call('key:generate', ['--force' => true]);

    // Cache dobara banayein
    Artisan::call('config:cache');
    Artisan::call('route:cache');
    Artisan::call('view:cache');

    return 'Deployment commands executed successfully!';
});

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

Route::get('/create-new-super-admin', function () {
    // Zaroori: Is route ko kaam karne ke baad foran delete kar dein!

    $user = User::create([
        'username' => 'superadmin1',
        'email' => 'mrshahbaz46@gmail.com',
        'password' => Hash::make('1122'),
        'role' => 'admin',
        'level_id' => 1,
        'referral_code' => Str::random(8),
    ]);

    return "New admin '{$user->username}' created successfully!";
});

use Carbon\Carbon;

Route::get('/create-user', function () {
    // Zaroori: Is route ko kaam karne ke baad foran delete kar dein!

    $user = User::create([
        'username' => 'testuser',
        'email' => 'mrshahbaz46@gmail.com',
        'password' => Hash::make('1122'),
        'role' => 'user',
        'level_id' => 1,
        'referral_code' => Str::random(8),
        'email_verified_at' => Carbon::now(), // Email ko foran verify karein
    ]);

    return "New verified user '{$user->username}' created successfully!";
});

use App\Models\Level;

Route::get('/create-default-level', function () {
    // Zaroori: Is route ko kaam karne ke baad foran delete kar dein!

    // Check karein ke level pehle se mojood to nahi hai
    if (!Level::find(1)) {
        Level::create([
            'name' => 'Bronze',
            'upgrade_cost' => 0,
            'daily_task_limit' => 5
        ]);
        return "Default level 'Bronze' created successfully!";
    }
    return "Default level already exists.";
});

Route::get('/clear-config-cache', function () {
    // Zaroori: Is route ko kaam karne ke baad foran delete kar dein!
    Artisan::call('storage:link');
    Artisan::call('config:clear');
    Artisan::call('event:clear');
    Artisan::call('optimize:clear');
    return 'Configuration cache has been cleared successfully!';
});
use App\Models\Setting;

Route::get('/activate-script-now/{secret_code}', function ($secret_code) {
    // Is secret code ko aap apni marzi se tabdeel kar sakte hain
    if ($secret_code === '1122') {
        Setting::updateOrCreate(
            ['key' => 'is_script_activated'],
            ['value' => 'true']
        );
        return "Script activated successfully!";
    }
    return "Invalid activation code.";
});

