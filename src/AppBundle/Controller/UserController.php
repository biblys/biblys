<?php
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


namespace AppBundle\Controller;

use Biblys\Exception\CannotDeleteUser;
use Biblys\Exception\InvalidConfigurationException;
use Biblys\Exception\InvalidEmailAddressException;
use Biblys\Service\BodyParamsService;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\FlashMessagesService;
use Biblys\Service\InvalidTokenException;
use Biblys\Service\Mailer;
use Biblys\Service\Pagination;
use Biblys\Service\QueryParamsService;
use Biblys\Service\TemplateService;
use Biblys\Service\TokenService;
use DateTime;
use Exception;
use Framework\Controller;
use Model\AuthenticationMethodQuery;
use Model\File;
use Model\FileQuery;
use Model\OrderQuery;
use Model\Session;
use Model\StockQuery;
use Model\User;
use Model\UserQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session as CurrentSession;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Usecase\DeleteUserUsecase;

class UserController extends Controller
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    public function indexAction(
        CurrentUser        $currentUser,
        QueryParamsService $queryParams,
        TemplateService    $templateService,
    ): Response
    {
        $currentUser->authAdmin();

        $queryParams->parse([
            "p" => ["type" => "numeric", "default" => 0],
            "q" => ["type" => "string", "default" => ""],
        ]);

        $userQuery = UserQuery::create();
        $userCount = $userQuery->count();

        if ($queryParams->get("q") !== "") {
            $userQuery->filterByEmail("%" . $queryParams->get("q") . "%", Criteria::LIKE);
            $userCount = $userQuery->count();
        }

        $pages = new Pagination(currentPageIndex: $queryParams->getInteger("p"), itemCount: $userCount, limit: 100);
        $pages->setQueryParams(["q" => $queryParams->get("q")]);

        $users = $userQuery
            ->orderByEmail()
            ->offset($pages->getOffset())
            ->limit($pages->getLimit())
            ->find();

        return $templateService->renderResponse("AppBundle:User:index.html.twig", [
            "userCount" => $userCount,
            "users" => $users->getData(),
            "pages" => $pages,
            "query" => $queryParams->get("q"),
        ], isPrivate: true);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    public function showAction(
        CurrentSite     $currentSite,
        CurrentUser     $currentUser,
        TemplateService $templateService,
        int             $id,
    ): Response
    {
        $currentUser->authAdmin();

        $user = UserQuery::create()
            ->filterBySite($currentSite->getSite())
            ->findPk($id);

        return $templateService->renderResponse("AppBundle:User:admin_informations.html.twig", [
            "user" => $user,
        ], isPrivate: true);
    }

    /**
     * @throws PropelException
     */
    public function deleteAction(
        CurrentUser          $currentUser,
        UrlGenerator         $urlGenerator,
        FlashMessagesService $flashMessages,
        int                  $id
    ): RedirectResponse
    {
        $currentUser->authAdmin();

        $user = UserQuery::create()->findPk($id);
        if ($user) {
            try {
                $usecase = new DeleteUserUsecase();
                $usecase->execute($user);
            } /** @noinspection PhpRedundantCatchClauseInspection */ catch (CannotDeleteUser $exception) {
                $flashMessages->add("error", $exception->getMessage());
                $userPageUrl = $urlGenerator->generate("admin_user_informations", ["id" => $id]);
                return new RedirectResponse($userPageUrl);
            }
        }

        $flashMessages->add("success", "L'utilisateur {$user->getEmail()} a bien été supprimé.");

        $usersPageUrl = $urlGenerator->generate("user_index");
        return new RedirectResponse($usersPageUrl);
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
        Config             $config
    ): Response
    {
        if ($currentUser->isAuthenticated()) {
            return new RedirectResponse("/");
        }

        $queryParams->parse(["return_url" => ["type" => "string", "default" => ""]]);
        $returnUrl = $queryParams->get("return_url");

        $returnUrlsToIgnore = [
            "/user/send-login_email",
        ];
        if (in_array($returnUrl, $returnUrlsToIgnore)) {
            $returnUrl = null;
        }

        $loginWithAxysUrl = null;
        if ($config->isAxysEnabled()) {
            $loginWithAxysUrl = $urlGenerator->generate("openid_axys", ["return_url" => $returnUrl]);
        }
        $response = $this->render("AppBundle:User:login.html.twig", [
            "loginWithAxysUrl" => $loginWithAxysUrl,
            "returnUrl" => $returnUrl,
        ], isPrivate: true);
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
        Request           $request,
        BodyParamsService $bodyParams,
        CurrentSite       $currentSite,
        TokenService      $tokenService,
        TemplateService   $templateService,
        UrlGenerator      $urlGenerator,
        Mailer            $mailer,
    ): Response
    {
        $bodyParams->parse([
            "email" => ["type" => "string"],
            "return_url" => ["type" => "string", "default" => "/"],
            "username" => ["type" => "string", "default" => ""],
        ]);

        $recipientEmail = $bodyParams->get("email");
        $returnUrl = $bodyParams->get("return_url");
        $honeyPot = $bodyParams->get("username");
        $senderEmail = $currentSite->getSite()->getContact();

        try {
            $mailer->validateEmail($recipientEmail);
        } catch
        (InvalidEmailAddressException $exception) {
            throw new BadRequestHttpException($exception->getMessage(), $exception);
        }

        $userAccountExists = UserQuery::create()
            ->filterBySite($currentSite->getSite())
            ->findOneByEmail($recipientEmail);

        $tokenAction = "login-by-email";
        $targetPath = "user_login_with_token";
        $emailTemplate = "login-with-email-email";
        $emailSubject = "Connectez-vous en un clic sur {$currentSite->getSite()->getDomain()}";
        if (!$userAccountExists) {
            $tokenAction = "signup-by-email";
            $targetPath = "user_signup_with_token";
            $emailTemplate = "signup-by-email-email";
            $emailSubject = "Créez votre compte {$currentSite->getTitle()} en un clic";
        }

        $expirationDate = new DateTime("+24 hours");
        $loginToken = $tokenService->createLoginToken(
            email: $recipientEmail,
            action: $tokenAction,
            afterLoginUrl: $returnUrl
        );
        $targetUrl = $urlGenerator->generate($targetPath, [
            "token" => $loginToken
        ]);

        $body = $templateService->render(
            "AppBundle:User:$emailTemplate.html.twig",
            [
                "recipientEmail" => $recipientEmail,
                "loginUrl" => $request->getSchemeAndHttpHost() . $targetUrl,
                "siteTitle" => $currentSite->getSite()->getTitle(),
                "domain" => $currentSite->getSite()->getDomain(),
                "expirationDate" => $expirationDate->format("d/m/Y à H\hi"),
            ]
        );
        if ($honeyPot === "") {
            $mailer->send(
                to: $recipientEmail,
                subject: "$emailSubject",
                body: $body,
            );
        }

        return $templateService->renderResponse(
            "AppBundle:User:send-login-email.html.twig",
            [
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
    public function signupWithTokenAction(
        QueryParamsService $queryParams,
        TokenService       $tokenService,
        CurrentSite        $currentSite,
        UrlGenerator       $urlGenerator,
        CurrentSession     $session,
    ): RedirectResponse
    {
        $queryParams->parse(["token" => ["type" => "string"]]);
        $rawToken = $queryParams->get("token");

        try {
            $signupToken = $tokenService->decodeLoginToken($rawToken);
            if ($signupToken["action"] !== "signup-by-email") {
                throw new InvalidTokenException("Invalid token action");
            }

        } catch (InvalidTokenException) {
            throw new BadRequestHttpException("Ce lien d'inscription est invalide.");
        }

        $existingUser = UserQuery::create()
            ->filterBySite($currentSite->getSite())
            ->findOneByEmail($signupToken["email"]);
        if ($existingUser) {
            throw new BadRequestHttpException("Ce lien d'inscription est invalide.");
        }

        $user = new User();
        $user->setSite($currentSite->getSite());
        $user->setEmail($signupToken["email"]);
        $user->save();

        $session->getFlashBag()
            ->add("success", "Votre compte {$user->getEmail()} a bien été créé.");

        $loginToken = $tokenService->createLoginToken(
            email: $signupToken["email"],
            action: "login-by-email",
            afterLoginUrl: $signupToken["after_login_url"],
        );
        $loginUrl = $urlGenerator->generate("user_login_with_token", ["token" => $loginToken]);

        $response = new RedirectResponse($loginUrl);
        $response->headers->set("X-Robots-Tag", "noindex, nofollow");

        return $response;
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

            if ($token["action"] === "login-by-email") {
                $user->setEmailValidatedAt(new DateTime());
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
     * @throws PropelException
     */
    public function account(
        CurrentSite     $currentSite,
        CurrentUser     $currentUser,
        TemplateService $templateService
    ): Response
    {
        $hasAxysMethod = AuthenticationMethodQuery::create()
            ->filterBySite($currentSite->getSite())
            ->filterByIdentityProvider("axys")
            ->filterByUser($currentUser->getUser())
            ->exists();

        return $templateService->renderResponse("AppBundle:User:account.html.twig", [
            "user_email" => $currentUser->getUser()->getEmail(),
            "has_axys_method" => $hasAxysMethod,
        ], isPrivate: true);
    }

    public function logout(CurrentSession $session): Response
    {
        $session->getFlashBag()->add("success", "Vous avez été déconnecté·e. À bientôt !");

        $response = new RedirectResponse("/");
        $response->headers->clearCookie("user_uid");
        $response->headers->set("X-Robots-Tag", "noindex, nofollow");

        return $response;
    }

    /**
     * @throws InvalidConfigurationException
     * @throws InvalidEmailAddressException
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     */
    public function requestEmailUpdateAction(
        Request              $request,
        CurrentUser          $currentUser,
        TokenService         $tokenService,
        TemplateService      $templateService,
        Mailer               $mailer,
        FlashMessagesService $flashMessages,
        UrlGenerator         $urlGenerator,
    ): RedirectResponse
    {
        $currentUser->authUser();

        $newEmail = $request->request->get("new_email");
        if ($newEmail === $currentUser->getUser()->getEmail()) {
            throw new BadRequestHttpException("La nouvelle adresse doit être différente de l'ancienne.");
        }

        try {
            $mailer->validateEmail($newEmail);
        } catch (InvalidEmailAddressException $exception) {
            throw new BadRequestHttpException("L'adresse $newEmail est invalide.", $exception);
        }

        $token = $tokenService->createEmailUpdateToken($currentUser->getUser(), $newEmail);
        $validationUrl = $urlGenerator->generate("user_update_email", ["token" => $token]);
        $expirationDate = new DateTime("+24 hours");
        $emailBody = $templateService->render("AppBundle:User:email-update-email.html.twig", [
            "recipientEmail" => $newEmail,
            "validationUrl" => $request->getSchemeAndHttpHost() . $validationUrl,
            "expirationDate" => $expirationDate->format("d/m/Y à H:i"),
        ]);
        $mailer->send($newEmail, "Validez votre nouvelle adresse e-mail", $emailBody);

        $flashMessages->add(
            "info",
            "Cliquez sur le lien envoyé à $newEmail pour valider votre changement d'adresse email."
        );

        $userAccountUrl = $urlGenerator->generate("user_account");
        return new RedirectResponse($userAccountUrl);
    }

    /**
     * @throws InvalidConfigurationException
     * @throws PropelException
     */
    public function updateEmailAction(
        CurrentUser          $currentUser,
        QueryParamsService   $queryParams,
        TokenService         $tokenService,
        FlashMessagesService $flashMessages,
        UrlGenerator         $urlGenerator,
    ): RedirectResponse
    {
        $currentUser->authUser();

        $queryParams->parse(["token" => ["type" => "string"]]);
        $token = $queryParams->get("token");

        try {
            $decodedToken = $tokenService->decodeEmailUpdateToken($token);
        } catch (InvalidTokenException) {
            throw new BadRequestHttpException("Ce lien est invalide.");
        }

        if ($decodedToken["user_id"] != $currentUser->getUser()->getId()) {
            throw new BadRequestHttpException("Ce lien n'est pas utilisable avec ce compte utilisateur.");
        }

        $currentUser->getUser()->setEmail($decodedToken["new_email"]);
        $currentUser->getUser()->save();

        $flashMessages->add(
            "success",
            "Votre nouvelle adresse e-mail {$decodedToken["new_email"]} a bien été enregistrée."
        );

        $userAccountUrl = $urlGenerator->generate("user_account");
        return new RedirectResponse($userAccountUrl);
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function ordersAction(
        CurrentUser     $currentUser,
        TemplateService $templateService,
    ): Response
    {
        $currentUser->authUser();

        $orders = OrderQuery::create()
            ->filterByType('web')
            ->filterByUserId($currentUser->getUser()->getId())
            ->orderByInsert('desc')
            ->find();

        return $templateService->renderResponse("AppBundle:User:orders.html.twig", ["orders" => $orders]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     */
    public function libraryAction(
        CurrentUser        $currentUser,
        QueryParamsService $queryParams,
        TemplateService    $templateService,
    ): Response
    {
        $currentUser->authUser();

        $queryParams->parse([
            "q" => ["type" => "string", "default" => ""],
            "p" => ["type" => "numeric", "default" => 0],
        ]);
        $searchQuery = $queryParams->get("q");
        $currentPageIndex = $queryParams->get("p");

        $baseQuery = StockQuery::create()
            ->filterByUser($currentUser->getUser())
            ->useArticleQuery()
            ->filterByKeywords("%$searchQuery%", Criteria::LIKE)
            ->endUse();
        $libraryItemsCount = $baseQuery->count();

        $pagination = new Pagination(currentPageIndex: $currentPageIndex, itemCount: $libraryItemsCount, limit: 25);
        $pagination->setQueryParams(["q" => $searchQuery]);

        $libraryItems = $baseQuery
            ->orderBySellingDate(Criteria::DESC)
            ->offset($pagination->getOffset())
            ->limit($pagination->getLimit())
            ->find();

        $items = [];
        $updated = 0;
        foreach ($libraryItems as $item) {

            $article = $item->getArticle();
            if (!$article || !$article->getType()->isDownloadable()) {
                continue;
            }

            $downloadIcon = 'cloud-download';
            if ($item->getFileUpdated()) {
                $updated++;
                $downloadIcon = 'refresh';
            }

            $downloadableFiles = FileQuery::create()
                ->filterByArticleId($article->getId())
                ->filterByAccess(File::ACCESS_RESTRICTED)
                ->find()
                ->getData();

            $items[] = [
                "article" => $article,
                "updated" => $item->isFileUpdated(),
                "predownload_is_allowed" => $item->isAllowPredownload(),
                "download_icon" => $downloadIcon,
                "downloadable_files" => $downloadableFiles,
            ];
        }

        return $templateService->renderResponse("AppBundle:User:library.html.twig", [
            "updates_available" => $updated > 0,
            "items" => $items,
            "pages" => $pagination,
            "searchQuery" => $searchQuery,
        ]);
    }
}
