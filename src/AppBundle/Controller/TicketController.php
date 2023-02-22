<?php

namespace AppBundle\Controller;

use Exception;
use Framework\Controller;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Ticket;
use TicketCommentManager;
use TicketManager;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class TicketController extends Controller
{

    /**
     * @var false|mixed
     */
    private mixed $site;
    private UrlGenerator $url;
    private TicketManager $tm;
    private TicketCommentManager $tcm;

    public function __construct()
    {
        global $urlgenerator, $_SITE;

        $this->site = $_SITE;
        $this->url = $urlgenerator;

        $this->tm = new TicketManager();
        $this->tcm = new TicketCommentManager();

        parent::__construct();
    }

    /**
     * @param Request $request
     * @return Response
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function indexAction(Request $request): Response
    {
        Controller::authAdmin($request);

        $open = $this->tm->getAll(array("site_id" => $this->site["site_id"], "ticket_closed" => "NULL", "ticket_resolved" => "NULL"), ['order' => 'ticket_priority', 'sort' => 'desc']);
        $resolved = $this->tm->getAll(array("site_id" => $this->site["site_id"], "ticket_closed" => "NULL", "ticket_resolved" => "NOT NULL"), ['order' => 'ticket_priority', 'sort' => 'desc']);

        $open = $this->renderTicketTable($open);
        $resolved = $this->renderTicketTable($resolved);

        return $this->render('AppBundle:Ticket:index.html.twig', [
            'open' => $open,
            'resolved' => $resolved
        ]);
    }

    private function renderTicketTable($tickets): bool|string
    {
        if (empty($tickets)) {
            return false;
        }

        $tickets = array_map(/**
         * @throws Exception
         */ function($ticket) {
            $user = $ticket->getRelated('user');
            return '
                <tr>
                    <td>'.$this->tm->getPriority($ticket->get('priority')).'</td>
                    <td>'.$ticket->get('type').'</td>
                    <td><a href="'.$this->url->generate('ticket_show', array("id" => $ticket->get('id'))).'">#'.$ticket->get('id').' '.$ticket->get('title').'</td>
                    <td>'.$user->get('screen_name').'</td>
                    <td>'._date($ticket->get('created'), 'd/m/Y').'</td>
                </tr>
            ';
        }, $tickets);

        return '
        <table class="table">
            <thead>
                <tr>
                    <th>Priorité</th>
                    <th>Type</th>
                    <th>Titre</th>
                    <th>Créé par</th>
                    <th>Créé le</th>
                </tr>
            </thead>
            <tbody>
                '.implode($tickets).'
            </tbody>
        </table>';
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     * @throws Exception
     */
    public function showAction(Request $request, int $id): Response
    {
        Controller::authAdmin($request);

        $ticket = $this->tm->getById($id);
        if (!$ticket) {
            throw new Exception("Ticket $id not found.");
        }

        $comments = $this->tcm->getAll(["ticket_id" => $ticket->get('id')]);
        $comments = array_map(/**
         * @throws Exception
         */ function($comment) {
            $user = $comment->getRelated('user');
            return '<h3>'.$user->get('screen_name').', le '._date($comment->get('created'), 'l j f à Hhi').'</h3><p>'.nl2br($comment->get('content')).'</p>';
        }, $comments);

        // Ticket position in list
        $tickets = $this->tm->getAll(array("ticket_closed" => "NULL", "ticket_resolved" => "NULL"), ['order' => 'ticket_created']);
        $tickets = $this->tm->sort($tickets);
        $position = (array_search($ticket, $tickets)+1)." / ".count($tickets);

        return $this->render('AppBundle:Ticket:show.html.twig', [
            'ticket' => $ticket,
            'position' => $position,
            'comments' => $comments
        ]);
    }

    /**
     * Create a new Ticket
     *
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function newAction(Request $request): RedirectResponse|Response
    {
        Controller::authAdmin($request);

        $ticket = new Ticket(array('ticket_priority' => 1));

        return $this->render('AppBundle:Ticket:new.html.twig', ['ticket' => $ticket]);
    }
}
