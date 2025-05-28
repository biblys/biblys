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

let listLoading = false;

function loadList(start = 0) {
  listLoading = true; // Chargement en cours

  const articleListElement = document.getElementById('articleList');

  // Variables de la requête
  const query = articleListElement.dataset.search_terms; // Termes de recherche
  const sort = articleListElement.dataset.sort; // Ordre de tri
  const order = articleListElement.dataset.order; // Tri descendant/ascendant
  start = typeof start !== 'undefined' ? start : '0'; // Première ligne

  // Chargement... (remplacer ou ajouter)
  const listTbody = document.querySelector('.list tbody');
  if (start === 0) listTbody.innerHTML = '<tr id="loadingTr"><td colspan=6 class="center loading">Chargement...</td></tr>';
  else listTbody.insertAdjacentHTML('beforeend', '<tr id="loadingTr"><td colspan=6 class="center loading">Chargement...</td></tr>');

  // Mise à jour dynamique de l'URL
  window.history.pushState(null, 'Title', window.location.pathname + '?q=' + query + '&o=' + sort + '&d=' + order);

  // Requête
  fetch(window.location.pathname + '?_FORMAT=json&q=' + query + '&o=' + sort + '&d=' + order + '&s=' + start)
    .then(response => response.json())
    .then(ws => {
      listLoading = false; // Fin du chargement
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
          let article;
          for (let i = 0; i < ws.articles.length; i++) {
            /**
             * @var article {object}
             * @property {string} article_title
             * @property {string} article_url
             * @property {string} article_authors
             * @property {string} article_collection
             * @property {string} collection_url
             * @property {string} article_covers
             * @property {string} article_condition
             * @property {string} article_keywords
             * @property {string} cycle
             * @property {string} number
             * @property {string} availability
             * @property {string} price
             * @property {string} cart
             * @property {string} wish
             * @property {string} alert
             * @property {string} covers
             * @property {string} condition
             * @property {string} article_keywords
             * @property {string} authors
             *
             */
            article = ws.articles[i];

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
          const nextPageElem = document.getElementById('nextPage');
          if (ws.nextPage !== 0) {
            nextPageElem.dataset.next_page = ws.nextPage;
            nextPageElem.style.display = '';
          }
          const loadingTr = document.getElementById('loadingTr');
          if (loadingTr) loadingTr.remove();
          if (start === 0) listTbody.innerHTML = table;
          else listTbody.insertAdjacentHTML('beforeend', table);
          document.getElementById('coverLane').innerHTML = article.covers;
          window.reloadEvents();
        }
      }
      document.querySelectorAll('#search input').forEach(input => input.classList.remove('loading'));
    })
    .catch(() => {
      listLoading = false; // Fin du chargement
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

  listLoading = false;

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

  // Afficher plus de résultats
  const nextPageElem = document.getElementById('nextPage');
  if (nextPageElem) {
    nextPageElem.addEventListener('click', function() {
      this.style.display = 'none';
      const nextPage = Number(this.dataset.next_page);
      loadList(nextPage);
    });
  }

  // Filtrer la liste
  const listSearch = document.getElementById('listSearch');
  if (listSearch) {
    listSearch.addEventListener('keyup', function() {
      filterList();
    });
  }

  /* Infinite scroll */

  // L'élément est-il visible par l'utilisateur ?
  function isScrolledIntoView(elem) {
    const rect = elem.getBoundingClientRect();
    const windowHeight = (window.innerHeight || document.documentElement.clientHeight);
    const windowTop = window.scrollY;
    const elemTop = rect.top + windowTop;
    const elemBottom = elemTop + rect.height;
    const docViewTop = windowTop;
    const docViewBottom = docViewTop + windowHeight;
    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
  }

  // On surveille l'évènement scroll
  window.addEventListener('scroll', function() {
    const nextPageElement = document.getElementById('nextPage');
    if (nextPageElement && nextPageElement.style.display !== 'none') {
      const lastItem = document.querySelector('.item:last-child');
      if (lastItem && isScrolledIntoView(lastItem) && listLoading === false) {
        nextPageElement.style.display = 'none';
        const nextPage = Number(nextPageElement.dataset.next_page);
        loadList(nextPage);
      }
    }
  });

});
