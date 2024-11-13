<?php

namespace Model;

use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    public function testGetTrackingLink(): void
    {
        // given
        $order = new Order();
        $order->setShippingMode("normal");

        // when
        $result = $order->getTrackingLink();

        // then
        $this->assertEquals("", $result);
    }

    public function testGetTrackingLinkWithoutTrackingNumber(): void
    {
        // given
        $order = new Order();
        $order->setShippingMode("suivi");

        // when
        $result = $order->getTrackingLink();

        // then
        $this->assertEquals("", $result);
    }

    public function testGetTrackingLinkForColissimo(): void
    {
        // given
        $order = new Order();
        $order->setShippingMode("suivi");
        $order->setTrackNumber("1234567890");

        // when
        $result = $order->getTrackingLink();

        // then
        $this->assertEquals("https://www.laposte.fr/outils/suivre-vos-envois?code=1234567890", $result);
    }

    public function testGetTrackingLinkForMondialRelay(): void
    {
        // given
        $order = new Order();
        $order->setShippingMode("mondial-relay");
        $order->setTrackNumber("1234567890");
        $order->setPostalcode(46800);

        // when
        $result = $order->getTrackingLink();

        // then
        $this->assertEquals(
            "https://www.mondialrelay.fr/suivi-de-colis?numeroExpedition=1234567890&codePostal=46800",
            $result
        );
    }
}
