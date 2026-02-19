<?php

namespace App\Http\Controllers\Webhooks;

use App\Enums\StatusDelivery;
use App\Http\Controllers\Controller;
use App\Models\Deliveries;
use App\Models\OrderLogs;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Biteship Webhook Controller
 *
 * Menerima callback/webhook dari Biteship untuk update status pengiriman.
 * Biteship akan mengirim POST request ke endpoint ini setiap kali
 * ada perubahan status pengiriman.
 *
 * Webhook URL yang perlu didaftarkan di Biteship Dashboard:
 * https://yourdomain.com/api/webhooks/biteship
 */
class BiteshipWebhookController extends Controller
{
    /**
     * Handle incoming webhook from Biteship
     *
     * Biteship webhook payload example:
     * {
     *   "event": "order.status",
     *   "order_id": "5dd45xxxxxx",
     *   "status": "delivered",
     *   "courier_tracking_id": "TRACK123",
     *   "courier_waybill_id": "AWB123",
     *   ...
     * }
     */
    public function handle(Request $request)
    {
        $payload = $request->all();

        Log::info('Biteship Webhook received', [
            'event' => $payload['event'] ?? 'unknown',
            'order_id' => $payload['order_id'] ?? null,
            'status' => $payload['status'] ?? null,
        ]);

        $event = $payload['event'] ?? null;

        if (!$event) {
            // Return 200 OK to pass Biteship's webhook URL validation check
            Log::info('Biteship Webhook verification received (no event provided)');
            return response()->json(['message' => 'Webhook validation successful'], 200);
        }

        try {
            match ($event) {
                'order.status' => $this->handleOrderStatus($payload),
                'order.waybill_id' => $this->handleWaybillUpdate($payload),
                'order.price' => $this->handlePriceUpdate($payload),
                default => Log::info("Biteship webhook event not handled: {$event}", $payload),
            };

            return response()->json(['message' => 'Webhook processed successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Biteship webhook error: ' . $e->getMessage(), [
                'payload' => $payload,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['message' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Handle order status update
     */
    private function handleOrderStatus(array $payload): void
    {
        $biteshipOrderId = $payload['order_id'] ?? null;
        $status = $payload['status'] ?? null;

        if (!$biteshipOrderId || !$status) {
            Log::warning('Biteship webhook: missing order_id or status', $payload);
            return;
        }

        // Cari delivery berdasarkan biteship_order_id
        $delivery = Deliveries::where('biteship_order_id', $biteshipOrderId)->first();

        if (!$delivery) {
            // Fallback: cari berdasarkan courier_order_id
            $delivery = Deliveries::where('courier_order_id', $biteshipOrderId)->first();
        }

        if (!$delivery) {
            Log::warning('Biteship webhook: delivery not found', [
                'biteship_order_id' => $biteshipOrderId,
            ]);
            return;
        }

        // Map Biteship status ke enum
        $newStatus = StatusDelivery::fromBiteshipStatus($status);

        // Update delivery
        $updateData = [
            'status' => $newStatus,
            'raw_response' => $payload,
        ];

        // Update tracking info jika tersedia
        if (!empty($payload['courier_tracking_id'])) {
            $updateData['tracking_number'] = $payload['courier_tracking_id'];
        }
        if (!empty($payload['courier_waybill_id'])) {
            $updateData['courier_waybill_id'] = $payload['courier_waybill_id'];
        }
        if (!empty($payload['courier_link'])) {
            $updateData['tracking_url'] = $payload['courier_link'];
        }

        // Update timestamp berdasarkan status
        if ($newStatus === StatusDelivery::PICKED) {
            $updateData['picked_up_at'] = now();
        }
        if ($newStatus === StatusDelivery::DELIVERED) {
            $updateData['delivered_at'] = now();
        }

        $delivery->update($updateData);

        // Buat order log
        $order = $delivery->order;
        if ($order) {
            OrderLogs::create([
                'order_id' => $order->id,
                'status' => $order->status,
                'note' => "Delivery status updated: {$status}",
            ]);

            // Jika delivered, update order status juga
            if ($newStatus === StatusDelivery::DELIVERED) {
                $order->update([
                    'order_status' => 'completed',
                ]);

                OrderLogs::create([
                    'order_id' => $order->id,
                    'status' => $order->status,
                    'note' => 'Order completed - package delivered',
                ]);
            }
        }

        Log::info('Biteship delivery status updated', [
            'delivery_id' => $delivery->id,
            'order_id' => $delivery->order_id,
            'old_status' => $delivery->getOriginal('status'),
            'new_status' => $newStatus->value,
        ]);
    }

    /**
     * Handle waybill ID update (resi)
     */
    private function handleWaybillUpdate(array $payload): void
    {
        $biteshipOrderId = $payload['order_id'] ?? null;
        $waybillId = $payload['courier_waybill_id'] ?? null;

        if (!$biteshipOrderId || !$waybillId) {
            return;
        }

        $delivery = Deliveries::where('biteship_order_id', $biteshipOrderId)->first()
            ?? Deliveries::where('courier_order_id', $biteshipOrderId)->first();

        if ($delivery) {
            $delivery->update([
                'courier_waybill_id' => $waybillId,
                'tracking_number' => $payload['courier_tracking_id'] ?? $delivery->tracking_number,
            ]);

            Log::info('Biteship waybill updated', [
                'delivery_id' => $delivery->id,
                'waybill_id' => $waybillId,
            ]);
        }
    }

    /**
     * Handle price update (final shipping cost)
     */
    private function handlePriceUpdate(array $payload): void
    {
        $biteshipOrderId = $payload['order_id'] ?? null;
        $price = $payload['price'] ?? null;

        if (!$biteshipOrderId || !is_numeric($price)) {
            return;
        }

        $delivery = Deliveries::where('biteship_order_id', $biteshipOrderId)->first()
            ?? Deliveries::where('courier_order_id', $biteshipOrderId)->first();

        if ($delivery) {
            $delivery->update([
                'price' => (int) $price,
            ]);

            Log::info('Biteship price updated', [
                'delivery_id' => $delivery->id,
                'price' => $price,
            ]);
        }
    }
}
