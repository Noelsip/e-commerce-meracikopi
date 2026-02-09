<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class DebugDokuCredentials extends Command
{
    protected $signature = 'doku:debug-credentials';
    protected $description = 'Debug DOKU credentials and test different combinations';

    public function handle()
    {
        $this->info("Debugging DOKU Credentials...");
        
        // Try the exact credentials from user's screenshot
        $configs = [
            [
                'name' => 'From Environment',
                'client_id' => config('doku.client_id'),
                'secret_key' => config('doku.secret_key'),
            ],
            [
                'name' => 'Direct from Screenshot', 
                'client_id' => 'BRN-0213-1769756224228',
                'secret_key' => 'SK-sY8W7ueiglN1NpSxijQQ',
            ]
        ];
        
        $baseUrl = 'https://api-sandbox.doku.com';
        
        foreach ($configs as $config) {
            $this->info("\n--- Testing: {$config['name']} ---");
            $this->line("Client ID: {$config['client_id']}");
            $this->line("Secret Key: " . substr($config['secret_key'], 0, 10) . "...");
            
            // Test multiple timestamp formats
            $timestampFormats = [
                'gmdate("c")' => gmdate('c'),
                'gmdate("Y-m-d\TH:i:s.v\Z")' => gmdate('Y-m-d\TH:i:s.v\Z'),
                'date("c")' => date('c'),
            ];
            
            foreach ($timestampFormats as $formatName => $timestamp) {
                $this->line("\nTesting timestamp format: {$formatName}");
                $this->line("Timestamp value: {$timestamp}");
                
                $signature = hash_hmac('sha256', $config['client_id'] . $timestamp, $config['secret_key']);
                
                $response = Http::withHeaders([
                    'X-CLIENT-KEY' => $config['client_id'],
                    'X-TIMESTAMP' => $timestamp,
                    'X-SIGNATURE' => "HMACSHA256=" . $signature,
                    'Content-Type' => 'application/json',
                ])->post($baseUrl . '/authorization/v1/access-token/b2b', [
                    'grantType' => 'client_credentials'
                ]);
                
                if ($response->successful()) {
                    $this->info("✅ SUCCESS with {$formatName}!");
                    $data = $response->json();
                    $this->line("Access Token: " . substr($data['accessToken'], 0, 20) . "...");
                    return; // Exit after first success
                } else {
                    $this->error("❌ Failed: " . $response->body());
                }
            }
        }
    }
}