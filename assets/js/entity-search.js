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

export default class EntitySearchField {
  constructor(element) {
    // Récupération de l'élément input
    const field = typeof element === 'string' ? document.querySelector(element) : element;
    const entity = field.getAttribute('data-entity_search');
    const form = field.closest('form');
    const submitButton = form.querySelector('button[type=submit]');

    // Création du bouton "Modifier"
    const changeButton = document.createElement('button');
    changeButton.type = 'button';
    changeButton.className = 'btn btn-outline-secondary';
    changeButton.disabled = true;
    changeButton.textContent = 'Modifier';

    // Création du champ caché pour l'ID
    const idField = document.createElement('input');
    idField.type = 'hidden';
    idField.name = entity.toLowerCase() + '_id';

    // Insertion dans le DOM
    field.insertAdjacentElement('afterend', idField);
    field.insertAdjacentElement('afterend', changeButton);

    // Création de la liste d'autocomplétion
    const autocompleteList = document.createElement('ul');
    autocompleteList.className = 'entity-autocomplete-list list-group position-absolute';
    autocompleteList.style.zIndex = '1000';
    autocompleteList.style.display = 'none';
    autocompleteList.style.maxHeight = '200px';
    autocompleteList.style.overflowY = 'auto';
    autocompleteList.style.top = '39px';
    autocompleteList.style.left = '-135px';
    autocompleteList.style.width = field.offsetWidth + 'px';
    field.parentNode.appendChild(autocompleteList);

    // Gestion de l'autocomplétion
    field.addEventListener('input', function() {
      const term = field.value.trim();
      if (term.length < 2) {
        autocompleteList.innerHTML = '';
        autocompleteList.style.display = 'none';
        return;
      }
      const source = '/' + entity.toLowerCase() + 's/';
      fetch(source + '?filter=' + encodeURIComponent(term))
        .then(r => r.json())
        .then(results => {
          autocompleteList.innerHTML = '';
          results.forEach(item => {
            const li = document.createElement('li');
            li.className = 'list-group-item list-group-item-action';
            li.textContent = item.label;
            li.dataset.value = item.id;
            li.tabIndex = 0;
            li.addEventListener('mousedown', function(e) {
              e.preventDefault();
              selectItem(item);
            });
            autocompleteList.appendChild(li);
          });
          if (results.length > 0) {
            autocompleteList.style.display = '';
            autocompleteList.style.width = field.offsetWidth + 'px';
          } else {
            autocompleteList.style.display = 'none';
          }
        });
    });

    // Sélection d'un élément
    const selectItem = (item) => {
      field.value = item.label;
      field.disabled = true;
      idField.value = item.id;
      changeButton.disabled = false;
      submitButton.disabled = false;
      autocompleteList.innerHTML = '';
      autocompleteList.style.display = 'none';
    };

    // Navigation clavier dans la liste
    field.addEventListener('keydown', function(e) {
      const items = autocompleteList.querySelectorAll('li');
      if (!items.length || autocompleteList.style.display === 'none') return;

      let index = Array.from(items).findIndex(li => li === document.activeElement);

      if (e.key === 'ArrowDown') {
        e.preventDefault();
        if (index < items.length - 1) {
          items[index + 1].focus();
        } else {
          items[0].focus();
        }
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        if (index > 0) {
          items[index - 1].focus();
        } else {
          items[items.length - 1].focus();
        }
      } else if (e.key === 'Enter') {
        if (document.activeElement.parentNode === autocompleteList) {
          e.preventDefault();
          document.activeElement.dispatchEvent(new Event('mousedown'));
        }
      } else if (e.key === 'Escape') {
        autocompleteList.innerHTML = '';
        autocompleteList.style.display = 'none';
      }
    });

    // Clic en dehors pour fermer la liste
    document.addEventListener('mousedown', function(e) {
      if (!autocompleteList.contains(e.target) && e.target !== field) {
        autocompleteList.innerHTML = '';
        autocompleteList.style.display = 'none';
      }
    });

    // Bouton "Modifier"
    changeButton.addEventListener('click', function() {
      field.value = '';
      field.disabled = false;
      idField.value = '';
      changeButton.disabled = true;
      submitButton.disabled = true;
      field.focus();
    });
  }
}
