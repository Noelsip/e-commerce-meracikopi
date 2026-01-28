<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Orders;
use App\Models\Deliveries;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\StatusDelivery;
use App\Models\OrderLogs;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Orders::query()
            ->with('payments')
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan type
        if ($request->filled('order_type')) {
            $query->where('order_type', $request->order_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(10);

        return response()->json([
            'data' => $orders->map(fn($order) => [
                'id' => $order->id,
                'order_type' => $order->order_type,
                'status' => $order->status,
                'total_price' => (int) $order->total_price,
                'customer_name' => $order->customer_name,
                'payment_status' => $order->payments->first()?->status ?? null,
                'created_at' => $order->created_at->toIso8601String(),
            ]),
        ]);
    }

    public function show($id)
    {
        $order = Orders::with([
            'items.menu',
            'order_addresses',
            'payments',
            'logs',
            'delivery',
        ])->findOrFail($id);

        return response()->json([
            'data' => $order
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'note' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($id, $request) {
            $order = Orders::lockForUpdate()->findOrFail($id);

            $newStatus = OrderStatus::from($request->status);

            $order->update([
                'status' => $newStatus,
            ]);

            OrderLogs::create([
                'order_id' => $order->id,
                'status' => $newStatus,
                'note' => $request->note ?? 'Updated by admin',
            ]);

            return response()->json([
                'message' => 'Order status updated',
                'data' => [
                    'order_id' => $order->id,
                    'new_status' => $newStatus,
                ]
            ]);
        });
    }

    /**
     * Create delivery request for an order
     * 
     * POST /api/admin/orders/{id}/delivery-request
     */
    public function createDeliveryRequest(Request $request, $id)
    {
        $request->validate([
            'courier' => 'required|string|max:100',
        ]);

        return DB::transaction(function () use ($id, $request) {
            $order = Orders::lockForUpdate()->findOrFail($id);

            // Validate order type is delivery
            if ($order->order_type !== OrderType::DELIVERY) {
                return response()->json([
                    'message' => 'Order is not a delivery order'
                ], 422);
            }

            // Check if delivery already exists
            $existingDelivery = Deliveries::where('order_id', $order->id)->first();
            if ($existingDelivery) {
                return response()->json([
                    'message' => 'Delivery request already exists for this order'
                ], 422);
            }

            // Create delivery record
            $delivery = Deliveries::create([
                'order_id' => $order->id,
                'courier_name' => $request->courier,
                'courier_order_id' => '', // Will be updated when courier assigns
                'status' => StatusDelivery::REQUESTED,
                'price' => $order->delivery_fee ?? 0,
                'raw_response' => [],
            ]);

            // Update order status to on_delivery if paid
            if ($order->status === OrderStatus::PAID || $order->status === OrderStatus::READY) {
                $order->update(['status' => OrderStatus::ON_DELIVERY]);
                
                OrderLogs::create([
                    'order_id' => $order->id,
                    'status' => OrderStatus::ON_DELIVERY,
                    'note' => 'Delivery requested with courier: ' . $request->courier,
                ]);
            }

            return response()->json([
                'message' => 'Delivery Request Created',
                'data' => [
                    'id' => $delivery->id,
                    'order_id' => $delivery->order_id,
                    'courier' => $delivery->courier_name,
                    'tracking_number' => $delivery->courier_order_id ?: null,
                    'delivery_status' => $delivery->status->value,
                ]
            ], 201);
        });
    }

    /**
     * Get delivery data for an order
     * 
     * GET /api/admin/orders/{id}/delivery
     */
    public function getDelivery($id)
    {
        $order = Orders::findOrFail($id);

        $delivery = Deliveries::where('order_id', $order->id)->first();

        if (!$delivery) {
            return response()->json([
                'message' => 'No delivery found for this order'
            ], 404);
        }

        return response()->json([
            'data' => [
                'id' => $delivery->id,
                'order_id' => $delivery->order_id,
                'courier' => $delivery->courier_name,
                'tracking_number' => $delivery->courier_order_id ?: null,
                'delivery_status' => $delivery->status->value,
                'price' => (int) $delivery->price,
                'eta' => $delivery->eta,
                'created_at' => $delivery->created_at->toIso8601String(),
                'updated_at' => $delivery->updated_at->toIso8601String(),
            ]
        ], 200);
    }

    /**
     * Update delivery status/tracking
     * 
     * PATCH /api/admin/orders/{id}/delivery
     */
    public function updateDelivery(Request $request, $id)
    {
        $request->validate([
            'tracking_number' => 'nullable|string|max:100',
            'delivery_status' => 'nullable|string|in:requested,assigned,on_delivery,delivered,cancelled',
            'eta' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($id, $request) {
            $order = Orders::findOrFail($id);
            
            $delivery = Deliveries::where('order_id', $order->id)
                ->lockForUpdate()
                ->firstOrFail();

            $updateData = [];

            if ($request->has('tracking_number')) {
                $updateData['courier_order_id'] = $request->tracking_number;
            }

            if ($request->has('delivery_status')) {
                $updateData['status'] = StatusDelivery::from($request->delivery_status);
                
                // If delivered, update order status to completed
                if ($request->delivery_status === 'delivered') {
                    $order->update(['status' => OrderStatus::COMPLETED]);
                    
                    OrderLogs::create([
                        'order_id' => $order->id,
                        'status' => OrderStatus::COMPLETED,
                        'note' => 'Delivery completed',
                    ]);
                }
            }

            if ($request->has('eta')) {
                $updateData['eta'] = $request->eta;
            }

            $delivery->update($updateData);

            return response()->json([
                'message' => 'Delivery updated',
                'data' => [
                    'id' => $delivery->id,
                    'order_id' => $delivery->order_id,
                    'courier' => $delivery->courier_name,
                    'tracking_number' => $delivery->courier_order_id ?: null,
                    'delivery_status' => $delivery->status->value,
                    'eta' => $delivery->eta,
                ]
            ], 200);
        });
    }
}
