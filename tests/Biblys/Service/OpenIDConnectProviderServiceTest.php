<?php

namespace Biblys\Service;

use Biblys\Test\Helpers;
use Exception;
use PHPUnit\Framework\TestCase;

class OpenIDConnectProviderServiceTest extends TestCase
{

    /**
     * @throws Exception
     */
    public function testGetClient()
    {
        // given
        $config = new Config();
        $service = new OpenIDConnectProviderService($config);

        // when
        $throwException = Helpers::runAndCatchException(function() use ($service) {
            $service->getClient();
        });

        // then
        $this->assertInstanceOf(Exception::class, $throwException);
        $this->assertEquals("Invalid identity provider configuration", $throwException->getMessage());
    }
}
