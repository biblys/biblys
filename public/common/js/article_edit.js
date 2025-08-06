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
import EntitySearchField from '/common/js/entity-search-field.js';

/** Collection autocomplete */
document.addEventListener('DOMContentLoaded', function() {
  const field = document.getElementById('collection-search-field');
  window.collectionField = new EntitySearchField(field, {
    action: {
      label: 'Créer une collection « %query% »',
      onSelect: (field, query) => {
        $('#collection_name').val(query);
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
      }
    },
  });
});

/** Publisher autocomplete (upon collection creation) */
function initPublisherAutocomplete() {
  $('#collection_publisher').autocomplete({
    source: '/x/adm_article_publisher',
    minLength: 3,
    delay: 250,
    select: function (event, ui) {
      if (ui.item.create === 1) { // Créer un nouvel editeur
        $('#collection_publisher').addClass('loading');
        $.post({
          url: '/x/adm_article_publisher',
          data: {
            publisher_name: ui.item.value
          },
          success: function (data) {
            $('#collection_publisher').removeClass('loading');
            const res = jQuery.parseJSON(data);
            if (res.error) window._alert(res.error);
            else choose_publisher(res.publisher_id, res.publisher_name);
          },
          error: function (data) {
            window._alert(data.responseJSON.error.message);
          }
        });
      } else {
        choose_publisher(ui.item.publisher_id, ui.item.value, ui.item);
      }
    }
  });
}

/** Cycle autocomplete */
document.addEventListener('DOMContentLoaded', function() {
  const field = document.getElementById('cycle-search-field');
  new EntitySearchField(field, {
    action: {
      label: 'Créer un cycle « %query% »',
      onSelect: (field, query) => {
        $.ajax({
          url: '/pages/adm_article_cycle',
          method: 'POST',
          data: {
            cycle_name: query
          },
          complete: function(jqXHR) {
            const data = jqXHR.responseJSON;

            if (data.error) {
              window._alert(data.error);
              return;
            }

            const { cycle_id, cycle_name } = data;
            field.selectResult({
              label: cycle_name,
              value: cycle_id,
            });

            $('#article_tome').focus();
          }
        });
      }
    },
  });
});


/* eslint-env jquery */

function handleHttpError(response) {
  if (response.error) {
    throw new Error(response.error.message);
  }

  return response;
}

// Choisir un editeur (creation de collection)
function choose_publisher(publisher_id, publisher_name) {
  $('#collection_publisher').addClass('pointer').removeClass('uncompleted').attr('readonly', 'readonly').val(publisher_name);
  $('#collection_publisher_id').val(publisher_id);
}

// Recherche dans les bases externes
function article_search() {
  let field;
  const articleEanField = $('#article_ean');
  if (articleEanField.val().length) {
    field = articleEanField;
  } else if ($('#article_title').val().length) {
    field = $('#article_title');
  } else return 0;
  if (field.val() !== field.data('searched')) {
    field.addClass('loadingOrange');
    const notification = new window.Biblys.Notification('Recherche de <em>' + field.val() + '</em> dans les bases externes...', {
      sticky: true,
      loader: true
    });

    const searchUrl = new URL('/pages/adm_noosfere_search', window.location.origin);
    searchUrl.searchParams.append('q', field.val());
    fetch(searchUrl, {
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
      reloadArticleAdminEvents(this);
    }).catch(function (error) {
      window._alert(error.message);
    });
  }
  return 0;
}

// Protection anti-doublons
function duplicate_check(field) {
  if (field.val() !== '' && field.val() !== field.data('checked')) {
    const notification = new window.Biblys.Notification('Recherche de doublons potentiels...', { sticky: true, loader: true });
    field.addClass('loading');
    $.get('/x/adm_article_duplicate_check', {
      article_id: $('#article_id').val(),
      collection_id: $('#collection_id').val(),
      field: field.attr('id'),
      value: field.val()
    }, function (res) {
      field.removeClass('loading').data('checked', field.val());
      notification.remove();
      if (res.error) window._alert(res.error);
      else if (res.content.length > 0) {
        new window.Biblys.Notification(res.message, { timeout: 5000, type: 'warning', sound: 'pop' });
        $('#duplicates').append(res.content);
        $('#duplicates_fieldset').slideDown();
      }
      reloadArticleAdminEvents();
    });
  }
}

function reloadArticleAdminEvents(scope) {

  // Lier les doublons potentiels a la fiche en cours de creation
  $('.linkto').click(function () {
    $('#article_link_to').val($(this).data('id'));
    new window.Biblys.Notification('Les livres ont bien été liés !', { sound: 'pop', type: 'success' });
  }).removeClass('linkto');

  // Empêcher validation formulaire par touche entree si champ ean
  $('.article_ean').keypress(function (event) { if (event.keyCode === 13) { event.preventDefault(); } });
  $('#article_people').keypress(function (event) { if (event.keyCode === 13) { event.preventDefault(); $('#article_people').autocomplete('search'); } });

  $('.reimport.event').click(function () { article_search(); }).removeClass('event');

  // Changer le type d'article
  $('#type_id', scope).change(function () {
    const type_id = $(this).val();
    if (type_id === 2) {
      $('#ebooks_fieldset').slideDown();
    } else if (type_id === 8) {
      $('#bundle_fieldset').slideDown();
    } else {
      $('#ebooks_fieldset').slideUp();
    }
  });

  // Verification doublons
  $('.article_duplicate_check.event', scope).blur(function () { duplicate_check($(this)); }).removeClass('event');

  // Verification validité ISBN + Import
  $('.article_ean.event', scope).blur(function () {
    const field = this;
    if (field.value !== '' && field.value !== field.dataset.checked) {
      field.classList.add('loading');
      const notification = new window.Biblys.Notification('Validation de l’EAN...', { sticky: true, loader: true });

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
        /**
         * @param json.isbn {string} Valid ISBN
         */
        .then(function (json) {
          field.classList.remove('loading');
          notification.remove();
          field.value = json.isbn;
          field.dataset.checked = json.isbn;
          if ($('#article_form').data('mode') === 'insert' && field.id === 'article_ean') {
            article_search();
          }
        }).catch(function (error) {
          field.classList.remove('loading');
          notification.remove();
          new window.Biblys.Alert(error.message, { title: 'Erreur de validation ISBN' });
        });
    }
  }).removeClass('event');

  // Auto-import
  $('.autoimport').each(function () {
    article_search();
    $(this).removeClass('autoimport');
  });

  // Importer une fiche
  $('.article-import').click(function () {
    window.overlay('Importation en cours...');
    const notification = new window.Biblys.Notification(
      'Importation de la fiche en cours...',
      { sticky: true, loader: true }
    );
    $.ajax({
      url: '/pages/adm_noosfere_import',
      data: {
        mode: 'import',
        ean: $(this).data('ean'),
        asin: $(this).data('asin'),
        noosfere_id: $(this).data('noosfere_id')
      },
      /**
       * @param data.error {string} Error message if any
       * @param data.publisher_id {number} ID of the publisher
       * @param data.collection_id {number} ID of the collection
       * @param data.article_collection {string} Name of the collection
       * @param data.article_publisher {string} Name of the publisher
       * @param data.pricegrid_id {number} ID of the price grid
       * @param data.cycle_id {number} ID of the cycle
       * @param data.article_cycle {string} Name of the cycle
       * @param data.article_people {Array} List of people associated with the article
       */
      success: function (data) {
        notification.remove();

        if (data.error) {
          window.overlay('hide');
          window._alert(data.error);
        } else {
          $.each(data, function (key, val) {
            if (key !== 'article_collection') $('#' + key).val(val);
          });
          if (data.collection_id) choose_collection(data.collection_id, data.article_collection, data.publisher_id, data.article_publisher, data.pricegrid_id);
          if (data.cycle_id) choose_cycle(data.cycle_id, data.article_cycle);
          if (data.article_people !== undefined && data.article_people != null) {
            $.each(data.article_people, function (pkey, people) {
              window.overlay('Importation en cours...');
              _addContribution(people.people_id, people.job_id);
            });
          }
          $('#article_people').val('');
          $('#article_import').hide();
          window.overlay('hide');
          duplicate_check($('#article_asin'));
          duplicate_check($('#article_noosfere_id'));
          duplicate_check($('#article_item'));
          duplicate_check($('#article_title'));
          new window.Biblys.Notification('Importation réussie !', { type: 'success' });
        }
      },
      /**
       * @param jqXHR.responseJSON.error.message {string} Error message if any
       */
      error: function (jqXHR) {
        notification.remove();
        window.overlay('hide');
        const error = jqXHR.responseJSON.error.message;
        window._alert('Erreur lors de l\'importation : ' + error);
      }
    });
  });

  // Si champ changeThis non valide
  $('.changeThis', scope).blur(function () {
    if ($(this).attr('readonly') !== 'readonly') $(this).val('');
  });

  // Créer une nouvelle collection
  $('#createCollection.event', scope).submit(function (e) {
    e.preventDefault();
    $.post({
      url: '/api/collections',
      data: {
        collection_name: $('#collection_name').val(),
        collection_publisher: $('#collection_publisher').val(),
        collection_publisher_id: $('#collection_publisher_id').val()
      },
      dataType: 'json',
      success: function (res) {
        window.collectionField.selectResult({
          label: res.collection_name,
          value: res.collection_id,
        });
        $('#article_publisher').val(res.collection_publisher);
        $('#publisher_id').val(res.collection_publisher_id);
        $('#createCollection').dialog('close');
        $('#article_number').focus();
      },
      error: function (data) {
        const { error } = data.responseJSON;
        window._alert(error.message);
      }
    });
  }).removeClass('event');

  // Supprimer un lien
  $('.deleteLink').click(function () {
    const linkId = $(this).data('link_id');
    $('#link_' + linkId).fadeTo('fast', 0.5);
    $.post(`/admin/links/${linkId}/delete`, function (res) {
      if (res.error) {
        $('#link_' + linkId).fadeTo('fast', 1);
        window._alert(res.error);
      } else $('#link_' + linkId).slideUp();
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
      /**
       * @param jqXHR.responseJSON.error {string} Error message if any
       * @param jqXHR.responseJSON.award {string} HTML of the newly created award
       */
      complete: function (jqXHR) {
        const res = jqXHR.responseJSON;
        $('#addAwardSubmit').removeClass('loading').removeAttr('disabled');
        if (res.error) window._alert(res.error);
        else {
          $('#add_award').dialog('close');
          const awardsSection = $('#awards');
          awardsSection.append(res.award);
          $('.newAward').slideDown().removeClass('newAward');
          reloadArticleAdminEvents(awardsSection);
        }
      }
    });
  }).removeClass('event');

  $('.deleteAward', scope).click(function () {
    const award_id = $(this).data('award_id');
    $('#award_' + award_id).fadeTo('fast', '0.5');
    $.ajax({
      url: '/x/adm_article_award',
      data: {
        del: 1,
        award_id: award_id
      },
      complete: function (jqXHR) {
        const res = jqXHR.responseJSON;
        if (res.error) {
          window._alert(res.error);
          $('#award_' + award_id).fadeTo('fast', '1');
        } else $('#award_' + award_id).slideUp();
      }
    });
  });
}

$(document).ready(function () {
  reloadArticleAdminEvents();

  // Créer un nouveau contributeur
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
        if (data.error) {
          window._alert(data.error);
        } else {
          $('#article_people').removeClass('uncompleted').removeAttr('readonly').val('');
          $('#people_first_name').val('');
          $('#people_last_name').val('');
          $('#create_people').dialog('close');
          _addContribution(data.people_id, data.job_id);
        }
      },
      error: function(jqXHR) {
        window._alert(`Erreur: ${jqXHR.responseJSON.error.message}`);
      },
    });
  }).removeClass('e');

  /* AUTOCOMPLETE */

  // Autocomplete people
  $('#article_people').autocomplete({
    source: '/x/adm_article_people',
    minLength: 3,
    delay: 250,
    select: function (event, ui) {
      const field = $(this);
      field.attr('readonly', 'readonly').addClass('loading');
      if (ui.item.create === 1) { // Créer un nouveau contributeur
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
            const articlePeopleField = $('#article_people');
            const people_name = articlePeopleField.val().split(' ');
            $('#people_first_name').val(people_name[0]).select();
            $('#people_last_name').val(people_name[1]);
            articlePeopleField.val('');
          },
          close: function () {
            field.removeAttr('readonly').removeClass('loading').val('');
          }
        });
        $('#people_first_name').focus();
      } else { // Sélectionner un contributeur existant
        _addContribution(ui.item.id, 1);
        field.removeAttr('readonly').removeClass('loading').val('');
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
    /**
     * @param ui.item.none {number} 1 if no category is selected
     */
    select: function (event, ui) {
      if (ui.item.create) { // Créer une nouvelle catégorie
        $('#create_price').dialog({
          title: 'Créer une nouvelle catégorie',
          modal: true,
          width: 500
        });
        $('#price_cat').val(ui.item.value).focus();
      } else if (ui.item.none === 1) {
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
    source: '/pages/adm_article_search',
    minLength: 3,
    delay: 250,
    select: function (event, ui) {
      const articleId = $('#article_id').val();
      $.post(`/api/admin/articles/${ui.item.article_id}/add-to-bundle`, {
        bundle_id: articleId,
      },
      /**
       * @param res {object}
       * @param res.error {string} Error message if any
       * @param res.link_id {number} ID of the newly created link
       * @param res.article_title {string} Title of the article
       * @param res.article_authors {string} Authors of the article
       * @param res.article_collection {string} Collection of the article
       * @param res.article_url {string} URL of the article
       */
      function (res) {
        if (res.error) {
          window._alert(res.error);
        } else {
          const row = `<tr id="link_${res.link_id}" class="new">
                    <td>${res.article_title}</td>
                    <td>${res.article_authors}</td>
                    <td>${res.article_collection}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm deleteLink pointer" data-link_id="${res.link_id}">
                            <i aria-label="Supprimer" class="fa-solid fa-chain-broken"></i>
                        </button> 
                    </td>
                </tr>`;
          $('#bundle_articles').append(row);
          $('.new').slideDown().removeClass('new');
          $('#addToBundle').val('');
          reloadArticleAdminEvents();
        }
      });
    }
  }).keypress(function (event) { if (event.keyCode === 13) { event.preventDefault(); } });
});

document.addEventListener('DOMContentLoaded', function () {

  const autoImport = document.querySelector('#article_form')?.dataset.autoimport;
  if (autoImport) {
    article_search();
  }

  const article = {
    id: document.querySelector('#article_id')?.value
  };

  // Remove a link

  function removeLink() {
    const element = this.parentNode,
      linkId = this.dataset['remove_link'];

    element.style.opacity = '.5';

    const req = new XMLHttpRequest();
    req.open('POST', '/admin/links/' + linkId + '/delete');

    req.onload = function () {

      element.style.opacity = '1';

      if (this.status !== 200) {
        window._alert('An error ' + this.status + ' occurred.');
        return;
      }

      element.parentNode.removeChild(element);
    };

    req.send();
  }

  const removeLinkButtons = document.querySelectorAll('[data-remove_link]');
  let i = 0;
  const c = removeLinkButtons.length;
  for (; i < c; i++) {
    removeLinkButtons[i].addEventListener('click', removeLink);
  }

  // Bulk adding tags

  function addTagsFromInput() {
    const addTagsInput = document.querySelector('#add_tags_input');
    if (!addTagsInput) {
      return;
    }

    addTagsButton.classList.add('disabled');
    addTagsButton.disabled = 'disabled';
    addTagsButton.textContent = 'Ajout...';

    const req = new XMLHttpRequest();
    req.open('POST', '/admin/articles/' + article.id + '/tags/add');
    req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    req.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    req.onload = function () {

      addTagsButton.classList.remove('disabled');
      addTagsButton.removeAttribute('disabled');
      addTagsButton.textContent = 'Ajouter';

      if (this.status !== 200) {
        window._alert('Error ' + this.status + ': ' + JSON.parse(this.response).error);
        return;
      }

      addTagsInput.value = '';

      const result = JSON.parse(this.response),
        tagList = document.querySelector('#the_tags');
      /**
       * @param link.tag_name {string} Name of the tag
       */
      result.links.forEach(function (link) {

        const button = document.createElement('a');
        button.className = 'btn btn-danger btn-sm';
        button.dataset['remove_link'] = link.id;
        button.innerHTML = '<span class="fa fa-remove"></a>';
        button.addEventListener('click', removeLink);

        const li = document.createElement('li');
        li.id = 'link_' + link.id;
        li.appendChild(button);
        li.appendChild(document.createTextNode(' ' + link.tag_name));
        tagList.appendChild(li);
      });
    };

    req.send('tags=' + addTagsInput.value);
  }

  // Add tags on button click
  const addTagsButton = document.querySelector('#add_tags_button');
  if (addTagsButton) {
    addTagsButton.addEventListener('click', addTagsFromInput);
  }

  // Add tags at the page start (for default tags)
  addTagsFromInput();

  // Add rayon
  const rayonInput = document.querySelector('#rayon_id');
  rayonInput && rayonInput.addEventListener('change', function () {

    const rayonId = rayonInput.value;

    const req = new XMLHttpRequest();
    req.open('POST', '/admin/articles/' + article.id + '/rayons/add');
    req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    req.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    req.onload = function () {

      rayonInput.querySelector('option:first-child').selected = 'selected';

      // Article is already in rayon
      if (this.status === 409) {
        new window.Biblys.Notification('L\'article est déjà dans ce rayon.', { type: 'danger' });
        return;
      }

      if (this.status !== 200) {
        window._alert('Error ' + this.status + ': ' + JSON.parse(this.response).error);
        return;
      }

      /**
       * @var result.rayon_name {string} Name of the rayon
       */
      const result = JSON.parse(this.response),
        rayonsList = document.querySelector('#rayons');

      const button = document.createElement('a');
      button.className = 'btn btn-danger btn-sm';
      button.dataset['remove_link'] = result.id;
      button.innerHTML = '<span class="fa fa-remove"></a>';
      button.addEventListener('click', removeLink);

      const li = document.createElement('li');
      li.id = 'link_' + result.id;
      li.appendChild(button);
      li.appendChild(document.createTextNode(' ' + result.rayon_name));
      rayonsList.appendChild(li);
    };

    req.send('rayon_id=' + rayonId);
  });

  // ** PRICE CATEGORIES ** //

  // Create a new price category
  const createPriceForm = document.querySelector('#create_price');
  if (createPriceForm) {
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
          if (res.error) window._alert(res.error);
          else {
            $('#create_price').dialog('close');
            $('#article_category').val($('#price_cat').val()).autocomplete('search');
          }
        },
        error: function (jqXHR) {
          const error = jqXHR.responseJSON.error.message;
          window._alert(error);
        }
      });
    });
  }

  const titleElement = document.querySelector('#article_title');
  if (titleElement) {
    titleElement.addEventListener('blur', function() {
      const form = document.querySelector('#article_form');
      const eanElement = document.querySelector('#article_ean');
      if(form.dataset.mode === 'insert' && eanElement.value === '' && titleElement.value !== '') {
        article_search();
      }
    });
  }
});

document.addEventListener('DOMContentLoaded', function() {
  if (!_isOnArticleEditorPage()) {
    return;
  }

  _loadContributions();
  document.querySelectorAll('.contribution-role-selector').forEach(element => {
    element.addEventListener('change', _changeContributionRole);
  });
  document.querySelectorAll('.contribution-delete-button').forEach(element => {
    element.addEventListener('click', _removeContribution);
  });
});

function _isOnArticleEditorPage() {
  const articleEditor = document.querySelector('.article-editor');
  return !!articleEditor;
}

function _loadContributions() {
  const articleId = document.querySelector('#article_id')?.value;
  if (!articleId) {
    return;
  }

  fetch(`/api/admin/articles/${articleId}/contributions`, {
    method: 'GET',
    credentials: 'same-origin',
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
    },
  }).then(function (response) {
    return response.json();
    /**
     * @param data.error {object} Error object if any
     * @param data.contributors {Array} List of contributors
     */
  }).then(function (data) {
    if (data.error) {
      return window._alert(data.error.message);
    }
    data.contributors.forEach(_addContributorLine);
  });
}

function _addContribution(peopleId, jobId) {
  const articleId = document.querySelector('#article_id').value;
  fetch(`/api/admin/articles/${articleId}/contributions`, {
    method: 'POST',
    credentials: 'same-origin',
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
    },
    body: JSON.stringify({
      people_id: peopleId,
      job_id: jobId
    }),
  }).then(function (response) {
    return response.json();
    /**
     * @param data.error {object} Error object if any
     * @param data.contributor {object} Contributor object with details
     */
  }).then(function (data) {
    if (data.error) {
      return window._alert(data.error.message);
    }

    _addContributorLine(data.contributor);
    $('#article_authors').val(data.authors);
    $('#article_people').val('');
  });
}

function _changeContributionRole() {
  const articleId = document.querySelector('#article_id').value;
  const contributionId = this.dataset.contribution_id;
  const jobId = this.value;

  $('#contribution_' + contributionId).fadeTo('slow', '0.5');

  fetch(`/api/admin/articles/${articleId}/contributions/${contributionId}`, {
    method: 'PUT',
    credentials: 'same-origin',
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
    },
    body: JSON.stringify({ job_id: jobId }),
  }).then(function (response) {
    return response.json();
  }).then(function (data) {

    $('#contribution_' + contributionId).fadeTo('fast', '1');

    if (data.error) {
      return window._alert(data.error);
    }

    $('#article_authors').val(data.authors);
    $('#article_people').focus();
  });
}

function _removeContribution() {
  const articleId = document.querySelector('#article_id').value;
  const contributionId = this.dataset.contribution_id;
  const contributionElement = $('#contribution_' + contributionId);

  contributionElement.fadeTo('slow', '0.5');

  fetch(`/api/admin/articles/${articleId}/contributions/${contributionId}`, {
    method: 'DELETE',
    credentials: 'same-origin',
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
    },
  }).then(function (response) {
    return response.json();
  }).then(function (data) {

    contributionElement.fadeTo('fast', '1');

    if (data.error) {
      window._alert(data.error);
    }

    contributionElement.hide();
    $('#article_authors').val(data.authors);
  });
}

function _addContributorLine({
  contribution_id: contributionId,
  contributor_name: contributorName,
  contributor_job_id: contributorJobId,
  job_options: jobOptions,
}) {
  const contributionLineSelector = `#contribution_${contributionId}`;

  const contributorLine = _createElementFromHTML(`
    <p id="contribution_${contributionId}" class="article_role">
      <label>${contributorName}&nbsp;:</label>
      <select class="contribution-role-selector" data-contribution_id="${contributionId}">
      </select>
      <a 
          class="btn btn-danger btn-sm contribution-delete-button" 
          data-contribution_id="${contributionId}"
      >
          <span class="fa fa-remove"></span>
      </a>
    </p>
  `);

  $('#people_list').append(contributorLine);

  /**
   * @param jobOption.job_id {number} ID of the job
   * @param jobOption.job_name {string} Name of the job
   */
  const jobOptionsElements = jobOptions.map((jobOption) => {
    return _createElementFromHTML(`
      <option value="${jobOption.job_id}">${jobOption.job_name}</option>
    `);
  });
  $(`${contributionLineSelector} select`).append(jobOptionsElements).focus();

  const optionForSelectedJob = document.querySelector(`${contributionLineSelector} option[value="${contributorJobId}"]`);
  optionForSelectedJob.selected = true;

  const roleSelector = document.querySelector(`${contributionLineSelector} .contribution-role-selector`);
  const deleteButton = document.querySelector(`${contributionLineSelector} .contribution-delete-button`);
  roleSelector.addEventListener('change', _changeContributionRole);
  deleteButton.addEventListener('click', _removeContribution);
}

function _createElementFromHTML(html) {
  const template = document.createElement('template');
  html = html.trim();
  template.innerHTML = html;
  return template.content.firstChild;
}