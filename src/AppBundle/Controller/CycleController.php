<?php

namespace AppBundle\Controller;

use Framework\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;

class CycleController extends Controller
{
    /**
     * Show a Cycle's page and related articles
     * /cycle/{slug}.
     *
     * @param  $slug the cycle's slug
     *
     * @return Response the rendered templated
     */
    public function showAction(Request $request, $slug)
    {
        global $site;

        $cm = $this->entityManager('Cycle');
        $am = $this->entityManager('Article');

        $cycle = $cm->get(['cycle_url' => $slug]);
        if (!$cycle) {
            throw new NotFoundException("Cycle $slug not found");
        }

        $this->setPageTitle($cycle->get('name'));

        // Pagination
        $page = (int) $request->query->get('p', 0);
        $totalCount = $am->count(['cycle_id' => $cycle->get('id')]);
        $pagination = new \Biblys\Utils\Pagination($page, $totalCount);

        $articles = $am->getAll(['cycle_id' => $cycle->get('id')], [
            'order' => 'article_tome',
            'limit' => $pagination->getLimit(),
            'offset' => $pagination->getOffset(),
        ]);

        return $this->render('AppBundle:Cycle:show.html.twig', [
            'cycle' => $cycle,
            'articles' => $articles,
            'pages' => $pagination,
        ]);
    }
}
