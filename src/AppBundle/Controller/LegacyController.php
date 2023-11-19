<?php


namespace AppBundle\Controller;

use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUrlService;
use Biblys\Service\CurrentUser;
use Biblys\Service\Mailer;
use Biblys\Service\MetaTagsService;
use Biblys\Service\TemplateService;
use Closure;
use Exception;
use Framework\Controller;
use Model\PageQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGenerator;

class LegacyController extends Controller
{
    /**
     * @throws Exception
     */
    public function defaultAction(
        Request         $request,
        Session         $session,
        Mailer          $mailer,
        Config          $config,
        CurrentSite     $currentSite,
        CurrentUser     $currentUser,
        UrlGenerator    $urlGenerator,
        TemplateService $templateService,
        MetaTagsService $metaTagsService,
    ): Response
    {
        global $_ECHO, $_SQL, $urlgenerator;

        $globalSite = LegacyCodeHelper::getGlobalSite();

        $pageQueryParam = $request->get('page', 'home');

        $pagePrefix = substr($pageQueryParam, 0, 4);
        if ($pagePrefix == 'adm_') {
            self::authAdmin($request);
        }
        if ($pagePrefix == 'pub_') {
            self::authPublisher($request, null);
        }
        if ($pagePrefix == 'log_') {
            self::authUser($request);
        }

        $routeParams = $request->attributes->get("_route_params", []);
        LegacyCodeHelper::saveRouteParams($routeParams);

        // Retrocompatibility for static page urls (eg. /pages/:page_slug)
        $staticPage = PageQuery::create()
            ->filterBySite($currentSite->getSite())
            ->filterByStatus(1)
            ->findOneByUrl($pageQueryParam);
        if ($staticPage) {
            $staticPageUrl = $urlGenerator->generate("static_page_show", ["slug" => $staticPage->getUrl()]);
            return new RedirectResponse($staticPageUrl, 301);
        }

        $controllerPath = get_controller_path($pageQueryParam);
        if (!$controllerPath) {
            throw new NotFoundHttpException("Cannot find a legacy controller for url $pageQueryParam.");
        }

        $_ECHO = "";
        $response = require $controllerPath;

        if ($response instanceof Closure) {
            $legacyController = $response;
            $container = include __DIR__ . "/../../container.php";
            $argumentResolver = $container->get("argument_resolver");
            $arguments = $argumentResolver->getArguments($request, $legacyController);
            $response = $legacyController(...$arguments);
        } elseif ($_ECHO !== "") {
            trigger_deprecation(
                "biblys/biblys",
                "2.70.0",
                "Using global \$_ECHO (in legacy controller $controllerPath) is deprecated. Return an anonymous function instead."
            );
            $response = new Response($_ECHO);
        } elseif (isset($response)) {
            trigger_deprecation(
                "biblys/biblys",
                "2.70.0",
                "Returning a Response (in legacy controller $controllerPath) is deprecated. Return an anonymous function instead."
            );
        } else {
            throw new Exception("Legacy controller must expose a legacyController function.");
        }

        if ($response instanceof JsonResponse) {
            return $response;
        }

        if ($response instanceof RedirectResponse) {
            return $response;
        }

        if (isset($GLOBALS["_PAGE_TITLE"])) {
            trigger_deprecation(
                "biblys",
                "2.59.0",
                "Using \$_PAGE_TITLE to set page title is deprecated. Use \$request->attributes->set(\"page_title\", …) instead.");
            $request->attributes->set("page_title", $GLOBALS["_PAGE_TITLE"]);
        }

        return $templateService->renderResponse("AppBundle:Legacy:default.html.twig", [
            "title" => $request->attributes->get("page_title"),
            "content" => $response->getContent(),
        ]);
    }
}