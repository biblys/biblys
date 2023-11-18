<?php

namespace AppBundle\Controller;

use Biblys\Service\Config;
use Biblys\Service\CurrentUser;
use Biblys\Service\Updater\Updater;
use Mockery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__."/../../setUp.php";

class MaintenanceControllerTest extends TestCase
{

    /**
     * @throws PropelException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testUpdateAction()
    {
        // given
        $controller = new MaintenanceController();
        $updater = $this->createMock(Updater::class);
        $config = new Config();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once()->andReturn();

        // when
        $response = $controller->updateAction($updater, $config, $currentUser);

        // then
        $this->assertEquals(200, $response->getStatusCode());
    }
}
