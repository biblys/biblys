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
