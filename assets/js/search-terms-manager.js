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
