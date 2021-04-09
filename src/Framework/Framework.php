<?php

namespace Framework;

use Composer\Console\Application;
use Exception;
use Symfony\Component\Console\Input\ArrayInput;
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
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class Framework
{
    private $kernel;

    private $matcher;
    private $controllerResolver;
    private $argumentResolver;

    public function __construct(UrlMatcher $matcher, ControllerResolver $controllerResolver, ArgumentResolver $argumentResolver)
    {
        $this->matcher = $matcher;
        $this->controllerResolver = $controllerResolver;
        $this->argumentResolver = $argumentResolver;
    }

    public function handle(Request $request): Response
    {
        $axysUid = $request->query->get("UID");
        if ($axysUid) {
            return $this->_createAfterLoginRedirectResponse($request, $axysUid);
        }

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new RouterListener($this->matcher, new RequestStack()));

        $this->kernel = new HttpKernel(
            $dispatcher,
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

    public static function runComposerCommand(string $command)
    {
        global $config;

        // Set composer home
        $composer_home = $config->get('composer_home');
        if (!$composer_home) {
            throw new Exception("L'option `composer_home` doit être définie dans le fichier de configuration pour utiliser composer.");
        }
        putenv('COMPOSER_HOME='.$composer_home);

        // Change directory to Biblys root
        chdir(BIBLYS_PATH);

        // Updating composer packages
        $application = new Application();
        $application->setAutoExit(false);
        $code = $application->run(new ArrayInput(['command' => $command]));

        if ($code !== 0) {
            throw new Exception('Une erreur est survenue lors de la mise à jour automatique
                    des composants.');
        }
    }

    static public function getUrlGenerator(RouteCollection $routes, RequestContext $context): UrlGenerator
    {
        return new UrlGenerator($routes, $context);
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
