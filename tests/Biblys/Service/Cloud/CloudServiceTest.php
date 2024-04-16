<?php

namespace Biblys\Service\Cloud;

use Biblys\Service\Config;
use Exception;
use GuzzleHttp\Client;
use Mockery;
use PHPUnit\Framework\TestCase;

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
}
