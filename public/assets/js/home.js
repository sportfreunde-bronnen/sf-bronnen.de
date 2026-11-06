/**
 * Start page: filter of the news by area ("Gesamtverein", "Fußball", ...)
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
