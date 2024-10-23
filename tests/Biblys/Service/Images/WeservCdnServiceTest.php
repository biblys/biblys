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


namespace Biblys\Service\Images;

use PHPUnit\Framework\TestCase;

class WeservCdnServiceTest extends TestCase
{
    public function testBuildUrl()
    {
        // given
        $localUrl = "https://www.biblys.fr/images/1955.jpg";
        $service = new WeservCdnService();

        // when
        $url = $service->buildUrl($localUrl);

        // then
        $this->assertEquals(
            "//images.weserv.nl/?url=https%3A%2F%2Fwww.biblys.fr%2Fimages%2F1955.jpg",
            $url,
            "builds url for Weserv CDN",
        );
    }

    public function testBuildUrlWithWidth()
    {
        // given
        $localUrl = "https://www.biblys.fr/images/1955.jpg";
        $service = new WeservCdnService();

        // when
        $url = $service->buildUrl(url: $localUrl, width: 512);

        // then
        $this->assertEquals(
            "//images.weserv.nl/?url=https%3A%2F%2Fwww.biblys.fr%2Fimages%2F1955.jpg&w=512",
            $url,
            "builds url for Weserv CDN",
        );
    }

    public function testBuildUrlWithHeight()
    {
        // given
        $localUrl = "https://www.biblys.fr/images/1955.jpg";
        $service = new WeservCdnService();

        // when
        $url = $service->buildUrl(url: $localUrl, height: 768);

        // then
        $this->assertEquals(
            "//images.weserv.nl/?url=https%3A%2F%2Fwww.biblys.fr%2Fimages%2F1955.jpg&h=768",
            $url,
            "builds url for Weserv CDN",
        );
    }
}
