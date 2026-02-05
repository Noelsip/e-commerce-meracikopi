<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateMerchantKeys extends Command
{
    protected $signature = 'doku:generate-keys';
    
    protected $description = 'Generate RSA key pair for merchant authentication with DOKU';

    public function handle()
    {
        $this->info('🔐 Generating RSA key pair for merchant authentication...');
        
        try {
            // Generate private key
            $privateKeyResource = openssl_pkey_new([
                'digest_alg' => 'sha256',
                'private_key_bits' => 2048,
                'private_key_type' => OPENSSL_KEYTYPE_RSA,
            ]);

            if (!$privateKeyResource) {
                $this->error('❌ Failed to generate private key');
                return;
            }

            // Export private key
            openssl_pkey_export($privateKeyResource, $privateKeyPem);

            // Get public key
            $publicKeyDetails = openssl_pkey_get_details($privateKeyResource);
            $publicKeyPem = $publicKeyDetails['key'];

            // Free resources
            openssl_pkey_free($privateKeyResource);

            $this->info('✅ Keys generated successfully!');
            $this->line('');
            $this->info('📋 Copy these keys to your .env file:');
            $this->line('');
            
            $this->info('MERCHANT_PRIVATE_KEY:');
            $this->line('-------------------');
            $this->line($privateKeyPem);
            $this->line('');
            
            $this->info('MERCHANT_PUBLIC_KEY:');
            $this->line('------------------');
            $this->line($publicKeyPem);
            $this->line('');
            
            $this->warn('⚠️  Important:');
            $this->line('1. Copy the PRIVATE KEY to your .env file (keep it secret!)');
            $this->line('2. Copy the PUBLIC KEY to DOKU Dashboard > Merchant Public Key');
            $this->line('3. Never share your private key with anyone');
            
        } catch (\Exception $e) {
            $this->error('❌ Error generating keys:');
            $this->error($e->getMessage());
        }
    }
}