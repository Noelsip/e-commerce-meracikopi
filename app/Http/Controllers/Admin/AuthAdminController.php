<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthAdminController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    // Menerima Input User
    public function login(Request $request)
    {
        $creadentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!Auth::guard('admin')->attempt($creadentials)) {
            return back()->withErrors([
                'email' => 'Email atau Password Salah'
            ])->withInput();
        }

        if ($user->role !== 'admin') {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Anda Tidak Memiliki Akses Admin'
                ], 403);
            }
            return back()->withErrors([
                'email' => 'Anda Tidak Memiliki Akses'
            ])->withInput();
        }

        Auth::login($user);
        $request->session()->regenerate();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Login Successful',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ]
            ], 200);
        }

        return redirect()->route('admin.dashboard');
    }

    // Logout Admin
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Logout Successful'
            ], 200);
        }

        return redirect()->route('admin.login');
    }
}