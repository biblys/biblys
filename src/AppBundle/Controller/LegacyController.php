<?php


namespace AppBundle\Controller;

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\Mailer;
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
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;

class LegacyController extends Controller
{
    /**
     * @throws Exception
     */
    public function defaultAction(
        Request $request,
        Session $session,
        Mailer $mailer,
        Config $config,
        CurrentSite $currentSite,
        CurrentUser $currentUser,
        UrlGenerator $urlGenerator,
        TemplateService $templateService,
    ): Response
    {
        global $_SITE, $_ECHO, $_SQL, $urlgenerator;

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

        // Retrocompatibility for static page urls (eg. /pages/:page_slug)
        $staticPage = PageQuery::create()
            ->filterBySite($currentSite->getSite())
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
            $container = include __DIR__."/../../container.php";
            $argumentResolver = $container->get("argument_resolver");
            $arguments = $argumentResolver->getArguments($request, $legacyController);
            $response = $legacyController(...$arguments);
        }

        if ($response instanceof JsonResponse) {
            return $response;
        }

        if ($response instanceof RedirectResponse) {
            return $response;
        }

        if (!($response instanceof Response)) {
            trigger_deprecation(
                "biblys/biblys",
                "2.69.0",
                "Using \$_ECHO (in {$controllerPath}) is deprecated. Return a Response instead."
            );
            $response = new Response($_ECHO);
        }

        if (isset($GLOBALS["_PAGE_TITLE"])) {
            trigger_deprecation(
                "biblys",
                "2.59.0",
                "Using \$_PAGE_TITLE to set page title is deprecated. Use \$request->attributes->set(\"page_title\", …) instead.");
            $request->attributes->set("page_title", $GLOBALS["_PAGE_TITLE"]);
        }

        return $this->render("AppBundle:Legacy:default.html.twig", [
            "title" => $request->attributes->get("page_title"),
            "content" => $response->getContent(),
        ]);
    }
}