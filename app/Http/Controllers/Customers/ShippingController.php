<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Services\Shipping\BiteshipClient;
use App\Services\Shipping\ShippingQuoteService;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function __construct(
        private readonly ShippingQuoteService $shipping,
        private readonly BiteshipClient $biteship,
    ) {
    }

    public function providers()
    {
        return response()->json([
            'data' => $this->shipping->providers(),
        ], 200);
    }

    public function quote(Request $request)
    {
        $validated = $request->validate([
            'channel' => 'required|in:ondemand,courier',
            'destination' => 'required|array',
            'destination.address' => 'required|string',
            'destination.city' => 'nullable|string',
            'destination.postal_code' => 'nullable|string',
            'destination.latitude' => 'nullable|numeric',
            'destination.longitude' => 'nullable|numeric',
            'destination.rajaongkir_destination_id' => 'nullable|integer',
            'weight_grams' => 'nullable|integer|min:1',
            'couriers' => 'nullable|string',
            'price_sort' => 'nullable|in:lowest,highest',
            'payload' => 'nullable|array',
        ]);

        $quote = $this->shipping->quote($validated);

        return response()->json([
            'data' => $quote,
        ], 200);
    }

    /**
     * Track pengiriman berdasarkan waybill (resi)
     *
     * GET /api/customer/shipping/track?waybill_id=RESI123&courier_code=jne
     */
    public function track(Request $request)
    {
        $validated = $request->validate([
            'waybill_id' => 'required|string',
            'courier_code' => 'required|string',
        ]);

        if (!$this->biteship->isConfigured()) {
            return response()->json([
                'message' => 'Tracking service is not available',
            ], 503);
        }

        $result = $this->biteship->trackByWaybill(
            $validated['waybill_id'],
            $validated['courier_code']
        );

        if (!($result['success'] ?? false)) {
            return response()->json([
                'message' => $result['message'] ?? 'Failed to track shipment',
            ], 502);
        }

        return response()->json([
            'data' => $result['data'],
        ], 200);
    }

    public function rajaOngkirProvinces()
    {
        $result = $this->shipping->rajaOngkirListProvinces();

        if (!($result['success'] ?? false)) {
            return response()->json([
                'message' => $result['message'] ?? 'Failed to fetch provinces',
                'raw' => $result['raw'] ?? null,
            ], 502);
        }

        return response()->json([
            'data' => $result['data'],
        ], 200);
    }

    public function rajaOngkirDestinations(Request $request)
    {
        $validated = $request->validate([
            'search' => 'required|string|min:2',
            'limit' => 'nullable|integer|min:1|max:50',
            'offset' => 'nullable|integer|min:0',
        ]);

        $result = $this->shipping->rajaOngkirSearchDestination(
            $validated['search'],
            (int) ($validated['limit'] ?? 10),
            (int) ($validated['offset'] ?? 0),
        );

        if (!($result['success'] ?? false)) {
            return response()->json([
                'message' => $result['message'] ?? 'Failed to fetch destinations',
                'raw' => $result['raw'] ?? null,
            ], 502);
        }

        return response()->json([
            'data' => $result['data'],
        ], 200);
    }
}
