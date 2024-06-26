<?php

namespace AppBundle\Controller;

use ArticleManager;
use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\Pagination;
use CollectionManager;
use Exception;
use Framework\Controller;
use Framework\Exception\AuthException;
use Model\PublisherQuery;
use Model\Right;
use Model\RightQuery;
use Model\UserQuery;
use Propel\Runtime\Exception\PropelException;
use PublisherManager;
use SupplierManager;
use InvalidArgumentException;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PublisherController extends Controller
{
    /**
     * @route GET /publishers/
     * @param Request $request
     * @return Response
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function indexAction(Request $request): Response
    {
        $globalSite = LegacyCodeHelper::getGlobalSite();

        $pm = new PublisherManager();

        $pageNumber = (int)$request->query->get("p", 0);
        if ($pageNumber < 0) {
            throw new BadRequestHttpException("Page number must be a positive integer");
        }

        $totalCount = $pm->count();
        $limit = $globalSite->getOpt('publisher_per_page') ? $globalSite->getOpt('publisher_per_page') : 100;
        $pagination = new Pagination($pageNumber, $totalCount, $limit);

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
     * @route /editeur/{slug}.
     * @param Request $request
     * @param $slug
     * @return Response
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function showAction(Request $request, $slug): Response
    {
        $globalSite = LegacyCodeHelper::getGlobalSite();

        $pm = new PublisherManager();
        $am = new ArticleManager();

        $publisher = $pm->get(['publisher_url' => $slug]);
        if (!$publisher) {
            throw new NotFoundException("Publisher $slug not found");
        }

        $use_old_controller = $globalSite->getOpt('use_old_publisher_controller');
        if ($use_old_controller) {
            return new RedirectResponse('/o/editeur/' . $slug);
        }

        $publisher_filter = $globalSite->getOpt('publisher_filter');
        if ($publisher_filter) {
            $publishersFromFilter = explode(',', $publisher_filter);
            if (!in_array($publisher->get('id'), $publishersFromFilter)) {
                throw new NotFoundException("Publisher $slug not found");
            }
        }

        $cm = new CollectionManager();
        $collections = $cm->getAll(['publisher_id' => $publisher->get('id')], ['order' => 'collection_name']);

        // Pagination
        $page = (int)$request->query->get('p', 0);
        $totalCount = $am->count(['publisher_id' => $publisher->get('id')]);

        try {
            $pagination = new Pagination($page, $totalCount);
        } catch (InvalidArgumentException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }

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

        $pm = new PublisherManager();

        $publisher = $pm->get(['publisher_id' => $id]);
        if (!$publisher) {
            throw new NotFoundException("Publisher $id not found.");
        }

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
            } catch (Exception $e) {
                $error = $e->getMessage();
            }

            if (!$error) {
                $url = $urlGenerator->generate('publisher_show', ['slug' => $updated->get('url')]);
                return new RedirectResponse($url);
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
     * @throws AuthException
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
        int          $id,
    ): Response
    {
        $currentUser->authAdmin();

        $pm = new PublisherManager();
        $publisher = $pm->getById($id);

        if (!$publisher) {
            throw new NotFoundException("Cannot find a publisher for $id");
        }

        $cm = new CollectionManager();
        $collections = $cm->getAll(['publisher_id' => $publisher->get('id')]);

        $am = new ArticleManager();
        $articles = $am->getAll(['publisher_id' => $publisher->get('id')]);

        // Id deletion is confirmed
        $error = null;
        if ($request->getMethod() === 'POST') {
            foreach ($articles as $article) {
                $am->delete($article);
            }

            foreach ($collections as $collection) {
                $cm->delete($collection);
            }

            $pm->delete($publisher);

            $url = $urlGenerator->generate(
                'publisher_deleted',
                ['name' => $publisher->get('name')]
            );
            return new RedirectResponse($url);
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
     * @param Request $request
     * @return Response
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function deletedAction(Request $request): Response
    {
        $name = $request->query->get('name');

        return $this->render(
            'AppBundle:Publisher:deleted.html.twig',
            ['name' => $name]
        );
    }

    /**
     * Manager a publisher's rights
     * @route /admin/publisher/{id}/rights.
     * @throws AuthException
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function rightsAction(CurrentUser $currentUser, int $id): Response
    {
        $currentUser->authAdmin();

        $publisher = PublisherQuery::create()->findPk($id);
        if (!$publisher) {
            throw new NotFoundException("Publisher $id not found.");
        }

        return $this->render('AppBundle:Publisher:rights.html.twig', [
            'publisher' => $publisher,
        ]);
    }

    /**
     * Give a user the right to manage a publisher.
     *
     * @throws AuthException
     * @throws PropelException
     * @throws Exception
     */
    public function rightsAddAction(
        Request      $request,
        CurrentUser  $currentUser,
        UrlGenerator $urlGenerator,
        Session      $session,
        CurrentSite  $currentSite,
                     $id
    ): RedirectResponse
    {
        $currentUser->authAdmin();

        $publisher = PublisherQuery::create()->findPk($id);
        if (!$publisher) {
            throw new NotFoundException("Publisher $id not found.");
        }

        $publisherRightsUrl = $urlGenerator->generate('publisher_rights',
            ['id' => $publisher->getId()]
        );

        $userEmail = $request->request->get('user_email');
        $user = UserQuery::create()->findOneByEmail($userEmail);
        if (!$user) {
            $session->getFlashBag()->add("error", "Impossible de trouver un utilisateur avec l'adresse $userEmail.");
            return new RedirectResponse($publisherRightsUrl);
        }

        $existingRight = RightQuery::create()
            ->filterBySite($currentSite->getSite())
            ->filterByUser($user)
            ->filterByPublisher($publisher)
            ->findOne();
        if ($existingRight) {
            $session->getFlashBag()->add("warning", "L'utilisateur $userEmail était déjà autorisé.");
            return new RedirectResponse($publisherRightsUrl);
        }

        $right = new Right();
        $right->setPublisher($publisher);
        $right->setUser($user);
        $right->setSite($currentSite->getSite());
        $right->save();

        $session->getFlashBag()->add("success", "L'utilisateur $userEmail a été autorisé.");
        return new RedirectResponse($publisherRightsUrl);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function rightsRemoveAction(
        UrlGenerator $urlGenerator,
        CurrentSite  $currentSite,
        CurrentUser  $currentUser,
        Session      $session,
                     $publisherId,
                     $userId
    ): RedirectResponse
    {
        $currentUser->authAdmin();

        $publisher = PublisherQuery::create()->findPk($publisherId);
        if (!$publisher) {
            throw new NotFoundException("Publisher $publisherId not found.");
        }

        $user = UserQuery::create()->findPk($userId);
        if (!$user) {
            throw new NotFoundException("User $userId not found.");
        }

        $existingRight = RightQuery::create()
            ->filterBySite($currentSite->getSite())
            ->filterByUser($user)
            ->filterByPublisher($publisher)
            ->findOne();
        $existingRight?->delete();

        $session->getFlashBag()->add("success", "L'utilisateur {$user->getEmail()} n'est plus autorisé.");
        $publisherRightsUrl = $urlGenerator->generate(
            'publisher_rights',
            ['id' => $publisher->getId()]
        );
        return new RedirectResponse($publisherRightsUrl);
    }

    /**
     * Manager a publisher's suppliers
     * @route /admin/publisher/{id}/suppliers.
     * @throws AuthException
     * @throws PropelException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function suppliersAction(CurrentUser $currentUser, int $id): Response
    {
        $currentUser->authAdmin();

        $pm = new PublisherManager();

        $publisher = $pm->get(['publisher_id' => $id]);
        if (!$publisher) {
            throw new NotFoundException("Publisher $id not found.");
        }

        // All suppliers
        $sm = new SupplierManager();
        $suppliers = $sm->getAll([], ['order' => 'supplier_name']);

        return $this->render('AppBundle:Publisher:suppliers.html.twig', [
            'publisher' => $publisher,
            'suppliers' => $suppliers,
        ]);
    }

    /**
     * Add a publisher's supplier.
     * @throws AuthException
     * @throws PropelException
     * @throws Exception
     */
    public function suppliersAddAction(
        Request      $request,
        CurrentUser  $currentUser,
        UrlGenerator $urlGenerator,
                     $id
    ): RedirectResponse
    {
        $currentUser->authAdmin();

        $pm = new PublisherManager();

        $publisher = $pm->get(['publisher_id' => $id]);
        if (!$publisher) {
            throw new NotFoundException("Publisher $id not found.");
        }

        $sm = new SupplierManager();

        $supplier = $sm->get(['supplier_id' => $request->request->get('supplier_id')]);
        if (!$supplier) {
            throw new NotFoundException("Supplier $id not found.");
        }

        $publisher->addSupplier($supplier);

        $suppliersUrl = $urlGenerator->generate('publisher_suppliers', ['id' => $publisher->get('id')]);
        return new RedirectResponse($suppliersUrl);
    }

    /**
     * Add a publisher's supplier.
     * @throws AuthException
     * @throws PropelException
     * @throws Exception
     */
    public function suppliersRemoveAction(
        CurrentUser  $currentUser,
        UrlGenerator $urlGenerator,
                     $id,
                     $supplier_id
    ): RedirectResponse
    {
        $currentUser->authAdmin();

        $pm = new PublisherManager();

        $publisher = $pm->get(['publisher_id' => $id]);
        if (!$publisher) {
            throw new NotFoundException("Publisher $id not found.");
        }

        $sm = new SupplierManager();

        $supplier = $sm->get(['supplier_id' => $supplier_id]);
        if (!$supplier) {
            throw new NotFoundException("Supplier $id not found.");
        }

        $publisher->removeSupplier($supplier);

        $suppliersUrl = $urlGenerator->generate('publisher_suppliers', ['id' => $publisher->get('id')]);
        return new RedirectResponse($suppliersUrl);
    }
}
