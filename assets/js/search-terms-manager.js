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
import SearchableArticle from './searchable-article';

export default class SearchTermsManager {
  constructor(articlesTable) {
    this.articles = [];
    this.articlesTable = articlesTable;
    this.progressBar = document.querySelector('#progress-bar');
    this.articleCount = document.querySelector('#article-count');
    this.articleCountTotal = +this.articleCount.textContent;
    this.articleCountRemaining = this.articleCountTotal;
    this.articleProcessed = 0;
    this.progressBar.value = 0;
    this.progressBar.max = this.articleCountTotal;
    this.loadArticles(() => this.updateNextArticle());
  }

  loadArticles(callback) {
    fetch('/admin/articles/search-terms/', {
      credentials: 'same-origin',
      headers: {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
      .then(response => {
        return response.json();
      })
      .then(json => {
        if (json.error) {
          throw json.error;
        }

        this.articleCountRemaining = json.count;
        json.articles.forEach(article => {
          this.articles.push(new SearchableArticle(article, this.articlesTable));
        });
        callback();
      })
      .catch(error => {
        new BiblysNotification(error, { type: 'warning', sticky: true });
      });
  }

  updateNextArticle() {
    var article = this.articles.shift();

    // If there is no article left
    if (typeof article === 'undefined') {
      // If there is still more article to process, load a new batch
      if (this.articleCountRemaining > 0) {
        this.loadArticles(() => this.updateNextArticle());
      }

      return;
    }

    article.update(
      function() {
        this.updateProgress();
        this.updateNextArticle();
      }.bind(this)
    );
  }

  updateProgress() {
    this.progressBar.value += 1;
    this.articleCountRemaining -= 1;
    this.articleCount.textContent = this.articleCountRemaining;
  }
}
