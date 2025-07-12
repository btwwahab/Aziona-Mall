class AIRecommendations {
    constructor(containerId, type = 'general') {
        this.containerId = containerId;
        this.type = type;
        this.apiUrl = `/api/ai-recommendations?type=${type}`;
        this.init();
    }

    init() {
        this.showLoadingState();
        this.loadRecommendations();
    }

    showLoadingState() {
        const container = document.querySelector(this.containerId);
        if (!container) return;

        container.innerHTML = `
            <div class="ai-recommendations-loading">
                <div class="ai-loading-spinner"></div>
                <p>Loading AI recommendations...</p>
            </div>
        `;
    }

    async loadRecommendations() {
        try {
            console.log('Loading AI recommendations for:', this.type);
            
            const response = await fetch(this.apiUrl);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('AI recommendations response:', data);
            
            if (data.status === 'success' && data.recommendations && data.recommendations.length > 0) {
                this.displayRecommendations(data.recommendations);
            } else {
                this.showEmptyState();
            }
        } catch (error) {
            console.error('Failed to load AI recommendations:', error);
            this.showErrorState();
        }
    }

    displayRecommendations(recommendations) {
        const container = document.querySelector(this.containerId);
        if (!container) return;

        const titles = {
            'general': '🤖 AI Recommendations for You',
            'related': '🔗 You Might Also Like',
            'trending': '🔥 Trending Now',
            'personal': '💎 Just for You',
            'cart': '🛒 Frequently Bought Together'
        };

        container.innerHTML = `
            <div class="ai-recommendations-wrapper">
                <div class="ai-recommendations-header">
                    <h3 class="ai-recommendations-title">
                        ${titles[this.type]}
                    </h3>
                    <span class="ai-badge">AI Powered</span>
                </div>
                <div class="ai-recommendations-grid">
                    ${recommendations.map(product => `
                        <div class="ai-recommendation-card">
                            <div class="ai-product-image">
                                <a href="${product.url}">
                                    <img src="${product.image}" alt="${product.name}" loading="lazy" 
                                         onerror="this.src='${this.getPlaceholderImage()}'">
                                </a>
                                ${product.discount_price ? '<span class="ai-discount-badge">Sale</span>' : ''}
                            </div>
                            <div class="ai-product-info">
                                <div class="ai-product-category">${product.category}</div>
                                <h4 class="ai-product-title">
                                    <a href="${product.url}">${product.name}</a>
                                </h4>
                                <div class="ai-product-price">
                                    ${product.discount_price ? 
                                        `<span class="ai-price-new">$${product.discount_price}</span>
                                         <span class="ai-price-old">$${product.price}</span>` : 
                                        `<span class="ai-price-new">$${product.price}</span>`
                                    }
                                </div>
                                <p class="ai-reason">${product.reason}</p>
                                <div class="ai-product-actions">
                                    <a href="${product.url}" class="ai-view-btn">
                                        <i class="fi fi-rs-eye"></i> 
                                    </a>
                                    <button class="ai-add-to-cart-btn" data-product-id="${product.id}">
                                        <i class="fi fi-rs-shopping-cart"></i> 
                                    </button>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;

        this.setupAddToCartButtons();
    }

    getPlaceholderImage() {
        return '/assets/img/product-placeholder.jpg';
    }

    showEmptyState() {
        const container = document.querySelector(this.containerId);
        if (!container) return;

        container.innerHTML = `
            <div class="ai-recommendations-empty">
                <div class="ai-empty-icon">
                    <i class="fi fi-rs-box-open"></i>
                </div>
                <h4>No AI Recommendations Available</h4>
                <p>We're learning about your preferences. Check back soon!</p>
            </div>
        `;
    }

    showErrorState() {
        const container = document.querySelector(this.containerId);
        if (!container) return;

        container.innerHTML = `
            <div class="ai-recommendations-error">
                <div class="ai-error-icon">
                    <i class="fi fi-rs-exclamation-triangle"></i>
                </div>
                <h4>Unable to Load AI Recommendations</h4>
                <p>Please check your internet connection and try again.</p>
                <button onclick="location.reload()" class="ai-retry-btn">
                    <i class="fi fi-rs-refresh"></i> Retry
                </button>
            </div>
        `;
    }

    setupAddToCartButtons() {
        const addToCartButtons = document.querySelectorAll('.ai-add-to-cart-btn');
        addToCartButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const productId = e.target.getAttribute('data-product-id') || 
                                e.target.closest('.ai-add-to-cart-btn').getAttribute('data-product-id');
                this.addToCart(productId, button);
            });
        });
    }

    addToCart(productId, button) {
        // Show loading state
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fi fi-rs-loading"></i> Adding...';
        button.disabled = true;

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('CSRF token not found');
            this.resetButton(button, originalText, 'error');
            this.showNotification('Security token not found. Please refresh the page.', 'error');
            return;
        }
        
        // Add to cart API call
        fetch('/add-to-cart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Show success state
                button.innerHTML = '<i class="fi fi-rs-check"></i> Added!';
                button.classList.add('ai-btn-success');
                
                // Update cart count if element exists
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    cartCount.textContent = data.cart_count;
                }
                
                // Show success notification
                this.showNotification(`Product added to cart successfully!`, 'success');
                
                // Reset button after 2 seconds
                setTimeout(() => {
                    this.resetButton(button, originalText);
                }, 2000);
            } else {
                throw new Error(data.message || 'Failed to add to cart');
            }
        })
        .catch(error => {
            console.error('Error adding to cart:', error);
            this.resetButton(button, originalText, 'error');
            this.showNotification(error.message || 'Failed to add product to cart', 'error');
        });
    }

    resetButton(button, originalText, state = 'normal') {
        if (state === 'error') {
            button.innerHTML = '<i class="fi fi-rs-exclamation-triangle"></i> Error';
            button.classList.add('ai-btn-error');
        }
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.disabled = false;
            button.classList.remove('ai-btn-success', 'ai-btn-error');
        }, state === 'error' ? 2000 : 0);
    }

    showNotification(message, type) {
        // Remove existing notification if any
        const existingNotification = document.querySelector('.ai-notification');
        if (existingNotification) {
            existingNotification.remove();
        }

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `ai-notification ai-notification-${type}`;
        notification.innerHTML = `
            <div class="ai-notification-content">
                <i class="fi fi-rs-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
                <button class="ai-notification-close">&times;</button>
            </div>
        `;

        // Add to body
        document.body.appendChild(notification);

        // Show notification
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);

        // Auto remove after 3 seconds
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);

        // Add close button functionality
        notification.querySelector('.ai-notification-close').addEventListener('click', () => {
            notification.classList.remove('show');
            setTimeout(() => {
                notification.remove();
            }, 300);
        });
    }
}

// Initialize different types on different pages
document.addEventListener('DOMContentLoaded', () => {
    // Home page
    if (document.querySelector('#ai-recommendations-home')) {
        new AIRecommendations('#ai-recommendations-home', 'general');
    }
    
    if (document.querySelector('#ai-recommendations-trending')) {
        new AIRecommendations('#ai-recommendations-trending', 'trending');
    }
    
    // Shop page
    if (document.querySelector('#ai-recommendations-shop')) {
        new AIRecommendations('#ai-recommendations-shop', 'trending');
    }
    
    // Product detail page
    if (document.querySelector('#ai-recommendations-related')) {
        new AIRecommendations('#ai-recommendations-related', 'related');
    }
    
    // Cart page
    if (document.querySelector('#ai-recommendations-cart')) {
        new AIRecommendations('#ai-recommendations-cart', 'cart');
    }
});