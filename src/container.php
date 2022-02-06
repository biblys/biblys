<?php

use Biblys\Service\Config;
use Biblys\Service\Updater\Updater;
use Framework\ArgumentResolver\ConfigValueResolver;
use Framework\ArgumentResolver\CurrentSiteValueResolver;
use Framework\ArgumentResolver\CurrentUserValueResolver;
use Framework\ArgumentResolver\SessionValueResolver;
use Framework\ArgumentResolver\MailerValueResolver;
use Framework\ArgumentResolver\UpdaterValueResolver;
use Framework\RequestListener;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

$container = new ContainerBuilder();

$container->register("context", RequestContext::class);
$container->register("matcher", UrlMatcher::class)
    ->setArguments(["%routes%", new Reference("context")]);
$container->register("request_stack", RequestStack::class);

$container->register("controller_resolver", ControllerResolver::class);

$argumentResolvers = ArgumentResolver::getDefaultArgumentValueResolvers();
$argumentResolvers[] = new ConfigValueResolver();
$argumentResolvers[] = new CurrentSiteValueResolver();
$argumentResolvers[] = new CurrentUserValueResolver();
$argumentResolvers[] = new MailerValueResolver();
$argumentResolvers[] = new SessionValueResolver();
$argumentResolvers[] = new UpdaterValueResolver();
$container->register("argument_resolver", ArgumentResolver::class)
    ->setArguments([null, $argumentResolvers]);

$container->register("listener.router", RouterListener::class)
    ->setArguments([new Reference("matcher"), new Reference("request_stack")]);
$container->register("listener.request", RequestListener::class);

$container->register("dispatcher", EventDispatcher::class)
    ->addMethodCall("addSubscriber", [new Reference("listener.router")])
    ->addMethodCall("addListener", [KernelEvents::REQUEST, [new Reference("listener.request"), "onUnsecureRequest"], 1])
    ->addMethodCall("addListener", [KernelEvents::REQUEST, [new Reference("listener.request"), "onReturningFromAxysRequest"], 1]);

$container->register("framework", HttpKernel::class)
    ->setArguments([
        new Reference("dispatcher"),
        new Reference("controller_resolver"),
        new Reference("request_stack"),
        new Reference("argument_resolver"),
    ]);

$container->register("config", Config::class);
$container->register("updater", Updater::class)
    ->setArguments([BIBLYS_PATH, BIBLYS_VERSION, new Reference("config")]);

// TODO: also add api routes
$routes = require __DIR__ . "/../src/AppBundle/routes.php";
$container->setParameter("routes", $routes);
$container->register("url_generator", UrlGenerator::class)
    ->setArguments(["%routes%", new Reference("context")]);

return $container;