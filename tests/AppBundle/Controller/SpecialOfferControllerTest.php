<?php
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


namespace AppBundle\Controller;

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\TemplateService;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Exception;
use Mockery;
use Model\Article;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGenerator;
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
        $site = ModelFactory::createSite();
        $offer = ModelFactory::createSpecialOffer(site: $site);
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->andReturn();
        $templateService = Mockery::mock(TemplateService::class);
        $templateService
            ->shouldReceive("renderResponse")
            ->with('AppBundle:SpecialOffer:index.html.twig', [
                'offers' => [$offer],
            ], true)
            ->andReturn(new Response());

        // when
        $response = $specialOfferController->indexAction(
            $currentSite,
            $currentUser,
            $templateService
        );

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
        $site = ModelFactory::createSite();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->andReturn();
        $templateService = Mockery::mock(TemplateService::class);

        // then
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage("Special offer not found");

        // when
        $specialOfferController->editAction(
            $currentUser,
            $templateService,
            999
        );
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     * @throws Exception
     */
    public function testEditActionReturns200(): void
    {
        // given
        $specialOfferController = new SpecialOfferController();

        $site = ModelFactory::createSite();
        $publisher = ModelFactory::createPublisher();
        $collection = ModelFactory::createCollection(publisher: $publisher, name: "Collection spéciale");
        $article = ModelFactory::createArticle(
            title: "Livre spécial",
            publisher: $publisher,
            collection: $collection,
            availabilityDilicom: Article::AVAILABILITY_PRIVATELY_PRINTED
        );
        $offer = ModelFactory::createSpecialOffer(site: $site, targetCollection: $collection, freeArticle: $article);

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->andReturn();
        $templateService = Helpers::getTemplateService();

        // when
        $response = $specialOfferController->editAction(
            $currentUser,
            $templateService,
            id: $offer->getId(),
        );

        // then
        $this->assertStringContainsString("Modifier « Offre spéciale »", $response->getContent());
        $this->assertStringContainsString("Collection spéciale", $response->getContent());
        $this->assertStringContainsString("Livre spécial", $response->getContent());
    }

    /* UPDATE ACTION */

    /**
     * @throws PropelException
     */
    public function testUpdateActionReturns404(): void
    {
        // given
        $specialOfferController = new SpecialOfferController();
        $request = new Request();
        $site = ModelFactory::createSite();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->andReturn();
        $session = Mockery::mock(Session::class);
        $urlGenerator = Mockery::mock(UrlGenerator::class);

        // then
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage("Special offer not found");

        // when
        $specialOfferController->updateAction(
            $request,
            $currentSite,
            $currentUser,
            $session,
            $urlGenerator,
            999
        );
    }

    /**
     * @throws PropelException
     */
    public function testUpdateActionUpdatesOffer(): void
    {
        // given
        $specialOfferController = new SpecialOfferController();

        $site = ModelFactory::createSite();
        $offer = ModelFactory::createSpecialOffer(site: $site, name: "Super offre");

        $request = new Request();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin")->andReturn();
        $flashBag = Mockery::mock(FlashBag::class);
        $flashBag->shouldReceive("add")
            ->with("success", "Offre spéciale « Nouvelle offre » mise à jour avec succès")
            ->andReturn();
        $session = Mockery::mock(Session::class);
        $session->shouldReceive("getFlashBag")
            ->andReturn($flashBag);
        $urlGenerator = Mockery::mock(UrlGenerator::class);
        $urlGenerator->shouldReceive("generate")
            ->with("special_offer_edit", ["id" => $offer->getId()])
            ->andReturn("/special_offer_edit");

        // when
        $request->request->set("name", "Nouvelle offre");
        $request->request->set("description", "Description de la nouvelle offre");
        $request->request->set("start_date", "2021-01-01");
        $request->request->set("end_date", "2021-01-31");
        $request->request->set("target_quantity", "3");
        $request->request->set("target_collection_id", "999");
        $request->request->set("free_article_id", "9999");
        $response = $specialOfferController->updateAction(
            $request,
            $currentSite,
            $currentUser,
            $session,
            $urlGenerator,
            id: $offer->getId()
        );

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/special_offer_edit", $response->getTargetUrl());

        $offer->reload();
        $this->assertEquals("Nouvelle offre", $offer->getName());
        $this->assertEquals("Description de la nouvelle offre", $offer->getDescription());
        $this->assertEquals("2021-01-01", $offer->getStartDate()->format("Y-m-d"));
        $this->assertEquals("2021-01-31", $offer->getEndDate()->format("Y-m-d"));
        $this->assertEquals(999, $offer->getTargetCollectionId());
        $this->assertEquals(9999, $offer->getFreeArticleId());
    }
}