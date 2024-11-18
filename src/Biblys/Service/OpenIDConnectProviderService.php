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
            $baseUri = $this->config->get("axys.base_uri") ?? "https://axys.me/";
            $wellKnownUrl = "$baseUri.well-known/openid-configuration";
            $issuer = (new IssuerBuilder())->build($wellKnownUrl);
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