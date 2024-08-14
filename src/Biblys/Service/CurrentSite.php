<?php

namespace Biblys\Service;

use Model\Option;
use Model\OptionQuery;
use Model\Publisher;
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
     * @throws InvalidSiteIdException
     */
    public static function buildFromConfig(Config $config): CurrentSite
    {
        $siteId = $config->get("site");

        $site = SiteQuery::create()->findPk($siteId);
        if (!$site) {
            throw new InvalidSiteIdException("Unable to find site with id $siteId");
        }

        return new CurrentSite($site);
    }

    /**
     * @throws PropelException
     */
    public function getOption(string $key, string $defaultValue = null): ?string
    {
        $option = OptionQuery::create()
            ->filterBySite($this->site)
            ->filterByKey($key)
            ->findOne();

        if ($option) {
            return $option->getValue();
        }

        if ($defaultValue) {
            return $defaultValue;
        }

        return $this->_getDefaultValueFor($key);
    }

    private function _getDefaultValueFor(string $key): string|null {
        if ($key === "articles_per_page") {
            return "10";
        }

        return null;
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

    /**
     * @throws PropelException
     */
    public function allowsPublisher(Publisher $publisher): bool
    {
        $publisherFilter = $this->getOption("publisher_filter");
        if ($publisherFilter === null) {
            return true;
        }

        $publisherIds = explode(",", $publisherFilter);
        if (in_array($publisher->getId(), $publisherIds)) {
            return true;
        }

        return false;
    }


}