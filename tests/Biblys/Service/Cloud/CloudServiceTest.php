<?php

namespace Biblys\Service\Cloud;

use Biblys\Service\Config;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class CloudServiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testIsConfiguredReturnsFalseWhenConfigIsMissing()
    {
        // given
        $cloudConfig = ["expires" => "2020-01-01"];
        $config = new Config(["cloud" => $cloudConfig]);
        $httpClient = Mockery::mock(Client::class);
        $cloud = new CloudService($config, $httpClient);

        // when
        $isConfigured = $cloud->isConfigured();

        // then
        $this->assertFalse($isConfigured);
    }

    /**
     * @throws Exception
     */
    public function testIsConfiguredReturnsTrueWhenConfigIsPresent()
    {
        // given
        $cloudConfig = ["public_key" => "cus_abcd1234"];
        $config = new Config(["cloud" => $cloudConfig]);
        $httpClient = Mockery::mock(Client::class);
        $cloud = new CloudService($config, $httpClient);

        // when
        $isConfigured = $cloud->isConfigured();

        // then
        $this->assertTrue($isConfigured);
    }

    /** getSubscription */

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testGetSubscription(): void
    {
        // given
        $cloudConfig = ["public_key" => "PUBLIC", "secret_key" => "SECRET"];
        $config = new Config(["cloud" => $cloudConfig]);
        $httpClient = Mockery::mock(Client::class);
        $body = Mockery::mock(StreamInterface::class);
        $body->shouldReceive("__toString")->andReturn(json_encode([
            "id" => 1,
            "status" => "active",
        ]));
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive("getBody")
            ->with()
            ->andReturn($body);
        $httpClient->shouldReceive("request")
            ->with("GET", "https://biblys.cloud/api/stripe/subscription", [
                "auth" => ["PUBLIC", "SECRET"],
            ])->andReturn($response);

        $cloud = new CloudService($config, $httpClient);

        // when
        $subscription = $cloud->getSubscription();

        // then
        $this->assertEquals(
            new CloudSubscription(status: "active"),
            $subscription
        );
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testGetSubscriptionSilentlyIgnoresServerError(): void
    {
        // given
        $cloudConfig = ["public_key" => "PUBLIC", "secret_key" => "SECRET"];
        $config = new Config(["cloud" => $cloudConfig]);
        $exception = Mockery::mock(ServerException::class);
        $httpClient = Mockery::mock(Client::class);
        $httpClient->shouldReceive("request")
            ->andThrow($exception);

        $cloud = new CloudService($config, $httpClient);

        // when
        $subscription = $cloud->getSubscription();

        // then
        $this->assertNull($subscription);
    }
}
