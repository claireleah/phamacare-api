<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return back()->withErrors([
                'email' => 'Invalid email or password'
            ]);
        }

        $token = $admin->createToken('admin-token')->plainTextToken;

        session(['admin_token' => $token]);
        session(['admin_name'  => $admin->name]);
        session(['admin_email' => $admin->email]);

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        $token = session('admin_token');

        if ($token) {
            // Find and delete the token directly, since we're not making an HTTP request anymore
            $tokenId = explode('|', $token)[0];
            \Laravel\Sanctum\PersonalAccessToken::find($tokenId)?->delete();
        }

        session()->flush();
        return redirect()->route('admin.login');
    }
}