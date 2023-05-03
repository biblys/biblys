<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Biblys\Service\Cloud;

use Biblys\Service\Config;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class CloudService
{
    private Config $config;
    private bool $subscriptionFetched = false;
    private ?CloudSubscription $subscription = null;

    public function __construct(Config $config)
    {
        $this->config = $config;
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
     */
    public function getSubscription(): ?CloudSubscription
    {
        if (!$this->subscriptionFetched) {
            $subscription = $this->_query("/stripe/subscription");
            $this->subscriptionFetched = true;

            if (isset($subscription["id"])) {
                $this->subscription = new CloudSubscription(
                    status: $subscription["status"],
                );
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
            throw new Exception("Biblys Cloud n'est pas configuré.");
        }

        $client = new Client();
        $baseUrl = $this->config->get("cloud.base_url") ?: "https://biblys.cloud";
        $response = $client->request("GET", "$baseUrl/api$endpointUrl", [
            "auth" => [
                $this->config->get("cloud.public_key"),
                $this->config->get("cloud.secret_key"),
            ],
        ]);
        $body = $response->getBody();
        return json_decode($body, true);
    }
}
