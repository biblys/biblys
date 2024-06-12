import loadModuleForElements from './loadModuleForElements';

import '../css/admin.css';

document.addEventListener('DOMContentLoaded', function() {
  loadModuleForElements('admin-shortcuts/admin-shortcuts-manager', '#shortcuts-form');
  loadModuleForElements('cron-task', '.cron-execute-task');
  loadModuleForElements('entity-search', '[data-entity_search]');
  loadModuleForElements('orders/orders-manager', '#orders');
  loadModuleForElements('search-terms-manager', '#searchable-articles-table');
  loadModuleForElements('shipping', '#shipping-table');
  loadModuleForElements('table-to-csv', '[data-table-to-csv]');
  loadModuleForElements('template-editor', '#template-editor-form');
});
