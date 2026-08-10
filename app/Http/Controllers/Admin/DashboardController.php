<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use App\Models\Subscription;

class DashboardController extends Controller
{
    public function index()
    {
        $pharmacies = Pharmacy::all();
        $subscriptions = Subscription::where('status', 'Paid')->get();

        $stats = [
            'total_pharmacies'   => $pharmacies->count(),
            'active_pharmacies'  => $pharmacies->where('status', 'Active')->count(),
            'pending_pharmacies' => $pharmacies->where('status', 'Pending')->count(),
            'monthly_revenue'    => $subscriptions->sum('amount'),
        ];

        $recentPharmacies = Pharmacy::orderBy('created_at', 'desc')
            ->take(4)
            ->get()
            ->toArray();

        return view('admin.dashboard', compact('stats', 'recentPharmacies'));
    }
}