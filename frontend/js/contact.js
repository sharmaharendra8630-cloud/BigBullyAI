class ContactPage {
  constructor() {
    this.contactForm = document.getElementById('contactForm');
    this.faqQuestions = document.querySelectorAll('.faq-question');
    this.init();
  }

  init() {
    this.attachFormListener();
    this.attachFaqListeners();
  }

  attachFormListener() {
    if (this.contactForm) {
      this.contactForm.addEventListener('submit', (e) => {
        e.preventDefault();
        this.submitForm();
      });
    }
  }

  submitForm() {
    const formData = new FormData(this.contactForm);
    const submitBtn = this.contactForm.querySelector('button[type="submit"]');
    
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Sending...';
    submitBtn.disabled = true;

    // Simulate API call
    setTimeout(() => {
      submitBtn.textContent = '✓ Message Sent!';
      this.contactForm.reset();
      
      setTimeout(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
      }, 2000);
    }, 1000);
  }

  attachFaqListeners() {
    this.faqQuestions.forEach(question => {
      question.addEventListener('click', () => {
        const parent = question.closest('.faq-item');
        const answer = parent.querySelector('.faq-answer');

        // Close other items
        document.querySelectorAll('.faq-item').forEach(item => {
          if (item !== parent) {
            item.classList.remove('active');
            item.querySelector('.faq-answer').style.display = 'none';
          }
        });

        // Toggle current item
        parent.classList.toggle('active');
        answer.style.display = answer.style.display === 'none' ? 'block' : 'none';
      });
    });
  }
}

document.addEventListener('DOMContentLoaded', () => {
  new ContactPage();
});