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


namespace Usecase;

use Biblys\Exception\InvalidEmailAddressException;
use Biblys\Service\CurrentSite;
use Biblys\Service\FlashMessagesService;
use Biblys\Service\Mailer;
use Biblys\Service\TemplateService;
use Model\Right;
use Model\RightQuery;
use Model\User;
use Model\UserQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AddAdminUsecase
{
    public function __construct(
        private readonly CurrentSite $currentSite,
        private readonly FlashMessagesService $flashMessages,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly TemplateService $templateService,
        private readonly Mailer $mailer,
    )
    {}

    /**
     * @throws TransportExceptionInterface
     * @throws RuntimeError
     * @throws InvalidEmailAddressException
     * @throws LoaderError
     * @throws SyntaxError
     * @throws PropelException
     * @throws BusinessRuleException
     */
    public function execute(string $userEmail): void
    {
        $user = UserQuery::create()
            ->filterBySite($this->currentSite->getSite())
            ->findOneByEmail($userEmail);
        if (!$user) {
            $user = new User();
            $user->setEmail($userEmail);
            $user->setSite($this->currentSite->getSite());
            $this->flashMessages->add("info", "Un compte utilisateur a été créé pour $userEmail.");
        }

        $isUserAlreadyAdmin = RightQuery::create()->isUserAdmin($user);
        if ($isUserAlreadyAdmin) {
            throw new BusinessRuleException("L'utilisateur $userEmail a déjà un accès administrateur.");
        }

        $right = new Right();
        $right->setUser($user);
        $right->setSite($this->currentSite->getSite());
        $right->setIsAdmin(true);
        $right->save();

        $adminUrl = $this->urlGenerator->generate("main_admin", [], UrlGeneratorInterface::ABSOLUTE_URL);
        $adminEmailBody = $this->templateService->render("AppBundle:Admins:admin-welcome_email.html.twig", [
            "email" => $userEmail,
            "adminUrl" => $adminUrl,
        ]);
        $this->mailer->send(
            to: $user->getEmail(),
            subject: "Votre accès admin au site {$this->currentSite->getSite()->getTitle()}",
            body: $adminEmailBody
        );

        $admins = RightQuery::create()
            ->filterByIsAdmin(true)
            ->joinWithUser()
            ->find();
        $adminsPageUrl = "https://{$this->currentSite->getSite()->getDomain()}/pages/adm_admins";
        $alertEmailBody = $this->templateService->render("AppBundle:Admins:admin-added-alert_email.html.twig", [
            "email" => $userEmail,
            "adminsPageUrl" => $adminsPageUrl,
        ]);
        foreach ($admins as $admin) {
            $adminEmail = $admin->getUser()->getEmail();
            $this->mailer->send(
                to: $adminEmail,
                subject: "Alerte de sécurité : nouvel·le admin ajouté·e",
                body: $alertEmailBody
            );
        }

        $this->flashMessages->add("success", "Un accès administrateur a été ajouté pour le compte $userEmail.");
    }
}