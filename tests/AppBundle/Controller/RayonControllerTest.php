<?php

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

use AppBundle\Controller\RayonController;
use Biblys\Test\EntityFactory;
use Biblys\Test\RequestFactory;
use Framework\Exception\AuthException;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

require_once __DIR__."/../../setUp.php";

class RayonControllerTest extends PHPUnit\Framework\TestCase
{
    public function testRayonShow()
    {
        // given
        $rayon = EntityFactory::createRayon();
        $controller = new RayonController();
        $request = new Request();
        $request->query->set("p", "3");

        // when
        $response = $controller->showAction($request, $rayon->get("url"));

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should return HTTP 200"
        );
        $this->assertStringContainsString(
            $rayon->get("name"),
            $response->getContent(),
            "it should contain rayon name"
        );
    }

    public function testRayonShowWithInvalidPageNumber()
    {
        // then
        $this->expectException("Symfony\Component\HttpKernel\Exception\BadRequestHttpException");
        $this->expectExceptionMessage("Page number must be a positive integer");

        // given
        $rayon = EntityFactory::createRayon();
        $controller = new RayonController();
        $request = new Request();
        $request->query->set("p", "-1690");

        // when
        $controller->showAction($request, $rayon->get("url"));
    }

    /**
     * @throws AuthException
     * @throws PropelException
     * @throws Exception
     */
    public function testRayonArticles()
    {
        // given
        $rayon = EntityFactory::createRayon();
        $article = EntityFactory::createArticle(["article_title" => "Article en rayon"]);
        $rayon->addArticle($article);
        $controller = new RayonController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $this->_mockContainerWithUrlGenerator();

        // when
        $response = $controller->rayonArticlesAction($request, $rayon->get("id"));

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should return HTTP 200"
        );
        $this->assertStringContainsString(
            "Article en rayon",
            $response->getContent(),
            "it should contain article title"
        );
    }

    /**
     * @return void
     */
    public function _mockContainerWithUrlGenerator(): void
    {
        $urlgenerator = $this->createMock(UrlGeneratorInterface::class);
        $GLOBALS["container"] = $this->createMock(ContainerInterface::class);
        $GLOBALS["container"]->method("get")->willReturn($urlgenerator);
    }
}