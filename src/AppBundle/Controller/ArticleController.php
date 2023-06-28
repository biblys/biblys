<?php

namespace AppBundle\Controller;

use ArticleManager;
use Biblys\Exception\ArticleAlreadyInRayonException;
use Biblys\Gleeph\GleephAPI;
use Biblys\Isbn\Isbn;
use Biblys\Isbn\IsbnParsingException;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\GleephService;
use Biblys\Service\LoggerService;
use Biblys\Service\MailingList\MailingListService;
use Biblys\Service\Pagination;
use Exception;
use Framework\Controller;
use InvalidArgumentException;
use LinkManager;
use Model\ArticleCategoryQuery;
use Model\ArticleQuery;
use Model\Link;
use Model\LinkQuery;
use Model\PublisherQuery;
use Model\StockQuery;
use Propel\Runtime\Exception\PropelException;
use Psr\Http\Client\ClientExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use TagManager;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use UserManager;

class ArticleController extends Controller
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     * @throws ClientExceptionInterface
     */
    public function showAction(
        Request $request,
        Config $config,
        CurrentSite $currentSiteService,
        UrlGenerator $urlGenerator,
        LoggerService $loggerService,
        $slug
    ): RedirectResponse|Response
    {
        $am = new ArticleManager();
        $article = $am->get(['article_url' => $slug]);

        if (!$article) {
            $request->attributes->set('ArticleNotFound', true);
            throw new NotFoundException("Article $slug not found.");
        }

        $use_old_controller = $currentSiteService->getOption('use_old_article_controller');
        if ($use_old_controller) {
            return new RedirectResponse("/".$slug);
        }

        $request->attributes->set(
            "page_title",
            $article->get('title').' de '.
            authors($article->get('authors')).' ('.
            $article->get('publisher')->get('name').')'
        );

        // Opengraph tags
        $summary = $article->get('summary') ?: "";
        $opengraphTags = [
            'type' => 'book',
            'title' => $article->get('title'),
            'url' => $request->getScheme().'://'.$request->getHost().
                $urlGenerator->generate('article_show', ['slug' => $article->get('url')]),
            'description' => truncate(strip_tags($summary), '500', '...', true),
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
            'description' => truncate(strip_tags($summary), '500', '...', true),
        ];
        if ($article->hasCover()) {
            $twitterCardsTags['image'] = $article->getCoverUrl();
            $twitterCardsTags['image:alt'] = $article->get('title');
        }
        $this->setTwitterCardsTags($twitterCardsTags);

        $similarArticles = [];
        $gleephConfig = $config->get("gleeph");
        if ($gleephConfig) {
            $gleephApi = new GleephAPI($gleephConfig["api_key"]);
            $gleephApi->setEnvironment($gleephConfig["environment"] ?? "prod");
            $gleephService = new GleephService($gleephApi, $currentSiteService, $loggerService);
            $similarArticles = $gleephService->getSimilarArticlesByEan(
                $article->get("ean"),
                numberOfSuggestions: 5,
            );
        }

        $articleModel = ArticleQuery::create()->findPk($article->get("id"));

        return $this->render("AppBundle:Article:show.html.twig", [
            "article" => $article,
            "articleModel" => $articleModel,
            "similarArticles" => $similarArticles,
        ]);
    }

    /**
     * Search results page.
     *
     * @route GET /articles/search
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws PropelException
     */
    public function searchAction(Request $request, CurrentSite $currentSite): Response
    {
        $am = new ArticleManager();

        $query = $request->query->get("q");
        $inStockFilter = $request->query->get("in-stock");

        $sort = $request->query->get("sort", "pubdate|desc");
        $sortArray = explode("|", $sort);
        if(count($sortArray) < 2) {
            throw new BadRequestHttpException("Option de tri '$sort' invalide.");
        }
        list($sortCriteria, $sortOrder) = $sortArray;

        if (!in_array($sortOrder, ["asc", "desc"])) {
            throw new BadRequestHttpException("Ordre de tri '$sortOrder' invalide.");
        }

        $sortOptions = [
            ["criteria" => "title", "field" => "article_title_alphabetic", "order" => "asc", "label" => "titre (△)"],
            ["criteria" => "title", "field" => "article_title_alphabetic", "order" => "desc", "label" => "titre (▽)"],
            ["criteria" => "authors", "field" => "article_authors_alphabetic", "order" => "asc", "label" => "auteur·trice (△)"],
            ["criteria" => "authors", "field" => "article_authors_alphabetic", "order" => "desc", "label" => "auteur·trice (▽)"],
            ["criteria" => "collection", "field" => "article_collection", "order" => "asc", "label" => "collection (△)"],
            ["criteria" => "collection", "field" => "article_collection", "order" => "desc", "label" => "collection (▽)"],
            ["criteria" => "number", "field" => "article_number", "order" => "asc", "label" => "numéro de collection (△)"],
            ["criteria" => "number", "field" => "article_number", "order" => "desc", "label" => "numéro de collection (▽)"],
            ["criteria" => "cycle", "field" => "article_cycle", "order" => "asc", "label" => "série (△)"],
            ["criteria" => "cycle", "field" => "article_cycle", "order" => "desc", "label" => "série (▽)"],
            ["criteria" => "tome", "field" => "article_tome", "order" => "asc", "label" => "numéro de volume (△)"],
            ["criteria" => "tome", "field" => "article_tome", "order" => "desc", "label" => "numéro de volume (▽)"],
            ["criteria" => "publisher", "field" => "article_publisher", "order" => "asc", "label" => "éditeur (△)"],
            ["criteria" => "publisher", "field" => "article_publisher", "order" => "desc", "label" => "éditeur (▽)"],
            ["criteria" => "pubdate", "field" => "article_pubdate", "order" => "asc", "label" => "date de publication (△)"],
            ["criteria" => "pubdate", "field" => "article_pubdate", "order" => "desc", "label" => "date de publication (▽)"],
        ];

        $selectedSortOption = array_filter($sortOptions, function($option) use($sortCriteria) {
            return $option["criteria"] === $sortCriteria;
        });
        if (!$selectedSortOption) {
            throw new BadRequestHttpException("Critère de tri '$sortCriteria' invalide.");
        }
        $selectedSortOptionField = current($selectedSortOption)["field"];

        $articles = [];
        $count = 0;
        $pagination = null;

        $request->attributes->set("page_title", "Recherche");

        if ($query) {
            $request->attributes->set("page_title", "Recherche de ".$query);
            $page = (int) $request->query->get("p", 0);
            $queryParams = ["q" => $query, "in-stock" => $inStockFilter, "sort" => $sort];

            if ($inStockFilter) {
                $count = $am->countSearchResultsForAvailableStock($query, $currentSite);
                try {
                    $pagination = new Pagination($page, $count);
                } catch (InvalidArgumentException $exception) {
                    throw new BadRequestHttpException($exception->getMessage());
                }
                $pagination->setQueryParams($queryParams);
                $articles = $am->searchWithAvailableStock($query, $currentSite, [
                    'order' => $selectedSortOptionField,
                    'sort' => $sortOrder,
                    'limit' => $pagination->getLimit(),
                    'offset' => $pagination->getOffset(),
                ]);
            } else {
                $count = $am->countSearchResults($query);
                try {
                    $pagination = new Pagination($page, $count);
                } catch (InvalidArgumentException $exception) {
                    throw new BadRequestHttpException($exception->getMessage());
                }
                $pagination->setQueryParams($queryParams);
                $articles = $am->search($query, [
                    'order' => $selectedSortOptionField,
                    'sort' => $sortOrder,
                    'limit' => $pagination->getLimit(),
                    'offset' => $pagination->getOffset(),
                ]);
            }
        }

        return $this->render('AppBundle:Article:search.html.twig', [
            'articles' => $articles,
            'pages' => $pagination,
            'count' => $count,
            'query' => $query,
            'inStockFilterChecked' => $inStockFilter ? "checked" : "",
            "sortOptions" => $sortOptions,
            "sortCriteria" => $sortCriteria,
            "sortOrder" => $sortOrder,
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    public function freeDownloadAction(
        Request $request,
        CurrentSite $currentSiteService,
        CurrentUser $currentUserService,
        MailingListService $mailingListService,
        $id,
    ): RedirectResponse|Response
    {
        Controller::authUser($request);

        $am = new ArticleManager();
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

        $currentUser = $currentUserService->getUser();
        $currentUserPurchasesForArticle = StockQuery::create()
            ->filterBySite($currentSiteService->getSite())
            ->filterByUser($currentUser)
            ->filterByArticleId($article->get("id"))
            ->count();
        if ($currentUserPurchasesForArticle > 0) {
            return new RedirectResponse("/pages/log_myebooks");
        }

        $request->attributes->set("page_title", "Téléchargement gratuit de {$article->get('title')}");

        $newsletter = false;
        $newsletter_checked = false;
        $mailingList = $mailingListService->getMailingList();
        if ($currentSiteService->getOption("newsletter") === "1") {
            $newsletter = true;
            if ($mailingList->hasContact($currentUser->getEmail())) {
                $newsletter_checked = null;
            }
        }

        if ($request->getMethod() == 'POST') {
            // Subscribe to newsletter
            $newsletter_checked = $request->request->get('newsletter', false);
            $email = $currentUser->getEmail();
            if ($newsletter_checked) {
                try {
                    $mailingList->addContact($email, true);
                } catch (Exception) {
                    // Ignore errors
                }
            }

            // Add book to library
            $um = new UserManager();
            $current_user = $um->getById($currentUser->getId());
            $um->addToLibrary($current_user, [$article], [], false, ['send_email' => false]);

            return new RedirectResponse("/pages/log_myebooks");
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
     * @route POST /admin/article/{id}/delete
     * @param Request $request
     * @param UrlGenerator $urlGenerator
     * @param CurrentSite $currentSite
     * @param int $id
     * @return Response
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function deleteAction(
        Request $request,
        UrlGenerator $urlGenerator,
        CurrentSite $currentSite,
        int $id
    ): Response
    {
        $article = ArticleQuery::create()->filterForCurrentSite($currentSite)->findOneById($id);
        if (!$article) {
            throw new NotFoundException("L'article $id n'existe pas.");
        }

        self::authPublisher($request, $article->getPublisher());

        $error = null;
        if ($request->getMethod() == 'POST') {
            try {
                $article->delete();
            } catch (Exception $e) {
                $error = $e->getMessage();
            }

            if (!$error) {
                return new RedirectResponse(
                    $urlGenerator->generate("article_deleted", ["title" => $article->getTitle()])
                );
            }
        }

        $request->attributes->set("page_title", "Suppression de l'article {$article->getTitle()}");

        return $this->render('AppBundle:Article:delete.html.twig', [
            'article' => $article,
            'error' => $error,
        ]);
    }

    /**
     * Show that a book has been deleted.
     *
     * @param Request $request
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws PropelException
     */
    public function deletedAction(Request $request): Response
    {
        $title = $request->query->get('title');

        return $this->render('AppBundle:Article:deleted.html.twig', [
            'title' => $title,
        ]);
    }

    /**
     * Add tags to an article via an XHR request
     * /articles/{id}/tags/add.
     *
     * @param Request $request
     * @param $id
     * @return Response
     * @throws PropelException
     * @throws Exception
     */
    public function addTagsAction(Request $request, $id): Response
    {
        self::authPublisher($request, null);

        $am = new ArticleManager();
        $tm = new TagManager();
        $lm = new LinkManager();

        $am->setIgnoreSiteFilters(true);

        $article = $am->getById($id);
        if (!$article) {
            throw new Exception("Article $id not found");
        }

        if ($article->has('publisher_id')) {
            $publisher = PublisherQuery::create()->findPk($article->get("publisher_id"));
            self::authPublisher($request, $publisher);
        }

        $links = [];

        $tags = $request->request->get('tags', "");
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
     * @param Request $request
     * @param CurrentSite $currentSite
     * @param $id
     * @return JsonResponse
     * @throws PropelException
     * @throws Exception
     */
    public function addRayonsAction(
        Request $request,
        CurrentSite $currentSite,
        $id
    ):
    JsonResponse
    {
        self::authPublisher($request, null);

        $article = ArticleQuery::create()->findPk($id);
        if (!$article) {
            throw new Exception("Article $id not found");
        }

        if ($article->getPublisher() !== null) {
            $publisher = PublisherQuery::create()->findPk($article->getPublisherId());
            self::authPublisher($request, $publisher);
        }

        $articleCategoryId = $request->request->get("rayon_id");
        $articleCategory = ArticleCategoryQuery::createForSite($currentSite)->findPk($articleCategoryId);
        if (!$articleCategory) {
            throw new BadRequestHttpException("Rayon $articleCategoryId not found");
        }

        try {
            $linkAlreadyExists = LinkQuery::create()
                ->filterByArticle($article)
                ->filterByArticleCategory($articleCategory)
                ->findOne();

            if ($linkAlreadyExists) {
                throw new ArticleAlreadyInRayonException(
                    articleTitle: $article->getTitle(),
                    rayonName: $articleCategory->getName(),
                );
            }

            $link = new Link();
            $link->setArticle($article);
            $link->setArticleCategory($articleCategory);
            $link->save();
        } catch (ArticleAlreadyInRayonException) {
            return new JsonResponse([], 409);
        }

        return new JsonResponse([
            'id' => $link->getId(),
            'rayon_name' => $articleCategory->getName(),
        ]);
    }

    /**
     * Refresh articles search terms
     * /admin/articles/search-terms.
     *
     * @param Request $request
     * @param UrlGenerator $urlGenerator
     * @return Response
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function searchTermsAction(Request $request, UrlGenerator $urlGenerator): Response
    {
        self::authAdmin($request);
        $request->attributes->set("page_title", "Termes de recherche");

        $am = new ArticleManager();
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

            return new RedirectResponse(
                $urlGenerator->generate("article_search_terms")
            );
        }

        if ($request->isXmlHttpRequest()) {
            $articles = $am->getAll([
                'article_keywords_generated' => 'NULL',
                'article_title' => 'NOT NULL',
                'article_url' => 'NOT NULL',
            ], ['limit' => 100]);

            return new JsonResponse([
                'count' => $total,
                'articles' => array_map(function ($article) use ($urlGenerator) {
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
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws PropelException
     * @throws Exception
     */
    public function refreshSearchTermsAction(Request $request, $id): JsonResponse
    {
        self::authAdmin($request);

        $am = new ArticleManager();
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
     * @param UrlGenerator $urlGenerator
     * @param string $ean An article's ISBN in EAN format
     * @return Response
     */
    public function byIsbn(UrlGenerator $urlGenerator, string $ean): Response
    {
        try {
            $ean = Isbn::convertToEan13($ean);
        } /** @noinspection PhpRedundantCatchClauseInspection */ catch (IsbnParsingException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }

        $am = new ArticleManager();
        $article = $am->get(['article_ean' => $ean]);
        if (!$article) {
            throw new NotFoundException("Article with ISBN $ean not found.");
        }

        $articleUrl = $urlGenerator->generate(
            'article_show',
            ['slug' => $article->get('url')]
        );

        return new RedirectResponse($articleUrl, 301);
    }

    /**
     * List all articles in catalog.
     *
     * @route GET /admin/articles/
     * @param Request $request
     * @param CurrentSite $currentSite
     * @return Response
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function adminCatalog(Request $request, CurrentSite $currentSite): Response
    {
        self::authAdmin($request);

        $request->attributes->set("page_title", "Catalogue");

        $articles = ArticleQuery::create()
            ->filterForCurrentSite($currentSite)
            ->find();

        return $this->render("AppBundle:Article:articleAdminCatalog.html.twig", [
            "articles" => $articles,
            "site" => $currentSite->getSite(),
        ]);
    }

    /**
     * Check that ISBN is valid and isn't used.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkIsbn(Request $request): JsonResponse
    {

        $am = new ArticleManager();
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
     * @throws Exception
     */
    public function updatePublisherStock(Request $request, int $articleId): JsonResponse
    {
        $am = new ArticleManager();

        $requestBody = $request->getContent();
        $publisherStock = (int)$requestBody;
        /** @noinspection PhpConditionAlreadyCheckedInspection */
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
