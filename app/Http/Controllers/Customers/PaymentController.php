<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\Payments;
use App\Models\Orders;
use App\Models\Cart;

use App\Enums\OrderStatus;
use App\Enums\StatusPayments;

use App\Services\DokuService;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;

class PaymentController extends Controller
{
    /**
     * Map frontend payment method to DOKU payment channels
     */
    private function mapPaymentMethod(?string $paymentMethod): ?string
    {
        return DokuService::mapPaymentMethod($paymentMethod);
    }

    /**
     * Get readable error message for user
     */
    private function getReadableError(string $errorMessage, string $paymentMethod): string
    {
        $methodNames = [
            'qris' => 'QRIS',
            'dana' => 'DANA',
            'gopay' => 'GoPay',
            'shopeepay' => 'ShopeePay',
            'ovo' => 'OVO',
            'bca_va' => 'Virtual Account BCA',
            'bni_va' => 'Virtual Account BNI',
            'bri_va' => 'Virtual Account BRI',
            'mandiri_va' => 'Virtual Account Mandiri',
        ];
        
        $methodName = $methodNames[$paymentMethod] ?? $paymentMethod;
        
        // Parse common DOKU errors
        if (str_contains($errorMessage, 'Unauthorized') || str_contains($errorMessage, 'Unknown Client')) {
            return "Pembayaran {$methodName} tidak tersedia saat ini. Silahkan coba metode pembayaran lain.";
        }
        
        if (str_contains($errorMessage, 'access token') || str_contains($errorMessage, 'Access Token')) {
            return "Koneksi ke payment gateway bermasalah. Silahkan coba beberapa saat lagi atau gunakan metode pembayaran lain.";
        }
        
        if (str_contains($errorMessage, 'timeout') || str_contains($errorMessage, 'Connection')) {
            return "Koneksi ke payment gateway timeout. Silahkan coba lagi atau gunakan metode pembayaran lain.";
        }
        
        return "Tidak dapat memproses pembayaran dengan {$methodName}. Silahkan coba metode pembayaran lain.";
    }

    /**
     * Validate DOKU response has required data for the payment method
     */
    private function validateDokuResponse(array $response, string $paymentMethod): array
    {
        // Check for QRIS - must have QR code data
        if ($paymentMethod === 'qris') {
            $qrData = $response['qr_code_data'] ?? null;
            if (!$qrData || (!isset($qrData['qr_image']) && !isset($qrData['qr_string']))) {
                return [
                    'valid' => false,
                    'error' => 'QR Code tidak dapat di-generate oleh payment gateway. Silahkan coba metode pembayaran lain.'
                ];
            }
        }
        
        // Check for Virtual Account - must have VA number
        if (in_array($paymentMethod, ['bca_va', 'bni_va', 'bri_va', 'mandiri_va'])) {
            $vaInfo = $response['virtual_account_info'] ?? null;
            if (!$vaInfo || !isset($vaInfo['va_number'])) {
                return [
                    'valid' => false,
                    'error' => 'Nomor Virtual Account tidak dapat di-generate. Silahkan coba metode pembayaran lain.'
                ];
            }
        }
        
        // Check for E-Wallet - must have payment URL or deep link
        if (in_array($paymentMethod, ['dana', 'gopay', 'shopeepay', 'ovo'])) {
            $ewalletInfo = $response['ewallet_info'] ?? null;
            $paymentUrl = $response['payment_url'] ?? null;
            if (!$ewalletInfo && !$paymentUrl) {
                return [
                    'valid' => false,
                    'error' => 'Link pembayaran e-wallet tidak tersedia. Silahkan coba metode pembayaran lain.'
                ];
            }
        }
        
        return ['valid' => true, 'error' => null];
    }

    /**
     * Clear cart after successful payment
     */
    private function clearCartForOrder(Orders $order): void
    {
        try {
            // Find cart by guest_token or user_id
            $cartQuery = Cart::query();
            
            if ($order->user_id) {
                $cartQuery->where('user_id', $order->user_id);
            } elseif ($order->guest_token) {
                $cartQuery->where('guest_token', $order->guest_token);
            } else {
                Log::warning('Cannot clear cart - no user_id or guest_token', ['order_id' => $order->id]);
                return;
            }
            
            $cart = $cartQuery->first();
            
            if ($cart) {
                $itemCount = $cart->items()->count();
                $cart->items()->delete();
                Log::info('Cart cleared after successful payment', [
                    'order_id' => $order->id,
                    'cart_id' => $cart->id,
                    'items_deleted' => $itemCount
                ]);
            }
        } catch (\Exception $e) {
            // Log error but don't fail the payment process
            Log::error('Error clearing cart after payment', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Generate fallback response when DOKU API fails (for development/testing)
     */
    private function generateFallbackResponse(string $paymentMethod, string $transactionId, array $orderData, Payments $payment): array
    {
        $response = [
            'payment_method' => $paymentMethod,
            'invoice_number' => $transactionId,
            'amount' => $orderData['amount'],
            'status' => 'PENDING',
            'fallback_mode' => true,
        ];
        
        switch ($paymentMethod) {
            case 'qris':
                $response['qr_code_data'] = [
                    'qr_string' => 'QRIS-' . $transactionId,
                    'qr_image' => $this->generatePaymentQRCode($transactionId, $orderData['amount']),
                    'expired_at' => now()->addHours(1)->toISOString()
                ];
                $response['instructions'] = 'Scan QR Code menggunakan aplikasi e-wallet atau mobile banking Anda';
                break;
                
            case 'bca_va':
            case 'bni_va':
            case 'bri_va':
            case 'mandiri_va':
                $bankName = strtoupper(str_replace('_va', '', $paymentMethod));
                $vaNumber = ($bankName === 'BCA' ? '8808' : '8809') . str_pad($payment->order_id, 6, '0', STR_PAD_LEFT);
                
                $response['virtual_account_info'] = [
                    'bank_name' => $bankName,
                    'va_number' => $vaNumber,
                    'amount' => $orderData['amount'],
                    'expired_at' => now()->addHours(24)->toISOString()
                ];
                $response['instructions'] = "Transfer ke Virtual Account {$bankName}: {$vaNumber}";
                break;
                
            case 'dana':
            case 'gopay':
            case 'shopeepay':
            case 'ovo':
                $walletName = ucfirst($paymentMethod);
                $response['payment_url'] = url('/checkout/success?payment_id=' . $payment->id);
                $response['ewallet_info'] = [
                    'deep_link' => "https://mock-{$paymentMethod}.app/pay?amount=" . $orderData['amount'],
                    'payment_url' => url('/checkout/success?payment_id=' . $payment->id),
                    'expired_at' => now()->addHours(1)->toISOString()
                ];
                $response['instructions'] = "Anda akan diarahkan ke aplikasi {$walletName} untuk menyelesaikan pembayaran";
                break;
                
            default:
                $response['payment_url'] = url('/checkout/success?payment_id=' . $payment->id);
                $response['instructions'] = 'Silahkan ikuti petunjuk pembayaran yang muncul';
        }
        
        return $response;
    }

    /**
     * Generate real QR code for payment using endroid/qr-code
     */
    private function generatePaymentQRCode(string $invoiceNumber, int $amount): string
    {
        // Create QR data with payment info (format that resembles QRIS)
        $qrData = json_encode([
            'invoice' => $invoiceNumber,
            'amount' => $amount,
            'merchant' => 'MERACIKOPI',
            'timestamp' => time(),
            'type' => 'QRIS_MOCK'
        ]);

        // Create QR Code using endroid/qr-code
        $qrCode = new QrCode(
            data: $qrData,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10
        );

        // Write to PNG and return as base64
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        
        return base64_encode($result->getString());
    }

    public function pay(Request $request, $orderId)
    {
        $guestToken = $request->attributes->get('guest_token');
        $selectedPaymentMethod = $request->input('payment_method');

        return DB::transaction(function () use ($orderId, $guestToken, $selectedPaymentMethod) {
            
            // Mengambil order berdasarkan ID
            $order = Orders::where('id', $orderId)
                ->where('guest_token', $guestToken)
                ->where('status', OrderStatus::PENDING_PAYMENT)
                ->lockForUpdate()
                ->firstOrFail();

            // Mencegah double payment
            $existingPayment = Payments::where('order_id', $order->id)
                ->where('status', StatusPayments::PAID)
                ->exists();

            if ($existingPayment) {
                abort(422, 'Order already paid');
            }

            // Generate transaction ID
            $transactionId = 'MERACIKOPI-' . $order->id . '-' . time();

            // Membuat record pembayaran
            $payment = Payments::create([
                'order_id' => $order->id,
                'payment_gateway' => 'doku',
                'payment_method' => $selectedPaymentMethod ?? 'qris',
                'transaction_id' => $transactionId,
                'amount' => $order->final_price,
                'status' => StatusPayments::PENDING,
                'payload' => [],
            ]);

            // Validate payment method
            if (!$selectedPaymentMethod) {
                $payment->delete();
                abort(422, 'Metode pembayaran harus dipilih');
            }

            // Prepare order data for DOKU
            $orderData = [
                'amount' => $payment->amount,
                'invoice_number' => $transactionId,
                'merchant_order_id' => $order->id,
            ];

            // Prepare customer data for DOKU
            $customerData = [
                'name' => $order->customer_name,
                'phone' => $order->customer_phone ?? '',
                'email' => $order->customer_email ?? 'customer@meracikopi.com',
            ];

            // Request payment from DOKU with specific method
            $dokuResponse = null;
            $useFallback = false;
            
            try {
                $dokuResponse = DokuService::createSpecificPayment($selectedPaymentMethod, $orderData, $customerData);
            } catch (\Exception $e) {
                Log::error('DOKU Payment Error', [
                    'error' => $e->getMessage(),
                    'order_id' => $order->id,
                    'payment_method' => $selectedPaymentMethod,
                    'trace' => $e->getTraceAsString(),
                ]);
                
                // Check if fallback mode is enabled
                if (config('doku.fallback_enabled', false)) {
                    Log::info('Using DOKU fallback mode due to error', [
                        'error' => $e->getMessage(),
                        'order_id' => $order->id,
                    ]);
                    $useFallback = true;
                } else {
                    // Fallback disabled - return error to user
                    $payment->delete();
                    
                    return response()->json([
                        'message' => 'Gagal memproses pembayaran dengan metode ini',
                        'error' => 'payment_gateway_error',
                        'error_detail' => $this->getReadableError($e->getMessage(), $selectedPaymentMethod),
                        'can_retry' => true,
                        'order_id' => $order->id,
                    ], 422);
                }
            }
            
            // Use fallback mock data if DOKU failed and fallback is enabled
            if ($useFallback) {
                $dokuResponse = $this->generateFallbackResponse($selectedPaymentMethod, $transactionId, $orderData, $payment);
            }
            
            // Verify DOKU response has required data for the payment method
            $validationResult = $this->validateDokuResponse($dokuResponse, $selectedPaymentMethod);
            if (!$validationResult['valid']) {
                Log::error('DOKU Response Invalid', [
                    'response' => $dokuResponse,
                    'payment_method' => $selectedPaymentMethod,
                    'validation_error' => $validationResult['error'],
                ]);
                
                $payment->delete();
                
                return response()->json([
                    'message' => 'Metode pembayaran tidak tersedia saat ini',
                    'error' => 'invalid_payment_response',
                    'error_detail' => $validationResult['error'],
                    'can_retry' => true,
                    'order_id' => $order->id,
                ], 422);
            }

            // Menyimpan payload response dari DOKU
            $payment->update([
                'payload' => [
                    'doku_request' => $orderData,
                    'doku_response' => $dokuResponse,
                    'selected_payment_method' => $selectedPaymentMethod,
                ]
            ]);

            // Prepare response data based on payment method
            $responseData = [
                'payment_id' => $payment->id,
                'payment_method' => $selectedPaymentMethod,
                'invoice_number' => $transactionId,
            ];

            // Add specific data based on payment method
            if (isset($dokuResponse['qr_code_data']) && $dokuResponse['qr_code_data']) {
                $responseData['qr_code'] = $dokuResponse['qr_code_data'];
            }
            
            if (isset($dokuResponse['virtual_account_info']) && $dokuResponse['virtual_account_info']) {
                $responseData['virtual_account'] = $dokuResponse['virtual_account_info'];
            }
            
            if (isset($dokuResponse['ewallet_info']) && $dokuResponse['ewallet_info']) {
                $responseData['ewallet'] = $dokuResponse['ewallet_info'];
            }
            
            if (isset($dokuResponse['payment_url']) && $dokuResponse['payment_url']) {
                $responseData['payment_url'] = $dokuResponse['payment_url'];
            }
            
            if (isset($dokuResponse['instructions']) && $dokuResponse['instructions']) {
                $responseData['instructions'] = $dokuResponse['instructions'];
            }

            return response()->json([
                'message' => 'Payment initiated',
                'data' => $responseData
            ]);
        });
    }

    public function dokuWebhook(Request $request)
    {
        Log::info('DOKU Webhook received', [
            'headers' => $request->headers->all(),
            'body' => $request->all()
        ]);

        // Untuk development - skip signature verification sementara
        $skipSignatureVerification = !config('doku.merchant_private_key');
        
        if (!$skipSignatureVerification) {
            // Validasi signature DOKU menggunakan RSA verification
            $signature = $request->header('X-SIGNATURE') ?? $request->input('signature');
            
            if (!$signature || !DokuService::verifyDokuSignature($request->all(), $signature)) {
                Log::warning('Invalid DOKU signature', [
                    'signature' => $signature,
                    'data' => $request->all()
                ]);
                abort(403, 'Invalid signature');
            }
        } else {
            Log::info('DOKU Webhook - Signature verification skipped (development mode)');
        }

        return DB::transaction(function () use ($request) {
            // Support multiple invoice number formats from DOKU
            $invoiceNumber = $request->input('order.invoiceNumber') 
                ?? $request->input('order.invoice_number')
                ?? $request->input('invoiceNumber')
                ?? $request->input('invoice_number');

            if (!$invoiceNumber) {
                Log::error('DOKU Webhook - No invoice number found', $request->all());
                return response()->json(['message' => 'Invoice number not found'], 400);
            }

            $payment = Payments::where('transaction_id', $invoiceNumber)
                ->lockForUpdate()
                ->first();

            if (!$payment) {
                Log::warning('DOKU Webhook - Payment not found', ['invoice' => $invoiceNumber]);
                return response()->json(['message' => 'Payment not found'], 404);
            }

            if ($payment->status === StatusPayments::PAID) {
                Log::info('DOKU Webhook - Payment already processed', ['invoice' => $invoiceNumber]);
                return response()->json(['message' => 'Payment already processed']);
            }

            // Mapping status DOKU
            $transactionStatus = $request->input('transaction.status') 
                ?? $request->input('transactionStatus')
                ?? $request->input('status');
            
            Log::info('DOKU Webhook - Processing status', [
                'invoice' => $invoiceNumber,
                'status' => $transactionStatus
            ]);

            // Handle different statuses
            if (in_array($transactionStatus, ['SUCCESS', 'COMPLETE', 'PAID', 'SETTLED'])) {
                $payment->update([
                    'status' => StatusPayments::PAID,
                    'paid_at' => now(),
                    'payload' => array_merge($payment->payload ?? [], ['webhook_data' => $request->all()]),
                ]);

                $payment->order->update([
                    'status' => OrderStatus::PAID,
                ]);
                
                // Clear cart setelah pembayaran berhasil
                $this->clearCartForOrder($payment->order);

                Log::info('DOKU Webhook - Payment marked as PAID', ['invoice' => $invoiceNumber]);
            } elseif (in_array($transactionStatus, ['FAILED', 'REJECTED', 'DENIED'])) {
                $payment->update([
                    'status' => StatusPayments::FAILED,
                    'payload' => array_merge($payment->payload ?? [], ['webhook_data' => $request->all()]),
                ]);

                Log::info('DOKU Webhook - Payment marked as FAILED', ['invoice' => $invoiceNumber]);
            } elseif (in_array($transactionStatus, ['EXPIRED', 'TIMEOUT'])) {
                $payment->update([
                    'status' => StatusPayments::EXPIRED,
                    'payload' => array_merge($payment->payload ?? [], ['webhook_data' => $request->all()]),
                ]);

                Log::info('DOKU Webhook - Payment marked as EXPIRED', ['invoice' => $invoiceNumber]);
            }

            return response()->json(['message' => 'Payment status updated']);
        });
    }

    /**
     * Simulate payment completion for testing (fallback mode)
     * This allows testing the payment flow without real DOKU integration
     */
    public function simulatePaymentComplete(Request $request, $invoiceNumber)
    {
        // Only allow in non-production environment
        if (config('app.env') === 'production') {
            abort(403, 'Not allowed in production');
        }

        $guestToken = $request->attributes->get('guest_token');

        $payment = Payments::where('transaction_id', $invoiceNumber)
            ->whereHas('order', function($query) use ($guestToken) {
                $query->where('guest_token', $guestToken);
            })
            ->first();

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        if ($payment->status === StatusPayments::PAID) {
            return response()->json(['message' => 'Payment already completed', 'status' => 'paid']);
        }

        // Check if this is a fallback mode payment
        $isFallback = isset($payment->payload['doku_response']['fallback_mode']);
        
        if (!$isFallback) {
            return response()->json([
                'message' => 'Cannot simulate - this is a real DOKU payment',
                'status' => 'error'
            ], 400);
        }

        // Simulate successful payment
        $payment->update([
            'status' => StatusPayments::PAID,
            'paid_at' => now(),
            'payload' => array_merge($payment->payload ?? [], [
                'simulated_at' => now()->toISOString(),
                'simulated_by' => 'test_endpoint'
            ]),
        ]);

        $payment->order->update([
            'status' => OrderStatus::PAID,
        ]);
        
        // Clear cart setelah pembayaran berhasil
        $this->clearCartForOrder($payment->order);

        Log::info('Payment simulated as complete', ['invoice' => $invoiceNumber]);

        return response()->json([
            'message' => 'Payment simulated as complete',
            'status' => 'paid',
            'payment_id' => $payment->id,
            'order_id' => $payment->order_id
        ]);
    }

    public function checkPaymentStatus($invoiceNumber)
    {
        $guestToken = request()->attributes->get('guest_token');
        
        // Find payment by transaction_id (invoice number)
        $payment = Payments::where('transaction_id', $invoiceNumber)
            ->whereHas('order', function($query) use ($guestToken) {
                $query->where('guest_token', $guestToken);
            })
            ->with('order')
            ->firstOrFail();
            
        $order = $payment->order;

        // Jika sudah paid, return status
        if ($payment->status === StatusPayments::PAID) {
            return response()->json([
                'message' => 'Payment completed',
                'status' => 'paid',
                'payment_id' => $payment->id
            ]);
        }

        // Check status dari DOKU jika masih pending
        try {
            $statusResponse = DokuService::getPaymentStatus($payment->transaction_id);
            
            // Update status jika ada perubahan
            $transactionStatus = $statusResponse['transaction']['status'] ?? 'PENDING';
            if (in_array($transactionStatus, ['SUCCESS', 'COMPLETE'])) {
                $payment->update([
                    'status' => StatusPayments::PAID,
                    'paid_at' => now(),
                    'payload' => array_merge($payment->payload, ['status_check' => $statusResponse]),
                ]);

                $payment->order->update([
                    'status' => OrderStatus::PAID,
                ]);

                return response()->json([
                    'message' => 'Payment completed',
                    'status' => 'paid',
                    'payment_id' => $payment->id
                ]);
            }

            return response()->json([
                'message' => 'Payment still pending',
                'status' => 'pending',
                'payment_id' => $payment->id,
                'transaction_status' => $transactionStatus
            ]);

        } catch (\Exception $e) {
            Log::error('DOKU Status Check Error', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id,
            ]);

            // For fallback mode, return pending status instead of error
            if (isset($payment->payload['doku_response']['fallback_mode'])) {
                return response()->json([
                    'message' => 'Payment still pending (fallback mode)',
                    'status' => 'pending',
                    'payment_id' => $payment->id,
                    'fallback_mode' => true
                ]);
            }

            return response()->json([
                'message' => 'Payment status check failed',
                'status' => 'unknown',
                'payment_id' => $payment->id
            ], 500);
        }
    }

    /**
     * Generate token untuk DOKU SNAP
     * Endpoint ini akan dipanggil oleh DOKU untuk mendapatkan access token
     */
    public function generateDokuToken(Request $request)
    {
        try {
            // Validasi request dari DOKU
            $clientId = $request->header('X-CLIENT-KEY') ?: $request->input('client_id');
            
            if ($clientId !== config('doku.client_id')) {
                return response()->json([
                    'error' => 'Invalid client ID'
                ], 401);
            }

            // Generate access token
            $tokenData = DokuService::getAccessTokenForSnap();

            return response()->json([
                'access_token' => $tokenData['accessToken'],
                'token_type' => 'Bearer',
                'expires_in' => $tokenData['expiresIn']
            ]);

        } catch (\Exception $e) {
            Log::error('DOKU Token Generation Error', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'error' => 'Token generation failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
