<?php

use Biblys\Service\TemplateService;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\LoaderError;
use Twig\Error\SyntaxError;

/**
 * @throws LoaderError
 * @throws SyntaxError
 */
return function (TemplateService $templateService): Response
{
    return $templateService->renderFromString("<p>Bientôt…</p>");
};
