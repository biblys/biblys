<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace AppBundle\Controller;

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\OpenIDConnectProviderService;
use Biblys\Service\TokenService;
use DateTime;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Framework\Controller;
use Http\Discovery\Psr17Factory;
use JsonException;
use Model\AxysAccountQuery;
use Model\Session;
use Propel\Runtime\Exception\PropelException;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

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
    ):
    RedirectResponse
    {
        $psr17Factory = new Psr17Factory();
        $psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
        $psrRequest = $psrHttpFactory->createRequest($request);

        $tokenSet = $openIDConnectProviderService->getTokenSet($psrRequest);

        $returnUrl = "/";
        $stateToken = $request->query->get("state");
        $decodedState = JWT::decode($stateToken, new Key($config->get("axys.client_secret"), "HS256"));
        if (isset($decodedState->return_url) && $decodedState->return_url !== "") {
            $returnUrl = $decodedState->return_url;
        }

        $response = new RedirectResponse($returnUrl);

        $claims = $tokenSet->claims();
        $userId = $claims["sub"];
        $user = AxysAccountQuery::create()->findPk($userId);
        $sessionExpiresAt = new DateTime("@".$claims["exp"]);
        $session = Session::buildForUserAndCurrentSite($user, $currentSite, $sessionExpiresAt);
        $session->save();

        $sessionCookie = Cookie::create("user_uid")
            ->withValue($session->getToken())
            ->withExpires($claims["exp"]);
        $response->headers->setCookie($sessionCookie);

        return $response;
    }
}
