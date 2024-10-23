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

/* NOTIFICATIONS */

/**
 * Displays a notification
 *
 * @param {string} text the notification's message
 * @param {options} options a object containing options
 *                          - type: a bootstrap alert class (default info)
 *                          - timeout: time in ms before notification disappears (default 2500)
 *                          - sticky: if true, notification won't disappear (default false)
 *                          - closeButton: if true, will display a close button
 *                          - loader: if true, will display an activity indicator
 *
 */
export default class Notification {
  constructor(text, options) {
    this.text = text;

    options = options || {};

    this.params = {
      type: options.type || 'info',
      timeout: options.timeout || 2500,
      sticky: options.sticky || false,
      closeButton: options.closeButton || false,
      loader: options.loader || false,
      sound: options.sound || false
    };

    this.init();
  }
  init() {
    var notifications = this.getNotificationsContainer(),
      notification = this.renderNotification();

    // Append notification to the container
    notifications.appendChild(notification);

    // Add close button
    if (this.params.closeButton) {
      this.addCloseButton();
    }

    // Add loader
    if (this.params.loader) {
      this.addLoader();
    }

    // Play sound
    if (this.params.sound) {
      this.playSound();
    }

    // Remove after timeout if not sticky
    if (!this.params.sticky) {
      window.setTimeout(
        function() {
          this.remove();
        }.bind(this),
        this.params.timeout
      );
    }
  }

  // Get the notifications container
  getNotificationsContainer() {
    var notifications = document.querySelector('.notifications');

    if (!notifications) {
      notifications = this.createNotificationsContainer();
    }

    return notifications;
  }

  // Create the notifications container
  createNotificationsContainer() {
    var notifications = document.createElement('div');
    notifications.classList.add('notifications');
    document.body.appendChild(notifications);
    return notifications;
  }

  // Render the notification element
  renderNotification() {
    this.element = document.createElement('div');
    this.element.classList.add('notification');
    this.element.classList.add('alert');
    this.element.classList.add('alert-' + this.params.type);
    this.element.innerHTML = this.text;

    return this.element;
  }

  addCloseButton() {
    var button = document.createElement('span');
    button.classList.add('fa');
    button.classList.add('fa-close');

    this.element.insertAdjacentElement('afterbegin', button);

    button.addEventListener(
      'click',
      function() {
        this.remove();
      }.bind(this)
    );
  }

  addLoader() {
    var loader = document.createElement('span');
    loader.classList.add('fa');
    loader.classList.add('fa-spinner');
    loader.classList.add('fa-spin');

    this.element.insertAdjacentElement('afterbegin', loader);
  }

  playSound() {
    var audio = document.createElement('audio');
    audio.style.display = 'none';
    audio.autoplay = true;
    audio.innerHTML =
      '<source src="/assets/sounds/' + this.params.sound + '.mp3" type="audio/mp3">';
    this.element.appendChild(audio);
  }

  remove() {
    this.element.style.opacity = 0;
    window.setTimeout(
      function() {
        this.element.parentNode.removeChild(this.element);
      }.bind(this),
      400
    );
  }
}
