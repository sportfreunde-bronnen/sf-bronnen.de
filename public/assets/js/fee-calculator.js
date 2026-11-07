/**
 * Fee calculator ("Beitragsrechner"), template beitraege.php
 *
 * The fees come from the page (structure field "beitraege", see BeitraegePage::calculator()) as JSON in the
 * attribute data-config of the calculator. Progressive enhancement: the calculator is hidden until this script runs.
 *
 * Calculation (all amounts per year):
 * - Jugendlich, Erwachsen: base fee + fees of the selected departments
 * - Familie: base fee once per family + fees of the selected departments for every family member (per head)
 * - Passiv: base fee only
 * A department is only available in a fee class if it has a fee there. Amounts are calculated in cents.
 */
(function () {
  'use strict';

  var has = function (object, key) {
    return Object.prototype.hasOwnProperty.call(object, key);
  };

  var toCents = function (amount) {
    return Math.round((Number(amount) || 0) * 100);
  };

  /**
   * @param {Object} config  fees, see BeitraegePage::calculator()
   * @param {{fee: ?string, persons: string[][]}} selection  chosen fee class and the chosen departments of every person
   * @return {?Object} null as long as no valid fee class is chosen
   */
  var calculate = function (config, selection) {
    var feeClass = selection.fee !== null && has(config.classes, selection.fee) ? config.classes[selection.fee] : null;

    if (!feeClass) {
      return null;
    }

    var selected = feeClass.family ? selection.persons : selection.persons.slice(0, 1);

    if (selected.length === 0) {
      selected = [[]];
    }

    var base = toCents(feeClass.base);
    var total = base;
    var separate = {};

    var persons = selected.map(function (departments) {
      var items = [];

      Object.keys(config.departments).forEach(function (key) {
        if (departments.indexOf(key) === -1 || !has(feeClass.departments, key)) {
          return;
        }

        var amount = toCents(feeClass.departments[key]);
        var isSeparate = config.departments[key].separate === true;

        items.push({ key: key, label: config.departments[key].label, amount: amount, separate: isSeparate });
        total += amount;

        if (isSeparate) {
          separate[key] = (separate[key] || 0) + amount;
        }
      });

      return { items: items };
    });

    return {
      label: feeClass.label,
      family: feeClass.family === true,
      base: base,
      persons: persons,
      total: total,
      separate: Object.keys(separate).map(function (key) {
        return { label: config.departments[key].label, amount: separate[key] };
      })
    };
  };

  if (typeof module !== 'undefined' && module.exports) {
    module.exports = { calculate: calculate };
  }

  if (typeof document === 'undefined') {
    return;
  }

  var root = document.querySelector('[data-fee-calculator]');

  if (!root) {
    return;
  }

  var config;

  try {
    config = JSON.parse(root.getAttribute('data-config'));
  } catch (error) {
    return;
  }

  var formatter = typeof Intl !== 'undefined' ? new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' }) : null;

  var format = function (cents) {
    var amount = cents / 100;

    return formatter ? formatter.format(amount) : amount.toFixed(2).replace('.', ',') + ' €';
  };

  var el = function (tag, className, text) {
    var node = document.createElement(tag);

    if (className) {
      node.className = className;
    }

    if (text !== undefined) {
      node.textContent = text;
    }

    return node;
  };

  var departmentsSection = root.querySelector('[data-fee-departments]');
  var familyHint = root.querySelector('[data-fee-family-hint]');
  var noDepartments = root.querySelector('[data-fee-no-departments]');
  var list = root.querySelector('[data-fee-persons]');
  var addButton = root.querySelector('[data-fee-add]');
  var template = root.querySelector('[data-fee-person-template]');
  var result = root.querySelector('[data-fee-result]');
  var maxMembers = parseInt(config.maxMembers, 10) || 1;
  var counter = 0;

  var persons = function () {
    return list.querySelectorAll('[data-fee-person]');
  };

  var readSelection = function () {
    var checked = root.querySelector('input[name="fee-class"]:checked');
    var selection = { fee: checked ? checked.value : null, persons: [] };

    persons().forEach(function (person) {
      var departments = [];

      person.querySelectorAll('input[data-fee-department-input]:checked').forEach(function (input) {
        departments.push(input.value);
      });

      selection.persons.push(departments);
    });

    return selection;
  };

  var addPerson = function () {
    var wrapper = document.createElement('div');

    wrapper.innerHTML = template.innerHTML.replace(/__INDEX__/g, counter++);

    var person = wrapper.firstElementChild;

    if (person) {
      list.appendChild(person);
    }

    return person;
  };

  var line = function (label, cents, className) {
    var item = el('li', 'fee-calculator__line' + (className ? ' ' + className : ''));

    item.appendChild(el('span', 'fee-calculator__line-label', label));
    item.appendChild(el('span', 'fee-calculator__line-amount', format(cents)));

    return item;
  };

  var renderResult = function (calculation) {
    result.textContent = '';

    if (!calculation) {
      result.appendChild(el('p', 'fee-calculator__placeholder', 'Bitte wählen Sie eine Beitragsklasse, um Ihren Jahresbeitrag zu berechnen.'));

      return;
    }

    var total = el('div', 'fee-calculator__total');
    total.setAttribute('role', 'status');
    total.appendChild(el('span', 'fee-calculator__total-label', 'Ihr Jahresbeitrag'));
    total.appendChild(el('strong', 'fee-calculator__total-amount', format(calculation.total)));
    result.appendChild(total);

    var lines = el('ul', 'fee-calculator__lines list-unstyled');
    var severalPersons = calculation.persons.length > 1;

    lines.appendChild(line(calculation.family ? 'Grundbeitrag (pro Familie)' : 'Grundbeitrag', calculation.base));

    calculation.persons.forEach(function (person, index) {
      if (severalPersons) {
        lines.appendChild(el('li', 'fee-calculator__line-title', 'Familienmitglied ' + (index + 1)));
      }

      if (severalPersons && person.items.length === 0) {
        lines.appendChild(line('Keine Abteilung', 0, 'is-muted'));
      }

      person.items.forEach(function (item) {
        lines.appendChild(line(item.label + (item.separate ? ' *' : ''), item.amount));
      });
    });

    result.appendChild(lines);

    calculation.separate.forEach(function (item) {
      result.appendChild(el(
        'p',
        'fee-calculator__note',
        '* Der Beitrag für ' + item.label + ' (' + format(item.amount) + ') ist im Jahresbeitrag enthalten, wird aber über eine getrennte Einzugsermächtigung erhoben.'
      ));
    });
  };

  var update = function () {
    var selection = readSelection();
    var feeClass = selection.fee !== null && has(config.classes, selection.fee) ? config.classes[selection.fee] : null;
    var family = feeClass !== null && feeClass.family === true;
    var hasDepartments = feeClass !== null && Object.keys(feeClass.departments).length > 0;
    var rows = persons();

    departmentsSection.hidden = !hasDepartments;
    noDepartments.hidden = feeClass === null || hasDepartments;
    familyHint.hidden = !family;
    addButton.hidden = !family;
    addButton.disabled = rows.length >= maxMembers;

    rows.forEach(function (person, index) {
      var head = person.querySelector('[data-fee-person-head]');
      var title = person.querySelector('[data-fee-person-title]');
      var remove = person.querySelector('[data-fee-remove]');

      // only the first person counts for the single fee classes
      person.hidden = !family && index > 0;
      head.hidden = !family;
      remove.hidden = index === 0;
      title.textContent = 'Familienmitglied ' + (index + 1);

      person.querySelectorAll('[data-fee-department]').forEach(function (department) {
        var key = department.getAttribute('data-fee-department');
        var available = feeClass !== null && has(feeClass.departments, key);

        department.hidden = !available;

        if (available) {
          department.querySelector('[data-fee-price]').textContent = format(toCents(feeClass.departments[key]));
        }
      });
    });

    renderResult(calculate(config, selection));
  };

  root.addEventListener('change', update);

  addButton.addEventListener('click', function () {
    if (persons().length >= maxMembers) {
      return;
    }

    var person = addPerson();

    update();

    var first = person ? person.querySelector('input') : null;

    if (first) {
      first.focus();
    }
  });

  list.addEventListener('click', function (event) {
    var button = event.target.closest('[data-fee-remove]');

    if (!button) {
      return;
    }

    var person = button.closest('[data-fee-person]');

    if (person && persons().length > 1) {
      person.parentNode.removeChild(person);
      update();
      addButton.focus();
    }
  });

  addPerson();
  root.hidden = false;
  update();
})();
