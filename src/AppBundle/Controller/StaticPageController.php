<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentSite;
use Framework\Controller;
use Model\PageQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class StaticPageController extends Controller
{
    /**
     * @route GET /page/:slug
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws PropelException
     */
    public function showAction(CurrentSite $currentSite, string $slug): Response
    {
        $pageQuery = PageQuery::create()
            ->filterBySiteId($currentSite->getId())
            ->filterByUrl($slug);
        $staticPage = $pageQuery->findOne();

        if ($staticPage === null) {
            throw new NotFoundHttpException("Cannot find a static page with slug \"$slug\".");
        }

        return $this->render('AppBundle:StaticPage:show.html.twig', [
            'staticPage' => $staticPage,
        ]);
    }
}
