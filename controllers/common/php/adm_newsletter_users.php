<?php

global $urlgenerator, $request;

use Symfony\Component\HttpFoundation\Response;

$request->attributes->set("page_title", "Abonnés à la newsletterr");

$content = '
    <p class="pull-right">
        <a href="'.$urlgenerator->generate('mailing_contacts').'" class="btn btn-info btn-sm">Exporter les contacts</a>
    </p>
    <h1><span class="fa fa-address-book"></span> Abonnés à la newsletter</h1>

    <p class="alert alert-warning">
        <span class="fa fa-warning"></span>
        <strong>La fonctionnalité &laquo; Abonnés à la newsletter &raquo; est dépréciée et va être supprimée.</strong><br />
        Vous pouvez utiliser en remplacement la fonctionnalité
        <a href="https://app.mailjet.com/contacts">Liste de contacts</a>
        de Mailjet.
    </p>

    <form method="post" class="fieldset">
        <fieldset>
            <h2>Ajouter une ou plusieurs adresses :</h2>
            <textarea class="form-control" name="emails" rows="5">'.$request->request->get("emails").'</textarea><br>
            <button type="submit" class="btn btn-primary">Enregistrer les adresses</button>
        </fieldset>
    </form>
';

$mm = new MailingManager();
$mailings = $mm->getAll([], ['order' => 'mailing_created', 'sort' => 'desc']);
$num_mailing = count($mailings);

$ajouts = null;
if (!empty($_POST)) {

    function extract_email_address ($string): array
    {
        $emails = array();
        $string = str_replace("\r\n",' ',$string);
        $string = str_replace("\n",' ',$string);

        foreach(explode(' ', $string) as $token) {
             $email = filter_var($token, FILTER_VALIDATE_EMAIL);
             if ($email !== false) {
                 $emails[] = $email;
             }
         }
         return $emails;
     }

    // Extrait les adresses e-mail de cette chaîne
    $emails = extract_email_address ($request->request->get("emails"));

    foreach($emails as $email) {
        $erreur = NULL;
        if(!empty($email)) {
            $mailing = $mm->get(['mailing_email' => $email]);
            if ($mailing) $erreur = "adresse déj&agrave; en base !";
            $syntaxe='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,5}$#';
            if(!preg_match($syntaxe,$email)) $erreur = "adresse $email mal formée !";

            if(!empty($erreur)) $ajouts .= '<span class="error">'.$email.'</span> : '.$erreur.'<br />';
            else {
                $mm->create([
                    'mailing_email' => $email,
                    'mailing_checked' => 1
                ]);
                $ajouts .= '<span class="success">'.$email.'</span><br />';
                $num_mailing++;
            }
        }
    }
}

$emails = null;
$num_emails = 0;
$invalid = null;
$num_invalid = 0;
$unsub = null;
$num_unsub = 0;
foreach ($mailings as $m) {
    if($m["mailing_checked"] == 0) {
        $invalid .= '<tr class="pointer"><td class="center"><input type="checkbox" data-id="'.$m["mailing_id"].'"></td><td>'.$m["mailing_email"].'</td><td class="center">'._date($m["mailing_date"],'d/m/Y').'</td><td class="status warning">Invalide</td></tr>';
        $num_invalid++;
    } elseif($m["mailing_block"] == 1) {
        $unsub .= '<tr class="pointer"><td class="center"><input type="checkbox" data-id="'.$m["mailing_id"].'"></td><td>'.$m["mailing_email"].'</td><td class="center">'._date($m["mailing_date"],'d/m/Y').'</td><td class="status error">Désabonné</td></tr>';
        $num_unsub++;
    } else {
        $emails .= '<tr class="pointer"><td class="center"><input type="checkbox" data-id="'.$m["mailing_id"].'"></td><td>'.$m["mailing_email"].'</td><td class="center">'._date($m["mailing_date"],'d/m/Y').'</td><td class="status success">Abonné</td></tr>';
        $num_emails++;
    }
}

$content .= '
    '.$ajouts.'
    <br />


    <table class="admin-table">
        <thead>
            <tr>
                <th class="center">Abonnés</th>
                <th class="center">Désabonnés</th>
                <th class="center">Invalides</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="center">
                    <img src="/common/icons/success_16.png" alt="" role="presentation" /> 
                    '.$num_emails.'
                </td>
                <td class="center">
                    <img src="/common/icons/error_16.png" alt="" role="presentation" /> 
                    '.$num_unsub.'
                </td>
                <td class="center">
                    <img src="/common/icons/warning_16.png" alt="" role="presentation" /> 
                    '.$num_invalid.'
                </td>
            </tr>
        </tbody>
    </table>

    <br />
    <form id="users"><fieldset>
        <table class="admin-table sortable checkable">
            <thead>
                <tr>
                    <th></th>
                    <th>E-mail</th>
                    <th>Depuis le</th>
                    <th>&Egrave;tat</th>
                </tr>
            </thead>
            <tbody>
                '.$emails.'
                '.$unsub.'
                '.$invalid.'
            </tbody>
        </table>

        <div class="floatingSubmit">
            <div>
                Pour la sélection (<span id="sel_count">0</span>) :
                <a class="btn btn-primary doit" data-action="resub">réabonner</a>
                <a class="btn btn-warning doit" data-action="unsub">désabonner</a>
            </div>
        </div>
    </fieldset></form>
';

return new Response($content);
