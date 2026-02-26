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

use Biblys\Noosfere\Noosfere;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\Slug\SlugService;
use Model\PeopleQuery;
use Model\PublisherQuery;
use Symfony\Component\HttpFoundation\JsonResponse;

return function(): JsonResponse
{
    $slugService = new SlugService();

    $noosfereId = $_GET["noosfere_id"];

    $noosfere = new Noosfere();
    $xml = $noosfere->search($noosfereId, 'noosfere_id');
    $importedArticles = Noosfere::buildArticlesFromXml($xml);
    $articleToCreate = $importedArticles[0];

    $publisher = Noosfere::getOrCreatePublisher($articleToCreate["noosfere_IdEditeur"], $articleToCreate["article_publisher"]);
    $articleToCreate["publisher_id"] = $publisher->getId();

    $collection = Noosfere::getOrCreateCollection(
        $articleToCreate["noosfere_IdCollection"],
        $articleToCreate["article_collection"],
        PublisherQuery::create()->findPk($articleToCreate["publisher_id"]),
    );
    $articleToCreate["collection_id"] = $collection->getId();
    $articleToCreate["article_collection"] = $collection->getName();
    $articleToCreate["article_publisher"] = $collection->getPublisherName();
    $articleToCreate["pricegrid_id"] = $collection->getPricegridId();

    // Reconnaissance de cycle
    if (!empty($articleToCreate["article_cycle"])) {
        $cym = new CycleManager();
        $cycle = $cym->get(array('cycle_noosfere_id' => $articleToCreate["noosfere_IdSerie"]));
        if (!$cycle) {
            $cycle = $cym->get(array('cycle_name' => $articleToCreate["article_cycle"]));
            if (!$cycle) {
                $articleToCreate["cycle_url"] = $slugService->slugify($articleToCreate["article_cycle"]);
                $cycle = $cym->create(array(
                    'cycle_name' => $articleToCreate['article_cycle'],
                    'cycle_url' => $slugService->slugify($articleToCreate["article_cycle"]),
                    'cycle_noosfere_id' => $articleToCreate["noosfere_IdSerie"]
                ));
            }
        }

        $articleToCreate['article_cycle'] = $cycle->get('name');
        $articleToCreate['cycle_id'] = $cycle->get('id');
    }

    // Reconnaissance des contributeurs
    unset($articleToCreate["article_authors"]);
    $authorsCount = 0;
    if (!empty($articleToCreate["article_people"])) {
        $articleToCreate["article_people"] = array_unique($articleToCreate["article_people"], SORT_REGULAR);
        foreach ($articleToCreate["article_people"] as $key => $c) {

            if (!empty($c["people_role"])) {
                $job = Noosfere::getJobFromNoosfereName($c["people_role"]);
                $articleToCreate["article_people"][$key]["job_id"] = $job->getId();
                if ($job->getId() === 1) {
                    $authorsCount++;
                }
            }

            if (empty($c['people_noosfere_id'])) {
                $c['people_noosfere_id'] = null;
            }

            $contributor = Noosfere::getOrCreateContributor(
                noosfereContributorId: $c["people_noosfere_id"],
                noosfereContributorFirstName: $c["people_first_name"],
                noosfereContributorLastName: $c["people_last_name"],
            );

            $articleToCreate["article_people"][$key]["people_id"] = $contributor->getId();
            $articleToCreate["article_people"][$key]["people_name"] = $contributor->getFullName();

            // S'il manque des infos, on n'ajoute pas le contributeur au livre
            if (empty($articleToCreate["article_people"][$key]["people_id"])
                || empty($articleToCreate["article_people"][$key]["people_name"])
                || empty($articleToCreate["article_people"][$key]["job_id"])
            ) {
                unset($articleToCreate["article_people"][$key]);
            }
        }
    }

    $config = Config::load();
    $currentSite = CurrentSite::buildFromConfig($config);
    $anonymousAuthorId = $currentSite->getOption("anonymous_author_id");
    if ($authorsCount === 0 && $anonymousAuthorId) {
        $anonymousAuthor = PeopleQuery::create()->findPk($anonymousAuthorId);
        $articleToCreate["article_people"][] = [
            "people_id" => $anonymousAuthor->getId(),
            "people_first_name" => $anonymousAuthor->getFirstName(),
            "people_last_name" => $anonymousAuthor->getLastName(),
            "people_name" => $anonymousAuthor->getFullName(),
            "people_role" => "Auteur",
            "people_noosfere_id" => null,
            "job_id" => 1,
        ];
    }

    // Reconnaissance des catégories
    if (isset($articleToCreate["pricegrid_id"]) && isset($articleToCreate["article_price"])) {
        /** @noinspection SqlResolve */
        $prices = EntityManager::prepareAndExecute(
            "SELECT `price_cat` FROM `prices`
            WHERE `price_amount` = :price_amount AND
                `pricegrid_id` = :pricegrid_id LIMIT 1",
            [
                'price_amount' => $articleToCreate['article_price'],
                'pricegrid_id' => $articleToCreate['pricegrid_id']
            ]
        );
        if ($p = $prices->fetch(PDO::FETCH_ASSOC)) {
            $articleToCreate["article_category"] = $p["price_cat"];
        }
    }

    return new JsonResponse($articleToCreate);
};
