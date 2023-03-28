<?php

namespace AppBundle\Controller;

use Framework\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;

class UserController extends Controller
{
    public function login(Request $request, UrlGenerator $urlGenerator): Response
    {
        $returnUrl = $request->query->get("return_url");
        $loginRoute = $urlGenerator->generate("openid_axys", ["return_url" => $returnUrl]);
        return new RedirectResponse($loginRoute);
    }

    public function signup(): Response
    {
        return new RedirectResponse("https://axys.me");
    }
}
