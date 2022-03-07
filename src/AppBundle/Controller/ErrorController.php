<?php

namespace AppBundle\Controller;

use Axys\LegacyClient;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\Log;
use Biblys\Service\Mailer;
use Exception;
use Framework\Controller;
use Framework\Exception\AuthException;
use PDO;
use ReflectionClass;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

// TODO: use twig template for exceptions

class ErrorController extends Controller
{
    /**
     * @param Request $request
     * @param Exception $exception
     * @param LegacyClient|null $axys
     * @return Response
     */
    public function exception(Request $request, Exception $exception, LegacyClient $axys = null): Response
    {
        if (is_a($exception, "Symfony\Component\Routing\Exception\ResourceNotFoundException")) {
            return $this->handlePageNotFound($request, $exception);
        }

        if (is_a($exception, "Symfony\Component\HttpKernel\Exception\BadRequestHttpException")) {
            return self::_defaultHandler(400, $exception, $request);
        }

        if (is_a($exception, "Framework\Exception\AuthException")) {
            // TODO: use dependency injection
            if ($axys === null) {
                global $axys;
            }
            return $this->handleUnauthorizedAccess($request, $exception, $axys);
        }

        if (is_a($exception, "Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException")) {
            return self::_defaultHandler(405, $exception, $request);
        }

        if (is_a($exception, "Symfony\Component\HttpKernel\Exception\ConflictHttpException")) {
            return self::_defaultHandler(409, $exception, $request);
        }

        if (is_a($exception, "Framework\Exception\ServiceUnavailableException")) {
            return $this->handleServiceUnavailable($request);
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
            try {
                global $originalRequest;
                $response = $legacyController->defaultAction($originalRequest, $session, $mailer, $config, $currentSite);
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
     * HTTP 401/403
     * TODO: Distinguish between 401 (not logged in) and 403 (not authorized)
     *
     * @param Request $request
     * @param AuthException $exception
     * @param LegacyClient $axys
     * @return Response
     */
    private function handleUnauthorizedAccess(Request $request, AuthException $exception, LegacyClient $axys): Response
    {
        if (
            $request->isXmlHttpRequest()
            || $request->headers->get('Accept') == 'application/json'
        ) {
            $response = new JsonResponse(['error' => $exception->getMessage()]);
            $response->setStatusCode(401);
            return $response;
        }

        $response = new Response();
        $response->setStatusCode(401);
        $response->setContent('
            <h1>Erreur d\'authentification</h1>
            <p>' . $exception->getMessage() . '</p>
            <p>Pour continuer, veuillez <a href="' . $axys->getLoginUrl() . '">vous identifier</a> ou <a href="' . $axys->getSignupUrl() . '">créer un compte</a>.</p>
        ');

        return $response;
    }

    /**
     * HTTP 404
     *
     * @param Request $request
     * @param ResourceNotFoundException $exception
     * @return Response
     */
    private function handlePageNotFound(Request $request, ResourceNotFoundException $exception): Response
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

        return new Response('
            <div>
                <h1>Une erreur '.$exceptionClass->getShortName().' est survenue.</h1>
                <p>' . $exception->getMessage() . '</p>
            </div>
        ', $statusCode);
    }
}
