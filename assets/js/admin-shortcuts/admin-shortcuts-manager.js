import AdminShortcut from './admin-shortcut';

export default class AdminShortcutsManager {
  constructor(shortcutsForm) {
    this.shortcuts = [];
    this.count = 0;
    this.shortcutList = document.querySelector('#shortcut-list');
    this.shortcutsInput = document.querySelector('#shortcuts-input');

    var addShortcut = document.querySelector('#add-shortcut');

    // On shorcuts form submit, clear admin bar shorcuts cache
    shortcutsForm.addEventListener('submit', function() {
      window.biblys.adminBar.clearShortcutsCache();
    });

    // Add shorcut event
    addShortcut.addEventListener('change', () => {
      var selectedOption = addShortcut.options[addShortcut.selectedIndex],
        optionData = selectedOption.dataset;

      if (selectedOption.value == '') {
        return;
      }

      // Create new shortcut
      var shortcut = new AdminShortcut(
        {
          name: selectedOption.value,
          icon: optionData.icon,
          url: optionData.url,
          class: optionData.class,
          subscription: optionData.subscription
        },
        this
      );

      // Push shortcut in the array
      this.add(shortcut);

      // Reset selected option
      this.selectedIndex = 0;
    });

    // Load shortcuts from input
    this.loadFromInput();
  }

  add(shortcut) {
    if (this.count >= 12) {
      window._alert('Vous ne pouvez pas ajouter plus de 12 raccourcis.');
      return;
    }

    this.shortcuts.push(shortcut);
    this.count++;

    // Add new shortcut to the HTML list
    this.shortcutList.appendChild(shortcut.renderHtml());

    // Update input value
    this.updateInput();
  }

  remove(shortcut) {
    for (var i = 0, c = this.shortcuts.length; i < c; i++) {
      if (this.shortcuts[i] == shortcut) {
        this.shortcuts.splice(i, 1);
        shortcut.remove();
        this.updateInput();
        this.count--;
      }
    }
  }

  updateInput() {
    var value = this.shortcuts.map(function(shortcut) {
      return shortcut.props;
    });

    this.shortcutsInput.value = JSON.stringify(value);

    // Update preview in admin bar
    if (window.biblys.adminBar) {
      window.biblys.adminBar.loadPreviewFromInput(value);
    }
  }

  loadFromInput() {
    try {
      var shortcuts = JSON.parse(this.shortcutsInput.value);
    } catch (e) {
      return;
    }

    this.shortcutsInput.value = '';
    shortcuts.forEach(
      function(shortcut) {
        this.add(
          new AdminShortcut(
            {
              name: shortcut.name,
              icon: shortcut.icon,
              url: shortcut.url,
              class: shortcut.class,
              subscription: shortcut.subscription
            },
            this
          )
        );
      }.bind(this)
    );
  }
}

document.addEventListener('DOMContentLoaded', function() {});
