<?php

namespace Framework\ArgumentResolver;

use Biblys\Service\OpenIDConnectProviderService;
use Biblys\Service\Config;
use Exception;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class OpenIDConnectProviderServiceValueResolver implements ArgumentValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if ($argument->getType() !== OpenIDConnectProviderService::class) {
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
        yield new OpenIDConnectProviderService($config);
    }
}
