<?php

namespace AppBundle\Controller;

use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Template\Template;
use Exception;
use Framework\Controller;
use Framework\Exception\AuthException;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class TemplateController extends Controller
{
    /**
     * GET /admin/templates.
     * @param Request $request
     * @return Response
     * @throws AuthException
     * @throws PropelException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function indexAction(Request $request): Response
    {
        self::authAdmin($request);

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
        self::authAdmin($request);

        $template = Template::get($slug);
        $request->attributes->set("page_title", "Éditer ".$template->getName());

        if ($request->getMethod() === 'POST') {
            $globalSite = LegacyCodeHelper::getGlobalSite();
            $body = $request->toArray();
            $filesystem = new Filesystem();
            $template->updateContent($globalSite, $body["content"], $filesystem);
            return new JsonResponse();
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
    public function deleteAction(Request $request, UrlGenerator $urlGenerator, $slug): RedirectResponse
    {
        self::authAdmin($request);

        $template = Template::get($slug);

        $template->deleteCustomFile();

        return $this->redirect($urlGenerator->generate('template_index'));
    }
}
