<?= $this->extend('layouts/it_support_layout') ?>

<?= $this->section('title') ?>Ticket #<?= $ticket['ticket_number'] ?? 'Unknown' ?> Detail - IT Support<?= $this->endSection() ?>

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
<div class="mt-4 md:mt-[77px] p-4 md:p-[30px] relative z-10">
    <!-- Page Header -->
    <div class="mb-6 md:mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-2xl md:text-[32px] font-semibold text-text-dark">Ticket #<?= esc($ticket['ticket_number'] ?? 'Unknown') ?></h1>
                    <div class="px-3 py-1 bg-secondary text-white text-sm font-semibold rounded-full">IT Support</div>
                </div>
                <p class="text-sm md:text-[15px] font-light text-[#666]">
                    <?= esc($ticket['category_name'] ?? 'No category') ?> • 
                    <?= esc($ticket['project_name'] ?? 'No project') ?>
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3">
                <a href="<?= base_url('department/it-support/assigned_tickets') ?>"
                    class="px-4 py-2 bg-white text-secondary border border-secondary rounded-lg hover:bg-secondary/5 transition-all text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Back to Assigned
                </a>
                <button id="focusViewBtn"
                    class="px-4 py-2 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-all text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-expand-alt"></i>
                    Focus View
                </button>
                <a href="<?= base_url('department/it-support/ticket_summary/' . ($ticket['ticket_id'] ?? '')) ?>"
                    class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-all text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-file-alt"></i>
                    View Summary
                </a>
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
                        <div class="px-3 py-1 bg-white/20 rounded-full text-sm font-semibold">
                            <?= strtoupper($ticket['status_name'] ?? 'UNKNOWN') ?>
                        </div>
                        <div class="text-lg font-bold">
                            <?= 
                                ($ticket['status_id'] ?? 1) == 2 ? 'Working on Fix' : 
                                (($ticket['status_id'] ?? 1) == 3 ? 'Resolved' : 
                                (($ticket['status_id'] ?? 1) == 4 ? 'Closed' : 'Open')) 
                            ?>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="text-white/80 text-sm mb-1">Priority</div>
                    <div class="px-3 py-1 <?= 
                        ($ticket['priority_id'] ?? 1) >= 3 ? 'bg-red-500/20' : 
                        (($ticket['priority_id'] ?? 1) == 2 ? 'bg-yellow-500/20' : 'bg-blue-500/20') 
                    ?> rounded-full text-sm font-semibold inline-block">
                        <?= strtoupper($ticket['priority_name'] ?? 'NORMAL') ?>
                    </div>
                </div>
                <div>
                    <div class="text-white/80 text-sm mb-1">Category</div>
                    <div class="text-lg font-semibold"><?= esc($ticket['category_name'] ?? 'Uncategorized') ?></div>
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
                        <span>
                            <?= 
                                ($ticket['status_id'] ?? 1) == 1 ? '0%' : 
                                (($ticket['status_id'] ?? 1) == 2 ? '65%' : 
                                (($ticket['status_id'] ?? 1) == 3 ? '100%' : '100%')) 
                            ?>
                        </span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-secondary rounded-full" 
                             style="width: <?= 
                                ($ticket['status_id'] ?? 1) == 1 ? '0%' : 
                                (($ticket['status_id'] ?? 1) == 2 ? '65%' : 
                                (($ticket['status_id'] ?? 1) == 3 ? '100%' : '100%')) 
                             ?>"></div>
                    </div>
                </div>
                <div class="text-sm text-gray-600">
                    <div class="flex items-center gap-2 mb-1">
                        <i class="far fa-clock text-gray-400"></i>
                        <span>
                            Created: <?= date('M d, Y H:i', strtotime($ticket['created_at'] ?? 'now')) ?>
                        </span>
                    </div>
                    <?php if (!empty($ticket['assigned_to_name'])): ?>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-user text-gray-400"></i>
                        <span>Assigned to: <?= esc($ticket['assigned_to_name']) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($ticket['due_date'])): ?>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-calendar-day text-gray-400"></i>
                        <span>Due: <?= date('M d, Y', strtotime($ticket['due_date'])) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- SLA Card -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">SLA Status</h3>
            <div class="space-y-4">
                <div>
                    <?php 
                    $firstResponseTime = !empty($ticket['first_response_at']) ? 
                        (strtotime($ticket['first_response_at']) - strtotime($ticket['created_at'])) / 60 : null;
                    $isResponseWithinSLA = $firstResponseTime !== null && $firstResponseTime <= 60; // 60 minutes SLA
                    ?>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm text-gray-600">Response Time</span>
                        <span class="<?= $isResponseWithinSLA ? 'text-green-600' : 'text-red-600' ?> font-medium">
                            <?= $isResponseWithinSLA ? '✓ Within SLA' : '✗ Exceeded SLA' ?>
                        </span>
                    </div>
                    <div class="text-xs text-gray-500">
                        <?php if ($firstResponseTime !== null): ?>
                            Initial response: <?= round($firstResponseTime) ?>m after creation
                        <?php else: ?>
                            No response yet
                        <?php endif; ?>
                    </div>
                </div>
                <div>
                    <?php 
                    $dueDate = !empty($ticket['due_date']) ? strtotime($ticket['due_date']) : null;
                    $now = time();
                    $isResolutionOnTrack = $dueDate === null || $dueDate > $now;
                    $timeRemaining = $dueDate !== null ? ($dueDate - $now) / 3600 : null;
                    ?>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm text-gray-600">Resolution Time</span>
                        <span class="<?= 
                            $isResolutionOnTrack && $timeRemaining !== null && $timeRemaining > 0 ? 
                            'text-yellow-600' : 'text-red-600' 
                        ?> font-medium">
                            <?= 
                                $timeRemaining !== null && $timeRemaining > 0 ? 
                                "⚠ " . round($timeRemaining) . "h remaining" : 
                                ($ticket['status_id'] == 3 ? '✓ Resolved' : '✗ Overdue') 
                            ?>
                        </span>
                    </div>
                    <div class="text-xs text-gray-500">
                        <?php if ($dueDate !== null): ?>
                            Due: <?= date('M d, Y H:i', $dueDate) ?>
                        <?php else: ?>
                            No due date set
                        <?php endif; ?>
                    </div>
                </div>
                <div class="pt-3 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Department</span>
                        <span class="text-blue-600 font-medium"><?= esc($ticket['department_name'] ?? 'IT Support') ?></span>
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
                        <h2 class="text-xl font-semibold text-gray-800">Ticket Conversation</h2>
                        <div class="text-gray-600 text-sm">
                            <i class="far fa-comments mr-1"></i>
                            <?= count($messages ?? []) ?> messages
                        </div>
                    </div>
                </div>

                <!-- Conversation Container -->
                <div id="conversationContainer" class="p-6 max-h-[500px] overflow-y-auto">
                    <!-- Conversation Timeline -->
                    <div class="space-y-6">
                        <?php if (!empty($messages)): ?>
                            <?php 
                            $currentDate = null;
                            foreach ($messages as $message): 
                                $messageDate = date('Y-m-d', strtotime($message['created_at']));
                                $displayDate = date('F j, Y', strtotime($message['created_at']));
                                
                                // Check if we need to display date header
                                if ($currentDate !== $messageDate):
                                    $currentDate = $messageDate;
                                    $today = date('Y-m-d');
                                    $yesterday = date('Y-m-d', strtotime('-1 day'));
                                    
                                    $dateLabel = $messageDate === $today ? 'Today' : 
                                                ($messageDate === $yesterday ? 'Yesterday' : $displayDate);
                            ?>
                                <div class="text-center">
                                    <span class="px-4 py-1 bg-gray-100 text-gray-600 text-sm rounded-full">
                                        <?= $dateLabel ?>
                                    </span>
                                </div>
                            <?php endif; ?>

                                <!-- Message -->
                                <div class="flex gap-4 message-item">
                                    <!-- Avatar -->
                                    <div class="flex-shrink-0">
                                        <?php if (!empty($message['photo_profile'])): ?>
                                            <img src="<?= base_url($message['photo_profile']) ?>" 
                                                 alt="<?= esc($message['sender_name']) ?>"
                                                 class="w-10 h-10 rounded-full object-cover border-2 border-white shadow">
                                        <?php else: ?>
                                            <div class="w-10 h-10 <?= 
                                                $message['sender_id'] == $user_details['user_id'] ? 
                                                'bg-secondary' : 'bg-green-100' 
                                            ?> rounded-full flex items-center justify-center">
                                                <i class="fas <?= 
                                                    $message['sender_id'] == $user_details['user_id'] ? 
                                                    'fa-server text-white' : 'fa-headset text-green-600' 
                                                ?>"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Message Content -->
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div>
                                                <span class="text-gray-800 font-semibold"><?= esc($message['sender_name']) ?></span>
                                                <?php if ($message['sender_id'] == $user_details['user_id']): ?>
                                                <span class="ml-2 px-2 py-0.5 bg-secondary/10 text-secondary text-xs rounded">
                                                    IT Support
                                                </span>
                                                <?php else: ?>
                                                <span class="ml-2 px-2 py-0.5 bg-green-50 text-green-700 text-xs rounded">
                                                    <?= 
                                                        $message['sender_id'] == $ticket['customer_id'] ? 'Customer' : 
                                                        (in_array($message['sender_id'], [1, 3]) ? 'Support' : 'User')
                                                    ?>
                                                </span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="text-gray-500 text-sm ml-auto">
                                                <i class="far fa-clock mr-1"></i>
                                                <?= date('g:i A', strtotime($message['created_at'])) ?>
                                            </div>
                                        </div>

                                        <div class="<?= 
                                            $message['sender_id'] == $user_details['user_id'] ? 
                                            'bg-gray-50' : 'bg-green-50 border border-green-100' 
                                        ?> rounded-xl p-4">
                                            <p class="text-gray-700 mb-3"><?= nl2br(esc($message['message'])) ?></p>
                                            
                                            <?php 
                                            // Check if this is a status update message
                                            $isStatusUpdate = stripos($message['message'], 'status') !== false || 
                                                             stripos($message['message'], 'resolved') !== false ||
                                                             stripos($message['message'], 'closed') !== false;
                                            
                                            if ($isStatusUpdate): 
                                            ?>
                                            <div class="<?= 
                                                stripos($message['message'], 'resolved') !== false || 
                                                stripos($message['message'], 'closed') !== false ? 
                                                'border-green-300 bg-white' : 'border-blue-300 bg-white' 
                                            ?> flex items-center gap-2 px-3 py-2 rounded-lg border">
                                                <i class="fas <?= 
                                                    stripos($message['message'], 'resolved') !== false || 
                                                    stripos($message['message'], 'closed') !== false ? 
                                                    'fa-check-circle text-green-600' : 'fa-sync-alt text-blue-600' 
                                                ?>"></i>
                                                <span class="<?= 
                                                    stripos($message['message'], 'resolved') !== false || 
                                                    stripos($message['message'], 'closed') !== false ? 
                                                    'text-green-700' : 'text-blue-700' 
                                                ?> text-sm font-medium">
                                                    <?= 
                                                        stripos($message['message'], 'resolved') !== false ? 'Ticket resolved' : 
                                                        (stripos($message['message'], 'closed') !== false ? 'Ticket closed' : 'Status updated') 
                                                    ?>
                                                </span>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-8">
                                <i class="fas fa-comments text-3xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">No messages yet</p>
                                <p class="text-gray-400 text-sm">Start the conversation by sending a message</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Reply Section -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Add Internal Note</h3>
                
                <form action="<?= base_url('department/it-support/ticket/' . ($ticket['ticket_id'] ?? '') . '/add_message') ?>" 
                      method="POST" id="messageForm">
                    <?= csrf_field() ?>
                    
                    <div class="space-y-4">
                        <!-- Message Input -->
                        <div>
                            <textarea name="message" placeholder="Add technical notes or updates..."
                                class="w-full h-32 p-4 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 resize-none text-gray-700"
                                rows="4" id="messageInput" required></textarea>
                            <div class="text-gray-500 text-xs mt-1">
                                Internal notes are visible only to IT Support and Support teams
                            </div>
                        </div>

                        <!-- File Attachment -->
                        <div class="flex items-center gap-4">
                            <button type="button" id="attachFileBtn"
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
                                <input type="checkbox" id="updateStatus" name="update_status" 
                                       class="rounded text-secondary focus:ring-secondary" value="1">
                                <label for="updateStatus" class="text-gray-700 text-sm">Update ticket status</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="notifySupport" name="notify_support"
                                       class="rounded text-secondary focus:ring-secondary" value="1" checked>
                                <label for="notifySupport" class="text-gray-700 text-sm">Notify Support team</label>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3 pt-4">
                            <button type="submit" id="sendReplyBtn"
                                class="px-6 py-3 bg-secondary text-white rounded-lg hover:bg-secondary/90 transition-colors font-medium flex items-center gap-2 flex-1 justify-center">
                                <i class="fas fa-paper-plane"></i>
                                Send Internal Note
                            </button>
                            <button type="button" id="cancelBtn"
                                class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium flex-1">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>
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
                        <!-- Update Status Form -->
                        <form action="<?= base_url('department/it-support/ticket/' . ($ticket['ticket_id'] ?? '') . '/update_status') ?>" 
                              method="POST" id="statusForm">
                            <?= csrf_field() ?>
                            
                            <div class="space-y-3">
                                <label class="text-gray-700 text-sm font-medium">Update Status</label>

                                <!-- Status Options -->
                                <div class="space-y-2">
                                    <?php 
                                    $statuses = [
                                        ['id' => 1, 'name' => 'Open', 'desc' => 'Ticket is open and awaiting action'],
                                        ['id' => 2, 'name' => 'In Progress', 'desc' => 'Currently working on this ticket'],
                                        ['id' => 3, 'name' => 'Resolved', 'desc' => 'Issue has been fixed and completed'],
                                        ['id' => 4, 'name' => 'Closed', 'desc' => 'Ticket is closed']
                                    ];
                                    
                                    foreach ($statuses as $status): 
                                        $isCurrent = ($ticket['status_id'] ?? 1) == $status['id'];
                                    ?>
                                    <div class="status-option flex items-center gap-3 p-3 bg-white border <?= 
                                        $isCurrent ? 'border-secondary' : 'border-gray-300' 
                                    ?> rounded-lg cursor-pointer hover:bg-gray-50 <?= $isCurrent ? 'selected' : '' ?>"
                                        data-status="<?= $status['id'] ?>">
                                        <div class="w-4 h-4 <?= 
                                            $isCurrent ? 'bg-purple-500' : 'bg-secondary' 
                                        ?> rounded-full flex items-center justify-center">
                                            <i class="fas <?= 
                                                $isCurrent ? 'fa-check' : 'fa-plus' 
                                            ?> text-white text-xs"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-800"><?= $status['name'] ?></p>
                                            <p class="text-gray-500 text-xs"><?= $status['desc'] ?></p>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <!-- Hidden input for status -->
                                <input type="hidden" name="status_id" id="selectedStatus" value="<?= $ticket['status_id'] ?? 1 ?>">
                                
                                <!-- Notes field -->
                                <div class="pt-2">
                                    <textarea name="notes" placeholder="Add notes about status change (optional)"
                                        class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary text-sm"
                                        rows="2"></textarea>
                                </div>

                                <!-- Action Buttons -->
                                <div class="space-y-2 pt-4">
                                    <button type="submit" id="updateTicketBtn"
                                        class="w-full px-4 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium">
                                        Update Status
                                    </button>
                                    
                                    <?php if (($ticket['status_id'] ?? 1) != 3 && ($ticket['status_id'] ?? 1) != 4): ?>
                                    <button type="button" id="markResolvedBtn"
                                        class="w-full px-4 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors font-medium flex items-center justify-center gap-2">
                                        <i class="fas fa-check-circle"></i>
                                        Mark as Resolved
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Ticket Details Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-info-circle text-secondary"></i>
                        Ticket Details
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Customer</p>
                            <p class="font-medium text-gray-800"><?= esc($ticket['customer_name'] ?? 'Unknown') ?></p>
                        </div>
                        
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Email</p>
                            <p class="font-medium text-gray-800"><?= esc($ticket['customer_email'] ?? 'No email') ?></p>
                        </div>
                        
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Project</p>
                            <p class="font-medium text-gray-800"><?= esc($ticket['project_name'] ?? 'No project') ?></p>
                        </div>
                        
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Category</p>
                            <p class="font-medium text-gray-800"><?= esc($ticket['category_name'] ?? 'Uncategorized') ?></p>
                        </div>
                        
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Created</p>
                            <p class="font-medium text-gray-800">
                                <?= date('M d, Y H:i', strtotime($ticket['created_at'] ?? 'now')) ?>
                            </p>
                        </div>
                        
                        <?php if (!empty($ticket['updated_at'])): ?>
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Last Updated</p>
                            <p class="font-medium text-gray-800">
                                <?= date('M d, Y H:i', strtotime($ticket['updated_at'])) ?>
                            </p>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($attachments)): ?>
                        <div class="pt-4 border-t border-gray-200">
                            <p class="text-gray-600 text-sm mb-3">Attachments</p>
                            <div class="space-y-2">
                                <?php foreach ($attachments as $attachment): ?>
                                <div class="flex items-center gap-2 p-2 bg-gray-50 rounded">
                                    <i class="fas fa-file text-secondary"></i>
                                    <span class="text-sm truncate flex-1"><?= esc($attachment['file_name']) ?></span>
                                    <span class="text-xs text-gray-500"><?= 
                                        round($attachment['file_size'] / 1024, 1) ?>KB
                                    </span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom styles */
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

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
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
    document.addEventListener('DOMContentLoaded', function () {
        // Auto-scroll to bottom of conversation
        const conversationContainer = document.getElementById('conversationContainer');
        if (conversationContainer) {
            setTimeout(() => {
                conversationContainer.scrollTop = conversationContainer.scrollHeight;
            }, 100);
        }

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
                input.accept = '.txt,.log,.pdf,.doc,.docx,.png,.jpg';
                input.onchange = function (e) {
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
                            <button class="ml-2 text-red-500 hover:text-red-700 remove-file-btn" type="button">
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

        // Status selection
        document.querySelectorAll('.status-option').forEach(option => {
            option.addEventListener('click', function () {
                // Remove selection from all
                document.querySelectorAll('.status-option').forEach(opt => {
                    opt.classList.remove('selected');
                    opt.querySelector('.w-4.h-4').classList.remove('bg-purple-500');
                    opt.querySelector('.w-4.h-4').classList.add('bg-secondary');
                    opt.querySelector('.w-4.h-4 i').className = 'fas fa-plus text-white text-xs';
                });

                // Select this option
                this.classList.add('selected');
                const icon = this.querySelector('.w-4.h-4');
                icon.classList.remove('bg-secondary');
                icon.classList.add('bg-purple-500');
                icon.querySelector('i').className = 'fas fa-check text-white text-xs';

                // Update hidden input value
                const statusId = this.getAttribute('data-status');
                document.getElementById('selectedStatus').value = statusId;

                // Update button text
                const statusName = this.querySelector('.font-medium').textContent;
                document.getElementById('updateTicketBtn').textContent = `Update to ${statusName}`;
            });
        });

        // Mark as Resolved button
        const markResolvedBtn = document.getElementById('markResolvedBtn');
        if (markResolvedBtn) {
            markResolvedBtn.addEventListener('click', function () {
                // Select resolved status (ID 3)
                const resolvedOption = document.querySelector('.status-option[data-status="3"]');
                if (resolvedOption) {
                    resolvedOption.click();
                    
                    // Set notes to indicate automatic resolution
                    const notesField = document.querySelector('textarea[name="notes"]');
                    if (notesField) {
                        notesField.value = 'Ticket marked as resolved by IT Support team.';
                    }
                    
                    // Submit the form
                    document.getElementById('statusForm').submit();
                }
            });
        }

        // Form submission handling
        const messageForm = document.getElementById('messageForm');
        if (messageForm) {
            messageForm.addEventListener('submit', function (e) {
                const message = messageInput.value.trim();
                if (!message) {
                    e.preventDefault();
                    showToast('Please write a message before sending', 'error');
                    return;
                }
                
                // Show loading state
                const sendBtn = document.getElementById('sendReplyBtn');
                if (sendBtn) {
                    sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
                    sendBtn.disabled = true;
                }
            });
        }

        const statusForm = document.getElementById('statusForm');
        if (statusForm) {
            statusForm.addEventListener('submit', function (e) {
                const updateBtn = document.getElementById('updateTicketBtn');
                if (updateBtn) {
                    updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
                    updateBtn.disabled = true;
                }
            });
        }

        // Cancel button
        const cancelBtn = document.getElementById('cancelBtn');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function () {
                messageInput.value = '';
                messageInput.style.height = 'auto';
                if (fileInfo) fileInfo.innerHTML = 'No files attached';
                document.getElementById('updateStatus').checked = false;
                document.getElementById('notifySupport').checked = true;
            });
        }

        // Focus View Button
        const focusViewBtn = document.getElementById('focusViewBtn');
        if (focusViewBtn) {
            focusViewBtn.addEventListener('click', function () {
                openFocusView();
            });
        }

        // Utility functions
        function openFocusView() {
            // Create modal
            const modal = document.createElement('div');
            modal.className = 'focus-view-modal';
            modal.innerHTML = `
            <div class="focus-view-header">
                <div class="flex items-center gap-3">
                    <h2 class="text-white text-xl font-bold">Focus View - Ticket #<?= esc($ticket['ticket_number'] ?? 'Unknown') ?></h2>
                    <span class="px-2 py-1 bg-secondary text-white text-xs rounded">IT Support</span>
                </div>
                <button id="closeFocusView" class="text-white hover:text-gray-300 text-xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="focus-view-body bg-gray-900">
                <div class="max-w-6xl mx-auto">
                    <!-- Ticket Header -->
                    <div class="bg-gray-800 rounded-xl p-6 mb-6">
                        <h3 class="text-white text-lg font-bold mb-2"><?= esc($ticket['subject'] ?? 'No subject') ?></h3>
                        <p class="text-gray-300">
                            <?= esc($ticket['project_name'] ?? 'No project') ?> • 
                            <?= esc($ticket['category_name'] ?? 'Uncategorized') ?>
                        </p>
                    </div>
                    
                    <!-- Technical Details -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                        <div class="bg-gray-800 rounded-xl p-6">
                            <h4 class="text-white font-bold mb-3">Technical Details</h4>
                            <div class="space-y-2 text-gray-300 text-sm">
                                <div class="flex justify-between">
                                    <span>Customer:</span>
                                    <span class="font-medium"><?= esc($ticket['customer_name'] ?? 'Unknown') ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Status:</span>
                                    <span class="<?= 
                                        ($ticket['status_id'] ?? 1) == 3 ? 'text-green-400' : 
                                        (($ticket['status_id'] ?? 1) == 2 ? 'text-yellow-400' : 'text-red-400') 
                                    ?> font-medium">
                                        <?= esc($ticket['status_name'] ?? 'Unknown') ?>
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Priority:</span>
                                    <span class="<?= 
                                        ($ticket['priority_id'] ?? 1) >= 3 ? 'text-red-400' : 
                                        (($ticket['priority_id'] ?? 1) == 2 ? 'text-yellow-400' : 'text-green-400') 
                                    ?> font-medium">
                                        <?= esc($ticket['priority_name'] ?? 'Normal') ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-800 rounded-xl p-6">
                            <h4 class="text-white font-bold mb-3">Timeline</h4>
                            <div class="space-y-2 text-gray-300 text-sm">
                                <div class="flex justify-between">
                                    <span>Created:</span>
                                    <span class="font-medium"><?= date('M d, H:i', strtotime($ticket['created_at'] ?? 'now')) ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Last Updated:</span>
                                    <span class="font-medium"><?= date('M d, H:i', strtotime($ticket['updated_at'] ?? $ticket['created_at'] ?? 'now')) ?></span>
                                </div>
                                <?php if (!empty($ticket['due_date'])): ?>
                                <div class="flex justify-between">
                                    <span>Due:</span>
                                    <span class="<?= 
                                        strtotime($ticket['due_date']) > time() ? 'text-green-400' : 'text-red-400' 
                                    ?> font-medium">
                                        <?= date('M d, H:i', strtotime($ticket['due_date'])) ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="bg-gray-800 rounded-xl p-6">
                            <h4 class="text-white font-bold mb-3">Quick Actions</h4>
                            <div class="space-y-3">
                                <button onclick="submitStatusForm()" 
                                        class="w-full bg-secondary text-white py-2 rounded-lg text-sm">
                                    Update Status
                                </button>
                                <?php if (($ticket['status_id'] ?? 1) != 3 && ($ticket['status_id'] ?? 1) != 4): ?>
                                <button onclick="markAsResolved()" 
                                        class="w-full bg-green-600 text-white py-2 rounded-lg text-sm">
                                    Mark Resolved
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Conversation in focus view -->
                    <div class="bg-gray-800 rounded-xl p-6">
                        <h4 class="text-white font-bold mb-4">Recent Conversation</h4>
                        <div class="space-y-4 max-h-[300px] overflow-y-auto pr-4">
                            <?php if (!empty($messages)): ?>
                                <?php foreach (array_slice($messages, -5) as $message): ?>
                                <div class="bg-gray-700 rounded p-4">
                                    <div class="flex justify-between mb-2">
                                        <span class="text-white font-medium"><?= esc($message['sender_name']) ?></span>
                                        <span class="text-gray-400 text-sm"><?= 
                                            date('H:i', strtotime($message['created_at'])) 
                                        ?></span>
                                    </div>
                                    <p class="text-gray-300 text-sm"><?= 
                                        strlen($message['message']) > 150 ? 
                                        substr($message['message'], 0, 150) . '...' : 
                                        $message['message'] 
                                    ?></p>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-gray-400 text-center">No messages yet</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        `;

            document.body.appendChild(modal);
            document.body.style.overflow = 'hidden';

            // Close button
            const closeBtn = modal.querySelector('#closeFocusView');
            closeBtn.addEventListener('click', function () {
                document.body.removeChild(modal);
                document.body.style.overflow = 'auto';
            });

            // Close on escape key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && document.body.contains(modal)) {
                    document.body.removeChild(modal);
                    document.body.style.overflow = 'auto';
                }
            });
        }
    });

    function submitStatusForm() {
        document.getElementById('statusForm').submit();
    }
    
    function markAsResolved() {
        const resolvedOption = document.querySelector('.status-option[data-status="3"]');
        if (resolvedOption) {
            resolvedOption.click();
            document.getElementById('statusForm').submit();
        }
    }

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
    
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fadeInUp {
        animation: fadeInUp 0.5s ease-out;
    }
`;
    document.head.appendChild(style);
</script>
<?= $this->endSection() ?>