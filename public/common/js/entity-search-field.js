/**
 * Copyright (C) 2025 Clément Latzarus
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

/**
 * A component to search for entities with completion and select them.
 *
 * When a user enters characters in the search input, it will query the server
 * receives the results and display a list with an item for each result.
 *
 * @typedef Result an object matching the search query returned by the server
 * @property {string} error - Error message if the search fails.
 * @property {string} url - The URL of the article.
 * @property {string} label - The label to display for the article.
 *
 * An Item is a clickable HTMLElement in the submenu that may represent a search result or a custom action.
 * The focusItemIndex is the index of the currently highlighted item in the submenu.
 *
 * The component has two modes
 * - Search mode: when the user can enter a query and search for entities.
 * - Locked mode: when the user has selected an entity, the input is read-only and displays the selected entity.
 */
export default class EntitySearchField {
  #focusItemIndex = -1;
  #results = [];
  #subMenuDisplayed = false;
  #lockedMode = false;
  #debounceTimeout = null;
  #currentQuery = '';

  /**
   *
   * @param {HTMLElement} element
   * @param {object} options
   * @param {(field: EntitySearchField, result: Result) => {}} options.onResultSelected
   * @param {object} options.action
   * @param {string} options.action.label
   * @param {(field: EntitySearchField, query: string) => {}} options.action.onSelect
   */
  constructor(element, options = {}) {
    const currentElementIsAlreadyLoaded = element.dataset.loaded;
    if (currentElementIsAlreadyLoaded) {
      return;
    }

    element.dataset.loaded = 'true';

    this.queryUrl = element.dataset.query_url;

    this.searchInput = element.querySelector('.EntitySearchField__search-input');
    this.searchInput.addEventListener('input', this.#onInput.bind(this));
    this.searchInput.addEventListener('focus', this.#onInputFocus.bind(this));
    this.searchInput.addEventListener('blur', this.#onInputBlur.bind(this));

    this.valueInput = element.querySelector('.EntitySearchField__value-input');

    this.button = element.querySelector('button');
    this.buttonIcon = this.button.querySelector('i');
    this.button.addEventListener('click', this.#onButtonClick.bind(this));

    this.helpText = element.querySelector('.help-text');
    if (this.searchInput.autofocus) {
      this.helpText.classList.remove('d-none');
    }

    this.subMenu = element.querySelector('.autocomplete-results');

    this.onResultSelectedCallback = options.onResultSelected;

    this.shouldSubmitParentForm = element.dataset.submit_form !== undefined;
    this.parentForm = element.closest('form');

    if (this.valueInput?.value) {
      this.#lockedMode = true;
      this.#switchToLockedMode({
        label: this.searchInput.value,
        value: this.valueInput.value
      });
    }

    document.addEventListener('keydown', this.#onKeyDown.bind(this));

    this.customAction = options.action;
  }

  #onInputFocus() {
    this.#showHelpText();

    if (this.#lockedMode) {
      this.#switchToSearchMode();
    }
  }

  #onInputBlur() {
    this.#hideHelpText();
  }

  #onButtonClick(event) {
    if (this.#lockedMode) {
      this.#switchToSearchMode();
      return;
    }

    return this.#search(event);
  }

  #onInput(event) {
    if (this.#subMenuDisplayed) {
      this.#focusItem(-1);
    }

    window.clearTimeout(this.#debounceTimeout);
    this.#debounceTimeout = window.setTimeout(() => this.#search(event), 300);
  }

  #onKeyDown(event) {
    if (event.key === 'Enter' && this.#focusItemIndex === -1) {
      return this.#search(event);
    }

    if (!this.#subMenuDisplayed) {
      return;
    }

    if (event.key === 'ArrowDown') {
      event.preventDefault();
      const nextItemIndex = Math.min(this.#focusItemIndex + 1, this.subMenu.children.length - 1);
      this.#focusItem(nextItemIndex);
    } else if (event.key === 'ArrowUp') {
      event.preventDefault();
      const previousItemIndex = Math.max(this.#focusItemIndex - 1, -1);

      if (previousItemIndex === -1) {
        this.searchInput.focus();
      }

      this.#focusItem(previousItemIndex);
    } else if (event.key === 'Enter') {
      event.preventDefault();
      this.#onItemSelect();
    }
  }

  #showHelpText() {
    if (this.#subMenuDisplayed) {
      return;
    }

    this.helpText.classList.remove('d-none');
  }

  #hideHelpText() {
    this.helpText.classList.add('d-none');
  }

  async #search(event) {
    event.preventDefault();

    const query = this.searchInput.value.trim();
    if (query.length < 1) {
      return;
    }

    this.#currentQuery = query;
    const response = await fetch(`${this.queryUrl}?term=${query}`, {
      method: 'GET'
    });

    /**
     * @type {object}
     * @property {string} error - Error message if the search fails.
     * @property {Result[]} results - Array of search results.
     */
    const responseData = await response.json();

    if (responseData.error) {
      window._alert(responseData.error, { title: 'Erreur lors de la recherche' });
      this.#hideResults();
      return;
    }

    this.subMenu.innerHTML = '';
    this.helpText.classList.add('d-none');

    this.#results = responseData.results;
    if (!this.#results) {
      throw new Error('Invalid autocomplete response: should include a results array.');
    }

    this.#results.forEach((result, index) => {
      const item = document.createElement('a');
      item.style.cursor = 'pointer';
      item.textContent = result.label;
      item.className = 'list-group-item list-group-item-action';
      this.subMenu.appendChild(item);
      item.addEventListener('focus', (event) => {
        event.preventDefault();
        this.#focusItem(index);
      });
      item.addEventListener('click', (event) => {
        event.preventDefault();
        this.#focusItemIndex = index;
        this.#onItemSelect();
      });
    });

    // Add custom actions if any
    if (this.customAction) {
      const item = document.createElement('a');
      item.style.cursor = 'pointer';
      item.textContent = '→ ' + this.customAction.label.replace('%query%', query);
      item.className = 'list-group-item list-group-item-action lead';
      this.subMenu.appendChild(item);
      item.addEventListener('click', (event) => {
        event.preventDefault();
        this.customAction.onSelect(this, query);
      });
    }

    this.#subMenuDisplayed = true;
  }

  /**
   * Gives focus to the item at the specified index.
   *
   * @param itemIndex
   */
  #focusItem(itemIndex) {
    this.#focusItemIndex = itemIndex;
    const items = this.subMenu.querySelectorAll('.list-group-item');
    items.forEach((item) => item.classList.remove('active'));

    if (this.#focusItemIndex > -1 && this.#focusItemIndex < items.length) {
      const focusedItem = items[this.#focusItemIndex];
      focusedItem.classList.add('active');
    }
  }

  #onItemSelect() {
    const selectedResult = this.#results[this.#focusItemIndex];

    if (!selectedResult && this.customAction) {
      this.customAction.onSelect(this, this.#currentQuery);
    }

    this.selectResult(selectedResult);
  }

  /**
   * @param {Result} result
   */
  selectResult(result) {
    if (this.valueInput) {
      this.valueInput.value = result?.value;
    }

    this.#switchToLockedMode(result);
    if (this.onResultSelectedCallback) {
      this.onResultSelectedCallback(this, result);
    }

    if (this.shouldSubmitParentForm && this.parentForm) {
      this.parentForm.submit();
    }
  }

  #switchToLockedMode(selectedResult) {
    this.#lockedMode = true;

    this.searchInput.value = selectedResult.label;
    this.searchInput.readOnly = true;
    this.searchInput.style.cursor = 'pointer';
    this.searchInput.blur();
    this.#hideResults();

    this.button.ariaLabel = 'Modifier';
    this.button.classList.remove('btn-primary');
    this.button.classList.add('btn-secondary');
    this.buttonIcon.classList.remove('fa-magnifying-glass');
    this.buttonIcon.classList.add('fa-pen-to-square');
    this.button.focus();
  }

  #switchToSearchMode() {
    this.#lockedMode = false;

    if (this.valueInput) {
      this.valueInput.value = '';
    }
    this.searchInput.value = '';
    this.searchInput.readOnly = false;
    this.searchInput.style.cursor = 'text';
    this.searchInput.focus();

    this.#hideResults();

    this.button.ariaLabel = 'Chercher';
    this.button.classList.remove('btn-secondary');
    this.button.classList.add('btn-primary');
    this.buttonIcon.classList.remove('fa-pen-to-square');
    this.buttonIcon.classList.add('fa-magnifying-glass');

    this.#focusItem(-1);
  }

  #hideResults() {
    this.subMenu.innerHTML = '';
    this.#subMenuDisplayed = false;
  }

  /** Public API **/

  reset() {
    this.#switchToSearchMode();
  }
}