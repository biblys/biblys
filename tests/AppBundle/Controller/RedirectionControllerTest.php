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

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Model\RedirectionQuery;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

class RedirectionControllerTest extends TestCase
{
    /**
     * @throws PropelException
     */
    protected function setUp(): void
    {
        RedirectionQuery::create()->deleteAll();
    }

    /**
     * @throws PropelException
     * @throws Exception
     * @throws \Exception
     */
    public function testIndexAction()
    {
        // given
        $controller = new RedirectionController();

        $site = ModelFactory::createSite();
        ModelFactory::createRedirection(site: $site, oldUrl: "/old-url", newUrl: "/new-url");

        $currentUser = $this->createMock(CurrentUser::class);
        $currentUser->method("authAdmin");
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getId")->willReturn($site->getId());
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->indexAction($currentUser, $currentSite, $templateService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("Redirections", $response->getContent());
        $this->assertStringContainsString("/old-url", $response->getContent());
        $this->assertStringContainsString("/new-url", $response->getContent());
    }
}
