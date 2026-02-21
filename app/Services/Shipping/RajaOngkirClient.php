<?php

namespace App\Services\Shipping;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class RajaOngkirClient
{
    private string $baseUrl;
    private ?string $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.rajaongkir.base_url', ''), '/');
        $this->apiKey = config('services.rajaongkir.api_key');
    }

    public function isConfigured(): bool
    {
        return $this->baseUrl !== '' && !empty($this->apiKey);
    }

    public function listProvinces(): array
    {
        $response = $this->client()->get($this->baseUrl.'/destination/province');

        return $this->normalizeResponse($response->json());
    }

    public function searchDomesticDestination(string $search, int $limit = 10, int $offset = 0): array
    {
        $response = $this->client()->get($this->baseUrl.'/destination/domestic-destination', [
            'search' => $search,
            'limit' => $limit,
            'offset' => $offset,
        ]);

        return $this->normalizeResponse($response->json());
    }

    public function findDestinationByPostalCode(string $postalCode): ?array
    {
        $result = $this->searchDomesticDestination($postalCode, 1);
        if (($result['success'] ?? false) && !empty($result['data'][0])) {
            return $result['data'][0];
        }
        return null;
    }

    public function calculateDomesticCost(int $originId, int $destinationId, int $weightGrams, string $courierCodes, string $priceSort = 'lowest'): array
    {
        $response = $this->client()
            ->asForm()
            ->post($this->baseUrl.'/calculate/domestic-cost', [
                'origin' => $originId,
                'destination' => $destinationId,
                'weight' => $weightGrams,
                'courier' => $courierCodes,
                'price' => $priceSort,
            ]);

        return $this->normalizeResponse($response->json());
    }

    private function client(): PendingRequest
    {
        return Http::acceptJson()
            ->timeout(15)
            ->withHeaders([
                'key' => (string) $this->apiKey,
            ]);
    }

    private function normalizeResponse(?array $payload): array
    {
        if (!is_array($payload)) {
            return [
                'success' => false,
                'message' => 'Invalid response from RajaOngkir',
                'raw' => $payload,
            ];
        }

        $meta = $payload['meta'] ?? [];
        $code = (int) ($meta['code'] ?? 0);
        $status = (string) ($meta['status'] ?? '');
        $message = (string) ($meta['message'] ?? '');

        $success = $code >= 200 && $code < 300;
        if ($status !== '') {
            $success = $success && strtolower($status) === 'success';
        }

        return [
            'success' => $success,
            'code' => $code,
            'message' => $message,
            'data' => $payload['data'] ?? null,
            'raw' => $payload,
        ];
    }
}

