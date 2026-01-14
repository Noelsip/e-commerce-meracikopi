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
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function pay(Request $request, $orderId)
    {
        $guestToken = $request->attributes->get('guest_token');

        return DB::transaction(function () use ($orderId, $guestToken) {
            
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

            // Membuat record pembayaran
            $payment = Payments::create([
                'order_id' => $order->id,
                'payment_gateway' => 'midtrans',
                'payment_method' => 'snap',
                'amount' => $order->total_price,
                'status' => StatusPayments::PENDING,
            ]);

            $transactionId = 'MERACIKOPI-' . $order->id;
            $payment->update([
                'transaction_id' => $transactionId,
            ]);

            // Init Midtrans
            MidtransService::init();

            // Payload Snap
            $snapPayload = [
                'transaction_details' => [
                    'order_id' => $transactionId,
                    'gross_amount' => (int) $payment->amount,
                ],
                'customer_details' => [
                    'first_name' => $order->customer_name,
                ],
            ];

            // Request Snap token
            $snapToken = Snap::getSnapToken($snapPayload);

            // Menyimpan payload token
            $payment->update([
                'payload' => [
                    'snap_request' => $snapPayload,
                    'snap_token' => $snapToken,
                ]
            ]);

            return response()->json([
                'message' => 'Payment initiated',
                'data' => [
                    'snap_token' => $snapToken,
                    'payment_id' => $payment->id,
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
