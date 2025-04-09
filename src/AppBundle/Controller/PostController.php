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

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\Images\ImagesService;
use Biblys\Service\MetaTagsService;
use Biblys\Service\Pagination;
use Biblys\Service\Slug\SlugService;
use Biblys\Service\TemplateService;
use DateTime;
use Exception;
use Framework\Controller;
use League\HTMLToMarkdown\HtmlConverter;
use Model\Post;
use Model\PostQuery;
use PostManager;
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
        CurrentSite     $currentSite,
        CurrentUser     $currentUser,
        TemplateService $templateService,
        UrlGenerator    $urlGenerator,
        ImagesService   $imagesService,
        MetaTagsService $metaTagsService,
        string          $slug
    ): Response
    {
        $post = PostQuery::create()
            ->filterBySite($currentSite->getSite())
            ->findOneByUrl($slug);
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
     * @throws PropelException
     */
    public function adminAction(CurrentUser $currentUser): RedirectResponse
    {
        if ($currentUser->isAdmin()) {
            return new RedirectResponse("/pages/adm_posts");
        }

        return new RedirectResponse("/pages/pub_posts");
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
    public function exportAction(CurrentSite $currentSite, TemplateService $templateService): Response
    {
        $posts = PostQuery::create()->findBySiteId($currentSite->getId());
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
}
