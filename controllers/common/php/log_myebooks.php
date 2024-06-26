<?php

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGenerator;

return function (UrlGenerator $urlGenerator): RedirectResponse
{
    $newPageUrl = $urlGenerator->generate('user_library');
    return new RedirectResponse($newPageUrl);
};
