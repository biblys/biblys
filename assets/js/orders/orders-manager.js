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

import $ from 'jquery';

import Order from './order';

export default class OrdersManager {
  constructor() {
    // Load order on page load
    this.loadOrders();

    // Load filtered orders on form submit
    $('#showOrders').on('submit', event => this.loadOrders(event));

    var loadMoreOrdersButton = document.getElementById('load-more-orders-button');
    loadMoreOrdersButton.addEventListener('click', () => this.loadMoreOrders());
  }

  loadOrders(event, offset) {
    if (event) {
      event.preventDefault();
    }

    var params = {};
    params.status = $('#order_status').val();
    params.payment = $('#order_payment_mode').val();
    params.shipping = $('#order_shipping_mode').val();
    params.query = $('#query').val();

    params.offset = typeof offset === 'undefined' ? 0 : offset;

    var loadMoreOrdersButton = document.getElementById('load-more-orders-button');

    // If offset = 0, reset table
    if (params.offset === 0) {
      $('#orders').html('');
    }

    // Show loading
    $('#ordersLoading').show();

    // Build url with params
    var url = new URL(document.location);
    url.search = new URLSearchParams(params);

    loadMoreOrdersButton.style.opacity = 0;

    // Load orders
    fetch(url, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
      .then(function(response) {
        return response.json();
      })
      .then(function(data) {
        $('#ordersLoading').hide();

        if (data.error) {
          window._alert(data.error);
        }

        if (data.results > 0) {
          var tr = null;
          $.each(data.orders, function(index, order_data) {
            var order = new Order(order_data);

            tr = order.getRow();
            $('#orders').append(tr);
          });

          // Using legacy jquery
          window.jQuery('[title]').tooltipster();

          var orders = document.querySelectorAll('#orders tr');
          var ordersCountElement = document.getElementById('orders-count');
          var ordersCount = orders.length;
          if (ordersCount < data.total) {
            loadMoreOrdersButton.style.opacity = 1;
            ordersCountElement.textContent = data.total - ordersCount;
          }
        } else {
          $('#orders').html(
            '<tr><td colspan="9" class="text-center alert-success">Aucune commande à afficher.</td></tr>'
          );
        }
      });
  }

  loadMoreOrders() {
    var orders = document.querySelectorAll('#orders tr');
    var ordersCount = orders.length;
    this.loadOrders(null, ordersCount);
  }
}
