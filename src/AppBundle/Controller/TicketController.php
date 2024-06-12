<?php

namespace AppBundle\Controller;

use Framework\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TicketController extends Controller
{

    public function __construct()
    {
        global $urlgenerator, $_SITE;

        $this->site = $_SITE;
        $this->url = $urlgenerator;

        $this->tm = new \TicketManager();
        $this->tcm = new \TicketCommentManager();

        $this->support_email = 'contact@biblys.fr';

        parent::__construct();
    }

    public function indexAction()
    {
        $this->setPageTitle("Support Biblys");
        $this->auth("admin");

        $open = $this->tm->getAll(array("site_id" => $this->site["site_id"], "ticket_closed" => "NULL", "ticket_resolved" => "NULL"), ['order' => 'ticket_priority', 'sort' => 'desc']);
        $resolved = $this->tm->getAll(array("site_id" => $this->site["site_id"], "ticket_closed" => "NULL", "ticket_resolved" => "NOT NULL"), ['order' => 'ticket_priority', 'sort' => 'desc']);

        $open = $this->renderTicketTable($open);
        $resolved = $this->renderTicketTable($resolved);

        return $this->render('AppBundle:Ticket:index.html.twig', [
            'open' => $open,
            'resolved' => $resolved
        ]);
    }

    public function rootAction()
    {
        $this->setPageTitle("Support Biblys");
        $this->auth("root");

        $response = null;

        $tickets = $this->tm->getAll(array("ticket_closed" => "NULL", "ticket_resolved" => "NULL"), ['order' => 'ticket_created']);
        $tickets = $this->tm->sort($tickets);
        $tickets = $this->renderTicketTable($tickets);

        return $this->render('AppBundle:Ticket:root.html.twig', ['tickets' => $tickets]);
    }

    private function renderTicketTable($tickets)
    {
        if (empty($tickets)) {
            return false;
        }

        $tickets = array_map( function($ticket) {
            $site = $ticket->getRelated('site');
            $user = $ticket->getRelated('user');
            return '
                <tr>
                    <td>'.$this->tm->getPriority($ticket->get('priority')).'</td>
                    <td>'.$ticket->get('type').'</td>
                    <td><a href="http://'.$site->get('domain').$this->url->generate('ticket_show', array("id" => $ticket->get('id'))).'">#'.$ticket->get('id').' '.$ticket->get('title').'</td>
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

    public function showAction($id)
    {
        $this->auth("admin");

        $ticket = $this->tm->getById($id);
        if (!$ticket) {
            throw new \Exception("Ticket $id not found.");
        }

        $this->setPageTitle('Ticket #'.$ticket->get('id').' : '.$ticket->get('title'));

        $comments = $this->tcm->getAll(["ticket_id" => $ticket->get('id')]);
        $comments = array_map( function($comment) {
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
     */
    public function newAction(Request $request)
    {
        $this->auth("admin");

        $this->setPageTitle("Nouveau ticket");

        $ticket = new \Ticket(array('ticket_priority' => 1));

        if ($request->getMethod() == "POST") {
            $request->request->add([
                'site_id' => $this->site['site_id'],
                'user_id' => $this->user->get('id')
            ]);
            $ticket = $this->tm->create($request->request->all());

            $url = $this->url->generate('ticket_show', ["id" => $ticket->get('id')], UrlGeneratorInterface::ABSOLUTE_URL);

            $mail = '
                <p>
                    '.$this->user->get('screen_name').' a créé le ticket 
                    <a href="'.$url.'">#'.$ticket->get('id').'</a>.<br>
                </p>
                <p>
                    Type : '.$ticket->get('type').'<br>
                    Priorité : '.$ticket->get('priority').'<br>
                    Titre : '.$ticket->get('title').'<br>
                </p>
                <p>
                    '.nl2br($ticket->get('content')).'
                </p>
                <p>
                    Pour répondre au ticket, rendez-vous sur :<br>
                    <a href="'.$url.'">'.$url.'</a>
                </p>
            ';

            $mailer = new \Mailer();
            $mailer->send(
                'contact@biblys.fr',
                'Ticket Biblys #'.$ticket->get('id').' : '.$ticket->get('title'),
                $mail,
                [],
                ['cc' => [$this->user->get('email')]]
            );

            return new RedirectResponse(
                $this->url->generate('ticket_show', ["id" => $ticket->get('id')])
            );
        }

        return $this->render('AppBundle:Ticket:new.html.twig', ['ticket' => $ticket]);
    }

    /**
     * Edit a Ticket
     */
    public function editAction(Request $request, $id)
    {
        $this->auth("admin");

        $ticket = $this->tm->getById($id);
        if (!$ticket) {
            throw new \Exception("Ticket $id not found.");
        }

        $this->setPageTitle('Éditer le ticket #'.$ticket->get('id'));

        if ($request->getMethod() == "POST") {

            foreach ($request->request->all() as $k => $v) {
                $ticket->set($k, $v);
            }

            $ticket = $this->tm->update($ticket);

            return new RedirectResponse($this->url->generate('ticket_show', ["id" => $ticket->get('id')]));
        }

        return $this->render('AppBundle:Ticket:edit.html.twig', ['ticket' => $ticket, 'priorities' => $this->tm->getPriorities()]);
    }

    /**
     * Close a Ticket
     */
    public function closeAction($id)
    {
        $this->auth("admin");

        $ticket = $this->tm->getById($id);
        if (!$ticket) {
            throw new \Exception("Ticket $id not found.");
        }

        $ticket->set('ticket_closed', date('Y-m-d H:i:s'));
        $this->tm->update($ticket);

        return new RedirectResponse($this->url->generate('ticket_index'));
    }

    /**
     * Mark a ticket as resolved
     */
    public function resolveAction($id)
    {
        $this->auth("root");

        $ticket = $this->tm->getById($id);
        if (!$ticket) {
            throw new \Exception("Ticket $id not found.");
        }

        $ticket->set('ticket_resolved', date('Y-m-d H:i:s'));
        $this->tm->update($ticket);

        // Send notification mail
        $url = $this->url->generate(
            'ticket_show', 
            ["id" => $ticket->get('id')], 
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $close = $this->url->generate(
            'ticket_close', 
            ["id" => $ticket->get('id')], 
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $mail = '
            <p>
                '.$this->user->get('screen_name').' a marqué le ticket 
                <a href="'.$url.'">#'.$ticket->get('id').'</a> comme résolu.<br>
            </p>
            <p>
                Si le ticket est bien résolu, vous pouvez le clore :<br>
                <a href="'.$close.'">'.$close.'</a>
            </p>
            <p>
                Sinon, vous pouvez répondre au ticket :<br>
                <a href="'.$url.'">'.$url.'</a>
            </p>
        ';

        $mailer = new \Mailer();
        $mailer->send(
            $ticket->getRelated('user')->get('email'),
            'Ticket Biblys #'.$ticket->get('id').' : '.$ticket->get('title'),
            $mail,
            [],
            ['cc' => ['contact@biblys.fr']]
        );

        return new RedirectResponse(
            $this->url->generate('ticket_show', ['id' => $ticket->get('id')])
        );
    }

    /**
     * Create a new TicketComment
     */
    public function newCommentAction(Request $request, $ticket_id)
    {
        $this->auth("admin");

        if ($request->getMethod() == "POST") {

            $ticket = $this->tm->getById($ticket_id);
            if (!$ticket) {
                throw new \Exception("Ticket $ticket_id not found.");
            }

            $request->request->add([
                'ticket_id' => $ticket_id,
                'user_id' => $this->user->get('id')
            ]);

            $comment = $this->tcm->create($request->request->all());

            $url = $this->url->generate('ticket_show', ["id" => $ticket->get('id')], UrlGeneratorInterface::ABSOLUTE_URL);

            $mail = '
                <p>
                    '.$this->user->get('screen_name').' a commenté le ticket <a href="'.$url.'">#'.$ticket->get('id').'</a> :<br>
                </p>
                <p>
                    '.$comment->get('content').'
                </p>
                <p>
                    <a href="'.$url.'">Répondre ou clore le ticket</a>
                </p>
            ';

            // If comment by root, send notification to ticket creator, else to root
            if ($this->user->isRoot()) {
                $dest = $ticket->getRelated('user')->get('email');
            } else {
                $dest = $this->support_email;
            }

            $mailer = new \Mailer();
            $mailer->send(
                $dest,
                'Ticket Biblys #'.$ticket->get('id').' : '.$ticket->get('title'),
                $mail,
                []
            );

            // Re-open ticket if it was resolved
            if ($ticket->has('resolved')) {
                $ticket->set('ticket_resolved', null);
                $this->tm->update($ticket);
            }

            return new RedirectResponse($this->url->generate('ticket_show', ["id" => $ticket->get('id')]));
        }
    }
}
