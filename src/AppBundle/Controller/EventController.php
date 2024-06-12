<?php

namespace AppBundle\Controller;

use Framework\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;

use \Exception;

class EventController extends Controller
{
    public function indexAction(Request $request)
    {
        // global $site;

        // $use_old_controller = $site->getOpt('use_old_event_controller');
        // if ($use_old_controller) {
        //     return $this->redirect('/o/blog/');
        // }

        $queryParams = [
            "event_status" => 1,
            "event_start" => ">= ".date("Y-m-d H:i:s", time() - 60 * 60 * 24)
        ];

        $pm = $this->entityManager("Event");

        // Pagination
        $page = (int) $request->query->get('p', 0);
        $totalEventCount = $pm->count($queryParams);
        $eventPerPage = 10;
        $totalPages = ceil($totalEventCount / $eventPerPage) + 1;
        $offset = $page * $eventPerPage;
        $currentPage = $page + 1;
        $prevPage = $page > 0 ? $page - 1 : false;
        $nextPage = $page < $totalPages-1 ? $page + 1 : false;

        $events = $pm->getAll($queryParams, [
            "order" => "event_start",
            "sort" => "asc",
            "limit" => 10,
            "offset" => $offset
        ]);

        $this->setPageTitle("Évènements");

        return $this->render('AppBundle:Event:index.html.twig', [
            'events' => $events,
            'current_page' => $currentPage,
            'prev_page' => $prevPage,
            'next_page' => $nextPage,
            'total_pages' => $totalPages,
        ]);
    }

    public function showAction(Request $request, $slug)
    {
        global $site, $urlgenerator;

        $use_old_controller = $site->getOpt('use_old_event_controller');
        if ($use_old_controller) {
            return $this->redirect('/o/blog/'.$slug);
        }

        $pm = $this->entityManager("Event");
        $event = $pm->get(["event_url" => $slug]);

        // Offline event
        if ($event && $event->get('status') == 0
            && $event->get('user_id') !== $this->user->get('id')
            && !$this->user->isAdmin()) {
            $event = false;
        }

        // Future event
        if ($event && $event->get('date') > date("Y-m-d H:i:s")
            && $event->get('user_id') !== $this->user->get('id')
            && !$this->user->isAdmin()) {
            $event = false;
        }

        if (!$event) {
            throw new NotFoundException("Event $slug not found.");
        }

        $use_old_controller = $site->getOpt('use_old_event_controller');
        if ($use_old_controller) {
            return $this->redirect('/event/'.$slug, 301);
        }

        $this->setPageTitle($event->get("title"));

        $opengraphTags = [
            "type" => "article",
            "title" => $event->get("title"),
            "url" => "http://".$request->getHost().
                $urlgenerator->generate("event_show", ["slug" => $event->get("url")]),
            "description" => truncate(strip_tags($event->get('content')), '500', '...', true),
            "site_name" => $site->get("title"),
            "locale" => "fr_FR",
            "article:published_time" => $event->get('date'),
            "article:modified_time" => $event->get('updated')
        ];

        // Get event illustration for opengraph
        if ($event->hasIllustration()) {
            $opengraphTags["image"] = $event->getIllustration()->url();
        }

        $this->setOpengraphTags($opengraphTags);

        return $this->render('AppBundle:Event:show.html.twig', [
            'event' => $event
        ]);
    }
}
