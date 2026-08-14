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


namespace Biblys\Test;

use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__ . "/../../setUp.php";

class ModelFactoryTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function testCreateOrderAttachesUserToGeneratedCustomer(): void
    {
        // given
        $user = ModelFactory::createUser(email: "chart.reuse@paronymie.fr");

        // when
        $order = ModelFactory::createOrder(user: $user);

        // then
        $customer = $order->getCustomer();
        $this->assertEquals($user->getId(), $customer->getUserId());
        $this->assertNull($customer->getAxysAccountId());
    }
}
