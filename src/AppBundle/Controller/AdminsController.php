<?php
/*
 * Copyright (C) 2025 Clément Latzarus
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

use Biblys\Exception\InvalidEmailAddressException;
use Biblys\Service\BodyParamsService;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\FlashMessagesService;
use Biblys\Service\Mailer;
use Biblys\Service\TemplateService;
use Framework\Controller;
use Model\Right;
use Model\RightQuery;
use Model\User;
use Model\UserQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AdminsController extends Controller
{
    /**
     * @throws LoaderError
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     */
    public function newAction(CurrentUser $currentUser , TemplateService $templateService): Response
    {
        $currentUser->authAdmin();

        $users = UserQuery::create()
            ->orderByCreatedAt(Criteria::DESC)
            ->limit(1000)
            ->find();

        return $templateService->renderResponse("AppBundle:Admins:new.html.twig", [
            "users" => $users,
        ]);
    }

    /**
     * @throws InvalidEmailAddressException
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     */
    public function createAction(
        BodyParamsService $bodyParams,
        CurrentUser $currentUser,
        CurrentSite $currentSite,
        UrlGenerator $urlGenerator,
        FlashMessagesService $flashMessages,
        Mailer $mailer,
        TemplateService $templateService,
    ): RedirectResponse
    {
        $currentUser->authAdmin();

        $bodyParams->parse(["user_email" => ["type" => "string"]]);
        $userEmail = $bodyParams->get("user_email");

        try {
            $user = UserQuery::create()
                ->filterBySite($currentSite->getSite())
                ->findOneByEmail($userEmail);
            if (!$user) {
                $user = new User();
                $user->setEmail($userEmail);
                $user->setSite($currentSite->getSite());
                $flashMessages->add("info", "Un compte utilisateur a été créé pour $userEmail.");
            }

            $isUserAlreadyAdmin = RightQuery::create()
                ->isUserAdminForSite($user, $currentSite->getSite());
            if ($isUserAlreadyAdmin) {
                throw new BadRequestHttpException("L'utilisateur $userEmail a déjà un accès administrateur.");
            }
        } catch (BadRequestHttpException $exception) {
            $adminAddUrl = $urlGenerator->generate("admins_new");
            $flashMessages->add("error", $exception->getMessage());
            return new RedirectResponse($adminAddUrl);
        }

        $right = new Right();
        $right->setUser($user);
        $right->setSite($currentSite->getSite());
        $right->setIsAdmin(true);
        $right->save();

        $adminUrl = $urlGenerator->generate("main_admin", [], UrlGeneratorInterface::ABSOLUTE_URL);
        $adminEmailBody = $templateService->render("AppBundle:Admins:admin-welcome_email.html.twig", [
            "email" => $userEmail,
            "adminUrl" => $adminUrl,
        ]);
        $mailer->send(
            to: $user->getEmail(),
            subject: "Votre accès admin au site {$currentSite->getSite()->getTitle()}",
            body: $adminEmailBody
        );

        $admins = RightQuery::create()
            ->filterByIsAdmin(true)
            ->joinWithUser()
            ->find();
        $adminsPageUrl = "https://{$currentSite->getSite()->getDomain()}/pages/adm_admins";
        $alertEmailBody = $templateService->render("AppBundle:Admins:admin-added-alert_email.html.twig", [
            "email" => $userEmail,
            "adminsPageUrl" => $adminsPageUrl,
        ]);
        foreach ($admins as $admin) {
            $adminEmail = $admin->getUser()->getEmail();
            $mailer->send(
                to: $adminEmail,
                subject: "Alerte de sécurité : nouvel·le admin ajouté·e",
                body: $alertEmailBody
            );
        }

        $flashMessages->add("success", "Un accès administrateur a été ajouté pour le compte $userEmail.");
        return new RedirectResponse("/pages/adm_admins");
    }
}