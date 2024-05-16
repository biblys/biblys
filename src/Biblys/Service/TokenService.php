<?php

namespace Biblys\Service;

use Biblys\Exception\InvalidConfigurationException;
use DateTime;
use Exception;
use Firebase\JWT\JWT;
use stdClass;

class TokenService
{
    public function __construct(
        private readonly Config $config,
        private readonly CurrentSite $currentSite
    )
    {
    }

    /**
     * @throws InvalidConfigurationException
     * @throws Exception
     */
    public function createLoginToken(string $email): string
    {
        return JWT::encode(
            [
                "iss" => "https://{$this->currentSite->getSite()->getDomain()}",
                "sub" => $email,
                "aud" => "https://{$this->currentSite->getSite()->getDomain()}",
                "iat" => (new DateTime())->getTimestamp(),
                "exp" => (new DateTime("+ 24 hours"))->getTimestamp(),
                "jti" => uniqid(),
                "action" => "login",
            ],
            $this->config->getAuthenticationSecret(),
            "HS256",
        );
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