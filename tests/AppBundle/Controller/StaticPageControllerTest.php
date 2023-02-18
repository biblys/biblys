<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentSite;
use Biblys\Test\ModelFactory;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__."/../../setUp.php";

class StaticPageControllerTest extends TestCase
{
    /**
     * @throws PropelException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testShowAction()
    {
        // given
        $controller = new StaticPageController();
        ModelFactory::createPage([
            "page_title" => "Mentions légales",
            "page_url" => "mentions-legales",
            "content" => "Merci de lire attentivement le texte suivant."
        ]);
        $currentSite = $this->createMock(CurrentSite::class);
        $currentSite->method("getId")->willReturn(1);

        // when
        $response = $controller->showAction($currentSite, "mentions-legales");

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "responds with http status 400",
        );
        $this->assertStringContainsString(
            "Mentions légales",
            $response->getContent(),
            "inserts static page title in body"
        );
        $this->assertStringContainsString(
            "Merci de lire attentivement le texte suivant.",
            $response->getContent(),
            "insert static page content in body"
        );
    }
}
