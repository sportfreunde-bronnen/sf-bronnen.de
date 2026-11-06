/**
 * Membership application ("Mitglied werden"), template membership.php
 *
 * Progressive enhancement: the form also works without JavaScript, the server validates everything again.
 * - The passive membership and the groups exclude each other
 * - The additional family members are only shown for the fee class "Familienbeitrag" and can be added and removed
 * - The name of the account holder can be taken over from the personal data
 * - IBAN and BIC are formatted
 */
(function () {
  'use strict';

  var form = document.querySelector('[data-membership-form]');

  if (!form) {
    return;
  }

  // ---------------------------------------------------------------------------------------------------------------
  // The passive membership excludes every group
  // ---------------------------------------------------------------------------------------------------------------

  var passive = form.querySelector('[data-membership-passive]');
  var groups = form.querySelectorAll('[data-membership-group]');

  if (passive) {
    passive.addEventListener('change', function () {
      if (passive.checked) {
        groups.forEach(function (group) {
          group.checked = false;
        });
      }
    });

    groups.forEach(function (group) {
      group.addEventListener('change', function () {
        if (group.checked) {
          passive.checked = false;
        }
      });
    });
  }

  // ---------------------------------------------------------------------------------------------------------------
  // Additional family members
  // ---------------------------------------------------------------------------------------------------------------

  var family = form.querySelector('[data-membership-family]');
  var feeClasses = form.querySelectorAll('input[name="beitragsklasse"]');

  if (family) {
    var list = family.querySelector('[data-membership-family-list]');
    var addButton = family.querySelector('[data-membership-family-add]');
    var template = document.getElementById('membership-family-template');
    var maxMembers = parseInt(family.getAttribute('data-max'), 10) || 10;

    var rows = function () {
      return list.querySelectorAll('[data-membership-family-row]');
    };

    var nextIndex = function () {
      var index = 0;

      rows().forEach(function (row) {
        row.querySelectorAll('[name^="familie["]').forEach(function (field) {
          var match = field.name.match(/^familie\[(\d+)\]/);

          if (match) {
            index = Math.max(index, parseInt(match[1], 10) + 1);
          }
        });
      });

      return index;
    };

    var refresh = function () {
      rows().forEach(function (row, position) {
        var number = row.querySelector('[data-membership-family-number]');

        if (number) {
          number.textContent = position + 1;
        }
      });

      if (addButton) {
        addButton.disabled = rows().length >= maxMembers;
      }
    };

    var isFamilyFee = function () {
      var selected = form.querySelector('input[name="beitragsklasse"]:checked');

      return selected !== null && selected.value === 'familie';
    };

    // the family members are only relevant for the family fee
    var toggleFamily = function () {
      var visible = isFamilyFee();

      family.hidden = !visible;
      family.querySelectorAll('input, select').forEach(function (field) {
        field.disabled = !visible;
      });
    };

    feeClasses.forEach(function (feeClass) {
      feeClass.addEventListener('change', toggleFamily);
    });

    if (addButton && template && list) {
      addButton.hidden = false;

      addButton.addEventListener('click', function () {
        if (rows().length >= maxMembers) {
          return;
        }

        var wrapper = document.createElement('div');
        wrapper.innerHTML = template.innerHTML.replace(/__INDEX__/g, nextIndex());
        var row = wrapper.firstElementChild;

        if (row) {
          list.appendChild(row);
          refresh();

          var first = row.querySelector('input');
          if (first) {
            first.focus();
          }
        }
      });
    }

    if (list) {
      list.addEventListener('click', function (event) {
        var button = event.target.closest('[data-membership-family-remove]');

        if (!button) {
          return;
        }

        var row = button.closest('[data-membership-family-row]');

        if (rows().length > 1) {
          row.parentNode.removeChild(row);
        } else {
          // the last row stays, but is emptied
          row.querySelectorAll('input').forEach(function (field) {
            field.value = '';
          });
          row.querySelectorAll('select').forEach(function (field) {
            field.selectedIndex = 0;
          });
        }

        refresh();
      });
    }

    refresh();
    toggleFamily();
  }

  // ---------------------------------------------------------------------------------------------------------------
  // The account holder is the member
  // ---------------------------------------------------------------------------------------------------------------

  var copyWrap = form.querySelector('[data-membership-account-copy-wrap]');
  var copy = form.querySelector('[data-membership-account-copy]');
  var pairs = [
    [document.getElementById('m-nachname'), document.getElementById('m-kontoinhaber-nachname')],
    [document.getElementById('m-vorname'), document.getElementById('m-kontoinhaber-vorname')]
  ];

  if (copyWrap && copy && pairs.every(function (pair) { return pair[0] && pair[1]; })) {
    copyWrap.hidden = false;

    var takeOver = function () {
      if (copy.checked) {
        pairs.forEach(function (pair) {
          pair[1].value = pair[0].value;
        });
      }
    };

    copy.addEventListener('change', takeOver);
    pairs.forEach(function (pair) {
      pair[0].addEventListener('input', takeOver);
    });
  }

  // ---------------------------------------------------------------------------------------------------------------
  // IBAN in groups of four characters, BIC in upper case
  // ---------------------------------------------------------------------------------------------------------------

  var iban = document.getElementById('m-iban');
  var bic = document.getElementById('m-bic');

  if (iban) {
    iban.addEventListener('blur', function () {
      iban.value = iban.value.replace(/\s+/g, '').toUpperCase().replace(/(.{4})/g, '$1 ').trim();
    });
  }

  if (bic) {
    bic.addEventListener('blur', function () {
      bic.value = bic.value.replace(/\s+/g, '').toUpperCase();
    });
  }

  // ---------------------------------------------------------------------------------------------------------------
  // After a failed submission the list of errors is brought into view
  // ---------------------------------------------------------------------------------------------------------------

  var errors = document.querySelector('.membership-wrap .alert-danger');

  if (errors && errors.scrollIntoView) {
    errors.scrollIntoView({ block: 'center' });
  }
})();
