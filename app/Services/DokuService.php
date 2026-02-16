<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DokuService
{
    private static function generateTimestamp(): string
    {
        // Use standard ISO 8601 format for consistency
        return gmdate('c');
    }
    /**
     * Generate signature menggunakan merchant private key untuk request ke DOKU
     */
    private static function generateMerchantSignature(string $stringToSign): string
    {
        $merchantPrivateKey = config('doku.merchant_private_key');

        if (!$merchantPrivateKey) {
            throw new \Exception('Merchant private key not configured in environment');
        }

        // Normalize line endings and ensure proper format
        $merchantPrivateKey = str_replace(["\r\n", "\r"], "\n", $merchantPrivateKey);

        // Add quotes if missing (in case of env parsing issues)
        if (!str_contains($merchantPrivateKey, '-----BEGIN')) {
            throw new \Exception('Invalid merchant private key format. Must include -----BEGIN and -----END headers');
        }

        $privateKey = openssl_pkey_get_private($merchantPrivateKey);

        if (!$privateKey) {
            $error = openssl_error_string();
            throw new \Exception('Invalid merchant private key: ' . ($error ?: 'Unable to parse private key'));
        }

        $success = openssl_sign($stringToSign, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        openssl_pkey_free($privateKey);

        if (!$success) {
            throw new \Exception('Failed to generate merchant signature');
        }

        return base64_encode($signature);
    }

    /**
     * Verifikasi signature dari DOKU menggunakan DOKU public key
     */
    public static function verifyDokuSignature(array $data, string $signature): bool
    {
        $dokuPublicKey = config('doku.public_key');

        if (!$dokuPublicKey) {
            Log::warning('DOKU public key not configured');
            return false;
        }

        // Normalize line endings
        $dokuPublicKey = str_replace(["\r\n", "\r"], "\n", $dokuPublicKey);

        $publicKey = openssl_pkey_get_public($dokuPublicKey);

        if (!$publicKey) {
            $error = openssl_error_string();
            Log::error('Invalid DOKU public key: ' . ($error ?: 'Unable to parse public key'));
            return false;
        }

        // Create string to verify dari data yang diterima
        $stringToVerify = json_encode($data, JSON_UNESCAPED_SLASHES);
        $decodedSignature = base64_decode($signature);

        $isValid = openssl_verify($stringToVerify, $decodedSignature, $publicKey, OPENSSL_ALGO_SHA256) === 1;
        openssl_pkey_free($publicKey);

        return $isValid;
    }

    /**
     * Generate signature untuk B2B access token (menggunakan HMAC dengan secret key)
     */
    private static function generateHmacSignature(string $stringToSign): string
    {
        $secretKey = config('doku.secret_key');
        return 'HMACSHA256=' . base64_encode(hash_hmac('sha256', $stringToSign, $secretKey, true));
    }
    private static function generateSignature(string $httpMethod, string $endpointUrl, string $accessToken, string $requestBody, string $timestamp): string
    {
        $clientId = trim(config('doku.client_id'));
        $secretKey = trim(config('doku.secret_key'));

        // Digest = Base64(SHA-256(RequestBody)) - untuk POST request
        $digest = base64_encode(hash('sha256', $requestBody, true));

        // Request-Id dihasilkan di luar, tapi kita perlu menyertakannya
        // Kita generate di sini dan simpan untuk dipakai di header
        $requestId = self::$lastRequestId ?? uniqid();

        // String to Sign untuk DOKU Checkout API
        // Format: ComponentName:Value dipisahkan dengan \n
        $stringToSign = "Client-Id:" . $clientId . "\n" .
            "Request-Id:" . $requestId . "\n" .
            "Request-Timestamp:" . $timestamp . "\n" .
            "Request-Target:" . $endpointUrl . "\n" .
            "Digest:" . $digest;

        // HMAC-SHA256 dengan Secret Key
        $signature = base64_encode(hash_hmac('sha256', $stringToSign, $secretKey, true));

        return "HMACSHA256=" . $signature;
    }

    // Simpan Request-Id agar bisa dipakai di generateSignature dan di header
    private static ?string $lastRequestId = null;

    private static function getAccessToken(): string
    {
        $tokenData = self::getAccessTokenForSnap();
        return $tokenData['accessToken'];
    }

    /**
     * Get access token untuk SNAP dengan caching
     */
    public static function getAccessTokenForSnap(): array
    {
        // Cache key untuk access token
        $cacheKey = 'doku_snap_access_token';

        // Cek apakah ada token yang masih valid di cache
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // Generate token baru jika tidak ada di cache
        $tokenData = self::generateAccessTokenFromAPI();

        // Cache token dengan expire time dari DOKU (biasanya 15 menit)
        $expiresIn = $tokenData['expiresIn'] - 60; // Kurangi 1 menit untuk safety
        Cache::put($cacheKey, $tokenData, now()->addSeconds($expiresIn));

        return $tokenData;
    }

    /**
     * Generate access token dari DOKU API untuk SNAP
     */
    private static function generateAccessTokenFromAPI(): array
    {
        $clientId = trim(config('doku.client_id'));
        $baseUrl = trim(config('doku.base_url'));

        // MERCHANT_PRIVATE_KEY digunakan untuk RSA Signing (Asymmetric)
        $privateKeyPem = config('doku.merchant_private_key');

        if (!$privateKeyPem) {
            throw new \Exception('MERCHANT_PRIVATE_KEY is not configured in environment');
        }

        // Normalize line endings (Railway env might use different line endings)
        $privateKeyPem = str_replace(["\r\n", "\r"], "\n", $privateKeyPem);

        // Timestamp ISO8601 UTC
        $timestamp = gmdate('Y-m-d\TH:i:s\Z');

        // Rumus SNAP B2B: ClientID + "|" + Timestamp
        $stringToSign = $clientId . '|' . $timestamp;

        // ===== ASYMMETRIC SIGNATURE: SHA256withRSA =====
        // DOKU SNAP B2B Access Token WAJIB pakai RSA, BUKAN HMAC!
        $privateKey = openssl_pkey_get_private($privateKeyPem);
        if (!$privateKey) {
            $opensslError = openssl_error_string();
            Log::error('DOKU_RSA_KEY_ERROR', [
                'error' => $opensslError,
                'key_length' => strlen($privateKeyPem),
                'key_starts_with' => substr($privateKeyPem, 0, 30),
            ]);
            throw new \Exception('Invalid Merchant Private Key: ' . $opensslError);
        }

        openssl_sign($stringToSign, $signatureBinary, $privateKey, OPENSSL_ALGO_SHA256);
        $signature = base64_encode($signatureBinary);

        Log::info('DOKU_AUTH_RSA', ['client_id' => $clientId, 'timestamp' => $timestamp]);

        // additionalInfo HARUS object {} bukan array [] di JSON
        $requestBody = [
            'grantType' => 'client_credentials',
            'additionalInfo' => new \stdClass(),
        ];

        $response = Http::withHeaders([
            'X-CLIENT-KEY' => $clientId,
            'X-TIMESTAMP' => $timestamp,
            'X-SIGNATURE' => $signature,
            'Content-Type' => 'application/json'
        ])->post($baseUrl . '/authorization/v1/access-token/b2b', $requestBody);

        if (!$response->successful()) {
            $errorBody = $response->body();
            Log::error('DOKU_AUTH_FAIL', [
                'status' => $response->status(),
                'body' => $errorBody,
                'base_url' => $baseUrl,
                'client_id' => $clientId,
            ]);

            throw new \Exception('DOKU Auth Failed (' . $response->status() . '): ' . $errorBody);
        }

        $data = $response->json();

        Log::info('DOKU_AUTH_SUCCESS', ['expires_in' => $data['expiresIn'] ?? 900]);

        return [
            'accessToken' => $data['accessToken'],
            'expiresIn' => $data['expiresIn'] ?? 900,
            'tokenType' => 'Bearer'
        ];
    }

    public static function createPayment(array $payload): array
    {
        $baseUrl = config('doku.base_url');
        $clientId = config('doku.client_id');

        $accessToken = self::getAccessToken();
        $timestamp = self::generateTimestamp();
        $requestBody = json_encode($payload, JSON_UNESCAPED_SLASHES);
        $endpointUrl = '/checkout/v1/payment';

        // Request-Id HARUS sama antara header dan signature
        $requestId = (string) Str::uuid();
        self::$lastRequestId = $requestId;

        $signature = self::generateSignature('POST', $endpointUrl, $accessToken, $requestBody, $timestamp);

        // Gunakan withBody agar JSON yang dikirim PERSIS sama dengan yang digunakan untuk signature
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
            'Client-Id' => $clientId,
            'Request-Id' => $requestId,
            'Request-Timestamp' => $timestamp,
            'Signature' => $signature,
        ])->withBody($requestBody, 'application/json')
            ->post($baseUrl . $endpointUrl);

        if (!$response->successful()) {
            Log::error('DOKU Create Payment Error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'payload' => $payload,
            ]);
            throw new \Exception('DOKU Payment Error: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Create payment with specific method handling
     */
    public static function createSpecificPayment(string $paymentMethod, array $orderData, array $customerData): array
    {
        $baseUrl = config('doku.base_url');
        $clientId = config('doku.client_id');

        // Build payload based on payment method
        $payload = self::buildPaymentPayload($paymentMethod, $orderData, $customerData);

        $accessToken = self::getAccessToken();
        $timestamp = self::generateTimestamp();
        $requestBody = json_encode($payload, JSON_UNESCAPED_SLASHES);
        $endpointUrl = '/checkout/v1/payment';

        // Request-Id HARUS sama antara header dan signature
        $requestId = (string) Str::uuid();
        self::$lastRequestId = $requestId;

        $signature = self::generateSignature('POST', $endpointUrl, $accessToken, $requestBody, $timestamp);

        Log::info('DOKU_PAYMENT_REQUEST', [
            'endpoint' => $baseUrl . $endpointUrl,
            'request_id' => $requestId,
            'payment_method' => $paymentMethod,
            'payload' => $payload,
        ]);

        // Gunakan withBody agar JSON yang dikirim PERSIS sama dengan yang digunakan untuk signature
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
            'Client-Id' => $clientId,
            'Request-Id' => $requestId,
            'Request-Timestamp' => $timestamp,
            'Signature' => $signature,
        ])->withBody($requestBody, 'application/json')
            ->post($baseUrl . $endpointUrl);

        if (!$response->successful()) {
            Log::error('DOKU Create Specific Payment Error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'payload' => $payload,
                'payment_method' => $paymentMethod,
                'request_id' => $requestId,
            ]);
            throw new \Exception('DOKU Payment Error: ' . $response->body());
        }

        $result = $response->json();

        // Process response based on payment method
        return self::processPaymentResponse($paymentMethod, $result);
    }

    /**
     * Build payment payload for specific payment method
     */
    private static function buildPaymentPayload(string $paymentMethod, array $orderData, array $customerData): array
    {
        // DOKU Checkout API payload
        $payload = [
            'order' => [
                'amount' => (int) round($orderData['amount']),
                'invoice_number' => $orderData['invoice_number'],
            ],
            'payment' => [
                'payment_due_date' => 60,
            ],
            'customer' => [
                'name' => $customerData['name'] ?: 'Customer',
                'phone' => (!empty($customerData['phone']) && strlen($customerData['phone']) >= 5)
                    ? $customerData['phone']
                    : '08123456789',
                'email' => $customerData['email'] ?: 'customer@meracikopi.com',
            ],
            'additional_info' => [
                'callback_url' => rtrim(config('app.url'), '/') . '/checkout/success',
                'override_notification_url' => rtrim(config('app.url'), '/') . '/api/webhooks/doku',
            ],
        ];

        // Log payload untuk debugging
        error_log('DOKU_PAYLOAD: ' . json_encode($payload));

        return $payload;
    }

    /**
     * Process payment response based on method
     */
    private static function processPaymentResponse(string $paymentMethod, array $response): array
    {
        $processedResponse = [
            'original_response' => $response,
            'payment_method' => $paymentMethod,
            'invoice_number' => $response['response']['payment']['invoiceNumber'] ?? null,
            'payment_url' => null,
            'qr_code_data' => null,
            'virtual_account_info' => null,
            'ewallet_info' => null,
            'instructions' => null
        ];

        switch ($paymentMethod) {
            case 'qris':
                if (isset($response['response']['payment']['qrString'])) {
                    $processedResponse['qr_code_data'] = [
                        'qr_string' => $response['response']['payment']['qrString'],
                        'qr_image' => $response['response']['payment']['qrImage'] ?? null,
                        'expired_at' => $response['response']['payment']['expiredDate'] ?? null
                    ];
                    $processedResponse['instructions'] = 'Scan QR Code menggunakan aplikasi e-wallet atau mobile banking Anda';
                }
                break;

            case 'bca_va':
            case 'bni_va':
            case 'bri_va':
            case 'mandiri_va':
                if (isset($response['response']['virtualAccountInfo'])) {
                    $vaInfo = $response['response']['virtualAccountInfo'];
                    $processedResponse['virtual_account_info'] = [
                        'bank_name' => $vaInfo['bank'] ?? strtoupper(str_replace('_va', '', $paymentMethod)),
                        'va_number' => $vaInfo['virtualAccountNumber'] ?? null,
                        'amount' => $vaInfo['amount'] ?? null,
                        'expired_at' => $vaInfo['expiredDate'] ?? null
                    ];
                    $bankName = $processedResponse['virtual_account_info']['bank_name'];
                    $vaNumber = $processedResponse['virtual_account_info']['va_number'];
                    $processedResponse['instructions'] = "Transfer ke Virtual Account {$bankName}: {$vaNumber}";
                }
                break;

            case 'dana':
            case 'gopay':
            case 'shopeepay':
            case 'ovo':
                if (isset($response['response']['payment']['url'])) {
                    $processedResponse['payment_url'] = $response['response']['payment']['url'];
                    $processedResponse['ewallet_info'] = [
                        'deep_link' => $response['response']['payment']['deepLink'] ?? null,
                        'payment_url' => $response['response']['payment']['url'],
                        'expired_at' => $response['response']['payment']['expiredDate'] ?? null
                    ];
                    $walletName = ucfirst($paymentMethod);
                    $processedResponse['instructions'] = "Anda akan diarahkan ke aplikasi {$walletName} untuk menyelesaikan pembayaran";
                }
                break;
        }

        return $processedResponse;
    }

    public static function getPaymentStatus(string $invoiceNumber): array
    {
        $baseUrl = config('doku.base_url');
        $clientId = config('doku.client_id');

        $accessToken = self::getAccessToken();
        $timestamp = self::generateTimestamp();
        $requestBody = '';
        $endpointUrl = '/orders/v1/status/' . $invoiceNumber;

        $signature = self::generateSignature('GET', $endpointUrl, $accessToken, $requestBody, $timestamp);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
            'X-CLIENT-KEY' => $clientId,
            'Request-Id' => uniqid(),
            'X-TIMESTAMP' => $timestamp,
            'Signature' => $signature,
        ])->get($baseUrl . $endpointUrl);

        if (!$response->successful()) {
            Log::error('DOKU Get Payment Status Error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'invoice' => $invoiceNumber,
            ]);
            throw new \Exception('DOKU Status Check Error: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Map frontend payment method to DOKU payment channels
     */
    public static function mapPaymentMethod(?string $paymentMethod): ?string
    {
        $mapping = [
            'qris' => 'QRIS',
            'dana' => 'DANA',
            'gopay' => 'GOPAY',
            'shopeepay' => 'SHOPEEPAY',
            'ovo' => 'OVO',
            'transfer_bank' => 'VIRTUAL_ACCOUNT_BCA', // Default VA, bisa disesuaikan
            'bca_va' => 'VIRTUAL_ACCOUNT_BCA',
            'bni_va' => 'VIRTUAL_ACCOUNT_BNI',
            'bri_va' => 'VIRTUAL_ACCOUNT_BRI',
            'mandiri_va' => 'VIRTUAL_ACCOUNT_MANDIRI',
        ];

        return $mapping[$paymentMethod] ?? null;
    }
}