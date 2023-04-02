<?php

namespace Framework\ArgumentResolver;

use Biblys\Service\Cloud\CloudService;
use Biblys\Service\Config;
use Exception;
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

    /**
     * @throws Exception
     */
    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        $config = Config::load();
        yield new CloudService($config);
    }
}
