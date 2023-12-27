
function addLink(type,id) {
  window.jQuery.post('/x/adm_links', {
    element_type: ''+window.jQuery('#element').data('type')+'',
    element_id: ''+window.jQuery('#element').data('id')+'',
    linkto_type: ''+type+'',
    linkto_id: ''+id+''
  }, function(res) {
    if(res.error) window._alert(res.error);
    else {
      window.jQuery('#linked_'+type+'s').append(res.link);
      window.reloadEvents();
      window.jQuery('.new').slideDown().removeClass('new');
      reloadLinksEvents();
    }
  });
}
	
function reloadLinksEvents(scope) {
  window.jQuery('.deleteLink', scope).click( function() {
    const link_id = window.jQuery(this).data('link_id');
    window.jQuery('#link_'+link_id).fadeTo('fast',0.5);
    window.jQuery.get('/x/adm_links', {
      del: 1,
      link_id: ''+link_id+''
    }, function(res) {
      if(res.error) {
        window.jQuery('#link_'+link_id).fadeTo('fast',1);
        window._alert(res.error);
      } else window.jQuery('#link_'+link_id).slideUp();
    });
  });
}

document.addEventListener('DOMContentLoaded', function() {

  reloadLinksEvents();
		
  // Recherche un article
  window.jQuery('#article').autocomplete({
    source: '/x/adm_links?type=articles',
    minLength: 3,
    delay: 250,
    select: function(event, ui) { addLink('article',ui.item.id); }
  });
		
  // Rechercher un contributeur
  window.jQuery('#people').autocomplete({
    source: '/x/adm_links?type=people',
    minLength: 3,
    delay: 250,
    select: function(event, ui) { addLink('people',ui.item.id); }
  });
		
  // Rechercher un billet
  window.jQuery('#post').autocomplete({
    source: '/x/adm_links?type=post',
    minLength: 3,
    delay: 250,
    select: function(event, ui) { addLink('post',ui.item.id); }
  });
		
  // Autocomplete a publisher
  window.jQuery('#publisher').autocomplete({
    source: '/x/adm_links?type=publisher',
    minLength: 3,
    delay: 250,
    select: function(event, ui) { addLink('publisher',ui.item.id); }
  });
		
  // Rechercher un evenement
  window.jQuery('#event').autocomplete({
    source: '/x/adm_links?type=event',
    minLength: 3,
    delay: 250,
    select: function(event, ui) { addLink('event',ui.item.id); }
  });
		
});
