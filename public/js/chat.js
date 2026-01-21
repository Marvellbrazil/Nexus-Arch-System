class TicketChat {
    constructor(ticketId, userId, userRole) {
        this.ticketId = ticketId;
        this.userId = userId;
        this.userRole = userRole;
        this.lastMessageId = 0;
        this.pollingInterval = null;
        this.baseUrl = userRole === 'Customer' ? '/customer' : '/support';
        
        console.log('Chat initialized:', { 
            ticketId: this.ticketId, 
            userId: this.userId, 
            userRole: this.userRole, 
            baseUrl: this.baseUrl 
        });
        
        this.init();
    }
    
    init() {
        console.log('Initializing chat...');
        
        // Get initial last message ID
        const lastMessage = document.querySelector('.message-item:last-child');
        if (lastMessage) {
            this.lastMessageId = parseInt(lastMessage.dataset.messageId) || 0;
            console.log('Last message ID from DOM:', this.lastMessageId);
        }
        
        this.setupEventListeners();
        this.startPolling();
        this.scrollToBottom();
    }
    
    setupEventListeners() {
        console.log('Setting up event listeners...');
        
        const sendButton = document.getElementById('sendReplyBtn');
        const messageInput = document.getElementById('messageInput');
        const cancelButton = document.getElementById('cancelBtn');
        
        console.log('Elements found:', {
            sendButton: !!sendButton,
            messageInput: !!messageInput,
            cancelButton: !!cancelButton
        });
        
        if (sendButton && messageInput) {
            sendButton.addEventListener('click', () => this.handleSendMessage());
            
            messageInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    this.handleSendMessage();
                }
            });
            
            console.log('Event listeners attached to send button');
        }
        
        if (cancelButton) {
            cancelButton.addEventListener('click', () => {
                if (messageInput) {
                    messageInput.value = '';
                    messageInput.style.height = 'auto';
                }
            });
        }
    }
    
    async handleSendMessage() {
        console.log('handleSendMessage called');
        
        const messageInput = document.getElementById('messageInput');
        const sendButton = document.getElementById('sendReplyBtn');
        
        if (!messageInput || !sendButton) {
            console.error('Message input or send button not found');
            return;
        }
        
        const message = messageInput.value.trim();
        console.log('Message to send:', message);
        
        if (!message) {
            alert('Please write a message before sending');
            return;
        }
        
        // Show loading state
        const originalText = sendButton.innerHTML;
        sendButton.disabled = true;
        sendButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
        
        try {
            console.log('Sending message to server...');
            const result = await this.sendMessage(message);
            console.log('Server response:', result);
            
            if (result.success) {
                messageInput.value = '';
                messageInput.style.height = 'auto';
                console.log('Message sent successfully');
                
                // Immediately poll for new messages
                setTimeout(() => this.pollNewMessages(), 500);
            } else {
                alert('Failed to send message: ' + result.message);
            }
        } catch (error) {
            console.error('Error sending message:', error);
            alert('Network error. Please try again.');
        } finally {
            // Reset button
            sendButton.disabled = false;
            sendButton.innerHTML = originalText;
        }
    }
    
    async sendMessage(message) {
        const formData = new FormData();
        formData.append('ticket_id', this.ticketId);
        formData.append('message', message);
        
        console.log('Sending to:', `${this.baseUrl}/chat/send`);
        console.log('Form data:', {
            ticket_id: this.ticketId,
            message: message
        });
        
        try {
            const response = await fetch(`${this.baseUrl}/chat/send`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            console.log('Response status:', response.status);
            const result = await response.json();
            console.log('Response data:', result);
            
            return result;
        } catch (error) {
            console.error('Fetch error:', error);
            throw error;
        }
    }
    
// Method pollNewMessages yang sudah ada, PERBAIKI dengan kode berikut:
async pollNewMessages() {
    try {
        console.log('Polling for new messages, lastMessageId:', this.lastMessageId);
        
        const response = await fetch(
            `${this.baseUrl}/chat/get-new?ticket_id=${this.ticketId}&last_message_id=${this.lastMessageId}`,
            {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }
        );
        
        console.log('Poll response status:', response.status);
        const result = await response.json();
        console.log('Poll result:', result);
        
        if (result.success && result.messages && result.messages.length > 0) {
            console.log('New messages found:', result.messages.length);
            this.addMessagesToUI(result.messages);
            this.lastMessageId = result.last_message_id;
            this.updateMessageCount();
            
            // PERBAIKAN: Logika notifikasi suara berdasarkan role
            if (this.userRole === 'Support' || this.userRole === 'Admin') {
                // Untuk Support/Admin: mainkan suara hanya untuk pesan dari Customer
                const newMessagesFromCustomer = result.messages.filter(msg => 
                    msg.sender_role === 'Customer' || 
                    msg.sender_role === 'customer' ||
                    !msg.is_current_user
                );
                
                if (newMessagesFromCustomer.length > 0) {
                    this.playNotificationSound();
                }
            } else {
                // Untuk Customer: mainkan suara untuk pesan dari selain diri sendiri
                const newMessagesFromOthers = result.messages.filter(msg => !msg.is_current_user);
                if (newMessagesFromOthers.length > 0) {
                    this.playNotificationSound();
                }
            }
            
            this.scrollToBottom();
        } else {
            console.log('No new messages');
        }
    } catch (error) {
        console.error('Error polling messages:', error);
    }
}
    startPolling() {
        console.log('Starting polling interval (3 seconds)');
        // Poll every 3 seconds
        this.pollingInterval = setInterval(() => {
            this.pollNewMessages();
        }, 3000);
    }
    
    stopPolling() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
            this.pollingInterval = null;
            console.log('Polling stopped');
        }
    }
    
    addMessagesToUI(messages) {
        const container = document.getElementById('conversationContainer');
        if (!container) {
            console.error('Conversation container not found!');
            return;
        }
        
        console.log('Adding', messages.length, 'messages to UI');
        
        let messagesWrapper = container.querySelector('.space-y-6');
        if (!messagesWrapper) {
            messagesWrapper = document.createElement('div');
            messagesWrapper.className = 'space-y-6';
            container.appendChild(messagesWrapper);
            console.log('Created new messages wrapper');
        }
        
        messages.forEach(message => {
            const existingMessage = container.querySelector(`[data-message-id="${message.message_id}"]`);
            if (existingMessage) {
                console.log('Message already exists:', message.message_id);
                return;
            }
            
            const messageElement = this.createMessageElement(message);
            messagesWrapper.appendChild(messageElement);
            console.log('Added message:', message.message_id);
        });
    }
    
    createMessageElement(message) {
        const isCurrentUser = message.is_current_user || message.sender_id == this.userId;
        const timeAgo = this.formatTimeAgo(message.created_at);
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex gap-4 message-item`;
        messageDiv.setAttribute('data-message-id', message.message_id);
        
        messageDiv.innerHTML = `
            <div class="flex-shrink-0 relative">
                <div class="w-10 h-10 ${isCurrentUser ? 'bg-green-100' : 'bg-blue-100'} rounded-full flex items-center justify-center">
                    <i class="fas ${isCurrentUser ? 'fa-headset text-green-600' : 'fa-user text-blue-600'}"></i>
                </div>
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <div>
                        <span class="text-gray-800 font-semibold">${message.sender_name || 'Unknown'}</span>
                        <span class="ml-2 px-2 py-0.5 ${isCurrentUser ? 'bg-green-50 text-green-700' : 'bg-blue-50 text-blue-700'} text-xs rounded">
                            ${message.sender_role || 'User'}
                        </span>
                    </div>
                    <div class="text-gray-500 text-sm ml-auto">
                        <i class="far fa-clock mr-1"></i>
                        ${timeAgo}
                    </div>
                </div>
                <div class="${isCurrentUser ? 'bg-green-50 border-green-100' : 'bg-gray-50 border-gray-200'} rounded-xl p-4 border">
                    <p class="text-gray-700">${this.escapeHtml(message.message)}</p>
                </div>
            </div>
        `;
        
        return messageDiv;
    }
    
    updateMessageCount() {
        const countElement = document.getElementById('messageCount');
        if (countElement) {
            const messages = document.querySelectorAll('.message-item');
            countElement.textContent = messages.length;
            console.log('Updated message count:', messages.length);
        }
    }
    
    scrollToBottom() {
        const container = document.getElementById('conversationContainer');
        if (container) {
            setTimeout(() => {
                container.scrollTop = container.scrollHeight;
                console.log('Scrolled to bottom');
            }, 100);
        }
    }
    
    playNotificationSound() {
        try {
            console.log('Playing notification sound');
            const audio = new Audio('/notification.mp3');
            audio.volume = 0.3;
            audio.play().catch(e => console.log('Audio play failed:', e));
        } catch (e) {
            console.log('Could not play sound');
        }
    }
    
    formatTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffMs = now - date;
        const diffSec = Math.floor(diffMs / 1000);
        const diffMin = Math.floor(diffSec / 60);
        const diffHour = Math.floor(diffMin / 60);
        const diffDay = Math.floor(diffHour / 24);
        
        if (diffSec < 60) return 'Just now';
        if (diffMin < 60) return `${diffMin}m ago`;
        if (diffHour < 24) return `${diffHour}h ago`;
        if (diffDay < 7) return `${diffDay}d ago`;
        
        return date.toLocaleDateString();
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Initialize chat when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Looking for chat container');
    
    // Cari SEMUA element dengan data-ticket-id
    const containers = document.querySelectorAll('[data-ticket-id]');
    console.log('Found containers with data-ticket-id:', containers.length);
    
    // Ambil container pertama
    const container = containers[0];
    
    if (!container) {
        console.error('ERROR: No container with data-ticket-id found!');
        return;
    }
    
    let ticketId = container.dataset.ticketId;
    let userId = container.dataset.userId;
    let userRole = container.dataset.userRole;
    
    console.log('Initial data from container:', { 
        ticketId, 
        userId, 
        userRole
    });
    
    // Jika userRole kosong, set default berdasarkan URL atau logic lain
    if (!userRole || userRole.trim() === '') {
        console.log('userRole is empty, determining from URL...');
        
        // Coba tentukan role dari URL
        const path = window.location.pathname;
        if (path.includes('/customer/')) {
            userRole = 'Customer';
        } else if (path.includes('/support/')) {
            userRole = 'Support';
        } else if (path.includes('/admin/')) {
            userRole = 'Admin';
        } else {
            userRole = 'Customer'; // Default
        }
        
        console.log('Determined userRole from URL:', userRole);
        
        // Update dataset
        container.dataset.userRole = userRole;
    }
    
    // Validasi data
    if (!ticketId || ticketId.trim() === '') {
        console.error('ERROR: ticketId is empty or invalid!');
        alert('Ticket ID tidak valid. Silakan refresh halaman.');
        return;
    }
    
    if (!userId || userId.trim() === '') {
        console.error('ERROR: userId is empty!');
        alert('User ID tidak ditemukan. Silakan login kembali.');
        return;
    }
    
    if (!userRole || userRole.trim() === '') {
        console.error('ERROR: userRole is empty after all attempts!');
        userRole = 'Customer'; // Force default
    }
    
    console.log('Final chat initialization data:', { 
        ticketId, 
        userId, 
        userRole 
    });
    
    console.log('All data present, initializing chat...');
    window.ticketChat = new TicketChat(ticketId, userId, userRole);
});