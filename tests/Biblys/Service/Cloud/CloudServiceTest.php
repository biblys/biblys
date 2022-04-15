<?php

namespace Biblys\Service;

use Biblys\Service\Cloud\CloudService;
use PHPUnit\Framework\TestCase;

class CloudServiceTest extends TestCase
{
    public function testIsConfiguredReturnsFalseWhenConfigIsMissing()
    {
        // given
        $cloudConfig = ["expires" => "2020-01-01"];
        $config = $this->createMock(Config::class);
        $config->method("get")->with("cloud")->willReturn($cloudConfig);
        $cloud = new CloudService($config);

        // when
        $isConfigured = $cloud->isConfigured();

        // then
        $this->assertEquals(false, $isConfigured);
    }

    public function testIsConfiguredReturnsTrueWhenConfigIsPresent()
    {
        // given
        $cloudConfig = ["public_key" => "cus_abcd1234"];
        $config = $this->createMock(Config::class);
        $config->method("get")->with("cloud")->willReturn($cloudConfig);
        $cloud = new CloudService($config);

        // when
        $isConfigured = $cloud->isConfigured();

        // then
        $this->assertEquals(true, $isConfigured);
    }
}
