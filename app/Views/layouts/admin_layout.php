<?php
$session = \Config\Services::session();
$success = $session->getFlashdata('success');
$error = $session->getFlashdata('error');
$message = $session->getFlashdata('message');
$search_message = $session->getFlashdata('search_message');

// Determine username for display in navbar (fallbacks if session keys differ)
$username = $session->get('username') ?? $session->get('name') ?? $session->get('user') ?? 'Admin';

$notification_count = 2;
$notifications = [
    ['id' => 1, 'title' => 'System Alert', 'message' => 'Project Alpha has unresolved tickets', 'time' => '15 minutes ago', 'read' => false, 'type' => 'warning'],
    ['id' => 2, 'title' => 'Maintenance Notice', 'message' => 'Scheduled maintenance at 10:00 PM', 'time' => '1 hour ago', 'read' => true, 'type' => 'info'],
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <meta name="csrf-header" content="<?= csrf_header() ?>">
    <title><?= esc($title ?? 'Admin Dashboard - NEXUS') ?></title>

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
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&family=Mulish:wght@300;400;500;600;700&family=Inter:wght@400;500;600&family=Poppins:wght@400;500;600&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideDown {
            from {
                transform: translateY(-10px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
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

        .animate-slide-in-left {
            animation: slideInLeft 0.3s ease-out;
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

        /* Liquid Glass Sidebar Styles - Desktop */
        .liquid-glass-sidebar {
            background: linear-gradient(135deg,
                    rgba(255, 255, 255, 0.25) 0%,
                    rgba(255, 255, 255, 0.15) 100%);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-right: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow:
                0 8px 32px 0 rgba(31, 38, 135, 0.15),
                0 4px 16px 0 rgba(0, 0, 0, 0.08),
                inset 2px 0 0 rgba(255, 255, 255, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            width: 280px;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 40;
            display: flex;
            flex-direction: column;
            padding: 0;
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        /* Sidebar Navigation Items */
        .sidebar-nav-item {
            background: transparent;
            color: #434264;
            position: relative;
            transition: all 0.3s ease;
            padding: 14px 24px;
            margin: 0;
            font-weight: 400;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 14px;
            border-left: 3px solid transparent;
        }

        .sidebar-nav-item:hover {
            background: rgba(117, 110, 164, 0.15);
            color: #756EA4;
        }

        .sidebar-nav-item.active {
            background: linear-gradient(90deg,
                    rgba(117, 110, 164, 0.25) 0%,
                    rgba(117, 110, 164, 0.15) 100%) !important;
            color: #756EA4 !important;
            border-left: 3px solid #756EA4;
            box-shadow: inset 4px 0 12px rgba(117, 110, 164, 0.1);
        }

        .sidebar-nav-item.active i {
            color: #756EA4 !important;
        }

        .sidebar-section-title {
            color: rgba(67, 66, 100, 0.7);
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.60px;
            padding: 20px 24px 8px 24px;
            margin-top: 8px;
        }

        /* User Profile in Sidebar */
        .sidebar-user-profile {
            padding: 30px 24px;
            background: rgba(255, 255, 255, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .sidebar-user-avatar {
            background: linear-gradient(135deg, #756EA4, #8A84C6);
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 15px rgba(117, 110, 164, 0.3);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
            flex-shrink: 0;
        }

        .user-info {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            color: #434264;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .user-role {
            color: rgba(67, 66, 100, 0.7);
            font-size: 12px;
        }

        /* Notification Bell in Sidebar */
        .sidebar-notification-bell {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 12px;
            transition: all 0.3s ease;
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            position: relative;
            flex-shrink: 0;
            cursor: pointer;
        }

        .sidebar-notification-bell:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(117, 110, 164, 0.2);
        }

        /* Main Content Area */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            background: #D7D5EE;
            position: relative;
            overflow: hidden;
            padding: 30px;
        }

        /* Mobile Menu Button (Only visible on mobile) */
        .mobile-menu-button {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 50;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow:
                0 8px 32px 0 rgba(31, 38, 135, 0.07),
                0 4px 16px 0 rgba(0, 0, 0, 0.05),
                inset 0 0 0 1px rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            width: 50px;
            height: 50px;
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 4px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .mobile-menu-button:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .hamburger-line {
            width: 20px;
            height: 2px;
            background: #434264;
            border-radius: 1px;
            transition: all 0.3s ease;
        }

        /* Mobile Sidebar Menu */
        .mobile-sidebar-menu {
            position: fixed;
            top: 0;
            left: -280px;
            width: 280px;
            height: 100vh;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            z-index: 45;
            overflow-y: auto;
            transition: all 0.3s ease;
            box-shadow: 20px 0 40px rgba(0, 0, 0, 0.1);
        }

        .mobile-sidebar-menu.open {
            left: 0;
        }

        /* Mobile Menu Items */
        .mobile-menu-item {
            padding: 14px 24px;
            border-radius: 0;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 14px;
            color: #434264;
            border-left: 3px solid transparent;
        }

        .mobile-menu-item:hover {
            background: rgba(117, 110, 164, 0.15);
        }

        .mobile-menu-item.active {
            background: rgba(117, 110, 164, 0.25);
            color: #756EA4;
            border-left: 3px solid #756EA4;
        }

        .mobile-menu-section {
            color: rgba(67, 66, 100, 0.7);
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.60px;
            padding: 20px 24px 8px 24px;
            margin-top: 8px;
        }

        /* Mobile Logo */
        .mobile-logo {
            padding: 30px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .mobile-logo-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #756EA4, #8A84C6);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }

        .mobile-logo-text {
            color: #434264;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* Mobile User Profile */
        .mobile-user-profile {
            padding: 30px 24px;
            background: rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .mobile-user-avatar {
            background: linear-gradient(135deg, #756EA4, #8A84C6);
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 15px rgba(117, 110, 164, 0.3);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
            flex-shrink: 0;
        }

        /* Mobile Notification Bell */
        .mobile-notification-bell {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 50;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow:
                0 8px 32px 0 rgba(31, 38, 135, 0.07),
                0 4px 16px 0 rgba(0, 0, 0, 0.05),
                inset 0 0 0 1px rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            width: 50px;
            height: 50px;
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .mobile-notification-bell:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(117, 110, 164, 0.4);
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(117, 110, 164, 0.6);
        }

        /* Overlay for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 39;
            backdrop-filter: blur(3px);
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* Notification Dropdown Positioning */
        .notification-dropdown-sidebar {
            position: absolute;
            left: 100%;
            top: 0;
            margin-left: 10px;
        }

        .notification-dropdown-mobile {
            position: fixed;
            top: 90px;
            right: 20px;
        }

        /* Sidebar Logo */
        .sidebar-logo {
            padding: 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #756EA4, #8A84C6);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }

        .logo-text {
            color: #434264;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* Sidebar Navigation Container */
        .sidebar-nav-container {
            flex: 1;
            padding: 16px 0;
        }

        /* Sidebar Bottom Section */
        .sidebar-bottom {
            padding: 20px 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            margin-top: auto;
        }

        /* Stats Card Styles */
        .stat-card {
            background: #3D3C5E;
            border-radius: 7.73px;
            padding: 20px;
            color: white;
            position: relative;
            height: 88px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
        }

        /* Dashboard Card Styles */
        .dashboard-card {
            background: #EFE9F9;
            box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.05);
            border-radius: 16px;
            outline: 1px solid rgba(255, 255, 255, 0.50);
            outline-offset: -1px;
            backdrop-filter: blur(2px);
            padding: 20px;
        }

        .dashboard-card-header {
            background: #E3DAEE;
            border-radius: 7.73px;
            padding: 12px 20px;
            margin: -20px -20px 20px -20px;
        }

        /* Quick Access Item */
        .quick-access-item {
            background: white;
            border-radius: 8px;
            padding: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
        }

        .quick-access-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .quick-access-icon {
            width: 32px;
            height: 32px;
            background: #F3E8FF;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9155FD;
            font-size: 14px;
        }

        /* Activity Item */
        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 8px 0;
        }

        .activity-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Project Row */
        .project-row {
            padding: 8px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .project-row:hover {
            background: rgba(0, 0, 0, 0.02);
        }

        /* Notification Item */
        .notification-item-card {
            background: #FFF4E5;
            border-radius: 8px;
            outline: 1px solid #FFE5BF;
            outline-offset: -1px;
            padding: 13px;
            margin-bottom: 12px;
        }

        .notification-item-card.info {
            background: #F4F5FA;
            outline: 1px solid #F3F4F6;
        }

        /* Responsive Styles */
        @media (max-width: 1024px) {
            .liquid-glass-sidebar {
                display: none;
            }

            .mobile-menu-button {
                display: flex;
            }

            .mobile-notification-bell {
                display: flex;
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
                padding-top: 30px;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
                padding-top: 30px;
            }

            .mobile-menu-button {
                top: 15px;
                left: 15px;
                width: 45px;
                height: 45px;
            }

            .mobile-notification-bell {
                top: 15px;
                right: 15px;
                width: 45px;
                height: 45px;
            }

            .mobile-sidebar-menu {
                width: 260px;
                left: -260px;
            }

            .notification-dropdown-mobile {
                right: 15px;
                top: 75px;
                width: 300px;
            }
        }
    </style>

    <?= $this->renderSection('styles') ?>
</head>

<body class="bg-light-bg font-roboto relative overflow-x-hidden">

    <!-- Background Effects -->
    <?= $this->renderSection('background_effects') ?>

    <!-- Alert Messages -->
    <?php if ($success): ?>
        <div
            class="fixed top-32 right-4 md:right-6 p-4 rounded-lg z-[1000] max-w-xs md:max-w-sm animate-slide-in shadow-lg bg-green-500 text-white border-l-4 border-green-600">
            <i class="fas fa-check-circle mr-2"></i> <?= esc($success) ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div
            class="fixed top-32 right-4 md:right-6 p-4 rounded-lg z-[1000] max-w-xs md:max-w-sm animate-slide-in shadow-lg bg-red-500 text-white border-l-4 border-red-600">
            <i class="fas fa-exclamation-circle mr-2"></i> <?= esc($error) ?>
        </div>
    <?php endif; ?>

    <?php if ($message): ?>
        <div
            class="fixed top-32 right-4 md:right-6 p-4 rounded-lg z-[1000] max-w-xs md:max-w-sm animate-slide-in shadow-lg bg-blue-500 text-white border-l-4 border-blue-600">
            <i class="fas fa-info-circle mr-2"></i> <?= esc($message) ?>
        </div>
    <?php endif; ?>

    <?php if ($search_message): ?>
        <div
            class="fixed top-32 right-4 md:right-6 p-4 rounded-lg z-[1000] max-w-xs md:max-w-sm animate-slide-in shadow-lg bg-blue-500 text-white border-l-4 border-blue-600">
            <i class="fas fa-search mr-2"></i> <?= esc($search_message) ?>
        </div>
    <?php endif; ?>

    <!-- Mobile Menu Button (Only on mobile) -->
    <button id="mobileMenuButton" class="mobile-menu-button">
        <div class="hamburger-line"></div>
        <div class="hamburger-line"></div>
        <div class="hamburger-line"></div>
    </button>

    <!-- Mobile Notification Bell (Only on mobile) -->
    <button id="mobileNotificationButton" class="mobile-notification-bell">
        <i class="fas fa-bell text-lg text-primary"></i>
        <?php if ($notification_count > 0): ?>
            <span class="notification-badge"><?= $notification_count ?></span>
        <?php endif; ?>
    </button>

    <!-- Sidebar Overlay (Mobile) -->
    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    <!-- Mobile Sidebar Menu -->
    <div id="mobileSidebarMenu" class="mobile-sidebar-menu custom-scrollbar">
        <!-- Logo -->
        <div class="mobile-logo">
            <div class="mobile-logo-icon">
                <i class="fas fa-cube"></i>
            </div>
            <div class="mobile-logo-text">NEXUS</div>
        </div>

        <!-- User Profile Section -->
        <div class="mobile-user-profile">
            <div class="mobile-user-avatar">
                <?= strtoupper(substr($username, 0, 1)) ?>
            </div>
            <div class="user-info">
                <div class="user-name"><?= esc($username) ?></div>
                <div class="user-role">Administrator</div>
            </div>
        </div>

        <!-- Navigation Container -->
        <div class="sidebar-nav-container">
            <!-- Navigation Links -->
            <a href="<?= base_url('admin/dashboard') ?>"
                class="mobile-menu-item <?= current_url() == base_url('admin/dashboard') ? 'active' : '' ?>">
                <i class="fas fa-home text-sm"></i>
                <span class="text-sm">Dashboard</span>
            </a>

            <div class="mobile-menu-section">Management</div>

            <a href="<?= base_url('admin/users') ?>"
                class="mobile-menu-item <?= strpos(current_url(), 'users') !== false ? 'active' : '' ?>">
                <i class="fas fa-users text-sm"></i>
                <span class="text-sm">Manage Users</span>
            </a>

            <a href="<?= base_url('admin/departments') ?>"
                class="mobile-menu-item <?= strpos(current_url(), 'departments') !== false ? 'active' : '' ?>">
                <i class="fas fa-sitemap text-sm"></i>
                <span class="text-sm">Manage Departments</span>
            </a>

             <a href="<?= base_url('admin/projects') ?>"
                class="sidebar-nav-item <?= strpos(current_url(), 'projects') !== false ? 'active' : '' ?>">
                <i class="fas fa-project-diagram text-sm"></i>
                <span class="text-sm">Manage Projects</span>
            </a>

            <a href="<?= base_url('admin/tickets') ?>"
                class="mobile-menu-item <?= strpos(current_url(), 'tickets') !== false ? 'active' : '' ?>">
                <i class="fas fa-ticket-alt text-sm"></i>
                <span class="text-sm">View Tickets</span>
            </a>
        </div>

        <!-- Bottom Section -->
        <div class="sidebar-bottom">
            <a href="<?= base_url('admin/logout') ?>" class="mobile-menu-item">
                <i class="fas fa-sign-out-alt text-sm"></i>
                <span class="text-sm">Logout</span>
            </a>
        </div>
    </div>

    <!-- Liquid Glass Sidebar (Desktop) -->
    <nav class="liquid-glass-sidebar custom-scrollbar">
        <!-- Logo -->
        <div class="sidebar-logo">
            <div class="logo-icon">
                <i class="fas fa-cube"></i>
            </div>
            <div class="logo-text">NEXUS</div>
        </div>

        <!-- User Profile Section -->
        <div class="sidebar-user-profile">
            <div class="sidebar-user-avatar">
                <?= strtoupper(substr($username, 0, 1)) ?>
            </div>
            <div class="user-info">
                <div class="user-name"><?= esc($username) ?></div>
                <div class="user-role">Administrator</div>
            </div>

            <!-- Notification Bell -->
            <div class="relative">
                <button id="sidebarNotificationButton"
                    class="sidebar-notification-bell text-primary hover:text-secondary transition-colors">
                    <i class="fas fa-bell text-lg"></i>
                    <?php if ($notification_count > 0): ?>
                        <span class="notification-badge"><?= $notification_count ?></span>
                    <?php endif; ?>
                </button>
            </div>
        </div>

        <!-- Navigation Container -->
        <div class="sidebar-nav-container">
            <!-- Navigation Links -->
            <a href="<?= base_url('admin/dashboard') ?>"
                class="sidebar-nav-item <?= current_url() == base_url('admin/dashboard') ? 'active' : '' ?>">
                <i class="fas fa-home text-sm"></i>
                <span class="text-sm">Dashboard</span>
            </a>

            <div class="sidebar-section-title">Management</div>

            <a href="<?= base_url('admin/users') ?>"
                class="sidebar-nav-item <?= strpos(current_url(), 'users') !== false ? 'active' : '' ?>">
                <i class="fas fa-users text-sm"></i>
                <span class="text-sm">Manage Users</span>
            </a>

            <a href="<?= base_url('admin/departments') ?>"
                class="sidebar-nav-item <?= strpos(current_url(), 'departments') !== false ? 'active' : '' ?>">
                <i class="fas fa-sitemap text-sm"></i>
                <span class="text-sm">Manage Departments</span>
            </a>

            <a href="<?= base_url('admin/projects') ?>"
                class="mobile-menu-item <?= strpos(current_url(), 'projects') !== false ? 'active' : '' ?>">
                <i class="fas fa-project-diagram text-sm"></i>
                <span class="text-sm">Manage Projects</span>
            </a>


            <a href="<?= base_url('admin/tickets') ?>"
                class="sidebar-nav-item <?= strpos(current_url(), 'tickets') !== false ? 'active' : '' ?>">
                <i class="fas fa-ticket-alt text-sm"></i>
                <span class="text-sm">View Tickets</span>
            </a>
        </div>

        <!-- Bottom Section -->
        <div class="sidebar-bottom">
            <a href="<?= base_url('logout') ?>" class="sidebar-nav-item">
                <i class="fas fa-sign-out-alt text-sm"></i>
                <span class="text-sm">Logout</span>
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Notification Dropdown (Desktop) -->
    <div id="sidebarNotificationDropdown"
        class="notification-dropdown notification-dropdown-sidebar w-72 bg-white rounded-2xl shadow-xl border border-gray-200 z-50 overflow-hidden"
        style="display: none;">
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
                    <a href="#"
                        class="block p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors <?= !$notification['read'] ? 'notification-unread' : '' ?>">
                        <div class="flex gap-3">
                            <div class="flex-shrink-0">
                                <?php if ($notification['type'] == 'warning'): ?>
                                    <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                                    </div>
                                <?php elseif ($notification['type'] == 'info'): ?>
                                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-info-circle text-blue-600"></i>
                                    </div>
                                <?php else: ?>
                                    <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-bell text-gray-600"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-800 text-sm truncate"><?= esc($notification['title']) ?></p>
                                <p class="text-gray-600 text-xs mt-1 truncate"><?= esc($notification['message']) ?></p>
                                <p class="text-gray-500 text-xs mt-2"><?= esc($notification['time']) ?></p>
                            </div>
                            <?php if (!$notification['read']): ?>
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
            <a href="#"
                class="block text-center text-secondary font-medium hover:text-[#665C9E] transition-colors text-sm">
                View All Notifications
            </a>
        </div>
    </div>

    <!-- Notification Dropdown (Mobile) -->
    <div id="mobileNotificationDropdown"
        class="notification-dropdown notification-dropdown-mobile w-72 bg-white rounded-2xl shadow-xl border border-gray-200 z-50 overflow-hidden"
        style="display: none;">
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
                    <a href="#"
                        class="block p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors <?= !$notification['read'] ? 'notification-unread' : '' ?>">
                        <div class="flex gap-3">
                            <div class="flex-shrink-0">
                                <?php if ($notification['type'] == 'warning'): ?>
                                    <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                                    </div>
                                <?php elseif ($notification['type'] == 'info'): ?>
                                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-info-circle text-blue-600"></i>
                                    </div>
                                <?php else: ?>
                                    <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-bell text-gray-600"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-800 text-sm truncate"><?= esc($notification['title']) ?></p>
                                <p class="text-gray-600 text-xs mt-1 truncate"><?= esc($notification['message']) ?></p>
                                <p class="text-gray-500 text-xs mt-2"><?= esc($notification['time']) ?></p>
                            </div>
                            <?php if (!$notification['read']): ?>
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
            <a href="#"
                class="block text-center text-secondary font-medium hover:text-[#665C9E] transition-colors text-sm">
                View All Notifications
            </a>
        </div>
    </div>

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

            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobileMenuButton');
            const mobileSidebarMenu = document.getElementById('mobileSidebarMenu');
            const hamburgerLines = mobileMenuButton?.querySelectorAll('.hamburger-line');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            if (mobileMenuButton && hamburgerLines) {
                mobileMenuButton.addEventListener('click', function(e) {
                    e.stopPropagation();

                    if (mobileSidebarMenu.classList.contains('open')) {
                        // Close menu
                        mobileSidebarMenu.classList.remove('open');
                        hamburgerLines[0].style.transform = 'none';
                        hamburgerLines[1].style.opacity = '1';
                        hamburgerLines[2].style.transform = 'none';
                        sidebarOverlay.classList.remove('show');
                    } else {
                        // Open menu
                        mobileSidebarMenu.classList.add('open');
                        hamburgerLines[0].style.transform = 'rotate(45deg) translate(6px, 6px)';
                        hamburgerLines[1].style.opacity = '0';
                        hamburgerLines[2].style.transform = 'rotate(-45deg) translate(6px, -6px)';
                        sidebarOverlay.classList.add('show');
                    }
                });

                // Close mobile menu when clicking overlay
                sidebarOverlay.addEventListener('click', function() {
                    mobileSidebarMenu.classList.remove('open');
                    if (hamburgerLines) {
                        hamburgerLines[0].style.transform = 'none';
                        hamburgerLines[1].style.opacity = '1';
                        hamburgerLines[2].style.transform = 'none';
                    }
                    sidebarOverlay.classList.remove('show');
                });

                // Close mobile menu when clicking a link
                if (mobileSidebarMenu) {
                    mobileSidebarMenu.querySelectorAll('a').forEach(link => {
                        link.addEventListener('click', function() {
                            mobileSidebarMenu.classList.remove('open');
                            if (hamburgerLines) {
                                hamburgerLines[0].style.transform = 'none';
                                hamburgerLines[1].style.opacity = '1';
                                hamburgerLines[2].style.transform = 'none';
                            }
                            sidebarOverlay.classList.remove('show');
                        });
                    });
                }
            }

            // Desktop sidebar notification dropdown
            const sidebarNotificationButton = document.getElementById('sidebarNotificationButton');
            const sidebarNotificationDropdown = document.getElementById('sidebarNotificationDropdown');
            let desktopNotificationVisible = false;

            if (sidebarNotificationButton && sidebarNotificationDropdown) {
                // Position dropdown relative to button
                function positionDesktopDropdown() {
                    const buttonRect = sidebarNotificationButton.getBoundingClientRect();
                    sidebarNotificationDropdown.style.top = buttonRect.top + 'px';
                    sidebarNotificationDropdown.style.left = (buttonRect.right + 10) + 'px';
                }

                sidebarNotificationButton.addEventListener('click', function(e) {
                    e.stopPropagation();

                    if (desktopNotificationVisible) {
                        // Close dropdown
                        sidebarNotificationDropdown.classList.remove('show');
                        setTimeout(() => {
                            sidebarNotificationDropdown.style.display = 'none';
                        }, 200);
                        desktopNotificationVisible = false;
                    } else {
                        // Open dropdown
                        positionDesktopDropdown();
                        sidebarNotificationDropdown.style.display = 'block';
                        setTimeout(() => {
                            sidebarNotificationDropdown.classList.add('show');
                        }, 10);
                        desktopNotificationVisible = true;

                        // Mark notifications as read
                        markNotificationsAsRead();
                    }
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (desktopNotificationVisible &&
                        !sidebarNotificationButton.contains(e.target) &&
                        !sidebarNotificationDropdown.contains(e.target)) {
                        sidebarNotificationDropdown.classList.remove('show');
                        setTimeout(() => {
                            sidebarNotificationDropdown.style.display = 'none';
                        }, 200);
                        desktopNotificationVisible = false;
                    }
                });

                // Update position on scroll/resize
                window.addEventListener('scroll', positionDesktopDropdown);
                window.addEventListener('resize', positionDesktopDropdown);
            }

            // Mobile notification dropdown
            const mobileNotificationButton = document.getElementById('mobileNotificationButton');
            const mobileNotificationDropdown = document.getElementById('mobileNotificationDropdown');
            let mobileNotificationVisible = false;

            if (mobileNotificationButton && mobileNotificationDropdown) {
                mobileNotificationButton.addEventListener('click', function(e) {
                    e.stopPropagation();

                    if (mobileNotificationVisible) {
                        // Close dropdown
                        mobileNotificationDropdown.classList.remove('show');
                        setTimeout(() => {
                            mobileNotificationDropdown.style.display = 'none';
                        }, 200);
                        mobileNotificationVisible = false;
                    } else {
                        // Open dropdown
                        mobileNotificationDropdown.style.display = 'block';
                        setTimeout(() => {
                            mobileNotificationDropdown.classList.add('show');
                        }, 10);
                        mobileNotificationVisible = true;

                        // Mark notifications as read
                        markNotificationsAsRead();
                    }
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (mobileNotificationVisible &&
                        !mobileNotificationButton.contains(e.target) &&
                        !mobileNotificationDropdown.contains(e.target)) {
                        mobileNotificationDropdown.classList.remove('show');
                        setTimeout(() => {
                            mobileNotificationDropdown.style.display = 'none';
                        }, 200);
                        mobileNotificationVisible = false;
                    }
                });
            }

            // Function to mark notifications as read
            function markNotificationsAsRead() {
                const badges = document.querySelectorAll('.notification-badge');
                badges.forEach(badge => {
                    badge.style.display = 'none';
                });

                const unreadIndicators = document.querySelectorAll('.notification-unread');
                unreadIndicators.forEach(indicator => {
                    indicator.classList.remove('notification-unread');
                });
            }

            // Set active nav item based on current URL
            function setActiveNavItem() {
                const currentPath = window.location.pathname;

                // Desktop sidebar items
                const desktopNavItems = document.querySelectorAll('.sidebar-nav-item');
                desktopNavItems.forEach(item => {
                    item.classList.remove('active');
                });

                // Mobile menu items
                const mobileNavItems = document.querySelectorAll('.mobile-menu-item');
                mobileNavItems.forEach(item => {
                    item.classList.remove('active');
                });

                if (currentPath.includes('/admin/dashboard')) {
                    document.querySelector('a[href*="dashboard"].sidebar-nav-item')?.classList.add('active');
                    document.querySelector('a[href*="dashboard"].mobile-menu-item')?.classList.add('active');
                } else if (currentPath.includes('/admin/users')) {
                    document.querySelector('a[href*="users"].sidebar-nav-item')?.classList.add('active');
                    document.querySelector('a[href*="users"].mobile-menu-item')?.classList.add('active');
                } else if (currentPath.includes('/admin/departments')) {
                    document.querySelector('a[href*="departments"].sidebar-nav-item')?.classList.add('active');
                    document.querySelector('a[href*="departments"].mobile-menu-item')?.classList.add('active');
                } else if (currentPath.includes('/admin/tickets')) {
                    document.querySelector('a[href*="tickets"].sidebar-nav-item')?.classList.add('active');
                    document.querySelector('a[href*="tickets"].mobile-menu-item')?.classList.add('active');
                } else if (currentPath.includes('/admin/settings')) {
                    document.querySelector('a[href*="settings"].sidebar-nav-item')?.classList.add('active');
                    document.querySelector('a[href*="settings"].mobile-menu-item')?.classList.add('active');
                }
            }

            setActiveNavItem();

            // Handle window resize
            function handleResize() {
                if (window.innerWidth > 1024) {
                    // Desktop: ensure mobile menu is closed
                    if (mobileSidebarMenu) {
                        mobileSidebarMenu.classList.remove('open');
                    }
                    if (hamburgerLines) {
                        hamburgerLines[0].style.transform = 'none';
                        hamburgerLines[1].style.opacity = '1';
                        hamburgerLines[2].style.transform = 'none';
                    }
                    if (sidebarOverlay) sidebarOverlay.classList.remove('show');

                    // Update notification dropdown position if visible
                    if (desktopNotificationVisible && sidebarNotificationDropdown) {
                        positionDesktopDropdown();
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