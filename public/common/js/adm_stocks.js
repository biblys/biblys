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

'use strict';

function fieldUpdate(stock_id, field, value) {
  $.ajax({
    url: '/x/adm_stock?action=update',
    method: 'POST',
    data: { stock_id, field, value },
    complete: function(jqXHR) {
      const res = jqXHR.responseJSON;
      if (res.error) {
        _alert(res.error);
      } else {
        if (field == 'stock_selling_price' || field == 'stock_purchase_price') {
          value = currency(value / 100);
        }
        $('#span-' + field + '-' + stock_id).html(value);
        $('#form-' + field + '-' + stock_id).hide();
        $('#span-' + field + '-' + stock_id).show();
        notify(
          `Le champ ${res.field_name} de l'exemplaire n° ${res.stock_id} (${res.article_title}) a été mis à jour.`
        );
      }
    }
  });
  return false;
}

document.addEventListener('DOMContentLoaded', function() {
  $('.changeBool').change(function() {
    var el = $(this),
      stock_id = el.data('stock_id'),
      field = el.data('field'),
      value = 0;
    if (el.is(':checked')) {
      value = 1;
    }
    fieldUpdate(stock_id, field, value);
  });

  $('.cell').click(function() {
    var field_id = $(this)
      .attr('id')
      .split('-');
    var field = field_id[1];
    var id = field_id[2];
    $('#span-' + field + '-' + id).hide();
    $('#form-' + field + '-' + id).show();
    $('#input-' + field + '-' + id).focus();
  });

  var forms = document.querySelectorAll('form.update');
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

  $('.deleteStock').click(function(event) {
    event.preventDefault();
    var stock_id = $(this).data('stock_id');
    var article_id = $(this).data('article_id');
    const isConfirmed = confirm("Voulez-vous vraiment SUPPRIMER l'exemplaire " + stock_id + ' ?');
    if (isConfirmed) {
      $.ajax({
        url: '/x/adm_stock?action=delete',
        method: 'POST',
        data: {
          stock_id,
          article_id,
        },
        complete: function(jqXHR) {
          var res = jqXHR.responseJSON;
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
            $('#stock_' + stock_id).fadeOut();
            const stocksElement = document.querySelector(`#stocks_${article_id}`);
            console.log(`stocks_${article_id}`, stocksElement);
            stocksElement.innerHTML = stocks;
            stocksElement.style.display = 'block';
            notify(`Exemplaire n° ${stock_id} supprimé !`);
          }
        }
      });
    }
  });
});
