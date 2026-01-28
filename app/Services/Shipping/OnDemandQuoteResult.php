<?php

namespace App\Services\Shipping;

class OnDemandQuoteResult
{
    public function __construct(
        public bool $success,
        public ?int $price,
        public ?string $currency,
        public ?string $serviceName,
        public ?string $etd,
        public array $raw = [],
        public ?string $message = null,
    ) {
    }
}

