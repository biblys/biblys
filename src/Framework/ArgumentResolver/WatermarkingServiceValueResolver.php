<?php

namespace Framework\ArgumentResolver;

use Biblys\Service\Config;
use Biblys\Service\Mailer;
use Biblys\Service\Watermarking\WatermarkingService;
use Exception;
use Generator;
use LemonInk\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class WatermarkingServiceValueResolver implements ArgumentValueResolverInterface
{

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if ($argument->getType() !== WatermarkingService::class) {
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
        $client = new Client($config->get("lemonink.api_key"));
        $isConfigured = $config->has("lemonink.api_key");
        yield new WatermarkingService($client, $isConfigured);
    }
}