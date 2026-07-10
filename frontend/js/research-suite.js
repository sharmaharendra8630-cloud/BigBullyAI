class ResearchSuite {
  constructor() {
    this.tabs = document.querySelectorAll('.nav-link');
    this.init();
  }

  init() {
    this.attachTabListeners();
    this.observeCards();
  }

  attachTabListeners() {
    this.tabs.forEach(tab => {
      tab.addEventListener('click', () => {
        setTimeout(() => {
          this.animateContent();
        }, 100);
      });
    });
  }

  animateContent() {
    const content = document.querySelector('.tab-content .active');
    if (content) {
      content.style.animation = 'none';
      setTimeout(() => {
        content.style.animation = 'fadeIn 0.3s ease';
      }, 10);
    }
  }

  observeCards() {
    const cards = document.querySelectorAll('.research-card');
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry, index) => {
        if (entry.isIntersecting) {
          setTimeout(() => {
            entry.target.style.animation = 'fadeInUp 0.6s ease forwards';
          }, index * 100);
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1 });

    cards.forEach(card => observer.observe(card));
  }
}

document.addEventListener('DOMContentLoaded', () => {
  new ResearchSuite();

  // Add CSS animation
  if (!document.querySelector('#researchAnimations')) {
    const style = document.createElement('style');
    style.id = 'researchAnimations';
    style.textContent = `
      @keyframes fadeInUp {
        from {
          opacity: 0;
          transform: translateY(20px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }
    `;
    document.head.appendChild(style);
  }
});