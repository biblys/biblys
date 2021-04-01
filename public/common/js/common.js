
window.biblys = {};
var Biblys = {};

/* NOTIFICATIONS */

/**
 * Displays a notification
 *
 * @param {string} text the notification's message
 * @param {options} options a object containing options
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
  extraPlugins: 'youtube,justify',
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
    message = 'Erreur : ' + message.message;
  }
  $('body').append('<div id="alert">' + message + '</div>');
  $('#alert').dialog({
    modal: true,
    close: function(e) {
      $('#alert').remove();
    }
  });
}

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
  field_value = field.val();
  field_id = field.attr('id');
  field_data = field.data();
  field_data.query = field_value;
  if (field_value.length >= 3) {
    $.post('/x/' + field_id, field_data, function(data) {
      field.removeClass('loading');
      if (data.indexOf('ERROR') >= 0) alert(data.split('>')[1]);
      else {
        field.after('<ul class="autocompleteResults">' + data + '</ul>');
      }
    });
  }
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

function addToList(x) {
  var list_id = $('#list_id').val();
  $.post('/x/list', { stock_id: '' + x + '', list_id: '' + list_id + '' }, function(data) {
    $('.autocompleteResults').hide();
    $('#list').val('');
    if (data.indexOf('ERROR') >= 0) alert(data.split('>')[1]);
    else {
      $('tbody').prepend(data);
      reloadEvents();
    }
  });
}

function addMultipleToList(x) {
  var s = x.split('-'),
    i = 0;
  setInterval(function() {
    if (i < s.length) {
      addToList(s[i]);
      i++;
    }
  }, 250);
}

function delFromList(x) {
  var list_id = $('#list_id').val();
  $.post('/x/list', { del: '' + x + '', list_id: '' + list_id + '' }, function(data) {
    if (data.indexOf('ERROR') >= 0) alert(data.split('>')[1]);
    else $('#link_' + x).remove();
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

function quickAdd() {
  $('body').append(
    '<form id="stockQuickAdd"><br /><br /><fieldset for="stockQuickAddInput"><label>Article :</label> <input id="stockQuickAddInput" type="text" class="verylong" placeholder="ISBN, titre, auteur, collection..." autofocus /> <input type="submit" value="&raquo;" /></fieldset><audio controls="controls" class="hidden" autoplay><source src="/assets/sounds/beep.mp3" type="audio/mp3" /></audio></form>'
  );
  $('#stockQuickAdd')
    .dialog({
      modal: true,
      title: 'Ajouter un exemplaire de...',
      height: 450,
      width: 650,
      close: function() {
        $('#stockQuickAdd').remove();
      }
    })
    .submit(function(e) {
      e.preventDefault();
      $('#stockQuickAddInput').autocomplete('search');
    });
  $('#stockQuickAddInput').autocomplete({
    source: '/x/adm_articles?v=2',
    minLength: 3,
    delay: 250,
    select: function(event, ui) {
      $(this).removeClass('loading');
      if (ui.item.url) {
        overlay('Chargement en cours...');
        window.location = ui.item.url;
      }
    }
  });
}

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

function currency(amount) {
  if (typeof amount !== 'number') {
    throw new Error('Currency amount must be a number');
  }

  var currency = (window.site && window.site.currency) || 'EUR';
  amount = amount
    .toFixed(2)
    .toString()
    .replace('.', ',');

  if (currency === 'FCFA') {
    return amount + '&nbsp;F&#8239;CFA';
  }

  return amount + '&nbsp;&euro;';
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

  // Fancybox
  if (jQuery.isFunction(jQuery.fn.fancybox)) {
    $('[rel="fancybox"]').fancybox();
  }
  if (jQuery.isFunction(jQuery.fn.fancybox)) {
    $('[data-rel="fancybox"]').fancybox();
  }
  if (jQuery.isFunction(jQuery.fn.fancybox)) {
    $('[data-fancybox]').fancybox();
  }

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

  var ac_timer;
  $('.autocomplete', scope).keyup(function() {
    // rechercher un article
    var field = $(this);
    if (field.val().length >= 3) {
      if (ac_timer) {
        clearTimeout(ac_timer);
      }
      ac_timer = setTimeout(function() {
        autocomplete(field);
      }, 500);
    }
  });
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

      const button = $(this),
        type = $(this).data('type'),
        id = $(this).data('id'),
        wish_id = $(this).data('wish_id'),
        as_a_gift = $(this).data('as_a_gift');
      let text_loading = '';
      let text_success = '';

      if (button.hasClass('with-text')) {
        (text_loading = 'Ajout...'), (text_success = 'Ajouté !');
      }

      // Button & cart preview state
      button
        .attr('data-loading-text', '<i class="fa fa-spin fa-spinner"></i> ' + text_loading)
        .button('loading');
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
          $('#myCart').load('/pages/cart?oneline');

          // If error
          if (data.error) {
            _alert(data.error);

            // If success
          } else {
            // Success button animation
            button.addClass('btn-success');
            button.html('<span class="fa fa-check-circle"></span> ' + text_success);
            window.setTimeout(function() {
              button.removeClass('btn-success').button('reset');
            }, 2500);

            if (data.added) {
              button.find('i.fa').addClass('green');
            } else {
              button.find('i.fa').removeClass('green');
            }
            new Biblys.Notification(
              `L'article a bien été ajouté au panier<br /><br /><p class="text-center"><a class="btn btn-primary btn-sm" href="/pages/cart"><span class="fa fa-shopping-cart"></span> Voir le panier</a></p>`,
              { type: 'success' }
            );
          }
        })
        .catch(function(error) {
          _alert(error);
        });
    })
    .removeClass('event');

  // Ajouter au panier
  $('.addToCart').click(function(e) {
    e.preventDefault();
    button = $(this);
    var type = $(this).data('type'),
      id = $(this).data('id'),
      button_img = button.find('img').attr('src');
    button.find('img').attr('src', '/common/img/loading2.gif');
    $('#myCart').html(
      '<p><img src="/common/img/loading2.gif" alt="Chargement" /> Mise à jour...</p>'
    );
    $.post(
      '/x/cart',
      {
        add: '' + type + '',
        id: '' + id + '',
        wish_id: '' + button.attr('data-wish_id') + ''
      },
      function(data) {
        var res = jQuery.parseJSON(data);
        if (res.error) {
          _alert(res.error);
          $('#myCart').load('/x/cart');
          button.find('img').attr('src', button_img);
        } else {
          button
            .hide()
            .find('img')
            .attr('src', button_img);
          $('#addedToCart_' + id + ', #delFromCart_' + id)
            .show()
            .css('display', 'inline-block');
          $('#myCart').load('/x/cart');
          new Biblys.Notification(res.success, { type: 'success' });
        }
      }
    );
  });

  // Supprimer du panier
  $('.delFromCart').click(function(e) {
    e.preventDefault();
    button = $(this);
    var type = $(this).data('type'),
      id = $(this).data('id'),
      button_img = button.find('img').attr('src');
    button.find('img').attr('src', '/common/img/loading2.gif');
    $('#myCart').html(
      '<p><img src="/common/img/loading2.gif" alt="Chargement" /> Mise à jour...</p>'
    );
    $.post('/x/cart', { del: '' + type + '', id: '' + id + '' }, function(data) {
      var res = jQuery.parseJSON(data);
      if (res.error) {
        _alert(res.error);
      } else {
        button
          .hide()
          .find('img')
          .attr('src', button_img);
        $('#addToCart_' + id)
          .show()
          .css('display', 'inline-block');
        $('#myCart').load('/x/cart');
        new Biblys.Notification(res.success, { type: 'success' });
      }
    });
  });

  var select_country = function(country_id) {
    if (country_id == 0 || typeof country_id === 'undefined') {
      return false;
    }
    $.ajax('/pages/shipping', {
      data: {
        country_id: country_id,
        order_weight: $('#order_weight').val(),
        order_amount: $('#order_amount').val()
      },
      dataType: 'json',
      contentType: 'application/json',
      success: function(res) {
        var select = $('#shipping_id');

        $('#shipping_id')
          .html('')
          .attr('disabled', 'disabled');
        // $('#continue').attr('disabled', 'disabled');
        $('.ship_notice').hide();

        if (res) {
          if (res.length == 0) {
            _alert(
              'Aucune option d\'expédition n\'est disponible pour ce pays. Merci de <a href="/contact/">nous contacter</a>.'
            );
            return false;
          }

          var options = $.map(res, function(fee) {
            return (
              '<option data-type="' +
              fee.type +
              '" data-amount="' +
              fee.fee +
              '" data-info="' +
              fee.info +
              '" value="' +
              fee.id +
              '">' +
              fee.mode +
              '</option>'
            );
          });

          // If only one option, add an empty option
          if (options.length > 1) {
            options.unshift(
              '<option data-info="Choisissez un mode d\'expédition ci-dessus.">Choisissez un mode d\'expédition...</option>'
            );
          }

          $('#shipping_id')
            .html(options)
            .removeAttr('disabled');

          $('#shipping_id').trigger('change');
        }
      }
    });
  };
  select_country($('#country_id').val());

  // Choisir le pays de destination
  $('#country_id').change(function() {
    select_country($(this).val());
  });

  var price = function(price, format) {
    if (format == 'EUR') {
      return (parseInt(price) / 100).toFixed(2).replace('.', ',') + '&nbsp;&euro;';
    }
    return (parseInt(price) / 100).toFixed(2);
  };

  // Choisir le mode d'expedition
  $('#shipping_id').change(function() {
    var option = $('#shipping_id option:selected');
    var sub_total = parseInt($('#sub_total').val());
    var shipping_type = option.attr('type');
    var shipping_fee = option.data('amount');
    var shipping_info = option.data('info') || '';
    var total = '—';

    if (typeof shipping_fee !== 'undefined') {
      total = currency((sub_total + parseInt(shipping_fee)) / 100);
      shipping_fee = currency(shipping_fee / 100);
    } else {
      shipping_fee = '—';
    }

    $('#shipping_info').text(shipping_info);
    $('#shipping_amount').html(shipping_fee);
    $('#total').html(total);
    $('#' + shipping_type).show();
    $('#continue').removeAttr('disabled');
  });

  $('#validate_cart').on('submit', function() {
    var select = $('#shipping_id'),
      option = select.find('option:selected');

    // If there is no shipping_id field, we don't need to check
    if (select.length == 0) {
      return true;
    }

    // If the select optino has an amount, we're okay
    if (typeof option.data('amount') !== 'undefined') {
      return true;
    }

    // Else, display an alert
    _alert("Merci de choisir un mode d'expédition afin de pouvoir finaliser la commande.");
    return false;
  });

  // ** ALERTS ** //

  // Create & delete alert
  $('[data-alert].event')
    .click(function() {
      var button = $(this);
      button
        .find('i.fa')
        .removeClass('fa-bell-o fa-bell orange')
        .addClass('fa-spin fa-spinner');
      $.post(
        '/pages/log_myalerts',
        {
          article_id: $(this).attr('data-alert')
        },
        function(data) {
          if (data.error) _alert(data.error);
          else if (data.created) {
            button
              .find('i.fa')
              .removeClass('fa-spin fa-spinner')
              .addClass('fa-bell orange');
            new Biblys.Notification(
              'L\'article a bien été ajouté à <a href="/pages/log_myalerts">vos alertes</a>.',
              { type: 'success' }
            );
          } else if (data.deleted) {
            button
              .find('i.fa')
              .removeClass('fa-spin fa-spinner')
              .addClass('fa-bell-o');
            new Biblys.Notification(
              'L\'article a bien été retiré de <a href="/pages/log_myalerts">vos alertes</a>.',
              { type: 'success' }
            );
          }
        },
        'json'
      );
    })
    .removeClass('event');

  // ** WISHLIST ** //

  // Create & delete wish
  $('[data-wish].event')
    .click(function() {
      var button = $(this);
      button
        .find('i.fa')
        .removeClass('fa-heart-o fa-heart red')
        .addClass('fa-spin fa-spinner');
      $.post(
        '/pages/log_mywishes',
        {
          article_id: $(this).attr('data-wish')
        },
        function(data) {
          if (data.error) _alert(data.error);
          else if (data.created) {
            button
              .find('i.fa')
              .removeClass('fa-spin fa-spinner')
              .addClass('fa-heart red');
            new Biblys.Notification(data.message, { type: 'success' });
          } else if (data.deleted) {
            button
              .find('i.fa')
              .removeClass('fa-spin fa-spinner')
              .addClass('fa-heart-o');
            new Biblys.Notification(data.message, { type: 'success' });
          }
        },
        'json'
      );
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
    quickAdd();
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
              '<p class="warning">Le champ Auteur(s) est obligatoire !</p> <p>Utilisez la section <strong>Contributeurs</strong> ajouter au moins un auteur &agrave; la fiche article.</p> <p>Plus d\'informations dans la <a href="http://www.biblys.fr/pages/doc_article#Contributeurs">documentation</a>.</p>'
            );
          else if (field == 'article_collection')
            _alert(
              '<p class="warning">Le champ Collection est obligatoire !</p> <p>Si le titre est hors-collection, créez une collection ayant pour nom le nom de l\'éditeur.</p> <p>Plus d\'informations dans la <a href="http://www.biblys.fr/pages/doc_article">documentation</a>.</p>'
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

  // Masquer un livre
  $('.hideArticle')
    .click(function(e) {
      article_id = $(this).data('article_id');
      $.post('/x/adm_hide', { article_id: '' + article_id }, function(data) {
        var res = jQuery.parseJSON(data);
        if (res.error) _alert(res.error);
        else {
          notify(res.message);
          if (res.result == 'hide') $('#article_' + article_id).fadeTo('200', 0.25);
          else if (res.result == 'show') $('#article_' + article_id).fadeTo('200', 1);
        }
      });
    })
    .removeClass('hideArticle');

  // Countdown
  if ($('.countdown').length) {
    var timer = $('.countdown');
    timer.countdown(timer.data('target'), function(event) {
      timer.text(event.strftime('%Hh %Mm %Ss'));
    });
  }
});
