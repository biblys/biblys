<?php

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

namespace AppBundle\Controller;

use Biblys\Test\EntityFactory;
use Biblys\Test\RequestFactory;
use Exception;
use Framework\Exception\AuthException;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

require_once __DIR__."/../../setUp.php";

class RayonControllerTest extends TestCase
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
     * @throws PropelException
     * @throws Exception
     */
    public function testAddArticles()
    {
        // given
        $rayon = EntityFactory::createRayon();
        $article = EntityFactory::createArticle(["article_title" => "Article en rayon"]);
        $controller = new RayonController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $request->setMethod("POST");
        $request->request->set("article_id", $article->get("id"));
        $session = $this->createMock(Session::class);
        $session->method("getFlashBag")->willReturn(new FlashBag());
        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->method("generate")->willReturn("/");

        // when
        $response = $controller->addArticleAction($request, $session, $urlGenerator, $rayon->get("id"));

        // then
        $this->assertEquals(
            302,
            $response->getStatusCode(),
            "it should return HTTP 302"
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