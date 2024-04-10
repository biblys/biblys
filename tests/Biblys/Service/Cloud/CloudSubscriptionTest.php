<?php

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
