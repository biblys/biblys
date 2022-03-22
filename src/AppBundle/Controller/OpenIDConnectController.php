<?php

namespace AppBundle\Controller;

use Axys\AxysOpenIDConnectProvider;
use Biblys\Service\Config;
use Framework\Controller;
use OpenIDConnectClient\Exception\InvalidTokenException;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class OpenIDConnectController extends Controller
{
    public function axys(Config $config): RedirectResponse
    {
        $axysConfig = $config->get("axys");
        $provider = new AxysOpenIDConnectProvider($axysConfig);

        $options = ["scope" => ["openid", "email"]];
        return new RedirectResponse($provider->getAuthorizationUrl($options));
    }

    /**
     * @throws InvalidTokenException
     */
    public function callback(Request $request, Config $config): RedirectResponse
    {
        $axysConfig = $config->get("axys");
        $provider = new AxysOpenIDConnectProvider($axysConfig);
        $code = $request->query->get("code");
        $token = $provider->getAccessToken("authorization_code", ["code" => $code]);
        $idToken = $token->getIdToken();
        $idTokenCookie = Cookie::create("id_token")->withValue($idToken);
        $response = new RedirectResponse("/");
        $response->headers->setCookie($idTokenCookie);

        return $response;
    }
}
