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

use Biblys\Service\BodyParamsService;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\FlashMessagesService;
use Biblys\Service\TemplateService;
use Framework\Controller;
use Model\Redirection;
use Model\RedirectionQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class RedirectionController extends Controller
{
    /**
     * @throws PropelException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function indexAction(
        CurrentUser     $currentUser,
        CurrentSite     $currentSite,
        TemplateService $templateService,
    ): Response
    {
        $currentUser->authAdmin();

        $redirections = RedirectionQuery::create()
            ->filterBySiteId($currentSite->getId())
            ->orderByOldUrl()
            ->find();

        return $templateService->renderResponse(
            "AppBundle:Redirection:index.html.twig",
            ["redirections" => $redirections]
        );
    }

    /**
     * @throws PropelException
     */
    public function createAction(
        CurrentUser $currentUser,
        CurrentSite $currentSite,
        BodyParamsService $bodyParamsService,
        FlashMessagesService $flashMessagesService,
        UrlGenerator $urlGenerator
    ): RedirectResponse
    {
        $currentUser->authAdmin();

        $bodyParamsService->parse([
            "old_url" => ["type" => "string"],
            "new_url" => ["type" => "string"]
        ]);

        $existingRedirectionForOldUrl = RedirectionQuery::create()
            ->filterBySiteId($currentSite->getId())
            ->filterByOldUrl($bodyParamsService->get("old_url"))
            ->findOne();

        if (!$existingRedirectionForOldUrl) {
            $redirection = new Redirection();
            $redirection->setSiteId($currentSite->getId());
            $redirection->setOldUrl($bodyParamsService->get("old_url"));
        } else {
            $redirection = $existingRedirectionForOldUrl;
        }

        $redirection->setNewUrl($bodyParamsService->get("new_url"));
        $redirection->save();

        $flashMessagesService->add("success", "La redirection de « {$redirection->getOldUrl()} » vers « {$redirection->getNewUrl()} » a été créée.");

        $redirectionsUrl = $urlGenerator->generate("redirection_index");
        return new RedirectResponse($redirectionsUrl, 302);
    }
}
