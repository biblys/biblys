<?php

namespace Biblys\Test;

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\MetaTagsService;
use Biblys\Service\TemplateService;
use Exception;
use Mockery;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;

class Helpers
{
    /**
     * @param object $object
     * @param string $methodName
     * @param array $args
     * @throws ReflectionException
     */

    public static function callPrivateMethod(object $object, string $methodName, array $args): void
    {
        $class = new ReflectionClass(get_class($object));
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);
        $method->invokeArgs($object, $args);
    }

    /**
     * @throws Exception
     */
    public static function runAndCatchException(callable $function): ?Exception
    {
        try {
            $function();
        } catch (Exception $exception) {
            return $exception;
        }

        throw new Exception("Excepted function to throw an exception, but none was.");
    }

    /**
     * @return TemplateService
     * @throws Exception
     */
    public static function getTemplateService(): TemplateService
    {
        $config = new Config(["environment" => "test"]);
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->expects("getTitle")->andReturn("Éditions Paronymie");
        $currentSite->expects("getOption")->andReturn(null);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("isAuthentified")->andReturn(false);
        $currentUser->expects("isAdmin")->andReturn(false);
        $currentUser->expects("hasPublisherRight")->andReturn(false);
        $metaTags = Mockery::mock(MetaTagsService::class);
        $metaTags->expects("dump")->andReturn("");
        $request = new Request();

        return new TemplateService(
            config: $config,
            currentSiteService: $currentSite,
            currentUserService: $currentUser,
            metaTagsService: $metaTags,
            request: $request
        );
    }
}