<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PharmacyController;
use App\Http\Controllers\Admin\RiderController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\LoginController;

Route::get('/', fn() => redirect()->route('admin.login'));

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',   [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/pharmacies',  [PharmacyController::class,  'index'])->name('pharmacies');
    Route::post('/pharmacies/{id}/status',  [PharmacyController::class,  'updateStatus'])->name('pharmacies.status');
    Route::delete('/pharmacies/{id}',  [PharmacyController::class,  'destroy'])->name('pharmacies.destroy');
    Route::get('/pharmacies/create', [PharmacyController::class, 'create'])->name('pharmacies.create');
    Route::get('/riders',     [RiderController::class, 'index'])->name('riders');
    Route::post('/riders/{id}/status', [RiderController::class, 'updateStatus'])->name('riders.status');
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions');
    Route::get('/reports',    [ReportController::class, 'index'])->name('reports');
    Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::post('/settings', [SettingsController::class, 'store'])->name('settings.store');
    Route::get('/pharmacies/export', [PharmacyController::class, 'export'])->name('pharmacies.export');

    // Route::get('/customers',  fn() => view('coming-soon'))->name('customers');
    // Route::get('/orders',     fn() => view('coming-soon'))->name('orders');
    Route::get('/users',      [UsersController::class, 'index'])->name('users');
    Route::get('/settings',   [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings',  [SettingsController::class, 'store'])->name('settings.store');
});

// Login routes
Route::get('/admin/login', [LoginController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout');
