<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestDokuAccessToken extends Command
{
    protected $signature = 'doku:test-access-token';
    protected $description = 'Test DOKU access token generation directly';

    public function handle()
    {
        $this->info("Testing DOKU Access Token Generation...");
        
        $clientId = config('doku.client_id');
        $secretKey = config('doku.secret_key');
        $baseUrl = config('doku.base_url');
        
        $this->line("Client ID: {$clientId}");
        $this->line("Base URL: {$baseUrl}");
        $this->line("Secret Key: " . (strlen($secretKey) > 0 ? 'Present (' . strlen($secretKey) . ' chars)' : 'Missing'));
        
        // Generate timestamp and signature
        $timestamp = gmdate('c');
        $this->line("Timestamp: {$timestamp}");
        
        $signature = hash_hmac('sha256', $clientId . $timestamp, $secretKey);
        $this->line("Signature: " . substr($signature, 0, 20) . "...");
        
        // Test request
        $this->info("\nSending request to DOKU...");
        
        $response = Http::withHeaders([
            'X-CLIENT-KEY' => $clientId,
            'X-TIMESTAMP' => $timestamp,
            'X-SIGNATURE' => "HMACSHA256=" . $signature,
            'Content-Type' => 'application/json',
        ])->post($baseUrl . '/authorization/v1/access-token/b2b', [
            'grantType' => 'client_credentials'
        ]);
        
        $this->line("Response Status: " . $response->status());
        
        if ($response->successful()) {
            $data = $response->json();
            $this->info("✅ Access token generated successfully!");
            $this->line("Access Token: " . substr($data['accessToken'], 0, 20) . "...");
            $this->line("Expires In: " . $data['expiresIn'] . " seconds");
        } else {
            $this->error("❌ Failed to generate access token");
            $this->error("Response: " . $response->body());
        }
    }
}