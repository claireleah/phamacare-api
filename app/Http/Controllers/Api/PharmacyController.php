<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
Use App\Models\PharmacyUser;
use App\Models\Product;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Subscription;
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
    $staffMember = PharmacyUser::with(['pharmacy', 'role'])
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

        if (!($pharmacy instanceof Pharmacy)) {
            return response()->json(['message' => 'only the pharmacy manager can view staff'], 403);
        }

        $staff = PharmacyUser::with('role')
            ->where('pharmacy_id', $pharmacy->id)
            ->get();

        return response()->json($staff);
    }

    // Add a new staff member (Manager only)
    public function staffStore(Request $request)
    {
        $pharmacy = $request->user();

        if (!($pharmacy instanceof Pharmacy)) {
            return response()->json(['message' => 'only the pharmacy manager can add staff'], 403);
        }

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:pharmacy_users,email|unique:pharmacies,email',
            'password' => 'required|string|min:8',
            'phone'    => 'required|regex:/^[0-9+\s]+$/|max:20',
            'role_id'  => 'required|exists:roles,id',
        ]);

        $staffMember = PharmacyUser::create([
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

        if (!($pharmacy instanceof Pharmacy)) {
            return response()->json(['message' => 'Only the pharmacy manager can edit staff.'], 403);
        }

        $staffMember = PharmacyUser::where('pharmacy_id', $pharmacy->id)->findOrFail($id);

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

        if (!($pharmacy instanceof Pharmacy)) {
            return response()->json(['message' => 'Only the pharmacy manager can update staff status.'], 403);
        }

        $staffMember = PharmacyUser::where('pharmacy_id', $pharmacy->id)->findOrFail($id);
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

        if (!($pharmacy instanceof Pharmacy)) {
            return response()->json(['message' => 'Only the pharmacy manager can delete staff.'], 403);
        }

        $staffMember = PharmacyUser::where('pharmacy_id', $pharmacy->id)->findOrFail($id);
        $staffMember->delete();

        return response()->json(['message' => 'Staff member deleted successfully']);
    }

    public function productIndex(Request $request)
    {
        $user = $request->user();

        $isManager = $user instanceof Pharmacy;
        $isPharmacist = $user instanceof PharmacyUser && $user->role->name === 'Pharmacist';
        
        if (!$isManager && !$isPharmacist) {
            return response()->json(['message' => 'You do not have permission to manage products.'], 403);
        }
        $pharmacy = $isManager ? $user : $user->pharmacy;


        $products = Product::where('pharmacy_id', $pharmacy->id)->get();
        return response()->json($products);
    }

    public function productStore(Request $request)
    {
        $user = $request->user();

        $isManager = $user instanceof Pharmacy;
        $isPharmacist = $user instanceof PharmacyUser && $user->role->name === 'Pharmacist';
        if (!$isManager && !$isPharmacist) {
            return response()->json(['message' => 'Only the pharmacy manager can add products.'], 403);
        }
        $pharmacy = $isManager ? $user : $user->pharmacy;

        $request->validate([
            'name'                 => 'required|string|max:255',
            'category'             => 'nullable|string|max:255',
            'price'                => 'required|integer|min:0',
            'quantity_in_stock'    => 'required|integer|min:0',
            'low_stock_threshold'  => 'required|integer|min:0',
        ]);

        $product = Product::create([
            'pharmacy_id'          => $pharmacy->id,
            'name'                 => $request->name,
            'category'             => $request->category,
            'price'                => $request->price,
            'quantity_in_stock'    => $request->quantity_in_stock,
            'low_stock_threshold'  => $request->low_stock_threshold,
            'status'               => 'Active',
        ]);

        return response()->json(['message' => 'Product added successfully', 'product' => $product], 201);
    }

    public function productUpdate(Request $request, $id)
    {
        $pharmacy = $request->user();
        if (!($pharmacy instanceof Pharmacy)) {
            return response()->json(['message' => 'Only the pharmacy manager can edit products.'], 403);
        }

        $product = Product::where('pharmacy_id', $pharmacy->id)->findOrFail($id);

        $request->validate([
            'name'                 => 'required|string|max:255',
            'category'             => 'nullable|string|max:255',
            'price'                => 'required|integer|min:0',
            'quantity_in_stock'    => 'required|integer|min:0',
            'low_stock_threshold'  => 'required|integer|min:0',
        ]);

        $product->update($request->only(['name', 'category', 'price', 'quantity_in_stock', 'low_stock_threshold']));

        return response()->json(['message' => 'Product updated successfully', 'product' => $product]);
    }

    public function productDestroy(Request $request, $id)
    {
        $pharmacy = $request->user();
        if (!($pharmacy instanceof Pharmacy)) {
            return response()->json(['message' => 'Only the pharmacy manager can delete products.'], 403);
        }

        $product = Product::where('pharmacy_id', $pharmacy->id)->findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

    public function orderIndex(Request $request)
    {
        $user = $request->user();
        $isManager = $user instanceof Pharmacy;
        $pharmacy = $isManager ? $user : $user->pharmacy;

        $orders = Order::with('items.product')
            ->where('pharmacy_id', $pharmacy->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }

    public function orderStore(Request $request)
    {
        $user = $request->user();
        $isManager = $user instanceof Pharmacy;
        $pharmacy = $isManager ? $user : $user->pharmacy;

        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_phone' => 'required|regex:/^[0-9+\s]+$/',
            'items'          => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        $total = 0;
        $itemsData = [];
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            $lineTotal = $product->price * $item['quantity'];
            $total += $lineTotal;
            $itemsData[] = [
                'product_id' => $product->id,
                'quantity'   => $item['quantity'],
                'price'      => $product->price,
            ];
        }

        $order = Order::create([
            'pharmacy_id'    => $pharmacy->id,
            'customer_name'  => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'total_amount'   => $total,
            'status'         => 'Pending',
        ]);

        foreach ($itemsData as $item) {
            $order->items()->create($item);
        }

        return response()->json(['message' => 'Order created', 'order' => $order->load('items.product')], 201);
    }

    public function orderUpdateStatus(Request $request, $id)
    {
        $user = $request->user();
        $isManager = $user instanceof Pharmacy;
        $pharmacy = $isManager ? $user : $user->pharmacy;

        $order = Order::where('pharmacy_id', $pharmacy->id)->findOrFail($id);
        $request->validate(['status' => 'required|in:Pending,Confirmed,Out for Delivery,Delivered']);
        $order->update(['status' => $request->status]);

        return response()->json(['message' => 'Status updated', 'order' => $order->load('items.product')]);
    }

    public function pharmacyDashboardStats(Request $request)
    {
        $user = $request->user();
        $isManager = $user instanceof Pharmacy;
        $pharmacy = $isManager ? $user : $user->pharmacy;

        $products = Product::where('pharmacy_id', $pharmacy->id)->get();
        $orders = Order::where('pharmacy_id', $pharmacy->id)->get();

        return response()->json([
            'total_products' => $products->count(),
            'pending_orders' => $orders->where('status', 'Pending')->count(),
            'total_riders'   => 0,
            'low_stock'      => $products->filter(fn($p) => $p->quantity_in_stock <= $p->low_stock_threshold)->count(),
        ]);
    }


    public function pharmacyUpdate(Request $request)
    {
        $user = $request->user();

        if (!($user instanceof Pharmacy)) {
            return response()->json(['message' => 'Only the pharmacy manager can update pharmacy settings.'], 403);
        }

        $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'required|regex:/^[0-9+\s]+$/',
            'location' => 'required|string|max:255',
        ]);

        $user->update([
            'name'     => $request->name,
            'phone'    => $request->phone,
            'location' => $request->location,
        ]);

        return response()->json(['message' => 'Pharmacy details updated successfully', 'pharmacy' => $user]);
    }

    public function salesSummary(Request $request)
    {
        $user = $request->user();
        $isManager = $user instanceof Pharmacy;
        $pharmacy = $isManager ? $user : $user->pharmacy;

        $delivered = Order::where('pharmacy_id', $pharmacy->id)
            ->where('status', 'Delivered');

        $today = (clone $delivered)->whereDate('created_at', now()->toDateString())->sum('total_amount');
        $todayCount = (clone $delivered)->whereDate('created_at', now()->toDateString())->count();

        $week = (clone $delivered)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('total_amount');
        $weekCount = (clone $delivered)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();

        $month = (clone $delivered)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('total_amount');
        $monthCount = (clone $delivered)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();

        return response()->json([
            'today'      => ['total' => $today, 'orders' => $todayCount],
            'this_week'  => ['total' => $week, 'orders' => $weekCount],
            'this_month' => ['total' => $month, 'orders' => $monthCount],
        ]);
    }


    public function billingInfo(Request $request)
    {
        $user = $request->user();
        if (!($user instanceof Pharmacy)) {
            return response()->json(['message' => 'Only the pharmacy manager can view billing.'], 403);
        }

        $plan = Plan::find($user->plan_id);
        $subscription = Subscription::where('pharmacy_id', $user->id)->first();

        return response()->json([
            'plan'          => $plan,
            'billing_cycle' => $user->billing_cycle,
            'subscription'  => $subscription,
        ]);
    }

    
}
