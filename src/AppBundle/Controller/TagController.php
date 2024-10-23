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

use ArticleManager;
use Biblys\Service\CurrentUser;
use Biblys\Service\Pagination;
use Exception;
use Framework\Controller;

use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Routing\Generator\UrlGenerator;
use TagManager;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class TagController extends Controller
{

    /**
     * Show a Tag's page and related articles
     * /tag/{slug}
     * @param Request $request
     * @param $slug
     * @return Response the rendered templated
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function showAction(Request $request, $slug): Response
    {
        $tm = new TagManager();
        $am = new ArticleManager();

        $tag = $tm->get(["tag_url" => $slug]);
        if (!$tag) {
            throw new NotFoundException("Tag $slug not found");
        }

        // Pagination
        $page = (int)$request->query->get('p', 0);
        $totalCount = $am->countAllFromTag($tag);
        $pagination = new Pagination($page, $totalCount);

        $am = new ArticleManager();
        $articles = $am->getAllFromTag($tag, [
            'fields' => 'article_id, article_title, article_url, article_authors, collection_id, publisher_id, type_id, article_pubdate, article_availability_dilicom, article_price',
            'order' => 'article_pubdate',
            'sort' => 'desc',
            'limit' => $pagination->getLimit(),
            'offset' => $pagination->getOffset()
        ]);

        return $this->render('AppBundle:Tag:show.html.twig', [
            'tag' => $tag,
            'articles' => $articles,
            'pages' => $pagination
        ]);
    }

    /**
     * Edit a tag
     * /admin/tag/{id}/edit
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function editAction(
        Request      $request,
        CurrentUser  $currentUser,
        UrlGenerator $urlGenerator,
        int          $id
    ): RedirectResponse|Response
    {
        $currentUser->authAdmin();

        $tm = new TagManager();

        $tag = $tm->get(["tag_id" => $id]);
        if (!$tag) {
            throw new NotFoundException("Tag $id not found.");
        }

        $formFactory = $this->getFormFactory();

        $defaults = [
            'name' => $tag->get('name'),
            'description' => $tag->get('description')
        ];

        $form = $formFactory->createBuilder(FormType::class, $defaults)
            ->add('name', TextType::class, ['label' => 'Nom :', 'required' => false])
            ->add('description', TextareaType::class, ['label' => false, 'attr' => ['class' => 'wysiwyg']])
            ->getForm();

        $error = false;
        if ($request->getMethod() == "POST") {

            $form->handleRequest($request);
            $data = $form->getData();

            $updated = clone $tag;
            $updated->set('tag_name', $data['name'])
                ->set('tag_description', $data['description']);

            try {
                $updated = $tm->update($updated);
            } catch (Exception $e) {
                $error = $e->getMessage();
            }

            if (!$error) {
                return new RedirectResponse(
                    $urlGenerator->generate('tag_show', ['slug' => $updated->get('url')])
                );
            }
        }

        return $this->render('AppBundle:Tag:edit.html.twig', [
            'tag' => $tag,
            'error' => $error,
            'form' => $form->createView()
        ]);
    }
}
