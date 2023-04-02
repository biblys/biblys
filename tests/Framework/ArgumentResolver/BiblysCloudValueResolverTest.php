<?php

namespace Framework\ArgumentResolver;

use Biblys\Service\Cloud\CloudService;
use Framework\Controller;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class BiblysCloudValueResolverTest extends TestCase
{
    public function testSupportsForAxysValue()
    {
        // given
        $axysValueResolver = new BiblysCloudValueResolver();
        $request = new Request();
        $argumentMetadata = new ArgumentMetadata("null", CloudService::class, false, false, null);

        // when
        $supports = $axysValueResolver->supports($request, $argumentMetadata);

        // then
        $this->assertTrue($supports);
    }

    public function testSupportsForOtherValue()
    {
        // given
        $axysValueResolver = new BiblysCloudValueResolver();
        $request = new Request();
        $argumentMetadata = new ArgumentMetadata("null", Controller::class, false, false, null);

        // when
        $supports = $axysValueResolver->supports($request, $argumentMetadata);

        // then
        $this->assertFalse($supports);
    }

    public function testResolve()
    {
        // given
        $axysValueResolver = new BiblysCloudValueResolver();
        $request = new Request();
        $argumentMetadata = new ArgumentMetadata("null", CloudService::class, false, false, null);

        // when
        $axysGenerator = $axysValueResolver->resolve($request, $argumentMetadata);

        // then
        $this->assertInstanceOf(CloudService::class, $axysGenerator->current());
    }
}
