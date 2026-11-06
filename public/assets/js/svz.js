/**
 * Sportvereinszentrum (SVZ) landing page
 *
 * - tabs (infos, availability, prices, faqs) with deep links (#infos, #verfuegbarkeit, #preise, #faqs)
 * - availability calendar (browse months, request button for the selected day)
 * - tariff switch for the price overview
 * - FAQ accordion
 * - gallery lightbox and reveal animations
 *
 * Without JavaScript all sections are displayed one below the other.
 */
(function () {

  'use strict';

  var root = document.querySelector('[data-svz]');

  if (!root) {
    return;
  }

  var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function toArray(list) {
    return Array.prototype.slice.call(list);
  }

  // ------------------------------------------------------------------
  // Tabs
  // ------------------------------------------------------------------
  var tabBar = root.querySelector('[data-svz-tabs]');
  var panelsWrap = root.querySelector('[data-svz-panels]');
  var panels = toArray(root.querySelectorAll('[data-svz-panel]'));
  var tabLinks = tabBar ? toArray(tabBar.querySelectorAll('.svz-tabs__list [data-svz-tab]')) : [];
  var tabIds = panels.map(function (panel) {
    return panel.getAttribute('data-svz-panel');
  });

  function panelsScrollTop() {
    // the main navigation scrolls away, so only the sticky tab bar has to be considered
    var offset = tabBar ? tabBar.offsetHeight : 0;
    return panelsWrap.getBoundingClientRect().top + window.pageYOffset - offset;
  }

  function scrollToPanels(smooth) {
    window.scrollTo({
      top: Math.max(panelsScrollTop(), 0),
      behavior: (smooth && !reduceMotion) ? 'smooth' : 'auto'
    });
  }

  function activate(id) {
    if (tabIds.indexOf(id) === -1) {
      id = tabIds[0];
    }

    panels.forEach(function (panel) {
      panel.classList.toggle('is-active', panel.getAttribute('data-svz-panel') === id);
    });

    tabLinks.forEach(function (link) {
      var active = link.getAttribute('data-svz-tab') === id;
      link.classList.toggle('is-active', active);
      link.setAttribute('aria-selected', active ? 'true' : 'false');
      link.setAttribute('tabindex', active ? '0' : '-1');
    });

    return id;
  }

  function show(id, forceScroll) {
    id = activate(id);

    if (window.history && window.history.replaceState) {
      window.history.replaceState(null, '', '#' + id);
    }

    if (forceScroll || window.pageYOffset > panelsScrollTop()) {
      scrollToPanels(forceScroll);
    }
  }

  function initialTab() {
    var id = window.location.hash.replace('#', '');
    return tabIds.indexOf(id) === -1 ? null : id;
  }

  if (tabBar && panels.length) {
    // accessibility roles are only set if the tabs really work (JavaScript available)
    var tabList = tabBar.querySelector('.svz-tabs__list');
    tabList.setAttribute('role', 'tablist');

    toArray(tabList.children).forEach(function (item) {
      item.setAttribute('role', 'presentation');
    });

    tabLinks.forEach(function (link) {
      var id = link.getAttribute('data-svz-tab');
      link.setAttribute('role', 'tab');
      link.setAttribute('id', 'svz-tab-' + id);
      link.setAttribute('aria-controls', id);
    });

    panels.forEach(function (panel) {
      panel.setAttribute('role', 'tabpanel');
      panel.setAttribute('aria-labelledby', 'svz-tab-' + panel.getAttribute('data-svz-panel'));
    });

    root.addEventListener('click', function (event) {
      var trigger = event.target.closest('[data-svz-tab]');

      if (!trigger) {
        return;
      }

      event.preventDefault();
      // buttons outside of the tab bar (hero, infos) always scroll to the content
      show(trigger.getAttribute('data-svz-tab'), !trigger.closest('[data-svz-tabs]'));
    });

    tabBar.addEventListener('keydown', function (event) {
      var key = event.key;

      if (key !== 'ArrowLeft' && key !== 'ArrowRight') {
        return;
      }

      var current = tabLinks.indexOf(document.activeElement);

      if (current === -1) {
        return;
      }

      event.preventDefault();
      var next = (current + (key === 'ArrowRight' ? 1 : -1) + tabLinks.length) % tabLinks.length;
      tabLinks[next].focus();
      show(tabLinks[next].getAttribute('data-svz-tab'), false);
    });

    window.addEventListener('hashchange', function () {
      var id = initialTab();

      if (id) {
        activate(id);
      }
    });

    var startTab = initialTab();
    activate(startTab || tabIds[0]);

    if (startTab) {
      scrollToPanels(false);
      window.addEventListener('load', function () {
        scrollToPanels(false);
      });
    }
  }

  // ------------------------------------------------------------------
  // Availability calendar
  // ------------------------------------------------------------------
  var calendar = root.querySelector('[data-svz-calendar]');

  if (calendar) {
    var months = toArray(calendar.querySelectorAll('[data-svz-month]'));
    var titleEl = calendar.querySelector('[data-svz-cal-title]');
    var prevBtn = calendar.querySelector('[data-svz-cal-prev]');
    var nextBtn = calendar.querySelector('[data-svz-cal-next]');
    var todayBtn = calendar.querySelector('[data-svz-cal-today]');
    var select = calendar.querySelector('[data-svz-cal-select]');
    var current = parseInt(calendar.getAttribute('data-active'), 10) || 0;

    var setMonth = function (index) {
      index = Math.max(0, Math.min(months.length - 1, index));
      current = index;

      months.forEach(function (month, i) {
        month.classList.toggle('is-active', i === index);
      });

      titleEl.textContent = months[index].getAttribute('aria-label');
      select.value = String(index);
      prevBtn.disabled = index <= 0;
      nextBtn.disabled = index >= months.length - 1;
    };

    prevBtn.addEventListener('click', function () {
      setMonth(current - 1);
    });

    nextBtn.addEventListener('click', function () {
      setMonth(current + 1);
    });

    todayBtn.addEventListener('click', function () {
      setMonth(0);
    });

    select.addEventListener('change', function () {
      setMonth(parseInt(select.value, 10));
    });

    calendar.addEventListener('click', function (event) {
      var link = event.target.closest('[data-svz-goto-month]');

      if (link) {
        event.preventDefault();
        setMonth(parseInt(link.getAttribute('data-svz-goto-month'), 10));
      }
    });

    setMonth(current);

    // A click on a day shows the button "Den xx.xx.xxxx direkt anfragen" below the calendar.
    // It leads to the contact form which has the date preselected (?datum=YYYY-MM-DD).
    var requestBox = calendar.querySelector('[data-svz-cal-request]');
    var contactUrl = calendar.getAttribute('data-contact-url');
    var selectedButton = null;

    var formatDate = function (iso) {
      var parts = iso.split('-');

      return parts[2] + '.' + parts[1] + '.' + parts[0];
    };

    var addNote = function (text, modifier) {
      var note = document.createElement('p');

      note.className = 'svz-cal__request-note' + (modifier ? ' svz-cal__request-note--' + modifier : '');
      note.textContent = text;
      requestBox.appendChild(note);
    };

    var showRequest = function (day) {
      var iso = day.getAttribute('data-date');
      var date = formatDate(iso);

      requestBox.textContent = '';

      if (day.classList.contains('is-booked')) {
        addNote('Der ' + date + ' ist leider bereits belegt. Bitte wählen Sie einen anderen Termin.', 'booked');

        return;
      }

      if (day.classList.contains('is-free')) {
        addNote('Der ' + date + ' ist noch frei.', 'free');
      }

      var button = document.createElement('a');
      var icon = document.createElement('i');

      icon.className = 'fa fa-envelope-o';
      icon.setAttribute('aria-hidden', 'true');
      button.className = 'svz-btn svz-btn--accent';
      button.href = contactUrl + (contactUrl.indexOf('?') === -1 ? '?' : '&') + 'datum=' + encodeURIComponent(iso);
      button.appendChild(icon);
      button.appendChild(document.createTextNode('Den ' + date + ' direkt anfragen'));
      requestBox.appendChild(button);
    };

    if (requestBox && contactUrl) {
      calendar.addEventListener('click', function (event) {
        var button = event.target.closest('[data-svz-day]');

        if (!button || button.disabled || !calendar.contains(button)) {
          return;
        }

        if (selectedButton) {
          selectedButton.parentNode.classList.remove('is-selected');
          selectedButton.setAttribute('aria-pressed', 'false');
        }

        selectedButton = button;
        button.parentNode.classList.add('is-selected');
        button.setAttribute('aria-pressed', 'true');
        showRequest(button.parentNode);
      });
    }
  }

  // ------------------------------------------------------------------
  // Tariff switch (prices)
  // ------------------------------------------------------------------
  var tariffSwitch = root.querySelector('[data-svz-tariff-switch]');

  if (tariffSwitch) {
    var tariffButtons = toArray(tariffSwitch.querySelectorAll('[data-svz-tariff]'));

    tariffSwitch.hidden = false;

    tariffButtons.forEach(function (button) {
      button.addEventListener('click', function () {
        root.setAttribute('data-tariff', button.getAttribute('data-svz-tariff'));

        tariffButtons.forEach(function (other) {
          var active = other === button;
          other.classList.toggle('is-active', active);
          other.setAttribute('aria-pressed', active ? 'true' : 'false');
        });
      });
    });
  }

  // ------------------------------------------------------------------
  // FAQ accordion
  // ------------------------------------------------------------------
  var faqList = root.querySelector('[data-svz-faq]');

  if (faqList) {
    faqList.addEventListener('click', function (event) {
      var trigger = event.target.closest('[data-svz-faq-trigger]');

      if (!trigger || !faqList.contains(trigger)) {
        return;
      }

      var item = trigger.closest('.svz-faq__item');
      var open = !item.classList.contains('is-open');

      item.classList.toggle('is-open', open);
      trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  // ------------------------------------------------------------------
  // Gallery lightbox
  // ------------------------------------------------------------------
  if (window.jQuery && window.jQuery.fn.magnificPopup) {
    window.jQuery('[data-svz-gallery]').magnificPopup({
      delegate: 'a',
      type: 'image',
      gallery: {
        enabled: true,
        navigateByImgClick: true
      },
      image: {
        titleSrc: 'title'
      }
    });
  }

  // ------------------------------------------------------------------
  // Reveal animations
  // ------------------------------------------------------------------
  var revealItems = toArray(root.querySelectorAll('.svz-reveal'));

  // slightly stagger items which are located next to each other
  revealItems.forEach(function (item) {
    var index = toArray(item.parentNode.children).indexOf(item);
    item.style.transitionDelay = (index % 4) * 70 + 'ms';
  });

  if ('IntersectionObserver' in window && !reduceMotion) {
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, {rootMargin: '0px 0px -8% 0px', threshold: 0.08});

    // content which is visible right now must not flash
    revealItems.forEach(function (item) {
      if (item.offsetParent !== null && item.getBoundingClientRect().top < window.innerHeight) {
        item.classList.add('is-visible');
      }
    });

    root.setAttribute('data-svz-ready', '');

    revealItems.forEach(function (item) {
      if (!item.classList.contains('is-visible')) {
        observer.observe(item);
      }
    });
  } else {
    revealItems.forEach(function (item) {
      item.classList.add('is-visible');
    });
    root.setAttribute('data-svz-ready', '');
  }

})();
