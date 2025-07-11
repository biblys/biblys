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

import Notification from '../notification';

const formatDate = (date, format) => {
  if (format === 'short') {
    const day = date
        .getDate()
        .toString()
        .padStart(2, '0'),
      month = (date.getMonth() + 1).toString().padStart(2, '0');
    return `<span title="${date.toLocaleString()}">${day}/${month}</span>`;
  }
};

const diffDate = (date1, date2) => {
  const coefficient = 1000 * 60 * 60 * 24;
  return Math.floor((date1 - date2) / coefficient);
};

const parseMysqlDate = string => {
  const t = string.split(/[- :]/);
  return new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3], t[4], t[5]));
};

export default class Order {

  /**
   * @param order_data object
   * @param order_data.id int
   * @param order_data.url string
   * @param order_data.customer string
   * @param order_data.amount string
   * @param order_data.total string
   * @param order_data.created string
   * @param order_data.payment_mode string|null
   * @param order_data.payment_date string|null
   * @param order_data.shipping_mode string
   * @param order_data.shipping_date string|null
   * @param order_data.shipping_amount string
   * @param order_data.followup_date string|null
   * @param order_data.cancel_date string|null
   */
  constructor(order_data) {
    const self = this;

    self.data = order_data;

    self.getRow = function() {
      let shippedButton =
        '<button title="Marquer comme expédiée" data-action="shipped" class="btn btn-sm btn-success"><i class="fa-solid fa-box"></i></button>';
      const payedButton = `
        <div class="dropdown">
          <button class="btn btn-sm btn-success dropdown-toggle" title="Marquée comme payée" type="button" data-toggle="dropdown" aria-expanded="false">
            <span class="fa-regular fa-money-bill-1"></span>
          </button>
          <div class="dropdown-menu">
            <a class="dropdown-item pointer" data-action="payed" data-mode="card">Carte bancaire</a>
            <a class="dropdown-item pointer" data-action="payed" data-mode="cheque">Chèque</a>
            <a class="dropdown-item pointer" data-action="payed" data-mode="cash">Espèces</a>
            <a class="dropdown-item pointer" data-action="payed" data-mode="paypal">Paypal</a>
            <a class="dropdown-item pointer" data-action="payed" data-mode="payplug">Payplug</a>
            <a class="dropdown-item pointer" data-action="payed" data-mode="transfer">Virement</a>
            <a class="dropdown-item pointer" data-action="payed" data-mode="exchange">Échange</a>
          </div>
        </div>`;
      let followupButton = '';

      if (self.pick) {
        shippedButton =
          '<button title="Marquer comme à dispo. en magasin" data-action="shipped" class="btn btn-sm btn-success"><i class="fa fa-shopping-bag"></i></button>';
      }

      if (self.overdued) {
        followupButton =
          ' <button title="Relancer" data-action="followup" class="btn btn-sm btn-warning"><i class="fa fa-warning"></i></button>';
      }

      // Création de la ligne (row) en DOM natif
      const row = document.createElement('tr');
      row.id = 'order_' + self.data.id;
      row.className = 'text-center ' + self.class;

      row.innerHTML =
        `<td title="${self.tooltip}" class="va-middle"><i class="fa-solid ${self.icon}"></i></td>
          <td><a href="/order/${self.data.url}">${self.data.id}</a></td>
          <td>${formatDate(self.created_at, 'short')}</td>
          <td class="text-left customer">${self.data.customer}</td>
          <td class="text-right">${self.data.total}</td>
          <td><a href="/invoice/${self.data.url}" class="btn btn-sm btn-light" title="Imprimer la facture" ><i class="fa fa-print"></i></a></td>
          <td>${self.data.payment_mode ? `<img src="/assets/icons/payment_${self.data.payment_mode}.png" alt="${self.data.payment_mode}" title="${self.data.payment_mode}" width=20 />` : ''}</td>
          <td>${self.payed ? formatDate(self.payed_at, 'short') : '<div class="btn-group">' + payedButton + followupButton + '</div>'}</td>
          <td>${self.shipped ? formatDate(self.shipped_at, 'short') : shippedButton}</td>
          <td><button title="Annuler la commande" data-action="cancel" class="btn btn-sm btn-danger"><i class="fa fa-trash-can"></i></button></td>`;

      // Ajout des événements sur les boutons d'action
      row.addEventListener('click', function(event) {
        const target = event.target.closest('[data-action]');
        if (!target) return;

        const action = target.getAttribute('data-action');
        const icon = target.querySelector('i');
        const icon_class = icon ? icon.className : '';
        let payment_mode = null,
          tracking_number = null;
        const status_filter_elem = document.getElementById('order_status');
        const status_filter = status_filter_elem ? status_filter_elem.value : null;

        // Confirmation avant annulation
        if (action === 'cancel') {
          const confirmation = confirm(
            'Voulez-vous vraiment annuler la commande n° ' +
            self.data.id +
            ' de ' +
            self.data.customer +
            ' ?'
          );
          if (!confirmation) {
            return false;
          }
        }

        // Loading state button
        if (icon) icon.className = 'fa fa-spinner fa-spin';

        // Demande du numéro de suivi
        const shippingModeUsesTracking = ['colissimo', 'mondial-relay'].includes(self.data.shipping_mode);
        if (shippingModeUsesTracking && action === 'shipped') {
          tracking_number = window.prompt('Numéro de suivi ?');
          if (tracking_number === null) {
            new Notification(
              'La commande n\'a pas été marquée comme expédiée.', { type: 'warning' });
            // Reset button state
            if (icon) icon.className = icon_class;
            return;
          }
        }

        if (action === 'payed') {
          payment_mode = target.getAttribute('data-mode');
        }

        fetch('/admin/orders/' + self.data.id + '/' + action, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({
            payment_mode: payment_mode,
            tracking_number: tracking_number
          })
        })
          .then(response => response.json())
          /**
           * @param data.notice string
           */
          .then(data => {
            // Reset button state
            if (icon) icon.className = icon_class;

            // Si erreur
            if (data.error) {
              window._alert(data.error);
              return;
            }

            // Mise à jour de l'objet commande
            self.data = data.order;
            self.updateStatus();

            // Mise à jour de la ligne
            const new_row = self.getRow();
            const old_row = document.getElementById('order_' + self.data.id);
            if (old_row && old_row.parentNode) {
              old_row.parentNode.replaceChild(new_row, old_row);
            }
            const buttons = document.getElementById('buttons_' + self.data.id);
            if (buttons && buttons.parentNode) {
              buttons.parentNode.removeChild(buttons);
            }

            // Notification
            if (data.notice) {
              new Notification(data.notice, { type: 'success' });
            }

            // Si la commande ne doit plus être affichée, masquer après un délai
            if (
              (status_filter === '1' && action === 'payed') ||
              (status_filter === '2' && action === 'shipped') ||
              (status_filter === '3' && action === 'shipped') ||
              action === 'cancel'
            ) {
              setTimeout(function() {
                const rowToHide = document.getElementById('order_' + self.data.id);
                if (rowToHide) rowToHide.style.display = 'none';
              }, 1000);
            }

            // Tooltipster non natif, à remplacer si besoin
            // $('[title]').tooltipster();
          })
          .catch(error => {
            // Reset button state
            if (icon) icon.className = icon_class;
            if (error && error.response && error.response.json) {
              error.response.json().then(json => {
                window._alert(json.error.message);
              });
            } else {
              window._alert('Erreur réseau');
            }
          });
      });

      return row;
    };

    self.updateStatus = function() {
      self.payed = true;
      self.shipped = true;
      self.overdued = false;
      self.canceled = false;
      self.icon = '';
      self.class = '';
      self.created_at = parseMysqlDate(self.data.created);
      self.shipped_at = null;
      self.payed_at = null;

      // Order canceled
      if (self.data.cancel_date !== null) {
        self.canceled = true;
        self.canceled_at = parseMysqlDate(self.data.cancel_date);
        self.icon = 'fa-trash-can';
        self.class = 'canceled';
        self.tooltip = 'Annulée';
        self.shipped = false;
        self.payed = false;
        return;
      }

      // Order unshipped
      if (self.data.shipping_date === null) {
        self.shipped = false;
        self.icon = 'fa-box';
        self.class = ' table-success';
        self.tooltip = 'À expédier';

        // Order to pick
        if (self.data.shipping_mode === 'magasin') {
          self.icon = 'fa-shopping-bag';
          self.tooltip = 'À mettre à dispo. en magasin';
          self.pick = true;
        }
      } else {
        self.shipped_at = parseMysqlDate(self.data.shipping_date);
      }

      if (self.data.payment_date === null) {
        self.payed = false;
        self.icon = 'fa-money';
        self.class = ' table-warning';
        self.tooltip = 'À payer';
      } else {
        self.payed_at = parseMysqlDate(self.data.payment_date);
      }

      // Order needs follow_up action
      const elapsed_since_payment = diffDate(new Date(), self.created_at);
      if (!self.payed && elapsed_since_payment > 4) {
        self.overdued = true;
        self.icon = 'fa-warning';
        self.class = ' table-danger';
        self.tooltip = 'À relancer';

        // Already sent followup
        if (self.data.followup_date) {
          self.followed_up_at = parseMysqlDate(self.data.followup_date);
          const elapsed_since_followup = diffDate(new Date(), self.followed_up_at);
          self.overdued = false;
          self.icon = 'fa-clock';
          self.class = '';
          self.tooltip = 'Relancée il y a ' + elapsed_since_followup + ' jours';
        }
      }
    };

    self.updateStatus();
  }
}