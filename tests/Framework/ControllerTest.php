<?php

namespace Framework;

use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
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

    /**
     * @throws ReflectionException
     */
    public function testAuthUserForAnonymousUser()
    {
        // then
        $this->expectException(UnauthorizedHttpException::class);
        $this->expectExceptionMessage("Identification requise.");

        // given
        $controller = new Controller();
        $request = new Request();

        // when
        Helpers::callPrivateMethod($controller, "authUser", [$request]);
    }

    /**
     * @throws ReflectionException
     * @throws PropelException
     */
    public function testAuthUserForAuthentifiedUser()
    {
        // then
        $this->expectNotToPerformAssertions();

        // given
        $controller = new Controller();
        $request = RequestFactory::createAuthRequest();

        // when
        Helpers::callPrivateMethod($controller, "authUser", [$request]);
    }

    /**
     * @throws ReflectionException
     */
    public function testAuthAdminForAnonymousUser()
    {
        // then
        $this->expectException(UnauthorizedHttpException::class);
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
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage("Accès réservé aux administrateurs.");

        // given
        $controller = new Controller();
        $request = RequestFactory::createAuthRequest();

        // when
        Helpers::callPrivateMethod($controller, "authAdmin", [$request]);
    }

    /**
     * @throws ReflectionException
     * @throws PropelException
     */
    public function testAuthAdminForWithCustomMessage()
    {
        // then
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage("Un message personnalisé.");

        // given
        $controller = new Controller();
        $request = RequestFactory::createAuthRequest();

        // when
        Helpers::callPrivateMethod($controller, "authAdmin", [$request, "Un message personnalisé."]);
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
        $request = RequestFactory::createAuthRequestForAdminUser();

        // when
        Helpers::callPrivateMethod($controller, "authAdmin", [$request]);
    }

    /**
     * @throws ReflectionException
     * @throws PropelException
     */
    public function testAuthPublisherForUserAuthorizedForGivenPublisher()
    {
        // then
        $this->expectNotToPerformAssertions();

        // given
        $publisher = ModelFactory::createPublisher();
        $controller = new Controller();
        $request = RequestFactory::createAuthRequestForPublisherUser($publisher);

        // when
        Helpers::callPrivateMethod($controller, "authPublisher", [$request, $publisher]);
    }

    /**
     * @throws ReflectionException
     * @throws PropelException
     */
    public function testAuthPublisherForAdminUser()
    {
        // then
        $this->expectNotToPerformAssertions();

        // given
        $publisher = ModelFactory::createPublisher();
        $controller = new Controller();
        $request = RequestFactory::createAuthRequestForAdminUser();

        // when
        Helpers::callPrivateMethod($controller, "authPublisher", [$request, $publisher]);
    }

    /**
     * @throws ReflectionException
     * @throws PropelException
     */
    public function testAuthPublisherForUserAuthorizedForOtherPublisher()
    {
        // then
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage("Vous n'avez pas l'autorisation de modifier l'éditeur Éditeur interdit");

        // given
        $userPublisher = ModelFactory::createPublisher();
        $otherPublisher = ModelFactory::createPublisher(name: "Éditeur interdit");
        $controller = new Controller();
        $request = RequestFactory::createAuthRequestForPublisherUser($userPublisher);

        // when
        Helpers::callPrivateMethod($controller, "authPublisher", [$request, $otherPublisher]);
    }

    /**
     * @throws ReflectionException
     * @throws PropelException
     */
    public function testAuthPublisherForUserWithNoPublisherRight()
    {
        // then
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage("Vous n'avez pas l'autorisation de modifier l'éditeur Éditeur interdit");

        // given
        $publisher = ModelFactory::createPublisher(name: "Éditeur interdit");
        $controller = new Controller();
        $request = RequestFactory::createAuthRequest();

        // when
        Helpers::callPrivateMethod($controller, "authPublisher", [$request, $publisher]);
    }

    /**
     * @throws ReflectionException
     * @throws PropelException
     */
    public function testAuthPublisherWhenPublisherIsNull()
    {
        // then
        $this->expectNotToPerformAssertions();

        // given
        $publisher = ModelFactory::createPublisher();
        $controller = new Controller();
        $request = RequestFactory::createAuthRequestForPublisherUser($publisher);

        // when
        Helpers::callPrivateMethod($controller, "authPublisher", [$request, null]);
    }

    /**
     * @throws ReflectionException
     * @throws PropelException
     */
    public function testAuthPublisherWhenPublisherIsNullForUserWithNoPublisherRight()
    {
        // then
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage("Vous n'avez pas l'autorisation de modifier un éditeur");

        // given
        $controller = new Controller();
        $request = RequestFactory::createAuthRequest();

        // when
        Helpers::callPrivateMethod($controller, "authPublisher", [$request, null]);
    }
}
