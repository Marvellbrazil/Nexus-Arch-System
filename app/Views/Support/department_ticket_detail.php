<?= $this->extend('layouts/support_layout') ?>

<?= $this->section('title') ?>Internal Chat - Ticket #<?= $ticket_id ?><?= $this->endSection() ?>

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
     data-user-role="<?= session()->get('role_name') ?? 'Support' ?>"
     data-department-id="<?= $department_id ?? ($ticket['department_id'] ?? '') ?>">

     <!-- Modal Update Internal Status -->
<div id="internalStatusModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl max-w-lg w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Update Ticket Status</h3>
            <button onclick="closeInternalStatusModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Select Status
            </label>
            <select id="internalStatusSelect" 
                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary/20 focus:border-secondary">
                <option value="review_needed">Review Needed</option>
                <option value="testing">Testing</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
                <option value="reopened">Reopen for Department</option>
            </select>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Notes
            </label>
            <textarea id="internalStatusNotes" 
                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary/20 focus:border-secondary"
                rows="4" 
                placeholder="Add notes about this status change..."></textarea>
        </div>
        
        <div id="reopenOption" class="mb-4 hidden">
            <label class="flex items-center">
                <input type="checkbox" id="reopenForDepartment" class="mr-2">
                <span class="text-sm text-gray-700">Reopen ticket for department to make corrections</span>
            </label>
            <p class="text-sm text-gray-500 mt-1">
                If checked, department will regain access to this ticket.
            </p>
        </div>
        
        <div class="flex gap-3">
            <button onclick="closeInternalStatusModal()" 
                class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                Cancel
            </button>
            <button onclick="submitInternalStatusUpdate(<?= $ticket_id ?>)" 
                class="flex-1 px-4 py-2 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium">
                Update Status
            </button>
        </div>
    </div>
</div>

<!-- Internal Status Indicator di Header -->
<div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200 mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="text-sm font-medium text-gray-700 mb-1">Internal Status</h4>
            <div id="internalStatusBadge" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold">
                <!-- Status akan diupdate via JavaScript -->
                <?php 
                $internalStatus = $ticket['internal_status'] ?? 'pending';
                $statusLabels = [
                    'pending' => ['label' => 'Pending', 'color' => 'bg-gray-100 text-gray-800', 'icon' => 'fa-clock'],
                    'review_needed' => ['label' => 'Review Needed', 'color' => 'bg-yellow-100 text-yellow-800', 'icon' => 'fa-search'],
                    'testing' => ['label' => 'Testing', 'color' => 'bg-blue-100 text-blue-800', 'icon' => 'fa-flask'],
                    'approved' => ['label' => 'Approved', 'color' => 'bg-green-100 text-green-800', 'icon' => 'fa-check'],
                    'rejected' => ['label' => 'Rejected', 'color' => 'bg-red-100 text-red-800', 'icon' => 'fa-times'],
                    'reopened' => ['label' => 'Reopened', 'color' => 'bg-purple-100 text-purple-800', 'icon' => 'fa-redo']
                ];
                $currentStatus = $statusLabels[$internalStatus] ?? $statusLabels['pending'];
                ?>
                <i class="fas <?= $currentStatus['icon'] ?> mr-1"></i>
                <?= $currentStatus['label'] ?>
            </div>
            <div id="internalStatusInfo" class="text-xs text-gray-500 mt-1">
                <?php if (!empty($ticket['department_resolved_at'])): ?>
                    Resolved by department on <?= date('F d, Y H:i', strtotime($ticket['department_resolved_at'])) ?>
                <?php endif; ?>
            </div>
        </div>
        <button onclick="openInternalStatusModal()" 
            class="px-4 py-2 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors text-sm font-medium">
            <i class="fas fa-sync-alt mr-2"></i>
            Update Status
        </button>
    </div>
</div>
    
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="<?= base_url('support/ticket_in_progress') ?>" 
                       class="text-secondary hover:text-[#817CB2] transition-colors">
                        <i class="fas fa-arrow-left"></i>
                        Back to In Progress
                    </a>
                </div>
                <h1 class="text-[32px] font-semibold mb-2 text-text-dark">
                    Internal Department Chat
                </h1>
                <p class="text-[15px] font-light text-[#666]">
                    Ticket #<?= $ticket['ticket_number'] ?? $ticket_id ?> • 
                    <?= esc($ticket['subject'] ?? 'No Subject') ?> •
                    <?= esc($ticket['department_name'] ?? 'No Department') ?>
                </p>
                <div class="mt-2 text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    This chat is internal only (Support ↔ Department). Customer messages are not shown here.
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3 mt-4 md:mt-0">
                <a href="<?= base_url('support/ticket_detail/' . $ticket_id) ?>"
                    class="px-4 py-2 bg-white text-secondary border border-secondary rounded-lg hover:bg-secondary/5 transition-all text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-ticket-alt"></i>
                    View Customer Conversation
                </a>
                <!-- REMOVE the duplicate Update Status button here -->
            </div>
        </div>
    </div>

    <!-- Ticket Info Bar -->
    <div class="bg-gradient-to-r from-secondary to-[#8A84C6] rounded-2xl p-6 text-white mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <div class="text-white/80 text-sm mb-1">Ticket Number</div>
                <div class="text-lg font-bold">#<?= $ticket['ticket_number'] ?? $ticket_id ?></div>
            </div>
            <div>
                <div class="text-white/80 text-sm mb-1">Department</div>
                <div class="text-lg font-bold"><?= $ticket['department_name'] ?? 'Not Assigned' ?></div>
            </div>
            <div>
                <div class="text-white/80 text-sm mb-1">Status</div>
                <div class="px-3 py-1 bg-white/20 rounded-full text-sm font-semibold inline-block">
                    <?= strtoupper($ticket['status_name'] ?? 'OPEN') ?>
                </div>
            </div>
            <div>
                <div class="text-white/80 text-sm mb-1">Priority</div>
                <div class="px-3 py-1 bg-red-500/20 rounded-full text-sm font-semibold inline-block">
                    <?= strtoupper($ticket['priority_name'] ?? 'MEDIUM') ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Conversation Section -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Section Header -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800">
                            Internal Discussion
                        </h2>
                        <div class="text-gray-600 text-sm">
                            <i class="fas fa-lock mr-1"></i>
                            <span>Support ↔ Department Only</span>
                        </div>
                    </div>
                    <p class="text-gray-500 text-sm mt-1">
                        Discuss technical details internally. Customer cannot see these messages.
                    </p>
                </div>

                <!-- Conversation Container -->
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
                                        <?php if ($message['role_name'] === 'Support'): ?>
                                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-headset text-green-600"></i>
                                            </div>
                                        <?php else: ?>
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-building text-blue-600"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Message Content -->
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div>
                                                <span class="text-gray-800 font-semibold"><?= esc($message['sender_name'] ?? 'Unknown') ?></span>
                                                <span class="ml-2 px-2 py-0.5 <?= $message['role_name'] === 'Support' ? 'bg-green-50 text-green-700' : 'bg-blue-50 text-blue-700' ?> text-xs rounded">
                                                    <?= $message['role_name'] ?? 'User' ?>
                                                </span>
                                            </div>
                                            <div class="text-gray-500 text-sm ml-auto">
                                                <i class="far fa-clock mr-1"></i>
                                                <?= date('H:i', strtotime($message['created_at'])) ?>
                                            </div>
                                        </div>

                                        <div class="<?= $message['role_name'] === 'Support' ? 'bg-green-50 border-green-100' : 'bg-blue-50 border-blue-100' ?> rounded-xl p-4 border">
                                            <p class="text-gray-700"><?= nl2br(esc($message['message'] ?? '')) ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-comments text-3xl mb-3"></i>
                            <p>No internal discussion yet. Start the conversation!</p>
                            <p class="text-sm text-gray-400 mt-1">
                                Only Support and Department team members can participate.
                            </p>
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

                <!-- Message Input -->
                <div class="p-6 border-t border-gray-200">
                    <div class="flex gap-4">
                        <textarea id="messageInput" placeholder="Type your internal message (customer won't see this)..."
                            class="flex-1 p-4 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 resize-none text-gray-700"
                            rows="3"></textarea>
                        <button id="sendMessageBtn"
                            class="px-6 py-4 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium flex items-center justify-center gap-2">
                            <i class="fas fa-paper-plane"></i>
                            <span class="hidden md:inline">Send</span>
                        </button>
                    </div>
                    <div class="mt-2 text-xs text-gray-500 flex items-center gap-2">
                        <i class="fas fa-lock"></i>
                        <span>This message will only be visible to Support and Department teams</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Participants & Actions -->
        <div class="space-y-6">
            <!-- Participants Card -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Participants</h3>
                
                <!-- Support Team -->
                <div class="mb-4">
                    <div class="text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                        <i class="fas fa-headset text-green-500"></i>
                        <span>Support Team</span>
                    </div>
                    <div class="space-y-2">
                        <?php if (!empty($support_users)): ?>
                            <?php foreach ($support_users as $user): ?>
                                <div class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded">
                                    <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-green-600 text-xs"></i>
                                    </div>
                                    <div class="text-sm">
                                        <div class="font-medium"><?= htmlspecialchars($user['full_name']) ?></div>
                                        <div class="text-gray-500 text-xs">Support</div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-gray-500 text-sm italic">No support team members</div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Department Team -->
                <div>
                    <div class="text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                        <i class="fas fa-building text-blue-500"></i>
                        <span><?= $ticket['department_name'] ?? 'Department' ?> Team</span>
                    </div>
                    <div class="space-y-2">
                        <?php if (!empty($department_users)): ?>
                            <?php foreach ($department_users as $user): ?>
                                <div class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded">
                                    <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600 text-xs"></i>
                                    </div>
                                    <div class="text-sm">
                                        <div class="font-medium"><?= htmlspecialchars($user['full_name']) ?></div>
                                        <div class="text-gray-500 text-xs">Department</div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-gray-500 text-sm italic">No department team members</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-bolt text-secondary"></i>
                    Internal Actions
                </h3>

                <div class="space-y-3">
                    <!-- Update Internal Status -->
                    <button onclick="openInternalStatusModal()" class="w-full px-4 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-sync-alt"></i>
                        <span>Update Status</span>
                    </button>

                    <!-- Add Internal Note -->
                    <button id="addInternalNoteBtn" class="w-full px-4 py-3 bg-white border border-secondary text-secondary rounded-lg hover:bg-secondary/5 transition-colors font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-sticky-note"></i>
                        <span>Add Internal Note</span>
                    </button>

                    <!-- Request Technical Info -->
                    <button id="requestTechInfoBtn" class="w-full px-4 py-3 bg-blue-50 border border-blue-200 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-code"></i>
                        <span>Request Tech Info</span>
                    </button>
                </div>
            </div>

            <!-- Ticket Info Card -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Ticket Details</h3>

                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Customer</span>
                        <span class="font-medium text-gray-800"><?= $ticket['customer_name'] ?? 'Unknown' ?></span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Project</span>
                        <span class="font-medium text-gray-800"><?= $ticket['project_name'] ?? 'No Project' ?></span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Category</span>
                        <span class="font-medium text-gray-800"><?= $ticket['category_name'] ?? 'Unknown' ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Animations untuk modal */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    #internalStatusModal > div {
        animation: slideIn 0.3s ease-out;
    }

    /* Status badge styles */
    .status-badge {
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
    }

    .status-badge i {
        margin-right: 4px;
        font-size: 0.625rem;
    }
    
    /* Auto-resize textarea */
    textarea {
        min-height: 48px;
        max-height: 200px;
        resize: none;
        transition: height 0.2s;
    }

    /* Message animations */
    @keyframes messageSlideIn {
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
        animation: messageSlideIn 0.3s ease-out;
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

    /* Toast notification */
    .toast-notification {
        position: fixed;
        top: 24px;
        right: 24px;
        padding: 12px 16px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 9999;
        max-width: 320px;
        animation: slideIn 0.3s ease-out;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .toast-success {
        background-color: #10b981;
        color: white;
    }

    .toast-error {
        background-color: #ef4444;
        color: white;
    }

    .toast-warning {
        background-color: #f59e0b;
        color: white;
    }

    .toast-info {
        background-color: #3b82f6;
        color: white;
    }
</style>


<script>
// Toast Notification Functions
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

// Internal Status Management
let currentInternalStatus = '<?= $ticket['internal_status'] ?? 'pending' ?>';

// Update di Support view - modal reopen
function openInternalStatusModal() {
    const modal = document.getElementById('internalStatusModal');
    const reopenOption = document.getElementById('reopenOption');
    const statusSelect = document.getElementById('internalStatusSelect');
    const checkboxLabel = document.querySelector('#reopenOption span');
    const checkboxDescription = document.querySelector('#reopenOption p');
    
    // Set current value
    statusSelect.value = currentInternalStatus;
    
    // Show/hide reopen option based on selected status
    statusSelect.addEventListener('change', function() {
        const showReopenOption = this.value === 'reopened';
        reopenOption.classList.toggle('hidden', !showReopenOption);
        
        if (this.value === 'reopened') {
            // Set label dan description yang jelas
            checkboxLabel.textContent = 'Allow department to make corrections';
            checkboxDescription.textContent = 
                'If checked: Department can mark as resolved again. ' +
                'If unchecked: Department can only chat, cannot mark as resolved.';
        }
    });
    
    // Trigger initial check
    const initialShow = statusSelect.value === 'reopened';
    reopenOption.classList.toggle('hidden', !initialShow);
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function fetchInternalMessages() {
    const ticketId = <?= $ticket_id ?>;
    
    // Pastikan URL ini memanggil fungsi getInternalMessages di SupportController
    fetch(`<?= base_url('support/internal_chat/messages/') ?>${ticketId}`, {
        headers: { 
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const container = document.getElementById('conversationContainer');
            if (container) {
                container.innerHTML = '';
                data.messages.forEach(msg => {
                    // Hanya render pesan dari internal_chat_messages untuk internal chat
                    appendInternalMessage(msg);
                });
            }
        }
    });
}

// 🔥 PERBAIKAN: Function appendInternalMessage hanya untuk internal messages
function appendInternalMessage(msg) {
    const container = document.getElementById('conversationContainer');
    if (!container) return;
    
    const isDepartment = msg.sender_role === 'Department' || msg.sender_role === 'Department Member';
    const isSupport = msg.sender_role === 'Support' || msg.role_name === 'Support';
    
    // Hanya tampilkan pesan untuk internal conversation
    if (isDepartment || isSupport || msg.sender_role === 'System') {
        const messageDate = new Date(msg.created_at);
        const formattedDate = messageDate.toLocaleDateString('en-US', { 
            month: 'long', 
            day: 'numeric', 
            year: 'numeric' 
        });
        
        // Create message HTML
        const messageHTML = `
            <div class="flex gap-4 message-item">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 ${isSupport ? 'bg-green-100' : 'bg-blue-100'} rounded-full flex items-center justify-center">
                        ${isSupport ? 
                            '<i class="fas fa-headset text-green-600"></i>' : 
                            '<i class="fas fa-building text-blue-600"></i>'
                        }
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div>
                            <span class="text-gray-800 font-semibold">${escapeHtml(msg.sender_name || 'Unknown')}</span>
                            <span class="ml-2 px-2 py-0.5 ${isSupport ? 'bg-green-50 text-green-700' : 'bg-blue-50 text-blue-700'} text-xs rounded">
                                ${escapeHtml(msg.sender_role || msg.role_name || 'User')}
                            </span>
                        </div>
                        <div class="text-gray-500 text-sm ml-auto">
                            <i class="far fa-clock mr-1"></i>
                            ${messageDate.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}
                        </div>
                    </div>
                    <div class="${isSupport ? 'bg-green-50 border-green-100' : 'bg-blue-50 border-blue-100'} rounded-xl p-4 border">
                        <p class="text-gray-700">${escapeHtml(msg.message || '')}</p>
                        <div class="mt-1 text-xs text-gray-500 flex items-center gap-1">
                            <i class="fas fa-lock text-xs"></i> Internal message
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', messageHTML);
    }
}

// 🔥 PERBAIKAN: Function untuk mengirim pesan internal
function sendInternalMessage() {
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value.trim();
    
    if (!message) {
        showToast('Please enter a message', 'warning');
        return;
    }
    
    // 🔥 PERHATIAN: Kirim dengan flag is_internal = true (untuk internal chat)
    const formData = new FormData();
    formData.append('message', message);
    formData.append('is_internal', 'true'); // 🔥 PENTING: true untuk internal
    
    fetch(`<?= base_url('support/internal_chat/send/') ?><?= $ticket_id ?>`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-RequestedWith': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            messageInput.value = '';
            
            // Tambahkan pesan ke UI
            if (data.data) {
                appendInternalMessage(data.data);
            }
            
            showToast('Internal message sent successfully', 'success');
        } else {
            showToast(data.message || 'Failed to send message', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Network error. Please try again.', 'error');
    });
}

// Helper function
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Event listener untuk send button di internal chat
document.addEventListener('DOMContentLoaded', function() {
    const sendBtn = document.getElementById('sendMessageBtn');
    if (sendBtn) {
        sendBtn.addEventListener('click', function(e) {
            e.preventDefault();
            sendInternalMessage();
        });
    }
    
    // Load internal messages on page load
    fetchInternalMessages();
});

function closeInternalStatusModal() {
    const modal = document.getElementById('internalStatusModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
    
    // Reset form
    document.getElementById('internalStatusNotes').value = '';
    document.getElementById('reopenForDepartment').checked = false;
}

function submitInternalStatusUpdate(ticketId) {
    const status = document.getElementById('internalStatusSelect').value;
    const notes = document.getElementById('internalStatusNotes').value;
    const reopenForDepartment = document.getElementById('reopenForDepartment')?.checked || false;
    
    console.log('Submitting status update:', {
        ticketId,
        status,
        notes,
        reopenForDepartment
    });
    
    // Show loading
    const submitBtn = document.querySelector('#internalStatusModal button[onclick*="submitInternalStatusUpdate"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
    submitBtn.disabled = true;
    
    // Prepare data
    const formData = new FormData();
    formData.append('status', status);
    formData.append('notes', notes);
    formData.append('reopen_for_department', reopenForDepartment);
    
    // Gunakan URL yang benar
    const url = `<?= base_url('support/update_internal_status/') ?>${ticketId}`;
    console.log('URL:', url);
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success) {
            showToast(data.message || 'Status updated successfully', 'success');
            currentInternalStatus = data.status;
            
            // Update internal status display
            updateInternalStatusDisplay(data);
            
            // Close modal
            closeInternalStatusModal();
            
            // 🔥 PERUBAHAN: Jika status approved, show special message dan reload
            if (status === 'approved') {
                showToast('Ticket marked as RESOLVED! Refreshing page...', 'success');
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } 
            // Jika status reopened atau rejected, reload juga
            else if (status === 'reopened' || status === 'rejected') {
                showToast('Refreshing page...', 'info');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            }
        } else {
            showToast(data.message || 'Failed to update status', 'error');
            console.error('Update failed:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Network error. Please try again.', 'error');
    })
    .finally(() => {
        // Reset button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}

function updateInternalStatusDisplay(data) {
    const badge = document.getElementById('internalStatusBadge');
    const info = document.getElementById('internalStatusInfo');
    
    const statusConfig = {
        pending: { 
            color: 'bg-gray-100 text-gray-800', 
            icon: 'fa-clock', 
            label: 'Pending' 
        },
        review_needed: { 
            color: 'bg-yellow-100 text-yellow-800', 
            icon: 'fa-search', 
            label: 'Review Needed' 
        },
        testing: { 
            color: 'bg-blue-100 text-blue-800', 
            icon: 'fa-flask', 
            label: 'Testing' 
        },
        approved: { 
            color: 'bg-green-100 text-green-800', 
            icon: 'fa-check', 
            label: 'Approved' 
        },
        rejected: { 
            color: 'bg-red-100 text-red-800', 
            icon: 'fa-times', 
            label: 'Rejected' 
        },
        reopened: { 
            color: 'bg-purple-100 text-purple-800', 
            icon: 'fa-redo', 
            label: 'Reopened' 
        }
    };
    
    const config = statusConfig[data.status] || statusConfig.pending;
    const statusLabel = data.status_label || config.label;
    
    // Update badge
    badge.innerHTML = `
        <i class="fas ${config.icon} mr-1"></i>
        ${statusLabel}
    `;
    badge.className = `inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold ${config.color}`;
    
    // Update info jika ada data tambahan
    if (info) {
        let infoText = '';
        
        if (data.ticket?.department_resolved_at) {
            const resolvedDate = new Date(data.ticket.department_resolved_at);
            infoText += `Resolved by department on ${resolvedDate.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })} ${resolvedDate.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}`;
        }
        
        if (data.ticket?.last_reopened_at && data.status === 'reopened') {
            const reopenedDate = new Date(data.ticket.last_reopened_at);
            infoText += `Reopened on ${reopenedDate.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}`;
        }
        
        info.innerHTML = infoText || 'Status updated';
    }
}

// Load internal status info on page load
function loadInternalStatusInfo() {
    const ticketId = <?= $ticket_id ?>;
    const url = `<?= base_url('support/internal_status_info/') ?>${ticketId}`;
    
    fetch(url, {
        headers: { 
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateInternalStatusDisplay(data);
        } else {
            console.error('Failed to load status info:', data.message);
        }
    })
    .catch(error => {
        console.error('Error loading status info:', error);
    });
}

// Debug session data
console.log('=== INTERNAL DEPARTMENT CHAT DEBUG ===');
console.log('Ticket ID:', <?= $ticket_id ?>);
console.log('User ID:', <?= session()->get('user_id') ?>);
console.log('User Role:', '<?= session()->get('role_name') ?? session()->get('role') ?? 'Support' ?>');
console.log('Base URL:', '<?= base_url() ?>');
console.log('Current Status:', currentInternalStatus);

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Initializing internal chat...');
    
    const ticketId = <?= $ticket_id ?>;
    const userId = <?= session()->get('user_id') ?>;
    const userRole = '<?= session()->get('role_name') ?? session()->get('role') ?? 'Support' ?>';
    let lastMessageId = 0;
    
    // Initialize
    initInternalChat();
    
    function initInternalChat() {
        console.log('Initializing internal chat for ticket:', ticketId);
        
        // Setup event listeners
        setupEventListeners();
        
        // Load initial messages
        loadInternalMessages();
        
        // Start polling for new messages
        startPolling();
        
        // Scroll to bottom
        setTimeout(scrollToBottom, 500);
        
        // Load internal status info
        loadInternalStatusInfo();
    }
    
    function setupEventListeners() {
        console.log('Setting up event listeners...');
        
        const sendBtn = document.getElementById('sendMessageBtn');
        const messageInput = document.getElementById('messageInput');
        
        if (!sendBtn) {
            console.error('Send button not found!');
            return;
        }
        
        if (!messageInput) {
            console.error('Message input not found!');
            return;
        }
        
        console.log('Elements found:', { sendBtn: !!sendBtn, messageInput: !!messageInput });
        
        // Remove existing listeners
        const newSendBtn = sendBtn.cloneNode(true);
        sendBtn.parentNode.replaceChild(newSendBtn, sendBtn);
        
        // Add new listeners
        document.getElementById('sendMessageBtn').addEventListener('click', handleSendMessage);
        
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                handleSendMessage();
            }
        });
        
        // Setup internal status modal listeners
        const statusSelect = document.getElementById('internalStatusSelect');
        const reopenOption = document.getElementById('reopenOption');
        
        if (statusSelect && reopenOption) {
            statusSelect.addEventListener('change', function() {
                reopenOption.classList.toggle('hidden', this.value !== 'reopened');
            });
        }
        
        console.log('Event listeners setup complete');
    }
    
    function handleSendMessage() {
        console.log('handleSendMessage called');
        
        const messageInput = document.getElementById('messageInput');
        const sendBtn = document.getElementById('sendMessageBtn');
        
        if (!messageInput || !sendBtn) {
            console.error('Required elements not found');
            return;
        }
        
        const message = messageInput.value.trim();
        console.log('Message to send:', message);
        
        if (!message) {
            showToast('Please enter a message', 'warning');
            return;
        }
        
        // Show loading state
        const originalText = sendBtn.innerHTML;
        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
        sendBtn.disabled = true;
        
        // Prepare form data
        const formData = new FormData();
        formData.append('message', message);
        
        console.log('Sending to:', `<?= base_url('support/internal_chat/send/') ?>${ticketId}`);
        console.log('Form data:', { message: message });
        
        // Send request
        fetch(`<?= base_url('support/internal_chat/send/') ?>${ticketId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            if (data.success) {
                // Clear input
                messageInput.value = '';
                
                // Add message to UI immediately
                if (data.data) {
                    addMessageToUI(data.data, true);
                    lastMessageId = data.data.message_id;
                    scrollToBottom();
                }
                
                showToast('Internal message sent successfully', 'success');
                console.log('Message sent successfully');
            } else {
                showToast(data.message || 'Failed to send message', 'error');
                console.error('Send failed:', data.message);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            showToast('Network error. Please try again.', 'error');
        })
        .finally(() => {
            // Reset button
            sendBtn.disabled = false;
            sendBtn.innerHTML = originalText;
        });
    }
    
    function loadInternalMessages() {
        console.log('Loading internal messages...');
        
        fetch(`<?= base_url('support/internal_chat/messages/') ?>${ticketId}`, {
            headers: { 
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Load response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Load response data:', data);
            
            if (data.success && data.messages && data.messages.length > 0) {
                console.log('Messages loaded:', data.messages.length);
                displayMessages(data.messages);
                
                // Update last message ID
                lastMessageId = data.messages[data.messages.length - 1].message_id;
                console.log('Last message ID:', lastMessageId);
                
                scrollToBottom();
            } else if (!data.success) {
                console.error('Failed to load messages:', data.message);
            }
        })
        .catch(error => {
            console.error('Error loading messages:', error);
        });
    }
    
    function checkNewInternalMessages() {
        if (lastMessageId === 0) return;
        
        fetch(`<?= base_url('support/internal_chat/get_new/') ?>${ticketId}?last_message_id=${lastMessageId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.messages && data.messages.length > 0) {
                console.log('New messages found:', data.messages.length);
                
                // Filter out messages from current user (to avoid duplicates)
                const newMessages = data.messages.filter(msg => !msg.is_current_user);
                
                if (newMessages.length > 0) {
                    displayMessages(newMessages);
                    playNotificationSound();
                    scrollToBottom();
                }
                
                // Update last message ID
                if (data.messages.length > 0) {
                    lastMessageId = data.messages[data.messages.length - 1].message_id;
                }
            }
        })
        .catch(error => {
            console.error('Error checking new messages:', error);
        });
    }
    
    function startPolling() {
        // Check for new messages every 3 seconds
        setInterval(checkNewInternalMessages, 3000);
        console.log('Polling started');
    }
    
    function displayMessages(messages) {
        const container = document.getElementById('conversationContainer');
        if (!container) {
            console.error('Conversation container not found!');
            return;
        }
        
        console.log('Displaying', messages.length, 'messages');
        
        messages.forEach(message => {
            // Check if message already exists
            if (container.querySelector(`[data-message-id="${message.message_id}"]`)) {
                return;
            }
            
            addMessageToUI(message, message.is_current_user);
        });
    }
    
    function addMessageToUI(messageData, isCurrentUser) {
        const container = document.getElementById('conversationContainer');
        if (!container) return;
        
        console.log('Adding message to UI:', messageData);
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex gap-4 message-item ${isCurrentUser ? 'justify-end' : ''}`;
        messageDiv.setAttribute('data-message-id', messageData.message_id);
        
        if (isCurrentUser) {
            messageDiv.innerHTML = `
                <div class="flex flex-col max-w-[80%]">
                    <div class="mb-1 text-right">
                        <span class="text-gray-700 font-semibold text-sm">You</span>
                        <span class="ml-2 px-2 py-0.5 bg-green-50 text-green-700 text-xs rounded">
                            ${escapeHtml(messageData.sender_role || messageData.role_name || 'Support')}
                        </span>
                        <span class="ml-2 text-gray-500 text-xs">${messageData.time_ago || 'Just now'}</span>
                    </div>
                    <div class="bg-green-50 border-green-100 rounded-xl p-3 border">
                        <p class="text-gray-800">${escapeHtml(messageData.message)}</p>
                        <div class="mt-1 text-xs text-gray-500 flex items-center gap-1">
                            <i class="fas fa-lock text-xs"></i> Internal message
                        </div>
                    </div>
                </div>
            `;
        } else {
            const isDepartment = (messageData.sender_role === 'Department' || 
                                 messageData.sender_role === 'Department Member' ||
                                 (messageData.role_name && messageData.role_name !== 'Support'));
            const bgColor = isDepartment ? 'bg-blue-50 border-blue-100' : 'bg-green-50 border-green-100';
            const textColor = isDepartment ? 'text-blue-700' : 'text-green-700';
            
            messageDiv.innerHTML = `
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 ${isDepartment ? 'bg-blue-100' : 'bg-green-100'} rounded-full flex items-center justify-center">
                        <i class="fas ${isDepartment ? 'fa-building text-blue-600' : 'fa-headset text-green-600'}"></i>
                    </div>
                </div>
                <div class="flex-1 max-w-[80%]">
                    <div class="mb-1">
                        <span class="text-gray-700 font-semibold text-sm">${escapeHtml(messageData.sender_name || 'Unknown')}</span>
                        <span class="ml-2 px-2 py-0.5 ${bgColor} ${textColor} text-xs rounded">
                            ${escapeHtml(messageData.sender_role || messageData.role_name || 'User')}
                        </span>
                        <span class="ml-2 text-gray-500 text-xs">${messageData.time_ago || 'Just now'}</span>
                    </div>
                    <div class="${bgColor} rounded-xl p-3 border">
                        <p class="text-gray-800">${escapeHtml(messageData.message)}</p>
                        <div class="mt-1 text-xs text-gray-500 flex items-center gap-1">
                            <i class="fas fa-lock text-xs"></i> Internal message
                        </div>
                    </div>
                </div>
            `;
        }
        
        container.appendChild(messageDiv);
    }
    
    function scrollToBottom() {
        const container = document.getElementById('conversationContainer');
        if (container) {
            setTimeout(() => {
                container.scrollTop = container.scrollHeight;
            }, 100);
        }
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    function playNotificationSound() {
        try {
            const audio = new Audio('<?= base_url('assets/notification.mp3') ?>');
            audio.volume = 0.3;
            audio.play().catch(e => console.log('Audio play failed:', e));
        } catch (e) {
            console.log('Could not play sound');
        }
    }
    
    // Initial scroll
    setTimeout(scrollToBottom, 1000);
});
</script>

<?= $this->endSection() ?>