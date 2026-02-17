<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DokuTestController extends Controller
{
    public function testConnection()
    {
        $clientId = trim(config('doku.client_id'));
        $baseUrl = trim(config('doku.base_url'));
        $privateKeyPem = config('doku.merchant_private_key');
        
        $results = [];

        // Cek Private Key dulu
        $privateKey = openssl_pkey_get_private($privateKeyPem);
        if (!$privateKey) {
            return response()->json([
                'error' => 'Private Key tidak valid!',
                'openssl_error' => openssl_error_string(),
                'key_length' => strlen($privateKeyPem),
                'key_preview' => substr($privateKeyPem, 0, 50) . '...',
            ]);
        }

        // TEST: SHA256withRSA Signature (Standar DOKU SNAP B2B)
        $timestamp = gmdate('Y-m-d\TH:i:s\Z');
        $stringToSign = $clientId . '|' . $timestamp;
        
        openssl_sign($stringToSign, $signatureBinary, $privateKey, OPENSSL_ALGO_SHA256);
        $signature = base64_encode($signatureBinary);

        $results[] = $this->tryRequest($baseUrl, $clientId, $timestamp, $signature, $signature, "RSA: SHA256withRSA + Pipe + UTC");

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
