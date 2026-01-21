<?= $this->extend('layouts/support_layout') ?>

<?= $this->section('title') ?>Ticket #<?= $ticket_id ?> Detail - NEXUS Support<?= $this->endSection() ?>

<?= $this->section('background_effects') ?>
<!-- Background Effects -->
<div class="fixed w-[40vw] h-[40vw] -right-[10%] -bottom-[10%] rotate-[149deg] bg-gradient-to-r from-[rgba(56.96,44.47,127.72,0.15)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-80 z-0"></div>
<div class="fixed w-[35vw] h-[25vw] -left-[5%] top-[10%] rotate-[8deg] bg-gradient-to-r from-[rgba(65.04,45.10,137.14,0.20)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-70 z-0"></div>
<div class="fixed w-[15vw] h-[20vw] right-[5%] -top-[5%] rotate-[8deg] bg-gradient-to-r from-[rgba(16.56,8.41,46.05,0.12)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-60 z-0"></div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mt-[77px] p-[30px] relative z-10"
     data-ticket-id="<?= $ticket_id ?>"
     data-user-id="<?= session()->get('user_id') ?>"
     data-user-role="<?= session()->get('role') ?? 'Support' ?>">
    
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-[32px] font-semibold mb-2 text-text-dark">Ticket #<?= $ticket['ticket_number'] ?? $ticket_id ?></h1>
                <p class="text-[15px] font-light text-[#666]">
                    <?= esc($ticket['subject'] ?? 'No Subject') ?> •
                    <?= esc($ticket['project_name'] ?? 'No Project') ?>
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3">
                <a href="<?= base_url('support/incoming') ?>"
                    class="px-4 py-2 bg-white text-secondary border border-secondary rounded-lg hover:bg-secondary/5 transition-all text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Back to Incoming
                </a>
                <!-- Link ke ticket_summary -->
                <a href="<?= base_url('support/ticket_summary/' . $ticket_id) ?>"
                    class="px-4 py-2 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-all text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-file-alt"></i>
                    View Summary
                </a>
                <button class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-all text-sm font-medium flex items-center gap-2">
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
                        <div class="px-3 py-1 bg-white/20 rounded-full text-sm font-semibold">
                            <?= strtoupper($ticket['status_name'] ?? 'OPEN') ?>
                        </div>
                        <div class="text-lg font-bold"><?= $ticket['status_name'] ?? 'Open' ?></div>
                    </div>
                </div>
                <div>
                    <div class="text-white/80 text-sm mb-1">Priority</div>
                    <div class="px-3 py-1 bg-red-500/20 rounded-full text-sm font-semibold inline-block">
                        <?= strtoupper($ticket['priority_name'] ?? 'HIGH') ?>
                    </div>
                </div>
                <div>
                    <div class="text-white/80 text-sm mb-1">Customer</div>
                    <div class="text-lg font-semibold"><?= $ticket['customer_name'] ?? 'John Smith' ?></div>
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
                        <span id="completionPercent">
                            <?php 
                            $status = $ticket['status_name'] ?? 'Open';
                            $percent = 0;
                            if ($status === 'Open') $percent = 25;
                            elseif ($status === 'In Progress') $percent = 50;
                            elseif ($status === 'Resolved') $percent = 75;
                            elseif ($status === 'Closed') $percent = 100;
                            else $percent = 25;
                            echo $percent . '%';
                            ?>
                        </span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div id="progressBar" class="h-full bg-secondary rounded-full" style="width: <?= $percent ?>%"></div>
                    </div>
                </div>
                <div class="text-sm text-gray-600">
                    <div class="flex items-center gap-2 mb-1">
                        <i class="far fa-clock text-gray-400"></i>
                        <span id="lastUpdateTime">Last update: <?= date('F d, Y H:i', strtotime($ticket['updated_at'] ?? 'now')) ?></span>
                    </div>
                    <div id="assignedInfo" class="text-secondary font-medium">
                        <?= $ticket['assigned_to_name'] ? 'Assigned to ' . $ticket['assigned_to_name'] : 'Unassigned' ?>
                    </div>
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
                    <div class="text-gray-800 font-semibold"><?= $ticket['category_name'] ?? 'Technical Issue' ?></div>
                    <div class="text-gray-600 text-sm"><?= $ticket['department_name'] ?? 'IT Support' ?></div>
                </div>
            </div>
            <p class="text-gray-600 text-sm">
                <?= $ticket['subject'] ?? 'No description available' ?>
            </p>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
        <!-- Left Column - Conversation -->
        <div class="lg:col-span-3">
            <!-- Conversation Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
                <!-- Section Header -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800">Conversation</h2>
                        <div class="text-gray-600 text-sm">
                            <i class="far fa-comments mr-1"></i>
                            <span id="messageCount"><?= count($messages ?? []) ?></span> messages
                        </div>
                    </div>
                </div>

                <!-- Conversation Container (Scrollable) -->
                <div id="conversationContainer" class="p-6 h-[500px] overflow-y-auto">
                    <?php if (!empty($messages)): ?>
                        <div class="space-y-6">
                            <?php 
                            $currentDate = null;
                            foreach ($messages as $message): 
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

                                        <div class="<?= $message['role_name'] === 'Customer' ? 'bg-gray-50 border-gray-200' : 'bg-green-50 border-green-100' ?> rounded-xl p-4 border">
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
                    
                    <!-- Typing Indicator (Hidden by default) -->
                    <div id="typingIndicator" class="hidden">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                    <i class="fas fa-ellipsis-h text-gray-400"></i>
                                </div>
                            </div>
                            <div class="flex-1">
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

                    <!-- Support Options -->
                    <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-200">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="markAsResolved" class="rounded text-secondary focus:ring-secondary">
                            <label for="markAsResolved" class="text-gray-700 text-sm">Mark as resolved after sending</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="internalNote" class="rounded text-secondary focus:ring-secondary">
                            <label for="internalNote" class="text-gray-700 text-sm">Add as internal note</label>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 pt-4">
                        <button id="sendReplyBtn"
                            class="px-6 py-3 bg-secondary text-white rounded-lg hover:bg-secondary/90 transition-colors font-medium flex items-center gap-2 flex-1 justify-center">
                            <i class="fas fa-paper-plane"></i>
                            Send Reply
                        </button>
                        <button id="cancelBtn"
                            class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium flex-1">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Support Actions -->
        <div class="space-y-6">
            <!-- Quick Actions Card -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-bolt text-secondary"></i>
                    Quick Actions
                </h3>

                <div class="space-y-3">
                    <!-- Assign to Me -->
                    <button id="assignToMeBtn" class="w-full px-4 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-user-plus"></i>
                        <span>Assign to Me</span>
                    </button>

                    <!-- Request Info -->
                    <button id="requestInfoBtn" class="w-full px-4 py-3 bg-white border border-secondary text-secondary rounded-lg hover:bg-secondary/5 transition-colors font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-question-circle"></i>
                        <span>Request Info</span>
                    </button>

                    <!-- Mark as Resolved -->
                    <button onclick="handleMarkResolved(<?= $ticket_id ?>)"
                        class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-3 rounded-lg flex items-center justify-center gap-2 transition-colors">
                        <i class="fas fa-check-circle"></i>
                        Closed Tickets
                    </button>   
                </div>
            </div>

            <!-- Assign to Department Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-secondary/10 to-secondary/5">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-building text-secondary"></i>
                        Assign to Department
                    </h2>
                </div>

                <div class="p-6">
                    <div class="mb-4">
                        <p class="text-gray-600 text-sm mb-3">Select appropriate department for this ticket:</p>

                        <!-- Department Options -->
                        <div class="space-y-2 mb-4">
                            <div class="department-option flex items-center gap-3 p-3 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50" data-department="technical">
                                <div class="w-4 h-4 bg-secondary rounded-full flex items-center justify-center">
                                    <i class="fas fa-plus text-white text-xs"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Technical Support</p>
                                    <p class="text-gray-500 text-xs">Login, authentication, access issues</p>
                                </div>
                            </div>

                            <div class="department-option flex items-center gap-3 p-3 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50" data-department="it">
                                <div class="w-4 h-4 bg-secondary rounded-full flex items-center justify-center">
                                    <i class="fas fa-plus text-white text-xs"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">IT Infrastructure</p>
                                    <p class="text-gray-500 text-xs">System, server, network problems</p>
                                </div>
                            </div>

                            <div class="department-option flex items-center gap-3 p-3 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50" data-department="development">
                                <div class="w-4 h-4 bg-secondary rounded-full flex items-center justify-center">
                                    <i class="fas fa-plus text-white text-xs"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Development</p>
                                    <p class="text-gray-500 text-xs">Bugs, features, code issues</p>
                                </div>
                            </div>

                            <div class="department-option flex items-center gap-3 p-3 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50" data-department="qa">
                                <div class="w-4 h-4 bg-secondary rounded-full flex items-center justify-center">
                                    <i class="fas fa-plus text-white text-xs"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Quality Assurance</p>
                                    <p class="text-gray-500 text-xs">Testing, verification, validation</p>
                                </div>
                            </div>
                        </div>

                        <button id="assignDepartmentBtn" class="w-full px-4 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium">
                            Forward to Selected Department
                        </button>
                    </div>
                </div>
            </div>

            <!-- Ticket Info Card -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Ticket Information</h3>

                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Ticket ID</span>
                        <span class="font-medium text-gray-800">#<?= $ticket['ticket_number'] ?? $ticket_id ?></span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Customer</span>
                        <span class="font-medium text-gray-800"><?= $ticket['customer_name'] ?? 'John Smith' ?></span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Created</span>
                        <span class="font-medium text-gray-800"><?= date('M d, Y', strtotime($ticket['created_at'] ?? 'now')) ?></span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Last Updated</span>
                        <span id="lastUpdatedTime" class="font-medium text-gray-800"><?= date('M d, Y h:i A', strtotime($ticket['updated_at'] ?? 'now')) ?></span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Response Time</span>
                        <span class="font-medium text-green-600">Within SLA</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Assigned To</span>
                        <span id="assignedToText" class="font-medium text-gray-800"><?= $ticket['assigned_to_name'] ?: 'Unassigned' ?></span>
                    </div>
                </div>
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
    
    /* Toast notification */
    .toast-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 16px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 1000;
        max-width: 350px;
        animation: slideInRight 0.3s ease-out;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100%);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .toast-success {
        background: #10b981;
        color: white;
    }
    
    .toast-error {
        background: #ef4444;
        color: white;
    }
    
    .toast-info {
        background: #3b82f6;
        color: white;
    }
    
    .toast-warning {
        background: #f59e0b;
        color: white;
    }
    
    /* Scroll to bottom button */
    #scrollToBottomBtn {
        position: fixed;
        bottom: 32px;
        right: 32px;
        background: #756EA4;
        color: white;
        padding: 12px;
        border-radius: 50%;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 10;
        cursor: pointer;
        border: none;
        transition: all 0.3s ease;
    }
    
    #scrollToBottomBtn:hover {
        background: #817CB2;
        transform: translateY(-2px);
    }
    
    /* Department option selected state */
    .department-option.selected {
        border-color: #756EA4;
        background-color: rgba(117, 110, 164, 0.05);
    }
</style>

<!-- Chat JavaScript -->
<script src="/js/chat.js"></script>

<script>
// Debug session data
console.log('=== SUPPORT CHAT DEBUG ===');
console.log('Ticket ID:', <?= $ticket_id ?>);
console.log('User ID:', '<?= session()->get('user_id') ?>');
console.log('User Role:', '<?= session()->get('role') ?? 'Support' ?>');

// Function untuk update ticket status UI
function updateTicketStatusUI(statusName) {
    console.log('Updating UI status to:', statusName);
    
    // Update status badge di Ticket Information Card
    const statusBadge = document.querySelector('.bg-gradient-to-r .px-3.py-1');
    if (statusBadge) {
        statusBadge.textContent = statusName.toUpperCase();
        statusBadge.classList.remove('bg-white/20');
        statusBadge.classList.add('bg-green-500');
    }
    
    // Update status text di card yang sama
    const statusText = document.querySelector('.bg-gradient-to-r .text-lg.font-bold');
    if (statusText) {
        statusText.textContent = statusName;
    }
    
    // Update progress completion
    const progressBar = document.getElementById('progressBar');
    if (progressBar) {
        progressBar.style.width = '100%';
    }
    
    const completionPercent = document.getElementById('completionPercent');
    if (completionPercent) {
        completionPercent.textContent = '100%';
    }
}

// Mark as Resolved function
window.handleMarkResolved = function(ticketId) {
    if (confirm('Are you sure you want to mark this ticket as resolved?\n\nThis will:\n• Change ticket status\n• Notify the customer\n• Customer will see status update immediately')) {
        
        // Show loading state
        const button = event.target;
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        button.disabled = true;
        
        // Kirim request ke server
        fetch(`<?= base_url('support/ticket/mark_resolved/') ?>${ticketId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update UI di halaman support dengan status dari response
                const statusName = data.status_name || 'Closed';
                updateTicketStatusUI(statusName);
                
                // Update button
                button.innerHTML = `<i class="fas fa-check"></i> ${statusName}`;
                button.classList.remove('bg-gray-600', 'hover:bg-gray-700');
                button.classList.add('bg-green-600', 'hover:bg-green-700');
                button.disabled = true;
                
                // Show success message
                showToast(data.message, 'success');
                
                // Update last updated time
                const lastUpdated = document.getElementById('lastUpdatedTime');
                if (lastUpdated) {
                    lastUpdated.textContent = new Date().toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                }
                
                // Optionally refresh page setelah 2 detik
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                throw new Error(data.message || 'Failed to mark as resolved');
            }
        })
        .catch(error => {
            // Reset button
            button.innerHTML = originalHTML;
            button.disabled = false;
            
            // Show error
            showToast(error.message || 'Failed to mark ticket as resolved', 'error');
            console.error('Error:', error);
        });
    }
};

// Toast notification function
function showToast(message, type = 'info') {
    // Remove existing toasts
    document.querySelectorAll('.toast-notification').forEach(toast => toast.remove());

    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${type}`;
    toast.innerHTML = `
        <div class="flex items-center gap-3">
            <i class="fas ${getToastIcon(type)} text-lg"></i>
            <span class="text-sm">${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);

    // Auto remove after 3 seconds
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

function getToastIcon(type) {
    switch(type) {
        case 'success': return 'fa-check-circle';
        case 'error': return 'fa-exclamation-circle';
        case 'warning': return 'fa-exclamation-triangle';
        default: return 'fa-info-circle';
    }
}

// Event listener untuk checkbox "Mark as resolved" dengan chat integration
document.addEventListener('DOMContentLoaded', function() {
    const sendBtn = document.getElementById('sendReplyBtn');
    const markAsResolvedCheckbox = document.getElementById('markAsResolved');
    
    if (sendBtn && markAsResolvedCheckbox) {
        // Remove any existing event listeners
        sendBtn.replaceWith(sendBtn.cloneNode(true));
        const newSendBtn = document.getElementById('sendReplyBtn');
        
        newSendBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const markAsResolved = document.getElementById('markAsResolved').checked;
            
            if (window.ticketChat) {
                if (markAsResolved) {
                    // Kirim pesan dulu, lalu mark as resolved
                    window.ticketChat.handleSendMessage().then(() => {
                        setTimeout(() => {
                            if (confirm('Mark this ticket as resolved after sending message?')) {
                                handleMarkResolved(<?= $ticket_id ?>);
                            }
                        }, 500);
                    });
                } else {
                    // Hanya kirim pesan biasa
                    window.ticketChat.handleSendMessage();
                }
            } else {
                alert('Chat system not initialized. Please refresh the page.');
            }
        });
    }
    
    // Auto-assign ticket when support sends first message
    const messageInput = document.getElementById('messageInput');
    if (messageInput) {
        messageInput.addEventListener('input', function() {
            const assignedToText = document.getElementById('assignedToText');
            const assignButton = document.getElementById('assignToMeBtn');
            
            // If ticket is unassigned and support types a message, auto-assign
            if (assignedToText && assignedToText.textContent === 'Unassigned' && 
                messageInput.value.trim().length > 0) {
                
                // Update UI
                assignedToText.textContent = '<?= session()->get('full_name') ?? 'You' ?>';
                assignedToText.classList.add('text-secondary');
                
                // Update assign button
                if (assignButton) {
                    assignButton.innerHTML = '<i class="fas fa-check"></i> Assigned to You';
                    assignButton.classList.remove('bg-secondary', 'hover:bg-[#817CB2]');
                    assignButton.classList.add('bg-green-500', 'hover:bg-green-600');
                    assignButton.disabled = true;
                }
                
                // Update progress card
                const assignedInfo = document.getElementById('assignedInfo');
                if (assignedInfo) {
                    assignedInfo.textContent = 'Assigned to <?= session()->get('full_name') ?? 'You' ?>';
                }
                
                showToast('Ticket auto-assigned to you', 'info');
            }
        });
    }
    
    // Assign to Me button
    const assignToMeBtn = document.getElementById('assignToMeBtn');
    if (assignToMeBtn) {
        assignToMeBtn.addEventListener('click', function() {
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Assigning...';
            this.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-check"></i> Assigned to You';
                this.classList.remove('bg-secondary', 'hover:bg-[#817CB2]');
                this.classList.add('bg-green-500', 'hover:bg-green-600');
                
                // Update UI
                const assignedToText = document.getElementById('assignedToText');
                if (assignedToText) {
                    assignedToText.textContent = '<?= session()->get('full_name') ?? 'You' ?>';
                    assignedToText.classList.add('text-secondary');
                }
                
                const assignedInfo = document.getElementById('assignedInfo');
                if (assignedInfo) {
                    assignedInfo.textContent = 'Assigned to <?= session()->get('full_name') ?? 'You' ?>';
                }
                
                showToast('Ticket assigned to you successfully', 'success');
            }, 1000);
        });
    }
    
    // Department selection
    document.querySelectorAll('.department-option').forEach(option => {
        option.addEventListener('click', function() {
            // Remove selection from all
            document.querySelectorAll('.department-option').forEach(opt => {
                opt.classList.remove('selected');
                const icon = opt.querySelector('.w-4.h-4');
                if (icon.querySelector('.fa-check')) {
                    icon.classList.remove('bg-purple-500');
                    icon.classList.add('bg-secondary');
                    icon.innerHTML = '<i class="fas fa-plus text-white text-xs"></i>';
                }
            });

            // Select this option
            this.classList.add('selected');
            const icon = this.querySelector('.w-4.h-4');
            icon.classList.remove('bg-secondary');
            icon.classList.add('bg-purple-500');
            icon.innerHTML = '<i class="fas fa-check text-white text-xs"></i>';

            // Update button text
            const deptName = this.querySelector('.font-medium').textContent;
            const assignBtn = document.getElementById('assignDepartmentBtn');
            if (assignBtn) {
                assignBtn.innerHTML = `Forward to ${deptName}`;
            }
        });
    });
    
    // Scroll to bottom button functionality
    const conversationContainer = document.getElementById('conversationContainer');
    if (conversationContainer) {
        conversationContainer.addEventListener('scroll', function() {
            const isScrolledUp = this.scrollTop < (this.scrollHeight - this.clientHeight - 100);
            
            // Remove existing button if any
            const existingBtn = document.getElementById('scrollToBottomBtn');
            if (existingBtn) {
                existingBtn.remove();
            }
            
            // Add scroll to bottom button if user is not at bottom
            if (isScrolledUp) {
                const scrollBtn = document.createElement('button');
                scrollBtn.id = 'scrollToBottomBtn';
                scrollBtn.innerHTML = '<i class="fas fa-chevron-down"></i>';
                scrollBtn.title = 'Scroll to latest message';
                
                scrollBtn.addEventListener('click', function() {
                    conversationContainer.scrollTop = conversationContainer.scrollHeight;
                });
                
                document.body.appendChild(scrollBtn);
            }
        });
    }
});

// Request notification permission
if ('Notification' in window && Notification.permission === 'default') {
    Notification.requestPermission();
}
</script>

<?= $this->endSection() ?>