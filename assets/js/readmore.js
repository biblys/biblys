var ReadMore = function(element) {
  this.element = element;
  this.originalHeight = element.offsetHeight;
  this.maxHeight = element.dataset.readmore;
  this.height = element.getBoundingClientRect().height;

  this.params = {};
  this.params.gradient =
    typeof element.dataset.readmoreGradient !== 'undefined'
      ? element.dataset.readmoreGradient
      : false;

  this.init();
};

ReadMore.prototype = {
  init: function() {
    // If element is not bigger than max height, ignore
    if (this.height <= this.maxHeight) {
      return;
    }

    this.opened = false;

    // Reduce element size
    this.element.style.overflow = 'hidden';
    this.element.style.position = 'relative';
    this.element.style.height = this.maxHeight + 'px';
    this.element.style.transition = 'height 400ms';

    // Add button to open element
    this.button = document.createElement('a');
    this.button.href = '#';
    this.button.textContent = 'suite';
    this.button.classList.add('readmore-button');
    this.element.insertAdjacentElement('afterend', this.button);
    this.button.addEventListener('click', this.toggle.bind(this));

    // Add gradient element if parameters is present
    if (this.params.gradient) {
      this.addGradientElement();
    }
  },

  // Add a gradient over text to smooth text cut
  addGradientElement: function() {
    this.gradient = document.createElement('div');
    this.gradient.style.position = 'absolute';
    this.gradient.style.bottom = 0;
    this.gradient.style.height = '50%';
    this.gradient.style.width = '100%';
    this.gradient.style.background =
      'linear-gradient(to top, rgba(' +
      this.params.gradient +
      ',1) 0%,rgba(' +
      this.params.gradient +
      ',0) 100%)';
    this.gradient.style.transition = 'opacity 400ms';
    this.element.appendChild(this.gradient);
  },

  showGradientElement: function() {
    this.gradient.style.opacity = 1;
  },

  removeGradientElement: function() {
    this.gradient.style.opacity = 0;
  },

  toggle: function(event) {
    if (this.opened) {
      this.close(event);
    } else {
      this.open(event);
    }
  },

  open: function(event) {
    event.preventDefault();
    this.element.style.height = this.originalHeight + 'px';
    this.button.textContent = 'réduire';
    this.opened = true;

    // Remove gradient element if present
    if (this.params.gradient) {
      this.removeGradientElement();
    }
  },

  close: function(event) {
    event.preventDefault();
    this.element.style.height = this.maxHeight + 'px';
    this.button.textContent = 'suite';
    this.opened = false;

    // Remove gradient element if present
    if (this.params.gradient) {
      this.showGradientElement();
    }
  }
};

export default ReadMore;
