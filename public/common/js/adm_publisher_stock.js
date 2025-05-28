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
    body: stock.toString(),
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

document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.stock').forEach(function(field) {
    field.addEventListener('blur', async function() {
      await updateStock(this);
    });
  });
});
