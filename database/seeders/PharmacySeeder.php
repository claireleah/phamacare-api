<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pharmacy;
use App\Models\Product;
use App\Models\Plan;
use Illuminate\Support\Facades\Hash;

class PharmacySeeder extends Seeder
{
    public function run()
    {
        $plan = Plan::first();

        $pharmacy1 = Pharmacy::updateOrCreate(
            ['email' => 'kaawa.pharmacy@pharmacare.com'],
            [
                'name' => 'Kaawa Pharmacy',
                'owner_name' => 'John Kaawa',
                'phone' => '0700111222',
                'location' => 'Kampala, Ntinda',
                'password' => Hash::make('password123'),
                'status' => 'Active',
                'plan_id' => $plan?->id,
                'billing_cycle' => 'monthly',
            ]
        );

        $pharmacy2 = Pharmacy::updateOrCreate(
            ['email' => 'zuri.pharmacy@pharmacare.com'],
            [
                'name' => 'Zuri Pharmacy',
                'owner_name' => 'Grace Zuri',
                'phone' => '0700333444',
                'location' => 'Entebbe, Main Street',
                'password' => Hash::make('password123'),
                'status' => 'Active',
                'plan_id' => $plan?->id,
                'billing_cycle' => 'monthly',
            ]
        );

        $products = [
            ['name' => 'Paracetamol 500mg', 'category' => 'Pain Relief', 'price' => 500, 'quantity_in_stock' => 100, 'low_stock_threshold' => 10],
            ['name' => 'Amoxicillin 500mg', 'category' => 'Antibiotics', 'price' => 5000, 'quantity_in_stock' => 50, 'low_stock_threshold' => 5],
            ['name' => 'Vitamin C 500mg', 'category' => 'Supplements', 'price' => 3000, 'quantity_in_stock' => 80, 'low_stock_threshold' => 10],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['pharmacy_id' => $pharmacy1->id, 'name' => $product['name']],
                array_merge($product, ['pharmacy_id' => $pharmacy1->id, 'status' => 'Active'])
            );
            Product::updateOrCreate(
                ['pharmacy_id' => $pharmacy2->id, 'name' => $product['name']],
                array_merge($product, ['pharmacy_id' => $pharmacy2->id, 'status' => 'Active'])
            );
        }
    }
}