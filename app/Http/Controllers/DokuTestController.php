<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DokuTestController extends Controller
{
    public function testConnection()
    {
        $clientId = config('doku.client_id'); // BRN-
        $secretKey = config('doku.secret_key'); // SK-
        $baseUrl = config('doku.base_url');
        
        $results = [];

        // 1. SK + UTC + Pipe + HMAC Prefix (Standar Paling Umum)
        $timestamp = gmdate('Y-m-d\TH:i:s\Z');
        $stringToSign = $clientId . '|' . $timestamp;
        $signature = base64_encode(hash_hmac('sha256', $stringToSign, $secretKey, true));
        $results[] = $this->tryRequest($baseUrl, $clientId, $timestamp, $signature, "HMACSHA256=" . $signature, "STANDARD: SK + UTC + Pipe + Prefix");

        // 2. SK + UTC + NoPipe + HMAC Prefix (Alternatif)
        $timestamp = gmdate('Y-m-d\TH:i:s\Z');
        $stringToSign = $clientId . $timestamp;
        $signature = base64_encode(hash_hmac('sha256', $stringToSign, $secretKey, true));
        $results[] = $this->tryRequest($baseUrl, $clientId, $timestamp, $signature, "HMACSHA256=" . $signature, "ALT: SK + UTC + NoPipe + Prefix");

        // 3. SK + Offset + Pipe + HMAC Prefix (Local Time)
        $timestamp = date('c');
        $stringToSign = $clientId . '|' . $timestamp;
        $signature = base64_encode(hash_hmac('sha256', $stringToSign, $secretKey, true));
        $results[] = $this->tryRequest($baseUrl, $clientId, $timestamp, $signature, "HMACSHA256=" . $signature, "OFFSET: SK + Offset + Pipe + Prefix");

        return response()->json($results);
    }

    private function tryRequest($baseUrl, $clientId, $timestamp, $rawSign, $headerSign, $label)
    {
        try {
            $response = Http::withHeaders([
                'X-CLIENT-KEY' => $clientId,
                'X-TIMESTAMP' => $timestamp,
                'X-SIGNATURE' => $headerSign,
                'Content-Type' => 'application/json'
            ])->post($baseUrl . '/authorization/v1/access-token/b2b', [
                'grantType' => 'client_credentials'
            ]);

            return [
                'label' => $label,
                'status' => $response->status(),
                'success' => $response->successful(),
                'body' => $response->json(),
                'sent_client_id' => $clientId,
                'sent_timestamp' => $timestamp
            ];
        } catch (\Exception $e) {
            return [
                'label' => $label,
                'status' => 'EXCEPTION',
                'error' => $e->getMessage()
            ];
        }
    }
}
