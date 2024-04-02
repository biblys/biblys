<?php

namespace Biblys\Noosfere;

use Biblys\Contributor\Job;
use Biblys\Contributor\UnknownJobException;
use Biblys\Isbn\Isbn as Isbn;
use Exception;
use SimpleXMLElement;

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
}
