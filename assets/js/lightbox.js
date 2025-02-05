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

// noinspection JSUnusedGlobalSymbols
export default class Lightbox {
  constructor(elem) {
    this.link = elem;
    this.image = this.link.querySelector('img');

    if (this.image.complete) {
      this.init();
    } else {
      this.image.addEventListener('load', () => {
        this.init();
      });
    }
  }

  init() {
    this.addEvents();
    this.animationSpeed = 200;
    this.link.style.cursor = 'zoom-in';
    this.displayed = false;
    this.imageRatio = this.image.width / this.image.height;
  }

  addEvents() {
    this.link.addEventListener('click', (event) => {
      event.preventDefault();
      this.openLightbox();
    });

    window.addEventListener('resize', () => {
      if (!this.displayed) {
        return;
      }
      this.resizeLightbox();
    });
  }

  getLightbox() {
    if (!this.lightbox) {
      this.lightbox = new Image();
      this.lightbox.src = this.link.getAttribute('href');
      this.lightbox.style.cursor = 'zoom-out';
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
    Object.assign(this.getLightbox().style, {
      top: `${target.top}px`,
      left: `${target.left}px`,
      height: `${target.height}px`,
      width: `${target.width}px`,
      transition: `all ${this.animationSpeed}ms`
    });
  }

  openLightbox() {
    const element = this.image;
    const lightbox = this.getLightbox();
    const startTop = element.getBoundingClientRect().top + window.scrollY;
    const startLeft = element.getBoundingClientRect().left + window.scrollX;
    const target = this.getLightboxDimensions();

    this.showOverlay();
    Object.assign(lightbox.style, {
      border: '10px solid white',
      position: 'absolute',
      top: `${startTop}px`,
      left: `${startLeft}px`,
      width: `${element.width}px`,
      height: `${element.height}px`,
      transition: `all ${this.animationSpeed * 1.5}ms`
    });
    element.style.opacity = 0;

    requestAnimationFrame(() => {
      Object.assign(lightbox.style, {
        top: `${target.top}px`,
        left: `${target.left}px`,
        height: `${target.height}px`,
        width: `${target.width}px`
      });
    });

    this.displayed = true;
  }

  closeLightbox() {
    const element = this.image;
    const lightbox = this.getLightbox();
    const targetHeight = element.offsetHeight;
    const targetWidth = element.offsetWidth;
    const targetTop = element.getBoundingClientRect().top + window.scrollY;
    const targetLeft = element.getBoundingClientRect().left + window.scrollX;

    this.hideOverlay();
    Object.assign(lightbox.style, {
      top: `${targetTop}px`,
      left: `${targetLeft}px`,
      height: `${targetHeight}px`,
      width: `${targetWidth}px`,
      transition: `all ${this.animationSpeed * 1.25}ms`
    });

    lightbox.addEventListener('transitionend', () => {
      element.style.opacity = 1;
    }, { once: true });

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
        zIndex: '10000'
      });
      document.body.appendChild(this.overlay);
      this.overlay.addEventListener('click', () => {
        this.closeLightbox();
      });
    }

    return this.overlay;
  }

  showOverlay() {
    this.overlay.style.display = 'block';
    this.overlay.style.transition = `opacity ${this.animationSpeed}ms`;
    this.overlay.style.opacity = '1';
  }

  hideOverlay() {
    this.overlay.style.transition = `opacity ${this.animationSpeed * 3.5}ms`;
    this.overlay.style.opacity = '0';
    this.overlay.addEventListener('transitionend', () => {
      this.overlay.style.display = 'none';
    }, { once: true });
  }
}
