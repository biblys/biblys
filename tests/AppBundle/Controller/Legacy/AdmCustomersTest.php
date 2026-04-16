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


namespace AppBundle\Controller\Legacy;

use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Exception;
use Model\CustomerQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . "/../../../setUp.php";

class AdmCustomersTest extends TestCase
{
    /**
     * @throws PropelException
     */
    protected function setUp(): void
    {
        CustomerQuery::create()->deleteAll();
    }

    private function getController(): callable
    {
        static $controller = null;
        if ($controller === null) {
            $controller = require __DIR__ . "/../../../../controllers/common/php/adm_customers.php";
        }
        return $controller;
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testDisplaysCustomersList(): void
    {
        // given
        $controller = $this->getController();
        $request = new Request();
        $templateService = Helpers::getTemplateService();
        $customer = ModelFactory::createCustomer(firstName: "Camille", lastName: "Honnête");
        ModelFactory::createStockItem(sellingPrice: 999, customer: $customer);
        ModelFactory::createStockItem(sellingPrice: 999, customer: $customer);
        ModelFactory::createStockItem(sellingPrice: 999, customer: $customer);

        // when
        $response = $controller(
            $request,
            $templateService,
        );

        // then
        $this->assertEquals(200, $response->getStatusCode(), "should display list");
        $this->assertStringContainsString("Camille Honnête", $response->getContent());
        $this->assertStringContainsString("3", $response->getContent());
        $this->assertStringContainsString("29,97&nbsp;&euro;", $response->getContent());
    }
}
