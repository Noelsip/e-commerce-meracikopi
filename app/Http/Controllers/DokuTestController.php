<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DokuTestController extends Controller
{
    public function testConnection()
    {
        $configs = [
            'merchant_id' => config('doku.client_id'), // BRN-...
            'api_key' => config('doku.api_key'),       // doku_key_...
        ];
        
        $secretKey = config('doku.secret_key');
        $baseUrl = config('doku.base_url');
        
        $results = [];

        foreach ($configs as $keyType => $clientId) {
            // Test 1: Format Standar (With Pipe)
            $timestamp = date('c');
            $stringToSign = $clientId . '|' . $timestamp;
            $signature = base64_encode(hash_hmac('sha256', $stringToSign, $secretKey, true));
            $results[] = $this->tryRequest($baseUrl, $clientId, $timestamp, $signature, "HMACSHA256=" . $signature, "$keyType + Pipe + HMAC Prefix");

            // Test 2: Format Tanpa Pipe
            $timestamp = date('c');
            $stringToSign = $clientId . $timestamp;
            $signature = base64_encode(hash_hmac('sha256', $stringToSign, $secretKey, true));
            $results[] = $this->tryRequest($baseUrl, $clientId, $timestamp, $signature, "HMACSHA256=" . $signature, "$keyType + NoPipe + HMAC Prefix");

            // Test 3: Standard With Pipe but NO HMAC Prefix
            $timestamp = date('c');
            $stringToSign = $clientId . '|' . $timestamp;
            $signature = base64_encode(hash_hmac('sha256', $stringToSign, $secretKey, true));
            $results[] = $this->tryRequest($baseUrl, $clientId, $timestamp, $signature, $signature, "$keyType + Pipe + NO Prefix");
        }

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
