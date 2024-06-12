<?php

	$_PAGE_TITLE = "Gestion des fichiers";

	$_JS_CALLS[] = "/common/js/sorttable.js";

	$files = array();
	$forbidden = array(".","..","php");

	$absolute_path = SITE_PATH;
	if (isset($_GET['path'])) $absolute_path .= $_GET["path"];

	// Supprimer un fichier
	if(isset($_GET["delete"])) {
		$file_delete = $absolute_path.'/'.$_GET["delete"];
		if(file_exists($file_delete)) {
			unlink($file_delete);
			redirect('/pages/adm_files?path='.$_GET["path"].'&deleted='.$_GET["delete"].'');
		}
	} elseif (isset($_GET["deleted"])) $message = '<p class="success">Le fichier <em>'.$_GET["deleted"].'</em> a été supprimé.</p>';

	// Chemin d'acces
	$access_path = '<a href="/pages/adm_files">'.$_SITE["site_name"].'</a>';

	if (isset($_GET['path']))
	{
		$paths = explode('/',$_GET["path"]);
		$ppath = null;
		foreach ($paths as $p)
		{
			if (!empty($p))
			{
				$ppath = $ppath.'/'.$p;
				$access_path .=  ' &raquo; <a href="?path='.$ppath.'">'.$p.'</a>';
			}
		}
	}

	foreach (new DirectoryIterator($absolute_path) as $file)
	{
		if (substr($file->getFilename(),0,1) != '.' && !in_array($file->getFilename(),$forbidden))
		{

			$f["name"] = $file->getFilename();
			if($file->isDir())
			{
				$f["icon"] = 'folder';
				$f["link"] = '?path='.(isset($_GET["path"]) ? $_GET["path"] : null).'/'.$f["name"];
				$f["size"] = $file->getSize().' élément'.s($file->getSize());
				$f["date"] = date("d/m/Y H:i",$file->getMTime());
				$f['buttons'] = null;
			}
			else
			{
				$f["icon"] = "file";
				$f["link"] = '?path='.$_GET["path"].'&file='.$f["name"];
				$f["size"] = file_size($file->getSize());
				$f["date"] = date("d/m/Y H:i",$file->getMTime());
				$f["buttons"] = '<a href="/'.$_SITE["site_name"].$_GET["path"].'/'.$f["name"].'" title="Télécharger" download><img src="/common/icons/download.svg" alt="Télécharger" height="16"></a> ';
				$f["buttons"] .= '<img src="/common/icons/link.svg" width=16 alt="Lien" title="Associer à un article en tant que fichier téléchargeable" class="dlfile_associate pointer" data-file="'.$_GET["path"].'/'.$f['name'].'" data-name="'.$f['name'].'"> ';
				$f["buttons"] .= '<a href="?path='.$_GET["path"].'&delete='.$f["name"].'" title="Supprimer" data-confirm="Voulez-vous vraiment SUPPRIMER le fichier '.$f["name"].' ?"><img src="/common/icons/delete.svg" width=16 title="Supprimer" alt="Supprimer"></a> ';
			}

		   $files[] = $f;
		}
	}

	$tbody = null;
	foreach ($files as $f)
	{
		$tbody .= '
			<tr>
				<td><img src="/common/icons/'.$f["icon"].'.svg" alt="'.$f["icon"].'" height=16></td>
				<td width="99%"><a href="'.$f["link"].'">'.$f["name"].'</a></td>
				<td class="right nowrap">'.$f["date"].'</td>
				<td class="right nowrap">'.$f["size"].'</td>
				<td class="right nowrap">'.$f["buttons"].'</td>
			</tr>';
	}

	$_ECHO .= '
		<a class="floatR" href="/pages/adm_media">Ancienne version</a>
		<h2><span class="fa fa-file"></span> '.$_PAGE_TITLE.'</h2>
		'.(isset($message) ? $message : null).'
		<h3>'.$access_path.'</h3>
		<table class="admin-table sortable">
			<thead>
				<tr>
					<th></th>
					<th>Nom</th>
					<th>Modifié le</th>
					<th>Taille</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				'.$tbody.'
			</tbody>
		</table>
	';

?>
