<?php

namespace AppBundle\Controller;

use Axys\AxysOpenIDConnectProvider;
use Biblys\Service\Config;
use Framework\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class OpenIDConnectController extends Controller
{
    public function axys(Config $config): RedirectResponse
    {
        $axysConfig = $config->get("axys");
        $provider = new AxysOpenIDConnectProvider($axysConfig);

        $options = ["scope" => ["openid", "email"]];
        return new RedirectResponse($provider->getAuthorizationUrl($options));
    }

}
