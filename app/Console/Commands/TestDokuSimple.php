<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestDokuSimple extends Command
{
    protected $signature = 'doku:test-simple';
    protected $description = 'Test DOKU with simplest possible approach';

    public function handle()
    {
        $this->info("Testing DOKU with simplest approach...");
        
        $clientId = config('doku.client_id');
        $secretKey = config('doku.secret_key');
        
        // Coba beberapa format timestamp
        $timestampFormats = [
            'ISO basic' => gmdate('Y-m-d\TH:i:s\Z'),
            'ISO with ms' => gmdate('Y-m-d\TH:i:s.000\Z'), 
            'Simple ISO' => date('c'),
            'Unix epoch' => time(),
        ];
        
        foreach ($timestampFormats as $name => $timestamp) {
            $this->line("\n--- Testing {$name}: {$timestamp} ---");
            
            $signature = hash_hmac('sha256', $clientId . $timestamp, $secretKey);
            
            $response = Http::timeout(30)->withHeaders([
                'X-CLIENT-KEY' => $clientId,
                'X-TIMESTAMP' => $timestamp,
                'X-SIGNATURE' => "HMACSHA256=" . $signature,
                'Content-Type' => 'application/json',
            ])->post('https://api-sandbox.doku.com/authorization/v1/access-token/b2b', [
                'grantType' => 'client_credentials'
            ]);
            
            $this->line("Status: " . $response->status());
            
            if ($response->successful()) {
                $this->info("✅ Success with {$name}!");
                $data = $response->json();
                $this->line("Access Token: " . substr($data['accessToken'] ?? 'none', 0, 20) . "...");
                break;
            } else {
                $this->error("❌ Failed: " . $response->body());
            }
        }
        
        // Jika semua gagal, coba dengan credentials yang berbeda
        $this->line("\n--- Testing with alternative approach ---");
        
        // Mungkin DOKU mengharapkan format yang berbeda
        $timestamp = gmdate('Y-m-d H:i:s');
        $signature = hash_hmac('sha256', $clientId . $timestamp, $secretKey);
        
        $response = Http::timeout(30)->withHeaders([
            'CLIENT-KEY' => $clientId,  // Coba tanpa X-
            'TIMESTAMP' => $timestamp,  // Coba tanpa X-
            'SIGNATURE' => "HMACSHA256=" . $signature,
            'Content-Type' => 'application/json',
        ])->post('https://api-sandbox.doku.com/authorization/v1/access-token/b2b', [
            'grantType' => 'client_credentials'
        ]);
        
        $this->line("Alternative Status: " . $response->status());
        $this->line("Alternative Response: " . $response->body());
    }
}