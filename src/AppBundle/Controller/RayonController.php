<?php

namespace AppBundle\Controller;

use ArticleManager;
use Biblys\Article\Type;
use Biblys\Isbn\Isbn as Isbn;
use Biblys\Service\Pagination;
use Exception;
use Framework\Controller;
use Framework\Exception\AuthException;
use Propel\Runtime\Exception\PropelException;
use Rayon;
use RayonManager;
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

class RayonController extends Controller
{
    /**
     * @throws SyntaxError
     * @throws AuthException
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     */
    public function indexAction(Request $request): Response
    {
        self::authAdmin($request);

        $rm = new RayonManager();

        $request->attributes->set("page_title", "Rayons");

        $rayons = $rm->getAll([], ['order' => 'rayon_name']);

        return $this->render('AppBundle:Rayon:index.html.twig', [
            'rayons' => $rayons,
        ]);
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function showAction(Request $request, $url): Response
    {
        $rm = new RayonManager();
        $am = new ArticleManager();

        $rayon = $rm->get(['rayon_url' => $url]);
        if (!$rayon) {
            throw new NotFoundException("Rayon $url not found.");
        }

        $request->attributes->set("page_title", $rayon->get('name'));

        $pageNumber = (int) $request->query->get("p", 0);
        if ($pageNumber < 0) {
            throw new BadRequestHttpException("Page number must be a positive integer");
        }

        // Pagination
        $totalCount = $am->countAllFromRayon($rayon);
        $pagination = new Pagination($pageNumber, $totalCount);

        $articles = $am->getAllFromRayon($rayon, [
            'order' => 'article_pubdate',
            'sort' => 'desc',
            'limit' => $pagination->getLimit(),
            'offset' => $pagination->getOffset(),
        ]);

        return $this->render('AppBundle:Rayon:show.html.twig', [
            'rayon' => $rayon,
            'articles' => $articles,
            'pages' => $pagination,
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws AuthException
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     * @throws Exception
     */
    public function editAction(Request $request, UrlGenerator $urlGenerator, $id)
    {
        global $_SITE;

        self::authAdmin($request);

        $rm = new RayonManager();

        $rayon = $rm->get(['rayon_id' => $id, 'site_id' => $_SITE->get('id')]);
        if (!$rayon) {
            throw new NotFoundException("Rayon $id not found.");
        }

        $request->attributes->set("page_title", 'Modifier le rayon '.$rayon->get('name'));

        if ($request->getMethod() == 'POST') {
            $rayon->set('rayon_name', $request->request->get('name'))
                ->set('rayon_sort_by', $request->request->get('sort_by'))
                ->set('rayon_sort_order', $request->request->get('sort_order'))
                ->set('rayon_show_upcoming', $request->request->get('show_upcoming'))
                ->set('rayon_desc', $request->request->get('desc'));
            $rayon = $rm->update($rayon);

            return new RedirectResponse(
                $urlGenerator->generate("rayon_show", ["url" => $rayon->get("url")])
            );
        }

        return $this->render('AppBundle:Rayon:edit.html.twig', [
            'rayon' => $rayon,
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws AuthException
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     */
    public function newAction(Request $request, UrlGenerator $urlGenerator)
    {
        self::authAdmin($request);

        $rm = new RayonManager();

        $rayon = new Rayon([]);

        $request->attributes->set("page_title", 'Créer un nouveau rayon ');

        $error = null;
        if ($request->getMethod() == 'POST') {
            try {
                $rayon = $rm->create([
                    'rayon_name' => $request->request->get('name'),
                    'rayon_sort_by' => $request->request->get('sort_by'),
                    'rayon_sort_order' => $request->request->get('sort_order'),
                    'rayon_show_upcoming' => $request->request->get('show_upcoming'),
                    'rayon_desc' => $request->request->get('desc'),
                ]);

                // Update rayon to set its slug (temp. fix)
                $rayon = $rm->update($rayon);
            } catch (Exception $e) {
                $error = $e->getMessage();
            }

            if (!$error) {
                return new RedirectResponse(
                    $urlGenerator->generate("rayon_show", ["url" => $rayon->get("url")])
                );
            }
        }

        return $this->render('AppBundle:Rayon:new.html.twig', [
            'rayon' => $rayon,
            'error' => $error,
        ]);
    }

    /**
     * @throws AuthException
     * @throws PropelException
     * @throws Exception
     */
    public function deleteAction(
        Request $request,
        UrlGenerator $urlGenerator,
        $id
    ): RedirectResponse
    {
        self::authAdmin($request);

        $rm = new RayonManager();

        $rayon = $rm->getById($id);
        if (!$rayon) {
            throw new NotFoundException("Rayon $id not found.");
        }

        $rm->delete($rayon);

        return new RedirectResponse($urlGenerator->generate("rayon_index"));
    }

    /**
     * Adding an Article to a Rayon
     * /rayon/:id/add.
     *
     * @param Request $request
     * @param Session $session
     * @param UrlGenerator $urlGenerator
     * @param $id
     * @return RedirectResponse|Response
     * @throws AuthException
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function addArticleAction(
        Request $request,
        Session $session,
        UrlGenerator $urlGenerator,
        $id
    )
    {
        global $_SITE;

        self::authPublisher($request, null);

        $rm = new RayonManager();

        $rayon = $rm->get(['rayon_id' => $id, 'site_id' => $_SITE->get('id')]);
        if (!$rayon) {
            throw new NotFoundException("Rayon $id not found.");
        }

        if ($request->getMethod() == 'POST') {
            $am = new ArticleManager();

            // Get params from POST request
            $article_ean = $request->request->get('article_ean', false);
            $article_id = $request->request->get('article_id', false);
            $collection_id = $request->request->get('collection_id', false);
            $article_type = $request->request->get('article_type', false);

            // Get articles to add
            $articleWhere = [];
            if ($article_ean) {
                $articleWhere['article_ean'] = Isbn::convertToEan13($article_ean);
            } elseif ($article_id) {
                $articleWhere['article_id'] = $article_id;
            } elseif ($collection_id) {
                $articleWhere['collection_id'] = $collection_id;
            } elseif ($article_type) {
                $articleWhere['type_id'] = $article_type;
            }

            $articles = $am->getAll($articleWhere, [], false);

            $articlesAdded = [];
            $errors = [];
            foreach ($articles as $article) {
                $error = false;
                try {
                    $articlesAdded[] = $rayon->addArticle($article);
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }

                if (!$error) {
                    $articlesAdded[] = $article->get('title');
                } else {
                    $errors[] = $error;
                }
            }

            $session->getFlashBag()->add('articlesAdded', $articlesAdded);
            $session->getFlashBag()->add('errors', $errors);

            return new RedirectResponse(
                $urlGenerator->generate("rayon_add", ["id" => $rayon->get('id')])
            );
        }

        $request->attributes->set("page_title", 'Ajouter au rayon '.$rayon->get('name'));

        $types = Type::getAll();

        return $this->render('AppBundle:Rayon:addArticle.html.twig', [
            'rayon' => $rayon,
            'added' => $request->query->get("added"),
            'not_added' => $request->query->get("not_added"),
            'types' => $types,
        ]);
    }

    /**
     * Display all articles in a Rayon.
     * @param Request $request
     * @param $id
     * @return Response
     * @throws AuthException
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function rayonArticlesAction(Request $request, $id): Response
    {
        self::authAdmin($request);

        $rm = new RayonManager();

        $rayon = $rm->get(['rayon_id' => $id]);
        if (!$rayon) {
            throw new NotFoundException("Rayon $id not found.");
        }

        $am = new ArticleManager();
        $articles = $am->getAllFromRayon($rayon, [
            'fields' => 'article_id, article_title, article_authors, article_publisher, publisher_id'
        ], false);

        return $this->render('AppBundle:Rayon:articles.html.twig', [
            'rayon' => $rayon,
            'articles' => $articles,
        ]);
    }

    /**
     * Remove an article from a Rayon.
     * @throws AuthException
     * @throws PropelException
     */
    public function removeArticleAction(
        Request $request,
        UrlGenerator $urlGenerator,
        $rayon_id
    ): RedirectResponse
    {
        self::authAdmin($request);

        $rm = new RayonManager();
        $rayon = $rm->get(['rayon_id' => $rayon_id]);
        if (!$rayon) {
            throw new NotFoundException("Rayon $rayon_id not found.");
        }

        $am = new ArticleManager();
        $article = $am->get(['rayon_id' => $rayon_id]);
        if (!$article) {
            throw new NotFoundException("Article $rayon_id not found.");
        }

        $rayon->removeArticle($article);

        return new RedirectResponse(
            $urlGenerator->generate("rayon_articles", ["id" => $rayon->get('id'),])
        );
    }
}
