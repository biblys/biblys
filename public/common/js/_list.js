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

function loadList() {
  const articleListElement = document.getElementById('articleList');

  // Variables de la requête
  const query = articleListElement.dataset.search_terms; // Termes de recherche
  const sort = articleListElement.dataset.sort; // Ordre de tri
  const order = articleListElement.dataset.order; // Tri descendant/ascendant

  // Chargement...
  const listTbody = document.querySelector('.list tbody');
  listTbody.innerHTML = '<tr id="loadingTr"><td colspan=6 class="center loading">Chargement...</td></tr>';

  // Mise à jour dynamique de l'URL
  window.history.pushState(null, 'Title', window.location.pathname + '?q=' + query + '&o=' + sort + '&d=' + order);

  // Requête (toujours la première page : changer de filtre/tri repart de zéro)
  fetch(window.location.pathname + '?_FORMAT=json&q=' + query + '&o=' + sort + '&d=' + order)
    .then(response => response.json())
    .then(ws => {
      if (ws.error) {
        window._alert('Erreur : impossible d\'afficher les résultats.');
        document.getElementById('listCount').textContent = '0';
        listTbody.innerHTML = '<tr><td colspan=6 class="center article_title">Aucun résultat !</td></tr>';
      } else {
        let table = '';
        if (ws.results === 0) {
          document.getElementById('listCount').textContent = '0';
          listTbody.innerHTML = '<tr><td colspan=6 class="center article_title">Aucun résultat !</td></tr>';
        } else {
          document.getElementById('listCount').textContent = ws.results;
          for (let i = 0; i < ws.articles.length; i++) {
            /**
             * @var article {object}
             * @property {string} article_title
             * @property {string} article_url
             * @property {string} article_authors
             * @property {string} article_collection
             * @property {string} collection_url
             * @property {string} article_condition
             * @property {string} article_keywords
             * @property {string} cycle
             * @property {string} number
             * @property {string} availability
             * @property {string} price
             * @property {string} cart
             * @property {string} wish
             * @property {string} alert
             * @property {string} condition
             * @property {string} authors
             *
             */
            const article = ws.articles[i];

            const line =
              '<tr class="item ' + article.condition + '" data-keywords="' + article.article_keywords + '">' +
              '<td><a href="' + article.article_url + '" class="article_title">' + article.article_title + '</a>' + article.cycle + '</td>' +
              '<td title="' + article.article_authors + '">' + article.authors + '</td>' +
              '<td class="right"><a href="/collection/' + article.collection_url + '">' + article.article_collection + '</a>' + article.number + '</td>' +
              '<td class="right nowrap">' + article.availability + '</td>' +
              '<td>' + article.price + '</td>' +
              article.cart + article.wish + article.alert +
              '</tr>';
            table += line;
          }
          listTbody.innerHTML = table;
          window.reloadEvents();
        }
      }
      document.querySelectorAll('#search input').forEach(input => input.classList.remove('loading'));
    })
    .catch(() => {
      const loadingTr = document.getElementById('loadingTr');
      if (loadingTr) loadingTr.remove();
      document.querySelectorAll('#search input').forEach(input => input.classList.remove('loading'));
    });
}

function filterList() {
  const query = document.getElementById('listSearch').value;
  const tableRows = document.querySelectorAll('tr');
  tableRows.forEach(row => {
    const keywords = (row.dataset.keywords || '').toLowerCase();
    if (keywords.indexOf(query.toLowerCase()) === -1) {
      row.style.display = 'none';
    } else {
      row.style.display = '';
    }
  });
}

document.addEventListener('DOMContentLoaded', function() {

  // Modifier le filtre
  document.querySelectorAll('#listFilter a').forEach(a => {
    a.addEventListener('click', function() {
      const articleListElement = document.getElementById('articleList');
      const label = this.innerHTML.replace('<a>', '').replace('</a>', '');
      const filter = this.dataset.filter;
      articleListElement.dataset.filter = filter;
      let search_terms = articleListElement.dataset.search_terms;
      search_terms = search_terms.toString().replace(/ ?etat:\S+/g, '');
      if (filter !== 'all') search_terms += ' etat:' + filter;
      articleListElement.dataset.search_terms = search_terms;
      document.querySelector('#search input').value = search_terms;
      document.querySelector('#listFilter button').innerHTML = label + ' <span class="caret"></span>';

      loadList();
    });
  });

  // Modifier l'ordre de tri
  document.querySelectorAll('#listSort a').forEach(a => {
    a.addEventListener('click', function() {
      const label = this.innerHTML.replace('<a>', '').replace('</a>', '');
      const sort = this.dataset.sort;
      const order = this.dataset.order;
      const articleListElement = document.getElementById('articleList');
      articleListElement.dataset.sort = sort;
      articleListElement.dataset.order = order;
      document.querySelector('#listSort button').innerHTML = label + ' <img src="/common/icons/dropdown.svg" width=8 alt="">';
      loadList();
    });
  });

  // Filtrer la liste
  const listSearch = document.getElementById('listSearch');
  if (listSearch) {
    listSearch.addEventListener('keyup', function() {
      filterList();
    });
  }

});
