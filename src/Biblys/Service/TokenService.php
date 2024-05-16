<?php

namespace Biblys\Service;

use Firebase\JWT\JWT;

class TokenService
{
    public function __construct(
        private readonly Config $config,
        private readonly CurrentSite $currentSite
    )
    {
    }

    public function createOIDCStateToken(string|null $returnUrl, string $key): string
    {
        return JWT::encode(
            [
                "return_url" => $returnUrl,
                "iat" => time(),
            ],
            $key,
            "HS256",
        );
    }
}