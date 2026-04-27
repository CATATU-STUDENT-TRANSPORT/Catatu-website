<?php

namespace App\Services\Mpesa;

class MpesaConfig
{
    public function __construct(
        public readonly ?string $consumerKey,
        public readonly ?string $consumerSecret,
        public readonly ?string $shortcode,
        public readonly ?string $passkey,
        public readonly string $environment,
        public readonly string $callbackUrl,
        public readonly bool $stub,
    ) {}

    public function isConfigured(): bool
    {
        return ! empty($this->consumerKey)
            && ! empty($this->consumerSecret)
            && ! empty($this->shortcode)
            && ! empty($this->passkey);
    }
}
