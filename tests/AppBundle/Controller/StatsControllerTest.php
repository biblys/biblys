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


namespace AppBundle\Controller;

use Biblys\Service\Config;
use Exception;
use PHPUnit\Framework\TestCase;

class StatsControllerTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testMatomo()
    {
        // given
        $controller = new StatsController();
        $config = new Config([
            "matomo" => [
                "domain" => "example.org",
                "login" => "login",
                "md5pass" => "password",
            ]
        ]);

        // when
        $response = $controller->matomo($config);

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(
            "https://example.org/index.php?module=Login&action=logme&login=login&password=password",
            $response->getTargetUrl(),
        );
    }

    /**
     * @throws Exception
     */
    public function testUmami()
    {
        // given
        $controller = new StatsController();
        $config = new Config(["umami" => ["share_url" => "https://example.org/umami"]]);

        // when
        $response = $controller->umami($config);

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("https://example.org/umami", $response->getTargetUrl());
    }
}
