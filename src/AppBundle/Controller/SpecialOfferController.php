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
use Exception;
use Framework\Controller;
use Model\Article;
use Model\ArticleQuery;
use Model\BookCollectionQuery;
use Model\SpecialOffer;
use Model\SpecialOfferQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class SpecialOfferController extends Controller
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     * @throws Exception
     */
    public function indexAction(
        CurrentSite $currentSite,
        CurrentUser $currentUser,
        TemplateService $templateService
    ): Response
    {
        $currentUser->authAdmin();

        $offers = SpecialOfferQuery::create()
            ->find();

        return $templateService->renderResponse('AppBundle:SpecialOffer:index.html.twig', [
            'offers' => $offers->getArrayCopy(),
        ], isPrivate: true);
    }

    /**
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function newAction(
        CurrentUser $currentUser,
        TemplateService $templateService,
    ): Response
    {
        $currentUser->authAdmin();

        $collections = BookCollectionQuery::create()
            ->orderByName()
            ->find();

        return $templateService->renderResponse(
            "AppBundle:SpecialOffer:new.html.twig", [
                "offer" => new SpecialOffer(),
                "collections" => $collections->getArrayCopy(),
        ], isPrivate: true);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function createAction(
        Request $request,
        CurrentUser $currentUser,
        Session $session,
        UrlGenerator $urlGenerator,
    ): RedirectResponse
    {
        $currentUser->authAdmin();

        $offer = new SpecialOffer();

        $fieldsWereApplied = $this->applyOfferFieldsFromRequest($offer, $request);
        if (!$fieldsWereApplied) {
            $session->getFlashBag()->add(
                "error",
                "Vous devez activer au moins une condition (montant ou quantité)."
            );
            $newUrl = $urlGenerator->generate("special_offer_new");
            return new RedirectResponse($newUrl);
        }

        $offer->save();

        $session->getFlashBag()->add(
            "success",
            "Offre spéciale « {$offer->getName()} » créée avec succès"
        );
        $editUrl = $urlGenerator->generate("special_offer_edit", ["id" => $offer->getId()]);
        return new RedirectResponse($editUrl);
    }

    /**
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function editAction(
        CurrentUser $currentUser,
        TemplateService $templateService,
        int $id,
    ): Response
    {
        $currentUser->authAdmin();

        $offer = SpecialOfferQuery::create()
            ->findOneById($id);

        if (!$offer) {
            throw new NotFoundHttpException("Special offer not found");
        }

        $collections = BookCollectionQuery::create()
            ->orderByName()
            ->find();

        return $templateService->renderResponse(
            "AppBundle:SpecialOffer:edit.html.twig", [
                "offer" => $offer,
                "collections" => $collections->getArrayCopy(),
        ], isPrivate: true);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function updateAction(
        Request $request,
        CurrentSite $currentSite,
        CurrentUser $currentUser,
        Session $session,
        UrlGenerator $urlGenerator,
        int $id,
    ): RedirectResponse
    {
        $currentUser->authAdmin();

        $offer = SpecialOfferQuery::create()
            ->findOneById($id);

        if (!$offer) {
            throw new NotFoundHttpException("Special offer not found");
        }

        $fieldsWereApplied = $this->applyOfferFieldsFromRequest($offer, $request);
        if (!$fieldsWereApplied) {
            $session->getFlashBag()->add(
                "error",
                "Vous devez activer au moins une condition (montant ou quantité)."
            );
            $editUrl = $urlGenerator->generate("special_offer_edit", ["id" => $offer->getId()]);
            return new RedirectResponse($editUrl);
        }

        $offer->save();

        $session->getFlashBag()->add(
            "success",
            "Offre spéciale « {$offer->getName()} » mise à jour avec succès"
        );
        $indexUrl = $urlGenerator->generate("special_offer_edit", ["id" => $offer->getId()]);
        return new RedirectResponse($indexUrl);
    }

    private function applyOfferFieldsFromRequest(SpecialOffer $offer, Request $request): bool
    {
        $targetAmountEnabled = (bool) $request->request->get("target_amount_enabled");
        $targetQuantityEnabled = (bool) $request->request->get("target_quantity_enabled");

        if (!$targetAmountEnabled && !$targetQuantityEnabled) {
            return false;
        }

        $offer->setName($request->request->get("name"));
        $offer->setDescription($request->request->get("description"));
        $offer->setStartDate($request->request->get("start_date"));
        $offer->setEndDate($request->request->get("end_date"));
        $offer->setTargetAmount(
            $targetAmountEnabled ? (int) round(((float) $request->request->get("target_amount")) * 100) : null
        );
        $offer->setTargetQuantity($targetQuantityEnabled ? (int) $request->request->get("target_quantity") : null);
        $offer->setTargetCollectionId($targetQuantityEnabled ? (int) $request->request->get("target_collection_id") : null);
        $offer->setFreeArticleId($request->request->get("free_article_id"));

        return true;
    }
}