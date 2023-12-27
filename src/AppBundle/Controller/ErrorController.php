<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUrlService;
use Biblys\Service\Log;
use Exception;
use Framework\Controller;
use Framework\Exception\AuthException;
use Model\ArticleQuery;
use Model\PeopleQuery;
use Propel\Runtime\Exception\PropelException;
use ReflectionClass;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ErrorController extends Controller
{
    /**
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function exception(
        Request     $request,
        CurrentSite $currentSite,
        UrlGenerator $urlGenerator,
        Exception   $exception
    ): Response
    {
        if (
            is_a($exception, ResourceNotFoundException::class)
            || is_a($exception, InvalidParameterException::class)
            || is_a($exception, NotFoundHttpException::class)) {
            return $this->handlePageNotFound($request, $currentSite, $urlGenerator, $exception);
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

        if (is_a($exception, ServiceUnavailableHttpException::class)) {
            return self::_customTemplateHandler(503, $request, $exception);
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
     * @param UrlGenerator $urlGenerator
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function handlePageNotFound(
        Request                                                                   $request,
        CurrentSite                                                               $currentSite,
        UrlGenerator                                                              $urlGenerator,
        NotFoundHttpException|ResourceNotFoundException|InvalidParameterException $exception,
    ): Response
    {
        $currentUrlService = new CurrentUrlService($request);
        $currentUrl = $currentUrlService->getRelativeUrl();
        $currentUrlWithoutFirstSlash = ltrim($currentUrl, "/");

        $article = ArticleQuery::create()
            ->filterForCurrentSite($currentSite)
            ->filterBySlug($currentUrlWithoutFirstSlash)
            ->findOne();
        if ($article) {
            $articleUrl = $urlGenerator->generate("article_show", ["slug" => $article->getSlug()]);
            return new RedirectResponse($articleUrl, 301);
        }

        $currentUrlWithoutSlash = str_replace("/", "", $currentUrl);
        $contributor = PeopleQuery::create()
            ->filterByUrl($currentUrlWithoutSlash)
            ->findOne();
        if ($contributor) {
            $contributorUrl = $urlGenerator->generate("people_show", ["slug" => $contributor->getUrl()]);
            return new RedirectResponse($contributorUrl, 301);
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
        $currentUrl = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

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
     * @param int $statusCode
     * @param Exception $exception
     * @param $request
     * @return Response
     */
    private static function _defaultHandler(int $statusCode, Exception $exception, $request): Response
    {
        if ($request->isXmlHttpRequest() || $request->headers->get('Accept') == 'application/json') {
            return new JsonResponse([
                "error" => [
                    "exception" => get_class($exception),
                    "message" => $exception->getMessage(),
                    "file" => $exception->getFile(),
                    "line" => $exception->getLine(),
                    "trace" => $exception->getTrace(),
                ]
            ], $statusCode);
        }
        $exceptionClass = new ReflectionClass($exception);

        // TODO: use render and twig template
        return new Response('
            <div>
                <h1>Une erreur ' . $exceptionClass->getShortName() . ' est survenue.</h1>
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
        int                         $statusCode,
        Request                     $request,
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

        $currentUrlService = new CurrentUrlService($request);
        $currentUrl = $currentUrlService->getRelativeUrl();
        $response = $this->render("AppBundle:Error:$statusCode.html.twig", [
            "message" => $exception->getMessage(),
            "return_url" => $currentUrl,
        ]);
        $response->setStatusCode($statusCode);
        foreach ($exception->getHeaders() as $name => $value) {
            $response->headers->set($name, $value);
        }

        return $response;
    }
}
