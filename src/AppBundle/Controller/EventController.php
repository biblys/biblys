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
use Biblys\Service\Pagination;
use Biblys\Service\QueryParamsService;
use Biblys\Service\TemplateService;
use Framework\Controller;
use Model\Event;
use Model\EventQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class EventController extends Controller
{
    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function indexAction(
        QueryParamsService $queryParams,
        CurrentSite        $currentSite,
        TemplateService    $templateService,
    ): Response
    {
        $queryParams->parse(["p" => ["type" => "numeric", "default" => 0]]);

        $eventQuery = EventQuery::create()
            ->filterBySiteId($currentSite->getSite()->getId())
            ->filterByPublished()
            ->filterByStart(time() - 60 * 60 * 24, Criteria::GREATER_EQUAL);

        $totalEventCount = $eventQuery->count();
        $page = $queryParams->getInteger("p");
        $pagination = new Pagination($page, $totalEventCount, limit: 10);

        $events = $eventQuery
            ->orderByStart()
            ->limit($pagination->getLimit())
            ->offset($pagination->getOffset())
            ->find();

        return $templateService->renderResponse("AppBundle:Event:index.html.twig", [
            "events" => $events,
            "pages" => $pagination
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     */
    public function showAction(
        CurrentUser $currentUser,
        CurrentSite $currentSite,
        TemplateService $templateService,
        string $slug
    ): Response
    {
        $event = EventQuery::create()
            ->filterByUrl($slug)
            ->filterBySiteId($currentSite->getId())
            ->findOne();
        if (!$event) {
            throw new NotFoundHttpException("Event $slug not found.");
        }

        // Offline event
        if (!$this->_userCanSeeEvent($event, $currentUser)) {
            throw new NotFoundHttpException("Event $slug not published.");
        }

        return $templateService->renderResponse("AppBundle:Event:show.html.twig", [
            "event" => $event
        ]);
    }

    /**
     * @param mixed $event
     * @param CurrentUser $currentUser
     * @return bool
     * @throws PropelException
     */
    private function _userCanSeeEvent(Event $event, CurrentUser $currentUser): bool
    {
        if ($event->isPublished()) {
            return true;
        }

        if (!$currentUser->isAuthenticated()) {
            return false;
        }

        if ($currentUser->isAdmin()) {
            return true;
        }

        return false;
    }
}
