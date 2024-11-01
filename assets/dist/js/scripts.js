"use strict";

(function ($, window, document, undefined) {
  /**
   * Parse HTML encoded script directives.
   *
   * @param {array} loadScripts The array of scripts to be loaded.
   * @return {object} The fully parsed html elements.
   */
  window.xckParseHtml = function xckParseHtml(htmlData) {
    // Parse encoded via text area.
    var txt = document.createElement("textarea");
    txt.innerHTML = htmlData; // Now add this as html to our mirror doc.

    var el = document.createElement("html");
    el.innerHTML = txt.innerText;
    return el;
  };
  /**
   * Load <xbee-script> tags.
   *
   * @param {object} loadScripts The HTMLCollection of scripts to be loaded.
   */


  window.xckLoadXbeeJs = function xckLoadXbeeJs(loadScripts, category) {
    var i = 0;

    if (loadScripts.length > 0) {
      for (i = 0; i < loadScripts.length; i++) {
        var item = loadScripts[i];
        var script = document.createElement("xbee-script");
        script.setAttribute('category', category);
        script.setAttribute('action', 'set');

        if (item.src === "") {
          script.innerText = item.text;
        } else {
          //load from file
          script.setAttribute('async', ''); // we always load async

          script.setAttribute('src', item.src); // other elements

          if (item.integrity !== "") {
            script.integrity = item.integrity;
          }

          if (item.crossOrigin !== "") {
            script.crossOrigin = item.crossOrigin;
          }
        } // now append to document for execution


        document.body.appendChild(script);
      }
    }
  };
  /**
   * Set a cookie based on name and value.
   *
   * @param {string} name - The name of the cookie.
   * @param {string} value - The value of the cookie.
   * @param {string} days - The number of days this cookie will be active. Zero or null will set cookies without expiration.
   */


  window.xckSetCookie = function xckSetCookie(name, value, days) {
    var expires = "";

    if (days) {
      var date = new Date();
      date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
      expires = "; expires=" + date.toUTCString();
    }

    document.cookie = name + "=" + (value || "") + expires + "; path=/";
  };
  /**
   * Get current cookie value.
   *
   * @param  {string} name - The name of the cookie.
   * @return {string|null} - The value of the cookie if exists, otherwise null.
   */


  window.xckGetCookie = function xckGetCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');

    for (var i = 0; i < ca.length; i++) {
      var c = ca[i];

      while (c.charAt(0) == ' ') {
        c = c.substring(1, c.length);
      }

      if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }

    return null;
  };
  /**
   * Deletes an exiting cookie for the domain.
   *
   * @param {string} name - The name of the cookie.
   */


  window.xckEraseCookie = function xckEraseCookie(name) {
    document.cookie = name + '=; Max-Age=-99999999; path=/';
  };
  /**
   * Bulk set cookies
   * 
   * @param {array} cookies - Array of cookies.
   */


  window.xckSetCookies = function xckSetCookies(cookies) {
    if (cookies) {
      cookies.forEach(function (ck) {
        xckSetCookie(ck.name, ck.value, ck.days);
      });
    }
  };
  /**
   * Bulk erase cookies.
   *
   * @param {array} cookies - Array of cookies.
   */


  window.xckEraseCookies = function xckEraseCookies(cookies) {
    if (cookies) {
      cookies.forEach(function (ck) {
        xckEraseCookie(ck.name);
      });
    }
  };
  /**
   * On document ready.
   */


  $(document).ready(function () {});
})(jQuery, window, document);