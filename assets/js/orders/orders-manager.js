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

import Order from './order';

// noinspection JSUnusedGlobalSymbols
export default class OrdersManager {
  constructor() {
    // Load order on the page load
    this.loadOrders();

    // Load filtered orders when the form is submitted
    const showOrdersForm = document.getElementById('showOrders');
    showOrdersForm.addEventListener('submit', (event) => {
      event.preventDefault();

      const action = event.submitter.dataset.action;
      if (action === 'show') {
        this.loadOrders();
      }

      if (action === 'export') {
        const url = `${window.location.protocol}//${window.location.host}${event.submitter.dataset.export_url}`;
        window.location.href = this._addParamsToUrl(url).toString();
      }
    });

    const loadMoreOrdersButton = document.getElementById('load-more-orders-button');
    loadMoreOrdersButton.addEventListener('click', () => this.loadMoreOrders());
  }

  loadOrders(offset = 0) {
    const url = this._addParamsToUrl(document.location);
    url.searchParams.append('offset', offset.toString());

    // If offset = 0, reset table
    if (offset === 0) {
      document.getElementById('orders').innerHTML = '';
    }

    // Show loading
    document.getElementById('ordersLoading').style.display = '';

    const loadMoreOrdersButton = document.getElementById('load-more-orders-button');
    loadMoreOrdersButton.style.opacity = '0';

    // Load orders
    fetch(url, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
      .then(function(response) {
        return response.json();
      })
      /**
       * @param {Object} data
       * @param {number} data.results - The number of results returned.
       * @param {number} data.total - The total number of orders available.
       * @param {Array} data.orders - The list of orders.
       */
      .then(function(data) {
        document.getElementById('ordersLoading').style.display = 'none';

        if (data.error) {
          window._alert(data.error.message);
        }

        if (data.results > 0) {
          let tr = null;
          data.orders.forEach(function(order_data) {
            const order = new Order(order_data);
            tr = order.getRow();
            document.getElementById('orders').appendChild(tr);
          });

          const orders = document.querySelectorAll('#orders tr');
          const ordersCountElement = document.getElementById('orders-count');
          const ordersCount = orders.length;
          if (ordersCount < data.total) {
            loadMoreOrdersButton.style.opacity = '1';
            ordersCountElement.textContent = (data.total - ordersCount).toString();
          }
        } else {
          document.getElementById('orders').innerHTML =
            '<tr><td colspan="10" class="text-center alert-success">Aucune commande à afficher.</td></tr>';
        }
      });
  }

  loadMoreOrders() {
    const orders = document.querySelectorAll('#orders tr');
    const ordersCount = orders.length;
    this.loadOrders(ordersCount);
  }

  _addParamsToUrl(url) {
    const params = {
      status: document.getElementById('order_status').value,
      payment: document.getElementById('order_payment_mode').value,
      shipping: document.getElementById('order_shipping_mode').value,
      query: document.getElementById('query').value
    };

    const newUrl = new URL(url);
    newUrl.search = new URLSearchParams(params).toString();

    return newUrl;
  }
}
