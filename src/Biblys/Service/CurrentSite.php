<?php

namespace Biblys\Service;

use Model\Site;
use Model\SiteQuery;

class CurrentSite
{
    /**
     * @var Site
     */
    private $site;

    public function __construct(Site $site)
    {
        $this->site = $site;
    }

    public function getSite(): Site
    {
        return $this->site;
    }

    /**
     * @param Config $config
     * @return CurrentSite
     */
    public static function buildFromConfig(Config $config): CurrentSite
    {
        $siteId = $config->get("site");

        $site = SiteQuery::create()->findPk($siteId);

        return new CurrentSite($site);
    }
}