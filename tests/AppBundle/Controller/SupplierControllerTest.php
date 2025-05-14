<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentUser;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Exception;
use Mockery;
use Model\PublisherQuery;
use Model\SupplierQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class SupplierControllerTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function setUp(): void
    {
        PublisherQuery::create()->deleteAll();
        SupplierQuery::create()->deleteAll();
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     * @throws Exception
     */
    public function testIndexAction(): void
    {
        // given
        $controller = new SupplierController();
        $supplier = ModelFactory::createSupplier(name: "FOURNITOU");
        $publisher = ModelFactory::createPublisher(name: "PUBLITOU");
        ModelFactory::createLink(publisher: $publisher, supplier: $supplier);

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->once();
        $templateService = Helpers::getTemplateService();

        // when
        $response = $controller->indexAction($currentUser, $templateService);

        // then
        $this->assertStringContainsString("Fournisseurs", $response->getContent());
        $this->assertStringContainsString("FOURNITOU", $response->getContent());
        $this->assertStringContainsString("PUBLITOU", $response->getContent());
    }
}
