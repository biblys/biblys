<?php

namespace Biblys\Service;

use Biblys\Exception\InvalidConfigurationException;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Mockery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__ . "/../../setUp.php";

class TokenServiceTest extends TestCase
{

    public function testCreateOIDCStateToken()
    {
        // given
        $config = new Config();
        $currentSite = Mockery::mock(CurrentSite::class);
        $tokenService = new TokenService($config, $currentSite);

        // when
        $token = $tokenService->createOIDCStateToken(null, "secret-key");

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

    /**
     * @throws PropelException
     * @throws InvalidConfigurationException
     */
    public function testCreateLoginToken()
    {
        // given
        $config = Mockery::mock(Config::class);
        $config->expects("getAuthenticationSecret")->andReturn("secret_key");
        $site = ModelFactory::createSite();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $tokenService = new TokenService($config, $currentSite);

        // when
        $loginToken = $tokenService->createLoginToken("user@paronymie.fr");

        // then
        $decodedToken = JWT::decode($loginToken, new Key("secret_key", "HS256"));
        $this->assertEquals("https://paronymie.fr", $decodedToken->iss);
        $this->assertEquals("user@paronymie.fr", $decodedToken->sub);
        $this->assertEquals("https://paronymie.fr", $decodedToken->aud);
        $this->assertIsInt($decodedToken->iat);
        $this->assertIsInt($decodedToken->exp);
        $this->assertNotNull($decodedToken->jti);
        $this->assertEquals("login", $decodedToken->action);
    }

    /**
     * #decodeLoginToken
     */

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testDecodeLoginTokenWithInvalidAction()
    {
        // given
        $secretKey = "222fabebd31bdc2ec7f382404cff0418";
        $config = new Config(["authentication" => ["secret" => $secretKey]]);
        $site = ModelFactory::createSite();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->expects("getSite")->andReturn($site);
        $tokenService = new TokenService($config, $currentSite);
        $token = JWT::encode(["action" => "logout"], $secretKey, "HS256");

        // when
        $exception = Helpers::runAndCatchException(function () use ($tokenService, $token) {
            $tokenService->decodeLoginToken($token);
        });

        // then
        $this->assertInstanceOf(InvalidTokenException::class, $exception);
        $this->assertEquals("Invalid action for login token", $exception->getMessage());
    }

    /**
     * @throws InvalidConfigurationException
     * @throws InvalidTokenException
     * @throws PropelException
     * @throws Exception
     */
    public function testDecodeLoginValidToken()
    {
        // given
        $secretKey = "222fabebd31bdc2ec7f382404cff0417";
        $config = new Config(["authentication" => ["secret" => $secretKey]]);
        $site = ModelFactory::createSite();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->expects("getSite")->andReturn($site);
        $tokenService = new TokenService($config, $currentSite);
        $token = $tokenService->createLoginToken("user@paronymie.fr");

        // when
        $email = $tokenService->decodeLoginToken($token);

        // then
        $this->assertEquals("user@paronymie.fr", $email);
    }
}
