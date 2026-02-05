<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DokuService;
use Illuminate\Support\Facades\Log;

class TestDokuPayment extends Command
{
    protected $signature = 'doku:test-payment {method=qris}';
    protected $description = 'Test DOKU payment creation with specific method';

    public function handle()
    {
        $paymentMethod = $this->argument('method');
        
        $this->info("Testing DOKU payment creation with method: {$paymentMethod}");
        
        try {
            // Test data
            $orderData = [
                'amount' => 25000,
                'invoice_number' => 'TEST-' . time(),
                'merchant_order_id' => 'ORDER-' . time(),
            ];
            
            $customerData = [
                'name' => 'Test Customer',
                'phone' => '08123456789',
                'email' => 'test@example.com',
            ];
            
            $this->info("Order Data:");
            $this->line(json_encode($orderData, JSON_PRETTY_PRINT));
            
            $this->info("Customer Data:");
            $this->line(json_encode($customerData, JSON_PRETTY_PRINT));
            
            $this->info("Creating payment...");
            
            $response = DokuService::createSpecificPayment($paymentMethod, $orderData, $customerData);
            
            $this->info("✅ Payment created successfully!");
            $this->line(json_encode($response, JSON_PRETTY_PRINT));
            
        } catch (\Exception $e) {
            $this->error("❌ Payment creation failed!");
            $this->error("Error: " . $e->getMessage());
            
            // Tampilkan trace untuk debugging
            $this->line("\nStack trace:");
            $this->line($e->getTraceAsString());
        }
    }
}