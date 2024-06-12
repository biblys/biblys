'use strict';

$(function() {

	$("table.checkable tr").click( function() {
		$(this).find("input[type=checkbox]").attr('checked',!$(this).hasClass('checked'));
		$(this).toggleClass("checked");
		$("#sel_count").html($("tr.checked").size());
	});
	
	$(".doit").click( function() {
		const users = [];
		var action = $(this).data("action");
		$("input[type=checkbox]:checked").each(function() {
			users.push($(this).data('id'));
		});
		$.ajax({
			url: `/x/adm_newsletter_users`, 
			method: 'POST',
			data: {
				action,
				subscriberIds: JSON.stringify(users),
			}, 
			complete: function(jqXHR) {
				const data = jqXHR.responseJSON;
				if (data.error) {
					window._alert(data.error);
				} else {
					if(action == "unsub") {
						notify(data.count+" adresse(s) d&eacute;sabonn&eacute;e(s)");
						$("tr.checked td.status").removeClass("success invalid").addClass("error").html("D&eacute;sabonn&eacute;");
					} else {
						notify(data.count+" adresse(s) r&eacute;abonn&eacute;e(s)");					
						$("tr.checked td.status").removeClass("error invalid").addClass("success").html("Abonn&eacute;");
					}
					$("tr.checked").removeClass("checked");
					$("input[type=checkbox]:checked").attr("checked",false);
					$("#sel_count").html($("tr.checked").size());
				}
			}
		});
	});
});