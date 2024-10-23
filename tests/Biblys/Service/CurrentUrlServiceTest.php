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

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class CurrentUrlServiceTest extends TestCase
{
    public function testGetRelativeUrl()
    {
        // given

        $request = $this->createMock(Request::class);
        $request->method("getSchemeAndHttpHost")->willReturn("https://www.paronymie.fr");
        $request->method("getBaseUrl")->willReturn("/admin");
        $request->method("getPathInfo")->willReturn("/current-page");
        $currentUrlService = new CurrentUrlService($request);

        // when
        $url = $currentUrlService->getRelativeUrl();

        // then
        $this->assertEquals(
            "/admin/current-page",
            $url,
            "returns current url"
        );
    }

    public function testGetAbsoluteUrl()
    {
        // given
        $request = $this->createMock(Request::class);
        $request->method("getSchemeAndHttpHost")->willReturn("https://www.paronymie.fr");
        $request->method("getBaseUrl")->willReturn("/admin");
        $request->method("getPathInfo")->willReturn("/current-page");
        $currentUrlService = new CurrentUrlService($request);

        // when
        $url = $currentUrlService->getAbsoluteUrl();

        // then
        $this->assertEquals(
            "https://www.paronymie.fr/admin/current-page",
            $url,
            "returns current url"
        );
    }
}
