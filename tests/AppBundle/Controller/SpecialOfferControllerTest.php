<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentSite;
use Biblys\Service\TemplateService;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use Mockery;
use Model\ArticleQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__ . "/../../setUp.php";

class SpecialOfferControllerTest extends TestCase
{
    /* INDEX ACTION */

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function testIndexActionDisplayOffers()
    {
        // given
        $specialOfferController = new SpecialOfferController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $site = ModelFactory::createSite();
        $offer = ModelFactory::createSpecialOffer(site: $site);
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $templateService = Mockery::mock(TemplateService::class);
        $templateService
            ->shouldReceive("renderResponse")
            ->with('AppBundle:SpecialOffer:index.html.twig', [
                'offers' => [$offer],
            ])
            ->andReturn(new Response());

        // when
        $response = $specialOfferController->indexAction($request, $currentSite, $templateService);

        // then
        $this->assertEquals(200, $response->getStatusCode());
    }

    /* EDIT ACTION */

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function testEditActionReturns404(): void
    {
        // given
        $specialOfferController = new SpecialOfferController();
        $request = RequestFactory::createAuthRequestForAdminUser();
        $site = ModelFactory::createSite();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $templateService = Mockery::mock(TemplateService::class);

        // then
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage("Special offer not found");

        // when
        $specialOfferController->editAction($request, $currentSite, $templateService, 999);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function testEditActionReturns200(): void
    {
        // given
        $specialOfferController = new SpecialOfferController();

        $site = ModelFactory::createSite();
        $publisher = ModelFactory::createPublisher();
        $collection = ModelFactory::createCollection(publisher: $publisher);
        $article = ModelFactory::createArticle(publisher: $publisher, collection: $collection);
        $offer = ModelFactory::createSpecialOffer(site: $site, targetCollection: $collection, freeArticle: $article);

        $request = RequestFactory::createAuthRequestForAdminUser();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentSite->shouldReceive("getOption")->with("publisher_filter")
            ->andReturn($publisher->getId());

        $givenResponse = new Response();
        $expectedArticle = ArticleQuery::create()
            ->select(["id", "titleAlphabetic"])
            ->findPk($article->getId());
        $templateService = Mockery::mock(TemplateService::class);
        $templateService
            ->shouldReceive("renderResponse")
            ->with("AppBundle:SpecialOffer:edit.html.twig", [
                "collections" => [$collection],
                "articles" => [$expectedArticle],
                "offer" => $offer
            ])
            ->andReturn($givenResponse);

        // when
        $response = $specialOfferController->editAction(
            $request,
            $currentSite,
            $templateService,
            id: $offer->getId(),
        );

        // then
        $this->assertEquals($response, $givenResponse);
    }
}