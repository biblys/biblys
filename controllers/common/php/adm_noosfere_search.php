<?php /** @noinspection PhpUnhandledExceptionInspection */

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

use Biblys\Isbn\Isbn;
use Biblys\Isbn\IsbnParsingException;
use Biblys\Noosfere\Noosfere;
use Biblys\Service\QueryParamsService;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @throws IsbnParsingException
 */
return function(QueryParamsService $queryParams): JsonResponse
{
    $queryParams->parse(["q" => ["type" => "string", "mb_min_length" => 3]]);

    $r = null;
    $noosfere = new Noosfere();

    $searchResults = $noosfere->search($_GET["q"]);
    $articlesFromNoosfere = Noosfere::buildArticlesFromXml($searchResults);

    // If query param is an EAN, try as an ISBN-10
    $query = $_GET["q"];
    if (count($articlesFromNoosfere) === 0 && Isbn::isParsable($query)) {
        $isbn10 = Isbn::convertToIsbn10($query);
        $xml = $noosfere->search($isbn10);
        $articleNoosfereIsbn10 = Noosfere::buildArticlesFromXml($xml);
        $articlesFromNoosfere = (array_merge($articlesFromNoosfere, $articleNoosfereIsbn10));
    }

    $results = 0;
    $additional_results = null;
    $r .= '<div id="results" class="hidden">';
    if ($articlesFromNoosfere) {
        foreach ($articlesFromNoosfere as $a) {

            $isbn = null;
            if ($a["article_ean"]) {
                $isbn = '<br />ISBN : ' . Isbn::convertToIsbn13($a["article_ean"]);
            }

            $authors = is_string($a["article_authors"])
                ? "de " . truncate($a["article_authors"], 65, '...', true, true)
                : "(Aucun·e auteur·ice)";
            $result = '
                    <div data-ean="' . $a["article_ean"] . '" data-asin="' . (isset($a["article_asin"]) ? $a['article_asin'] : null) . '" data-noosfere_id="' . $a["article_noosfere_id"] . '" class="article-thumb article-import pointer">
                        <img src="' . $a["article_cover_import"] . '" height="85" class="article-thumb-cover" alt="Image de couverture" />
                        <div class="article-thumb-data">
                            <h3>' . $a["article_title"] . '</h3>
                            <p>
                                ' . $authors . '<br />
                                coll. ' . $a["article_collection"] . ' ' . numero($a["article_number"]) . ' (' . $a["article_publisher"] . ')<br />
                                ' . $isbn . '
                            </p>
                        </div>
                    </div>
                ';
            if ($results < 3) {
                $r .= $result;
            } else {
                $additional_results .= $result;
            }
            $results++;
        }
    }
    $r .= '</div>';
    if (empty($results)) {
        $r .= '<p class="center">Aucun résultat dans les bases externes.</p>';
    }
    if (isset($additional_results)) {
        $r .= '<div id="additionalResults" class="hidden">' . $additional_results . '</div><h3 id="showAllResults" class="toggleThis center pointer">Afficher plus de résultats...</h3>';
    }

    return new JsonResponse(["result" => $r]);
};
