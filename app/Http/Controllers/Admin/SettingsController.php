<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SettingsController extends Controller
{
    private $apiUrl = 'http://localhost:8001/api';

    public function index()
    {
        return view('admin.settings');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        $payload = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:8|confirmed',
            ]);
            $payload['password']              = $request->password;
            $payload['password_confirmation'] = $request->password_confirmation;
        }

        $response = Http::withToken(session('admin_token'))
            ->put("{$this->apiUrl}/admin/profile", $payload);

        if ($response->successful()) {
            $admin = $response->json('admin');
            session([
                'admin_name'  => $admin['name'],
                'admin_email' => $admin['email'],
            ]);
            return back()->with('success', 'Profile updated successfully');
        }

        return back()->withErrors(['error' => 'Failed to update profile']);
    }
}