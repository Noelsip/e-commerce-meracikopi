<?php

namespace App\Services\Shipping;

use Illuminate\Support\Facades\Http;

class GoSendClient
{
    private ?string $baseUrl;
    private ?string $apiKey;
    private string $quotePath;
    private int $timeoutSeconds;

    public function __construct()
    {
        $this->baseUrl = config('services.gosend.base_url');
        $this->apiKey = config('services.gosend.api_key');
        $this->quotePath = (string) config('services.gosend.quote_path', '/quotes');
        $this->timeoutSeconds = (int) config('services.gosend.timeout_seconds', 15);
    }

    public function isConfigured(): bool
    {
        return !empty($this->baseUrl) && !empty($this->apiKey);
    }

    public function quote(array $origin, array $destination, array $payload = []): OnDemandQuoteResult
    {
        if (!$this->isConfigured()) {
            return new OnDemandQuoteResult(false, null, null, null, null, [], 'GoSend is not configured');
        }

        $url = rtrim((string) $this->baseUrl, '/').'/'.ltrim($this->quotePath, '/');

        $response = Http::acceptJson()
            ->timeout($this->timeoutSeconds)
            ->withToken((string) $this->apiKey)
            ->post($url, array_merge([
                'origin' => $origin,
                'destination' => $destination,
            ], $payload));

        if (!$response->successful()) {
            return new OnDemandQuoteResult(false, null, null, null, null, $response->json() ?? [], 'Failed to get GoSend quote');
        }

        $json = $response->json() ?? [];
        $price = $this->extractInt($json, ['price', 'fee', 'amount', 'data.price', 'data.fee', 'data.amount']);
        $currency = $this->extractString($json, ['currency', 'data.currency']);
        $serviceName = $this->extractString($json, ['service', 'service_name', 'data.service', 'data.service_name']);
        $etd = $this->extractString($json, ['etd', 'eta', 'data.etd', 'data.eta']);

        if ($price === null) {
            return new OnDemandQuoteResult(false, null, $currency, $serviceName, $etd, $json, 'GoSend quote response missing price');
        }

        return new OnDemandQuoteResult(true, $price, $currency, $serviceName, $etd, $json);
    }

    private function extractInt(array $json, array $paths): ?int
    {
        foreach ($paths as $path) {
            $value = data_get($json, $path);
            if (is_numeric($value)) {
                return (int) round((float) $value);
            }
        }

        return null;
    }

    private function extractString(array $json, array $paths): ?string
    {
        foreach ($paths as $path) {
            $value = data_get($json, $path);
            if (is_string($value) && trim($value) !== '') {
                return $value;
            }
        }

        return null;
    }
}

