<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DokuService
{
    // =========================================================================
    // TIMESTAMP & AUTH HELPERS
    // =========================================================================

    /**
     * Generate ISO 8601 timestamp with timezone offset for SNAP API
     */
    private static function generateSnapTimestamp(): string
    {
        return Carbon::now()->format('Y-m-d\TH:i:sP');
    }

    /**
     * Generate ISO 8601 UTC timestamp for Checkout v1
     */
    private static function generateTimestamp(): string
    {
        return gmdate('Y-m-d\TH:i:s\Z');
    }

    /**
     * Generate HMAC-SHA512 Transaction Signature (SNAP)
     */
    private static function generateTransactionSignature(string $method, string $endpoint, string $accessToken, string $requestBody, string $timestamp): string
    {
        $secretKey = trim(config('doku.secret_key'));

        // SNAP spec: empty body uses empty string for signature
        // The request body must be the EXACT string sent in the request
        $hashedBody = strtolower(bin2hex(hash('sha256', $requestBody, true)));

        $stringToSign = implode(':', [
            strtoupper($method),
            $endpoint,
            $accessToken,
            $hashedBody,
            $timestamp
        ]);

        $signature = base64_encode(hash_hmac('sha512', $stringToSign, $secretKey, true));
        return 'HMACSHA512=' . $signature;
    }

    /**
     * Generate HMAC-SHA256 signature for Checkout v1 API
     */
    private static ?string $lastRequestId = null;

    private static function generateCheckoutSignature(string $endpointUrl, string $requestBody, string $timestamp): string
    {
        $clientId = trim(config('doku.client_id'));
        $secretKey = trim(config('doku.secret_key'));

        $digest = base64_encode(hash('sha256', $requestBody, true));
        $requestId = self::$lastRequestId ?? uniqid();

        $stringToSign = "Client-Id:" . $clientId . "\n" .
            "Request-Id:" . $requestId . "\n" .
            "Request-Timestamp:" . $timestamp . "\n" .
            "Request-Target:" . $endpointUrl . "\n" .
            "Digest:" . $digest;

        $signature = base64_encode(hash_hmac('sha256', $stringToSign, $secretKey, true));

        return "HMACSHA256=" . $signature;
    }

    // =========================================================================
    // B2B ACCESS TOKEN (for SNAP Direct API)
    // =========================================================================

    public static function getAccessTokenForSnap(): array
    {
        $cacheKey = 'doku_snap_access_token';

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $tokenData = self::generateAccessTokenFromAPI();

        $expiresIn = $tokenData['expiresIn'] - 60;
        Cache::put($cacheKey, $tokenData, now()->addSeconds($expiresIn));

        return $tokenData;
    }

    private static function generateAccessTokenFromAPI(): array
    {
        $clientId = trim(config('doku.client_id'));
        $baseUrl = trim(config('doku.base_url'));
        $privateKeyPem = config('doku.merchant_private_key');

        $timestamp = self::generateSnapTimestamp();

        $stringToSign = $clientId . '|' . $timestamp;

        $privateKey = openssl_pkey_get_private($privateKeyPem);
        if (!$privateKey) {
            $opensslError = openssl_error_string();
            Log::error('DOKU RSA Key Error: ' . $opensslError);
            throw new \Exception('Invalid Merchant Private Key: ' . $opensslError);
        }

        openssl_sign($stringToSign, $signatureBinary, $privateKey, OPENSSL_ALGO_SHA256);
        openssl_pkey_free($privateKey);
        $signature = base64_encode($signatureBinary);

        $response = Http::withHeaders([
            'X-CLIENT-KEY' => $clientId,
            'X-TIMESTAMP' => $timestamp,
            'X-SIGNATURE' => $signature,
            'Content-Type' => 'application/json'
        ])->post($baseUrl . '/authorization/v1/access-token/b2b', [
                    'grantType' => 'client_credentials',
                    'additionalInfo' => []
                ]);

        if (!$response->successful()) {
            $errorBody = $response->body();
            Log::error('DOKU Auth Fail', ['body' => $errorBody]);
            throw new \Exception($errorBody);
        }

        $data = $response->json();

        return [
            'accessToken' => $data['accessToken'],
            'expiresIn' => $data['expiresIn'] ?? 900,
            'tokenType' => 'Bearer'
        ];
    }

    // =========================================================================
    // API REQUEST METHODS
    // =========================================================================

    /**
     * specific request for SNAP Direct (QRIS/VA)
     */
    private static function snapDirectRequest(string $method, string $endpointUrl, array $payload, string $channelId = 'H2H'): array
    {
        $baseUrl = trim(config('doku.base_url'));
        $clientId = trim(config('doku.client_id'));

        $accessToken = self::getAccessTokenForSnap()['accessToken']; // Extract token string

        // Generate timestamp and external ID
        $timestamp = self::generateSnapTimestamp();
        // DOKU requires numeric X-EXTERNAL-ID for SNAP
        $externalId = str_pad(mt_rand(1, 99999999999999), 14, '0', STR_PAD_LEFT) . time();

        // JSON Encode with specific flags - CRITICAL: Must be same for signature and body
        $requestBody = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        // Generate transaction signature (HMAC-SHA512)
        $signature = self::generateTransactionSignature(
            $method,
            $endpointUrl,
            $accessToken,
            $requestBody,
            $timestamp
        );

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
            'X-PARTNER-ID' => $clientId,
            'X-EXTERNAL-ID' => $externalId,
            'X-TIMESTAMP' => $timestamp,
            'X-SIGNATURE' => $signature,
            'CHANNEL-ID' => $channelId,
        ];

        Log::debug('DOKU SNAP Direct Request', [
            'method' => $method,
            'endpoint' => $endpointUrl,
            'headers' => $headers,
            'body' => $payload
        ]);

        if (strtoupper($method) === 'POST') {
            $response = Http::withHeaders($headers)
                ->withBody($requestBody, 'application/json')
                ->post($baseUrl . $endpointUrl);
        } else {
            $response = Http::withHeaders($headers)
                ->get($baseUrl . $endpointUrl);
        }

        if (!$response->successful()) {
            Log::error('DOKU SNAP Direct API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'endpoint' => $endpointUrl,
            ]);
            throw new \Exception('DOKU SNAP API Error (' . $response->status() . '): ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Request for Checkout v1
     */
    private static function checkoutV1Request(array $payload): array
    {
        $baseUrl = config('doku.base_url');
        $clientId = config('doku.client_id');

        $timestamp = self::generateTimestamp();
        $requestBody = json_encode($payload);
        $endpointUrl = '/checkout/v1/payment';

        $requestId = uniqid();
        self::$lastRequestId = $requestId;

        $signature = self::generateCheckoutSignature($endpointUrl, $requestBody, $timestamp);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Client-Id' => $clientId,
            'Request-Id' => $requestId,
            'Request-Timestamp' => $timestamp,
            'Signature' => $signature,
        ])->post($baseUrl . $endpointUrl, $payload);

        if (!$response->successful()) {
            Log::error('DOKU Checkout v1 Error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'payload' => $payload,
            ]);
            throw new \Exception('DOKU Checkout Error: ' . $response->body());
        }

        return $response->json();
    }

    // =========================================================================
    // MAIN PAYMENT LOGIC
    // =========================================================================

    public static function createSpecificPayment(string $paymentMethod, array $orderData, array $customerData): array
    {
        Log::info('Creating DOKU Payment', ['method' => $paymentMethod]);

        // Strategy:
        // 1. Try Direct API for QRIS (if Mall ID configured) -> Embeds QR
        // 2. Fallback to Checkout v1 for everything else -> Popup URL

        if ($paymentMethod === 'qris') {
            // Attempt Direct API first
            try {
                return self::createQrisDirectPayment($orderData);
            } catch (\Exception $e) {
                Log::warning('QRIS Direct failed (likely missing Mall ID), falling back to Checkout v1', ['error' => $e->getMessage()]);
                // Fallthrough to checkout v1
            }
        }

        // For all others (VA, E-Wallet, Credit Card), use Checkout v1 with restricted method
        return self::createCheckoutPayment($paymentMethod, $orderData, $customerData);
    }

    /**
     * QRIS Direct (QR Merchant Presented Mode)
     */
    private static function createQrisDirectPayment(array $orderData): array
    {
        $endpointUrl = '/snap-adapter/b2b/v1.0/qr/qr-mpm-generate';

        // Mall ID is required for SNAP.
        // If not explicitly set in main config, this will likely fail in sandbox with error 500.
        // But we try anyway just in case user has configured it.
        $merchantId = trim(config('doku.merchant_id'));
        if (empty($merchantId)) {
            throw new \Exception('DOKU Merchant ID (Mall ID) not configured');
        }

        $payload = [
            'partnerReferenceNo' => $orderData['invoice_number'],
            'amount' => [
                'value' => number_format((float) $orderData['amount'], 2, '.', ''),
                'currency' => 'IDR',
            ],
            'merchantId' => $merchantId,
            'terminalId' => 'A01',
        ];

        $response = self::snapDirectRequest('POST', $endpointUrl, $payload, 'H2H');

        $qrContent = $response['qrContent'] ?? null;
        $qrUrl = $response['qrUrl'] ?? null;

        if (!$qrContent && !$qrUrl) {
            throw new \Exception('No QR content in Direct API response');
        }

        return [
            'payment_method' => 'qris',
            'invoice_number' => $orderData['invoice_number'],
            'display_type' => 'on_page', 
            'qr_code_data' => [
                'qr_string' => $qrContent,
                'qr_url' => $qrUrl,
                'qr_image' => $qrUrl,
                'expired_at' => $response['validityPeriod'] ?? null,
            ],
            'payment_url' => null,
            'instructions' => 'Scan QR Code di bawah menggunakan aplikasi e-wallet atau mobile banking Anda.',
        ];
    }

    /**
     * Checkout v1 (Hybrid/Popup)
     */
    private static function createCheckoutPayment(string $paymentMethod, array $orderData, array $customerData): array
    {
        $dokuPaymentTypes = self::getDokuPaymentMethodType($paymentMethod);

        $payload = [
            'order' => [
                'amount' => (int) round($orderData['amount']),
                'invoice_number' => $orderData['invoice_number'],
            ],
            'payment' => [
                'payment_due_date' => 60,
                // Override per transaction if needed
                'override_notification_url' => rtrim(config('app.url'), '/') . '/api/webhooks/doku',
            ],
            'customer' => [
                'name' => $customerData['name'],
                'phone' => $customerData['phone'],
                'email' => $customerData['email'],
            ],
            'additional_info' => [
                // 'callback_url' is sometimes used for redirect. 
                // We'll keep it here just in case, but DOKU might ignore it.
                'callback_url' => rtrim(config('app.url'), '/') . '/checkout/success',
            ],
        ];

        if (!empty($dokuPaymentTypes)) {
            $payload['payment']['payment_method_types'] = $dokuPaymentTypes;
        }

        $response = self::checkoutV1Request($payload);
        $checkoutUrl = $response['response']['payment']['url'] ?? null;

        return [
            'payment_method' => $paymentMethod,
            'invoice_number' => $orderData['invoice_number'],
            'display_type' => 'popup', // Instruct frontend to open in popup
            'payment_url' => $checkoutUrl,
            'qr_code_data' => null,
            'virtual_account_info' => null,
            'instructions' => self::getInstructions($paymentMethod),
            'original_response' => $response
        ];
    }

    // =========================================================================
    // MAPS & HELPERS
    // =========================================================================

    private static function getInstructions(string $paymentMethod): string
    {
        $instructions = [
            'qris' => 'Scan QR Code yang muncul di jendela pembayaran.',
            'dana' => 'Selesaikan pembayaran di jendela DANA yang muncul.',
            'bca_va' => 'Selesaikan pembayaran via Virtual Account BCA di jendela yang muncul.',
            // ... add others
        ];
        return $instructions[$paymentMethod] ?? 'Selesaikan pembayaran di jendela yang muncul.';
    }

    private static function getDokuPaymentMethodType(string $paymentMethod): array
    {
        $mapping = [
            'qris' => ['QRIS'],
            'dana' => ['EMONEY_DANA'],
            'ovo' => ['EMONEY_OVO'],
            'shopeepay' => ['EMONEY_SHOPEE_PAY'],
            'gopay' => ['QRIS'],
            'bca_va' => ['VIRTUAL_ACCOUNT_BCA'],
            'bni_va' => ['VIRTUAL_ACCOUNT_BNI'],
            'bri_va' => ['VIRTUAL_ACCOUNT_BRI'],
            'mandiri_va' => ['VIRTUAL_ACCOUNT_BANK_MANDIRI'],
            'credit_card' => ['CREDIT_CARD'],
        ];
        return $mapping[$paymentMethod] ?? [];
    }

    public static function mapPaymentMethod(?string $paymentMethod): ?string
    {
        $mapping = [
            'qris' => 'QRIS',
            'dana' => 'DANA',
            'gopay' => 'GOPAY',
            'shopeepay' => 'SHOPEEPAY',
            'ovo' => 'OVO',
            'bca_va' => 'VIRTUAL_ACCOUNT_BCA',
            'bni_va' => 'VIRTUAL_ACCOUNT_BNI',
            'bri_va' => 'VIRTUAL_ACCOUNT_BRI',
            'mandiri_va' => 'VIRTUAL_ACCOUNT_MANDIRI',
        ];
        return $mapping[$paymentMethod] ?? null;
    }

    // Status Check
    public static function getPaymentStatus(string $invoiceNumber): array
    {
        $baseUrl = config('doku.base_url');
        $clientId = config('doku.client_id');
        $timestamp = self::generateTimestamp();
        $endpointUrl = '/orders/v1/status/' . $invoiceNumber;
        $requestId = uniqid();

        $signature = self::generateCheckoutSignature($endpointUrl, '', $timestamp);

        $response = Http::withHeaders([
            'Client-Id' => $clientId,
            'Request-Id' => $requestId,
            'Request-Timestamp' => $timestamp,
            'Signature' => $signature,
        ])->get($baseUrl . $endpointUrl);

        return $response->json();
    }

    // Verify Webhook Signature
    public static function verifyDokuSignature(array $data, string $signature): bool
    {
        $dokuPublicKey = config('doku.public_key');
        if (!$dokuPublicKey)
            return false;

        $publicKey = openssl_pkey_get_public($dokuPublicKey);
        if (!$publicKey)
            return false;

        $stringToVerify = json_encode($data, JSON_UNESCAPED_SLASHES);
        $decodedSignature = base64_decode($signature);

        $isValid = openssl_verify($stringToVerify, $decodedSignature, $publicKey, OPENSSL_ALGO_SHA256) === 1;
        openssl_pkey_free($publicKey);

        return $isValid;
    }
}