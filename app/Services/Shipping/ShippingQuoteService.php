<?php

namespace App\Services\Shipping;

use App\Services\DeliveryService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ShippingQuoteService
{
    public function __construct(
        private readonly RajaOngkirClient $rajaOngkir,
        private readonly GrabExpressClient $grabExpress,
        private readonly GoSendClient $goSend,
        private readonly BiteshipClient $biteship,
        private readonly DeliveryService $distanceCalculator,
    ) {
    }

    public function providers(): array
    {
        return [
            [
                'id' => 'rajaongkir',
                'type' => 'courier',
                'available' => $this->rajaOngkir->isConfigured() && !empty($this->getRajaOngkirOriginId()),
            ],
            [
                'id' => 'biteship',
                'type' => 'courier',
                'available' => $this->biteship->isConfigured(),
            ],
            [
                'id' => 'grabexpress',
                'type' => 'ondemand',
                'available' => $this->grabExpress->isConfigured(),
            ],
            [
                'id' => 'gosend',
                'type' => 'ondemand',
                'available' => $this->goSend->isConfigured(),
            ],
            [
                'id' => 'distance_fallback',
                'type' => 'ondemand',
                'available' => true,
            ],
        ];
    }

    public function quote(array $input): array
    {
        $channel = (string) ($input['channel'] ?? 'ondemand');
        $origin = $this->getOrigin();
        $destination = (array) ($input['destination'] ?? []);

        $options = [];
        $meta = [
            'channel' => $channel,
        ];

        if ($channel === 'courier') {
            $options = $this->quoteCourier($origin, $destination, $input, $meta);
        } else {
            $options = $this->quoteOnDemand($origin, $destination, $input, $meta);
        }

        $quoteId = (string) Str::uuid();
        $quotePayload = [
            'quote_id' => $quoteId,
            'origin' => $origin,
            'destination' => $destination,
            'channel' => $channel,
            'options' => $options,
            'meta' => $meta,
            'created_at' => now()->toIso8601String(),
        ];

        Cache::put($this->cacheKey($quoteId), $quotePayload, now()->addMinutes(30));

        return $quotePayload;
    }

    public function getQuote(string $quoteId): ?array
    {
        $quote = Cache::get($this->cacheKey($quoteId));

        return is_array($quote) ? $quote : null;
    }

    public function getOptionFromQuote(string $quoteId, string $optionId): ?array
    {
        $quote = $this->getQuote($quoteId);
        if (!$quote) {
            return null;
        }

        foreach (($quote['options'] ?? []) as $option) {
            if (($option['id'] ?? null) === $optionId) {
                return $option;
            }
        }

        return null;
    }

    public function rajaOngkirListProvinces(): array
    {
        return $this->rajaOngkir->listProvinces();
    }

    public function rajaOngkirSearchDestination(string $search, int $limit = 10, int $offset = 0): array
    {
        return $this->rajaOngkir->searchDomesticDestination($search, $limit, $offset);
    }

    private function quoteCourier(array $origin, array $destination, array $input, array &$meta): array
    {
        $options = [];

        // === Biteship (prioritas utama) ===
        if ($this->biteship->isConfigured()) {
            $biteshipOptions = $this->quoteBiteship($origin, $destination, $input, $meta);
            $options = array_merge($options, $biteshipOptions);
        }

        // === RajaOngkir (fallback jika Biteship tidak dikonfigurasi atau gagal) ===
        if (empty($options)) {
            $rajaOngkirOptions = $this->quoteRajaOngkir($origin, $destination, $input, $meta);
            $options = array_merge($options, $rajaOngkirOptions);
        }

        return $options;
    }

    /**
     * Quote via Biteship API
     */
    private function quoteBiteship(array $origin, array $destination, array $input, array &$meta): array
    {
        $items = [];
        if (!empty($input['items'])) {
            $items = $input['items'];
        } else {
            $items = [
                [
                    'name' => 'Package',
                    'quantity' => 1,
                    'weight' => (int) ($input['weight_grams'] ?? $this->defaultWeightGrams()),
                    'value' => (int) ($input['total_value'] ?? 0),
                ]
            ];
        }

        $couriers = [];
        if (!empty($input['couriers'])) {
            // Konversi dari format colon-separated ke array
            $couriers = is_array($input['couriers'])
                ? $input['couriers']
                : explode(',', str_replace(':', ',', $input['couriers']));
        }

        // Enhance origin with specific Biteship config if present
        $biteshipOrigin = $origin;
        if (config('services.biteship.store_address')) {
            $biteshipOrigin['address'] = config('services.biteship.store_address');
        }
        if (config('services.biteship.store_postal_code')) {
            $biteshipOrigin['postal_code'] = (string) config('services.biteship.store_postal_code');
            // Remove coordinates to prevent conflict/mismatch with postal code
            unset($biteshipOrigin['latitude']);
            unset($biteshipOrigin['longitude']);
        }

        $result = $this->biteship->getRates($biteshipOrigin, $destination, $items, $couriers);

        $meta['biteship'] = [
            'origin' => $origin,
            'destination' => $destination,
        ];

        if (!($result['success'] ?? false) || empty($result['data'])) {
            $meta['biteship_error'] = $result['message'] ?? 'Failed to fetch Biteship rates';

            return [];
        }

        $options = [];
        foreach ($result['data'] as $pricing) {
            $courierCode = (string) ($pricing['courier_code'] ?? '');
            $courierService = (string) ($pricing['courier_service_code'] ?? '');
            $courierName = (string) ($pricing['courier_name'] ?? '');
            $serviceName = (string) ($pricing['courier_service_name'] ?? '');
            $price = $pricing['price'] ?? null;
            $type = (string) ($pricing['type'] ?? '');

            if ($courierCode === '' || !is_numeric($price)) {
                continue;
            }

            $optionId = 'biteship:' . $courierCode . ':' . Str::slug($courierService ?: $serviceName, '_');

            $options[] = [
                'id' => $optionId,
                'provider' => 'biteship',
                'service' => $serviceName ?: $courierService,
                'courier_code' => $courierCode,
                'courier_name' => $courierName,
                'courier_service_code' => $courierService,
                'price' => $this->biteship->isMockMode() ? 0 : (int) $price,
                'currency' => 'IDR',
                'etd' => (string) ($pricing['shipment_duration_range'] ?? $pricing['duration'] ?? ''),
                'type' => $type,
                'raw' => $pricing,
            ];
        }

        return $options;
    }

    /**
     * Quote via RajaOngkir API
     */
    private function quoteRajaOngkir(array $origin, array $destination, array $input, array &$meta): array
    {
        $originId = $this->getRajaOngkirOriginId();
        $destinationId = (int) ($destination['rajaongkir_destination_id'] ?? 0);
        $weightGrams = (int) ($input['weight_grams'] ?? $this->defaultWeightGrams());

        if (!$this->rajaOngkir->isConfigured() || empty($originId)) {
            return [];
        }

        // Auto-lookup destination ID by postal code if not provided
        if ($destinationId <= 0 && !empty($destination['postal_code'])) {
            $cacheKey = 'rajaongkir_dest_postal:' . $destination['postal_code'];
            $found = Cache::remember($cacheKey, now()->addDays(7), function () use ($destination) {
                return $this->rajaOngkir->findDestinationByPostalCode((string) $destination['postal_code']);
            });
            if ($found && isset($found['id'])) {
                $destinationId = (int) $found['id'];
                $meta['rajaongkir_auto_destination'] = $found['label'] ?? '';
            }
        }

        if ($destinationId <= 0) {
            return [];
        }

        $couriers = (string) ($input['couriers'] ?? config('services.rajaongkir.couriers'));
        $priceSort = (string) ($input['price_sort'] ?? config('services.rajaongkir.price_sort', 'lowest'));

        $result = $this->rajaOngkir->calculateDomesticCost((int) $originId, $destinationId, max(1, $weightGrams), $couriers, $priceSort);

        $meta['rajaongkir'] = [
            'origin_destination_id' => (int) $originId,
            'destination_id' => $destinationId,
            'weight_grams' => max(1, $weightGrams),
            'couriers' => $couriers,
            'price_sort' => $priceSort,
        ];

        if (!($result['success'] ?? false) || !is_array($result['data'] ?? null)) {
            $meta['rajaongkir_error'] = $result['message'] ?? 'Failed to fetch RajaOngkir rates';

            return [];
        }

        $options = [];
        foreach ($result['data'] as $row) {
            $courierCode = (string) ($row['code'] ?? '');
            $service = (string) ($row['service'] ?? '');
            $cost = $row['cost'] ?? null;

            if ($courierCode === '' || $service === '' || !is_numeric($cost)) {
                continue;
            }

            $optionId = 'rajaongkir:' . $courierCode . ':' . Str::slug($service, '_');

            $options[] = [
                'id' => $optionId,
                'provider' => 'rajaongkir',
                'service' => $service,
                'courier_code' => $courierCode,
                'courier_name' => (string) ($row['name'] ?? ''),
                'price' => (int) round((float) $cost),
                'currency' => (string) ($row['currency'] ?? 'IDR'),
                'etd' => (string) ($row['etd'] ?? ''),
                'raw' => $row,
            ];
        }

        return $options;
    }

    private function quoteOnDemand(array $origin, array $destination, array $input, array &$meta): array
    {
        $options = [];

        $lat = $destination['latitude'] ?? null;
        $lng = $destination['longitude'] ?? null;
        if (!is_numeric($lat) || !is_numeric($lng)) {
            return [];
        }

        $distance = $this->distanceCalculator->calculateDistance(
            (float) $origin['latitude'],
            (float) $origin['longitude'],
            (float) $lat,
            (float) $lng
        );

        $meta['distance_km'] = round($distance, 2);
        $meta['estimated_time'] = $this->estimateTime($distance);

        $payload = (array) ($input['payload'] ?? []);

        if ($this->grabExpress->isConfigured()) {
            $quote = $this->grabExpress->quote($origin, $destination, $payload);
            if ($quote->success && $quote->price !== null) {
                $options[] = [
                    'id' => 'grabexpress:' . Str::uuid(),
                    'provider' => 'grabexpress',
                    'service' => $quote->serviceName ?? 'GrabExpress',
                    'price' => (int) $quote->price,
                    'currency' => $quote->currency ?? 'IDR',
                    'etd' => $quote->etd,
                    'raw' => $quote->raw,
                ];
            } else {
                $meta['grabexpress_error'] = $quote->message;
            }
        }

        if ($this->goSend->isConfigured()) {
            $quote = $this->goSend->quote($origin, $destination, $payload);
            if ($quote->success && $quote->price !== null) {
                $options[] = [
                    'id' => 'gosend:' . Str::uuid(),
                    'provider' => 'gosend',
                    'service' => $quote->serviceName ?? 'GoSend',
                    'price' => (int) $quote->price,
                    'currency' => $quote->currency ?? 'IDR',
                    'etd' => $quote->etd,
                    'raw' => $quote->raw,
                ];
            } else {
                $meta['gosend_error'] = $quote->message;
            }
        }

        $options[] = [
            'id' => 'distance_fallback:standard',
            'provider' => 'distance_fallback',
            'service' => 'Standard',
            'price' => (int) $this->distanceCalculator->calculateDeliveryFeeByDistance($distance),
            'currency' => 'IDR',
            'etd' => $this->estimateTime($distance),
            'raw' => [
                'distance_km' => round($distance, 2),
            ],
        ];

        return $options;
    }

    private function getOrigin(): array
    {
        return [
            'latitude' => (float) config('app.restaurant_latitude'),
            'longitude' => (float) config('app.restaurant_longitude'),
            'address' => (string) config('app.restaurant_address'),
        ];
    }

    private function defaultWeightGrams(): int
    {
        return (int) env('DEFAULT_SHIPPING_WEIGHT_GRAMS', 1000);
    }

    private function getRajaOngkirOriginId(): ?int
    {
        $originId = config('services.rajaongkir.origin_destination_id');

        return is_numeric($originId) ? (int) $originId : null;
    }

    private function estimateTime(float $distanceKm): string
    {
        $averageSpeed = 20;
        $preparationTime = 15;

        $travelTime = ($distanceKm / $averageSpeed) * 60;
        $totalTime = (int) ceil($travelTime + $preparationTime);

        return $totalTime . ' minutes';
    }

    private function cacheKey(string $quoteId): string
    {
        return 'shipping_quote:' . $quoteId;
    }
}

