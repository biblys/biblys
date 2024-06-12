import Swiper from 'swiper';
import 'swiper/css/swiper.css';

import '../css/carousel.css';

export default class Caroussel {
  constructor(element) {
    this.element = element;

    // Default params
    this.params = {};
    this.params.lazy = { loadPrevNext: true };

    // Prev/Next buttons
    const prevEl = this.element.querySelector('.swiper-button-prev');
    const nextEl = this.element.querySelector('.swiper-button-next');
    if (prevEl && nextEl) {
      this.params.navigation = {
        prevEl,
        nextEl,
      };
    }

    // Pagination
    var pagination = this.element.querySelector('.swiper-pagination');
    if (pagination) {
      this.params.pagination = {
        el: pagination,
        clickable: true,
      };
    }

    // Get params from element's data attributes
    if (this.element.dataset.autoplayDelay) {
      this.params.autoplay = {
        delay: this.element.dataset.autoplayDelay,
      };
    }

    this.init();
  }

  init() {
    new Swiper(this.element, this.params);
  }
}
