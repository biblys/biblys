<?php

namespace Framework;

use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use Model\SiteQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

require_once __DIR__."/../setUp.php";

class ControllerTest extends TestCase
{
    public function testConstructorWithUserNull()
    {
        // given
        $GLOBALS["_V"] = null;

        // when
        new Controller();

        // then
        $this->expectNotToPerformAssertions();
    }
}
