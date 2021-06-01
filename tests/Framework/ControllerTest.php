<?php

namespace Framework;

use Biblys\Test\Factory;
use Biblys\Test\Helpers;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__."/../setUp.php";

class ControllerTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testAuthAdminForAnonymousUser()
    {
        // then
        $this->expectException("Framework\Exception\AuthException");
        $this->expectExceptionMessage("Identification requise.");

        // given
        $controller = new Controller();
        $request = new Request();

        // when
        Helpers::callPrivateMethod($controller, "authAdmin", [$request]);
    }

    /**
     * @throws ReflectionException
     * @throws PropelException
     */
    public function testAuthAdminForSimpleUser()
    {
        // then
        $this->expectException("Framework\Exception\AuthException");
        $this->expectExceptionMessage("Accès réservé aux administrateurs.");

        // given
        $controller = new Controller();
        $request = Factory::createAuthRequest();

        // when
        Helpers::callPrivateMethod($controller, "authAdmin", [$request]);
    }

    /**
     * @throws ReflectionException
     * @throws PropelException
     */
    public function testAuthAdminForAdminUser()
    {
        // then
        $this->expectNotToPerformAssertions();

        // given
        $controller = new Controller();
        $request = Factory::createAuthRequestForAdminUser();

        // when
        Helpers::callPrivateMethod($controller, "authAdmin", [$request]);
    }
}
