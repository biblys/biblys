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


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

$cm = new CustomerManager();

$pageTitle = "Clients";

/** @var $request */
if ($request->getMethod() === "POST") {
    /** @var $request */
    $customerId = (int) $request->query->get("id");
    $customer = $cm->getById($customerId);
    if ($customer) {
        $params["updated"] = 1;
    } else {
        $customer = $cm->create();
        $params["created"] = 1;
    }

    // Update object
    foreach ($request->request->all() as $key => $val) {
        if ($key === "customer_newsletter") {
            continue;
        }
        $customer->set($key, $val);
    }

    // Persist object
    try {
        $cm->update($customer);
    } catch (Exception $e) {
        $error = $e;
    }

    $params["id"] = $customer->get("customer_id");
    $params["name"] = trim($customer->get('customer_first_name').' '.$customer->get('customer_last_name'));

    /** @var $request */
    if ($request->isXmlHttpRequest()) {
        return new JsonResponse($params);
    } elseif (!isset($error)) {
        return new RedirectResponse(
            sprintf("/pages/adm_customer?%s", http_build_query($params))
        );
    }
}

/** @var $request */
$customerId = (int) $request->query->get("id");
$customer = $cm->getById($customerId);

if ($customer) {
    $pageTitle = sprintf("Client n°%s", $customerId);
} else {
    $pageTitle = "Créer un nouveau client";
    $customer = new Customer([]);
}

if ($customer->get("customer_newsletter") == 1) {
    $newsletterChecked = " checked";
} else {
    $newsletterChecked = NULL;
}

$message = NULL;
if (isset($error)) {
    $message = '<p class="error">' . $error . '</p>';
}
if (isset($_GET['created'])) {
    $message = '<p class="success">Le nouveau client a bien été créé.</p>';
}
if (isset($_GET['updated'])) {
    $message = '<p class="success">Le client a bien été mis à jour.</p>';
}

$content = '
    <h1><i class="fa fa-user"></i> '.$pageTitle.'</h1>

    '.$message.'

    <form method="post" class="fieldset">
        <fieldset>

            <p>
                <label for="customer_type">Type :</label>
                <select name="customer_type" id="customer_type">
                    <option'.($customer->get('customer_type') == 'Particulier' ? ' selected' : null).'>Particulier</option>
                    <option'.($customer->get('customer_type') == 'Professionnel' ? ' selected' : null).'>Professionnel</option>
                    <option'.($customer->get('customer_type') == 'Collectivité' ? ' selected' : null).'>Collectivité</option>
                    <option'.($customer->get('customer_type') == 'Libraire' ? ' selected' : null).'>Libraire</option>
                </select>
            </p>

            <p>
                <label for="customer_last_name">Nom :</label>
                <input type="text" name="customer_last_name" id="customer_last_name" value="'.$customer->get('customer_last_name').'" autofocus required>
            </p>

            <p>
                <label for="customer_first_name">Prénom :</label>
                <input type="text" name="customer_first_name" id="customer_first_name" value="'.$customer->get('customer_first_name').'">
            </p>

            <p>
                <label for="customer_email">Adresse e-mail :</label>
                <input type="email" name="customer_email" id="customer_email" value="'.$customer->get('customer_email').'" class="verylong">
            </p>

            <p>
                <label for="customer_phone">Téléphone :</label>
                <input type="text" name="customer_phone" id="customer_phone" value="'.$customer->get('customer_phone').'">
            </p>

            <p>
                <label for="customer_privatization">Privatisation :</label>
                <input type="date" name="customer_privatization" id="customer_privatization" value="'.$customer->get('customer_privatization').'" placeholder="AAAA-MM-JJ">
            </p>

            <p class="center">
                <input type="checkbox" name="customer_newsletter" id="customer_newsletter" value="1" '.$newsletterChecked.' disabled>
                <label for="customer_newsletter" class="after">Abonné à la newsletter</label>
            </p>

            <br>
            <p class="center">
                <button type="submit">Enregistrer les modifications</button>
            </p>

        </fieldset>
    </form>
';

$request->attributes->set("page_title", $pageTitle);
return new Response($content);
