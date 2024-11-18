<?php /** @noinspection SpellCheckingInspection */
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


use Biblys\Exception\EntityAlreadyExistsException;
use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Model\PublisherQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

/**
 * @throws PropelException
 * @throws Exception
 */
return function (Request $request, CurrentSite $currentSite, CurrentUser $currentUser): Response
{
    $getTerm = trim($request->query->get('term'));

    // Look for a collection
    if ($getTerm) {
        $params = [];

        $_REQ_SITE = null;

        // Si on est sur un site éditeur
        $publisherId = $currentSite->getSite()->getPublisherId();
        if ($publisherId) {
            $_REQ_SITE = "AND `publishers`.`publisher_id` = :publisher_id";
            $params["publisher_id"] = $publisherId;
        } // Si on est en mode gestion éditeur
        elseif (!$currentUser->isAdmin() && $currentUser->hasPublisherRight()) {
            $_REQ_SITE = "AND `publishers`.`publisher_id` = :publisher_id";
            $publisherId = $currentUser->getCurrentRight()->getPublisherId();
            $params["publisher_id"] = $publisherId;
        }
        $terms = explode(" ", $getTerm);
        $_REQ = null;
        $i = 0;
        $termsParams = [];
        foreach ($terms as $term) {
            $termId = 'term_' . $i;
            if (isset($_REQ)) {
                $_REQ .= ' AND ';
            }
            $_REQ .= "(`collection_name` LIKE :" . $termId . " 
            OR `publisher_name` LIKE :" . $termId . ")";
            $termsParams[$termId] = '%' . $term . '%';
            $i++;
        }

        $i = 0;
        $j_collections[] = 0;

        $qu1 = "SELECT `collection_id`, `collection_name`, `collections`.`publisher_id`, 
            `publisher_name`, `pricegrid_id` 
        FROM `collections`
        JOIN `publishers` ON `publishers`.`publisher_id` = `collections`.`publisher_id` 
        WHERE `collection_name` LIKE :query 
            " . $_REQ_SITE . " 
        ORDER BY `collection_name`";
        $collectionQuery = LegacyCodeHelper::getGlobalDatabaseConnection()->prepare($qu1);
        if ($publisherId) {
            $collectionQuery->bindParam('publisher_id', $params['publisher_id']);
        }
        $collectionQuery->bindValue('query', '%' . $getTerm . '%');
        $collectionQuery->execute();

        $qu2 = "SELECT `collection_id`, `collection_name`, `collections`.`publisher_id`, 
            `publisher_name`, `pricegrid_id` 
        FROM `collections` 
        JOIN `publishers` ON `publishers`.`publisher_id` = `collections`.`publisher_id` 
        WHERE " . $_REQ . " " . $_REQ_SITE . " 
        ORDER BY `collection_name`";
        $collectionsQuery = LegacyCodeHelper::getGlobalDatabaseConnection()->prepare($qu2);
        $collectionsQuery->execute(array_merge($params, $termsParams));

        while ($c = $collectionQuery->fetch() or $c = $collectionsQuery->fetch()) {

            // If the collection is already in array, skip (deduplication)
            if (in_array($c["collection_id"], $j_collections)) {
                continue;
            }

            $publisher = PublisherQuery::create()->findPk($c["publisher_id"]);

            $json[$i]["label"] = $c["collection_name"] . ' (' . $c["publisher_name"] . ')';
            $json[$i]["value"] = $c["collection_name"];
            $json[$i]["collection_name"] = $c["collection_name"];
            $json[$i]["collection_publisher"] = $c["publisher_name"];
            $json[$i]["collection_id"] = $c["collection_id"];
            $json[$i]["publisher_id"] = $c["publisher_id"];
            $json[$i]["pricegrid_id"] = $c["pricegrid_id"];
            $json[$i]["publisher_allowed_on_site"] = $currentSite->allowsPublisher($publisher) ? 1 : 0;

            $i++;
            $j_collections[] = $c["collection_id"];
        }
        $json[$i]["label"] = '=> Créer : ' . $getTerm;
        $json[$i]["value"] = $getTerm;
        $json[$i]["create"] = 1;

        return new JsonResponse($json);

    // Create a new collection
    } elseif ($_POST) {
        if (empty($_POST["collection_publisher_id"])) {
            throw new BadRequestHttpException("Éditeur non défini !");
        } else {
            $collectionName = $request->request->get('collection_name');
            $publisherId = $request->request->get('collection_publisher_id');

            $cm = new CollectionManager();
            try {
                $collection = $cm->create([
                    'collection_name' => $collectionName,
                    'publisher_id' => $publisherId,
                ]);
            } catch (EntityAlreadyExistsException $exception) {
                throw new ConflictHttpException($exception->getMessage());
            }
            $data = array_merge([
                'collection_id' => $collection->get('id'),
            ], $_POST);
            return new JsonResponse($data);
        }
    }

    throw new BadRequestHttpException();
};
