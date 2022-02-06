<?php

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
