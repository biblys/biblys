
	var loadedCollections = [],
		reorder = null;
	
	var Collection = function(id) {
		Collection.loaded = 0; // counter
		this.id = id;
		this.name = undefined;
		this.publisher = undefined;
		this.articles = [];
	};

	Collection.prototype = {
		load: function(collections) {
		
			var collection = this;
			
			return $.ajax('/x/adm_reorder', {
				data: {
					collection_id: this.id
				},
				dataType: 'json',
				contentType: 'application/json',
				success: function(response) {
					
					if (response.error)
					{
						_alert(response.error);
					}
					
					// Hydrate collection object
					collection.name = response.collection;
					collection.publisher = response.publisher;
					
					// Turn data into Article objects
					if (typeof response.articles !== 'undefined')
					{
						collection.articles = $.map(response.articles, function(item, i) {
							return new Article(item.article_id, item.article_ean, item.article_title, item.article_url, collection.publisher, item.dnr, item.stock, item.sales, item.lastSale);
						});
					}
					
					// Update progressbar
					Collection.loaded++;
					var percent = Collection.loaded / (Collection.loaded + collections.length) * 100 + '%';
					$('#progressValue').text(Collection.loaded);
					$('.progress-bar').html(Math.round(parseFloat(percent))+' %').css({ 'width': percent+'%'});
					
					// Add the collection to the loaded collection array
					loadedCollections.push(collection);
					
					// Load next collection (if any left)
					if (collections.length)
					{
						next = collections.pop();
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
			
			var doReorder = $('<tbody id="doReorder"></tbody>'),
				doNotReorder = $('<tbody id="doNotReorder"></tbody>'),
				articles_dr = [],
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
					
					// Put it it the right table
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

	var Article = function(id, ean, title, url, publisher, donotreorder,  stock, sales, lastSale) {
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
		var red = this.stock ? '' : ' class="alert alert-danger"',
			article = '<tr'+red+' id="article_'+this.id+'">' +
			'<td><a href="/'+this.url+'" tabindex=-1>'+this.title+'</a></td>' +
			'<td class="nowrap">'+this.lastSale+'</td>' +
			'<td class="center">'+this.stock+'</td>' +
			'<td class="center">'+this.sales+'</td>';
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
		var collections = $('#collections').data('ids').split(',');
		var total = collections.length;
		
		// Turn ids into objects
		collections = $.map(collections, function(item, i) {
			return new Collection(item);
		});
		
		// Load first collections
		var first = collections.pop();
		first.load(collections);
		
		
		// Change reorder status
		$('.reorder').on('click', '.changeStatus', function() {
			
			var button = $(this);
			
			$.post("/pages/adm_reorder",
				{
					article_id: button.data('article_id'),
					changeStatus: 1		
				}, function(res) {
					if (res.error)
					{
						_alert(res.error);
					}
					else
					{
						$('#article_'+res.id).remove();
						var article = new Article(res.id, res.ean, res.title, res.url, res.publisher, res.dnr);
						
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
