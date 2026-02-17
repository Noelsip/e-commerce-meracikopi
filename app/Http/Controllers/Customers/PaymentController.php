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

        if (str_contains($errorMessage, 'responseCode') || str_contains($errorMessage, '{')) {
            return "DOKU Error: " . $errorMessage;
        }

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
     * 
     * All payment methods use Checkout v1 which returns a payment_url.
     * The user is directed to DOKU's hosted page (in a popup) where they
     * see only the selected payment method.
     */
    private function validateDokuResponse(array $response, string $paymentMethod): array
    {
        $paymentUrl = $response['payment_url'] ?? null;
        $isFallback = $response['fallback_mode'] ?? false;

        if (!$paymentUrl && !$isFallback) {
            return [
                'valid' => false,
                'error' => 'Link pembayaran tidak tersedia dari payment gateway. Silahkan coba lagi atau gunakan metode pembayaran lain.'
            ];
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
            'display_type' => 'popup', // Default fallback UX
            // Default URL creates a mock payment page (or reuse success page for dev)
            'payment_url' => url('/checkout/success?payment_id=' . $payment->id . '&simulated=true'),
        ];

        switch ($paymentMethod) {
            case 'qris':
                $response['qr_code_data'] = [
                    'qr_string' => 'QRIS-' . $transactionId,
                    'qr_image' => 'https://dummyimage.com/300x300/000/fff&text=QRIS+Mock', // Mock QR
                    'qr_url' => 'https://dummyimage.com/300x300/000/fff&text=QRIS+Mock',
                    'expired_at' => now()->addHours(1)->toIso8601String()
                ];
                $response['instructions'] = 'Scan QR Code Mock di jendela popup.';
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
                'phone' => $order->customer_phone ?: '081211111111',
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
                'display_type' => $dokuResponse['display_type'] ?? 'popup', // Default to popup if missing
            ];

            // Add specific data based on payment method
            if (isset($dokuResponse['qr_code_data']) && $dokuResponse['qr_code_data']) {
                $responseData['qr_code'] = $dokuResponse['qr_code_data']; // Legacy
                $responseData['qr_code_data'] = $dokuResponse['qr_code_data']; // Consistent key
            }

            if (isset($dokuResponse['virtual_account_info']) && $dokuResponse['virtual_account_info']) {
                $responseData['virtual_account'] = $dokuResponse['virtual_account_info']; // Legacy
                $responseData['virtual_account_info'] = $dokuResponse['virtual_account_info']; // Consistent key
            }

            if (isset($dokuResponse['ewallet_info']) && $dokuResponse['ewallet_info']) {
                $responseData['ewallet'] = $dokuResponse['ewallet_info']; // Legacy
                $responseData['ewallet_info'] = $dokuResponse['ewallet_info']; // Consistent key
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
        return $this->handleWebhook($request);
    }

    public function handleWebhook(Request $request)
    {
        // Berikan respon sukses untuk request GET (pengecekan URL oleh dashboard DOKU)
        if ($request->isMethod('get')) {
            return response()->json(['status' => 'active', 'message' => 'DOKU Webhook Endpoint is ready']);
        }

        $data = $request->all();

        Log::info('DOKU WEBHOOK', $data);

        // Validasi signature (opsional, tapi disarankan)
        $signature = $request->header('X-SIGNATURE');
        if ($signature && !DokuService::verifyDokuSignature($data, $signature)) {
            Log::warning('Invalid DOKU signature in webhook');
            // Tetap lanjut jika dalam mode development tanpa key
            if (config('doku.merchant_private_key')) {
                return response()->json(['message' => 'Invalid signature'], 403);
            }
        }

        $invoice = $data['order']['invoiceNumber'] ?? null;
        $status = $data['transaction']['status'] ?? null;

        if (!$invoice) {
            return response()->json(['message' => 'Invalid data'], 400);
        }

        return DB::transaction(function () use ($invoice, $status, $data) {
            // Menggunakan transaction_id karena sesuai dengan schema database Anda
            $payment = Payments::where('transaction_id', $invoice)
                ->lockForUpdate()
                ->first();

            if (!$payment) {
                Log::warning('DOKU WEBHOOK - Payment not found', ['invoice' => $invoice]);
                return response()->json(['message' => 'Payment not found'], 404);
            }

            if ($payment->status === StatusPayments::PAID) {
                return response()->json(['message' => 'OK']);
            }

            if ($status === 'SUCCESS' || $status === 'SETTLED') {
                $payment->status = StatusPayments::PAID;
                $payment->paid_at = now();

                // Update status pesanan terkait
                if ($payment->order) {
                    $payment->order->update([
                        'status' => OrderStatus::PAID,
                        'payment_status' => StatusPayments::PAID,
                    ]);
                    $this->clearCartForOrder($payment->order);
                }
            } elseif ($status === 'FAILED') {
                $payment->status = StatusPayments::FAILED;
                if ($payment->order) {
                    $payment->order->update(['payment_status' => StatusPayments::FAILED]);
                }
            } elseif ($status === 'EXPIRED') {
                $payment->status = StatusPayments::EXPIRED;
                if ($payment->order) {
                    $payment->order->update(['payment_status' => StatusPayments::EXPIRED]);
                }
            }

            // Simpan payload tambahan untuk logging
            $payload = $payment->payload ?? [];
            $payload['webhook_data'] = $data;
            $payment->payload = $payload;

            $payment->save();

            Log::info('DOKU WEBHOOK - Status updated', [
                'invoice' => $invoice,
                'status' => $payment->status
            ]);

            return response()->json(['message' => 'OK']);
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
            ->whereHas('order', function ($query) use ($guestToken) {
                $query->where('guest_token', $guestToken);
            })
            ->first();

        if (!$payment) {
            return response()->json(['success' => false, 'message' => 'Payment not found'], 404);
        }

        if ($payment->status === StatusPayments::PAID) {
            return response()->json(['success' => true, 'message' => 'Payment already completed', 'status' => 'paid']);
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
            'payment_status' => StatusPayments::PAID,
        ]);

        // Clear cart setelah pembayaran berhasil
        $this->clearCartForOrder($payment->order);

        Log::info('Payment simulated as complete', ['invoice' => $invoiceNumber]);

        return response()->json([
            'success' => true,
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
            ->whereHas('order', function ($query) use ($guestToken) {
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
                    'payment_status' => StatusPayments::PAID,
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
            // Berikan respon sukses untuk request GET atau jika request dianggap pengecekan awal
            if ($request->isMethod('get') || !$request->header('X-CLIENT-KEY')) {
                return response()->json([
                    'responseCode' => '2000000',
                    'responseMessage' => 'Success',
                    'status' => 'active'
                ])->header('ngrok-skip-browser-warning', 'true');
            }

            $clientId = $request->header('X-CLIENT-KEY') ?: $request->input('client_id');

            if ($clientId !== config('doku.client_id')) {
                return response()->json([
                    'responseCode' => '4017300',
                    'responseMessage' => 'Invalid Client ID'
                ], 401);
            }

            // Generate access token
            $tokenData = DokuService::getAccessTokenForSnap();

            // STANDAR SNAP menggunakan CamelCase
            return response()->json([
                'accessToken' => $tokenData['accessToken'],
                'tokenType' => 'Bearer',
                'expiresIn' => (string) $tokenData['expiresIn']
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
