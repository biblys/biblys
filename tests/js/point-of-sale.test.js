// @vitest-environment happy-dom
import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest';
import PointOfSale from '../../assets/js/point-of-sale/point-of-sale';

const cartLine = (stockId, price) => `
  <li id="stock_${stockId}">
    <span class="stock_selling_price" data-price="${price}">Neuf</span>
    <button data-remove_from_cart="${stockId}"></button>
  </li>`;

function renderPage(lines = [cartLine(1, 1000), cartLine(2, 550)]) {
  document.body.innerHTML = `
    <div id="point-of-sale">
      <span id="cart_title" contenteditable>Panier</span>
      <input type="hidden" id="cart_id" value="12">
      <input type="hidden" id="seller_id" value="3">
      <div id="customer" class="EntitySearchField">
        <input type="hidden" name="customer_id" value="8">
      </div>
      <div id="article" class="EntitySearchField">
        <input class="EntitySearchField__search-input">
      </div>
      <span id="cart_count"></span><span id="cart_count_s"></span>
      <table>
        <tr>
          <td id="cart_total" data-value="0"></td>
          <td><input id="cart_cash" class="cart_payment"></td>
          <td><input id="cart_cheque" class="cart_payment"></td>
          <td><input id="cart_card" class="cart_payment"></td>
          <td id="cart_topay" data-value="0"></td>
          <td id="cart_togive" data-value="0"></td>
        </tr>
      </table>
      <form id="checkout"><button type="submit"></button></form>
      <ul id="checkout_cart">${lines.join('')}</ul>
      <button id="clear" data-clear_cart="5" data-clear_cart_confirmation="Vider ?"></button>
    </div>`;
  return document.getElementById('point-of-sale');
}

class FakeEntitySearchField {
  static instances = [];

  constructor(element, options) {
    this.element = element;
    this.options = options;
    this.reset = vi.fn();
    FakeEntitySearchField.instances.push(this);
  }

  static forElement(id) {
    return FakeEntitySearchField.instances.find((field) => field.element.id === id);
  }
}

function buildDependencies(overrides = {}) {
  return {
    loadEntitySearchField: async () => FakeEntitySearchField,
    showAlert: vi.fn(),
    notify: vi.fn(),
    confirm: vi.fn().mockReturnValue(true),
    reloadPage: vi.fn(),
    redirectTo: vi.fn(),
    ...overrides,
  };
}

async function startPointOfSale(dependencies = buildDependencies(), lines) {
  const pointOfSale = new PointOfSale(renderPage(lines), dependencies);
  await pointOfSale.ready;
  return pointOfSale;
}

const jsonResponse = (body, ok = true) => ({ ok, json: async () => body });
const text = (selector) => document.querySelector(selector).textContent;
const type = (selector, value) => {
  const input = document.querySelector(selector);
  input.value = value;
  input.dispatchEvent(new Event('keyup'));
};
const submitCheckout = () =>
  document.getElementById('checkout').dispatchEvent(new Event('submit', { cancelable: true }));

let fetchMock;

beforeEach(() => {
  FakeEntitySearchField.instances = [];
  fetchMock = vi.fn();
  vi.stubGlobal('fetch', fetchMock);
});

afterEach(() => {
  vi.unstubAllGlobals();
});

describe('PointOfSale totals', () => {
  it('computes the totals from the cart lines on load', async () => {
    // when
    await startPointOfSale();

    // then
    expect(text('#cart_count')).toBe('2');
    expect(text('#cart_count_s')).toBe('s');
    expect(text('#cart_total')).toBe('15,50 €');
    expect(text('#cart_topay')).toBe('15,50 €');
    expect(text('#cart_togive')).toBe('0,00 €');
  });

  it('updates the amount left to pay and the change when payments are typed', async () => {
    // given
    await startPointOfSale();

    // when
    type('#cart_cash', '10');
    type('#cart_card', '7.5');

    // then
    expect(text('#cart_topay')).toBe('0,00 €');
    expect(text('#cart_togive')).toBe('2,00 €');
    expect(document.getElementById('cart_togive').dataset.value).toBe('200');
  });
});

describe('PointOfSale cart', () => {
  it('adds the selected article to the cart and resets the search field', async () => {
    // given
    const dependencies = buildDependencies();
    await startPointOfSale(dependencies, [cartLine(1, 1000)]);
    fetchMock.mockResolvedValue(jsonResponse({ success: 'Ajouté', line: cartLine(2, 550) }));
    const articleField = FakeEntitySearchField.forElement('article');

    // when
    await articleField.options.onResultSelected(articleField, { value: 345 });

    // then
    expect(fetchMock.mock.calls[0][0]).toBe('/admin/pos/carts/12/items');
    expect(document.getElementById('stock_2')).not.toBeNull();
    expect(text('#cart_total')).toBe('15,50 €');
    expect(dependencies.notify).toHaveBeenCalledWith('Ajouté');
    expect(articleField.reset).toHaveBeenCalled();
  });

  it('shows the server error when an article cannot be added', async () => {
    // given
    const dependencies = buildDependencies();
    await startPointOfSale(dependencies, [cartLine(1, 1000)]);
    fetchMock.mockResolvedValue(jsonResponse({ message: 'Exemplaire déjà vendu' }, false));
    const articleField = FakeEntitySearchField.forElement('article');

    // when
    await articleField.options.onResultSelected(articleField, { value: 345 });

    // then
    expect(dependencies.showAlert).toHaveBeenCalledWith('Exemplaire déjà vendu');
    expect(document.getElementById('stock_2')).toBeNull();
    expect(articleField.reset).toHaveBeenCalled();
  });

  it('removes a line from the cart and updates the totals', async () => {
    // given
    const dependencies = buildDependencies();
    await startPointOfSale(dependencies);
    fetchMock.mockResolvedValue(jsonResponse({ success: 'Retiré' }));

    // when
    document.querySelector('[data-remove_from_cart="2"]').click();

    // then
    await vi.waitFor(() => expect(document.getElementById('stock_2')).toBeNull());
    expect(fetchMock.mock.calls[0][0]).toBe('/admin/pos/carts/12/items/2');
    expect(text('#cart_total')).toBe('10,00 €');
    expect(text('#cart_count_s')).toBe('');
  });

  it('saves the cart title when it loses focus', async () => {
    // given
    const dependencies = buildDependencies();
    await startPointOfSale(dependencies);
    fetchMock.mockResolvedValue(jsonResponse({ success: 'Titre enregistré' }));
    document.getElementById('cart_title').textContent = 'Panier de Léa';

    // when
    document.getElementById('cart_title').dispatchEvent(new Event('blur'));

    // then
    await vi.waitFor(() => expect(dependencies.notify).toHaveBeenCalledWith('Titre enregistré'));
    const [url, options] = fetchMock.mock.calls[0];
    expect(url).toBe('/admin/pos/carts/12');
    expect(new URLSearchParams(options.body).get('title')).toBe('Panier de Léa');
  });
});

describe('PointOfSale customer', () => {
  it('attaches the selected customer to the cart and focuses the article search', async () => {
    // given
    const dependencies = buildDependencies();
    await startPointOfSale(dependencies);
    fetchMock.mockResolvedValue(jsonResponse({ success: 'Client associé' }));
    const customerField = FakeEntitySearchField.forElement('customer');

    // when
    await customerField.options.onResultSelected(customerField, { value: 8 });

    // then
    expect(new URLSearchParams(fetchMock.mock.calls[0][1].body).get('customer_id')).toBe('8');
    expect(dependencies.notify).toHaveBeenCalledWith('Client associé');
    expect(document.activeElement.className).toBe('EntitySearchField__search-input');
  });

  it('resets the customer field when the customer cannot be attached', async () => {
    // given
    const dependencies = buildDependencies();
    await startPointOfSale(dependencies);
    fetchMock.mockResolvedValue(jsonResponse({ message: 'Client introuvable' }, false));
    const customerField = FakeEntitySearchField.forElement('customer');

    // when
    await customerField.options.onResultSelected(customerField, { value: 8 });

    // then
    expect(dependencies.showAlert).toHaveBeenCalledWith('Client introuvable');
    expect(customerField.reset).toHaveBeenCalled();
  });

  it('detaches the customer from the cart when the field is unlocked', async () => {
    // given
    await startPointOfSale();
    fetchMock.mockResolvedValue(jsonResponse({ success: true }));
    const customerField = FakeEntitySearchField.forElement('customer');

    // when
    await customerField.options.onReset();

    // then
    expect(new URLSearchParams(fetchMock.mock.calls[0][1].body).get('customer_id')).toBe('0');
  });
});

describe('PointOfSale sale', () => {
  it('refuses to save a sale for an empty cart', async () => {
    // given
    const dependencies = buildDependencies();
    await startPointOfSale(dependencies, []);

    // when
    submitCheckout();

    // then
    expect(dependencies.showAlert).toHaveBeenCalledWith(
      "Impossible d'enregistrer la vente : le panier est vide.",
    );
    expect(fetchMock).not.toHaveBeenCalled();
  });

  it('asks for confirmation when an amount is left to pay and stops if declined', async () => {
    // given
    const dependencies = buildDependencies({ confirm: vi.fn().mockReturnValue(false) });
    await startPointOfSale(dependencies);
    type('#cart_cash', '10');

    // when
    submitCheckout();

    // then
    expect(dependencies.confirm).toHaveBeenCalledWith(
      'Il reste 5,50 € à payer, enregistrer quand même la vente ?',
    );
    expect(fetchMock).not.toHaveBeenCalled();
  });

  it('saves the sale with amounts in cents and goes back to the point of sale', async () => {
    // given
    const dependencies = buildDependencies();
    await startPointOfSale(dependencies);
    fetchMock.mockResolvedValue(jsonResponse({ success: true, order_id: 7 }));
    type('#cart_cash', '12.1');
    type('#cart_card', '3.4');

    // when
    submitCheckout();

    // then
    await vi.waitFor(() => expect(dependencies.redirectTo).toHaveBeenCalledWith('/admin/caisse'));
    const [url, options] = fetchMock.mock.calls[0];
    const body = new URLSearchParams(options.body);
    expect(url).toBe('/admin/pos/carts/12/sale');
    expect(Object.fromEntries(body)).toEqual({
      seller_id: '3',
      customer_id: '8',
      cart_cash: '1210',
      cart_cheque: '0',
      cart_card: '340',
      cart_topay: '0',
      cart_togive: '0',
    });
    expect(dependencies.confirm).not.toHaveBeenCalled();
  });

  it('stays on the page and shows the error when the sale cannot be saved', async () => {
    // given
    const dependencies = buildDependencies();
    await startPointOfSale(dependencies);
    fetchMock.mockResolvedValue(jsonResponse({ message: 'Vente refusée' }, false));
    type('#cart_cash', '15.5');

    // when
    submitCheckout();

    // then
    await vi.waitFor(() => expect(dependencies.showAlert).toHaveBeenCalledWith('Vente refusée'));
    expect(dependencies.redirectTo).not.toHaveBeenCalled();
  });
});

describe('PointOfSale clear cart', () => {
  it('does nothing when the confirmation is declined', async () => {
    // given
    const dependencies = buildDependencies({ confirm: vi.fn().mockReturnValue(false) });
    await startPointOfSale(dependencies);

    // when
    document.getElementById('clear').click();

    // then
    expect(dependencies.confirm).toHaveBeenCalledWith('Vider ?');
    expect(fetchMock).not.toHaveBeenCalled();
  });

  it('clears the cart and reloads the page when confirmed', async () => {
    // given
    const dependencies = buildDependencies();
    await startPointOfSale(dependencies);
    fetchMock.mockResolvedValue(jsonResponse({ success: true }));

    // when
    document.getElementById('clear').click();

    // then
    await vi.waitFor(() => expect(dependencies.reloadPage).toHaveBeenCalled());
    expect(fetchMock.mock.calls[0][0]).toBe('/admin/pos/carts/5/items');
    expect(fetchMock.mock.calls[0][1].method).toBe('DELETE');
  });
});
