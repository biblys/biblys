<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentSite;
use Biblys\Service\TemplateService;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use Mockery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Response;
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
}