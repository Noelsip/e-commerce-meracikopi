<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service untuk integrasi dengan third party delivery service
 *
 * Contoh third party yang bisa digunakan:
 * - GoSend API
 * - Grab Express API
 * - JNE API
 * - SiCepat API
 * - Custom delivery service
 */
class DeliveryService
{
    protected $apiUrl;

    protected $apiKey;

    public function __construct()
    {
        // Load dari config atau .env
        $this->apiUrl = config('services.delivery.api_url', env('DELIVERY_API_URL'));
        $this->apiKey = config('services.delivery.api_key', env('DELIVERY_API_KEY'));
    }

    /**
     * Menghitung ongkir dari third party API
     *
     * @param  array  $origin  Koordinat atau alamat asal
     * @param  array  $destination  Koordinat atau alamat tujuan
     * @param  float  $weight  Berat dalam kg (opsional)
     * @return array
     */
    public function calculateDeliveryFee($origin, $destination, $weight = 1)
    {
        try {
            // Contoh request ke third party API
            // Sesuaikan dengan API yang digunakan
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl.'/calculate', [
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $weight,
            ]);

            if ($response->ok()) {
                $data = $response->json();

                return [
                    'success' => true,
                    'delivery_fee' => $data['fee'] ?? 0,
                    'distance' => $data['distance'] ?? 0,
                    'estimated_time' => $data['estimated_time'] ?? null,
                    'raw_response' => $data,
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to calculate delivery fee',
                'delivery_fee' => 0,
            ];
        } catch (\Exception $e) {
            Log::error('Delivery Service Error: '.$e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'delivery_fee' => 0,
            ];
        }
    }

    /**
     * Menghitung ongkir berdasarkan jarak (fallback method)
     *
     * Rumus sederhana:
     * - 0-5 km: Rp 10.000
     * - 5-10 km: Rp 15.000
     * - 10-15 km: Rp 20.000
     * - >15 km: Rp 25.000 + (Rp 2.000 per km)
     *
     * @param  float  $distance  Jarak dalam km
     * @return int
     */
    public function calculateDeliveryFeeByDistance($distance)
    {
        if ($distance <= 5) {
            return 10000;
        } elseif ($distance <= 10) {
            return 15000;
        } elseif ($distance <= 15) {
            return 20000;
        } else {
            $extraDistance = $distance - 15;
            $extraKm = (int) ceil($extraDistance);

            return 25000 + ($extraKm * 2000);
        }
    }

    /**
     * Menghitung jarak antara dua koordinat (Haversine formula)
     *
     * @param  float  $lat1  Latitude titik 1
     * @param  float  $lon1  Longitude titik 1
     * @param  float  $lat2  Latitude titik 2
     * @param  float  $lon2  Longitude titik 2
     * @return float Jarak dalam km
     */
    public function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Request pickup driver dari third party
     *
     * @param  int  $orderId
     * @param  array  $pickupAddress
     * @param  array  $deliveryAddress
     * @return array
     */
    public function requestDriver($orderId, $pickupAddress, $deliveryAddress)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl.'/request-driver', [
                'order_id' => $orderId,
                'pickup' => $pickupAddress,
                'delivery' => $deliveryAddress,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'success' => true,
                    'driver_id' => $data['driver_id'] ?? null,
                    'driver_name' => $data['driver_name'] ?? null,
                    'driver_phone' => $data['driver_phone'] ?? null,
                    'estimated_pickup_time' => $data['estimated_pickup_time'] ?? null,
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to request driver',
            ];
        } catch (\Exception $e) {
            Log::error('Request Driver Error: '.$e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Track delivery status
     *
     * @param  string  $deliveryId
     * @return array
     */
    public function trackDelivery($deliveryId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->apiKey,
            ])->get($this->apiUrl.'/track/'.$deliveryId);

            if ($response->ok()) {
                return $response->json();
            }

            return [
                'success' => false,
                'message' => 'Failed to track delivery',
            ];
        } catch (\Exception $e) {
            Log::error('Track Delivery Error: '.$e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
