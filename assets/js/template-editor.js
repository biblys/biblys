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

import ace from 'ace-builds/src-min-noconflict/ace';
import Notification from './notification';

export default class TemplateEditor {
  constructor(form) {
    const textarea = document.querySelector('#editor');
    const mode = textarea.dataset.mode;
    this.submitButton = document.querySelector('#submitButton');

    // cdnjs didn't have a "no-conflict" version, so we'll use jsdelivr
    const CDN = 'https://cdn.jsdelivr.net/npm/ace-builds@1.4.8/src-min-noconflict';
    ace.config.set('basePath', CDN);
    ace.config.set('modePath', CDN);
    ace.config.set('themePath', CDN);
    ace.config.set('workerPath', CDN);

    // Setup ace editor
    this.editor = ace.edit(textarea);
    this.editor.setOptions({ maxLines: Infinity, minLines: 30 });
    this.editor.session.setMode(`ace/mode/${mode}`);
    this.editor.setTheme('ace/theme/chrome');

    // Enable save button when template is edited
    this.editor.session.on('change', () => {
      this.submitButton.disabled = false;
    });

    // Save template on form submit
    form.addEventListener('submit', (event) => this.onFormSubmit(event, form));
  }

  onFormSubmit(event, form) {
    event.preventDefault();

    const content = this.editor.session.getValue();
    const url = form.action;

    fetch(url, {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({content}),
    }).then((response) => response.json())
      .then((data) => {

        if (data.error) {
          throw new Error(data.error);
        }

        new Notification('Modèle enregistré.');
        this.submitButton.disabled = true;
      }).catch((error) => {
        window._alert(error.message);
      });
  }
}
