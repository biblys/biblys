<?php

namespace AppBundle\Controller;

use Biblys\Article\Type;
use Biblys\Service\CurrentSite;
use Biblys\Service\TemplateService;
use Biblys\Test\ModelFactory;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__."/../../setUp.php";

class InvitationControllerTest extends TestCase
{

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     */
    public function testNewAction()
    {
        // given
        $publisher = ModelFactory::createPublisher();
        ModelFactory::createArticle(publisher: $publisher, title: "Book");
        $ebook = ModelFactory::createArticle(publisher: $publisher, title: "E-book", typeId: Type::EBOOK);
        $controller = new InvitationController();
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $currentSite->setOption("publisher_filter", $publisher->getId());
        $templateService = $this->createMock(TemplateService::class);
        $templateService
            ->expects($this->once())
            ->method("render")
            ->with("AppBundle:Invitation:new.html.twig", [
                "downloadableArticles" => [$ebook],
            ])
            ->willReturn(new Response("Créer une invitation de téléchargement"));

        // when
        $response = $controller->newAction($currentSite, $templateService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString(
            "Créer une invitation de téléchargement",
            $response->getContent()
        );
    }
}
