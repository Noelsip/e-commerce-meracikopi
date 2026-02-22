<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;

class StoreOpen
{
    /**
     * Handle an incoming request.
     * Block access when the store is closed (setting store_open !== '1').
     */
    public function handle(Request $request, Closure $next)
    {
        $isOpen = Setting::get('store_open', '1') === '1';

        if (!$isOpen) {
            // If API request, return 503 JSON
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => 'Meracikopi sedang tutup. Silakan coba lagi nanti.',
                    'store_open' => false,
                ], 503);
            }

            // For web requests, show the closed page
            return response()->view('pages.guest.store-closed', [], 503);
        }

        return $next($request);
    }
}
