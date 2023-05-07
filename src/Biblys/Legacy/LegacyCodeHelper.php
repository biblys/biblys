<?php

namespace Biblys\Legacy;

use Biblys\Service\Config;
use Exception;

class LegacyCodeHelper
{
    /**
     * @deprecated Using getLegacyGlobalConfig is deprecated. Use CurrentUser service instead.
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
     * @deprecated Using getLegacyGlobalPageTitle is deprecated. Use Twig blog "title" or $request->attributes->set("page_title", …) instead.
     */
    public static function setLegacyGlobalPageTitle($pageTitle): void
    {
        trigger_deprecation(
            "biblys/biblys",
            "2.69.0",
            "Using setLegacyGlobalPageTitle is deprecated. Use Twig blog \"title\" or \$request->attributes->set(\"page_title\", …) instead.",
        );

        $GLOBALS["LEGACY_PAGE_TITLE"] = $pageTitle;
    }

    /**
     * @throws Exception
     * @deprecated Using getLegacyGlobalPageTitle is deprecated. Use Twig blog "title" or $request->attributes->set("page_title", …) instead.
     */
    public static function getLegacyGlobalPageTitle(): ?string
    {
        trigger_deprecation(
            "biblys/biblys",
            "2.69.0",
            "Using getLegacyGlobalPageTitle is deprecated. Use Twig blog \"title\" or \$request->attributes->get(\"page_title\", …) instead.",
        );

        if (isset($GLOBALS["LEGACY_PAGE_TITLE"])) {
            return $GLOBALS["LEGACY_PAGE_TITLE"];
        }

        return null;
    }
}