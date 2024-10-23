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
     * #createLoginToken
     */

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
        $loginToken = $tokenService->createLoginToken(
            email: "user@paronymie.fr",
            action: "login-by-email",
            afterLoginUrl: "/after_login_url",
        );

        // then
        $decodedToken = JWT::decode($loginToken, new Key("secret_key", "HS256"));
        $this->assertEquals("https://paronymie.fr", $decodedToken->iss);
        $this->assertEquals("user@paronymie.fr", $decodedToken->sub);
        $this->assertEquals("https://paronymie.fr", $decodedToken->aud);
        $this->assertIsInt($decodedToken->iat);
        $this->assertIsInt($decodedToken->exp);
        $this->assertNotNull($decodedToken->jti);
        $this->assertEquals("login-by-email", $decodedToken->action);
        $this->assertEquals("/after_login_url", $decodedToken->after_login_url);
    }

    /**
     * @throws PropelException
     * @throws InvalidConfigurationException
     */
    public function testCreateLoginTokenForOidc()
    {
        // given
        $config = Mockery::mock(Config::class);
        $config->expects("getAuthenticationSecret")->andReturn("secret_key");
        $site = ModelFactory::createSite();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $tokenService = new TokenService($config, $currentSite);

        // when
        $loginToken = $tokenService->createLoginToken(
            email: "user@paronymie.fr",
            action: "login-with-oidc",
            afterLoginUrl: "/after_login_url",
        );

        // then
        $decodedToken = JWT::decode($loginToken, new Key("secret_key", "HS256"));
        $this->assertEquals("https://paronymie.fr", $decodedToken->iss);
        $this->assertEquals("user@paronymie.fr", $decodedToken->sub);
        $this->assertEquals("https://paronymie.fr", $decodedToken->aud);
        $this->assertIsInt($decodedToken->iat);
        $this->assertIsInt($decodedToken->exp);
        $this->assertNotNull($decodedToken->jti);
        $this->assertEquals("login-with-oidc", $decodedToken->action);
        $this->assertEquals("/after_login_url", $decodedToken->after_login_url);
    }

    /**
     * #decodeLoginToken
     */

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testDecodeLoginTokenWithInvalidToken()
    {
        // given
        $secretKey = "222fabebd31bdc2ec7f382404cff0418";
        $config = new Config(["authentication" => ["secret" => $secretKey]]);
        $site = ModelFactory::createSite();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->expects("getSite")->andReturn($site);
        $tokenService = new TokenService($config, $currentSite);
        $token = "nope";

        // when
        $exception = Helpers::runAndCatchException(function () use ($tokenService, $token) {
            $tokenService->decodeLoginToken($token);
        });

        // then
        $this->assertInstanceOf(InvalidTokenException::class, $exception);
        $this->assertEquals("Invalid token", $exception->getMessage());
    }

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
        $token = $tokenService->createLoginToken(
            email: "user@paronymie.fr",
            action: "login-by-email",
            afterLoginUrl: "/after_login_url"
        );

        // when
        $token = $tokenService->decodeLoginToken($token);

        // then
        $this->assertEquals("user@paronymie.fr", $token["email"]);
        $this->assertEquals("login-by-email", $token["action"]);
        $this->assertEquals("/after_login_url", $token["after_login_url"]);
    }

    /**
     * #createLoginToken
     */

    /**
     * #decodeEmailUpdateToken
     */

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testDecodeEmailUpdateTokenWithInvalidAction()
    {
        // given
        $secretKey = "222fabebd31bdc2ec7f382404cff0418";
        $config = new Config(["authentication" => ["secret" => $secretKey]]);
        $site = ModelFactory::createSite();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->expects("getSite")->andReturn($site);
        $tokenService = new TokenService($config, $currentSite);
        $token = JWT::encode(["action" => "update-username"], $secretKey, "HS256");

        // when
        $exception = Helpers::runAndCatchException(function () use ($tokenService, $token) {
            $tokenService->decodeEmailUpdateToken($token);
        });

        // then
        $this->assertInstanceOf(InvalidTokenException::class, $exception);
        $this->assertEquals("Invalid action for email update token", $exception->getMessage());
    }

    /**
     * @throws InvalidConfigurationException
     * @throws PropelException
     * @throws Exception
     */
    public function testDecodeValidEmailUpdateToken()
    {
        // given
        $secretKey = "222fabebd31bdc2ec7f382404cff0417";
        $site = ModelFactory::createSite();
        $user = ModelFactory::createUser(site: $site);

        $config = new Config(["authentication" => ["secret" => $secretKey]]);
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->expects("getSite")->andReturn($site);
        $tokenService = new TokenService($config, $currentSite);
        $token = $tokenService->createEmailUpdateToken($user, "new-email@paronymie.fr");

        // when
        $token = $tokenService->decodeEmailUpdateToken($token);

        // then
        $this->assertEquals($user->getId(), $token["user_id"]);
        $this->assertEquals("new-email@paronymie.fr", $token["new_email"]);
    }

    /**
     * @throws PropelException
     * @throws InvalidConfigurationException
     */
    public function testCreateEmailUpdateToken()
    {
        // given
        $site = ModelFactory::createSite();
        $user = ModelFactory::createUser(site: $site);

        $config = Mockery::mock(Config::class);
        $config->expects("getAuthenticationSecret")->andReturn("secret_key");
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $tokenService = new TokenService($config, $currentSite);

        // when
        $loginToken = $tokenService->createEmailUpdateToken(
            user: $user,
            newEmail: "new-email@paronymie.fr",
        );

        // then
        $decodedToken = JWT::decode($loginToken, new Key("secret_key", "HS256"));
        $this->assertEquals("https://paronymie.fr", $decodedToken->iss);
        $this->assertEquals($user->getId(), $decodedToken->sub);
        $this->assertEquals("https://paronymie.fr", $decodedToken->aud);
        $this->assertIsInt($decodedToken->iat);
        $this->assertIsInt($decodedToken->exp);
        $this->assertNotNull($decodedToken->jti);
        $this->assertEquals("update-email", $decodedToken->action);
        $this->assertEquals("new-email@paronymie.fr", $decodedToken->new_email);
    }
}
