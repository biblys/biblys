<?php

namespace AppBundle\Controller;

use Biblys\Service\TemplateService;
use Framework\Controller;
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
     */
    public function newAction(TemplateService $templateService): Response
    {
        return $templateService->render("AppBundle:Invitation:new.html.twig");
    }
}