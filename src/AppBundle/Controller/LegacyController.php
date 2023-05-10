<?php


namespace AppBundle\Controller;

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\Mailer;
use Exception;
use Framework\Controller;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGenerator;

class LegacyController extends Controller
{
    /**
     * @throws PropelException
     * @throws Exception
     */
    public function defaultAction(
        Request $request,
        Session $session,
        Mailer $mailer,
        Config $config,
        CurrentSite $currentSite,
        UrlGenerator $urlGenerator,
    ): Response
    {
        global $_SITE, $_ECHO, $_SQL, $urlgenerator;

        $_PAGE = $request->get('page', 'home');

        $_PAGE_TYPE = substr($_PAGE, 0, 4);
        if ($_PAGE_TYPE == 'adm_') {
            self::authAdmin($request);
        }
        if ($_PAGE_TYPE == 'pub_') {
            self::authPublisher($request, null);
        }
        if ($_PAGE_TYPE == 'log_') {
            self::authUser($request);
        }

        $controllerPath = get_controller_path($_PAGE);
        if (!$controllerPath) {
            $staticPageUrl = $urlGenerator->generate("static_page_show", ["slug" => $_PAGE]);
            return new RedirectResponse($staticPageUrl, 301);
        }

        $_ECHO = "";
        $response = require $controllerPath;

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