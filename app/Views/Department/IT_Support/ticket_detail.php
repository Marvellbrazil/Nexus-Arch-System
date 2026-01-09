<?= $this->extend('layouts/it_support_layout') ?>

<?= $this->section('title') ?>Ticket #<?= $ticket_id ?? '10421' ?> Detail - IT Support<?= $this->endSection() ?>

<?= $this->section('background_effects') ?>
<!-- Background Effects -->
<div class="fixed w-[40vw] h-[40vw] -right-[10%] -bottom-[10%] rotate-[149deg] bg-gradient-to-r from-[rgba(56.96,44.47,127.72,0.15)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-80 z-0"></div>
<div class="fixed w-[35vw] h-[25vw] -left-[5%] top-[10%] rotate-[8deg] bg-gradient-to-r from-[rgba(65.04,45.10,137.14,0.20)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-70 z-0"></div>
<div class="fixed w-[15vw] h-[20vw] right-[5%] -top-[5%] rotate-[8deg] bg-gradient-to-r from-[rgba(16.56,8.41,46.05,0.12)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-60 z-0"></div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mt-4 md:mt-[77px] p-4 md:p-[30px] relative z-10">
    <!-- Page Header -->
    <div class="mb-6 md:mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-2xl md:text-[32px] font-semibold text-text-dark">Ticket #<?= $ticket_id ?? '10421' ?></h1>
                    <div class="px-3 py-1 bg-secondary text-white text-sm font-semibold rounded-full">IT Support</div>
                </div>
                <p class="text-sm md:text-[15px] font-light text-[#666]">Server maintenance required • Project Alpha</p>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3">
                <a href="<?= base_url('department/it-support/assigned_tickets') ?>" 
                   class="px-4 py-2 bg-white text-secondary border border-secondary rounded-lg hover:bg-secondary/5 transition-all text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Back to Assigned
                </a>
                <button id="focusViewBtn" class="px-4 py-2 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-all text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-expand-alt"></i>
                    Focus View
                </button>
                <button class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-all text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-download"></i>
                    Export Log
                </button>
            </div>
        </div>
    </div>

    <!-- Ticket Status -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Ticket Info Card -->
        <div class="bg-gradient-to-r from-secondary to-[#8A84C6] rounded-xl p-6 text-white">
            <h3 class="text-lg font-semibold mb-4">Ticket Information</h3>
            <div class="space-y-4">
                <div>
                    <div class="text-white/80 text-sm mb-1">Status</div>
                    <div class="flex items-center gap-3">
                        <div class="px-3 py-1 bg-white/20 rounded-full text-sm font-semibold">IN PROGRESS</div>
                        <div class="text-lg font-bold">Working on Fix</div>
                    </div>
                </div>
                <div>
                    <div class="text-white/80 text-sm mb-1">Priority</div>
                    <div class="px-3 py-1 bg-red-500/20 rounded-full text-sm font-semibold inline-block">HIGH</div>
                </div>
                <div>
                    <div class="text-white/80 text-sm mb-1">Category</div>
                    <div class="text-lg font-semibold">Server Infrastructure</div>
                </div>
            </div>
        </div>

        <!-- Progress Card -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Progress</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Completion</span>
                        <span>65%</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-secondary rounded-full w-[65%]"></div>
                    </div>
                </div>
                <div class="text-sm text-gray-600">
                    <div class="flex items-center gap-2 mb-1">
                        <i class="far fa-clock text-gray-400"></i>
                        <span>Time spent: 3h 45m</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-user text-gray-400"></i>
                        <span>Assigned to: IT Support Team</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- SLA Card -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">SLA Status</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm text-gray-600">Response Time</span>
                        <span class="text-green-600 font-medium">✓ Within SLA</span>
                    </div>
                    <div class="text-xs text-gray-500">Initial response: 45m ago</div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm text-gray-600">Resolution Time</span>
                        <span class="text-yellow-600 font-medium">⚠ 12h remaining</span>
                    </div>
                    <div class="text-xs text-gray-500">Due: Today, 8:00 PM</div>
                </div>
                <div class="pt-3 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Department</span>
                        <span class="text-blue-600 font-medium">IT Support</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Left Column - Conversation -->
        <div class="lg:col-span-2">
            <!-- Conversation Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
                <!-- Section Header -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800">IT Support Conversation</h2>
                        <div class="text-gray-600 text-sm">
                            <i class="far fa-comments mr-1"></i>
                            6 messages
                        </div>
                    </div>
                </div>

                <!-- Conversation Container -->
                <div id="conversationContainer" class="p-6 h-[500px] overflow-y-auto">
                    <!-- Conversation Timeline -->
                    <div class="space-y-6">
                        <!-- Date Header - Yesterday -->
                        <div class="text-center">
                            <span class="px-4 py-1 bg-gray-100 text-gray-600 text-sm rounded-full">Yesterday</span>
                        </div>

                        <!-- Message 1 - Support Team (Forwarded) -->
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
                                        <span class="text-gray-800 font-semibold">Support Team</span>
                                        <span class="ml-2 px-2 py-0.5 bg-green-50 text-green-700 text-xs rounded">Support Agent</span>
                                    </div>
                                    <div class="text-gray-500 text-sm ml-auto">
                                        <i class="far fa-clock mr-1"></i>
                                        2:30 PM
                                    </div>
                                </div>
                                
                                <div class="bg-green-50 rounded-xl p-4 border border-green-100">
                                    <p class="text-gray-700 mb-3">
                                        <span class="font-semibold">Ticket forwarded to IT Support Department</span><br>
                                        This server issue requires infrastructure expertise. Please review the server logs and error details provided by the customer.
                                    </p>
                                    
                                    <!-- Forward Info -->
                                    <div class="flex items-center gap-2 px-3 py-2 bg-white rounded-lg border border-green-200">
                                        <i class="fas fa-share-alt text-green-600"></i>
                                        <span class="text-green-700 text-sm font-medium">Forwarded from: Technical Support</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Message 2 - IT Support -->
                        <div class="flex gap-4">
                            <!-- Avatar -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-secondary rounded-full flex items-center justify-center">
                                    <i class="fas fa-server text-white"></i>
                                </div>
                            </div>
                            
                            <!-- Message Content -->
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div>
                                        <span class="text-gray-800 font-semibold">IT Support</span>
                                        <span class="ml-2 px-2 py-0.5 bg-secondary/10 text-secondary text-xs rounded">IT Specialist</span>
                                    </div>
                                    <div class="text-gray-500 text-sm ml-auto">
                                        <i class="far fa-clock mr-1"></i>
                                        3:15 PM
                                    </div>
                                </div>
                                
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <p class="text-gray-700 mb-3">
                                        Received the ticket. I've accessed the server logs and identified the issue. It appears to be a memory leak in the application server. 
                                        Need to coordinate with the development team for a potential fix.
                                    </p>
                                    
                                    <!-- Attachment -->
                                    <div class="flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200">
                                        <div class="w-8 h-8 bg-secondary/10 rounded flex items-center justify-center">
                                            <i class="fas fa-file-alt text-secondary"></i>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-gray-800 text-sm font-medium">server_logs_analysis.txt</div>
                                            <div class="text-gray-500 text-xs">128 KB • Log File</div>
                                        </div>
                                        <button class="text-gray-400 hover:text-secondary download-btn">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Date Header - Today -->
                        <div class="text-center mt-8 pt-8 border-t border-gray-200">
                            <span class="px-4 py-1 bg-gray-100 text-gray-600 text-sm rounded-full">Today</span>
                        </div>

                        <!-- Message 3 - IT Support -->
                        <div class="flex gap-4">
                            <!-- Avatar -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-secondary rounded-full flex items-center justify-center">
                                    <i class="fas fa-server text-white"></i>
                                </div>
                            </div>
                            
                            <!-- Message Content -->
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div>
                                        <span class="text-gray-800 font-semibold">Michael Chen</span>
                                        <span class="ml-2 px-2 py-0.5 bg-secondary/10 text-secondary text-xs rounded">IT Support Lead</span>
                                    </div>
                                    <div class="text-gray-500 text-sm ml-auto">
                                        <i class="far fa-clock mr-1"></i>
                                        9:30 AM • 2 hours ago
                                    </div>
                                </div>
                                
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <p class="text-gray-700 mb-3">
                                        I've escalated this to our senior infrastructure engineer. The memory leak appears to be related to the latest deployment. 
                                        We're working on a rollback plan while the development team fixes the issue.
                                    </p>
                                    
                                    <!-- Status Update -->
                                    <div class="flex items-center gap-2 px-3 py-2 bg-white rounded-lg border border-blue-200">
                                        <i class="fas fa-sync-alt text-blue-600"></i>
                                        <span class="text-blue-700 text-sm font-medium">Status updated to: In Progress</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Message 4 - Support Team -->
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
                                        <span class="ml-2 px-2 py-0.5 bg-green-50 text-green-700 text-xs rounded">Support Lead</span>
                                    </div>
                                    <div class="text-gray-500 text-sm ml-auto">
                                        <i class="far fa-clock mr-1"></i>
                                        10:45 AM • 45 min ago
                                    </div>
                                </div>
                                
                                <div class="bg-green-50 rounded-xl p-4 border border-green-100">
                                    <p class="text-gray-700 mb-3">
                                        Thanks for the update. Customer has been notified about the ongoing fix. Please keep us posted on the rollback progress.
                                    </p>
                                    
                                    <!-- Customer Note -->
                                    <div class="text-xs text-gray-600 mt-2">
                                        <i class="fas fa-user-circle mr-1"></i>
                                        Customer notification sent via email
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Message 5 - IT Support (Latest) -->
                        <div id="latestMessage" class="flex gap-4">
                            <!-- Avatar -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-secondary rounded-full flex items-center justify-center">
                                    <i class="fas fa-server text-white"></i>
                                </div>
                            </div>
                            
                            <!-- Message Content -->
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div>
                                        <span class="text-gray-800 font-semibold">IT Support Team</span>
                                        <span class="ml-2 px-2 py-0.5 bg-secondary/10 text-secondary text-xs rounded">Senior Engineer</span>
                                    </div>
                                    <div class="text-gray-500 text-sm ml-auto">
                                        <i class="far fa-clock mr-1"></i>
                                        11:15 AM • 15 min ago
                                    </div>
                                </div>
                                
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <p class="text-gray-700 mb-3">
                                        Rollback completed successfully. Server memory usage has stabilized. Monitoring the situation for the next hour. 
                                        Will provide final update once confirmed stable.
                                    </p>
                                    
                                    <!-- Progress Update -->
                                    <div class="flex items-center gap-2 px-3 py-2 bg-white rounded-lg border border-green-200">
                                        <i class="fas fa-check-circle text-green-600"></i>
                                        <span class="text-green-700 text-sm font-medium">Rollback successful • Memory stabilized</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reply Section -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Add Internal Note</h3>
                
                <div class="space-y-4">
                    <!-- Message Input -->
                    <div>
                        <textarea 
                            placeholder="Add technical notes or updates..."
                            class="w-full h-32 p-4 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 resize-none text-gray-700"
                            rows="4"
                            id="messageInput"
                        ></textarea>
                        <div class="text-gray-500 text-xs mt-1">
                            Internal notes are visible only to IT Support and Support teams
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
                            <input type="checkbox" id="updateStatus" class="rounded text-secondary focus:ring-secondary" checked>
                            <label for="updateStatus" class="text-gray-700 text-sm">Update ticket status</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="notifySupport" class="rounded text-secondary focus:ring-secondary" checked>
                            <label for="notifySupport" class="text-gray-700 text-sm">Notify Support team</label>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex gap-3 pt-4">
                        <button id="sendReplyBtn" class="px-6 py-3 bg-secondary text-white rounded-lg hover:bg-secondary/90 transition-colors font-medium flex items-center gap-2 flex-1 justify-center">
                            <i class="fas fa-paper-plane"></i>
                            Send Internal Note
                        </button>
                        <button id="cancelBtn" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium flex-1">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Ticket Actions & Info -->
        <div class="space-y-6">
            <!-- Ticket Actions Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-secondary/10 to-secondary/5">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-tools text-secondary"></i>
                        Ticket Actions
                    </h2>
                </div>
                
                <div class="p-6">
                    <div class="space-y-3">
                        <!-- Update Status (Redesigned like Support) -->
                        <div class="space-y-3">
                            <label class="text-gray-700 text-sm font-medium">Update Status</label>
                            
                            <!-- Status Options -->
                            <div class="space-y-2">
                                <!-- In Progress Option -->
                                <div class="status-option flex items-center gap-3 p-3 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50" data-status="in-progress">
                                    <div class="w-4 h-4 bg-secondary rounded-full flex items-center justify-center">
                                        <i class="fas fa-plus text-white text-xs"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800">In Progress</p>
                                        <p class="text-gray-500 text-xs">Currently working on this ticket</p>
                                    </div>
                                </div>
                                
                                <!-- Waiting for Information -->
                                <div class="status-option flex items-center gap-3 p-3 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50" data-status="waiting-info">
                                    <div class="w-4 h-4 bg-secondary rounded-full flex items-center justify-center">
                                        <i class="fas fa-plus text-white text-xs"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800">Waiting for Information</p>
                                        <p class="text-gray-500 text-xs">Need more details from support/customer</p>
                                    </div>
                                </div>
                                
                                <!-- Resolved -->
                                <div class="status-option flex items-center gap-3 p-3 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50" data-status="resolved">
                                    <div class="w-4 h-4 bg-secondary rounded-full flex items-center justify-center">
                                        <i class="fas fa-plus text-white text-xs"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800">Resolved</p>
                                        <p class="text-gray-500 text-xs">Issue has been fixed and completed</p>
                                    </div>
                                </div>
                                
                                <!-- On Hold -->
                                <div class="status-option flex items-center gap-3 p-3 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50" data-status="on-hold">
                                    <div class="w-4 h-4 bg-secondary rounded-full flex items-center justify-center">
                                        <i class="fas fa-plus text-white text-xs"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800">On Hold</p>
                                        <p class="text-gray-500 text-xs">Paused due to external dependencies</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="space-y-2 pt-4">
                            <button id="updateTicketBtn" class="w-full px-4 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium">
                                Update Status
                            </button>
                            

                        </div>
                    </div>
                </div>
            </div>

            
        </div>
    </div>
</div>

<style>
    /* Custom styles */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    /* Scrollbar styling */
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
    .message-item:hover {
        background-color: #f8fafc;
    }
    
    /* Status badge animations */
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    /* Card hover effects */
    .card-hover {
        transition: all 0.2s ease;
    }
    
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    /* Focus View Modal */
    .focus-view-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(10px);
        z-index: 1000;
        display: flex;
        flex-direction: column;
        animation: fadeIn 0.3s ease-out;
    }
    
    .focus-view-content {
        flex: 1;
        overflow: hidden;
    }
    
    .focus-view-header {
        background: #3D3C5E;
        padding: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .focus-view-body {
        height: calc(100vh - 60px);
        overflow-y: auto;
        padding: 2rem;
    }
    
    /* Smooth transitions */
    .transition-all {
        transition: all 0.2s ease;
    }
    
    .transition-colors {
        transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
    }
    
    /* Status option selected state */
    .status-option.selected {
        border-color: #756EA4;
        background-color: rgba(117, 110, 164, 0.05);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-scroll to latest message
    const conversationContainer = document.getElementById('conversationContainer');
    if (conversationContainer) {
        setTimeout(() => {
            conversationContainer.scrollTop = conversationContainer.scrollHeight;
        }, 100);
    }
    
    // Textarea auto-resize
    const messageInput = document.getElementById('messageInput');
    if (messageInput) {
        messageInput.addEventListener('input', function() {
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
            input.accept = '.txt,.log,.pdf,.doc,.docx,.png,.jpg';
            input.onchange = function(e) {
                if (e.target.files.length > 0) {
                    const file = e.target.files[0];
                    const fileSize = (file.size / (1024 * 1024)).toFixed(2);
                    
                    if (fileSize > 20) {
                        alert('File size exceeds 20MB limit');
                        return;
                    }
                    
                    fileInfo.innerHTML = `
                        <div class="flex items-center gap-2 animate-fadeIn">
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
    
    // Ticket Actions
    const updateTicketBtn = document.getElementById('updateTicketBtn');
    const markResolvedBtn = document.getElementById('markResolvedBtn');
    
    // Status selection (like department selection in Support)
    document.querySelectorAll('.status-option').forEach(option => {
        option.addEventListener('click', function() {
            // Remove selection from all
            document.querySelectorAll('.status-option').forEach(opt => {
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
            const statusName = this.querySelector('.font-medium').textContent;
            document.getElementById('updateTicketBtn').innerHTML = `Update to ${statusName}`;
        });
    });
    
    // Set default selected status to "In Progress"
    const inProgressOption = document.querySelector('.status-option[data-status="in-progress"]');
    if (inProgressOption) {
        inProgressOption.click();
    }
    
    // Update Ticket Status
    if (updateTicketBtn) {
        updateTicketBtn.addEventListener('click', function() {
            const selectedOption = document.querySelector('.status-option.selected');
            if (!selectedOption) {
                showToast('Please select a status first', 'error');
                return;
            }
            
            const newStatus = selectedOption.dataset.status;
            const statusName = selectedOption.querySelector('.font-medium').textContent;
            
            if (confirm(`Update ticket status to "${statusName}"?`)) {
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
                this.disabled = true;
                
                setTimeout(() => {
                    this.innerHTML = 'Update Status';
                    this.disabled = false;
                    
                    // Update UI
                    const statusBadge = document.querySelector('.px-3.py-1.bg-white\\/20');
                    const statusText = document.querySelector('.text-lg.font-bold');
                    
                    if (statusBadge) {
                        statusBadge.textContent = statusName.toUpperCase();
                        
                        // Change badge color based on status
                        statusBadge.className = 'px-3 py-1 rounded-full text-sm font-semibold inline-block';
                        if (newStatus === 'resolved') {
                            statusBadge.classList.add('bg-green-500', 'text-white');
                        } else if (newStatus === 'in-progress') {
                            statusBadge.classList.add('bg-blue-500', 'text-white');
                        } else if (newStatus === 'waiting-info') {
                            statusBadge.classList.add('bg-yellow-500', 'text-white');
                        } else if (newStatus === 'on-hold') {
                            statusBadge.classList.add('bg-gray-500', 'text-white');
                        } else {
                            statusBadge.classList.add('bg-white/20', 'text-white');
                        }
                    }
                    
                    if (statusText) {
                        statusText.textContent = statusName;
                    }
                    
                    showToast(`Status updated to ${statusName}`, 'success');
                    
                    // Add timeline entry
                    addTimelineEntry('Status updated', `Changed to ${statusName}`);
                    
                }, 1000);
            }
        });
    }
    
    // Mark as Resolved
    if (markResolvedBtn) {
        markResolvedBtn.addEventListener('click', function() {
            if (confirm('Mark this ticket as resolved? This will notify the Support team.')) {
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Marking...';
                this.disabled = true;
                
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-check-circle"></i> Resolved';
                    this.classList.remove('bg-green-500', 'hover:bg-green-600');
                    this.classList.add('bg-green-600', 'hover:bg-green-700');
                    
                    // Update status selection
                    const resolvedOption = document.querySelector('.status-option[data-status="resolved"]');
                    if (resolvedOption) {
                        resolvedOption.click();
                    }
                    
                    // Update UI
                    const statusBadge = document.querySelector('.px-3.py-1.bg-white\\/20');
                    const statusText = document.querySelector('.text-lg.font-bold');
                    
                    if (statusBadge) {
                        statusBadge.textContent = 'RESOLVED';
                        statusBadge.classList.remove('bg-white/20');
                        statusBadge.classList.add('bg-green-500', 'text-white');
                    }
                    
                    if (statusText) {
                        statusText.textContent = 'Resolved';
                    }
                    
                    // Update progress bar
                    const progressBar = document.querySelector('.h-full.bg-secondary.rounded-full');
                    if (progressBar) {
                        progressBar.style.width = '100%';
                    }
                    
                    showToast('Ticket marked as resolved', 'success');
                    
                    // Add timeline entry
                    addTimelineEntry('Resolved', 'Ticket marked as resolved');
                    
                    // Add resolved message to conversation
                    addResolvedMessage();
                    
                }, 1000);
            }
        });
    }
    
    // Send Reply/Internal Note
    const sendReplyBtn = document.getElementById('sendReplyBtn');
    if (sendReplyBtn) {
        sendReplyBtn.addEventListener('click', function() {
            const message = messageInput.value.trim();
            if (!message) {
                alert('Please write a message before sending');
                return;
            }
            
            const updateStatus = document.getElementById('updateStatus').checked;
            const notifySupport = document.getElementById('notifySupport').checked;
            
            // Show loading state
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            this.disabled = true;
            
            // Simulate sending
            setTimeout(() => {
                // Reset button
                this.innerHTML = originalText;
                this.disabled = false;
                
                // Add message to conversation
                addInternalNote(message, notifySupport);
                
                // Clear form
                messageInput.value = '';
                messageInput.style.height = 'auto';
                if (fileInfo) fileInfo.innerHTML = 'No files attached';
                
                // Show success message
                showToast('Internal note added successfully', 'success');
                
            }, 1500);
        });
    }
    
    // Cancel button
    const cancelBtn = document.getElementById('cancelBtn');
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            messageInput.value = '';
            messageInput.style.height = 'auto';
            if (fileInfo) fileInfo.innerHTML = 'No files attached';
            document.getElementById('updateStatus').checked = true;
            document.getElementById('notifySupport').checked = true;
        });
    }
    
    // Download buttons
    document.querySelectorAll('.download-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const fileName = this.closest('.bg-white')?.querySelector('.text-gray-800')?.textContent || 'file';
            alert(`Downloading ${fileName}...`);
        });
    });
    
    // Focus View Button
    const focusViewBtn = document.getElementById('focusViewBtn');
    if (focusViewBtn) {
        focusViewBtn.addEventListener('click', function() {
            openFocusView();
        });
    }
    
    // Utility functions
    function addInternalNote(text, notifySupport = true) {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { 
            hour: '2-digit', 
            minute: '2-digit',
            hour12: true 
        });
        
        const newMessage = document.createElement('div');
        newMessage.className = 'flex gap-4 animate-fadeIn';
        newMessage.innerHTML = `
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-secondary rounded-full flex items-center justify-center">
                    <i class="fas fa-server text-white"></i>
                </div>
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <div>
                        <span class="text-gray-800 font-semibold">IT Support Team</span>
                        <span class="ml-2 px-2 py-0.5 bg-secondary/10 text-secondary text-xs rounded">Internal Note</span>
                    </div>
                    <div class="text-gray-500 text-sm ml-auto">
                        <i class="far fa-clock mr-1"></i>
                        ${timeString} • Just now
                    </div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-gray-700">${text}</p>
                    ${notifySupport ? 
                        '<div class="text-xs text-gray-600 mt-2"><i class="fas fa-bell mr-1"></i>Support team notified</div>' : 
                        ''
                    }
                </div>
            </div>
        `;
        
        // Append to conversation
        const conversationTimeline = document.querySelector('#conversationContainer .space-y-6');
        if (conversationTimeline) {
            conversationTimeline.appendChild(newMessage);
            
            // Scroll to new message
            setTimeout(() => {
                if (conversationContainer) {
                    conversationContainer.scrollTop = conversationContainer.scrollHeight;
                }
            }, 100);
        }
    }
    
    function addResolvedMessage() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { 
            hour: '2-digit', 
            minute: '2-digit',
            hour12: true 
        });
        
        const resolvedMessage = document.createElement('div');
        resolvedMessage.className = 'flex gap-4 animate-fadeIn';
        resolvedMessage.innerHTML = `
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-white"></i>
                </div>
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <div>
                        <span class="text-gray-800 font-semibold">IT Support Team</span>
                        <span class="ml-2 px-2 py-0.5 bg-green-100 text-green-800 text-xs rounded">Resolution</span>
                    </div>
                    <div class="text-gray-500 text-sm ml-auto">
                        <i class="far fa-clock mr-1"></i>
                        ${timeString} • Just now
                    </div>
                </div>
                <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                    <p class="text-gray-700 mb-3">
                        <strong>Ticket marked as resolved.</strong> Server issue has been fixed and all services are running normally.
                    </p>
                    <div class="flex items-center gap-2 px-3 py-2 bg-white rounded-lg border border-green-300">
                        <i class="fas fa-check-circle text-green-600"></i>
                        <span class="text-green-700 text-sm font-medium">Issue resolved successfully</span>
                    </div>
                </div>
            </div>
        `;
        
        // Append to conversation
        const conversationTimeline = document.querySelector('#conversationContainer .space-y-6');
        if (conversationTimeline) {
            conversationTimeline.appendChild(resolvedMessage);
            
            // Scroll to new message
            setTimeout(() => {
                if (conversationContainer) {
                    conversationContainer.scrollTop = conversationContainer.scrollHeight;
                }
            }, 100);
        }
    }
    
    function addTimelineEntry(title, description) {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { 
            hour: '2-digit', 
            minute: '2-digit',
            hour12: true 
        });
        
        const timelineContainer = document.querySelector('.space-y-4');
        if (!timelineContainer) return;
        
        const timelineEntry = document.createElement('div');
        timelineEntry.className = 'flex items-start gap-3 animate-fadeIn';
        timelineEntry.innerHTML = `
            <div class="flex-shrink-0">
                <div class="w-6 h-6 bg-secondary rounded-full flex items-center justify-center">
                    <i class="fas fa-sync-alt text-white text-xs"></i>
                </div>
            </div>
            <div>
                <div class="text-gray-800 font-medium text-sm">${title}</div>
                <div class="text-gray-500 text-xs">${timeString} • ${description}</div>
            </div>
        `;
        
        // Insert before the current status entry
        const currentStatus = timelineContainer.querySelector('.animate-pulse');
        if (currentStatus && currentStatus.parentNode) {
            timelineContainer.insertBefore(timelineEntry, currentStatus.parentNode);
        } else {
            timelineContainer.appendChild(timelineEntry);
        }
    }
    
    function openFocusView() {
        // Create modal
        const modal = document.createElement('div');
        modal.className = 'focus-view-modal';
        modal.innerHTML = `
            <div class="focus-view-header">
                <div class="flex items-center gap-3">
                    <h2 class="text-white text-xl font-bold">Focus View - Ticket #<?= $ticket_id ?? '10421' ?></h2>
                    <span class="px-2 py-1 bg-secondary text-white text-xs rounded">IT Support</span>
                </div>
                <button id="closeFocusView" class="text-white hover:text-gray-300 text-xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="focus-view-body bg-gray-900">
                <div class="max-w-6xl mx-auto">
                    <div class="bg-gray-800 rounded-xl p-6 mb-6">
                        <h3 class="text-white text-lg font-bold mb-2">Server Maintenance Required</h3>
                        <p class="text-gray-300">Project Alpha • Category: Server Infrastructure</p>
                    </div>
                    
                    <!-- Technical Details -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                        <div class="bg-gray-800 rounded-xl p-6">
                            <h4 class="text-white font-bold mb-3">Technical Details</h4>
                            <div class="space-y-2 text-gray-300 text-sm">
                                <div class="flex justify-between">
                                    <span>Server:</span>
                                    <span class="font-medium">SRV-ALPHA-01</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Environment:</span>
                                    <span class="font-medium">Production</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Issue Type:</span>
                                    <span class="text-red-400 font-medium">Memory Leak</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-800 rounded-xl p-6">
                            <h4 class="text-white font-bold mb-3">Ticket Status</h4>
                            <div class="space-y-2 text-gray-300 text-sm">
                                <div class="flex justify-between">
                                    <span>Priority:</span>
                                    <span class="text-red-400 font-medium">High</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Status:</span>
                                    <span class="text-yellow-400 font-medium">In Progress</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>SLA Status:</span>
                                    <span class="text-green-400 font-medium">On Track</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-800 rounded-xl p-6">
                            <h4 class="text-white font-bold mb-3">Quick Actions</h4>
                            <div class="space-y-3">
                                <button class="w-full bg-secondary text-white py-2 rounded-lg text-sm">Update Status</button>
                                <button class="w-full bg-green-600 text-white py-2 rounded-lg text-sm">Mark Resolved</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Conversation in focus view -->
                    <div class="bg-gray-800 rounded-xl p-6">
                        <h4 class="text-white font-bold mb-4">Recent Conversation</h4>
                        <div class="space-y-4 max-h-[300px] overflow-y-auto pr-4">
                            ${conversationContainer ? conversationContainer.innerHTML : ''}
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        document.body.style.overflow = 'hidden';
        
        // Close button
        const closeBtn = modal.querySelector('#closeFocusView');
        closeBtn.addEventListener('click', function() {
            document.body.removeChild(modal);
            document.body.style.overflow = 'auto';
        });
        
        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && document.body.contains(modal)) {
                document.body.removeChild(modal);
                document.body.style.overflow = 'auto';
            }
        });
    }
    
    // Quick Links click handlers
    document.querySelectorAll('.bg-white\\/10.rounded-lg').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const linkText = this.querySelector('span').textContent;
            showToast(`Opening ${linkText}...`, 'info');
        });
    });
});

function showToast(message, type = 'info') {
    // Remove existing toasts
    document.querySelectorAll('.toast-notification').forEach(toast => toast.remove());
    
    const toast = document.createElement('div');
    toast.className = `toast-notification fixed top-24 right-6 p-4 rounded-xl shadow-xl z-[9999] max-w-sm animate-fadeInUp ${type === 'error' ? 'bg-red-500 text-white border-l-4 border-red-600' : type === 'success' ? 'bg-green-500 text-white border-l-4 border-green-600' : 'bg-blue-500 text-white border-l-4 border-blue-600'}`;
    toast.innerHTML = `
        <div class="flex items-center gap-3">
            <i class="fas ${type === 'error' ? 'fa-exclamation-circle text-xl' : type === 'success' ? 'fa-check-circle text-xl' : 'fa-info-circle text-xl'}"></i>
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

// Add CSS for animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
`;
document.head.appendChild(style);
</script>
<?= $this->endSection() ?>