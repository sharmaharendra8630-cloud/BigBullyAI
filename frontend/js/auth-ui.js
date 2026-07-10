class AuthUI {
  constructor() {
    this.loginCard = document.getElementById('loginCard');
    this.registerCard = document.getElementById('registerCard');
    this.forgotCard = document.getElementById('forgotCard');
    this.toggleLinks = document.querySelectorAll('.toggle-auth');
    this.forms = document.querySelectorAll('form');
    this.init();
  }

  init() {
    this.attachToggleListeners();
    this.attachFormListeners();
  }

  attachToggleListeners() {
    this.toggleLinks.forEach(link => {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        const text = e.target.textContent.toLowerCase();
        
        if (text.includes('create')) {
          this.showCard('register');
        } else if (text.includes('forgot') || text.includes('reset')) {
          this.showCard('forgot');
        } else {
          this.showCard('login');
        }
      });
    });
  }

  showCard(type) {
    // Hide all cards
    this.loginCard.classList.add('hidden');
    this.registerCard.classList.add('hidden');
    this.forgotCard.classList.add('hidden');

    // Show selected card
    switch(type) {
      case 'register':
        this.registerCard.classList.remove('hidden');
        break;
      case 'forgot':
        this.forgotCard.classList.remove('hidden');
        break;
      default:
        this.loginCard.classList.remove('hidden');
    }
  }

  attachFormListeners() {
    this.forms.forEach(form => {
      form.addEventListener('submit', (e) => {
        e.preventDefault();
        this.handleFormSubmit(form);
      });
    });
  }

  handleFormSubmit(form) {
    const formData = new FormData(form);
    console.log('Form submitted:', form.id);
    
    // Validate form
    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    // Show success message
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = '✓ Success!';
    submitBtn.disabled = true;

    // Reset after delay
    setTimeout(() => {
      submitBtn.textContent = originalText;
      submitBtn.disabled = false;
      form.reset();
    }, 2000);
  }
}

document.addEventListener('DOMContentLoaded', () => {
  new AuthUI();
});