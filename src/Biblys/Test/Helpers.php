<?php
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


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
        $currentSite->expects("getSite")->andReturn(ModelFactory::createSite());
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("isAuthenticated")->andReturn(false);
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