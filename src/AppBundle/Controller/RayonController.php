<?php

namespace AppBundle\Controller;

use ArticleManager;
use Biblys\Isbn\Isbn as Isbn;
use Framework\Controller;
use Framework\Exception\AuthException;
use Propel\Runtime\Exception\PropelException;
use RayonManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;

class RayonController extends Controller
{
    public function indexAction(Request $request)
    {
        $this->auth('admin');

        $rm = $this->entityManager('Rayon');

        $request->attributes->set("page_title", "Rayons");

        $rayons = $rm->getAll([], ['order' => 'rayon_name']);

        return $this->render('AppBundle:Rayon:index.html.twig', [
            'rayons' => $rayons,
        ]);
    }

    public function showAction(Request $request, $url)
    {
        global $site;

        $rm = $this->entityManager('Rayon');
        $am = $this->entityManager('Article');

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
        $pagination = new \Biblys\Service\Pagination($pageNumber, $totalCount);

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

    public function editAction(Request $request, $id)
    {
        global $site;

        $this->auth('admin');

        $rm = $this->entityManager('Rayon');

        $rayon = $rm->get(['rayon_id' => $id, 'site_id' => $site->get('id')]);
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

            return new RedirectResponse($this->generateUrl('rayon_show', ['url' => $rayon->get('url')]));
        }

        return $this->render('AppBundle:Rayon:edit.html.twig', [
            'rayon' => $rayon,
        ]);
    }

    public function newAction(Request $request)
    {
        global $site;

        $this->auth('admin');

        $rm = $this->entityManager('Rayon');

        $rayon = new \Rayon([]);

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

                // Update rayon to set it's slug (temp. fix)
                $rayon = $rm->update($rayon);
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }

            if (!$error) {
                return $this->redirect($this->generateUrl('rayon_show', ['url' => $rayon->get('url')]));
            }
        }

        return $this->render('AppBundle:Rayon:new.html.twig', [
            'rayon' => $rayon,
            'error' => $error,
        ]);
    }

    public function deleteAction($id)
    {
        $this->auth('admin');

        $rm = $this->entityManager('Rayon');

        $rayon = $rm->getById($id);
        if (!$rayon) {
            throw new NotFoundException("Rayon $id not found.");
        }

        $rm->delete($rayon);

        return new RedirectResponse($this->generateUrl('rayon_index'));
    }

    /**
     * Adding an Article to a Rayon
     * /rayon/:id/add.
     *
     * @param [Integer] $id contains the Rayon's id
     */
    public function addArticleAction(Request $request, $id)
    {
        global $site, $session;

        $this->auth('publisher');

        $rm = $this->entityManager('Rayon');

        $rayon = $rm->get(['rayon_id' => $id, 'site_id' => $site->get('id')]);
        if (!$rayon) {
            throw new NotFoundException("Rayon $id not found.");
        }

        if ($request->getMethod() == 'POST') {
            $am = $this->entityManager('Article');

            // Get params from POST request
            $article_ean = $request->request->get('article_ean', false);
            $article_id = $request->request->get('article_id', false);
            $collection_id = $request->request->get('collection_id', false);
            $article_type = $request->request->get('article_type', false);

            // Get articles to add
            if ($article_ean) {
                $article_where['article_ean'] = Isbn::convertToEan13($article_ean);
            } elseif ($article_id) {
                $article_where['article_id'] = $_POST['article_id'];
            } elseif ($collection_id) {
                $article_where['collection_id'] = $_POST['collection_id'];
            } elseif ($article_type) {
                $article_where['type_id'] = $_POST['article_type'];
            }

            $articles = $am->getAll($article_where, [], false);

            $articles_added = [];
            $errors = [];
            foreach ($articles as $article) {
                $error = false;
                try {
                    $added = $rayon->addArticle($article);
                } catch (\Exception $e) {
                    $error = $e->getMessage();
                }

                if (!$error) {
                    $articles_added[] = $article->get('title');
                } else {
                    $errors[] = $error;
                }
            }

            $session->getFlashBag()->add('articlesAdded', $articles_added);
            $session->getFlashBag()->add('errors', $errors);

            return new RedirectResponse($this->generateUrl('rayon_add', [
                'id' => $rayon->get('id'),
            ]));
        }

        $request->attributes->set("page_title", 'Ajouter au rayon '.$rayon->get('name'));

        $types = \Biblys\Article\Type::getAll();

        return $this->render('AppBundle:Rayon:addArticle.html.twig', [
            'rayon' => $rayon,
            'added' => $request->query->get('added', null),
            'not_added' => $request->query->get('not_added', null),
            'types' => $types,
        ]);
    }

    /**
     * Display all articles in a Rayon.
     * @throws AuthException
     * @throws PropelException
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
     */
    public function removeArticleAction($rayon_id, $article_id)
    {
        $this->auth('admin');

        $rm = $this->entityManager('Rayon');
        $rayon = $rm->get(['rayon_id' => $rayon_id]);
        if (!$rayon) {
            throw new NotFoundException("Rayon $rayon_id not found.");
        }

        $am = $this->entityManager('Article');
        $article = $am->get(['rayon_id' => $rayon_id]);
        if (!$article) {
            throw new NotFoundException("Article $rayon_id not found.");
        }

        $rayon->removeArticle($article);

        return new RedirectResponse($this->generateUrl('rayon_articles', [
            'id' => $rayon->get('id'),
        ]));
    }
}
