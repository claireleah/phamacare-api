<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::updateOrCreate(
            ['email' => 'admin@pharmacare.com'],
            [
                'name' => 'PharmaCare Admin',
                'password' => Hash::make('ChangeMe123!'),
            ]
        );
    }
}