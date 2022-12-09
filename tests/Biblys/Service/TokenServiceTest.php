<?php

namespace Biblys\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PHPUnit\Framework\TestCase;

class TokenServiceTest extends TestCase
{

    public function testCreateOIDCStateToken()
    {
        // given
        $tokenService = new TokenService();

        // when
        $token = $tokenService->createOIDCStateToken(null,"secret-key");

        // then
        $decodedToken = JWT::decode($token, new Key("secret-key", "HS256"));
        $this->assertEquals(null, $decodedToken->return_url);
    }

    public function testCreateOIDCStateTokenWithReturnUrl()
    {
        // given
        $tokenService = new TokenService();

        // when
        $token = $tokenService->createOIDCStateToken("return_url", "secret-key");

        // then
        $decodedToken = JWT::decode($token, new Key("secret-key", "HS256"));
        $this->assertEquals(
            "return_url",
            $decodedToken->return_url,
        );
    }
}
