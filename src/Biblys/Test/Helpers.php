<?php

namespace Biblys\Test;

use Exception;
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
}