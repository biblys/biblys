<?php

namespace AppBundle\Controller;

use Biblys\Template\Template;
use Exception;
use Framework\Controller;
use Framework\Exception\AuthException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;

class TemplateController extends Controller
{
    /**
     * GET /admin/templates.
     * @throws AuthException
     */
    public function indexAction(Request $request): Response
    {
        $this->auth('admin');

        $request->attributes->set("page_title", "Éditeur de thème");

        return $this->render('AppBundle:Template:index.html.twig', [
            'templates' => Template::getAll(),
        ]);
    }

    /**
     * GET/POST /admin/templates/:slug/edit.
     * @throws AuthException
     * @throws Exception
     */
    public function editAction(Request $request, $slug): Response
    {
        $this->auth('admin');

        $template = Template::get($slug);
        if (!$template) {
            throw new NotFoundException("Cannot find template $slug");
        }

        $request->attributes->set("page_title", "Éditer ".$template->getName());

        if ($request->getMethod() === 'POST') {
            $content = $request->request->get('content');
            $template->updateContent($content);
        }

        return $this->render('AppBundle:Template:edit.html.twig', [
            'template' => $template,
        ]);
    }

    /**
     * GET /admin/templates/:slug/delete.
     * @throws AuthException
     * @throws Exception
     */
    public function deleteAction($slug): RedirectResponse
    {
        $this->auth('admin');

        $template = Template::get($slug);
        if (!$template) {
            throw new NotFoundException("Cannot find template $slug");
        }

        $template->deleteCustomFile();

        return $this->redirect($this->url->generate('template_index'));
    }
}
