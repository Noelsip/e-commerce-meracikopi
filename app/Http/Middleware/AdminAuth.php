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
        // Untuk API request, cek Sanctum token
        if ($request->is('api/*')) {
            $user = Auth::guard('sanctum')->user();
            
            if (!$user) {
                return response()->json([
                    'message' => 'Unauthenticated. Please login first.'
                ], 401);
            }
            
            if ($user->role !== 'admin') {
                return response()->json([
                    'message' => 'Unauthorized. Admin access only.'
                ], 403);
            }
            
            // Set authenticated user
            Auth::setUser($user);
            return $next($request);
        }

        // Untuk web request, cek session
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.login')
                ->with('error', 'Anda tidak memiliki akses admin.');
        }

        return $next($request);
    }
}