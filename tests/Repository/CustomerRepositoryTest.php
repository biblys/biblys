<?php

/*
 * Copyright (C) 2026 Clément Latzarus
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

namespace Repository;

use Biblys\Test\ModelFactory;
use Model\CartQuery;
use Model\CustomerQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__ . "/../setUp.php";

class CustomerRepositoryTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function setUp(): void
    {
        CartQuery::create()->deleteAll();
        CustomerQuery::create()->deleteAll();
    }

    /**
     * @throws PropelException
     */
    public function testSearchFindsCustomersByLastName(): void
    {
        // given
        $coade = ModelFactory::createCustomer(lastName: "Coade", email: "silas@paronymie.fr");
        ModelFactory::createCustomer(lastName: "Aubert", email: "ines@paronymie.fr");
        $repository = new CustomerRepository();

        // when
        $customers = $repository->search("coad");

        // then
        $this->assertCount(1, $customers);
        $this->assertEquals($coade->getId(), $customers[0]->getId());
    }

    /**
     * @throws PropelException
     */
    public function testSearchFindsCustomersByEmail(): void
    {
        // given
        $customer = ModelFactory::createCustomer(email: "silas.coade@paronymie.fr");
        ModelFactory::createCustomer(lastName: "Aubert", email: "ines@exemple.fr");
        $repository = new CustomerRepository();

        // when
        $customers = $repository->search("paronymie");

        // then
        $this->assertCount(1, $customers);
        $this->assertEquals($customer->getId(), $customers[0]->getId());
    }

    /**
     * @throws PropelException
     */
    public function testSearchRequiresAllKeywordsToMatch(): void
    {
        // given
        $silas = ModelFactory::createCustomer(firstName: "Silas", lastName: "Coade", email: "a@exemple.fr");
        ModelFactory::createCustomer(firstName: "Ines", lastName: "Coade", email: "b@exemple.fr");
        $repository = new CustomerRepository();

        // when
        $customers = $repository->search("Silas Coade");

        // then
        $this->assertCount(1, $customers);
        $this->assertEquals($silas->getId(), $customers[0]->getId());
    }

    /**
     * @throws PropelException
     */
    public function testSearchReturnsEmptyArrayWhenNoCustomerMatches(): void
    {
        // given
        ModelFactory::createCustomer();
        $repository = new CustomerRepository();

        // when
        $customers = $repository->search("introuvable");

        // then
        $this->assertSame([], $customers);
    }

    /**
     * @throws PropelException
     */
    public function testSearchReturnsNothingForBlankTerm(): void
    {
        // given
        ModelFactory::createCustomer();
        $repository = new CustomerRepository();

        // when
        $customers = $repository->search("   ");

        // then
        $this->assertSame([], $customers);
    }

    /**
     * @throws PropelException
     */
    public function testSearchLimitsNumberOfResults(): void
    {
        // given
        foreach (range(1, 4) as $i) {
            ModelFactory::createCustomer(email: "client{$i}@exemple.fr");
        }
        $repository = new CustomerRepository();

        // when
        $customers = $repository->search("Coade", limit: 3);

        // then
        $this->assertCount(3, $customers);
    }
}
