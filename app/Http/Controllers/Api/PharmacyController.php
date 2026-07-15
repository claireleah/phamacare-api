<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PharmacyController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'email'      => 'required|email|unique:pharmacies,email',
            'phone'      => 'required|regex:/^[0-9+\s]+$/|max:20',
            'location'   => 'required|string|max:255',
            'password'   => 'required|string|min:8',
            'plan_id'       => 'required|exists:plans,id',
            'billing_cycle' => 'required|in:monthly,yearly',
        ]);

        $pharmacy = Pharmacy::create([
            'name'       => $request->name,
            'owner_name' => $request->owner_name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'location'   => $request->location,
            'password'   => Hash::make($request->password),
            'status'     => 'Pending',
            'plan_id'       => $request->plan_id,
            'billing_cycle' => $request->billing_cycle,
        ]);

        return response()->json([
            'message'  => 'Registration successful. Please wait for admin approval.',
            'pharmacy' => $pharmacy,
        ], 201);
    }


    public function login(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required|string',
    ]);

    // Step 1: Check pharmacy_users table first (staff — Pharmacist/Cashier)
    $staffMember = \App\Models\PharmacyUser::with(['pharmacy', 'role'])
        ->where('email', $request->email)
        ->first();

    if ($staffMember) {
        if (!Hash::check($request->password, $staffMember->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if ($staffMember->status === 'Inactive') {
            return response()->json(['message' => 'Your account has been deactivated. Contact your pharmacy manager.'], 403);
        }

        if ($staffMember->pharmacy->status !== 'Active') {
            return response()->json(['message' => 'Your pharmacy is not currently active on the platform.'], 403);
        }

        $token = $staffMember->createToken('pharmacy-user-token')->plainTextToken;

        return response()->json([
            'message'    => 'Login successful',
            'token'      => $token,
            'user_type'  => 'staff',
            'role'       => $staffMember->role->name,
            'pharmacy'   => $staffMember->pharmacy,
            'user'       => [
                'id'    => $staffMember->id,
                'name'  => $staffMember->name,
                'email' => $staffMember->email,
            ],
        ]);
    }

    // Step 2: Not found in staff table — check pharmacies table (Manager/owner)
    $pharmacy = Pharmacy::where('email', $request->email)->first();

    if (!$pharmacy || !Hash::check($request->password, $pharmacy->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    if ($pharmacy->status === 'Pending') {
        return response()->json(['message' => 'Your account is pending approval from the admin.'], 403);
    }

    if ($pharmacy->status === 'Suspended') {
        return response()->json(['message' => 'Your account has been suspended. Contact admin.'], 403);
    }

    $token = $pharmacy->createToken('pharmacy-token')->plainTextToken;

    return response()->json([
        'message'   => 'Login successful',
        'token'     => $token,
        'user_type' => 'manager',
        'role'      => 'Manager',
        'pharmacy'  => $pharmacy,
        'user'      => [
            'id'    => $pharmacy->id,
            'name'  => $pharmacy->owner_name,
            'email' => $pharmacy->email,
        ],
    ]);
}

    
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function index()
    {
        $pharmacies = Pharmacy::with(['subscription', 'plan'])->get();
        return response()->json($pharmacies);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Active,Pending,Suspended',
        ]);

        $pharmacy = Pharmacy::findOrFail($id);
        $pharmacy->update(['status' => $request->status]);

        return response()->json([
            'message'  => 'Pharmacy status updated',
            'pharmacy' => $pharmacy,
        ]);
    }

    public function destroy($id)
    {
        $pharmacy = Pharmacy::findOrFail($id);
        $pharmacy->delete();

        return response()->json(['message' => 'Pharmacy deleted successfully']);
    }

        // List all staff for the logged-in pharmacy (Manager only)
    public function staffIndex(Request $request)
    {
        $pharmacy = $request->user();

        if (!($pharmacy instanceof \App\Models\Pharmacy)) {
            return response()->json(['message' => 'only the pharmacy manager can view staff'], 403);
        }

        $staff = \App\Models\PharmacyUser::with('role')
            ->where('pharmacy_id', $pharmacy->id)
            ->get();

        return response()->json($staff);
    }

    // Add a new staff member (Manager only)
    public function staffStore(Request $request)
    {
        $pharmacy = $request->user();

        if (!($pharmacy instanceof \App\Models\Pharmacy)) {
            return response()->json(['message' => 'only the pharmacy manager can add staff'], 403);
        }

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:pharmacy_users,email|unique:pharmacies,email',
            'password' => 'required|string|min:8',
            'phone'    => 'required|regex:/^[0-9+\s]+$/|max:20',
            'role_id'  => 'required|exists:roles,id',
        ]);

        $staffMember = \App\Models\PharmacyUser::create([
            'pharmacy_id' => $pharmacy->id,
            'role_id'     => $request->role_id,
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'phone'       => $request->phone,
            'status'      => 'Active',
        ]);

        return response()->json([
            'message' => 'Staff member added successfully',
            'staff'   => $staffMember->load('role'),
        ], 201);
    }

    public function staffUpdate(Request $request, $id)
    {
        $pharmacy = $request->user();

        if (!($pharmacy instanceof \App\Models\Pharmacy)) {
            return response()->json(['message' => 'Only the pharmacy manager can edit staff.'], 403);
        }

        $staffMember = \App\Models\PharmacyUser::where('pharmacy_id', $pharmacy->id)->findOrFail($id);

        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:pharmacy_users,email,' . $id . '|unique:pharmacies,email',
            'role_id' => 'required|exists:roles,id',
        ]);

        $staffMember->update([
            'name'    => $request->name,
            'email'   => $request->email,
            'role_id' => $request->role_id,
        ]);

        return response()->json([
            'message' => 'Staff member updated successfully',
            'staff'   => $staffMember->load('role'),
        ]);
    }

    public function staffToggleStatus(Request $request, $id)
    {
        $pharmacy = $request->user();

        if (!($pharmacy instanceof \App\Models\Pharmacy)) {
            return response()->json(['message' => 'Only the pharmacy manager can update staff status.'], 403);
        }

        $staffMember = \App\Models\PharmacyUser::where('pharmacy_id', $pharmacy->id)->findOrFail($id);
        $staffMember->update([
            'status' => $staffMember->status === 'Active' ? 'Inactive' : 'Active',
        ]);

        return response()->json([
            'message' => 'Status updated',
            'staff'   => $staffMember->load('role'),
        ]);
    }

    public function staffDestroy(Request $request, $id)
    {
        $pharmacy = $request->user();

        if (!($pharmacy instanceof \App\Models\Pharmacy)) {
            return response()->json(['message' => 'Only the pharmacy manager can delete staff.'], 403);
        }

        $staffMember = \App\Models\PharmacyUser::where('pharmacy_id', $pharmacy->id)->findOrFail($id);
        $staffMember->delete();

        return response()->json(['message' => 'Staff member deleted successfully']);
    }
}
