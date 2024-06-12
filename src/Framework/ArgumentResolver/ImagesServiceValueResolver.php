<?php

namespace Framework\ArgumentResolver;

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\Images\ImagesService;
use Exception;
use Generator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class ImagesServiceValueResolver implements ArgumentValueResolverInterface
{

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if ($argument->getType() !== ImagesService::class) {
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
        $currentSite = CurrentSite::buildFromConfig($config);
        yield new ImagesService($config, $currentSite, new Filesystem());
    }
}
