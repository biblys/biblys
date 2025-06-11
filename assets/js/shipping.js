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

/* global Biblys */

import createElementFromHTML from './helpers/createElementFromHTML';

export default class Shipping {
  constructor() {
    this.tableBody = document.querySelector('#shipping-table tbody');

    this.addRangeButton = document.querySelector('#add-range');
    this.addRangeButton.addEventListener('click', async () => {
      const newRangeForm = document.querySelector('#new-range-form');
      const form = await this.renderForm({}, event => {
        event.preventDefault();
        const loader = new Biblys.Notification('Ajout en cours…', { loader: true, sticky: true });
        this.send(form).then(fee => {
          loader.remove();
          newRangeForm.innerHTML = '';
          this.fees.push(fee);
          this.render();
          new Biblys.Notification(`L'option <strong>${fee.mode}</strong> a bien été ajoutée.`, {
            type: 'success'
          });
        });
      });
      newRangeForm.appendChild(form);
    });

    this.getAll();
  }

  getAll() {
    this.setLoadingState(true);
    return fetch('/api/admin/shipping', { headers: { Accept: 'application/json' } })
      .then(response => response.json())
      .then(data => {

        if (data.error) {
          throw data.error.message;
        }

        this.setLoadingState(false);
        this.fees = data;
        this.render();
      })
      .catch(window._alert);
  }

  send(form) {
    const url = form.id.value ? `/api/admin/shipping/${form.id.value}` : '/api/admin/shipping';
    const method = form.id.value ? 'PUT' : 'POST';

    return fetch(url, {
      method,
      credentials: 'same-origin',
      headers: { Accept: 'application/json' },
      body: JSON.stringify(Object.fromEntries(new FormData(form)))
    })
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          throw data.error;
        }

        return data;
      })
      .catch(window._alert);
  }

  setLoadingState(active) {
    if (active) {
      this.tableBody.innerHTML = '<tr><td colspan="1">Chargement…</tr></td>';
    } else {
      this.tableBody.innerHTML = '';
    }
  }

  render() {
    this.tableBody.innerHTML = '';
    const rows = this.fees.map(fee => this.renderRow(fee));
    rows.forEach(row => this.tableBody.appendChild(row));
  }

  /**
   * @param {object} range
   * @param {string} range.mode
   * @param {string} range.type
   * @param {string} range.zone_name
   * @param {boolean} range.is_compliant_with_french_law
   * @param {string} range.max_weight
   * @param {string} range.min_amount
   * @param {string} range.max_amount
   * @param {string} range.max_articles
   * @param {string} range.fee
   * @param {string} range.info
   * @returns {HTMLElement}
   */
  renderRow(range) {
    let rowClass = '';
    let warning = '';
    if (!range.is_compliant_with_french_law) {
      warning = '<span class="fa fa-exclamation-triangle" title="Ce tarif n\'est pas conforme à la loi française"></span>';
      rowClass = 'table-warning';
    }
    const tr = createElementFromHTML(`
      <tr class="${rowClass}">
        <td>${warning} ${range.mode}</td>
        <td>${range.type}</td>
        <td>${range.zone_name}</td>
        <td class="nowrap">${this.renderConditions(range)}</td>
        <td class="right">${window.currency(parseInt(range.fee) / 100)}</td>
      </tr>
    `);
    const actionsCell = createElementFromHTML('<td class="action-buttons"></td>');
    actionsCell.appendChild(this.renderEditIcon(range, tr));
    actionsCell.appendChild(this.renderArchiveIcon(range, tr));
    actionsCell.appendChild(this.renderDeleteIcon(range));
    tr.appendChild(actionsCell);
    return tr;
  }

  renderEditIcon(range, tr) {
    const icon = createElementFromHTML(
      `<span class="btn btn-primary btn-sm">
        <span class="fa fa-pencil pointer" aria-label="modifier l'option'"></span>
      </span>`
    );
    icon.addEventListener('click', () => this.onRangeEditButtonClick(range, tr));
    return icon;
  }

  onRangeEditButtonClick(range, tr) {
    const confirm = window.confirm(`Modifier une option existante peut impacter les commandes en cours. Il est recommandé de créer une nouvelle option et d'archiver l'ancienne plutôt que de modifier une option existante. Voulez-vous vraiment modifier l'option ${range.mode}?`);
    if (confirm) {
      this.showRangeEditForm(range, tr);
    }
  }

  renderArchiveIcon(range) {
    const icon = createElementFromHTML(
      `<span class="btn btn-warning btn-sm">
        <span class="fa fa-archive pointer" aria-label="Archiver l'option"></span>
      </span>`
    );
    icon.addEventListener('click', () => this.archiveRange(range));
    return icon;
  }

  renderDeleteIcon(range) {
    const icon = createElementFromHTML(
      `<span class="btn btn-danger btn-sm">
        <span class="fa fa-trash-can pointer" aria-label="Supprimer l'option"></span>
      </span>`
    );
    icon.addEventListener('click', () => this.deleteRange(range));
    return icon;
  }

  renderConditions(range) {
    // Parse string as numbers
    const [maxWeight, minAmount, maxAmount, maxArticles] = [
      range.max_weight,
      range.min_amount,
      range.max_amount,
      range.max_articles
    ].map(string => {
      if (string === null) {
        return null;
      }

      return parseInt(string);
    });

    let conditions = [];

    if (maxWeight !== null) {
      conditions.push(`≤ ${maxWeight} g`);
    }

    if (minAmount !== null) {
      conditions.push(`≥ ${window.currency(minAmount / 100)}`);
    }

    if (maxAmount !== null) {
      conditions.push(`≤ ${window.currency(maxAmount / 100)}`);
    }

    if (maxArticles !== null) {
      conditions.push(`≤ ${maxArticles} articles`);
    }

    return conditions.join('<br/>');
  }

  /**
   * Renders a form
   *
   * @param {object} values to prefill form with if editing range
   * @param {function} onSubmit to be called when for is submitted
   *
   * @return {HtmlElement}
   */
  async renderForm(
    {
      id = '',
      mode = '',
      type = '',
      zone_id = '',
      max_weight = '',
      min_amount = '',
      max_amount = '',
      max_articles = '',
      fee = '',
      info = ''
    },
    onSubmit
  ) {

    const zonesResponse = await fetch('/api/admin/shipping/zones', {
      headers: { Accept: 'application/json' }
    });
    const zonesData = await zonesResponse.json();
    const zonesOptions = zonesData.map(zone => {
      return `<option value="${zone.id}" ${zone_id === zone.id ? 'selected' : ''}>${zone.name}</option>`;
    }).join('');

    const formContent = `
      <input type="hidden" id="id" name="id" value="${id}">

      <div class="form-group">
        <label class="col-form-label label-inline" for="mode">Intitulé :</label>
        <input type="text" id="mode" name="mode" class="form-control" value="${mode}" required>
      </div>

      <div class="form-group">
        <label class="col-form-label label-inline" for="mode">Type d'envoi :</label>
        <select id="type" name="type" class="form-control" required>
          <option value="normal" ${type === 'normal' ? 'selected' : ''}>Normal</option>
          <option value="colissimo" ${type === 'colissimo' ? 'selected' : ''}>Colissimo</option>
          <option value="mondial-relay" ${type === 'mondial-relay' ? 'selected' : ''}>Mondial Relay</option>
          <option value="magasin" ${type === 'magasin' ? 'selected' : ''}>Retrait en magasin</option>
        </select>
      </div>

      <div class="form-group">
        <label class="col-form-label label-inline" for="zone_id">Zone :</label>
        <select id="zone_id" name="zone_id" class="form-control" required>
          ${zonesOptions}
        </select>
      </div>

      <fieldset>
        <legend>Conditions</legend>

        <p>
          Cette option d'expédition sera proposée au client si la commande :
        </p>

        <div class="form-group">
          <label class="col-form-label label-inline" for="max_weight">… a un poids (en grammes) inférieur ou égal à :</label>
          <input type="number" min="1" step="1" id="max_weight" name="max_weight" class="form-control" value="${max_weight}">
        </div>

        <div class="form-group">
          <label class="col-form-label label-inline" for="min_amount">… a un montant (en centimes) supérieur ou égal à :</label>
          <input type="number" min="1" step="1" id="min_amount" name="min_amount" class="form-control" value="${min_amount}">
        </div>

        <div class="form-group">
          <label class="col-form-label label-inline" for="max_amount">… a un montant (en centimes) inférieur ou égal à :</label>
          <input type="number" min="1" step="1" id="max_amount" name="max_amount" class="form-control" value="${max_amount}">
        </div>

        <div class="form-group">
          <label class="col-form-label label-inline" for="max_articles">… a un nombre d'exemplaires inférieur ou égal à :</label>
          <input type="number" min="1" step="1" id="max_articles" name="max_articles" class="form-control" value="${max_articles}">
        </div>

      </fieldset>

      <div class="form-group">
        <label class="col-form-label label-inline" for="fee">Montant (en centimes) :</label>
        <input type="number" min="0" step="1" id="fee" name="fee" class="form-control" value="${fee}" required>
      </div>

      <div class="form-group">
        <label class="col-form-label label-inline" for="info">Commentaire (affiché au client lorsqu'il choisit ce mode) :</label>
        <input type="text" id="info" name="info" class="form-control" value="${info === null ? '' : info}">
      </div>

      <div class="form-group text-center">
        <button type="submit" class="btn btn-primary">${id === '' ? 'Ajouter' : 'Modifier'}</button>
      </div>
    `;
    const form = document.createElement('form');
    form.classList.add('fieldset');
    form.innerHTML = formContent;
    form.addEventListener('submit', onSubmit.bind(this));

    return form;
  }

  archiveRange(range) {
    const confirm = window.confirm(`Voulez-vous vraiment archiver l'option ${range.mode}?`);
    if (!confirm) {
      return;
    }

    const loader = new Biblys.Notification('Archivage en cours…', { loader: true, sticky: true });
    fetch(`/api/admin/shipping/${range.id}/archive`, {
      method: 'POST',
      credentials: 'same-origin',
      headers: { Accept: 'application/json' }
    })
      .then((response) => this._handleEmptyResponse(loader, response))
      .then(() => {
        new Biblys.Notification(`L'option <strong>${range.mode}</strong> a bien été archivée.`, {
          type: 'success'
        });
        this.fees = this.fees.filter(fee => fee.id !== range.id);
        this.render();
      })
      .catch(window._alert);
  }

  deleteRange(range) {
    const confirm = window.confirm(`Voulez-vous vraiment supprimer l'option ${range.mode}?`);
    if (!confirm) {
      return;
    }

    const loader = new Biblys.Notification('Suppression en cours…', { loader: true, sticky: true });
    fetch(`/api/admin/shipping/${range.id}`, {
      method: 'DELETE',
      credentials: 'same-origin',
      headers: { Accept: 'application/json' }
    })
      .then((response) => this._handleEmptyResponse(loader, response))
      .then(() => {
        new Biblys.Notification(`L'option <strong>${range.mode}</strong> a bien été supprimée.`, {
          type: 'success'
        });
        this.fees = this.fees.filter(fee => fee.id !== range.id);
        this.render();
      })
      .catch(window._alert);
  }

  async _handleEmptyResponse(loader, response) {
    loader.remove();

    if (response.status === 204) {
      return;
    }

    const data = await response.json();
    if (data.error) {
      throw data.error.message;
    }

    throw new Error('Une erreur est survenue.');
  }

  async showRangeEditForm(range, tr) {
    const rangeEditRow = createElementFromHTML('<tr></tr>');
    const rangeEditCell = createElementFromHTML('<td colspan=8></td>');
    const form = await this.renderForm(range, event => {
      event.preventDefault();
      const loader = new Biblys.Notification(
        `Mise à jour de <strong>${range.mode}</strong> en cours…`,
        {
          loader: true,
          sticky: true
        }
      );
      this.send(form).then(fee => {
        loader.remove();
        rangeEditRow.remove();

        // Update fee in table
        const updatedRangeIndex = this.fees.findIndex(range => fee.id === range.id);
        this.fees[updatedRangeIndex] = fee;
        this.render();

        new Biblys.Notification(`L'option <strong>${fee.mode}</strong> a bien été mise à jour.`, {
          type: 'success'
        });
      });
    });
    rangeEditRow.appendChild(rangeEditCell);
    rangeEditCell.appendChild(form);
    tr.after(rangeEditRow);
  }
}
