import ace from 'ace-builds/src-min-noconflict/ace';
import Notification from './notification';

export default class TemplateEditor {
  constructor(form) {
    var textarea = document.querySelector('#editor');
    var mode = textarea.dataset.mode;
    var submitButton = document.querySelector('#submitButton');

    // cdnjs didn't have a "no-conflict" version, so we'll use jsdelivr
    const CDN = 'https://cdn.jsdelivr.net/npm/ace-builds@1.4.8/src-min-noconflict';
    ace.config.set('basePath', CDN);
    ace.config.set('modePath', CDN);
    ace.config.set('themePath', CDN);
    ace.config.set('workerPath', CDN);

    // Setup ace editor
    const editor = ace.edit(textarea);
    editor.setOptions({ maxLines: Infinity, minLines: 30 });
    editor.session.setMode(`ace/mode/${mode}`);
    editor.setTheme('ace/theme/chrome');

    // Enable save button when template is edited
    editor.session.on('change', function() {
      submitButton.disabled = false;
    });

    // Save template on form submit
    form.addEventListener('submit', function(event) {
      event.preventDefault();

      var content = editor.session.getValue(),
        url = this.action,
        req = new XMLHttpRequest(),
        data = new FormData();

      data.append('content', content);
      req.open('POST', url);

      req.onload = function() {
        if (this.status === 200) {
          new Notification('Modèle enregistré.');
          submitButton.disabled = true;
        } else {
          window._alert('Une erreur (' + this.status + ') est survenue.');
        }
      };

      req.send(data);
    });
  }
}
