<?php
use App\Models\UserModel;
use Config\Services;
use Config\Database;

$session = Services::session();
$success = $session->getFlashdata('success');
$error = $session->getFlashdata('error');
$message = $session->getFlashdata('message');
$search_message = $session->getFlashdata('search_message');

$userModel = new UserModel();
$username = $userModel->where('user_id', session()->get('user_id'))->get()->getRowArray()['full_name'];

$db = Database::connect();
$notification_count = $db->table('notifications')->where('user_id', session()->get('user_id'))->countAllResults();
$notifications = $db->table('notifications')->where('user_id', session()->get('user_id'))->get()->getResultArray();
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
                        'poppins': ['Poppins', 'sans-serif'],
                    },
                    blur: {
                        '100': '100px',
                    },
                    screens: {
                        'xs': '480px',
                        'sm': '640px',
                        'md': '768px',
                        'lg': '1024px',
                        'xl': '1280px',
                        '2xl': '1536px',
                    }
                }
            }
        }
    </script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&family=Mulish:wght@300;400;500;600;700&family=Inter:wght@400;500;600&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    
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
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes float {
            0%, 100% { transform: translateX(-50%) translateY(0px); }
            50% { transform: translateX(-50%) translateY(-5px); }
        }
        
        @keyframes backgroundTransition {
            from { background-color: transparent; }
            to { background-color: #756EA4; }
        }
        
        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }
        
        .animate-slide-down {
            animation: slideDown 0.2s ease-out;
        }
        
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        
        .animate-background-transition {
            animation: backgroundTransition 0.3s ease-out;
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
        
        /* Liquid Glass Navbar Styles - FIXED CENTER */
        .liquid-glass-navbar {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 
                0 8px 32px 0 rgba(31, 38, 135, 0.07),
                0 4px 16px 0 rgba(0, 0, 0, 0.05),
                inset 0 0 0 1px rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            width: auto;
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 50;
            max-width: calc(100% - 40px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            height: 60px;
            animation: float 6s ease-in-out infinite;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Scrolled State - Solid Background */
        .liquid-glass-navbar.scrolled {
            background: #756EA4 !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            animation: none;
        }
        
        .liquid-glass-navbar.scrolled .nav-glass-item {
            color: white !important;
        }
        
        .liquid-glass-navbar.scrolled .nav-glass-item:hover {
            background: rgba(255, 255, 255, 0.2) !important;
            color: white !important;
        }
        
        .liquid-glass-navbar.scrolled .nav-glass-item.active {
            background: rgba(255, 255, 255, 0.25) !important;
            color: white !important;
        }
        
        .liquid-glass-navbar.scrolled .logo-glass {
            background: rgba(255, 255, 255, 0.2) !important;
        }
        
        .liquid-glass-navbar.scrolled .notification-bell-glass {
            background: rgba(255, 255, 255, 0.2) !important;
            color: white !important;
        }
        
        .liquid-glass-navbar.scrolled .hamburger-glass {
            background: rgba(255, 255, 255, 0.2) !important;
        }
        
        .liquid-glass-navbar.scrolled .hamburger-line {
            background: white !important;
        }
        
        .liquid-glass-navbar.scrolled .text-primary {
            color: white !important;
        }
        
        .liquid-glass-navbar.scrolled .user-avatar-glass {
            background: white !important;
            color: #756EA4 !important;
            border: 2px solid rgba(255, 255, 255, 0.5) !important;
        }
        
        @media (max-width: 768px) {
            .liquid-glass-navbar {
                padding: 0 16px;
                height: 56px;
                justify-content: space-between;
                animation: none;
                position: fixed;
                left: 20px;
                right: 20px;
                transform: none;
                max-width: none;
                width: auto;
                margin: 20px 0;
            }
            
            .liquid-glass-navbar.scrolled {
                left: 0;
                right: 0;
                top: 0;
                border-radius: 0;
                margin: 0;
                border: none;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            }
        }
        
        .nav-glass-item {
            background: transparent;
            color: #434264;
            position: relative;
            transition: all 0.3s ease;
            padding: 8px 16px;
            border-radius: 16px;
            font-weight: 500;
            text-decoration: none;
            white-space: nowrap;
        }
        
        .nav-glass-item:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #756EA4;
            transform: translateY(-2px);
        }
        
        .nav-glass-item.active {
            background: rgba(117, 110, 164, 0.15);
            color: #756EA4;
            box-shadow: 0 4px 12px rgba(117, 110, 164, 0.15);
        }
        
        .nav-glass-item.active::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 6px;
            height: 6px;
            background: #756EA4;
            border-radius: 50%;
        }
        
        .user-avatar-glass {
            background: linear-gradient(135deg, #756EA4, #8A84C6);
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 15px rgba(117, 110, 164, 0.2);
            transition: all 0.3s ease;
        }
        
        .user-avatar-glass:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(117, 110, 164, 0.3);
        }
        
        /* Desktop Navigation Container */
        .desktop-nav-container {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        @media (max-width: 768px) {
            .desktop-nav-container {
                display: none;
            }
        }
        
        /* Mobile Glass Menu */
        .mobile-glass-menu {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 24px;
            margin: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        }
        
        .mobile-nav-item {
            padding: 14px 20px;
            border-radius: 16px;
            transition: all 0.3s ease;
            margin: 4px 0;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .mobile-nav-item:hover {
            background: rgba(117, 110, 164, 0.1);
        }
        
        .mobile-nav-item.active {
            background: rgba(117, 110, 164, 0.15);
            color: #756EA4;
            font-weight: 600;
        }
        
        /* Logo Glass Effect */
        .logo-glass {
            background: linear-gradient(135deg, rgba(117, 110, 164, 0.9), rgba(138, 132, 198, 0.9));
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.1);
        }

        /* Extra spacing for logo area to separate from center nav */
        .navbar-left {
            margin-right: 48px;
        }
        
        /* Notification Bell Glass */
        .notification-bell-glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            transition: all 0.3s ease;
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .notification-bell-glass:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        
        /* Navbar Separator */
        .nav-separator {
            height: 24px;
            width: 1px;
            background: rgba(67, 66, 100, 0.2);
            margin: 0 16px;
        }
        
        /* Main Content Padding for Floating Navbar */
        .main-content {
            padding-top: 100px;
        }
        
        @media (max-width: 768px) {
            .main-content {
                padding-top: 96px;
            }
        }
        
        /* Hamburger button for mobile */
        .hamburger-glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            width: 40px;
            height: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 4px;
            transition: all 0.3s ease;
        }
        
        .hamburger-glass:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .hamburger-line {
            width: 20px;
            height: 2px;
            background: #434264;
            border-radius: 1px;
            transition: all 0.3s ease;
        }
        
        /* Mobile Layout Specific */
        .mobile-navbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .mobile-navbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        @media (min-width: 769px) {
            .mobile-navbar-left,
            .mobile-navbar-right {
                display: none;
            }
        }
        
        /* Desktop Layout Specific */
        .desktop-layout {
            display: flex;
            align-items: center;
            gap: 30px;
        }
        
        @media (max-width: 768px) {
            .desktop-layout {
                display: none;
            }
        }
        
        /* Username display in navbar */
        .username-display {
            font-weight: 500;
            color: #434264;
            transition: all 0.3s ease;
        }
        
        .liquid-glass-navbar.scrolled .username-display {
            color: white !important;
        }
        
        /* Mobile profile section */
        .mobile-profile-section {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        @media (min-width: 769px) {
            .mobile-profile-section {
                display: none;
            }
        }
    </style>
    
    <?= $this->renderSection('styles') ?>
</head>
<body class="bg-light-bg min-h-screen relative overflow-x-hidden font-roboto">
    
    <!-- Background Effects -->
    <?= $this->renderSection('background_effects') ?>
    
    <!-- Alert Messages -->
    <?php if ($success): ?>
        <div class="fixed top-32 right-4 md:right-6 p-4 rounded-lg z-[1000] max-w-xs md:max-w-sm animate-slide-in shadow-lg bg-green-500 text-white border-l-4 border-green-600">
            <i class="fas fa-check-circle mr-2"></i> <?= esc($success) ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="fixed top-32 right-4 md:right-6 p-4 rounded-lg z-[1000] max-w-xs md:max-w-sm animate-slide-in shadow-lg bg-red-500 text-white border-l-4 border-red-600">
            <i class="fas fa-exclamation-circle mr-2"></i> <?= esc($error) ?>
        </div>
    <?php endif; ?>

    <?php if ($message): ?>
        <div class="fixed top-32 right-4 md:right-6 p-4 rounded-lg z-[1000] max-w-xs md:max-w-sm animate-slide-in shadow-lg bg-blue-500 text-white border-l-4 border-blue-600">
            <i class="fas fa-info-circle mr-2"></i> <?= esc($message) ?>
        </div>
    <?php endif; ?>

    <?php if ($search_message): ?>
        <div class="fixed top-32 right-4 md:right-6 p-4 rounded-lg z-[1000] max-w-xs md:max-w-sm animate-slide-in shadow-lg bg-blue-500 text-white border-l-4 border-blue-600">
            <i class="fas fa-search mr-2"></i> <?= esc($search_message) ?>
        </div>
    <?php endif; ?>

    <!-- Floating Liquid Glass Navbar (FIXED CENTER) -->
    <header class="liquid-glass-navbar">
        <!-- Mobile Layout (Visible only on mobile) -->
        <div class="mobile-navbar-left">
            <!-- Hamburger Menu Button -->
            <button id="hamburgerButton" class="hamburger-glass">
                <div class="hamburger-line"></div>
                <div class="hamburger-line"></div>
                <div class="hamburger-line"></div>
            </button>
            
            <!-- Logo (Mobile) -->
            <a href="<?= base_url('customer/dashboard') ?>" class="flex items-center gap-3 no-underline">
                <div class="w-8 h-8 logo-glass rounded-xl flex items-center justify-center">
                    <span class="text-white font-bold text-sm">N</span>
                </div>
                <div class="text-primary text-base font-bold whitespace-nowrap">
                    NEXUS
                </div>
            </a>
        </div>
        
        <!-- Desktop Layout (Center Navigation) -->
        <div class="desktop-layout">
            <!-- Logo (Desktop) -->
            <div class="hidden md:flex items-center gap-3 navbar-left">
                <a href="<?= base_url('customer/dashboard') ?>" class="flex items-center gap-3 no-underline">
                    <div class="w-8 h-8 logo-glass rounded-xl flex items-center justify-center">
                        <span class="text-white font-bold text-sm">N</span>
                    </div>
                    <div class="text-primary text-base md:text-lg font-bold whitespace-nowrap">NEXUS</div>
                </a>
            </div>
            <!-- Desktop Navigation -->
            <div class="desktop-nav-container">
                <a href="<?= base_url('customer/dashboard') ?>" 
                    class="nav-glass-item <?= current_url() == base_url('customer/dashboard') ? 'active' : '' ?>">
                    <i class="fas fa-home mr-2 text-sm"></i>
                    Dashboard
                </a>
                
                <a href="<?= base_url('customer/my_tickets') ?>" 
                    class="nav-glass-item <?= current_url() == base_url('customer/my_tickets') ? 'active' : '' ?>">
                    <i class="fas fa-ticket-alt mr-2 text-sm"></i>
                    My Tickets
                </a>
                
                <a href="<?= base_url('customer/create_ticket') ?>" 
                    class="nav-glass-item <?= current_url() == base_url('customer/create_ticket') ? 'active' : '' ?>">
                    <i class="fas fa-plus mr-2 text-sm"></i>
                    Create Ticket
                </a>
                
                <div class="nav-separator"></div>

                <!-- Notification placed next to Profile in center nav -->
                <div class="relative" title="Notification (<?= $notification_count ?>)">
                    <button id="notificationButton" class="notification-bell-glass p-2 text-primary hover:text-secondary transition-colors">
                        <i class="fas fa-bell text-lg"></i>
                        <?php if ($notification_count > 0): ?>
                            <span class="notification-badge"><?= $notification_count ?></span>
                        <?php endif; ?>
                    </button>

                    <div id="notificationDropdown" class="notification-dropdown absolute right-0 mt-3 w-72 md:w-80 bg-white rounded-2xl shadow-xl border border-gray-200 z-50 overflow-hidden">
                        <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-secondary to-[#8A84C6] text-white rounded-t-2xl">
                            <div class="flex justify-between items-center">
                                <h3 class="font-bold text-sm md:text-base">Customer Notifications</h3>
                                <span class="text-xs md:text-sm bg-white/20 px-2 py-1 rounded-full"><?= $notification_count ?> new</span>
                            </div>
                        </div>
                        <div class="max-h-64 md:max-h-96 overflow-y-auto">
                            <?php if (empty($notifications)): ?>
                                <div class="p-6 text-center text-gray-500">
                                    <i class="fas fa-bell-slash text-2xl md:text-3xl mb-3 text-gray-300"></i>
                                    <p class="text-sm">No notifications</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($notifications as $notification): ?>
                                    <a href="<?= base_url('customer/notifications/' . $notification['notification_id']) ?>" 
                                        class="block p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors <?= !$notification['is_read'] ? 'notification-unread' : '' ?>">
                                        <div class="flex gap-3">
                                            <div class="flex-shrink-0">
                                                <?php if ($notification['notification_type'] == 'message'): ?>
                                                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                                        <i class="fas fa-comment text-blue-600"></i>
                                                    </div>
                                                <?php elseif ($notification['notification_type'] == 'success'): ?>
                                                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                                                        <i class="fas fa-check-circle text-green-600"></i>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                                                        <i class="fas fa-ticket-alt text-purple-600"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="font-semibold text-gray-800 text-sm truncate"><?= esc($notification['title']) ?></p>
                                                <p class="text-gray-600 text-xs mt-1 truncate"><?= esc($notification['message']) ?></p>
                                                <p class="text-gray-500 text-xs mt-2"><?= esc($notification['created_at']) ?></p>
                                            </div>
                                            <?php if (!$notification['is_read']): ?>
                                                <div class="flex-shrink-0 mt-1">
                                                    <span class="w-2 h-2 bg-secondary rounded-full"></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="p-3 border-t border-gray-200 bg-gray-50">
                            <a href="<?= base_url('customer/notifications') ?>" class="block text-center text-secondary font-medium hover:text-[#665C9E] transition-colors text-sm">
                                View All Notifications
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mobile Right Section (Visible only on mobile) -->
        <div class="mobile-navbar-right">
            <!-- Notification Bell (Mobile) -->
            <div class="relative">
                <button id="mobileNotificationButton" class="notification-bell-glass p-2 text-primary hover:text-secondary transition-colors">
                    <i class="fas fa-bell text-lg"></i>
                    <?php if ($notification_count > 0): ?>
                        <span class="notification-badge"><?= $notification_count ?></span>
                    <?php endif; ?>
                </button>

                <div id="mobileNotificationDropdown" class="notification-dropdown absolute right-0 mt-3 w-72 bg-white rounded-2xl shadow-xl border border-gray-200 z-50 overflow-hidden">
                    <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-secondary to-[#8A84C6] text-white rounded-t-2xl">
                        <div class="flex justify-between items-center">
                            <h3 class="font-bold text-sm">Notifications</h3>
                            <span class="text-xs bg-white/20 px-2 py-1 rounded-full"><?= $notification_count ?> new</span>
                        </div>
                    </div>
                    <div class="max-h-64 overflow-y-auto">
                        <?php if (empty($notifications)): ?>
                            <div class="p-6 text-center text-gray-500">
                                <i class="fas fa-bell-slash text-2xl mb-3 text-gray-300"></i>
                                <p class="text-sm">No notifications</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($notifications as $notification): ?>
                                <a href="<?= base_url('customer/notifications/' . $notification['notification_id']) ?>" 
                                    class="block p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors <?= !$notification['is_read'] ? 'notification-unread' : '' ?>">
                                    <div class="flex gap-3">
                                        <div class="flex-shrink-0">
                                            <?php if ($notification['notification_type'] == 'message'): ?>
                                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-comment text-blue-600 text-xs"></i>
                                                </div>
                                            <?php elseif ($notification['notification_type'] == 'success' || $notification['notification_type'] == 'notification' || $notification['notification_type'] == 'success'): ?>
                                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-check-circle text-green-600 text-xs"></i>
                                                </div>
                                            <?php else: ?>
                                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-ticket-alt text-purple-600 text-xs"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-gray-800 text-xs truncate"><?= esc($notification['title']) ?></p>
                                            <p class="text-gray-600 text-xs mt-1 truncate"><?= esc($notification['message']) ?></p>
                                            <p class="text-gray-500 text-xs mt-2"><?= esc($notification['created_at']) ?></p>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="p-3 border-t border-gray-200 bg-gray-50">
                        <a href="<?= base_url('customer/notifications') ?>" class="block text-center text-secondary font-medium hover:text-[#665C9E] transition-colors text-xs">
                            View All
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- User Profile Section (Mobile) -->
            <div class="mobile-profile-section">
                <a href="<?= base_url('customer/profile') ?>" class="no-underline">
                    <div class="user-avatar-glass w-9 h-9 rounded-full flex items-center justify-center text-white font-bold text-xs ml-2">
                        <?= strtoupper(substr($username, 0, 1)) ?>
                    </div>
                </a>
            </div>
        </div>
        
        <!-- Desktop Right Section (Hidden on mobile) -->
        <div class="hidden md:flex items-center gap-3">
            <!-- User Avatar (Desktop) -->
            <div title="Logout">
                <button id="logoutButton" class="notification-bell-glass p-2 text-primary hover:text-secondary transition-colors ml-4"
                onclick="(confirm('Are you sure you want to logout?')) ? location.href = '/logout' : ''">
                    <i class="fa-solid fa-arrow-right-from-bracket text-lg active"></i>
                </button>
            </div>
            <div class="nav-separator"></div>
            <div title="<?= esc($username) ?>">
                <a href="<?= base_url('customer/profile') ?>" class="no-underline">
                    <div class="user-avatar-glass w-9 h-9 rounded-full flex items-center justify-center text-white font-bold text-sm">
                        <?= strtoupper(substr($username, 0, 1)) ?>
                    </div>
                </a>
            </div>
        </div>
    </header>

    <!-- Mobile Navigation Menu -->
    <div id="mobileNav" class="md:hidden fixed top-24 left-0 w-full h-0 overflow-hidden transition-all duration-300 z-40">
        <div class="mobile-glass-menu animate-fade-in">
            <!-- Mobile User Info -->
            <a href="<?= base_url('customer/profile') ?>" class="flex items-center gap-3 p-4 mb-2 border-b border-gray-100 no-underline hover:bg-gray-50 rounded-t-2xl">
                <div class="w-12 h-12 user-avatar-glass rounded-full flex items-center justify-center text-white font-bold">
                    <?= strtoupper(substr($username, 0, 1)) ?>
                </div>
                <div>
                    <div class="text-primary font-bold"><?= esc($username) ?></div>
                    <div class="text-primary/70 text-xs">Customer</div>
                </div>
            </a>
            
            <!-- Mobile Navigation Links -->
            <div class="p-3">
                <a href="<?= base_url('customer/dashboard') ?>" 
                    class="mobile-nav-item <?= current_url() == base_url('customer/dashboard') ? 'active' : 'text-primary' ?>">
                    <i class="fas fa-home text-secondary"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                
                <a href="<?= base_url('customer/my_tickets') ?>" 
                    class="mobile-nav-item <?= current_url() == base_url('customer/my_tickets') ? 'active' : 'text-primary' ?>">
                    <i class="fas fa-ticket-alt text-secondary"></i>
                    <span class="font-medium">My Tickets</span>
                </a>
                
                <a href="<?= base_url('customer/create_ticket') ?>" 
                    class="mobile-nav-item <?= current_url() == base_url('customer/create_ticket') ? 'active' : 'text-primary' ?>">
                    <i class="fas fa-plus text-secondary"></i>
                    <span class="font-medium">Create Ticket</span>
                </a>
                
                <a href="<?= base_url('customer/notifications') ?>" 
                    class="mobile-nav-item <?= current_url() == base_url('customer/notifications') ? 'active' : 'text-primary' ?>">
                    <i class="fas fa-bell text-secondary"></i>
                    <span class="font-medium">Notifications</span>
                </a>
                
                <a href="<?= base_url('customer/profile') ?>" 
                    class="mobile-nav-item <?= current_url() == base_url('customer/profile') ? 'active' : 'text-primary' ?>">
                    <i class="fas fa-user text-secondary"></i>
                    <span class="font-medium">Profile</span>
                </a>
                
                <a href="<?= base_url('customer/logout') ?>" 
                    class="mobile-nav-item text-red-600 mt-4 border-t border-gray-100 pt-4"
                    onclick="return confirm('Are you sure you want to logout?')">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="font-medium">Logout</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content px-4 md:px-6 relative z-10">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-footer-bg h-12 md:h-[60px] w-full flex items-center px-4 md:px-6 mt-8">
        <div class="text-text-dark text-xs md:text-sm font-normal">
            Customer Dashboard • Copyright © <?= date('Y') ?> Nexus Arch System. All Rights Reserved.
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alerts
            const alerts = document.querySelectorAll('.fixed.top-32');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateX(100%)';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });

            // Hamburger Menu Toggle
            const hamburgerButton = document.getElementById('hamburgerButton');
            const mobileNav = document.getElementById('mobileNav');
            const hamburgerLines = hamburgerButton?.querySelectorAll('.hamburger-line');
            
            if (hamburgerButton && hamburgerLines) {
                hamburgerButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    
                    if (mobileNav.style.height === '0px' || !mobileNav.style.height) {
                        // Open menu
                        mobileNav.style.height = 'auto';
                        hamburgerLines[0].style.transform = 'rotate(45deg) translate(6px, 6px)';
                        hamburgerLines[1].style.opacity = '0';
                        hamburgerLines[2].style.transform = 'rotate(-45deg) translate(6px, -6px)';
                    } else {
                        // Close menu
                        mobileNav.style.height = '0';
                        hamburgerLines[0].style.transform = 'none';
                        hamburgerLines[1].style.opacity = '1';
                        hamburgerLines[2].style.transform = 'none';
                    }
                });
            }

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(e) {
                if (hamburgerButton && !hamburgerButton.contains(e.target) && !mobileNav.contains(e.target)) {
                    mobileNav.style.height = '0';
                    if (hamburgerLines) {
                        hamburgerLines[0].style.transform = 'none';
                        hamburgerLines[1].style.opacity = '1';
                        hamburgerLines[2].style.transform = 'none';
                    }
                }
            });

            // Close mobile menu when clicking a link
            if (mobileNav) {
                mobileNav.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', function() {
                        mobileNav.style.height = '0';
                        if (hamburgerLines) {
                            hamburgerLines[0].style.transform = 'none';
                            hamburgerLines[1].style.opacity = '1';
                            hamburgerLines[2].style.transform = 'none';
                        }
                    });
                });
            }

            // Notification dropdown toggle (Desktop)
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
            
            // Notification dropdown toggle (Mobile)
            const mobileNotificationButton = document.getElementById('mobileNotificationButton');
            const mobileNotificationDropdown = document.getElementById('mobileNotificationDropdown');
            
            if (mobileNotificationButton && mobileNotificationDropdown) {
                mobileNotificationButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    mobileNotificationDropdown.classList.toggle('show');
                    
                    // Mark notifications as read when dropdown is opened
                    if (mobileNotificationDropdown.classList.contains('show')) {
                        markNotificationsAsRead();
                    }
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!mobileNotificationButton.contains(e.target) && !mobileNotificationDropdown.contains(e.target)) {
                        mobileNotificationDropdown.classList.remove('show');
                    }
                });
                
                // Prevent dropdown from closing when clicking inside
                mobileNotificationDropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
            
            function markNotificationsAsRead() {
                // In a real application, this would be an AJAX call
                console.log('Marking notifications as read...');
                const badges = document.querySelectorAll('.notification-badge');
                badges.forEach(badge => {
                    badge.style.display = 'none';
                });
                
                const unreadIndicators = document.querySelectorAll('.notification-unread');
                unreadIndicators.forEach(indicator => {
                    indicator.classList.remove('notification-unread');
                });
            }
            
            // Update notification count in real-time (simulated)
            function updateNotificationCount(count) {
                const badges = document.querySelectorAll('.notification-badge');
                badges.forEach(badge => {
                    badge.textContent = count;
                    badge.style.display = count > 0 ? 'flex' : 'none';
                });
            }
            
            // Simulate new notification (for demo purposes)
            setInterval(() => {
                if (window.location.pathname.includes('customer') && Math.random() > 0.7) {
                    const currentCount = parseInt(document.querySelector('.notification-badge')?.textContent || '0');
                    updateNotificationCount(currentCount + 1);
                }
            }, 30000);
            
            // Scroll effect with smooth background transition
            let lastScrollTop = 0;
            const navbar = document.querySelector('.liquid-glass-navbar');
            
            if (navbar) {
                window.addEventListener('scroll', function() {
                    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                    
                    if (scrollTop > 50) { // Reduced threshold for faster transition
                        if (!navbar.classList.contains('scrolled')) {
                            navbar.classList.add('scrolled');
                            navbar.classList.add('animate-background-transition');
                        }
                    } else {
                        if (navbar.classList.contains('scrolled')) {
                            navbar.classList.remove('scrolled');
                            navbar.classList.remove('animate-background-transition');
                        }
                    }
                    
                    // Smooth hide/show on scroll
                    if (scrollTop > lastScrollTop && scrollTop > 100) {
                        // Scroll down
                        navbar.style.opacity = '0.9';
                        navbar.style.transform = window.innerWidth <= 768 ? 'translateY(-10px)' : 'translateX(-50%) translateY(-10px)';
                    } else {
                        // Scroll up
                        navbar.style.opacity = '1';
                        navbar.style.transform = window.innerWidth <= 768 ? 'translateY(0)' : 'translateX(-50%) translateY(0)';
                    }
                    
                    lastScrollTop = scrollTop;
                });
            }
            
            // Add active state to current page
            function setActiveNavItem() {
                const currentPath = window.location.pathname;
                const navItems = document.querySelectorAll('.nav-glass-item, .mobile-nav-item');
                
                navItems.forEach(item => {
                    item.classList.remove('active');
                });
                
                // Desktop nav
                if (currentPath.includes('/customer/dashboard')) {
                    document.querySelector('a[href*="dashboard"].nav-glass-item')?.classList.add('active');
                } else if (currentPath.includes('/customer/my_tickets')) {
                    document.querySelector('a[href*="my_tickets"].nav-glass-item')?.classList.add('active');
                } else if (currentPath.includes('/customer/create_ticket')) {
                    document.querySelector('a[href*="create_ticket"].nav-glass-item')?.classList.add('active');
                } else if (currentPath.includes('/customer/notifications')) {
                    document.querySelector('a[href*="notifications"].nav-glass-item')?.classList.add('active');
                } else if (currentPath.includes('/customer/profile')) {
                    document.querySelector('a[href*="profile"].nav-glass-item')?.classList.add('active');
                }
                
                // Mobile nav (handled by PHP current_url())
            }
            
            setActiveNavItem();
            
            // Handle window resize for navbar positioning
            function handleResize() {
                const navbar = document.querySelector('.liquid-glass-navbar');
                if (!navbar) return;
                
                if (window.innerWidth <= 768) {
                    // Mobile layout
                    navbar.style.left = '20px';
                    navbar.style.right = '20px';
                    navbar.style.transform = 'none';
                    navbar.style.animation = 'none';
                    
                    if (navbar.classList.contains('scrolled')) {
                        navbar.style.left = '0';
                        navbar.style.right = '0';
                    }
                } else {
                    // Desktop layout
                    navbar.style.left = '50%';
                    navbar.style.right = 'auto';
                    navbar.style.transform = 'translateX(-50%)';
                    navbar.style.animation = 'float 6s ease-in-out infinite';
                    
                    if (navbar.classList.contains('scrolled')) {
                        navbar.style.transform = 'translateX(-50%)';
                    }
                }
            }
            
            // Initial call
            handleResize();
            
            // Listen for resize events
            window.addEventListener('resize', handleResize);
        });
    </script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>