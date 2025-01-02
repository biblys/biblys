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


use Biblys\Service\Cloud\CloudService;
use Biblys\Service\Config;
use Framework\ArgumentResolver\BiblysCloudValueResolver;
use Framework\ArgumentResolver\BodyParamsServiceValueResolver;
use Framework\ArgumentResolver\CacheServiceValueResolver;
use Framework\ArgumentResolver\ConfigValueResolver;
use Framework\ArgumentResolver\CurrentSiteValueResolver;
use Framework\ArgumentResolver\CurrentUrlServiceValueResolver;
use Framework\ArgumentResolver\CurrentUserValueResolver;
use Framework\ArgumentResolver\FlashMessagesServiceValueResolver;
use Framework\ArgumentResolver\ImagesServiceValueResolver;
use Framework\ArgumentResolver\LoggerServiceValueResolver;
use Framework\ArgumentResolver\MailerValueResolver;
use Framework\ArgumentResolver\MailingListServiceValueResolver;
use Framework\ArgumentResolver\MetaTagsServiceValueResolver;
use Framework\ArgumentResolver\OpenIDConnectProviderServiceValueResolver;
use Framework\ArgumentResolver\QueryParamsServiceValueResolver;
use Framework\ArgumentResolver\SessionValueResolver;
use Framework\ArgumentResolver\TemplateServiceValueResolver;
use Framework\ArgumentResolver\TokenServiceValueResolver;
use Framework\ArgumentResolver\UrlGeneratorValueResolver;
use Framework\ArgumentResolver\WatermarkingServiceValueResolver;
use Framework\RequestListener;
use Framework\RouteLoader;
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

$routes = RouteLoader::load();


$container->register("context", RequestContext::class);
$container->register("matcher", UrlMatcher::class)
    ->setArguments([$routes, new Reference("context")]);
$container->register("request_stack", RequestStack::class);

$container->register("controller_resolver", ControllerResolver::class);

$argumentResolvers = ArgumentResolver::getDefaultArgumentValueResolvers();
$argumentResolvers[] = new BiblysCloudValueResolver();
$argumentResolvers[] = new BodyParamsServiceValueResolver();
$argumentResolvers[] = new CacheServiceValueResolver();
$argumentResolvers[] = new ConfigValueResolver();
$argumentResolvers[] = new CurrentSiteValueResolver();
$argumentResolvers[] = new CurrentUrlServiceValueResolver();
$argumentResolvers[] = new CurrentUserValueResolver();
$argumentResolvers[] = new LoggerServiceValueResolver();
$argumentResolvers[] = new FlashMessagesServiceValueResolver();
$argumentResolvers[] = new ImagesServiceValueResolver();
$argumentResolvers[] = new MailerValueResolver();
$argumentResolvers[] = new MailingListServiceValueResolver();
$argumentResolvers[] = new MetaTagsServiceValueResolver();
$argumentResolvers[] = new OpenIDConnectProviderServiceValueResolver();
$argumentResolvers[] = new QueryParamsServiceValueResolver();
$argumentResolvers[] = new SessionValueResolver();
$argumentResolvers[] = new TemplateServiceValueResolver();
$argumentResolvers[] = new TokenServiceValueResolver();
$argumentResolvers[] = new UrlGeneratorValueResolver();
$argumentResolvers[] = new WatermarkingServiceValueResolver();
$container->register("argument_resolver", ArgumentResolver::class)
    ->setArguments([null, $argumentResolvers]);

$container->register("listener.router", RouterListener::class)
    ->setArguments([new Reference("matcher"), new Reference("request_stack")]);
$container->register("listener.request", RequestListener::class);

$container->register("dispatcher", EventDispatcher::class)
    ->addMethodCall("addSubscriber", [new Reference("listener.router")])
    ->addMethodCall("addListener", [KernelEvents::REQUEST, [new Reference("listener.request"), "onUnsecureRequest"], 1]);

$container->register("framework", HttpKernel::class)
    ->setArguments([
        new Reference("dispatcher"),
        new Reference("controller_resolver"),
        new Reference("request_stack"),
        new Reference("argument_resolver"),
    ]);

$container->register("config", Config::class);
$container->register("biblys_cloud", CloudService::class)
    ->setArguments([new Reference("config")]);

$container->register("url_generator", UrlGenerator::class)
    ->setArguments([$routes, new Reference("context")]);

return $container;