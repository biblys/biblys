<?php

namespace Framework\ArgumentResolver;

use Biblys\Service\Axys;
use Biblys\Service\Config;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class AxysValueResolver implements ArgumentValueResolverInterface
{

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if ($argument->getType() !== Axys::class) {
            return false;
        }

        return true;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        $config = Config::load();
        yield new Axys($config);
    }
}
