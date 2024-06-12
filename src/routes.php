<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;

// Get routes from src/routes.yml
$locator = new FileLocator(array(BIBLYS_PATH."/src"));
$loader = new YamlFileLoader($locator);
$routes = $loader->load('routes.yml');

return $routes;
