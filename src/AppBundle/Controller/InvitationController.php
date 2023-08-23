<?php

namespace AppBundle\Controller;

use Biblys\Article\Type;
use Biblys\Service\CurrentSite;
use Biblys\Service\TemplateService;
use Framework\Controller;
use Model\Base\ArticleQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class InvitationController extends Controller
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function newAction(
        CurrentSite $currentSite,
        TemplateService $templateService,
    ): Response
    {
        $downloadableTypes = Type::getAllDownloadableTypes();
        $downloadbleTypeIds = array_map(function($type) {
            return $type->getId();
        }, $downloadableTypes);

        $downloadableArticles = ArticleQuery::create()
            ->filterForCurrentSite($currentSite)
            ->filterByTypeId($downloadbleTypeIds)
            ->orderByTitleAlphabetic()
            ->find();
        return $templateService->render("AppBundle:Invitation:new.html.twig", [
            "downloadableArticles" => $downloadableArticles->getData(),
        ]);
    }
}