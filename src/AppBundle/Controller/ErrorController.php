<?php

namespace AppBundle\Controller;

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\Log;
use Biblys\Service\Mailer;
use Exception;
use Framework\Controller;
use Framework\Exception\AuthException;
use Framework\RouteLoader;
use PDO;
use Propel\Runtime\Exception\PropelException;
use ReflectionClass;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ErrorController extends Controller
{
    /**
     * @param Request $request
     * @param Exception $exception
     * @return Response
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function exception(Request $request, Exception $exception): Response
    {
        if (is_a($exception, ResourceNotFoundException::class) || is_a($exception, InvalidParameterException::class)) {
            return $this->handlePageNotFound($request, $exception);
        }

        if (is_a($exception, BadRequestHttpException::class)) {
            return $this->handleBadRequest($request, $exception);
        }

        if (is_a($exception, UnauthorizedHttpException::class)) {
            return self::_customTemplateHandler(401, $request, $exception);
        }

        if (is_a($exception, AccessDeniedHttpException::class)) {
            return self::_customTemplateHandler(403, $request, $exception);
        }

        if (is_a($exception, "Framework\Exception\AuthException")) {
            return self::_customTemplateHandler(401, $request, $exception);
        }

        if (is_a($exception, "Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException")) {
            return self::_defaultHandler(405, $exception, $request);
        }

        if (is_a($exception, "Symfony\Component\HttpKernel\Exception\ConflictHttpException")) {
            return self::_defaultHandler(409, $exception, $request);
        }

        // If route is not found in route.yml, we might be dealing with a legacy
        // controller. We try to handle the route below. If not, default action
        // will throw a resourceNotFoundException that will be catched below.
        if (is_a($exception, "Symfony\Component\HttpKernel\Exception\NotFoundHttpException")) {
            $legacyController = new LegacyController();
            $session = new Session();
            $mailer = new Mailer();
            $config = new Config();
            $currentSite = CurrentSite::buildFromConfig($config);
            $routes = RouteLoader::load();
            $urlgenerator = new UrlGenerator($routes, new RequestContext());
            try {
                global $originalRequest;
                $response = $legacyController->defaultAction(
                    $originalRequest,
                    $session,
                    $mailer,
                    $config,
                    $currentSite,
                    $urlgenerator
                );
                $response->headers->set("SHOULD_RESET_STATUS_CODE_TO_200", "true");
                return $response;
            } catch (Exception $exception) {
                // TODO: find a better way
                // This is necessary because of legacy controller can throw exceptions
                // that won't be caught by event dispatcher
                $errorController = new ErrorController();
                return $errorController->exception($request, $exception);
            }
        }

        return $this->handleServerError($request, $exception);
    }

    /**
     * HTTP 400
     *
     * @param Request $request
     * @param BadRequestHttpException $exception
     * @return Response
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function handleBadRequest(Request $request, BadRequestHttpException $exception): Response
    {
        if (
            $request->isXmlHttpRequest()
            || $request->headers->get('Accept') == 'application/json'
        ) {
            $response = new JsonResponse(['error' => $exception->getMessage()]);
            $response->setStatusCode(400);
            return $response;
        }

        $response = $this->render("AppBundle:Error:400.html.twig", [
            "message" => $exception->getMessage(),
        ]);
        $response->setStatusCode(400);

        return $response;
    }

    /**
     * HTTP 404
     *
     * @param Request $request
     * @param ResourceNotFoundException|InvalidParameterException $exception
     * @return Response
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function handlePageNotFound(
        Request $request,
        ResourceNotFoundException|InvalidParameterException $exception
    ): Response
    {
        global $_SQL, $site;

        $protocol = $request->isSecure() ? 'https' : 'http';

        $currentUrl = "$protocol://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $parsedUrl = parse_url($currentUrl);
        $redirectionOld = $parsedUrl["path"];
        if (!empty($parsedUrl["query"])) {
            $redirectionOld .= '?' . $parsedUrl["query"];
        }

        $redirections = $_SQL->prepare(
            "SELECT `redirection_id`, `redirection_new`
          FROM `redirections` WHERE (`redirection_old` = :redirection_old)
              AND `redirection_old` != `redirection_new`
              AND (`site_id` = :site_id OR `site_id` IS NULL) LIMIT 1"
        );
        $redirections->execute(
            [
                'redirection_old' => $redirectionOld,
                'site_id' => $site->get('id')
            ]
        );
        if ($r = $redirections->fetch(PDO::FETCH_ASSOC)) {
            return new RedirectResponse($r['redirection_new'], 301);
        }

        if ($request->headers->get("Accept") === "application/json") {
            $response = new JsonResponse(["error" => $exception->getMessage()]);
            $response->setStatusCode(404);
            return $response;
        }

        $request->attributes->set("page_title", "Erreur 404");

        $response = $this->render("AppBundle:Error:404.html.twig", [
            "exception" => $exception,
            "exceptionClass" => get_class($exception),
        ]);
        $response->setStatusCode(404);

        return $response;
    }

    /**
     * HTTP 500
     *
     * @param Request $request
     * @param Exception $exception
     * @return Response
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function handleServerError(Request $request, Exception $exception): Response
    {
        $currentUrl = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

        Log::error("ERROR", $exception->getMessage(), [
            "URL" => $currentUrl,
            "File" => $exception->getFile(),
            "Line" => $exception->getLine(),
            "Trace" => $exception->getTrace(),
        ]);

        // FIXME: Use ApiBundle for request excepting json
        if (
            $request->headers->get("Accept") === "application/json" ||
            $request->isXmlHttpRequest()
        ) {
            return new JsonResponse([
                "error" => $exception->getMessage(),
                "file" => $exception->getFile(),
                "line" => $exception->getLine(),
                "trace" => $exception->getTrace(),
            ], 500);
        }

        $currentException = $exception;
        $previousExceptions = [];
        while ($previous = $currentException->getPrevious()) {
            $previousExceptions[] = $previous;
            $currentException = $previous;
        }

        $response = $this->render("AppBundle:Error:500.html.twig", [
            "exception" => $exception,
            "exceptionClass" => get_class($exception),
            "previousExceptions" => $previousExceptions,
        ]);
        $response->setStatusCode(500);

        return $response;
    }

    /**
     * HTTP 503
     *
     * @param Request $request
     * @return Response
     */
    private function handleServiceUnavailable(Request $request): Response
    {
        $response = self::_defaultHandler(
            503,
            new Exception("Maintenance en cours. Merci de réessayer dans quelques instants…"),
            $request
        );
        $response->headers->set('Retry-After', 3600);

        return $response;
    }

    /**
     * @param int $statusCode
     * @param Exception $exception
     * @param $request
     * @return Response
     */
    private static function _defaultHandler(int $statusCode, Exception $exception, $request): Response
    {
        if ($request->isXmlHttpRequest() || $request->headers->get('Accept') == 'application/json') {
            return new JsonResponse([
                "error" => $exception->getMessage(),
            ], $statusCode);
        }
        $exceptionClass = new ReflectionClass($exception);


        // TODO: use render and twig template
        return new Response('
            <div>
                <h1>Une erreur '.$exceptionClass->getShortName().' est survenue.</h1>
                <p>' . $exception->getMessage() . '</p>
            </div>
        ', $statusCode);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    private function _customTemplateHandler(
        int $statusCode,
        Request $request,
        HttpException|AuthException $exception
    ): Response
    {
        if (
            $request->isXmlHttpRequest()
            || $request->headers->get('Accept') == 'application/json'
        ) {
            $response = new JsonResponse(['error' => $exception->getMessage()]);
            $response->setStatusCode($statusCode);
            return $response;
        }

        $currentUrl = $request->getSchemeAndHttpHost().$request->getBaseUrl().$request->getPathInfo();
        $response = $this->render("AppBundle:Error:$statusCode.html.twig", [
            "message" => $exception->getMessage(),
            "return_url" => $currentUrl,
        ]);
        $response->setStatusCode($statusCode);

        return $response;
    }
}
