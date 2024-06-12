	// EVENTS AU CHARGEMENT DE LA PAGE
	(function($){
		
		// Ajouter au lot
		$("#publisher_country").autocomplete({
			source: "/x/countries",
			minLength: 3,
			delay: 250
		});
		
	})(jQuery);