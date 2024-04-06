<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Biblys\Service;

use Exception;
use Facile\OpenIDClient\Client\ClientBuilder;
use Facile\OpenIDClient\Client\ClientInterface;
use Facile\OpenIDClient\Client\Metadata\ClientMetadata;
use Facile\OpenIDClient\Issuer\IssuerBuilder;
use Facile\OpenIDClient\Service\AuthorizationService;
use Facile\OpenIDClient\Service\Builder\AuthorizationServiceBuilder;
use Facile\OpenIDClient\Token\TokenSetInterface;
use JsonException;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class OpenIDConnectProviderService
{
    private Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @throws ServiceUnavailableHttpException
     */
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

    /**
     * @throws Exception
     */
    public function getClient(): ClientInterface
    {
        $clientId = $this->config->get("axys.client_id");
        $clientSecret = $this->config->get("axys.client_secret");
        if (!$clientId || !$clientSecret) {
            throw new Exception("Invalid identity provider configuration");
        }

        try {
            $issuer = (new IssuerBuilder())
                ->build('https://axys.me/.well-known/openid-configuration');
        } catch(RuntimeException) {
            throw new ServiceUnavailableHttpException("Invalid issuer");
        }

        $clientMetadata = ClientMetadata::fromArray([
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
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