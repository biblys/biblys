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

import Notification from './notification';

export default class CronTask {
  constructor(button) {
    button.addEventListener('click', this.executeTask.bind(this));
  }

  executeTask(event) {
    const button = event.target;
    const { task, cronKey } = button.dataset;

    button.disabled = true;
    button.classList.add('loading');

    fetch(`/crons/${task}`, {
      headers: {
        Accept: 'application/json',
        'X-CRON-KEY': cronKey
      }
    })
      .then(response => response.json())
      .then(json => {
        button.disabled = false;
        button.classList.remove('loading');

        if (json.error || json.result === 'error') {
          window._alert(json.error || json.message);
        }

        if (json.result === 'success') {
          new Notification(json.message);
          window.setTimeout(() => window.location.reload(), 1000);
        }
      })
      .catch(error => {
        window._alert(error);
      });
  }
}
