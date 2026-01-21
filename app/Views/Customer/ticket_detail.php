<?= $this->extend('layouts/customer_layout') ?>

<?= $this->section('title') ?>Ticket #<?= $data['ticket']['ticket_number'] ?? '12345' ?> Detail - NEXUS<?= $this->endSection() ?>

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
     data-user-role="<?= $data['user_role'] ?? session()->get('role') ?? 'Customer' ?>"> <!-- PERBAIKAN DISINI -->
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-[32px] font-semibold mb-2 text-text-dark"><?= $data['ticket']['ticket_number'] ?? 'N/A' ?></h1>
                <p class="text-[15px] text-[#000]"><?= esc($data['ticket']['subject'] ?? 'No Subject') ?></p>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3">
                <a href="<?= base_url('customer/my_tickets') ?>"
                    class="px-4 py-2 bg-white text-secondary border border-secondary rounded-lg hover:bg-secondary/5 transition-all text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Back to Tickets
                </a>
                <button
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
                        <div class="px-3 py-1 bg-white/20 rounded-full text-sm font-semibold"><?= $data['ticket']['status_name'] ?? 'Open' ?></div>
                    </div>
                </div>
                <div>
                    <div class="text-white/80 text-sm mb-1">Priority</div>
                    <div class="px-3 py-1 bg-red-500/20 rounded-full text-sm font-semibold inline-block"><?= $data['ticket']['priority_name'] ?? 'Medium' ?></div>
                </div>
                <div>
                    <div class="text-white/80 text-sm mb-1">Created</div>
                    <div class="text-lg font-semibold"><?= date('F d, Y', strtotime($data['ticket']['created_at'] ?? 'now')) ?></div>
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
                        <span></span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-secondary rounded-full w-3/4"></div>
                    </div>
                </div>
                <div class="text-sm text-gray-600">
                    <div class="flex items-center gap-2 mb-1">
                        <i class="far fa-clock text-gray-400"></i>
                        <span>Last update: <?= date('F d, Y H:i:s', strtotime($data['ticket']['updated_at'] ?? 'now')) ?></span>
                    </div>
                    <div class="text-secondary font-medium">Accepted by Support Team</div>
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
                    <div class="text-gray-800 font-semibold"><?= $data['ticket']['category_name'] ?? 'Technical' ?></div>
                    <div class="text-gray-600 text-sm"><?= $data['ticket']['department_name'] ?? 'IT Support' ?></div>
                </div>
            </div>
            <p class="text-gray-600 text-sm">
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
                <div class="text-gray-600 text-sm">
                    <i class="far fa-comments mr-1"></i>
                    <span id="messageCount"><?= count($data['messages'] ?? []) ?></span> messages
                </div>
            </div>
        </div>

        <!-- Conversation Container (Scrollable) -->
        <div id="conversationContainer" class="p-6 h-[500px] overflow-y-auto">
            <?php if (!empty($data['messages'])): ?>
                <div class="space-y-6">
                    <?php 
                    $currentDate = null;
                    foreach ($data['messages'] as $message): 
                        $messageDate = date('F j, Y', strtotime($message['created_at']));
                    ?>
                        <?php if ($messageDate != $currentDate): ?>
                            <!-- Date Header -->
                            <div class="text-center">
                                <span class="px-4 py-1 bg-gray-100 text-gray-600 text-sm rounded-full">
                                    <?= $messageDate ?>
                                </span>
                            </div>
                            <?php $currentDate = $messageDate; ?>
                        <?php endif; ?>
                        
                        <!-- Message -->
                        <div class="flex gap-4 message-item" data-message-id="<?= $message['message_id'] ?? '' ?>">
                            <!-- Avatar -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 <?= $message['role_name'] === 'Customer' ? 'bg-blue-100' : 'bg-green-100' ?> rounded-full flex items-center justify-center">
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
                                        <span class="ml-2 px-2 py-0.5 <?= $message['role_name'] === 'Customer' ? 'bg-blue-50 text-blue-700' : 'bg-green-50 text-green-700' ?> text-xs rounded">
                                            <?= $message['role_name'] ?? 'User' ?>
                                        </span>
                                    </div>
                                    <div class="text-gray-500 text-sm ml-auto">
                                        <i class="far fa-clock mr-1"></i>
                                        <?= date('H:i', strtotime($message['created_at'])) ?>
                                    </div>
                                </div>

                                <div class="<?= $message['role_name'] === 'Customer' ? 'bg-gray-50' : 'bg-green-50' ?> rounded-xl p-4">
                                    <p class="text-gray-700"><?= nl2br(esc($message['message'] ?? '')) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-comments text-3xl mb-3"></i>
                    <p>No messages yet. Start the conversation!</p>
                </div>
            <?php endif; ?>
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
    /* Auto-resize textarea */
    textarea {
        min-height: 48px;
        max-height: 200px;
        resize: none;
        transition: height 0.2s;
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
        margin: 8px 0;
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
    }
</style>
<script>
// Debug session data
console.log('=== SESSION DEBUG ===');
console.log('User ID from session():', '<?= session()->get('user_id') ?>');
console.log('Role from session():', '<?= session()->get('role') ?>');
console.log('User role from $data:', '<?= $data['user_role'] ?? 'NOT SET' ?>');

// Set default jika kosong
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('[data-ticket-id]');
    if (container && (!container.dataset.userRole || container.dataset.userRole === '')) {
        console.log('userRole is empty, setting default to "Customer"');
        container.dataset.userRole = 'Customer';
    }
});
</script>
<script src="/js/chat.js"></script>
<script>
// Auto-resize textarea

function autoResizeTextarea(textarea) {
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
}

document.querySelectorAll('textarea').forEach(textarea => {
    textarea.addEventListener('input', function() {
        autoResizeTextarea(this);
    });
    // Initial resize
    autoResizeTextarea(textarea);
});

// Request notification permission
if ('Notification' in window && Notification.permission === 'default') {
    Notification.requestPermission();
}

// Export button
document.querySelector('button:contains("Export")')?.addEventListener('click', function() {
    alert('Exporting conversation...');
});
</script>

<?= $this->endSection() ?>