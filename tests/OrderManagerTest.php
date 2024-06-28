<?php

use Biblys\Data\ArticleType;
use Biblys\Service\CurrentSite;
use Biblys\Test\EntityFactory;
use Biblys\Test\ModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;

require_once "setUp.php";

class OrderManagerTest extends TestCase
{
    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testMarkAsPayed()
    {
        // given
        $orderManager = new OrderManager();
        $order = EntityFactory::createOrder(orderEmail: "customer@paronymie.fr");
        $currentSite = Mockery::mock(CurrentSite::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);

        // when
        $orderManager->markAsPayed($currentSite, $urlGenerator, $order);

        // then
        $this->assertTrue($order->isPayed());
    }

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function testMarkAsPayedWithEbooks()
    {
        // given
        $site = ModelFactory::createSite();
        $user = ModelFactory::createUser();
        $orderManager = new OrderManager();
        $orderEntity = EntityFactory::createOrder(user: $user, orderEmail: "customer@paronymie.fr");
        $ebook = ModelFactory::createArticle(typeId: ArticleType::EBOOK);
        $item = EntityFactory::createStock(["article_id" => $ebook->getId()]);
        $orderManager->addStock($orderEntity, $item);

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->expects("getOption")->andReturn($ebook->getPublisherId());
        $currentSite->expects("getSite")->andReturn($site);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->expects("generate")->andReturn("url");

        // when
        $orderManager->markAsPayed($currentSite, $urlGenerator, $orderEntity);

        // then
        $this->assertTrue($orderEntity->isPayed());
    }
}
