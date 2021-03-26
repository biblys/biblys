<?php

namespace AppBundle\Controller;

use Framework\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
// Forms
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;

class CollectionController extends Controller
{
    public function indexAction(Request $request)
    {
        $cm = $this->entityManager('Collection');

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
                'label' => $collection->get('name').' ('.$collection->get('publisher')->get('name').')',
            ];
        }, $collections);

        return new JsonResponse($collections);
    }

    /**
     * Show a Collection's page and related articles
     * /collection/{slug}.
     *
     * @param  $slug the collection's slug
     *
     * @return Response the rendered templated
     */
    public function showAction(Request $request, $slug)
    {
        global $site;

        $cm = $this->entityManager('Collection');
        $am = $this->entityManager('Article');

        $collection = $cm->get(['collection_url' => $slug]);
        if (!$collection) {
            throw new NotFoundException("Collection $slug not found");
        }

        $use_old_controller = $site->getOpt('use_old_collection_controller');
        if ($use_old_controller) {
            return new RedirectResponse('/o/collection/'.$slug);
        }

        $this->setPageTitle($collection->get('name'));

        // Pagination
        $page = (int) $request->query->get('p', 0);
        $totalCount = $am->count(['collection_id' => $collection->get('id')]);
        $pagination = new \Biblys\Service\Pagination($page, $totalCount);

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
     * /admin/collection/{id}/edit.
     *
     * @param int $id
     *
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        global $site;

        $this->auth('admin');

        $cm = $this->entityManager('Collection');

        $collection = $cm->get(['collection_id' => $id]);
        if (!$collection) {
            throw new NotFoundException("Collection $id not found.");
        }

        $this->setPageTitle('Modifier la collection '.$collection->get('name'));

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
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }

            if (!$error) {
                return new RedirectResponse($this->generateUrl('collection_show', ['slug' => $updated->get('url')]));
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
     * /admin/collection/{id}/delete.
     *
     * @param int $id
     *
     * @return Response
     */
    public function deleteAction(Request $request, $id)
    {
        $this->auth('admin');

        $cm = $this->entityManager('Collection');

        $collection = $cm->get(['collection_id' => $id]);
        if (!$collection) {
            throw new NotFoundException("Collection $id not found.");
        }

        $this->setPageTitle('Supprimer la collection '.$collection->get('name'));

        $error = false;
        if ($request->getMethod() == 'POST') {
            $publisher = $collection->getPublisher();

            try {
                $cm->delete($collection);
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }

            if (!$error) {
                return new RedirectResponse($this->generateUrl('publisher_show', ['slug' => $publisher->get('url')]));
            }
        }

        return $this->render('AppBundle:Collection:delete.html.twig', [
            'collection' => $collection,
            'error' => $error,
        ]);
    }
}
