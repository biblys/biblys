<?php

namespace Biblys\Service;

use Biblys\Exception\InvalidConfigurationException;
use DateTime;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

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
                "iss" => $this->_getIssuer(),
                "sub" => $email,
                "aud" => $this->_getIssuer(),
                "iat" => (new DateTime())->getTimestamp(),
                "exp" => (new DateTime("+ 24 hours"))->getTimestamp(),
                "jti" => uniqid(),
                "action" => "login",
            ],
            $this->config->getAuthenticationSecret(),
            "HS256",
        );
    }

    /**
     * @throws InvalidConfigurationException
     * @throws InvalidTokenException
     */
    public function decodeLoginToken(string $token): string
    {
        $decodedToken = JWT::decode($token, new Key($this->config->getAuthenticationSecret(), "HS256"));

        if (!isset($decodedToken->action) || $decodedToken->action !== "login") {
            throw new InvalidTokenException("Invalid action for login token");
        }

        return $decodedToken->sub;
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

    /**
     * @return string
     */
    private function _getIssuer(): string
    {
        return "https://{$this->currentSite->getSite()->getDomain()}";
    }

}