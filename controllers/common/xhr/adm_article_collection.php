<?php /** @noinspection PhpUnhandledExceptionInspection */

use Biblys\Exception\EntityAlreadyExistsException;
use Biblys\Legacy\LegacyCodeHelper;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

/**
 * @throws PropelException
 */
function admArticleCollectionController(Request $request): Response
{
    $getTerm = trim($request->query->get('term'));

    // Look for a collection
    if ($getTerm) {
        $params = [];

        $_REQ_SITE = null;
        $publisherId = null;

        // Si on est sur un site editeur
        if (!empty(LegacyCodeHelper::getGlobalSite()["publisher_id"])) {
            $_REQ_SITE = "AND `publisher_id` = :publisher_id";
            $publisherId = LegacyCodeHelper::getGlobalSite()->get('publisher_id');
            $params["publisher_id"] = $publisherId;
        } // Si on est en mode gestion editeur
        elseif (!LegacyCodeHelper::getGlobalVisitor()->isAdmin() && LegacyCodeHelper::getGlobalVisitor()->isPublisher()) {
            $_REQ_SITE = "AND `publisher_id` = :publisher_id";
            $publisherId = LegacyCodeHelper::getGlobalVisitor()->getCurrentRight()->get('publisher_id');
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
        $j_colls[] = 0;

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

            // If collection is already in array, skip (deduplication)
            if (in_array($c["collection_id"], $j_colls)) {
                continue;
            }

            $json[$i]["label"] = $c["collection_name"] . ' (' . $c["publisher_name"] . ')';
            $json[$i]["value"] = $c["collection_name"];
            $json[$i]["collection_name"] = $c["collection_name"];
            $json[$i]["collection_publisher"] = $c["publisher_name"];
            $json[$i]["collection_id"] = $c["collection_id"];
            $json[$i]["publisher_id"] = $c["publisher_id"];
            $json[$i]["pricegrid_id"] = $c["pricegrid_id"];
            $json[$i]["publisher_allowed_on_site"] = LegacyCodeHelper::getGlobalSite()->allowsPublisherWithId
            ($c["publisher_id"]) ? 1 : 0;

            $i++;
            $j_colls[] = $c["collection_id"];
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

$request = LegacyCodeHelper::getGlobalRequest();
$response = admArticleCollectionController($request);
$response->send();
