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

use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\CurrentSite;
use Biblys\Test\ModelFactory;
use DateTime;
use Model\OrderQuery;
use Model\Site;
use Model\StockQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use Symfony\Component\Console\Tester\CommandTester;

require_once __DIR__ . "/../setUp.php";

class RecalculateOrdersTaxCommandTest extends TestCase
{
    /**
     * @throws PropelException
     */
    protected function setUp(): void
    {
        // The legacy StockManager writes via raw SQL, so Propel's instance pool
        // would otherwise return stale objects when reloading after --apply.
        Propel::disableInstancePooling();
        StockQuery::create()->deleteAll();
        OrderQuery::create()->deleteAll();
    }

    /**
     * @throws PropelException
     */
    private function buildCommandTester(Site $site): CommandTester
    {
        $site->setTva("fr");
        $site->save();
        LegacyCodeHelper::setGlobalSite($site);

        $currentSite = new CurrentSite($site);
        $command = new RecalculateOrdersTaxCommand($currentSite);

        return new CommandTester($command);
    }

    /**
     * @throws PropelException
     */
    public function testDryRunDoesNotWrite(): void
    {
        // given
        $site = ModelFactory::createSite();
        $order = ModelFactory::createOrder();
        $stockItem = ModelFactory::createStockItem(
            order: $order,
            sellingPrice: 2690,
            sellingDate: new DateTime("2024-06-01"),
        );
        $commandTester = $this->buildCommandTester($site);

        // when
        $commandTester->execute([]);

        // then
        $reloaded = StockQuery::create()->findPk($stockItem->getId());
        $this->assertEquals(0, $reloaded->getSellingPriceTva() ?? 0);
        $this->assertEquals(0, $reloaded->getTvaRate() ?? 0);
    }

    /**
     * @throws PropelException
     */
    public function testApplyRecalculatesTaxAndKeepsPriceUnchanged(): void
    {
        // given
        $site = ModelFactory::createSite();
        $order = ModelFactory::createOrder();
        $stockItem = ModelFactory::createStockItem(
            order: $order,
            sellingPrice: 2690,
            sellingDate: new DateTime("2024-06-01"),
        );
        $commandTester = $this->buildCommandTester($site);

        // when
        $commandTester->execute(["--apply" => true]);

        // then
        $reloaded = StockQuery::create()->findPk($stockItem->getId());
        $this->assertEquals(2690, $reloaded->getSellingPrice());
        $this->assertEquals(5.5, $reloaded->getTvaRate());
        $this->assertEquals(2550, $reloaded->getSellingPriceHt());
        $this->assertEquals(140, $reloaded->getSellingPriceTva());
    }

    /**
     * @throws PropelException
     */
    public function testAlreadyComputedItemIsUntouched(): void
    {
        // given
        $site = ModelFactory::createSite();
        $order = ModelFactory::createOrder();
        $stockItem = ModelFactory::createStockItem(
            order: $order,
            sellingPrice: 2690,
            sellingDate: new DateTime("2024-06-01"),
        );
        $stockItem->setSellingPriceHt(2550);
        $stockItem->setSellingPriceTva(140);
        $stockItem->setTvaRate(5.5);
        $stockItem->save();
        $commandTester = $this->buildCommandTester($site);

        // when
        $commandTester->execute(["--apply" => true]);

        // then: not targeted (tva already > 0), so left as-is
        $reloaded = StockQuery::create()->findPk($stockItem->getId());
        $this->assertEquals(140, $reloaded->getSellingPriceTva());
        $this->assertStringContainsString("Aucun exemplaire", $commandTester->getDisplay());
    }

    /**
     * @throws PropelException
     */
    public function testItemWithoutSellingDateIsSkipped(): void
    {
        // given
        $site = ModelFactory::createSite();
        $order = ModelFactory::createOrder();
        $stockItem = ModelFactory::createStockItem(
            order: $order,
            sellingPrice: 2690,
            sellingDate: null,
        );
        $commandTester = $this->buildCommandTester($site);

        // when
        $commandTester->execute(["--apply" => true]);

        // then
        $reloaded = StockQuery::create()->findPk($stockItem->getId());
        $this->assertEquals(0, $reloaded->getSellingPriceTva() ?? 0);
        $this->assertStringContainsString("Aucun exemplaire", $commandTester->getDisplay());
    }

    /**
     * @throws PropelException
     */
    public function testSiteWithoutTvaIsNoop(): void
    {
        // given
        $site = ModelFactory::createSite();
        $order = ModelFactory::createOrder();
        $stockItem = ModelFactory::createStockItem(
            order: $order,
            sellingPrice: 2690,
            sellingDate: new DateTime("2024-06-01"),
        );
        LegacyCodeHelper::setGlobalSite($site);
        $currentSite = new CurrentSite($site);
        $command = new RecalculateOrdersTaxCommand($currentSite);
        $commandTester = new CommandTester($command);

        // when
        $commandTester->execute(["--apply" => true]);

        // then
        $reloaded = StockQuery::create()->findPk($stockItem->getId());
        $this->assertEquals(0, $reloaded->getSellingPriceTva() ?? 0);
        $this->assertStringContainsString("n'utilise pas la TVA", $commandTester->getDisplay());
    }
}
