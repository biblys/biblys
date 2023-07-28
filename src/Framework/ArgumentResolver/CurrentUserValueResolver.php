<?php

namespace Framework\ArgumentResolver;

use Biblys\Service\Config;
use Biblys\Service\CurrentUser;
use Exception;
use Generator;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class CurrentUserValueResolver implements ArgumentValueResolverInterface
{

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if ($argument->getType() !== CurrentUser::class) {
            return false;
        }

        return true;
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        yield CurrentUser::buildFromRequestAndConfig($request, Config::load());
    }
}