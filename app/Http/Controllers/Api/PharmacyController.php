<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PharmacyController extends Controller
{
    // Public — pharmacy registers itself
    public function register(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'email'      => 'required|email|unique:pharmacies,email',
            'phone'      => 'required|string|max:20',
            'location'   => 'required|string|max:255',
            'password'   => 'required|string|min:8',
        ]);

        $pharmacy = Pharmacy::create([
            'name'       => $request->name,
            'owner_name' => $request->owner_name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'location'   => $request->location,
            'password'   => Hash::make($request->password),
            'status'     => 'Pending',
        ]);

        return response()->json([
            'message'  => 'Registration successful. Please wait for admin approval.',
            'pharmacy' => $pharmacy,
        ], 201);
    }

    // Public — pharmacy logs in
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

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
            'message'  => 'Login successful',
            'token'    => $token,
            'pharmacy' => $pharmacy,
        ]);
    }

    // Protected — get all pharmacies (admin only)
    public function index()
    {
        $pharmacies = Pharmacy::with('subscription')->get();
        return response()->json($pharmacies);
    }

    // Protected — update pharmacy status (admin only)
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

    // Protected — delete pharmacy (admin only)
    public function destroy($id)
    {
        $pharmacy = Pharmacy::findOrFail($id);
        $pharmacy->delete();

        return response()->json(['message' => 'Pharmacy deleted successfully']);
    }
}
