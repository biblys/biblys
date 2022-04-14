<?php

namespace AppBundle\Controller;

use Biblys\Service\Axys;
use Biblys\Service\Config;
use Framework\Controller;
use OpenIDConnectClient\Exception\InvalidTokenException;
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
     */
    public function callback(Request $request, Axys $axys): RedirectResponse
    {
        $provider = $axys->getOpenIDConnectProvider();
        $code = $request->query->get("code");
        $token = $provider->getAccessToken("authorization_code", ["code" => $code]);
        $idToken = $token->getIdToken();
        $idTokenCookie = Cookie::create("id_token")->withValue($idToken);
        $response = new RedirectResponse("/");
        $response->headers->setCookie($idTokenCookie);

        return $response;
    }
}
