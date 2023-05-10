<?php

namespace Biblys\Legacy;

use Exception;

class LegacyCodeHelper
{
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