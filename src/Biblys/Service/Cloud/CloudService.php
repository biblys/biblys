<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
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


namespace Biblys\Service\Cloud;

use Biblys\Exception\InvalidConfigurationException;
use Biblys\Service\CacheService;
use Biblys\Service\Config;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use Psr\SimpleCache\InvalidArgumentException;

class CloudService
{
    private Config $config;
    private bool $subscriptionFetched = false;
    private ?CloudSubscription $subscription = null;
    private Client $httpClient;
    private CacheService $cacheService;

    /**
     * @throws InvalidConfigurationException
     */
    public function __construct(Config $config, Client $httpClient)
    {
        $this->config = $config;
        $this->httpClient = $httpClient;
        $this->cacheService = new CacheService($config);
    }

    /**
     * @throws GuzzleException
     */
    public function getPortalUrl(string $returnUrl)
    {
        $endpointUrl = "/stripe/portal-url?return_url=".urlencode($returnUrl);
        $json = $this->_query($endpointUrl);

        return $json["url"];
    }

    /**
     * @throws GuzzleException
     * @throws InvalidArgumentException
     */
    public function getSubscription(): ?CloudSubscription
    {
        if (!$this->subscriptionFetched) {
            try {
                $subscriptionResponse = $this->cacheService->get("cloud_subscription");
                if (!$subscriptionResponse) {
                    $subscriptionResponse = $this->_query("/stripe/subscription");
                    $this->cacheService->set("cloud_subscription", $subscriptionResponse);
                }

                $this->subscriptionFetched = true;
                if (isset($subscriptionResponse["id"])) {
                    $this->subscription = new CloudSubscription(
                        status: $subscriptionResponse["status"],
                    );
                }
            } catch (ServerException) {

            }
        }

        return $this->subscription;
    }

    public function isConfigured(): bool
    {
        $publicKey = $this->config->get("cloud.public_key");
        if ($publicKey) {
            return true;
        }

        return false;
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    private function _query(string $endpointUrl): array
    {
        if (!$this->isConfigured()) {
            throw new Exception("L'abonnement Biblys n'est pas configuré.");
        }

        $baseUrl = $this->_getBaseUrl();
        $requestUrl = "$baseUrl/api$endpointUrl";

        $response = $this->httpClient->request("GET", $requestUrl, [
            "auth" => [
                $this->config->get("cloud.public_key"),
                $this->config->get("cloud.secret_key"),
            ],
        ]);
        $body = $response->getBody();
        return json_decode($body, true);
    }

    private function _getBaseUrl(): string
    {
        return $this->config->get("cloud.base_url") ?: "https://cloud.biblys.fr";
    }
}
