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

import loadModuleForElements from './loadModuleForElements';

import '../css/admin.css';

document.addEventListener('DOMContentLoaded', function() {
  loadModuleForElements('admin-shortcuts/admin-shortcuts-manager', '#shortcuts-form');
  loadModuleForElements('cron-task', '.cron-execute-task');
  loadModuleForElements('orders/orders-manager', '#orders');
  loadModuleForElements('search-terms-manager', '#searchable-articles-table');
  loadModuleForElements('shipping', '#shipping-table');
  loadModuleForElements('table-to-csv', '[data-table-to-csv]');
  loadModuleForElements('template-editor', '#template-editor-form');
});
