class LegalPage {
  constructor() {
    this.tabBtns = document.querySelectorAll('.tab-btn');
    this.tabContents = document.querySelectorAll('.legal-content');
    this.init();
  }

  init() {
    this.attachTabListeners();
  }

  attachTabListeners() {
    this.tabBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        const tabName = btn.dataset.tab;

        // Remove active class from all
        this.tabBtns.forEach(b => b.classList.remove('active'));
        this.tabContents.forEach(content => content.classList.remove('active'));

        // Add active class to selected
        btn.classList.add('active');
        document.getElementById(tabName).classList.add('active');
      });
    });
  }
}

document.addEventListener('DOMContentLoaded', () => {
  new LegalPage();
});