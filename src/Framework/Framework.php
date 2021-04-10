<?php

namespace Framework;

use Exception;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Matcher\UrlMatcher;

class Framework
{
    private $kernel;

    private $dispatcher;
    private $matcher;
    private $controllerResolver;
    private $argumentResolver;

    public function __construct(EventDispatcher $dispatcher, UrlMatcher $matcher, ControllerResolver $controllerResolver, ArgumentResolver $argumentResolver)
    {
        $this->dispatcher = $dispatcher;
        $this->matcher = $matcher;
        $this->controllerResolver = $controllerResolver;
        $this->argumentResolver = $argumentResolver;
    }

    /**
     * @throws Exception
     */
    public function handle(Request $request): Response
    {
        $axysUid = $request->query->get("UID");
        if ($axysUid) {
            return $this->_createAfterLoginRedirectResponse($request, $axysUid);
        }

        $this->dispatcher->addSubscriber(new RouterListener($this->matcher, new RequestStack()));

        $this->kernel = new HttpKernel(
            $this->dispatcher,
            $this->controllerResolver,
            new RequestStack(),
            $this->argumentResolver
        );

        return $this->kernel->handle($request);
    }

    public function terminateKernel(Request $request, Response $response)
    {
        if (!$this->kernel) {
            return;
        }

        $this->kernel->terminate($request, $response);
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
