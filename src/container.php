<?php

use Framework\Framework;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

$container = new ContainerBuilder();

$container->register("context", RequestContext::class);
$container->register("matcher", UrlMatcher::class)
    ->setArguments(["%routes%", new Reference("context")]);
$container->register("request_stack", RequestStack::class);

$container->register("controller_resolver", ControllerResolver::class);
$container->register("argument_resolver", ArgumentResolver::class);

$container->register("listener.router", RouterListener::class)
    ->setArguments([new Reference("matcher"), new Reference("request_stack")]);
$container->register("listener.request", \Framework\RequestListener::class);

$container->register("dispatcher", EventDispatcher::class)
    ->addMethodCall("addSubscriber", [new Reference("listener.router")])
    ->addMethodCall("addListener", [KernelEvents::REQUEST, [new Reference("listener.request"), "onUnsecureRequest"], 1])
    ->addMethodCall("addListener", [KernelEvents::REQUEST, [new Reference("listener.request"), "onReturningFromAxysRequest"], 1]);

$container->register("framework", Framework::class)
    ->setArguments([
        new Reference("dispatcher"),
        new Reference("controller_resolver"),
        new Reference("request_stack"),
        new Reference("argument_resolver"),
    ]);

return $container;