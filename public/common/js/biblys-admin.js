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

/* global $, file_size, _alert, notify, price, currency, Biblys */

function reloadAdminEvents() {
  /* FORM CHECK */

  $('form[data-uploading]').submit(function() {
    $(this).attr('data-submitted', 1);

    if ($(this).attr('data-uploading') > 0) {
      window._alert(
        'Veuillez patienter, des fichiers sont toujours en cours de chargement. Le formulaire sera envoyé automatiquement dès la fin du téléchargement.'
      );
      return false;
    } else return true;
  });

  /* DROPZONE */

  // Do not redirect on page drop
  document.ondragover = function() {
    return false;
  };
  document.ondrop = function(e) {
    e.preventDefault();
  };

  // Dropzone cosmetics
  $('.dropzone')
    .on('dragenter', function() {
      $(this).addClass('hover');
    })
    .on('dragleave', function() {
      $(this).removeClass('hover');
    })
    .on('drop', function() {
      $(this).removeClass('hover');
    });

  /* IMAGES */

  var imageUpload = document.querySelector('.imageUpload');
  if (imageUpload) {
    imageUpload.ondrop = function(e) {
      e.preventDefault();

      for (var f in e.dataTransfer.files) {
        var file = e.dataTransfer.files[f];
        if (file instanceof File) {
          if (!file.type.match(/^image\/.*/)) {
            window._alert(file.name + ' n\'est pas une image !');
          } else {
            var reader = new FileReader();
            reader.file = file;

            reader.onload = function() {
              var image_id = 'new_' + Math.round(Math.random() * 10000),
                new_line =
                  '<tr id="image_' +
                  image_id +
                  '">' +
                  '<td class="center"><img src="' +
                  this.result +
                  '" style="max-width: 100px; max-height: 50px;"></td>' +
                  '<td></td>' +
                  '<td></td>' +
                  '<td class="center">' +
                  this.file.type +
                  '</td>' +
                  '<td class="right size">0</td>' +
                  '<td style="min-width: 100px;" colspan=2><progress value="0" max="100"></progress> </td>' +
                  '</tr>';
              $('#images_list tbody').append(new_line);

              // Upload image
              var xhr = new XMLHttpRequest();
              xhr.open('POST', '/x/adm_images');
              xhr.upload.onprogress = function(e) {
                $('#image_' + image_id + ' .size').html(file_size(e.loaded));
                $('#image_' + image_id + ' progress').attr('value', e.loaded);
                $('#image_' + image_id + ' progress').attr('max', e.total);
              };
              xhr.onloadstart = function() {
                var uploading = parseInt($('form[data-uploading]').attr('data-uploading'));
                $('form[data-uploading]').attr('data-uploading', uploading + 1);
              };
              xhr.onloadend = function() {
                var uploading = parseInt($('form[data-uploading]').attr('data-uploading')),
                  submitted = $('form[data-uploading]').attr('data-submitted');
                $('form[data-uploading]').attr('data-uploading', uploading - 1);
                if (uploading - 1 == 0 && submitted == 1) $('form[data-uploading]').submit();
              };
              xhr.onload = function() {
                var r = JSON.parse(xhr.response);
                if (r.error) {
                  window._alert('Erreur : ' + r.error);
                } else if (r.success) {
                  $('#image_' + image_id).replaceWith(r.new_line);
                  window.notify(r.success);
                  reloadAdminEvents();
                }
              };

              var form = new FormData();
              form.append('image', this.file);
              form.append('action', 'upload');
              form.append('link_to', $('.imageUpload').attr('data-link_to'));
              form.append('link_to_id', $('.imageUpload').attr('data-link_to_id'));
              form.append('image_id', image_id);
              xhr.send(form);
            };

            reader.readAsDataURL(file);
          }
        }
      }
    };
  }

  // Delete image
  $('.image [data-delete_image].event')
    .click(function() {
      var image_id = $(this).attr('data-delete_image');
      $.ajax({
        type: 'POST',
        url: '/x/adm_images',
        data: {
          image_id: '' + image_id + '',
          action: 'delete'
        },
        dataType: 'json',
        success: function(r) {
          if (r.error) {
            _alert('Erreur : ' + r.error);
          } else if (r.success) {
            $('#image_' + image_id).hide();
            notify(r.success);
          }
        }
      });
    })
    .removeClass('event');

  // Update image data
  $('.image [data-update_image].event')
    .click(function() {
      var id = $(this).attr('data-update_image');
      $.ajax({
        type: 'POST',
        url: '/x/adm_images',
        data: {
          image_id: '' + id + '',
          action: 'update',
          image_legend: '' + $('#image_' + id + ' .image_legend').text() + '',
          image_nature: '' + $('#image_' + id + ' .image_nature option:selected').val() + ''
        },
        dataType: 'json',
        success: function(r) {
          if (r.error) {
            _alert('Erreur : ' + r.error);
          } else if (r.success) {
            notify(r.success);
          }
        }
      });
    })
    .removeClass('event');

  /* ARTICLE / DOWNLOADABLE FILES */

  // Delete file
  $('.dlfile [data-delete_file].event')
    .click(function() {
      var file_id = $(this).attr('data-delete_file');

      $.ajax({
        type: 'POST',
        url: '/pages/adm_article_files',
        data: {
          file_id: '' + file_id + '',
          action: 'delete'
        },
        dataType: 'json',
        success: function(r) {
          if (r.error) {
            _alert('Erreur : ' + r.error.message);
          } else if (r.success) {
            $('#dlfile_' + file_id).hide();
            notify(r.success);
          }
        }
      });
    })
    .removeClass('event');

  // Update file data
  $('.dlfile [data-update_file].event')
    .click(function() {
      var id = $(this).attr('data-update_file'),
        title = $('#dlfile_' + id + ' .file_title').text(),
        access = $('#dlfile_' + id + ' .file_access option:selected').val(),
        version = $('#dlfile_' + id + ' .file_version').text(),
        ean = $('#dlfile_' + id + ' .file_ean').text();

      $.ajax({
        type: 'POST',
        url: '/pages/adm_article_files',
        data: {
          file_id: '' + id + '',
          file_title: '' + title + '',
          file_access: '' + access + '',
          file_version: '' + version + '',
          file_ean: '' + ean + '',
          action: 'update'
        },
        dataType: 'json',
        success: function(r) {
          if (r.error) {
            _alert('Erreur : ' + r.error.message);
          } else if (r.success) {
            notify(r.success);
          }
        }
      });
    })
    .removeClass('event');

  // Upload dlfile
  function dlfile_upload(file, file_id) {
    var reader = new FileReader();

    if (file_id == 'new') {
      file_id = 'new_' + Math.round(Math.random() * 10000);
      var new_file = 1;
    }
    reader.onload = function() {
      // Show in dlfiles table
      var new_line =
        '<tr id="dlfile_' +
        file_id +
        '">' +
        '<td style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">' +
        file.name +
        '</td>' +
        '<td colspan=3></td>' +
        '<td>' +
        file.type +
        '</td>' +
        '<td class="right size">0</td>' +
        '<td class="right">0</td>' +
        '<td style="min-width: 100px;"><progress value="0" max="100"></progress></td>' +
        '</tr>';

      if (new_file) $('#dlfiles_list tbody').append(new_line);
      else $('#dlfile_' + file_id).replaceWith(new_line);

      // Upload file
      var xhr = new XMLHttpRequest();
      xhr.open('POST', '/pages/adm_article_files');
      xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
      xhr.upload.onprogress = function(e) {
        $('#dlfile_' + file_id + ' .size').html(file_size(e.loaded));
        $('#dlfile_' + file_id + ' progress').attr('value', e.loaded);
        $('#dlfile_' + file_id + ' progress').attr('max', e.total);
      };
      xhr.onloadstart = function() {
        var uploading = parseInt($('form[data-uploading]').attr('data-uploading'));
        $('form[data-uploading]').attr('data-uploading', uploading + 1);
      };
      xhr.onloadend = function() {
        var uploading = parseInt($('form[data-uploading]').attr('data-uploading')),
          submitted = $('form[data-uploading]').attr('data-submitted');
        $('form[data-uploading]').attr('data-uploading', uploading - 1);
        if (uploading - 1 == 0 && submitted == 1) {
          $('form[data-uploading]').submit();
        }
      };
      xhr.onload = function() {
        var r = JSON.parse(xhr.response);
        if (r.error) {
          _alert('Erreur : ' + r.error.message);
        } else if (r.success) {
          $('#dlfile_' + file_id).replaceWith(r.new_line);
          notify(r.success);
          reloadAdminEvents();
        }
      };

      var form = new FormData();
      form.append('file', file);
      form.append('action', 'upload');
      form.append('file_id', file_id);
      form.append('article_id', $('#article_id').val());
      xhr.send(form);

      $('#dlfile_upload').val('');
    };

    reader.readAsText(file);
  }

  // File drag and drop event
  var fileUpload = document.querySelector('.fileUpload');
  if (fileUpload) {
    fileUpload.ondrop = function(e) {
      e.preventDefault();
      for (var i = 0, c = e.dataTransfer.files.length; i < c; i++) {
        dlfile_upload(e.dataTransfer.files[i], 'new');
      }
    };
  }

  // File input event
  $('.dlfile_upload.event')
    .change(function() {
      for (var i = 0, c = $(this)[0].files.length; i < c; i++) {
        dlfile_upload($(this)[0].files[i], $(this).data('file_id'));
      }
    })
    .removeClass('event');

  /* CHECKOUT */

  // Enregistrer la vente
  $('#checkout.event')
    .submit(function(event) {
      event.preventDefault();

      var go = 1;

      if ($('#cart_topay').attr('data-value') != 0) {
        go = 0;
        if (
          confirm(
            'Il reste ' +
              price($('#cart_topay').attr('data-value')) +
              ' ' +
              window.site.currency +
              ' à payer, enregistrer quand même la vente ?'
          )
        )
          go = 1;
      } else if ($('#cart_total').attr('data-value') == 0) {
        go = 0;
        _alert('Impossible d\'enregistrer la vente : le panier est vide.');
      }

      if (go == 1) {
        var data = {
          validate: 1,
          seller_id: $('#seller_id').val(),
          customer_id: $('#customer_id').val(),
          cart_cash: $('#cart_cash').val() * 100,
          cart_cheque: $('#cart_cheque').val() * 100,
          cart_card: $('#cart_card').val() * 100,
          cart_topay: $('#cart_topay').attr('data-value'),
          cart_togive: $('#cart_togive').attr('data-value')
        };
        $.post({
          url: '/pages/adm_checkout?cart_id=' + $('#cart_id').val(),
          data: data,
          dataType: 'json'
        })
          .success(function(res) {
            window.location.href = '/pages/adm_checkout?order_created=' + res.created_order;
          })
          .fail(function(res) {
            var json = res.responseJSON;
            _alert(json.error);
          });
      }
    })
    .removeClass('event');

  // Update cart
  function update_cart() {
    var cart_count = 0,
      cart_total = 0,
      cart_payment = 0,
      cart_topay = 0;

    // Calculate & update total
    $('.stock_selling_price').each(function() {
      cart_count++;
      cart_total += parseInt($(this).attr('data-price'));
    });
    $('#cart_count').text(cart_count);
    if (cart_count > 1) $('#cart_count_s').text('s');
    else $('#cart_count_s').text('');
    $('#cart_total')
      .attr('data-value', cart_total)
      .html(currency(cart_total / 100));

    // Calculate payment
    $('.cart_payment').each(function() {
      var payment = Math.round($(this).val() * 100);
      if (payment.isNan) payment = 0;
      cart_payment += payment;
    });

    // Calculate & update amount to pay & to give back
    cart_topay = cart_total - cart_payment;
    var cart_togive = cart_payment - cart_total;
    if (cart_topay < 0) cart_topay = 0;
    if (cart_togive < 0) cart_togive = 0;

    $('#cart_topay')
      .attr('data-value', cart_topay)
      .html(currency(cart_topay / 100));
    $('#cart_togive')
      .attr('data-value', cart_togive)
      .html(currency(cart_togive / 100));
  }

  // Events updating cart
  $('#cart_cash, #cart_cheque, #cart_card').on('keyup click', function() {
    update_cart();
  });

  // Ajouter un exemplaire au panier
  async function add_to_cart(cartId, stockId) {
    const response = await fetch(`/pages/adm_checkout?cart_id=${cartId}&add=stock&id=${stockId}&_=${Date.now()}`, {
      headers: {
        'Accept': 'application/json',
      }
    });
    const json = await response.json();

    $('#checkout_add_input').removeClass('loading');

    if (json.error) {
      window._alert(json.error);
    }

    $('#checkout_add_results').html('');
    $('#checkout_add_input').val('');
    $('#checkout_cart tbody').prepend(json.line);
    notify(json.success);
    reloadAdminEvents();
    update_cart();
  }

  // Supprimer un article
  $('[data-remove_from_cart].event')
    .click(function() {
      var stock_id = $(this).attr('data-remove_from_cart');
      $.get(
        '/pages/adm_checkout?cart_id=' + $('#cart_id').val() + '&remove_stock=' + stock_id,
        {},
        function(r) {
          if (r.error) _alert('Erreur : ' + r.error);
          else {
            $('#stock_' + stock_id).remove();
            notify(r.success);
            update_cart();
          }
        },
        'json'
      );
    })
    .removeClass('event');

  // Rechercher un article
  function checkout_lookup(event) {
    var input = $('#checkout_add_input').val();
    $('#checkout_add_results').html('');

    if (input.length >= 3) {
      $('#checkout_add_input').addClass('loading');
      $.ajax({
        type: 'GET',
        url: '/stock/search/' + input + '?_=' + Date.now(),
        dataType: 'json',
        success: function(r) {
          $('#checkout_add_input').removeClass('loading');
          if (r.error) {
            _alert('Erreur : ' + r.error);
          } else {
            if (r.length == 1 && event == 'submit') {
              add_to_cart($('#cart_id').val(), r[0].id);
            } else {
              var results = r.length,
                div = null;
              for (var i = 0; i < results; i++) {
                var color = 'orange',
                  selected = '';
                if (r[i].condition == 'Neuf') color = 'green';

                div =
                  '<tr class="pointer event' +
                  selected +
                  '" data-add_to_checkout=' +
                  r[i].id +
                  '>' +
                  '<td>' +
                  r[i].title +
                  '</td>' +
                  '<td>' +
                  r[i].publisher +
                  '</td>' +
                  '<td>' +
                  r[i].count +
                  '</td>' +
                  '<td class=' +
                  color +
                  '>' +
                  r[i].condition +
                  '</td>' +
                  '<td class="right">' +
                  r[i].price +
                  '</td>' +
                  '</tr>' +
                  div;
              }
              $('#checkout_add_results').html(div);
              reloadAdminEvents();
            }
          }
        }
      });
    }
  }

  // Rechercher un article (touche entrée)
  $('#checkout_add.event')
    .submit(function(e) {
      e.preventDefault();
      checkout_lookup('submit');
    })
    .removeClass('event');

  // Rechercher un article (entrée texte)
  var timer = 0;
  $('#checkout_add_input.event')
    .keyup(function() {
      clearTimeout(timer);
      timer = setTimeout(function() {
        checkout_lookup('keyup');
      }, 500);
    })
    .removeClass('event');

  // Ajouter un article au panier
  $('[data-add_to_checkout].event')
    .click(function() {
      var stock_id = $(this).attr('data-add_to_checkout');
      add_to_cart($('#cart_id').val(), stock_id);
    })
    .removeClass('event');

  // Sauvegarder le titre du panier
  $('#cart_title.event')
    .on('blur keydown', function(e) {
      // sauvegarder le nouveau titre

      // Si validation par la touche entrée
      if (e.type == 'keydown') {
        if (e.keyCode == 13) {
          e.preventDefault();
          $(this).blur();
        }
        return true;
      }

      $.post(
        '/pages/adm_checkout?cart_id=' + $('#cart_id').val(),
        {
          set_title: '' + $(this).text() + ''
        },
        function(res) {
          if (res.error) _alert(res.error);
          else notify(res.success);
        }
      );

      return true;
    })
    .removeClass('event');

  // Rechercher un client existant
  $('#customer.event')
    .autocomplete({
      source: '/x/adm_customers',
      minLength: 3,
      delay: 250,
      select: function(event, ui) {
        if (ui.item.create == '1') {
          // Creer un nouveau client
          $('#customer_first_name')
            .val(ui.item.value)
            .focus();
          $('#createCustomer').dialog({
            title: 'Créer un nouveau client',
            modal: true,
            width: 500
          });
        } else {
          // Selectionner un client existant
          selectCustomer(ui.item.id, ui.item.label, ui.item.newsletter);
        }
      }
      // Annuler la sélection du client
    })
    .click(function() {
      $.post(
        '/pages/adm_checkout?cart_id=' + $('#cart_id').val(),
        {
          set_customer: ''
        },
        function(res) {
          if (res.error) _alert(res.error);
          else {
            $('#customer')
              .removeClass('pointer')
              .removeAttr('readonly')
              .val('');
            $('#customer_mailing').removeAttr('checked');
            $('#newsletter').hide();
            $('#customer_id').val('');
            //notify(res.success);
          }
        }
      );
    })
    .removeClass('event');

  // Sélectionner un client
  function selectCustomer(id, name, newsletter) {
    if (newsletter == 1) $('#customer_mailing').attr('checked', 'checked');
    $.post(
      '/pages/adm_checkout?cart_id=' + $('#cart_id').val(),
      {
        set_customer: '' + id + ''
      },
      function(res) {
        if (res.error) _alert(res.error);
        else {
          $('#customer')
            .addClass('pointer')
            .attr('readonly', 'readonly')
            .val(name);
          $('#customer_id').val(id);
          $('#newsletter').show();
          $('#article').focus();
          notify(res.success);
        }
      }
    );
  }

  // Créer un client
  $('#createCustomer').submit(function(e) {
    e.preventDefault();
    $.post(
      '/pages/adm_customer',
      {
        customer_last_name: '' + $('#customer_last_name').val() + '',
        customer_first_name: '' + $('#customer_first_name').val() + '',
        customer_email: '' + $('#customer_email').val() + '',
        customer_phone: '' + $('#customer_phone').val() + '',
        customer_newsletter: '' + $('#new_customer_newsletter:checked').val() + ''
      },
      function(res) {
        if (res.error) _alert(res.error);
        else {
          $('#createCustomer').dialog('close');
          selectCustomer(res.id, res.name, 0);
          notify('Le client ' + res.name + ' a bien été créé.');
        }
      }
    );
  });

  document.addEventListener('keyup', function(event) {
    const { ctrlKey, shiftKey, key } = event;
    if (ctrlKey && shiftKey && key === 'A') {
      window.location.href = '/admin/stock-items/new';
    }
  });
}

$(function() {
  reloadAdminEvents();
});

// Admin bar

Biblys.AdminBar = function() {
  this.init();

  // Listen for localStorage updates
  window.addEventListener(
    'storage',
    function(event) {
      if (event.key === 'shortcuts') {
        // this.loadShortcutsFromCache();
      }
    }.bind(this)
  );
};

Biblys.AdminBar.prototype = {
  init: function() {
    this.shortcutsElement = document.createElement('span');
    this.shortcutsElement.classList.add('admin-bar-shortcuts');

    const overallMenuAdminSection = document.querySelector(".OverallMenu__admin-section");
    overallMenuAdminSection.appendChild(this.shortcutsElement);

    this.loadShortcutsFromCache();
  },

  /**
   * Load shortcuts from cache (or fallback to server)
   * @return {[type]} [description]
   */
  loadShortcutsFromCache: function() {
    // If nothing in cache, load from server
    if (typeof window.localStorage.shortcuts === 'undefined') {
      this.loadShortcutsFromServer();
      return;
    }

    // If cache is older than 1 hour, refresh it from server
    var oneHourAgo = new Date().getTime() - 3600000;
    if (window.localStorage.shortcutsUpdated < oneHourAgo) {
      this.loadShortcutsFromServer();
      return;
    }

    const shortcutsFromCache = JSON.parse(window.localStorage.shortcuts);
    if (!Array.isArray(shortcutsFromCache)) {
      this.loadShortcutsFromServer();
      return;
    }

    this.shortcuts = shortcutsFromCache;
    this.displayShortcuts();
  },

  /**
   * Remove shortcuts from localStorage
   */
  clearShortcutsCache: function() {
    window.localStorage.removeItem('shortcuts');
    window.localStorage.removeItem('shortcutsUpdated');
  },

  /**
   * Load shorcuts from server
   */
  loadShortcutsFromServer: function() {
    fetch('/admin/shortcuts', {
      credentials: 'same-origin',
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
      .then(function(response) {
        return response.json();
      })
      .then(
        function(json) {
          this.shortcuts = json;
          this.updateShortcutsCache(json);
          this.displayShortcuts();
        }.bind(this)
      )
      .catch(function(ex) {
        throw ('parsing failed', ex);
      });
  },

  /**
   * Update shortcuts in cache loaded from server
   * @param {Object} shortcuts
   */
  updateShortcutsCache: function(shortcuts) {
    window.localStorage.shortcuts = JSON.stringify(shortcuts);
    window.localStorage.shortcutsUpdated = new Date().getTime();
  },

  loadPreviewFromInput: function(shortcuts) {
    this.shortcuts = shortcuts;
    this.displayShortcuts();
  },

  /**
   * Display shortcuts in admin bar
   */
  displayShortcuts: function() {
    // Reset shortcuts element before updating;
    this.shortcutsElement.innerHTML = '';

    if (this.shortcuts.length === 0) {
      this.displayShortcutsEmptyMessage();
      return;
    }

    var fragment = document.createDocumentFragment();
    this.shortcuts.forEach(
      function(shortcut) {
        var element = document.createElement('a');

        element.classList.add('OverallMenu__entry');
        if (shortcut.class) {
          element.classList.add(shortcut.class);
        }

        element.href = shortcut.url;
        element.innerHTML = `<span aria-label="${shortcut.name}" class="fa fa-${shortcut.icon}"></span>`;
        fragment.appendChild(element);

        if (shortcut.subscription) {
          window.biblys.subscriptions.add(element, shortcut.subscription);
        }
      }.bind(this)
    );
    this.shortcutsElement.appendChild(fragment);
  },

  displayShortcutsEmptyMessage: function() {
    var element = document.createElement('a');
    element.classList.add('OverallMenu__entry');
    element.href = '/admin/shortcuts';
    element.innerHTML = '<span class="fa fa-share"></span> Ajouter des raccourcis';
    this.shortcutsElement.appendChild(element);
  }
};

// Subscriptions to notifications

// Single Subscription

Biblys.Subscription = function(element, channel) {
  this.element = element;
  this.channel = channel;
};

Biblys.Subscription.prototype = {
  getBadge: function() {
    var badge = this.element.querySelector('.icon-badge');

    if (!badge) {
      badge = document.createElement('span');
      badge.classList.add('icon-badge');
      badge.classList.add('badge');
      badge.classList.add('badge-pill');
      badge.classList.add('badge-danger');
      this.element.appendChild(badge);
    }

    return badge;
  },

  updateElement: function(notification) {
    var badge = this.getBadge();

    if (notification == 0) {
      this.element.classList.remove('with-badge');
      badge.style.opacity = 0;
    } else {
      this.element.classList.add('with-badge');
      badge.style.opacity = 1;
    }

    // Cap notification at 99
    if (notification >= 100) {
      notification = '99';
    }

    badge.textContent = notification;
  }
};

// Subscription Manager

Biblys.Subscriptions = function() {
  this.subscriptions = [];
  this.notifications = this.getNotificationsFromCache();
  this.active = false;
  this.timer = null;

  this.init();
};

Biblys.Subscriptions.prototype = {
  init: function() {
    // Detect tab change and stop pinging server
    document.addEventListener(
      'visibilitychange',
      function() {
        if (document.hidden) {
          this.deactivate();
        } else {
          this.activate();
        }
      }.bind(this)
    );

    // Find elements to subscribe in current page
    var elements = document.querySelectorAll('[data-subscribe]');
    for (var i = 0, c = elements.length; i < c; i++) {
      this.add(elements[i], elements[i].dataset.subscribe);
    }

    this.activate();
  },

  add: function(element, channel) {
    var subscription = new Biblys.Subscription(element, channel);

    this.subscriptions.push(subscription);

    // Update element with cache data if available
    this.updateElements();
  },

  loadData: function() {
    var channels = this.getChannelsFromSubscriptions();

    // If no channel subscribed, don't pull
    if (channels.length == 0) {
      return;
    }

    fetch('/admin/notifications?subscriptions=' + channels.join(), {
      credentials: 'same-origin',
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
      .then(function(response) {
        return response.json();
      })
      .then(
        function(json) {
          this.notifications = json;
          this.updateNotificationsCache(json);
          this.updateElements();
        }.bind(this)
      )
      .catch(function(ex) {
        throw ('parsing failed', ex);
      });
  },

  /**
   * Save notifications in cache loaded from server
   * @param {Object} shortcuts
   */
  updateNotificationsCache: function(notifications) {
    window.localStorage.notifications = JSON.stringify(notifications);
    window.localStorage.notificationsUpdated = new Date().getTime();
  },

  /**
   * Get notifications from cache (localStorage)
   * @return {Object} notifications
   */
  getNotificationsFromCache: function() {
    // If nothing in cache, load from server
    if (typeof window.localStorage.notifications === 'undefined') {
      return {};
    }

    return JSON.parse(window.localStorage.notifications);
  },

  getChannelsFromSubscriptions: function() {
    var channels = this.subscriptions.map(function(subscription) {
      return subscription.channel;
    });

    // Remove duplicate channels
    channels = channels.filter(function(elem, index, self) {
      return index == self.indexOf(elem);
    });

    return channels;
  },

  /**
   * Loop through every subscription and update elements with known notifications
   */
  updateElements: function() {
    this.subscriptions.forEach(
      function(subscription) {
        var notification = this.getNotificationForChannel(subscription.channel);
        subscription.updateElement(notification);
      }.bind(this)
    );
  },

  /**
   * Returns known notification for given channel
   * @param  String channel
   * @return Int    notification
   */
  getNotificationForChannel: function(channel) {
    var notification = this.notifications[channel];
    return notification;
  },

  activate: function() {
    if (this.active) {
      return;
    }

    this.loadData();

    // Refresh every 5 seconds
    this.timer = window.setInterval(
      function() {
        this.loadData();
      }.bind(this),
      15000
    );

    this.active = true;
  },

  deactivate: function() {
    if (!this.active) {
      return;
    }

    window.clearInterval(this.timer);
    this.timer = null;
    this.active = false;
  }
};

document.addEventListener('DOMContentLoaded', function() {
  window.biblys.subscriptions = new Biblys.Subscriptions();
  window.biblys.adminBar = new Biblys.AdminBar();
});

window.biblys.hideAdminWelcomeMessage = function() {
  localStorage.hideAdminWelcomeMessage = 1;
  window.biblys.adminWelcomeMessage.remove();
};
