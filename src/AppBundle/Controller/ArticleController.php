<?php

namespace AppBundle\Controller;

use Biblys\Exception\ArticleAlreadyInRayonException;
use Biblys\Isbn\Isbn;
use Biblys\Isbn\IsbnParsingException;
use Exception;
use Framework\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;

class ArticleController extends Controller
{
    public function showAction(Request $request, $slug)
    {
        global $site, $urlgenerator;

        $am = $this->entityManager('Article');
        $article = $am->get(['article_url' => $slug]);

        if (!$article) {
            $request->attributes->set('ArticleNotFound', true);
            throw new NotFoundException("Article $slug not found.");
        }

        $use_old_controller = $site->getOpt('use_old_article_controller');
        if ($use_old_controller) {
            return $this->redirect('/'.$slug);
        }

        $request->attributes->set(
            "page_title",
            $article->get('title').' de '.
            authors($article->get('authors')).' ('.
            $article->get('publisher')->get('name').')'
        );

        // Opengraph tags
        $opengraphTags = [
            'type' => 'book',
            'title' => $article->get('title'),
            'url' => $request->getScheme().'://'.$request->getHost().
                $urlgenerator->generate('article_show', ['slug' => $article->get('url')]),
            'description' => truncate(strip_tags($article->get('summary')), '500', '...', true),
        ];
        if ($article->hasCover()) {
            $opengraphTags['image'] = $article->getCoverUrl();
        }
        if ($article->has('ean')) {
            $opengraphTags['isbn'] = $article->get('ean');
        }
        if ($article->has('pubdate')) {
            $opengraphTags['release_date'] = $article->get('pubdate');
        }
        if ($article->has('updated')) {
            $opengraphTags['updated_time'] = $article->get('updated');
        }
        $this->setOpengraphTags($opengraphTags);

        // Twitter Cards tags
        $twitterCardsTags = [
            'title' => $article->get('title'),
            'description' => truncate(strip_tags($article->get('summary')), '500', '...', true),
        ];
        if ($article->hasCover()) {
            $twitterCardsTags['image'] = $article->getCoverUrl();
            $twitterCardsTags['image:alt'] = $article->get('title');
        }
        $this->setTwitterCardsTags($twitterCardsTags);

        return $this->render('AppBundle:Article:show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * Search results page.
     *
     * @param string $terms search terms
     *
     * @return Response
     */
    public function searchAction(Request $request)
    {
        $am = $this->entityManager('Article');

        $query = $request->query->get('q', null);

        $error = false;
        $articles = [];
        $count = 0;
        $pagination = null;

        if ($query !== null && strlen($query) < 3) {
            $error = 'Le terme de recherche est trop court (trois caractères minimum).';
        } elseif ($query !== null) {
            $count = $am->countSearchResults($query);
            $page = (int) $request->query->get('p', 0);
            $pagination = new \Biblys\Service\Pagination($page, $count);

            $articles = $am->search($query, [
                'order' => 'article_pubdate',
                'sort' => 'desc',
                'limit' => $pagination->getLimit(),
                'offset' => $pagination->getOffset(),
            ]);
        }

        $request->attributes->set("page_title", "Recherche");
        if ($query) {
            $request->attributes->set("page_title", "Recherche de ".$query);
        }

        return $this->render('AppBundle:Article:search.html.twig', [
            'articles' => $articles,
            'pages' => $pagination,
            'count' => $count,
            'error' => $error,
            'query' => $query,
        ]);
    }

    public function freeDownloadAction(Request $request, $id)
    {
        global $_V, $site;

        $am = $this->entityManager('Article');
        $article = $am->getById($id);

        if (!$article) {
            throw new NotFoundException("L'article $id n'existe pas.");
        }

        if (!$article->isDownloadable()) {
            throw new NotFoundException($article->get('title')." n'est pas téléchargeable.");
        }

        if (!$article->isFree()) {
            throw new NotFoundException($article->get('title')." n'est pas gratuit.");
        }

        if (!$article->isAvailable()) {
            throw new NotFoundException($article->get('title')." n'est pas disponible.");
        }

        // If article is already in library, redirect to library page
        if ($_V->hasPurchased($article)) {
            return $this->redirect('/pages/log_myebooks');
        }

        $this->setPageTitle('Téléchargement gratuit de '.$article->get('title'));

        $newsletter = false;
        $newsletter_checked = false;
        if ($site->getOpt('newsletter') == 1) {
            $newsletter = true;
            if ($_V->isLogged()) {
                $mm = $this->entityManager('Mailing');
                $mailing = $mm->get(['mailing_email' => $_V->get('email')]);
                if ($mailing && $mailing->hasUnsubscribed()) {
                    $newsletter_checked = null;
                }
            }
        }

        if ($request->getMethod() == 'POST') {
            // Subscribe to newsletter
            $newsletter_checked = $request->request->get('newsletter', false);
            $email = $_V->get('email');
            if ($newsletter_checked) {
                try {
                    $result = $mm->addSubscriber($email, true);
                } catch (Exception $e) {
                    // Ignore errors
                }
            }

            // Add book to library
            $um = $this->entityManager('User');
            $current_user = $um->getById($_V->get('id'));
            $um->addToLibrary($current_user, [$article], [], false, ['send_email' => false]);
            $success = 'Le livre '.$article->get('title').' a bien été ajouté à votre bibliothèque !';

            return $this->redirect('/pages/log_myebooks');
        }

        return $this->render('AppBundle:Article:freeDownload.html.twig', [
            'article' => $article,
            'newsletter' => $newsletter,
            'newsletter_checked' => $newsletter_checked,
        ]);
    }

    /**
     * Mark an article for deletion.
     *
     * @param int $id id of article to delete
     *
     * @return Response
     */
    public function deleteAction(Request $request, $id)
    {
        global $_SQL;

        $this->auth('publisher');

        $am = $this->entityManager('Article');

        $article = $am->getById($id);
        if (!$article) {
            throw new NotFoundException("L'article $id n'existe pas.");
        }

        $error = null;
        if ($request->getMethod() == 'POST') {
            $article->set('article_deletion_by', $this->user->get('id'));
            $article->set('article_deletion_date', date('Y-m-d H:i:s'));
            $article->set('article_deletion_reason', $request->request->get('reason'));
            $am->update($article);

            try {
                $am->delete($article);
            } catch (Exception $e) {
                $error = $e->getMessage();
            }

            if (!$error) {
                return $this->redirect($this->generateUrl('article_deleted', ['title' => $article->get('title')]));
            }
        }

        $this->setPageTitle("Suppression de l'article ".$article->get('title'));

        return $this->render('AppBundle:Article:delete.html.twig', [
            'article' => $article,
            'error' => $error,
        ]);
    }

    /**
     * Show that a book has been deleted.
     *
     * @param string $title the book's title
     *
     * @return Response
     */
    public function deletedAction(Request $request)
    {
        $title = $request->query->get('title');

        return $this->render('AppBundle:Article:deleted.html.twig', [
            'title' => $title,
        ]);
    }

    /**
     * List article marked for deletions (root only).
     *
     * @return Response
     */
    public function deletionsAction(Request $request)
    {
        $this->auth('root');
        $request->attributes->set("page_title", "Articles à supprimer");

        $am = $this->entityManager('Article');

        $articles = $am->getAll(['article_deletion_by' => 'NOT NULL']);

        return $this->render('AppBundle:Article:deletions.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * List article marked for deletions (root only).
     *
     * @return Response
     */
    public function pushToDataAction()
    {
        $this->auth('root');

        $am = $this->entityManager('Article');

        $articlesToPush = $am->getArticlesToBePushedToData();
        $articlesToPushCount = count($articlesToPush);

        $this->setPageTitle($articlesToPushCount + ' articles à envoyer à Biblys Data');

        $pushedArticle = null;
        if ($articlesToPushCount) {
            $pushedArticle = $articlesToPush[0];
            $am->pushToDataServer($pushedArticle);
        }

        if ($articlesToPushCount > 99) {
            $articlesToPushCount = '100+';
        }

        return $this->render('AppBundle:Article:pushToData.html.twig', [
            'articlesToPush' => $articlesToPushCount,
            'pushedArticle' => $pushedArticle,
        ]);
    }

    /**
     * Add tags to an article via an XHR request
     * /articles/{id}/tags/add.
     *
     * @return Response
     */
    public function addTagsAction(Request $request, $id)
    {
        $am = $this->entityManager('Article');
        $tm = $this->entityManager('Tag');
        $lm = $this->entityManager('Link');

        $am->setIgnoreSiteFilters(true);

        $article = $am->getById($id);
        if (!$article) {
            throw new Exception("Article $id not found");
        }

        if ($article->has('publisher_id')) {
            $this->auth('publisher', $article->get('publisher_id'));
        }

        $links = [];

        $tags = $request->request->get('tags');
        $tags = explode(',', $tags);
        foreach ($tags as $tag_name) {
            $tag_name = trim($tag_name);

            if (empty($tag_name)) {
                continue;
            }

            // Search if tag already exists
            $tag = $tm->search($tag_name);

            // Else, create a new one
            if ($tag === null) {
                $tag_url = makeurl($tag_name);
                $tag = $tm->create([
                    'tag_name' => $tag_name,
                    'tag_url' => $tag_url,
                ]);
            }

            // Search if link between tag and article already exists
            $link = $lm->get(['article_id' => $article->get('id'), 'tag_id' => $tag->get('id')]);

            // Else, create a new one
            if (!$link) {
                $link = $lm->create([
                    'article_id' => $article->get('id'),
                    'tag_id' => $tag->get('id'),
                ]);
                $links[] = ['id' => $link->get('id'), 'tag_name' => $tag->get('name')];
            }
        }

        return new JsonResponse(['links' => $links]);
    }

    /**
     * Add rayon to an article via an XHR request
     * /articles/{id}/rayons/add.
     *
     * @return JsonResponse
     */
    public function addRayonsAction(Request $request, $id)
    {
        $am = new \ArticleManager();
        $rm = new \RayonManager();

        $am->setIgnoreSiteFilters(true);

        $article = $am->getById($id);
        if (!$article) {
            throw new Exception("Article $id not found");
        }

        if ($article->has('publisher_id')) {
            $this->auth('publisher', $article->get('publisher_id'));
        }

        $rayon_id = $request->request->get('rayon_id');
        $rayon = $rm->getById($rayon_id);
        if (!$rayon) {
            throw new Exception("Rayon $id not found");
        }

        try {
            $link = $am->addRayon($article, $rayon);
        } catch (ArticleAlreadyInRayonException $e) {
            return new JsonResponse([], 409);
        }

        return new JsonResponse([
            'id' => $link->get('id'),
            'rayon_name' => $rayon->get('name'),
        ]);
    }

    /**
     * Refresh articles search terms
     * /admin/articles/search-terms.
     *
     * @return Response
     */
    public function searchTermsAction(Request $request, UrlGenerator $urlGenerator)
    {
        self::authAdmin($request);
        $request->attributes->set("page_title", "Termes de recherche");

        $am = $this->entityManager('Article');
        $total = $am->count([
            'article_keywords_generated' => 'NULL',
            'article_title' => 'NOT NULL',
            'article_url' => 'NOT NULL',
        ]);

        if ($request->getMethod() == 'POST') {
            $articles = $am->getAll();
            foreach ($articles as $article) {
                $article->set('article_keywords_generated', null);
                $am->update($article);
            }

            return $this->redirect($this->generateUrl('article_search_terms'));
        }

        if ($request->isXmlHttpRequest()) {
            $articles = $am->getAll([
                'article_keywords_generated' => 'NULL',
                'article_title' => 'NOT NULL',
                'article_url' => 'NOT NULL',
            ], ['limit' => 100]);

            return new JsonResponse([
                'count' => $total,
                'articles' => array_map(function ($article) {
                    return [
                        'id' => $article->get('id'),
                        'title' => $article->get('title'),
                        'url' => $urlGenerator->generate('article_show', [
                            'slug' => $article->get('url'),
                        ]),
                    ];
                }, $articles),
            ]);
        }

        return $this->render('AppBundle:Article:searchTerms.html.twig', [
            'total' => $total,
        ]);
    }

    /**
     * Refresh an article's search terms
     * /admin/articles/{id}/refresh-search-terms.
     *
     * @return JsonResponse
     */
    public function refreshSearchTermsAction($id)
    {
        $this->auth('admin');

        $am = $this->entityManager('Article');
        $article = $am->getById($id);

        if (!$article) {
            throw new NotFoundException("Article $id not found.");
        }

        $article = $am->refreshMetadata($article);
        $am->update($article);

        return new JsonResponse(['terms' => $article->get('keywords')]);
    }

    /**
     * Find article by ISBN
     * /isbn/{ean}.
     *
     * @param UrlGenerator|null $urlGenerator
     * @param string $ean An article's ISBN in EAN format
     * @return Response
     */
    public function byIsbn(UrlGenerator $urlGenerator, string $ean)
    {
        try {
            $ean = Isbn::convertToEan13($ean);
        } catch (IsbnParsingException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }

        $am = $this->entityManager('Article');
        $article = $am->get(['article_ean' => $ean]);
        if (!$article) {
            throw new NotFoundException("Article with ISBN $ean not found.");
        }

        $articleUrl = $urlGenerator->generate(
            'article_show',
            ['slug' => $article->get('url')]
        );

        return $this->redirect($articleUrl, 301);
    }

    /**
     * List all articles in catalog.
     *
     * @return Response
     */
    public function adminCatalog(Request $request)
    {
        $am = $this->entityManager('Article');
        $articles = $am->getAll();

        $request->attributes->set("page_title", "Catalogue");

        return $this->render('AppBundle:Article:articleAdminCatalog.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * Check that ISBN is valid and isn't used.
     *
     * @return Response
     */
    public function checkIsbn(Request $request)
    {

        $am = $this->entityManager('Article');
        $content = $request->getContent();
        $params = json_decode($content, true);

        try {
            $ean = Isbn::convertToEan13($params['article_ean']);
            $am->checkIsbn($params['article_id'], $ean);
        } catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return new JsonResponse(['isbn' => $ean]);
    }

    /**
     * Update article's publisher stock property
     *
     * @return Response
     */
    public function updatePublisherStock(Request $request, int $articleId)
    {
        $am = new \ArticleManager();

        $requestBody = $request->getContent();
        $publisherStock = (int) $requestBody;
        if (!is_int($publisherStock) || $requestBody === "NaN") {
            throw new BadRequestHttpException("article_publisher_stock $requestBody is not an integer.");
        }

        $article = $am->getById($articleId);
        if (!$article) {
            throw new BadRequestHttpException("Impossible de trouver l'article $articleId.");
        }

        $article->set("article_publisher_stock", $publisherStock);
        $am->update($article);

        return new JsonResponse();
    }
}
