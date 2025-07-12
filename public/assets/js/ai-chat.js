// filepath: c:\wamp64\www\wahab-e-comerce\public\assets\js\ai-chatbot.js
class AIChatbot {
    constructor() {
        this.apiUrl = '/api/ai-chat';
        this.init();
    }

    init() {
        this.createChatInterface();
        this.setupEventListeners();
    }

    createChatInterface() {
        // Create chat widget if it doesn't exist
        if (!document.querySelector('#ai-chat-widget')) {
            const chatWidget = document.createElement('div');
            chatWidget.id = 'ai-chat-widget';
            chatWidget.innerHTML = `
                <div class="chat-toggle-btn" id="chat-toggle">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="chat-container" id="chat-container">
                    <div class="chat-header">
                        <h4><i class="fas fa-robot"></i> AI Assistant</h4>
                        <button class="chat-close-btn" id="chat-close">×</button>
                    </div>
                    <div class="chat-messages" id="chat-messages">
                        <div class="ai-message">
                            <div class="message-content">
                                <p>Hello! I'm your AI shopping assistant. How can I help you today?</p>
                            </div>
                        </div>
                    </div>
                    <div class="chat-input-container">
                        <form id="chat-form">
                            <input type="text" id="chat-input" placeholder="Ask me anything..." required>
                            <button type="submit" class="chat-send-btn">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>
            `;
            document.body.appendChild(chatWidget);
        }
    }

    setupEventListeners() {
        const chatToggle = document.getElementById('chat-toggle');
        const chatClose = document.getElementById('chat-close');
        const chatContainer = document.getElementById('chat-container');
        const chatForm = document.getElementById('chat-form');

        chatToggle.addEventListener('click', () => {
            chatContainer.classList.toggle('show');
        });

        chatClose.addEventListener('click', () => {
            chatContainer.classList.remove('show');
        });

        chatForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleUserMessage();
        });
    }

    async handleUserMessage() {
        const messageInput = document.getElementById('chat-input');
        const userMessage = messageInput.value.trim();
        
        if (!userMessage) return;

        // Display user message
        this.addMessageToChat(userMessage, 'user');
        messageInput.value = '';

        // Show typing indicator
        this.showTypingIndicator();

        try {
            const response = await fetch(this.apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    message: userMessage,
                    context: this.getChatContext()
                })
            });

            const data = await response.json();
            
            this.hideTypingIndicator();
            this.addMessageToChat(data.response, 'ai');
            
        } catch (error) {
            this.hideTypingIndicator();
            this.addMessageToChat('Sorry, I encountered an error. Please try again.', 'ai');
        }
    }

    addMessageToChat(message, sender) {
        const chatMessages = document.getElementById('chat-messages');
        const messageElement = document.createElement('div');
        messageElement.className = `${sender}-message`;
        messageElement.innerHTML = `
            <div class="message-content">
                <p>${message}</p>
                <span class="message-time">${new Date().toLocaleTimeString()}</span>
            </div>
        `;
        chatMessages.appendChild(messageElement);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    showTypingIndicator() {
        const chatMessages = document.getElementById('chat-messages');
        const typingIndicator = document.createElement('div');
        typingIndicator.className = 'typing-indicator';
        typingIndicator.innerHTML = `
            <div class="typing-dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        `;
        chatMessages.appendChild(typingIndicator);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    hideTypingIndicator() {
        const typingIndicator = document.querySelector('.typing-indicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }

    getChatContext() {
        const messages = document.querySelectorAll('#chat-messages .user-message, #chat-messages .ai-message');
        return Array.from(messages).slice(-5).map(msg => ({
            content: msg.querySelector('p').textContent,
            sender: msg.classList.contains('user-message') ? 'user' : 'ai'
        }));
    }
}

// Initialize AI chatbot
document.addEventListener('DOMContentLoaded', () => {
    new AIChatbot();
});