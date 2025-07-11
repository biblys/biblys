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

export default class EntitySearchField {
  #focusItemIndex = -1;
  #results = [];
  #resultsDisplayed = false;
  #lockedMode = false;
  #debounceTimeout = null;
  #currentQuery = '';

  /**
   *
   * @param {HTMLElement} element
   * @param {object} options
   * @param {(result: Result) => {}} options.onResultSelected
   * @param {object} options.action
   * @param {string} options.action.label
   * @param {(query: string) => {}} options.action.onSelect
   */
  constructor(element, options = {}) {
    if(element.dataset.loaded) {
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
    this.buttonLabel = this.button.querySelector('span');
    this.button.addEventListener('click', this.#onButtonClick.bind(this));

    this.helpText = element.querySelector('.help-text');
    if (this.searchInput.autofocus) {
      this.helpText.classList.remove('d-none');
    }

    this.resultsElement = element.querySelector('.autocomplete-results');

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
    if (!this.#resultsDisplayed) {
      this.helpText.classList.remove('d-none');
    }

    if (this.#lockedMode) {
      this.#switchToSearchMode();
    }
  }

  #onInputBlur() {
    this.helpText.classList.add('d-none');
  }

  #onButtonClick(event) {
    if (this.#lockedMode) {
      this.#switchToSearchMode();
      return;
    }

    return this.#search(event);
  }

  #onInput(event) {
    if (this.#resultsDisplayed) {
      this.#focusItem(-1);
    }

    window.clearTimeout(this.#debounceTimeout);
    this.#debounceTimeout = window.setTimeout(() => this.#search(event), 300);
  }

  #onKeyDown(event) {
    if (event.key === 'Enter' && this.#focusItemIndex === -1) {
      return this.#search(event);
    }

    if (!this.#resultsDisplayed) {
      return;
    }

    if (event.key === 'ArrowDown') {
      event.preventDefault();
      const nextItemIndex = Math.min(this.#focusItemIndex + 1, this.resultsElement.children.length - 1);
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
     * @typedef Result
     * @property {string} error - Error message if the search fails.
     * @property {string} url - The URL of the article.
     * @property {string} label - The label to display for the article.
     */

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

    this.resultsElement.innerHTML = '';
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
      this.resultsElement.appendChild(item);
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
      this.resultsElement.appendChild(item);
      item.addEventListener('click', (event) => {
        event.preventDefault();
        this.customAction.onSelect(query);
      });
    }

    this.#resultsDisplayed = true;
  }

  #focusItem(itemIndex) {
    this.#focusItemIndex = itemIndex;
    const items = this.resultsElement.querySelectorAll('.list-group-item');
    items.forEach((item) => item.classList.remove('active'));

    if (this.#focusItemIndex > -1 && this.#focusItemIndex < items.length) {
      const focusedItem = items[this.#focusItemIndex];
      focusedItem.classList.add('active');
    }
  }

  #onItemSelect() {
    const selectedResult = this.#results[this.#focusItemIndex];

    if (!selectedResult && this.customAction) {
      this.customAction.onSelect(this.#currentQuery);
    }

    if (this.valueInput) {
      this.valueInput.value = selectedResult.value;
    }

    this.#switchToLockedMode(selectedResult);
    if (this.onResultSelectedCallback) {
      this.onResultSelectedCallback(selectedResult);
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

    this.buttonLabel.textContent = 'Modifier';
    this.button.classList.remove('btn-primary');
    this.button.classList.add('btn-secondary');
    this.buttonIcon.classList.remove('fa-magnifying-glass');
    this.buttonIcon.classList.add('fa-pen-to-square');
    this.button.focus();
  }

  #hideResults() {
    this.resultsElement.innerHTML = '';
    this.#resultsDisplayed = false;
  }

  #switchToSearchMode() {
    this.#lockedMode = false;

    this.searchInput.value = '';
    this.searchInput.readOnly = false;
    this.searchInput.style.cursor = 'text';
    this.searchInput.focus();

    this.#hideResults();

    this.buttonLabel.textContent = 'Chercher';
    this.button.classList.remove('btn-secondary');
    this.button.classList.add('btn-primary');
    this.buttonIcon.classList.remove('fa-pen-to-square');
    this.buttonIcon.classList.add('fa-magnifying-glass');

    this.#focusItem(-1);
  }
}