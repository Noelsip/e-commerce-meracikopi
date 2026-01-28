<?php

namespace App\Services;

use Midtrans\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    public static function init(): void
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = config('midtrans.is_3ds', true);
        
        // Set CURL options
        Config::$curlOptions = [
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_CONNECTTIMEOUT => 30,
            // Force IPv4 to avoid IPv6 connection issues on some networks
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        ];
    }

    /**
     * Get Snap token using Laravel HTTP client (bypass Midtrans library CURL issues)
     */
    public static function getSnapToken(array $payload): string
    {
        $serverKey = config('midtrans.server_key');
        $isProduction = config('midtrans.is_production', false);
        
        $baseUrl = $isProduction 
            ? 'https://app.midtrans.com' 
            : 'https://app.sandbox.midtrans.com';
        
        $url = $baseUrl . '/snap/v1/transactions';
        
        $response = Http::withOptions([
            'force_ip_resolve' => 'v4', // Force IPv4
            'timeout' => 60,
            'connect_timeout' => 30,
        ])
        ->withBasicAuth($serverKey, '')
        ->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])
        ->post($url, $payload);
        
        if (!$response->successful()) {
            Log::error('Midtrans API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'payload' => $payload,
            ]);
            throw new \Exception('Midtrans API Error: ' . $response->body());
        }
        
        $data = $response->json();
        
        if (!isset($data['token'])) {
            Log::error('Midtrans missing token', [
                'response' => $data,
                'payload' => $payload,
            ]);
            throw new \Exception('Midtrans did not return a token');
        }
        
        return $data['token'];
    }
}