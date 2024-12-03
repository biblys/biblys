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


use Biblys\Service\CurrentSite;
use Biblys\Service\Mailer;
use Biblys\Service\TemplateService;
use Model\RightQuery;
use Model\UserQuery;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session as HttpSession;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

return function (
    Request         $request,
    CurrentSite     $currentSite,
    TemplateService $templateService,
    Mailer          $mailer,
    HttpSession     $session,
): Response|RedirectResponse
{
    if ($request->getMethod() === "POST") {

        $userEmail = $request->request->get("user_email");
        if ($userEmail === null) {
            throw new BadRequestHttpException("Le champ Adresse e-mail est obligatoire.");
        }

        try {
            $user = UserQuery::create()
                ->filterBySite($currentSite->getSite())
                ->findOneByEmail($userEmail);
            if (!$user) {
                throw new BadRequestHttpException(
                    "L'adresse e-mail doit correspondre à un compte utilisateur existant."
                );
            }

            $isUserAlreadyAdmin = RightQuery::create()
                ->isUserAdminForSite($user, $currentSite->getSite());
            if ($isUserAlreadyAdmin) {
                throw new BadRequestHttpException("L'utilisateur $userEmail a déjà un accès administrateur.");
            }
        } catch (BadRequestHttpException $exception) {
            $session->getFlashBag()->add("error", $exception->getMessage());
            return new RedirectResponse("/pages/adm_admin_add");
        }

        $right = new \Model\Right();
        $right->setUser($user);
        $right->setSite($currentSite->getSite());
        $right->setIsAdmin(true);
        $right->save();

        $subject = "Votre accès admin au site {$currentSite->getSite()->getTitle()}";
        $message = '
            <p>Bonjour,</p>
            <p>
                Votre accès admin a été créé sur le site 
                <a href="https://' . $currentSite->getSite()->getDomain() . '/">'
            . $currentSite->getSite()->getTitle() . '</a>.
            </p>
            <p>
                Vos identifiants de connexion sont votre adresse e-mail (' . $user->getEmail() . ') 
                et le mot de passe que vous avez défini au moment de la création de votre compte 
                (vous pourrez demander à en recevoir un nouveau si besoin).
            </p>
        ';

        $mailer->send(to: $user->getEmail(), subject: $subject, body: $message);

        $session->getFlashBag()->add(
            "success",
            "Un accès administrateur a été ajouté pour le compte $userEmail."
        );

        return new RedirectResponse("/pages/adm_admins");
    }

    $request->attributes->set("page_title", "Ajouter un administrateur");

    $template = '
    <h2><span class="fa fa-user-plus"></span> Ajouter un administrateur</h2>

    <p class="alert alert-warning">
        <span class="fa fa-warning"></span>&nbsp;
        L\'utilisateur obtiendra tous les droits d\'administration sur le site.
    </p>

    <p class="alert alert-info">
            <span class="fa fa-info-circle"></span>&nbsp;
            L\'adresse e-mail doit correspondre à un compte utilisateur existant.
            Si ce n\'est pas le cas, invitez le futur administrateur à créer au préalable
            un compte utilisateur et à vous communiquer l\'adresse e-mail utilisée.
        </p>
    
        <form method="post" class="check">
        <fieldset>
            <div class="form-group"">
                <label for="user_email">Adresse e-mail :</label>
                <input type="email"  class="form-control" name="user_email" id="user_email" value="' . ($userEmail ?? null) . '" required>&nbsp;
            </div>
            <div class="center">
                <button class="btn btn-primary" type="submit">Ajouter un administrateur</button>
            </div>
        </fieldset>
    </form>
';

    return $templateService->renderResponseFromString($template);
};
