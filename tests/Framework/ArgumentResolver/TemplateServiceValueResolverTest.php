<?php

namespace Framework\ArgumentResolver;

use Biblys\Service\TemplateService;
use Framework\Controller;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class TemplateServiceValueResolverTest extends TestCase
{
    public function testSupportsForTemplateServiceValue()
    {
        // given
        $templateServiceValueResolver = new TemplateServiceValueResolver();
        $request = new Request();
        $argumentMetadata = new ArgumentMetadata("null", TemplateService::class, false, false, null);

        // when
        $supports = $templateServiceValueResolver->supports($request, $argumentMetadata);

        // then
        $this->assertTrue($supports);
    }

    public function testSupportsForOtherValue()
    {
        // given
        $templateServiceValueResolver = new TemplateServiceValueResolver();
        $request = new Request();
        $argumentMetadata = new ArgumentMetadata("null", Controller::class, false, false, null);

        // when
        $supports = $templateServiceValueResolver->supports($request, $argumentMetadata);

        // then
        $this->assertFalse($supports);
    }

    public function testResolve()
    {
        // given
        $templateServiceValueResolver = new TemplateServiceValueResolver();
        $request = new Request();
        $argumentMetadata = new ArgumentMetadata("null", TemplateService::class, false, false, null);

        // when
        $templateServiceGenerator = $templateServiceValueResolver->resolve($request, $argumentMetadata);

        // then
        $this->assertInstanceOf(TemplateService::class, $templateServiceGenerator->current());
    }
}
