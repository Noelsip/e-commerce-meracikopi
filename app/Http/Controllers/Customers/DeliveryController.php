<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Services\DeliveryService;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    protected $deliveryService;

    public function __construct(DeliveryService $deliveryService)
    {
        $this->deliveryService = $deliveryService;
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

        // Origin (lokasi resto/cafe)
        // Bisa disimpan di config atau database
        $origin = [
            'latitude' => config('app.restaurant_latitude', -6.200000),
            'longitude' => config('app.restaurant_longitude', 106.816666),
            'address' => config('app.restaurant_address', 'Jl. Sudirman No. 1, Jakarta'),
        ];

        $destination = $request->destination;

        // Hitung jarak
        $distance = $this->deliveryService->calculateDistance(
            $origin['latitude'],
            $origin['longitude'],
            $destination['latitude'],
            $destination['longitude']
        );

        // Option 1: Gunakan third party API (jika tersedia)
        // $result = $this->deliveryService->calculateDeliveryFee($origin, $destination);

        // Option 2: Gunakan perhitungan internal berdasarkan jarak
        $deliveryFee = $this->deliveryService->calculateDeliveryFeeByDistance($distance);

        return response()->json([
            'data' => [
                'distance' => round($distance, 2),
                'distance_unit' => 'km',
                'delivery_fee' => $deliveryFee,
                'estimated_time' => $this->estimateDeliveryTime($distance),
                'origin' => $origin,
                'destination' => $destination,
            ]
        ], 200);
    }

    /**
     * Estimate delivery time based on distance
     * 
     * Asumsi: 
     * - Kecepatan rata-rata 20 km/jam
     * - Tambah 15 menit untuk persiapan
     * 
     * @param float $distance
     * @return string
     */
    private function estimateDeliveryTime($distance)
    {
        $averageSpeed = 20; // km/jam
        $preparationTime = 15; // menit

        $travelTime = ($distance / $averageSpeed) * 60; // menit
        $totalTime = ceil($travelTime + $preparationTime);

        return $totalTime . ' minutes';
    }

    /**
     * Get available delivery options
     * 
     * GET /api/customer/delivery/options
     */
    public function getOptions()
    {
        return response()->json([
            'data' => [
                [
                    'id' => 'standard',
                    'name' => 'Standard Delivery',
                    'description' => 'Delivery dalam 45-60 menit',
                    'min_time' => 45,
                    'max_time' => 60,
                ],
                [
                    'id' => 'express',
                    'name' => 'Express Delivery',
                    'description' => 'Delivery dalam 30-45 menit',
                    'min_time' => 30,
                    'max_time' => 45,
                    'additional_fee' => 5000,
                ],
            ]
        ], 200);
    }
}
