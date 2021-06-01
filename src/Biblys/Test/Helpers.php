<?php

namespace Biblys\Test;

use ReflectionClass;
use ReflectionException;

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
}