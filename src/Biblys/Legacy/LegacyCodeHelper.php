<?php

namespace Biblys\Legacy;

use Biblys\Service\Config;
use Biblys\Service\LoggerService;
use Exception;
use Site;
use SiteManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Visitor;
use function trigger_deprecation;

class LegacyCodeHelper
{
    /**
     * @throws Exception
     * @deprecated Using getLegacyCurrentSite is deprecated. Use CurrentSite service instead.
     */
    public static function getLegacyCurrentSite(): Site
    {
        return self::getGlobalSite();
    }

    /**
     * @throws Exception
     * @deprecated Using getGlobalSite is deprecated. Use CurrentSite service instead.
     */
    public static function getGlobalSite(): Site
    {
        trigger_deprecation(
            "biblys/biblys",
            "2.69.0",
            "Using getLegacyCurrentSite is deprecated. Use CurrentSite service instead.",
        );

        if (!isset($GLOBALS["LEGACY_CURRENT_SITE"])) {
            $config = Config::load();
            $currentSiteId = $config->get("site");
            $sm = new SiteManager();
            $currentSite = $sm->getById($currentSiteId);
            $GLOBALS["LEGACY_CURRENT_SITE"] = $currentSite;
        }

        return $GLOBALS["LEGACY_CURRENT_SITE"];
    }

    /**
     * @throws Exception
     * @deprecated Using getGlobalConfig is deprecated. Use CurrentUser service instead.
     */
    public static function getGlobalConfig(): Config
    {
        trigger_deprecation(
            "biblys/biblys",
            "2.69.0",
            "Using getGlobalConfig is deprecated. Use Config service instead.",
        );

        if (!isset($GLOBALS["LEGACY_CONFIG"])) {
            $GLOBALS["LEGACY_CONFIG"] = Config::load();
        }

        return $GLOBALS["LEGACY_CONFIG"];
    }

    /**
     * @throws Exception
     * @deprecated Using setGlobalPageTitle is deprecated. Use Twig blog "title" or $request->attributes->set("page_title", …) instead.
     */
    public static function setGlobalPageTitle($pageTitle): void
    {
        trigger_deprecation(
            "biblys/biblys",
            "2.69.0",
            "Using setGlobalPageTitle is deprecated. Use Twig blog \"title\" or \$request->attributes->set(\"page_title\", …) instead.",
        );

        $GLOBALS["LEGACY_PAGE_TITLE"] = $pageTitle;
    }

    /**
     * @throws Exception
     * @deprecated Using getGlobalPageTitle is deprecated. Use Twig blog "title" or
     * $request->attributes->set("page_title", …) instead.
     */
    public static function getGlobalPageTitle(): ?string
    {
        trigger_deprecation(
            "biblys/biblys",
            "2.69.0",
            "Using getGlobalPageTitle is deprecated. Use Twig blog \"title\" or \$request->attributes->get(\"page_title\", …) instead.",
        );

        if (isset($GLOBALS["LEGACY_PAGE_TITLE"])) {
            return $GLOBALS["LEGACY_PAGE_TITLE"];
        }

        return null;
    }

    /**
     * @deprecated Using getGlobalRequest is deprecated. Use Request instead.
     */
    public static function getGlobalRequest(): Request
    {
        trigger_deprecation(
            "biblys/biblys",
            "2.69.0",
            "Using getGlobalRequest is deprecated. Use Request instead.",
        );

        if (!isset($GLOBALS["LEGACY_REQUEST"])) {
            $GLOBALS["LEGACY_REQUEST"] = Request::createFromGlobals();
        }

        return $GLOBALS["LEGACY_REQUEST"];
    }

    /**
     * @deprecated Using getLegacyVisitor is deprecated. Use CurrentUser service instead.
     */
    public static function getGlobalVisitor(): Visitor
    {
        trigger_deprecation(
            "biblys/biblys",
            "2.68.0",
            "Using getLegacyVisitor is deprecated. Use CurrentUser service instead.",
        );

        if (!isset($GLOBALS["LEGACY_VISITOR"])) {
            $request = Request::createFromGlobals();
            $GLOBALS["LEGACY_VISITOR"] = new Visitor($request);
        }

        return $GLOBALS["LEGACY_VISITOR"];
    }

    public static function catchDeprecationNotices(Config $config, Session $session): void
    {
        set_error_handler(function ($level, $message) use ($config, $session): void {
            $trace = debug_backtrace();
            $caller = $trace[1];
            if ($level === E_USER_DEPRECATED) {
                $caller = $trace[3];
            }

            if ($config->get("logs.deprecations")) {
                $loggerService = new LoggerService();
                $loggerService->log("deprecations", "WARNING", $message, ["trace" => debug_backtrace()]);
            }

            if ($config->get("environment") === "dev") {
                $session->getFlashBag()->add(
                    "warning",
                    "DEPRECATED (from {$caller["file"]}:{$caller["line"]}): $message"
                );
            }
        }, E_USER_DEPRECATED ^ E_DEPRECATED);
    }

    public static function saveRouteParams(array $params): void
    {
        $GLOBALS["LEGACY_ROUTE_PARAMS"] = $params;
    }

    public static function getRouteParam(string $key): ?string
    {
        if (isset($GLOBALS["LEGACY_ROUTE_PARAMS"][$key])) {
            return $GLOBALS["LEGACY_ROUTE_PARAMS"][$key];
        }

        return null;
    }
}