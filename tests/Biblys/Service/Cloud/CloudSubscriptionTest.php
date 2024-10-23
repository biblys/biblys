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


namespace Biblys\Service\Cloud;

use PHPUnit\Framework\TestCase;

class CloudSubscriptionTest extends TestCase
{

    public function testIsActiveForActiveSubscription()
    {
        // given
        $subscription = new CloudSubscription(status: "active");

        // when
        $isActive = $subscription->isActive();

        // then
        $this->assertTrue($isActive);
    }

    public function testIsActiveForFreeTrial()
    {
        // given
        $subscription = new CloudSubscription(status: "trialing");

        // when
        $isActive = $subscription->isActive();

        // then
        $this->assertTrue($isActive);
    }

    public function testIsActiveForUnpaidSubscription()
    {
        // given
        $subscription = new CloudSubscription(status: "unpaid");

        // when
        $isActive = $subscription->isActive();

        // then
        $this->assertFalse($isActive);
    }
}
