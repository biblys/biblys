<?php

$cm = new CustomerManager();

$_PAGE_TITLE = 'Clients';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Modifier un client existant
    if (isset($_GET['id']) && $c = $cm->get(array('customer_id' => $_GET['id']))) {
        $c = $cm->get(array('customer_id' => $_GET['id']));
        $params['updated'] = 1;
    }

    // Récupérer un client existant
    elseif (!isset($_GET['id'])) {
        $c = $cm->create();
        $params['created'] = 1;
    }

    // Update object
    foreach ($_POST as $key => $val) {
        $c->set($key, $val);
    }

    // Persist object
    try {
        $cm->update($c);
    } catch (Exception $e) {
        $error = $e;
    }

    $params['id'] = $c->get('customer_id');
    $params['name'] = trim($c->get('customer_first_name').' '.$c->get('customer_last_name'));

    if ($request->isXmlHttpRequest()) {
        die(json_encode($params));
    } else {
        if (!isset($error)) redirect('/pages/adm_customer', $params);
    }

}

if (isset($_GET['id']) && $c = $cm->get(array('customer_id' => $_GET['id']))) {

    $_PAGE_TITLE = 'Client n&deg;&nbsp;'.$c->get('customer_id');

    if ($c->get('customer_newsletter') == 1) $newsletter_checked = ' checked';
    else $newsletter_checked = NULL;
} elseif (!isset($_GET['id'])) {
    $_PAGE_TITLE = 'Créer un nouveau client';
    $c = new Customer(array());
    $newsletter_checked = NULL;
} else {
    trigger_error('Ce client n\'existe pas !');
}

$message = NULL;
if (isset($error)) $message = '<p class="error">'.$error.'</p>';
if (isset($_GET['created'])) $message = '<p class="success">Le nouveau client a bien été créé.</p>';
if (isset($_GET['updated'])) $message = '<p class="success">Le client a bien été mis à jour.</p>';

$_ECHO .= '
    <h1><i class="fa fa-user"></i> '.$_PAGE_TITLE.'</h1>

    '.$message.'

    <form method="post" class="fieldset">
        <fieldset>

            <input type="hidden" name="action" value="update">

            <p>
                <label for="customer_type">Type :</label>
                <select name="customer_type" id="customer_type">
                    <option'.($c->get('customer_type') == 'Particulier' ? ' selected' : null).'>Particulier</option>
                    <option'.($c->get('customer_type') == 'Professionnel' ? ' selected' : null).'>Professionnel</option>
                    <option'.($c->get('customer_type') == 'Collectivité' ? ' selected' : null).'>Collectivité</option>
                    <option'.($c->get('customer_type') == 'Libraire' ? ' selected' : null).'>Libraire</option>
                </select>
            </p>

            <p>
                <label for="customer_last_name">Nom :</label>
                <input type="text" name="customer_last_name" id="customer_last_name" value="'.$c->get('customer_last_name').'" autofocus required>
            </p>

            <p>
                <label for="customer_first_name">Prénom :</label>
                <input type="text" name="customer_first_name" id="customer_first_name" value="'.$c->get('customer_first_name').'">
            </p>

            <p>
                <label for="customer_email">Adresse e-mail :</label>
                <input type="email" name="customer_email" id="customer_email" value="'.$c->get('customer_email').'" class="verylong">
            </p>

            <p>
                <label for="customer_phone">Téléphone :</label>
                <input type="text" name="customer_phone" id="customer_phone" value="'.$c->get('customer_phone').'">
            </p>

            <p>
                <label for="customer_privatization">Privatisation :</label>
                <input type="date" name="customer_privatization" id="customer_privatization" value="'.$c->get('customer_privatization').'" placeholder="AAAA-MM-JJ">
            </p>

            <p class="center">
                <input type="checkbox" name="customer_newsletter" id="customer_newsletter" value="1"'.$newsletter_checked.' disabled>
                <label for="customer_newsletter" class="after">Abonné à la newsletter</label>
            </p>

            <br>
            <p class="center">
                <button type="submit">Enregistrer les modifications</button>
            </p>

        </fieldset>
    </form>
';
