<?php

namespace Biblys\Database;

use Biblys\Service\Config;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class ConnectionTest extends TestCase
{
    public function testInitReturnsError500()
    {
        // then
        $this->expectException(ServiceUnavailableHttpException::class);
        $this->expectExceptionMessage("An error ocurred while connecting to database.");

        // given
        $config = new Config(["db" => []]);


        // when
        Connection::init($config);
    }
}
