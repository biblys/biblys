<?php

namespace AppBundle\Controller;

use Biblys\Service\Axys;
use Biblys\Service\CurrentSite;
use Biblys\Service\TokenService;
use DateTime;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Framework\Controller;
use Framework\Exception\AuthException;
use Model\Session;
use Model\SessionQuery;
use Model\UserQuery;
use OpenIDConnectClient\Exception\InvalidTokenException;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class OpenIDConnectController extends Controller
{

    public function axys(Request $request, Axys $axys, TokenService $tokenService): RedirectResponse
    {
        $provider = $axys->getOpenIDConnectProvider();
        $clientSecret = $axys->getClientSecret();

        $stateToken = $tokenService->createOIDCStateToken(
            $request->query->get("return_url"),
            $clientSecret
        );
        $options = [
            "scope" => ["openid", "email"],
            "state" => $stateToken,
        ];
        $authorizationUrl = $provider->getAuthorizationUrl($options);
        return new RedirectResponse($authorizationUrl);
    }

    /**
     * @throws InvalidTokenException
     * @throws PropelException
     */
    public function callback(Request $request, Axys $axys, CurrentSite $currentSite): RedirectResponse
    {
        $provider = $axys->getOpenIDConnectProvider();
        $code = $request->query->get("code");
        $token = $provider->getAccessToken("authorization_code", ["code" => $code]);
        $idToken = $token->getIdToken();

        $response = new RedirectResponse("/");

        $response->headers->setCookie(Cookie::create("id_token")->withValue($idToken));

        $userId = $idToken->getClaim("sub");
        $user = UserQuery::create()->findPk($userId);
        $sessionExpiresAt = new DateTime("+1 day");
        $session = Session::buildForUserAndCurrentSite($user, $currentSite, $sessionExpiresAt);
        $session->save();
        $response->headers->setCookie(Cookie::create("user_uid")->withValue($session->getToken()));

        return $response;
    }

    /**
     * @throws AuthException
     * @throws PropelException
     */
    public function logout(Request $request): RedirectResponse
    {
        self::authUser($request);

        $session = SessionQuery::create()->findOneByToken($request->cookies->get("user_uid"));
        $session->delete();

        return new RedirectResponse("/");
    }
}
