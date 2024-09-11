<?php

namespace Biblys\Legacy;

use Biblys\Database\Connection;
use Biblys\Service\Config;
use Biblys\Service\LoggerService;
use Exception;
use Framework\RouteLoader;
use Model\SiteQuery;
use PDO;
use Site;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;
use Visitor;
use function trigger_deprecation;

class LegacyCodeHelper
{
    private static array $knownDeprecations = [];

    /**
     * @throws Exception
     * @deprecated Using getLegacyCurrentSite is deprecated. Use CurrentSite service instead.
     */
    public static function getLegacyCurrentSite(): Site
    {
        trigger_deprecation(
            "biblys/biblys",
            "2.69.0",
            "Using getGlobalSite is deprecated. Use CurrentSite service instead.",
        );

        return self::getGlobalSite(ignoreDeprecation: true);
    }

    /**
     * @throws Exception
     * @deprecated Using getGlobalSite is deprecated. Use CurrentSite service instead.
     */
    public static function getGlobalSite($ignoreDeprecation = false): Site
    {
        if (!$ignoreDeprecation) {
            trigger_deprecation(
                "biblys/biblys",
                "2.69.0",
                "Using getGlobalSite is deprecated. Use CurrentSite service instead.",
            );
        }

        if (!isset($GLOBALS["LEGACY_CURRENT_SITE"])) {
            $config = Config::load();
            $currentSiteId = $config->get("site");
            $currentSite = SiteQuery::create()->findPk($currentSiteId);
            if (!$currentSite) {
                throw new Exception("Unable to find site with id $currentSiteId");
            }
            self::setGlobalSite($currentSite);
        }

        return $GLOBALS["LEGACY_CURRENT_SITE"];
    }

    public static function setGlobalSite(\Model\Site $site): void
    {
        $GLOBALS["LEGACY_CURRENT_SITE"] = new Site([
            "site_id" => $site->getId(),
            "site_title" => $site->getTitle(),
            "site_domain" => $site->getDomain(),
            "site_contact" => $site->getContact(),
            "site_tag" => $site->getTag(),
        ]);
    }

    /**
     * @throws Exception
     * @deprecated Using getGlobalConfig is deprecated. Use CurrentUser service instead.
     */
    public static function getGlobalConfig($ignoreDeprecation = false): Config
    {
        if (!$ignoreDeprecation) {
            trigger_deprecation(
                "biblys/biblys",
                "2.69.0",
                "Using getGlobalConfig is deprecated. Use Config service instead.",
            );
        }

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
     * @deprecated Using getGlobalVisitor is deprecated. Use CurrentUser service instead.
     */
    public static function getGlobalVisitor(): Visitor
    {
        trigger_deprecation(
            "biblys/biblys",
            "2.68.0",
            "Using getGlobalVisitor is deprecated. Use CurrentUser service instead.",
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

            $deprecationKey = "{$caller["file"]}:{$caller["line"]}:";
            if (array_key_exists($deprecationKey, self::$knownDeprecations)) {
                return;
            }
            self::$knownDeprecations[$deprecationKey] = true;

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

    /**
     * @deprecated Using getGlobalDatabaseConnection is deprecated. Use EntityManager instead.
     */
    public static function getGlobalDatabaseConnection(): PDO
    {
        trigger_deprecation(
            "biblys/biblys",
            "2.76.0",
            "Using getGlobalDatabaseConnection is deprecated. Use EntityManager instead.",
        );

        if (!isset($GLOBALS["LEGACY_DATABASE_CONNECTION"])) {
            $config = self::getGlobalConfig(ignoreDeprecation: true);
            $GLOBALS["LEGACY_DATABASE_CONNECTION"] = Connection::init($config);
        }

        return $GLOBALS["LEGACY_DATABASE_CONNECTION"];
    }

    /**
     * @deprecated Using getGlobalUrlGenerator is deprecated. Inject UrlGenerator in controller instead
     */
    public static function getGlobalUrlGenerator(): UrlGenerator
    {
        trigger_deprecation(
            "biblys/biblys",
            "2.86.0",
            "Using getGlobalUrlGenerator is deprecated. Inject UrlGenerator in controller instead.",
        );

        if (!isset($GLOBALS["LEGACY_URL_GENERATOR"])) {
            $routes = RouteLoader::load();
            $GLOBALS["LEGACY_URL_GENERATOR"] = new UrlGenerator($routes, new RequestContext());
        }

        return $GLOBALS["LEGACY_URL_GENERATOR"];
    }
}