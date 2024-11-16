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
use Biblys\Noosfere\Noosfere;
use Biblys\Service\Slug\SlugService;
use Symfony\Component\HttpFoundation\JsonResponse;

$pm = new PublisherManager();
$cm = new CollectionManager();
$pom = new PeopleManager();

$slugService = new SlugService();

// IMPORT NOOSFERE
function noosfere($query, $mode = null): array
{
    $noosfere = new Noosfere();
    $xml = $noosfere->search($query, $mode);
    return Noosfere::buildArticlesFromXml($xml);
}

if ($_GET["mode"] == "search") { // Mode recherche
    $r = null;
    $articles_noosfere = noosfere($_GET["q"]); // ne fonctionne plus le 16 avril 2015

    // If query param is an EAN, try as an ISBN-10
    $query = $_GET["q"];
    if (count($articles_noosfere) === 0 && Isbn::isParsable($query)) {
        $isbn10 = Isbn::convertToIsbn10($query);
        $articleNoosfereIsbn10 = noosfere($isbn10);
        $articles_noosfere = (array_merge($articles_noosfere, $articleNoosfereIsbn10));
    }

    $results = 0;
    $additional_results = null;
    $r .= '<div id="results" class="hidden">';
    if ($articles_noosfere) {
        foreach ($articles_noosfere as $a) {

            $isbn = null;
            if ($a["article_ean"]) {
                $isbn = '<br />ISBN : ' . Isbn::convertToIsbn13($a["article_ean"]);
            }

            $result = '
                <div data-ean="'.$a["article_ean"].'" data-asin="'.(isset($a["article_asin"]) ? $a['article_asin'] : null).'" data-noosfere_id="'.$a["article_noosfere_id"].'" class="article-thumb article-import pointer">
                    <img src="'.$a["article_cover_import"].'" height="85" class="article-thumb-cover" alt="Image de couverture" />
                    <div class="article-thumb-data">
                        <h3>'.$a["article_title"]. '</h3>
                        <p>
                            de '.truncate($a["article_authors"], 65, '...', true, true).'<br />
                            coll. '.$a["article_collection"].' '.numero($a["article_number"]).' ('.$a["article_publisher"]. ')<br />
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
        $r .= '<div id="additionalResults" class="hidden">'.$additional_results.'</div><h3 id="showAllResults" class="toggleThis center pointer">Afficher plus de résultats...</h3>';
    }

    $response = new JsonResponse(['result' => $r]);
    $response->send();
} elseif ($_GET["mode"] == "import") { // Mode import
    $x = [];
    if (!empty($_GET["noosfere_id"])) { // Importation de fiche noosfere
        $n = noosfere($_GET["noosfere_id"], 'noosfere_id');
        $n = $n[0];
        if (!empty($n["article_ean"])) { // Infos complémentaires d'Amazon si EAN
            if (!empty($a)) {
                foreach ($a as $key => $val) { // Champ à récupérer d'Amazon uniquement si vide chez noosfere
                    if (empty($n[$key])) {
                        $n[$key] = $val;
                    }
                }
            }
            // Champ à récupérer en priorité chez Amazon
            if (isset($a["article_pubdate"])) {
                $n["article_pubdate"] = $a["article_pubdate"];
            }
            if (isset($a["article_cover_import"])) {
                $n["article_cover_import"] = $a["article_cover_import"];
            }
            if (isset($a["article_price"])) {
                $n["article_price"] = $a["article_price"];
            }
        }
        $x = $n;
    }

    $publisher = Noosfere::getOrCreatePublisher($x["noosfere_IdEditeur"], $x["article_publisher"]);
    $x["publisher_id"] = $publisher->get("id");
    $x["article_publisher"] = $publisher->get("name");

    $collection = Noosfere::getOrCreateCollection(
        $x["noosfere_IdCollection"],
        $x["article_collection"],
        $publisher
    );
    $x["collection_id"] = $collection->get('id');
    $x["article_collection"] = $collection->get("name");
    $x["article_publisher"] = $collection->get("publisher");
    $x["pricegrid_id"] = $collection->get("pricegrid_id");

    // Reconnaissance de cycle
    if (!empty($x["article_cycle"])) {
        $cym = new CycleManager();
        $cycle = $cym->get(array('cycle_noosfere_id' => $x["noosfere_IdSerie"]));
        if (!$cycle) {
            $cycle = $cym->get(array('cycle_name' => $x["article_cycle"]));
            if (!$cycle) {
                $x["cycle_url"] = $slugService->slugify($x["article_cycle"]);
                $cycle = $cym->create(array(
                    'cycle_name' => $x['article_cycle'],
                    'cycle_url' => $slugService->slugify($x["article_cycle"]),
                    'cycle_noosfere_id' => $x["noosfere_IdSerie"]
                ));
            }
        }

        $x['article_cycle'] = $cycle->get('name');
        $x['cycle_id'] = $cycle->get('id');
    }

    // Reconnaissance des contributeurs
    unset($x["article_authors"]);
    if (!empty($x["article_people"])) {
        $x["article_people"] = array_unique($x["article_people"], SORT_REGULAR);
        foreach ($x["article_people"] as $k => $c) {

            if (!empty($c["people_role"])) {
                $job = Noosfere::getJobFromNoosfereName($c["people_role"]);
                $x["article_people"][$k]["job_id"] = $job->getId();
            }

            if (empty($c['people_noosfere_id'])) {
                $c['people_noosfere_id'] = null;
            }

            $contributor = Noosfere::getOrCreateContributor(
                noosfereContributorId: $c["people_noosfere_id"],
                noosfereContributorFirstName: $c["people_first_name"],
                noosfereContributorLastName: $c["people_last_name"],
            );

            $x["article_people"][$k]["people_id"] = $contributor->get('id');
            $x["article_people"][$k]["people_name"] = $contributor->getName();

            // S'il manque des infos, on n'ajoute pas le contributeur au livre
            if (empty($x["article_people"][$k]["people_id"])
                || empty($x["article_people"][$k]["people_name"])
                || empty($x["article_people"][$k]["job_id"])
            ) {
                unset($x["article_people"][$k]);
            }
        }
    }

    // Reconnaissance des catégories
    if (isset($x["pricegrid_id"]) && isset($x["article_price"])) {
        $prices = EntityManager::prepareAndExecute(
            "SELECT `price_cat` FROM `prices`
            WHERE `price_amount` = :price_amount AND
                `pricegrid_id` = :pricegrid_id LIMIT 1",
            [
                'price_amount' => $x['article_price'],
                'pricegrid_id' => $x['pricegrid_id']
            ]
        );
        if ($p = $prices->fetch(PDO::FETCH_ASSOC)) {
            $x["article_category"] = $p["price_cat"];
        }
    }

    echo str_replace("\u0092", "\u2019", json_encode($x));
}
