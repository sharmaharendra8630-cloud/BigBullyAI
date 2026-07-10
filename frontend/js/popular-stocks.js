class PopularStocks {
  constructor() {
    this.cards = document.querySelectorAll('.stock-card');
    this.init();
  }

  init() {
    this.drawCharts();
    this.observeCards();
  }

  drawCharts() {
    const stocks = [
      { id: 'tcsChart', data: [100, 105, 110, 108, 115, 120, 125, 122, 130] },
      { id: 'infyChart', data: [100, 102, 104, 103, 107, 110, 112, 109, 115] },
      { id: 'hdfcChart', data: [100, 99, 101, 100, 98, 99, 97, 96, 99] },
      { id: 'relianceChart', data: [100, 103, 107, 110, 112, 115, 120, 118, 125] },
      { id: 'wiproChart', data: [100, 101, 103, 102, 105, 108, 106, 109, 112] },
      { id: 'iciciChart', data: [100, 100, 102, 101, 103, 105, 104, 106, 108] }
    ];

    stocks.forEach(stock => {
      const canvas = document.getElementById(stock.id);
      if (canvas) this.drawChart(canvas, stock.data);
    });
  }

  drawChart(canvas, data) {
    const ctx = canvas.getContext('2d');
    const width = canvas.offsetWidth;
    const height = canvas.offsetHeight;

    canvas.width = width;
    canvas.height = height;

    const min = Math.min(...data);
    const max = Math.max(...data);
    const range = max - min;
    const spacing = width / (data.length - 1);

    // Determine color based on trend
    const isPositive = data[data.length - 1] >= data[0];
    const color = isPositive ? '#10b981' : '#ef4444';

    // Draw gradient
    const gradient = ctx.createLinearGradient(0, 0, 0, height);
    gradient.addColorStop(0, `${color}20`);
    gradient.addColorStop(1, `${color}00`);

    ctx.fillStyle = gradient;
    ctx.beginPath();
    ctx.moveTo(0, height);

    data.forEach((value, index) => {
      const x = index * spacing;
      const y = height - ((value - min) / range) * height * 0.8;
      ctx.lineTo(x, y);
    });

    ctx.lineTo(width, height);
    ctx.closePath();
    ctx.fill();

    // Draw line
    ctx.strokeStyle = color;
    ctx.lineWidth = 2;
    ctx.beginPath();

    data.forEach((value, index) => {
      const x = index * spacing;
      const y = height - ((value - min) / range) * height * 0.8;
      index === 0 ? ctx.moveTo(x, y) : ctx.lineTo(x, y);
    });

    ctx.stroke();
  }

  observeCards() {
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

    this.cards.forEach(card => observer.observe(card));
  }
}

document.addEventListener('DOMContentLoaded', () => {
  new PopularStocks();

  // Add CSS animation
  if (!document.querySelector('#popularStocksAnimations')) {
    const style = document.createElement('style');
    style.id = 'popularStocksAnimations';
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