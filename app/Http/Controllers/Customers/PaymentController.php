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

use App\Services\MidtransService;

class PaymentController extends Controller
{
    /**
     * Map frontend payment method to Midtrans enabled_payments
     */
    private function mapPaymentMethod(?string $paymentMethod): ?array
    {
        $mapping = [
            'qris' => ['other_qris'],
            'dana' => ['other_qris'], // DANA uses QRIS in Midtrans Snap
            'gopay' => ['gopay'],
            'shopeepay' => ['shopeepay'],
            'transfer_bank' => ['bca_va', 'bni_va', 'bri_va', 'permata_va', 'other_va'],
        ];

        return $mapping[$paymentMethod] ?? null;
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
                'payment_gateway' => 'midtrans',
                'payment_method' => $selectedPaymentMethod ?? 'snap',
                'transaction_id' => $transactionId,
                'amount' => $order->final_price,
                'status' => StatusPayments::PENDING,
                'payload' => [],
            ]);

            // Payload Snap
            $snapPayload = [
                'transaction_details' => [
                    'order_id' => $transactionId,
                    'gross_amount' => (int) $payment->amount,
                ],
                'customer_details' => [
                    'first_name' => $order->customer_name,
                    'phone' => $order->customer_phone ?? '',
                ],
            ];

            // If payment method is selected, only show that method in Snap
            $enabledPayments = $this->mapPaymentMethod($selectedPaymentMethod);
            if ($enabledPayments) {
                $snapPayload['enabled_payments'] = $enabledPayments;
            }

            // Request Snap token using custom HTTP client (bypasses Midtrans library CURL issues)
            try {
                $snapToken = MidtransService::getSnapToken($snapPayload);
            } catch (\Exception $e) {
                Log::error('Midtrans Snap Error', [
                    'error' => $e->getMessage(),
                    'order_id' => $order->id,
                    'payload' => $snapPayload,
                ]);
                
                // Delete the pending payment record
                $payment->delete();
                
                abort(500, 'Gagal terhubung ke payment gateway. Silahkan coba lagi.');
            }

            // Menyimpan payload token
            $payment->update([
                'payload' => [
                    'snap_request' => $snapPayload,
                    'snap_token' => $snapToken,
                    'selected_payment_method' => $selectedPaymentMethod,
                ]
            ]);

            return response()->json([
                'message' => 'Payment initiated',
                'data' => [
                    'snap_token' => $snapToken,
                    'payment_id' => $payment->id,
                    'payment_method' => $selectedPaymentMethod,
                ]
            ]);
        });
    }

    public function midtransWebhook(Request $request)
    {
        // Validasi signature midtrans
        $serverKey = config('midtrans.server_key');
        $expectedSignature = hash(
            'sha512',
            $request->order_id .
            $request->status_code .
            $request->gross_amount .
            $serverKey
        );

        if ($expectedSignature !== $request->signature_key) {
            Log::warning('Invalid Midtrans signature', $request->all());
            abort(403, 'Invalid signature');
        }

        return DB::transaction(function () use ($request) {

            // Mengambil payment & lock
            $payment = Payments::where('transaction_id', $request->order_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($payment->status === StatusPayments::PAID) {
                return response()->json([
                    'message' => 'Payment already processed'
                ]);
            }

            // Mapping status midtrans
            if (in_array($request->transaction_status, ['settlement', 'capture'])) {

                $payment->update([
                    'status' => StatusPayments::PAID,
                    'paid_at' => now(),
                    'payload' => $request->all(),
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
}
