<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PharmacyController;
use App\Http\Controllers\Api\SubscriptionController;

// ── Public routes (no token needed) ──────────────────────────

// Admin login
Route::post('/admin/login', [AuthController::class, 'login']);

// Pharmacy self-registration & login
Route::post('/pharmacies/register', [PharmacyController::class, 'register']);
Route::post('/pharmacies/login',    [PharmacyController::class, 'login']);

// ── Protected routes (token required) ────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Admin logout
    Route::post('/admin/logout', [AuthController::class, 'logout']);

    // Pharmacies (admin only)
    Route::get('/pharmacies',               [PharmacyController::class, 'index']);
    Route::post('/pharmacies/{id}/status',  [PharmacyController::class, 'updateStatus']);
    Route::delete('/pharmacies/{id}',       [PharmacyController::class, 'destroy']);

    // Subscriptions (admin only)
    Route::get('/subscriptions',             [SubscriptionController::class, 'index']);
    Route::patch('/subscriptions/{id}/status',[SubscriptionController::class, 'updateStatus']);
    Route::get('/subscriptions/stats',       [SubscriptionController::class, 'stats']);
});
