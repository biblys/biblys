<?php /** @noinspection JSVoidFunctionReturnValueUsed */
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


/** @noinspection BadExpressionStatementJS */

use Biblys\Isbn\Isbn;
use Biblys\Service\CurrentUser;
use Biblys\Service\Images\ImagesService;
use Model\ArticleQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @throws Exception
 * @noinspection SqlCheckUsingColumns
 */
return function (Request $request, CurrentUser $currentUser, ImagesService $imagesService): JsonResponse {
    $content = "";

    $lm = new LinkManager();

    $query = $request->query->get("query");
    $query = $request->request->get("query", $query);
    $listId = $request->request->get("list_id");
    $stockId = $request->request->get("stock_id");
    $del = $request->request->get("del");

    $currentUser->authAdmin();

    if ($query) {
        $req = null;
        $params = [];
        if (Isbn::isParsable($query)) {
            $req = "`article_ean` = :ean";
            $params["ean"] = Isbn::convertToEan13($query);
        } else {
            $queryWords = explode(" ", addslashes($query));
            $i = 0;
            foreach ($queryWords as $queryWord) {
                if (isset($req)) {
                    $req .= " AND ";
                }
                $req .= "(`article_keywords` LIKE :keyword$i)";
                $params["keyword$i"] = "%$queryWord%";
                $i++;
            }
        }
        $req = "
            SELECT `articles`.`article_id`, `article_title`, `article_authors`, `article_collection`, `article_number`,
                `stock_id`, `stock_selling_price`, `stock_condition`, `stock_stockage`
            FROM `articles`
            JOIN `stock` ON `articles`.`article_id` = `stock`.`article_id`
            WHERE " . $req . "
                AND `stock_selling_date` IS NULL
                AND `stock_return_date` IS NULL
                AND `stock_lost_date` IS NULL
        ";

        // EXEMPLAIRES
        $num = 0;
        $multiple = null;
        $articles = EntityManager::prepareAndExecute($req, $params);
        while ($a = $articles->fetch(PDO::FETCH_ASSOC)) {
            $article = ArticleQuery::create()->findPk($a["article_id"]);
            if ($imagesService->imageExistsFor($article)) {
                $a["article_cover"] = '<img src="' . $imagesService->getImageUrlFor($article, height: 60) . '" />';
            } else {
                $a["article_cover"] = null;
            }

            if (!empty($a["stock_cart_date"])) {
                $a["led"] = "square_gray";
                $a["led_title"] = "En panier";
            } else {
                $a["led"] = "square_green";
                $a["led_title"] = "En stock";
            }

            // If the item is already in a list, skip it
            $listed = $lm->get(["list_id" => $listId, "stock_id" => $a["stock_id"]]);
            if ($listed) {
                continue;
            }

            $content .= '
                <li class="choose clearL" onClick="addToList(\'' . $a["stock_id"] . '\',\'' . addslashes($a["article_title"]) . '\')">
                    ' . $a["article_cover"] . '
                    <img src="/common/img/' . $a["led"] . '.png" width="8" height="8" title="' . $a["led_title"] . '" alt="' . $a["led_title"] . '" /> 
                      <strong>' . $a["article_title"] . '</strong>
                      <br />
                    ' . $a["article_authors"] . '<br />
                    ' . $a["article_collection"] . ' ' . numero($a["article_number"]) . '<br />
                    ' . price($a["stock_selling_price"], 'EUR') . ' (' . $a["stock_condition"] . ')<br />
                    Ref. ' . $a["stock_id"] . '<br />
                    Empl. : ' . $a["stock_stockage"] . '<br />
                    <div class="clearL"></div>
                </li>
            ';
            $num++;
            if (isset($multiple)) {
                $multiple .= '-';
            }
            $multiple .= $a["stock_id"];
        }

        // ARTICLES

        if ($num > 1) {
            $content .= '<li class="choose clearL" onClick="addMultipleToList(\'' . $multiple . '\')"><strong>Ajouter les ' . $num . ' exemplaires à la liste</strong></li>';
        }
    } elseif ($stockId) {
        $sm = new StockManager();

        /** @var Stock $stock */
        $stock = $sm->getById($stockId);

        if ($stock) {
            // If the item is already in a list, skip it
            $listed = $lm->get(["list_id" => $listId, "stock_id" => $stockId]);
            if (!$listed) {
                $lim = new LinkManager();
                $link = $lim->create(
                    ["list_id" => $listId, "stock_id" => $stockId]
                );

                if (!empty($stock->get("return_date"))) {
                    $led = "square_orange";
                    $ledTitle = "Retourné";
                } elseif (!empty($stock->get("selling_date"))) {
                    $led = "square_blue";
                    $ledTitle = "Vendu";
                } elseif (!empty($stock->get("lost_date"))) {
                    $led = "square_purple";
                    $ledTitle = "Perdu";
                } elseif (!empty($stock->get("cart_date"))) {
                    $led = "square_gray";
                    $ledTitle = "En panier";
                } else {
                    $led = "square_green";
                    $ledTitle = "En stock";
                }

                $article = $stock->getArticle();

                $content .= '
                    <tr id="link_' . $link->get("id") . '">
                        <td>
                            <img src="/common/img/' . $led . '.png" width="8" height="8"
                                title="' . $ledTitle . '" alt="' . $ledTitle . '">
                        </td>
                        <td>
                            <a href="/' . $article->get("url") . '">
                                ' . $article->get("title") . '
                            </a>
                        </td>
                        <td>' . $article->get("collection")->get("name") . '</td>
                        <td
                            data-price=' . $article->get("selling_price") . '
                            data-stock_id=' . $stock->get("id") . '
                            data-article_title="' . $article->get("title") . '"
                            class="right pointer changePriceInList e">'
                        . price($stock->get("selling_price"), 'EUR') . '
                        </td>
                        <td class="text-right">1</td>
                        <td class="text-right">
                            ' . price($stock->get("selling_price"), 'EUR') . '
                        </td>
                        <td style="width: 50px;">
                            <a href="/pages/adm_stock?id=' . $stock->get("id") . 'exemplaire">
                            </a>
                            <img src="/common/icons/delete.svg" width=16 class="pointer"
                                alt="Retirer de la liste" title="Retirer de la liste"
                                onClick="delFromList(\'' . $link->get("id") . ')" />
                        </td>
                    </tr>
                ';

            } else {
                throw new BadRequestHttpException("Cet exemplaire est déjà dans la liste !");
            }
        }

    } elseif ($del) {
        $link = $lm->getById($del);
        if ($link) {
            $lm->delete($link);
        }
    }

    return new JsonResponse(["content" => $content]);
};
