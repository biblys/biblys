<?php
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


namespace Biblys\Service;

use Biblys\Exception\InvalidConfigurationException;
use DateTime;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Model\User;
use UnexpectedValueException;

class TokenService
{
    public function __construct(
        private readonly Config      $config,
        private readonly CurrentSite $currentSite
    )
    {
    }

    /**
     * @throws InvalidConfigurationException
     * @throws Exception
     */
    public function createLoginToken(
        string $email,
        string $action,
        string $afterLoginUrl
    ): string
    {
        return $this->_createJsonWebToken($email, $action, ["after_login_url" => $afterLoginUrl]);
    }

    /**
     * @throws InvalidConfigurationException
     * @throws InvalidTokenException
     */
    public function decodeLoginToken(string $token): array
    {
        try {
            $decodedToken = JWT::decode($token, new Key($this->config->getAuthenticationSecret(), "HS256"));
        } catch (UnexpectedValueException) {
            throw new InvalidTokenException("Invalid token");
        }

        if (!isset($decodedToken->action)) {
            throw new InvalidTokenException("Invalid action for login token");
        }

        if (!in_array($decodedToken->action,
            ["signup-by-email", "login-by-email", "login-with-oidc"]
        )) {
            throw new InvalidTokenException("Invalid action for login token");
        }

        return [
            "email" => $decodedToken->sub,
            "action" => $decodedToken->action,
            "after_login_url" => $decodedToken->after_login_url,
        ];
    }

    /**
     * @throws InvalidConfigurationException
     */
    public function createEmailUpdateToken(User $user, string $newEmail): string
    {
        return $this->_createJsonWebToken(
            sub: $user->getId(),
            action: "update-email",
            extraClaims: ["new_email" => $newEmail],
        );
    }

    /**
     * @throws InvalidConfigurationException
     * @throws InvalidTokenException
     */
    public function decodeEmailUpdateToken(string $token): array
    {
        $decodedToken = JWT::decode($token, new Key($this->config->getAuthenticationSecret(), "HS256"));

        if (!isset($decodedToken->action) || $decodedToken->action !== "update-email") {
            throw new InvalidTokenException("Invalid action for email update token");
        }

        return [
            "user_id" => $decodedToken->sub,
            "action" => $decodedToken->action,
            "new_email" => $decodedToken->new_email,
        ];
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
     * @throws InvalidConfigurationException
     */
    private function _createJsonWebToken(string $sub, string $action, array $extraClaims): string
    {
        $baseClaims = [
            "iss" => $this->_getIssuer(),
            "sub" => $sub,
            "aud" => $this->_getIssuer(),
            "iat" => (new DateTime())->getTimestamp(),
            "exp" => (new DateTime("+ 24 hours"))->getTimestamp(),
            "jti" => uniqid(),
            "action" => $action,
        ];
        $claims = array_merge($baseClaims, $extraClaims);
        return JWT::encode(
            $claims,
            $this->config->getAuthenticationSecret(),
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