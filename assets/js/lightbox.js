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

import $ from 'jquery';

export default class Lightbox {
  constructor(elem) {
    this.link = $(elem);
    this.image = this.link.find('img');

    if (this.image[0].complete) {
      this.init();
    } else {
      this.image.load(
        function() {
          this.init();
        }.bind(this)
      );
    }
  }

  init() {
    this.addEvents();
    this.animationSpeed = 200;
    this.link.css({ cursor: 'zoom-in' });
    this.displayed = false;
    this.imageRatio = this.image.width() / this.image.height();
  }

  addEvents() {
    this.link.on(
      'click',
      function(event) {
        event.preventDefault();
        this.openLightbox();
      }.bind(this)
    );

    $(window).on(
      'resize',
      function() {
        if (!this.displayed) {
          return;
        }
        this.resizeLightbox();
      }.bind(this)
    );
  }

  getLightbox() {
    // Create lightbox if necessary
    if (typeof this.lightbox === 'undefined') {
      this.lightbox = new Image();
      this.lightbox.src = this.link.attr('href');
      this.lightbox.style.cursor = 'zoom-out';
      this.getOverlay().append(this.lightbox);
    }

    return $(this.lightbox);
  }

  getLightboxDimensions() {
    var windowHeight = $(window).height(),
      windowWidth = $(window).width(),
      targetHeight = windowHeight * 0.95,
      targetWidth = targetHeight * this.imageRatio,
      targetTop = windowHeight * 0.025,
      targetLeft = windowWidth / 2 - targetWidth / 2;

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
    var target = this.getLightboxDimensions();
    this.getLightbox().animate(
      {
        top: target.top,
        left: target.left,
        height: target.height,
        width: target.width
      },
      this.animationSpeed
    );
  }

  openLightbox() {
    var element = this.image,
      lightbox = this.getLightbox(),
      startTop =
        element.offset().top - $(document).scrollTop() + parseInt(element.css('border-top-width')),
      startLeft =
        element.offset().left -
        $(document).scrollLeft() +
        parseInt(element.css('border-left-width')),
      target = this.getLightboxDimensions();

    this.showOverlay();
    lightbox.css({
      border: '10px solid white',
      position: 'absolute',
      top: startTop,
      left: startLeft,
      width: element.width(),
      height: element.height()
    });
    element.css('opacity', 0);

    lightbox.animate(
      {
        top: target.top,
        left: target.left,
        height: target.height,
        width: target.width
      },
      this.animationSpeed * 1.5
    );

    this.displayed = true;
  }

  closeLightbox() {
    var element = this.image,
      clone = this.getLightbox(),
      targetHeight = element.outerHeight(),
      targetWidth = element.outerWidth(),
      targetTop = element.offset().top - $(document).scrollTop(),
      targetLeft = element.offset().left - $(document).scrollLeft(),
      targetBorderWidth = element.css('border-width'),
      targetBorderColor = element.css('border-color');

    this.hideOverlay();
    clone
      .animate(
        {
          borderWidth: targetBorderWidth,
          borderColor: targetBorderColor,
          top: targetTop,
          left: targetLeft,
          height: targetHeight,
          width: targetWidth
        },
        this.animationSpeed * 1.25
      )
      .promise()
      .done(function() {
        element.css('opacity', 1);
      });

    this.displayed = false;
  }

  getOverlay() {
    // Create overlay if necessary
    if (typeof this.overlay === 'undefined') {
      this.overlay = $('<div></div>');
      this.overlay.css({
        position: 'fixed',
        display: 'none',
        top: 0,
        right: 0,
        background: 'rgba(0, 0, 0, .5)',
        width: '100%',
        height: '100%',
        zIndex: '10000'
      });
      this.overlay.appendTo('body');
      this.overlay.on(
        'click',
        function() {
          this.closeLightbox();
        }.bind(this)
      );
    }

    return this.overlay;
  }

  showOverlay() {
    var overlay = this.getOverlay();
    overlay.fadeIn(this.animationSpeed);
  }

  hideOverlay() {
    var overlay = this.getOverlay();
    overlay.fadeOut(this.animationSpeed * 3.5);
  }
}
