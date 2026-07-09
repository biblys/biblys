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

use Biblys\Database\Connection;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\LoggerService;
use Exception;
use Model\Map\StockTableMap;
use Model\StockQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use StockManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RecalculateOrdersTaxCommand extends Command
{
    protected static $defaultName = "orders:recalculate-tax";

    public function __construct(
        private readonly CurrentSite $currentSite,
        string                       $name = null,
    )
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setDescription(
            "Recalculate VAT (HT / TVA / rate) for sold stock items where it was zeroed out"
        );

        $this->addOption(
            name: "apply",
            mode: InputOption::VALUE_NONE,
            description: "Persist the recalculated tax. Without this flag, the command runs as a dry-run."
        );
    }

    /**
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $apply = (bool) $input->getOption("apply");

        if (!$this->currentSite->getSite()->getTva()) {
            $output->writeln("Ce site n'utilise pas la TVA — rien à recalculer.");
            return Command::SUCCESS;
        }

        $this->_bootstrapLegacy();

        $stockItemIds = $this->_buildQuery()->select(["id"])->find()->getData();
        $count = count($stockItemIds);

        if ($count === 0) {
            $output->writeln("Aucun exemplaire à recalculer.");
            return Command::SUCCESS;
        }

        $output->writeln(
            $count . " exemplaire(s) à recalculer" . ($apply ? "" : " (dry-run, aucune écriture)")
        );

        $loggerService = new LoggerService();
        $stockManager = new StockManager();

        $progressBar = new ProgressBar($output, $count);
        $progressBar->setFormat("%current%/%max% [%bar%] %percent:3s%%");
        $progressBar->start();

        $rows = [];
        foreach ($stockItemIds as $stockItemId) {
            $stock = $stockManager->getById($stockItemId);
            if (!$stock) {
                $progressBar->advance();
                continue;
            }

            $rate = $stockManager->getTaxRate($stock);
            $stock = $stockManager->calculateTax($stock);

            if ($apply) {
                $stockManager->update($stock);
                $loggerService->log(
                    "orders-recalculate-tax",
                    "info",
                    "Stock item {$stock->get('id')}: rate {$rate}%, " .
                    "HT {$stock->get('selling_price_ht')}, TVA {$stock->get('selling_price_tva')}"
                );
            }

            $rows[] = [
                "id" => $stock->get("id"),
                "selling_date" => $stock->get("selling_date"),
                "selling_price" => $stock->get("selling_price"),
                "rate" => $rate,
                "ht" => $stock->get("selling_price_ht"),
                "tva" => $stock->get("selling_price_tva"),
            ];

            $progressBar->advance();
        }

        $progressBar->finish();
        $output->writeln("");

        $this->_renderTable($output, $rows);

        $summary = $apply
            ? "$count exemplaire(s) recalculé(s) et enregistré(s)."
            : "$count exemplaire(s) recalculé(s) (dry-run). Relancer avec --apply pour enregistrer.";
        $output->writeln($summary);
        $loggerService->log("orders-recalculate-tax", "info", $summary);

        return Command::SUCCESS;
    }

    /**
     * The tax computation reuses the legacy StockManager, which needs the legacy
     * entity autoloader and the legacy PDO connection ($_SQL). bin/console does not
     * load the legacy web bootstrap (inc/functions.php), so wire up just those two
     * pieces here, without its web-only side effects.
     *
     * @throws Exception
     */
    private function _bootstrapLegacy(): void
    {
        require_once __DIR__ . "/../../inc/autoload-entity.php";
        if (!isset($GLOBALS["_SQL"])) {
            Connection::init(Config::load());
        }
    }

    private function _buildQuery(): StockQuery
    {
        return StockQuery::create()
            ->filterByOrderId(null, Criteria::ISNOTNULL)
            ->filterBySellingPrice(0, Criteria::GREATER_THAN)
            ->filterBySellingDate(null, Criteria::ISNOTNULL)
            ->condition("tvaZero", StockTableMap::COL_STOCK_SELLING_PRICE_TVA . " = ?", 0)
            ->condition("tvaNull", StockTableMap::COL_STOCK_SELLING_PRICE_TVA . " IS NULL")
            ->where(["tvaZero", "tvaNull"], Criteria::LOGICAL_OR);
    }

    /**
     * @param array[] $rows
     */
    private function _renderTable(OutputInterface $output, array $rows): void
    {
        $output->writeln("Ref.\tDate\t\tTTC\tTaux\tHT\tTVA");
        foreach ($rows as $row) {
            $output->writeln(
                $row["id"] . "\t" .
                $row["selling_date"] . "\t" .
                $row["selling_price"] . "\t" .
                $row["rate"] . "%\t" .
                $row["ht"] . "\t" .
                $row["tva"]
            );
        }
    }
}
