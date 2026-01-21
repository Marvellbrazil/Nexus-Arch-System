<?= $this->extend('layouts/it_support_layout') ?>

<?= $this->section('title') ?>Ticket #<?= $ticket_id ?> - IT Support<?= $this->endSection() ?>

<?= $this->section('background_effects') ?>
<!-- Background Effects -->
<div class="fixed w-[40vw] h-[40vw] -right-[10%] -bottom-[10%] rotate-[149deg] bg-gradient-to-r from-[rgba(56.96,44.47,127.72,0.15)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-80 z-0"></div>
<div class="fixed w-[35vw] h-[25vw] -left-[5%] top-[10%] rotate-[8deg] bg-gradient-to-r from-[rgba(65.04,45.10,137.14,0.20)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-70 z-0"></div>
<div class="fixed w-[15vw] h-[20vw] right-[5%] -top-[5%] rotate-[8deg] bg-gradient-to-r from-[rgba(16.56,8.41,46.05,0.12)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-60 z-0"></div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mt-4 md:mt-[77px] p-4 md:p-[30px] relative z-10"
     data-ticket-id="<?= $ticket_id ?>"
     data-user-id="<?= session()->get('user_id') ?>"
     data-user-role="Department"
     data-department-id="<?= $department_id ?>">
    
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="<?= base_url('department/it-support/assigned_tickets') ?>" 
                       class="text-secondary hover:text-[#817CB2] transition-colors flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        Back to Tickets
                    </a>
                </div>
                <h1 class="text-2xl md:text-[32px] font-semibold mb-2 text-text-dark">
                    Ticket #<?= $ticket['ticket_number'] ?? $ticket_id ?>
                </h1>
                <p class="text-sm md:text-[15px] font-light text-[#666]">
                    <?= esc($ticket['subject'] ?? 'No Subject') ?> • 
                    <?= esc($ticket['project_name'] ?? 'No Project') ?>
                </p>
                <?php if (!empty($ticket['assigned_by_support_name'])): ?>
                    <div class="mt-2 text-sm text-gray-600">
                        <i class="fas fa-user-shield mr-1"></i>
                        Assigned by Support: <?= esc($ticket['assigned_by_support_name']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3 mt-4 md:mt-0">
                <button id="updateStatusBtn" class="px-4 py-2 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-all text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-sync-alt"></i>
                    Update Status
                </button>
                <a href="<?= base_url('department/it-support/ticket_summary/' . $ticket_id) ?>"
                    class="px-4 py-2 bg-white text-secondary border border-secondary rounded-lg hover:bg-secondary/5 transition-all text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-file-alt"></i>
                    View Summary
                </a>
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
            <div>
                <div class="text-white/80 text-sm mb-1">Customer</div>
                <div class="text-lg font-semibold"><?= $ticket['customer_name'] ?? 'Unknown' ?></div>
            </div>
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
                        <h2 class="text-xl font-semibold text-gray-800">Department ↔ Support Chat</h2>
                        <div class="text-gray-600 text-sm flex items-center gap-2">
                            <i class="fas fa-lock"></i>
                            <span>Internal Communication Only</span>
                        </div>
                    </div>
                    <p class="text-gray-500 text-sm mt-1">
                        This chat is between IT Support Department and Support Team only. Customer cannot see these messages.
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
                if ($messageDate != $currentDate):
            ?>
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
                        <p class="text-gray-700 whitespace-pre-wrap"><?= nl2br(esc($message['message'] ?? '')) ?></p>
                        <div class="mt-1 text-xs text-gray-500 flex items-center gap-1">
                            <i class="fas fa-lock text-xs"></i>
                            Internal message
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-comments text-3xl mb-3"></i>
            <p>No messages yet. Start the conversation with Support!</p>
            <p class="text-sm text-gray-400 mt-1">
                This chat is only visible to IT Support Department and Support Team.
            </p>
        </div>
    <?php endif; ?>
</div>

<!-- Message Input -->
<div class="p-6 border-t border-gray-200">
    <div class="flex gap-4">
        <textarea id="messageInput" placeholder="Type your message to Support team..."
            class="flex-1 p-4 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 resize-none text-gray-700"
            rows="1"></textarea>
        <button id="sendMessageBtn"
            class="px-6 py-4 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium flex items-center justify-center gap-2">
            <i class="fas fa-paper-plane"></i>
            <span class="hidden md:inline">Send</span>
        </button>
    </div>
    <div class="mt-2 text-xs text-gray-500 flex items-center gap-2">
        <i class="fas fa-info-circle"></i>
        <span>This message will only be visible to Support team and IT Support Department members</span>
    </div>
</div>
            </div>

            <!-- Ticket Details -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Ticket Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Customer</p>
                        <p class="font-medium"><?= esc($ticket['customer_name']) ?></p>
                        <p class="text-gray-500 text-sm"><?= esc($ticket['customer_email']) ?></p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Project</p>
                        <p class="font-medium"><?= esc($ticket['project_name'] ?? 'No Project') ?></p>
                        <p class="text-gray-500 text-sm"><?= esc($ticket['project_code'] ?? '') ?></p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Category</p>
                        <p class="font-medium"><?= esc($ticket['category_name']) ?></p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Created</p>
                        <p class="font-medium"><?= date('F d, Y H:i', strtotime($ticket['created_at'])) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="space-y-6">
            <!-- Support Team -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-headset text-green-500"></i>
                    <span>Support Team</span>
                </h3>
                <div class="space-y-3">
                    <?php if (!empty($support_users)): ?>
                        <?php foreach ($support_users as $user): ?>
                            <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-green-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800"><?= htmlspecialchars($user['full_name']) ?></p>
                                    <p class="text-gray-500 text-xs">Support Agent</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-gray-500 text-sm">No support team members found</p>
                    <?php endif; ?>
                </div>
            </div>
            

            <!-- Department Members -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-users text-blue-500"></i>
                    <span>IT Support Team</span>
                </h3>
                <div class="space-y-3">
                    <?php if (!empty($department_members)): ?>
                        <?php foreach ($department_members as $member): ?>
                            <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800"><?= htmlspecialchars($member['full_name']) ?></p>
                                    <p class="text-gray-500 text-xs">Department Member</p>
                                </div>
                                <?php if ($member['user_id'] == $ticket['assigned_to']): ?>
                                    <span class="ml-auto px-2 py-1 bg-green-100 text-green-800 text-xs rounded">
                                        Assigned
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-gray-500 text-sm">No department members found</p>
                    <?php endif; ?>
                </div>
            </div>

<!-- Modal Mark as Resolved -->
<div id="markResolvedModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl max-w-md w-full p-6 animate-fadeIn">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Mark Ticket as Resolved</h3>
            <button onclick="closeMarkResolvedModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="mb-4">
            <p class="text-gray-600 mb-3">
                Once marked as resolved, this ticket will be sent to Support for review. 
                You won't be able to access it until Support approves or reopens it.
            </p>
            
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Resolution Notes (Optional)
            </label>
            <textarea id="resolutionNotes" 
                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary/20 focus:border-secondary"
                rows="4" 
                placeholder="Describe what was done to resolve the issue..."></textarea>
        </div>
        
        <div class="flex gap-3">
            <button onclick="closeMarkResolvedModal()" 
                class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                Cancel
            </button>
<button id="confirmMarkResolvedBtn" 
    class="flex-1 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors font-medium">
    <i class="fas fa-check-circle mr-2"></i>
    Mark as Resolved
</button>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
    <div id="quickActionsContainer">
        <?php
        // Determine if can mark as resolved - SESUAI DATABASE
        $canMarkResolved = false;
        $reasonMessage = '';
        
        // LOGIKA: Jika status = 'reopened' atau 'rejected' DAN belum di-resolve
        if (($ticket['internal_status'] === 'reopened' || $ticket['internal_status'] === 'rejected') 
            && $ticket['department_resolved_at'] === null) {
            $canMarkResolved = true;
            $reasonMessage = 'Ticket has been ' . $ticket['internal_status'] . ' by Support';
        } 
        elseif ($ticket['department_resolved_at'] === null && $ticket['internal_status'] === 'pending') {
            $canMarkResolved = true;
            $reasonMessage = 'Ticket not yet resolved';
        }
        ?>
        
        <?php if ($canMarkResolved): ?>
            <button onclick="openMarkResolvedModal()" 
                    class="w-full px-4 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors font-medium flex items-center justify-center gap-2 mb-3">
                <i class="fas fa-check-circle"></i>
                <span>Mark as Resolved</span>
            </button>
            
            <?php if ($ticket['internal_status'] === 'reopened'): ?>
                <div class="mb-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-center gap-2 text-yellow-800">
                        <i class="fas fa-exclamation-circle"></i>
                        <span class="text-sm font-medium">Support reopened this ticket</span>
                    </div>
                    <?php if (!empty($ticket['internal_status_notes'])): ?>
                        <p class="text-sm text-yellow-700 mt-1">
                            <strong>Correction needed:</strong> <?= esc($ticket['internal_status_notes']) ?>
                        </p>
                    <?php endif; ?>
                    <?php if (!empty($ticket['last_reopened_at'])): ?>
                        <p class="text-xs text-yellow-600 mt-1">
                            Reopened on: <?= date('F d, Y H:i', strtotime($ticket['last_reopened_at'])) ?>
                        </p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($ticket['internal_status'] === 'rejected'): ?>
                <div class="mb-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center gap-2 text-red-800">
                        <i class="fas fa-times-circle"></i>
                        <span class="text-sm font-medium">Support rejected the resolution</span>
                    </div>
                    <?php if (!empty($ticket['internal_status_notes'])): ?>
                        <p class="text-sm text-red-700 mt-1">
                            <strong>Reason:</strong> <?= esc($ticket['internal_status_notes']) ?>
                        </p>
                    <?php endif; ?>
                    <?php if (!empty($ticket['last_rejected_at'])): ?>
                        <p class="text-xs text-red-600 mt-1">
                            Rejected on: <?= date('F d, Y H:i', strtotime($ticket['last_rejected_at'])) ?>
                        </p>
                    <?php endif; ?>
                    <p class="text-sm text-red-600 mt-1">Please review and make corrections.</p>
                </div>
            <?php endif; ?>
            
            <?php if ($reasonMessage && ($ticket['internal_status'] !== 'reopened' && $ticket['internal_status'] !== 'rejected')): ?>
                <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center gap-2 text-blue-800">
                        <i class="fas fa-info-circle"></i>
                        <span class="text-sm font-medium"><?= $reasonMessage ?></span>
                    </div>
                </div>
            <?php endif; ?>
            
        <?php else: ?>
            <div class="px-4 py-3 bg-gray-100 text-gray-600 rounded-lg text-center">
                <?php if ($ticket['internal_status'] === 'approved'): ?>
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>Approved by Support</span>
                    <?php if (!empty($ticket['approved_at'])): ?>
                        <p class="text-xs text-gray-500 mt-1">
                            Approved on: <?= date('F d, Y H:i', strtotime($ticket['approved_at'])) ?>
                        </p>
                    <?php endif; ?>
                <?php elseif ($ticket['internal_status'] === 'review_needed'): ?>
                    <i class="fas fa-search mr-2"></i>
                    <span>Under Support Review</span>
                    <?php if (!empty($ticket['department_resolved_at'])): ?>
                        <p class="text-xs text-gray-500 mt-1">
                            Resolved on: <?= date('F d, Y H:i', strtotime($ticket['department_resolved_at'])) ?>
                        </p>
                    <?php endif; ?>
                <?php elseif ($ticket['internal_status'] === 'testing'): ?>
                    <i class="fas fa-flask mr-2"></i>
                    <span>Testing Phase</span>
                <?php else: ?>
                    <i class="fas fa-hourglass-half mr-2"></i>
                    <span>Under Support Review</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
        </div>
    </div>
</div>

<style>
    /* Chat styling */
    #conversationContainer {
        scroll-behavior: smooth;
    }
    
    #conversationContainer::-webkit-scrollbar {
        width: 6px;
    }
    
    #conversationContainer::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    #conversationContainer::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
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
    
    /* Status update modal */
    .status-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s;
    }
    
    .status-modal.active {
        opacity: 1;
        visibility: visible;
    }
    
    .status-modal-content {
        background: white;
        border-radius: 12px;
        padding: 24px;
        max-width: 400px;
        width: 90%;
        transform: translateY(-20px);
        transition: transform 0.3s;
    }
    
    .status-modal.active .status-modal-content {
        transform: translateY(0);
    }
</style>

<script>
    // Real-time status polling
    let statusPollingInterval = null;
    let currentInternalStatus = '<?= $ticket["internal_status"] ?? "pending" ?>';

    function startStatusPolling() {
        // Check status every 10 seconds
        statusPollingInterval = setInterval(checkTicketStatus, 10000);
    }

    function checkTicketStatus() {
        const ticketId = <?= $ticket_id ?>;
        
        // GUNAKAN ENDPOINT YANG BENAR - getTicketStatusInfo
        fetch(`<?= base_url('department/it-support/ticket/status_info/') ?>${ticketId}`, {
            headers: { 
                'X-Requested-With': 'XMLHttpRequest',
                'Cache-Control': 'no-cache'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Status check response:', data);
            
            if (data.success) {
                // Update UI if status changed
                if (data.current_status !== currentInternalStatus) {
                    console.log('Status changed from', currentInternalStatus, 'to', data.current_status);
                    currentInternalStatus = data.current_status;
                    updateQuickActionsUI(data);
                    
                    // Only show toast for specific status changes
                    if (data.current_status === 'reopened' || data.current_status === 'rejected') {
                        showToast('Ticket status updated: ' + data.current_status_label, 'info');
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error checking ticket status:', error);
        });
    }

    function updateQuickActionsUI(data) {
        const container = document.getElementById('quickActionsContainer');
        if (!container) return;
        
        console.log('Updating quick actions UI:', data);
        
        const ticket = data.ticket;
        const canMarkResolved = data.can_mark_resolved;
        const statusInfo = data.status_info;
        
        if (canMarkResolved) {
            container.innerHTML = `
                <button onclick="openMarkResolvedModal()" 
                        class="w-full px-4 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors font-medium flex items-center justify-center gap-2 mb-3">
                    <i class="fas fa-check-circle"></i>
                    <span>Mark as Resolved</span>
                </button>
                
                ${ticket.internal_status === 'reopened' ? `
                    <div class="mb-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-center gap-2 text-yellow-800">
                            <i class="fas fa-exclamation-circle"></i>
                            <span class="text-sm font-medium">Support reopened this ticket</span>
                        </div>
                        ${ticket.internal_status_notes ? `
                            <p class="text-sm text-yellow-700 mt-1">
                                <strong>Correction needed:</strong> ${escapeHtml(ticket.internal_status_notes)}
                            </p>
                        ` : ''}
                        ${ticket.last_reopened_at ? `
                            <p class="text-xs text-yellow-600 mt-1">
                                Reopened on: ${new Date(ticket.last_reopened_at).toLocaleDateString('en-US', { 
                                    month: 'long', 
                                    day: 'numeric', 
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })}
                            </p>
                        ` : ''}
                        <p class="text-sm text-yellow-600 mt-1">Please make the requested corrections and mark as resolved again.</p>
                    </div>
                ` : ''}
                
                ${ticket.internal_status === 'rejected' ? `
                    <div class="mb-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center gap-2 text-red-800">
                            <i class="fas fa-times-circle"></i>
                            <span class="text-sm font-medium">Support rejected the resolution</span>
                        </div>
                        ${ticket.internal_status_notes ? `
                            <p class="text-sm text-red-700 mt-1">
                                <strong>Reason:</strong> ${escapeHtml(ticket.internal_status_notes)}
                            </p>
                        ` : ''}
                        ${ticket.last_rejected_at ? `
                            <p class="text-xs text-red-600 mt-1">
                                Rejected on: ${new Date(ticket.last_rejected_at).toLocaleDateString('en-US', { 
                                    month: 'long', 
                                    day: 'numeric', 
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })}
                            </p>
                        ` : ''}
                        <p class="text-sm text-red-600 mt-1">Please review and make corrections.</p>
                    </div>
                ` : ''}
                
                ${data.reason && (ticket.internal_status !== 'reopened' && ticket.internal_status !== 'rejected') ? `
                    <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center gap-2 text-blue-800">
                            <i class="fas fa-info-circle"></i>
                            <span class="text-sm font-medium">${data.reason}</span>
                        </div>
                    </div>
                ` : ''}
            `;
        } else {
            container.innerHTML = `
                <div class="px-4 py-3 ${statusInfo.color || 'bg-gray-100'} text-gray-600 rounded-lg text-center">
                    ${ticket.internal_status === 'approved' ? `
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>Approved by Support</span>
                        ${ticket.approved_at ? `
                            <p class="text-xs text-gray-500 mt-1">
                                Approved on: ${new Date(ticket.approved_at).toLocaleDateString('en-US', { 
                                    month: 'long', 
                                    day: 'numeric', 
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })}
                            </p>
                        ` : ''}
                    ` : ticket.internal_status === 'review_needed' ? `
                        <i class="fas fa-search mr-2"></i>
                        <span>Under Support Review</span>
                        ${ticket.department_resolved_at ? `
                            <p class="text-xs text-gray-500 mt-1">
                                Resolved on: ${new Date(ticket.department_resolved_at).toLocaleDateString('en-US', { 
                                    month: 'long', 
                                    day: 'numeric', 
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })}
                            </p>
                        ` : ''}
                    ` : ticket.internal_status === 'testing' ? `
                        <i class="fas fa-flask mr-2"></i>
                        <span>Testing Phase</span>
                    ` : `
                        <i class="fas ${statusInfo.icon || 'fa-hourglass-half'} mr-2"></i>
                        <span>${statusInfo.label || 'Under Support Review'}</span>
                    `}
                </div>
            `;
        }
        
        // Juga update status badge di header jika ada
        updateStatusBadgeInHeader(ticket);
    }
    
    function updateStatusBadgeInHeader(ticket) {
        // Update status badge di info bar (jika ada)
        const infoBar = document.querySelector('.bg-gradient-to-r.from-secondary');
        if (infoBar) {
            const statusElements = infoBar.querySelectorAll('div.text-white\\/80');
            statusElements.forEach(element => {
                if (element.textContent.includes('Status') && element.nextElementSibling) {
                    const statusBadge = element.nextElementSibling;
                    const statusLabels = {
                        'pending': 'OPEN',
                        'review_needed': 'UNDER REVIEW',
                        'testing': 'TESTING',
                        'approved': 'APPROVED',
                        'rejected': 'REJECTED',
                        'reopened': 'REOPENED'
                    };
                    
                    const statusText = statusLabels[ticket.internal_status] || 'OPEN';
                    statusBadge.innerHTML = statusText;
                    
                    // Update color berdasarkan status
                    const statusColors = {
                        'pending': 'bg-white/20',
                        'review_needed': 'bg-yellow-500/20 animate-pulse',
                        'testing': 'bg-blue-500/20',
                        'approved': 'bg-green-500/20',
                        'rejected': 'bg-red-500/20',
                        'reopened': 'bg-purple-500/20'
                    };
                    
                    statusBadge.className = `px-3 py-1 ${statusColors[ticket.internal_status] || 'bg-white/20'} rounded-full text-sm font-semibold inline-block`;
                }
            });
        }
    }

    // Utility function for HTML escaping
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Start polling when page loads
    document.addEventListener('DOMContentLoaded', function() {

     // Setup event listener untuk tombol di modal
    const confirmBtn = document.getElementById('confirmMarkResolvedBtn');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            const notes = document.getElementById('resolutionNotes').value;
            const ticketId = <?= $ticket_id ?>;
            
            if (!confirm('Are you sure you want to mark this ticket as resolved? This action cannot be undone.')) {
                return;
            }
            
            // Show loading state
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            this.disabled = true;
            
            fetch(`<?= base_url('department/it-support/ticket/mark_resolved/') ?>${ticketId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `notes=${encodeURIComponent(notes || '')}`
            })
            .then(response => response.json())
            .then(data => {
                console.log('Response:', data);
                
                if (data.success) {
                    // Update UI
                    updateUIAfterResolution(data.ticket_data);
                    showToast(data.message, 'success');
                    
                    // Close modal
                    setTimeout(() => {
                        closeMarkResolvedModal();
                        document.getElementById('resolutionNotes').value = '';
                        this.disabled = false;
                        this.innerHTML = originalText;
                    }, 1000);
                } else {
                    showToast(data.message || 'Error marking as resolved', 'error');
                    this.disabled = false;
                    this.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Network error. Please try again.', 'error');
                this.disabled = false;
                this.innerHTML = originalText;
            });
        });
    }
        console.log('=== DEPARTMENT TICKET SYSTEM INITIALIZED ===');
        console.log('Current internal status:', currentInternalStatus);
        
        const ticketId = <?= $ticket_id ?>;
        const userId = <?= session()->get('user_id') ?>;
        let lastMessageId = 0;
        
        // Initialize systems
        initDepartmentChat();
        initTicketActions();
        
        // Start real-time status polling
        startStatusPolling();
        
        // Also check status on page load
        setTimeout(checkTicketStatus, 1000);
        
        function initTicketActions() {
            console.log('Initializing ticket actions');
            
            // Setup mark as resolved modal
            setupMarkResolvedModal();
        }
        
        function setupMarkResolvedModal() {
            const modal = document.getElementById('markResolvedModal');
            if (!modal) return;
            
            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeMarkResolvedModal();
                }
            });
            
            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal.classList.contains('flex')) {
                    closeMarkResolvedModal();
                }
            });
        }
        
        function submitMarkResolved(ticketId) {
            const notes = document.getElementById('resolutionNotes').value;
            
            if (!confirm('Are you sure you want to mark this ticket as resolved? This action cannot be undone.')) {
                return;
            }
            
            // Show loading state
            const submitBtn = document.querySelector('#markResolvedModal button[onclick*="submitMarkResolved"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            submitBtn.disabled = true;
            
            fetch(`<?= base_url('department/it-support/ticket/mark_resolved/') ?>${ticketId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `notes=${encodeURIComponent(notes || '')}`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                
                if (data.success) {
                    // Update UI immediately
                    updateUIAfterResolution(data.ticket_data);
                    
                    // Show success message
                    showToast(data.message, 'success');
                    
                    // Close modal after delay
                    setTimeout(() => {
                        closeMarkResolvedModal();
                        // Clear notes
                        document.getElementById('resolutionNotes').value = '';
                    }, 1000);
                    
                } else {
                    showToast(data.message || 'Failed to mark as resolved', 'error');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Network error. Please try again.', 'error');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        }
        
        function updateUIAfterResolution(ticketData) {
            console.log('Updating UI with ticket data:', ticketData);
            
            // 1. Update Quick Actions button
            const quickActionsContainer = document.getElementById('quickActionsContainer');
            if (quickActionsContainer) {
                quickActionsContainer.innerHTML = `
                    <div class="px-4 py-3 bg-yellow-100 text-yellow-800 rounded-lg text-center animate-pulse">
                        <i class="fas fa-hourglass-half mr-2"></i>
                        <span>Under Support Review</span>
                        <p class="text-xs text-yellow-600 mt-1">
                            Resolved on: ${new Date().toLocaleDateString('en-US', { 
                                month: 'long', 
                                day: 'numeric', 
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            })}
                        </p>
                    </div>
                `;
            }
            
            // 2. Update status badge in info bar
            updateStatusBadgeInHeader({
                internal_status: 'review_needed',
                department_resolved_at: new Date().toISOString()
            });
            
            // 3. Update Update Status button
            const updateStatusBtn = document.getElementById('updateStatusBtn');
            if (updateStatusBtn) {
                updateStatusBtn.innerHTML = `
                    <i class="fas fa-hourglass-half"></i>
                    <span>Under Review</span>
                `;
                updateStatusBtn.className = 'px-4 py-2 bg-gray-300 text-gray-600 rounded-lg cursor-not-allowed text-sm font-medium flex items-center gap-2';
                updateStatusBtn.disabled = true;
            }
            
            // 4. Add resolved message to chat
            addResolutionMessageToChat();
            
            // 5. Update current internal status
            currentInternalStatus = 'review_needed';
        }
        
        function addResolutionMessageToChat() {
            const userName = '<?= session()->get("full_name") ?>' || 'You';
            const message = `✅ Ticket marked as resolved by ${userName}. Waiting for Support review.`;
            
            const container = document.getElementById('conversationContainer');
            if (container) {
                const messageDiv = document.createElement('div');
                messageDiv.className = 'flex justify-center my-4';
                messageDiv.innerHTML = `
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg px-4 py-3 max-w-md text-center">
                        <div class="flex items-center justify-center gap-2">
                            <i class="fas fa-check-circle text-yellow-600"></i>
                            <span class="text-yellow-800 font-medium text-sm">${message}</span>
                        </div>
                    </div>
                `;
                container.appendChild(messageDiv);
                scrollToBottom();
            }
        }
        
        function showToast(message, type = 'info') {
            // Remove existing toasts
            document.querySelectorAll('.toast-notification').forEach(toast => toast.remove());

            const toast = document.createElement('div');
            toast.className = `toast-notification fixed top-6 right-6 p-4 rounded-xl shadow-lg z-[9999] max-w-sm animate-fadeIn ${type === 'error' ? 'bg-red-500 text-white border-l-4 border-red-600' : type === 'success' ? 'bg-green-500 text-white border-l-4 border-green-600' : 'bg-blue-500 text-white border-l-4 border-blue-600'}`;
            toast.innerHTML = `
                <div class="flex items-center gap-3">
                    <i class="fas ${type === 'error' ? 'fa-exclamation-circle' : type === 'success' ? 'fa-check-circle' : 'fa-info-circle'} text-lg"></i>
                    <span class="text-sm font-medium flex-1">${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white/80 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            document.body.appendChild(toast);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(-20px)';
                    setTimeout(() => toast.remove(), 300);
                }
            }, 5000);
        }
        
        // Chat System Functions
        function initDepartmentChat() {
            console.log('Initializing department chat for ticket:', ticketId);
            
            // Setup event listeners
            setupChatListeners();
            
            // Load initial messages
            loadChatMessages();
            
            // Start polling for new messages
            startPolling();
            
            // Scroll to bottom initially
            setTimeout(scrollToBottom, 500);
        }
        
        function setupChatListeners() {
            const sendBtn = document.getElementById('sendMessageBtn');
            const messageInput = document.getElementById('messageInput');
            
            if (!sendBtn || !messageInput) return;
            
            sendBtn.addEventListener('click', handleSendMessage);
            
            messageInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    handleSendMessage();
                }
            });
            
            messageInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        }
        
        function handleSendMessage() {
            const messageInput = document.getElementById('messageInput');
            const sendBtn = document.getElementById('sendMessageBtn');
            
            if (!messageInput || !sendBtn) return;
            
            const message = messageInput.value.trim();
            
            if (!message) {
                showToast('Please enter a message', 'error');
                return;
            }
            
            const originalText = sendBtn.innerHTML;
            sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            sendBtn.disabled = true;
            
            fetch(`<?= base_url('department/chat/send/') ?>${ticketId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `message=${encodeURIComponent(message)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageInput.value = '';
                    messageInput.style.height = 'auto';
                    
                    if (data.data) {
                        addMessageToUI(data.data, true);
                        lastMessageId = data.data.message_id;
                        scrollToBottom();
                    }
                    
                    showToast('Message sent successfully', 'success');
                } else {
                    showToast(data.message || 'Failed to send message', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Network error. Please try again.', 'error');
            })
            .finally(() => {
                sendBtn.innerHTML = originalText;
                sendBtn.disabled = false;
            });
        }
        
        function loadChatMessages() {
            fetch(`<?= base_url('department/chat/messages/') ?>${ticketId}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.messages && data.messages.length > 0) {
                    data.messages.forEach(message => {
                        addMessageToUI(message, message.is_current_user);
                    });
                    lastMessageId = data.messages[data.messages.length - 1].message_id;
                    scrollToBottom();
                }
            })
            .catch(error => {
                console.error('Error loading messages:', error);
            });
        }
        
        function getNewMessages() {
            if (lastMessageId === 0) return;
            
            fetch(`<?= base_url('department/chat/get_new/') ?>${ticketId}?last_message_id=${lastMessageId}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.messages && data.messages.length > 0) {
                    const newMessages = data.messages.filter(msg => !msg.is_current_user);
                    
                    if (newMessages.length > 0) {
                        newMessages.forEach(msg => {
                            addMessageToUI(msg, false);
                        });
                        scrollToBottom();
                    }
                    
                    lastMessageId = data.messages[data.messages.length - 1].message_id;
                }
            })
            .catch(error => {
                console.error('Error getting new messages:', error);
            });
        }
        
        function startPolling() {
            setInterval(getNewMessages, 3000);
        }
        
        function addMessageToUI(messageData, isCurrentUser) {
            const container = document.getElementById('conversationContainer');
            if (!container) return;
            
            if (container.querySelector(`[data-message-id="${messageData.message_id}"]`)) {
                return;
            }
            
            const messageDiv = document.createElement('div');
            messageDiv.className = `flex gap-4 message-item ${isCurrentUser ? 'justify-end' : ''}`;
            messageDiv.setAttribute('data-message-id', messageData.message_id);
            
            if (isCurrentUser) {
                messageDiv.innerHTML = `
                    <div class="flex flex-col max-w-[80%]">
                        <div class="mb-1 text-right">
                            <span class="text-gray-700 font-semibold text-sm">You</span>
                            <span class="ml-2 px-2 py-0.5 bg-blue-50 text-blue-700 text-xs rounded">
                                ${escapeHtml(messageData.sender_role || 'Department')}
                            </span>
                            <span class="ml-2 text-gray-500 text-xs">${messageData.time_ago || 'Just now'}</span>
                        </div>
                        <div class="bg-blue-50 border-blue-100 rounded-xl p-4 border">
                            <p class="text-gray-800 whitespace-pre-wrap">${escapeHtml(messageData.message)}</p>
                            <div class="mt-1 text-xs text-gray-500 flex items-center gap-1">
                                <i class="fas fa-lock text-xs"></i> Internal message
                            </div>
                        </div>
                    </div>
                `;
            } else {
                const bgClass = messageData.sender_role === 'Support' ? 'bg-green-50 border-green-100' : 'bg-blue-50 border-blue-100';
                const iconClass = messageData.sender_role === 'Support' ? 'fa-headset text-green-600' : 'fa-building text-blue-600';
                const avatarBg = messageData.sender_role === 'Support' ? 'bg-green-100' : 'bg-blue-100';
                
                messageDiv.innerHTML = `
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 ${avatarBg} rounded-full flex items-center justify-center">
                            <i class="fas ${iconClass}"></i>
                        </div>
                    </div>
                    <div class="flex-1 max-w-[80%]">
                        <div class="mb-1">
                            <span class="text-gray-700 font-semibold text-sm">${escapeHtml(messageData.sender_name)}</span>
                            <span class="ml-2 px-2 py-0.5 ${messageData.sender_role === 'Support' ? 'text-green-700 bg-green-50' : 'text-blue-700 bg-blue-50'} text-xs rounded">
                                ${escapeHtml(messageData.sender_role)}
                            </span>
                            <span class="ml-2 text-gray-500 text-xs">${messageData.time_ago || 'Just now'}</span>
                        </div>
                        <div class="${bgClass} rounded-xl p-4 border">
                            <p class="text-gray-800 whitespace-pre-wrap">${escapeHtml(messageData.message)}</p>
                            <div class="mt-1 text-xs text-gray-500 flex items-center gap-1">
                                <i class="fas fa-lock text-xs"></i> Internal message
                            </div>
                        </div>
                    </div>
                `;
            }
            
            container.appendChild(messageDiv);
            messageDiv.style.animation = 'slideIn 0.3s ease-out';
        }
        
        function scrollToBottom() {
            const container = document.getElementById('conversationContainer');
            if (container) {
                setTimeout(() => {
                    container.scrollTop = container.scrollHeight;
                }, 100);
            }
        }
        
        console.log('Ticket system ready');
    });

    // Clean up on page unload
    window.addEventListener('beforeunload', function() {
        if (statusPollingInterval) {
            clearInterval(statusPollingInterval);
        }
    });

    // Global functions for modal
    function openMarkResolvedModal() {
        const modal = document.getElementById('markResolvedModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeMarkResolvedModal() {
        const modal = document.getElementById('markResolvedModal');
        if (modal) {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('resolutionNotes').value = '';
        }
    }
    
    function submitMarkResolved(ticketId) {
        // This is handled by the inner function in DOMContentLoaded
        console.log('submitMarkResolved called for ticket:', ticketId);
    }
</script>
<?= $this->endSection() ?>