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

export default class Shortcut {
  constructor(props, manager) {
    this.props = {};
    this.props.name = props.name;
    this.props.url = props.url;
    this.props.icon = props.icon;
    this.props.class = props.class;
    this.props.subscription = props.subscription;

    this.manager = manager;
  }

  renderHtml() {
    // Create element
    this.element = document.createElement('div');
    this.element.classList.add('shortcut');
    this.element.innerHTML =
      '<span class="icon fa fa-' +
      this.props.icon +
      '"></span> ' +
      '<p>' +
      this.props.name +
      '</p>';

    // Create delete button
    var deleteButton = document.createElement('p');
    deleteButton.innerHTML =
      '<button class="btn btn-sm btn-danger"><span class="fa fa-trash-can"></span></button>';
    this.element.appendChild(deleteButton);

    // Add delete event on button
    deleteButton.addEventListener(
      'click',
      function() {
        this.manager.remove(this);
      }.bind(this)
    );

    return this.element;
  }

  remove() {
    this.element.parentNode.removeChild(this.element);
  }
}
