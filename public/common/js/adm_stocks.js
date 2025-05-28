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

function fieldUpdate(stock_id, field, value) {
  fetch('/x/adm_stock?action=update', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
      'Accept': 'application/json',
    },
    body: new URLSearchParams({ stock_id, field, value })
  })
    .then(response => response.json().then(data => ({ status: response.status, body: data })))
    /**
     * @param res {Object}
     * @param res.error {String} Error message if any
     * @param res.field_name {String} Name of the field that was updated
     */
    .then(({ body: res }) => {
      if (res.error) {
        window._alert(res.error);
      } else {
        if (field === 'stock_selling_price' || field === 'stock_purchase_price') {
          value = window.currency(value / 100);
        }
        document.getElementById('span-' + field + '-' + stock_id).innerHTML = value;
        document.getElementById('form-' + field + '-' + stock_id).style.display = 'none';
        document.getElementById('span-' + field + '-' + stock_id).style.display = '';
        new window.Biblys.Notification(
          `Le champ ${res.field_name} de l'exemplaire n° ${res.stock_id} (${res.article_title}) a été mis à jour.`
          , { type: 'info' }
        );
      }
    });
  return false;
}

document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.changeBool').forEach(function(el) {
    el.addEventListener('change', function() {
      const stock_id = el.dataset.stock_id;
      const field = el.dataset.field;
      let value = 0;
      if (el.checked) {
        value = 1;
      }
      fieldUpdate(stock_id, field, value);
    });
  });

  document.querySelectorAll('.cell').forEach(function(cell) {
    cell.addEventListener('click', function() {
      const field_id = cell.id.split('-');
      const field = field_id[1];
      const id = field_id[2];
      document.getElementById('span-' + field + '-' + id).style.display = 'none';
      document.getElementById('form-' + field + '-' + id).style.display = '';
      document.getElementById('input-' + field + '-' + id).focus();
    });
  });

  const forms = document.querySelectorAll('form.update');
  for (let i = 0, c = forms.length; i < c; i++) {
    const form = forms[i];
    const input = form.querySelector('input');
    const { stock_id, field } = form.dataset;
    form.addEventListener('submit', event => {
      event.preventDefault();
      input.blur();
    });
    input.addEventListener('blur', () => {
      fieldUpdate(stock_id, field, input.value);
    });
  }

  document.querySelectorAll('.deleteStock').forEach(function(btn) {
    btn.addEventListener('click', function(event) {
      event.preventDefault();
      const stock_id = btn.dataset.stock_id;
      const article_id = btn.dataset.article_id;
      const isConfirmed = confirm('Voulez-vous vraiment SUPPRIMER l\'exemplaire ' + stock_id + ' ?');
      if (isConfirmed) {
        fetch('/x/adm_stock?action=delete', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            'Accept': 'application/json',
          },
          body: new URLSearchParams({ stock_id, article_id })
        })
          .then(response => response.json())
          /**
           * @param res {Object}
           * @param res.error {String}
           * @param res.total {Number}
           * @param res.available {Number}
           * @param res.inCart {Number}
           * @param res.sold {Number}
           * @param res.returned {Number}
           * @param res.lost {Number}
           */
          .then(res => {
            if (res.error) {
              window._alert(res.error);
            } else {
              const stocks = `
                <span class="total">${res.total}</span> exemplaires dont&nbsp;
                <span class="in-stock">${res.available}</span> 
                <span class="fa fa-square led-green" title="En stock"></span>&nbsp;
                <span class="in-cart">${res.inCart}</span> 
                <span class="fa fa-square led-grey" title="En panier"></span>&nbsp;
                <span class="sold">${res.sold}</span> 
                <span class="fa fa-square led-blue" title="Vendus"></span>&nbsp;
                <span class="returned">${res.returned}</span> 
                <span class="fa fa-square led-orange" title="Retournés"></span>&nbsp;
                <span class="lost">${res.lost}</span> 
                <span class="fa fa-square led-purple" title="Perdus"></span>&nbsp;
              `;
              const stockRow = document.getElementById('stock_' + stock_id);
              if (stockRow) {
                stockRow.style.transition = 'opacity 0.5s';
                stockRow.style.opacity = '0';
                setTimeout(() => {
                  stockRow.style.display = 'none';
                }, 500);
              }
              const stocksElement = document.querySelector(`#stocks_${article_id}`);
              stocksElement.innerHTML = stocks;
              stocksElement.style.display = 'block';
              new window.Biblys.Notification(`Exemplaire n° ${stock_id} supprimé !`, { type: 'info' });
            }
          });
      }
    });
  });
});
