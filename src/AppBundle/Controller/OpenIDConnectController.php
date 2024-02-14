<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace AppBundle\Controller;

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\OpenIDConnectProviderService;
use Biblys\Service\TemplateService;
use Biblys\Service\TokenService;
use DateTime;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Framework\Controller;
use Http\Discovery\Psr17Factory;
use JsonException;
use Model\AxysAccount;
use Model\AxysAccountQuery;
use Model\Session;
use Propel\Runtime\Exception\PropelException;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OpenIDConnectController extends Controller
{
    /**
     * @throws JsonException
     */
    public function axys(
        Request $request,
        TokenService $tokenService,
        OpenIDConnectProviderService $openIDConnectProviderService,
    ): RedirectResponse
    {
        $returnUrl = $request->query->get("return_url", "");
        $authorizationUri = $openIDConnectProviderService->getAuthorizationUri($tokenService, $returnUrl);
        return new RedirectResponse($authorizationUri);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function callback(
        Request $request,
        CurrentSite $currentSite,
        Config $config,
        OpenIDConnectProviderService $openIDConnectProviderService,
        TemplateService $templateService,
    ): Response|RedirectResponse
    {
        $error = $request->query->get("error");
        if ($error === "access_denied") {
            return $templateService->renderResponse("AppBundle:OpenIDConnect:callback.html.twig", [
                "siteTitle" => $currentSite->getTitle(),
            ]);
        }

        [$externalId, $sessionExpiresAt] = OpenIDConnectController::_getClaimsFromOidcTokens($request, $openIDConnectProviderService);
        $returnUrl = OpenIDConnectController::_getReturnUrlFromState($request, $config);
        $axysAccount = AxysAccountQuery::create()->findPk($externalId);

        $sessionCookie = OpenIDConnectController::_createSession($axysAccount, $currentSite, $sessionExpiresAt);

        $response = new RedirectResponse($returnUrl);
        $response->headers->setCookie($sessionCookie);
        return $response;
    }

    /**
     * @throws Exception
     */
    private static function _getClaimsFromOidcTokens(Request $request, OpenIDConnectProviderService $openIDConnectProviderService): array
    {
        $psr17Factory = new Psr17Factory();
        $psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
        $psrRequest = $psrHttpFactory->createRequest($request);

        $oidcTokens = $openIDConnectProviderService->getTokenSet($psrRequest);

        $claims = $oidcTokens->claims();
        $externalId = $claims["sub"];
        $sessionExpiresAt = new DateTime("@" . $claims["exp"]);

        return [$externalId, $sessionExpiresAt];
    }

    private static function _getReturnUrlFromState(Request $request, Config $config): string
    {
        $returnUrl = "/";
        $stateToken = $request->query->get("state");
        $decodedState = JWT::decode($stateToken, new Key($config->get("axys.client_secret"), "HS256"));
        if (isset($decodedState->return_url) && $decodedState->return_url !== "") {
            $returnUrl = $decodedState->return_url;
        }

        return $returnUrl;
    }

    /**
     * @param mixed $axysAccount
     * @param CurrentSite $currentSite
     * @param mixed $sessionExpiresAt
     * @return Cookie
     * @throws PropelException
     */
    private static function _createSession(
        AxysAccount $axysAccount,
        CurrentSite $currentSite,
        mixed       $sessionExpiresAt
    ): Cookie
    {
        $session = Session::buildForUserAndCurrentSite($axysAccount, $currentSite, $sessionExpiresAt);
        $session->save();
        return Cookie::create("user_uid")
            ->withValue($session->getToken())
            ->withExpires($sessionExpiresAt);
    }

}
