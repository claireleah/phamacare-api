<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = [
            [
                'pharmacy'     => 'HealthPlus Pharmacy',
                'location'     => 'Kampala',
                'amount'       => 200000,
                'start_date'   => 'Jan 1, 2026',
                'next_billing' => 'Jul 1, 2026',
                'status'       => 'Active',
                'paid_months'  => 6,
            ],
            [
                'pharmacy'     => 'Life Pharmacy',
                'location'     => 'Jinja',
                'amount'       => 200000,
                'start_date'   => 'Feb 1, 2026',
                'next_billing' => 'Jul 1, 2026',
                'status'       => 'Active',
                'paid_months'  => 5,
            ],
            [
                'pharmacy'     => 'MedPlus Pharmacy',
                'location'     => 'Mukono',
                'amount'       => 200000,
                'start_date'   => 'Mar 1, 2026',
                'next_billing' => 'Jun 1, 2026',
                'status'       => 'Overdue',
                'paid_months'  => 3,
            ],
            [
                'pharmacy'     => 'Care Pharmacy',
                'location'     => 'Entebbe',
                'amount'       => 200000,
                'start_date'   => 'Mar 1, 2026',
                'next_billing' => 'Jul 1, 2026',
                'status'       => 'Active',
                'paid_months'  => 4,
            ],
            [
                'pharmacy'     => 'QuickMed Pharmacy',
                'location'     => 'Mbarara',
                'amount'       => 200000,
                'start_date'   => 'Apr 1, 2026',
                'next_billing' => 'Jun 1, 2026',
                'status'       => 'Overdue',
                'paid_months'  => 2,
            ],
            [
                'pharmacy'     => 'Sunrise Pharmacy',
                'location'     => 'Gulu',
                'amount'       => 200000,
                'start_date'   => 'May 1, 2026',
                'next_billing' => '—',
                'status'       => 'Cancelled',
                'paid_months'  => 1,
            ],
        ];

        $stats = [
            'total'          => count($subscriptions),
            'active'         => count(array_filter($subscriptions, fn($s) => $s['status'] === 'Active')),
            'overdue'        => count(array_filter($subscriptions, fn($s) => $s['status'] === 'Overdue')),
            'monthly_revenue'=> array_sum(
                array_map(
                    fn($s) => $s['status'] === 'Active' ? $s['amount'] : 0,
                    $subscriptions
                )
            ),
        ];

        return view('admin.subscriptions', compact('subscriptions', 'stats'));
    }
}
