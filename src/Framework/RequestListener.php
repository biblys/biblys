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

        $config = new Config();
        if ($config->get('https') !== true) {
            return;
        }

        $httpsUrl = "https://" . $request->getHttpHost() . $request->getRequestUri();
        $response = new RedirectResponse($httpsUrl, 302);
        $event->setResponse($response);
    }

    public function onReturningFromAxysRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (!$request->query->has("UID")) {
            return;
        }

        $url = $request->getRequestUri();
        $url = preg_replace('/([?&]UID=[^&]*)/', '', $url);
        $axysUid = $request->query->get("UID");
        $cookie = Cookie::create("user_uid")->withValue($axysUid);
        $response = new RedirectResponse($url, 302);
        $response->headers->setCookie($cookie);
        $event->setResponse($response);
    }
}