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

import jQuery from 'jquery';
require('jquery-ui/ui/widgets/autocomplete');
require('jquery-ui/themes/base/autocomplete.css');

// TODO: refactor without jQuery
export default class EntitySearchField {
  constructor(element) {
    // For each entity search field
    var field = jQuery(element),
      entity = field.attr('data-entity_search'),
      changeButton = jQuery('<button class="btn btn-default" disabled>Modifier</button>'),
      submitButton = field.closest('form').find('button[type=submit]'),
      idField = jQuery('<input type="hidden" name="' + entity.toLowerCase() + '_id">');

    console.log(field);

    // Insert hidden entity_id field after search field
    field.after(idField);

    // Insert disabled change button after the field
    field.after(changeButton);

    // Add XHR autocomplete capability
    field.autocomplete({
      // Get results
      source: function(request, response) {
        var source = '/' + entity.toLowerCase() + 's/';
        jQuery.getJSON(source, { filter: request.term }, function(results) {
          results = jQuery.map(results, function(entity, index) {
            return { label: entity.label, value: entity.id };
          });
          response(results);
        });
      },

      // On result selection
      select: function(event, ui) {
        // Change field value and disable it
        field.val(ui.item.label).attr('disabled', true);

        // Add entity id to id field
        idField.val(ui.item.value);

        // Enable change button
        changeButton.prop('disabled', false);

        // Enable submit button
        submitButton.prop('disabled', false);

        return false;
      }
    });

    // Change selected item
    changeButton.on('click', function() {
      // Reset & enable search field
      field.val('').prop('disabled', false);

      // Reset entity id field
      idField.val();

      // Disable search button
      changeButton.prop('disabled', true);

      // Disable submit button
      submitButton.prop('disabled', true);
    });
  }
}
