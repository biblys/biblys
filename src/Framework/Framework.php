<?php

namespace Framework;

use Biblys\Service\Config;
use Exception;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernel;

class Framework extends HttpKernel
{
    /**
     * @throws Exception
     */
    public function handle(
        Request $request,
        $type = self::MASTER_REQUEST,
        $catch = true
    ): Response
    {
        $config = new Config();
        if (!$request->isSecure() && $config->get('https') === true) {
            return self::_createHttpsRedirectResponse($request);
        }

        if ($request->query->has("UID")) {
            return $this->_createAfterLoginRedirectResponse($request);
        }

        return parent::handle($request, $type, $catch);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    static private function _createHttpsRedirectResponse(
        Request $request
    ): RedirectResponse
    {
        $httpsUrl = "https://".$request->getHttpHost().$request->getRequestUri();
        return new RedirectResponse($httpsUrl, 302);
    }

    /**s
     * @param Request $request
     * @return RedirectResponse
     */
    static private function _createAfterLoginRedirectResponse(
        Request $request
    ): RedirectResponse
    {
        $url = $request->getRequestUri();
        $url = preg_replace('/([?&]UID=[^&]*)/', '', $url);
        $axysUid = $request->query->get("UID");
        $cookie = Cookie::create("user_uid")
            ->withValue($axysUid)
            ->withExpires(0)
            ->withSecure(true);
        $response = new RedirectResponse($url, 302);
        $response->headers->setCookie($cookie);
        return $response;
    }
}
