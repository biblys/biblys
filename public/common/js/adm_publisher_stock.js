$(document).ready(function() {
  $('.stock').blur(function() {
    title = $(this).data('title');
    id = $(this).data('id');
    stock = $(this).html();
    var n = notify(
      'Mise &agrave; jour du stock de<br />&laquo; ' + title + ' &raquo; en cours...',
      'sticky'
    );
    $.ajax({
      url: '/x/adm_publisher_stock',
      data: {
        article_id: id,
        article_publisher_stock: stock
      },
			complete: function (jqXHR) {
				notify('close', n);
        const data = jqXHR.responseJSON;
        if (data.error) {
          _alert(data.error);
        } else {
          td = $('#article_' + id + ' > td.stock');
          if (stock > 3) td.removeClass('alert');
          else td.addClass('alert');
        }
      }
    });
  });
});
