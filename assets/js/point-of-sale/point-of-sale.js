/**
 * Copyright (C) 2026 Clément Latzarus
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
import '../../css/point-of-sale/point-of-sale.css';
import Notification from '../notification';
import createElementFromHTML from '../helpers/createElementFromHTML';
import eurosToCents from '../helpers/eurosToCents';
import formatAmount from '../helpers/formatAmount';
import safeHtml from '../helpers/safeHtml';
import {
  addItem,
  CartApiError,
  clearCart,
  createSale,
  removeItem,
  updateCart,
  updateItemPrice,
} from './cart-api';
import parsePrice from './parse-price';
import { calculateCartTotals } from './payments';

const POINT_OF_SALE_URL = '/admin/caisse';
const REQUEST_FAILURE_MESSAGE =
  'La communication avec le serveur a échoué. Rechargez la page pour vérifier avant de réessayer.';

const ENTITY_SEARCH_FIELD_URL = '/common/js/entity-search-field.js';

async function loadEntitySearchField() {
  const entitySearchFieldModule = await import(
    /* webpackIgnore: true */ /* @vite-ignore */ ENTITY_SEARCH_FIELD_URL
  );
  return entitySearchFieldModule.default;
}

function renderArticleSearchResult(result) {
  const conditionColor = result.condition === 'Neuf' ? 'green' : 'orange';

  return safeHtml`
    <div class="d-flex justify-content-between">
      <span class="flex-grow-1">${result.title}</span>
      <span class="mx-3">${result.publisher}</span>
      <span class="mx-3">${result.count}</span>
      <span class="mx-3 ${conditionColor}">${result.condition}</span>
      <span class="text-right">${result.price}</span>
    </div>
  `;
}

export default class PointOfSale {
  #pendingPriceSaves = Promise.resolve();

  constructor(element, dependencies = {}) {
    this.element = element;
    this.loadEntitySearchField = dependencies.loadEntitySearchField ?? loadEntitySearchField;
    this.showAlert = dependencies.showAlert ?? ((message) => window._alert(message));
    this.notify = dependencies.notify ?? ((text) => new Notification(text));
    this.confirm = dependencies.confirm ?? ((message) => window.confirm(message));
    this.reloadPage = dependencies.reloadPage ?? (() => window.location.reload());
    this.redirectTo =
      dependencies.redirectTo ??
      ((url) => {
        window.location.href = url;
      });

    this.#bindEvents();
    this.#updateTotals();
    this.ready = this.#setupSearchFields();
  }

  get #cartId() {
    return this.#find('#cart_id').value;
  }

  #find(selector) {
    return this.element.querySelector(selector);
  }

  #bindEvents() {
    this.#find('#checkout').addEventListener('submit', (event) => {
      event.preventDefault();
      this.#saveSale();
    });

    this.element.querySelectorAll('.cart_payment').forEach((input) => {
      input.addEventListener('keyup', () => this.#updateTotals());
      input.addEventListener('click', () => this.#updateTotals());
    });

    this.element.addEventListener('click', (event) => {
      const removeButton = event.target.closest('[data-remove_from_cart]');
      if (removeButton) {
        this.#removeFromCart(removeButton.getAttribute('data-remove_from_cart'));
        return;
      }

      const editButton = event.target.closest('[data-edit_price]');
      if (editButton) {
        this.#openPriceEditor(editButton.getAttribute('data-edit_price'));
        return;
      }

      const clearButton = event.target.closest('[data-clear_cart]');
      if (clearButton) {
        this.#clearCartAfterConfirmation(clearButton);
      }
    });

    this.element.addEventListener('submit', (event) => {
      const priceForm = event.target.closest('[data-edit_price_form]');
      if (priceForm) {
        event.preventDefault();
        priceForm.querySelector('input[name=price]').blur();
      }
    });

    this.element.addEventListener('focusout', (event) => {
      const priceForm = event.target.closest('[data-edit_price_form]');
      if (priceForm) {
        this.#pendingPriceSaves = Promise.allSettled([
          this.#pendingPriceSaves,
          this.#savePrice(priceForm),
        ]);
      }
    });

    const title = this.#find('#cart_title');
    title.addEventListener('keydown', (event) => {
      if (event.key === 'Enter') {
        event.preventDefault();
        title.blur();
      }
    });
    title.addEventListener('blur', () => this.#saveTitle(title.textContent));
  }

  async #setupSearchFields() {
    const EntitySearchField = await this.loadEntitySearchField();

    const articleField = this.#find('#article.EntitySearchField');
    if (articleField) {
      new EntitySearchField(articleField, {
        autoSelectSingleResult: true,
        renderItem: renderArticleSearchResult,
        onResultSelected: async (field, { value }) => {
          await this.#addToCart(value);
          field.reset();
        },
      });
    }

    const customerField = this.#find('#customer.EntitySearchField');
    if (customerField) {
      new EntitySearchField(customerField, {
        onResultSelected: async (field, { value }) => {
          const response = await this.#updateCartInfo({ customer_id: value });
          if (!response) {
            field.reset();
            return;
          }

          this.notify(response.success);
          this.#find('#article .EntitySearchField__search-input')?.focus();
        },
        onReset: () => this.#updateCartInfo({ customer_id: 0 }),
        action: {
          label: 'Créer un nouveau client',
          onSelect: () => window.open('/pages/adm_customer', '_blank'),
        },
      });
    }
  }

  async #callApi(request) {
    try {
      return await request();
    } catch (error) {
      if (error instanceof CartApiError) {
        this.showAlert(error.message);
      } else {
        console.error(error);
        this.showAlert(REQUEST_FAILURE_MESSAGE);
      }
      return null;
    }
  }

  #updateCartInfo(fields) {
    return this.#callApi(() => updateCart(this.#cartId, fields));
  }

  async #saveTitle(title) {
    const response = await this.#updateCartInfo({ title });
    if (response) {
      this.notify(response.success);
    }
  }

  async #addToCart(stockId) {
    const response = await this.#callApi(() => addItem(this.#cartId, stockId));
    if (!response) {
      return;
    }

    this.#find('#checkout_cart').prepend(createElementFromHTML(response.line));
    this.notify(response.success);
    this.#updateTotals();
  }

  async #removeFromCart(stockId) {
    const response = await this.#callApi(() => removeItem(this.#cartId, stockId));
    if (!response) {
      return;
    }

    this.#find(`#stock_${stockId}`)?.remove();
    this.notify(response.success);
    this.#updateTotals();
  }

  #openPriceEditor(stockItemId) {
    const form = this.#find(`[data-edit_price_form="${stockItemId}"]`);
    form.hidden = false;
    this.#find(`#stock_${stockItemId}`).classList.add('pos-cart-line--editing');

    const input = form.querySelector('input[name=price]');
    input.focus();
    input.select();
  }

  #closePriceEditor(form) {
    form.reset();
    form.hidden = true;
    form.closest('li').classList.remove('pos-cart-line--editing');
  }

  async #savePrice(form) {
    const stockItemId = form.dataset.edit_price_form;
    const input = form.querySelector('input[name=price]');
    if (input.value === input.defaultValue) {
      this.#closePriceEditor(form);
      return;
    }

    const price = parsePrice(input.value);
    if (price === null) {
      this.showAlert('Veuillez saisir un prix valide.');
      return;
    }

    const response = await this.#callApi(() => updateItemPrice(this.#cartId, stockItemId, price));
    if (!response) {
      return;
    }

    this.#find(`#stock_${stockItemId}`)?.replaceWith(createElementFromHTML(response.line));
    this.notify(response.success);
    this.#updateTotals();
  }

  async #clearCartAfterConfirmation(button) {
    if (!this.confirm(button.getAttribute('data-clear_cart_confirmation'))) {
      return;
    }

    const response = await this.#callApi(() => clearCart(button.getAttribute('data-clear_cart')));
    if (response) {
      this.reloadPage();
    }
  }

  async #saveSale() {
    await this.#pendingPriceSaves;
    if (this.#find('[data-edit_price_form]:not([hidden])')) {
      this.showAlert("Un prix n'a pas été enregistré : corrigez-le avant d'enregistrer la vente.");
      return;
    }

    const toPay = Number(this.#find('#cart_topay').dataset.value);
    const total = Number(this.#find('#cart_total').dataset.value);

    if (toPay !== 0) {
      const message = `Il reste ${formatAmount(toPay)} à payer, enregistrer quand même la vente ?`;
      if (!this.confirm(message)) {
        return;
      }
    } else if (total === 0) {
      this.showAlert("Impossible d'enregistrer la vente : le panier est vide.");
      return;
    }

    const response = await this.#callApi(() =>
      createSale(this.#cartId, {
        seller_id: this.#find('#seller_id').value,
        customer_id: this.#find('#customer input[name=customer_id]')?.value ?? '',
        cart_cash: eurosToCents(this.#find('#cart_cash').value),
        cart_cheque: eurosToCents(this.#find('#cart_cheque').value),
        cart_card: eurosToCents(this.#find('#cart_card').value),
        cart_topay: toPay,
        cart_togive: this.#find('#cart_togive').dataset.value,
      }),
    );
    if (response) {
      this.redirectTo(POINT_OF_SALE_URL);
    }
  }

  #updateTotals() {
    const itemPrices = Array.from(this.element.querySelectorAll('.stock_selling_price')).map(
      (priceElement) => parseInt(priceElement.dataset.price, 10),
    );
    const payments = Array.from(this.element.querySelectorAll('.cart_payment')).map((input) =>
      eurosToCents(input.value),
    );

    const { count, total, toPay, toGive } = calculateCartTotals({ itemPrices, payments });

    this.#find('#cart_count').textContent = count;
    this.#find('#cart_count_s').textContent = count > 1 ? 's' : '';
    this.#displayAmount('#cart_total', total);
    this.#displayAmount('#cart_topay', toPay);
    this.#displayAmount('#cart_togive', toGive);
  }

  #displayAmount(selector, amountInCents) {
    const element = this.#find(selector);
    element.dataset.value = amountInCents;
    element.textContent = formatAmount(amountInCents);
  }
}
