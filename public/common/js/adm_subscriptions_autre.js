$(function() {

	$(".subscriptions").on("click", "tr", function() {
		var row = $(this),
			checkbox = row.find("input[type=checkbox]"),
			checked = $(this).hasClass('checked'),
			new_value = !checked;
		
		checkbox.attr('checked', new_value);
		row.toggleClass("checked");
		$("#sel_count").html($("tr.checked").size());
	});
	
	$(".doit").click( function() {
		ids = '';
		var action = $(this).data("action");
		var year = $(this).data("year");
		$("input[type=checkbox]:checked").each( function() {
			ids += $(this).data('id')+' ';
		});
		$.post('/pages/adm_subscriptions', {
			action: action,
            ids: ids,
			year: year,
		}, function(data) {	
			var res = jQuery.parseJSON(data);
			if(res.error) {
                _alert(res.error);
			} else {
				if (action == "resub") {
					$("tr.checked td.status").removeClass("error invalid").addClass("success").html(res.year);
				} else if (action == "delete") {
					$("tr.checked").slideUp();
				}
				if(res.message) notify(res.message);
				$("tr.checked").removeClass("checked");
				$("input[type=checkbox]:checked").attr("checked",false);
				$("#sel_count").html(0);
			}
		});
	
	});

});
