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

export default class TableToCsv {
  constructor(button) {
    var tableTarget = button.dataset.tableToCsv;
    var table = document.querySelector(tableTarget);

    var fileName = 'export';
    if (typeof button.dataset.csvFileName !== 'undefined') {
      fileName = button.dataset.csvFileName;
    }

    this.init(button, table, fileName);
  }

  init(button, table, fileName) {
    button.addEventListener(
      'click',
      function() {
        this.onClick(table, fileName);
      }.bind(this)
    );
  }

  onClick(table, fileName) {
    var csvContent = 'data:text/csv;charset=utf-8,\uFEFF';
    var trs = table.querySelectorAll('tr');
    for (var i = 0, c = trs.length; i < c; i++) {
      var tr = trs[i];
      var tds = tr.querySelectorAll('td,th');
      for (var ii = 0, cc = tds.length; ii < cc; ii++) {
        csvContent += '"' + tds[ii].innerText + '"';
        if (ii < cc - 1) {
          csvContent += ',';
        }
      }
      csvContent += '\r\n';
    }
    var encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.href = encodedUri;
    link.download = fileName + '.csv';
    link.click();
  }
}
