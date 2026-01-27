<?= $this->extend('layouts/customer_layout') ?>

<?= $this->section('title') ?>Ticket #<?= $data['ticket']['ticket_number'] ?? '12345' ?> Detail - NEXUS<?= $this->endSection() ?>

<?= $this->section('head') ?>
<!-- Tambahkan script untuk WebSocket -->
<script src="https://cdn.socket.io/4.5.0/socket.io.min.js"></script>
<?= $this->endSection() ?>

<?= $this->section('background_effects') ?>
<!-- Background Effects -->
<div
    class="fixed w-[40vw] h-[40vw] -right-[10%] -bottom-[10%] rotate-[149deg] bg-gradient-to-r from-[rgba(56.96,44.47,127.72,0.15)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-80 z-0">
</div>
<div
    class="fixed w-[35vw] h-[25vw] -left-[5%] top-[10%] rotate-[8deg] bg-gradient-to-r from-[rgba(65.04,45.10,137.14,0.20)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-70 z-0">
</div>
<div
    class="fixed w-[15vw] h-[20vw] right-[5%] -top-[5%] rotate-[8deg] bg-gradient-to-r from-[rgba(16.56,8.41,46.05,0.12)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-60 z-0">
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mt-[77px] p-[30px] relative z-10"
     data-ticket-id="<?= $data['ticket']['ticket_id'] ?? '' ?>"
     data-user-id="<?= session()->get('user_id') ?>"
     data-user-role="<?= $data['user_role'] ?? session()->get('role') ?? 'Customer' ?>">
    <!-- WebSocket Status Indicator -->
    <div id="wsStatus" class="fixed top-4 right-4 z-50 hidden">
        <div class="px-3 py-1 rounded-full text-xs font-medium bg-gray-800 text-white shadow-md flex items-center gap-2">
            <span class="status-dot w-2 h-2 rounded-full"></span>
            <span class="status-text">Connecting...</span>
        </div>
    </div>

    <!-- Notification Toast Container -->
    <div id="notificationContainer" class="fixed top-20 right-4 z-50 space-y-2"></div>

    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-[32px] font-semibold mb-2 text-text-dark" id="ticketNumber"><?= $data['ticket']['ticket_number'] ?? 'N/A' ?></h1>
                <p class="text-[15px] text-[#000]" id="ticketSubject"><?= esc($data['ticket']['subject'] ?? 'No Subject') ?></p>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3">
                <a href="<?= base_url('customer/my_tickets') ?>"
                    class="px-4 py-2 bg-white text-secondary border border-secondary rounded-lg hover:bg-secondary/5 transition-all text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Back to Tickets
                </a>
                <button id="exportChatBtn"
                    class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-all text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-download"></i>
                    Export
                </button>
            </div>
        </div>
    </div>

    <!-- Ticket Status -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Ticket Info Card -->
        <div class="bg-gradient-to-r from-secondary to-[#8A84C6] rounded-2xl p-6 text-white">
            <h3 class="text-lg font-semibold mb-4">Ticket Information</h3>
            <div class="space-y-4">
                <div>
                    <div class="text-white/80 text-sm mb-1">Status</div>
                    <div class="flex items-center gap-3">
                        <div class="px-3 py-1 bg-white/20 rounded-full text-sm font-semibold" id="ticketStatus"><?= $data['ticket']['status_name'] ?? 'Open' ?></div>
                        <!-- Online Indicator for Support -->
                        <?php if ($data['ticket']['assigned_to']): ?>
                            <div class="flex items-center gap-1 text-sm text-white/80" id="assignedSupport">
                                <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                                <span>Support Online</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div>
                    <div class="text-white/80 text-sm mb-1">Priority</div>
                    <div class="px-3 py-1 bg-red-500/20 rounded-full text-sm font-semibold inline-block" id="ticketPriority"><?= $data['ticket']['priority_name'] ?? 'Medium' ?></div>
                </div>
                <div>
                    <div class="text-white/80 text-sm mb-1">Created</div>
                    <div class="text-lg font-semibold" id="ticketCreated"><?= date('F d, Y', strtotime($data['ticket']['created_at'] ?? 'now')) ?></div>
                </div>
            </div>
        </div>

        <!-- Progress Card -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Progress</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Completion</span>
                        <span id="progressPercent"></span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div id="progressBar" class="h-full bg-secondary rounded-full"></div>
                    </div>
                </div>
                <div class="text-sm text-gray-600">
                    <div class="flex items-center gap-2 mb-1">
                        <i class="far fa-clock text-gray-400"></i>
                        <span id="lastUpdate">Last update: <?= date('F d, Y H:i:s', strtotime($data['ticket']['updated_at'] ?? 'now')) ?></span>
                    </div>
                    <div class="text-secondary font-medium" id="progressStatus">Accepted by Support Team</div>
                </div>
            </div>
        </div>

        <!-- Category Card -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Category</h3>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-secondary/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-tools text-secondary text-xl"></i>
                </div>
                <div>
                    <div class="text-gray-800 font-semibold" id="ticketCategory"><?= $data['ticket']['category_name'] ?? 'Technical' ?></div>
                    <div class="text-gray-600 text-sm" id="ticketDepartment"><?= $data['ticket']['department_name'] ?? 'IT Support' ?></div>
                </div>
            </div>
            <p class="text-gray-600 text-sm" id="ticketDescription">
                <?= esc($data['ticket']['subject'] ?? 'No description') ?>
            </p>
        </div>
    </div>

    <!-- Conversation Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-8 overflow-hidden">
        <!-- Section Header -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">Conversation</h2>
                <div class="flex items-center gap-4">
                    <!-- Online Status -->
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <div class="w-2 h-2 bg-gray-400 rounded-full" id="supportOnlineStatus"></div>
                        <span id="onlineStatusText">Offline</span>
                    </div>
                    <!-- Message Count -->
                    <div class="text-gray-600 text-sm">
                        <i class="far fa-comments mr-1"></i>
                        <span id="messageCount"><?= count($data['messages'] ?? []) ?></span> messages
                    </div>
                </div>
            </div>
        </div>

        <!-- Conversation Container (Scrollable) -->
        <div id="conversationContainer" class="p-6 h-[500px] overflow-y-auto">
            <?php if (!empty($data['messages'])): ?>
                <div class="space-y-6" id="messagesContainer">
                    <?php 
                    $currentDate = null;
                    foreach ($data['messages'] as $message): 
                        $messageDate = date('F j, Y', strtotime($message['created_at']));
                    ?>
                        <?php if ($messageDate != $currentDate): ?>
                            <!-- Date Header -->
                            <div class="text-center date-header" data-date="<?= $messageDate ?>">
                                <span class="px-4 py-1 bg-gray-100 text-gray-600 text-sm rounded-full">
                                    <?= $messageDate ?>
                                </span>
                            </div>
                            <?php $currentDate = $messageDate; ?>
                        <?php endif; ?>
                        
                        <!-- Message -->
                        <div class="flex gap-4 message-item" 
                             data-message-id="<?= $message['message_id'] ?? '' ?>"
                             data-sender-id="<?= $message['sender_id'] ?? '' ?>"
                             data-sender-role="<?= $message['role_name'] ?? 'User' ?>">
                            <!-- Avatar -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 <?= $message['role_name'] === 'Customer' ? 'bg-blue-100' : 'bg-green-100' ?> rounded-full flex items-center justify-center relative">
                                    <?php if ($message['role_name'] === 'Customer'): ?>
                                        <i class="fas fa-user text-blue-600"></i>
                                    <?php else: ?>
                                        <i class="fas fa-headset text-green-600"></i>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Message Content -->
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div>
                                        <span class="text-gray-800 font-semibold"><?= esc($message['full_name'] ?? 'Unknown') ?></span>
                                        <span class="ml-2 px-2 py-0.5 <?= $message['role_name'] === 'Customer' ? 'bg-blue-50 text-blue-700' : 'bg-green-50 text-green-700' ?> text-xs rounded sender-role">
                                            <?= $message['role_name'] ?? 'User' ?>
                                        </span>
                                    </div>
                                    <div class="text-gray-500 text-sm ml-auto message-time" data-timestamp="<?= strtotime($message['created_at']) ?>">
                                        <i class="far fa-clock mr-1"></i>
                                        <?= date('H:i', strtotime($message['created_at'])) ?>
                                    </div>
                                </div>

                                <div class="<?= $message['role_name'] === 'Customer' ? 'bg-gray-50' : 'bg-green-50' ?> rounded-xl p-4 message-content">
                                    <p class="text-gray-700"><?= nl2br(esc($message['message'] ?? '')) ?></p>
                                    
                                    <!-- Message attachments -->
                                    <?php if (!empty($message['attachments'])): ?>
                                        <div class="mt-3 space-y-2">
                                            <?php foreach ($message['attachments'] as $attachment): ?>
                                                <div class="flex items-center gap-2 p-2 bg-white/50 rounded-lg">
                                                    <i class="fas fa-paperclip text-gray-400"></i>
                                                    <span class="text-xs text-gray-600"><?= $attachment['file_name'] ?></span>
                                                    <a href="<?= base_url('download/attachment/' . $attachment['attachment_id']) ?>" 
                                                       class="ml-auto text-xs text-secondary hover:underline">
                                                        Download
                                                    </a>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8 text-gray-500" id="noMessages">
                    <i class="fas fa-comments text-3xl mb-3"></i>
                    <p>No messages yet. Start the conversation!</p>
                </div>
            <?php endif; ?>
            
            <!-- Typing Indicator -->
            <div id="typingIndicator" class="hidden">
                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-headset text-green-600"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div>
                                <span class="text-gray-800 font-semibold">Support Team</span>
                                <span class="ml-2 px-2 py-0.5 bg-green-50 text-green-700 text-xs rounded">
                                    Support
                                </span>
                            </div>
                        </div>
                        <div class="bg-green-50 rounded-xl p-4">
                            <div class="typing-indicator">
                                <div class="typing-dot"></div>
                                <div class="typing-dot"></div>
                                <div class="typing-dot"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Unread Messages Indicator -->
        <div id="unreadIndicator" class="hidden">
            <div class="text-center py-2 bg-blue-50 border-t border-blue-100">
                <button id="jumpToLatest" class="text-sm text-secondary hover:underline">
                    <i class="fas fa-arrow-down mr-1"></i>
                    New messages
                </button>
            </div>
        </div>
    </div>

    <!-- Reply Section -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Add Your Reply</h3>

        <div class="space-y-4">
            <!-- Message Input -->
            <div>
                <textarea id="messageInput" placeholder="Type your message here..."
                    class="w-full h-32 p-4 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 resize-none text-gray-700"
                    rows="4"></textarea>
                <div class="text-gray-500 text-xs mt-1">
                    Max file size: 10MB • Supports images, PDF, Word, text files
                </div>
            </div>

            <!-- File Attachment -->
            <div class="flex items-center gap-4">
                <input type="file" id="fileInput" class="hidden" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt">
                <button id="attachFileBtn"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium flex items-center gap-2">
                    <i class="fas fa-paperclip"></i>
                    Attach File
                </button>
                <div id="fileInfo" class="text-gray-500 text-sm">
                    No files attached
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button id="sendReplyBtn"
                    class="px-6 py-3 bg-secondary text-white rounded-lg hover:bg-secondary/90 transition-colors font-medium flex items-center gap-2 flex-1 justify-center">
                    <i class="fas fa-paper-plane"></i>
                    Send Reply
                </button>
                <button id="cancelBtn"
                    class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium flex-1">
                    Clear
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* WebSocket Status Styles */
    #wsStatus {
        transition: all 0.3s ease;
    }
    
    #wsStatus.connected .status-dot {
        background-color: #10B981;
        box-shadow: 0 0 10px #10B981;
        animation: pulse 2s infinite;
    }
    
    #wsStatus.disconnected .status-dot {
        background-color: #EF4444;
        box-shadow: 0 0 10px #EF4444;
    }
    
    #wsStatus.connecting .status-dot {
        background-color: #F59E0B;
        box-shadow: 0 0 10px #F59E0B;
        animation: pulse 1s infinite;
    }
    
    /* Notification Toast Styles */
    .notification-toast {
        animation: slideInRight 0.3s ease-out;
        max-width: 400px;
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    /* Message animations */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message-item {
        animation: slideIn 0.3s ease-out;
    }
    
    /* New message highlight */
    .new-message {
        animation: highlightMessage 2s ease-out;
    }
    
    @keyframes highlightMessage {
        0% {
            background-color: rgba(59, 130, 246, 0.1);
        }
        100% {
            background-color: transparent;
        }
    }

    /* Scrollbar styling */
    #conversationContainer {
        scroll-behavior: smooth;
    }

    #conversationContainer::-webkit-scrollbar {
        width: 8px;
    }

    #conversationContainer::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    #conversationContainer::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }

    #conversationContainer::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    /* Typing indicator */
    .typing-indicator {
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 8px 12px;
        background: #f3f4f6;
        border-radius: 16px;
        width: fit-content;
    }

    .typing-dot {
        width: 8px;
        height: 8px;
        background: #6b7280;
        border-radius: 50%;
        animation: typing 1.4s infinite ease-in-out;
    }

    .typing-dot:nth-child(1) { animation-delay: -0.32s; }
    .typing-dot:nth-child(2) { animation-delay: -0.16s; }

    @keyframes typing {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1); }
    }
    
    /* Online indicator */
    .online-indicator {
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
        position: absolute;
        bottom: 0;
        right: 0;
        border: 2px solid white;
        animation: pulse 2s infinite;
    }
    
    /* Auto-resize textarea */
    textarea {
        min-height: 48px;
        max-height: 200px;
        resize: none;
        transition: height 0.2s;
    }
    
    /* Progress bar animation */
    #progressBar {
        transition: width 0.5s ease-in-out;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ==================== INITIALIZATION ====================
    const container = document.querySelector('[data-ticket-id]');
    const ticketId = container.dataset.ticketId;
    const userId = container.dataset.userId;
    const userRole = container.dataset.userRole || 'Customer';
    const departmentId = '<?= session()->get('department_id') ?? null ?>';
    
    // Set defaults if not set
    if (!userRole || userRole === '') {
        container.dataset.userRole = 'Customer';
    }
    
    console.log('Ticket Chat Initialized:', { ticketId, userId, userRole });
    
    // ==================== WEBSOCKET INITIALIZATION ====================
    const wsStatus = document.getElementById('wsStatus');
    let socket = null;
    let reconnectAttempts = 0;
    const maxReconnectAttempts = 5;
    let isTyping = false;
    let typingTimeout = null;
    let lastMessageId = 0;
    
    // Get the last message ID for polling
    const lastMessageElement = document.querySelector('.message-item:last-child');
    if (lastMessageElement) {
        lastMessageId = parseInt(lastMessageElement.dataset.messageId) || 0;
    }
    
    function initializeWebSocket() {
        if (!userId || !ticketId) {
            console.warn('User ID or Ticket ID not found, skipping WebSocket connection');
            return;
        }
        
        // WebSocket server URL
        const socketUrl = '<?= env('WS_SERVER_URL', 'http://localhost:3000') ?>';
        
        // Initialize Socket.IO
        socket = io(socketUrl, {
            transports: ['websocket', 'polling'],
            reconnection: true,
            reconnectionAttempts: maxReconnectAttempts,
            reconnectionDelay: 1000,
            reconnectionDelayMax: 5000,
            timeout: 20000,
            auth: {
                user_id: userId,
                role: userRole,
                department_id: departmentId
            }
        });
        
        // Connection established
        socket.on('connect', () => {
            console.log('✅ WebSocket connected with ID:', socket.id);
            updateConnectionStatus('connected', 'Connected');
            reconnectAttempts = 0;
            
            // Send authentication data
            socket.emit('authenticate', {
                user_id: userId,
                role: userRole,
                department_id: departmentId
            });
            
            // Join ticket room
            socket.emit('join_ticket', { ticket_id: ticketId });
        });
        
        // Authentication successful
        socket.on('authenticated', (data) => {
            console.log('✅ Authenticated:', data);
        });
        
        // New chat message received
        socket.on('new_message', (message) => {
            console.log('💬 New message received:', message);
            handleNewMessage(message);
        });
        
        // User typing indicator
        socket.on('user_typing', (data) => {
            console.log('✍️ User typing:', data);
            handleTypingIndicator(data);
        });
        
        // Ticket status updated
        socket.on('ticket_status_changed', (data) => {
            console.log('🔄 Ticket status changed:', data);
            handleTicketStatusChange(data);
        });
        
        // New notification
        socket.on('new_notification', (notification) => {
            console.log('📢 New notification:', notification);
            showNotificationToast(notification);
        });
        
        // Connection error
        socket.on('connect_error', (error) => {
            console.error('❌ Connection error:', error);
            updateConnectionStatus('disconnected', 'Connection Error');
        });
        
        // Disconnected
        socket.on('disconnect', (reason) => {
            console.log('🔌 Disconnected:', reason);
            updateConnectionStatus('disconnected', 'Disconnected');
            
            // Leave ticket room
            socket.emit('leave_ticket', { ticket_id: ticketId });
            
            // Attempt to reconnect
            if (reason === 'io server disconnect') {
                setTimeout(() => {
                    if (reconnectAttempts < maxReconnectAttempts) {
                        reconnectAttempts++;
                        console.log(`🔄 Attempting to reconnect (${reconnectAttempts}/${maxReconnectAttempts})...`);
                        socket.connect();
                    }
                }, 3000);
            }
        });
        
        // Reconnecting
        socket.on('reconnecting', (attemptNumber) => {
            console.log(`🔄 Reconnecting (${attemptNumber}/${maxReconnectAttempts})...`);
            updateConnectionStatus('connecting', `Reconnecting (${attemptNumber})`);
        });
        
        // Show status after 1 second
        setTimeout(() => {
            wsStatus.classList.remove('hidden');
        }, 1000);
    }
    
    function updateConnectionStatus(status, text) {
        // Remove all status classes
        wsStatus.classList.remove('connected', 'disconnected', 'connecting');
        
        // Add current status class
        wsStatus.classList.add(status);
        
        // Update text
        const statusText = wsStatus.querySelector('.status-text');
        if (statusText) {
            statusText.textContent = text;
        }
    }
    
    // ==================== MESSAGE HANDLING ====================
    function handleNewMessage(message) {
        const { ticket_id, sender_id, message: messageText, sender_role, created_at, message_id, is_internal } = message;
        
        // Only handle messages for this ticket
        if (ticket_id != ticketId) return;
        
        // Skip internal messages (between support/department)
        if (is_internal && sender_role !== 'Customer') return;
        
        // Check if message already exists
        const existingMessage = document.querySelector(`[data-message-id="${message_id}"]`);
        if (existingMessage) return;
        
        // Remove "no messages" placeholder if exists
        const noMessages = document.getElementById('noMessages');
        if (noMessages) {
            noMessages.remove();
        }
        
        // Create message element
        const messageElement = createMessageElement({
            message_id: message_id,
            sender_id: sender_id,
            full_name: sender_role === 'Customer' ? 'You' : 'Support Team',
            role_name: sender_role,
            message: messageText,
            created_at: created_at,
            is_current_user: sender_id == userId
        });
        
        // Add to messages container
        const messagesContainer = document.getElementById('messagesContainer');
        if (!messagesContainer) {
            // Create messages container if it doesn't exist
            const container = document.getElementById('conversationContainer');
            const newContainer = document.createElement('div');
            newContainer.id = 'messagesContainer';
            newContainer.className = 'space-y-6';
            container.appendChild(newContainer);
            messagesContainer = newContainer;
        }
        
        // Check if we need a date header
        const messageDate = new Date(created_at).toLocaleDateString('en-US', { 
            month: 'long', 
            day: 'numeric', 
            year: 'numeric' 
        });
        
        const lastDateHeader = messagesContainer.querySelector('.date-header:last-child');
        const lastDate = lastDateHeader ? lastDateHeader.dataset.date : null;
        
        if (messageDate !== lastDate) {
            const dateHeader = document.createElement('div');
            dateHeader.className = 'text-center date-header';
            dateHeader.dataset.date = messageDate;
            dateHeader.innerHTML = `
                <span class="px-4 py-1 bg-gray-100 text-gray-600 text-sm rounded-full">
                    ${messageDate}
                </span>
            `;
            messagesContainer.appendChild(dateHeader);
        }
        
        messagesContainer.appendChild(messageElement);
        
        // Update message count
        updateMessageCount();
        
        // Scroll to bottom if user is near bottom
        scrollToBottomIfNeeded();
        
        // Highlight new message
        messageElement.classList.add('new-message');
        setTimeout(() => {
            messageElement.classList.remove('new-message');
        }, 2000);
        
        // Play notification sound if message is from someone else
        if (sender_id != userId) {
            playNotificationSound();
            
            // Show browser notification
            if (Notification.permission === 'granted') {
                new Notification('New Message', {
                    body: `${sender_role}: ${messageText.substring(0, 100)}${messageText.length > 100 ? '...' : ''}`,
                    icon: '/favicon.ico'
                });
            }
        }
        
        // Update last message ID
        lastMessageId = Math.max(lastMessageId, message_id);
    }
    
    function createMessageElement(message) {
        const isCustomer = message.role_name === 'Customer';
        const isCurrentUser = message.sender_id == userId;
        
        const messageElement = document.createElement('div');
        messageElement.className = 'flex gap-4 message-item';
        messageElement.dataset.messageId = message.message_id;
        messageElement.dataset.senderId = message.sender_id;
        messageElement.dataset.senderRole = message.role_name;
        
        const time = new Date(message.created_at);
        const timeString = time.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
        
        messageElement.innerHTML = `
            <!-- Avatar -->
            <div class="flex-shrink-0">
                <div class="w-10 h-10 ${isCustomer ? 'bg-blue-100' : 'bg-green-100'} rounded-full flex items-center justify-center relative">
                    ${isCustomer ? 
                        '<i class="fas fa-user text-blue-600"></i>' : 
                        '<i class="fas fa-headset text-green-600"></i>'
                    }
                </div>
            </div>

            <!-- Message Content -->
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <div>
                        <span class="text-gray-800 font-semibold">${message.full_name}</span>
                        <span class="ml-2 px-2 py-0.5 ${isCustomer ? 'bg-blue-50 text-blue-700' : 'bg-green-50 text-green-700'} text-xs rounded sender-role">
                            ${message.role_name}
                        </span>
                    </div>
                    <div class="text-gray-500 text-sm ml-auto message-time" data-timestamp="${Math.floor(time.getTime() / 1000)}">
                        <i class="far fa-clock mr-1"></i>
                        ${timeString}
                    </div>
                </div>

                <div class="${isCustomer ? 'bg-gray-50' : 'bg-green-50'} rounded-xl p-4 message-content">
                    <p class="text-gray-700">${escapeHtml(message.message).replace(/\n/g, '<br>')}</p>
                </div>
            </div>
        `;
        
        return messageElement;
    }
    
    function handleTypingIndicator(data) {
        const { ticket_id, user_id, is_typing } = data;
        
        if (ticket_id != ticketId || user_id == userId) return;
        
        const typingIndicator = document.getElementById('typingIndicator');
        
        if (is_typing) {
            typingIndicator.classList.remove('hidden');
            // Scroll to show typing indicator
            setTimeout(() => {
                scrollToBottom();
            }, 100);
        } else {
            typingIndicator.classList.add('hidden');
        }
    }
    
    function handleTicketStatusChange(data) {
        const { ticket_id, status, updated_by, updated_at } = data;
        
        if (ticket_id != ticketId) return;
        
        // Update status display
        const statusElement = document.getElementById('ticketStatus');
        if (statusElement) {
            statusElement.textContent = status;
            
            // Update color based on status
            const statusColors = {
                'Open': 'bg-white/20',
                'In Progress': 'bg-blue-500/20',
                'Resolved': 'bg-green-500/20',
                'Closed': 'bg-purple-500/20',
                'Cancelled': 'bg-red-500/20'
            };
            
            statusElement.className = `px-3 py-1 ${statusColors[status] || 'bg-white/20'} rounded-full text-sm font-semibold`;
        }
        
        // Update last update time
        const lastUpdateElement = document.getElementById('lastUpdate');
        if (lastUpdateElement) {
            lastUpdateElement.textContent = `Last update: ${new Date(updated_at).toLocaleString()}`;
        }
        
        // Update progress bar based on status
        updateProgressBar(status);
        
        // Show notification
        showToast(`Ticket status updated to ${status}`, 'info');
    }
    
    function updateProgressBar(status) {
        const progressBar = document.getElementById('progressBar');
        const progressPercent = document.getElementById('progressPercent');
        const progressStatus = document.getElementById('progressStatus');
        
        if (!progressBar) return;
        
        const statusProgress = {
            'Open': 25,
            'In Progress': 50,
            'Resolved': 75,
            'Closed': 100,
            'Cancelled': 100
        };
        
        const progress = statusProgress[status] || 25;
        
        progressBar.style.width = `${progress}%`;
        
        if (progressPercent) {
            progressPercent.textContent = `${progress}%`;
        }
        
        if (progressStatus) {
            const statusTexts = {
                'Open': 'Ticket is open',
                'In Progress': 'Support is working on it',
                'Resolved': 'Issue resolved',
                'Closed': 'Ticket closed',
                'Cancelled': 'Ticket cancelled'
            };
            progressStatus.textContent = statusTexts[status] || 'Ticket is open';
        }
    }
    
    // ==================== MESSAGE SENDING ====================
    async function sendMessage() {
        const messageInput = document.getElementById('messageInput');
        const message = messageInput.value.trim();
        
        if (!message) {
            showToast('Please enter a message', 'warning');
            return;
        }
        
        // Disable send button while sending
        const sendBtn = document.getElementById('sendReplyBtn');
        const originalText = sendBtn.innerHTML;
        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
        sendBtn.disabled = true;
        
        try {
            // Send via AJAX to PHP controller
            const response = await fetch('<?= base_url('customer/chat/send') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    ticket_id: ticketId,
                    message: message
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Clear input
                messageInput.value = '';
                autoResizeTextarea(messageInput);
                
                // Stop typing indicator
                stopTypingIndicator();
                
                // The message will be added via WebSocket event
                showToast('Message sent successfully', 'success');
                
                // Also send via WebSocket for real-time
                if (socket && socket.connected) {
                    socket.emit('new_message', {
                        ticket_id: ticketId,
                        sender_id: userId,
                        message: message,
                        sender_role: userRole,
                        is_internal: false
                    });
                }
            } else {
                showToast(data.message || 'Failed to send message', 'error');
            }
        } catch (error) {
            console.error('Error sending message:', error);
            showToast('Failed to send message', 'error');
        } finally {
            // Re-enable send button
            sendBtn.innerHTML = originalText;
            sendBtn.disabled = false;
        }
    }
    
    async function sendTypingIndicator() {
        if (!socket || !socket.connected) return;
        
        if (!isTyping) {
            isTyping = true;
            socket.emit('typing', {
                ticket_id: ticketId,
                user_id: userId,
                is_typing: true
            });
        }
        
        // Clear existing timeout
        if (typingTimeout) {
            clearTimeout(typingTimeout);
        }
        
        // Set timeout to stop typing indicator after 2 seconds
        typingTimeout = setTimeout(() => {
            stopTypingIndicator();
        }, 2000);
    }
    
    function stopTypingIndicator() {
        if (!socket || !socket.connected || !isTyping) return;
        
        isTyping = false;
        socket.emit('typing', {
            ticket_id: ticketId,
            user_id: userId,
            is_typing: false
        });
        
        if (typingTimeout) {
            clearTimeout(typingTimeout);
            typingTimeout = null;
        }
    }
    
    // ==================== FILE ATTACHMENT ====================
    function handleFileAttachment() {
        const fileInput = document.getElementById('fileInput');
        const fileInfo = document.getElementById('fileInfo');
        
        fileInput.click();
        
        fileInput.addEventListener('change', async function() {
            if (!this.files.length) return;
            
            const file = this.files[0];
            const maxSize = 10 * 1024 * 1024; // 10MB
            
            if (file.size > maxSize) {
                showToast('File size exceeds 10MB limit', 'error');
                return;
            }
            
            // Show file info
            fileInfo.textContent = `${file.name} (${formatFileSize(file.size)})`;
            fileInfo.classList.remove('text-gray-500');
            fileInfo.classList.add('text-secondary', 'font-medium');
            
            // You can implement file upload here if needed
            // For now, just show the file info
        });
    }
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // ==================== HELPER FUNCTIONS ====================
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    function updateMessageCount() {
        const messageCountElement = document.getElementById('messageCount');
        if (messageCountElement) {
            const messages = document.querySelectorAll('.message-item').length;
            messageCountElement.textContent = messages;
        }
    }
    
    function scrollToBottom() {
        const container = document.getElementById('conversationContainer');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }
    
    function scrollToBottomIfNeeded() {
        const container = document.getElementById('conversationContainer');
        if (container) {
            const isNearBottom = container.scrollHeight - container.scrollTop - container.clientHeight < 100;
            if (isNearBottom) {
                scrollToBottom();
            } else {
                // Show "new messages" indicator
                const unreadIndicator = document.getElementById('unreadIndicator');
                if (unreadIndicator) {
                    unreadIndicator.classList.remove('hidden');
                }
            }
        }
    }
    
    function autoResizeTextarea(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    }
    
    function playNotificationSound() {
        try {
            const audio = new Audio('data:audio/wav;base64,UklGRigAAABXQVZFZm10IBIAAAABAAEAQB8AAEAfAAABAAgAZGF0YQ');
            audio.volume = 0.3;
            audio.play().catch(() => {
                // Ignore errors if audio cannot play
            });
        } catch (error) {
            console.log('Audio playback not supported');
        }
    }
    
    function showNotificationToast(notification) {
        const container = document.getElementById('notificationContainer');
        if (!container) return;
        
        const toastId = 'toast-' + Date.now();
        const toast = document.createElement('div');
        toast.id = toastId;
        toast.className = 'notification-toast bg-white rounded-lg shadow-lg border border-gray-200 p-4';
        toast.innerHTML = `
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 mt-1">
                    <div class="w-3 h-3 bg-secondary rounded-full"></div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-1">
                        <h4 class="text-sm font-semibold text-gray-800 truncate">${notification.title}</h4>
                        <button onclick="closeToast('${toastId}')" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>
                    <p class="text-xs text-gray-600 mb-2">${notification.message}</p>
                    <div class="text-xs text-gray-500">
                        ${formatTimeAgo(new Date(notification.created_at))}
                    </div>
                </div>
            </div>
        `;
        
        container.appendChild(toast);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            closeToast(toastId);
        }, 5000);
    }
    
    function closeToast(toastId) {
        const toast = document.getElementById(toastId);
        if (toast) {
            toast.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => {
                toast.remove();
            }, 300);
        }
    }
    
    function formatTimeAgo(date) {
        const now = new Date();
        const diffMs = now - date;
        const diffSec = Math.floor(diffMs / 1000);
        const diffMin = Math.floor(diffSec / 60);
        const diffHour = Math.floor(diffMin / 60);
        const diffDay = Math.floor(diffHour / 24);
        
        if (diffSec < 60) return 'Just now';
        if (diffMin < 60) return `${diffMin} minute${diffMin > 1 ? 's' : ''} ago`;
        if (diffHour < 24) return `${diffHour} hour${diffHour > 1 ? 's' : ''} ago`;
        if (diffDay < 7) return `${diffDay} day${diffDay > 1 ? 's' : ''} ago`;
        return date.toLocaleDateString();
    }
    
    function showToast(message, type = 'info') {
        const container = document.getElementById('notificationContainer');
        if (!container) return;
        
        const toastId = 'alert-toast-' + Date.now();
        const toast = document.createElement('div');
        toast.id = toastId;
        
        const colors = {
            success: 'bg-green-500 text-white',
            error: 'bg-red-500 text-white',
            warning: 'bg-yellow-500 text-white',
            info: 'bg-blue-500 text-white'
        };
        
        toast.className = `notification-toast ${colors[type]} rounded-lg shadow-lg p-4`;
        toast.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 
                                  type === 'error' ? 'fa-exclamation-circle' : 
                                  type === 'warning' ? 'fa-exclamation-triangle' : 
                                  'fa-info-circle'}"></i>
                    <span class="text-sm font-medium">${message}</span>
                </div>
                <button onclick="closeToast('${toastId}')" class="text-white/80 hover:text-white">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        `;
        
        container.appendChild(toast);
        
        setTimeout(() => {
            closeToast(toastId);
        }, 3000);
    }
    
    // Make closeToast globally available
    window.closeToast = closeToast;
    
    // Base URL helper
    const baseUrl = '<?= base_url() ?>';
    
    // ==================== EVENT LISTENERS ====================
    // Message input events
    const messageInput = document.getElementById('messageInput');
    if (messageInput) {
        messageInput.addEventListener('input', function() {
            autoResizeTextarea(this);
            sendTypingIndicator();
        });
        
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
        
        // Initial resize
        autoResizeTextarea(messageInput);
    }
    
    // Send button
    document.getElementById('sendReplyBtn')?.addEventListener('click', sendMessage);
    
    // Cancel/clear button
    document.getElementById('cancelBtn')?.addEventListener('click', function() {
        messageInput.value = '';
        autoResizeTextarea(messageInput);
        document.getElementById('fileInfo').textContent = 'No files attached';
        document.getElementById('fileInfo').className = 'text-gray-500 text-sm';
        document.getElementById('fileInput').value = '';
    });
    
    // File attachment
    document.getElementById('attachFileBtn')?.addEventListener('click', handleFileAttachment);
    
    // Export chat
    document.getElementById('exportChatBtn')?.addEventListener('click', function() {
        showToast('Export feature coming soon!', 'info');
    });
    
    // Jump to latest messages
    document.getElementById('jumpToLatest')?.addEventListener('click', function() {
        scrollToBottom();
        document.getElementById('unreadIndicator').classList.add('hidden');
    });
    
    // Poll for new messages (fallback if WebSocket fails)
    async function pollForNewMessages() {
        if (!ticketId || !lastMessageId) return;
        
        try {
            const response = await fetch(`${baseUrl}/customer/chat/get-new?ticket_id=${ticketId}&last_message_id=${lastMessageId}`);
            const data = await response.json();
            
            if (data.success && data.messages.length > 0) {
                data.messages.forEach(message => {
                    handleNewMessage({
                        ticket_id: ticketId,
                        sender_id: message.sender_id,
                        message: message.message,
                        sender_role: message.sender_role,
                        created_at: message.created_at,
                        message_id: message.message_id,
                        is_internal: false
                    });
                });
            }
        } catch (error) {
            console.error('Error polling for messages:', error);
        }
    }
    
    // Initialize progress bar
    updateProgressBar('<?= $data['ticket']['status_name'] ?? 'Open' ?>');
    
    // Initialize WebSocket
    initializeWebSocket();
    
    // Start polling as fallback
    const pollInterval = setInterval(pollForNewMessages, 10000);
    
    // Scroll to bottom initially
    setTimeout(scrollToBottom, 500);
    
    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        if (socket) {
            socket.emit('leave_ticket', { ticket_id: ticketId });
            socket.disconnect();
        }
        clearInterval(pollInterval);
    });
    
    // Handle page visibility change
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden && socket && !socket.connected) {
            console.log('Page visible, reconnecting WebSocket...');
            socket.connect();
        }
    });
    
    // Request notification permission
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }
});
</script>
<?= $this->endSection() ?>