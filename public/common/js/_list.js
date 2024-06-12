
	function loadList(start) {

		listLoading = true; // Chargement en cours

		// Variables de la requête
		var query = $('#articleList').data('search_terms'); // Termes de recherche
		var sort = $('#articleList').data('sort'); // Ordre de tri
		var order = $('#articleList').data('order'); // Tri descendant/ascendant
		start = typeof start !== 'undefined' ? start : '0'; // Première ligne

		// Chargement... (remplacer ou ajouter)
		if (start == 0) $(".list tbody").html('<tr id="loadingTr"><td colspan=6 class="center loading">Chargement...</td></tr>');
		else $(".list tbody").append('<tr id="loadingTr"><td colspan=6 class="center loading">Chargement...</td></tr>');

		// Mise à jour dynamique de l'URL
		window.history.pushState(null, "Title", window.location.pathname+"?q="+query+"&o="+sort+"&d="+order);

		// Requête
		$.get(window.location.pathname+"?_FORMAT=json&q="+query+"&o="+sort+"&d="+order+"&s="+start, function(ws) {
			listLoading = false; // Fin du chargement
			if(ws.error) {
				_alert("Erreur : impossible d\'afficher les résultats.");
				$("#list_num").html("0");
				$(".list tbody").html('<tr><td colspan=6 class="center article_title">Aucun résultat !</td></tr>');
			} else {
				var table = null;
				if(ws.results == 0) {
					$("#listCount").html("0");
					$(".list tbody").html('<tr><td colspan=6 class="center article_title">Aucun résultat !</td></tr>');
				} else {
					$("#listCount").html(ws.results);
					for(var i = 0; i < ws.articles.length; i++) {
						var a = ws.articles[i];

						line =
						'<tr class="item '+a.condition+'" data-keywords="'+a.article_keywords+'">' +
							'<td><a href="/'+a.article_url+'" class="article_title">'+a.article_title+'</a>'+a.cycle+'</td>'+
							'<td title="'+a.article_authors+'">'+a.authors+'</td>' +
							'<td class="right"><a href="/collection/'+a.collection_url+'">'+a.article_collection+'</a>'+a.number+'</td>' +
							'<td class="right nowrap">'+a.availability+'</td>' +
							'<td>'+a.price+'</td>' +
							a.cart + a.wish + a.alert +
						'</tr>';
						table += line;
					}
					if(ws.nextPage != 0) $('#nextPage').data('next_page',ws.nextPage).show();
					$('#loadingTr').remove();
					if(start == 0) $('.list tbody').html(table);
					else $('.list tbody').append(table);
					$('#coverLane').html(a.covers);
					reloadEvents();
				}
			}
			$("#search input").removeClass("loading");
		}, 'json');
	}

	function filterList() {
		var query = $('#listSearch').val();
		$('tr').show();
		var videoTitle = "star wars";
		var reQ =  RegExp(query ,"i");
		$('tr').filter(function() {
			if($(this).data('keywords').toLowerCase().indexOf(query.toLowerCase()) == -1) return true;
			else return false;
		}).hide();

	}


	$(document).ready(function() {

		listLoading = false;

		// Modifier le filtre
		$('#listFilter li').click( function(event) {
			var label = $(this).html().replace('<a>', '').replace('</a>', '');
			var filter = $(this).data('filter');
			$('#articleList').data('filter',filter).data('filter',filter);
			var search_terms = $('#articleList').data('search_terms');
			search_terms = search_terms.toString().replace(/[ ]?etat:[\S]+/g, '');
			if (filter !== "all") search_terms += ' etat:'+filter;
			$('#articleList').data('search_terms',search_terms);
			$("#search input").val(search_terms);
			$('#listFilter button').html(label+' <span class="caret"></span>');

			loadList();
		});

		// Modifier l'ordre de tri
		$('#listSort li').click( function(event) {
			var label = $(this).html().replace('<a>', '').replace('</a>', '');
			var sort = $(this).data('sort');
			var order = $(this).data('order');
			$('#articleList').data('sort',sort).data('order',order);
			$('#listSort button').html(label+' <img src="/common/icons/dropdown.svg" width=8>');
			loadList();
		});

		// Option par défaut
		$('#listFilter li[data-selected=true]').each( function() {
			var label = $(this).html();
			$('#listFilterButton').html(label+' <img src="/common/icons/dropdown.svg" width=8>');
			$(this).data('selected','false');
		});
		$('#listSort li[data-selected=true]').each( function() {
			var label = $(this).html();
			$('#listSortButton').html(label+' <img src="/common/icons/dropdown.svg" width=8>');
			$(this).data('selected','false');
		});

		// Afficher plus de résultats
		$("#nextPage").click( function() {
			$(this).hide();
			var nextPage = $(this).data('next_page');
			loadList(nextPage);
		});

		// Filtrer la liste
		$('#listSearch').keyup( function() {
			filterList();
		})

		/* Infinite scroll */

		// L'élément est-il visible par l'utilisateur ?
		function isScrolledIntoView(elem) {
            var docViewTop = $(window).scrollTop();
            var docViewBottom = docViewTop + $(window).height();
            var elemTop = $(elem).offset().top;
            var elemBottom = elemTop + $(elem).height();
            return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
        }

		// On surveille l'évènement scroll
		$(window).scroll( function() {
			// Si le dernier rang est affiché, bouton page suivante présent et pas de chargement en cours
			if ($('#nextPage').is(':visible')) {
				if (isScrolledIntoView($('.item:last')) && listLoading == false) {
					$('#nextPage').hide();
					loadList($('#nextPage').data('next_page'));
				}
			}
		});

	});
