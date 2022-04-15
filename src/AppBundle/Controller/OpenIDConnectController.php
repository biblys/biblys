<?php

namespace AppBundle\Controller;

use Biblys\Service\Axys;
use Framework\Controller;
use Model\Session;
use Model\UserQuery;
use OpenIDConnectClient\Exception\InvalidTokenException;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class OpenIDConnectController extends Controller
{

    public function axys(Axys $axys): RedirectResponse
    {
        $provider = $axys->getOpenIDConnectProvider();

        $options = ["scope" => ["openid", "email"]];
        return new RedirectResponse($provider->getAuthorizationUrl($options));
    }

    /**
     * @throws InvalidTokenException
     * @throws PropelException
     */
    public function callback(Request $request, Axys $axys): RedirectResponse
    {
        $provider = $axys->getOpenIDConnectProvider();
        $code = $request->query->get("code");
        $token = $provider->getAccessToken("authorization_code", ["code" => $code]);
        $idToken = $token->getIdToken();

        $response = new RedirectResponse("/");

        $response->headers->setCookie(Cookie::create("id_token")->withValue($idToken));

        $userId = $idToken->claims()->get("sub");
        $user = UserQuery::create()->findPk($userId);
        $session = Session::buildForUser($user);
        $session->save();
        $response->headers->setCookie(Cookie::create("user_uid")->withValue($session->getToken()));

        return $response;
    }
}
