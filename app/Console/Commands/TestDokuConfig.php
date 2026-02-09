<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestDokuConfig extends Command
{
    protected $signature = 'doku:test-config';
    protected $description = 'Test DOKU configuration values';

    public function handle()
    {
        $this->info("Testing DOKU Configuration...");
        
        $config = [
            'Client ID' => config('doku.client_id'),
            'Secret Key' => config('doku.secret_key') ? 'Set (Hidden)' : 'NOT SET',
            'API Key' => config('doku.api_key') ? 'Set (Hidden)' : 'NOT SET',
            'Public Key' => config('doku.public_key') ? 'Set (' . strlen(config('doku.public_key')) . ' chars)' : 'NOT SET',
            'Merchant Private Key' => config('doku.merchant_private_key') ? 'Set (' . strlen(config('doku.merchant_private_key')) . ' chars)' : 'NOT SET',
            'Merchant Public Key' => config('doku.merchant_public_key') ? 'Set (' . strlen(config('doku.merchant_public_key')) . ' chars)' : 'NOT SET',
            'Base URL' => config('doku.base_url'),
            'Is Production' => config('doku.is_production') ? 'Yes' : 'No',
        ];
        
        foreach ($config as $key => $value) {
            $status = $value === 'NOT SET' ? '❌' : '✅';
            $this->line("{$status} {$key}: {$value}");
        }
        
        // Test if public key is valid
        if (config('doku.public_key')) {
            $publicKey = config('doku.public_key');
            if (strpos($publicKey, '-----BEGIN PUBLIC KEY-----') !== false) {
                $this->info("\n✅ Public key format appears correct");
            } else {
                $this->error("\n❌ Public key format might be incorrect");
                $this->line("First 50 chars: " . substr($publicKey, 0, 50));
            }
        }
        
        // Test if private key is valid
        if (config('doku.merchant_private_key')) {
            $privateKey = config('doku.merchant_private_key');
            if (strpos($privateKey, '-----BEGIN PRIVATE KEY-----') !== false) {
                $this->info("✅ Private key format appears correct");
            } else {
                $this->error("❌ Private key format might be incorrect");
                $this->line("First 50 chars: " . substr($privateKey, 0, 50));
            }
        }
    }
}