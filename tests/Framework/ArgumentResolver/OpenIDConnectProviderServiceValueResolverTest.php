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

use Biblys\Service\OpenIDConnectProviderService;
use Framework\Controller;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class OpenIDConnectProviderServiceValueResolverTest extends TestCase
{
    public function testSupportsForOpenIDConnectProviderServiceValue()
    {
        // given
        $openIDConnectProviderServiceValueResolver = new OpenIDConnectProviderServiceValueResolver();
        $request = new Request();
        $argumentMetadata = new ArgumentMetadata("null", OpenIDConnectProviderService::class, false, false, null);

        // when
        $supports = $openIDConnectProviderServiceValueResolver->supports($request, $argumentMetadata);

        // then
        $this->assertTrue($supports);
    }

    public function testSupportsForOtherValue()
    {
        // given
        $openIDConnectProviderServiceValueResolver = new OpenIDConnectProviderServiceValueResolver();
        $request = new Request();
        $argumentMetadata = new ArgumentMetadata("null", Controller::class, false, false, null);

        // when
        $supports = $openIDConnectProviderServiceValueResolver->supports($request, $argumentMetadata);

        // then
        $this->assertFalse($supports);
    }

    public function testResolve()
    {
        // given
        $openIDConnectProviderServiceValueResolver = new OpenIDConnectProviderServiceValueResolver();
        $request = new Request();
        $argumentMetadata = new ArgumentMetadata("null", OpenIDConnectProviderService::class, false, false, null);

        // when
        $openIDConnectProviderServiceGenerator = $openIDConnectProviderServiceValueResolver->resolve($request, $argumentMetadata);

        // then
        $this->assertInstanceOf(OpenIDConnectProviderService::class, $openIDConnectProviderServiceGenerator->current());
    }
}
