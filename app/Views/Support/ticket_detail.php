<?= $this->extend('layouts/support_layout') ?>

<?= $this->section('title') ?>Ticket #<?= $ticket_id ?> Detail - NEXUS Support<?= $this->endSection() ?>

<?= $this->section('background_effects') ?>
<!-- Background Effects -->
<div class="fixed w-[40vw] h-[40vw] -right-[10%] -bottom-[10%] rotate-[149deg] bg-gradient-to-r from-[rgba(56.96,44.47,127.72,0.15)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-80 z-0"></div>
<div class="fixed w-[35vw] h-[25vw] -left-[5%] top-[10%] rotate-[8deg] bg-gradient-to-r from-[rgba(65.04,45.10,137.14,0.20)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-70 z-0"></div>
<div class="fixed w-[15vw] h-[20vw] right-[5%] -top-[5%] rotate-[8deg] bg-gradient-to-r from-[rgba(16.56,8.41,46.05,0.12)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-60 z-0"></div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mt-[77px] p-[30px] relative z-10">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-[32px] font-semibold mb-2 text-text-dark">Ticket #<?= $ticket_id ?></h1>
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
                        <span>75%</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-secondary rounded-full w-3/4"></div>
                    </div>
                </div>
                <div class="text-sm text-gray-600">
                    <div class="flex items-center gap-2 mb-1">
                        <i class="far fa-clock text-gray-400"></i>
                        <span>Last update: 1 hour ago</span>
                    </div>
                    <div class="text-secondary font-medium">
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
                    <div class="text-gray-600 text-sm">Software & Applications</div>
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
                            <?= count($messages ?? []) ?> messages
                        </div>
                    </div>
                </div>

                <!-- Conversation Container (Scrollable) -->
                <div id="conversationContainer" class="p-6 h-[500px] overflow-y-auto">
                    <!-- Conversation Timeline -->
                    <div class="space-y-6">
                        <!-- Date Header - February 19 -->
                        <div class="text-center">
                            <span class="px-4 py-1 bg-gray-100 text-gray-600 text-sm rounded-full">February 19, 2026</span>
                        </div>

                        <!-- Loop through messages -->
                        <?php if (!empty($messages)): ?>
                            <?php foreach ($messages as $message): ?>
                                <?php
                                $bgClass = 'bg-gray-50';
                                $borderClass = 'border-gray-200';
                                $roleClass = 'bg-blue-50 text-blue-700';

                                if ($message['role_name'] === 'Support Agent' || $message['role_name'] === 'Support Lead') {
                                    $bgClass = 'bg-green-50';
                                    $borderClass = 'border-green-100';
                                    $roleClass = 'bg-green-50 text-green-700';
                                } elseif (strpos($message['full_name'], 'Support') !== false) {
                                    $bgClass = 'bg-green-50';
                                    $borderClass = 'border-green-100';
                                    $roleClass = 'bg-green-50 text-green-700';
                                }

                                $time = date('h:i A', strtotime($message['created_at'] ?? 'now'));
                                ?>
                                <div class="flex gap-4">
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
                                                <span class="text-gray-800 font-semibold"><?= esc($message['full_name']) ?></span>
                                                <span class="ml-2 px-2 py-0.5 <?= $roleClass ?> text-xs rounded">
                                                    <?= esc($message['role_name'] ?? 'User') ?>
                                                </span>
                                            </div>
                                            <div class="text-gray-500 text-sm ml-auto">
                                                <i class="far fa-clock mr-1"></i>
                                                <?= $time ?>
                                            </div>
                                        </div>

                                        <div class="<?= $bgClass ?> rounded-xl p-4 <?= $borderClass ?>">
                                            <p class="text-gray-700"><?= nl2br(esc($message['message'])) ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Fallback messages jika tidak ada data -->
                            <div class="flex gap-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div>
                                            <span class="text-gray-800 font-semibold">John Smith</span>
                                            <span class="ml-2 px-2 py-0.5 bg-blue-50 text-blue-700 text-xs rounded">Customer</span>
                                        </div>
                                        <div class="text-gray-500 text-sm ml-auto">
                                            <i class="far fa-clock mr-1"></i>
                                            11:00 AM
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <p class="text-gray-700">Hi team, I'm having trouble accessing the ProjectX dashboard. Every time I try to log in, I receive an error message that says "Access Denied".</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Date Header - Today -->
                        <div class="text-center mt-8 pt-8 border-t border-gray-200">
                            <span class="px-4 py-1 bg-gray-100 text-gray-600 text-sm rounded-full">Today</span>
                        </div>

                        <!-- Latest Message -->
                        <div id="latestMessage" class="flex gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-headset text-green-600"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div>
                                        <span class="text-gray-800 font-semibold">Support Team</span>
                                        <span class="ml-2 px-2 py-0.5 bg-green-50 text-green-700 text-xs rounded">Support Lead</span>
                                    </div>
                                    <div class="text-gray-500 text-sm ml-auto">
                                        <i class="far fa-clock mr-1"></i>
                                        10:30 AM • 15 min ago
                                    </div>
                                </div>
                                <div class="bg-green-50 rounded-xl p-4 border border-green-100">
                                    <p class="text-gray-700 mb-3">Issue identified and resolved. There was a permission configuration issue on our end. The dashboard should now be accessible.</p>
                                    <div class="flex items-center gap-2 px-3 py-2 bg-white rounded-lg border border-green-200">
                                        <i class="fas fa-check-circle text-green-600"></i>
                                        <span class="text-green-700 text-sm font-medium">Issue marked as resolved</span>
                                    </div>
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
                        <textarea
                            placeholder="Type your message here..."
                            class="w-full h-32 p-4 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 resize-none text-gray-700"
                            rows="4"></textarea>
                        <div class="text-gray-500 text-xs mt-1">
                            Max file size: 10MB • Supports images, PDF, Word, text files
                        </div>
                    </div>

                    <!-- File Attachment -->
                    <div class="flex items-center gap-4">
                        <button id="attachFileBtn" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium flex items-center gap-2">
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
                        <button id="sendReplyBtn" class="px-6 py-3 bg-secondary text-white rounded-lg hover:bg-secondary/90 transition-colors font-medium flex items-center gap-2 flex-1 justify-center">
                            <i class="fas fa-paper-plane"></i>
                            Send Reply
                        </button>
                        <button id="cancelBtn" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium flex-1">
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


                    <!-- Request Info -->
                    <button id="requestInfoBtn" class="w-full px-4 py-3 bg-white border border-secondary text-secondary rounded-lg hover:bg-secondary/5 transition-colors font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-question-circle"></i>
                        <span>Request Info</span>
                    </button>

                    <!-- Di bagian Quick Actions Card - GANTI button Mark as Resolved dengan ini: -->
<!-- Ganti button yang ada dengan ini: -->
<button 
    onclick="handleMarkResolved(<?= $ticket_id ?>)"
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
                        <span class="font-medium text-gray-800">#<?= $ticket_id ?></span>
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
                        <span class="font-medium text-gray-800"><?= date('M d, Y h:i A', strtotime($ticket['updated_at'] ?? 'now')) ?></span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Response Time</span>
                        <span class="font-medium text-green-600">Within SLA</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Assigned To</span>
                        <span class="font-medium text-gray-800"><?= $ticket['assigned_to_name'] ?: 'Unassigned' ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Animations */
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

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    /* Custom scrollbar for conversation container */
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

    /* Message hover effects */
    .bg-gray-50:hover {
        background-color: #f8fafc;
    }

    .bg-green-50:hover {
        background-color: #f0fdf4;
    }

    /* Transition effects */
    .transition-all {
        transition: all 0.2s ease;
    }

    .transition-colors {
        transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
    }

    /* New message animation */
    .new-message {
        animation: slideIn 0.3s ease-out;
    }

    /* Scroll to bottom button */
    #scrollToBottomBtn {
        animation: fadeIn 0.3s ease-out;
    }

    /* Conversation container styling */
    #conversationContainer {
        scroll-behavior: smooth;
    }

    /* Department option selected state */
    .department-option.selected {
        border-color: #756EA4;
        background-color: rgba(117, 110, 164, 0.05);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get conversation container
        const conversationContainer = document.getElementById('conversationContainer');

        // Auto-scroll to latest message when page loads
        setTimeout(() => {
            if (conversationContainer) {
                conversationContainer.scrollTop = conversationContainer.scrollHeight;
            }
        }, 100);

        // Textarea auto-resize
        const textarea = document.querySelector('textarea');
        if (textarea) {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        }

        // File attachment
        const attachBtn = document.getElementById('attachFileBtn');
        const fileInfo = document.getElementById('fileInfo');

        if (attachBtn && fileInfo) {
            attachBtn.addEventListener('click', function() {
                const input = document.createElement('input');
                input.type = 'file';
                input.accept = 'image/*,.pdf,.doc,.docx,.txt';
                input.onchange = function(e) {
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
                            <span class="text-gray-700">${file.name} (${fileSize} MB)</span>
                            <button class="ml-2 text-red-500 hover:text-red-700 remove-file-btn">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;

                        // Add remove file button handler
                        const removeBtn = fileInfo.querySelector('.remove-file-btn');
                        if (removeBtn) {
                            removeBtn.addEventListener('click', function() {
                                fileInfo.innerHTML = 'No files attached';
                            });
                        }
                    }
                };
                input.click();
            });
        }

        // Support action buttons
        const assignToMeBtn = document.getElementById('assignToMeBtn');
        const requestInfoBtn = document.getElementById('requestInfoBtn');
        const markResolvedBtn = document.getElementById('markResolvedBtn');
        const assignDepartmentBtn = document.getElementById('assignDepartmentBtn');

        if (assignToMeBtn) {
            assignToMeBtn.addEventListener('click', function() {
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Assigning...';
                this.disabled = true;

                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-check"></i> Assigned to You';
                    this.classList.remove('bg-secondary', 'hover:bg-[#817CB2]');
                    this.classList.add('bg-green-500', 'hover:bg-green-600');
                    showToast('Ticket assigned to you successfully', 'success');

                    // Update ticket info
                    document.querySelector('.text-secondary.font-medium').textContent = 'Assigned to You';
                    document.querySelectorAll('span:contains("Unassigned")').forEach(span => {
                        if (span.textContent === 'Unassigned') {
                            span.textContent = 'Your Name';
                        }
                    });
                }, 1000);
            });
        }

        if (requestInfoBtn) {
            requestInfoBtn.addEventListener('click', function() {
                if (confirm('Send request for additional information to customer?')) {
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
                    this.disabled = true;

                    setTimeout(() => {
                        this.innerHTML = '<i class="fas fa-check"></i> Request Sent';
                        this.disabled = false;
                        showToast('Request sent to customer', 'success');
                    }, 1000);
                }
            });
        }

        if (markResolvedBtn) {
            markResolvedBtn.addEventListener('click', function() {
                if (confirm('Mark this ticket as resolved?')) {
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Marking...';
                    this.disabled = true;

                    setTimeout(() => {
                        showToast('Ticket marked as resolved', 'success');
                        // Update status in UI
                        document.querySelector('.text-lg.font-bold').textContent = 'Resolved';
                        document.querySelector('.px-3.py-1.bg-white\\/20').textContent = 'RESOLVED';
                        document.querySelector('.px-3.py-1.bg-white\\/20').classList.remove('bg-white/20');
                        document.querySelector('.px-3.py-1.bg-white\\/20').classList.add('bg-green-500');
                    }, 1000);
                }
            });
        }   

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
                
                // Update juga di halaman customer secara real-time (jika ada WebSocket)
                // notifyCustomerTicketClosed(ticketId, statusName);
                
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

// Fungsi untuk update UI
window.updateTicketStatusUI = function(statusName) {
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
    const progressBar = document.querySelector('.h-2 .bg-secondary');
    if (progressBar) {
        progressBar.style.width = '100%';
    }
    
    const completionText = document.querySelector('.flex.justify-between .text-gray-600 span:last-child');
    if (completionText) {
        completionText.textContent = '100%';
    }
};

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
                document.getElementById('assignDepartmentBtn').innerHTML = `Forward to ${deptName}`;
            });
        });

        // Assign to Department button - Redirect ke ticket_in_progress
        if (assignDepartmentBtn) {
            assignDepartmentBtn.addEventListener('click', function() {
                const selectedOption = document.querySelector('.department-option.selected');
                if (!selectedOption) {
                    showToast('Please select a department first', 'error');
                    return;
                }

                const deptName = selectedOption.querySelector('.font-medium').textContent;
                const deptCode = selectedOption.dataset.department;

                if (confirm(`Forward this ticket to ${deptName}? The ticket will be moved to "In Progress".`)) {
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Forwarding...';
                    this.disabled = true;

                    // Simulate API call delay
                    setTimeout(() => {
                        // Show success message
                        showToast(`Ticket forwarded to ${deptName}! Redirecting to In Progress...`, 'success');

                        // Redirect after delay
                        setTimeout(() => {
                            window.location.href = '<?= base_url('support/ticket_in_progress') ?>';
                        }, 1500);

                    }, 1000);
                }
            });
        }

        // Send reply button
        const sendBtn = document.getElementById('sendReplyBtn');
        if (sendBtn) {
            sendBtn.addEventListener('click', function() {
                const message = textarea.value.trim();
                if (!message) {
                    alert('Please write a message before sending');
                    return;
                }

                const isInternalNote = document.getElementById('internalNote').checked;
                const markAsResolved = document.getElementById('markAsResolved').checked;

                // Show loading state
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
                this.disabled = true;

                // Simulate sending delay
                setTimeout(() => {
                    // Reset button
                    this.innerHTML = originalText;
                    this.disabled = false;

                    // Add new message to conversation
                    addNewMessage(message, isInternalNote);

                    // Mark as resolved if checked
                    if (markAsResolved) {
                        markResolvedBtn.click();
                    }

                    // Clear form
                    textarea.value = '';
                    textarea.style.height = 'auto';
                    if (fileInfo) fileInfo.innerHTML = 'No files attached';
                    document.getElementById('internalNote').checked = false;
                    document.getElementById('markAsResolved').checked = false;

                }, 1500);
            });
        }

        // Cancel button
        const cancelBtn = document.getElementById('cancelBtn');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                textarea.value = '';
                textarea.style.height = 'auto';
                if (fileInfo) fileInfo.innerHTML = 'No files attached';
                document.getElementById('internalNote').checked = false;
                document.getElementById('markAsResolved').checked = false;
            });
        }

        // Function to add new message
        function addNewMessage(text, isInternalNote = false) {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });

            // Create new message element
            const newMessage = document.createElement('div');
            newMessage.className = 'flex gap-4 new-message';

            if (isInternalNote) {
                newMessage.innerHTML = `
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-sticky-note text-purple-600"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div>
                            <span class="text-gray-800 font-semibold">Support Team</span>
                            <span class="ml-2 px-2 py-0.5 bg-purple-100 text-purple-700 text-xs rounded">Internal Note</span>
                        </div>
                        <div class="text-gray-500 text-sm ml-auto">
                            <i class="far fa-clock mr-1"></i>
                            ${timeString} • Just now
                        </div>
                    </div>
                    <div class="bg-purple-50 rounded-xl p-4 border border-purple-100">
                        <p class="text-gray-700">${text}</p>
                    </div>
                </div>
            `;
            } else {
                newMessage.innerHTML = `
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-headset text-green-600"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div>
                            <span class="text-gray-800 font-semibold">Support Team</span>
                            <span class="ml-2 px-2 py-0.5 bg-green-50 text-green-700 text-xs rounded">Support Agent</span>
                        </div>
                        <div class="text-gray-500 text-sm ml-auto">
                            <i class="far fa-clock mr-1"></i>
                            ${timeString} • Just now
                        </div>
                    </div>
                    <div class="bg-green-50 rounded-xl p-4 border border-green-100">
                        <p class="text-gray-700">${text}</p>
                    </div>
                </div>
            `;
            }

            // Get conversation timeline container
            const conversationTimeline = conversationContainer.querySelector('.space-y-6');

            // Check if we need to create "Today" section
            let todaySection = conversationTimeline.querySelector('div:has(span:contains("Today"))');

            if (!todaySection) {
                // Create new "Today" section
                todaySection = document.createElement('div');
                todaySection.innerHTML = `
                <div class="text-center mt-8 pt-8 border-t border-gray-200">
                    <span class="px-4 py-1 bg-gray-100 text-gray-600 text-sm rounded-full">Today</span>
                </div>
            `;
                conversationTimeline.appendChild(todaySection);
            }

            // Add message to the bottom of the conversation (after today section)
            conversationTimeline.appendChild(newMessage);

            // Scroll to new message
            setTimeout(() => {
                conversationContainer.scrollTop = conversationContainer.scrollHeight;
            }, 100);
        }

        // Show scroll to bottom button when user scrolls up
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
                    scrollBtn.className = 'fixed bottom-32 right-8 bg-secondary text-white p-3 rounded-full shadow-lg hover:bg-secondary/90 transition-colors z-10';
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

    // Toast notification function
    function showToast(message, type = 'info') {
        // Remove existing toasts
        document.querySelectorAll('.toast-notification').forEach(toast => toast.remove());

        const toast = document.createElement('div');
        toast.className = `toast-notification fixed top-20 right-4 p-4 rounded-lg shadow-lg z-[1000] max-w-sm ${type === 'error' ? 'bg-red-500 text-white' : type === 'success' ? 'bg-green-500 text-white' : 'bg-blue-500 text-white'}`;
        toast.innerHTML = `
        <div class="flex items-center gap-2">
            <i class="fas ${type === 'error' ? 'fa-exclamation-circle' : type === 'success' ? 'fa-check-circle' : 'fa-info-circle'}"></i>
            <span class="text-sm">${message}</span>
        </div>
    `;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>
<?= $this->endSection() ?>