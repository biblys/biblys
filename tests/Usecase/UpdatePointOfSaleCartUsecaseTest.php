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


namespace Usecase;

use Biblys\Exception\CannotFindCustomerException;
use Biblys\Test\ModelFactory;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

class UpdatePointOfSaleCartUsecaseTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function testRenamesTheCart(): void
    {
        // given
        $cart = ModelFactory::createCart();
        $cart->setType("shop");
        $cart->setTitle("Ancien nom");
        $cart->save();
        $usecase = new UpdatePointOfSaleCartUsecase();

        // when
        $usecase->execute($cart->getId(), title: "Nouveau nom");

        // then
        $cart->reload();
        $this->assertEquals("Nouveau nom", $cart->getTitle());
    }

    /**
     * @throws PropelException
     * @throws CannotFindCustomerException
     */
    public function testAssociatesACustomerToTheCart(): void
    {
        // given
        $cart = ModelFactory::createCart();
        $cart->setType("shop");
        $cart->save();
        $customer = ModelFactory::createCustomer();
        $usecase = new UpdatePointOfSaleCartUsecase();

        // when
        $usecase->execute($cart->getId(), customerId: $customer->getId());

        // then
        $cart->reload();
        $this->assertEquals($customer->getId(), $cart->getCustomerId());
    }

    /**
     * @throws PropelException
     * @throws CannotFindCustomerException
     */
    public function testUnassociatesTheCustomerFromTheCart(): void
    {
        // given
        $cart = ModelFactory::createCart();
        $cart->setType("shop");
        $customer = ModelFactory::createCustomer();
        $cart->setCustomerId($customer->getId());
        $cart->save();
        $usecase = new UpdatePointOfSaleCartUsecase();

        // when
        $usecase->execute($cart->getId(), customerId: 0);

        // then
        $cart->reload();
        $this->assertNull($cart->getCustomerId());
    }

    /**
     * @throws PropelException
     */
    public function testThrowsIfCustomerDoesNotExist(): void
    {
        // given
        $cart = ModelFactory::createCart();
        $cart->setType("shop");
        $cart->save();
        $usecase = new UpdatePointOfSaleCartUsecase();

        // then
        $this->expectException(CannotFindCustomerException::class);

        // when
        $usecase->execute($cart->getId(), customerId: 99999);
    }

    /**
     * @throws PropelException
     * @throws CannotFindCustomerException
     */
    public function testDoesNotChangeAnythingIfNoParameterIsProvided(): void
    {
        // given
        $cart = ModelFactory::createCart();
        $cart->setType("shop");
        $cart->setTitle("Panier de test");
        $customer = ModelFactory::createCustomer();
        $cart->setCustomerId($customer->getId());
        $cart->save();
        $usecase = new UpdatePointOfSaleCartUsecase();

        // when
        $usecase->execute($cart->getId());

        // then
        $cart->reload();
        $this->assertEquals("Panier de test", $cart->getTitle());
        $this->assertEquals($customer->getId(), $cart->getCustomerId());
    }
}
