<?php

namespace AppBundle\Controller;

use Framework\Controller;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class UserController extends Controller
{
    public function login(Request $request, UrlGenerator $urlGenerator): Response
    {
        $returnUrl = $request->query->get("return_url");
        $loginRoute = $urlGenerator->generate("openid_axys", ["return_url" => $returnUrl]);
        return new RedirectResponse($loginRoute);
    }

    public function logout(UrlGenerator $urlGenerator): Response
    {
        $loggedOutUrl = $urlGenerator->generate("user_logged_out");
        $response = new RedirectResponse($loggedOutUrl, status: 302);
        $response->headers->clearCookie("user_uid");

        return $response;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function loggedOut(): Response
    {
        return $this->render("AppBundle:User:loggedOut.html.twig");
    }

    public function signup(): Response
    {
        return new RedirectResponse("https://axys.me");
    }
}
