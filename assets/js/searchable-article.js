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

import BiblysNotification from './notification';

export default class SearchableArticle {
  constructor(article, table) {
    this.id = article.id;
    this.title = article.title;
    this.url = article.url;
    this.table = table;
  }

  update(callback) {
    this.tr = document.createElement('tr');
    this.tr.innerHTML = `
      <td>
        <a href="${this.url}">${this.title}</a>
      </td>
      <td class="search-terms"><span class="fa fa-spinner fa-pulse fa-fw"></span></td>
    `;
    this.table.insertAdjacentElement('afterBegin', this.tr);

    fetch('/admin/articles/' + this.id + '/refresh-search-terms', {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        Accept: 'application/json'
      }
    })
      .then(function(response) {
        return response.json();
      })
      .then(
        function(json) {
          if (json.error) {
            throw json.error;
          }

          this.displayTerms(json.terms);
          callback();
        }.bind(this)
      )
      .catch(function(error) {
        new BiblysNotification(error, { type: 'warning', sticky: true });
      });
  }

  displayTerms(terms) {
    this.tr.querySelector('.search-terms').textContent = terms;
  }
}
