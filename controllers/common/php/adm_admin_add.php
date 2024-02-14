<?php

use Biblys\Service\CurrentSite;
use Biblys\Service\Mailer;
use Biblys\Service\TemplateService;
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
    $um = new AxysAccountManager();

    if ($request->getMethod() === "POST") {

        $axysAccountEmail = $request->request->get("axys_account_email");
        if ($axysAccountEmail === null) {
            throw new BadRequestHttpException("Le champ Adresse e-mail est obligatoire.");
        }

        try {
            /** @var AxysAccount $axysAccount */
            $axysAccount = $um->get(['axys_account_email' => $axysAccountEmail]);
            if (!$axysAccount) {
                throw new BadRequestHttpException(
                    "L'adresse e-mail doit correspondre à un compte utilisateur existant."
                );
            }

            if ($axysAccount->hasRight('site', $currentSite->getId())) {
                throw new BadRequestHttpException("L'utilisateur $axysAccountEmail a déjà un accès administrateur.");
            }
        } catch (BadRequestHttpException $exception) {
            $session->getFlashBag()->add("error", $exception->getMessage());
            return new RedirectResponse("/pages/adm_admin_add");
        }

        $axysAccount->giveRight('site', $currentSite->getId());

        $subject = $currentSite->getSite()->getTag() . ' | Votre accès au site';
        $message = '
            <p>Bonjour,</p>
            <p>
                Votre accès admin a été créé sur le site 
                <a href="https://' . $currentSite->getSite()->getDomain() . '/">'
            . $currentSite->getSite()->getTitle() . '</a>.
            </p>
            <p>
                Vos identifiants de connexion sont votre adresse e-mail (' . $axysAccount->get("axys_account_email") . ') 
                et le mot de passe que vous avez défini au moment de la création de votre compte 
                (vous pourrez demander à en recevoir un nouveau si besoin).
            </p>
        ';

        $um->mail($axysAccount, $subject, $message);

        $session->getFlashBag()->add(
            "success",
            "Un accès administrateur a été ajouté pour le compte $axysAccountEmail."
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
            <p>
                <label for="axys_account_email">Adresse e-mail :</label>
                <input type="email" name="axys_account_email" id="axys_account_email" value="' . ($axysAccountEmail ?? null) . '" class="long" required>&nbsp;
                
            </p>
            <br>
            <div class="center">
                <button class="btn btn-primary" type="submit">Ajouter un administrateur</button>
            </div>
        </fieldset>
    </form>
';

    return $templateService->renderResponseFromString($template);
};
