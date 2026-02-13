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

        // KITA SUDAH TAHU: Prefix HMACSHA256= bikin CRASH (500). Jadi kita WAJIB RAW SIGNATURE.
        // TAPI: Raw Signature + Pipe = 401.
        // TAPI: Raw Signature + NoPipe = ??? (Belum dites pakai BRN)

        // 1. SK + UTC + NoPipe + RAW (Tanpa Separator)
        $timestamp = gmdate('Y-m-d\TH:i:s\Z');
        $stringToSign = $clientId . $timestamp;
        $signature = base64_encode(hash_hmac('sha256', $stringToSign, $secretKey, true));
        $results[] = $this->tryRequest($baseUrl, $clientId, $timestamp, $signature, $signature, "NO PIPE: ClientId+Timestamp");

        // 2. SK + UTC + Dash + RAW (Separator -)
        $timestamp = gmdate('Y-m-d\TH:i:s\Z');
        $stringToSign = $clientId . '-' . $timestamp;
        $signature = base64_encode(hash_hmac('sha256', $stringToSign, $secretKey, true));
        $results[] = $this->tryRequest($baseUrl, $clientId, $timestamp, $signature, $signature, "DASH: ClientId-Timestamp");

        // 3. SK + UTC + NewLine + RAW (Separator \n)
        $timestamp = gmdate('Y-m-d\TH:i:s\Z');
        $stringToSign = $clientId . "\n" . $timestamp;
        $signature = base64_encode(hash_hmac('sha256', $stringToSign, $secretKey, true));
        $results[] = $this->tryRequest($baseUrl, $clientId, $timestamp, $signature, $signature, "NEWLINE: ClientId\\nTimestamp");

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
