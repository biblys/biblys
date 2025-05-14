<?php
/*
 * Copyright (C) 2025 Clément Latzarus
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

use Biblys\Service\CurrentUser;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Exception;
use Mockery;
use Model\PublisherQuery;
use Model\SupplierQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class SupplierControllerTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function setUp(): void
    {
        PublisherQuery::create()->deleteAll();
        SupplierQuery::create()->deleteAll();
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     * @throws Exception
     */
    public function testIndexAction(): void
    {
        // given
        $controller = new SupplierController();
        $supplier = ModelFactory::createSupplier(name: "FOURNITOU");
        $publisher = ModelFactory::createPublisher(name: "PUBLITOU");
        ModelFactory::createLink(publisher: $publisher, supplier: $supplier);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once();
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->indexAction($currentUser, $templateService);

        // then
        $this->assertStringContainsString("Fournisseurs", $response->getContent());
        $this->assertStringContainsString("FOURNITOU", $response->getContent());
        $this->assertStringContainsString("PUBLITOU", $response->getContent());
    }
}
