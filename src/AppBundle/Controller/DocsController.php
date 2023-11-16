<?php

namespace AppBundle\Controller;

use Biblys\Service\TemplateService;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class DocsController
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function ebooksAction(TemplateService $templateService): Response
    {
        return $templateService->render("AppBundle:Docs:ebooks.html.twig");
    }
}
