/**
 * API Client - Handles all API communication
 * Base URL: /api
 */

class APIClient {
  constructor(baseURL = '/api') {
    this.baseURL = baseURL;
    this.timeout = 30000; // 30 seconds
    this.token = localStorage.getItem('auth_token');
  }

  /**
   * Get request
   */
  async get(endpoint, options = {}) {
    return this.request(endpoint, 'GET', null, options);
  }

  /**
   * Post request
   */
  async post(endpoint, data, options = {}) {
    return this.request(endpoint, 'POST', data, options);
  }

  /**
   * Put request
   */
  async put(endpoint, data, options = {}) {
    return this.request(endpoint, 'PUT', data, options);
  }

  /**
   * Delete request
   */
  async delete(endpoint, options = {}) {
    return this.request(endpoint, 'DELETE', null, options);
  }

  /**
   * Main request method
   */
  async request(endpoint, method = 'GET', data = null, options = {}) {
    const url = `${this.baseURL}${endpoint}`;
    const headers = {
      'Content-Type': 'application/json',
      ...this.getAuthHeader(),
      ...options.headers,
    };

    const config = {
      method,
      headers,
      ...options,
    };

    if (data) {
      config.body = JSON.stringify(data);
    }

    try {
      const response = await Promise.race([
        fetch(url, config),
        new Promise((_, reject) =>
          setTimeout(() => reject(new Error('Request timeout')), this.timeout)
        ),
      ]);

      return await this.handleResponse(response);
    } catch (error) {
      this.handleError(error);
      throw error;
    }
  }

  /**
   * Handle response
   */
  async handleResponse(response) {
    const data = await response.json();

    if (!response.ok) {
      if (response.status === 401) {
        localStorage.removeItem('auth_token');
        window.location.hash = '#login';
      }
      throw new Error(data.message || 'API Error');
    }

    return data;
  }

  /**
   * Handle errors
   */
  handleError(error) {
    console.error('API Error:', error);
    document.dispatchEvent(
      new CustomEvent('api-error', { detail: error.message })
    );
  }

  /**
   * Get auth header
   */
  getAuthHeader() {
    const token = localStorage.getItem('auth_token');
    return token ? { Authorization: `Bearer ${token}` } : {};
  }

  /**
   * Stock endpoints
   */
  async getStocks(filters = {}) {
    const query = new URLSearchParams(filters);
    return this.get(`/stocks?${query}`);
  }

  async getStock(symbol) {
    return this.get(`/stocks/${symbol}`);
  }

  async analyzeStock(symbol) {
    return this.post(`/stocks/${symbol}/analyze`, {});
  }

  /**
   * Market endpoints
   */
  async getMarketOverview() {
    return this.get('/market/overview');
  }

  async getMarketData() {
    return this.get('/market/data');
  }

  /**
   * User endpoints
   */
  async getUserProfile() {
    return this.get('/user/profile');
  }

  async updateProfile(data) {
    return this.put('/user/profile', data);
  }

  async getWatchlist() {
    return this.get('/user/watchlist');
  }

  async addToWatchlist(symbol) {
    return this.post('/user/watchlist', { symbol });
  }

  async removeFromWatchlist(symbol) {
    return this.delete(`/user/watchlist/${symbol}`);
  }

  /**
   * Auth endpoints
   */
  async login(email, password) {
    return this.post('/auth/login', { email, password });
  }

  async register(userData) {
    return this.post('/auth/register', userData);
  }

  async logout() {
    localStorage.removeItem('auth_token');
    return this.post('/auth/logout', {});
  }

  /**
   * Payment endpoints
   */
  async createPayment(plan, amount) {
    return this.post('/payments/create', { plan, amount });
  }

  async verifyPayment(paymentId) {
    return this.post('/payments/verify', { paymentId });
  }
}

// Export for use
if (typeof module !== 'undefined' && module.exports) {
  module.exports = APIClient;
}