<?php

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;

// Get routes from src/routes.yml
$locator = new FileLocator([__DIR__]);
$loader = new YamlFileLoader($locator);
return $loader->load('routes.yml');