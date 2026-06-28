<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Pharmacy;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    // Get all subscriptions
    public function index()
    {
        $subscriptions = Subscription::with('pharmacy')->get();
        return response()->json($subscriptions);
    }

    // Update subscription status
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Paid,Overdue,Cancelled',
        ]);

        $subscription = Subscription::findOrFail($id);
        $subscription->update(['status' => $request->status]);

        return response()->json([
            'message'      => 'Subscription status updated',
            'subscription' => $subscription,
        ]);
    }

    // Get subscription revenue stats
    public function stats()
    {
        $total    = Subscription::sum('amount');
        $active   = Subscription::where('status', 'Paid')->count();
        $overdue  = Subscription::where('status', 'Overdue')->count();
        $monthly  = Subscription::where('status', 'Paid')->sum('amount');

        return response()->json([
            'total_revenue'     => $total,
            'active_pharmacies' => $active,
            'overdue'           => $overdue,
            'monthly_revenue'   => $monthly,
        ]);
    }
}