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


namespace Biblys\Gleeph;

use Biblys\Exception\GleephAPIException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

class GleephAPI
{
    private string $apiKey;
    private string $environment = "prod";
    private ClientInterface $client;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;

        $this->setHttpClient(new Client());
    }

    /**
     * @param string $environment
     * @return GleephAPI
     */
    public function setEnvironment(string $environment): GleephAPI
    {
        $this->environment = $environment;
        return $this;
    }

    /**
     * @param ClientInterface $client
     * @return GleephAPI
     */
    public function setHttpClient(ClientInterface $client): GleephAPI
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @param string $ean
     * @param int $numberOfSuggestions
     * @return string[]
     * @throws ClientExceptionInterface
     * @throws GleephAPIException
     */
    public function getSimilarBooksByEan(string $ean, int $numberOfSuggestions = 3): array
    {
        $queryParams = http_build_query(["ean" => $ean, "nbrecos" => $numberOfSuggestions]);
        $requestUri = "{$this->_getBaseUrl()}/{$this->_getSimilarBooksByEanEndpointPath()}?$queryParams";
        $request = new Request("GET", $requestUri, ["x-api-key" => $this->apiKey]);
        $response = $this->client->sendRequest($request);

        if ($response->getStatusCode() !== 200) {
            $json = json_decode($response->getBody(), true);
            throw new GleephAPIException($json["message"]);
        }

        return $this->_extractEansFromResponse($response);
    }

    private function _getBaseUrl(): string
    {
        if ($this->environment === "test") {
            return "https://test.data.gleeph.pro/v2.0.0";
        }

        return "https://data.gleeph.pro/v2.0.0";
    }

    private function _getSimilarBooksByEanEndpointPath(): string
    {
        if ($this->environment === "test") {
            return "similarsbooks_byean_test";
        }

        return "similarsbooks_byean";
    }

    /**
     * @param ResponseInterface $response
     * @return array
     */
    public function _extractEansFromResponse(ResponseInterface $response): array
    {
        $json = json_decode($response->getBody(), true);
        return array_map(function ($book) {
            return $book["ean13"];
        }, $json["similarsbooks"]);
    }
}
