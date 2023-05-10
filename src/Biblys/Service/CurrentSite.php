<?php

namespace Biblys\Service;

use Model\Option;
use Model\OptionQuery;
use Model\Site;
use Model\SiteQuery;
use Propel\Runtime\Exception\PropelException;

class CurrentSite
{
    /**
     * @var Site
     */
    private Site $site;

    public function __construct(Site $site)
    {
        $this->site = $site;
    }

    public function getSite(): Site
    {
        return $this->site;
    }

    public function getId(): int
    {
        return $this->site->getId();
    }

    public function getTitle(): string
    {
        return $this->site->getTitle();
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

    /**
     * @throws PropelException
     */
    public function getOption(string $key): ?string
    {
        $option = OptionQuery::create()
            ->filterBySite($this->site)
            ->filterByKey($key)
            ->findOne();

        return $option?->getValue();

    }

    /**
     * @throws PropelException
     */
    public function setOption(string $key, string $value): void
    {
        $option = OptionQuery::create()
            ->filterBySite($this->site)
            ->filterByKey($key)
            ->findOne();

        if ($option && $value === "") {
            $option->delete();
            return;
        }

        if (!$option) {
            $option = new Option();
            $option->setSite($this->site);
            $option->setKey($key);
        }

        $option->setValue($value);
        $option->save();
    }

    /**
     * @throws PropelException
     */
    public function hasOptionEnabled(string $optionKey): bool
    {
        return $this->getOption($optionKey) === "1";
    }
}