<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;

class ReportController extends Controller
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

        return view('admin.reports', compact('stats', 'pharmacies'));
    }
}