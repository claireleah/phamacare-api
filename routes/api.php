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
    Route::get('/pharmacy-users',               [PharmacyController::class, 'staffIndex']);
    Route::post('/pharmacy-users',              [PharmacyController::class, 'staffStore']);
    Route::put('/pharmacy-users/{id}', [PharmacyController::class, 'staffUpdate']);
    Route::patch('/pharmacy-users/{id}/status', [PharmacyController::class, 'staffToggleStatus']);
    Route::delete('/pharmacy-users/{id}', [PharmacyController::class, 'staffDestroy']);

    // Subscriptions (admin only)
    Route::get('/subscriptions',             [SubscriptionController::class, 'index']);
    Route::patch('/subscriptions/{id}/status',[SubscriptionController::class, 'updateStatus']);
    Route::get('/subscriptions/stats',       [SubscriptionController::class, 'stats']);


    
    Route::get('/dashboard/stats', function () {
    $pharmacies = \App\Models\Pharmacy::all();
    $subscriptions = \App\Models\Subscription::where('status','Paid')->get();
    return response()->json([
        'total_pharmacies'   => $pharmacies->count(),
        'active_pharmacies'  => $pharmacies->where('status', 'Active')->count(),
        'pending_pharmacies' => $pharmacies->where('status', 'Pending')->count(),
        'monthly_revenue'    => $subscriptions->sum('amount'),
    ]);
    
    });
    


    Route::put('/admin/profile', function (\Illuminate\Http\Request $request) {
    $admin = $request->user();

    $request->validate([
        'name'  => 'required|string|max:255',
        'email' => 'required|email|unique:admins,email,' . $admin->id,
    ]);

    $admin->update([
        'name'  => $request->name,
        'email' => $request->email,
    ]);

    if ($request->filled('password')) {
        $request->validate([
            'password' => 'min:8|confirmed',
        ]);
        $admin->update(['password' => bcrypt($request->password)]);
    }

    return response()->json([
        'message' => 'Profile updated successfully',
        'admin'   => $admin,
    ]);
});
});


Route::get('/plans', function () {
    return response()->json(\App\Models\Plan::all());
});
