(function () {

  'use strict';

  var tree = document.querySelector('.page-tree');
  if (!tree) {
    return;
  }

  var filter = tree.querySelector('.page-tree__filter');
  var toggle = tree.querySelector('.page-tree__toggle');
  var emptyHint = tree.querySelector('.page-tree__empty');
  var cards = Array.prototype.slice.call(tree.querySelectorAll('.page-tree__card'));
  var details = Array.prototype.slice.call(tree.querySelectorAll('details'));
  var items = Array.prototype.slice.call(tree.querySelectorAll('.page-tree__item'));

  // controls need JavaScript: only show them if it is available (and there is something to collapse/filter)
  filter.hidden = false;
  if (details.length > 0) {
    toggle.hidden = false;
  }

  function setAll(open) {
    details.forEach(function (detail) {
      detail.open = open;
    });
    toggle.dataset.expanded = open ? 'true' : 'false';
    toggle.textContent = open ? 'Alle zuklappen' : 'Alle aufklappen';
  }

  toggle.addEventListener('click', function () {
    setAll(toggle.dataset.expanded !== 'true');
  });

  // plain text of the own link only (not of the subpages)
  function ownText(item) {
    var link = item.querySelector(':scope > a, :scope > details > summary > a');
    return link ? link.textContent.toLowerCase() : '';
  }

  function applyFilter() {
    var term = filter.value.trim().toLowerCase();
    var visibleCards = 0;

    if (term === '') {
      items.forEach(function (item) { item.hidden = false; });
      cards.forEach(function (card) { card.hidden = false; });
      emptyHint.hidden = true;
      return;
    }

    // from the leaves up: an item stays visible if it matches itself or one of its subpages matches
    items.slice().reverse().forEach(function (item) {
      var match = ownText(item).indexOf(term) !== -1;
      var childMatch = item.querySelector('.page-tree__item:not([hidden])') !== null;
      item.hidden = !(match || childMatch);
      var detail = item.querySelector(':scope > details');
      if (detail && childMatch) {
        detail.open = true;
      }
    });

    cards.forEach(function (card) {
      var title = card.querySelector('.page-tree__card-title a').textContent.toLowerCase();
      var visible = title.indexOf(term) !== -1 || card.querySelector('.page-tree__item:not([hidden])') !== null;
      card.hidden = !visible;
      if (visible) {
        visibleCards++;
      }
    });
    emptyHint.hidden = visibleCards > 0;
  }

  filter.addEventListener('input', applyFilter);

})();
