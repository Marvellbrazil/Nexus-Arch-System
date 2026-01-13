<?= $this->extend('layouts/support_layout') ?>

<?= $this->section('title') ?>Notifications - NEXUS Support<?= $this->endSection() ?>

<?= $this->section('background_effects') ?>
<!-- Background Effects -->
<div
    class="fixed w-[40vw] h-[40vw] -right-[10%] -bottom-[10%] rotate-[149deg] bg-gradient-to-r from-[rgba(56.96,44.47,127.72,0.15)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-80 z-0">
</div>
<div
    class="fixed w-[35vw] h-[25vw] -left-[5%] top-[10%] rotate-[8deg] bg-gradient-to-r from-[rgba(65.04,45.10,137.14,0.20)] via-[#D6D1E9] to-[#817CB2] blur-[100px] opacity-70 z-0">
</div>
<div
    class="fixed w-[15vw] h-[20vw] right-[5%] -top-[5%] rotate-[8deg] bg-gradient-to-r from-[rgba(16.56,8.41,46.05,0.12)] via-[#D6D1E9] to-[#817CB2] blur-[100px] opacity-60 z-0">
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
// Get request object
$request = service('request');
?>
<div class="mt-4 md:mt-[77px] p-4 md:p-[30px] relative z-10">
    <!-- Page Header -->
    <div class="mb-6 md:mb-[25px] relative">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-[35px] font-semibold mb-1 md:mb-[5px] text-text-dark">Notifications</h1>
                <p class="text-sm md:text-[15px] font-light text-[#666]">See what's new in your inbox</p>
            </div>

            <!-- Notification Actions -->
            <div class="flex items-center gap-3">
                <form action="<?= base_url('support/notifications/mark_read') ?>" method="POST" class="inline">
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

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-8">
        <div class="bg-gradient-to-r from-secondary to-[#8A84C6] rounded-2xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-white/80 text-sm mb-1">Total</div>
                    <div class="text-3xl font-bold"><?= $stats['total'] ?? 0 ?></div>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-bell text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-gray-600 text-sm mb-1">Unread</div>
                    <div class="text-3xl font-bold text-gray-800"><?= $stats['unread'] ?? 0 ?></div>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-envelope text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-gray-600 text-sm mb-1">This Week</div>
                    <div class="text-3xl font-bold text-gray-800"><?= $stats['this_week'] ?? 0 ?></div>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-week text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="mb-6 md:mb-8">
        <div class="flex flex-col md:flex-row gap-4 md:gap-6">
            <!-- Search Bar -->
            <div class="flex-1 relative">
                <form method="GET" action="<?= base_url('support/notifications') ?>" id="searchForm">
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-text-muted">
                            <i class="fas fa-search"></i>
                        </div>
                        <input type="text" name="search" value="<?= esc($request->getGet('search') ?? '') ?>"
                            placeholder="Search notifications..." id="searchInput"
                            class="w-full h-12 md:h-[50px] pl-12 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm md:text-[14px] focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
                        <?php if ($request->getGet('search')): ?>
                            <a href="<?= base_url('support/notifications') ?>"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Filter Buttons -->
            <div class="flex gap-3">
                <!-- Filter by Type -->
                <div class="flex gap-2">
                    <button data-filter="all"
                        class="h-12 md:h-[50px] px-4 md:px-6 rounded-xl text-sm md:text-[14px] font-medium hover:bg-[#817CB2] transition-colors <?= !$request->getGet('filter') || $request->getGet('filter') === 'all' ? 'bg-secondary text-white border border-secondary' : 'bg-[#AEA3CA] text-text-dark border-[#D1D1E9]' ?>">
                        All (<?= $stats['total'] ?? 0 ?>)
                    </button>

                    <?php if (!empty($notification_types)): ?>
                        <?php foreach ($notification_types as $type): ?>
                            <?php if ($type['type']): ?>
                                <button data-filter="<?= strtolower($type['type']) ?>"
                                    class="h-12 md:h-[50px] px-4 md:px-6 rounded-xl text-sm md:text-[14px] font-medium hover:bg-[#817CB2] transition-colors <?= $request->getGet('filter') === strtolower($type['type']) ? 'bg-secondary text-white border border-secondary' : 'bg-[#AEA3CA] text-text-dark border-[#D1D1E9]' ?>">
                                    <?= ucfirst($type['type']) ?> (<?= $type['count'] ?>)
                                </button>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Priority Dropdown -->
                <div class="relative">
                    <button id="priorityFilterBtn"
                        class="h-12 md:h-[50px] px-4 md:px-6 bg-[#AEA3CA] text-text-dark border border-[#D1D1E9] rounded-xl text-sm md:text-[14px] font-medium flex items-center gap-2 hover:bg-[#9F95C0] transition-colors">
                        <span>
                            <?php
                            $priorityText = 'Priority';
                            if ($request->getGet('priority') && $request->getGet('priority') !== 'all') {
                                $priorityText = ucfirst($request->getGet('priority'));
                            }
                            echo $priorityText;
                            ?>
                        </span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="priorityDropdown"
                        class="absolute top-full right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 z-50 hidden">
                        <div class="py-2">
                            <a href="<?= base_url('support/notifications?' . http_build_query(array_merge($_GET, ['priority' => 'all']))) ?>"
                                class="w-full px-4 py-3 text-left text-sm hover:bg-gray-50 transition-colors flex items-center gap-2 <?= !$request->getGet('priority') || $request->getGet('priority') === 'all' ? 'bg-gray-50' : '' ?>">
                                <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                                All Priorities
                            </a>
                            <a href="<?= base_url('support/notifications?' . http_build_query(array_merge($_GET, ['priority' => 'urgent']))) ?>"
                                class="w-full px-4 py-3 text-left text-sm hover:bg-gray-50 transition-colors flex items-center gap-2 <?= $request->getGet('priority') === 'urgent' ? 'bg-gray-50' : '' ?>">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                Urgent
                            </a>
                            <a href="<?= base_url('support/notifications?' . http_build_query(array_merge($_GET, ['priority' => 'high']))) ?>"
                                class="w-full px-4 py-3 text-left text-sm hover:bg-gray-50 transition-colors flex items-center gap-2 <?= $request->getGet('priority') === 'high' ? 'bg-gray-50' : '' ?>">
                                <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                                High
                            </a>
                            <a href="<?= base_url('support/notifications?' . http_build_query(array_merge($_GET, ['priority' => 'medium']))) ?>"
                                class="w-full px-4 py-3 text-left text-sm hover:bg-gray-50 transition-colors flex items-center gap-2 <?= $request->getGet('priority') === 'medium' ? 'bg-gray-50' : '' ?>">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                Medium
                            </a>
                            <a href="<?= base_url('support/notifications?' . http_build_query(array_merge($_GET, ['priority' => 'low']))) ?>"
                                class="w-full px-4 py-3 text-left text-sm hover:bg-gray-50 transition-colors flex items-center gap-2 <?= $request->getGet('priority') === 'low' ? 'bg-gray-50' : '' ?>">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                Low
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Title -->
    <div class="mb-4 flex justify-between items-center">
        <h2 class="text-lg md:text-[20px] font-medium text-text-dark">
            <?php
            $filterText = 'All Notifications';
            if ($request->getGet('filter') && $request->getGet('filter') !== 'all') {
                $filterText = ucfirst($request->getGet('filter')) . ' Notifications';
            }
            echo $filterText;
            ?>
            (<?= count($notifications) ?>)
        </h2>

        <?php if ($request->getGet('search') || $request->getGet('filter') || $request->getGet('priority')): ?>
            <a href="<?= base_url('support/notifications') ?>"
                class="text-sm text-secondary hover:text-[#665C9E] font-medium">
                <i class="fas fa-times mr-1"></i> Clear filters
            </a>
        <?php endif; ?>
    </div>

    <!-- Notifications Container -->
    <div
        class="bg-gradient-to-r from-[#3D3C5E] to-[#48466B] rounded-2xl shadow-sm border border-[#AEA3CA] overflow-hidden mb-8">
        <!-- Notifications List -->
        <div class="p-4 md:p-6 space-y-3 md:space-y-4 max-h-[500px] overflow-y-auto custom-scrollbar"
            id="notificationsContainer">
            <?php if (empty($notifications)): ?>
                <!-- Empty State -->
                <div class="p-8 md:p-12 text-center">
                    <div
                        class="w-16 h-16 md:w-20 md:h-20 mx-auto mb-4 bg-white/10 rounded-full flex items-center justify-center">
                        <i class="fas fa-bell-slash text-white/40 text-2xl md:text-3xl"></i>
                    </div>
                    <h3 class="text-lg md:text-xl font-semibold text-white mb-2">
                        <?= $request->getGet('search') ? 'No notifications found' : 'No notifications yet' ?>
                    </h3>
                    <p class="text-white/60 max-w-md mx-auto">
                        <?php if ($request->getGet('search')): ?>
                            Try adjusting your search or filters
                        <?php else: ?>
                            When you have notifications, they'll appear here
                        <?php endif; ?>
                    </p>
                    <?php if ($request->getGet('search') || $request->getGet('filter') || $request->getGet('priority')): ?>
                        <a href="<?= base_url('support/notifications') ?>"
                            class="mt-4 inline-block px-4 py-2 bg-white/20 text-white rounded-lg hover:bg-white/30 transition-colors">
                            Clear filters
                        </a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <?php foreach ($notifications as $notification): ?>
                    <?php
                    // Determine priority
                    $priority = strtolower($notification['priority_name'] ?? 'medium');
                    $priorityClass = '';
                    $priorityText = '';

                    switch ($priority) {
                        case 'urgent':
                            $priorityClass = 'bg-[#E16D7F] text-white';
                            $priorityText = 'Urgent';
                            break;
                        case 'high':
                            $priorityClass = 'bg-[#FFD2D2] text-red-800';
                            $priorityText = 'High';
                            break;
                        case 'medium':
                            $priorityClass = 'bg-[#FED7AA] text-orange-800';
                            $priorityText = 'Medium';
                            break;
                        case 'low':
                            $priorityClass = 'bg-[#C7D2FE] text-blue-800';
                            $priorityText = 'Low';
                            break;
                        default:
                            $priorityClass = 'bg-gray-200 text-gray-800';
                            $priorityText = 'Normal';
                    }

                    // Determine type
                    $type = strtolower($notification['notification_type'] ?? 'system');

                    // Format time
                    function formatNotificationTime($datetime)
                    {
                        $time = strtotime($datetime);
                        $now = time();
                        $diff = $now - $time;

                        if ($diff < 60)
                            return 'Just now';
                        elseif ($diff < 3600)
                            return floor($diff / 60) . ' minutes ago';
                        elseif ($diff < 86400)
                            return floor($diff / 3600) . ' hours ago';
                        elseif ($diff < 604800)
                            return floor($diff / 86400) . ' days ago';
                        else
                            return date('M d, Y', $time);
                    }

                    $timeAgo = formatNotificationTime($notification['created_at']);

                    // Get ticket link if exists
                    $ticketLink = $notification['ticket_id'] ?
                        base_url('support/ticket_detail/' . $notification['ticket_id']) :
                        '#';
                    ?>

                    <div class="notification-item bg-white/10 rounded-xl p-4 md:p-5 hover:bg-white/15 transition-colors <?= !$notification['is_read'] ? 'notification-unread' : '' ?>"
                        data-priority="<?= $priority ?>" data-type="<?= $type ?>"
                        data-id="<?= $notification['notification_id'] ?>">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-2">
                                    <h3 class="text-base md:text-lg font-bold text-white">
                                        <?= esc($notification['title']) ?>
                                    </h3>
                                    <span class="text-xs md:text-sm text-white/60"><?= $timeAgo ?></span>
                                </div>
                                <p class="text-sm md:text-[14px] text-white/80 mb-3">
                                    <?= esc($notification['message']) ?>
                                    <?php if ($notification['ticket_number']): ?>
                                        <br>
                                        <span class="font-medium">Ticket: <?= esc($notification['ticket_number']) ?> -
                                            <?= esc($notification['ticket_subject'] ?? '') ?></span>
                                    <?php endif; ?>
                                </p>
                                <div class="flex items-center gap-2">
                                    <div class="priority-badge px-3 py-1 rounded-lg <?= $priorityClass ?>">
                                        <span class="text-xs font-bold"><?= $priorityText ?></span>
                                    </div>
                                    <span class="text-xs text-white/60 px-2 py-1 bg-white/10 rounded">
                                        <?= ucfirst($type) ?>
                                    </span>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-3">
                                <?php if (!$notification['is_read']): ?>
                                    <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center unread-indicator">
                                        <div class="w-2 h-2 bg-white rounded-full"></div>
                                    </div>
                                <?php else: ?>
                                    <div class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center">
                                        <div class="w-2 h-2 bg-white/50 rounded-full"></div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($notification['ticket_id']): ?>
                                    <a href="<?= $ticketLink ?>"
                                        class="text-white/60 hover:text-white transition-colors text-sm px-3 py-1 bg-white/10 rounded-lg">
                                        View Ticket
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Load More Button (if there are more notifications) -->
        <?php if (count($notifications) >= 20): ?>
            <div class="p-4 border-t border-white/10 text-center">
                <button id="loadMoreBtn"
                    class="px-6 py-2 bg-white/10 text-white rounded-lg hover:bg-white/20 transition-colors">
                    <i class="fas fa-spinner fa-spin hidden mr-2"></i>
                    Load More Notifications
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- JavaScript tetap sama -->
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

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.6;
        }
    }

    .unread-indicator {
        animation: pulse 2s ease-in-out infinite;
    }

    .notification-unread {
        background-color: rgba(255, 255, 255, 0.15) !important;
        border-left: 3px solid #756EA4;
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
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Mark all as read button
        const markAllReadBtn = document.querySelector('button[type="submit"]');
        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', function (e) {
                // Show loading state
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
                this.disabled = true;

                // Form will submit normally via POST
            });
        }

        // Mark individual notification as read
        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function (e) {
                // Don't trigger if clicking a link or button
                if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON' ||
                    e.target.closest('a') || e.target.closest('button')) {
                    return;
                }

                const notificationId = this.getAttribute('data-id');
                const isUnread = this.classList.contains('notification-unread');

                if (isUnread) {
                    markNotificationAsRead(notificationId, this);
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
        }

        // Filter by type buttons
        document.querySelectorAll('button[data-filter]').forEach(btn => {
            btn.addEventListener('click', function () {
                const filterType = this.getAttribute('data-filter');
                const url = new URL(window.location.href);

                if (filterType === 'all') {
                    url.searchParams.delete('filter');
                } else {
                    url.searchParams.set('filter', filterType);
                }

                // Reset pagination
                url.searchParams.delete('page');

                window.location.href = url.toString();
            });
        });

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const searchForm = document.getElementById('searchForm');

        if (searchInput && searchForm) {
            let searchTimeout;
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    // Submit form after typing stops
                    searchForm.submit();
                }, 800);
            });

            // Also allow Enter key
            searchInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchForm.submit();
                }
            });
        }

        // Load more button
        const loadMoreBtn = document.getElementById('loadMoreBtn');
        if (loadMoreBtn) {
            let loading = false;
            let page = 1;

            loadMoreBtn.addEventListener('click', async function () {
                if (loading) return;

                loading = true;
                const spinner = this.querySelector('.fa-spinner');
                spinner.classList.remove('hidden');
                this.disabled = true;

                try {
                    // Get current filter parameters
                    const params = new URLSearchParams(window.location.search);
                    params.set('page', page + 1);

                    const response = await fetch(`<?= base_url('support/notifications') ?>?${params.toString()}&ajax=1`);
                    const html = await response.text();

                    if (html.trim()) {
                        // Parse HTML and extract notifications
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newNotifications = doc.querySelectorAll('.notification-item');

                        if (newNotifications.length > 0) {
                            // Append new notifications
                            const container = document.getElementById('notificationsContainer');
                            newNotifications.forEach(notification => {
                                container.appendChild(notification);
                            });

                            page++;

                            // Attach click events to new notifications
                            newNotifications.forEach(item => {
                                item.addEventListener('click', function (e) {
                                    if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON' ||
                                        e.target.closest('a') || e.target.closest('button')) {
                                        return;
                                    }

                                    const notificationId = this.getAttribute('data-id');
                                    const isUnread = this.classList.contains('notification-unread');

                                    if (isUnread) {
                                        markNotificationAsRead(notificationId, this);
                                    }
                                });
                            });

                            showToast('More notifications loaded', 'success');
                        } else {
                            loadMoreBtn.textContent = 'No more notifications';
                            loadMoreBtn.disabled = true;
                        }
                    }
                } catch (error) {
                    console.error('Error loading more notifications:', error);
                    showToast('Error loading more notifications', 'error');
                } finally {
                    loading = false;
                    spinner.classList.add('hidden');
                    loadMoreBtn.disabled = false;
                }
            });
        }

        // Function to mark notification as read
        async function markNotificationAsRead(notificationId, element) {
            try {
                const response = await fetch('<?= base_url("support/mark_notification_read") ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        notification_id: notificationId,
                        csrf_token: '<?= csrf_hash() ?>'
                    })
                });

                const result = await response.json();
                if (result.success) {
                    // Update UI
                    element.classList.remove('notification-unread');
                    const indicator = element.querySelector('.unread-indicator');
                    if (indicator) {
                        indicator.classList.remove('unread-indicator');
                        indicator.querySelector('.w-2.h-2').style.opacity = '0.5';
                    }

                    // Update unread count in stats
                    const unreadElement = document.querySelector('.text-3xl.font-bold.text-gray-800');
                    if (unreadElement) {
                        const currentUnread = parseInt(unreadElement.textContent || '0');
                        if (currentUnread > 0) {
                            unreadElement.textContent = currentUnread - 1;
                            showToast('Notification marked as read', 'success');
                        }
                    }
                }
            } catch (error) {
                console.error('Error marking as read:', error);
            }
        }

        // Function to check if empty state should be shown
        function checkEmptyState() {
            const notifications = document.querySelectorAll('.notification-item');
            const emptyState = document.getElementById('emptyState');

            if (notifications.length === 0) {
                if (emptyState) emptyState.classList.remove('hidden');
            } else {
                if (emptyState) emptyState.classList.add('hidden');
            }
        }

        // Toast notification function
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

        // Initialize
        checkEmptyState();
    });
</script>
<?= $this->endSection() ?>