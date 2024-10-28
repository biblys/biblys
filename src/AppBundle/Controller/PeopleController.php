<?php

namespace AppBundle\Controller;

use ArticleManager;
use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\CurrentUser;
use Biblys\Service\Images\ImagesService;
use Biblys\Service\Pagination;
use Biblys\Service\QueryParamsService;
use Exception;
use Framework\Controller;
use People;
use PeopleManager;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PeopleController extends Controller
{
    /**
     * List all authors.
     *
     * @param Request $request
     * @return Response
     * @throws PropelException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function authorsAction(Request $request): Response
    {
        $request->attributes->set("page_title", 'Auteurs');

        $pm = new PeopleManager();
        $authors = $pm->getAllFromCatalog();

        return $this->render('AppBundle:People:authors.html.twig', [
            'authors' => $authors,
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     * @throws Exception
     */
    public function showAction(
        QueryParamsService $queryParams,
                           $slug
    ): RedirectResponse|Response
    {
        $globalSite = LegacyCodeHelper::getGlobalSite();

        $pm = new PeopleManager();
        $am = new ArticleManager();
        $people = $pm->get(['people_url' => $slug]);

        if (!$people) {
            throw new NotFoundException("People $slug not found");
        }

        $use_old_controller = $globalSite->getOpt('use_old_people_controller');
        if ($use_old_controller) {
            return new RedirectResponse("/legacy/p/$slug/");
        }

        // Pagination
        $queryParams->parse(["p" => ["type" => "numeric", "min" => 0, "default" => 0]]);
        $page = $queryParams->getInteger("p");
        $totalCount = $am->countAllFromPeople($people);
        $pagination = new Pagination($page, $totalCount);

        $articles = $am->getAllFromPeople($people, [
            'order' => 'article_pubdate',
            'sort' => 'desc',
            'limit' => $pagination->getLimit(),
            'offset' => $pagination->getOffset(),
        ]);

        // If not article is associated with this people, return 404
        // This is to prevent search engines to index empty people page
        // TODO: use meta tag or header instead?
        if (count($articles) === 0) {
            throw new NotFoundException("There are no article associated with people $slug");
        }

        return $this->render('AppBundle:People:show.html.twig', [
            'people' => $people,
            'articles' => $articles,
            'pages' => $pagination,
        ]);
    }

    /**
     * @route /admin/people/{id}/edit.
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function editAction(
        Request       $request,
        UrlGenerator  $urlGenerator,
        CurrentUser   $currentUser,
        ImagesService $imagesService,
        int           $id,
    ): RedirectResponse|Response
    {
        $currentUser->authAdmin();

        $pm = new PeopleManager();
        $people = $pm->get(['people_id' => $id]);
        if (!$people) {
            throw new NotFoundException("People $id not found.");
        }

        $request->attributes->set("page_title", "Modifier le contributeur " . $people->get('name'));

        $formFactory = $this->getFormFactory();

        $defaults = [
            'first_name' => $people->get('first_name'),
            'last_name' => $people->get('last_name'),
            'gender' => $people->get('gender'),
            'bio' => $people->get('bio'),
            'site' => $people->get('site'),
            'facebook' => $people->get('facebook'),
            'twitter' => $people->get('twitter'),
        ];

        $form = $formFactory->createBuilder(FormType::class, $defaults)
            ->add('first_name', TextType::class, ['label' => 'Prénom :', 'required' => false])
            ->add('last_name', TextType::class, ['label' => 'Nom :', 'required' => true])
            ->add('gender', ChoiceType::class, [
                'label' => 'Genre à utiliser pour les contributions :',
                'required' => true,
                'choices' => [
                    "" => null,
                    "Féminin (ex: autrice, illustratrice, etc.)" => "F",
                    "Masculin (ex: auteur, illustrateur, etc.)" => "M",
                    "Neutre (ex: auteur·trice, illustrateur·trice, etc.)" => "N",
                ]
            ])
            ->add('photo', FileType::class, ['label' => 'Photo (JPEG) :', 'required' => false])
            ->add('site', TextType::class, ['label' => 'Site web :', 'required' => false, 'attr' => ['placeholder' => 'http(s)://']])
            ->add('facebook', TextType::class, ['label' => 'Page Facebook :', 'required' => false, 'attr' => ['placeholder' => 'https://www.facebook.com/...']])
            ->add('twitter', TextType::class, ['label' => 'Compte Twitter :', 'required' => false, 'attr' => ['placeholder' => '@...']])
            ->add('bio', TextareaType::class, ['label' => false, 'attr' => ['class' => 'wysiwyg']])
            ->getForm();

        $error = false;
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $data = $form->getData();

            $updated = clone $people;
            $updated->set('people_first_name', $data['first_name'])
                ->set('people_last_name', $data['last_name'])
                ->set('people_gender', $data['gender'])
                ->set('people_bio', $data['bio'])
                ->set('people_site', $data['site'])
                ->set('people_facebook', $data['facebook'])
                ->set('people_twitter', $data['twitter']);

            try {
                /** @var People $updated */
                $updated = $pm->update($updated);

                if ($data["photo"] !== null) {
                    $imagesService->addImageFor($updated->getModel(), $data["photo"]->getPathname());
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
            }

            if (!$error) {
                return new RedirectResponse(
                    $urlGenerator->generate('people_show', ['slug' => $updated->get('url')])
                );
            }
        }

        return $this->render('AppBundle:People:edit.html.twig', [
            'people' => $people,
            'error' => $error,
            'form' => $form->createView(),
        ]);
    }
}
