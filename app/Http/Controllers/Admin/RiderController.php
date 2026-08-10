<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class RiderController extends Controller
{
    public function index()
    {
        // Later this will come from the database
        $riders = [
            [
                'id'        => 1,
                'name'      => 'Peter Kamau',
                'phone'     => '+256 700 111222',
                'email'     => 'peter.kamau@gmail.com',
                'pharmacy'  => 'HealthPlus Pharmacy',
                'location'  => 'Kampala',
                'deliveries'=> 142,
                'earnings'  => 1420000,
                'status'    => 'Active',
                'joined'    => 'Jan 15, 2026',
            ],
            [
                'id'        => 2,
                'name'      => 'Brian Ssekajugo',
                'phone'     => '+256 700 333444',
                'email'     => 'brian.s@gmail.com',
                'pharmacy'  => 'Care Pharmacy',
                'location'  => 'Entebbe',
                'deliveries'=> 89,
                'earnings'  => 890000,
                'status'    => 'Active',
                'joined'    => 'Feb 10, 2026',
            ],
            [
                'id'        => 3,
                'name'      => 'Joseph Ochieng',
                'phone'     => '+256 700 555666',
                'email'     => 'joseph.o@gmail.com',
                'pharmacy'  => 'Life Pharmacy',
                'location'  => 'Jinja',
                'deliveries'=> 34,
                'earnings'  => 340000,
                'status'    => 'Inactive',
                'joined'    => 'Mar 5, 2026',
            ],
            [
                'id'        => 4,
                'name'      => 'Moses Wafula',
                'phone'     => '+256 700 777888',
                'email'     => 'moses.w@gmail.com',
                'pharmacy'  => 'MedPlus Pharmacy',
                'location'  => 'Mukono',
                'deliveries'=> 67,
                'earnings'  => 670000,
                'status'    => 'Active',
                'joined'    => 'Mar 20, 2026',
            ],
            [
                'id'        => 5,
                'name'      => 'Grace Nakato',
                'phone'     => '+256 700 999000',
                'email'     => 'grace.n@gmail.com',
                'pharmacy'  => 'HealthPlus Pharmacy',
                'location'  => 'Kampala',
                'deliveries'=> 210,
                'earnings'  => 2100000,
                'status'    => 'Active',
                'joined'    => 'Jan 3, 2026',
            ],
            [
                'id'        => 6,
                'name'      => 'Allan Tumwine',
                'phone'     => '+256 701 123456',
                'email'     => 'allan.t@gmail.com',
                'pharmacy'  => 'QuickMed Pharmacy',
                'location'  => 'Mbarara',
                'deliveries'=> 5,
                'earnings'  => 50000,
                'status'    => 'Suspended',
                'joined'    => 'Apr 12, 2026',
            ],
        ];

        $stats = [
            'total'     => count($riders),
            'active'    => count(array_filter($riders, fn($r) => $r['status'] === 'Active')),
            'inactive'  => count(array_filter($riders, fn($r) => $r['status'] === 'Inactive')),
            'suspended' => count(array_filter($riders, fn($r) => $r['status'] === 'Suspended')),
        ];

        return view('admin.riders', compact('riders', 'stats'));
    }
}
