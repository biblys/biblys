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


namespace Framework;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;

class RouteLoader
{
    public static function load(): RouteCollection
    {
        $locator = new FileLocator([__DIR__]);
        $loader = new YamlFileLoader($locator);
        $webRoutes = $loader->load(__DIR__."/../AppBundle/routes.yml");
        $apiRoutes = $loader->load(__DIR__."/../ApiBundle/routes.yml");
        $webRoutes->addCollection($apiRoutes);
        return $webRoutes;
    }
}
