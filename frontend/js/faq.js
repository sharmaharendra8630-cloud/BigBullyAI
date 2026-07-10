class FAQPage {
  constructor() {
    this.categoryBtns = document.querySelectorAll('.category-btn');
    this.faqHeaders = document.querySelectorAll('.faq-header-btn');
    this.init();
  }

  init() {
    this.attachCategoryListeners();
    this.attachFaqListeners();
  }

  attachCategoryListeners() {
    this.categoryBtns.forEach(btn => {
      btn.addEventListener('click', (e) => {
        const category = btn.dataset.category;
        
        // Remove active from all buttons
        this.categoryBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        // Hide all content
        document.querySelectorAll('.faq-category-content').forEach(content => {
          content.classList.remove('active');
        });

        // Show selected content
        document.getElementById(category).classList.add('active');
      });
    });
  }

  attachFaqListeners() {
    this.faqHeaders.forEach(header => {
      header.addEventListener('click', () => {
        const parent = header.closest('.faq-item');
        const body = parent.querySelector('.faq-body');

        // Close other items
        document.querySelectorAll('.faq-item').forEach(item => {
          if (item !== parent) {
            item.classList.remove('active');
            item.querySelector('.faq-body').style.display = 'none';
          }
        });

        // Toggle current item
        parent.classList.toggle('active');
        body.style.display = body.style.display === 'none' ? 'block' : 'none';
      });
    });
  }
}

document.addEventListener('DOMContentLoaded', () => {
  new FAQPage();
});