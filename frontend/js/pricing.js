class PricingPage {
  constructor() {
    this.billingToggle = document.getElementById('billingToggle');
    this.proPriceMonthly = document.getElementById('proPriceMonthly');
    this.premiumPriceMonthly = document.getElementById('premiumPriceMonthly');
    this.init();
  }

  init() {
    this.attachToggleListener();
  }

  attachToggleListener() {
    if (this.billingToggle) {
      this.billingToggle.addEventListener('change', (e) => {
        if (e.target.checked) {
          // Yearly prices (20% discount)
          this.proPriceMonthly.textContent = '₹4,788';
          this.premiumPriceMonthly.textContent = '₹9,588';
        } else {
          // Monthly prices
          this.proPriceMonthly.textContent = '₹499';
          this.premiumPriceMonthly.textContent = '₹999';
        }
      });
    }
  }
}

document.addEventListener('DOMContentLoaded', () => {
  new PricingPage();
});