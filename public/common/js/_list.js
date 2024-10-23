/**
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

/* global jQuery */

let listLoading = false;

function loadList(start = 0) {
  listLoading = true; // Chargement en cours

  const articleListElement = jQuery('#articleList');

  // Variables de la requête
  const query = articleListElement.data('search_terms'); // Termes de recherche
  const sort = articleListElement.data('sort'); // Ordre de tri
  const order = articleListElement.data('order'); // Tri descendant/ascendant
  start = typeof start !== 'undefined' ? start : '0'; // Première ligne

  // Chargement... (remplacer ou ajouter)
  if (start === 0) jQuery('.list tbody').html('<tr id="loadingTr"><td colspan=6 class="center loading">Chargement...</td></tr>');
  else jQuery('.list tbody').append('<tr id="loadingTr"><td colspan=6 class="center loading">Chargement...</td></tr>');

  // Mise à jour dynamique de l'URL
  window.history.pushState(null, 'Title', window.location.pathname+'?q='+query+'&o='+sort+'&d='+order);

  // Requête
  jQuery.get(window.location.pathname+'?_FORMAT=json&q='+query+'&o='+sort+'&d='+order+'&s='+start, function(ws) {
    listLoading = false; // Fin du chargement
    if(ws.error) {
      window._alert('Erreur : impossible d\'afficher les résultats.');
      jQuery('#list_num').html('0');
      jQuery('.list tbody').html('<tr><td colspan=6 class="center article_title">Aucun résultat !</td></tr>');
    } else {
      let table = null;
      if(ws.results === 0) {
        jQuery('#listCount').html('0');
        jQuery('.list tbody').html('<tr><td colspan=6 class="center article_title">Aucun résultat !</td></tr>');
      } else {
        jQuery('#listCount').html(ws.results);
        let article;
        for(let i = 0; i < ws.articles.length; i++) {
          article = ws.articles[i];

          const line =
						'<tr class="item '+article.condition+'" data-keywords="'+article.article_keywords+'">' +
							'<td><a href="'+article.article_url+'" class="article_title">'+article.article_title+'</a>'+article.cycle+'</td>'+
							'<td title="'+article.article_authors+'">'+article.authors+'</td>' +
							'<td class="right"><a href="/collection/'+article.collection_url+'">'+article.article_collection+'</a>'+article.number+'</td>' +
							'<td class="right nowrap">'+article.availability+'</td>' +
							'<td>'+article.price+'</td>' +
							article.cart + article.wish + article.alert +
						'</tr>';
          table += line;
        }
        if(ws.nextPage !== 0) jQuery('#nextPage').data('next_page',ws.nextPage).show();
        jQuery('#loadingTr').remove();
        if(start === 0) jQuery('.list tbody').html(table);
        else jQuery('.list tbody').append(table);
        jQuery('#coverLane').html(article.covers);
        window.reloadEvents();
      }
    }
    jQuery('#search input').removeClass('loading');
  }, 'json');
}

function filterList() {
  const query = jQuery('#listSearch').val();
  const tableRows = jQuery('tr');
  tableRows.show();
  tableRows.filter(function() {
    return jQuery(this).data('keywords').toLowerCase().indexOf(query.toLowerCase()) === -1;
  }).hide();

}


jQuery(document).ready(function() {

  listLoading = false;

  // Modifier le filtre
  jQuery('#listFilter li').click( function() {
    const articleListElement = jQuery('#articleList');
    const label = jQuery(this).html().replace('<a>', '').replace('</a>', '');
    const filter = jQuery(this).data('filter');
    articleListElement.data('filter',filter).data('filter',filter);
    let search_terms = articleListElement.data('search_terms');
    search_terms = search_terms.toString().replace(/ ?etat:\S+/g, '');
    if (filter !== 'all') search_terms += ' etat:'+filter;
    articleListElement.data('search_terms',search_terms);
    jQuery('#search input').val(search_terms);
    jQuery('#listFilter button').html(label+' <span class="caret"></span>');

    loadList();
  });

  // Modifier l'ordre de tri
  jQuery('#listSort li').click( function() {
    const label = jQuery(this).html().replace('<a>', '').replace('</a>', '');
    const sort = jQuery(this).data('sort');
    const order = jQuery(this).data('order');
    jQuery('#articleList').data('sort',sort).data('order',order);
    jQuery('#listSort button').html(label+' <img src="/common/icons/dropdown.svg" width=8 alt="">');
    loadList();
  });

  // Option par défaut
  jQuery('#listFilter li[data-selected=true]').each( function() {
    const label = jQuery(this).html();
    jQuery('#listFilterButton').html(label+' <img src="/common/icons/dropdown.svg" width=8 alt="">');
    jQuery(this).data('selected','false');
  });
  jQuery('#listSort li[data-selected=true]').each( function() {
    const label = jQuery(this).html();
    jQuery('#listSortButton').html(label+' <img src="/common/icons/dropdown.svg" width=8 alt="">');
    jQuery(this).data('selected','false');
  });

  // Afficher plus de résultats
  jQuery('#nextPage').click( function() {
    jQuery(this).hide();
    const nextPage = jQuery(this).data('next_page');
    loadList(nextPage);
  });

  // Filtrer la liste
  jQuery('#listSearch').keyup( function() {
    filterList();
  });

  /* Infinite scroll */

  // L'élément est-il visible par l'utilisateur ?
  function isScrolledIntoView(elem) {
    const docViewTop = jQuery(window).scrollTop();
    const docViewBottom = docViewTop + jQuery(window).height();
    const elemTop = jQuery(elem).offset().top;
    const elemBottom = elemTop + jQuery(elem).height();
    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
  }

  // On surveille l'évènement scroll
  jQuery(window).scroll( function() {
    // Si le dernier rang est affiché, bouton page suivante présent et pas de chargement en cours
    const nextPageElement = jQuery('#nextPage');
    if (nextPageElement.is(':visible')) {
      if (isScrolledIntoView(jQuery('.item:last')) && listLoading === false) {
        nextPageElement.hide();
        loadList(nextPageElement.data('next_page'));
      }
    }
  });

});
