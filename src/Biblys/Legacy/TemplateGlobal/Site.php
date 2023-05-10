<?php

namespace Biblys\Legacy\TemplateGlobal;

use Biblys\Service\Config;
use Entity;
use Exception;
use SiteManager;

class Site
{
    private Entity|false $site;

    public function __construct(Config $config)
    {
        $currentSiteId = $config->get("site");
        $sm = new SiteManager();
        $this->site = $sm->getById($currentSiteId);
    }

    /**
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        trigger_deprecation(
            "biblys/biblys",
            "2.69.0",
            "Using app.site.$name is deprecated. Use app.currentSite instead.",
        );

        if (method_exists($this->site, $name)) {
            return $this->site->$name(...$arguments);
        }

        return $this->site->get($name);
    }
}