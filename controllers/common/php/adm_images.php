<?php

	\Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle($_GET["dir"].' Gestion des images');
	$_ECHO .= '
		<h2>Gestion des images</h2>
		
		<div id="fileList">
			<h3>Fichiers</h3>
		</div>
	';
	
	// Creer les dossiers
	//for($i = 0; $i < 100; $i++) {
	//	$dir = '/homez.378/nokto/biblys/media/book/'.file_dir($i).'/';
	//	$_ECHO .= $dir.'<br />';
	//	mkdir($dir);
	//}
	
	// Supprimer les vignettes	
	if(isset($_GET["dir"])) {
		$dir = '/homez.378/nokto/tys/media/livre/'.file_dir($_GET["dir"]).'/';
		$_ECHO .= '<h4>'.$dir.'</h4>';
		$rdir = opendir($dir);
		while($element = readdir($rdir)) {
			if($element != '.' && $element != '..') {
				
				// Supprimer les vignettes
				//if(strstr($element,'-h') or strstr($element,'-w')) {
				//	$_ECHO .= 'xx> '.$dir.$element.'<br />';
				//	unlink($dir.$element);
				//}
				
				$from = $dir.$element;
				$to = '/homez.378/nokto/biblys/media/book/'.file_dir($_GET["dir"]).'/'.$element;
				copy($from,$to);
				$_ECHO .= $from.'<br />===> '.$to.'<br /><br />';
				
				
				//$file = explode('.',$element);
				//$from = '/homez.378/nokto/tys/media/ebook/'.$file[0].'.'.$file[1];
				//$to = '/homez.378/nokto/biblys/dl/'.$file[1].'/'.file_dir($file[0]).'/'.$file[0].'.'.$file[1];
				//echo $from.' => '.$to.'<br />';
				//copy($from,$to);
				//break;
			}
		}
		$_GET["dir"] += 1;
		$_ECHO .= '
			<script>
				setTimeout(function() {
					window.location = "/pages/adm_images?dir='.$_GET["dir"].'";
				}, 1000);
			</script>
		';
	}

?>