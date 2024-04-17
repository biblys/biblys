<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentUser;
use Biblys\Service\QueryParamsService;
use Biblys\Service\TemplateService;
use Framework\Controller;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        QueryParamsService $queryParams,
        CurrentUser $currentUser,
        UrlGenerator $urlGenerator,
    ): Response
    {
        if ($currentUser->isAuthentified()) {
            return new RedirectResponse("/");
        }

        $queryParams->parse(["return_url" => ["type" => "string", "optional" => true, "default" => ""]]);
        $returnUrl = $queryParams->get("return_url");
        if (str_contains($returnUrl, "logged-out")) {
            $returnUrl = null;
        }

        $loginWithAxysUrl = $urlGenerator->generate("openid_axys", ["return_url" => $returnUrl]);
        $response = $this->render("AppBundle:User:login.html.twig", [
            "loginWithAxysUrl" => $loginWithAxysUrl,
        ]);
        $response->headers->set("X-Robots-Tag", "noindex, nofollow");

        return $response;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function account(CurrentUser $currentUser, TemplateService $templateService): Response
    {
        return $templateService->renderResponse("AppBundle:User:account.html.twig", [
            "user_email" => $currentUser->getAxysAccount()->getEmail(),
        ]);
    }

    public function logout(UrlGenerator $urlGenerator): Response
    {
        $loggedOutUrl = $urlGenerator->generate("user_logged_out");
        $response = new RedirectResponse($loggedOutUrl, status: 302);
        $response->headers->clearCookie("user_uid");
        $response->headers->set("X-Robots-Tag", "noindex, nofollow");

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
        $response = $this->render("AppBundle:User:loggedOut.html.twig");
        $response->headers->set("X-Robots-Tag", "noindex, nofollow");

        return $response;
    }

    public function signup(): RedirectResponse
    {
        $response = new RedirectResponse("https://axys.me");
        $response->headers->set("X-Robots-Tag", "noindex, nofollow");

        return $response;
    }
}
