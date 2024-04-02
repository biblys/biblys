<?php

namespace Biblys\Noosfere;

use Biblys\Contributor\Job;
use Biblys\Contributor\UnknownJobException;
use Biblys\Exception\EntityAlreadyExistsException;
use Biblys\Isbn\Isbn as Isbn;
use Biblys\Service\Slug\SlugService;
use Collection;
use CollectionManager;
use Exception;
use Model\BookCollectionQuery;
use Model\PublisherQuery;
use Publisher;
use PublisherManager;
use SimpleXMLElement;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class Noosfere
{
    /**
     * @throws Exception
     */
    public function search($query, $mode = null): SimpleXMLElement|false
    {

        // Auto-select mode if not defined
        if (!$mode) {
            if (Isbn::isParsable($query)) {
                $mode = "isbn";
            } elseif (is_numeric($query)) {
                $mode = "noosfere_id";
            } else {
                $mode = "title";
            }
        }

        // Fetch raw response from nooSFere
        $response = $this->fetchData($query, $mode);
        if (!$response) {
            return false;
        }

        // Check if XML is valid
        $xml = $this->parseResponse($response);
        if ($xml) {
            return $xml;
        }

        return false;
    }

    /**
     * @throws Exception
     */
    private function fetchData($query, $mode = "isbn"): string
    {
        // Clean query
        $query = stripslashes(utf8_decode(trim($query)));

        // Query param name
        $query_key = "isbn";
        if ($mode == "title") {
            $query_key = "Id";
        } elseif ($mode == "noosfere_id") {
            $query_key = "numlivre";
        }

        // Build query url
        $url = "https://www.noosfere.org/biblio/xml_livres.asp?";
        $url .= http_build_query([
            'resume' => 1,
            $query_key => $query
        ]);

        // GET http request
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        ]);
        $response = curl_exec($curl);

        $info = curl_getinfo($curl);
        if ($info['http_code'] != '200') {
            $error = strip_tags($response);
            throw new Exception("Noosfere a répondu « $error » pour l'url $url");
        }

        curl_close($curl);

        return $response;
    }

    private function parseResponse($response): SimpleXMLElement|false
    {
        libxml_use_internal_errors(true);
        try {
            $xml = new SimpleXMLElement($response);
        } catch (Exception) {
            return false;
        }
        return $xml;
    }

    public static function getCorrectIdFor($id): ?string
    {
        $corrections = [
            '-10246456' => '1975550694',
            '-10246446' => '1975550694',
            '-10246447' => '1975550694',
            '83' => '1975550694',
            '2752' => '1672',
        ];

        if (isset($corrections[$id])) {
            return $corrections[$id];
        }

        return null;
    }

    /**
     * @throws UnknownJobException
     */
    public static function getJobFromNoosfereName(string $name): Job
    {
        if ($name === "Illustrateur") {
            return Job::getByName("Illustrateur (couverture)");
        }

        if ($name === "Illustrateur intérieur") {
            return Job::getByName("Illustrateur (intérieur)");
        }

        if (in_array($name, ["Adaptateur", "Ouvrages sur l'auteur", "Présenté par", "Prête-plume", "Rédacteur en chef"])) {
            return Job::getByName("Autre auteur");
        }

        if ($name === "Révision de traduction") {
            return Job::getByName("Traducteur");
        }

        return Job::getByName($name);
    }

    /**
     * @throws Exception
     */
    public static function getOrCreatePublisher(
        int    $publisherNoosfereId,
        string $publisherNoosfereName,
    ): Publisher
    {
        $pm = new PublisherManager();
        $slugService = new SlugService();

        $publisher = null;

        $existingPublisher = PublisherQuery::create()
            ->filterByNoosfereId($publisherNoosfereId)
            ->_or()
            ->filterByUrl($slugService->slugify($publisherNoosfereName))
            ->findOne();

        if ($existingPublisher) {
            /** @var Publisher $publisher */
            $publisher = $pm->getById($existingPublisher->getId());
            if (!empty($publisherNoosfereId)) {
                $publisher->set('publisher_noosfere_id', $publisherNoosfereId);
                $pm->update($publisher);
            }
        }

        if (!$publisher) {
            /** @var Publisher $publisher */
            $publisher = $pm->create([
                "publisher_name" => $publisherNoosfereName,
                "publisher_noosfere_id" => $publisherNoosfereId
            ]);
        }

        return $publisher;
    }

    /**
     * @throws Exception
     */
    public static function getOrCreateCollection(
        int       $collectionNoosfereId,
        string    $collectionNoosfereName,
        Publisher $publisher
    ): Collection
    {
        $slugService = new SlugService();
        $cm = new CollectionManager();

        $correctId = self::getCorrectIdFor($collectionNoosfereId);
        if ($correctId) {
            $collectionNoosfereId = $correctId;
        }

        $existingCollectionByNoosfereId = BookCollectionQuery::create()->findOneByNoosfereId($collectionNoosfereId);
        $existingCollectionByNameAndPublisher = BookCollectionQuery::create()
            ->filterByUrl($slugService->slugify($collectionNoosfereName))
            ->filterByPublisherId($publisher->get('id'))
            ->findOne();
        $existingCollection = $existingCollectionByNoosfereId ?? $existingCollectionByNameAndPublisher;

        if ($existingCollection) {
            /** @var Collection $collection */
            $collection = $cm->getById($existingCollection->getId());

            if (!$collection->has('collection_noosfere_id') && !empty($collectionNoosfereId)) {
                $collection->set('collection_noosfere_id', $collectionNoosfereId);
                $cm->update($collection);
            }
        }

        if (!$existingCollection) {
            $collectionParams = [
                "publisher_id" => $publisher->get('id'),
                "collection_name" => $collectionNoosfereName,
                "collection_noosfere_id" => $collectionNoosfereId
            ];

            try {
                /** @var Collection $collection */
                $collection = $cm->create($collectionParams);
            } catch (EntityAlreadyExistsException $exception) {
                throw new ConflictHttpException($exception->getMessage(), $exception);
            }
        }

        return $collection;
    }
}
