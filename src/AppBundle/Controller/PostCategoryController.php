<?php

namespace AppBundle\Controller;

use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\Pagination;
use CategoryManager;
use Framework\Controller;
use PostManager;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PostCategoryController extends Controller
{

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function showAction(Request $request, $slug): RedirectResponse|Response
    {
        $globalSite = LegacyCodeHelper::getGlobalSite();

        $use_old_controller = $globalSite->getOpt('use_old_post_controller');
        if ($use_old_controller) {
            return new RedirectResponse("/o/blog/$slug/");
        }

        $cm = new CategoryManager();

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

        $pm = new PostManager();

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
