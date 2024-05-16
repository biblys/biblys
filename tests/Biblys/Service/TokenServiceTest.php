<?php

namespace Biblys\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Mockery;
use PHPUnit\Framework\TestCase;

class TokenServiceTest extends TestCase
{

    public function testCreateOIDCStateToken()
    {
        // given
        $config = new Config();
        $currentSite = Mockery::mock(CurrentSite::class);
        $tokenService = new TokenService($config, $currentSite);

        // when
        $token = $tokenService->createOIDCStateToken(null,"secret-key");

        // then
        $decodedToken = JWT::decode($token, new Key("secret-key", "HS256"));
        $this->assertEquals(null, $decodedToken->return_url);
    }

    public function testCreateOIDCStateTokenWithReturnUrl()
    {
        // given
        $config = new Config();
        $currentSite = Mockery::mock(CurrentSite::class);
        $tokenService = new TokenService($config, $currentSite);

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
