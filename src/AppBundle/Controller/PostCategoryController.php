<?php

namespace AppBundle\Controller;

use Framework\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;

class PostCategoryController extends Controller
{

    public function showAction(Request $request, $slug)
    {
        global $site;

        $use_old_controller = $site->getOpt('use_old_post_controller');
        if ($use_old_controller) {
            return $this->redirect('/o/blog/'.$slug.'/');
        }

        $cm = $this->entityManager("Category");

        $category = $cm->get(["category_url" => $slug]);

        if (!$category) {
            throw new NotFoundException("Category $slug not found.");
        }

        $this->setPageTitle($category->get("name"));

        $queryParams = [
            "category_id" => $category->get('id'),
            "post_status" => 1,
            "post_date" => "< ".date("Y-m-d H:i:s")
        ];

        $pm = $this->entityManager("Post");

        // Pagination
        $page = (int) $request->query->get('p', 0);
        $totalPostCount = $pm->count($queryParams);
        $postPerPage = 10;
        $totalPages = ceil($totalPostCount / $postPerPage);
        $offset = $page * $postPerPage;
        $currentPage = $page + 1;
        $prevPage = $page > 0 ? $page - 1 : false;
        $nextPage = $page < $totalPages-1 ? $page + 1 : false;

        $posts = $pm->getAll($queryParams, [
            "order" => "post_date",
            "sort" => "desc",
            "limit" => 10,
            "offset" => $offset
        ]);

        return $this->render('AppBundle:PostCategory:show.html.twig', [
            'category' => $category,
            'posts' => $posts,
            'current_page' => $currentPage,
            'prev_page' => $prevPage,
            'next_page' => $nextPage,
            'total_pages' => $totalPages
        ]);
    }

}
