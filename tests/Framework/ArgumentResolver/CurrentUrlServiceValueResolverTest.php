<?php

namespace Framework\ArgumentResolver;

use Biblys\Service\CurrentUrlService;
use Exception;
use Framework\Controller;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class CurrentUrlServiceValueResolverTest extends TestCase
{
    public function testSupportsForAxysValue()
    {
        // given
        $axysValueResolver = new CurrentUrlServiceValueResolver();
        $request = new Request();
        $argumentMetadata = new ArgumentMetadata("null", CurrentUrlService::class, false, false, null);

        // when
        $supports = $axysValueResolver->supports($request, $argumentMetadata);

        // then
        $this->assertTrue($supports);
    }

    public function testSupportsForOtherValue()
    {
        // given
        $axysValueResolver = new CurrentUrlServiceValueResolver();
        $request = new Request();
        $argumentMetadata = new ArgumentMetadata("null", Controller::class, false, false, null);

        // when
        $supports = $axysValueResolver->supports($request, $argumentMetadata);

        // then
        $this->assertFalse($supports);
    }

    /**
     * @throws Exception
     */
    public function testResolve()
    {
        // given
        $axysValueResolver = new CurrentUrlServiceValueResolver();
        $request = new Request();
        $argumentMetadata = new ArgumentMetadata("null", CurrentUrlService::class, false, false, null);

        // when
        $axysGenerator = $axysValueResolver->resolve($request, $argumentMetadata);

        // then
        $this->assertInstanceOf(CurrentUrlService::class, $axysGenerator->current());
    }
}
