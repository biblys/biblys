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


namespace Biblys\Noosfere;

use Biblys\Contributor\Job;
use Biblys\Contributor\UnknownJobException;
use Biblys\Exception\EntityAlreadyExistsException;
use Biblys\Isbn\Isbn as Isbn;
use Biblys\Isbn\IsbnParsingException;
use Biblys\Service\Slug\SlugService;
use Exception;
use Model\BookCollection;
use Model\BookCollectionQuery;
use Model\People;
use Model\PeopleQuery;
use Model\Publisher;
use Model\PublisherQuery;
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
        $query = stripslashes(mb_convert_encoding(trim($query), 'ISO-8859-1', 'UTF-8'));

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
            throw new Exception("Noosfere a répondu « $error » pour l‘url $url");
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
        $slugService = new SlugService();

        $publisher = null;

        $existingPublisher = PublisherQuery::create()
            ->filterByNoosfereId($publisherNoosfereId)
            ->_or()
            ->filterByUrl($slugService->slugify($publisherNoosfereName))
            ->findOne();
        if ($existingPublisher) {
            if (!$existingPublisher->getNoosfereId()) {
                $existingPublisher->setNoosfereId($publisherNoosfereId);
                $existingPublisher->save();
            }
            $publisher = $existingPublisher;
        }

        if (!$publisher) {
            $publisher = new Publisher();
            $publisher->setName($publisherNoosfereName);
            $publisher->setNoosfereId($publisherNoosfereId);
            $publisher->save();
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
    ): BookCollection
    {
        $slugService = new SlugService();

        $correctId = self::getCorrectIdFor($collectionNoosfereId);
        if ($correctId) {
            $collectionNoosfereId = $correctId;
        }

        $collectionSlug = $slugService->createForBookCollection(
            $collectionNoosfereName, $publisher->getName()
        );
        $existingCollection = BookCollectionQuery::create()
            ->filterByNoosfereId($collectionNoosfereId)
            ->_or()
            ->filterByUrl($collectionSlug)
            ->findOne();

        if ($existingCollection) {
            $collection = BookCollectionQuery::create()->findPk($existingCollection->getId());

            if (!$collection) {
                throw new ConflictHttpException(
                    "Impossible d'importer cet article car il appartient à un éditeur non-autorisé sur ce site."
                );
            }

            if (!$collection->getNoosfereId() && !empty($collectionNoosfereId)) {
                $collection->setNoosfereId($collectionNoosfereId);
                $collection->save();
            }
        }

        if (!$existingCollection) {
            try {
                $collection = new BookCollection();
                $collection->setPublisher($publisher);
                $collection->setName($collectionNoosfereName);
                $collection->setNoosfereId($collectionNoosfereId);
                $collection->save();
            } /** @noinspection PhpRedundantCatchClauseInspection */ catch (EntityAlreadyExistsException $exception) {
                throw new ConflictHttpException($exception->getMessage(), $exception);
            }
        }

        return $collection;
    }

    /**
     * @throws Exception
     */
    public static function getOrCreateContributor(
        int     $noosfereContributorId,
        ?string $noosfereContributorFirstName,
        ?string $noosfereContributorLastName,
    ): People
    {
        $slugService = new SlugService();

        $noosfereContributorName = trim("$noosfereContributorFirstName $noosfereContributorLastName");

        $contributorSlug = $slugService->slugify($noosfereContributorName);
        $existingContributor = PeopleQuery::create()
            ->filterByNoosfereId($noosfereContributorId)
            ->_or()
            ->filterByUrl($contributorSlug)
            ->findOne();

        if ($existingContributor) {
            if (!$existingContributor->getNoosfereId() && !empty($noosfereContributorId)) {
                $existingContributor->setNoosfereId($noosfereContributorId);
                $existingContributor->save();
            }

            return $existingContributor;
        }

        try {
            $newContributor = new People();
            $newContributor->setFirstName($noosfereContributorFirstName);
            $newContributor->setLastName($noosfereContributorLastName);
            $newContributor->setNoosfereId($noosfereContributorId);

            $newContributor->save();
        } /** @noinspection PhpRedundantCatchClauseInspection */ catch (EntityAlreadyExistsException $exception) {
            throw new ConflictHttpException($exception->getMessage(), $exception);
        }

        return $newContributor;
    }

    /**
     * @throws IsbnParsingException
     */
    public static function buildArticlesFromXml(SimpleXMLElement|null $xml): array
    {
        if ($xml === null) {
            return [];
        }

        $articles = [];

        foreach ($xml->Livre as $articleFromXml) {
            $builtArticle = array();
            $builtArticle["article_title"] = (string)$articleFromXml->Titre;
            $builtArticle["article_title"] = str_replace("  ", " – ", $builtArticle["article_title"]); // Bug tiret long noosfere
            $builtArticle["article_title_original"] = (string)$articleFromXml->TitreOriginal;

            $builtArticle["article_publisher"] = (string)$articleFromXml->Editeur;
            $builtArticle["noosfere_IdEditeur"] = (string)$articleFromXml->Editeur["IdEditeur"];

            $builtArticle["article_collection"] = (string)$articleFromXml->Collection;
            $builtArticle["noosfere_IdCollection"] = (string)$articleFromXml->Collection["IdCollection"];
            if (empty($builtArticle["article_collection"])) {
                $builtArticle["article_collection"] = $builtArticle["article_publisher"];
                $builtArticle["noosfere_IdCollection"] = (string)$articleFromXml->Editeur["IdEditeur"];
            }
            $builtArticle["article_number"] = str_replace(")", "", str_replace("(", "", $articleFromXml->Reference));

            $builtArticle["article_item"] = (string)$articleFromXml['IdItem'];
            $builtArticle["article_noosfere_id"] = (string)$articleFromXml['IdLivre'];

            $builtArticle["article_cycle"] = (string)$articleFromXml->Serie->TitreSerie;
            if (str_contains($builtArticle["article_cycle"], "(")) {
                $ex_cycle = explode("(", $builtArticle["article_cycle"]);
                $cycle = $ex_cycle[1] . "  " . $ex_cycle[0];
                $cycle = str_replace(")", "", $cycle);
                $cycle = ucfirst($cycle);
                $builtArticle["article_cycle"] = str_replace("'  ", "'", $cycle);
            }
            $builtArticle["article_tome"] = (string)$articleFromXml->Serie->Volume;
            $builtArticle["noosfere_IdSerie"] = (string)$articleFromXml->Serie["IdSerie"];

            $builtArticle["article_cover_import"] = (string)$articleFromXml->Couverture['LienCouverture'];
            $builtArticle["article_cover_import"] = str_replace('https://www.noosfere.org/images/', 'https://images.noosfere.org/', $builtArticle['article_cover_import']);

            $builtArticle["article_pages"] = (string)$articleFromXml->Page;

            $builtArticle["article_summary"] = null;
            if (!empty($articleFromXml->Resume) && $articleFromXml->Resume != "Pas de texte sur la quatriÃ?me de couverture") {
                $builtArticle["article_summary"] = '<p>' . str_replace('<br />', '</p><p>', nl2br($articleFromXml->Resume)) . '</p>';
            }

            // Retrait des liens dans la quatrième
            $builtArticle["article_summary"] = $builtArticle["article_summary"] ? preg_replace('/<a href=\"(.*?)\">(.*?)<\/a>/', "\\2", $builtArticle["article_summary"]) : "";

            // Date parution
            $MoisDL = $articleFromXml->Parution->MoisParution;
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
            $builtArticle["article_pubdate"] = trim($articleFromXml->Parution->AnneeParution . '-' . $MoisDLNum . '-01');
            $builtArticle["article_copyright"] = (string)$articleFromXml->ParutionOriginale;

            // Contributeurs
            $builtArticle['article_authors'] = null;
            $contributors = [];
            $contributorIndex = 0;
            if (!empty($articleFromXml->Intervenants->Intervenant)) {
                foreach ($articleFromXml->Intervenants->Intervenant as $Intervenant) {
                    if ((string) $Intervenant->Nom === "ANTHOLOGIE" || (string) $Intervenant->Nom === "REVUE") {
                        continue;
                    }

                    $newContributor = self::buildContributorFromIntervenant($Intervenant);
                    $builtArticle = self::addNewContributorToArticleAuthors($newContributor, $builtArticle);

                    $contributors[$contributorIndex] = $newContributor;
                    $contributorIndex++;
                }
            }

            // Sommaire
            if (!empty($articleFromXml->Sommaire)) {
                $builtArticle["article_contents"] = '<ul>';
                foreach ($articleFromXml->Sommaire->EntreeSommaire as $entree) {
                    $builtArticle["article_contents"] .= '<li>' . $entree->TitreSommaire;
                    if (!empty($entree->Intervenants->Intervenant)) {
                        $contributorsForTableOfContents = "";
                        foreach ($entree->Intervenants->Intervenant as $Intervenant) {
                            if ($contributorsForTableOfContents === "") {
                                $contributorsForTableOfContents .= ' de ';
                            } else {
                                $contributorsForTableOfContents .= ' &amp; ';
                            }
                            $contributorsForTableOfContents .= trim($Intervenant->Prenom . " " . $Intervenant->Nom);

                            if (
                                (string) $Intervenant['TypeIntervention'] !== "Auteur"
                                || $Intervenant->Nom === "REVUE"
                                || $Intervenant->Nom === "COLLECTIF"
                                || $Intervenant->Nom === "ANONYME"
                                || $Intervenant->Nom === "(non mentionnÃ©)"
                            ) {
                                continue;
                            }

                            $newContributorForTableOfContents = self::buildContributorFromIntervenant($Intervenant);
                            $builtArticle = self::addNewContributorToArticleAuthors($newContributorForTableOfContents, $builtArticle);

                            $contributors[$contributorIndex] = $newContributorForTableOfContents;
                            $contributorIndex++;
                        }
                        $builtArticle["article_contents"] .= $contributorsForTableOfContents;
                    }
                    $builtArticle["article_contents"] .= '</li>';
                }
                $builtArticle["article_contents"] .= '</ul>';
            }

            // ISBN
            $builtArticle['article_ean'] = null;
            if (Isbn::isParsable($articleFromXml->ISBN)) {
                $builtArticle["article_ean"] = Isbn::convertToEan13($articleFromXml->ISBN);
            }

            // Prix
            $category = (string) $articleFromXml->Categorie;
            $price = self::parsePriceFromCategory($category);
            $builtArticle["article_price"] = $price;

            $builtArticle["article_people"] = $contributors;

            $builtArticle["article_uid"] = $builtArticle["article_ean"];
            $builtArticle["article_import_source"] = "noosfere";
            $articles[] = $builtArticle;
        }

        return $articles;
    }

    public static function parsePriceFromCategory(string $category): int
    {
        if (str_ends_with($category, " FF")) {
            return 0;
        }

        if (preg_match("/^(\d+,?\d*) /", $category, $matches)) {
            $priceString = $matches[1];
            $priceFloat = (float) str_replace(",", ".", $priceString);
            return round($priceFloat * 100);
        }

        return "0";
    }

    /**
     * @param SimpleXMLElement|null $Intervenant
     * @return array
     */
    private static function buildContributorFromIntervenant(?SimpleXMLElement $Intervenant): array
    {
        $newContributor = [];
        $newContributor["people_first_name"] = null;
        if (!empty($Intervenant->Prenom)) {
            $newContributor["people_first_name"] = (string)$Intervenant->Prenom;
        }
        $newContributor["people_last_name"] = (string)$Intervenant->Nom;
        $newContributor["people_name"] = trim($newContributor["people_first_name"] . ' ' . $newContributor["people_last_name"]);
        $newContributor["people_role"] = trim($Intervenant['TypeIntervention']);
        $newContributor["people_noosfere_id"] = (string)$Intervenant['NooId'];
        return $newContributor;
    }

    /**
     * @param array $newContributor
     * @param array $builtArticle
     * @return array
     */
    private static function addNewContributorToArticleAuthors(array $newContributor, array $builtArticle): array
    {
        if ($newContributor["people_role"] == "Auteur") {
            if (isset($builtArticle["article_authors"])) {
                $builtArticle["article_authors"] .= ', ';
            }
            $builtArticle["article_authors"] .= $newContributor["people_name"];
        }

        return $builtArticle;
    }
}
