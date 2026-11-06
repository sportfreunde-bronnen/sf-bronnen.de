/**
 * Start page
 *
 * 1. Parallax: the photo behind the introduction moves a little slower than the content while scrolling
 * 2. Filter of the news by area ("Gesamtverein", "Fußball", ...)
 */

/**
 * Parallax of the photo behind the introduction ([data-parallax], see .home-intro-bg in sass/templates/home.scss)
 *
 * The photo overhangs the section by 20% of its height at the top and at the bottom, so it may move by that much.
 * It is a transform instead of "background-attachment: fixed", which does not work on iOS and is janky elsewhere.
 * Only runs while the section is visible and not at all for visitors who prefer reduced motion.
 */
(function () {

  'use strict';

  var layer = document.querySelector('[data-parallax]');
  var section = layer && layer.parentElement;
  if (!section || !window.requestAnimationFrame) {
    return;
  }

  var reducedMotion = window.matchMedia ? window.matchMedia('(prefers-reduced-motion: reduce)') : null;
  // share of the scrolled distance the photo lags behind the content, and the maximum shift as share of the section height
  var SPEED = 0.2;
  var OVERHANG = 0.2;

  var visible = true;
  var ticking = false;

  function update() {
    ticking = false;

    if (reducedMotion && reducedMotion.matches) {
      layer.style.removeProperty('--home-parallax');
      return;
    }

    var rect = section.getBoundingClientRect();
    var max = rect.height * OVERHANG;
    // 0 while the top of the section touches the top of the window, growing the further the section is scrolled up
    var shift = Math.max(-max, Math.min(max, -rect.top * SPEED));

    layer.style.setProperty('--home-parallax', shift.toFixed(1) + 'px');
  }

  function request() {
    if (visible && !ticking) {
      ticking = true;
      window.requestAnimationFrame(update);
    }
  }

  if ('IntersectionObserver' in window) {
    new IntersectionObserver(function (entries) {
      visible = entries[0].isIntersecting;
      request();
    }).observe(section);
  }

  window.addEventListener('scroll', request, { passive: true });
  window.addEventListener('resize', request);
  if (reducedMotion && reducedMotion.addEventListener) {
    reducedMotion.addEventListener('change', request);
  }

  update();

})();

/**
 * Filter of the news by area
 *
 * Shows the latest news of the chosen area (or of all areas). The filter is hidden in the markup and only
 * revealed here, so without JavaScript the visitor simply sees the latest news.
 */
(function () {

  'use strict';

  var root = document.querySelector('[data-news]');
  if (!root) {
    return;
  }

  var filter = root.querySelector('[data-news-filter]');
  var grid = root.querySelector('[data-news-grid]');
  var empty = root.querySelector('[data-news-empty]');
  var archive = root.querySelector('[data-news-archive]');
  if (!filter || !grid) {
    return;
  }

  var items = Array.prototype.slice.call(grid.children);
  var buttons = Array.prototype.slice.call(filter.querySelectorAll('[data-filter]'));
  var limit = parseInt(root.getAttribute('data-limit'), 10) || 6;

  function apply(area, button) {
    var shown = 0;

    items.forEach(function (item) {
      var visible = shown < limit && (area === 'all' || item.getAttribute('data-area') === area);
      if (visible) {
        shown++;
      }
      item.hidden = !visible;
      // restart the fade-in animation of the cards which appear
      item.classList.remove('is-entering');
      if (visible) {
        void item.offsetWidth;
        item.classList.add('is-entering');
      }
    });

    buttons.forEach(function (other) {
      var active = other === button;
      other.classList.toggle('is-active', active);
      other.setAttribute('aria-pressed', active ? 'true' : 'false');
    });

    if (empty) {
      empty.hidden = shown > 0;
    }

    if (archive) {
      var url = button.getAttribute('data-archive-url');
      var link = archive.querySelector('a');
      archive.hidden = area === 'all' || !url;
      if (url && link) {
        link.setAttribute('href', url);
        link.textContent = button.getAttribute('data-archive-label') || 'Alle Beiträge';
      }
    }
  }

  buttons.forEach(function (button) {
    button.addEventListener('click', function () {
      apply(button.getAttribute('data-filter'), button);
    });
  });

  filter.hidden = false;

})();
