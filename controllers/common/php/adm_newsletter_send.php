<?php

use Biblys\Service\Config;
use Biblys\Service\Mailer;

$config = new Config();
$smtp = $config->get('smtp');
if (!$smtp) {
    throw new Exception('SMTP doit être configuré pour envoyer la newsletter.');
}

/** @var $request */
$offset = $request->query->get('offset', 0);

$sent = 0;
$sendAll = false;

$mm = new MailingManager();

/** @var Site $site */
$bulkEmailBatch = $site->getOpt('bulk_email_batch');
if (!$bulkEmailBatch) {
    $bulkEmailBatch = 10;
}

$defaultCampaignName = 'newsletter-'.$site->get("name").'-'.date('Y-m-d');
$campaignName = $request->request->get('campaignName', $defaultCampaignName);

$_PAGE_TITLE = 'Envoyer la newsletter';

$_ECHO .= '<h1><span class="fa fa-send"></span> Envoyer la newsletter</h1>';
    
$_ECHO .= '<p class="alert alert-warning">
        L\'outil intégré à Biblys ne permet 
        pas de prouver le consentement des utilisateurs inscrits à la
        newsletter. Pour être en confirmité avec le Règlement Général sur la
        Protection des Données, vous devriez un outil spécialisé 
        (<a href="https://fr.mailjet.com/blog/news/rgpd-comment-requalifier-vos-listes-et-prouver-le-consentement-de-vos-contacts/">en
        savoir plus</a>).
    </p>';

// Destinataires
$req = null;
if (empty($_POST["envoi"])) {
    $req = " AND `mailing_email` = '".$this->user->get("email")."' ";
}

$mailingQuery = [
    'mailing_block' => 0,
    'mailing_checked' => 1,
];

$emails = $mm->getAll($mailingQuery);
$emailsCount = count($emails);

// Envoi
if (!empty($_POST)) {

    $offset = $request->request->get('offset', $offset);
    $sendAll = $request->request->get('sendAll', $sendAll);

    // Subject
    $subject = stripslashes($_POST["objet"]);

    // Message
    $message = stripslashes($_POST["message"]); // Retiré utf8_encode de $_POST[message] le 29/10/2013 pour Lettre d'Ys

    // Ajout des liens utm au message;
    function process_link_matches(array $matches): string
    {
        global $_SITE, $campaignName;
        $vars = 'utm_source=newsletter-'.$_SITE["site_name"].'&utm_medium=e-mail&utm_campaign='.slugify($campaignName);
        if (preg_match('/\\?[^"]/', $matches[2])) {
            $matches[2] .= '&'.$vars;
        } // link already contains $_GET parameters
        else {
            $matches[2] .= '?'.$vars;
        } // starting a new list of parameters
        return '<a href="'.$matches[2].'">'.$matches[3].'</a>';
    }
    $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
    $message = preg_replace_callback("/$regexp/siU", 'process_link_matches', $message);

    // By default, send only to current user
    $users = [["mailing_email" => $this->user->get('email')]];
    
    // If checked, 
    if ($sendAll) {
        $users = $mm->getAll($mailingQuery, [
            "offset" => $offset,
            "limit" => $bulkEmailBatch,
        ]);
    }

    $mails = array();
    $protocol = $request->isSecure() ? 'https' : 'http';
    foreach ($users as $u) {
        $m = [];
        $m["to"] = $u["mailing_email"];
        $m["subject"] = $subject;
        $m["unsubscribeLink"] = $protocol.'://'.$site->get("domain").'/mailing/unsubscribe?email='.$m["to"];
        $m["footer"] = '
            <div id="footer">
                <p style="text-align: center;">
                    Si vous ne souhaitez plus recevoir cette newsletter, rendez-vous &agrave; l\'adresse ci-dessous :<br />
                    <a href="'.$m["unsubscribeLink"].'">'.$m["unsubscribeLink"].'</a>
                </p>
                <p style="text-align: center;">
                    Ce courriel a été transmis via <a href="'.$protocol.'://www.biblys.fr/">Biblys</a>.<br />
                    Si vous pensez qu\'il s\'agit d\'un spam, merci de le faire suivre &agrave; <a href="mailto:abuse@biblys.fr">abuse@biblys.fr</a>
                </p>
            </div>
        ';
        $mails[] = $m;
    }

    // Common headers for all recipients
    $baseHeaders = [
        "Content-Type" => "text/html; charset=utf-8",
        "Precedence" => "bulk",
        "X-Mailjet-Campaign" => $campaignName,
    ];

    // If sending to all, we don't want duplicates
    if ($sendAll) {
        $baseHeaders["X-Mailjet-DeduplicateCampaign"] = "true";
    }

    foreach ($mails as $m) {
        $m["message"] = '<html lang="fr"><head><title>'.$m["subject"].'</title><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><style>'.$_POST['css'].'</style></head><body>'.$message.stripslashes($m["footer"]).'</body></html>';

        $headers = $baseHeaders;
        $headers["List-Unsubscribe"] = "<".$m["unsubscribeLink"].">";

        $mailer = new Mailer();
        $mailer->send(
            $m["to"], // to
            $m["subject"], // subject
            $m["message"], // body
            [$site->get('site_contact') => $site->get('site_title')], // from
            [], // options
            $headers // headers
        );

        $sent++;
    }

    if ($sendAll) {
        $totalSent = $offset + $sent;
        $offset = $totalSent;
        

        if ($totalSent < $emailsCount) {
            $percent = round(($totalSent / $emailsCount) * 100);
            $_ECHO .= '
                <p class="alert alert-warning">
                    <span class="fa fa-spinner fa-spin"></span>
                    Envois toujours en cours, ne quittez pas la page...
                    ('.$totalSent.' sur '.$emailsCount.')
                </p>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="'.$percent.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$percent.'%;">
                        <span style="display: inline-block; width:50px;">'.$percent.'&nbsp;%</span>
                    </div>
                </div>
                <script>
                    setTimeout(function() { $("#newsletter").submit(); }, 100);
                </script>
            ';
        } else {
            $_ECHO .='<p class="success">La newsletter a été envoyée à '.$totalSent.' inscrit'.s($totalSent).'.</p>';
        }

    } else {
        $_ECHO .= '<p class="success">Une newsletter de test a été envoyée à '.$this->user->get('email').'</p>';
    }
}

$_ECHO .= '

<form method="post" action="/pages/adm_newsletter_send" id="newsletter" class="fieldset">
    <fieldset>

        <legend>En-tête</legend>
        <input type="hidden" name="offset" value="'.$offset.'" />

        <label for="from">De :</label>
        <input type="text" id="from" name="from" value="'.$site->get("title").' &lt;'.$site->get("contact").'&gt;" class="long" readonly />
        <br />

        <label for="to">&Agrave; :</label>
        <input type="text" id="to" name="to" value="'.$emailsCount.' abonn&eacute;s" readonly />
        <br />

        <label for="objet">Objet :</label>
        <input type="text" id="objet" name="objet" value="'.(isset($_POST["objet"]) ? stripslashes($_POST["objet"]) : null).'" class="long" required />
        <br />

        <label for="campaignName">Nom de campagne :</label>
        <input type="text" id="campaignName" name="campaignName" value="'.$campaignName.'" class="long" required />
        <br />
    </fieldset>
    <fieldset>
        <legend>Contenu</legend>
        <textarea name="message" class="wysiwyg">'.(isset($_POST["message"]) ? stripslashes($_POST["message"]) : null).'</textarea>
    </fieldset>
    <fieldset>
        <legend>CSS personnalisé</legend>
        <textarea name="css">'.(isset($_POST["css"]) ? stripslashes($_POST["css"]) : null).'</textarea>
    </fieldset>
    <fieldset>
        <legend>Envoi</legend>

        <input type="radio" id="sendAll-0" name="sendAll" value="0" '.($sendAll ? '' : ' checked').'>
        <label for="sendAll-0" class="after">Envoyer un e-mail de test à '.$this->user->get('email').'</label>
        <br/>

        <input type="radio" id="sendAll-1" name="sendAll" value="1" '.($sendAll ? ' checked' : '').'>
        <label for="sendAll-1" class="after">Envoyer pour de bon à '.$emailsCount.' abonnés</label>
        <br/>

        <p class="center"><button type="submit" class="btn btn-primary">Envoyer</button></p>
    </fieldset>
</form>

';
