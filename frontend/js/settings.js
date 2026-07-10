class SettingsPage {
  constructor() {
    this.navItems = document.querySelectorAll('.settings-nav-item');
    this.panels = document.querySelectorAll('.settings-panel');
    this.toggleSwitches = document.querySelectorAll('.toggle-switch input');
    this.themeOptions = document.querySelectorAll('.theme-option input');
    this.init();
  }

  init() {
    this.attachNavListeners();
    this.attachToggleSwitches();
    this.attachThemeOptions();
  }

  attachNavListeners() {
    this.navItems.forEach(item => {
      item.addEventListener('click', (e) => {
        e.preventDefault();
        const section = item.dataset.section;
        this.showPanel(section);
      });
    });
  }

  showPanel(section) {
    // Remove active class from all
    this.navItems.forEach(item => item.classList.remove('active'));
    this.panels.forEach(panel => panel.classList.remove('active'));

    // Add active class to selected
    document.querySelector(`[data-section="${section}"]`).classList.add('active');
    document.getElementById(section).classList.add('active');
  }

  attachToggleSwitches() {
    this.toggleSwitches.forEach(toggle => {
      toggle.addEventListener('change', (e) => {
        console.log(`Setting changed: ${e.target.id} = ${e.target.checked}`);
      });
    });
  }

  attachThemeOptions() {
    this.themeOptions.forEach(option => {
      option.addEventListener('change', (e) => {
        console.log(`Theme changed to: ${e.target.value}`);
      });
    });
  }
}

document.addEventListener('DOMContentLoaded', () => {
  new SettingsPage();
});