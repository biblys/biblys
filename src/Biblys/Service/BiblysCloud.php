<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Biblys\Service;

use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BiblysCloud
{
    /**
     * @var array
     */
    private $cloudConfig;

    /**
     * @var bool
     */
    private $subscriptionExists = false;

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
        $json = $this->query($endpointUrl);

        return $json["url"];
    }

    /**
     * @throws GuzzleException
     */
    public function getSubscription(): array
    {
        if ($this->subscription === null) {
            $subscription = $this->query("/stripe/subscription");

            if (isset($subscription["id"])) {
                $this->subscriptionExists = true;
                $this->subscription = [
                    "expires_at" => (new DateTime())->setTimestamp($subscription["current_period_end"]),
                    "days_until_due" => $subscription["days_until_due"],
                ];
            } else {
                $this->subscriptionExists = false;
                $this->subscription = [];
            }
        }

        return $this->subscription;
    }

    /**
     * @throws GuzzleException
     */
    public function subscriptionExists(): bool
    {
        $this->getSubscription();
        return $this->subscriptionExists;
    }

    /**
     * @throws GuzzleException
     */
    public function hasSubscriptionExpired(): bool
    {
        $subscription = $this->getSubscription();
        $daysUntilDue = $subscription["days_until_due"];

        if ($daysUntilDue === null) {
            return false;
        }

        if ($daysUntilDue > 0) {
            return false;
        }

        return true;
    }

    /**
     * @throws GuzzleException
     */
    public function isSubscriptionExpiringSoon(): bool
    {
        $subscription = $this->getSubscription();
        $daysUntilDue = $subscription["days_until_due"];

        if ($daysUntilDue === null) {
            return false;
        }

        if ($this->hasSubscriptionExpired()) {
            return false;
        }

        if ($daysUntilDue < 7) {
            return false;
        }

        return true;
    }

    /**
     * @throws GuzzleException
     */
    private function query(string $endpointUrl): array
    {
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