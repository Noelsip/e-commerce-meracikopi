<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DokuTestController extends Controller
{
    public function testConnection()
    {
        $clientId = config('doku.client_id'); // BRN-...
        $secretKey = config('doku.secret_key'); // SK-...
        $apiKey = config('doku.api_key'); // doku_key_...
        $baseUrl = config('doku.base_url');
        
        $results = [];

        // KITA SUDAH TAHU: Client ID = BRN, Header = Raw (Tanpa Prefix)
        // VARIABEL YANG DITES: Timestamp Format & Signing Key

        // --- SKENARIO A: Pakai SECRET KEY (SK-...) sebagai Kunci ---
        
        // A.1: UTC Time + Pipa
        $timestamp = gmdate('Y-m-d\TH:i:s\Z');
        $stringToSign = $clientId . '|' . $timestamp;
        $signature = base64_encode(hash_hmac('sha256', $stringToSign, $secretKey, true));
        $results[] = $this->tryRequest($baseUrl, $clientId, $timestamp, $signature, $signature, "SK + UTC + Pipe");

        // A.2: Offset Time (WIB) + Pipa
        $timestamp = date('c');
        $stringToSign = $clientId . '|' . $timestamp;
        $signature = base64_encode(hash_hmac('sha256', $stringToSign, $secretKey, true));
        $results[] = $this->tryRequest($baseUrl, $clientId, $timestamp, $signature, $signature, "SK + Offset + Pipe");


        // --- SKENARIO B: Pakai API KEY (doku_key_...) sebagai Kunci (Siapa tahu ini kuncinya) ---

        // B.1: UTC Time + Pipa
        $timestamp = gmdate('Y-m-d\TH:i:s\Z');
        $stringToSign = $clientId . '|' . $timestamp;
        $signature = base64_encode(hash_hmac('sha256', $stringToSign, $apiKey, true));
        $results[] = $this->tryRequest($baseUrl, $clientId, $timestamp, $signature, $signature, "APIKEY + UTC + Pipe");

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
