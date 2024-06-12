<?php

namespace AppBundle\Controller;

use Biblys\Template\Template;
use Framework\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;

class TemplateController extends Controller
{
    /**
     * GET /admin/templates.
     */
    public function indexAction()
    {
        $this->auth('admin');

        $this->setPageTitle('Éditeur de thème');

        return $this->render('AppBundle:Template:index.html.twig', [
            'templates' => Template::getAll(),
        ]);
    }

    /**
     * GET/POST /admin/templates/:slug/edit.
     */
    public function editAction(Request $request, $slug)
    {
        $this->auth('admin');

        $template = Template::get($slug);
        if (!$template) {
            throw new NotFoundException("Cannot find template $slug");
        }

        $this->setPageTitle('Éditer '.$template->getName());

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
     */
    public function deleteAction($slug)
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
