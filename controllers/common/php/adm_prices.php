<?php

    $pricegridId = $request->query->get('pricegrid_id');

    \Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle('Grille de prix n&deg; '.$pricegridId);

    $_ECHO .= '<h2>Grille de prix <a href="/pages/adm_prices?pricegrid_id='.$pricegridId.'">n&deg;'.$pricegridId.'</a></h2>';

    // Collections concernees
    $cm = new CollectionManager();
    $collections = $cm->getAll(['pricegrid_id' => $pricegridId]);
    $colls = null;
    $update_cols = null;
    foreach ($collections as $collection) {
        $c = $collection;
        $colls .= '<li><a href="/collection/'.$c["collection_url"].'">'.$c["collection_name"].'</a></li>';
        $update_cols .= " OR `collection_id` = `".$c["collection_id"]."`";
    }

    if(isset($colls)) $_ECHO .= '<h3>Collections concernées</h3><ul>'.$colls.'</ul><br />';

    $priceId = $request->request->get('id');
    if ($priceId) {
        $priceUpdate = $_SQL->prepare("UPDATE `prices` SET `price_cat` = :price_cat, `price_amount` = :price_amount WHERE `price_id` = :price_id LIMIT 1");
        $priceUpdate->execute([
            'price_cat' => $request->request->get('price_cat'),
            'price_amount' => $request->request->get('price_amount'),
            'price_id' => $priceId
        ]);

        $_ECHO .= '<p class="success">La catégorie a été mise &agrave; jour.</p>';

        $livres = $_SQL->prepare("SELECT `article_id`, `article_title`, `article_number`, `article_url`
                                FROM `articles`
                                JOIN `collections` USING(`collection_id`)
                                WHERE `article_category` = :price_cat AND `collections`.`pricegrid_id` = :pricegrid_id ORDER BY `article_number`");
        $livres->execute([
            'price_cat' => $request->request->get('price_cat'),
            'pricegrid_id' => $request->query->get('pricegrid_id')
        ]);
        $num_livres = $livres->rowCount();

        if(!empty($num_livres)) {
            $_ECHO .= '<h3>'.$num_livres.' article'.s($num_livres).' modifié'.s($num_livres).' ('.$_POST["price_cat"].' &#224; '.price($_POST["price_amount"],'EUR').')</h3>';
            while ($l = $livres->fetch(PDO::FETCH_ASSOC)) {
                $articleUpdate = $_SQL->prepare("UPDATE `articles` SET `article_price` = :price_amount, `article_updated` = NOW() WHERE `article_id` = :article_id LIMIT 1");
                $articleUpdate->execute([
                    'price_amount' => $request->request->get('price_amount'),
                    'article_id' => $l['article_id']
                ]);

                $num_stock = 0;
                $stockUpdate = $_SQL->prepare("UPDATE `stock` SET `stock_selling_price` = :price_amount WHERE `article_id` = :article_id AND `stock_condition` = 'Neuf' AND `stock_selling_date` IS NULL AND `stock_return_date` IS NULL AND `stock_lost_date` IS NULL");
                $stockUpdate->execute([
                    'price_amount' => $request->request->get('price_amount'),
                    'article_id' => $l['article_id']
                ]);

                $_ECHO .= '<p>'.$l["article_number"].'. <a href="/'.$l["article_url"].'">'.$l["article_title"].'</a> (+ '.$num_stock.' exemplaire'.s($num_stock).')</p>';
            }
            $_ECHO .= '<br />';
        }
    }

    $priceId = $request->query->get('id');
    if ($priceId) {
        $prices = $_SQL->prepare("SELECT `price_id`, `price_cat`, `price_amount` FROM `prices` WHERE `price_id` = :price_id LIMIT 1");
        $prices->execute(['price_id' => $priceId]);
        if ($p = $prices->fetch(PDO::FETCH_ASSOC)) {
            $_ECHO .= '
                <form method="post">
                    <fieldset>
                        <input type="hidden" name="id" value="'.$priceId.'" />
                        <label for="price_cat">Catégorie :</label>
                        <input type="text" name="price_cat" id="price_cat" value="'.$p['price_cat'].'" />
                        <br />
                        <label for="price_amount">Prix :</label>
                        <input type="text" name="price_amount" id="price_amount" value="'.$p['price_amount'].'" autofocus /> centimes
                        <br /><br />
                        <center><input type="submit" value="Enregistrer" class="center" /></center>
                    </fieldset>
                </form>
                <br /><br />
            ';
        }
    }

    $newpriceCat = $request->query->get('newprice_cat');
    if ($newpriceCat) {
        $priceInsert = $_SQL->prepare("INSERT INTO `prices`(`pricegrid_id`,`price_cat`,`price_amount`) VALUES(:pricegrid_id, :newprice_cat, :newprice_amount)");
        $priceInsert->execute([
            'pricegrid_id' => $pricegridId,
            'newprice_cat' => $newpriceCat,
            'newprice_amount' => $request->query->get('newprice_amount')
        ]);
    }

    $_ECHO .= '<table class="admin-table sortable">
            <tr>
                <td>Catégorie</td>
                <td class="right">Prix</td>
                <td></td>
            </tr>';


    $prices = $_SQL->prepare("SELECT `price_cat`,`price_amount`,`price_id`
                            FROM `collections`
                            JOIN `prices` USING(`pricegrid_id`)
                            WHERE `collections`.`pricegrid_id` = :pricegrid_id
                            GROUP BY `price_id`
                            ORDER BY `price_amount`
                        ");
    $prices->execute(['pricegrid_id' => $pricegridId]);

    while ($p = $prices->fetch(PDO::FETCH_ASSOC)) {
        $_ECHO .= '
            <tr>
                <td>'.$p["price_cat"].'</td>
                <td class="right">'.price($p["price_amount"],'EUR').'</td>
                <td><a href="/pages/adm_prices?pricegrid_id='.$pricegridId.'&id='.$p["price_id"].'">modifier</td>
            </tr>
        ';
    }

    $_ECHO .= '</table>';

    $_ECHO .= '
        <h3>Nouvelle cat&#233;gorie</h3>
        <form>
            <fieldset>
                <input type="hidden" name="pricegrid_id" value="'.$pricegridId.'" />

                <label for="newprice_cat">Cat&#233;gorie :</label>
                <input type="text" name="newprice_cat" id="newprice_cat" />
                <br />

                <label for="newprice_amount">Prix :</label>
                <input type="text" name="newprice_amount" id="newprice_amount" /> centimes
                <br />
                <br />
                <div class="center"><input type="submit" value="Cr&#233;er"></div>

            </fiedset>
        </form>
    ';

