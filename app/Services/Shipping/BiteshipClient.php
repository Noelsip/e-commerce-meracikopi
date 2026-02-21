<?php

namespace App\Services\Shipping;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Biteship API Client
 *
 * Dokumentasi: https://biteship.com/en/docs
 * Base URL: https://api.biteship.com/v1
 *
 * Authentication: Bearer token via API Key
 * Header: Authorization: Bearer <API_KEY>
 */
class BiteshipClient
{
    private string $baseUrl;
    private ?string $apiKey;
    private int $timeout;
    private bool $mockMode;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.biteship.base_url', 'https://api.biteship.com/v1'), '/');
        $this->apiKey = config('services.biteship.api_key');
        $this->timeout = (int) config('services.biteship.timeout_seconds', 15);
        $this->mockMode = (bool) config('services.biteship.mock_mode', false);
    }

    /**
     * Cek apakah dalam mock/development mode
     */
    public function isMockMode(): bool
    {
        return $this->mockMode;
    }

    /**
     * Cek apakah Biteship sudah dikonfigurasi
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Cek ongkir / Retrieve shipping rates
     *
     * @param array $origin ['latitude' => float, 'longitude' => float, 'postal_code' => string]
     * @param array $destination ['latitude' => float, 'longitude' => float, 'postal_code' => string]
     * @param array $items [['name' => string, 'quantity' => int, 'weight' => int (gram), 'value' => int]]
     * @param array $couriers ['jne', 'sicepat', 'jnt', 'anteraja', 'grab', 'gojek', etc.]
     * @return array
     */
    public function getRates(array $origin, array $destination, array $items = [], array $couriers = []): array
    {
        if ($this->mockMode) {
            return $this->mockGetRates($origin, $destination, $items, $couriers);
        }

        try {
            $payload = [];

            // Origin - bisa pakai koordinat atau postal_code
            if (!empty($origin['latitude']) && !empty($origin['longitude'])) {
                $payload['origin_latitude'] = (float) $origin['latitude'];
                $payload['origin_longitude'] = (float) $origin['longitude'];
            }
            if (!empty($origin['postal_code'])) {
                $payload['origin_postal_code'] = (string) $origin['postal_code'];
            }

            // Destination - bisa pakai koordinat atau postal_code
            if (!empty($destination['latitude']) && !empty($destination['longitude'])) {
                $payload['destination_latitude'] = (float) $destination['latitude'];
                $payload['destination_longitude'] = (float) $destination['longitude'];
            }
            if (!empty($destination['postal_code'])) {
                $payload['destination_postal_code'] = (string) $destination['postal_code'];
            }

            // Couriers
            if (!empty($couriers)) {
                $payload['couriers'] = implode(',', $couriers);
            } else {
                // Default couriers dari config
                $defaultCouriers = config('services.biteship.default_couriers', 'jne,sicepat,jnt,anteraja');
                $payload['couriers'] = $defaultCouriers;
            }

            // Items (minimal 1 item diperlukan)
            if (!empty($items)) {
                $payload['items'] = array_map(function ($item) {
                    return [
                        'name' => (string) ($item['name'] ?? 'Item'),
                        'quantity' => (int) ($item['quantity'] ?? 1),
                        'weight' => (int) ($item['weight'] ?? 500), // gram
                        'value' => (int) ($item['value'] ?? 0),
                    ];
                }, $items);
            } else {
                // Default single item
                $payload['items'] = [
                    [
                        'name' => 'Package',
                        'quantity' => 1,
                        'weight' => (int) config('services.biteship.default_weight_grams', 1000),
                        'value' => 0,
                    ],
                ];
            }

            $response = $this->client()->post($this->baseUrl . '/rates/couriers', $payload);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'success' => (bool) ($data['success'] ?? false),
                    'data' => $data['pricing'] ?? [],
                    'origin' => $data['origin'] ?? null,
                    'destination' => $data['destination'] ?? null,
                    'raw' => $data,
                ];
            }

            $errorBody = $response->json();
            Log::warning('Biteship getRates failed', [
                'status' => $response->status(),
                'body' => $errorBody,
            ]);

            return [
                'success' => false,
                'message' => $errorBody['error'] ?? 'Failed to retrieve rates from Biteship',
                'data' => [],
            ];
        } catch (\Exception $e) {
            Log::error('Biteship getRates error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ];
        }
    }

    /**
     * Buat order pengiriman di Biteship
     *
     * @param array $shipper Info pengirim ['name', 'phone', 'email', 'address', 'postal_code', 'coordinate' => ['latitude', 'longitude']]
     * @param array $receiver Info penerima (sama format dengan shipper) 
     * @param array $items [['name', 'quantity', 'weight', 'value']]
     * @param string $courierCode Kode kurir (e.g. 'jne')
     * @param string $courierService Service type (e.g. 'reg')
     * @param string $type 'draft' atau 'confirmed'
     * @return array
     */
    public function createOrder(
        array $shipper,
        array $receiver,
        array $items,
        string $courierCode,
        string $courierService,
        string $type = 'draft'
    ): array {
        if ($this->mockMode) {
            return $this->mockCreateOrder($courierCode, $courierService);
        }

        try {
            $payload = [
                'shipper_contact_name' => $shipper['name'] ?? '',
                'shipper_contact_phone' => $shipper['phone'] ?? '',
                'shipper_contact_email' => $shipper['email'] ?? '',
                'shipper_organization' => $shipper['organization'] ?? config('app.name'),
                'origin_contact_name' => $shipper['name'] ?? '',
                'origin_contact_phone' => $shipper['phone'] ?? '',
                'origin_address' => $shipper['address'] ?? '',
                'origin_postal_code' => (string) ($shipper['postal_code'] ?? ''),
                'origin_coordinate' => [
                    'latitude' => (float) ($shipper['coordinate']['latitude'] ?? $shipper['latitude'] ?? 0),
                    'longitude' => (float) ($shipper['coordinate']['longitude'] ?? $shipper['longitude'] ?? 0),
                ],
                'destination_contact_name' => $receiver['name'] ?? '',
                'destination_contact_phone' => $receiver['phone'] ?? '',
                'destination_contact_email' => $receiver['email'] ?? '',
                'destination_address' => $receiver['address'] ?? '',
                'destination_postal_code' => (string) ($receiver['postal_code'] ?? ''),
                'destination_coordinate' => [
                    'latitude' => (float) ($receiver['coordinate']['latitude'] ?? $receiver['latitude'] ?? 0),
                    'longitude' => (float) ($receiver['coordinate']['longitude'] ?? $receiver['longitude'] ?? 0),
                ],
                'courier_company' => $courierCode,
                'courier_type' => $courierService,
                'delivery_type' => 'now',
                'order_note' => $shipper['note'] ?? '',
                'items' => array_map(function ($item) {
                    return [
                        'name' => (string) ($item['name'] ?? 'Item'),
                        'quantity' => (int) ($item['quantity'] ?? 1),
                        'weight' => (int) ($item['weight'] ?? 500),
                        'value' => (int) ($item['value'] ?? 0),
                    ];
                }, $items),
            ];

            if ($type === 'draft') {
                $payload['type'] = 'draft';
            }

            $response = $this->client()->post($this->baseUrl . '/orders', $payload);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'success' => true,
                    'order_id' => $data['id'] ?? null,
                    'waybill_id' => $data['courier']['waybill_id'] ?? null,
                    'tracking_id' => $data['courier']['tracking_id'] ?? null,
                    'courier' => $data['courier'] ?? null,
                    'status' => $data['status'] ?? null,
                    'price' => $data['price'] ?? null,
                    'raw' => $data,
                ];
            }

            $errorBody = $response->json();
            Log::warning('Biteship createOrder failed', [
                'status' => $response->status(),
                'body' => $errorBody,
            ]);

            return [
                'success' => false,
                'message' => $errorBody['error'] ?? 'Failed to create order in Biteship',
            ];
        } catch (\Exception $e) {
            Log::error('Biteship createOrder error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Track pengiriman berdasarkan order ID Biteship
     *
     * @param string $orderId Biteship order ID
     * @return array
     */
    public function trackOrder(string $orderId): array
    {
        if ($this->mockMode) {
            return $this->mockTrackOrder($orderId);
        }

        try {
            $response = $this->client()->get($this->baseUrl . '/trackings/' . $orderId);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to track order',
            ];
        } catch (\Exception $e) {
            Log::error('Biteship trackOrder error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Track pengiriman berdasarkan waybill (resi) publik
     *
     * @param string $waybillId Nomor resi
     * @param string $courierCode Kode kurir (e.g. 'jne', 'sicepat')
     * @return array
     */
    public function trackByWaybill(string $waybillId, string $courierCode): array
    {
        if ($this->mockMode) {
            return $this->mockTrackByWaybill($waybillId, $courierCode);
        }

        try {
            $response = $this->client()->get($this->baseUrl . '/trackings', [
                'waybill_id' => $waybillId,
                'courier_code' => $courierCode,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to track waybill',
            ];
        } catch (\Exception $e) {
            Log::error('Biteship trackByWaybill error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Konfirmasi draft order
     *
     * @param string $orderId Biteship order ID
     * @return array
     */
    public function confirmOrder(string $orderId): array
    {
        try {
            $response = $this->client()->post($this->baseUrl . '/orders/' . $orderId . '/confirm');

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to confirm order',
            ];
        } catch (\Exception $e) {
            Log::error('Biteship confirmOrder error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Cancel order
     *
     * @param string $orderId Biteship order ID
     * @param string $reason Alasan cancel
     * @return array
     */
    public function cancelOrder(string $orderId, string $reason = ''): array
    {
        try {
            $response = $this->client()->delete($this->baseUrl . '/orders/' . $orderId, [
                'reason' => $reason,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to cancel order',
            ];
        } catch (\Exception $e) {
            Log::error('Biteship cancelOrder error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * HTTP client dengan authorization header
     */
    private function client(): PendingRequest
    {
        return Http::acceptJson()
            ->timeout($this->timeout)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ]);
    }

    // =====================================================
    // MOCK METHODS (untuk development/testing tanpa saldo)
    // =====================================================

    private function mockGetRates(array $origin, array $destination, array $items, array $couriers): array
    {
        Log::info('[MOCK] Biteship getRates called', compact('origin', 'destination'));

        $mockCouriers = [
            [
                'code' => 'jne',
                'name' => 'JNE',
                'services' => [
                    ['code' => 'reg', 'name' => 'JNE Reguler', 'price' => 15000, 'etd' => '2-3 hari'],
                    ['code' => 'yes', 'name' => 'JNE YES', 'price' => 25000, 'etd' => '1 hari'],
                    ['code' => 'oke', 'name' => 'JNE OKE', 'price' => 10000, 'etd' => '3-5 hari'],
                ]
            ],
            [
                'code' => 'sicepat',
                'name' => 'SiCepat',
                'services' => [
                    ['code' => 'reg', 'name' => 'SiCepat Reguler', 'price' => 12000, 'etd' => '2-3 hari'],
                    ['code' => 'best', 'name' => 'SiCepat BEST', 'price' => 18000, 'etd' => '1-2 hari'],
                ]
            ],
            [
                'code' => 'jnt',
                'name' => 'J&T Express',
                'services' => [
                    ['code' => 'ez', 'name' => 'J&T EZ', 'price' => 11000, 'etd' => '3-5 hari'],
                    ['code' => 'reg', 'name' => 'J&T Reguler', 'price' => 13000, 'etd' => '2-3 hari'],
                ]
            ],
            [
                'code' => 'anteraja',
                'name' => 'AnterAja',
                'services' => [
                    ['code' => 'reg', 'name' => 'AnterAja Reguler', 'price' => 12000, 'etd' => '2-3 hari'],
                    ['code' => 'next', 'name' => 'AnterAja Next Day', 'price' => 22000, 'etd' => '1 hari'],
                ]
            ],
            [
                'code' => 'ninja',
                'name' => 'Ninja Xpress',
                'services' => [
                    ['code' => 'reg', 'name' => 'Ninja Standard', 'price' => 14000, 'etd' => '2-4 hari'],
                ]
            ],
            [
                'code' => 'tiki',
                'name' => 'TIKI',
                'services' => [
                    ['code' => 'reg', 'name' => 'TIKI Reguler', 'price' => 13000, 'etd' => '3-4 hari'],
                    ['code' => 'ons', 'name' => 'TIKI ONS', 'price' => 28000, 'etd' => '1 hari'],
                ]
            ],
        ];

        // Filter berdasarkan couriers yang diminta
        if (!empty($couriers)) {
            $mockCouriers = array_filter($mockCouriers, fn($c) => in_array($c['code'], $couriers));
        }

        $pricing = [];
        foreach ($mockCouriers as $courier) {
            foreach ($courier['services'] as $service) {
                $pricing[] = [
                    'courier_code' => $courier['code'],
                    'courier_name' => $courier['name'],
                    'courier_service_code' => $service['code'],
                    'courier_service_name' => $service['name'],
                    'price' => $service['price'],
                    'type' => 'courier',
                    'shipment_duration_range' => $service['etd'],
                    'shipment_duration_unit' => 'hari',
                ];
            }
        }

        return [
            'success' => true,
            'data' => $pricing,
            'origin' => $origin,
            'destination' => $destination,
            'raw' => ['mock' => true, 'pricing' => $pricing],
        ];
    }

    private function mockCreateOrder(string $courierCode, string $courierService): array
    {
        $mockOrderId = 'mock-' . Str::uuid();
        $mockWaybillId = 'MOCK' . strtoupper(Str::random(10));

        Log::info('[MOCK] Biteship createOrder called', [
            'order_id' => $mockOrderId,
            'courier' => $courierCode,
            'service' => $courierService,
        ]);

        return [
            'success' => true,
            'order_id' => $mockOrderId,
            'waybill_id' => $mockWaybillId,
            'tracking_id' => $mockWaybillId,
            'courier' => [
                'company' => $courierCode,
                'type' => $courierService,
                'waybill_id' => $mockWaybillId,
                'tracking_id' => $mockWaybillId,
            ],
            'status' => 'confirmed',
            'price' => 15000,
            'raw' => ['mock' => true],
        ];
    }

    private function mockTrackOrder(string $orderId): array
    {
        Log::info('[MOCK] Biteship trackOrder called', ['order_id' => $orderId]);

        return [
            'success' => true,
            'data' => [
                'id' => $orderId,
                'status' => 'dropping_off',
                'courier' => [
                    'company' => 'jne',
                    'driver_name' => 'Mock Driver',
                    'driver_phone' => '081234567890',
                ],
                'history' => [
                    [
                        'status' => 'confirmed',
                        'note' => 'Pesanan dikonfirmasi',
                        'updated_at' => now()->subHours(5)->toIso8601String(),
                    ],
                    [
                        'status' => 'picking_up',
                        'note' => 'Kurir menuju lokasi pickup',
                        'updated_at' => now()->subHours(4)->toIso8601String(),
                    ],
                    [
                        'status' => 'picked',
                        'note' => 'Paket sudah dipickup',
                        'updated_at' => now()->subHours(3)->toIso8601String(),
                    ],
                    [
                        'status' => 'dropping_off',
                        'note' => 'Paket dalam perjalanan ke tujuan',
                        'updated_at' => now()->subHour()->toIso8601String(),
                    ],
                ],
            ],
        ];
    }

    private function mockTrackByWaybill(string $waybillId, string $courierCode): array
    {
        Log::info('[MOCK] Biteship trackByWaybill called', [
            'waybill_id' => $waybillId,
            'courier_code' => $courierCode,
        ]);

        return [
            'success' => true,
            'data' => [
                'courier' => [
                    'company' => $courierCode,
                    'waybill_id' => $waybillId,
                ],
                'status' => 'dropping_off',
                'history' => [
                    [
                        'status' => 'confirmed',
                        'note' => 'Pesanan dikonfirmasi',
                        'service_type' => 'pickup',
                        'updated_at' => now()->subHours(5)->toIso8601String(),
                    ],
                    [
                        'status' => 'picked',
                        'note' => 'Paket telah dipickup oleh kurir [Mock Hub Jakarta]',
                        'service_type' => 'pickup',
                        'updated_at' => now()->subHours(3)->toIso8601String(),
                    ],
                    [
                        'status' => 'dropping_off',
                        'note' => 'Paket sedang dalam pengiriman ke alamat tujuan',
                        'service_type' => 'delivery',
                        'updated_at' => now()->subHour()->toIso8601String(),
                    ],
                ],
            ],
        ];
    }
}
