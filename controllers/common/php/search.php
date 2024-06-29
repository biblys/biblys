<?php

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

$query = null;
$terms = null;
$sql = [];
$_REQ = null;
$filters = null;

/** @var $request */
$input = $request->query->get('q', false);

$useOldArticleController = $globalSite->getOpt("use_old_article_controller");
if (!$useOldArticleController) {
//    return new RedirectResponse("/articles/search?q=$input");
}

$content = '';

/**
 * @param array|string $date
 * @return false|string
 * @throws Exception
 */
function _formatDate(array|string $date): string|false
{
    try {
        return _date($date, 'j f Y');
    } catch (InvalidDateFormatException $exception) {
        throw new BadRequestHttpException($exception->getMessage(), $exception);
    }
}

if (!$input) {
    $content .= '
        <h1><i class="fa fa-search"></i> Rechercher</h1>
        <form action="/pages/search">
            <div class="form-group">
                <input id="search-field" type="text" name="q" class="form-control" placeholder="Titre, auteur, editeur, ISBN, mot-clé...">
            </div>
            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </div>
        </form>
    ';
} elseif (strlen($input) < 3) {
    $content .= '<p class="error">Vous devez entrer un mot-clé d\'au moins trois caractères.</p>';
} else {
	$queries = explode(" ",$input);

	foreach($queries as $q) {

		$q = str_replace('+',' ',$q);

		if (strstr($q,'titre:')) // Recherche par titre
		{
			$title = str_replace("titre:","",$q);
			$sql[] = "`article_title` LIKE '%".$title."%'";
			$filters .= ' du titre '.$title;
		}
		elseif (strstr($q,'auteur:')) // Recherche par auteur
		{
			$author = str_replace("auteur:","",$q);
			$sql[] = "`article_authors` LIKE '%".$author."%'";
			$filters .= ' de l\'auteur '.$author;
		}
		elseif (strstr($q,'collection:')) // Recherche par collection
		{
			$collection = str_replace("collection:","",$q);
			$sql[] = "`article_collection` LIKE '%".$collection."%'";
			$filters .= ' de la collection '.$collection;

		}
		elseif (strstr($q,'editeur:') || strstr($q,'éditeur:')) // Recherche par editeur
		{
			$editeur = str_replace(array("editeur:","éditeur:"),"",$q);
			$sql[] = "`article_publisher` LIKE '%".$editeur."%'";
			$filters .= ' de l\'éditeur '.$editeur;

		}
		elseif (strstr($q,'date:')) // Ajoute au stock le
		{

			$date = str_replace("date:","",$q);
			$sql[] = "`stock_purchase_date` LIKE '".$date."%' ";
			$filters .= ' ajoutés le '. _formatDate($date);

		}
		elseif (strstr($q,'date<')) // Ajoute au stock avant le
		{

			$date = str_replace("date<","",$q);
			$sql[] = "`stock_purchase_date` < '".$date." 00:00:00' ";
			$filters .= ' ajoutés avant le '. _formatDate($date);
		}
		elseif (strstr($q,'date>')) // Ajoute au stock avant le
		{

			$date = str_replace("date>","",$q);
			$sql[] = "`stock_purchase_date` > '".$date." 00:00:00' ";
			$filters .= ' ajoutés après le '. _formatDate($date);
		}
		elseif (strstr($q,'etat:') || strstr($q,'état:'))
		{

			$q = str_replace(array("etat:","état:"),'',$q);
			$sel_etat = $q;
			if ($q == "neuf")
			{
				$sql[] = "`stock_condition` = 'Neuf'";
				$filters .= ' neufs';
			}
			elseif ($q == "occasion")
			{
				$sql[] = "`stock_condition` != 'Neuf'";
				$filters .= ' d\'occasion';
			}
			elseif ($q == "indisp")
			{
				$sql[] = "`stock_id` IS NULL";
				$filters .= ' pas en stock';
			}

		}
		elseif (strstr($q,'type:'))
		{

			$type = str_replace("type:","",$q);

			switch ($type)
			{
				case 'papier': $type = 'Livres papiers'; $type_id = 1; break;
				case 'numérique': $type = 'Livres numériques'; $type_id = 2; break;
				case 'CD': $type = 'CDs'; $type_id = 3; break;
				case 'DVD': $type = 'DVDs'; $type_id = 4; break;
				case 'lot': $type = 'Lots'; $type_id = 8; break;
				case 'BD': $type = 'BDs'; $type_id = 9; break;
				default: $type = 'Livres'; $type_id = 0;
			}

			$sql[] = "`type_id` = ".$type_id;
			$filters .= ' de type '.$type;

		}
		elseif (!empty($q))
		{
			if(isset($terms)) $terms .= ' ';
			$terms .= $q;
			$sql[] .= "`article_keywords` LIKE '%".addslashes($q)."%'";
		}
	}

	foreach ($sql as $s)
	{
		if(isset($_REQ)) $_REQ .= ' AND ';
		$_REQ .= $s;
	}

	if(!empty($terms)) {
		\Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle('Recherche de &laquo;&nbsp;'.trim(htmlspecialchars($terms)).'&nbsp;&raquo;'.$filters);
	} else {
		if(!isset($type)) $type = 'Livres';
		\Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle($type.' '.$filters);
	}

	$content .= '
		<h2>'.\Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle().'</h2>
	';

	$_SEARCH_TERMS = $_GET['q'];

	$path = get_controller_path('_list');
	$listContent = require_once $path;
	$content .= $listContent;
}

return new Response($content);
