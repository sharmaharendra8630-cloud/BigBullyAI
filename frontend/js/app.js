/**
 * BigBully AI - Main Application Script
 * Handles component initialization and routing
 */

class BigBullyApp {
  constructor() {
    this.user = null;
    this.isAuthenticated = false;
    this.currentPage = 'home';
    this.apiClient = new APIClient();
    this.init();
  }

  init() {
    this.loadComponents();
    this.setupRouting();
    this.checkAuthentication();
    this.setupEventListeners();
    console.log('🚀 BigBully AI initialized');
  }

  loadComponents() {
    // Load all components into their containers
    this.loadComponent('frontend/components/navigation.html', '#navigation-container');
    this.loadComponent('frontend/components/hero.html', '#hero-section');
    this.loadComponent('frontend/components/search.html', '#search-section');
    this.loadComponent('frontend/components/market-overview.html', '#market-overview-section');
    this.loadComponent('frontend/components/ai-cards.html', '#ai-cards-section');
    this.loadComponent('frontend/components/research-suite.html', '#research-suite-section');
    this.loadComponent('frontend/components/company-snapshot.html', '#company-snapshot-section');
    this.loadComponent('frontend/components/popular-stocks.html', '#popular-stocks-section');
    this.loadComponent('frontend/components/footer.html', '#footer-container');
  }

  loadComponent(path, selector) {
    fetch(path)
      .then(response => response.text())
      .then(html => {
        const container = document.querySelector(selector);
        if (container) {
          container.innerHTML = html;
          this.initializeComponentScripts();
        }
      })
      .catch(error => console.warn(`Could not load ${path}:`, error));
  }

  initializeComponentScripts() {
    // Re-run component initialization scripts
    // This is called after components are loaded dynamically
  }

  setupRouting() {
    window.addEventListener('hashchange', () => this.route());
    this.route();
  }

  route() {
    const hash = window.location.hash.slice(1) || 'home';
    this.currentPage = hash.split('/')[0];
    console.log(`📍 Routing to: ${this.currentPage}`);
    
    // Hide all pages
    document.querySelectorAll('[data-page]').forEach(page => {
      page.style.display = 'none';
    });
    
    // Show current page
    const currentPage = document.querySelector(`[data-page="${this.currentPage}"]`);
    if (currentPage) {
      currentPage.style.display = 'block';
    }
  }

  checkAuthentication() {
    const token = localStorage.getItem('auth_token');
    if (token) {
      this.isAuthenticated = true;
      this.loadUserData();
    }
  }

  async loadUserData() {
    try {
      const response = await this.apiClient.get('/api/user/profile');
      this.user = response.data;
      this.updateUI();
    } catch (error) {
      console.error('Error loading user data:', error);
      this.logout();
    }
  }

  updateUI() {
    // Update UI based on authentication state
    if (this.isAuthenticated && this.user) {
      document.querySelectorAll('.auth-only').forEach(el => {
        el.style.display = 'block';
      });
      document.querySelectorAll('.guest-only').forEach(el => {
        el.style.display = 'none';
      });
    } else {
      document.querySelectorAll('.auth-only').forEach(el => {
        el.style.display = 'none';
      });
      document.querySelectorAll('.guest-only').forEach(el => {
        el.style.display = 'block';
      });
    }
  }

  async login(email, password) {
    try {
      const response = await this.apiClient.post('/api/auth/login', { email, password });
      localStorage.setItem('auth_token', response.token);
      this.isAuthenticated = true;
      this.user = response.user;
      this.updateUI();
      return response;
    } catch (error) {
      console.error('Login failed:', error);
      throw error;
    }
  }

  async register(userData) {
    try {
      const response = await this.apiClient.post('/api/auth/register', userData);
      localStorage.setItem('auth_token', response.token);
      this.isAuthenticated = true;
      this.user = response.user;
      this.updateUI();
      return response;
    } catch (error) {
      console.error('Registration failed:', error);
      throw error;
    }
  }

  logout() {
    localStorage.removeItem('auth_token');
    this.isAuthenticated = false;
    this.user = null;
    this.updateUI();
    window.location.hash = '#home';
  }

  setupEventListeners() {
    // Global event listeners
    document.addEventListener('api-error', (e) => {
      console.error('API Error:', e.detail);
      this.showNotification('An error occurred. Please try again.', 'error');
    });
  }

  showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    notification.style.cssText = `
      position: fixed;
      top: 20px;
      right: 20px;
      padding: 1rem 1.5rem;
      border-radius: 8px;
      background: ${type === 'error' ? '#ef4444' : '#10b981'};
      color: white;
      z-index: 9999;
      animation: slideIn 0.3s ease;
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
      notification.remove();
    }, 3000);
  }
}

// Initialize app on DOM ready
document.addEventListener('DOMContentLoaded', () => {
  window.app = new BigBullyApp();
});

// Add slide in animation
const style = document.createElement('style');
style.textContent = `
  @keyframes slideIn {
    from {
      transform: translateX(400px);
      opacity: 0;
    }
    to {
      transform: translateX(0);
      opacity: 1;
    }
  }
`;
document.head.appendChild(style);