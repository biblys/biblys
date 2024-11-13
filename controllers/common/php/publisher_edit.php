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


use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\Images\ImagesService;
use Biblys\Service\Slug\SlugService;
use Model\PublisherQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * @throws PropelException
 */
return function (
    Request $request,
    CurrentSite $currentSite,
    CurrentUser $currentUser,
    ImagesService $imagesService,
): Response|RedirectResponse
{
    $_SQL = LegacyCodeHelper::getGlobalDatabaseConnection();

    $pm = new PublisherManager();
    $cm = new CollectionManager();
    $am = new ArticleManager();
    $lm = new LinkManager();

    if ($currentUser->isAdmin()) {
        $publisherId = $request->query->get("id");
    } elseif ($currentUser->hasPublisherRight()) {
        $publisherId = $currentUser->getCurrentRight()->getPublisherId();
    } else {
        throw new AccessDeniedHttpException('Accès réservé aux administrateurs et aux éditeurs.');
    }

    /** @var Publisher $publisherEntity */
    $publisherEntity = $pm->getById($publisherId);
    $p = $publisherEntity;
    if (!$publisherEntity) {
        throw new ResourceNotFoundException("Publisher id not defined");
    }

    $publisher = PublisherQuery::create()->findPk($publisherId);

    if ($request->getMethod() == "POST") {
        $url_p = array();

        $create_redirection = 0;
        $update_children = 0;

        $_FIELDS = array();

        // Logo
        if (!empty($_FILES["publisher_logo_upload"]["tmp_name"])) {
            if ($_FILES["publisher_logo_upload"]["type"] == "image/png") {
                if ($imagesService->imageExistsFor($publisher)) {
                    $imagesService->deleteImageFor($publisher);
                }
                $imagesService->addImageFor($publisher, $_FILES["publisher_logo_upload"]["tmp_name"]);
                $url_p['logo_uploaded'] = 1;
                $_FIELDS[] = 'logo ajouté';
            } else $url_p['warning'] = 'Le logo n\'a pas pu être ajouté : l\'image doit être au format PNG.';
        } elseif (isset($_POST["publisher_logo_delete"])) {
            $imagesService->deleteImageFor($publisher);
            unset($_POST["publisher_logo_delete"]);
            $_FIELDS[] = 'logo supprimé';
            $url_p['logo_deleted'] = 1;
        }

        // Nom en majuscule, alphabétique et URL
        $slugService = new SlugService();
        $_POST["publisher_name"] = mb_strtoupper($_POST["publisher_name"], 'UTF-8');
        $_POST["publisher_name_alphabetic"] = alphabetize($_POST["publisher_name"]);
        $_POST["publisher_url"] = $slugService->slugify($_POST["publisher_name"]);

        // Short description (L'Autre livre)
        if ($currentSite->getId() == 11) {
            $main_desc = $request->request->get("publisher_desc", false);
            $short_desc = $request->request->get("publisher_desc_short", false);

            if (!$short_desc) {
                $short_desc = trim(strip_tags($main_desc));
            }

            // Limit short description to fit in 500 chars
            $short_desc = truncate($short_desc, 500, '...', true);
            $request->request->set("publisher_short_desc", $short_desc);
            $_POST['publisher_desc_short'] = $short_desc;
        }

        if (!isset($_POST['publisher_vpc'])) {
            $_POST['publisher_vpc'] = 0;
        }

        $updating = $_SQL->prepare("SELECT * FROM `publishers` WHERE `publisher_id` = :publisher_id LIMIT 1");
        $updating->bindValue('publisher_id', $publisherEntity->get("id"), PDO::PARAM_INT);
        $updating->execute() or error($updating->errorInfo());
        if ($u = $updating->fetch(PDO::FETCH_ASSOC)) {
            foreach ($_POST as $key => $val) {
                if ($u[$key] != $val)  // Seulement si le champ a été modifié
                {
                    $_FIELDS[] = $key;

                    if (!isset($_UPDATE)) $_UPDATE = '';
                    else $_UPDATE .= ', ';
                    if (empty($val)) $_UPDATE .= '`' . $key . '` = NULL';
                    else {
                        $_UPDATE .= '`' . $key . '` = :' . $key;
                        $params[$key] = $val;
                    }
                    if ($key == 'publisher_url') $create_redirection = 1; // Creation d'une redirection si l'adresse de la page a changé
                    elseif ($key == 'publisher_name') $update_children = 1; // Si le nom a changé, on met à jour collections et articles

                }
            }

        }

        // Mise à jour
        if (isset($_UPDATE)) {

            // Changelog
            $updated_fields = NULL;
            foreach ($_FIELDS as $key) {
                if (isset($updated_fields)) $updated_fields .= ', ';
                $updated_fields .= str_replace('publisher_', '', $key);
            }

            // Requête de mise à jour
            $params['publisher_id'] = $publisherEntity->get("id");
            $update_query = "UPDATE `publishers` SET " . $_UPDATE . ", `publisher_update` = NOW() WHERE `publisher_id` = :publisher_id";
            try {
                $query = $_SQL->prepare($update_query);
                $query->execute($params);
            } catch (PDOException $e) {
                trigger_error($e->getMessage() . ' <br> query: ' . $update_query . ' <br> params: ' . print_r($params, 1));
            }

            // Creation d'une redirection si l'adresse de la page a changé
            if ($create_redirection) {
                $_SQL->exec("REPLACE INTO `redirections`(`redirection_old`,`redirection_new`,`redirection_date`) VALUES('/editeur/" . $b["publisher_url"] . "','/editeur/" . $_POST["publisher_url"] . "',NOW())") or error($_SQL->errorInfo());
            }

            // Mise à jour des articles et collections liés
            if ($update_children) {

                $collections = $cm->getAll(array('publisher_id' => $publisherEntity->get("id")));
                $url_p['collections_updated'] = 0;
                foreach ($collections as $collection) {
                    $collection->set('publisher_name', $_POST['publisher_name']);
                    $collection->set('collection_url', $cm->makeslug($collection));
                    $cm->update($collection);
                    $url_p['collections_updated']++;
                }

                $articles = $am->getAll(array('publisher_id' => $publisherEntity->get('id')));
                $url_p['articles_updated'] = 0;
                foreach ($articles as $article) {
                    $article->set('article_publisher', $_POST['publisher_name']);
                    $am->update($article);
                    $url_p['articles_updated']++;
                }
            }

            $url_p['updated'] = 1;
        }

        if ($currentUser->isAdmin()) {
            $url_p['id'] = $publisherEntity->get("id");
        }
        $urlQueryString = http_build_query($url_p);
        return new RedirectResponse("/pages/publisher_edit?$urlQueryString");
    }

    // Logo
    if ($imagesService->imageExistsFor($publisher)) {
        $publisher_logo_upload = '<input type="file" id="publisher_logo_upload" name="publisher_logo_upload" accept="image/jpeg, image/png, image/webp" class="hidden"> <label class="after button" for="publisher_logo_upload">Remplacer</label> <input type="checkbox" id="publisher_logo_delete" name="publisher_logo_delete" value="1" /> <label for="publisher_logo_delete" class="after">Supprimer</label>';
        $publisher_logo = '<a href="' . $imagesService->getImageUrlFor($publisher) . '" rel="lightbox"><img height="100" src="' . $imagesService->getImageUrlFor($publisher, height: 100) . '" alt="Logo" class="floatR"></a>';
    } else {
        $publisher_logo_upload = '<input type="file" id="publisher_logo_upload" accept="image/jpeg, image/png, image/webp" name="publisher_logo_upload">';
        $publisher_logo = NULL;
    }

    $sel = array("article_pubdate" => NULL, "article_title_alphabetic" => NULL, "article_authors_alphabetic" => NULL, "normal" => NULL, "fixes" => NULL, "suivi" => NULL, "offerts" => NULL);

    // Options de tri
    $sel[$p["publisher_order_by"]] = ' selected';
    $order_options = '
        <option' . $sel["article_pubdate"] . ' value="article_pubdate">Date de parution</option>
        <option' . $sel["article_title_alphabetic"] . ' value="article_title_alphabetic">Titre</option>
        <option' . $sel["article_authors_alphabetic"] . ' value="article_authors_alphabetic">Auteur</option>
    ';

    // VPC
    if ($p['publisher_vpc'] == 1) $publisher_vpc_checked = ' checked'; else $publisher_vpc_checked = NULL;
    $sel[$p["publisher_shipping_mode"]] = ' selected';

    $request->attributes->set("page_title", "Modifier l'éditeur {$publisherEntity->get("name")}");

    $content = '<h1><i class="fa fa-institution"></i> ' . 'Modifier <a href="/editeur/' . $p["publisher_url"] . '">' . $p["publisher_name"] . '</a>' . '</h1>';

    if (isset($_GET["updated"])) $content .= '<p class="success">L\'éditeur a bien été mis à jour</p>';
    if (isset($_GET["collections_updated"])) $content .= '<p class="success">' . $_GET['collections_updated'] . ' collection' . s($_GET['collections_updated']) . ' mise' . s($_GET['collections_updated']) . ' à jour</p>';
    if (isset($_GET["articles_updated"])) $content .= '<p class="success">' . $_GET['articles_updated'] . ' article' . s($_GET['collections_updated']) . ' mis à jour</p>';
    if (isset($_GET["logo_uploaded"])) $content .= '<p class="success">Le logo de l\'éditeur a bien été ajouté.</p>';
    elseif (isset($_GET["logo_deleted"])) $content .= '<p class="success">Le logo de l\'éditeur a bien été supprimé.</p>';
    if (isset($_GET["warning"])) $content .= '<p class="warning">' . $_GET['warning'] . '</p>';


    if (isset($p["publisher_legal_form"])) $selected_legal_form = '<option selected>' . $p["publisher_legal_form"] . '</option>';
    else $selected_legal_form = null;

    $content .= '

    <form method="post" class="fieldset" enctype="multipart/form-data" >
        <fieldset>
            <legend>Informations</legend>
            <p>
                <label for="publisher_name">Nom :</label>
                <input type="text" name="publisher_name" id="publisher_name" value="' . $p["publisher_name"] . '" class="long" required>
            </p>
        </fieldset>
';
    if (
        $currentUser->getCurrentRight()->getPublisherId() == $p['publisher_id'] ||
        $p["publisher_id"] == $currentSite->getSite()->getPublisherId() ||
        $currentUser->isAdmin()
    ) {
        $content .= '
            <fieldset>
                <legend>Coordonnées</legend>
                <label for="publisher_address">Adresse :</label>
                <textarea name="publisher_address" id="publisher_address" class="medium">' . $p["publisher_address"] . '</textarea>
                <br />
                <label for="publisher_postal_code">Code postal :</label>
                <input type="text" name="publisher_postal_code" id="publisher_postal_code" value="' . $p["publisher_postal_code"] . '" class="mini">
                <br />
                <label for="publisher_city">Ville :</label>
                <input type="text" name="publisher_city" id="publisher_city" value="' . $p["publisher_city"] . '">
                <br />
                <label for="publisher_country">Pays :</label>
                <input type="text" name="publisher_country" id="publisher_country" value="' . $p["publisher_country"] . '">
                <br /><br />
                <label for="publisher_phone">Téléphone :</label>
                <input type="tel" name="publisher_phone" id="publisher_phone" value="' . $p["publisher_phone"] . '" class="long">
                <br />
                <label for="publisher_fax">Fax :</label>
                <input type="tel" name="publisher_fax" id="publisher_fax" value="' . $p["publisher_fax"] . '" class="long">
                <br /><br />
                <p>
                    <label for="publisher_website">Site web :</label>
                    <input 
                        type="url" 
                        name="publisher_website" 
                        id="publisher_website" 
                        value="' . $p["publisher_website"] . '" 
                        placeholder="https://..." 
                        title="L\'adresse doit commencer par https://" 
                        class="long"
                    >
                    <br>
                    <label for="publisher_email">Courriel :</label>
                    <input type="email" name="publisher_email" id="publisher_email" value="' . $p["publisher_email"] . '" class="long">
                </p>

                <p>
                    <label for="publisher_facebook">Facebook :</label>
                    <input type="url" name="publisher_facebook" id="publisher_facebook" value="' . $p["publisher_facebook"] . '" placeholder="https://www.facebook.com/..." class="long">
                    <br>
                    <label for="publisher_twitter">Twitter :</label>
                    @ <input type="text" name="publisher_twitter" id="publisher_twitter" value="' . $p["publisher_twitter"] . '" maxlength=15 class="long">
                </p>

            </fieldset>
            <fieldset>
                <legend>Autres informations</legend>
                <label for="publisher_representative">Représentant légal :</label>
                <input type="text" name="publisher_representative" id="publisher_representative" value="' . $p["publisher_representative"] . '" class="long">
                <br />
                <label for="publisher_legal_form">Forme juridique :</label>
                <select name="publisher_legal_form" id="publisher_legal_form">
                    <option></option>
                    ' . $selected_legal_form . '
                    <option>Association</option>
                    <option>Auto-entrepreneur</option>
                    <option>Coopérative</option>
                    <option>Entreprise individuelle</option>
                    <option>EURL</option>
                    <option>GIE</option>
                    <option>SA</option>
                    <option>SARL</option>
                    <option>SAS</option>
                    <option>SNC</option>
                </select>
                <br />
                <label for="publisher_creation_year">Année de création :</label>
                <input type="number" name="publisher_creation_year" id="publisher_creation_year" value="' . $p["publisher_creation_year"] . '" class="mini" min=1850 max=' . date('Y') . '>
                <br /><br />
                <label for="publisher_volumes">Nombre de titres :</label>
                <input type="number" name="publisher_volumes" id="publisher_volumes" value="' . $p["publisher_volumes"] . '" min=0 max=9999 class="mini">
                <br />
                <label for="publisher_average_run">Tirage moyen :</label>
                <input type="number" name="publisher_average_run" id="publisher_average_run" value="' . $p["publisher_average_run"] . '" min=0 max=999999 class="mini">
                <br />
                <label for="publisher_isbn">Racine ISBN :</label>
                <input type="text" name="publisher_isbn" id="publisher_isbn" value="' . $p["publisher_isbn"] . '" class="long">
                <br /><br />
                <label for="publisher_diffuseur">Diffuseur :</label>
                <input type="text" name="publisher_diffuseur" id="publisher_diffuseur" value="' . $p["publisher_diffuseur"] . '" class="long"> <img src="/common/icons/info.svg" alt="" role="presentation" width=16 class="va-middle" title="Si éditeur autodiffusé, laisser vide.">
                <br />
                <label for="publisher_distributeur">Distributeur :</label>
                <input type="text" name="publisher_distributeur" id="publisher_distributeur" value="' . $p["publisher_distributeur"] . '" class="long"> <img src="/common/icons/info.svg" alt="" role="presentation" width=16 class="va-middle" title="Si éditeur autodiffusé, laisser vide.">
                <br /><br />
                <label for="publisher_specialities">Spécialités :</label>
                <textarea name="publisher_specialities" id="publisher_specialities" class="small">' . $publisherEntity->get("specialities") . '</textarea>
            </fieldset>
            
            <fieldset>
                <legend>Options</legend>
                <label for="publisher_order_by">Tri du catalogue par :</label>
                <select name="publisher_order_by" id="publisher_order_by">
                    ' . $order_options . '
                </select>
                <br /><br />
                <label for="publisher_buy_link">Lien d\'achat générique :</label>
                <input type="url" name="publisher_buy_link" id="publisher_buy_link" value="' . $p["publisher_buy_link"] . '" placeholder="Lien générique complété automatiquement" class="long"> &nbsp; Joker : {ISBN}, {EAN}, {TITRE}
                <br />
            </fieldset>
            <fieldset>
                <legend>Logo</legend>
                ' . $publisher_logo . '
                <label for="publisher_logo">Image :</label>
                ' . $publisher_logo_upload . '
                <br /><br />
                <p class="center">Pour un résultat optimal, utilisez une image carrée <br />au format PNG sur fond transparent de 500 pixels de côté.</p>
            </fieldset>
            <fieldset>
                <legend>Présentation</legend>
                <textarea id="publisher_desc" name="publisher_desc" class="wysiwyg">' . $p["publisher_desc"] . '</textarea>
            </fieldset>
            <fieldset>
                <legend>Présentation courte pour le catalogue</legend>
                <p>Cette présentation courte (500 caractères maximum) sera utilisée pour le catalogue papier de L\'Autre Livre imprimé à l\'occasion du salon. Si vous laissez ce champ vide, les 500 premiers caractères de votre présentation seront utilisés à la place.</p>
                <textarea id="publisher_desc_short" name="publisher_desc_short" class="xxl" maxlength=512>' . $p["publisher_desc_short"] . '</textarea>
            </fieldset>
            <fieldset>
                <legend>Vente par correspondance</legend>

                <p class="center">
                    <input type="checkbox" name="publisher_vpc" id="publisher_vpc" value="1"' . $publisher_vpc_checked . '>
                    <label for="publisher_vpc" class="after">Activer la VPC pour ' . $p['publisher_name'] . ' sur lautrelivre.fr</label>
                </p>

                <p>
                    <label for="publisher_paypal">Identifiant Paypal :</label>
                    <input type="text" name="publisher_paypal" id="publisher_paypal" value="' . $p['publisher_paypal'] . '">
                    <img src="/common/icons/info.svg" width=16 alt="Paypal" title="Vous pouvez obtenir votre identifiant de compte marchand en vous rendant sur paypal.com puis dans Préférences > Mes coordonnées > Identifiant de compte marchand.">
                </p>

                <p>
                    <label for="publisher_shipping_mode">Frais de port :</label>
                    <select name="publisher_shipping_mode" id="publisher_shipping_mode">
                        <option value="offerts"' . $sel['offerts'] . '>Offerts par l\'éditeur</option>
                        <option value="normal"' . $sel['normal'] . '>Calculés au tarif Lettre Verte / Livres et brochures</option>
                        <option value="suivi"' . $sel['suivi'] . '>Calculés au tarif Colissimo</option>
                        <option value="fixes"' . $sel['fixes'] . '>Participation au frais (précisez le montant ci-dessous)</option>
                    </select>
                </p>

                <p>
                    <label for="publisher_shipping_fee">Montant de la PAF :</label>
                    <input type="number" name="publisher_shipping_fee" id="publisher_shipping_fee" value="' . $p['publisher_shipping_fee'] . '" class="short"> centimes (par titre)
                    <img src="/common/icons/info.svg" width=16 alt="Participation" title="Si vous avez choisi le mode de calcul des frais de port &laquo;&nbsp;Participation aux frais&nbsp;&raquo;, indiquez ici, en centimes, le montant de la participation par titre. Ex : pour 1,00&nbsp;€ de port par titre, entrez &laquo;&nbsp;100&raquo;&nbsp; : pour cinq titres, le port facturé au client sera de 5,00&nbsp;€.">
                </p>

                <br>
                <p class="center"><a href="/pages/doc_adherents_vpc">Mode d\'emploi de la vente par correspondance</a></p>
            </fieldset>
        ';
    }

    $content .= '
        <fieldset class="center">
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        </fieldset>
        <fieldset>
            <legend>Base de données</legend>

            <p>
                <label for="publisher_id"">Éditeur n&deg; :</label>
                <input type="text" name="publisher_id" id="publisher_id" value="' . $p["publisher_id"] . '" class="short" readonly>
            </p>
            <br>

            <p>
                <label for="publisher_url">URL :</label>
                <input type="text" name="publisher_url" id="publisher_url" value="' . $p["publisher_url"] . '" disabled>
            </p>
            <p>
                <label for="publisher_noosfere_id">Ref. nooSFere :</label>
                <input type="text" name="publisher_noosfere_id" id="publisher_noosfere_id" value="' . $p["publisher_noosfere_id"] . '">
            </p>
            <p>
                <label for="publisher_gln">GLN/GENCOD :</label>
                <input type="text" name="publisher_gln" id="publisher_gln" value="' . $p["publisher_gln"] . '">
            </p>
            <br>

            <p>
                <label for="publisher_name_alphabetic">Nom pour le tri :</label>
                <input type="text" name="publisher_name_alphabetic" id="publisher_name_alphabetic" value="' . $p["publisher_name_alphabetic"] . '" class="long" readonly>
            </p>
            <br>

            <p>
                <label for="publisher_insert">Fiche créée le :</label>
                <input type="email" name="publisher_insert" id="publisher_insert" value="' . $p["publisher_insert"] . '" disabled class="long">
            </p>
            <p>
                <label for="publisher_update">Fiche modifiée le :</label>
                <input type="email" name="publisher_update" id="publisher_update" value="' . $p["publisher_update"] . '" disabled class="long">
            </p>
        </fieldset>
    </form>
';

    // Gestion des fournisseurs
    if ($currentSite->getSite()->getShop()) {
        $sm = new SupplierManager();

        $addSupplier = $request->query->get('add_supplier');
        $delSupplier = $request->query->get('del_supplier');
        if ($addSupplier) {
            $lm->create([
                'publisher_id' => $publisherEntity->get('id'),
                'site_id' => $currentSite->getId(),
                'supplier_id' => $addSupplier
            ]);
            return new RedirectResponse("/pages/publisher_edit?id=" . $publisherEntity->get('id') . "#suppliers");
        } elseif (isset($_GET["del_supplier"])) {
            $link = $lm->get([
                'publisher_id' => $publisherEntity->get('id'),
                'site_id' => $currentSite->getId(),
                'supplier_id' => $delSupplier
            ]);
            $lm->delete($link);
            return new RedirectResponse("/pages/publisher_edit?id=" . $_GET["id"] . "#suppliers");
        }

        // Fournisseurs actuels
        $suppliers = $publisherEntity->getSuppliers();
        $currentSuppliers = array_map(function ($supplier) use ($publisherEntity) {
            return '
            <li>
                <a href="/pages/publisher_edit?id=' . $publisherEntity->get('id') . '&del_supplier=' . $supplier->get('id') . '" 
                    data-confirm="Voulez-vous vraiment SUPPRIMER le lien entre l\'éditeur ' . $publisherEntity->get('name') . ' et le fournisseur ' . $supplier->get('name') . '">
                    <span class="fa fa-trash-o"></span> 
                    ' . $supplier->get('name') . '
                </a>
            </li>
        ';
        }, $suppliers);

        $all_suppliers = $sm->getAll(array(), array('order' => 'supplier_name'));
        $all_suppliers = array_map(function ($supplier) use ($publisherEntity) {

            return '<option value="/pages/publisher_edit?id=' . $publisherEntity->get('id') . '&add_supplier=' . $supplier->get('id') . '">' . $supplier->get('name') . '</option>';

        }, $all_suppliers);

        $content .= '
        <h3 id="suppliers">Fournisseurs pour ' . $p["publisher_name"] . '</h3>
        <ul>
            ' . join($currentSuppliers) . '
        </ul>
        <form>
            <fieldset>
                <label for="fournisseur">Ajouter :</label>
                    <select id="fournisseur" class="goto">
                        <option />
                        ' . implode($all_suppliers) . '
                    </select>
            </fieldset>
        </form>
    ';

    }

    return new Response($content);
};
