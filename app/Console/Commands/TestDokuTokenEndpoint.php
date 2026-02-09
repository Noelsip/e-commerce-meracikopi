<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestDokuTokenEndpoint extends Command
{
    protected $signature = 'doku:test-token';
    
    protected $description = 'Test DOKU token endpoint';

    public function handle()
    {
        $this->info('Testing DOKU Token Endpoint...');
        
        try {
            $baseUrl = config('app.url');
            $clientId = config('doku.client_id');
            
            $this->info("Testing URL: {$baseUrl}/api/doku/token");
            $this->info("Client ID: {$clientId}");
            
            $response = Http::withHeaders([
                'X-CLIENT-KEY' => $clientId,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post($baseUrl . '/api/doku/token');
            
            $this->info('Response Status: ' . $response->status());
            
            if ($response->successful()) {
                $data = $response->json();
                $this->info('✅ Token endpoint working!');
                $this->line('Access Token: ' . substr($data['access_token'], 0, 20) . '...');
                $this->line('Token Type: ' . $data['token_type']);
                $this->line('Expires In: ' . $data['expires_in'] . ' seconds');
            } else {
                $this->error('❌ Token endpoint failed!');
                $this->error('Response: ' . $response->body());
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Test failed:');
            $this->error($e->getMessage());
        }
    }
}