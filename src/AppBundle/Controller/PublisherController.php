<?php /** @noinspection PhpUnused */

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
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\Images\ImagesService;
use Biblys\Service\Pagination;
use Biblys\Service\QueryParamsService;
use Biblys\Service\TemplateService;
use CollectionManager;
use Exception;
use Framework\Controller;
use Model\Publisher;
use Model\PublisherQuery;
use Model\Right;
use Model\RightQuery;
use Model\User;
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
use Symfony\Component\Form\FormInterface;
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
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function indexAction(
        Request         $request,
        CurrentSite     $currentSite,
        TemplateService $templateService,
    ): Response
    {
        $pm = new PublisherManager();

        $pageNumber = (int)$request->query->get("p", 0);
        if ($pageNumber < 0) {
            throw new BadRequestHttpException("Page number must be a positive integer");
        }

        $totalCount = $pm->count();
        $limit = $currentSite->getOption('publisher_per_page') ? $currentSite->getOption('publisher_per_page') : 100;
        $pagination = new Pagination($pageNumber, $totalCount, $limit);

        $publishers = $pm->getAll([], [
            'order' => 'publisher_name_alphabetic',
            'limit' => $pagination->getLimit(),
            'offset' => $pagination->getOffset(),
        ]);

        return $templateService->renderResponse('AppBundle:Publisher:index.html.twig', [
            'publishers' => $publishers,
            'pages' => $pagination,
        ]);
    }

    /**
     * Show a Publisher's page and related articles
     * @route /editeur/{slug}.
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function showAction(
        Request         $request,
        CurrentSite     $currentSite,
        TemplateService $templateService,
        string          $slug
    ): Response
    {
        $pm = new PublisherManager();
        $am = new ArticleManager();

        $publisher = $pm->get(['publisher_url' => $slug]);
        if (!$publisher) {
            throw new NotFoundException("Publisher $slug not found");
        }

        $use_old_controller = $currentSite->getOption('use_old_publisher_controller');
        if ($use_old_controller) {
            return new RedirectResponse('/o/editeur/' . $slug);
        }

        $publisher_filter = $currentSite->getOption('publisher_filter');
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
        $articlesPerPage = $currentSite->getOption("articles_per_page", 10);

        try {
            $pagination = new Pagination($page, $totalCount, $articlesPerPage);
        } catch (InvalidArgumentException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }

        $articles = $am->getAll(['publisher_id' => $publisher->get('id')], [
            'order' => 'article_pubdate',
            'sort' => 'desc',
            'limit' => $pagination->getLimit(),
            'offset' => $pagination->getOffset(),
        ]);

        return $templateService->renderResponse('AppBundle:Publisher:show.html.twig', [
            'publisher' => $publisher,
            'articles' => $articles,
            'pages' => $pagination,
            'collections' => $collections,
        ]);
    }

    /**
     * @route GET/POST /admin/publishers/new
     *
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function newAction(
        Request         $request,
        CurrentUser     $currentUser,
        ImagesService   $imagesService,
        URLGenerator    $urlGenerator,
        TemplateService $templateService,
    ): Response
    {
        $currentUser->authAdmin();
        $form = $this->_getPublisherForm();

        if ($request->getMethod() === "POST") {

            try {
                $publisher = $this->_handleRequest($form, $request, $imagesService);
            } catch (Exception $exception) {
                return $templateService->renderResponse('AppBundle:Publisher:new.html.twig', [
                    "form" => $form->createView(),
                    "error" => $exception->getMessage(),
                ], isPrivate: true);
            }

            $url = $urlGenerator->generate("publisher_show", ["slug" => $publisher->getUrl()]);
            return new RedirectResponse($url);
        }

        return $templateService->renderResponse('AppBundle:Publisher:new.html.twig', [
            "form" => $form->createView(),
            "error" => null,
        ], isPrivate: true);
    }

    /**
     * Edit a publisher
     * @route GET/POST /admin/publisher/{id}/edit.
     *
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function editAction(
        Request         $request,
        CurrentUser     $currentUser,
        UrlGenerator    $urlGenerator,
        ImagesService   $imagesService,
        TemplateService $templateService,
        int             $id
    ): Response
    {
        $currentUser->authAdmin();

        $publisher = PublisherQuery::create()->findPk($id);
        if (!$publisher) {
            throw new NotFoundException("Publisher $id not found.");
        }

        $form = $this->_getPublisherForm(defaults: [
            "name" => $publisher->getName(),
            "email" => $publisher->getEmail(),
            "desc" => $publisher->getDesc(),
        ]);

        if ($request->getMethod() === "POST") {

            try {
                $publisher = $this->_handleRequest($form, $request, $imagesService, $publisher);
            } catch (Exception $exception) {
                return $templateService->renderResponse("AppBundle:Publisher:edit.html.twig", [
                    "publisher" => $publisher,
                    "error" => $exception->getMessage(),
                    "form" => $form->createView(),
                ], isPrivate: true);
            }

            $url = $urlGenerator->generate("publisher_show", ["slug" => $publisher->getUrl()]);
            return new RedirectResponse($url);
        }

        return $templateService->renderResponse("AppBundle:Publisher:edit.html.twig", [
            "form" => $form->createView(),
            "publisher" => $publisher,
            "error" => null,
        ], isPrivate: true);
    }

    /**
     * Delete a publisher.
     *
     * @route GET /admin/publisher/{id}/delete
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function deleteAction(
        Request         $request,
        CurrentUser     $currentUser,
        UrlGenerator    $urlGenerator,
        TemplateService $templateService,
        int             $id,
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

        return $templateService->renderResponse("AppBundle:Publisher:delete.html.twig", [
            "publisher" => $publisher,
            "collections" => $collections,
            "articles" => $articles,
            "error" => $error,
        ], isPrivate: true
        );
    }

    /**
     * Publisher deletion confirm page.
     *
     * @route GET /admin/publisher/deleted
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function deletedAction(Request $request, TemplateService $templateService): Response
    {
        $name = $request->query->get('name');

        return $templateService->renderResponse(
            'AppBundle:Publisher:deleted.html.twig',
            ['name' => $name],
            isPrivate: true,
        );
    }

    /**
     * Manager a publisher's rights
     * @route /admin/publisher/{id}/rights.
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function rightsAction(CurrentUser $currentUser, TemplateService $templateService, int $id): Response
    {
        $currentUser->authAdmin();

        $publisher = PublisherQuery::create()->findPk($id);
        if (!$publisher) {
            throw new NotFoundException("Publisher $id not found.");
        }

        return $templateService->renderResponse("AppBundle:Publisher:rights.html.twig", [
            "publisher" => $publisher,
        ], isPrivate: true);
    }

    /**
     * Give a user the right to manage a publisher.
     *
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
        $user = UserQuery::create()
            ->filterBySite($currentSite->getSite())
            ->findOneByEmail($userEmail);
        if (!$user) {
            $user = new User();
            $user->setEmail($userEmail);
            $user->setSite($currentSite->getSite());
            $user->save();
            $session->getFlashBag()->add("success", "Le compte utilisateur $userEmail a été créé.");
        }

        $existingRight = RightQuery::create()
            ->filterBySite($currentSite->getSite())
            ->filterByUser($user)
            ->filterByPublisher($publisher)
            ->findOne();
        if ($existingRight) {
            $session->getFlashBag()->add(
                "warning",
                "Le compte utilisateur $userEmail était déjà autorisé pour cet éditeur."
            );
            return new RedirectResponse($publisherRightsUrl);
        }

        $right = new Right();
        $right->setPublisher($publisher);
        $right->setUser($user);
        $right->setSite($currentSite->getSite());
        $right->save();

        $session->getFlashBag()->add(
            "success",
            "Le compte utilisateur $userEmail peut désormais gérer cet éditeur."
        );
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
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function suppliersAction(CurrentUser $currentUser, TemplateService $templateService, int $id): Response
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

        return $templateService->renderResponse("AppBundle:Publisher:suppliers.html.twig", [
            "publisher" => $publisher,
            "suppliers" => $suppliers,
        ], isPrivate: true);
    }

    /**
     * Add a publisher's supplier.
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

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     */
    public function adminAction(
        CurrentUser        $currentUser,
        QueryParamsService $paramsService,
        TemplateService    $templateService,
    ): Response
    {
        $currentUser->authAdmin();

        $paramsService->parse(["p" => ["type" => "numeric", "min" => 0, "default" => 0]]);

        $publisherQuery = PublisherQuery::create();
        $count = $publisherQuery->count();

        $pagination = new Pagination($paramsService->getInteger("p"), $count, 100);
        $publishers = $publisherQuery
            ->offset($pagination->getOffset())
            ->limit($pagination->getLimit())
            ->orderByNameAlphabetic()
            ->find();

        return $templateService->renderResponse(
            "AppBundle:Publisher:admin.html.twig", [
            "publishers" => $publishers,
            "count" => $count,
            "pages" => $pagination,
        ], isPrivate: true
        );
    }

    /**
     * @param $defaults = []
     * @return FormInterface
     */
    private function _getPublisherForm(array $defaults = []): FormInterface
    {
        $formFactory = $this->getFormFactory();
        return $formFactory->createBuilder(FormType::class, $defaults)
            ->add('name', TextType::class, ['label' => 'Nom', 'required' => false])
            ->add('email', EmailType::class, ['label' => 'Adresse e-mail', 'required' => false])
            ->add('logo', FileType::class, ['label' => 'Logo', 'required' => false, "attr" => ["class" => "form-control-file"]])
            ->add('desc', TextareaType::class, ['label' => false, 'attr' => ['class' => 'wysiwyg']])
            ->getForm();
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    private function _handleRequest(
        FormInterface    $form,
        Request          $request,
        ImagesService    $imagesService,
        Publisher $publisher = null,
    ): Publisher
    {
        $form->handleRequest($request);
        $data = $form->getData();

        $publisher = $publisher ?? new Publisher();
        $publisher->setName($data["name"]);
        $publisher->setEmail($data["email"]);
        $publisher->setDesc($data["desc"]);

        if ($data["logo"] !== null) {
            $imagesService->addImageFor($publisher, $data["logo"]);
        }

        $publisher->save();

        return $publisher;
    }
}
