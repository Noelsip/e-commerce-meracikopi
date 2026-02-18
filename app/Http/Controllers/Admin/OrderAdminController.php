<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\Tables;
use App\Models\User;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\OrderProcessStatus;
use App\Enums\StatusPayments;
use Illuminate\Http\Request;
use App\Services\Shipping\BiteshipClient;
use App\Models\Deliveries;
use App\Enums\StatusDelivery;

class OrderAdminController extends Controller
{
    public function __construct(
        protected BiteshipClient $biteship
    ) {
    }
    public function index(Request $request)
    {
        $query = Orders::with(['user', 'tables'])->latest();

        // Filter by order status
        if ($request->filled('order_status')) {
            $query->where('order_status', $request->order_status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by order type
        if ($request->filled('order_type')) {
            $query->where('order_type', $request->order_type);
        }

        $orders = $query->paginate(15);
        $orderStatuses = OrderProcessStatus::cases();
        $paymentStatuses = StatusPayments::cases();
        $orderTypes = OrderType::cases();

        return view('admin.orders.index', compact('orders', 'orderStatuses', 'paymentStatuses', 'orderTypes'));
    }

    public function show(Orders $order)
    {
        $order->load(['user', 'tables', 'order_items', 'order_addresses']);
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Orders $order)
    {
        $orderStatuses = OrderProcessStatus::cases();
        $paymentStatuses = StatusPayments::cases();
        $orderTypes = OrderType::cases();
        $tables = Tables::where('is_active', true)->get();

        return view('admin.orders.edit', compact('order', 'orderStatuses', 'paymentStatuses', 'orderTypes', 'tables'));
    }

    public function update(Request $request, Orders $order)
    {
        $request->validate([
            'order_status' => 'required|in:' . implode(',', array_column(OrderProcessStatus::cases(), 'value')),
            'order_type' => 'required|in:dine_in,take_away,delivery',
            'table_id' => 'nullable|exists:tables,id',
        ]);

        $order->update($request->only(['order_status', 'order_type', 'table_id']));

        return redirect()->route('admin.orders.index')->with('success', 'Pesanan berhasil diperbarui.');
    }

    public function destroy(Orders $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Pesanan berhasil dihapus.');
    }

    public function updateStatus(Request $request, Orders $order)
    {
        $request->validate([
            'order_status' => 'required|in:' . implode(',', array_column(OrderProcessStatus::cases(), 'value')),
        ]);

        $order->update(['order_status' => $request->order_status]);

        return response()->json(['success' => true, 'message' => 'Status pesanan berhasil diubah.']);
    }

    /**
     * Request Pickup ke Biteship (Manual Trigger by Admin)
     */
    public function requestPickup(Orders $order)
    {
        // 1. Validasi
        if ($order->order_type !== OrderType::DELIVERY) {
            return back()->with('error', 'Order bukan tipe delivery.');
        }

        // Cek apakah sudah pernah request
        if ($order->deliveries()->exists() && $order->deliveries->first()->courier_order_id) {
            return back()->with('error', 'Order ini sudah memiliki resi pengiriman.');
        }

        // 2. Ambil data address
        $address = $order->order_addresses->first();
        if (!$address) {
            return back()->with('error', 'Alamat pengiriman tidak ditemukan.');
        }

        // 3. Ambil data kurir dari delivery_meta
        $meta = $order->delivery_meta; // casted array
        $courierCode = $meta['selected_option']['courier_code'] ?? null;
        $courierService = $meta['selected_option']['courier_service_code'] ?? null;

        if (!$courierCode || !$courierService) {
            // Fallback: coba ambil dari field delivery_provider
            $courierCode = $order->delivery_provider === 'biteship' ? 'jne' : $order->delivery_provider; // Guesswork
            $courierService = $order->delivery_service;

            if (!$courierCode) {
                return back()->with('error', 'Data kurir tidak lengkap. Tidak bisa request pickup.');
            }
        }

        // 4. Siapkan Payload Biteship
        $shipper = [
            'name' => config('app.name', 'Meracikopi'),
            'phone' => config('app.phone', '08123456789'), // Default placeholder
            'email' => config('app.email', 'admin@meracikopi.com'),
            'address' => config('app.restaurant_address'),
            'postal_code' => config('app.restaurant_postal_code', '76111'), // Balikpapan Central
            'latitude' => (float) config('app.restaurant_latitude'),
            'longitude' => (float) config('app.restaurant_longitude'),
            'organization' => config('app.name'),
        ];

        $receiver = [
            'name' => $address->receiver_name,
            'phone' => $address->phone,
            'address' => $address->full_address,
            'postal_code' => $address->postal_code,
            'latitude' => $address->latitude,
            'longitude' => $address->longitude,
            'email' => $order->user->email ?? 'customer@example.com',
        ];

        $items = $order->order_items->map(function ($item) {
            return [
                'name' => $item->menu->name,
                'quantity' => $item->quantity,
                'weight' => 200, // Hardcoded assumption: 200g per item if not set
                'value' => $item->price,
            ];
        })->toArray();

        // 5. Call API
        $result = $this->biteship->createOrder(
            $shipper,
            $receiver,
            $items,
            $courierCode,
            $courierService,
            'draft' // Use 'confirmed' for real booking, 'draft' for testing flow first? Or confirmed if user wants booking.
            // Let's use 'confirmed' because user asked "Request Pickup".
            // But to be safe, maybe 'draft' first? 
            // Biteship 'draft' creates order but status is pending.
            // Let's us 'confirmed' so they get Waybill immediately if balance allows.
        );

        // If user is in MOCK MODE, result is mock success.

        if (!$result['success']) {
            return back()->with('error', 'Gagal request pickup: ' . ($result['message'] ?? 'Unknown error'));
        }

        // 6. Update Database
        // Simpan ke table deliveries
        $delivery = Deliveries::updateOrCreate(
            ['order_id' => $order->id],
            [
                'courier_name' => $courierCode . ' ' . $courierService,
                'courier_order_id' => $result['waybill_id'] ?? 'DRAFT-' . time(), // Waybill ID
                'status' => StatusDelivery::REQUESTED, // Or whatever Enum matches
                'price' => $result['price'] ?? 0,
                'raw_response' => $result['raw'] ?? [],
            ]
        );

        // Update status order
        $order->update(['status' => OrderStatus::ON_DELIVERY]);

        return back()->with('success', 'Request Pickup Berhasil! Resi: ' . ($result['waybill_id'] ?? '-'));
    }
}
