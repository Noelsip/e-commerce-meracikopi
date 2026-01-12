<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login dan role admin
        if (!Auth::check()) {
            // Jika request mengharapkan JSON (API), return JSON response
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Unauthenticated. Please login first.'
                ], 401);
            }
            
            // Jika web request, redirect ke login page
            return redirect()->route('admin.login');
        }

        // Cek role admin
        if (Auth::user()->role !== 'admin') {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Unauthorized. Admin access only.'
                ], 403);
            }
            
            return redirect()->route('admin.login')
                ->with('error', 'Anda tidak memiliki akses admin.');
        }

        return $next($request);
    }
}