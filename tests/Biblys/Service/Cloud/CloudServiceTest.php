<?php

namespace Biblys\Service;

use Biblys\Service\Cloud\CloudService;
use Exception;
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
        $cloud = new CloudService($config);

        // when
        $isConfigured = $cloud->isConfigured();

        // then
        $this->assertEquals(false, $isConfigured);
    }

    /**
     * @throws Exception
     */
    public function testIsConfiguredReturnsTrueWhenConfigIsPresent()
    {
        // given
        $cloudConfig = ["public_key" => "cus_abcd1234"];
        $config = new Config(["cloud" => $cloudConfig]);
        $cloud = new CloudService($config);

        // when
        $isConfigured = $cloud->isConfigured();

        // then
        $this->assertEquals(true, $isConfigured);
    }
}
