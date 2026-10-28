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

use Biblys\Test\ModelFactory;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__ . "/../setUp.php";

class ChangeStockItemArticleUsecaseTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function testExecuteLinksStockItemToNewArticle(): void
    {
        // given
        ModelFactory::createSite();
        $originalArticle = ModelFactory::createArticle(title: "Article d'origine");
        $newArticle = ModelFactory::createArticle(title: "Nouvel article");
        $stockItem = ModelFactory::createStockItem(
            article: $originalArticle,
            sellingPrice: 1200,
            condition: "Occasion",
            stockage: "Étagère B",
        );
        $usecase = new ChangeStockItemArticleUsecase();

        // when
        $usecase->execute($stockItem, $newArticle);

        // then
        $stockItem->reload();
        $this->assertEquals(
            $newArticle->getId(),
            $stockItem->getArticleId(),
            "should link the stock item to the new article"
        );
        $this->assertEquals(1200, $stockItem->getSellingPrice(), "should keep the selling price");
        $this->assertEquals("Occasion", $stockItem->getCondition(), "should keep the condition");
        $this->assertEquals("Étagère B", $stockItem->getStockage(), "should keep the stockage");
    }
}
