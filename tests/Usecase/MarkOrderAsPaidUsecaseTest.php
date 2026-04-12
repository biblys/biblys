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

use Biblys\Data\ArticleType;
use Biblys\Exception\InvalidEmailAddressException;
use Biblys\Service\CurrentSite;
use Biblys\Service\Mailer;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MarkOrderAsPaidUsecaseTest extends TestCase
{
    /**
     * @throws PropelException
     * @throws InvalidEmailAddressException
     * @throws Exception
     * @throws TransportExceptionInterface
     * @throws \Exception
     */
    public function testMarkingAsPaidOrderWithPhysicalArticles(): void
    {
        // given
        $physicalArticle = ModelFactory::createArticle();
        $stockItem = ModelFactory::createStockItem(article: $physicalArticle);
        $order = ModelFactory::createOrder(
            slug: "order-123",
            email: "payer@paronymie.fr",
        );
        $order->addStockItem($stockItem);

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->expects($this->once())->method("generate")->with(
            "legacy_order",
            ["url" => "order-123"],
        )->willReturn("https://paronymie.fr/order/order-123");
        $templateService = Helpers::getTemplateService();
        $mailer = $this->createMock(Mailer::class);
        $mailer->expects($this->once())->method("send")->with(
            "payer@paronymie.fr",
            "Paiement pour votre commande bien reçu",
            Helpers::stringContainsString([
                "Merci !",
                "Votre paiement pour la commande n° {$order->getId()} a bien été reçu.",
                "Montant : 9,99 €",
                "Mode de règlement : Carte bancaire",
                "https://paronymie.fr/order/order-123",
            ], $this),
        );

        $usecase = new MarkOrderAsPaidUsecase(
            urlGenerator: $urlGenerator,
            templateService: $templateService,
            mailer: $mailer,
            addArticleToUserLibraryUsecase: $this->createMock(AddArticleToUserLibraryUsecase::class),
        );

        // when
        $usecase->execute($order, payedAmountInCents: 999, paymentMode: "Carte bancaire");

        // then
        $this->assertTrue($order->isPaid(), "order is marked as paid");
        $this->assertFalse($order->isShipped(), "order is not marked as shipped");
    }

    /**
     * @throws PropelException
     * @throws InvalidEmailAddressException
     * @throws Exception
     * @throws TransportExceptionInterface
     * @throws \Exception
     */
    public function testMarkingAsPaidOrderWithDownloadableArticles(): void
    {
        // given
        $downloadableArticle = ModelFactory::createArticle(typeId: ArticleType::EBOOK);
        $stockItem = ModelFactory::createStockItem(article: $downloadableArticle);
        $user = ModelFactory::createUser();
        $order = ModelFactory::createOrder(
            slug: "order-123",
            email: "payer@paronymie.fr",
        );
        $order->addStockItem($stockItem);
        $order->setUser($user);

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method("generate")->willReturnMap([
            ["legacy_order", ["url" => "order-123"], 1, "https://paronymie.fr/order/order-123"],
            ["user_library", [], 1, "https://paronymie.fr/user/library"],
        ]);
        $templateService = Helpers::getTemplateService();
        $mailer = $this->createMock(Mailer::class);
        $mailer->expects($this->once())->method("send")->with(
            $this->anything(),
            $this->anything(),
            Helpers::stringContainsString([
                "Vous pouvez télécharger les articles numériques de votre commande depuis",
                "https://paronymie.fr/user/library",
            ], $this),
        );
        $currentSite = $this->createMock(CurrentSite::class);

        $addArticleToUserLibraryUsecase = $this->getMockBuilder(AddArticleToUserLibraryUsecase::class)
            ->disableOriginalConstructor()
            ->onlyMethods(["execute"])
            ->getMock();
        $addArticleToUserLibraryUsecase->expects($this->once())->method("execute")->with(
            $user,
            null,          // article
            [$stockItem],  // items
            false,         // allowsPreDownload
            true,          // sendEmail
        );

        $usecase = new MarkOrderAsPaidUsecase(
            urlGenerator: $urlGenerator,
            templateService: $templateService,
            mailer: $mailer,
            addArticleToUserLibraryUsecase: $addArticleToUserLibraryUsecase,
        );

        // when
        $usecase->execute($order, payedAmountInCents: 999, paymentMode: "Carte bancaire");

        // then
        $order->reload();
        $this->assertTrue($order->isPaid(), "order is marked as paid");
        $this->assertTrue($order->isShipped(), "order marked as shipped");
    }

    /**
     * @throws PropelException
     * @throws InvalidEmailAddressException
     * @throws Exception
     * @throws TransportExceptionInterface
     * @throws \Exception
     */
    public function testMarkingAsPaidOrderWithBothArticleTypes(): void
    {
        // given
        $user = ModelFactory::createUser();
        $order = ModelFactory::createOrder(
            slug: "order-123",
            email: "payer@paronymie.fr",
        );
        $physicalArticle = ModelFactory::createArticle();
        $physicalStockItem = ModelFactory::createStockItem(article: $physicalArticle);
        $downloadableArticle = ModelFactory::createArticle(typeId: ArticleType::EBOOK);
        $downloadableStockItem = ModelFactory::createStockItem(article: $downloadableArticle);
        $order->addStockItem($physicalStockItem);
        $order->addStockItem($downloadableStockItem);
        $order->setUser($user);

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method("generate")->willReturnMap([
            ["legacy_order", ["url" => "order-123"], 1, "https://paronymie.fr/order/order-123"],
            ["user_library", [], 1, "https://paronymie.fr/user/library"],
        ]);
        $templateService = Helpers::getTemplateService();
        $mailer = $this->createMock(Mailer::class);
        $mailer->expects($this->once())->method("send")->with(
            $this->anything(),
            $this->anything(),
            Helpers::stringContainsString([
                "Vous pouvez télécharger les articles numériques de votre commande depuis",
                "https://paronymie.fr/user/library",
            ], $this),
        );
        $currentSite = $this->createMock(CurrentSite::class);

        $addArticleToUserLibraryUsecase = $this->getMockBuilder(AddArticleToUserLibraryUsecase::class)
            ->disableOriginalConstructor()
            ->onlyMethods(["execute"])
            ->getMock();
        $addArticleToUserLibraryUsecase->expects($this->once())->method("execute")->with(
            $user,
            null,          // article
            [$downloadableStockItem],  // items
            false,         // allowsPreDownload
            true,          // sendEmail
        );

        $usecase = new MarkOrderAsPaidUsecase(
            urlGenerator: $urlGenerator,
            templateService: $templateService,
            mailer: $mailer,
            addArticleToUserLibraryUsecase: $addArticleToUserLibraryUsecase,
        );

        // when
        $usecase->execute($order, payedAmountInCents: 999, paymentMode: "Carte bancaire");

        // then
        $this->assertTrue($order->isPaid(), "order is marked as paid");
        $this->assertFalse($order->isShipped(), "order marked as shipped");
    }
}
