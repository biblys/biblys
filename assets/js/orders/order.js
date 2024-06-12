import $ from 'jquery';

import Notification from '../notification';

const formatDate = (date, format) => {
  if (format === 'short') {
    var day = date
        .getDate()
        .toString()
        .padStart(2, '0'),
      month = (date.getMonth() + 1).toString().padStart(2, 0);
    return `<span title="${date.toLocaleString()}">${day}/${month}</span>`;
  }
};

const diffDate = (date1, date2) => {
  var coeff = 1000 * 60 * 60 * 24;
  return Math.floor((date1 - date2) / coeff);
};

const parseMysqlDate = string => {
  var t = string.split(/[- :]/);
  var date = new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3], t[4], t[5]));
  return date;
};

export default class Order {
  constructor(order_data) {
    var self = this;

    self.data = order_data;

    self.getRow = function() {
      var shippedButton =
          '<button title="Marquer comme expédiée" data-action="shipped" class="btn btn-xs btn-success"><i class="fa fa-dropbox"></i></button>',
        payedButton =
          '<div class="btn-group">' +
          '<button title="Marquer comme payée" type="button" class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
          '<i class="fa fa-money"></i>' +
          '</button>' +
          '<ul class="dropdown-menu">' +
          '<li><a class="pointer" data-action="payed" data-mode="card">Carte bancaire</a></li>' +
          '<li><a class="pointer" data-action="payed" data-mode="cheque">Chèque</a></li>' +
          '<li><a class="pointer" data-action="payed" data-mode="cash">Espèces</a></li>' +
          '<li><a class="pointer" data-action="payed" data-mode="paypal">Paypal</a></li>' +
          '<li><a class="pointer" data-action="payed" data-mode="payplug">Payplug</a></li>' +
          '<li><a class="pointer" data-action="payed" data-mode="transfer">Virement</a></li>' +
          '</ul>' +
          '</div>',
        followupButton = '';

      if (self.pick) {
        shippedButton =
          '<button title="Marquer comme à dispo. en magasin" data-action="shipped" class="btn btn-xs btn-success"><i class="fa fa-shopping-bag"></i></button>';
      }

      if (self.overdued) {
        followupButton =
          ' <button title="Relancer" data-action="followup" class="btn btn-xs btn-warning"><i class="fa fa-warning"></i></button>';
      }

      // Render row
      var row = $(
        '<tr id="order_' +
          self.data.id +
          '" class="text-center ' +
          self.class +
          '">' +
          '<td title="' +
          self.tooltip +
          '" class="va-middle"><i class="fa ' +
          self.icon +
          '"></i></td>' +
          '<td><a href="/order/' +
          self.data.url +
          '">' +
          self.data.id +
          '</a>' +
          '<td>' +
          formatDate(self.created_at, 'short') +
          '</td>' +
          '<td class="text-left customer">' +
          self.data.customer +
          '</td>' +
          '<td class="text-right">' +
          self.data.total +
          '</td>' +
          '<td><a href="/invoice/' +
          self.data.url +
          '" class="btn btn-xs btn-default" title="Imprimer la facture" ><i class="fa fa-print"></i></a></td>' +
          '<td>' +
          (self.data.payment_mode
            ? '<img src="/assets/icons/payment_' +
              self.data.payment_mode +
              '.png" alt="' +
              self.data.payment_mode +
              '" title="' +
              self.data.payment_mode +
              '" width=20 />'
            : '') +
          '</td>' +
          '<td>' +
          (self.payed ? formatDate(self.payed_at, 'short') : payedButton + followupButton) +
          '</td>' +
          '<td>' +
          (self.shipped ? formatDate(self.shipped_at, 'short') : shippedButton) +
          '</td>' +
          '<td><button title="Annuler la commande" data-action="cancel" class="btn btn-xs btn-danger"><i class="fa fa-trash-o"></i></button></td>' +
          '</tr>'
      );

      // Add actions events
      row.on('click', '[data-action]', function() {
        var action = $(this).data('action'),
          icon = $(this).find('i'),
          icon_class = icon.attr('class'),
          payment_mode = null,
          tracking_number = null,
          status_filter = $('#order_status').val();

        // Confirmation before order cancelation
        if (action == 'cancel') {
          var confirmation = confirm(
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
        icon.attr('class', 'fa fa-spinner fa-spin');

        // Ask for tracking number
        if (self.data.shipping_mode == 'suivi' && action == 'shipped') {
          tracking_number = prompt('Numéro de suivi ?');
        }

        // Ask for payment mode
        if (action == 'payed') {
          payment_mode = $(this).data('mode');
        }

        $.ajax({
          type: 'POST',
          url: '/admin/orders/' + self.data.id + '/' + action,
          data: {
            payment_mode: payment_mode,
            tracking_number: tracking_number
          },
          dataType: 'json',
          success: function(data) {
            // Reset button state
            icon.attr('class', icon_class);

            // If error
            if (data.error) {
              window._alert(data.error);
              return;
            }

            // Update order object
            self.data = data.order;
            self.updateStatus();

            // Update row
            var new_row = self.getRow();
            $('#buttons_' + self.data.id).remove();
            $('#order_' + self.data.id).replaceWith(new_row);

            // Notify
            if (data.notice) {
              new Notification(data.notice, { type: 'success' });
            }

            // If order should not be shown, hide after a few seconds
            if (
              (status_filter === '1' && action === 'payed') ||
              (status_filter === '2' && action === 'shipped') ||
              (status_filter === '3' && action === 'shipped') ||
              action === 'cancel'
            ) {
              setTimeout(function() {
                $('#order_' + self.data.id).fadeOut();
              }, 1000);
            }

            $('[title]').tooltipster();
          },

          error: function(jqXHR) {
            // Reset button state
            icon.attr('class', icon_class);

            window._alert(jqXHR.responseJSON.error);
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
        self.icon = 'fa-trash-o';
        self.class = 'canceled';
        self.tooltip = 'Annulée';
        self.shipped = false;
        self.payed = false;
        return;
      }

      // Order not shipped
      if (self.data.shipping_date === null) {
        self.shipped = false;
        self.icon = 'fa-dropbox';
        self.class = ' bg-success';
        self.tooltip = 'À expédier';

        // Order to pick
        if (self.data.shipping_mode == 'magasin') {
          self.icon = 'fa-shopping-bag';
          self.tooltip = 'À mettre à dispo. en magasin';
          self.pick = true;
        }
      } else {
        self.shipped_at = parseMysqlDate(self.data.shipping_date);
      }

      // Order not payed
      if (self.data.payment_date === null) {
        self.payed = false;
        self.icon = 'fa-money';
        self.class = ' bg-warning';
        self.tooltip = 'À payer';
      } else {
        self.payed_at = parseMysqlDate(self.data.payment_date);
      }

      // Order needs follow_up action
      var elapsed_since_payment = diffDate(new Date(), self.created_at);
      if (!self.payed && elapsed_since_payment > 4) {
        self.overdued = true;
        self.icon = 'fa-warning';
        self.class = ' bg-danger';
        self.tooltip = 'À relancer';

        // Already sent followup
        if (self.data.followup_date) {
          self.followed_up_at = parseMysqlDate(self.data.followup_date);
          var elapsed_since_followup = diffDate(new Date(), self.followed_up_at);
          self.overdued = false;
          self.icon = 'fa-clock-o';
          self.class = '';
          self.tooltip = 'Relancée il y a ' + elapsed_since_followup + ' jours';
        }
      }
    };

    self.updateStatus();
  }
}
