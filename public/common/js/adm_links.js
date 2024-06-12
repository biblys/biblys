
	function addLink(type,id) {
		$.post("/x/adm_links", {
			element_type: ""+$("#element").data('type')+"",
			element_id: ""+$("#element").data('id')+"",
			linkto_type: ""+type+"",
			linkto_id: ""+id+""
		}, function(data) {
			res = jQuery.parseJSON(data);
			if(res.error) _alert(res.error);
			else {
				$("#linked_"+type+"s").append(res.link);
                reloadEvents();
				$(".new").slideDown().removeClass("new");
				reloadLinksEvents();
			}
		});
	}
	
	function reloadLinksEvents(scope) {
		$(".deleteLink", scope).click( function() {
			var link_id = $(this).data("link_id");
			$("#link_"+link_id).fadeTo('fast',0.5);
			$.get("/x/adm_links", {
				del: 1,
				link_id: ""+link_id+""
			}, function(data) {
				res = jQuery.parseJSON(data);
				if(res.error) {
					$("#link_"+link_id).fadeTo('fast',1);
					_alert(res.error);
				} else $("#link_"+link_id).slideUp();
			});
		});
	}

    $(document).ready(function() {
		
		reloadLinksEvents();
		
		// Recherche un article
		$("#article").autocomplete({
			source: "/x/adm_links?type=articles",
			minLength: 3,
			delay: 250,
			select: function(event, ui) { addLink("article",ui.item.id); }
		});
		
		// Rechercher un contributeur
		$("#people").autocomplete({
			source: "/x/adm_links?type=people",
			minLength: 3,
			delay: 250,
			select: function(event, ui) { addLink("people",ui.item.id); }
		});
		
		// Rechercher un billet
		$("#post").autocomplete({
			source: "/x/adm_links?type=post",
			minLength: 3,
			delay: 250,
			select: function(event, ui) { addLink("post",ui.item.id); }
		});
		
		// Autocomplete a publisher
		$("#publisher").autocomplete({
			source: "/x/adm_links?type=publisher",
			minLength: 3,
			delay: 250,
			select: function(event, ui) { addLink("publisher",ui.item.id); }
		});
		
		// Rechercher un evenement
		$("#event").autocomplete({
			source: "/x/adm_links?type=event",
			minLength: 3,
			delay: 250,
			select: function(event, ui) { addLink("event",ui.item.id); }
		});
		
	});
