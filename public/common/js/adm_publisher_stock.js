// eslint-disable-next-line strict
'use strict';

window.jQuery(document).ready(function() {
  window.jQuery('.stock').blur(function() {
    const title = window.jQuery(this).data('title');
    const id = parseInt(window.jQuery(this).data('id'));
    const stock = parseInt(window.jQuery(this).html());
    var n = window.notify(
      'Mise &agrave; jour du stock de<br />&laquo; ' + title + ' &raquo; en cours...',
      'sticky'
    );
    window.jQuery.ajax({
      url: '/x/adm_publisher_stock',
      data: {
        article_id: id,
        article_publisher_stock: stock
      },
      complete: function (jqXHR) {
        window.notify('close', n);
        const data = jqXHR.responseJSON;
        if (data.error) {
          window._alert(data.error);
        } else {
          const td = window.jQuery('#article_' + id + ' > td.stock');
          if (stock > 3) {
            td.removeClass('alert');
          }
          else td.addClass('alert');
        }
      }
    });
  });
});
