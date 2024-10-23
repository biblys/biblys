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