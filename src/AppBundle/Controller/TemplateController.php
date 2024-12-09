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

use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\CurrentUser;
use Biblys\Template\Template;
use Exception;
use Framework\Controller;
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
     * @throws PropelException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function indexAction(Request $request, CurrentUser $currentUser): Response
    {
        $currentUser->authAdmin();

        $request->attributes->set("page_title", "Éditeur de thème");

        return $this->render('AppBundle:Template:index.html.twig', [
            'templates' => Template::getAll(),
        ]);
    }

    /**
     * GET/POST /admin/templates/:slug/edit.
     * @throws Exception
     */
    public function editAction(Request $request, CurrentUser $currentUser, $slug): Response
    {
        $currentUser->authAdmin();

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
     * @throws Exception
     */
    public function deleteAction(
        CurrentUser $currentUser,
        UrlGenerator $urlGenerator,
        $slug
    ): RedirectResponse
    {
        $currentUser->authAdmin();

        $template = Template::get($slug);

        $template->deleteCustomFile();

        return $this->redirect($urlGenerator->generate('template_index'));
    }
}
