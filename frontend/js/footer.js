class Footer {
  constructor() {
    this.init();
  }

  init() {
    this.observeFooter();
    this.attachSocialLinks();
  }

  observeFooter() {
    const footer = document.querySelector('.footer-section');
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.animation = 'fadeIn 0.6s ease forwards';
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1 });

    if (footer) observer.observe(footer);
  }

  attachSocialLinks() {
    const socialLinks = document.querySelectorAll('.social-link');
    socialLinks.forEach(link => {
      link.addEventListener('mouseover', () => {
        link.style.transform = 'translateY(-2px) scale(1.1)';
      });
      link.addEventListener('mouseout', () => {
        link.style.transform = 'translateY(0) scale(1)';
      });
    });
  }
}

document.addEventListener('DOMContentLoaded', () => {
  new Footer();
});