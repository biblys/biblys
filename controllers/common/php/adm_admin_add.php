<?php

use Biblys\Legacy\LegacyCodeHelper;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/** @var Request $request */

$um = new AxysAccountManager();

if ($request->getMethod() === "POST") {

    $axysAccountEmail = $request->request->get("axys_account_email", false);

    if (!$axysAccountEmail) {
        trigger_error("Le champ Adresse e-mail est obligatoire.");
    }

    // Create a user if it doesn't exist
    $user = $um->get(['axys_account_email' => $axysAccountEmail]);
    if (!$user)  {
        $user = $um->create(array("axys_account_email" => $axysAccountEmail));
        $params['created'] = 1;
    }

    // Set admin right
    if (!$user->hasRight('site', LegacyCodeHelper::getLegacyCurrentSite()['site_id'])) {
        $user->giveRight('site', LegacyCodeHelper::getLegacyCurrentSite()['site_id']);
    }

    // E-mail de bienvenue
    if ($user->get('axys_account_just_created')) // Si nouvel utilisateur, on ajoute les identifiants
    {
        $credentials = '
            <p>
                Vos identifiants de connexion Axys :<br />
                Adresse e-mail : '.$user->get("axys_account_email").'<br />
                Le mot de passe vous a été envoyé par courriel. Nous vous invitons à le changer dès votre première connexion.
            </p>
        ';
    }
    else
    {
        $credentials = '
            <p>
                Vos identifiants de connexion sont votre adresse e-mail ('.$user->get("axys_account_email").') et le mot de passe que vous avez défini au moment de la création de votre compte Axys (vous pourrez demander à en recevoir un nouveau si besoin).
            </p>
        ';
    }

    $headers = 'From: '. LegacyCodeHelper::getLegacyCurrentSite()['site_title'].' <'. LegacyCodeHelper::getLegacyCurrentSite()['site_contact'].'>'."\r\n";
    $subject = LegacyCodeHelper::getLegacyCurrentSite()['site_tag'].' | Votre accès au site';
    $message = '
<p>Bonjour,</p>
<p>Votre accès administrateur a été créé sur le site <a href="https://'. LegacyCodeHelper::getLegacyCurrentSite()['site_domain'].'/">'. LegacyCodeHelper::getLegacyCurrentSite()['site_title'].'</a>.</p>
'.$credentials;

    $um->mail($user,$subject,$message,$headers);

    $params["added"] = 1;
    $params["email"] = $user->get("axys_account_email");
    $queryParams = http_build_query($params);

    return new RedirectResponse("/pages/adm_admins?$queryParams");
}

$request->attributes->set("page_title", "Ajouter un administrateur");

$content = '
    <h2><span class="fa fa-user-plus"></span> Ajouter un administrateur</h2>

    <p class="alert alert-warning">
        <span class="fa fa-warning"></span>&nbsp;
        L\'utilisateur obtiendra tous les droits d\'administration sur le site.
    </p>

    <form method="post" class="check">
        <fieldset>
            <p>
                <label for="axys_account_email">Adresse e-mail :</label>
                <input type="email" name="axys_account_email" id="axys_account_email" value="'.($axysAccountEmail ?? null).'" class="long" required>&nbsp;
                <span class="fa fa-info-circle" title="Si cette adresse ne correspond pas à un compte Axys, un nouveau compte sera créé automatiquement avec un mot de passe aléatoire envoyé par e-mail."></span>
            </p>
            <br>
            <div class="center">
                <button class="btn btn-primary" type="submit">Ajouter un administrateur</button>
            </div>
        </fieldset>
    </form>
';

return new Response($content);
