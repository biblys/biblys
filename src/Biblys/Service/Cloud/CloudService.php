<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Biblys\Service\Cloud;

use Biblys\Service\Config;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CloudService
{
    /**
     * @var array
     */
    private $cloudConfig;

    /**
     * @var bool
     */
    private $subscriptionFetched = false;

    /**
     * @var array
     */
    private $subscription = null;

    public function __construct(Config $config)
    {
        $this->cloudConfig = $config->get("cloud");
    }

    /**
     * @throws GuzzleException
     */
    public function getPortalUrl(UrlGenerator $urlGenerator)
    {
        $adminCloudUrl = $urlGenerator->generate(
            "main_admin_cloud",
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $endpointUrl = "/stripe/portal-url?return_url=".urlencode($adminCloudUrl);
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
                $this->subscription = new CloudSubscription($subscription["status"]);
            }
        }

        return $this->subscription;
    }

    public function isConfigured(): bool
    {
        if (isset($this->cloudConfig["public_key"])) {
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
        $response = $client->request("GET", "https://biblys.cloud/api$endpointUrl", [
            "auth" => [
                $this->cloudConfig["public_key"],
                $this->cloudConfig["secret_key"],
            ],
        ]);
        $body = $response->getBody();
        return json_decode($body, true);
    }
}
