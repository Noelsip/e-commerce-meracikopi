<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Services\Shipping\ShippingQuoteService;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function __construct(private readonly ShippingQuoteService $shipping)
    {
    }

    /**
     * Calculate delivery fee based on address
     *
     * POST /api/customer/delivery/calculate-fee
     *
     * Request body:
     * {
     *   "origin": {
     *     "latitude": -6.200000,
     *     "longitude": 106.816666,
     *     "address": "Jl. Sudirman No. 1, Jakarta"
     *   },
     *   "destination": {
     *     "latitude": -6.175110,
     *     "longitude": 106.865036,
     *     "address": "Jl. Gatot Subroto, Jakarta"
     *   }
     * }
     */
    public function calculateFee(Request $request)
    {
        $request->validate([
            'destination' => 'required|array',
            'destination.latitude' => 'required|numeric',
            'destination.longitude' => 'required|numeric',
            'destination.address' => 'required|string',
        ]);

        $destination = $request->destination;
        $quote = $this->shipping->quote([
            'channel' => 'ondemand',
            'destination' => $destination,
            'payload' => $request->input('payload', []),
        ]);

        $best = collect($quote['options'] ?? [])
            ->filter(fn ($opt) => is_numeric($opt['price'] ?? null))
            ->sortBy('price')
            ->first();

        $deliveryFee = (int) ($best['price'] ?? 0);

        return response()->json([
            'data' => [
                'distance' => (float) ($quote['meta']['distance_km'] ?? 0),
                'distance_unit' => 'km',
                'delivery_fee' => (int) $deliveryFee,
                'estimated_time' => (string) ($quote['meta']['estimated_time'] ?? ''),
                'origin' => $quote['origin'] ?? null,
                'destination' => $destination,
                'quote_id' => $quote['quote_id'] ?? null,
                'options' => $quote['options'] ?? [],
            ],
        ], 200);
    }

    /**
     * Get available delivery options
     *
     * GET /api/customer/delivery/options
     */
    public function getOptions()
    {
        return response()->json([
            'data' => $this->shipping->providers(),
        ], 200);
    }
}
