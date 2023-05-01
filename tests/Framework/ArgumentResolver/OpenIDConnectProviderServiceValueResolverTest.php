<?php

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
