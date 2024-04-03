<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentSite;
use Biblys\Service\TemplateService;
use Framework\Controller;
use Model\SpecialOfferQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class SpecialOfferController extends Controller
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     */
    public function indexAction(
        Request $request,
        CurrentSite $currentSite,
        TemplateService $templateService
    ): Response
    {
        self::authAdmin($request);

        $offers = SpecialOfferQuery::create()
            ->filterBySite($currentSite->getSite())
            ->find();

        return $templateService->renderResponse('AppBundle:SpecialOffer:index.html.twig', [
            'offers' => $offers->getArrayCopy(),
        ]);
    }
}