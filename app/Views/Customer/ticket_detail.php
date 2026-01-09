<?= $this->extend('layouts/customer_layout') ?>

<?= $this->section('title') ?>Ticket #12345 Detail - NEXUS<?= $this->endSection() ?>

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
                <h1 class="text-[32px] font-semibold mb-2 text-text-dark">Ticket #12345</h1>
                <p class="text-[15px] font-light text-[#666]">Cannot Access Dashboard • ProjectX</p>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex gap-3">
                <a href="<?= base_url('dashboard/my_tickets') ?>" 
                   class="px-4 py-2 bg-white text-secondary border border-secondary rounded-lg hover:bg-secondary/5 transition-all text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Back to Tickets
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
                        <div class="px-3 py-1 bg-white/20 rounded-full text-sm font-semibold">OPEN</div>
                        <div class="text-lg font-bold">In Progress</div>
                    </div>
                </div>
                <div>
                    <div class="text-white/80 text-sm mb-1">Priority</div>
                    <div class="px-3 py-1 bg-red-500/20 rounded-full text-sm font-semibold inline-block">HIGH</div>
                </div>
                <div>
                    <div class="text-white/80 text-sm mb-1">Created</div>
                    <div class="text-lg font-semibold">Feb 19, 2026</div>
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
                    <div class="text-gray-800 font-semibold">Technical Issue</div>
                    <div class="text-gray-600 text-sm">Software & Applications</div>
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
            <!-- Conversation Timeline -->
            <div class="space-y-6">
                <!-- Date Header - February 19 -->
                <div class="text-center">
                    <span class="px-4 py-1 bg-gray-100 text-gray-600 text-sm rounded-full">February 19, 2026</span>
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
                                <span class="text-gray-800 font-semibold">John Smith</span>
                                <span class="ml-2 px-2 py-0.5 bg-blue-50 text-blue-700 text-xs rounded">Customer</span>
                            </div>
                            <div class="text-gray-500 text-sm ml-auto">
                                <i class="far fa-clock mr-1"></i>
                                11:00 AM
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-gray-700 mb-3">
                                Hi team, I'm having trouble accessing the ProjectX dashboard. Every time I try to log in, 
                                I receive an error message that says "Access Denied". I've tried clearing my cache and using 
                                different browsers, but the issue persists.
                            </p>
                            
                            <!-- Attachment -->
                            <div class="flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200">
                                <div class="w-8 h-8 bg-secondary/10 rounded flex items-center justify-center">
                                    <i class="fas fa-image text-secondary"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="text-gray-800 text-sm font-medium">error_screenshot.png</div>
                                    <div class="text-gray-500 text-xs">320 KB • Image</div>
                                </div>
                                <button class="text-gray-400 hover:text-secondary download-btn">
                                    <i class="fas fa-download"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message 2 - Support -->
                <div class="flex gap-4">
                    <!-- Avatar -->
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-headset text-green-600"></i>
                        </div>
                    </div>
                    
                    <!-- Message Content -->
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div>
                                <span class="text-gray-800 font-semibold">Sarah Johnson</span>
                                <span class="ml-2 px-2 py-0.5 bg-green-50 text-green-700 text-xs rounded">Support Agent</span>
                            </div>
                            <div class="text-gray-500 text-sm ml-auto">
                                <i class="far fa-clock mr-1"></i>
                                11:30 AM
                            </div>
                        </div>
                        
                        <div class="bg-green-50 rounded-xl p-4 border border-green-100">
                            <p class="text-gray-700">
                                Thank you for reporting this issue, John. We've received your ticket and will look into it immediately. 
                                Could you please provide your browser version and operating system?
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Date Header - Today -->
                <div class="text-center mt-8 pt-8 border-t border-gray-200">
                    <span class="px-4 py-1 bg-gray-100 text-gray-600 text-sm rounded-full">Today</span>
                </div>

                <!-- Message 3 - Customer -->
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
                                <span class="text-gray-800 font-semibold">John Smith</span>
                                <span class="ml-2 px-2 py-0.5 bg-blue-50 text-blue-700 text-xs rounded">Customer</span>
                            </div>
                            <div class="text-gray-500 text-sm ml-auto">
                                <i class="far fa-clock mr-1"></i>
                                09:15 AM • 1 hour ago
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-gray-700 mb-3">
                                Thanks for the quick response! I'm using Chrome version 120.0.6099.130 on Windows 11. 
                                I've attached the error log file for your reference.
                            </p>
                            
                            <!-- Attachment -->
                            <div class="flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200">
                                <div class="w-8 h-8 bg-secondary/10 rounded flex items-center justify-center">
                                    <i class="fas fa-file-alt text-secondary"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="text-gray-800 text-sm font-medium">error_log.txt</div>
                                    <div class="text-gray-500 text-xs">45 KB • Text File</div>
                                </div>
                                <button class="text-gray-400 hover:text-secondary download-btn">
                                    <i class="fas fa-download"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message 4 - Support (Resolved) - LATEST MESSAGE -->
                <div id="latestMessage" class="flex gap-4">
                    <!-- Avatar -->
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-headset text-green-600"></i>
                        </div>
                    </div>
                    
                    <!-- Message Content -->
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div>
                                <span class="text-gray-800 font-semibold">Michael Chen</span>
                                <span class="ml-2 px-2 py-0.5 bg-green-50 text-green-700 text-xs rounded">Support Lead</span>
                            </div>
                            <div class="text-gray-500 text-sm ml-auto">
                                <i class="far fa-clock mr-1"></i>
                                10:30 AM • 15 min ago
                            </div>
                        </div>
                        
                        <div class="bg-green-50 rounded-xl p-4 border border-green-100">
                            <p class="text-gray-700 mb-3">
                                Issue identified and resolved. There was a permission configuration issue on our end. 
                                The dashboard should now be accessible. Please try logging in again and let us know if you encounter any further issues.
                            </p>
                            
                            <!-- Resolution Badge -->
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
                    rows="4"
                ></textarea>
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
            
            <!-- Action Buttons -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
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
    
    // Send reply button
    const sendBtn = document.getElementById('sendReplyBtn');
    if (sendBtn) {
        sendBtn.addEventListener('click', function() {
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
        cancelBtn.addEventListener('click', function() {
            textarea.value = '';
            textarea.style.height = 'auto';
            if (fileInfo) fileInfo.innerHTML = 'No files attached';
        });
    }
    
    // Download buttons
    document.querySelectorAll('.download-btn').forEach(btn => {
        btn.addEventListener('click', function() {
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
    
    // Export button
    const exportBtn = document.querySelector('button:contains("Export")');
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            alert('Exporting conversation...');
        });
    }
});
</script>
<?= $this->endSection() ?>