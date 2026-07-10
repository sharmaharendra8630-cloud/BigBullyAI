class AboutPage {
  constructor() {
    this.init();
  }

  init() {
    this.observeElements();
  }

  observeElements() {
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

    // Observe cards
    document.querySelectorAll('.mission-card, .value-card, .team-member').forEach(el => {
      observer.observe(el);
    });
  }
}

document.addEventListener('DOMContentLoaded', () => {
  new AboutPage();

  // Add CSS animation
  if (!document.querySelector('#aboutAnimations')) {
    const style = document.createElement('style');
    style.id = 'aboutAnimations';
    style.textContent = `
      @keyframes fadeInUp {
        from {
          opacity: 0;
          transform: translateY(30px);
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