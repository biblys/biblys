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


namespace Command;

use Biblys\Service\Slug\SlugService;
use Model\ArticleQuery;
use Model\BookCollectionQuery;
use Model\CustomerQuery;
use Model\OrderQuery;
use Model\PaymentQuery;
use Model\PeopleQuery;
use Model\PublisherQuery;
use Model\RoleQuery;
use Model\StockQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

require_once __DIR__ . "/../setUp.php";

class CreateSeedsCommandTest extends TestCase
{
    private const SEEDED_ARTICLE_TITLE = "L'Ordure du jeu";
    private const SEEDED_ORDERED_CATALOG_TITLE = "Au-revoir Mao";
    private const SEEDED_GOODIES_TITLE = "Tote bag Paronymie";
    private const SEEDED_TO_BE_PAID_CATALOG_TITLE = "Papeete";

    /**
     * The command is not idempotent: Publisher, BookCollection and People
     * reject duplicate names, so the entities it seeds must be removed first.
     * The site it creates is left alone: its domain is also ModelFactory's
     * default, so deleting by domain would drop the fixture other tests
     * resolve as site 1.
     *
     * @throws PropelException
     */
    protected function setUp(): void
    {
        PaymentQuery::create()->deleteAll();
        StockQuery::create()->deleteAll();
        OrderQuery::create()->deleteAll();
        CustomerQuery::create()->deleteAll();

        $seededTitles = array_merge(
            [self::SEEDED_ARTICLE_TITLE, self::SEEDED_GOODIES_TITLE],
            array_column(CreateSeedsCommand::CATALOG_ARTICLES, "title"),
        );
        foreach ($seededTitles as $seededTitle) {
            $seededArticle = ArticleQuery::create()->findOneByTitle($seededTitle);
            if ($seededArticle !== null) {
                RoleQuery::create()->filterByArticle($seededArticle)->delete();
                $seededArticle->delete();
            }
        }

        PeopleQuery::create()->filterByUrl("aymeric-buvard")->delete();
        foreach (CreateSeedsCommand::CATALOG_ARTICLES as $catalogArticle) {
            PeopleQuery::create()
                ->filterByFirstName($catalogArticle["firstName"])
                ->filterByLastName($catalogArticle["lastName"])
                ->delete();
        }
        BookCollectionQuery::create()->filterByName("Lis tes ratures")->delete();
        PublisherQuery::create()->filterByName("Les Éditions Paronymie")->delete();
    }

    /**
     * @throws PropelException
     */
    public function testExecuteSeedsAnArticleWithStockItems(): void
    {
        // given
        $commandTester = new CommandTester(new CreateSeedsCommand());

        // when
        $commandTester->execute([]);

        // then
        $this->assertEquals(Command::SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString("Seeds generated!", $commandTester->getDisplay());

        $stockItems = StockQuery::create()->orderById()->find();
        $expectedCount = 4 + count(CreateSeedsCommand::CATALOG_ARTICLES);
        $this->assertCount($expectedCount, $stockItems);

        $availableItem = $stockItems[0];
        $this->assertEquals(self::SEEDED_ARTICLE_TITLE, $availableItem->getArticle()->getTitle());
        $this->assertEquals(999, $availableItem->getSellingPrice());
        $this->assertNull($availableItem->getOrderId());

        $orderId = OrderQuery::create()->filterByShippingCost(300)->findOne()->getId();

        $orderedItem = $stockItems[$expectedCount - 3];
        $this->assertEquals(self::SEEDED_ORDERED_CATALOG_TITLE, $orderedItem->getArticle()->getTitle());
        $this->assertEquals(1800, $orderedItem->getSellingPrice());
        $this->assertEquals($orderId, $orderedItem->getOrderId());

        $orderedGoodiesItem = $stockItems[$expectedCount - 2];
        $this->assertEquals(self::SEEDED_GOODIES_TITLE, $orderedGoodiesItem->getArticle()->getTitle());
        $this->assertEquals(500, $orderedGoodiesItem->getSellingPrice());
        $this->assertEquals($orderId, $orderedGoodiesItem->getOrderId());

        $orderToBePaidItem = $stockItems[$expectedCount - 1];
        $this->assertEquals(self::SEEDED_TO_BE_PAID_CATALOG_TITLE, $orderToBePaidItem->getArticle()->getTitle());
        $this->assertEquals(900, $orderToBePaidItem->getSellingPrice());
        $this->assertNotEquals($orderId, $orderToBePaidItem->getOrderId());
    }

    /**
     * The seeded order carries a shipping cost and a weighed stock item so that
     * the invoice page shows both a shipping line and a total weight.
     *
     * @throws PropelException
     */
    public function testExecuteSeedsAnOrderWithShippingCostAndWeight(): void
    {
        // given
        $commandTester = new CommandTester(new CreateSeedsCommand());

        // when
        $commandTester->execute([]);

        // then
        $order = OrderQuery::create()->filterByShippingCost(300)->findOne();
        $this->assertEquals(300, $order->getShippingCost());
        $this->assertNotNull($order->getShippingMode(), "Le mode d'expédition doit être renseigné pour la ligne de port");

        $orderedItem = StockQuery::create()->filterByOrderId($order->getId())->findOne();
        $this->assertEquals(450, $orderedItem->getWeight());
    }

    /**
     * The seeded order is fully paid so that the invoice page shows the
     * "Règlement effectué le… par…" notice.
     *
     * @throws PropelException
     */
    public function testExecuteSeedsAPaidOrder(): void
    {
        // given
        $commandTester = new CommandTester(new CreateSeedsCommand());

        // when
        $commandTester->execute([]);

        // then
        $order = OrderQuery::create()->filterByShippingCost(300)->findOne();
        $this->assertNotNull($order->getPaymentDate(), "La commande des seeds doit être marquée comme payée");
        $this->assertEquals("stripe", $order->getPaymentMode());
        $this->assertEquals(0, $order->getAmountTobepaid());

        $payment = PaymentQuery::create()->filterByOrderId($order->getId())->findOne();
        $this->assertNotNull($payment, "Un paiement doit être rattaché à la commande des seeds");
        $this->assertEquals(1800 + 500 + 300, $payment->getAmount());
        $this->assertEquals("stripe", $payment->getMode());
        $this->assertNotNull($payment->getExecuted());
    }

    /**
     * @throws PropelException
     */
    public function testExecuteSeedsCatalogArticlesWithAuthorAndNewStockItem(): void
    {
        // given
        $commandTester = new CommandTester(new CreateSeedsCommand());

        // when
        $commandTester->execute([]);

        // then
        foreach (CreateSeedsCommand::CATALOG_ARTICLES as $catalogArticle) {
            $article = ArticleQuery::create()->findOneByTitle($catalogArticle["title"]);
            $this->assertNotNull($article, "Article {$catalogArticle["title"]} should have been seeded");
            $this->assertEquals($catalogArticle["ean"], $article->getEan());
            $this->assertEquals(
                "{$catalogArticle["firstName"]} {$catalogArticle["lastName"]}",
                $article->getAuthors()
            );

            $stockItems = StockQuery::create()->filterByArticle($article)->filterByOrderId(null)->find();
            $this->assertCount(1, $stockItems, "Article {$catalogArticle["title"]} should have one unordered stock item");
            $this->assertEquals("Neuf", $stockItems[0]->getCondition());
            $this->assertEquals($catalogArticle["price"], $stockItems[0]->getSellingPrice());
        }
    }

    /**
     * @throws PropelException
     */
    public function testExecuteSeedsCatalogArticlesWithDistinctEansAndSlugs(): void
    {
        // given
        $commandTester = new CommandTester(new CreateSeedsCommand());

        // when
        $commandTester->execute([]);

        // then
        $titles = array_column(CreateSeedsCommand::CATALOG_ARTICLES, "title");
        $articles = ArticleQuery::create()->filterByTitle($titles)->find();

        $eans = array_map(fn($article) => $article->getEan(), $articles->getData());
        $this->assertCount(count($titles), array_unique($eans), "Seeded EANs must be distinct");

        $urls = array_map(fn($article) => $article->getUrl(), $articles->getData());
        $this->assertCount(count($titles), array_unique($urls), "Seeded article slugs must be distinct");

        $slugService = new SlugService();
        foreach ($urls as $url) {
            $slugService->validateArticleSlug($url);
        }
        $this->addToAssertionCount(count($urls));
    }
}
