<?php

namespace Framework;

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
        $axysUid = $request->query->get("UID");
        if ($axysUid) {
            return $this->_createAfterLoginRedirectResponse($request, $axysUid);
        }

        return parent::handle($request, $type, $catch);
    }

    /**s
     * @param Request $request
     * @param string $axysUid
     * @return RedirectResponse
     */
    static private function _createAfterLoginRedirectResponse(
        Request $request,
        string $axysUid
    ): RedirectResponse
    {
        $url = $request->getRequestUri();
        $url = preg_replace('/([?&]UID=[^&]*)/', '', $url);
        $cookie = Cookie::create("user_uid")
            ->withValue($axysUid)
            ->withExpires(0)
            ->withSecure(true);
        $response = new RedirectResponse($url, 302);
        $response->headers->setCookie($cookie);
        return $response;
    }
}
