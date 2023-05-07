<?php

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/** @var Request $request */

$um = new UserManager();

if ($request->getMethod() === "POST") {

    $user_email = $request->request->get("user_email", false);

    if (!$user_email) {
        trigger_error("Le champ Adresse e-mail est obligatoire.");
    }

    // Create a user if it doesn't exist
    $user = $um->get(['user_email' => $user_email]);
    if (!$user)  {
        $user = $um->create(array("user_email" => $user_email));
        $params['created'] = 1;
    }

    // Set admin right
    if (!$user->hasRight('site', $_SITE['site_id'])) {
        $user->giveRight('site', $_SITE['site_id']);
    }

    // E-mail de bienvenue
    if ($user->get('user_just_created')) // Si nouvel utilisateur, on ajoute les identifiants
    {
        $credentials = '
            <p>
                Vos identifiants de connexion Axys :<br />
                Adresse e-mail : '.$user->get("user_email").'<br />
                Le mot de passe vous a été envoyé par courriel. Nous vous invitons à le changer dès votre première connexion.
            </p>
        ';
    }
    else
    {
        $credentials = '
            <p>
                Vos identifiants de connexion sont votre adresse e-mail ('.$user->get("user_email").') et le mot de passe que vous avez défini au moment de la création de votre compte Axys (vous pourrez demander à en recevoir un nouveau si besoin).
            </p>
        ';
    }

    $headers = 'From: '.$_SITE['site_title'].' <'.$_SITE['site_contact'].'>'."\r\n";
    $subject = $_SITE['site_tag'].' | Votre accès au site';
    $message = '
<p>Bonjour,</p>
<p>Votre accès administrateur a été créé sur le site <a href="https://'.$_SITE['site_domain'].'/">'.$_SITE['site_title'].'</a>.</p>
'.$credentials;

    $um->mail($user,$subject,$message,$headers);

    $params["added"] = 1;
    $params["email"] = $user->get("user_email");
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
                <label for="user_email">Adresse e-mail :</label>
                <input type="email" name="user_email" id="user_email" value="'.($user_email ?? null).'" class="long" required>&nbsp;
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
