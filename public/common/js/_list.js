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

function filterList() {
  const query = document.getElementById('listSearch').value;
  const tableRows = document.querySelectorAll('tr');
  tableRows.forEach(row => {
    const keywords = (row.dataset.keywords || '').toLowerCase();
    if (keywords.indexOf(query.toLowerCase()) === -1) {
      row.style.display = 'none';
    } else {
      row.style.display = '';
    }
  });
}

document.addEventListener('DOMContentLoaded', function() {

  // Filtrer la liste
  const listSearch = document.getElementById('listSearch');
  if (listSearch) {
    listSearch.addEventListener('keyup', function() {
      filterList();
    });
  }

});
