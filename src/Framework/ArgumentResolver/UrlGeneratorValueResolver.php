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


namespace Framework\ArgumentResolver;

use Framework\RouteLoader;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;

class UrlGeneratorValueResolver implements ArgumentValueResolverInterface
{

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if ($argument->getType() !== UrlGenerator::class) {
            return false;
        }

        return true;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        $routes = RouteLoader::load();
        $requestContext = RequestContext::fromUri($request->getSchemeAndHttpHost());
        yield new UrlGenerator($routes, $requestContext);
    }
}