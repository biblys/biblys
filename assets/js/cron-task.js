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
