import { afterEach, describe, expect, it, vi } from 'vitest';
import {
  addItem,
  CartApiError,
  clearCart,
  createSale,
  removeItem,
  updateCart,
  updateItemPrice,
} from '../../assets/js/point-of-sale/cart-api';

function stubFetch(response) {
  const fetchMock = vi.fn().mockResolvedValue(response);
  vi.stubGlobal('fetch', fetchMock);
  return fetchMock;
}

const jsonResponse = (body, ok = true) => ({ ok, json: async () => body });

afterEach(() => {
  vi.unstubAllGlobals();
});

describe('cart api', () => {
  it('adds an item with a form-encoded POST and returns the json', async () => {
    // given
    const fetchMock = stubFetch(jsonResponse({ success: 'Ajouté', line: '<tr></tr>' }));

    // when
    const result = await addItem(12, 345);

    // then
    expect(result).toEqual({ success: 'Ajouté', line: '<tr></tr>' });
    expect(fetchMock).toHaveBeenCalledWith('/admin/pos/carts/12/items', {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: 'stock_id=345',
    });
  });

  it('removes an item with a DELETE without body', async () => {
    // given
    const fetchMock = stubFetch(jsonResponse({ success: 'Retiré' }));

    // when
    await removeItem(12, 345);

    // then
    expect(fetchMock).toHaveBeenCalledWith('/admin/pos/carts/12/items/345', {
      method: 'DELETE',
      headers: { Accept: 'application/json' },
    });
  });

  it('updates the price of an item with a form-encoded PATCH', async () => {
    // given
    const fetchMock = stubFetch(jsonResponse({ success: 'Modifié', line: '<li></li>' }));

    // when
    await updateItemPrice(12, 345, 850);

    // then
    expect(fetchMock).toHaveBeenCalledWith('/admin/pos/carts/12/items/345', {
      method: 'PATCH',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: 'price=850',
    });
  });

  it('clears a cart', async () => {
    // given
    const fetchMock = stubFetch(jsonResponse({ success: true }));

    // when
    await clearCart(12);

    // then
    expect(fetchMock.mock.calls[0][0]).toBe('/admin/pos/carts/12/items');
    expect(fetchMock.mock.calls[0][1].method).toBe('DELETE');
  });

  it('updates a cart with a PATCH', async () => {
    // given
    const fetchMock = stubFetch(jsonResponse({ success: true }));

    // when
    await updateCart(12, { title: 'Panier de Léa & Zoé' });

    // then
    const [url, options] = fetchMock.mock.calls[0];
    expect(url).toBe('/admin/pos/carts/12');
    expect(options.method).toBe('PATCH');
    expect(new URLSearchParams(options.body).get('title')).toBe('Panier de Léa & Zoé');
  });

  it('creates a sale with all payment fields', async () => {
    // given
    const fetchMock = stubFetch(jsonResponse({ success: true, order_id: 7 }));

    // when
    const result = await createSale(12, { seller_id: 3, cart_cash: 1500 });

    // then
    expect(result.order_id).toBe(7);
    const [url, options] = fetchMock.mock.calls[0];
    expect(url).toBe('/admin/pos/carts/12/sale');
    expect(options.body).toBe('seller_id=3&cart_cash=1500');
  });

  it('throws a CartApiError carrying the server message', async () => {
    // given
    stubFetch(jsonResponse({ message: 'Exemplaire déjà vendu' }, false));

    // when
    const error = await addItem(12, 345).catch((caughtError) => caughtError);

    // then
    expect(error).toBeInstanceOf(CartApiError);
    expect(error.message).toBe('Exemplaire déjà vendu');
  });

  it('falls back to a generic message when the error body is not json', async () => {
    // given
    stubFetch({
      ok: false,
      json: async () => {
        throw new SyntaxError('Unexpected token <');
      },
    });

    // when
    const clearUnclearableCart = clearCart(12);

    // then
    await expect(clearUnclearableCart).rejects.toThrow("Le panier n'a pas pu être vidé.");
  });
});
