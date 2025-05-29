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


window.biblys = {};
var Biblys = {};

function createElementFromHTML(htmlString) {
  const template = document.createElement('template');
  htmlString = htmlString.trim();
  template.innerHTML = htmlString;
  return template.content.firstChild;
}

/* NOTIFICATIONS */

/**
 * Displays a notification
 *
 * @param {string} text the notification's message
 * @param {object} options a object containing options
 *                          - type: a bootstrap alert class (default info)
 *                          - timeout: time in ms before notification disappears (default 2500)
 *                          - sticky: if true, notification won't disappear (default false)
 *                          - closeButton: if true, will display a close button
 *                          - loader: if true, will display an activity indicator
 *
 */
Biblys.Notification = function(text, options) {
  this.text = text;

  options = options || {};

  this.params = {
    type: options.type || 'info',
    timeout: options.timeout || 2500,
    sticky: options.sticky || false,
    closeButton: options.closeButton || false,
    loader: options.loader || false,
    sound: options.sound || false
  };

  this.init();
};

Biblys.Notification.prototype = {
  init: function() {
    var notifications = this.getNotificationsContainer(),
      notification = this.renderNotification();

    // Append notification to the container
    notifications.appendChild(notification);

    // Add close button
    if (this.params.closeButton) {
      this.addCloseButton();
    }

    // Add loader
    if (this.params.loader) {
      this.addLoader();
    }

    // Play sound
    if (this.params.sound) {
      this.playSound();
    }

    // Remove after timeout if not sticky
    if (!this.params.sticky) {
      window.setTimeout(
        function() {
          this.remove();
        }.bind(this),
        this.params.timeout
      );
    }
  },

  // Get the notifications container
  getNotificationsContainer: function() {
    var notifications = document.querySelector('.notifications');

    if (!notifications) {
      notifications = this.createNotificationsContainer();
    }

    return notifications;
  },

  // Create the notifications container
  createNotificationsContainer: function() {
    var notifications = document.createElement('div');
    notifications.classList.add('notifications');
    document.body.appendChild(notifications);
    return notifications;
  },

  // Render the notification element
  renderNotification: function() {
    this.element = document.createElement('div');
    this.element.classList.add('notification');
    this.element.classList.add('alert');
    this.element.classList.add('alert-' + this.params.type);
    this.element.innerHTML = this.text;

    return this.element;
  },

  addCloseButton: function() {
    var button = document.createElement('span');
    button.classList.add('fa');
    button.classList.add('fa-close');

    this.element.insertAdjacentElement('afterbegin', button);

    button.addEventListener(
      'click',
      function() {
        this.remove();
      }.bind(this)
    );
  },

  addLoader: function() {
    var loader = document.createElement('span');
    loader.classList.add('fa');
    loader.classList.add('fa-spinner');
    loader.classList.add('fa-spin');

    this.element.insertAdjacentElement('afterbegin', loader);
  },

  playSound: function() {
    var audio = document.createElement('audio');
    audio.style.display = 'none';
    audio.autoplay = true;
    audio.innerHTML =
      '<source src="/assets/sounds/' + this.params.sound + '.mp3" type="audio/mp3">';
    this.element.appendChild(audio);
  },

  remove: function() {
    this.element.style.opacity = 0;
    window.setTimeout(
      function() {
        this.element.parentNode.removeChild(this.element);
      }.bind(this),
      400
    );
  }
};

/* COLLAPSABLE FIELDSET */

Biblys.CollapsableFieldset = function(fieldset) {
  var collapsedDefault = fieldset.dataset.collapsed;
  this.collapsed = false;
  if (typeof collapsedDefault === 'undefined' || collapsedDefault === 'true') {
    this.collapsed = true;
  }

  this.legend = fieldset.querySelector('legend');

  this.element = fieldset.querySelector('.collapsable-element');

  if (!this.element) {
    throw new Error(
      'Collapsable fieldset must have a child element with a .collapsable-element class'
    );
  }

  this.init();
};

Biblys.CollapsableFieldset.prototype = {
  init: function() {
    this.legend.style.cursor = 'pointer';
    this.legend.addEventListener('click', this.toggle.bind(this));

    if (this.collapsed) {
      this.hide();
    } else {
      this.show();
    }
  },

  toggle: function() {
    if (this.collapsed) {
      this.show();
    } else {
      this.hide();
    }
  },

  show: function() {
    this.element.style.display = 'block';
    this.collapsed = false;
  },

  hide: function() {
    this.element.style.display = 'none';
    this.collapsed = true;
  }
};

document.addEventListener('DOMContentLoaded', function() {
  var fieldsets = document.querySelectorAll('.collapsable-fieldset');
  for (var i = 0, c = fieldsets.length; i < c; i++) {
    new Biblys.CollapsableFieldset(fieldsets[i]);
  }
});

// Bootstrap .hidden fix
$('.hidden').each(function() {
  $(this)
    .hide()
    .removeClass('hidden');
});

// Bootstrap popover
$('[data-toggle=popover]').popover();

// CKEditor
var config = {
  entities: false,
  entities_latin: false,
  extraPlugins: 'youtube,justify,nbsp',
  allowedContent: true,
  filebrowserBrowseUrl: '/pages/adm_media',
  toolbarGroups: [
    { name: 'document', groups: ['mode', 'tools', 'document', 'doctools'] },
    { name: 'clipboard', groups: ['clipboard', 'undo'] },
    { name: 'editing', groups: ['find', 'selection', 'editing'] },
    { name: 'forms', groups: ['forms'] },
    { name: 'insert', groups: ['insert'] },
    '/',
    { name: 'styles', groups: ['format'] },
    { name: 'basicstyles', groups: ['basicstyles', 'cleanup'] },
    { name: 'paragraph', groups: ['align', 'list', 'blocks'] },
    { name: 'links', groups: ['links'] }
  ]
};

$('textarea.wysiwyg').ckeditor(config);

/* ALERT */

function _alert(message) {
  if (message instanceof Error) {
    new Biblys.Alert(message.message, { title: 'Erreur' });
    return;
  }

  new Biblys.Alert(message);
}

Biblys.Alert = class {
  constructor(message, options = {}) {
    const title = options.title || 'Alerte';

    const alertHtml = `
    <div class="modal fade" id="alert-modal" tabindex="-1" role="dialog" aria-labelledby="alert-modal-title" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="alert-modal-title">${title}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <i class="fa-solid fa-xmark"></i>
            </button>
          </div>
          <div class="modal-body">
            <p>${message}</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
          </div>
        </div>
      </div>
    </div>
  `;
    const alertElement = createElementFromHTML(alertHtml);
    window.document.body.appendChild(alertElement);
    const alertModal = $('#alert-modal');
    alertModal.modal();
    alertModal.on('hidden.bs.modal', function() {
      document.getElementById('alert-modal').remove();
    });
  }
};

/** NOTIFY */

function notify(text, time) {
  if (text === 'close') {
    time.remove();
    return;
  }

  var sticky = false;
  if (time == 'sticky') {
    time = 0;
    sticky = true;
  } else if (!time) {
    time = 2500;
  }

  return new Biblys.Notification(text, {
    timeout: time,
    sticky: sticky
  });
}

/* SLEEP */

function sleep(ms) {
  var dt = new Date();
  dt.setTime(dt.getTime() + ms);
  while (new Date().getTime() < dt.getTime());
}

/* AUTOCOMPLETE */

function autocomplete(field) {
  $('.autocompleteResults').remove();
  field.addClass('loading');
  const searchQuery = field.val();
  const entityToSearch = field.attr('id');

  if (searchQuery.length < 3) {
    return;
  }

  const searchUrl = field.data('autocomplete-url') ? field.data('autocomplete-url') : `/x/${entityToSearch}`;
  fetch(`${searchUrl}?query=${searchQuery}`)
    .then((response) => {
      field.removeClass('loading');
      return response.text();
    })
    .then((data) => {
      const response = JSON.parse(data);
      if (response.error) {
        window._alert(response.error.message);
        return;
      }

      field.after('<ul class="autocompleteResults">' + response.content + '</ul>');
    });
}

function choose(field, id, value) {
  $('.autocompleteResults').hide();
  $('#' + field)
    .val(value)
    .attr('disabled', 'disabled')
    .after(
      '<input type="button" value="modifier" onClick="unchoose(\'' +
        field +
        '\');" id="unchoose-' +
        field +
        '" class="unchoose" />'
    );
  $('#' + field + '_id').val(id);
  $('#' + field + '_id')
    .siblings('input[type="submit"]')
    .show();
  $('#' + field + '_id')
    .closest('form')
    .find('[type="submit"]')
    .show();
}

function unchoose(field) {
  $('#unchoose-' + field).hide();
  $('#' + field)
    .val('')
    .removeAttr('disabled');
  $('#unchoose-' + field)
    .siblings('input[type="submit"]')
    .hide();
}

/* LISTES */

function addToList(stockId) {
  const listId = document.getElementById('list_id').value;
  fetch('/pages/list_xhr', {
    method: 'POST',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: new URLSearchParams({ stock_id: stockId, list_id: listId })
  })
  .then(response => response.json())
  .then(data => {
    document.querySelector('.autocompleteResults').style.display = 'none';
    document.getElementById('list').value = '';

    if (data.error) {
      new Biblys.Notification(data.error.message, { type: 'danger' });
      return;
    }

    document.querySelector('tbody').insertAdjacentHTML('afterbegin', data.content);
    new Biblys.Notification('L’exemplaire a été ajouté à la liste', { type: 'success' });

    reloadEvents();
  })
  .catch(error => {
    console.error('Error:', error);
  });
}

function addMultipleToList(stockItemIds) {
  const stockId = stockItemIds.split('-');
  let i = 0;

  setInterval(function() {
    if (i < stockId.length) {
      addToList(stockId[i]);
      i++;
    }
  }, 250);
}

function delFromList(x) {
  const list_id = $('#list_id').val();
  $.post('/pages/list_xhr', { del: '' + x + '', list_id: '' + list_id + '' }, function(data) {
    if (data.error) {
      window._alert(data.error.message);
      return;
    }

    $('#link_' + x).remove();
  });
}

/* OVERLAY */

function overlay(text) {
  if (text == 'hide')
    $('#overlay')
      .fadeOut('fast')
      .promise()
      .then(function() {
        $(this).remove();
      });
  else {
    $('body').append(
      '<div id="overlay" style="background: black; position: absolute; width: 100%; height: 100%; top: 0; z-index: 9999; color: white; text-align: center; font-size: 15px;" class="hidden">' +
        text +
        '</div>'
    );
    $('#overlay').fadeTo('fast', 0.4);
  }
}

/* QUICK ADD */
/* MISC */

function file_size(size) {
  var i = 0;
  while (size > 1024) {
    size /= 1024;
    i++;
  }
  if (i == 0) u = '&nbsp;octets';
  else if (i == 1) u = '&nbsp;Ko';
  else if (i == 2) u = '&nbsp;Mo';
  else if (i == 3) u = '&nbsp;Go';
  else if (i == 4) u = '&nbsp;To';
  return Math.round(size * 100) / 100 + u;
}

function price(price, currency) {
  price = parseInt(price) / 100;
  price = price.toFixed(2);
  if (currency == 'EUR') {
    price = price.replace('.', ',');
    price += '&nbsp;&euro;';
  }
  return price;
}

function currency(amountInEuros) {
  if (typeof amountInEuros !== 'number') {
    throw new Error('Currency amount must be a number');
  }

  const currency = (window.site && window.site.currency) || 'EUR';
  const formattedAmountInEuros = amountInEuros
    .toFixed(2)
    .toString()
    .replace('.', ',');

  if (currency === 'FCFA') {
    return formattedAmountInEuros + '&nbsp;F&#8239;CFA';
  }

  return formattedAmountInEuros + '&nbsp;&euro;';
}

function reloadEvents(scope) {
  // Bootstrap .hidden fix
  $('.hidden').each(function() {
    $(this)
      .hide()
      .removeClass('hidden');
  });

  /* AUDIO */

  // Play/Stop
  $('.audioPlayer').click(function() {
    var player = $(this).find('audio')[0];

    if (player.paused == false) {
      $(this).removeClass('playing');
      player.pause().currentTime = 0;
    } else {
      $(this).addClass('playing');
      player.play();
    }
  });

  // Cliquer sur une alerte pour la cacher
  $('.success, .warning, .error').click(function() {
    $(this).slideUp();
  });

  // Changer le prix d'un exemplaire dans la list
  $('.changePriceInList.e')
    .click(function() {
      oldPrice = $(this).data('price');
      $(this)
        .html($(this).data('price'))
        .attr('contenteditable', true)
        .removeClass('pointer')
        .focus()
        .select();
    })
    .blur(function() {
      var newPrice = $(this).html(),
        articleTitle = $(this).data('article_title'),
        newPriceEur =
          parseFloat(newPrice / 100)
            .toFixed(2)
            .replace('.', ',') + ' €';
      if (newPrice != parseInt(newPrice)) {
        // Le prix entré n'est pas un entier
        newPrice = oldPrice;
        _alert('Prix mal formaté !');
      } else if (newPrice == oldPrice) {
        // Le prix entré est le même que l'actuel
      } // Sinon, on fait la modif
      else {
        $.post(
          '/x/adm_stock',
          {
            article_id: '' + $(this).data('article_id') + '',
            stock_id: '' + $(this).data('stock_id') + '',
            field: 'stock_selling_price',
            value: '' + newPrice + ''
          },
          function(data) {
            if (data.indexOf('ERROR') >= 0) alert(data.split('>')[1]);
            else {
              notify('Le prix de ' + articleTitle + ' a été mis à jour !');
            }
          }
        );
      }
      $(this)
        .data('price', newPrice)
        .attr('contenteditable', false)
        .html(newPriceEur)
        .addClass('pointer');
    })
    .removeClass('e');

  // Deplier une table cachee
  $('table.unfold > thead').click(function() {
    $(this)
      .siblings('tbody')
      .slideToggle();
  });

  // Tooltip
  $('[title]').each(function(index, element) {
    if (element.title != '') {
      $(element).tooltipster({ position: 'top', contentAsHTML: true });
    }
  });

  // DateTimePicker
  if (jQuery.isFunction(jQuery.fn.datetimepicker)) {
    $('.datetime').datetimepicker({
      dateFormat: 'yy-mm-dd',
      timeFormat: 'hh:mm:ss'
    });
  }

  // Select goto
  $('select.goto').change(function() {
    var valeur = $(this).val();
    document.location.href = valeur;
  });

  // showThis
  $('.showThis').click(function(e) {
    e.preventDefault();
    var element = $(this)
      .attr('id')
      .split('_')[1];
    $('#' + element).slideDown();
  });

  // toggleThis
  $('.toggleThis').click(function(e) {
    e.preventDefault();
    var element = $(this)
      .attr('id')
      .split('_')[1];
    $('#' + element).slideToggle();
  });

  // data-toggle
  $('[data-toggle]').click(function(e) {
    e.preventDefault();
    var element = $(this).data('toggle');
    $('#' + element).slideToggle();
  });

  // dialogThis
  $('.dialogThis').click(function(e) {
    e.preventDefault();
    var element = $(this)
        .attr('id')
        .split('_')[1],
      title = $('#' + element).data('title');
    $('#' + element).dialog({ minWidth: 400, title: '' + title + '' });
  });

  // data-dialog
  $('[data-dialog]').click(function(event) {
    event.preventDefault();
    var element = $(this).data('dialog'),
      title = $('#' + element).data('title');
    $('#' + element).dialog({ minWidth: 400, title: '' + title + '' });
  });

  // data-click
  $('[data-click]').click(function(e) {
    e.preventDefault();
    $('#' + $(this).attr('data-click')).click();
  });

  // Desactiver input type submit apres clic
  $('<img />')
    .attr('src', '/common/img/loadingFB.gif')
    .appendTo('body')
    .css('display', 'none');
  $('form').submit(function() {
    //$(this).find('[type="submit"]').addClass('loading');
    //return false;
  });

  // Upload automatique medias
  $('.autosubmit').change(function() {
    $(this)
      .parent()
      .submit();
  });

  // Confirmation avant de supprimer
  $('.confirm', scope).click(function() {
    var answer = confirm('Vraiment ?');
    return answer;
  });

  // Confirmer avec message personnalisé
  $('[data-confirm]')
    .not('.event-created')
    .click(function(e) {
      var question = $(this).data('confirm'),
        answer = confirm(question);
      return answer;
    })
    .addClass('event-created');

  /* AUTOCOMPLETE */

  let ac_timer;
  $('.autocomplete.event')
    .keyup(function() {
      const field = $(this);
      if (field.val().length >= 3) {
        if (ac_timer) {
          clearTimeout(ac_timer);
        }
        ac_timer = setTimeout(function() {
          autocomplete(field);
        }, 500);
      }
    })
    .removeClass('event');

  // Fermer autocomplete suggestion
  $('body').click(function() {
    $('.autocompleteResults').hide();
  });

  $('[data-autocomplete]').each(function() {
    $(this).autocomplete({
      source: $(this).attr('data-autocomplete')
    });
  });

  // ** PANIER ET COMMANDE LIBRAIRE ** //

  // Add to & remove from cart (new version)
  $('.add_to_cart.event')
    .click(function(event) {
      event.preventDefault();

      const button = $(this);
      const type = $(this).data('type');
      const id = $(this).data('id');
      let loadingButtonLabel = '';
      let successButtonLabel = '';

      const buttonLabel = button.text().trim();

      if (buttonLabel) {
        loadingButtonLabel = 'Ajout...';
        successButtonLabel = 'Ajouté !';
      }

      // Button & cart preview state
      button.html('<i class="fa-solid fa-spinner fa-spin-pulse"></i> ' + loadingButtonLabel);
      $('#myCart').html('<i class="fa fa-spin fa-spinner"></i> Mise à jour...');

      function getCartEndpointUrl(type, id) {
        if (type === 'article') {
          return `/cart/add-article/${id}`;
        }

        if (type === 'stock') {
          return `/cart/add-stock/${id}`;
        }

        if (type === 'reward') {
          return `/cart/add-reward/${id}`;
        }
      }

      const response = fetch(getCartEndpointUrl(type, id), {
        method: 'post',
        credentials: 'include',
        headers: { Accept: 'application/json' },
      });

      response.then(function(response) {
        return response.json();
      })
        .then(function(data) {
          button.button('reset');

          // Update cart preview
          fetch('/cart/summary')
            .then((response) => response.json())
            .then((json) => {
              const myCart = document.querySelector('#myCart');
              myCart.innerHTML = json.summary;
            });

          // If error
          if (data.error) {
            button.removeClass('btn-success').button('reset');
            button.html('<i class="fa-solid fa-shopping-basket"></i> ' + buttonLabel);

            _alert(data.error.message || data.error);

            // If success
          } else {
            // Success button animation
            button.addClass('btn-success');
            button.html('<span class="fa fa-check-circle"></span> ' + successButtonLabel);
            window.setTimeout(function() {
              button.removeClass('btn-success').button('reset');
              button.html('<i class="fa-solid fa-shopping-basket"></i> ' + buttonLabel);
            }, 2500);

            new Biblys.Notification(
              'L’article a bien été ajouté au panier<br /><br /><p class="text-center"><a class="btn btn-success btn-sm" href="/pages/cart"><span class="fa fa-shopping-cart"></span> Voir le panier</a></p>',
              { type: 'success' }
            );
          }
        })
        .catch(function(error) {
          _alert(error);
        });
    })
    .removeClass('event');

  async function selectCountry(countryId) {
    if (countryId == 0 || typeof countryId === 'undefined') {
      return false;
    }

    const shippingModeSelector = document.querySelector('#shipping_id');
    const continueButton = document.querySelector('#continue');
    shippingModeSelector.setAttribute('disabled', 'disabled');
    continueButton.setAttribute('disabled', 'disabled');

    const orderWeightInput = document.querySelector('#order_weight');
    const orderAmountInput = document.querySelector('#order_amount');
    const articleCountInput = document.querySelector('#article_count');
    const query = `country_id=${countryId}&order_weight=${orderWeightInput.value}&order_amount=${orderAmountInput.value}&article_count=${articleCountInput.value}`;

    const response = await fetch(`/api/shipping/search?${query}`);
    const json = await response.json();
    if (json.error) {
      _alert(json.error.message);
      return;
    }

    const fees = json;

    const shippingNotice = document.querySelector('.ship_notice');
    if (shippingNotice) {
      shippingNotice.style.display = 'none';
    }

    if (fees.length === 0) {
      window._alert(
        `Aucune option d'expédition n'est disponible pour ce pays. Merci de <a href="/contact/">nous contacter</a>.`
      );
      return;
    }

    const feeOptions = fees.map(fee => `<option value="${fee.id}">${fee.mode}</option>`);
    if (feeOptions.length > 1) {
      feeOptions.unshift(`<option value="0">Choisissez une option d'expédition</option>`);
    }

    shippingModeSelector.innerHTML = feeOptions.join('');

    if (feeOptions.length === 1) {
      await selectShippingMode(fees[0].id);
    }

    shippingModeSelector.removeAttribute('disabled');
  };

  async function selectShippingMode(shippingId) {
    const continueButton = document.querySelector('#continue');

    if (shippingId === 0) {
      continueButton.setAttribute('disabled', 'disabled');
      return false;
    }

    const response = await fetch(`/api/shipping/${shippingId}`);
    const shipping = await response.json();

    const subTotalElement = document.querySelector('#sub_total');
    const subTotal = parseInt(subTotalElement.value);
    const shippingFee = parseInt(shipping.fee);
    const total = subTotal + shippingFee;

    const shippingFeeInEuros = currency(shippingFee / 100);
    const totalInEuros = currency(total / 100);

    const shippingInfoElement = document.querySelector('#shipping_info');
    shippingInfoElement.textContent = shipping.info;

    const shippingAmountElement = document.querySelector('#shipping_amount');
    shippingAmountElement.innerHTML = shippingFeeInEuros;

    const totalElement = document.querySelector('#total');
    totalElement.innerHTML = totalInEuros;

    continueButton.removeAttribute('disabled');
  }

  const countrySelector = document.querySelector('#country_id');
  if (countrySelector) {
    countrySelector.addEventListener('change', function() {
      selectCountry(this.value);
    });
    selectCountry(countrySelector.value);
  }

  const shippingSelectorMenu = document.querySelector('#shipping_id');
  if (shippingSelectorMenu) {
    shippingSelectorMenu.addEventListener('change', function (event) {
      const shippingId = parseInt(event.target.value);
      selectShippingMode(shippingId);
    });
  }

  var price = function(price, format) {
    if (format == 'EUR') {
      return (parseInt(price) / 100).toFixed(2).replace('.', ',') + '&nbsp;&euro;';
    }
    return (parseInt(price) / 100).toFixed(2);
  };

  $('#validate_cart').on('submit', function() {
    var select = $('#shipping_id'),
      option = select.find('option:selected');

    // If there is no shipping_id field, we don't need to check
    if (select.length === 0) {
      return true;
    }
  });

  // ** ALERTS ** //

  // Create & delete alert
  $('[data-alert].event')
    .click(async function () {
      const buttonIcon = $(this).find('i');

      const articleId = this.getAttribute('data-alert');
      const response = await fetch(`/pages/log_myalerts`, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({article_id: articleId})
      });
      const data = await response.json();

      buttonIcon.removeClass('fa-solid fa-regular orange');

      if (data.error) {
        _alert(data.error);
      } else if (data.created) {
        buttonIcon.addClass('fa-solid orange');
        new Biblys.Notification(
          `L'article a bien été ajouté à <a href="/pages/log_myalerts">vos alertes</a>.`,
          {type: 'success'}
        );
      } else if (data.deleted) {
        buttonIcon.addClass('fa-regular');
        new Biblys.Notification(
          `L'article a bien été retiré de <a href="/pages/log_myalerts">vos alertes</a>.`,
          {type: 'success'}
        );
      }
    })
    .removeClass('event');

  // ** WISHLIST ** //

  // Create & delete wish
  $('[data-wish].event')
    .click(function() {
      const button = $(this);
      const icon = button.find('i');

      fetch('/pages/log_mywishes', {
        method: 'post',
        credentials: 'include',
        headers: { Accept: 'application/json' },
        body: JSON.stringify({
          article_id: $(this).attr('data-wish'),
        }),
      })
        .then(function(response) {
          return response.json();
        })
        .then(function(data) {
          icon.removeClass('fa-regular fa-solid red');
          if (data.error) {
            _alert(data.error);
            icon.addClass('fa-regular');
          } else if (data.created) {
            icon.addClass('fa-solid red');
            new Biblys.Notification(data.message, { type: 'success' });
          } else if (data.deleted) {
            icon.addClass('fa-regular');
            new Biblys.Notification(data.message, { type: 'success' });
          }
        });

    })
    .removeClass('event');

  // ** RESERVATION LIBRAIRIE ** //

  // Ouvrir la fenêtre de reservation
  $('.createResa').click(function() {
    var article_title = $(this).data('article_title');
    $('#createResa').dialog({
      title: 'Créer une alerte pour &laquo; ' + article_title + ' &raquo; ',
      width: 400
    });
  });

  // Enregistrer la reservation
  $('#createResa form').submit(function(event) {
    event.preventDefault();
    var article_title = $(this).data('article_title');
    $('#createResa').dialog('close');
    new Biblys.Notification(
      'Une réservation a bien été créée pour le livre &laquo; ' + article_title + ' &raquo;.',
      { type: 'success' }
    );
  });

  // ** QUICK ADD **//
  $(document).on('click', '.stockQuickAdd', function(event) {
    event.preventDefault();
    window.location.href = '/admin/stock-items/new';
  });

  // Checklist
  $('.checklist li').click(function(e) {
    $(this).toggleClass('checked');
  });
}
//reloadEvents();

$(document).ready(function() {
  reloadEvents(this);

  // Verification des champs obligatoires du formulaire
  $('form.check.event')
    .submit(function(e) {
      var error = 0;
      uploading = $(this).attr('data-uploading');

      // Check fields
      $('input[required]', this).each(function() {
        if ($(this).val().length == 0) {
          var field = $(this).attr('id');
          //if ($(this).attr('type') != 'hidden')
          //{
          if ($('label[for="' + field + '"]').length)
            fieldlabel = $('label[for="' + field + '"]')
              .html()
              .replace(' :', '');
          else fieldlabel = field;
          $('input[type=submit]')
            .removeClass('loading')
            .removeAttr('disabled');
          if (field == 'article_authors')
            _alert(
              '<p class="warning">Le champ Auteur(s) est obligatoire !</p> <p>Utilisez la section <strong>Contributeurs</strong> ajouter au moins un auteur &agrave; la fiche article.</p> <p>Plus d\'informations dans la <a href="https://docs.biblys.fr/administrer/catalogue/fiche-article/#contributeurs">documentation</a>.</p>'
            );
          else if (field == 'article_collection')
            _alert(
              '<p class="warning">Le champ Collection est obligatoire !</p> <p>Si le titre est hors-collection, créez une collection ayant pour nom le nom de l\'éditeur.</p> <p>Plus d\'informations dans la <a href="https://docs.biblys.fr/administrer/catalogue/fiche-article/">documentation</a>.</p>'
            );
          else _alert('Le champ &laquo; ' + fieldlabel + ' &raquo; est obligatoire !');
          error = 1;
          return false;
          //}
        }
        if (error == 1) return false;
        // break the loop
        else return true;
      });

      if (error == 1) return false;
      else {
        return true;
      }
    })
    .removeClass('event');

  // Countdown
  if ($('.countdown').length) {
    var timer = $('.countdown');
    timer.countdown(timer.data('target'), function(event) {
      timer.text(event.strftime('%Hh %Mm %Ss'));
    });
  }
});
