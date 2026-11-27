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
export class CartApiError extends Error {}

async function request(url, { method, fields, errorMessage }) {
  const options = { method, headers: { Accept: 'application/json' } };
  if (fields) {
    options.headers['Content-Type'] = 'application/x-www-form-urlencoded';
    options.body = new URLSearchParams(fields).toString();
  }

  const response = await fetch(url, options);
  if (!response.ok) {
    throw new CartApiError(await readErrorMessage(response, errorMessage));
  }

  return response.json();
}

async function readErrorMessage(response, fallbackMessage) {
  try {
    const error = await response.json();
    return error?.message || fallbackMessage;
  } catch {
    return fallbackMessage;
  }
}

export function addItem(cartId, stockId) {
  return request(`/admin/pos/carts/${cartId}/items`, {
    method: 'POST',
    fields: { stock_id: stockId },
    errorMessage: "L'exemplaire n'a pas pu être ajouté au panier.",
  });
}

export function updateItemPrice(cartId, stockItemId, price) {
  return request(`/admin/pos/carts/${cartId}/items/${stockItemId}`, {
    method: 'PATCH',
    fields: { price },
    errorMessage: "Le prix de l'exemplaire n'a pas pu être modifié.",
  });
}

export function removeItem(cartId, stockId) {
  return request(`/admin/pos/carts/${cartId}/items/${stockId}`, {
    method: 'DELETE',
    errorMessage: "L'exemplaire n'a pas pu être retiré du panier.",
  });
}

export function clearCart(cartId) {
  return request(`/admin/pos/carts/${cartId}/items`, {
    method: 'DELETE',
    errorMessage: "Le panier n'a pas pu être vidé.",
  });
}

export function updateCart(cartId, fields) {
  return request(`/admin/pos/carts/${cartId}`, {
    method: 'PATCH',
    fields,
    errorMessage: "Le panier n'a pas pu être mis à jour.",
  });
}

export function createSale(cartId, fields) {
  return request(`/admin/pos/carts/${cartId}/sale`, {
    method: 'POST',
    fields,
    errorMessage: "La vente n'a pas pu être enregistrée.",
  });
}
