<?php

namespace Framework;

use Biblys\Service\Config;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequestListener
{
    public function onUnsecureRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if ($request->isSecure()) {
            return;
        }

        $config = Config::load();
        if ($config->get('https') !== true) {
            return;
        }

        $httpsUrl = "https://" . $request->getHttpHost() . $request->getRequestUri();
        $response = new RedirectResponse($httpsUrl, 302);
        $event->setResponse($response);
    }
}
