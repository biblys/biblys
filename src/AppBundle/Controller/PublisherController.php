<?php

namespace AppBundle\Controller;

use Framework\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;

class PublisherController extends Controller
{
    /**
     * 
     */
    public function indexAction(Request $request)
    {
        global $site;
        
        $request->attributes->set("page_title", "Éditeurs");

        $pm = new \PublisherManager();

        $pageNumber = (int) $request->query->get("p", 0);
        if ($pageNumber < 0) {
            throw new BadRequestHttpException("Page number must be a positive integer");
        }

        $totalCount = $pm->count([]);
        $limit = $site->getOpt('publisher_per_page') ? $site->getOpt('publisher_per_page') : 100;
        $pagination = new \Biblys\Service\Pagination($pageNumber, $totalCount, $limit);

        $publishers = $pm->getAll([], [
            'order' => 'publisher_name_alphabetic',
            'limit' => $pagination->getLimit(),
            'offset' => $pagination->getOffset(),
        ]);

        return $this->render('AppBundle:Publisher:index.html.twig', [
            'publishers' => $publishers,
            'pages' => $pagination,
        ]);
    }

    /**
     * Show a Publisher's page and related articles
     * /editeur/{slug}.
     *
     * @param  $slug the publisher's slug
     *
     * @return Response the rendered templated
     */
    public function showAction(Request $request, $slug)
    {
        global $site;

        $pm = $this->entityManager('Publisher');
        $am = $this->entityManager('Article');

        $publisher = $pm->get(['publisher_url' => $slug]);
        if (!$publisher) {
            throw new NotFoundException("Publisher $slug not found");
        }

        $use_old_controller = $site->getOpt('use_old_publisher_controller');
        if ($use_old_controller) {
            return new RedirectResponse('/o/editeur/'.$slug);
        }

        $publisher_filter = $site->getOpt('publisher_filter');
        if ($publisher_filter) {
            $publishersFromFilter = explode(',', $publisher_filter);
            if (!in_array($publisher->get('id'), $publishersFromFilter)) {
                throw new NotFoundException("Publisher $slug not found");
            }
        }

        $this->setPageTitle($publisher->get('name'));

        $cm = $this->entityManager('Collection');
        $collections = $cm->getAll(['publisher_id' => $publisher->get('id')], ['order' => 'collection_name']);

        // Pagination
        $page = (int) $request->query->get('p', 0);
        $totalCount = $am->count(['publisher_id' => $publisher->get('id')]);
        $pagination = new \Biblys\Service\Pagination($page, $totalCount);

        $articles = $am->getAll(['publisher_id' => $publisher->get('id')], [
            'order' => 'article_pubdate',
            'sort' => 'desc',
            'limit' => $pagination->getLimit(),
            'offset' => $pagination->getOffset(),
        ]);

        return $this->render('AppBundle:Publisher:show.html.twig', [
            'publisher' => $publisher,
            'articles' => $articles,
            'pages' => $pagination,
            'collections' => $collections,
        ]);
    }

    /**
     * Edit a publisher
     * /admin/publisher/{id}/edit.
     *
     * @param int $id
     *
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        global $site;

        $this->auth('admin');

        $pm = $this->entityManager('Publisher');

        $publisher = $pm->get(['publisher_id' => $id]);
        if (!$publisher) {
            throw new NotFoundException("Publisher $id not found.");
        }

        $this->setPageTitle('Modifier l\'éditeur '.$publisher->get('name'));

        $formFactory = $this->getFormFactory();

        $defaults = [
            'name' => $publisher->get('name'),
            'email' => $publisher->get('email'),
            'desc' => $publisher->get('desc'),
        ];

        $form = $formFactory->createBuilder(FormType::class, $defaults)
            ->add('name', TextType::class, ['label' => 'Nom :', 'required' => false])
            ->add('email', EmailType::class, ['label' => 'Adresse e-mail :', 'required' => false])
            ->add('logo', FileType::class, ['label' => 'Logo :', 'required' => false])
            ->add('desc', TextareaType::class, ['label' => false, 'attr' => ['class' => 'wysiwyg']])
            ->getForm();

        $error = false;
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $data = $form->getData();

            $updated = clone $publisher;
            $updated->set('publisher_name', $data['name'])
                ->set('publisher_email', $data['email'])
                ->set('publisher_desc', $data['desc']);

            try {
                $updated = $pm->update($updated);

                if ($data['logo'] !== null) {
                    $updated->addLogo($data['logo']);
                }
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }

            if (!$error) {
                return new RedirectResponse($this->generateUrl('publisher_show', ['slug' => $updated->get('url')]));
            }
        }

        return $this->render('AppBundle:Publisher:edit.html.twig', [
            'publisher' => $publisher,
            'error' => $error,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete a publisher.
     *
     * @route GET /admin/publisher/{id}/delete
     */
    public function deleteAction(int $id, Request $request)
    {
        $this->auth('admin');

        $pm = $this->entityManager('Publisher');
        $publisher = $pm->getById($id);

        if (!$publisher) {
            throw new NotFoundException("Cannot find a publisher for $id");
        }

        $cm = $this->entityManager('Collection');
        $collections = $cm->getAll(['publisher_id' => $publisher->get('id')]);

        $am = $this->entityManager('Article');
        $articles = $am->getAll(['publisher_id' => $publisher->get('id')]);

        // Id deletion is confirmed
        $error = null;
        if ($request->getMethod() === 'POST') {
            try {
                foreach ($articles as $article) {
                    $am->delete($article);
                }

                foreach ($collections as $collection) {
                    $cm->delete($collection);
                }

                $pm->delete($publisher);

                return $this->redirect(
                    $this->generateUrl(
                        'publisher_deleted',
                        ['name' => $publisher->get('name')]
                    )
                );
            } catch (\Exception $exception) {
                throw $exception;
                $error = $exception->getMessage();
            }
        }

        return $this->render(
            'AppBundle:Publisher:delete.html.twig', [
                'publisher' => $publisher,
                'collections' => $collections,
                'articles' => $articles,
                'error' => $error,
            ]
        );
    }

    /**
     * Publisher deletion confirm page.
     *
     * @route GET /admin/publisher/deleted
     */
    public function deletedAction(Request $request)
    {
        $name = $request->query->get('name');

        return $this->render(
            'AppBundle:Publisher:deleted.html.twig',
            ['name' => $name]
        );
    }

    /**
     * Manager a publisher's rights
     * /admin/publisher/{id}/rights.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function rightsAction($id)
    {
        $this->auth('admin');

        $pm = $this->entityManager('Publisher');

        $publisher = $pm->get(['publisher_id' => $id]);
        if (!$publisher) {
            throw new NotFoundException("Publisher $id not found.");
        }

        $this->setPageTitle(("Permissions pour l'éditeur ".$publisher->get('name')));

        return $this->render('AppBundle:Publisher:rights.html.twig', [
            'publisher' => $publisher,
        ]);
    }

    /**
     * Give a user the right to manage a publisher.
     *
     * @param $id publisher's id
     *
     * @return RedirectResponse
     */
    public function rightsAddAction(Request $request, $id)
    {
        $this->auth('admin');

        $pm = $this->entityManager('Publisher');

        $publisher = $pm->get(['publisher_id' => $id]);
        if (!$publisher) {
            throw new NotFoundException("Publisher $id not found.");
        }

        $um = $this->entityManager('User');
        $userEmail = $request->request->get('user_email');
        $user = $um->get(['Email' => $userEmail]);
        if (!$user) {
            throw new \Exception("Cannot find a user with e-mail $userEmail");
        }

        if (!$user->hasRight('publisher', $id)) {
            $user->giveRight('publisher', $id);
        }

        return $this->redirect($this->generateUrl(
            'publisher_rights', 
            ['id' => $publisher->get('id')])
        );
    }

    /**
     * Remove rights from a user to manager a publisher.
     *
     * @param  $rightId      id of right to delete
     * @param  $publisherId  id of publisher to remove right from
     *
     * @return RedirectResponse
     */
    public function rightsRemoveAction($publisherId, $userId)
    {
        $this->auth('admin');

        $pm = $this->entityManager('Publisher');
        $publisher = $pm->get(['publisher_id' => $publisherId]);
        if (!$publisher) {
            throw new NotFoundException("Publisher $publisherId not found.");
        }

        $um = $this->entityManager('User');
        $user = $um->getById($userId);
        if (!$user) {
            throw new NotFoundException("User $userId not found.");
        }

        $user->removeRight('publisher', $publisher->get('id'));

        return $this->redirect(
            $this->generateUrl('publisher_rights', ['id' => $publisher->get('id')])
        );
    }

    /**
     * Manager a publisher's suppliers
     * /admin/publisher/{id}/suppliers.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function suppliersAction($id)
    {
        $this->auth('admin');

        $pm = $this->entityManager('Publisher');

        $publisher = $pm->get(['publisher_id' => $id]);
        if (!$publisher) {
            throw new NotFoundException("Publisher $id not found.");
        }

        // All suppliers
        $sm = $this->entityManager('Supplier');
        $suppliers = $sm->getAll([], ['order' => 'supplier_name']);

        return $this->render('AppBundle:Publisher:suppliers.html.twig', [
            'publisher' => $publisher,
            'suppliers' => $suppliers,
        ]);
    }

    /**
     * Add a publisher's supplier.
     *
     * @param  $id     supplier's id
     *
     * @return RedirectResponse
     */
    public function suppliersAddAction(Request $request, $id)
    {
        $this->auth('admin');

        $pm = $this->entityManager('Publisher');

        $publisher = $pm->get(['publisher_id' => $id]);
        if (!$publisher) {
            throw new NotFoundException("Publisher $id not found.");
        }

        $sm = $this->entityManager('Supplier');

        $supplier = $sm->get(['supplier_id' => $request->request->get('supplier_id')]);
        if (!$supplier) {
            throw new NotFoundException("Supplier $id not found.");
        }

        $publisher->addSupplier($supplier);

        return $this->redirect($this->generateUrl('publisher_suppliers', ['id' => $publisher->get('id')]));
    }

    /**
     * Add a publisher's supplier.
     *
     * @param  $id     supplier's id
     *
     * @return RedirectResponse
     */
    public function suppliersRemoveAction(Request $request, $id, $supplier_id)
    {
        $this->auth('admin');

        $pm = $this->entityManager('Publisher');

        $publisher = $pm->get(['publisher_id' => $id]);
        if (!$publisher) {
            throw new NotFoundException("Publisher $id not found.");
        }

        $sm = $this->entityManager('Supplier');

        $supplier = $sm->get(['supplier_id' => $supplier_id]);
        if (!$supplier) {
            throw new NotFoundException("Supplier $id not found.");
        }

        $publisher->removeSupplier($supplier);

        return $this->redirect($this->generateUrl('publisher_suppliers', ['id' => $publisher->get('id')]));
    }
}
