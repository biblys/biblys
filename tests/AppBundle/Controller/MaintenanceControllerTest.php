<?php

namespace AppBundle\Controller;

use Biblys\Service\Config;
use Biblys\Service\Updater\Updater;
use Biblys\Test\RequestFactory;
use Framework\Exception\AuthException;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__."/../../setUp.php";

class MaintenanceControllerTest extends TestCase
{

    /**
     * @throws AuthException
     * @throws PropelException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testUpdateAction()
    {
        // given
        $controller = new MaintenanceController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $updater = $this->createMock(Updater::class);
        $config = new Config();

        // when
        $response = $controller->updateAction($request, $updater, $config);

        // then
        $this->assertEquals(200, $response->getStatusCode());
    }
}
