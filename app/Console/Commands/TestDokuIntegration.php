<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DokuService;
use Illuminate\Support\Facades\Log;

class TestDokuIntegration extends Command
{
    protected $signature = 'doku:test {--method=qris} {--amount=10000}';
    
    protected $description = 'Test DOKU payment integration with specific payment method';

    public function handle()
    {
        $method = $this->option('method');
        $amount = $this->option('amount');
        
        $this->info("Testing DOKU Integration with {$method}...");
        
        try {
            // Test order data
            $orderData = [
                'amount' => (int) $amount,
                'invoice_number' => 'TEST-' . time(),
                'merchant_order_id' => 'ORDER-' . time(),
            ];
            
            // Test customer data
            $customerData = [
                'name' => 'Test Customer',
                'phone' => '08123456789',
                'email' => 'test@meracikopi.com',
            ];
            
            $this->info("Creating test payment for {$method}...");
            $response = DokuService::createSpecificPayment($method, $orderData, $customerData);
            
            $this->info('Response received:');
            $this->line(json_encode($response, JSON_PRETTY_PRINT));
            
            // Show specific data based on payment method
            if (isset($response['qr_code_data'])) {
                $this->info("\n📱 QR Code Data:");
                $this->line("QR String: " . ($response['qr_code_data']['qr_string'] ?? 'N/A'));
                $this->line("Expires: " . ($response['qr_code_data']['expired_at'] ?? 'N/A'));
            }
            
            if (isset($response['virtual_account_info'])) {
                $this->info("\n🏦 Virtual Account Info:");
                $this->line("Bank: " . ($response['virtual_account_info']['bank_name'] ?? 'N/A'));
                $this->line("VA Number: " . ($response['virtual_account_info']['va_number'] ?? 'N/A'));
                $this->line("Amount: " . ($response['virtual_account_info']['amount'] ?? 'N/A'));
            }
            
            if (isset($response['ewallet_info'])) {
                $this->info("\n💰 E-Wallet Info:");
                $this->line("Payment URL: " . ($response['ewallet_info']['payment_url'] ?? 'N/A'));
                $this->line("Deep Link: " . ($response['ewallet_info']['deep_link'] ?? 'N/A'));
            }
            
            if (isset($response['instructions'])) {
                $this->info("\n📋 Instructions:");
                $this->line($response['instructions']);
            }
            
            $this->info("\n✅ DOKU integration test completed successfully!");
            
        } catch (\Exception $e) {
            $this->error('❌ DOKU integration test failed:');
            $this->error($e->getMessage());
            
            Log::error('DOKU Test Failed', [
                'method' => $method,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}