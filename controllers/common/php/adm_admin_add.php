<?php

use Biblys\Service\CurrentSite;
use Biblys\Service\Mailer;
use Biblys\Service\TemplateService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

/**
 * @throws Exception
 * @throws TransportExceptionInterface
 */
return function (
    Request $request,
    CurrentSite $currentSite,
    TemplateService $templateService,
    Mailer $mailer,
): Response|RedirectResponse
{
    $um = new AxysAccountManager();

    if ($request->getMethod() === "POST") {

        $axysAccountEmail = $request->request->get("axys_account_email");
        if ($axysAccountEmail === null) {
            throw new BadRequestHttpException("Le champ Adresse e-mail est obligatoire.");
        }

        /** @var AxysAccount $user */
        $user = $um->get(['axys_account_id' => $axysAccountEmail]);
        if (!$user) {
            throw new BadRequestHttpException(
                "L'adresse e-mail doit correspondre à un compte utilisateur existant."
            );
        }

        if (!$user->hasRight('site', $currentSite->getId())) {
            $user->giveRight('site', $currentSite->getId());
        }

        $subject = $currentSite->getSite()->getTag() . ' | Votre accès au site';
        $message = '
            <p>Bonjour,</p>
            <p>
                Votre accès administrateur a été créé sur le site 
                <a href="https://' . $currentSite->getSite()->getDomain() . '/">'
            . $currentSite->getSite()->getTitle() . '
                </a>.
            </p>
            <p>
                Vos identifiants de connexion sont votre adresse e-mail (' . $user->get("user_email") . ') 
                et le mot de passe que vous avez défini au moment de la création de votre compte 
                (vous pourrez demander à en recevoir un nouveau si besoin).
            </p>
        ';

        $um->mail($user, $subject, $message);

        $params["added"] = 1;
        $params["email"] = $user->get("axys_account_email");
        $queryParams = http_build_query($params);

        return new RedirectResponse("/pages/adm_admins?$queryParams");
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

    return $templateService->renderFromString($template);
};
