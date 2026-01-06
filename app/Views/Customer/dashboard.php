<?php
// Helper untuk flash messages
$session = \Config\Services::session();
$success = $session->getFlashdata('success');
$error = $session->getFlashdata('error');
$message = $session->getFlashdata('message');
$search_message = $session->getFlashdata('search_message');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard - NEXUS</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&family=Mulish:wght@300;400;500;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #D7D5EE;
            color: #302B48;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Background Effects */
        .bg-effect {
            position: fixed;
            z-index: 1;
            filter: blur(100px);
            opacity: 0.6;
        }

        .bg-effect-1 {
            width: 50vw;
            height: 50vw;
            right: -15%;
            bottom: -10%;
            transform: rotate(149deg);
            background: linear-gradient(75deg, rgba(56.96, 44.47, 127.72, 0.26) 75%, #D6D3EE 83%, rgba(174.48, 162.84, 202.33, 0.94) 100%, #817CB2 100%);
        }

        .bg-effect-2 {
            width: 40vw;
            height: 30vw;
            left: -10%;
            top: 15%;
            transform: rotate(8deg);
            background: linear-gradient(75deg, rgba(65.04, 45.10, 137.14, 0.31) 75%, #D6D3EE 83%, rgba(174.48, 162.84, 202.33, 0.94) 100%, #817CB2 100%);
        }

        .bg-effect-3 {
            width: 20vw;
            height: 25vw;
            right: 5%;
            top: -5%;
            transform: rotate(8deg);
            background: linear-gradient(75deg, rgba(16.56, 8.41, 46.05, 0.97) 33%, #D6D3EE 83%, rgba(174.48, 162.84, 202.33, 0.94) 100%, #817CB2 100%);
        }

        /* Alert Messages */
        .alert {
            position: fixed;
            top: 90px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            z-index: 1000;
            max-width: 400px;
            animation: slideIn 0.3s ease-out;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .alert-success {
            background-color: #4CAF50;
            color: white;
            border-left: 5px solid #2E7D32;
        }

        .alert-error {
            background-color: #f44336;
            color: white;
            border-left: 5px solid #c62828;
        }

        .alert-info {
            background-color: #2196F3;
            color: white;
            border-left: 5px solid #1565C0;
        }

        /* Header */
        .header {
            background-color: #D3CBE0;
            height: 77px;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-icon {
            width: 25px;
            height: 25px;
            background-color: #434264;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .logo-text {
            color: #817CB2;
            font-size: 16px;
            font-weight: 700;
        }

        .nav-menu {
            display: flex;
            gap: 40px;
            margin-left: 50px;
        }

        .nav-item {
            color: #434264;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: color 0.3s;
            padding: 5px 10px;
            border-radius: 4px;
        }

        .nav-item.active {
            color: #756EA4;
            background-color: rgba(117, 110, 164, 0.1);
        }

        .nav-item:hover {
            color: #756EA4;
            background-color: rgba(117, 110, 164, 0.1);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .username {
            color: #434264;
            font-size: 14px;
            font-weight: 700;
        }

        .user-avatar {
            width: 37px;
            height: 37px;
            background-color: #434264;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }

        /* Main Content */
        .main-content {
            margin-top: 77px;
            padding: 30px;
            position: relative;
            z-index: 2;
        }

        .page-header {
            margin-bottom: 25px;
            position: relative;
        }

        .page-title {
            font-size: 35px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .page-subtitle {
            font-size: 15px;
            font-weight: 300;
            color: #666;
        }

        .action-buttons {
            display: flex;
            gap: 20px;
            position: absolute;
            right: 0;
            top: 0;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            border: none;
            font-family: 'Mulish', sans-serif;
            text-decoration: none;
        }

        .btn-primary {
            background-color: #756EA4;
            color: rgba(255, 255, 255, 0.87);
        }

        .btn-primary:hover {
            background-color: #656099;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Dashboard Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            grid-gap: 20px;
            margin-bottom: 30px;
        }

        /* User Profile Card */
        .user-card {
            grid-column: span 3;
            background-color: #F0E9F9;
            border-radius: 8px;
            padding: 25px;
            height: 320px;
        }

        .user-name {
            color: #3E3B5D;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 5px;
            font-family: 'Mulish', sans-serif;
        }

        .user-role {
            color: #3E3B5D;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 20px;
            font-family: 'Mulish', sans-serif;
        }

        .user-email {
            color: #3E3B5D;
            font-size: 12px;
            margin-bottom: 25px;
            font-family: 'Inter', sans-serif;
        }

        .project-list {
            color: #3E3B5D;
            font-size: 12px;
            margin-bottom: 30px;
            line-height: 1.8;
            font-family: 'Inter', sans-serif;
        }

        .profile-actions {
            display: flex;
            gap: 12px;
        }

        .btn-secondary {
            background-color: #DFD8E8;
            color: #475569;
            font-size: 11px;
            font-weight: 600;
            padding: 8px 16px;
            border: 1px solid #E5E7EB;
        }

        .btn-secondary:hover {
            background-color: #D1C9DF;
        }

        .btn-danger {
            background-color: #756EA4;
            color: white;
            font-size: 11px;
            font-weight: 600;
            padding: 8px 16px;
        }

        .btn-danger:hover {
            background-color: #656099;
        }

        /* Stats Cards */
        .stat-card {
            grid-column: span 3;
            background-color: #3D3C5E;
            border-radius: 8px;
            padding: 25px;
            height: 167px;
            position: relative;
            overflow: hidden;
        }

        .stat-icon {
            position: absolute;
            width: 50px;
            height: 44px;
            top: 25px;
            left: 25px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .stat-indicator {
            position: absolute;
            width: 15px;
            height: 7px;
            top: 25px;
            right: 25px;
            border-radius: 3px;
        }

        .indicator-purple {
            background-color: #BA94ED;
        }

        .indicator-green {
            background-color: #00FF00;
        }

        .indicator-gray {
            background-color: #9E9E9E;
        }

        .stat-number {
            color: rgba(255, 255, 255, 0.85);
            font-size: 32px;
            font-weight: 500;
            margin-top: 45px;
            font-family: 'Mulish', sans-serif;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.79);
            font-size: 18px;
            font-weight: 500;
            margin-top: 10px;
            font-family: 'Mulish', sans-serif;
        }

        /* Support Messages Card */
        .messages-card {
            grid-column: span 3;
            background-color: #F0E9F9;
            border-radius: 8px;
            padding: 25px;
            height: 135px;
        }

        .messages-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .messages-title {
            color: #3E3B5D;
            font-size: 20px;
            font-weight: 500;
            font-family: 'Mulish', sans-serif;
        }

        .message-icon {
            width: 16px;
            height: 17px;
            background-color: #3E3B5D;
            border-radius: 3px;
        }

        .no-messages {
            color: #555265;
            font-size: 12px;
            font-weight: 500;
            margin-top: 10px;
            font-family: 'Mulish', sans-serif;
        }

        .btn-view-messages {
            background-color: #756EA4;
            color: white;
            font-size: 11px;
            font-weight: 600;
            padding: 8px 16px;
            margin-top: 20px;
        }

        .btn-view-messages:hover {
            background-color: #656099;
        }

        /* Tickets Section */
        .tickets-section {
            grid-column: span 9;
            background-color: #3C3B5D;
            border-radius: 8px;
            overflow: hidden;
            height: 203px;
        }

        .section-header {
            background-color: #48466B;
            height: 51px;
            padding: 0 25px;
            display: flex;
            align-items: center;
        }

        .section-title {
            color: rgba(255, 255, 255, 0.79);
            font-size: 19px;
            font-weight: 500;
            font-family: 'Mulish', sans-serif;
        }

        .section-content {
            padding: 25px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: calc(100% - 51px);
        }

        .no-tickets {
            color: rgba(209, 213, 219, 0.90);
            font-size: 18px;
            font-weight: 300;
            text-align: center;
            font-family: 'Mulish', sans-serif;
        }

        .ticket-illustration {
            width: 53px;
            height: 53px;
            margin-bottom: 15px;
            position: relative;
        }

        .ticket-illustration::before,
        .ticket-illustration::after {
            content: '';
            position: absolute;
            border: 2px solid #9790C6;
            border-radius: 4px;
        }

        .ticket-illustration::before {
            width: 40px;
            height: 40px;
            top: 6px;
            left: 6px;
        }

        .ticket-illustration::after {
            width: 13px;
            height: 13px;
            top: 20px;
            left: 20px;
        }

        /* Search Tickets Card */
        .search-card {
            grid-column: span 4;
            background-color: #434264;
            border-radius: 7px;
            padding: 25px;
            height: 230px;
        }

        .search-title {
            color: rgba(255, 255, 255, 0.80);
            font-size: 17px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 25px;
            font-family: 'Mulish', sans-serif;
        }

        .search-input {
            width: 100%;
            height: 47px;
            background-color: rgba(255, 255, 255, 0.10);
            border: 1px solid rgba(255, 255, 255, 0.10);
            border-radius: 5px;
            padding: 0 20px;
            color: #9CA3AF;
            font-size: 16px;
            font-family: 'Mulish', sans-serif;
            margin-bottom: 25px;
        }

        .search-input::placeholder {
            color: #9CA3AF;
        }

        .btn-search {
            background-color: white;
            color: #5C59AC;
            font-size: 9px;
            font-weight: 600;
            padding: 7px 13px;
            display: block;
            margin: 0 auto;
        }

        .btn-search:hover {
            background-color: #f0f0f0;
        }

        /* Notifications Card */
        .notifications-card {
            grid-column: span 8;
            background-color: #3C3B5D;
            border-radius: 8px;
            overflow: hidden;
            height: 230px;
        }

        .notifications-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
        }

        .notifications-title {
            color: rgba(255, 255, 255, 0.80);
            font-size: 20px;
            font-weight: 600;
            font-family: 'Mulish', sans-serif;
        }

        .notification-icon {
            width: 16px;
            height: 16px;
            background-color: #434264;
            border-radius: 3px;
        }

        .notifications-content {
            padding: 25px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: calc(100% - 50px);
        }

        .no-notifications {
            color: rgba(209, 213, 219, 0.86);
            font-size: 15px;
            font-weight: 300;
            text-align: center;
            font-family: 'Mulish', sans-serif;
        }

        .notification-bell {
            width: 28px;
            height: 34px;
            background-color: #9790C6;
            border-radius: 4px;
            margin-bottom: 15px;
            position: relative;
        }

        .notification-bell::before {
            content: '';
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: #756EA4;
            border-radius: 50%;
            top: -5px;
            right: -5px;
        }

        /* Footer */
        .footer {
            background-color: #C8BFDC;
            height: 74px;
            width: 100%;
            position: fixed;
            bottom: 0;
            left: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            padding: 0 30px;
        }

        .copyright {
            color: #302B48;
            font-size: 14px;
            font-weight: 400;
        }

        /* Divider */
        .divider {
            width: 1px;
            height: 136px;
            background-color: rgba(217, 217, 217, 0.55);
            position: absolute;
            left: 33%;
            top: 234px;
            z-index: 10;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .dashboard-grid {
                grid-template-columns: repeat(6, 1fr);
            }
            
            .user-card, .messages-card, .search-card {
                grid-column: span 6;
            }
            
            .stat-card {
                grid-column: span 2;
            }
            
            .tickets-section, .notifications-card {
                grid-column: span 6;
            }
            
            .divider {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 0 15px;
            }
            
            .nav-menu {
                display: none;
            }
            
            .action-buttons {
                position: relative;
                right: 0;
                top: 0;
                margin-top: 20px;
                justify-content: flex-start;
            }
            
            .main-content {
                padding: 20px 15px;
            }
            
            .dashboard-grid {
                grid-template-columns: 1fr;
                grid-gap: 15px;
            }
            
            .user-card, .stat-card, .messages-card, .search-card, 
            .tickets-section, .notifications-card {
                grid-column: span 1;
            }
            
            .stat-card {
                height: 140px;
                padding: 20px;
            }
            
            .stat-number {
                font-size: 28px;
                margin-top: 40px;
            }
            
            .stat-label {
                font-size: 16px;
            }
            
            .footer {
                padding: 0 15px;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 28px;
            }
            
            .btn {
                padding: 8px 12px;
                font-size: 12px;
            }
            
            .user-info {
                gap: 10px;
            }
            
            .username {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <!-- Background Effects -->
    <div class="bg-effect bg-effect-1"></div>
    <div class="bg-effect bg-effect-2"></div>
    <div class="bg-effect bg-effect-3"></div>

    <!-- Alert Messages -->
    <?php if ($success): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <?php if ($message): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <?php if ($search_message): ?>
        <div class="alert alert-info">
            <i class="fas fa-search"></i> <?php echo $search_message; ?>
        </div>
    <?php endif; ?>

    <!-- Header -->
    <header class="header">
        <div class="logo-container">
            <div class="logo-icon">N</div>
            <div class="logo-text">NEXUS</div>
        </div>
        
        <nav class="nav-menu">
            <a href="<?php echo base_url('dashboard'); ?>" class="nav-item active" id="homeNav">Home</a>
            <a href="<?php echo base_url('dashboard/my_tickets'); ?>" class="nav-item" id="ticketsNav">My Tickets</a>
        </nav>
        
        <div class="user-info">
            <div class="username">Username</div>
            <div class="user-avatar">U</div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Username's Dashboard</h1>
            <p class="page-subtitle">Dashboard Area</p>
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="<?php echo base_url('dashboard/create_ticket'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Create New Ticket
                </a>
                <a href="<?php echo base_url('dashboard/my_tickets'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    My Tickets
                </a>
            </div>
        </div>

        <!-- Divider -->
        <div class="divider"></div>

        <!-- Dashboard Grid -->
        <div class="dashboard-grid">
            <!-- User Profile Card -->
            <div class="user-card">
                <h2 class="user-name">Username</h2>
                <div class="user-role">Customer</div>
                <div class="user-email">username@example.com</div>
                
                <div class="project-list">
                    Project :<br>
                    Project 1<br>
                    Project 2<br>
                    Project 3
                </div>
                
                <div class="profile-actions">
                    <button class="btn btn-secondary" id="updateBtn">Update</button>
                    <a href="<?php echo base_url('dashboard/logout'); ?>" class="btn btn-danger">Logout</a>
                </div>
            </div>

            <!-- Stat Cards -->
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <div class="stat-indicator indicator-purple"></div>
                <div class="stat-number">12</div>
                <div class="stat-label">Total Tickets</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-indicator indicator-green"></div>
                <div class="stat-number">5</div>
                <div class="stat-label">Active Tickets</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-indicator indicator-gray"></div>
                <div class="stat-number">7</div>
                <div class="stat-label">Resolved Tickets</div>
            </div>

            <!-- Support Messages Card -->
            <div class="messages-card">
                <div class="messages-header">
                    <h3 class="messages-title">Support Messages</h3>
                    <div class="message-icon"></div>
                </div>
                <div class="no-messages">No messages yet</div>
                <a href="<?php echo base_url('dashboard/view_messages'); ?>" class="btn btn-view-messages">View Messages</a>
            </div>

            <!-- Active Tickets Section -->
            <div class="tickets-section">
                <div class="section-header">
                    <h3 class="section-title">Your Active Tickets</h3>
                </div>
                <div class="section-content">
                    <div class="ticket-illustration"></div>
                    <div class="no-tickets">No active tickets found</div>
                </div>
            </div>

            <!-- Search Tickets Card -->
            <div class="search-card">
                <h3 class="search-title">Search Your Tickets</h3>
                <form id="searchForm" action="<?php echo base_url('dashboard/search_tickets'); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="text" name="search_term" class="search-input" placeholder="Find your tickets...">
                    <button type="submit" class="btn btn-search">Search</button>
                </form>
            </div>

            <!-- Notifications Card -->
            <div class="notifications-card">
                <div class="section-header notifications-header">
                    <h3 class="notifications-title">Recent Notifications</h3>
                    <div class="notification-icon"></div>
                </div>
                <div class="notifications-content">
                    <div class="notification-bell"></div>
                    <div class="no-notifications">No Recent Notifications Found</div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="copyright">
            Copyright © 2025 NexusArchSystem. All Rights Reserved.
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateX(100%)';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });

            // Navigation active state
            const currentPage = window.location.pathname;
            const navItems = document.querySelectorAll('.nav-item');
            
            navItems.forEach(item => {
                if (currentPage.includes(item.getAttribute('href'))) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });

            // Update button functionality
            document.getElementById('updateBtn').addEventListener('click', function() {
                alert('Update profile feature would open here.');
            });

            // Search form
            document.getElementById('searchForm').addEventListener('submit', function(e) {
                const searchInput = this.querySelector('input[name="search_term"]');
                if (!searchInput.value.trim()) {
                    e.preventDefault();
                    alert('Please enter a search term.');
                    searchInput.focus();
                }
            });

            // Simulate loading
            setTimeout(() => {
                console.log('Dashboard loaded successfully');
            }, 1000);
        });
    </script>
</body>
</html>