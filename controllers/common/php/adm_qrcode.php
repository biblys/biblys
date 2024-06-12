<?php

	include(BIBLYS_PATH.'/inc/phpqrcode/qrlib.php');
	
	$_PAGE_TITLE = 'G&eacute;n&eacute;rateur de QRCodes';

	$_ECHO .= '
		<h2>'.$_PAGE_TITLE.'</h2>
		
		<form method="get" action="http://media.biblys.fr/qrcode/">
			<fieldset>
			
				<label for="qr_url">Adresse web :</label>
				<input type="url" id="qr_url" name="qr_url" placeholder="http://" class="long">
				<br />
				
				<label for="qr_format">Format :</label>
				<select name="qr_format" id="qr_format">
					<option value="png">PNG</option>
					<option value="svg" disabled>SVG</option>
					<option value="eps" disabled>EPS</option>
				</select>
				<br />
				
				<label for="qr_size">Taille des modules :</label>
				<input type="num" id="qr_size" name="qr_size" min=1 max=32 maxlenght=3 value=32 class="nano">
				<br />
				
				<label for="qr_margin">Marge blanche :</label>
				<input type="num" id="qr_margin" name="qr_margin" min=1 max=9 maxlenght=1 value=4 class="nano">
				<br />
				
				<label for="qr_error">Correction d\'erreur :</label>
				<select name="qr_error" id="qr_error">
					<option>L</option>
					<option>M</option>
					<option>Q</option>
					<option selected>H</option>
				</select>
				<br /><br />
				
				<div class="center">
					<button type="submit">G&eacute;n&eacute;rer le QRCode</button>
				</div>
				
			</fielset>
		</form>
	';	
	
?>