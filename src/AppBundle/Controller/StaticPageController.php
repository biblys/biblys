<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Exception;
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
     * @throws Exception
     */
    public function showAction(
        CurrentSite $currentSite,
        CurrentUser $currentUser,
        string      $slug,
    ): Response
    {
        $pageQuery = PageQuery::create()
            ->filterBySiteId($currentSite->getId())
            ->filterByUrl($slug);
        $staticPage = $pageQuery->findOne();

        if ($staticPage === null) {
            throw new NotFoundHttpException("Cannot find a static page with slug \"$slug\".");
        }

        if (!$staticPage->isOnline()) {
            $currentUser->authAdmin( "Page \"$slug\" is offline.");
        }

        return $this->render("AppBundle:StaticPage:show.html.twig", [
            "staticPage" => $staticPage,
        ]);
    }
}
