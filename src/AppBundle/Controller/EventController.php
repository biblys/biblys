<?php

namespace AppBundle\Controller;

use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\TemplateService;
use EventManager;
use Framework\Controller;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class EventController extends Controller
{
    public function indexAction(Request $request): Response
    {
        $queryParams = [
            "event_status" => 1,
            "event_start" => ">= ".date("Y-m-d H:i:s", time() - 60 * 60 * 24)
        ];

        $em = new EventManager();

        // Pagination
        $page = (int) $request->query->get('p', 0);
        $totalEventCount = $em->count($queryParams);
        $eventPerPage = 10;
        $totalPages = ceil($totalEventCount / $eventPerPage) + 1;
        $offset = $page * $eventPerPage;
        $currentPage = $page + 1;
        $prevPage = $page > 0 ? $page - 1 : false;
        $nextPage = $page < $totalPages-1 ? $page + 1 : false;

        $events = $em->getAll($queryParams, [
            "order" => "event_start",
            "sort" => "asc",
            "limit" => 10,
            "offset" => $offset
        ]);

        $request->attributes->set("page_title", "Évènements");

        return $this->render('AppBundle:Event:index.html.twig', [
            'events' => $events,
            'current_page' => $currentPage,
            'prev_page' => $prevPage,
            'next_page' => $nextPage,
            'total_pages' => $totalPages,
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     */
    public function showAction(
        Request $request,
        CurrentUser $currentUser,
        CurrentSite $currentSite,
        TemplateService $templateService,
        string $slug
    ): Response
    {
        $em = new EventManager();
        $event = $em->get(["event_url" => $slug, "site_id" => $currentSite->getId()]);
        if (!$event) {
            throw new NotFoundHttpException("Event $slug not found.");
        }

        // Offline event
        if (!$this->_userCanSeeEvent($event, $currentUser)) {
            throw new NotFoundHttpException("Event $slug not published.");
        }

        return $templateService->renderResponse('AppBundle:Event:show.html.twig', [
            'event' => $event
        ]);
    }

    /**
     * @param mixed $event
     * @param CurrentUser $currentUser
     * @return bool
     * @throws PropelException
     */
    private function _userCanSeeEvent(mixed $event, CurrentUser $currentUser): bool
    {
        if ($event->get('status') === 1) {
            return true;
        }

        if (!$currentUser->isAuthentified()) {
            return false;
        }

        if ($currentUser->isAdmin()) {
            return true;
        }

        return false;
    }
}
