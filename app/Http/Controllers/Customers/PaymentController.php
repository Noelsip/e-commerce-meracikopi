<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\Payments;
use App\Models\Orders;

use App\Enums\OrderStatus;
use App\Enums\StatusPayments;

use App\Services\DokuService;

class PaymentController extends Controller
{
    /**
     * Map frontend payment method to DOKU payment channels
     */
    private function mapPaymentMethod(?string $paymentMethod): ?string
    {
        return DokuService::mapPaymentMethod($paymentMethod);
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
            try {
                $dokuResponse = DokuService::createSpecificPayment($selectedPaymentMethod, $orderData, $customerData);
            } catch (\Exception $e) {
                Log::error('DOKU Payment Error', [
                    'error' => $e->getMessage(),
                    'order_id' => $order->id,
                    'payment_method' => $selectedPaymentMethod,
                    'trace' => $e->getTraceAsString(),
                ]);
                
                // Use fallback for development/testing
                Log::info('Using DOKU fallback due to error', [
                    'error' => $e->getMessage(),
                    'order_id' => $order->id,
                ]);
                
                $dokuResponse = [
                    'payment_method' => $selectedPaymentMethod,
                    'payment_url' => url('/checkout/success?payment_id=' . $payment->id),
                    'qr_code_data' => base64_encode('FALLBACK_QR_CODE_FOR_' . $selectedPaymentMethod),
                    'virtual_account' => '8808' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                    'amount' => $orderData['amount'],
                    'status' => 'PENDING',
                    'invoice_number' => $transactionId,
                    'fallback_mode' => true,
                ];
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
        // Untuk development - skip signature verification sementara
        // TODO: Enable ini ketika merchant keys sudah dikonfigurasi dengan benar
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

            // Mengambil payment & lock berdasarkan invoice number
            $invoiceNumber = $request->input('order.invoiceNumber');
            $payment = Payments::where('transaction_id', $invoiceNumber)
                ->lockForUpdate()
                ->firstOrFail();

            if ($payment->status === StatusPayments::PAID) {
                return response()->json([
                    'message' => 'Payment already processed'
                ]);
            }

            // Mapping status DOKU
            $transactionStatus = $request->input('transaction.status');
            if (in_array($transactionStatus, ['SUCCESS', 'COMPLETE'])) {

                $payment->update([
                    'status' => StatusPayments::PAID,
                    'paid_at' => now(),
                    'payload' => array_merge($payment->payload, ['webhook_data' => $request->all()]),
                ]);

                $payment->order->update([
                    'status' => OrderStatus::PAID,
                ]);
            }

            return response()->json([
                'message' => 'Payment status updated'
            ]);
        });
    }

    public function checkPaymentStatus($orderId)
    {
        $guestToken = request()->attributes->get('guest_token');
        
        $order = Orders::where('id', $orderId)
            ->where('guest_token', $guestToken)
            ->firstOrFail();

        $payment = Payments::where('order_id', $order->id)->first();

        if (!$payment) {
            return response()->json([
                'message' => 'Payment not found',
                'status' => 'not_found'
            ]);
        }

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
