<?= $this->extend('layouts/support_layout') ?>

<?= $this->section('title') ?>Department Conversation - Ticket #<?= $ticket_id ?> - NEXUS
Support<?= $this->endSection() ?>

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
<div class="mt-[77px] p-[30px] relative z-10">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-[32px] font-semibold text-text-dark">Department Conversation</h1>
                    <span class="px-3 py-1 bg-secondary/10 text-secondary text-sm font-semibold rounded-full">
                        Ticket #<?= $ticket_id ?>
                    </span>
                </div>
                <p class="text-[15px] font-light text-[#666]">
                    <?= esc($ticket['subject'] ?? 'No Subject') ?> •
                    <span class="font-medium"><?= esc($ticket['department_name'] ?? 'Technical Support') ?></span>
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3">
                <a href="<?= base_url('support/ticket_in_progress') ?>"
                    class="px-4 py-2 bg-white text-secondary border border-secondary rounded-lg hover:bg-secondary/5 transition-all text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Back to In Progress
                </a>
                <a href="<?= base_url('support/ticket_detail/' . $ticket_id) ?>"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-eye"></i>
                    View Customer Ticket
                </a>
                <button onclick="window.print()"
                    class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-all text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-print"></i>
                    Print Conversation
                </button>
            </div>
        </div>
    </div>

    <!-- Ticket Status and Info -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
        <!-- Department Info -->
        <div class="bg-gradient-to-r from-secondary to-[#8A84C6] rounded-2xl p-6 text-white">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <i class="fas fa-building"></i>
                Department Information
            </h3>
            <div class="space-y-4">
                <div>
                    <div class="text-white/80 text-sm mb-1">Department</div>
                    <div class="text-lg font-bold"><?= $ticket['department_name'] ?? 'Technical Support' ?></div>
                </div>
                <div>
                    <div class="text-white/80 text-sm mb-1">Assigned To</div>
                    <div class="text-lg font-semibold"><?= $ticket['assigned_to_name'] ?? 'Technical Team' ?></div>
                </div>
                <div>
                    <div class="text-white/80 text-sm mb-1">Status</div>
                    <div class="flex items-center gap-3">
                        <div class="px-3 py-1 bg-white/20 rounded-full text-sm font-semibold">
                            <?= strtoupper($ticket['status_name'] ?? 'IN PROGRESS') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ticket Info -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 lg:col-span-2">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Ticket Information</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <div class="text-gray-600 text-sm mb-1">Subject</div>
                    <div class="font-medium text-gray-800"><?= $ticket['subject'] ?? 'No Subject' ?></div>
                </div>
                <div>
                    <div class="text-gray-600 text-sm mb-1">Priority</div>
                    <div class="px-3 py-1 bg-red-500/10 text-red-700 text-sm rounded-full font-medium inline-block">
                        <?= strtoupper($ticket['priority_name'] ?? 'URGENT') ?>
                    </div>
                </div>
                <div>
                    <div class="text-gray-600 text-sm mb-1">Customer</div>
                    <div class="font-medium text-gray-800"><?= $ticket['customer_name'] ?? 'John Smith' ?></div>
                </div>
                <div>
                    <div class="text-gray-600 text-sm mb-1">Project</div>
                    <div class="font-medium text-gray-800"><?= $ticket['project_name'] ?? 'Project Alpha' ?></div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <button id="requestUpdateBtn"
                    class="w-full px-4 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium flex items-center justify-center gap-2">
                    <i class="fas fa-sync-alt"></i>
                    Request Update
                </button>
                <button id="escalateBtn"
                    class="w-full px-4 py-3 bg-white border border-red-300 text-red-600 rounded-lg hover:bg-red-50 transition-colors font-medium flex items-center justify-center gap-2">
                    <i class="fas fa-exclamation-triangle"></i>
                    Escalate Ticket
                </button>
                <button id="markResolvedBtn"
                    class="w-full px-4 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors font-medium flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle"></i>
                    Mark as Resolved
                </button>
            </div>
        </div>
    </div>

    <!-- Main Conversation -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
        <!-- Conversation Panel -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Conversation Header -->
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800">Support & Department Conversation</h2>
                        <div class="text-gray-600 text-sm">
                            <i class="far fa-comments mr-1"></i>
                            <?= count($messages ?? []) ?> messages
                        </div>
                    </div>
                    <div class="mt-2 text-sm text-gray-500">
                        Private conversation between Support team and
                        <?= $ticket['department_name'] ?? 'Technical Support' ?> department
                    </div>
                </div>

                <!-- Conversation Container -->
                <div id="conversationContainer" class="p-6 h-[600px] overflow-y-auto">
                    <div class="space-y-6">
                        <?php if (!empty($messages)): ?>
                            <?php foreach ($messages as $message): ?>
                                <?php
                                $bgClass = 'bg-blue-50 border-blue-100';
                                $senderBg = 'bg-blue-100 text-blue-800';
                                $icon = 'fas fa-headset';

                                if ($message['sender_type'] === 'department') {
                                    $bgClass = 'bg-green-50 border-green-100';
                                    $senderBg = 'bg-green-100 text-green-800';
                                    $icon = 'fas fa-building';
                                }
                                ?>
                                <div class="flex gap-4 animate-fade-in">
                                    <!-- Avatar -->
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-10 h-10 <?= $message['sender_type'] === 'support' ? 'bg-blue-100' : 'bg-green-100' ?> rounded-full flex items-center justify-center">
                                            <i
                                                class="<?= $icon ?> <?= $message['sender_type'] === 'support' ? 'text-blue-600' : 'text-green-600' ?>"></i>
                                        </div>
                                    </div>

                                    <!-- Message Content -->
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div>
                                                <span class="text-gray-800 font-semibold"><?= $message['sender_name'] ?></span>
                                                <span class="ml-2 px-2 py-0.5 <?= $senderBg ?> text-xs rounded">
                                                    <?= $message['sender_role'] ?>
                                                </span>
                                            </div>
                                            <div class="text-gray-500 text-sm ml-auto">
                                                <i class="far fa-clock mr-1"></i>
                                                <?= date('h:i A', strtotime($message['created_at'])) ?>
                                            </div>
                                        </div>

                                        <div class="<?= $bgClass ?> rounded-xl p-4 border">
                                            <p class="text-gray-700"><?= nl2br(esc($message['message'])) ?></p>
                                        </div>

                                        <?php if (isset($message['attachments']) && !empty($message['attachments'])): ?>
                                            <div class="mt-2 pl-4">
                                                <?php foreach ($message['attachments'] as $attachment): ?>
                                                    <div class="flex items-center gap-2 p-2 bg-white rounded-lg border border-gray-200">
                                                        <i class="fas fa-paperclip text-gray-400"></i>
                                                        <span class="text-gray-700 text-sm"><?= $attachment['filename'] ?></span>
                                                        <button class="ml-auto text-gray-400 hover:text-secondary">
                                                            <i class="fas fa-download"></i>
                                                        </button>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-comments text-3xl mb-3"></i>
                                <p>No conversation yet. Start the discussion!</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Message Input -->
                <div class="p-6 border-t border-gray-200">
                    <div class="space-y-4">
                        <div>
                            <textarea placeholder="Type your message to the department..."
                                class="w-full h-32 p-4 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 resize-none text-gray-700"
                                rows="4" id="messageInput"></textarea>
                            <div class="text-gray-500 text-xs mt-1">
                                This message will be sent to <?= $ticket['department_name'] ?? 'the department' ?>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
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

                            <div class="flex gap-3">
                                <button id="cancelBtn"
                                    class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                                    Cancel
                                </button>
                                <button id="sendMessageBtn"
                                    class="px-6 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium flex items-center gap-2">
                                    <i class="fas fa-paper-plane"></i>
                                    Send to Department
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="space-y-6">
            <!-- Department Members -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-users text-secondary"></i>
                    Department Members
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-green-600"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">Alex Johnson</div>
                            <div class="text-gray-500 text-xs">Technical Lead</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">Maria Garcia</div>
                            <div class="text-gray-500 text-xs">Database Admin</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-purple-600"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">David Chen</div>
                            <div class="text-gray-500 text-xs">System Engineer</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conversation History -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Activity</h3>
                <div class="space-y-3">
                    <div class="text-sm">
                        <div class="font-medium text-gray-800">Ticket forwarded</div>
                        <div class="text-gray-500 text-xs">Today, 09:00 AM</div>
                    </div>
                    <div class="text-sm">
                        <div class="font-medium text-gray-800">Database access provided</div>
                        <div class="text-gray-500 text-xs">Today, 09:30 AM</div>
                    </div>
                    <div class="text-sm">
                        <div class="font-medium text-gray-800">Issue identified</div>
                        <div class="text-gray-500 text-xs">Today, 10:00 AM</div>
                    </div>
                    <div class="text-sm">
                        <div class="font-medium text-gray-800">Fix implemented</div>
                        <div class="text-gray-500 text-xs">Today, 10:30 AM</div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Links</h3>
                <div class="space-y-2">
                    <a href="<?= base_url('support/ticket_detail/' . $ticket_id) ?>"
                        class="flex items-center gap-2 text-gray-700 hover:text-secondary transition-colors">
                        <i class="fas fa-external-link-alt"></i>
                        <span>Customer Conversation</span>
                    </a>
                    <a href="<?= base_url('support/ticket_summary/' . $ticket_id) ?>"
                        class="flex items-center gap-2 text-gray-700 hover:text-secondary transition-colors">
                        <i class="fas fa-file-alt"></i>
                        <span>Ticket Summary</span>
                    </a>
                    <a href="#" class="flex items-center gap-2 text-gray-700 hover:text-secondary transition-colors">
                        <i class="fas fa-history"></i>
                        <span>Activity Log</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Context -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Customer Context</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="text-gray-600 text-sm mb-1">Original Issue</div>
                <div class="text-gray-800">Database connection errors preventing login</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="text-gray-600 text-sm mb-1">Customer Impact</div>
                <div class="text-gray-800">Unable to access the dashboard for 2 hours</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="text-gray-600 text-sm mb-1">SLA Status</div>
                <div class="flex items-center gap-2">
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Within SLA</span>
                    <span class="text-gray-500 text-xs">1.5 hours remaining</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(20px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }

    .animate-slide-in {
        animation: slideIn 0.3s ease-out;
    }

    /* Custom scrollbar */
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

    /* Message bubbles */
    .message-bubble {
        position: relative;
        max-width: 80%;
    }

    .message-bubble.support::before {
        content: '';
        position: absolute;
        left: -8px;
        top: 12px;
        border: 8px solid transparent;
        border-right-color: #dbeafe;
    }

    .message-bubble.department::before {
        content: '';
        position: absolute;
        right: -8px;
        top: 12px;
        border: 8px solid transparent;
        border-left-color: #dcfce7;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Auto-scroll to bottom of conversation
        const conversationContainer = document.getElementById('conversationContainer');
        setTimeout(() => {
            if (conversationContainer) {
                conversationContainer.scrollTop = conversationContainer.scrollHeight;
            }
        }, 100);

        // Textarea auto-resize
        const messageInput = document.getElementById('messageInput');
        if (messageInput) {
            messageInput.addEventListener('input', function () {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        }

        // File attachment
        const attachBtn = document.getElementById('attachFileBtn');
        const fileInfo = document.getElementById('fileInfo');

        if (attachBtn && fileInfo) {
            attachBtn.addEventListener('click', function () {
                const input = document.createElement('input');
                input.type = 'file';
                input.accept = 'image/*,.pdf,.doc,.docx,.txt,.zip';
                input.onchange = function (e) {
                    if (e.target.files.length > 0) {
                        const file = e.target.files[0];
                        const fileSize = (file.size / (1024 * 1024)).toFixed(2);

                        if (fileSize > 10) {
                            alert('File size exceeds 10MB limit');
                            return;
                        }

                        fileInfo.innerHTML = `
                        <div class="flex items-center gap-2 animate-slide-in">
                            <i class="fas fa-file text-secondary"></i>
                            <span class="text-gray-700 text-sm">${file.name} (${fileSize} MB)</span>
                            <button class="ml-2 text-red-500 hover:text-red-700 remove-file-btn">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;

                        // Add remove file button handler
                        const removeBtn = fileInfo.querySelector('.remove-file-btn');
                        if (removeBtn) {
                            removeBtn.addEventListener('click', function () {
                                fileInfo.innerHTML = 'No files attached';
                            });
                        }
                    }
                };
                input.click();
            });
        }

        // Send message button
        const sendBtn = document.getElementById('sendMessageBtn');
        if (sendBtn && messageInput) {
            sendBtn.addEventListener('click', function () {
                const message = messageInput.value.trim();
                if (!message) {
                    alert('Please write a message before sending');
                    return;
                }

                // Show loading state
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
                this.disabled = true;

                // Simulate sending delay
                setTimeout(() => {
                    // Add new message to conversation
                    addNewMessage(message, 'support');

                    // Reset form
                    messageInput.value = '';
                    messageInput.style.height = 'auto';
                    if (fileInfo) fileInfo.innerHTML = 'No files attached';

                    // Reset button
                    this.innerHTML = originalText;
                    this.disabled = false;

                    // Auto-reply from department after 2 seconds
                    setTimeout(() => {
                        const responses = [
                            "Thanks for the update. We'll review this and get back to you shortly.",
                            "Received. We're currently investigating this issue.",
                            "Message received. Our team will look into this today.",
                            "Thanks for the information. We'll update you within the hour."
                        ];
                        const randomResponse = responses[Math.floor(Math.random() * responses.length)];
                        addNewMessage(randomResponse, 'department');
                    }, 2000);

                }, 1000);
            });
        }

        // Cancel button
        const cancelBtn = document.getElementById('cancelBtn');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function () {
                messageInput.value = '';
                messageInput.style.height = 'auto';
                if (fileInfo) fileInfo.innerHTML = 'No files attached';
            });
        }

        // Action buttons
        const requestUpdateBtn = document.getElementById('requestUpdateBtn');
        const escalateBtn = document.getElementById('escalateBtn');
        const markResolvedBtn = document.getElementById('markResolvedBtn');

        if (requestUpdateBtn) {
            requestUpdateBtn.addEventListener('click', function () {
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Requesting...';
                this.disabled = true;

                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-check"></i> Update Requested';
                    this.classList.remove('bg-secondary', 'hover:bg-[#817CB2]');
                    this.classList.add('bg-green-500', 'hover:bg-green-600');

                    // Add auto-message
                    const autoMessage = "Hi team, could you please provide an update on this ticket? We need to update the customer.";
                    addNewMessage(autoMessage, 'support');

                    showToast('Update request sent to department', 'success');
                }, 1000);
            });
        }

        if (escalateBtn) {
            escalateBtn.addEventListener('click', function () {
                if (confirm('Are you sure you want to escalate this ticket? This will notify department management.')) {
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Escalating...';
                    this.disabled = true;

                    setTimeout(() => {
                        this.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Escalated';

                        // Add escalation message
                        const escalationMessage = "⚠️ TICKET ESCALATED: This ticket has been escalated to department management for urgent attention.";
                        addNewMessage(escalationMessage, 'support');

                        showToast('Ticket escalated successfully', 'warning');
                    }, 1000);
                }
            });
        }

        if (markResolvedBtn) {
            markResolvedBtn.addEventListener('click', function () {
                if (confirm('Mark this ticket as resolved? This will notify the department and customer.')) {
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Marking...';
                    this.disabled = true;

                    setTimeout(() => {
                        this.innerHTML = '<i class="fas fa-check"></i> Resolved';

                        // Add resolution message
                        const resolutionMessage = "✅ TICKET RESOLVED: This ticket has been marked as resolved. Please confirm with the customer.";
                        addNewMessage(resolutionMessage, 'support');

                        showToast('Ticket marked as resolved', 'success');

                        // Update status in UI
                        const statusBadge = document.querySelector('.px-3.py-1.bg-white\\/20');
                        if (statusBadge) {
                            statusBadge.textContent = 'RESOLVED';
                            statusBadge.classList.remove('bg-white/20');
                            statusBadge.classList.add('bg-green-500');
                        }
                    }, 1000);
                }
            });
        }

        // Function to add new message
        function addNewMessage(text, senderType = 'support') {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });

            const senderName = senderType === 'support' ? 'Support Agent' : '<?= $ticket["department_name"] ?? "Department" ?> Team';
            const senderRole = senderType === 'support' ? 'Support' : '<?= $ticket["department_name"] ?? "Department" ?>';
            const bgClass = senderType === 'support' ? 'bg-blue-50 border-blue-100' : 'bg-green-50 border-green-100';
            const senderBg = senderType === 'support' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800';
            const icon = senderType === 'support' ? 'fas fa-headset text-blue-600' : 'fas fa-building text-green-600';
            const avatarBg = senderType === 'support' ? 'bg-blue-100' : 'bg-green-100';

            // Create new message element
            const newMessage = document.createElement('div');
            newMessage.className = 'flex gap-4 animate-fade-in';
            newMessage.innerHTML = `
            <div class="flex-shrink-0">
                <div class="w-10 h-10 ${avatarBg} rounded-full flex items-center justify-center">
                    <i class="${icon}"></i>
                </div>
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <div>
                        <span class="text-gray-800 font-semibold">${senderName}</span>
                        <span class="ml-2 px-2 py-0.5 ${senderBg} text-xs rounded">${senderRole}</span>
                    </div>
                    <div class="text-gray-500 text-sm ml-auto">
                        <i class="far fa-clock mr-1"></i>
                        ${timeString} • Just now
                    </div>
                </div>
                <div class="${bgClass} rounded-xl p-4 border">
                    <p class="text-gray-700">${text}</p>
                </div>
            </div>
        `;

            // Add to conversation
            const conversationTimeline = conversationContainer.querySelector('.space-y-6');
            conversationTimeline.appendChild(newMessage);

            // Scroll to new message
            setTimeout(() => {
                conversationContainer.scrollTop = conversationContainer.scrollHeight;
            }, 100);
        }

        // Show scroll to bottom button when user scrolls up
        if (conversationContainer) {
            conversationContainer.addEventListener('scroll', function () {
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
                    scrollBtn.className = 'fixed bottom-32 right-8 bg-secondary text-white p-3 rounded-full shadow-lg hover:bg-secondary/90 transition-colors z-10';
                    scrollBtn.innerHTML = '<i class="fas fa-chevron-down"></i>';
                    scrollBtn.title = 'Scroll to latest message';

                    scrollBtn.addEventListener('click', function () {
                        conversationContainer.scrollTop = conversationContainer.scrollHeight;
                    });

                    document.body.appendChild(scrollBtn);
                }
            });
        }
    });

    function showToast(message, type = 'info') {
        // Remove existing toasts
        document.querySelectorAll('.toast-notification').forEach(toast => toast.remove());

        const toast = document.createElement('div');
        toast.className = `toast-notification fixed top-24 right-6 p-4 rounded-xl shadow-xl z-[9999] max-w-sm animate-fade-in ${type === 'error' ? 'bg-red-500 text-white border-l-4 border-red-600' :
                type === 'success' ? 'bg-green-500 text-white border-l-4 border-green-600' :
                    type === 'warning' ? 'bg-yellow-500 text-white border-l-4 border-yellow-600' :
                        'bg-blue-500 text-white border-l-4 border-blue-600'
            }`;
        toast.innerHTML = `
        <div class="flex items-center gap-3">
            <i class="fas ${type === 'error' ? 'fa-exclamation-circle text-xl' :
                type === 'success' ? 'fa-check-circle text-xl' :
                    type === 'warning' ? 'fa-exclamation-triangle text-xl' :
                        'fa-info-circle text-xl'
            }"></i>
            <div class="flex-1">
                <p class="font-medium">${message}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-white/80 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;

        document.body.appendChild(toast);

        // Auto remove after 3 seconds
        setTimeout(() => {
            if (toast.parentElement) {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => toast.remove(), 300);
            }
        }, 3000);
    }
</script>
<?= $this->endSection() ?>