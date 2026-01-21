class DepartmentChat {
    constructor(ticketId, userId, userRole, departmentId) {
        this.ticketId = ticketId;
        this.userId = userId;
        this.userRole = userRole; // 'Support' atau 'Department'
        this.departmentId = departmentId;
        this.lastMessageId = 0;
        this.pollingInterval = null;
        this.baseUrl = userRole === 'Department' ? '/department' : '/support';
        this.participants = [];
        this.typingTimeout = null;
        this.isTyping = false;
        
        console.log('Department Chat initialized:', { 
            ticketId: this.ticketId, 
            userId: this.userId, 
            userRole: this.userRole,
            departmentId: this.departmentId,
            baseUrl: this.baseUrl 
        });
        
        this.init();
    }
    
    init() {
        console.log('Initializing department chat...');
        
        // Get initial last message ID
        const lastMessage = document.querySelector('.department-message:last-child');
        if (lastMessage) {
            this.lastMessageId = parseInt(lastMessage.dataset.messageId) || 0;
            console.log('Last department message ID from DOM:', this.lastMessageId);
        }
        
        this.setupEventListeners();
        this.startPolling();
        this.scrollToBottom();
        this.loadParticipants();
        
        // Load initial messages
        this.loadDepartmentMessages();
    }
    
    setupEventListeners() {
        console.log('Setting up department chat event listeners...');
        
        const sendButton = document.getElementById('sendDepartmentBtn');
        const messageInput = document.getElementById('departmentMessageInput');
        const internalCheckbox = document.getElementById('departmentInternalCheckbox');
        const attachButton = document.getElementById('departmentAttachBtn');
        
        console.log('Department chat elements found:', {
            sendButton: !!sendButton,
            messageInput: !!messageInput,
            internalCheckbox: !!internalCheckbox,
            attachButton: !!attachButton
        });
        
        if (sendButton && messageInput) {
            // Remove existing listeners
            sendButton.replaceWith(sendButton.cloneNode(true));
            const newSendButton = document.getElementById('sendDepartmentBtn');
            
            newSendButton.addEventListener('click', () => this.handleSendMessage());
            
            messageInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    this.handleSendMessage();
                }
            });
            
            messageInput.addEventListener('input', () => {
                this.handleTyping();
            });
            
            console.log('Event listeners attached to department chat');
        }
        
        if (internalCheckbox) {
            internalCheckbox.addEventListener('change', (e) => {
                this.updateInternalBadge(e.target.checked);
            });
        }
        
        // Setup for typing indicator
        this.setupTypingIndicator();
    }
    
    async handleSendMessage() {
        console.log('handleSendMessage called for department chat');
        
        const messageInput = document.getElementById('departmentMessageInput');
        const sendButton = document.getElementById('sendDepartmentBtn');
        const internalCheckbox = document.getElementById('departmentInternalCheckbox');
        
        if (!messageInput || !sendButton) {
            console.error('Department message input or send button not found');
            return;
        }
        
        const message = messageInput.value.trim();
        const isInternal = internalCheckbox ? internalCheckbox.checked : false;
        
        console.log('Message to send:', {
            message: message,
            isInternal: isInternal,
            length: message.length
        });
        
        if (!message) {
            alert('Please write a message before sending');
            return;
        }
        
        // Show loading state
        const originalText = sendButton.innerHTML;
        sendButton.disabled = true;
        sendButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
        
        try {
            console.log('Sending department message to server...');
            const result = await this.sendDepartmentMessage(message, isInternal);
            console.log('Server response:', result);
            
            if (result.success) {
                messageInput.value = '';
                messageInput.style.height = 'auto';
                
                if (internalCheckbox) {
                    internalCheckbox.checked = false;
                    this.updateInternalBadge(false);
                }
                
                console.log('Department message sent successfully');
                
                // Immediately poll for new messages
                setTimeout(() => this.pollNewMessages(), 500);
                
                // Send typing stop
                this.sendTypingStop();
            } else {
                alert('Failed to send message: ' + result.message);
            }
        } catch (error) {
            console.error('Error sending department message:', error);
            alert('Network error. Please try again.');
        } finally {
            // Reset button
            sendButton.disabled = false;
            sendButton.innerHTML = originalText;
        }
    }
    
    async sendDepartmentMessage(message, isInternal = false) {
        const formData = new FormData();
        formData.append('ticket_id', this.ticketId);
        formData.append('department_id', this.departmentId);
        formData.append('message', message);
        if (isInternal) {
            formData.append('is_internal', 'true');
        }
        
        console.log('Sending to:', `${this.baseUrl}/department_chat/send`);
        console.log('Form data:', {
            ticket_id: this.ticketId,
            department_id: this.departmentId,
            message: message,
            is_internal: isInternal
        });
        
        try {
            const response = await fetch(`${this.baseUrl}/department_chat/send`, {
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
    
    async loadDepartmentMessages() {
        try {
            console.log('Loading department messages...');
            
            const response = await fetch(
                `${this.baseUrl}/department_chat/messages?ticket_id=${this.ticketId}&department_id=${this.departmentId}&last_message_id=${this.lastMessageId}`,
                {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }
            );
            
            console.log('Load response status:', response.status);
            const result = await response.json();
            console.log('Load result:', result);
            
            if (result.success && result.messages && result.messages.length > 0) {
                console.log('Department messages loaded:', result.messages.length);
                this.addMessagesToUI(result.messages);
                this.lastMessageId = result.last_message_id;
                this.updateMessageCount();
                
                // Play sound for new messages from others
                const newMessagesFromOthers = result.messages.filter(msg => !msg.is_current_user);
                if (newMessagesFromOthers.length > 0) {
                    this.playNotificationSound();
                }
                
                this.scrollToBottom();
            }
        } catch (error) {
            console.error('Error loading department messages:', error);
        }
    }
    
    async pollNewMessages() {
        try {
            console.log('Polling for new department messages, lastMessageId:', this.lastMessageId);
            
            const response = await fetch(
                `${this.baseUrl}/department_chat/messages?ticket_id=${this.ticketId}&department_id=${this.departmentId}&last_message_id=${this.lastMessageId}`,
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
                console.log('New department messages found:', result.messages.length);
                this.addMessagesToUI(result.messages);
                this.lastMessageId = result.last_message_id;
                this.updateMessageCount();
                
                // Play sound for new messages from others
                const newMessagesFromOthers = result.messages.filter(msg => !msg.is_current_user);
                if (newMessagesFromOthers.length > 0) {
                    this.playNotificationSound();
                }
                
                this.scrollToBottom();
            }
        } catch (error) {
            console.error('Error polling department messages:', error);
        }
    }
    
    startPolling() {
        console.log('Starting department polling interval (3 seconds)');
        this.pollingInterval = setInterval(() => {
            this.pollNewMessages();
        }, 3000);
    }
    
    stopPolling() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
            this.pollingInterval = null;
            console.log('Department polling stopped');
        }
    }
    
    addMessagesToUI(messages) {
        const container = document.getElementById('departmentChatMessages');
        if (!container) {
            console.error('Department chat container not found!');
            return;
        }
        
        console.log('Adding', messages.length, 'department messages to UI');
        
        let messagesWrapper = container.querySelector('.space-y-4');
        if (!messagesWrapper) {
            messagesWrapper = document.createElement('div');
            messagesWrapper.className = 'space-y-4';
            container.appendChild(messagesWrapper);
            console.log('Created new department messages wrapper');
        }
        
        messages.forEach(message => {
            const existingMessage = container.querySelector(`[data-message-id="${message.message_id}"]`);
            if (existingMessage) {
                console.log('Department message already exists:', message.message_id);
                return;
            }
            
            const messageElement = this.createMessageElement(message);
            messagesWrapper.appendChild(messageElement);
            console.log('Added department message:', message.message_id);
        });
        
        this.updateParticipantsList();
    }
    
    createMessageElement(message) {
        const isCurrentUser = message.is_current_user;
        const isInternal = message.is_internal;
        const isSupport = message.sender_role === 'Support' || message.sender_role === 'Admin';
        const isDepartment = message.sender_role === 'Department';
        
        let roleBadge = '';
        if (isSupport) {
            roleBadge = '<span class="ml-2 px-2 py-0.5 bg-blue-50 text-blue-700 text-xs rounded">Support</span>';
        } else if (isDepartment) {
            roleBadge = '<span class="ml-2 px-2 py-0.5 bg-purple-50 text-purple-700 text-xs rounded">' + (message.sender_department || 'Department') + '</span>';
        }
        
        let internalBadge = '';
        if (isInternal) {
            internalBadge = '<span class="ml-2 px-2 py-0.5 bg-yellow-50 text-yellow-700 text-xs rounded">Internal</span>';
        }
        
        const timeAgo = message.time_ago || this.formatTimeAgo(message.created_at);
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex gap-3 department-message ${isCurrentUser ? 'justify-end' : ''}`;
        messageDiv.setAttribute('data-message-id', message.message_id);
        messageDiv.setAttribute('data-sender-id', message.sender_id);
        messageDiv.setAttribute('data-is-internal', isInternal);
        
        if (isInternal) {
            messageDiv.classList.add('opacity-90');
        }
        
        if (isCurrentUser) {
            messageDiv.innerHTML = `
                <div class="flex flex-col max-w-[80%]">
                    <div class="mb-1 text-right">
                        <span class="text-gray-700 font-semibold text-sm">${message.sender_name || 'You'}</span>
                        ${roleBadge}
                        ${internalBadge}
                        <span class="ml-2 text-gray-500 text-xs">${timeAgo}</span>
                    </div>
                    <div class="${isInternal ? 'bg-yellow-50 border-yellow-200' : 'bg-blue-50 border-blue-200'} rounded-xl p-3 border">
                        <p class="text-gray-800">${this.escapeHtml(message.message)}</p>
                    </div>
                </div>
            `;
        } else {
            messageDiv.innerHTML = `
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 ${isDepartment ? 'bg-purple-100' : 'bg-blue-100'} rounded-full flex items-center justify-center">
                        <i class="fas ${isDepartment ? 'fa-building text-purple-600' : 'fa-headset text-blue-600'} text-xs"></i>
                    </div>
                </div>
                <div class="flex-1 max-w-[80%]">
                    <div class="mb-1">
                        <span class="text-gray-700 font-semibold text-sm">${message.sender_name || 'Unknown'}</span>
                        ${roleBadge}
                        ${internalBadge}
                        <span class="ml-2 text-gray-500 text-xs">${timeAgo}</span>
                    </div>
                    <div class="${isInternal ? 'bg-gray-50 border-gray-200' : 'bg-white border-gray-300'} rounded-xl p-3 border">
                        <p class="text-gray-800">${this.escapeHtml(message.message)}</p>
                    </div>
                </div>
            `;
        }
        
        return messageDiv;
    }
    
    async loadParticipants() {
        try {
            const response = await fetch(
                `${this.baseUrl}/department_chat/participants?ticket_id=${this.ticketId}&department_id=${this.departmentId}`,
                {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }
            );
            
            const result = await response.json();
            if (result.success && result.participants) {
                this.participants = result.participants;
                this.updateParticipantsList();
            }
        } catch (error) {
            console.error('Error loading participants:', error);
        }
    }
    
    updateParticipantsList() {
        const container = document.getElementById('departmentParticipants');
        if (!container) return;
        
        // Group by department
        const byDepartment = {};
        this.participants.forEach(participant => {
            const dept = participant.department_name || 'Other';
            if (!byDepartment[dept]) {
                byDepartment[dept] = [];
            }
            byDepartment[dept].push(participant);
        });
        
        let html = '';
        for (const [deptName, members] of Object.entries(byDepartment)) {
            html += `<div class="mb-4">
                <h4 class="font-semibold text-gray-700 text-sm mb-2">${deptName}</h4>
                <div class="space-y-2">`;
            
            members.forEach(member => {
                const isOnline = this.checkIfOnline(member.user_id);
                html += `
                <div class="flex items-center gap-2">
                    <div class="relative">
                        <div class="w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center text-gray-700 text-xs">
                            ${this.getInitials(member.full_name)}
                        </div>
                        ${isOnline ? '<div class="absolute -bottom-0.5 -right-0.5 w-2 h-2 bg-green-500 rounded-full border border-white"></div>' : ''}
                    </div>
                    <div class="flex-1">
                        <div class="text-gray-800 text-xs font-medium truncate">${member.full_name}</div>
                        <div class="text-gray-500 text-xs">${member.role_name}</div>
                    </div>
                </div>`;
            });
            
            html += `</div></div>`;
        }
        
        container.innerHTML = html;
    }
    
    updateMessageCount() {
        const countElement = document.getElementById('departmentMessageCount');
        if (countElement) {
            const messages = document.querySelectorAll('.department-message');
            countElement.textContent = messages.length;
        }
    }
    
    scrollToBottom() {
        const container = document.getElementById('departmentChatMessages');
        if (container) {
            setTimeout(() => {
                container.scrollTop = container.scrollHeight;
            }, 100);
        }
    }
    
    setupTypingIndicator() {
        const messageInput = document.getElementById('departmentMessageInput');
        if (!messageInput) return;
        
        messageInput.addEventListener('focus', () => {
            this.sendTypingStart();
        });
        
        messageInput.addEventListener('blur', () => {
            this.sendTypingStop();
        });
    }
    
    handleTyping() {
        if (!this.isTyping) {
            this.isTyping = true;
            this.sendTypingStart();
        }
        
        clearTimeout(this.typingTimeout);
        this.typingTimeout = setTimeout(() => {
            this.isTyping = false;
            this.sendTypingStop();
        }, 3000);
    }
    
    sendTypingStart() {
        // Implement WebSocket or AJAX for typing indicators
        console.log('User started typing in department chat');
    }
    
    sendTypingStop() {
        console.log('User stopped typing in department chat');
    }
    
    updateInternalBadge(isInternal) {
        const badge = document.getElementById('internalBadge');
        if (badge) {
            if (isInternal) {
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }
    }
    
    playNotificationSound() {
        try {
            const audio = new Audio('/notification.mp3');
            audio.volume = 0.3;
            audio.play().catch(e => console.log('Audio play failed:', e));
        } catch (e) {
            console.log('Could not play sound');
        }
    }
    
    getInitials(name) {
        const parts = name.split(' ');
        if (parts.length >= 2) {
            return (parts[0][0] + parts[1][0]).toUpperCase();
        }
        return name.substring(0, 2).toUpperCase();
    }
    
    checkIfOnline(userId) {
        // Implement online status check
        return Math.random() > 0.5; // Demo only
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
    
    destroy() {
        this.stopPolling();
        console.log('Department chat destroyed');
    }
}

// Initialize department chat when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Looking for department chat container');
    
    const containers = document.querySelectorAll('[data-ticket-id][data-department-id]');
    console.log('Found department chat containers:', containers.length);
    
    const container = containers[0];
    
    if (!container) {
        console.log('No department chat container found, skipping initialization');
        return;
    }
    
    let ticketId = container.dataset.ticketId;
    let userId = container.dataset.userId;
    let userRole = container.dataset.userRole;
    let departmentId = container.dataset.departmentId;
    
    console.log('Department chat initialization data:', { 
        ticketId, 
        userId, 
        userRole,
        departmentId
    });
    
    // Validate data
    if (!ticketId || !userId || !departmentId) {
        console.error('Missing required data for department chat');
        return;
    }
    
    if (!userRole || userRole.trim() === '') {
        const path = window.location.pathname;
        if (path.includes('/support/')) {
            userRole = 'Support';
        } else if (path.includes('/department/')) {
            userRole = 'Department';
        } else {
            userRole = 'Support'; // Default
        }
        
        container.dataset.userRole = userRole;
    }
    
    console.log('Initializing department chat...');
    window.departmentChat = new DepartmentChat(ticketId, userId, userRole, departmentId);
});