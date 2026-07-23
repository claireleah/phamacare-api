<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Pharmacy;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:customers,email',
            'phone'    => 'required|regex:/^[0-9+\s]+$/',
            'password' => 'required|string|min:8',
        ]);

        $customer = Customer::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        $token = $customer->createToken('customer-token')->plainTextToken;

        return response()->json([
            'message'  => 'Registration successful',
            'token'    => $token,
            'customer' => $customer,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $customer = Customer::where('email', $request->email)->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $customer->createToken('customer-token')->plainTextToken;

        return response()->json([
            'message'  => 'Login successful',
            'token'    => $token,
            'customer' => $customer,
        ]);
    }


    public function browsePharmacies()
    {
        $pharmacies = Pharmacy::where('status', 'Active')->get();
        return response()->json($pharmacies);
    }

    public function pharmacyProducts($id)
    {
        $pharmacy = Pharmacy::where('status', 'Active')->findOrFail($id);
        $products = Product::where('pharmacy_id', $pharmacy->id)
            ->where('status', 'Active')
            ->get();

        return response()->json([
            'pharmacy' => $pharmacy,
            'products' => $products,
        ]);
    }

    public function placeOrder(Request $request)
    {
        $customer = $request->user();

        $request->validate([
            'pharmacy_id' => 'required|exists:pharmacies,id',
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
            'pharmacy_id'    => $request->pharmacy_id,
            'customer_id'    => $customer->id,
            'customer_name'  => $customer->name,
            'customer_phone' => $customer->phone,
            'total_amount'   => $total,
            'status'         => 'Pending',
        ]);

        foreach ($itemsData as $item) {
            $order->items()->create($item);
        }

        return response()->json(['message' => 'Order placed successfully', 'order' => $order->load('items.product')], 201);
    }

    public function myOrders(Request $request)
    {
        $customer = $request->user();

        $orders = Order::with('items.product', 'pharmacy')
            ->where('customer_id', $customer->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }

    public function orderDetail(Request $request, $id)
    {
        $customer = $request->user();

        $order = Order::with('items.product', 'pharmacy')
            ->where('customer_id', $customer->id)
            ->findOrFail($id);

        return response()->json($order);
    }
}