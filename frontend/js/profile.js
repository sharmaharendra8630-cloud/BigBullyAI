class ProfilePage {
  constructor() {
    this.toggleSwitches = document.querySelectorAll('.toggle-switch input');
    this.forms = document.querySelectorAll('.profile-form');
    this.init();
  }

  init() {
    this.attachToggleSwitchListeners();
    this.attachFormListeners();
  }

  attachToggleSwitchListeners() {
    this.toggleSwitches.forEach(toggle => {
      toggle.addEventListener('change', (e) => {
        const label = e.target.nextElementSibling.getAttribute('for');
        console.log(`Toggle ${label}: ${e.target.checked}`);
        localStorage.setItem(label, e.target.checked);
      });
    });
  }

  attachFormListeners() {
    this.forms.forEach(form => {
      form.addEventListener('submit', (e) => {
        e.preventDefault();
        console.log('Profile form submitted');
        const btn = form.querySelector('button[type="submit"]');
        const originalText = btn.textContent;
        btn.textContent = '✓ Saved!';
        setTimeout(() => {
          btn.textContent = originalText;
        }, 2000);
      });
    });
  }
}

document.addEventListener('DOMContentLoaded', () => {
  new ProfilePage();
});