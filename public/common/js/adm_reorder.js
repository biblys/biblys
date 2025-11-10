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

/* global $ */

const loadedCollections = [];

const Collection = function (id) {
  Collection.loaded = 0; // counter
  this.id = id;
  this.name = undefined;
  this.publisher = undefined;
  this.articles = [];
};

Collection.prototype = {
  load: function(collections) {

    const collection = this;

    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');

    return $.ajax('/pages/adm_reorderable_articles', {
      data: {
        collection_id: this.id,
        start_date: startDate.value,
        end_date: endDate.value,
      },
      dataType: 'json',
      contentType: 'application/json',
      success: function(response) {
					
        if (response.error) {
          window._alert(response.error);
        }
					
        // Hydrate collection object
        collection.name = response.collection;
        collection.publisher = response.publisher;
					
        // Turn data into Article objects
        if (typeof response.articles !== 'undefined')
        {
          collection.articles = $.map(response.articles, function(item) {
            return new Article(item.article_id, item.article_ean, item.article_title, item.article_url, collection.publisher, item.dnr, item.stock, item.sales, item.lastSale);
          });
        }
					
        // Update progressbar
        Collection.loaded++;
        const percent = Collection.loaded / (Collection.loaded + collections.length) * 100;
        $('#progressValue').text(Collection.loaded);
        $('.progress-bar').html(Math.round(percent)+' %').css({ 'width': percent+'%'});
					
        // Add the collection to the loaded collection array
        loadedCollections.push(collection);
					
        // Load next collection (if any left)
        if (collections.length)
        {
          const next = collections.pop();
          next.load(collections);
        }
					
        // Else, start rendering process
        else 
        {
          $('#loadingBar').removeClass('progress-bar-striped');
          $('#loadingText').text('Affichage...');
						
          setTimeout(function() { collection.process(loadedCollections); }, 500);
        }
					
					
      }
    });
  },
  process: function(collections) {

    const doReorder = $('<tbody id="doReorder"></tbody>'),
      doNotReorder = $('<tbody id="doNotReorder"></tbody>');
    let articles_dr = [],
      articles_dnr = [],
      article,
      thead;

    // Cycle through each collection
    $.each(collections.reverse(), function(i, item) {
				
      articles_dr = [];
      articles_dnr = [];
				
      // Collection table head
      thead = '<tr>' +
					'<th colspan=7>' +
						this.name + ' (' +this.publisher + ')' +
					'</th>' +
				'</tr>';
				
      // Cycle through each articles for this collection
      $.each(item.articles, function(i, item) {
					
        // Put it in the right table
        article = item.render();
        if (item.donotreorder) {
          articles_dnr.push(article);
        }
        else {
          articles_dr.push(article);
        }
					
      });
				
      if (articles_dr.length) 
      {
        doReorder.append(thead);
        doReorder.append(articles_dr);
      }
      if (articles_dnr.length) 
      {
        doNotReorder.append(thead);
        doNotReorder.append(articles_dnr);
      }
    });
			
    $('#doReorder').replaceWith(doReorder);
    $('#doNotReorder').replaceWith(doNotReorder);
    $('table.reorder').show();
			
    $('#loadingText, #loadingBar').remove();
			
  }
};

const Article = function(id, ean, title, url, publisher, donotreorder,  stock, sales, lastSale) {
  this.id = id;
  this.ean = ean;
  this.title = title;
  this.url = url;
  this.donotreorder = +donotreorder;
  this.stock = stock;
  this.sales = sales;
  this.lastSale = lastSale || 'N/A';
};

Article.prototype.render = function() {
  const red = this.stock ? '' : ' class="alert alert-danger"';
  let article = '<tr' + red + ' id="article_' + this.id + '">' +
    '<td><a href="/' + this.url + '" tabindex=-1>' + this.title + '</a></td>' +
    '<td class="nowrap">' + this.lastSale + '</td>' +
    '<td class="center">' + this.stock + '</td>' +
    '<td class="center">' + this.sales + '</td>';
  if (!this.donotreorder)
  {
    article +=
				'<td>' +
					'<input type="number" name="article['+this.id+']" value="0" class="mini right">' +
				'</td>' +
				'<td>' +
					'<i class="fa fa-chevron-down pointer changeStatus" data-article_id="'+this.id+'" title="Ne jamais réassortir" data-article_status="">'+
				'</td>';
  }
  else
  {
    article +=
				'<td>' +
					'<i class="fa fa-chevron-up pointer changeStatus" data-article_id="'+this.id+'" title="Remettre dans les titres à réassortir" data-article_status="">'+
				'</td>';
  }
  article += '</tr>';
		
  return article;
};
	
	
$(document).ready( function () {
		
  // Get collections ids
  const collectionElementIds = $('#collections').data('ids');
  if (typeof collectionElementIds === 'undefined') {
    return;
  }

  const collectionIds = collectionElementIds.split(',');

  // Turn ids into objects
  const collections = $.map(collectionIds, function(item) {
    return new Collection(item);
  });
		
  // Load first collections
  const first = collections.pop();
  first.load(collections);

  // Change reorder status
  $('.reorder').on('click', '.changeStatus', function() {

    const button = $(this);

    $.post('/pages/adm_reorder',
      {
        article_id: button.data('article_id'),
        changeStatus: 1		
      }, function(res) {
        if (res.error)
        {
          window._alert(res.error);
        }
        else
        {
          $('#article_'+res.id).remove();
          const article = new Article(res.id, res.ean, res.title, res.url, res.publisher, res.dnr);

          if (article.donotreorder)
          {
            $('#doNotReorder').append(article.render());
          }
          else
          {	
            $('#doReorder').append(article.render());
          }
        }
					
      },
      'json'
    );
  });
		
});
