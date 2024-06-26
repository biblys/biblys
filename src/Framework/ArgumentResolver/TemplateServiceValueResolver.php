<?php

namespace Framework\ArgumentResolver;

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\MetaTagsService;
use Biblys\Service\TemplateService;
use Exception;
use Generator;
use Opengraph\Writer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class TemplateServiceValueResolver implements ArgumentValueResolverInterface
{

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if ($argument->getType() !== TemplateService::class) {
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
        $currentSiteService = CurrentSite::buildFromConfig($config);
        $currentUserService = CurrentUser::buildFromRequestAndConfig($request, $config);
        $metaTagsService = new MetaTagsService(new Writer());
        yield new TemplateService(
            config: $config,
            currentSiteService: $currentSiteService,
            currentUserService: $currentUserService,
            metaTagsService: $metaTagsService,
            request: $request,
        );
    }
}
