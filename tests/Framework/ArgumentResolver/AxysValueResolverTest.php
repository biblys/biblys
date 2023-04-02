<?php

namespace Framework\ArgumentResolver;

use Biblys\Service\Axys;
use Framework\Controller;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class AxysValueResolverTest extends TestCase
{
    public function testSupportsForAxysValue()
    {
        // given
        $axysValueResolver = new AxysValueResolver();
        $request = new Request();
        $argumentMetadata = new ArgumentMetadata("null", Axys::class, false, false, null);

        // when
        $supports = $axysValueResolver->supports($request, $argumentMetadata);

        // then
        $this->assertTrue($supports);
    }

    public function testSupportsForOtherValue()
    {
        // given
        $axysValueResolver = new AxysValueResolver();
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
        $axysValueResolver = new AxysValueResolver();
        $request = new Request();
        $argumentMetadata = new ArgumentMetadata("null", Axys::class, false, false, null);

        // when
        $axysGenerator = $axysValueResolver->resolve($request, $argumentMetadata);

        // then
        $this->assertInstanceOf(Axys::class, $axysGenerator->current());
    }
}
