<?= $this->extend('layouts/customer_layout') ?>

<?= $this->section('title') ?>Notifications - NEXUS<?= $this->endSection() ?>

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

<?= $this->section('styles') ?>
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

    /* Notification styles */
    .notification-item {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .notification-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
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

    /* Custom scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #F0E9F9;
        border-radius: 3px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #756EA4;
        border-radius: 3px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #665C9E;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mt-16 md:mt-20 px-4 md:px-6 lg:px-8 py-6 relative z-10 max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8 md:mb-10">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl lg:text-[34px] font-semibold text-text-dark mb-2">Notifications</h1>
                <p class="text-sm md:text-base text-text-muted font-light">See what's new in your inbox</p>
            </div>
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                <div class="bg-gradient-to-r from-secondary to-[#8A84C6] rounded-2xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-white/80 text-sm mb-1">Total</div>
                            <div class="text-3xl font-bold"><?= $data['stats']['total_notifications'] ?></div>
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
                            <div class="text-3xl font-bold text-gray-800"><?= $data['stats']['unread_notifications'] ?>
                            </div>
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
                            <div class="text-3xl font-bold text-gray-800">
                                <?= $data['stats']['this_week_notifications'] ?></div>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-week text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notification Actions -->
            <div class="flex items-center gap-3">
                <button class="px-4 py-2 bg-white text-secondary border border-secondary rounded-lg hover:bg-secondary/5 transition-all text-sm font-medium flex items-center gap-2"
                onclick="location.href='<?= base_url('customer/notifications/mark_all_read') ?>'">
                    <i class="fas fa-check-double"></i>
                    Mark All as Read
                </button>
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
                    <input type="text" 
                        placeholder="Search notifications..." 
                        class="w-full h-12 md:h-14 pl-12 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm md:text-base focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
                </div>
            </div>

            <!-- Filter Buttons -->
            <div class="flex gap-3">
                <!-- Priority Dropdown -->
                <div class="relative">
                    <button
                        class="h-12 md:h-14 px-4 md:px-6 bg-white border border-[#D1D1E9] rounded-xl text-text-dark text-sm md:text-base font-medium flex items-center gap-2 hover:bg-gray-50 transition-colors">
                        <span>Choose Priority</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div
                        class="absolute top-full right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 z-50 hidden">
                        <div class="py-2">
                            <button
                                class="w-full px-4 py-3 text-left text-sm hover:bg-gray-50 transition-colors flex items-center gap-2">
                                <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                                All Priorities
                            </button>
                            <button
                                class="w-full px-4 py-3 text-left text-sm hover:bg-gray-50 transition-colors flex items-center gap-2">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                Urgent
                            </button>
                            <button
                                class="w-full px-4 py-3 text-left text-sm hover:bg-gray-50 transition-colors flex items-center gap-2">
                                <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                                High
                            </button>
                            <button
                                class="w-full px-4 py-3 text-left text-sm hover:bg-gray-50 transition-colors flex items-center gap-2">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                Medium
                            </button>
                            <button
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
        <h2 class="text-lg md:text-xl font-medium text-text-dark">All Notifications</h2>
    </div>


    <!-- Notifications Container -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-8">
        <div class="p-4 md:p-6 space-y-4 custom-scrollbar"
            style="min-height: 400px; max-height: 500px; overflow-y: auto;">
            <?php if (!empty($data['notifications'])): ?>
                <?php foreach ($data['notifications'] as $notification): ?>
                    <div
                        class="notification-item bg-card-bg rounded-xl p-4 md:p-5 hover:bg-card-bg/80 transition-colors animate-slide-in">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-2">
                                    <h3 class="text-base md:text-lg font-bold text-text-dark"><?= $notification['title'] ?></h3>
                                    <span class="text-xs md:text-sm text-gray-500"><?= date('F d, Y H:i:s', strtotime($notification['created_at'])) ?></span>
                                </div>
                                <p class="text-sm md:text-base text-gray-700 mb-3"><?= $notification['message'] ?></p>
                                <div class="flex items-center gap-2">
                                    <div class="priority-badge px-3 py-1 bg-[#E16D7F] rounded-lg">
                                        <span class="text-xs font-bold text-white"><?= $notification['notification_type'] ?></span>
                                    </div>
                                    <div class="priority-badge px-3 py-1 <?= $notification['is_read'] == 't' ? 'bg-[#66BBFF]' : 'bg-[#a9a9a9]' ?> rounded-lg">
                                        <span class="text-xs font-bold text-white"><?= $notification['is_read'] == 't' ? 'Read' : 'Unread' ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-24 items-center">
                    <div class="bg-gray-100 p-4 rounded-full mb-4">
                        <i class="fas fa-bell text-gray-400 text-2xl"></i>
                    </div>
                    <p class="text-gray-500">There is no notification yet</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<script>
    let wsConnection = null;
    const WS_URL = '<?= \App\Helpers\WebSocketHelper::getSocketUrl() ?: "http://localhost:3000" ?>'.replace('http://', 'ws://').replace('https://', 'wss://');

    // Initialize WebSocket connection
    function initWebSocket() {
        try {
            wsConnection = new WebSocket(WS_URL);
            
            wsConnection.onopen = function() {
                console.log('WebSocket connected for notifications');
                
                // Authenticate dengan user data
                const userData = {
                    type: 'authenticate',
                    user_id: '<?= session()->get("user_id") ?>',
                    role: '<?= session()->get("role") ?>',
                    department_id: '<?= session()->get("department_id") ?>'
                };
                wsConnection.send(JSON.stringify(userData));
                
                // Join personal notification room
                wsConnection.send(JSON.stringify({
                    type: 'join_notifications',
                    user_id: '<?= session()->get("user_id") ?>'
                }));
            };
            
            wsConnection.onmessage = function(event) {
                try {
                    const data = JSON.parse(event.data);
                    
                    if (data.type === 'new_notification') {
                        handleNewNotification(data);
                    }
                } catch (error) {
                    console.error('Error processing WebSocket message:', error);
                }
            };
            
            wsConnection.onerror = function(error) {
                console.error('WebSocket error:', error);
            };
            
            wsConnection.onclose = function() {
                console.log('WebSocket disconnected');
            };
            
        } catch (error) {
            console.error('Failed to initialize WebSocket:', error);
        }
    }

    // Handle new notification from WebSocket
    function handleNewNotification(notificationData) {
        // Show desktop notification if permitted
        if ("Notification" in window && Notification.permission === "granted") {
            new Notification(notificationData.title, {
                body: notificationData.message,
                icon: '/favicon.ico'
            });
        }
        
        // Add notification to UI
        addNotificationToUI(notificationData);
        
        // Update counters
        updateNotificationCounters(1, 1);
        
        // Show toast
        showToast(`New notification: ${notificationData.title}`, 'info');
    }

    // Add notification to UI
    function addNotificationToUI(notification) {
        const container = document.querySelector('.space-y-4');
        const emptyState = container.querySelector('.text-center');
        
        // Hide empty state if visible
        if (emptyState) {
            emptyState.remove();
        }
        
        // Create notification element
        const notificationElement = document.createElement('div');
        notificationElement.className = 'notification-item bg-card-bg rounded-xl p-4 md:p-5 hover:bg-card-bg/80 transition-colors animate-slide-in';
        
        const timeAgo = formatTimeAgo(notification.created_at);
        
        notificationElement.innerHTML = `
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-2">
                        <h3 class="text-base md:text-lg font-bold text-text-dark">${notification.title}</h3>
                        <span class="unread-indicator px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">
                            NEW
                        </span>
                        <span class="text-xs md:text-sm text-gray-500">
                            ${timeAgo}
                        </span>
                    </div>
                    <p class="text-sm md:text-base text-gray-700 mb-3">${notification.message}</p>
                    <div class="flex items-center gap-2">
                        <div class="priority-badge px-3 py-1 bg-[#E16D7F] rounded-lg">
                            <span class="text-xs font-bold text-white">${notification.type || 'system'}</span>
                        </div>
                        <div class="priority-badge px-3 py-1 bg-[#a9a9a9] rounded-lg">
                            <span class="text-xs font-bold text-white">Unread</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Insert at the top
        container.insertBefore(notificationElement, container.firstChild);
    }

    // Update notification counters
    function updateNotificationCounters(totalChange = 0, unreadChange = 0) {
        const totalElement = document.querySelector('.bg-gradient-to-r.from-secondary .text-3xl');
        const unreadElement = document.querySelector('.bg-white .text-gray-800');
        
        if (totalElement) {
            const currentTotal = parseInt(totalElement.textContent) || 0;
            totalElement.textContent = currentTotal + totalChange;
        }
        
        if (unreadElement) {
            const currentUnread = parseInt(unreadElement.textContent) || 0;
            unreadElement.textContent = currentUnread + unreadChange;
        }
    }

    // Format time ago
    function formatTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffMs = now - date;
        const diffSec = Math.floor(diffMs / 1000);
        const diffMin = Math.floor(diffSec / 60);
        const diffHour = Math.floor(diffMin / 60);
        const diffDay = Math.floor(diffHour / 24);
        
        if (diffSec < 60) return 'Just now';
        if (diffMin < 60) return `${diffMin} minute${diffMin > 1 ? 's' : ''} ago`;
        if (diffHour < 24) return `${diffHour} hour${diffHour > 1 ? 's' : ''} ago`;
        if (diffDay < 7) return `${diffDay} day${diffDay > 1 ? 's' : ''} ago`;
        
        return date.toLocaleDateString();
    }

    // Toast notification
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed top-24 right-4 md:right-6 p-4 rounded-lg shadow-lg z-[1000] max-w-sm animate-slide-in ${
            type === 'error' ? 'bg-red-500 text-white' :
            type === 'success' ? 'bg-green-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        toast.innerHTML = `
            <div class="flex items-center gap-2">
                <i class="fas ${
                    type === 'error' ? 'fa-exclamation-circle' :
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

    // Request notification permission
    function requestNotificationPermission() {
        if ("Notification" in window && Notification.permission === "default") {
            Notification.requestPermission().then(permission => {
                if (permission === "granted") {
                    console.log("Notification permission granted");
                }
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Initialize WebSocket
        initWebSocket();
        
        // Request notification permission
        requestNotificationPermission();
        
        // Mark all as read button
        const markAllReadBtn = document.querySelector('button:contains("Mark All as Read")');
        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', function () {
                // Remove unread indicators
                document.querySelectorAll('.unread-indicator').forEach(indicator => {
                    indicator.style.animation = 'none';
                    indicator.style.opacity = '0.5';
                });

                // Update unread count in stats
                const unreadElement = document.querySelector('.text-3xl.font-bold.text-gray-800');
                if (unreadElement) {
                    unreadElement.textContent = '0';
                }

                // Change button text and style
                this.innerHTML = '<i class="fas fa-check mr-2"></i>All Marked as Read';
                this.classList.remove('bg-white', 'text-secondary', 'border-secondary');
                this.classList.add('bg-green-500', 'text-white', 'border-green-500');

                // Show success toast
                showToast('All notifications marked as read', 'success');
            });
        }

        // Notification click to mark as read
        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function (e) {
                // Don't trigger if clicking the ellipsis button
                if (e.target.closest('button') || e.target.closest('.fas.fa-ellipsis-v')) {
                    return;
                }

                const indicator = this.querySelector('.unread-indicator');
                if (indicator) {
                    indicator.style.animation = 'none';
                    indicator.style.opacity = '0.5';

                    // Update unread count
                    const unreadElement = document.querySelector('.text-3xl.font-bold.text-gray-800');
                    if (unreadElement) {
                        const currentUnread = parseInt(unreadElement.textContent || '5');
                        if (currentUnread > 0) {
                            unreadElement.textContent = currentUnread - 1;
                        }
                    }
                }
            });
        });

        // Priority dropdown toggle
        const priorityBtn = document.querySelector('button:contains("Choose Priority")');
        const priorityDropdown = priorityBtn?.nextElementSibling;

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
            priorityDropdown.querySelectorAll('button').forEach(btn => {
                btn.addEventListener('click', function () {
                    const priorityText = this.textContent.trim();
                    priorityBtn.querySelector('span').textContent = priorityText;

                    // Filter notifications based on priority
                    if (priorityText === 'All Priorities') {
                        document.querySelectorAll('.notification-item').forEach(item => {
                            item.style.display = 'flex';
                        });
                    } else {
                        document.querySelectorAll('.notification-item').forEach(item => {
                            const badge = item.querySelector('.priority-badge span');
                            if (badge && badge.textContent === priorityText) {
                                item.style.display = 'flex';
                            } else {
                                item.style.display = 'none';
                            }
                        });
                    }

                    // Show/hide empty state
                    const visibleNotifications = document.querySelectorAll('.notification-item[style*="display: flex"]').length;
                    const emptyState = document.querySelector('.text-center.py-24');
                    const notificationsContainer = document.querySelector('.space-y-4');

                    if (visibleNotifications === 0 && priorityText !== 'All Priorities') {
                        if (!emptyState) {
                            notificationsContainer.innerHTML = `
                                <div class="text-center py-24 items-center">
                                    <div class="bg-gray-100 p-4 rounded-full mb-4">
                                        <i class="fas fa-bell text-gray-400 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-500">No notifications match your filters</p>
                                </div>
                            `;
                        }
                    } else if (emptyState && visibleNotifications > 0) {
                        emptyState.remove();
                    }

                    priorityDropdown.classList.add('hidden');
                });
            });
        }

        // Search functionality
        const searchInput = document.querySelector('input[placeholder="Search notifications..."]');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const searchTerm = this.value.toLowerCase().trim();
                const notifications = document.querySelectorAll('.notification-item');
                const emptyState = document.querySelector('.text-center.py-24');

                let visibleCount = 0;

                notifications.forEach(notification => {
                    const title = notification.querySelector('h3')?.textContent.toLowerCase() || '';
                    const content = notification.querySelector('.text-gray-700')?.textContent.toLowerCase() || '';
                    const time = notification.querySelector('.text-gray-500')?.textContent.toLowerCase() || '';

                    if (searchTerm === '' ||
                        title.includes(searchTerm) ||
                        content.includes(searchTerm) ||
                        time.includes(searchTerm)) {
                        notification.style.display = 'flex';
                        visibleCount++;
                    } else {
                        notification.style.display = 'none';
                    }
                });

                // Show/hide empty state
                if (visibleCount === 0 && searchTerm !== '') {
                    if (!emptyState) {
                        const container = document.querySelector('.space-y-4');
                        container.innerHTML = `
                            <div class="text-center py-24 items-center">
                                <div class="bg-gray-100 p-4 rounded-full mb-4">
                                    <i class="fas fa-bell text-gray-400 text-2xl"></i>
                                </div>
                                <p class="text-gray-500">No notifications match your search</p>
                            </div>
                        `;
                    }
                } else if (emptyState && visibleCount > 0) {
                    emptyState.remove();
                }
            });
        }
    });

    // Close WebSocket on page unload
    window.addEventListener('beforeunload', function() {
        if (wsConnection && wsConnection.readyState === WebSocket.OPEN) {
            wsConnection.close();
        }
    });
</script>
<?= $this->endSection() ?>