<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentUser;
use Biblys\Service\TemplateService;
use Framework\Controller;
use Model\SupplierQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class SupplierController extends Controller
{
    /**
     * @throws PropelException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function indexAction(CurrentUser $currentUser, TemplateService $templateService): Response
    {
        $currentUser->authAdmin();

        $suppliers = SupplierQuery::create()
            ->orderByName()
            ->find();

        return $templateService->renderResponse('AppBundle:Supplier:index.html.twig', [
            'suppliers' => $suppliers,
        ]);
    }
}