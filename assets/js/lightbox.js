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

export default class Lightbox {
  constructor(elem) {
    this.link = typeof elem === 'string' ? document.querySelector(elem) : elem;
    this.image = this.link.querySelector('img');

    if (this.image.complete) {
      this.init();
    } else {
      this.image.addEventListener('load', () => this.init());
    }
  }

  init() {
    this.addEvents();
    this.animationSpeed = 200;
    this.link.style.cursor = 'zoom-in';
    this.displayed = false;
    this.imageRatio = this.image.naturalWidth / this.image.naturalHeight;
  }

  addEvents() {
    this.link.addEventListener('click', (event) => {
      event.preventDefault();
      this.openLightbox();
    });

    window.addEventListener('resize', () => {
      if (!this.displayed) return;
      this.resizeLightbox();
    });
  }

  getLightbox() {
    if (!this.lightbox) {
      this.lightbox = new window.Image();
      this.lightbox.src = this.link.getAttribute('href');
      this.lightbox.style.cursor = 'zoom-out';
      this.lightbox.style.position = 'absolute';
      this.lightbox.style.transition = `all ${this.animationSpeed}ms`;
      this.getOverlay().appendChild(this.lightbox);
    }
    return this.lightbox;
  }

  getLightboxDimensions() {
    const windowHeight = window.innerHeight;
    const windowWidth = window.innerWidth;
    let targetHeight = windowHeight * 0.95;
    let targetWidth = targetHeight * this.imageRatio;
    let targetTop = windowHeight * 0.025;
    let targetLeft = windowWidth / 2 - targetWidth / 2;

    if (targetWidth > windowWidth * 0.95) {
      targetWidth = windowWidth * 0.95;
      targetHeight = targetWidth / this.imageRatio;
      targetLeft = windowWidth * 0.025;
      targetTop = windowHeight / 2 - targetHeight / 2;
    }

    return {
      top: targetTop,
      left: targetLeft,
      width: targetWidth,
      height: targetHeight
    };
  }

  resizeLightbox() {
    const target = this.getLightboxDimensions();
    const lightbox = this.getLightbox();
    lightbox.style.top = `${target.top}px`;
    lightbox.style.left = `${target.left}px`;
    lightbox.style.width = `${target.width}px`;
    lightbox.style.height = `${target.height}px`;
  }

  openLightbox() {
    const element = this.image;
    const lightbox = this.getLightbox();
    const rect = element.getBoundingClientRect();
    const scrollTop = window.scrollY || document.documentElement.scrollTop;
    const scrollLeft = window.scrollX || document.documentElement.scrollLeft;
    const startTop = rect.top + scrollTop + (parseInt(getComputedStyle(element).borderTopWidth) || 0);
    const startLeft = rect.left + scrollLeft + (parseInt(getComputedStyle(element).borderLeftWidth) || 0);
    const target = this.getLightboxDimensions();

    this.showOverlay();
    lightbox.style.border = '10px solid white';
    lightbox.style.position = 'absolute';
    lightbox.style.top = `${startTop}px`;
    lightbox.style.left = `${startLeft}px`;
    lightbox.style.width = `${element.width}px`;
    lightbox.style.height = `${element.height}px`;
    lightbox.style.opacity = '1';
    element.style.opacity = '0';

    // Animation
    setTimeout(() => {
      lightbox.style.transition = `all ${this.animationSpeed * 1.5}ms`;
      lightbox.style.top = `${target.top}px`;
      lightbox.style.left = `${target.left}px`;
      lightbox.style.width = `${target.width}px`;
      lightbox.style.height = `${target.height}px`;
    }, 10);

    this.displayed = true;
  }

  closeLightbox() {
    const element = this.image;
    const lightbox = this.getLightbox();
    const rect = element.getBoundingClientRect();
    const scrollTop = window.scrollY || document.documentElement.scrollTop;
    const scrollLeft = window.scrollX || document.documentElement.scrollLeft;
    const targetTop = rect.top + scrollTop;
    const targetLeft = rect.left + scrollLeft;
    const targetHeight = element.offsetHeight;
    const targetWidth = element.offsetWidth;
    const targetBorderWidth = getComputedStyle(element).borderWidth;
    const targetBorderColor = getComputedStyle(element).borderColor;

    this.hideOverlay();
    lightbox.style.transition = `all ${this.animationSpeed * 1.25}ms`;
    lightbox.style.top = `${targetTop}px`;
    lightbox.style.left = `${targetLeft}px`;
    lightbox.style.width = `${targetWidth}px`;
    lightbox.style.height = `${targetHeight}px`;
    lightbox.style.borderWidth = targetBorderWidth;
    lightbox.style.borderColor = targetBorderColor;

    setTimeout(() => {
      element.style.opacity = '1';
    }, this.animationSpeed * 1.25);

    this.displayed = false;
  }

  getOverlay() {
    if (!this.overlay) {
      this.overlay = document.createElement('div');
      Object.assign(this.overlay.style, {
        position: 'fixed',
        display: 'none',
        top: 0,
        right: 0,
        background: 'rgba(0, 0, 0, .5)',
        width: '100%',
        height: '100%',
        zIndex: '10000',
        left: 0,
        transition: `opacity ${this.animationSpeed}ms`
      });
      document.body.appendChild(this.overlay);
      this.overlay.addEventListener('click', () => this.closeLightbox());
    }
    return this.overlay;
  }

  showOverlay() {
    const overlay = this.getOverlay();
    overlay.style.display = 'block';
    overlay.style.opacity = '0';
    setTimeout(() => {
      overlay.style.opacity = '1';
    }, 10);
  }

  hideOverlay() {
    const overlay = this.getOverlay();
    overlay.style.opacity = '0';
    setTimeout(() => {
      overlay.style.display = 'none';
    }, this.animationSpeed * 3.5);
  }
}
