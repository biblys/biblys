<?php

namespace AppBundle\Controller;

use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\CurrentUser;
use Biblys\Service\TemplateService;
use EventManager;
use Framework\Controller;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        TemplateService $templateService,
        string $slug
    ): Response
    {
        global $urlgenerator;

        $globalSite = LegacyCodeHelper::getGlobalSite();

        $em = new EventManager();
        $event = $em->get(["event_url" => $slug]);
        if (!$event) {
            throw new NotFoundException("Event $slug not found.");
        }

        // Offline event
        $eventIsOffline = $event && $event->get('status') == 0;
        $userCanSeeOfflineEvent = $event->get('user_id') !== $currentUser->getUser()->getId() ||
            $currentUser->isAdmin();
        if ($eventIsOffline && !$userCanSeeOfflineEvent) {
            throw new NotFoundException("Event $slug not published.");
        }

        $request->attributes->set("page_title", $event->get("title"));

        $opengraphTags = [
            "type" => "article",
            "title" => $event->get("title"),
            "url" => "https://".$request->getHost().
                $urlgenerator->generate("event_show", ["slug" => $event->get("url")]),
            "description" => truncate(strip_tags($event->get('content')), '500', '...', true),
            "site_name" => $globalSite->get("title"),
            "locale" => "fr_FR",
            "article:published_time" => $event->get('date'),
            "article:modified_time" => $event->get('updated')
        ];

        // Get event illustration for opengraph
        if ($event->hasIllustration()) {
            $opengraphTags["image"] = $event->getIllustration()->url();
        }

        $this->setOpengraphTags($opengraphTags);

        return $templateService->renderResponse('AppBundle:Event:show.html.twig', [
            'event' => $event
        ]);
    }
}
