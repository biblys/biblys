<?php /** @noinspection DuplicatedCode */
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

use Article;
use ArticleManager;
use Biblys\Exception\ArticleAlreadyInRayonException;
use Biblys\Exception\CannotDeleteArticleWithStock;
use Biblys\Gleeph\GleephAPI;
use Biblys\Isbn\Isbn;
use Biblys\Isbn\IsbnParsingException;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\GleephService;
use Biblys\Service\Images\ImagesService;
use Biblys\Service\LoggerService;
use Biblys\Service\Mailer;
use Biblys\Service\MailingList\MailingListService;
use Biblys\Service\MetaTagsService;
use Biblys\Service\Pagination;
use Biblys\Service\QueryParamsService;
use Biblys\Service\Slug\SlugService;
use Biblys\Service\TemplateService;
use Biblys\Service\Watermarking\WatermarkingService;
use Exception;
use Framework\Controller;
use InvalidArgumentException;
use LinkManager;
use Model\ArticleCategoryQuery;
use Model\ArticleQuery;
use Model\Link;
use Model\LinkQuery;
use Model\PublisherQuery;
use Model\Stock;
use Model\StockQuery;
use Propel\Runtime\Exception\PropelException;
use Psr\Http\Client\ClientExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use TagManager;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Usecase\AddArticleToUserLibraryUsecase;
use Usecase\BusinessRuleException;

class ArticleController extends Controller
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function showAction(
        Request         $request,
        Config          $config,
        CurrentSite     $currentSiteService,
        UrlGenerator    $urlGenerator,
        LoggerService   $loggerService,
        MetaTagsService $metaTags,
        TemplateService $templateService,
        ImagesService   $imagesService,
        string          $slug
    ): RedirectResponse|Response
    {
        $am = new ArticleManager();
        /** @var Article $article */
        $article = $am->get(['article_url' => $slug]);

        if (!$article) {
            $request->attributes->set('ArticleNotFound', true);
            throw new NotFoundException("Article $slug not found.");
        }

        $articleModel = ArticleQuery::create()->findPk($article->get("id"));

        $use_old_controller = $currentSiteService->getOption('use_old_article_controller');
        if ($use_old_controller) {
            return new RedirectResponse("/legacy/a/".$slug);
        }

        $request->attributes->set(
            "page_title",
            $article->get('title').' de '.
            authors($article->get('authors')).' ('.
            $article->get('publisher')->get('name').')'
        );

        // Meta tags
        $metaTags->setTitle($articleModel->getTitle());
        $metaTags->setUrl($urlGenerator->generate("article_show", ["slug" => $articleModel->getUrl()]));

        $summary = $article->get('summary') ?: "";
        $opengraphTags = [
            'type' => 'book',
            'description' => truncate(strip_tags($summary), '500', '...', true),
        ];
        if ($imagesService->imageExistsFor($articleModel)) {
            $opengraphTags['image'] = $imagesService->getImageUrlFor($articleModel);
            $metaTags->setImage($opengraphTags["image"]);
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
        if ($imagesService->imageExistsFor($articleModel)) {
            $twitterCardsTags['image'] = $imagesService->getImageUrlFor($articleModel);
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

        return $templateService->renderResponse("AppBundle:Article:show.html.twig", [
            "article" => $article,
            "articleModel" => $articleModel,
            "similarArticles" => $similarArticles,
        ]);
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     * @throws Exception
     * @throws Exception
     * @throws Exception
     */
    public function searchAction(
        Request $request,
        CurrentSite $currentSite,
        QueryParamsService $queryParamsService,
        TemplateService $templateService,
    ): Response
    {
        $am = new ArticleManager();

        $queryParamsService->parse([
            "q" => ["type" => "string", "mb_min_length" => 3, "mb_max_length" => 255, "default" => ""],
            "in-stock" => ["type" => "string", "default" => "0"],
            "sort" => ["type" => "string", "default" => "pubdate|desc"],
            "p" => ["type" => "string", "default" => 0],
            "autofocus" => ["type" => "numeric", "default" => 0],
        ]);

        $query = $queryParamsService->get("q");
        $inStockFilter = (bool) $queryParamsService->get("in-stock");
        $sort = $queryParamsService->get("sort");

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
            $page = (int) $queryParamsService->get("p");
            $queryParams = ["q" => $query, "in-stock" => $inStockFilter, "sort" => $sort];
            $articlesPerPage = $currentSite->getOption("articles_per_page", 10);

            if ($inStockFilter) {
                $count = $am->countSearchResultsForAvailableStock($query);
                try {
                    $pagination = new Pagination($page, $count, $articlesPerPage);
                } catch (InvalidArgumentException $exception) {
                    throw new BadRequestHttpException($exception->getMessage());
                }
                $pagination->setQueryParams($queryParams);
                $articles = $am->searchWithAvailableStock($query, [
                    'order' => $selectedSortOptionField,
                    'sort' => $sortOrder,
                    'limit' => $pagination->getLimit(),
                    'offset' => $pagination->getOffset(),
                ]);
            } else {
                $count = $am->countSearchResults($query);
                try {
                    $pagination = new Pagination($page, $count, $articlesPerPage);
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

        return $templateService->renderResponse('AppBundle:Article:search.html.twig', [
            'articles' => $articles,
            'pages' => $pagination,
            'count' => $count,
            'query' => $query,
            'inStockFilterChecked' => $inStockFilter ? "checked" : "",
            "sortOptions" => $sortOptions,
            "sortCriteria" => $sortCriteria,
            "sortOrder" => $sortOrder,
            "autofocus" => $queryParamsService->getInteger("autofocus"),
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function freeDownloadAction(
        Request $request,
        CurrentSite $currentSiteService,
        CurrentUser $currentUserService,
        MailingListService $mailingListService,
        TemplateService $templateService,
        Mailer $mailer,
        Session $session,
        UrlGenerator $urlGenerator,
        $id,
    ): RedirectResponse|Response
    {
        $currentUserService->authUser();

        $am = new ArticleManager();
        /** @var Article $articleEntity */
        $articleEntity = $am->getById($id);

        if (!$articleEntity) {
            throw new NotFoundException("L'article $id n'existe pas.");
        }

        if (!$articleEntity->isDownloadable()) {
            throw new NotFoundException($articleEntity->get('title')." n'est pas téléchargeable.");
        }

        if (!$articleEntity->isFree()) {
            throw new NotFoundException($articleEntity->get('title')." n'est pas gratuit.");
        }

        if (!$articleEntity->isAvailable()) {
            throw new NotFoundException($articleEntity->get('title')." n'est pas disponible.");
        }

        $userLibraryUrl = $urlGenerator->generate("user_library");

        $currentUser = $currentUserService->getUser();
        $currentUserPurchasesForArticle = StockQuery::create()
            ->filterByUser($currentUser)
            ->filterByArticleId($articleEntity->get("id"))
            ->count();
        if ($currentUserPurchasesForArticle > 0) {
            return new RedirectResponse($userLibraryUrl);
        }

        $request->attributes->set("page_title", "Téléchargement gratuit de {$articleEntity->get('title')}");

        $newsletter = false;
        $newsletter_checked = false;
        if ($mailingListService->isConfigured()) {
            $mailingList = $mailingListService->getMailingList();
            if ($currentSiteService->getOption("newsletter") === "1") {
                $newsletter = true;
                if ($mailingList->hasContact($currentUser->getEmail())) {
                    $newsletter_checked = null;
                }
            }
        }

        if ($request->getMethod() == 'POST') {
            // Subscribe to newsletter
            $newsletter_checked = $request->request->get('newsletter', false);
            $email = $currentUser->getEmail();
            if ($mailingListService->isConfigured() && $newsletter_checked) {
                try {
                    $mailingList = $mailingListService->getMailingList();
                    $mailingList->addContact($email, true);
                } catch (Exception) {
                    // Ignore errors
                }
            }

            $article = ArticleQuery::create()->findPk($articleEntity->get("id"));
            try {
                $usecase = new AddArticleToUserLibraryUsecase($mailer);
                $usecase->execute(
                    currentSite: $currentSiteService,
                    urlGenerator: $urlGenerator,
                    user: $currentUser,
                    article: $article,
                );
            } catch (BusinessRuleException $exception) {
                $errorMessage = "Ajout à la bibliothèque impossible : {$exception->getMessage()}";
                $session->getFlashBag()->add("error", $errorMessage);
            }

            return new RedirectResponse($userLibraryUrl);
        }

        return $templateService->renderResponse('AppBundle:Article:freeDownload.html.twig', [
            'article' => $articleEntity,
            'newsletter' => $newsletter,
            'newsletter_checked' => $newsletter_checked,
        ]);
    }

    /**
     * @route GET/POST /admin/article/{id}/delete
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function deleteAction(
        Request $request,
        UrlGenerator $urlGenerator,
        CurrentSite $currentSite,
        CurrentUser $currentUser,
        ImagesService $imagesService,
        TemplateService $templateService,
        int $id
    ): Response
    {
        $article = ArticleQuery::create()->filterForCurrentSite($currentSite)->findOneById($id);
        if (!$article) {
            throw new NotFoundException("L'article $id n'existe pas.");
        }

        $currentUser->authPublisher($article->getPublisher());

        $error = null;
        if ($request->getMethod() == 'POST') {
            try {
                $article->preDelete();

                if ($imagesService->imageExistsFor($article)) {
                    $imagesService->deleteImageFor($article);
                }

                $article->delete();
            } catch (CannotDeleteArticleWithStock) {
                throw new BadRequestHttpException(
                    "Impossible de supprimer l'article {$article->getTitle()} car il a des exemplaires associés."
                );
            }

            return new RedirectResponse(
                $urlGenerator->generate("article_deleted", ["title" => $article->getTitle()])
            );
        }

        $request->attributes->set("page_title", "Suppression de l'article {$article->getTitle()}");

        return $templateService->renderResponse("AppBundle:Article:delete.html.twig", [
            "article" => $article,
            "error" => $error,
        ], isPrivate: true);
    }

    /**
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
        ], isPrivate: true);
    }

    /**
     * @route /articles/{id}/tags/add.
     * @throws PropelException
     * @throws Exception
     */
    public function addTagsAction(Request $request, CurrentUser $currentUser, $id): Response
    {
        $currentUser->authPublisher();

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
            $currentUser->authPublisher($publisher);
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
            $slugService = new SlugService();
            if ($tag === null) {
                $tag_url = $slugService->slugify($tag_name);
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
     * @throws PropelException
     * @throws Exception
     */
    public function addRayonsAction(
        Request $request,
        CurrentSite $currentSite,
        CurrentUser $currentUser,
        $id
    ):
    JsonResponse
    {
        $currentUser->authPublisher();

        $article = ArticleQuery::create()->findPk($id);
        if (!$article) {
            throw new Exception("Article $id not found");
        }

        if ($article->getPublisher() !== null) {
            $publisher = PublisherQuery::create()->findPk($article->getPublisherId());
            $currentUser->authPublisher($publisher);
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
     * @route /admin/articles/search-terms.
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function searchTermsAction(
        Request $request,
        CurrentUser $currentUser,
        UrlGenerator $urlGenerator,
    ): Response
    {
        $currentUser->authAdmin();
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
        ], isPrivate: true);
    }

    /**
     * @route /admin/articles/{id}/refresh-search-terms.
     * @throws Exception
     */
    public function refreshSearchTermsAction(CurrentUser $currentUser, $id): JsonResponse
    {
        $currentUser->authAdmin();

        $am = new ArticleManager();
        /** @var Article $article */
        $article = $am->getById($id);

        if (!$article) {
            throw new NotFoundException("Article $id not found.");
        }

        $article = $am->refreshMetadata($article);
        $am->update($article);

        return new JsonResponse(['terms' => $article->get('keywords')]);
    }

    /**
     * @route /isbn/{ean}.
     */
    public function byIsbn(UrlGenerator $urlGenerator, string $ean): Response
    {
        try {
            $ean = Isbn::convertToEan13($ean);
        } catch (IsbnParsingException $exception) {
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
     * @route GET /admin/articles/
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function adminCatalog(
        Request $request,
        CurrentUser $currentUser,
        TemplateService $templateService,
    ): Response
    {
        $currentUser->authPublisher();

        $request->attributes->set("page_title", "Catalogue");

        $articleQuery = ArticleQuery::create();
        if (!$currentUser->isAdmin()) {
            $articleQuery->filterByPublisherId($currentUser->getCurrentRight()->getPublisherId());
        }

        $count = $articleQuery->count();

        try {
            $page = (int) $request->query->get("p", 0);
            $pagination = new Pagination($page, $count, limit: 100);
        } catch (InvalidArgumentException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
        $articles = $articleQuery
            ->limit($pagination->getLimit())
            ->offset($pagination->getOffset())
            ->find();

        return $templateService->renderResponse("AppBundle:Article:articleAdminCatalog.html.twig", [
            "articles" => $articles,
            "count" => $count,
            "pages" => $pagination,
        ], isPrivate: true);
    }

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

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     * @throws PropelException
     */
    public function downloadWithWatermarkAction(
        CurrentSite         $currentSite,
        CurrentUser         $currentUser,
        WatermarkingService $watermarkingService,
        TemplateService     $templateService,
        int                 $id
    ): Response
    {
        $currentUser->authUser();

        $files = [];
        $libraryItem = $this->_getLibraryItem($watermarkingService, $currentSite, $id, $currentUser);
        if ($libraryItem->isWatermarked()) {
            $files = $watermarkingService->getFiles(
                masterId: $libraryItem->getArticle()->getLemoninkMasterId(),
                transactionId: $libraryItem->getLemoninkTransactionId(),
                transactionToken: $libraryItem->getLemoninkTransactionToken(),
            );
        }

        return $templateService->renderResponse("AppBundle:Article:download-with-watermark.html.twig", [
            "article_id" => $id,
            "article_title" => $libraryItem->getArticle()->getTitle(),
            "item_id" => $libraryItem->getId(),
            "user_email" => $currentUser->getUser()->getEmail(),
            "isWatermarked" => $libraryItem->isWatermarked(),
            "files" => $files,
        ], isPrivate: true);
    }

    /**
     * @throws PropelException
     */
    public function watermarkAction(
        Request $request,
        WatermarkingService $watermarkingService,
        CurrentSite $currentSite,
        CurrentUser $currentUser,
        UrlGenerator $urlGenerator,
        int $id
    ): RedirectResponse
    {
        $currentUser->authUser();

        $libraryItem = $this->_getLibraryItem($watermarkingService, $currentSite, $id, $currentUser);
        if ($libraryItem->isWatermarked()) {
            throw new BadRequestHttpException("Item {$libraryItem->getId()} is already watermarked.");
        }

        $consent = $request->request->get("consent");
        if ($consent !== "given") {
            throw new BadRequestHttpException("Vous devez accepter le tatouage numérique du fichier pour continuer.");
        }

        $transaction = $watermarkingService->watermark(
            masterId: $libraryItem->getArticle()->getLemoninkMasterId(),
            text: "Téléchargé par {$currentUser->getUser()->getEmail()} (#{$libraryItem->getId()})",
        );
        $libraryItem->setLemoninkTransactionId($transaction->getId());
        $libraryItem->setLemoninkTransactionToken($transaction->getToken());
        $libraryItem->save();

        $url = $urlGenerator->generate("article_download_with_watermark", ["id" => $id]);
        return new RedirectResponse($url);
    }

    /**
     * @throws PropelException
     */
    private function _getLibraryItem(WatermarkingService $watermarkingService, CurrentSite $currentSite, int $id, CurrentUser $currentUser): Stock
    {
        if (!$watermarkingService->isConfigured()) {
            throw new ServiceUnavailableHttpException(
                3600,
                "Watermarking service is not configured (missing API key)."
            );
        }

        $article = ArticleQuery::create()->filterForCurrentSite($currentSite)->findPk($id);
        if (!$article) {
            throw new BadRequestHttpException("Article $id does not exist.");
        }

        if ($article->getLemoninkMasterId() === null) {
            throw new BadRequestHttpException("Article $id does not have a watermark master id.");
        }

        $libraryItem = StockQuery::create()
            ->filterByUser($currentUser->getUser())
            ->filterByArticleId($id)
            ->findOne();
        if (!$libraryItem) {
            throw new AccessDeniedHttpException("Article $id is not in user library.");
        }

        return StockQuery::create()
            ->filterByUser($currentUser->getUser())
            ->filterByArticleId($id)
            ->findOne();
    }
}
