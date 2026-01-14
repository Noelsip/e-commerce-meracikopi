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
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Check if user exists and is admin
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Invalid credentials'
                ], 401);
            }
            return back()->withErrors([
                'email' => 'Email atau Password Salah'
            ])->withInput();
        }

        if ($user->role !== 'admin') {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Unauthorized. Admin access only.'
                ], 403);
            }
            return back()->withErrors([
                'email' => 'Anda Tidak Memiliki Akses Admin'
            ])->withInput();
        }

        // Attempt login with web guard
        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Email atau Password Salah'
            ])->withInput();
        }

        // For API requests - return token
        if ($request->expectsJson() || $request->is('api/*')) {
            $token = $user->createToken('admin-token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
            ], 200);
        }

        // For web requests
        Auth::login($user);
        $request->session()->regenerate();
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