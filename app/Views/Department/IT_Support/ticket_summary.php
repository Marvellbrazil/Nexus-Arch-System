<?= $this->extend('layouts/it_support_layout') ?>

<?= $this->section('title') ?>Ticket Summary #<?= $ticket['ticket_number'] ?? 'Unknown' ?> - IT Support<?= $this->endSection() ?>

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
<div class="mt-16 md:mt-[77px] p-4 md:p-6 relative z-10">
    <!-- Page Header -->
    <div class="mb-6 md:mb-8">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
            <a href="<?= base_url('department/it-support/dashboard') ?>"
                class="hover:text-secondary transition-colors">Dashboard</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <a href="<?= base_url('department/it-support/assigned_tickets') ?>"
                class="hover:text-secondary transition-colors">Assigned Tickets</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-secondary font-medium">Ticket Summary</span>
        </div>

        <!-- Main Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Ticket Summary</h1>
                    <div class="px-3 py-1 bg-secondary/10 text-secondary text-sm font-semibold rounded-full">
                        <?= esc($ticket['ticket_number'] ?? 'TKT-Unknown') ?>
                    </div>
                </div>
                <p class="text-gray-600">Technical summary for <?= esc($ticket['subject'] ?? 'ticket') ?></p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="<?= base_url('department/it-support/ticket_detail/' . ($ticket['ticket_id'] ?? '')) ?>"
                    class="px-4 py-2 bg-white text-secondary border border-secondary rounded-lg hover:bg-secondary/5 transition-all font-medium flex items-center gap-2 text-sm">
                    <i class="fas fa-external-link-alt"></i>
                    Open Full View
                </a>
                <button onclick="window.print()"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all font-medium flex items-center gap-2 text-sm">
                    <i class="fas fa-print"></i>
                    Print Summary
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Stats Bar -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6 md:mb-8">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user text-blue-600"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Customer</p>
                    <p class="text-gray-800 font-bold truncate"><?= esc($ticket['customer_name'] ?? 'Unknown') ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-project-diagram text-purple-600"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Project</p>
                    <p class="text-gray-800 font-bold"><?= esc($ticket['project_name'] ?? 'No Project') ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 <?= ($ticket['priority_id'] ?? 1) >= 3 ? 'bg-red-100' : 'bg-yellow-100' ?> rounded-lg flex items-center justify-center">
                    <i class="fas fa-flag <?= ($ticket['priority_id'] ?? 1) >= 3 ? 'text-red-600' : 'text-yellow-600' ?>"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Priority</p>
                    <p class="<?= ($ticket['priority_id'] ?? 1) >= 3 ? 'text-red-600' : 'text-yellow-600' ?> font-bold"><?= esc($ticket['priority_name'] ?? 'Normal') ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 <?= 
                    ($ticket['status_id'] ?? 1) == 3 ? 'bg-green-100' : 
                    (($ticket['status_id'] ?? 1) == 2 ? 'bg-purple-100' : 
                    (($ticket['status_id'] ?? 1) == 1 ? 'bg-blue-100' : 'bg-gray-100')) 
                ?> rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock <?= 
                        ($ticket['status_id'] ?? 1) == 3 ? 'text-green-600' : 
                        (($ticket['status_id'] ?? 1) == 2 ? 'text-purple-600' : 
                        (($ticket['status_id'] ?? 1) == 1 ? 'text-blue-600' : 'text-gray-600')) 
                    ?>"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Status</p>
                    <p class="<?= 
                        ($ticket['status_id'] ?? 1) == 3 ? 'text-green-600' : 
                        (($ticket['status_id'] ?? 1) == 2 ? 'text-purple-600' : 
                        (($ticket['status_id'] ?? 1) == 1 ? 'text-blue-600' : 'text-gray-600')) 
                    ?> font-bold"><?= esc($ticket['status_name'] ?? 'Unknown') ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Left Column - Problem Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Problem Description Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-server text-secondary"></i>
                            Technical Issue Details
                        </h2>
                        <span class="text-gray-500 text-sm">
                            <i class="far fa-calendar mr-1"></i>
                            Created: <?= date('M d, Y H:i', strtotime($ticket['created_at'] ?? 'now')) ?>
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Subject -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Issue Summary</h3>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <p class="text-gray-800 font-medium"><?= esc($ticket['subject'] ?? 'No subject') ?></p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Technical Analysis</h3>
                        <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                            <div class="text-gray-700 leading-relaxed space-y-4">
                                <p><strong>Issue Description:</strong></p>
                                <div class="prose max-w-none">
                                    <?= $ticket['description'] ?? 'No description provided' ?>
                                </div>
                                
                                <?php if (!empty($ticket['category_name'])): ?>
                                <p><strong>Category:</strong> <?= esc($ticket['category_name']) ?></p>
                                <?php endif; ?>
                                
                                <?php if (!empty($ticket['department_name'])): ?>
                                <p><strong>Assigned Department:</strong> <?= esc($ticket['department_name']) ?></p>
                                <?php endif; ?>
                                
                                <?php if (!empty($ticket['assigned_to_name'])): ?>
                                <p><strong>Assigned To:</strong> <?= esc($ticket['assigned_to_name']) ?></p>
                                <?php endif; ?>
                                
                                <?php if (!empty($ticket['due_date'])): ?>
                                <p><strong>Due Date:</strong> <?= date('M d, Y H:i', strtotime($ticket['due_date'])) ?></p>
                                <?php endif; ?>
                                
                                <?php if (!empty($ticket['first_response_at'])): ?>
                                <p><strong>First Response:</strong> <?= date('M d, Y H:i', strtotime($ticket['first_response_at'])) ?></p>
                                <?php endif; ?>
                                
                                <?php if (!empty($ticket['resolved_at'])): ?>
                                <p><strong>Resolved:</strong> <?= date('M d, Y H:i', strtotime($ticket['resolved_at'])) ?></p>
                                <?php endif; ?>
                                
                                <?php if (!empty($ticket['closed_at'])): ?>
                                <p><strong>Closed:</strong> <?= date('M d, Y H:i', strtotime($ticket['closed_at'])) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <?php if (!empty($timeline)): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-history text-secondary"></i>
                        Timeline
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <?php foreach ($timeline as $event): ?>
                        <div class="flex gap-4">
                            <div class="w-8 h-8 rounded-full bg-secondary/10 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-comment text-secondary text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-gray-800"><?= esc($event['message']) ?></p>
                                <p class="text-sm text-gray-500 mt-1">
                                    By <?= esc($event['sender_name']) ?> • 
                                    <?= date('M d, H:i', strtotime($event['created_at'])) ?>
                                </p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="space-y-6">
            <!-- Customer Information Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-user text-blue-600"></i>
                        Customer Information
                    </h2>
                </div>

                <div class="p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                            <?= substr($ticket['customer_name'] ?? 'CU', 0, 2) ?>
                        </div>
                        <div>
                            <h3 class="text-gray-800 font-bold"><?= esc($ticket['customer_name'] ?? 'Unknown Customer') ?></h3>
                            <p class="text-gray-600 text-sm">Customer</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-envelope text-gray-400 w-5"></i>
                            <span class="text-gray-700 truncate"><?= esc($ticket['customer_email'] ?? 'No email') ?></span>
                        </div>

                        <div class="pt-3 border-t border-gray-200">
                            <p class="text-gray-600 text-sm mb-2">Customer Tickets</p>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-700">Total Tickets</span>
                                <span class="font-medium">
                                    <?php 
                                    // This would come from database query in real app
                                    echo rand(1, 10); 
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Technical Details Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-secondary/10 to-secondary/5">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-info-circle text-secondary"></i>
                        Technical Details
                    </h2>
                </div>

                <div class="p-6">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Environment</span>
                            <span class="px-3 py-1 bg-<?= 
                                ($ticket['project_name'] ?? '') == 'Production' ? 'red' : 'blue' 
                            ?>-100 text-<?= 
                                ($ticket['project_name'] ?? '') == 'Production' ? 'red' : 'blue' 
                            ?>-800 text-sm rounded-full">
                                <?= ($ticket['project_name'] ?? '') == 'Production' ? 'Production' : 'Development' ?>
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Status</span>
                            <span class="px-3 py-1 <?= 
                                ($ticket['status_id'] ?? 1) == 3 ? 'bg-green-100 text-green-800' : 
                                (($ticket['status_id'] ?? 1) == 2 ? 'bg-purple-100 text-purple-800' : 
                                (($ticket['status_id'] ?? 1) == 1 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) 
                            ?> text-sm rounded-full">
                                <?= esc($ticket['status_name'] ?? 'Unknown') ?>
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Priority</span>
                            <span class="px-3 py-1 <?= 
                                ($ticket['priority_id'] ?? 1) >= 3 ? 'bg-red-100 text-red-800' : 
                                (($ticket['priority_id'] ?? 1) == 2 ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') 
                            ?> text-sm rounded-full">
                                <?= esc($ticket['priority_name'] ?? 'Normal') ?>
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Category</span>
                            <span class="px-3 py-1 bg-purple-100 text-purple-800 text-sm rounded-full">
                                <?= esc($ticket['category_name'] ?? 'Uncategorized') ?>
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Created</span>
                            <span class="font-medium"><?= date('M d, H:i', strtotime($ticket['created_at'] ?? 'now')) ?></span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Last Updated</span>
                            <span class="font-medium"><?= date('M d, H:i', strtotime($ticket['updated_at'] ?? $ticket['created_at'] ?? 'now')) ?></span>
                        </div>

                        <div class="pt-3 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">SLA Status</span>
                                <span class="<?= 
                                    !empty($ticket['due_date']) && strtotime($ticket['due_date']) > time() ? 
                                    'text-green-600' : 'text-red-600' 
                                ?> font-medium">
                                    <?= 
                                        !empty($ticket['due_date']) && strtotime($ticket['due_date']) > time() ? 
                                        '✓ On Track' : '✗ Overdue' 
                                    ?>
                                </span>
                            </div>
                            <?php if (!empty($ticket['due_date'])): ?>
                            <div class="text-gray-500 text-xs mt-1">
                                <?php 
                                $dueDate = new DateTime($ticket['due_date']);
                                $now = new DateTime();
                                $interval = $now->diff($dueDate);
                                
                                if ($dueDate > $now) {
                                    echo $interval->d . 'd ' . $interval->h . 'h remaining';
                                } else {
                                    echo 'Overdue by ' . $interval->d . 'd ' . $interval->h . 'h';
                                }
                                ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Tickets -->
            <?php if (!empty($related_tickets)): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-link text-secondary"></i>
                        Related Tickets
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <?php foreach ($related_tickets as $related): ?>
                        <a href="<?= base_url('department/it-support/ticket_summary/' . $related['ticket_id']) ?>" 
                           class="block p-3 border rounded-lg hover:border-secondary transition-colors">
                            <div class="flex justify-between items-start">
                                <span class="font-medium text-gray-800"><?= esc($related['ticket_number']) ?></span>
                                <span class="text-xs px-2 py-1 rounded-full <?= 
                                    $related['priority_id'] >= 3 ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' 
                                ?>">
                                    <?= esc($related['priority_name']) ?>
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1 truncate"><?= esc($related['subject']) ?></p>
                            <div class="flex justify-between items-center mt-2 text-xs text-gray-500">
                                <span><?= esc($related['status_name']) ?></span>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bottom Actions -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Technical Summary Complete</h3>
                <p class="text-gray-600">Review complete details or return to assigned tickets</p>
            </div>

            <div class="flex gap-3">
                <a href="<?= base_url('department/it-support/assigned_tickets') ?>"
                    class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Back to Assigned
                </a>
                <a href="<?= base_url('department/it-support/ticket_detail/' . ($ticket['ticket_id'] ?? '')) ?>"
                    class="px-6 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium flex items-center gap-2">
                    <i class="fas fa-comments"></i>
                    Open Conversation
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Smooth animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Card hover effects */
    .card-hover {
        transition: all 0.2s ease;
    }

    .card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    /* Status badge pulse */
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.7;
        }
    }

    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    /* Prose styling for description */
    .prose {
        color: #374151;
    }
    
    .prose p {
        margin-top: 0.5em;
        margin-bottom: 0.5em;
    }
    
    .prose ul {
        list-style-type: disc;
        padding-left: 1.5em;
        margin-top: 0.5em;
        margin-bottom: 0.5em;
    }
    
    .prose ol {
        list-style-type: decimal;
        padding-left: 1.5em;
        margin-top: 0.5em;
        margin-bottom: 0.5em;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Add hover effects to cards
        document.querySelectorAll('.bg-white.rounded-2xl, .bg-gradient-to-r').forEach(card => {
            card.classList.add('card-hover');
        });

        // Update current time for timeline
        function updateCurrentTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });

            // You can add current time display if needed
        }

        // Update every minute
        updateCurrentTime();
        setInterval(updateCurrentTime, 60000);

        // Print button functionality
        const printBtn = document.querySelector('button:contains("Print")');
        if (printBtn) {
            printBtn.addEventListener('click', function () {
                // Show print preview
                showToast('Preparing print preview...', 'info');

                setTimeout(() => {
                    // In a real app, this would open the print dialog
                    // window.print();
                    showToast('Print preview ready', 'success');
                }, 1000);
            });
        }

        // Related tickets click tracking
        document.querySelectorAll('a.block.p-3.border.rounded-lg').forEach(link => {
            link.addEventListener('click', function (e) {
                const ticketNumber = this.querySelector('.font-medium').textContent;
                showToast(`Opening related ticket: ${ticketNumber}`, 'info');
            });
        });
    });

    function showToast(message, type = 'info') {
        // Remove existing toasts
        document.querySelectorAll('.toast-notification').forEach(toast => toast.remove());

        const toast = document.createElement('div');
        toast.className = `toast-notification fixed top-24 right-6 p-4 rounded-xl shadow-xl z-[9999] max-w-sm animate-fadeInUp ${type === 'error' ? 'bg-red-500 text-white border-l-4 border-red-600' :
                type === 'success' ? 'bg-green-500 text-white border-l-4 border-green-600' :
                    'bg-blue-500 text-white border-l-4 border-blue-600'
            }`;
        toast.innerHTML = `
        <div class="flex items-center gap-3">
            <i class="fas ${type === 'error' ? 'fa-exclamation-circle text-xl' :
                type === 'success' ? 'fa-check-circle text-xl' :
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

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toast.parentElement) {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => toast.remove(), 300);
            }
        }, 5000);
    }

    // Add CSS animation
    const style = document.createElement('style');
    style.textContent = `
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