<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rider;
use Illuminate\Http\Request;

class RiderController extends Controller
{
    public function index()
    {
        $riders = Rider::all()->toArray();

        $stats = [
            'total'     => count($riders),
            'pending'   => count(array_filter($riders, fn($r) => $r['status'] === 'Pending')),
            'active'    => count(array_filter($riders, fn($r) => $r['status'] === 'Active')),
            'suspended' => count(array_filter($riders, fn($r) => $r['status'] === 'Suspended')),
        ];

        return view('admin.riders', compact('riders', 'stats'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Active,Pending,Suspended',
        ]);

        $rider = Rider::findOrFail($id);
        $rider->update(['status' => $request->status]);

        return back()->with('success', 'Rider status updated');
    }
}