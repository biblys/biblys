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

document.addEventListener('DOMContentLoaded', function() {

  const applyButton = document.getElementById('listApply');
  if (!applyButton) return;

  // Choix en attente, appliqués seulement au clic sur "Actualiser"
  const pendingParams = new URLSearchParams(window.location.search);
  pendingParams.delete('p');
  pendingParams.delete('s');
  pendingParams.delete('_FORMAT');

  function rememberChoice(link, keys) {
    const linkParams = new URL(link.href, window.location.href).searchParams;
    keys.forEach(function(key) {
      if (linkParams.has(key)) pendingParams.set(key, linkParams.get(key));
      else pendingParams.delete(key);
    });
    pendingParams.delete('p');
  }

  function markSelected(link, group) {
    group.forEach(function(a) { a.removeAttribute('data-selected'); });
    link.setAttribute('data-selected', 'true');
  }

  // Filtre (Afficher :)
  const filterLinks = document.querySelectorAll('#listFilter .dropdown-item');
  const filterButton = document.querySelector('#listFilter > button');
  filterLinks.forEach(function(link) {
    link.addEventListener('click', function(e) {
      e.preventDefault();
      rememberChoice(link, ['q']);
      markSelected(link, filterLinks);
      filterButton.innerHTML =
        '<i class="fa fa-square ' + link.dataset.color + '"></i>&nbsp; ' +
        link.textContent.trim() + ' <span class="caret"></span>';
    });
  });

  // Tri (Trier par :)
  const sortLinks = document.querySelectorAll('#listSort .dropdown-item');
  const sortButton = document.querySelector('#listSort > button');
  sortLinks.forEach(function(link) {
    link.addEventListener('click', function(e) {
      e.preventDefault();
      rememberChoice(link, ['o', 'd']);
      markSelected(link, sortLinks);
      sortButton.innerHTML = link.textContent.trim() + ' <span class="caret"></span>';
    });
  });

  applyButton.addEventListener('click', function() {
    const query = pendingParams.toString();
    window.location.href = window.location.pathname + (query ? '?' + query : '');
  });

});
