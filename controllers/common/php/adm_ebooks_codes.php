<?php

    function create_coupon_code($num = 1) {
        global $_SQL;
        $codes = array();
        $n = 1;
        while ($n < $num+1) {
            $code = NULL;
            for($i=0; $i<6; $i++) $code .= substr('ABCDEFGHJKMNPQRSTUVWXYZ23456789',rand(0,31),1);
            if (!array_search($code,$codes)) { // Si le code n'est pas deja dans le batch en cours
                $codesReq = $_SQL->prepare("SELECT `coupon_id` FROM `coupons` WHERE `coupon_code` = :code LIMIT 1");
                $codesReq->execute(['code' => $code]);
                if ($codesReq->rowCount() == 0) { // Si le code n'existe pas deja en base
                    $codes[$n] = $code.' '.
                    $n++;
                }
            }
        }
        return $codes;
    }

    $_PAGE_TITLE = 'Codes de t&eacute;l&eacute;chargement';

    $options = null;
    $am = new ArticleManager();
    $articles = $am->getAll(['type_id' => 2], ['order' => 'article_title_alphabetic']);
    foreach ($articles as $article) {
        if ($article->get('id') == $request->query->get('article_id')) {
            $options .= '<option value="?article_id='.$article->get('id').'" selected>'.$article->get('title').'</option>';
            $article_price = $article->get('price');
            $article_title = $article->get('title');
        } else {
            $options .= '<option value="?article_id='.$article->get('id').'">'.$article->get('title').'</option>';
        }
    }

    $req = null;
    $form = null;
    if(!empty($_GET["article_id"])) {
        $form = '
            <button id="dialog_newCodes" class="dialogThis">Nouveaux codes</button>
            <form id="newCodes" method="post" data-title="Nouveaux codes pour '.$article_title.'" class="hidden">
                <h3 class="center">G&eacute;n&eacute;rer <input type="number" name="coupon_num" id="coupon_num" value="1" min=1 max=999> nouveaux codes</h3>

                <label>Type :</label>
                <input type="radio" name="coupon_amount" id="free_coupon" value="0" checked> <label class="after" for="free_coupon">Gratuit</label>
                <input type="radio" name="coupon_amount" id="payed_coupon" value="'.$article_price.'"> <label class="after" for="payed_coupon">Payant ('.price($article_price,'EUR').')</label>
                <br>

                <label for="coupon_note">Note :</label>
                <input type="text" name="coupon_note" id="coupon_note" maxlength=256>
                <br><br>

                <div class="center"><button type="submit">G&eacute;n&eacute;rer les codes</button></div>
            </form>
        ';
        $req = " AND `article_id` = '".$_GET["article_id"]."'";
    }

    // Creer de nouveaux codes
    $codes = [];
    if(!empty($_POST)) {
        $codes = create_coupon_code($_POST["coupon_num"]);
        foreach($codes as $code) {
            $createCodes = $_SQL->prepare("INSERT INTO `coupons`(`site_id`,`coupon_code`,`article_id`,`coupon_creator`,`coupon_amount`,`coupon_note`,`coupon_insert`)
                VALUES(:site_id, :code, :article_id, :user_id, :coupon_amount, :coupon_note, NOW())");
            $createCodes->execute([
                'site_id' => $site->get('id'),
                'code' => $code,
                'article_id' => $request->query->get('article_id'),
                'user_id' => $_V->get('user_id'),
                'coupon_amount' => $request->request->get('coupon_amount'),
                'coupon_note' => $request->request->get('coupon_note')
            ]);
        }
    }

    // Afficher les codes existants
    $table = NULL;
    $coupons = $_SQL->prepare("SELECT `article_title`, `article_url`, `coupon_code`, `coupon_used`, `Email` AS `user_email` FROM `coupons` JOIN `articles` USING(`article_id`) LEFT JOIN `Users` ON `Users`.`id` = `coupons`.`user_id` WHERE `site_id` = :site_id ".$req." ORDER BY `coupon_id` DESC");
    $coupons->execute(['site_id' => $site->get('id')]);
    while ($c = $coupons->fetch(PDO::FETCH_ASSOC)) {
        if(!$c["coupon_used"]) $codes[] = $c["coupon_code"];
        $table .= '
            <tr>
                <td><a href="/'.$c["article_url"].'">'.$c["article_title"].'</a></td>
                <td><a href="http://media.biblys.fr/qrcode/?qr_url=http%3A%2F%2Fcode.biblys.fr%2F'.$c["coupon_code"].'/&qr_format=png&qr_size=32&qr_margin=4&qr_error=H">'.$c["coupon_code"].'</a></td>
                <td>'.$c["coupon_used"].'</td>
                <td>'.$c["user_email"].'</td>
            </tr>
        ';
    }

    $_ECHO = '
        <h1><span class="fa fa-qrcode"></span> '.$_PAGE_TITLE.'</h1>

        <label for="article_id" class="goto">Livres num&eacute;riques :</label>
        <select id="article_id" name="article_id" class="goto">
            <option value="?">Tous</option>
            '.$options.'
        </select>

        '.$form.'

        <br /><br />
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Livre</th>
                    <th>Code</th>
                    <th>Utilis&eacute;</th>
                    <th>Par</th>
                </tr>
            </thead>
            <tbody>
                '.$table.'
            </tbody>
        </table>
    ';
