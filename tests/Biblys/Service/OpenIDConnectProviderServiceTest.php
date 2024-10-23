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
