<?php

namespace Biblys\Service;

use Symfony\Component\HttpFoundation\Request;

class CurrentUrlService
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getRelativeUrl(): string
    {
        return $this->request->getBaseUrl()
                .$this->request->getPathInfo();
    }

    public function getAbsoluteUrl(): string
    {
        return $this->request->getSchemeAndHttpHost()
                .$this->request->getBaseUrl()
                .$this->request->getPathInfo();
    }
}
