<?php

namespace AppBundle\Controller;

use ArticleManager;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\Pagination;
use CollectionManager;
use Exception;
use Framework\Controller;
use InvalidArgumentException;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CollectionController extends Controller
{
    public function indexAction(Request $request): Response
    {
        $cm = new CollectionManager();

        // If there is a filter
        $filter = $request->query->get('filter');
        if ($filter) {
            $collections = $cm->filter($filter);
        } else {
            $collections = $cm->getAll();
        }

        $collections = array_map(function ($collection) {
            return [
                'id' => $collection->get('id'),
                'name' => $collection->get('name'),
                'label' => $collection->get('name') . ' (' . $collection->get('publisher')->get('name') . ')',
            ];
        }, $collections);

        return new JsonResponse($collections);
    }

    /**
     * Show a Collection's page and related articles
     *
     * @route GET /collection/{slug}.
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function showAction(
        Request     $request,
        CurrentSite $currentSite,
        string      $slug
    ):
    RedirectResponse|Response
    {
        $cm = new CollectionManager();
        $am = new ArticleManager();

        $collection = $cm->get(['collection_url' => $slug]);
        if (!$collection) {
            throw new NotFoundException("Collection $slug not found");
        }

        $use_old_controller = $currentSite->getOption("use_old_collection_controller");
        if ($use_old_controller) {
            return new RedirectResponse('/o/collection/' . $slug);
        }

        // Pagination
        $page = (int)$request->query->get('p', 0);
        $totalCount = $am->count(['collection_id' => $collection->get('id')]);
        $limit = $currentSite->getOption("articles_per_page");

        try {
            $pagination = new Pagination($page, $totalCount, $limit);
        } catch (InvalidArgumentException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }

        $articles = $am->getAll(['collection_id' => $collection->get('id')], [
            'order' => 'article_pubdate',
            'sort' => 'desc',
            'limit' => $pagination->getLimit(),
            'offset' => $pagination->getOffset(),
        ]);

        return $this->render('AppBundle:Collection:show.html.twig', [
            'collection' => $collection,
            'articles' => $articles,
            'pages' => $pagination,
        ]);
    }

    /**
     * Edit a collection
     *
     * @route GET /admin/collection/{id}/edit.
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
    ): Response
    {
        $currentUser->authAdmin();

        $cm = new CollectionManager();

        $collection = $cm->get(['collection_id' => $id]);
        if (!$collection) {
            throw new NotFoundException("Collection $id not found.");
        }

        $formFactory = $this->getFormFactory();

        $defaults = [
            'name' => $collection->get('name'),
            'desc' => $collection->get('desc'),
        ];

        $form = $formFactory->createBuilder(FormType::class, $defaults)
            ->add('name', TextType::class, ['label' => 'Nom :', 'required' => false])
            ->add('desc', TextareaType::class, ['label' => false, 'attr' => ['class' => 'wysiwyg']])
            ->getForm();

        $error = false;
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $data = $form->getData();

            $updated = clone $collection;
            $updated->set('collection_name', $data['name'])
                ->set('collection_desc', $data['desc']);

            try {
                $updated = $cm->update($updated);
            } catch (Exception $e) {
                $error = $e->getMessage();
            }

            if (!$error) {
                return new RedirectResponse($urlGenerator->generate('collection_show', ['slug' =>
                    $updated->get('url')]));
            }
        }

        return $this->render('AppBundle:Collection:edit.html.twig', [
            'collection' => $collection,
            'error' => $error,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete a collection
     * @route GET /admin/collection/{id}/delete.
     *
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function deleteAction(
        Request      $request,
        CurrentUser  $currentUser,
        UrlGenerator $urlGenerator,
        int          $id
    ): RedirectResponse|Response
    {
        $currentUser->authAdmin();

        $cm = new CollectionManager();

        $collection = $cm->get(['collection_id' => $id]);
        if (!$collection) {
            throw new NotFoundException("Collection $id not found.");
        }

        $error = false;
        if ($request->getMethod() == 'POST') {
            $publisher = $collection->getPublisher();

            try {
                $cm->delete($collection);
            } catch (Exception $e) {
                $error = $e->getMessage();
            }

            if (!$error) {
                return new RedirectResponse($urlGenerator->generate('publisher_show', ['slug' =>
                    $publisher->get('url')]));
            }
        }

        return $this->render('AppBundle:Collection:delete.html.twig', [
            'collection' => $collection,
            'error' => $error,
        ]);
    }
}
