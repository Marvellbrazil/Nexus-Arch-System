<?= $this->extend('layouts/customer_layout') ?>

<?= $this->section('title') ?>Ticket #12345 Detail - NEXUS<?= $this->endSection() ?>

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
                <h1 class="text-[32px] font-semibold mb-2 text-text-dark"><?= $data['ticket']['ticket_number'] ?></h1>
                <p class="text-[15px] text-[#000]"><?= $data['ticket']['subject'] ?></p>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3">
                <a href="<?= base_url('dashboard/my_tickets') ?>"
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
                        <div class="px-3 py-1 bg-white/20 rounded-full text-sm font-semibold"><?= $data['ticket']['status_name'] ?></div>
                    </div>
                </div>
                <div>
                    <div class="text-white/80 text-sm mb-1">Priority</div>
                    <div class="px-3 py-1 bg-red-500/20 rounded-full text-sm font-semibold inline-block"><?= $data['ticket']['priority_name'] ?></div>
                </div>
                <div>
                    <div class="text-white/80 text-sm mb-1">Created</div>
                    <div class="text-lg font-semibold"><?= date('F d, Y', strtotime($data['ticket']['created_at'])) ?></div>
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
                        <span>Last update: <?= date('F d, Y H:i:s', strtotime($data['ticket']['updated_at'])) ?></span>
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
                    <div class="text-gray-800 font-semibold"><?= $data['ticket']['category_name'] ?></div>
                    <div class="text-gray-600 text-sm"><?= $data['ticket']['department_name'] ?></div>
                </div>
            </div>
            <p class="text-gray-600 text-sm">
                Dashboard access problem with error message "Access Denied"
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
                    4 messages
                </div>
            </div>
        </div>

        <!-- Conversation Container (Scrollable) -->
        <div id="conversationContainer" class="p-6 h-[500px] overflow-y-auto">
            <?php foreach ($data['messages'] as $message) : ?>
                <div class="space-y-6">
                <!-- Date Header - February 19 -->
                <div class="text-center">
                    <span class="px-4 py-1 bg-gray-100 text-gray-600 text-sm rounded-full"><?= date('j F Y, H:i', strtotime($message['created_at'])) ?></span>
                </div>

                <!-- Message 1 - Customer (OLDEST) -->
                <div class="flex gap-4">
                    <!-- Avatar -->
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                    </div>

                    <!-- Message Content -->
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div>
                                <span class="text-gray-800 font-semibold"><?= $message['full_name'] ?></span>
                                <span class="ml-2 px-2 py-0.5 bg-blue-50 text-blue-700 text-xs rounded"><?= $message['role_name'] ?></span>
                            </div>
                            <div class="text-gray-500 text-sm ml-auto">
                                <i class="far fa-clock mr-1"></i>
                                <?= date('H:i', strtotime($message['created_at'])) ?>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-gray-700 mb-3">
                                <?= $message['message'] ?>
                            </p>
                            <!-- Attachment -->
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Reply Section -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Add Your Reply</h3>

        <div class="space-y-4">
            <!-- Message Input -->
            <div>
                <textarea placeholder="Type your message here..."
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
                    Cancel
                </button>
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
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
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
            textarea.addEventListener('input', function () {
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
                input.accept = 'image/*,.pdf,.doc,.docx,.txt';
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
                            <span class="text-gray-700">${file.name} (${fileSize} MB)</span>
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

        // Send reply button
        const sendBtn = document.getElementById('sendReplyBtn');
        if (sendBtn) {
            sendBtn.addEventListener('click', function () {
                const message = textarea.value.trim();
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
                    // Reset button
                    this.innerHTML = originalText;
                    this.disabled = false;

                    // Add new message to conversation
                    addNewMessage(message);

                    // Clear form
                    textarea.value = '';
                    textarea.style.height = 'auto';
                    if (fileInfo) fileInfo.innerHTML = 'No files attached';

                }, 1500);
            });
        }

        // Cancel button
        const cancelBtn = document.getElementById('cancelBtn');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function () {
                textarea.value = '';
                textarea.style.height = 'auto';
                if (fileInfo) fileInfo.innerHTML = 'No files attached';
            });
        }

        // Download buttons
        document.querySelectorAll('.download-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const fileName = this.closest('.bg-white')?.querySelector('.text-gray-800')?.textContent || 'file';
                alert(`Downloading ${fileName}...`);
            });
        });

        // Function to add new message
        function addNewMessage(text) {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });

            // Create new message element
            const newMessage = document.createElement('div');
            newMessage.className = 'flex gap-4 new-message';
            newMessage.innerHTML = `
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-blue-600"></i>
                </div>
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <div>
                        <span class="text-gray-800 font-semibold">You</span>
                        <span class="ml-2 px-2 py-0.5 bg-blue-50 text-blue-700 text-xs rounded">Customer</span>
                    </div>
                    <div class="text-gray-500 text-sm ml-auto">
                        <i class="far fa-clock mr-1"></i>
                        ${timeString} • Just now
                    </div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-gray-700">${text}</p>
                </div>
            </div>
        `;

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

        // Export button
        const exportBtn = document.querySelector('button:contains("Export")');
        if (exportBtn) {
            exportBtn.addEventListener('click', function () {
                alert('Exporting conversation...');
            });
        }
    });
</script>
<?= $this->endSection() ?>