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


namespace Usecase;

use Biblys\Exception\InvalidEmailAddressException;
use Biblys\Service\CurrentSite;
use Biblys\Service\Mailer;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Mockery;
use PHPUnit\Framework\Constraint\Callback;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

require_once __DIR__ . "/../setUp.php";

class MarkOrderAsShippedUsecaseTest extends TestCase
{
    /**
     * @throws Exception
     * @throws PropelException
     * @throws InvalidEmailAddressException
     * @throws TransportExceptionInterface
     * @throws \Exception
     */
    public function testExecuteForOrderWithoutShippingOption()
    {
        // given
        $order = ModelFactory::createOrder(email: "customer@paronymie.fr");
        $site = ModelFactory::createSite();

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->expects("getOption")->andReturn(null);
        $currentSite->expects("getSite")->andReturn($site);
        $mailer = $this->createMock(Mailer::class);
        $templateService = Helpers::getTemplateService();

        $usecase = new MarkOrderAsShippedUsecase($currentSite, $templateService, $mailer);

        // when
        $usecase->execute($order, trackingNumber: null);

        // then
        $order->reload();
        $this->assertNotNull($order->getShippingDate());
    }

    /**
     * @throws Exception
     * @throws PropelException
     * @throws InvalidEmailAddressException
     * @throws TransportExceptionInterface
     * @throws \Exception
     */
    public function testExecute()
    {
        // given
        $shipping = ModelFactory::createShippingOption();
        $order = ModelFactory::createOrder(shippingOption: $shipping, email: "customer@paronymie.fr");
        $site = ModelFactory::createSite(domain: "paronymie-expeditions.fr");

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->expects("getSite")->andReturn($site);
        $currentSite->expects("getOption")->with("shipped_mail_subject")->andReturn(null);
        $currentSite->expects("getOption")->with("shipped_mail_message")->andReturn(null);
        $mailer = $this->createMock(Mailer::class);
        $mailer->expects($this->once())->method("send")->with(
            "customer@paronymie.fr",
            "Votre commande a été expédiée !",
            $this->contains([
                "Votre commande n°{$order->getId()} a été expédiée.",
                "https://paronymie-expeditions.fr/order/{$order->getSlug()}",
            ]),
        );
        $templateService = Helpers::getTemplateService();
        $usecase = new MarkOrderAsShippedUsecase($currentSite, $templateService, $mailer);

        // when
        $usecase->execute($order, trackingNumber: null);

        // then
        $order->reload();
        $this->assertNotNull($order->getShippingDate());
        $this->assertNull($order->getTrackNumber());
    }

    /**
     * @throws Exception
     * @throws PropelException
     * @throws InvalidEmailAddressException
     * @throws TransportExceptionInterface
     * @throws \Exception
     */
    public function testExecuteWithCustomShippingMessage()
    {
        // given
        $shipping = ModelFactory::createShippingOption();
        $order = ModelFactory::createOrder(shippingOption: $shipping, email: "customer@paronymie.fr");
        $site = ModelFactory::createSite();

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->expects("getSite")->andReturn($site);
        $currentSite->expects("getOption")->with("shipped_mail_subject")->andReturn("est en route !");
        $currentSite->expects("getOption")->with("shipped_mail_message")
            ->andReturn("Votre colis va bientôt prendre la route !");
        $mailer = $this->createMock(Mailer::class);
        $mailer->expects($this->once())->method("send")->with(
            "customer@paronymie.fr",
            "Votre commande est en route !",
            $this->contains(["Votre colis va bientôt prendre la route !"]),
        );
        $templateService = Helpers::getTemplateService();
        $usecase = new MarkOrderAsShippedUsecase($currentSite, $templateService, $mailer);

        // when
        $usecase->execute($order, trackingNumber: null);

        // then
        $order->reload();
        $this->assertNotNull($order->getShippingDate());
        $this->assertNull($order->getTrackNumber());
    }

    /**
     * @throws Exception
     * @throws InvalidEmailAddressException
     * @throws PropelException
     * @throws TransportExceptionInterface
     * @throws \Exception
     */
    public function testExecuteWithTrackedShipping()
    {
        // given
        $shipping = ModelFactory::createShippingOption(type: "colissimo");
        $order = ModelFactory::createOrder(shippingOption: $shipping, email: "customer@paronymie.fr");
        $site = ModelFactory::createSite();

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->expects("getSite")->andReturn($site);
        $currentSite->expects("getOption")->with("shipped_mail_subject")->andReturn(null);
        $currentSite->expects("getOption")->with("shipped_mail_message")->andReturn(null);
        $mailer = $this->createMock(Mailer::class);
        $mailer->expects($this->once())->method("send")->with(
            "customer@paronymie.fr",
            "Votre commande a été expédiée !",
            $this->contains([
                "Votre commande n°{$order->getId()} a été expédiée",
                "Vous pouvez suivre votre colis en cliquant sur le lien suivant :",
                "https://www.laposte.fr/outils/suivre-vos-envois?code=ABCD1234",
            ]),
        );
        $templateService = Helpers::getTemplateService();
        $usecase = new MarkOrderAsShippedUsecase($currentSite, $templateService, $mailer);

        // when
        $usecase->execute($order, trackingNumber: "ABCD1234");

        // then
        $order->reload();
        $this->assertEquals("ABCD1234", $order->getTrackNumber());
    }

    /**
     * @throws Exception
     * @throws InvalidEmailAddressException
     * @throws PropelException
     * @throws TransportExceptionInterface
     * @throws \Exception
     */
    public function testExecuteWithPickupShipping()
    {
        // given
        $shipping = ModelFactory::createShippingOption(type: "magasin");
        $order = ModelFactory::createOrder(shippingOption: $shipping, email: "customer@paronymie.fr");
        $site = ModelFactory::createSite();

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->expects("getSite")->andReturn($site);
        $currentSite->expects("getOption")->with("shipped_mail_message")->andReturn(null);
        $currentSite->expects("getOption")->with("shipped_mail_subject")->andReturn(null);
        $mailer = $this->createMock(Mailer::class);
        $mailer->expects($this->once())->method("send")->with(
            "customer@paronymie.fr",
            "Votre commande est disponible en magasin !",
            $this->contains([
                "Votre commande n°{$order->getId()} est disponible en magasin.",
                "Retrouvez les coordonnées et horaires d'ouverture du magasin sur notre site.",
            ]),
        );
        $templateService = Helpers::getTemplateService();
        $usecase = new MarkOrderAsShippedUsecase($currentSite, $templateService, $mailer);

        // when
        $usecase->execute($order, null);

        // then
        $order->reload();
        $this->assertNotNull($order->getShippingDate());
    }

    private function contains(array $needles): Callback
    {
        return $this->callback(function ($arg) use ($needles) {
            foreach ($needles as $needle) {
                if (!str_contains($arg, $needle)) {
                    return false;
                }
            }

            return true;
        });
    }
}
