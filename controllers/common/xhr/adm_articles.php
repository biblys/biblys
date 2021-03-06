<?php

use Symfony\Component\HttpFoundation\JsonResponse;
use Biblys\Isbn\Isbn;

$am = new ArticleManager();

$query = $request->query->get('query');
$term = $request->query->get('term');

$q = $query;
if ($term) {
    $q = $term;
}

if (isset($q)) {
    $params = [];
    if (Isbn::isParsable($q)) {
        $req = "(`article_ean` = :ean OR `article_ean_others` = :ean)";
        $params['ean'] = Isbn::convertToEan13($q);
    } else {
        $qex = explode(" ", addslashes($q));
        $req = null;
        $i = 0;
        foreach ($qex as $qexa) {
            if (isset($req)) {
                $req .= " AND ";
            }
            $req .= "(`article_keywords` LIKE :q".$i.")";
            $params['q'.$i] = "%".$qexa."%";
            $i++;
        }
    }

    $j = 0;
    $articles = $am->search($q, ['limit' => 10]);
    foreach ($articles as $article) {
        $a = $article;
        if (media_exists('book', $a["article_id"])) {
            $a["article_cover"] = '<img src="'.media_url('book', $a["article_id"], "h60").'" />';
        } else {
            $a["article_cover"] = null;
        }

        $json[$j]["label"] = $a["article_title"].' ('.$a["article_collection"].numero($a["article_number"], ' - ').')';
        $json[$j]["value"] = $a["article_title"];
        $json[$j]["url"] = "/pages/adm_stock?add=".$a["article_id"]."#add";
        $json[$j]["article_id"] = $a["article_id"];
        $j++;
    }
    $json[$j]["label"] = '=> Nouvel article pour '.$_GET["term"];
    $json[$j]["value"] = '';
    $json[$j]["url"] = "/pages/adm_article?import=".$_GET["term"];
}

$response = new JsonResponse($json);
$response->send();
