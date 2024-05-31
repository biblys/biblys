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
    public function search($query, $mode = null): SimpleXMLElement|null
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
            return null;
        }

        // Check if XML is valid
        $xml = $this->parseResponse($response);
        if ($xml) {
            return $xml;
        }

        return null;
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

        $collectionSlug = $slugService->createForBookCollection(
            $collectionNoosfereName, $publisher->get("name")
        );
        $existingCollection = BookCollectionQuery::create()
            ->filterByNoosfereId($collectionNoosfereId)
            ->_or()
            ->filterByUrl($collectionSlug)
            ->findOne();

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

    public static function buildArticlesFromXml(SimpleXMLElement|null $xml): array
    {
        if ($xml === null) {
            return [];
        }

        $articles = [];

        foreach ($xml->Livre as $n) {
            $a = array();
            $a["article_title"] = (string)$n->Titre;
            $a["article_title"] = str_replace("  ", " – ", $a["article_title"]); // Bug tiret long noosfere
            $a["article_title_original"] = (string)$n->TitreOriginal;

            $a["article_publisher"] = (string)$n->Editeur;
            $a["noosfere_IdEditeur"] = (string)$n->Editeur["IdEditeur"];

            $a["article_collection"] = (string)$n->Collection;
            $a["noosfere_IdCollection"] = (string)$n->Collection["IdCollection"];
            if (empty($a["article_collection"])) {
                $a["article_collection"] = $a["article_publisher"];
                $a["noosfere_IdCollection"] = (string)$n->Editeur["IdEditeur"];
            }
            $a["article_number"] = str_replace(")", "", str_replace("(", "", $n->Reference));

            $a["article_item"] = (string)$n['IdItem'];
            $a["article_noosfere_id"] = (string)$n['IdLivre'];

            $a["article_cycle"] = (string)$n->Serie->TitreSerie;
            if (str_contains($a["article_cycle"], "(")) {
                $ex_cycle = explode("(", $a["article_cycle"]);
                $cycle = $ex_cycle[1] . "  " . $ex_cycle[0];
                $cycle = str_replace(")", "", $cycle);
                $cycle = ucfirst($cycle);
                $a["article_cycle"] = str_replace("'  ", "'", $cycle);
            }
            $a["article_tome"] = (string)$n->Serie->Volume;
            $a["noosfere_IdSerie"] = (string)$n->Serie["IdSerie"];

            $a["article_cover_import"] = (string)$n->Couverture['LienCouverture'];
            $a["article_cover_import"] = str_replace('https://www.noosfere.org/images/', 'https://images.noosfere.org/', $a['article_cover_import']);

            $a["article_pages"] = (string)$n->Page;

            $a["article_summary"] = null;
            if (!empty($n->Resume) && $n->Resume != "Pas de texte sur la quatriÃ?me de couverture") {
                $a["article_summary"] = '<p>' . str_replace('<br />', '</p><p>', nl2br($n->Resume)) . '</p>';
            }

            // Retrait des liens dans la quatrième
            $a["article_summary"] = $a["article_summary"] ? preg_replace('/<a href=\"(.*?)\">(.*?)<\/a>/', "\\2", $a["article_summary"]) : "";

            // Date parution
            $MoisDL = $n->Parution->MoisParution;
            $MoisDL = str_replace("1", "1er", $MoisDL);
            $MoisDL = str_replace("2", "2Ã?me", $MoisDL);
            $MoisDL = str_replace("3", "3Ã?me", $MoisDL);
            $MoisDL = str_replace("4", "4Ã?me", $MoisDL);
            $slugService = new SlugService();
            $MoisDL = $slugService->slugify($MoisDL);
            $MoisDLNum = '';
            if ($MoisDL == "janvier") {
                $MoisDLNum = '01';
            } elseif ($MoisDL == "fevrier") {
                $MoisDLNum = '02';
            } elseif ($MoisDL == "mars") {
                $MoisDLNum = '03';
            } elseif ($MoisDL == "avril") {
                $MoisDLNum = '04';
            } elseif ($MoisDL == "mai") {
                $MoisDLNum = '05';
            } elseif ($MoisDL == "juin") {
                $MoisDLNum = '06';
            } elseif ($MoisDL == "juillet") {
                $MoisDLNum = '07';
            } elseif ($MoisDL == "aout") {
                $MoisDLNum = '08';
            } elseif ($MoisDL == "septembre") {
                $MoisDLNum = '09';
            } elseif ($MoisDL == "octobre") {
                $MoisDLNum = '10';
            } elseif ($MoisDL == "novembre") {
                $MoisDLNum = '11';
            } elseif ($MoisDL == "decembre") {
                $MoisDLNum = '12';
            }
            $a["article_pubdate"] = trim($n->Parution->AnneeParution . '-' . $MoisDLNum . '-01');
            $a["article_copyright"] = (string)$n->ParutionOriginale;

            // Contributeurs
            $a['article_authors'] = null;
            $people = [];
            if (!empty($n->Intervenants->Intervenant)) {
                $p = 0;
                foreach ($n->Intervenants->Intervenant as $Intervenant) {
                    if ($Intervenant->Nom != "ANTHOLOGIE" and $Intervenant->Nom != "REVUE") {
                        if (!empty($Intervenant->Prenom)) {
                            $people[$p]["people_first_name"] = (string)$Intervenant->Prenom;
                        } else {
                            $people[$p]["people_first_name"] = null;
                        }
                        $people[$p]["people_last_name"] = (string)$Intervenant->Nom;
                        $people[$p]["people_name"] = trim($people[$p]["people_first_name"] . ' ' . $people[$p]["people_last_name"]);
                        $people[$p]["people_role"] = (string)$Intervenant['TypeIntervention'];
                        /** @noinspection DuplicatedCode */
                        $people[$p]["people_noosfere_id"] = (string)$Intervenant['NooId'];
                        if ($people[$p]["people_role"] == "Auteur") {
                            if (isset($a["article_authors"])) {
                                $a["article_authors"] .= ', ';
                            }
                            $a["article_authors"] .= $people[$p]["people_name"];
                        }
                        $p++;
                    }
                }
            }

            // Sommaire
            if (!empty($n->Sommaire)) {
                $a["article_contents"] = '<ul>';
                foreach ($n->Sommaire->EntreeSommaire as $entree) {
                    $a["article_contents"] .= '<li>' . $entree->TitreSommaire;
                    if (!empty($entree->Intervenants->Intervenant)) {
                        $entry_people = null;
                        $p = 0;
                        foreach ($entree->Intervenants->Intervenant as $Intervenant) {
                            if (!isset($entry_people)) {
                                $entry_people .= ' de ';
                            } else {
                                $entry_people .= ' &amp; ';
                            }
                            $entry_people .= trim($Intervenant->Prenom . " " . $Intervenant->Nom);
                            if ($Intervenant['TypeIntervention'] == "Auteur" and $Intervenant->Nom != "REVUE" and $Intervenant->Nom != "COLLECTIF" and $Intervenant->Nom != "ANONYME" and $Intervenant->Nom != "(non mentionnÃ©)") {
                                $people[$p]['people_first_name'] = null;
                                if (!empty($Intervenant->Prenom)) {
                                    $people[$p]["people_first_name"] = (string)$Intervenant->Prenom;
                                }
                                $people[$p]["people_last_name"] = (string)$Intervenant->Nom;
                                $people[$p]["people_name"] = trim($people[$p]["people_first_name"] . ' ' . $people[$p]["people_last_name"]);
                                $people[$p]["people_role"] = trim($Intervenant['TypeIntervention']);
                                /** @noinspection DuplicatedCode */
                                $people[$p]["people_noosfere_id"] = (string)$Intervenant['NooId'];
                                if ($people[$p]["people_role"] == "Auteur") {
                                    if (isset($a["article_authors"])) {
                                        $a["article_authors"] .= ', ';
                                    }
                                    $a["article_authors"] .= $people[$p]["people_name"];
                                }
                                $p++;
                            }
                        }
                        $a["article_contents"] .= $entry_people;
                    }
                    $a["article_contents"] .= '</li>';
                }
                $a["article_contents"] .= '</ul>';
            }

            if ($a["article_authors"] === null) {
                $people = [[
                    "people_first_name" => null,
                    "people_last_name" => "ANONYME",
                    "people_name" => "ANONYME",
                    "people_role" => "Auteur",
                    "people_noosfere_id" => null,
                ]];
                $a["article_authors"] = "ANONYME";
            }

            // ISBN
            $a['article_ean'] = null;
            if (Isbn::isParsable($n->ISBN)) {
                $a["article_ean"] = Isbn::convertToEan13($n->ISBN);
            }

            // Prix
            $categorie = (string)$n->Categorie;
            $price = null;
            if (preg_match("/(\d+,?\d*) FF$/", $categorie, $matches)) {
                $price = $matches[1];
                $price = str_replace(",", ".", $price);
                $price = round((float)$price / 6.55957 * 100);
            } elseif ($price !== null) {
                $price = str_replace(",", ".", $price);
                $price = (float)$price * 100;
            }
            $a["article_price"] = $price;

            $a["article_people"] = $people;

            $a["article_uid"] = $a["article_ean"];
            $a["article_import_source"] = "noosfere";
            $articles[] = $a;
        }

        return $articles;
    }
}
