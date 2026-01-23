<?= $this->extend('layouts/it_support_layout') ?>

<?= $this->section('title') ?>Notifications - IT Support Department<?= $this->endSection() ?>

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
    <div class="mb-6 md:mb-[25px] relative">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-[35px] font-semibold mb-1 md:mb-[5px] text-text-dark">IT Support Notifications</h1>
                <p class="text-sm md:text-[15px] font-light text-[#666]">System alerts and ticket updates for IT Support team</p>
            </div>

            <!-- Notification Actions -->
            <div class="flex items-center gap-3">
                <form action="<?= base_url('department/it-support/notifications/mark_read') ?>" method="POST"
                    class="inline">
                    <?= csrf_field() ?>
                    <button type="submit"
                        class="px-4 py-2 bg-white text-secondary border border-secondary rounded-lg hover:bg-secondary/5 transition-all text-sm font-medium flex items-center gap-2">
                        <i class="fas fa-check-double"></i>
                        Mark All as Read
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="mb-6 md:mb-8">
        <div class="flex flex-col md:flex-row gap-4 md:gap-6">
            <!-- Search Bar -->
            <div class="flex-1 relative">
                <div class="relative">
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-text-muted">
                        <i class="fas fa-search"></i>
                    </div>
                    <input type="text" placeholder="Search IT Support notifications..." id="searchInput"
                        class="w-full h-12 md:h-[50px] pl-12 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm md:text-[14px] focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
                </div>
            </div>

            <!-- Filter Buttons -->
            <div class="flex gap-3">
                <!-- Filter by Type -->
                <div class="flex gap-2">
                    <button data-filter="all"
                        class="h-12 md:h-[50px] px-4 md:px-6 bg-secondary text-white border border-secondary rounded-xl text-sm md:text-[14px] font-medium hover:bg-[#817CB2] transition-colors">
                        All
                    </button>
                    <button data-filter="ticket"
                        class="h-12 md:h-[50px] px-4 md:px-6 bg-[#AEA3CA] text-text-dark border border-[#D1D1E9] rounded-xl text-sm md:text-[14px] font-medium hover:bg-[#9F95C0] transition-colors">
                        Tickets
                    </button>
                    <button data-filter="system"
                        class="h-12 md:h-[50px] px-4 md:px-6 bg-[#AEA3CA] text-text-dark border border-[#D1D1E9] rounded-xl text-sm md:text-[14px] font-medium hover:bg-[#9F95C0] transition-colors">
                        System
                    </button>
                </div>

                <!-- Priority Dropdown -->
                <div class="relative">
                    <button id="priorityFilterBtn"
                        class="h-12 md:h-[50px] px-4 md:px-6 bg-[#AEA3CA] text-text-dark border border-[#D1D1E9] rounded-xl text-sm md:text-[14px] font-medium flex items-center gap-2 hover:bg-[#9F95C0] transition-colors">
                        <span>Priority</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="priorityDropdown"
                        class="absolute top-full right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 z-50 hidden">
                        <div class="py-2">
                            <button data-priority="all"
                                class="w-full px-4 py-3 text-left text-sm hover:bg-gray-50 transition-colors flex items-center gap-2">
                                <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                                All Priorities
                            </button>
                            <button data-priority="4"
                                class="w-full px-4 py-3 text-left text-sm hover:bg-gray-50 transition-colors flex items-center gap-2">
                                <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                                Critical
                            </button>
                            <button data-priority="3"
                                class="w-full px-4 py-3 text-left text-sm hover:bg-gray-50 transition-colors flex items-center gap-2">
                                <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                                High
                            </button>
                            <button data-priority="2"
                                class="w-full px-4 py-3 text-left text-sm hover:bg-gray-50 transition-colors flex items-center gap-2">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                Medium
                            </button>
                            <button data-priority="1"
                                class="w-full px-4 py-3 text-left text-sm hover:bg-gray-50 transition-colors flex items-center gap-2">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                Low
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Title -->
    <div class="mb-4">
        <h2 class="text-lg md:text-[20px] font-medium text-text-dark">System & Ticket Notifications</h2>
    </div>

    <!-- Notifications Container -->
    <div
        class="bg-gradient-to-r from-[#3D3C5E] to-[#48466B] rounded-2xl shadow-sm border border-[#AEA3CA] overflow-hidden mb-8">
        <!-- Notifications List -->
        <div class="p-4 md:p-6 space-y-3 md:space-y-4 max-h-[500px] overflow-y-auto custom-scrollbar" id="notificationsList">
            <?php if (!empty($notifications)): ?>
                <?php foreach ($notifications as $notification): ?>
                    <?php 
                    // Determine notification type based on title or message
                    $notificationType = strpos(strtolower($notification['title']), 'ticket') !== false ? 'ticket' : 'system';
                    $isUnread = !$notification['is_read'];
                    ?>
                    <div class="notification-item bg-white/10 rounded-xl p-4 md:p-5 hover:bg-white/15 transition-colors"
                        data-priority="<?= $notification['priority_id'] ?? '1' ?>" data-type="<?= $notificationType ?>">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-2">
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-base md:text-lg font-bold text-white"><?= esc($notification['title']) ?></h3>
                                        <?php if ($isUnread): ?>
                                        <div class="unread-indicator">
                                            <div class="w-2 h-2 bg-red-500 rounded-full <?= $notification['priority_id'] == 4 ? 'animate-pulse' : '' ?>"></div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <span class="text-xs md:text-sm text-white/60"><?= esc($notification['time_ago']) ?></span>
                                </div>
                                <p class="text-sm md:text-[14px] text-white/80 mb-3"><?= esc($notification['message']) ?></p>
                                <?php if ($notification['ticket_number']): ?>
                                <p class="text-sm md:text-[14px] text-white/60 mb-3">Ticket: <?= esc($notification['ticket_number']) ?></p>
                                <?php endif; ?>
                                <div class="flex items-center gap-2">
                                    <div class="priority-badge px-3 py-1 <?= $notification['priorityColor'] ?> rounded-lg">
                                        <span class="text-xs font-bold"><?= esc($notification['priorityText']) ?></span>
                                    </div>
                                    <span class="text-xs text-white/60"><?= ucfirst($notificationType) ?></span>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-3">
                                <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">
                                    <i class="fas <?= $notificationType == 'ticket' ? 'fa-ticket-alt' : 'fa-server' ?> text-white/60"></i>
                                </div>
                                <?php if ($notification['ticket_id']): ?>
                                <a href="<?= base_url('department/it-support/ticket_detail/') . $notification['ticket_id'] ?>" 
                                   class="text-white/40 hover:text-white/60 transition-colors">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Empty State -->
                <div id="emptyState" class="p-8 md:p-12 text-center">
                    <div
                        class="w-16 h-16 md:w-20 md:h-20 mx-auto mb-4 bg-white/10 rounded-full flex items-center justify-center">
                        <i class="fas fa-server text-white/40 text-2xl md:text-3xl"></i>
                    </div>
                    <h3 class="text-lg md:text-xl font-semibold text-white mb-2">No notifications</h3>
                    <p class="text-white/60 max-w-md mx-auto">
                        You're all caught up! You'll receive alerts for ticket updates and system notifications.
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
        <div class="bg-gradient-to-r from-secondary to-[#8A84C6] rounded-2xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-white/80 text-sm mb-1">Total Alerts</div>
                    <div class="text-3xl font-bold"><?= $stats['total'] ?? 0 ?></div>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-server text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-gray-600 text-sm mb-1">Unread</div>
                    <div class="text-3xl font-bold text-gray-800"><?= $stats['unread'] ?? 0 ?></div>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-gray-600 text-sm mb-1">This Week</div>
                    <div class="text-3xl font-bold text-gray-800"><?= $stats['this_week'] ?? 0 ?></div>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-week text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom animations */
    @keyframes slideIn {
        from {
            transform: translateY(10px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .animate-slide-in {
        animation: slideIn 0.3s ease-out;
    }

    /* Unread indicator animation */
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.6;
        }
    }

    .unread-indicator {
        animation: pulse 2s ease-in-out infinite;
    }

    /* Priority badges */
    .priority-badge {
        transition: all 0.2s ease;
    }

    .priority-badge:hover {
        transform: scale(1.05);
    }

    /* Custom scrollbar for notifications */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 3px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 3px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.4);
    }

    /* Critical priority pulse */
    @keyframes criticalPulse {
        0%, 100% {
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
        }
        70% {
            box-shadow: 0 0 0 6px rgba(239, 68, 68, 0);
        }
    }

    .animate-pulse {
        animation: criticalPulse 2s infinite;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Notification data from PHP
        const notificationsData = <?= json_encode($notifications) ?>;
        
        // Mark all as read button
        const markAllReadBtn = document.querySelector('button[type="submit"]');
        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', function (e) {
                e.preventDefault();

                // Update UI immediately for better UX
                document.querySelectorAll('.unread-indicator').forEach(indicator => {
                    indicator.style.animation = 'none';
                    const dot = indicator.querySelector('.w-2.h-2');
                    if (dot) {
                        dot.style.opacity = '0.5';
                    }
                });

                // Update unread count in stats
                const unreadElement = document.querySelector('.text-3xl.font-bold.text-gray-800');
                if (unreadElement) {
                    unreadElement.textContent = '0';
                }

                // Change button text and style temporarily
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
                this.disabled = true;

                // Simulate API call
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-check mr-2"></i> All Marked as Read';
                    this.classList.remove('bg-white', 'text-secondary', 'border-secondary');
                    this.classList.add('bg-green-500', 'text-white', 'border-green-500');

                    // Show success toast
                    showToast('All notifications marked as read', 'success');
                }, 1000);
            });
        }

        // Notification click to mark as read
        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function (e) {
                // Don't trigger if clicking links or buttons
                if (e.target.closest('a') || e.target.closest('button')) {
                    return;
                }

                const indicator = this.querySelector('.unread-indicator');
                if (indicator) {
                    indicator.style.animation = 'none';
                    const dot = indicator.querySelector('.w-2.h-2');
                    if (dot) {
                        dot.style.opacity = '0.5';
                    }

                    // Update unread count in stats
                    const unreadElement = document.querySelector('.text-3xl.font-bold.text-gray-800');
                    if (unreadElement) {
                        const currentUnread = parseInt(unreadElement.textContent || '0');
                        if (currentUnread > 0) {
                            unreadElement.textContent = currentUnread - 1;
                            showToast('Notification marked as read', 'info');
                        }
                    }
                }
            });
        });

        // Priority dropdown toggle
        const priorityBtn = document.getElementById('priorityFilterBtn');
        const priorityDropdown = document.getElementById('priorityDropdown');

        if (priorityBtn && priorityDropdown) {
            priorityBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                priorityDropdown.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function (e) {
                if (!priorityBtn.contains(e.target) && !priorityDropdown.contains(e.target)) {
                    priorityDropdown.classList.add('hidden');
                }
            });

            // Handle priority selection
            priorityDropdown.querySelectorAll('button[data-priority]').forEach(btn => {
                btn.addEventListener('click', function () {
                    const priorityValue = this.getAttribute('data-priority');
                    const priorityText = this.textContent.trim();

                    // Update button text
                    priorityBtn.querySelector('span').textContent = 'Priority: ' + priorityText.split(' ')[0];

                    // Filter notifications based on priority
                    filterNotificationsByPriority(priorityValue);

                    priorityDropdown.classList.add('hidden');
                });
            });
        }

        // Filter by type buttons
        document.querySelectorAll('button[data-filter]').forEach(btn => {
            btn.addEventListener('click', function () {
                const filterType = this.getAttribute('data-filter');

                // Update active state
                document.querySelectorAll('button[data-filter]').forEach(b => {
                    b.classList.remove('bg-secondary', 'text-white', 'border-secondary');
                    b.classList.add('bg-[#AEA3CA]', 'text-text-dark', 'border-[#D1D1E9]');
                });

                this.classList.remove('bg-[#AEA3CA]', 'text-text-dark', 'border-[#D1D1E9]');
                this.classList.add('bg-secondary', 'text-white', 'border-secondary');

                // Filter notifications
                if (filterType === 'all') {
                    document.querySelectorAll('.notification-item').forEach(item => {
                        item.style.display = 'flex';
                    });
                } else {
                    document.querySelectorAll('.notification-item').forEach(item => {
                        const itemType = item.getAttribute('data-type');
                        if (itemType === filterType) {
                            item.style.display = 'flex';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                }

                // Show/hide empty state
                checkEmptyState();
            });
        });

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const searchTerm = this.value.toLowerCase().trim();
                    filterNotificationsBySearch(searchTerm);
                    checkEmptyState();
                }, 300);
            });
        }

        // Function to filter notifications by priority
        function filterNotificationsByPriority(priorityValue) {
            if (priorityValue === 'all') {
                document.querySelectorAll('.notification-item').forEach(item => {
                    item.style.display = 'flex';
                });
            } else {
                document.querySelectorAll('.notification-item').forEach(item => {
                    const itemPriority = item.getAttribute('data-priority');
                    if (itemPriority === priorityValue) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }

            // Show/hide empty state
            checkEmptyState();
        }

        // Function to filter notifications by search term
        function filterNotificationsBySearch(searchTerm) {
            const notifications = document.querySelectorAll('.notification-item');

            notifications.forEach(notification => {
                const title = notification.querySelector('h3')?.textContent.toLowerCase() || '';
                const content = notification.querySelector('.text-white\\/80')?.textContent.toLowerCase() || '';
                const time = notification.querySelector('.text-white\\/60')?.textContent.toLowerCase() || '';
                const priority = notification.querySelector('.priority-badge span')?.textContent.toLowerCase() || '';
                const ticketNumber = notification.querySelector('.text-white\\/60.text-sm')?.textContent.toLowerCase() || '';

                if (searchTerm === '' ||
                    title.includes(searchTerm) ||
                    content.includes(searchTerm) ||
                    time.includes(searchTerm) ||
                    priority.includes(searchTerm) ||
                    ticketNumber.includes(searchTerm)) {
                    notification.style.display = 'flex';
                } else {
                    notification.style.display = 'none';
                }
            });
        }

        // Function to check if empty state should be shown
        function checkEmptyState() {
            const visibleNotifications = Array.from(document.querySelectorAll('.notification-item'))
                .filter(item => item.style.display !== 'none').length;

            const emptyState = document.getElementById('emptyState');
            const notificationsContainer = document.getElementById('notificationsList');

            if (visibleNotifications === 0) {
                if (emptyState) emptyState.classList.remove('hidden');
                if (notificationsContainer) {
                    notificationsContainer.querySelectorAll('.notification-item').forEach(item => {
                        item.style.display = 'none';
                    });
                }
            } else {
                if (emptyState) emptyState.classList.add('hidden');
            }
        }
    });

    function showToast(message, type = 'info') {
        // Remove existing toasts
        document.querySelectorAll('.custom-toast').forEach(toast => toast.remove());

        const toast = document.createElement('div');
        toast.className = `custom-toast fixed top-24 right-4 md:right-6 p-4 rounded-lg shadow-lg z-[1000] max-w-sm animate-slide-in ${type === 'error' ? 'bg-red-500 text-white' :
                type === 'success' ? 'bg-green-500 text-white' :
                    'bg-blue-500 text-white'
            }`;
        toast.innerHTML = `
            <div class="flex items-center gap-2">
                <i class="fas ${type === 'error' ? 'fa-exclamation-circle' :
                type === 'success' ? 'fa-check-circle' :
                    'fa-info-circle'
            }"></i>
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