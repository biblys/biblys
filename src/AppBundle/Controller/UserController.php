<?php

namespace AppBundle\Controller;

use Framework\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;

class UserController extends Controller
{
    public function login(UrlGenerator $urlGenerator): Response
    {
        $loginRoute = $urlGenerator->generate("openid_axys");
        return new RedirectResponse($loginRoute);
    }

    public function signup(): Response
    {
        return new RedirectResponse("https://axys.me");
    }
}
