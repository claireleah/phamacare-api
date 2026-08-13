<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rider;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class RiderController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:riders,email',
            'phone'    => 'required|string',
            'password' => 'required|min:6',
            'license_plate'  => 'required|string|max:20',
        ]);

        $rider = Rider::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'status'   => 'Pending',
            'license_plate' => $request->license_plate,
        ]);


        return response()->json([
            'message' => 'Registration successful. Please wait for admin approval.',
            'rider' => $rider,
            ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $rider = Rider::where('email', $request->email)->first();

        if (!$rider || !Hash::check($request->password, $rider->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if ($rider->status === 'Pending') {
            return response()->json(['message' => 'Your account is pending approval from the admin.'], 403);
        }

        if ($rider->status === 'Suspended') {
            return response()->json(['message' => 'Your account has been suspended. Please contact support.'], 403);
        }

        $token = $rider->createToken('rider-token')->plainTextToken;

        return response()->json(['rider' => $rider, 'token' => $token]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    // Orders ready for pickup, not yet assigned to any rider
    public function availableOrders()
    {
        $orders = Order::with(['items.product', 'pharmacy'])
            ->whereNull('rider_id')
            ->where('status', 'Confirmed')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }

    // Rider accepts an order
    public function acceptOrder(Request $request, $id)
    {
        $rider = $request->user();

        $order = Order::whereNull('rider_id')->findOrFail($id);
        $order->update([
            'rider_id' => $rider->id,
            'status'   => 'Out for Delivery',
        ]);

        return response()->json(['message' => 'Order accepted', 'order' => $order->load('items.product', 'pharmacy')]);
    }

    // Orders currently assigned to this rider (active + history)
    public function myOrders(Request $request)
    {
        $rider = $request->user();

        $orders = Order::with(['items.product', 'pharmacy'])
            ->where('rider_id', $rider->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }

    // Mark an order as Delivered
    public function markDelivered(Request $request, $id)
    {
        $rider = $request->user();

        $order = Order::where('rider_id', $rider->id)->findOrFail($id);
        $order->update(['status' => 'Delivered']);

        return response()->json(['message' => 'Order marked as delivered', 'order' => $order]);
    }


    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $rider = Rider::where('email', $request->email)->first();

        if (!$rider) {
            return response()->json(['message' => 'If that email exists, a reset code has been sent.']);
        }

        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        $rider->update([
            'reset_code' => $code,
            'reset_code_expires_at' => now()->addMinutes(15),
        ]);

        Mail::raw("Your PharmaCare Rider password reset code is: {$code}\n\nThis code expires in 15 minutes.", function ($message) use ($rider) {
            $message->to($rider->email)
                    ->subject('PharmaCare Rider - Password Reset Code');
        });

        return response()->json(['message' => 'If that email exists, a reset code has been sent.']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $rider = Rider::where('email', $request->email)->first();

        if (!$rider || $rider->reset_code !== $request->code || now()->greaterThan($rider->reset_code_expires_at)) {
            return response()->json(['message' => 'Invalid or expired reset code.'], 400);
        }

        $rider->update([
            'password' => Hash::make($request->password),
            'reset_code' => null,
            'reset_code_expires_at' => null,
        ]);

        return response()->json(['message' => 'Password has been reset successfully.']);
    }

    public function updateProfile(Request $request)
    {
        $rider = $request->user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'required|string',
        ]);

        $rider->update([
            'name'  => $request->name,
            'phone' => $request->phone,
        ]);

        return response()->json([
            'message' => 'Profile updated successfully',
            'rider'   => $rider,
        ]);
    }
}