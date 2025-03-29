<?php
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


namespace AppBundle\Controller;

use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\Mailer;
use Biblys\Service\MetaTagsService;
use Biblys\Service\TemplateService;
use Closure;
use Exception;
use Framework\Controller;
use Model\PageQuery;
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
        global $_ECHO, $_SQL;

        $globalSite = LegacyCodeHelper::getGlobalSite(ignoreDeprecation: true);

        $pageQueryParam = $request->get('page', 'home');

        $wrapperTemplate = "AppBundle:Legacy:default.html.twig";
        $pagePrefix = substr($pageQueryParam, 0, 4);
        $isResponsePrivate = false;
        if ($pagePrefix == 'adm_') {
            $wrapperTemplate = "AppBundle:Legacy:default-admin.html.twig";
            $currentUser->authAdmin();
            $isResponsePrivate = true;
        }
        if ($pagePrefix == 'pub_') {
            $currentUser->authPublisher();
            $isResponsePrivate = true;
        }
        if ($pagePrefix == 'log_') {
            $currentUser->authUser();
            $isResponsePrivate = true;
        }

        if ($pageQueryParam == "article_edit") {
            $wrapperTemplate = "AppBundle:Legacy:default-admin.html.twig";
        }

        $routeParams = $request->attributes->get("_route_params", []);
        LegacyCodeHelper::saveRouteParams($routeParams);

        // Backward compatibility for static page urls (eg. /pages/:page_slug)
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

        return $templateService->renderResponse($wrapperTemplate, [
            "title" => $request->attributes->get("page_title"),
            "content" => $response->getContent(),
        ], isPrivate: $isResponsePrivate);
    }
}