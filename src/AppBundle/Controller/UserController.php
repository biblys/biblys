<?php

namespace AppBundle\Controller;

use Biblys\Exception\InvalidConfigurationException;
use Biblys\Exception\InvalidEmailAddressException;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\InvalidTokenException;
use Biblys\Service\Mailer;
use Biblys\Service\QueryParamsService;
use Biblys\Service\TemplateService;
use Biblys\Service\TokenService;
use DateTime;
use Exception;
use Framework\Controller;
use Model\Session;
use Model\User;
use Model\UserQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
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
     * @throws Exception
     */
    public function indexAction(
        CurrentSite     $currentSite,
        CurrentUser     $currentUser,
        TemplateService $templateService,
    ): Response
    {
        $currentUser->authAdmin();

        $users = UserQuery::create()
            ->filterBySite($currentSite->getSite())
            ->orderByLastLoggedAt()
            ->find();

        return $templateService->renderResponse("AppBundle:User:index.html.twig", [
            "users" => $users->getData(),
        ]);
    }


    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function login(
        QueryParamsService $queryParams,
        CurrentUser        $currentUser,
        UrlGenerator       $urlGenerator,
    ): Response
    {
        if ($currentUser->isAuthentified()) {
            return new RedirectResponse("/");
        }

        $queryParams->parse(["return_url" => ["type" => "string", "default" => ""]]);
        $returnUrl = $queryParams->get("return_url");
        if (str_contains($returnUrl, "logged-out")) {
            $returnUrl = null;
        }

        $loginWithAxysUrl = $urlGenerator->generate("openid_axys", ["return_url" => $returnUrl]);
        $response = $this->render("AppBundle:User:login.html.twig", [
            "loginWithAxysUrl" => $loginWithAxysUrl,
            "returnUrl" => $returnUrl,
        ]);
        $response->headers->set("X-Robots-Tag", "noindex, nofollow");

        return $response;
    }

    /**
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws InvalidEmailAddressException
     * @throws TransportExceptionInterface
     * @throws InvalidConfigurationException
     */
    public function sendLoginEmailAction(
        Request         $request,
        CurrentSite     $currentSite,
        TokenService    $tokenService,
        TemplateService $templateService,
        UrlGenerator    $urlGenerator,
        Mailer          $mailer,
    ): Response
    {
        $recipientEmail = $request->request->get("email");
        $returnUrl = $request->request->get("return_url", "/");
        $senderEmail = $currentSite->getSite()->getContact();

        $userAccountExists = UserQuery::create()
            ->filterBySite($currentSite->getSite())
            ->findOneByEmail($recipientEmail);

        if ($userAccountExists) {
            $expirationDate = new DateTime("+24 hours");
            $loginToken = $tokenService->createLoginToken(email: $recipientEmail, afterLoginUrl: $returnUrl);
            $loginRelativeUrl = $urlGenerator->generate("user_login_with_token", [
                "token" => $loginToken
            ]);
            $loginUrl = $request->getSchemeAndHttpHost() . $loginRelativeUrl;
            $body = $templateService->render(
                "AppBundle:User:login-with-email-email.html.twig",
                [
                    "recipientEmail" => $recipientEmail,
                    "loginUrl" => $loginUrl,
                    "siteTitle" => $currentSite->getSite()->getTitle(),
                    "domain" => $currentSite->getSite()->getDomain(),
                    "expirationDate" => $expirationDate->format("d/m/Y à H\hi"),
                ]
            );
            $mailer->send(
                to: $recipientEmail,
                subject: "Connectez-vous en un clic sur {$currentSite->getSite()->getDomain()}",
                body: $body,
            );
        }

        return $templateService->renderResponse(
            "AppBundle:User:send-login-email.html.twig",
            [
                "emailExists" => $userAccountExists,
                "recipientEmail" => $recipientEmail,
                "returnUrl" => $returnUrl,
                "senderEmail" => $senderEmail,
            ],
        );
    }

    /**
     * @throws InvalidConfigurationException
     * @throws PropelException
     */
    public function loginWithTokenAction(
        Request            $request,
        QueryParamsService $queryParams,
        TokenService       $tokenService,
        CurrentSite        $currentSite,
        CurrentUser        $currentUser,
    ): RedirectResponse
    {
        $queryParams->parse(["token" => ["type" => "string"]]);
        $token = $queryParams->get("token");

        try {
            $token = $tokenService->decodeLoginToken($token);
            $user = UserQuery::create()
                ->filterBySite($currentSite->getSite())
                ->findOneByEmail($token["email"]);
            if (!$user) {
                throw new BadRequestHttpException("Ce lien de connexion est invalide.");
            }

            $user->setLastLoggedAt(new DateTime());
            $user->save();

            $sessionExpiresAt = new DateTime("+ 7 days");
            $sessionCookie = self::_createSession(
                $currentSite,
                $sessionExpiresAt,
                $user
            );

            $currentUser->setUser($user);
            $currentUser->transfertVisitorCartToUser(visitorToken: $request->cookies->get("visitor_uid"));

            $redirectUrl = !empty($token["after_login_url"]) ? $token["after_login_url"] : "/";
            $response = new RedirectResponse($redirectUrl);
            $response->headers->setCookie($sessionCookie);
            $response->headers->set("X-Robots-Tag", "noindex, nofollow");

            return $response;
        } catch (InvalidTokenException) {
            throw new BadRequestHttpException("Ce lien de connexion est invalide.");
        }
    }

    /**
     * @throws PropelException
     */
    private static function _createSession(
        CurrentSite $currentSite,
        DateTime    $sessionExpiresAt,
        User        $user,
    ): Cookie
    {
        $session = new Session();
        $session->setUser($user);
        $session->setSite($currentSite->getSite());
        $session->setToken(Session::generateToken());
        $session->setExpiresAt($sessionExpiresAt);
        $session->save();

        return Cookie::create("user_uid")
            ->withValue($session->getToken())
            ->withExpires($sessionExpiresAt);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function account(CurrentUser $currentUser, TemplateService $templateService): Response
    {
        return $templateService->renderResponse("AppBundle:User:account.html.twig", [
            "user_email" => $currentUser->getUser()->getEmail(),
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
