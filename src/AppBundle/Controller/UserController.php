<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentUser;
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
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function login(
        Request $request,
        CurrentUser $currentUser,
        UrlGenerator $urlGenerator
    ): Response
    {
        if ($currentUser->isAuthentified()) {
            return new RedirectResponse("/");
        }

        $returnUrl = $request->query->get("return_url");
        if (str_contains($returnUrl, "logged-out")) {
            $returnUrl = null;
        }

        $loginWithAxysUrl = $urlGenerator->generate("openid_axys", ["return_url" => $returnUrl]);
        return $this->render("AppBundle:User:login.html.twig", [
            "loginWithAxysUrl" => $loginWithAxysUrl,
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function account(): Response
    {
        return $this->render("AppBundle:User:account.html.twig");
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
