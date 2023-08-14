<?php

namespace AppBundle\Controller;

use Biblys\Service\Pagination;
use Exception;
use Framework\Controller;
use PostManager;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PostController extends Controller
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     */
    public function indexAction(Request $request)
    {
        global $_SITE;

        $use_old_controller = $_SITE->getOpt('use_old_post_controller');
        if ($use_old_controller) {
            return new RedirectResponse("/o/blog/");
        }

        $queryParams = [
            "post_status" => 1,
            "post_date" => "< ".date("Y-m-d H:i:s")
        ];

        $pm = new PostManager();

        $pageNumber = (int) $request->query->get("p", 0);
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

        return $this->render('AppBundle:Post:index.html.twig', [
            'posts' => $posts,
            'pages' => $pagination,
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     */
    public function showAction(Request $request, $slug)
    {
        global $_SITE, $urlgenerator;

        $use_old_controller = $_SITE->getOpt('use_old_post_controller');
        if ($use_old_controller) {
            return new RedirectResponse('/o/blog/'.$slug);
        }

        $pm = new PostManager();
        $post = $pm->get(["post_url" => $slug]);

        // Offline post
        if ($post && $post->get('status') == 0 &&
                $post->get('axys_account_id') !== \Biblys\Legacy\LegacyCodeHelper::getGlobalVisitor()->get('id') && !\Biblys\Legacy\LegacyCodeHelper::getGlobalVisitor()->isAdmin()) {
            $post = false;
        }

        // Future post
        if ($post && $post->get('date') > date("Y-m-d H:i:s") &&
                $post->get('axys_account_id') !== \Biblys\Legacy\LegacyCodeHelper::getGlobalVisitor()->get('id') && !\Biblys\Legacy\LegacyCodeHelper::getGlobalVisitor()->isAdmin()) {
            $post = false;
        }

        if (!$post) {
            throw new NotFoundException("Post $slug not found.");
        }

        $use_old_controller = $_SITE->getOpt('use_old_post_controller');
        if ($use_old_controller) {
            return new RedirectResponse('/o/blog/'.$slug, 301);
        }

        $request->attributes->set("page_title", $post->get("title"));

        $description = "";
        if ($post->has("content")) {
            $description = truncate(strip_tags($post->get('content')), '500', '...', true);
        }

        $opengraphTags = [
            "type" => "article",
            "title" => $post->get("title"),
            "url" => "https://" .$request->getHost().
                $urlgenerator->generate("post_show", ["slug" => $post->get("url")]),
            "description" => $description,
            "site_name" => $_SITE->get("title"),
            "locale" => "fr_FR",
            "article:published_time" => $post->get('date'),
            "article:modified_time" => $post->get('updated')
        ];

        // Get post illustration for opengraph
        $image = $post->getFirstImageUrl();
        if ($post->hasIllustration()) {
            $opengraphTags["image"] = $post->getIllustration()->getUrl();
        }
        // Else get first image from post
        elseif ($image) {
            $opengraphTags["image"] = $image;
        }

        $this->setOpengraphTags($opengraphTags);

        return $this->render('AppBundle:Post:show.html.twig', [
            'post' => $post
        ]);
    }

    // GET /admin/posts/
    public function adminAction(): RedirectResponse
    {
        if (\Biblys\Legacy\LegacyCodeHelper::getGlobalVisitor()->isAdmin()) {
            return new RedirectResponse('/pages/adm_posts');
        }

        return new RedirectResponse('/pages/pub_posts');
    }

    // GET /post/:id/delete

    /**
     * @throws Exception
     */
    public function deleteAction(UrlGenerator $urlGenerator, $id): RedirectResponse
    {
        $pm = new PostManager();
        $post = $pm->getById($id);
        if (!$post) {
            throw new NotFoundException("Post $id not found.");
        }

        if (!$post->canBeDeletedBy(\Biblys\Legacy\LegacyCodeHelper::getGlobalVisitor())) {
            throw new Exception("Vous n'avez pas le droit de supprimer ce billet.");
        }

        $pm->delete($post);

        return new RedirectResponse($urlGenerator->generate('posts_admin'));
    }

    // GET /post/:slug
    public function oldUrlAction($slug): RedirectResponse
    {
        return new RedirectResponse('/blog/'.$slug);
    }

}
