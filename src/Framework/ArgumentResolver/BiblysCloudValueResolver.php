<?php

namespace Framework\ArgumentResolver;

use Biblys\Service\Cloud\CloudService;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class BiblysCloudValueResolver implements ArgumentValueResolverInterface
{

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if ($argument->getType() !== CloudService::class) {
            return false;
        }

        return true;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        $container = include __DIR__."/../../container.php";
        yield $container->get("biblys_cloud");
    }
}
