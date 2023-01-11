<?php

namespace AppBundle\Controller;

use Biblys\Service\Pagination;
use Framework\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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

        $request->attributes->set("page_title", $category->get("name"));

        $queryParams = [
            "category_id" => $category->get('id'),
            "post_status" => 1,
            "post_date" => "< ".date("Y-m-d H:i:s")
        ];

        $pm = $this->entityManager("Post");

        // Pagination
        $pageNumber = (int) $request->query->get("p", 0);
        if ($pageNumber < 0) {
            throw new BadRequestHttpException("Page number must be a positive integer");
        }

        $totalPostCount = $pm->count($queryParams);
        $pagination = new Pagination($pageNumber, $totalPostCount, 10);

        $posts = $pm->getAll($queryParams, [
            "order" => "post_date",
            "sort" => "desc",
            "limit" => 10,
            "offset" => $pagination->getOffset(),
        ]);

        return $this->render('AppBundle:PostCategory:show.html.twig', [
            'category' => $category,
            'posts' => $posts,
            'current_page' => $pagination->getCurrent(),
            'prev_page' => $pagination->getPrevious(),
            'next_page' => $pagination->getNext(),
            'total_pages' => $pagination->getTotal()
        ]);
    }

}
