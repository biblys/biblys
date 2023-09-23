<?php

namespace Biblys\Noosfere;

use Biblys\Isbn\Isbn as Isbn;
use Exception;
use SimpleXMLElement;

class Noosfere
{
    public function search($query, $mode = null)
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
        $response = $this->fetch_data($query, $mode);
        if (!$response) {
            return false;
        }

        // Check if XML is valid
        $xml = $this->parse_response($response);
        if ($xml) {
            return $xml;
        }

        return false;
    }

    /**
     * Fetch data from noosfere
     * @param  string $query
     * @return string Raw response from noosfere
     */
    private function fetch_data($query, $mode = "isbn")
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

    private function parse_response($response)
    {
        libxml_use_internal_errors(true);
        try {
            $xml = new SimpleXMLElement($response);
        } catch (Exception $e) {
            return false;
        }
        return $xml;
    }

    public static function getCorrectIdFor($id) {
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
}
