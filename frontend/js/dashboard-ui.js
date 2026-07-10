class DashboardUI {
  constructor() {
    this.sidebarItems = document.querySelectorAll('.nav-item');
    this.init();
  }

  init() {
    this.attachNavListeners();
    this.attachTableActions();
  }

  attachNavListeners() {
    this.sidebarItems.forEach(item => {
      item.addEventListener('click', (e) => {
        e.preventDefault();
        
        // Remove active class from all items
        this.sidebarItems.forEach(i => i.classList.remove('active'));
        
        // Add active class to clicked item
        item.classList.add('active');
      });
    });
  }

  attachTableActions() {
    const actionButtons = document.querySelectorAll('.btn-action');
    actionButtons.forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        console.log('Action menu clicked for:', btn.closest('tr').querySelector('strong').textContent);
      });
    });
  }
}

document.addEventListener('DOMContentLoaded', () => {
  new DashboardUI();
});