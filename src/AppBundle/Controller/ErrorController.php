<?php

namespace AppBundle\Controller;

use Biblys\Axys\Client;
use Biblys\Service\Log;
use Exception;
use Framework\Controller;
use Framework\Exception\AuthException;
use PDO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

// TODO: use twig template for exceptions

class ErrorController extends Controller
{
    /**
     * @param Request $request
     * @param Exception $exception
     * @param Client|null $axys
     * @return Response
     */
    public function exception(
        Request $request,
        Exception $exception,
        Client $axys = null
    ): Response
    {
        if (is_a($exception, "Symfony\Component\Routing\Exception\ResourceNotFoundException")) {
            return $this->handlePageNotFound($request, $exception);
        }

        if (is_a($exception, "Symfony\Component\HttpKernel\Exception\BadRequestHttpException")) {
            return $this->handleBadRequest($request, $exception);
        }

        if (is_a($exception, "Framework\Exception\AuthException")) {
            // TODO: use dependency injection
            if ($axys === null) {
                global $axys;
            }
            return $this->handleUnauthorizedAccess($request, $exception, $axys);
        }

        if (is_a($exception, "Framework\Exception\ServiceUnavailableException")) {
            return $this->handleServiceUnavailable();
        }

        // If route is not found in route.yml, we might be dealing with a legacy
        // controller. We try to handle the route below. If not, default action
        // will throw a resourceNotFoundException that will be catched below.
        if (is_a($exception, "Symfony\Component\HttpKernel\Exception\NotFoundHttpException")) {
            $legacyController = new LegacyController();
            try {
                return $legacyController->defaultAction($request);
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
     */
    private function handleBadRequest(Request $request, BadRequestHttpException $exception): Response
    {
        if (
            $request->isXmlHttpRequest()
            || $request->headers->get('Accept') == 'application/json'
        ) {
            $response = new JsonResponse([
                "error" => sprintf("Bad request: %s", $exception->getMessage())
            ]);
        } else {
            $response = new Response('
            <div class="text-center">
                <h1>Error: Bad request</h1>
                <p>' . $exception->getMessage() . '</p>
            </div>
        ');
        }

        $response->setStatusCode(400);
        return $response;
    }

    /**
     * HTTP 401/403
     * TODO: Distinguish between 401 (not logged in) and 403 (not authorized)
     *
     * @param Request $request
     * @param AuthException $exception
     * @param Client $axys
     * @return Response
     */
    private function handleUnauthorizedAccess(
        Request $request,
        AuthException $exception,
        Client $axys
    ): Response
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
        global $_SQL, $_V, $site;

        $currentUrl = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
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
            $response = new RedirectResponse($r['redirection_new'], 301);
        } else {
            if ($request->headers->get("Accept") === "application/json") {
                $response = new JsonResponse(["error" => $exception->getMessage()]);
                $response->setStatusCode(404);
                return $response;
            }

            $response = new Response();
            $response->setStatusCode(404);

            $this->setPageTitle("Erreur 404");
            $content = '
              <h2>Erreur 404</h2>
              <p>Cette page  n\'existe pas !</p>
          ';

            if ($_V->isAdmin()) {
                $content .= '

                  ' . (isset($errorMessage) ? '<p>Debug info: ' . $errorMessage . '</p>' : null) . '
                  <p>Page : ' . $redirectionOld . '</p>
              ';
            }

            $response->setContent($content);
        }
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
        if ($request->headers->get("Accept") === "application/json") {
            return new JsonResponse([
                "error" => $exception->getMessage(),
                "file" => $exception->getFile(),
                "line" => $exception->getLine(),
                "trace" => $exception->getTrace(),
            ], 500);
        }

        $response = $this->render("AppBundle:Error:500.html.twig", [
            "exception" => $exception,
            "exceptionClass" => get_class($exception),
        ]);
        $response->setStatusCode(500);

        return $response;
    }

    /**
     * HTTP 503
     *
     * @return Response
     */
    private function handleServiceUnavailable(): Response
    {
        $response = new Response();
        $response->setStatusCode(503);
        $response->headers->set('Retry-After', 3600);
        $response->setContent('
            <div class="text-center">
                <h1>Maintenance en cours</h1>
                <p>Merci de réessayer dans quelques instants…</p>
            </div>
        ');

        return $response;
    }
}
