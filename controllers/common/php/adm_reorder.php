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


use Biblys\Service\BodyParamsService;
use Biblys\Service\QueryParamsService;
use Model\ArticleQuery;
use Model\BookCollectionQuery;
use Model\LinkQuery;
use Model\SupplierQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;

/**
 * @throws Exception
 */
return function (
    Request $request,
    UrlGenerator $urlGenerator,
    QueryParamsService $queryParams,
    BodyParamsService $bodyParams
): Response
{
    $request->attributes->set("page_title", "Réassort");

    $queryParams->parse(["supplier_id" => ["type" => "numeric", "default" => 0]]);
    $supplierId = $queryParams->getInteger("supplier_id");

    $suppliers = SupplierQuery::create()->find();
    $supplierOptions = array_map(function (\Model\Supplier $supplier) use ($supplierId) {
        return '<option value="/pages/adm_reorder?supplier_id=' . $supplier->getId() . '"
            '.($supplier->getId() === $supplierId ? "selected" : "").'
        >
            ' . htmlspecialchars($supplier->getName(), ENT_QUOTES, "UTF-8") . '
        </option>';
    }, $suppliers->getData());

    $suppliersUrl = $urlGenerator->generate("supplier_index");

    $content = '
        <h1><span class="fa fa-refresh"></span> Réassort</h1>

        <div class="admin">
            <p><a href="' . $suppliersUrl . '">Fournisseurs</a></p>
        </div>

        <form>
            <fieldset>
            <label for="supplier_id">Fournisseur :</label>
            <select id="supplier_id" class="goto">
            <option value=""> </option>
              ' . implode($supplierOptions) . '
            </select>
            </fieldset>
        </form>
    ';

    $supplier = SupplierQuery::create()->findPk($supplierId);
    if (!$supplier) {
        return new Response($content);
    }

    if ($request->getMethod() === "POST") {
        $bodyParams->parse([
            "changeStatus" => ["type" => "boolean", "default" => false],
            "article_id" => ["type" => "numeric", "default" => 0],
            "article" => ["type" => "array", "default" => []],
        ]);

        // Change article reorder status
        if ($bodyParams->getBoolean("changeStatus")) {
            $articleId = $bodyParams->get("article_id");
            $article = ArticleQuery::create()->findPk($articleId);

            if (!$article) {
                throw new Exception("Article inexistant");
            }

            $link = LinkQuery::create()
                ->filterByArticle($article)
                ->filterByDoNotReorder(1)
                ->findOne();

            if ($link) {
                $link->delete();
                $dnr = '0';
            } else {
                $link = new \Model\Link();
                $link->setArticle($article);
                $link->setDoNotReorder(1);
                $link->save();
                $dnr = '1';
            }

            return new JsonResponse([
                "id" => $article->getId(),
                "ean" => $article->getEan(),
                "title" => $article->getTitle(),
                "url" => $article->getUrl(),
                "publisher" => $article->getPublisher(),
                "dnr" => $dnr,
            ]);

        } // Generate reorder table
        else {
            $table = null;
            $articles = $bodyParams->getArray("article");
            foreach ($articles as $id => $qty) {
                if ($qty) {
                    $article = ArticleQuery::create()->findPk($id);
                    $table .= '<tr>
                        <td>' . htmlspecialchars($article->getPublisher()->getName(), ENT_QUOTES, 'UTF-8') . '</td>
                        <td>' . htmlspecialchars($article->getTitle(), ENT_QUOTES, 'UTF-8') . '</td>
                        <td>' . htmlspecialchars($article->getEan(), ENT_QUOTES, 'UTF-8') . '</td>
                        <td class="right">' . htmlspecialchars($qty, ENT_QUOTES, 'UTF-8') . '</td>
                    </tr> ';
                }
            }

            $content .= '
                <br>
                <table class="table">
                    <thead>
                        <th>Éditeur</th>
                        <th>Titre</th>
                        <th>EAN</th>
                        <th>Qté</th>
                    </thead>
                    <tbody>
                      ' . $table . '
                    </tbody>
                </table>
            ';

            return new Response($content);
        }
    }

    $publisherIds = $supplier->getPublisherIds();
    if (count($publisherIds) === 0) {
        throw new Exception("Aucun éditeur associé à ce fournisseur.");
    }

    $collections = BookCollectionQuery::create()->filterByPublisherId($publisherIds)->find();
    $collectionIds = array_map(function ($collection) {
        return $collection->getId();
    }, $collections->getData());
    $total = count($collectionIds);

    $content .= '
        <div id="collections" data-ids="' . implode(',', $collectionIds) . '"></div>
    
        <br>
        <p id="loadingText" class="center">Chargement : <span id="progressValue">0</span> / ' . $total . '</p>
        <div id="loadingBar" class="progress">
          <div class="progress-bar progress-bar-striped active" role="progressbar" style="width: 0;">
            0%
          </div>
        </div>
    
        <form method="post" action="/pages/adm_reorder?supplier_id=' . $supplierId . '">
            <fieldset>
                <table class="reorder table hidden" id="reorder">
                    <thead>
                        <tr class="pointer">
                            <th>Titre</th>
                            <th>Dernière vente</th>
                            <th>Stock</th>
                            <th>Ventes</th>
                            <th>Réa</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="doReorder">
                    </tbody>
                </table>
            </fieldset>
    
            <fieldset class="center">
                <br>
                <button class="btn btn-primary" type="submit">Générer le tableau de réassort</button>
            </fieldset>
        </form>

        <h2>&Agrave; ne pas réassortir</h2>

        <table class="reorder table hidden">
            <thead>
                <tr class="pointer">
                    <th>Titre</th>
                    <th>Dernière vente</th>
                    <th>Stock</th>
                    <th>Ventes</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="doNotReorder">
            </tbody>
        </table>
    ';

    return new Response($content);
};
