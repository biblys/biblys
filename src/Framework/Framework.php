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
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

class Framework
{
    protected $matcher;
    protected $resolver;
    private $request;
    private $routes;
    private $context;
    private $kernel;

    public function __construct($request)
    {
        $this->request = $request;
        $this->routes = require BIBLYS_PATH.'src/routes.php';
        $this->context = new RequestContext();
        $this->context->fromRequest($request);
    }

    public function getUrlGenerator(): UrlGenerator
    {
        return new UrlGenerator($this->routes, $this->context);
    }

    public function handle(): Response
    {

        $axysUid = $this->request->query->get("UID");
        if ($axysUid) {
            return $this->_createAfterLoginRedirectResponse($this->request, $axysUid);
        }

        $matcher = new UrlMatcher($this->routes, $this->context);
        $controllerResolver = new ControllerResolver();
        $argumentResolver = new ArgumentResolver();
        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new RouterListener($matcher, new RequestStack()));

        // Handle Kernel exception (404)
        $dispatcher->addListener(
            'kernel.exception',
            function (GetResponseForExceptionEvent $event) {
                $exception = $event->getThrowable();
                if ($exception instanceof NotFoundHttpException) {
                    throw new ResourceNotFoundException();
                }

                throw $exception;
            }
        );

        $this->kernel = new HttpKernel(
            $dispatcher,
            $controllerResolver,
            new RequestStack(),
            $argumentResolver
        );

        return $this->kernel->handle($this->request);
    }

    public function terminateKernel(Request $request, Response $response)
    {
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
