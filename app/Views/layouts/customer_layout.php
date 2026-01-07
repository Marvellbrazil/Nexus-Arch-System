<?php
$session = \Config\Services::session();
$success = $session->getFlashdata('success');
$error = $session->getFlashdata('error');
$message = $session->getFlashdata('message');
$search_message = $session->getFlashdata('search_message');

// Simulasi data notifikasi (nanti bisa dari database)
$notification_count = 3;
$notifications = [
    ['id' => 1, 'title' => 'New message from support', 'message' => 'Your ticket #10421 has been updated', 'time' => '2 mins ago', 'read' => false, 'type' => 'message'],
    ['id' => 2, 'title' => 'Ticket resolved', 'message' => 'Ticket #10422 has been resolved', 'time' => '1 hour ago', 'read' => true, 'type' => 'success'],
    ['id' => 3, 'title' => 'New ticket assigned', 'message' => 'You have been assigned to ticket #10425', 'time' => '3 hours ago', 'read' => false, 'type' => 'assignment'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Customer Dashboard - NEXUS') ?></title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#434264',
                        'secondary': '#756EA4',
                        'accent': '#BA94ED',
                        'light-bg': '#D7D5EE',
                        'text-light': 'rgba(255, 255, 255, 0.80)',
                        'border-light': 'rgba(255, 255, 255, 0.10)',
                        'dark-bg': '#3D3C5E',
                        'nav-bg': '#D3CBE0',
                        'card-bg': '#F0E9F9',
                        'footer-bg': '#C8BFDC',
                        'text-dark': '#302B48',
                        'text-muted': '#3E3B5D',
                    },
                    fontFamily: {
                        'roboto': ['Roboto', 'sans-serif'],
                        'mulish': ['Mulish', 'sans-serif'],
                        'inter': ['Inter', 'sans-serif'],
                    },
                    blur: {
                        '100': '100px',
                    }
                }
            }
        }
    </script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&family=Mulish:wght@300;400;500;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideDown {
            from { transform: translateY(-10px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }
        
        .animate-slide-down {
            animation: slideDown 0.2s ease-out;
        }
        
        .notification-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            width: 18px;
            height: 18px;
            background-color: #EF4444;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: bold;
        }
        
        .notification-unread {
            background-color: rgba(117, 110, 164, 0.1);
            border-left: 3px solid #756EA4;
        }
        
        /* Smooth transitions for dropdown */
        .notification-dropdown {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s ease;
        }
        
        .notification-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
    </style>
    
    <?= $this->renderSection('styles') ?>
</head>
<body class="bg-light-bg min-h-screen relative overflow-x-hidden font-roboto">
    
    <!-- Background Effects -->
    <?= $this->renderSection('background_effects') ?>
    
    <!-- Alert Messages -->
    <?php if ($success): ?>
        <div class="fixed top-[90px] right-[20px] p-[15px_20px] rounded-lg z-[1000] max-w-[400px] animate-slide-in shadow-[0_4px_12px_rgba(0,0,0,0.1)] bg-[#4CAF50] text-white border-l-[5px] border-l-[#2E7D32]">
            <i class="fas fa-check-circle mr-2"></i> <?= esc($success) ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="fixed top-[90px] right-[20px] p-[15px_20px] rounded-lg z-[1000] max-w-[400px] animate-slide-in shadow-[0_4px_12px_rgba(0,0,0,0.1)] bg-[#f44336] text-white border-l-[5px] border-l-[#c62828]">
            <i class="fas fa-exclamation-circle mr-2"></i> <?= esc($error) ?>
        </div>
    <?php endif; ?>

    <?php if ($message): ?>
        <div class="fixed top-[90px] right-[20px] p-[15px_20px] rounded-lg z-[1000] max-w-[400px] animate-slide-in shadow-[0_4px_12px_rgba(0,0,0,0.1)] bg-[#2196F3] text-white border-l-[5px] border-l-[#1565C0]">
            <i class="fas fa-info-circle mr-2"></i> <?= esc($message) ?>
        </div>
    <?php endif; ?>

    <?php if ($search_message): ?>
        <div class="fixed top-[90px] right-[20px] p-[15px_20px] rounded-lg z-[1000] max-w-[400px] animate-slide-in shadow-[0_4px_12px_rgba(0,0,0,0.1)] bg-[#2196F3] text-white border-l-[5px] border-l-[#1565C0]">
            <i class="fas fa-search mr-2"></i> <?= esc($search_message) ?>
        </div>
    <?php endif; ?>

    <!-- Header -->
    <header class="bg-nav-bg h-[77px] w-full fixed top-0 left-0 z-50 flex items-center justify-between px-[30px] shadow-[0_2px_10px_rgba(0,0,0,0.1)]">
        <div class="flex items-center gap-[15px]">
            <div class="w-[25px] h-[25px] bg-primary rounded-[5px] flex items-center justify-center text-white font-bold">
                N
            </div>
            <div class="text-[#817CB2] text-[16px] font-bold">
                NEXUS
            </div>
        </div>
        
        <nav class="flex gap-[40px] ml-[50px]">
            <a href="<?= base_url('dashboard') ?>" 
               class="text-[#434264] text-[16px] font-bold cursor-pointer transition-colors duration-300 px-[10px] py-[5px] rounded <?= current_url() == base_url('dashboard') ? 'bg-secondary/10 text-secondary' : 'hover:bg-secondary/10 hover:text-secondary' ?>">
                Home
            </a>
            <a href="<?= base_url('dashboard/my_tickets') ?>" 
               class="text-[#434264] text-[16px] font-bold cursor-pointer transition-colors duration-300 px-[10px] py-[5px] rounded <?= current_url() == base_url('dashboard/my_tickets') ? 'bg-secondary/10 text-secondary' : 'hover:bg-secondary/10 hover:text-secondary' ?>">
                My Tickets
            </a>
            <a href="<?= base_url('dashboard/notifications') ?>" 
               class="text-[#434264] text-[16px] font-bold cursor-pointer transition-colors duration-300 px-[10px] py-[5px] rounded <?= current_url() == base_url('dashboard/notifications') ? 'bg-secondary/10 text-secondary' : 'hover:bg-secondary/10 hover:text-secondary' ?>">
                Notifications
            </a>
        </nav>
        
        <div class="flex items-center gap-[20px]">
            <!-- Notification Bell -->
            <div class="relative">
                <button id="notificationButton" class="relative p-2 text-[#434264] hover:text-secondary transition-colors">
                    <i class="fas fa-bell text-xl"></i>
                    <?php if ($notification_count > 0): ?>
                        <span class="notification-badge"><?= $notification_count ?></span>
                    <?php endif; ?>
                </button>
                
                <!-- Notification Dropdown -->
                <div id="notificationDropdown" class="notification-dropdown absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50 overflow-hidden">
                    <div class="p-4 border-b border-gray-200 bg-secondary text-white">
                        <div class="flex justify-between items-center">
                            <h3 class="font-bold text-lg">Notifications</h3>
                            <span class="text-sm"><?= $notification_count ?> new</span>
                        </div>
                    </div>
                    
                    <div class="max-h-96 overflow-y-auto">
                        <?php if (empty($notifications)): ?>
                            <div class="p-4 text-center text-gray-500">
                                <i class="fas fa-bell-slash text-2xl mb-2"></i>
                                <p>No notifications</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($notifications as $notification): ?>
                                <a href="<?= base_url('dashboard/notifications/' . $notification['id']) ?>" 
                                   class="block p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors <?= !$notification['read'] ? 'notification-unread' : '' ?>">
                                    <div class="flex gap-3">
                                        <div class="flex-shrink-0">
                                            <?php if ($notification['type'] == 'message'): ?>
                                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-comment text-blue-600"></i>
                                                </div>
                                            <?php elseif ($notification['type'] == 'success'): ?>
                                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-check-circle text-green-600"></i>
                                                </div>
                                            <?php else: ?>
                                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-ticket-alt text-purple-600"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-gray-800 truncate"><?= esc($notification['title']) ?></p>
                                            <p class="text-sm text-gray-600 mt-1"><?= esc($notification['message']) ?></p>
                                            <p class="text-xs text-gray-500 mt-2"><?= esc($notification['time']) ?></p>
                                        </div>
                                        <?php if (!$notification['read']): ?>
                                            <div class="flex-shrink-0">
                                                <span class="w-2 h-2 bg-secondary rounded-full"></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <div class="p-3 border-t border-gray-200 bg-gray-50">
                        <a href="<?= base_url('dashboard/notifications') ?>" class="block text-center text-secondary font-medium hover:text-[#665C9E] transition-colors">
                            View All Notifications
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- User Profile -->
            <div class="flex items-center gap-[15px]">
                <div class="text-[#434264] text-[14px] font-bold">Username</div>
                <div class="w-[37px] h-[37px] bg-primary rounded-full flex items-center justify-center text-white font-bold text-[14px]">
                    U
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="mt-[77px] min-h-[calc(100vh-151px)]">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-footer-bg h-[74px] w-full flex items-center px-[30px]">
        <div class="text-text-dark text-[14px] font-normal">
            Copyright © <?= date('Y') ?> NexusArchSystem. All Rights Reserved.
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alerts
            const alerts = document.querySelectorAll('.fixed.top-\\[90px\\]');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateX(100%)';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });

            // Notification dropdown toggle
            const notificationButton = document.getElementById('notificationButton');
            const notificationDropdown = document.getElementById('notificationDropdown');
            
            if (notificationButton && notificationDropdown) {
                notificationButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    notificationDropdown.classList.toggle('show');
                    
                    // Mark notifications as read when dropdown is opened
                    if (notificationDropdown.classList.contains('show')) {
                        markNotificationsAsRead();
                    }
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!notificationButton.contains(e.target) && !notificationDropdown.contains(e.target)) {
                        notificationDropdown.classList.remove('show');
                    }
                });
                
                // Prevent dropdown from closing when clicking inside
                notificationDropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
            
            function markNotificationsAsRead() {
                // In a real application, this would be an AJAX call to update the notification status
                console.log('Marking notifications as read...');
                // Update badge count
                const badge = document.querySelector('.notification-badge');
                if (badge) {
                    badge.style.display = 'none';
                }
                
                // Update notification items
                const unreadIndicators = document.querySelectorAll('.notification-unread');
                unreadIndicators.forEach(indicator => {
                    indicator.classList.remove('notification-unread');
                });
            }
            
            // Update notification count in real-time (simulated)
            function updateNotificationCount(count) {
                const badge = document.querySelector('.notification-badge');
                if (badge) {
                    badge.textContent = count;
                    badge.style.display = count > 0 ? 'flex' : 'none';
                }
            }
            
            // Simulate new notification (for demo purposes)
            setInterval(() => {
                // Only simulate if user is on dashboard
                if (window.location.pathname.includes('dashboard') && Math.random() > 0.7) {
                    const currentCount = parseInt(document.querySelector('.notification-badge')?.textContent || '0');
                    updateNotificationCount(currentCount + 1);
                }
            }, 30000); // Every 30 seconds
        });
    </script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>