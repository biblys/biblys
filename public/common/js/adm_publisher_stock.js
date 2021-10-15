// eslint-disable-next-line strict
'use strict';

async function updateStock(field) {
  const title = field.dataset.title;
  const articleId = parseInt(field.dataset.id);
  const stock = parseInt(field.innerText);

  const loader = new window.Biblys.Notification(
    `Mise à jour du stock en cours pour &laquo&nbsp;${title}&nbsp;&raquo;...`,
    { sticky: true }
  );

  const response = await fetch(`/admin/articles/${articleId}/update-publisher-stock`, {
    method: 'POST',
    headers: {
      Accept: 'application/json',
    },
    body: stock
  });

  loader.remove();
  if (response.status !== 200) {
    const data = await response.json();
    window._alert(`Une erreur est survenue pendant la mise à jour du stock pour  &laquo&nbsp;${title}&nbsp;&raquo; : ${data.error}`);
    return;
  }

  new window.Biblys.Notification(
    `Le stock a bien été mis à jour pour &laquo&nbsp;${title}&nbsp;&raquo;.`,
    { type: 'success' }
  );

  if (stock <= 3) {
    field.classList.add('alert');
  } else {
    field.classList.remove('alert');
  }
}

window.jQuery(document).ready(function() {
  window.jQuery('.stock').blur(function() {
    updateStock(this);
  });
});
