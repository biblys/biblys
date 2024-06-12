/* global Cookies, ga */

'use strict';

if (typeof Biblys === 'undefined') {
  var Biblys = {};
}

Biblys.Analytics = function(options) {

  this.options = typeof options !== 'undefined' ? options : {};

  if (typeof this.options.propertyId === 'undefined') {
    throw Error('Option propertyId must be defined.');
  }

  this.init();
};

Biblys.Analytics.prototype = {
  init: function() {
    var cookie = Cookies.get('cookies_consent');

    // No cookie yet, display banner
    if (typeof cookie === 'undefined') {
      this.displayBanner();
      Cookies.set('cookies_consent', 'pending');

    // User has navigated and therefore accepted cookies
    } else if (cookie == 'pending') {
      this.acceptCookies();

    // User has accepted cookies
    } else if (cookie == 'true') {
      this.callGoogleAnalytics();
    }
  },

  displayBanner: function() {
    this.banner = document.createElement('div');
    var message = document.createElement('p'),
      refuse    = document.createElement('a'),
      close     = document.createElement('span');

    this.banner.classList.add('cookies-consent-banner');

    message.classList.add('cookies-consent-message');
    message.textContent = 'En poursuivant votre navigation sur ce site, vous acceptez l’utilisation de cookies à des fins de mesure d\'audience';

    refuse.textContent = ' (ou cliquez ici pour refuser ces cookies).';
    refuse.addEventListener('click', this.refuseCookies.bind(this));

    close.classList.add('cookies-consent-close', 'fa', 'fa-times-circle');
    close.addEventListener('click', this.acceptCookies.bind(this));

    this.banner.appendChild(close);
    this.banner.appendChild(message);
    message.appendChild(refuse);
    document.body.appendChild(this.banner);
  },

  acceptCookies: function() {
    Cookies.set('cookies_consent', true, { expires: 390 });
    this.callGoogleAnalytics();
    this.closeBanner();
  },

  refuseCookies: function() {
    Cookies.set('cookies_consent', false, { expires: 390 });
    this.closeBanner();
  },

  closeBanner: function() {
    console.log(this.banner);
    if (this.banner) {
      this.banner.style.display = 'none';
    }
  },

  callGoogleAnalytics: function() {
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments);},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m);
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', this.options.propertyId, 'auto');
    ga('send', 'pageview');
  }
};
