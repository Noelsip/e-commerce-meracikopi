<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GuestTokenMiddleware
{
    
    public function handle(Request $request, Closure $next)
    {
        // Mengambil token dari header
        $guestToken = $request->header('X-GUEST-TOKEN');

        // Buat token jika belum ada
        if (!$guestToken){
            $guestToken = (string) Str::uuid();
        }

        // Menyimpan token ke req untuk dipakai controller
        $request->attributes->set('guest_token', $guestToken);

        $response = $next($request);

        $response->headers->set('X-GUEST-TOKEN', $guestToken);

        return $response;
    }
}
