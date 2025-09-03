<?php
/*
 * Copyright (C) 2025 Clément Latzarus
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


namespace ApiBundle\Controller;

use Biblys\Service\CurrentUser;
use Biblys\Service\QueryParamsService;
use Framework\Controller;
use League\Csv\Exception;
use League\Csv\InvalidArgument;
use League\Csv\Writer;
use Model\StockQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Response;

class StatsController extends Controller
{
    /**
     * @throws PropelException
     * @throws InvalidArgument
     * @throws Exception
     */
    public function salesByArticleAction(
        CurrentUser $currentUser,
        QueryParamsService $queryParams
    ): Response
    {
        $currentUser->authAdmin();

        $queryParams->parse([
            "year" => [
                "type" => "numeric",
                "default" => date("Y"),
                "mb_min_length" => 4,
                "mb_max_length" => 4,
            ]
        ]);

        $year = $queryParams->getInteger("year");

        $sales = StockQuery::create()
            ->filterBySellingDate("$year-01-01", Criteria::GREATER_EQUAL)
            ->filterBySellingDate("$year-12-31", Criteria::LESS_EQUAL)
            ->orderBySellingDate("desc")
            ->find();

        $salesByArticles = [];
        foreach ($sales as $sale) {
            $articleId = $sale->getArticleId();
            if (!isset($salesByArticles[$articleId])) {
                $article = $sale->getArticle();

                if ($article === null) {
                    continue;
                }

                $salesByArticles[$articleId] = [
                    "ean" => $article->getEan(),
                    "title" => $article->getTitle(),
                    "price" => $article->getPrice() / 100,
                    "quantity" => 0,
                    "discount" => 0,
                    "type" => "Vente directe",
                    "vat" => "5,50%",
                    "cession" => "non",
                ];
            }
            $salesByArticles[$articleId]["quantity"]++;
        }

        $csv = Writer::createFromString();
        $csv->setDelimiter(';');

        foreach ($salesByArticles as $saleByArticle) {
            $csv->insertOne($saleByArticle);
        }

        $fileName = "export-ventes-par-article-$year.csv";
        return new Response(
            content: $csv->toString(),
            headers: [
                "Content-Type" => "text/csv",
                "Content-Disposition" => "attachment; filename=\"$fileName\"",
            ]
        );
    }
}