import loadModuleForElements from './loadModuleForElements';

import '../css/app.css';

document.addEventListener('DOMContentLoaded', function() {
  loadModuleForElements('carousel', '.biblys-carousel');
  loadModuleForElements('lightbox', '[rel=lightbox]');
  loadModuleForElements('readmore', '[data-readmore]');
});
