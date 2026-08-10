<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    public function index()
    {
        $pharmacies = Pharmacy::with(['subscription', 'plan'])->get()->toArray();

        $stats = [
            'total'     => count($pharmacies),
            'active'    => count(array_filter($pharmacies, fn($p) => $p['status'] === 'Active')),
            'pending'   => count(array_filter($pharmacies, fn($p) => $p['status'] === 'Pending')),
            'suspended' => count(array_filter($pharmacies, fn($p) => $p['status'] === 'Suspended')),
        ];

        return view('admin.pharmacies', compact('pharmacies', 'stats'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Active,Pending,Suspended',
        ]);

        $pharmacy = Pharmacy::findOrFail($id);
        $pharmacy->update(['status' => $request->status]);

        return back()->with('success', 'Pharmacy status updated');
    }

    public function destroy($id)
    {
        $pharmacy = Pharmacy::findOrFail($id);
        $pharmacy->delete();

        return back()->with('success', 'Pharmacy deleted');
    }

    public function export()
    {
        $pharmacies = Pharmacy::all()->toArray();

        $filename = 'pharmacies_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($pharmacies) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'Owner', 'Email', 'Phone', 'Location', 'Status', 'Joined']);

            foreach ($pharmacies as $p) {
                fputcsv($file, [
                    $p['name'], $p['owner_name'], $p['email'], $p['phone'],
                    $p['location'], $p['status'], $p['created_at'],
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}