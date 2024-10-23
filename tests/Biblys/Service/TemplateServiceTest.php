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
use Twig\Error\LoaderError;
use Twig\Error\SyntaxError;

require_once __DIR__."/../../setUp.php";

class TemplateServiceTest extends TestCase
{

    /**
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function testRenderFromString()
    {
        // given
        $config = new Config();
        $currentSite = $this->createMock(CurrentSite::class);
        $currentUser = $this->createMock(CurrentUser::class);
        $request = new Request();
        $metaTagsService = $this->createMock(MetaTagsService::class);
        $templateService = new TemplateService(
            config: $config,
            currentSiteService: $currentSite,
            currentUserService: $currentUser,
            metaTagsService: $metaTagsService,
            request: $request,
        );

        // when
        $response = $templateService->renderResponseFromString(
            "Hello <b>{{ name }}</b>!",
            ["name" => "World"]
        );

        // then
        $this->assertEquals(
            "Hello <b>World</b>!",
            $response->getContent(),
        );
    }
}
