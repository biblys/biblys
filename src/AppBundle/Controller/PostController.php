<?php

namespace AppBundle\Controller;

use Framework\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;

use \Exception;
use Symfony\Component\Routing\Generator\UrlGenerator;

class PostController extends Controller
{
    public function indexAction(Request $request)
    {
        global $site;

        $use_old_controller = $site->getOpt('use_old_post_controller');
        if ($use_old_controller) {
            return $this->redirect('/o/blog/');
        }

        $queryParams = [
            "post_status" => 1,
            "post_date" => "< ".date("Y-m-d H:i:s")
        ];

        $pm = $this->entityManager("Post");

        $pageNumber = (int) $request->query->get("p", 0);
        if ($pageNumber < 0) {
            throw new BadRequestHttpException("Page number must be a positive integer");
        }

        $totalPostCount = $pm->count($queryParams);
        $pagination = new \Biblys\Service\Pagination($pageNumber, $totalPostCount);

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

    public function showAction(Request $request, $slug)
    {
        global $site, $urlgenerator;

        $use_old_controller = $site->getOpt('use_old_post_controller');
        if ($use_old_controller) {
            return $this->redirect('/o/blog/'.$slug);
        }

        $pm = $this->entityManager("Post");
        $post = $pm->get(["post_url" => $slug]);

        // Offline post
        if ($post && $post->get('status') == 0 &&
                $post->get('user_id') !== $this->user->get('id') && !$this->user->isAdmin()) {
            $post = false;
        }

        // Future post
        if ($post && $post->get('date') > date("Y-m-d H:i:s") &&
                $post->get('user_id') !== $this->user->get('id') && !$this->user->isAdmin()) {
            $post = false;
        }

        if (!$post) {
            throw new NotFoundException("Post $slug not found.");
        }

        $use_old_controller = $site->getOpt('use_old_post_controller');
        if ($use_old_controller) {
            return $this->redirect('/o/blog/'.$slug, 301);
        }

        $this->setPageTitle($post->get("title"));

        $opengraphTags = [
            "type" => "article",
            "title" => $post->get("title"),
            "url" => "http://".$request->getHost().
                $urlgenerator->generate("post_show", ["slug" => $post->get("url")]),
            "description" => truncate(strip_tags($post->get('content')), '500', '...', true),
            "site_name" => $site->get("title"),
            "locale" => "fr_FR",
            "article:published_time" => $post->get('date'),
            "article:modified_time" => $post->get('updated')
        ];

        // Get post illustration for opengraph
        $image = $post->getFirstImageUrl();
        if ($post->hasIllustration()) {
            $opengraphTags["image"] = $post->getIllustration()->url();
        }
        // Else get first image from post
        else if ($image) {
            $opengraphTags["image"] = $image;
        }

        $this->setOpengraphTags($opengraphTags);

        return $this->render('AppBundle:Post:show.html.twig', [
            'post' => $post
        ]);
    }

    // GET /admin/posts/
    public function adminAction()
    {
        if ($this->user->isAdmin()) {
            return $this->redirect('/pages/adm_posts');
        }

        return $this->redirect('/pages/pub_posts');
    }

    // GET /post/:id/delete
    public function deleteAction(UrlGenerator $urlGenerator, $id)
    {
        $pm = $this->entityManager('Post');
        $post = $pm->getById($id);
        if (!$post) {
            throw new NotFoundException("Post $id not found.");
        }

        if (!$post->canBeDeletedBy($this->user)) {
            throw new Exception("Vous n'avez pas le droit de supprimer ce billet.");
        }

        $pm->delete($post);

        return $this->redirect($urlGenerator->generate('posts_admin'));
    }

    // GET /post/:slug
    public function oldUrlAction($slug)
    {
        return $this->redirect('/blog/'.$slug);
    }

}
