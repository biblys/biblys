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

use Model\ArticleQuery;
use Model\BookCollectionQuery;
use Model\CustomerQuery;
use Model\OrderQuery;
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
        StockQuery::create()->deleteAll();
        OrderQuery::create()->deleteAll();
        CustomerQuery::create()->deleteAll();

        $seededArticle = ArticleQuery::create()->findOneByTitle(self::SEEDED_ARTICLE_TITLE);
        if ($seededArticle !== null) {
            RoleQuery::create()->filterByArticle($seededArticle)->delete();
            $seededArticle->delete();
        }

        PeopleQuery::create()->filterByUrl("aymeric-buvard")->delete();
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
        $this->assertCount(2, $stockItems);

        $availableItem = $stockItems[0];
        $this->assertEquals(self::SEEDED_ARTICLE_TITLE, $availableItem->getArticle()->getTitle());
        $this->assertEquals(999, $availableItem->getSellingPrice());
        $this->assertNull($availableItem->getOrderId());

        $orderedItem = $stockItems[1];
        $this->assertEquals(self::SEEDED_ARTICLE_TITLE, $orderedItem->getArticle()->getTitle());
        $this->assertEquals(999, $orderedItem->getSellingPrice());
        $this->assertEquals(OrderQuery::create()->findOne()->getId(), $orderedItem->getOrderId());
    }
}
