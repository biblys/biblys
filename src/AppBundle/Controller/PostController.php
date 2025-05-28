<?php
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

use Biblys\Service\BodyParamsService;
use Biblys\Service\CurrentUser;
use Biblys\Service\FlashMessagesService;
use Biblys\Service\Images\ImagesService;
use Biblys\Service\MetaTagsService;
use Biblys\Service\Pagination;
use Biblys\Service\QueryParamsService;
use Biblys\Service\Slug\SlugService;
use Biblys\Service\TemplateService;
use DateTime;
use Exception;
use Framework\Controller;
use League\HTMLToMarkdown\HtmlConverter;
use Model\ArticleQuery;
use Model\BlogCategoryQuery;
use Model\Link;
use Model\LinkQuery;
use Model\Post;
use Model\PostQuery;
use PostManager;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PostController extends Controller
{
    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function indexAction(
        Request         $request,
        TemplateService $templateService,
    ): Response
    {
        $queryParams = [
            "post_status" => 1,
            "post_date" => "< " . date("Y-m-d H:i:s")
        ];

        $pm = new PostManager();

        $pageNumber = (int)$request->query->get("p", 0);
        if ($pageNumber < 0) {
            throw new BadRequestHttpException("Page number must be a positive integer");
        }

        $totalPostCount = $pm->count($queryParams);
        $pagination = new Pagination($pageNumber, $totalPostCount);

        $posts = $pm->getAll($queryParams, [
            "order" => "post_date",
            "sort" => "desc",
            'limit' => $pagination->getLimit(),
            'offset' => $pagination->getOffset(),
        ]);

        $request->attributes->set("page_title", "Actualités");

        return $templateService->renderResponse("AppBundle:Post:index.html.twig", [
            "posts" => $posts,
            "pages" => $pagination,
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     */
    public function showAction(
        Request         $request,
        CurrentUser     $currentUser,
        TemplateService $templateService,
        UrlGenerator    $urlGenerator,
        ImagesService   $imagesService,
        MetaTagsService $metaTagsService,
        string          $slug
    ): Response
    {
        $post = PostQuery::create()->findOneByUrl($slug);
        if (!$post) {
            throw new NotFoundException("Post $slug not found.");
        }

        $userCanSeeUnpublishedPost = false;
        if ($currentUser->isAuthenticated()) {
            $userIsPostAuthor = $post->getUser() === $currentUser->getUser();
            $userCanSeeUnpublishedPost = $userIsPostAuthor || $currentUser->isAdmin();
        }

        if (!$post->isPublished() && !$userCanSeeUnpublishedPost) {
            throw new NotFoundException("Post $slug is currently offline.");
        }

        $postIsToBePublished = $post->getDate() > new DateTime();
        if ($postIsToBePublished && !$userCanSeeUnpublishedPost) {
            throw new NotFoundException("Post $slug is to be published.");
        }

        $request->attributes->set("page_title", $post->getTitle());

        $description = "";
        if ($post->getContent()) {
            $description = truncate(strip_tags($post->getContent()), '500', '...', true);
        }

        $metaTagsService->setTitle($post->getTitle());
        $metaTagsService->setDescription($description);
        $metaTagsService->setUrl($urlGenerator->generate("post_show", ["slug" => $post->getUrl()]));

        if ($imagesService->imageExistsFor($post)) {
            $metaTagsService->setImage($imagesService->getImageUrlFor($post));
        }

        return $templateService->renderResponse('AppBundle:Post:show.html.twig', [
            'post' => \Post::buildFromModel($post)
        ]);
    }

    /**
     * @route GET /admin/posts/
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function adminAction(
        CurrentUser        $currentUser,
        QueryParamsService $queryParams,
        TemplateService    $templateService,
    ): Response
    {
        $currentUser->authPublisher();
        $rank = $currentUser->isAdmin() ? "adm_" : "pub_";

        $queryParams->parse([
            "category_id" => ["type" => "numeric", "default" => 0],
            "p" => ["type" => "numeric", "min" => 0, "default" => 0],
        ]);

        $currentCategoryId = $queryParams->getInteger("category_id");

        $postQuery = PostQuery::create();

        if ($currentCategoryId) {
            $postQuery->filterByCategoryId($_GET["category_id"]);
        }

        if (!$currentUser->isAdmin() && $currentUser->hasPublisherRight()) {
            $postQuery->filterByPublisherId($currentUser->getCurrentRight()->getPublisherId());
        }

        $count = $postQuery->count();
        $pagination = new Pagination($queryParams->getInteger("p"), $count, 100);
        $pagination->setQueryParams(["category_id" => $currentCategoryId]);

        $posts = $postQuery
            ->orderByDate(Criteria::DESC)
            ->limit($pagination->getLimit())
            ->offset($pagination->getOffset())
            ->find();

        $categories = BlogCategoryQuery::create()->find();
        return $templateService->renderResponse('AppBundle:Post:admin.html.twig', [
            "posts" => $posts,
            "rank" => $rank,
            "categories" => $categories,
            "currentCategoryId" => $currentCategoryId,
            "count" => $count,
            "pages" => $pagination,
        ]);
    }

    /**
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function newAction(
        CurrentUser     $currentUser,
        TemplateService $templateService,
    ): Response
    {
        $currentUser->authPublisher();

        $post = new Post();
        $post->setUserId($currentUser->getUser()->getId());
        if ($currentUser->hasPublisherRight()) {
            $post->setPublisherId($currentUser->getCurrentRight()->getPublisherId());
        }

        $categories = BlogCategoryQuery::create()->find();

        return $templateService->renderResponse('AppBundle:Post:new.html.twig', [
            "post" => $post,
            "categories" => $categories,
        ]);
    }

    /**
     * @throws PropelException
     */
    public function createAction(
        BodyParamsService $bodyParams,
        CurrentUser       $currentUser,
        UrlGenerator      $urlGenerator,
        ImagesService     $imagesService,
    ): RedirectResponse
    {
        $currentUser->authPublisher();
        $post = new Post();
        return $this->_updatePost($bodyParams, $imagesService, $urlGenerator, $currentUser, $post);
    }

    /**
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function editAction(
        CurrentUser     $currentUser,
        TemplateService $templateService,
        string          $id,
    ): Response
    {
        $currentUser->authPublisher();

        $post = PostQuery::create()->findPk($id);
        $categories = BlogCategoryQuery::create()->find();

        return $templateService->renderResponse('AppBundle:Post:edit.html.twig', [
            "post" => $post,
            "categories" => $categories,
        ]);
    }

    /**
     * @throws PropelException
     */
    public function updateAction(
        BodyParamsService $bodyParams,
        CurrentUser       $currentUser,
        UrlGenerator      $urlGenerator,
        ImagesService     $imagesService,
        string            $id,
    ): RedirectResponse
    {
        $currentUser->authPublisher();
        $post = PostQuery::create()->findPk($id);
        return $this->_updatePost($bodyParams, $imagesService, $urlGenerator, $currentUser, $post);
    }

    /**
     * @throws PropelException
     */
    private function _updatePost(
        BodyParamsService $bodyParams,
        ImagesService     $imagesService,
        UrlGenerator      $urlGenerator,
        CurrentUser       $currentUser,
        Post              $post,
    ): RedirectResponse
    {
        $bodyParams->parse([
            "user_id" => ["type" => "numeric", "default" => $currentUser->getUser()->getId()],
            "publisher_id" => ["type" => "numeric", "default" => null],
            "category_id" => ["type" => "numeric", "default" => null],
            "post_title" => ["type" => "string"],
            "post_status" => ["type" => "boolean", "default" => false],
            "post_date" => ["type" => "string"],
            "post_link" => ["type" => "string", "default" => null],
            "post_selected" => ["type" => "boolean", "default" => false],
            "post_excerpt" => ["type" => "string", "default" => ""],
            "post_content" => ["type" => "string", "default" => ""],
            "post_illustration_legend" => ["type" => "string", "default" => null],
        ]);

        $post->setUserId($bodyParams->getInteger("user_id"));
        $post->setPublisherId($bodyParams->getInteger("publisher_id"));
        $post->setTitle($bodyParams->get("post_title"));
        $post->setExcerpt($bodyParams->get("post_excerpt"));
        $post->setContent($bodyParams->get("post_content"));
        $post->setCategoryId($bodyParams->getInteger("category_id"));
        $post->setLink($bodyParams->get("post_link"));
        $post->setIllustrationLegend($bodyParams->get("post_illustration_legend"));
        $post->setStatus($bodyParams->getBoolean("post_status"));
        $post->setDate($bodyParams->get("post_date"));
        $post->setStatus($bodyParams->getBoolean("post_status"));
        $post->setSelected($bodyParams->getBoolean("post_selected"));
        $post->save();

        $slugService = new SlugService();
        $postUrl = $slugService->slugify($post->getTitle());
        $postWithTheSameUrl = PostQuery::create()->findOneByUrl($postUrl);
        if ($postWithTheSameUrl && ($post->isNew() || $postWithTheSameUrl->getId() !== $post->getId())) {
            $postUrl .= '_' . $post->getId();
        }
        $post->setUrl($postUrl);
        $post->save();

        if (!empty($_FILES["post_illustration_upload"]["tmp_name"])) {
            $imagesService->addImageFor($post, $_FILES["post_illustration_upload"]["tmp_name"]);
        }

        if (isset($_POST["post_illustration_delete"]) && $_POST['post_illustration_delete']) {
            $imagesService->deleteImageFor($post);
        }

        $postUrl = $urlGenerator->generate("post_show", ["slug" => $post->getUrl()]);
        return new RedirectResponse($postUrl);
    }

    /**
     * @route GET /post/:id/delete
     * @throws Exception
     */
    public function deleteAction(
        UrlGenerator $urlGenerator,
        CurrentUser  $currentUser,
                     $id
    ): RedirectResponse
    {
        $pm = new PostManager();
        $post = $pm->getById($id);
        /** @var \Post $post */
        if (!$post) {
            throw new NotFoundException("Post $id not found.");
        }

        if (!$currentUser->isAdmin() && !$post->canBeDeletedBy($currentUser->getUser())) {
            throw new Exception("Vous n'avez pas le droit de supprimer ce billet.");
        }

        $pm->delete($post);

        return new RedirectResponse($urlGenerator->generate('posts_admin'));
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function articlesAction(CurrentUser $currentUser, TemplateService $templateService, int $id): Response
    {
        $currentUser->authPublisher();

        $post = PostQuery::create()->findPk($id);
        $articleLinks = LinkQuery::create()
            ->filterByPostId($id)
            ->filterByArticleId(null, Criteria::NOT_EQUAL)
            ->joinWithArticle()
            ->find();

        $articles = ArticleQuery::create()
            ->orderByTitleAlphabetic()
            ->find();

        return $templateService->renderResponse("AppBundle:Post:articles.html.twig", [
            "post" => $post,
            "article_links" => $articleLinks,
            "articles" => $articles,
        ]);
    }

    /**
     * @route GET /post/:slug
     */
    public function oldUrlAction($slug): RedirectResponse
    {
        return new RedirectResponse('/blog/' . $slug);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    public function exportAction(TemplateService $templateService): Response
    {
        $posts = PostQuery::create()->find();
        foreach ($posts as $post) {
            $this->_writePostToFile($post, $templateService);
        }

        return new Response("OK");
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private
    function _writePostToFile(Post $post, TemplateService $templateService): void
    {
        $converter = new HtmlConverter();
        $markdown = $converter->convert($post->getContent());

        $response = $templateService->renderResponse('AppBundle:Post:export.md.twig', [
            "post" => $post,
            "content" => $markdown,
            "published" => $post->getStatus() ? "true" : "false",
        ]);
        $response->headers->set('Content-Type', ' text/markdown');

        $slugService = new SlugService();
        $fileName = $slugService->slugify($post->getTitle());

        $fs = new FileSystem();
        $fs->dumpFile(__DIR__ . "/../../../posts/$fileName.md", $response->getContent());
    }

    /**
     * @route POST /post/:id/articles
     * @throws Exception
     */
    public function articleLinkAction(
        CurrentUser $currentUser,
        BodyParamsService $bodyParamsService,
        FlashMessagesService $flashMessagesService,
        UrlGenerator $urlGenerator,
        int $id
    ): RedirectResponse
    {
        $currentUser->authPublisher();

        $bodyParamsService->parse(["article_id" => ["type" => "numeric"]]);
        $articleId = $bodyParamsService->getInteger("article_id");
        $article = ArticleQuery::create()->findPk($articleId);

        $link = new Link();
        $link->setPostId($id);
        $link->setArticle($article);
        $link->save();

        $flashMessagesService->add("success", "L'article {$article->getTitle()} a été lié au billet.");

        $returnUrl = $urlGenerator->generate("post_articles", ["id" => $id]);
        return new RedirectResponse($returnUrl);
    }
}
