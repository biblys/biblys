<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Biblys\Service;

use Facile\OpenIDClient\Client\ClientBuilder;
use Facile\OpenIDClient\Client\ClientInterface;
use Facile\OpenIDClient\Client\Metadata\ClientMetadata;
use Facile\OpenIDClient\Issuer\IssuerBuilder;
use Facile\OpenIDClient\Service\AuthorizationService;
use Facile\OpenIDClient\Service\Builder\AuthorizationServiceBuilder;
use Facile\OpenIDClient\Token\TokenSetInterface;
use JsonException;
use Psr\Http\Message\ServerRequestInterface;

class OpenIDConnectProviderService
{
    private Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function getTokenSet(ServerRequestInterface $psrRequest): TokenSetInterface
    {
        $client = OpenIDConnectProviderService::getClient();
        $authorizationService = OpenIDConnectProviderService::getAuthorizationService();
        $callbackParams = $authorizationService->getCallbackParams($psrRequest, $client);
        return $authorizationService->callback($client, $callbackParams);
    }

    /**
     * @return AuthorizationService
     */
    public function getAuthorizationService(): AuthorizationService
    {
        return (new AuthorizationServiceBuilder())->build();
    }

    /**
     * @throws JsonException
     */
    public function getAuthorizationUri(TokenService $tokenService, string $returnUrl): string
    {
        $authorizationService = OpenIDConnectProviderService::getAuthorizationService();
        $client = OpenIDConnectProviderService::getClient();
        $stateToken = $tokenService->createOIDCStateToken(
            $returnUrl,
            $this->config->get("axys.client_secret")
        );
        return $authorizationService->getAuthorizationUri(
            $client,
            [
                "scope" => "openid email",
                "state" => $stateToken,
            ],
        );
    }

    public function getClient(): ClientInterface
    {
        $issuer = (new IssuerBuilder())
            ->build('https://axys.me/.well-known/openid-configuration');
        $clientMetadata = ClientMetadata::fromArray([
            'client_id' => $this->config->get("axys.client_id"),
            'client_secret' => $this->config->get("axys.client_secret"),
            'token_endpoint_auth_method' => 'client_secret_basic',
            'redirect_uris' => [
                $this->config->get("axys.redirect_uri"),
            ],
        ]);
        return (new ClientBuilder())
            ->setIssuer($issuer)
            ->setClientMetadata($clientMetadata)
            ->build();
    }
}