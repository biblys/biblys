/* eslint-env jquery */
/* global _alert */
/* global overlay */

'use strict';

if (typeof Biblys === 'undefined') {
  var Biblys = {};
}

function handleHttpError(response) {
  if (response.error) {
    throw new Error(response.error);
  }

  return response;
}

// Choisir une collection
function choose_collection(collection_id, collection_name, publisher_id, collection_publisher, pricegrid_id) {
  $('#article_collection').addClass('pointer').removeClass('uncomplete').attr('readonly', 'readonly').val(collection_name);
  $('#article_publisher').addClass('pointer').removeClass('uncomplete').attr('readonly', 'readonly').val(collection_publisher);
  $('#collection_id').val(collection_id);
  $('#publisher_id').val(publisher_id);
  $('#pricegrid_id').val(pricegrid_id);
  if (pricegrid_id != 0 && typeof (pricegrid_id) !== 'undefined') {
    $('#article_category_div').slideDown();
    $('#article_category').attr('required', 'required');
    $('#article_price').attr('readonly', 'readonly');
  } else {
    $('#article_category_div').slideUp();
    $('#article_category').removeAttr('required').val('');
    $('#article_price').removeAttr('readonly');
  }
  $('#article_number').focus();
}

// Choisir un editeur (creation de collection)
function choose_publisher(publisher_id, publisher_name) {
  $('#collection_publisher').addClass('pointer').removeClass('uncomplete').attr('readonly', 'readonly').val(publisher_name);
  $('#collection_publisher_id').val(publisher_id);
}

// Choisir un cycle
function choose_cycle(cycle_id, cycle_name) {
  $('#article_cycle').addClass('pointer').removeClass('uncomplete').attr('readonly', 'readonly').val(cycle_name);
  $('#cycle_id').val(cycle_id);
  $('#article_tome').focus();
}

// Choisir un contributeur
function choose_people(people_id, people_name, job_id) {
  $.ajax({
      url: '/x/adm_article_people', 
      method: 'POST',
      data: {
        action: 'add',
        article_id: $('#article_id').val(),
        people_id: people_id,
        people_name: people_name,
        job_id: job_id
      }, 
      complete: function (jqXHR) {
        var data = jqXHR.responseJSON;
        $('#article_people').removeAttr('readonly').removeClass('loading');
        if (data.error) {
          window._alert(data.error);
        } else {
          $('#people_list').append(data.people);
          $('#job_id_' + data.role_id).html($('#new_people_job').html()).focus();
          $('#job_id_' + data.role_id + ' option[value=' + job_id + ']').attr('selected', 'selected');
          $('#article_authors').val(data.authors);
          $('#article_people').val('');
          reloadEvents($('#people_list'));
        }
      }
  });
}

// Recherche dans les bases externes
function article_search() {
  var field;
  if ($('#article_ean').val().length) {
    field = $('#article_ean');
  } else if ($('#article_title').val().length) {
    field = $('#article_title');
  } else return 0;
  if (field.val() != field.data('searched')) {
    field.addClass('loadingOrange');
    var notification = new Biblys.Notification('Recherche de <em>' + field.val() + '</em> dans les bases externes...', { sticky: true, loader: true });

    fetch('/x/adm_article_import?mode=search&q=' + field.val(), {
      credentials: 'same-origin',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      }
    }).then(function (response) {
      return response.json();
    }).then(function (json) {
      field.removeClass('loadingOrange').data('searched', field.val());
      notification.remove();
      if (json.error) {
        throw json.error;
      }
      $('#article_import').html(json.result);
      $('#results').removeClass('hidden').slideDown();
      $('#showAllResults').click(function () {
        const additionalResults = document.querySelector('#additionalResults');
        const showAllResults = document.querySelector('#showAllResults');
        additionalResults.classList.remove('hidden');
        showAllResults.style.display = 'none';
      });
      reloadEvents(this);
    }).catch(function (error) {
      _alert(error);
    });
  }
  return 0;
}

// Protection anti-doublons
function duplicate_check(field) {
  if (field.val() != '' && field.val() != field.data('checked')) {
    var notification = new Biblys.Notification('Recherche de doublons potentiels...', { sticky: true, loader: true });
    field.addClass('loading');
    $.get('/x/adm_article_duplicate_check', {
      article_id: $('#article_id').val(),
      collection_id: $('#collection_id').val(),
      field: field.attr('id'),
      value: field.val()
    }, function (res) {
      field.removeClass('loading').data('checked', field.val());
      notification.remove();
      if (res.error) _alert(res.error);
      else if (res.content.length > 0) {
        new Biblys.Notification(res.message, { timeout: 5000, type: 'warning', sound: 'pop' });
        $('#duplicates').append(res.content);
        $('#duplicates_fieldset').slideDown();
      }
      reloadEvents();
    });
  }
}

function reloadEvents(scope) {

  // Lier les doublons potentiels a la fiche en cours de creation
  $('.linkto').click(function () {
    $('#article_link_to').val($(this).data('id'));
    new Biblys.Notification('Les livres ont bien été liés !', { sound: 'pop', type: 'success' });
  }).removeClass('linkto');

  // Empêcher validation formulaire par touche entree si champ ean
  $('.article_ean').keypress(function (event) { if (event.keyCode == 13) { event.preventDefault(); } });
  $('#article_people').keypress(function (event) { if (event.keyCode == 13) { event.preventDefault(); $('#article_people').autocomplete('search'); } });

  $('.reimport.event').click(function () { article_search(); }).removeClass('event');

  // Changer le type d'article
  $('#type_id', scope).change(function () {
    var type_id = $(this).val();
    if (type_id == 2) {
      $('#ebooks_fieldset').slideDown();
      $('#article_ean_div').slideUp();
    } else if (type_id == 8) {
      $('#bundle_fieldset').slideDown();
    } else {
      $('#ebooks_fieldset').slideUp();
      $('#article_ean_div').slideDown();
    }
  });

  // Verification doublons
  $('.article_duplicate_check.event', scope).blur(function () { duplicate_check($(this)); }).removeClass('event');

  // Verification validite ISBN + Import
  $('.article_ean.event', scope).blur(function () {
    var field = this;
    if (field.value != '' && field.value != field.dataset.checked) {
      field.classList.add('loading');
      var notification = new Biblys.Notification('Validation de l\'EAN...', { sticky: true, loader: true });

      fetch('/admin/articles/check-isbn', {
        method: 'POST',
        body: JSON.stringify({
          article_id: $('#article_id').val(),
          article_ean: field.value,
        }),
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
        },
      }).then(function (response) {
        return response.json();
      }).then(handleHttpError)
        .then(function (json) {
          field.classList.remove('loading');
          notification.remove();
          field.value = json.isbn
          field.dataset.checked = json.isbn;
          if ($('#article_form').data('mode') == 'insert' && field.id == 'article_ean') {
            article_search();
          }
        }).catch(function (error) {
          field.classList.remove('loading');
          notification.remove();
          _alert(error);
        });
    }
  }).removeClass('event');

  // Autoimport
  $('.autoimport').each(function () {
    article_search();
    $(this).removeClass('autoimport');
  });

  // Importer une fiche
  $('.article-import').click(function () {
    overlay('Importation en cours...');
    var notification = new Biblys.Notification('Importation de la fiche en cours...', { sticky: true, loader: true });
    $.ajax({
      url: '/x/adm_article_import', 
        data: {
        mode: 'import',
        ean: $(this).data('ean'),
        asin: $(this).data('asin'),
        noosfere_id: $(this).data('noosfere_id')
      }, 
      success: function (data) {
        notification.remove();
        var res = jQuery.parseJSON(data);
        if (res.error) {
          overlay('hide');
          _alert(res.error);
        } else {
          $.each(res, function (key, val) {
            if (key != 'article_collection') $('#' + key).val(val);
          });
          if (res.collection_id) choose_collection(res.collection_id, res.article_collection, res.publisher_id, res.article_publisher, res.pricegrid_id);
          if (res.cycle_id) choose_cycle(res.cycle_id, res.article_cycle);
          if (res.article_people !== undefined && res.article_people != null) {
            // var count = res.article_people.length;
            $.each(res.article_people, function (pkey, pval) {
              overlay('Importation en cours...');
              choose_people(pval.people_id, pval.people_name, pval.job_id);
            });
          }
          $('#article_people').val('');
          $('#article_import').hide();
          overlay('hide');
          duplicate_check($('#article_asin'));
          duplicate_check($('#article_noosfere_id'));
          duplicate_check($('#article_item'));
          duplicate_check($('#article_title'));
          new Biblys.Notification('Importation réussie !', { type: 'success' });
        }
      },
      error: function (jqXHR) {
        notification.remove();
        overlay('hide');
        var error = jqXHR.responseJSON.error;
        _alert('Erreur lors de l\'importation : ' + error);
      }
    });
  });

  // Si champ changeThis non valide
  $('.changeThis', scope).blur(function () {
    if ($(this).attr('readonly') != 'readonly') $(this).val('');
  });

  // Changer les champs collection ou cycle
  $('.changeThis', scope).click(function () {
    $(this).removeClass('pointer').addClass('uncomplete').removeAttr('readonly').val('').focus();
    $('#' + $(this).attr('id').replace('article_', '') + '_id').val('');
  });
  $('.changeThis', scope).bind('keypress', function (e) {
    var code = (e.keyCode ? e.keyCode : e.which);
    if (code == 13) {
      e.preventDefault();
      $(this).removeClass('pointer').addClass('uncomplete').removeAttr('readonly').val('').focus();
      $('#' + $(this).attr('id').replace('article_', '') + '_id').val('');
    }
  });

  // Creer une nouvelle collection
  $('#createCollection.event', scope).submit(function (e) {
    e.preventDefault();
    $.post({
      url: '/x/adm_article_collection', 
      data: {
        collection_name: $('#collection_name').val(),
        collection_publisher: $('#collection_publisher').val(),
        collection_publisher_id: $('#collection_publisher_id').val()
      },
      dataType: 'json',
      success: function (res) {
        $('#article_collection').addClass('pointer').removeClass('uncomplete').attr('readonly', 'readonly').val(res.collection_name);
        $('#article_publisher').val(res.collection_publisher);
        $('#collection_id').val(res.collection_id);
        $('#publisher_id').val(res.collection_publisher_id);
        $('#createCollection').dialog('close');
        $('#article_number').focus();
      },
      error: function (data) {
        var error = data.responseJSON.error;
        _alert(error);
      }
    });
  }).removeClass('event');

  // CONTRIBUTEURS

  // Changer le role d'un contributeur
  $('.change_role', scope).change(function () {
    var role_id = $(this).data('role_id');
    var job_id = $(this).val();
    $('#role_' + role_id).fadeTo('slow', '0.5');
    $.post('/x/adm_article_people', {
      action: 'update',
      article_id: $('#article_id').val(),
      role_id: role_id,
      job_id: job_id
    }, function (data) {
      $('#role_' + role_id).fadeTo('fast', '1');
      if (data.error) _alert(data.error);
      else {
        $('#article_authors').val(data.authors);
        $('#article_people').focus();
      }
    });
  });

  // Retirer un contributeur
  $('.remove_people', scope).click(function (e) {
    e.preventDefault();
    var role_id = $(this).data('role_id');
    $('#role_' + role_id).fadeTo('fast', '0.5');
    $.post('/x/adm_article_people', {
      action: 'remove',
      article_id: $('#article_id').val(),
      role_id: role_id,
    }, function (data) {
      if (data.error) {
        _alert(data.error);
        $('#role_' + role_id).fadeTo('fast', '1');
      } else {
        $('#role_' + role_id).slideUp();
        $('#article_authors').val(data.authors);
      }
    });
  });

  // Supprimer un lien
  $('.deleteLink').click(function () {
    var link_id = $(this).data('link_id');
    $('#link_' + link_id).fadeTo('fast', 0.5);
    $.get('/x/adm_links', {
      del: 1,
      link_id: link_id
    }, function (data) {
      var res = jQuery.parseJSON(data);
      if (res.error) {
        $('#link_' + link_id).fadeTo('fast', 1);
        _alert(res.error);
      } else $('#link_' + link_id).slideUp();
    });
  }).removeClass('deleteLink');

  // ** RECOMPENSES ** //

  // Ouvrir le dialog d'ajout de recompense
  $('#addAward', scope).click(function () {
    $('#add_award').dialog({
      title: 'Ajouter une récompense',
      modal: true,
      width: 500
    });
  });

  // Ajouter la nouvelle recompense
  $('#add_award.event', scope).submit(function (event) {
    event.preventDefault();
    $('#addAwardSubmit').addClass('loading').attr('disabled', 'disabled');
    $.ajax({
      url: '/x/adm_article_award',
      method: 'POST',
      data: {
        article_id: $('#article_id').val(),
        award_name: $('#award_name').val(),
        award_year: $('#award_year').val(),
        award_category: $('#award_category').val(),
        award_note: $('#award_note').val()
      }, 
      complete: function (jqXHR) {
        const res = jqXHR.responseJSON;
        $('#addAwardSubmit').removeClass('loading').removeAttr('disabled');
        if (res.error) _alert(res.error);
        else {
          $('#add_award').dialog('close');
          $('#awards').append(res.award);
          $('.newAward').slideDown().removeClass('newAward');
          reloadEvents($('#awards'));
        }
      }
    });
  }).removeClass('event');

  $('.deleteAward', scope).click(function () {
    var award_id = $(this).data('award_id');
    $('#award_' + award_id).fadeTo('fast', '0.5');
    $.ajax({
      url: '/x/adm_article_award', 
      data: {
        del: 1,
        award_id: award_id
      }, 
      complete: function (jqXHR) {
        var res = jqXHR.responseJSON;
        if (res.error) {
          _alert(res.error);
          $('#award_' + award_id).fadeTo('fast', '1');
        } else $('#award_' + award_id).slideUp();
      }
    });
  });
}

$(document).ready(function () {
  reloadEvents();

  // Creer un nouveau contributeur
  $('#create_people.e').submit(function (e) {
    e.preventDefault();
    $('#submitPeopleForm').addClass('loading');
    $.post({
      method: 'POST',
      url: '/x/adm_article_people',
      data: {
        action: 'create',
        people_first_name: $('#people_first_name').val(),
        people_last_name: $('#people_last_name').val()
      },
      success: function (data) {
        $('#submitPeopleForm').removeClass('loading');
        console.log(data);
        if (data.error) {
          _alert(data.error);
        } else {
          $('#article_people').removeClass('uncomplete').removeAttr('readonly').val('');
          $('#people_first_name').val('');
          $('#people_last_name').val('');
          $('#create_people').dialog('close');
          choose_people(data.people_id, data.people_name, data.job_id);
        }
      },
      error: function(jqXHR) {
        var error = jqXHR.responseJSON.error;
        window._alert(error);
      },
    });
  }).removeClass('e');

  /* AUTOCOMPLETE */

  // Autocomplete collection
  $('#article_collection').autocomplete({
    source: '/x/adm_article_collection',
    minLength: 3,
    delay: 250,
    select: function (event, ui) {
      if (ui.item.create == '1') { // Creer une nouvelle collection
        $('#collection_name').val(ui.item.value);
        $('#createCollection').dialog({
          title: 'Créer une nouvelle collection',
          minHeight: 500,
          modal: true,
          width: 500,
          buttons: [{
            text: 'Annuler',
            click: function () {
              $(this).dialog('close');
            }
          }, {
            text: 'Valider',
            click: function () {
              $(this).submit();
            }
          }]
        });
        initPublisherAutocomplete();
        $('#collection_publisher').focus();
      } else { // Selectionner une collection existante
        choose_collection(ui.item.collection_id, ui.item.value, ui.item.publisher_id, ui.item.collection_publisher, ui.item.pricegrid_id);
      }
    }
  });

  // Autocomplete editeur (a la creation de collection)
  function initPublisherAutocomplete() {
    $('#collection_publisher').autocomplete({
      source: '/x/adm_article_publisher',
      minLength: 3,
      delay: 250,
      select: function (event, ui) {
        if (ui.item.create == '1') { // Creer un nouvel editeur
          $('#collection_publisher').addClass('loading');
          $.post({ 
            url: '/x/adm_article_publisher', 
            data: {
              publisher_name: ui.item.value
            }, 
            success: function (data) {
              $('#collection_publisher').removeClass('loading');
              var res = jQuery.parseJSON(data);
              if (res.error) _alert(res.error);
              else choose_publisher(res.publisher_id, res.publisher_name);
            },
            error: function (data) {
              _alert(data.responseJSON.error);
            }
          });
        } else { // Selectionner un editeur existant
          choose_publisher(ui.item.publisher_id, ui.item.value, ui.item);
        }
      }
    });
  }

  // Autocomplete cycle
  $('#article_cycle').autocomplete({
    source: '/x/adm_article_cycle',
    minLength: 3,
    delay: 250,
    select: function (event, ui) {
      if (ui.item.create == '1') { // Creer un nouveau cycle
        $('#article_cycle').addClass('loading');
        $.ajax({
          url: '/x/adm_article_cycle', 
          method: 'POST',
          data: {
            cycle_name: ui.item.value
          }, 
          complete: function (jqXHR) {
            var data = jqXHR.responseJSON;
            $('#article_cycle').removeClass('loading');

            if (data.error) {
              _alert(data.error);
              return;
            }

            $('#article_cycle').addClass('pointer').removeClass('uncomplete').attr('readonly', 'readonly');
            $('#cycle_id').val(data.cycle_id);
            $('#article_tome').focus();
            reloadEvents(this);
          }
        });
      } else { // Selectionner un cycle existante
        choose_cycle(ui.item.cycle_id, ui.item.cycle_name);
      }
    }
  });

  // Autocomplete people
  $('#article_people').autocomplete({
    source: '/x/adm_article_people',
    minLength: 3,
    delay: 250,
    select: function (event, ui) {
      var field = $(this);
      field.attr('readonly', 'readonly').addClass('loading');
      if (ui.item.create == '1') { // Creer un nouveau contributeur
        $('#create_people').dialog({
          title: 'Créer un nouveau contributeur',
          modal: true,
          width: 500,
          buttons: [{
            text: 'Annuler',
            click: function () {
              $(this).dialog('close');
            }
          }, {
            text: 'Valider',
            click: function () {
              $(this).submit();
            }
          }],
          open: function () {
            var people_name = $('#article_people').val().split(' ');
            $('#people_first_name').val(people_name[0]).select();
            $('#people_last_name').val(people_name[1]);
            $('#article_people').val('');
          },
          close: function () {
            field.removeAttr('readonly').removeClass('loading').val('');
          }
        });
        $('#people_first_name').focus();
      } else { // Selectionner un contributeur existant
        choose_people(ui.item.id, ui.item.label, 1);
      }
    }
  });

  // Autocomplete category
  $('#article_category').autocomplete({
    source: function (request, response) {
      $.ajax({
        url: '/x/adm_article_prices',
        dataType: 'json',
        data: {
          term: request.term,
          pricegrid_id: $('#pricegrid_id').val()
        },
        success: function (data) {
          response(data);
        }
      });
    },
    minLength: 0,
    delay: 250,
    select: function (event, ui) {
      if (ui.item.create) { // Creer une nouvelle categorie
        $('#create_price').dialog({
          title: 'Créer une nouvelle catégorie',
          modal: true,
          width: 500
        });
        $('#price_cat').val(ui.item.value).focus();
      } else if (ui.item.none == 1) {
        $('#article_category').val('0').removeAttr('required');
        $('#article_category_div').slideUp();
        $('#article_price').removeAttr('readonly').val('').focus();
      } else {
        $('#article_price').val(ui.item.price);
        $('#article_tva').focus();
      }
    }
  }).keypress(function () { $(this).autocomplete('search'); });

  // Ajouter au lot
  $('#addToBundle').autocomplete({
    source: '/x/adm_articles?v=2',
    minLength: 3,
    delay: 250,
    select: function (event, ui) {
      $.post('/x/adm_links', {
        element_type: 'bundle',
        element_id: $('#article_id').val(),
        linkto_type: 'article',
        linkto_id: ui.item.article_id
      }, function (data) {
        var res = jQuery.parseJSON(data);
        if (res.error) {
          _alert(res.error);
        } else {
          $('#bundle_articles').append(res.link);
          $('.new').slideDown().removeClass('new');
          $('#addToBundle').val('');
          reloadEvents();
        }
      });
    }
  }).keypress(function (event) { if (event.keyCode == 13) { event.preventDefault(); } });
});

document.addEventListener('DOMContentLoaded', function () {

  var article = {
    id: document.querySelector('#article_id').value
  };

  // Remove a link

  function removeLink() {
    var element = this.parentNode,
      linkId = this.dataset['remove_link'];

    element.style.opacity = '.5';

    var req = new XMLHttpRequest();
    req.open('POST', '/admin/links/' + linkId + '/delete');

    req.onload = function () {

      element.style.opacity = '1';

      if (this.status !== 200) {
        _alert('An error ' + this.status + ' occured.');
        return;
      }

      element.parentNode.removeChild(element);
    };

    req.send();
  }
  var removeLinkButtons = document.querySelectorAll('[data-remove_link]');
  for (var i = 0, c = removeLinkButtons.length; i < c; i++) {
    removeLinkButtons[i].addEventListener('click', removeLink);
  }

  // Bulk adding tags

  var addTagsFromInput = function () {
    var addTagsInput = document.querySelector('#add_tags_input');

    addTagsButton.classList.add('disabled');
    addTagsButton.disabled = 'disabled';
    addTagsButton.textContent = 'Ajout...';

    var req = new XMLHttpRequest();
    req.open('POST', '/admin/articles/' + article.id + '/tags/add');
    req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    req.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    req.onload = function () {

      addTagsButton.classList.remove('disabled');
      addTagsButton.removeAttribute('disabled');
      addTagsButton.textContent = 'Ajouter';

      if (this.status !== 200) {
        _alert('Error ' + this.status + ': ' + JSON.parse(this.response).error);
        return;
      }

      addTagsInput.value = '';

      var result = JSON.parse(this.response),
        tagList = document.querySelector('#the_tags');
      result.links.forEach(function (link) {

        var button = document.createElement('a');
        button.className = 'btn btn-danger btn-xs';
        button.dataset['remove_link'] = link.id;
        button.innerHTML = '<span class="fa fa-remove"></a>';
        button.addEventListener('click', removeLink);

        var li = document.createElement('li');
        li.id = 'link_' + link.id;
        li.appendChild(button);
        li.appendChild(document.createTextNode(' ' + link.tag_name));
        tagList.appendChild(li);
      });
    };

    req.send('tags=' + addTagsInput.value);
  };

  // Add tags on button click
  var addTagsButton = document.querySelector('#add_tags_button');
  addTagsButton.addEventListener('click', addTagsFromInput);

  // Add tags at page start (for default tags)
  addTagsFromInput();

  // Add a rayon

  var rayonInput = document.querySelector('#rayon_id');
  rayonInput.addEventListener('change', function () {

    var rayonId = rayonInput.value;

    var req = new XMLHttpRequest();
    req.open('POST', '/admin/articles/' + article.id + '/rayons/add');
    req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    req.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    req.onload = function () {

      rayonInput.querySelector('option:first-child').selected = 'selected';

      // Article is already in rayon
      if (this.status === 409) {
        new Biblys.Notification('L\'article est déjà dans ce rayon.', { type: 'danger' });
        return;
      }

      if (this.status !== 200) {
        _alert('Error ' + this.status + ': ' + JSON.parse(this.response).error);
        return;
      }

      var result = JSON.parse(this.response),
        rayonsList = document.querySelector('#rayons');

      var button = document.createElement('a');
      button.className = 'btn btn-danger btn-xs';
      button.dataset['remove_link'] = result.id;
      button.innerHTML = '<span class="fa fa-remove"></a>';
      button.addEventListener('click', removeLink);

      var li = document.createElement('li');
      li.id = 'link_' + result.id;
      li.appendChild(button);
      li.appendChild(document.createTextNode(' ' + result.rayon_name));
      rayonsList.appendChild(li);
    };

    req.send('rayon_id=' + rayonId);
  });

  // ** PRICE CATEGORIES ** //

  // Create a new price category
  var createPriceForm = document.querySelector('#create_price');
  createPriceForm.addEventListener('submit', function(event) {
    event.preventDefault();
    $('#createPriceSubmit').addClass('loading').attr('disabled', 'disabled');
    $.ajax({
      url: '/x/adm_article_prices',
      method: 'POST',
      data: {
        price_cat: $('#price_cat').val(),
        price_amount: $('#price_amount').val(),
        pricegrid_id: $('#pricegrid_id').val()
      },
      success: function (res) {
        $('#createPriceSubmit').removeClass('loading').removeAttr('disabled');
        if (res.error) _alert(res.error);
        else {
          $('#create_price').dialog('close');
          $('#article_category').val($('#price_cat').val()).autocomplete('search');
        }
      },
      error: function (jqXHR) {
        var error = jqXHR.responseJSON.error;
        window._alert(error);
      }
    });
  });

  // Importation a partir du titre
  // $('#article_title', scope).blur(function () {
  //   if ($('#article_form').data('mode') == 'insert' && !$('#article_ean').val() && $('#article_title').val()) article_search();
  // });

  const titleElement = document.querySelector('#article_title');
  titleElement.addEventListener('blur', function() {
    const form = document.querySelector('#article_form');
    const eanElement = document.querySelector('#article_ean');
    if(form.dataset.mode === 'insert' && eanElement.value === '' && titleElement.value !== '') {
      article_search();
    }
  });
});
