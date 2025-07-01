/**
 * FAQ Chatbot Frontend JavaScript
 *
 * This file contains all the client-side functionality for the FAQ chatbot.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const chatbotContainer = document.querySelector('.faq-chatbot-container');
    if (!chatbotContainer) return;

    const chatMessages = chatbotContainer.querySelector('.faq-chatbot-messages');
    const chatForm = chatbotContainer.querySelector('.faq-chatbot-form');
    const chatInput = chatbotContainer.querySelector('.faq-chatbot-input');
    const chatToggle = document.querySelector('.faq-chatbot-toggle');
    const chatCloseBtn = chatbotContainer.querySelector('.faq-chatbot-close');
    
    // State variables
    let isOpen = false;
    let conversationHistory = [];
    
    /**
     * Initialize the chatbot
     */
    function init() {
        // Add event listeners
        chatForm.addEventListener('submit', handleSubmit);
        chatToggle.addEventListener('click', toggleChatbot);
        chatCloseBtn.addEventListener('click', closeChatbot);
        
        // Show welcome message
        addBotMessage(faqChatbotData.welcomeMessage || 'Hello! How can I help you today?');
    }
    
    /**
     * Handle form submission
     * @param {Event} e - Form submit event
     */
    function handleSubmit(e) {
        e.preventDefault();
        
        const userQuestion = chatInput.value.trim();
        if (!userQuestion) return;
        
        // Add user message to chat
        addUserMessage(userQuestion);
        
        // Clear input
        chatInput.value = '';
        
        // Get response from server
        getResponse(userQuestion);
    }
    
    /**
     * Send question to server and get response
     * @param {string} question - User's question
     */
    function getResponse(question) {
        // Show typing indicator
        showTypingIndicator();
        
        // Create AJAX request
        const data = new FormData();
        data.append('action', 'faq_chatbot_get_answer');
        data.append('question', question);
        data.append('nonce', faqChatbotData.nonce);
        
        // Send request
        fetch(faqChatbotData.ajaxUrl, {
            method: 'POST',
            credentials: 'same-origin',
            body: data
        })
        .then(response => response.json())
        .then(data => {
            // Remove typing indicator
            hideTypingIndicator();
            
            if (data.success) {
                // Add bot response to chat
                addBotMessage(data.data.answer);
                
                // Store in conversation history
                conversationHistory.push({
                    question: question,
                    answer: data.data.answer,
                    timestamp: new Date().toISOString()
                });
            } else {
                // Show error message
                addBotMessage('Sorry, an error occurred. Please try again.');
                console.error('Error:', data.data);
            }
        })
        .catch(error => {
            // Remove typing indicator
            hideTypingIndicator();
            
            // Show error message
            addBotMessage('Sorry, an error occurred. Please try again.');
            console.error('Error:', error);
        });
    }
    
    /**
     * Add user message to chat interface
     * @param {string} message - User's message
     */
    function addUserMessage(message) {
        const messageElement = document.createElement('div');
        messageElement.className = 'faq-chatbot-message faq-chatbot-user-message';
        messageElement.textContent = message;
        chatMessages.appendChild(messageElement);
        scrollToBottom();
    }
    
    /**
     * Add bot message to chat interface
     * @param {string} message - Bot's message
     */
    function addBotMessage(message) {
        const messageElement = document.createElement('div');
        messageElement.className = 'faq-chatbot-message faq-chatbot-bot-message';
        messageElement.innerHTML = message; // Using innerHTML to allow links in responses
        chatMessages.appendChild(messageElement);
        scrollToBottom();
    }
    
    /**
     * Show bot typing indicator
     */
    function showTypingIndicator() {
        const indicator = document.createElement('div');
        indicator.className = 'faq-chatbot-message faq-chatbot-bot-message faq-chatbot-typing';
        indicator.innerHTML = '<span>.</span><span>.</span><span>.</span>';
        indicator.id = 'faq-chatbot-typing';
        chatMessages.appendChild(indicator);
        scrollToBottom();
    }
    
    /**
     * Hide bot typing indicator
     */
    function hideTypingIndicator() {
        const indicator = document.getElementById('faq-chatbot-typing');
        if (indicator) {
            indicator.remove();
        }
    }
    
    /**
     * Scroll chat window to bottom
     */
    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    /**
     * Toggle chatbot visibility
     */
    function toggleChatbot() {
        if (isOpen) {
            closeChatbot();
        } else {
            openChatbot();
        }
    }
    
    /**
     * Open chatbot interface
     */
    function openChatbot() {
        chatbotContainer.classList.add('faq-chatbot-active');
        isOpen = true;
        chatInput.focus();
    }
    
    /**
     * Close chatbot interface
     */
    function closeChatbot() {
        chatbotContainer.classList.remove('faq-chatbot-active');
        isOpen = false;
    }
    
    // Initialize the chatbot
    init();
});