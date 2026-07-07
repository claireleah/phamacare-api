<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('plans')->insert([
            [
                'name'             => 'Basic',
                'monthly_price'    => 100000,
                'yearly_price'     => 1000000,
                'max_products'     => 50,
                'max_riders'       => 2,
                'stock_alerts'     => false,
                'sales_reports'    => false,
                'priority_support' => false,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 'Standard',
                'monthly_price'    => 200000,
                'yearly_price'     => 2000000,
                'max_products'     => 200,
                'max_riders'       => 5,
                'stock_alerts'     => true,
                'sales_reports'    => false,
                'priority_support' => false,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 'Premium',
                'monthly_price'    => 350000,
                'yearly_price'     => 3500000,
                'max_products'     => 0,
                'max_riders'       => 0,
                'stock_alerts'     => true,
                'sales_reports'    => true,
                'priority_support' => true,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
        ]);
    }
}