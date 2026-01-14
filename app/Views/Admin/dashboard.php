<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>Admin Dashboard - NEXUS<?= $this->endSection() ?>

<?= $this->section('background_effects') ?>
<!-- Background Effects -->
<div class="fixed w-[40vw] h-[40vw] -right-[10%] -bottom-[10%] rotate-[149deg] bg-gradient-to-r from-[rgba(56.96,44.47,127.72,0.15)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-80 z-0"></div>
<div class="fixed w-[35vw] h-[25vw] -left-[5%] top-[10%] rotate-[8deg] bg-gradient-to-r from-[rgba(65.04,45.10,137.14,0.20)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-70 z-0"></div>
<div class="fixed w-[15vw] h-[20vw] right-[5%] -top-[5%] rotate-[8deg] bg-gradient-to-r from-[rgba(16.56,8.41,46.05,0.12)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-60 z-0"></div>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* Stat Card Styles - Purple Theme */
    .stat-card {
        background: linear-gradient(135deg, #3D3C5E 0%, #4A4570 100%);
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        color: white;
        cursor: pointer;
        height: 100%;
        min-height: 180px;
        display: flex;
        flex-direction: column;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 16px 48px rgba(0, 0, 0, 0.2);
        background: linear-gradient(135deg, #434264 0%, #524D7A 100%);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #C7A8FF 0%, #8A84C6 100%);
        border-radius: 20px 20px 0 0;
    }

    .stat-card .stat-icon {
        position: absolute;
        right: 24px;
        bottom: 24px;
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
    }

    /* Specific icon colors and backgrounds from original */
    .stat-card .stat-icon.users {
        background: rgba(145, 85, 253, 0.30);
    }
    .stat-card .stat-icon.users i {
        color: #C7A8FF;
    }

    .stat-card .stat-icon.tickets {
        background: rgba(255, 180, 0, 0.37);
    }
    .stat-card .stat-icon.tickets i {
        color: #FEE29E;
    }

    .stat-card .stat-icon.projects {
        background: rgba(51.59, 185.49, 252.87, 0.27);
    }
    .stat-card .stat-icon.projects i {
        color: #74CAF5;
    }

    .stat-card .stat-icon.open-tickets {
        background: rgba(192.65, 69.87, 73.30, 0.51);
    }
    .stat-card .stat-icon.open-tickets i {
        color: #FF4C51;
    }

    .stat-card .stat-label {
        font-size: 12px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 8px;
    }

    .stat-card .stat-value {
        font-size: 36px;
        font-weight: 600;
        line-height: 1;
        color: white;
        margin-bottom: 12px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .stat-card .stat-info {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 16px;
        line-height: 1.4;
    }

    .stat-card .stat-trend {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 500;
        padding-top: 16px;
        border-top: 1px solid rgba(255, 255, 255, 0.15);
        margin-top: auto;
    }

    .stat-card .stat-trend i {
        font-size: 12px;
    }

    .stat-card .stat-trend.positive {
        color: #A7F3D0;
    }

    .stat-card .stat-trend.negative {
        color: #FCA5A5;
    }

    .stat-card .stat-trend.neutral {
        color: #C7D2FE;
    }

    /* Dashboard Cards */
    .dashboard-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.85));
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        height: 100%;
    }

    .dashboard-card-header {
        padding: 24px;
        border-bottom: 1px solid rgba(226, 232, 240, 0.6);
        background: linear-gradient(90deg, rgba(248, 250, 252, 0.8), rgba(241, 245, 249, 0.6));
    }

    .card-title {
        font-size: 20px;
        font-weight: 600;
        color: #1F2937;
        margin-bottom: 4px;
    }

    .card-subtitle {
        font-size: 14px;
        color: #6B7280;
        font-weight: 400;
    }

    .view-all-btn {
        color: #756EA4;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
        padding: 8px 16px;
        border-radius: 10px;
        background: rgba(117, 110, 164, 0.1);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }

    .view-all-btn:hover {
        background: rgba(117, 110, 164, 0.2);
        transform: translateX(4px);
    }

    /* Activity Items */
    .activity-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 16px;
        border-radius: 14px;
        background: rgba(249, 250, 251, 0.8);
        border: 1px solid rgba(229, 231, 235, 0.6);
        margin-bottom: 12px;
        transition: all 0.3s ease;
    }

    .activity-item:hover {
        background: white;
        border-color: #D6D3EE;
        transform: translateX(4px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .activity-avatar {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: linear-gradient(135deg, #F0E9F9, #E4DFF6);
        color: #756EA4;
        font-weight: 600;
    }

    /* Project Rows */
    .project-row {
        display: grid;
        grid-template-columns: 1fr auto;
        align-items: center;
        padding: 20px;
        border-radius: 14px;
        background: rgba(249, 250, 251, 0.8);
        border: 1px solid rgba(229, 231, 235, 0.6);
        margin-bottom: 12px;
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
    }

    .project-row:hover {
        background: white;
        border-color: #D6D3EE;
        transform: translateX(4px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    /* Quick Access Items */
    .quick-access-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px;
        border-radius: 14px;
        background: rgba(249, 250, 251, 0.8);
        border: 1px solid rgba(229, 231, 235, 0.6);
        margin-bottom: 12px;
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
    }

    .quick-access-item:hover {
        background: white;
        border-color: #D6D3EE;
        transform: translateX(4px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .quick-access-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        background: linear-gradient(135deg, var(--icon-start), var(--icon-end));
        color: white;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Notification Items */
    .notification-item {
        padding: 20px;
        border-radius: 14px;
        background: rgba(249, 250, 251, 0.8);
        border: 1px solid rgba(229, 231, 235, 0.6);
        margin-bottom: 12px;
        transition: all 0.3s ease;
        border-left: 4px solid;
    }

    .notification-item:hover {
        background: white;
        border-color: #D6D3EE;
        transform: translateX(4px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    /* Donut Chart */
    .donut-chart-container {
        position: relative;
        width: 200px;
        height: 200px;
        margin: 0 auto;
    }

    .donut-chart {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        position: relative;
        overflow: hidden;
    }

    .donut-center {
        position: absolute;
        width: 120px;
        height: 120px;
        background: #EAE8F3;
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .donut-total {
        font-size: 28px;
        font-weight: 600;
        color: #302C5B;
        line-height: 1;
    }

    .donut-label {
        font-size: 13px;
        color: #5A5774;
        margin-top: 4px;
    }

    /* Status Legend */
    .status-item {
        display: flex;
        align-items: center;
        padding: 16px;
        background: rgba(249, 250, 251, 0.8);
        border-radius: 12px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }

    .status-item:hover {
        background: white;
        transform: translateX(4px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    }

    .status-color {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 16px;
        flex-shrink: 0;
    }

    .status-info {
        flex: 1;
        min-width: 0;
    }

    .status-name {
        font-size: 14px;
        color: #374151;
        font-weight: 500;
        margin-bottom: 2px;
    }

    .status-count {
        font-size: 16px;
        font-weight: 600;
        color: #1F2937;
    }

    .status-percentage {
        font-size: 14px;
        color: #6B7280;
        font-weight: 500;
    }

    /* Animations */
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

    .animate-fadeInUp {
        animation: fadeInUp 0.6s ease-out;
    }

    /* Custom scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(241, 245, 249, 0.5);
        border-radius: 3px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(117, 110, 164, 0.5);
        border-radius: 3px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(117, 110, 164, 0.7);
    }

    /* Responsive adjustments */
    @media (max-width: 640px) {
        .stat-card {
            padding: 20px;
            min-height: 160px;
        }
        
        .stat-card .stat-value {
            font-size: 32px;
        }
        
        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            font-size: 20px;
            right: 20px;
            bottom: 20px;
        }
        
        .donut-chart-container {
            width: 160px;
            height: 160px;
        }
        
        .donut-center {
            width: 96px;
            height: 96px;
        }
        
        .donut-total {
            font-size: 24px;
        }
    }
</style>
<?= $this->endSection() ?>

<?php
// Helper functions
function getStatValue($stats, $key) {
    return isset($stats[$key]) ? number_format($stats[$key]) : '0';
}

function getTrendValue($stats, $key) {
    return isset($stats[$key]) ? $stats[$key] : '0%';
}

// Data fallbacks
$ticket_status = $ticket_status ?? [];
$total_tickets_for_chart = $total_tickets_for_chart ?? 0;
$recentActivities = $recentActivities ?? [];
$projectOverview = $projectOverview ?? [];
?>

<?= $this->section('content') ?>
<div class="mt-4 md:mt-[77px] px-4 md:px-[30px] py-4 md:py-[20px] relative z-10">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex-1">
                <h1 class="text-2xl md:text-3xl lg:text-[34px] font-semibold mb-2 text-text-dark">Admin Dashboard</h1>
                <p class="text-sm md:text-base text-gray-600">Welcome back, Administrator. Here's what's happening with your system.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-600 hidden md:inline">
                    <i class="fas fa-clock mr-2"></i>Last updated: <?= date('h:i A') ?>
                </span>
                <button onclick="refreshDashboard()" 
                        class="px-4 py-2.5 bg-secondary text-white rounded-xl hover:bg-[#665C9E] transition-all text-sm font-medium flex items-center gap-2 shadow-lg shadow-secondary/20">
                    <i class="fas fa-sync-alt"></i>
                    <span>Refresh</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards Grid - PURPLE THEME LIKE ORIGINAL -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users Card -->
        <div class="stat-card" onclick="showStatModal('Total Users')">
            <div class="stat-label">Total Users</div>
            <div class="stat-value"><?= getStatValue($stats ?? [], 'total_users') ?></div>
            <div class="stat-info">All registered users</div>

            <div class="stat-icon users">
                <i class="fas fa-users"></i>
            </div>
        </div>

        <!-- Total Tickets Card -->
        <div class="stat-card" onclick="showStatModal('Total Tickets')">
            <div class="stat-label">Total Tickets</div>
            <div class="stat-value"><?= getStatValue($stats ?? [], 'total_tickets') ?></div>
            <div class="stat-info">All support requests created</div>

            <div class="stat-icon tickets">
                <i class="fas fa-ticket-alt"></i>
            </div>
        </div>

        <!-- Total Projects Card -->
        <div class="stat-card" onclick="showStatModal('Total Projects')">
            <div class="stat-label">Total Projects</div>
            <div class="stat-value"><?= getStatValue($stats ?? [], 'total_projects') ?></div>
            <div class="stat-info">Active projects in the system</div>

            <div class="stat-icon projects">
                <i class="fas fa-project-diagram"></i>
            </div>
        </div>

        <!-- Open Tickets Card -->
        <div class="stat-card" onclick="showStatModal('Open Tickets')">
            <div class="stat-label">Open Tickets</div>
            <div class="stat-value"><?= getStatValue($stats ?? [], 'open_tickets') ?></div>
            <div class="stat-info">Requiring immediate attention</div>

            <div class="stat-icon open-tickets">
                <i class="fas fa-exclamation-circle"></i>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Ticket Status Overview -->
        <div class="lg:col-span-2">
            <div class="dashboard-card">
                <div class="dashboard-card-header flex justify-between items-center">
                    <div>
                        <div class="card-title">Ticket Status Overview</div>
                        <div class="card-subtitle">Current distribution of tickets</div>
                    </div>
                    <a href="<?= base_url('admin/tickets') ?>" class="view-all-btn">
                        <span>View All Tickets</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <div class="p-6">
                    <div class="flex flex-col lg:flex-row items-center lg:items-start gap-8">
                        <!-- Donut Chart -->
                        <div class="donut-chart-container">
                            <div class="donut-chart" style="background: conic-gradient(
                                <?php 
                                $totalDeg = 0;
                                if (!empty($ticket_status)) {
                                    foreach ($ticket_status as $index => $status) {
                                        $color = $status['color'] ?? '#756EA4';
                                        $percentage = $status['percentage'] ?? 0;
                                        $deg = ($percentage / 100) * 360;
                                        
                                        echo $color . ' ' . $totalDeg . 'deg ' . ($totalDeg + $deg) . 'deg';
                                        $totalDeg += $deg;
                                        if ($index < count($ticket_status) - 1) echo ', ';
                                    }
                                } else {
                                    echo '#E5E7EB 0deg 360deg';
                                }
                                ?>
                            );"></div>
                            <div class="donut-center">
                                <div class="donut-total"><?= $total_tickets_for_chart ?></div>
                                <div class="donut-label">Total Tickets</div>
                            </div>
                        </div>

                        <!-- Status Legend -->
                        <div class="flex-1 w-full">
                            <?php if (!empty($ticket_status)): ?>
                                <div class="space-y-3">
                                    <?php foreach ($ticket_status as $status): ?>
                                        <div class="status-item">
                                            <div class="status-color" style="background: <?= $status['color'] ?? '#756EA4' ?>"></div>
                                            <div class="status-info">
                                                <div class="status-name"><?= $status['status_name'] ?? 'Unknown' ?></div>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <div class="status-count"><?= $status['count'] ?? 0 ?></div>
                                                <div class="status-percentage"><?= $status['percentage'] ?? 0 ?>%</div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-12">
                                    <div class="w-20 h-20 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-chart-pie text-gray-400 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-500">No ticket data available</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="lg:col-span-1">
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <div class="card-title">Recent Activity</div>
                    <div class="card-subtitle">Latest system activities</div>
                </div>

                <div class="p-6 max-h-[420px] overflow-y-auto custom-scrollbar">
                    <?php if (empty($recentActivities)): ?>
                        <div class="text-center py-12">
                            <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-history text-gray-400 text-xl"></i>
                            </div>
                            <p class="text-gray-500">No recent activities</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($recentActivities as $activity): ?>
                                <div class="activity-item">
                                    <div class="activity-avatar" style="background: <?= $activity['avatar_color'] ?? 'linear-gradient(135deg, #F0E9F9, #E4DFF6)' ?>;">
                                        <i class="fas fa-user text-secondary"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="font-semibold text-gray-800"><?= $activity['user'] ?? 'Unknown User' ?></span>
                                            <span class="text-gray-500 text-xs"><?= $activity['time'] ?? 'Recently' ?></span>
                                        </div>
                                        <p class="text-gray-600 text-sm mb-2">
                                            <?= $activity['action'] ?? 'Performed an action' ?>
                                            <?php if ($activity['project'] ?? false): ?>
                                                in <span class="font-medium text-secondary"><?= $activity['project'] ?></span>
                                            <?php endif; ?>
                                        </p>
                                        <div class="flex items-center gap-2">
                                            <span class="px-3 py-1 bg-secondary/10 text-secondary text-xs font-medium rounded-full">
                                                <?= $activity['role'] ?? 'User' ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Project Overview -->
        <div class="lg:col-span-2">
            <div class="dashboard-card">
                <div class="dashboard-card-header flex justify-between items-center">
                    <div>
                        <div class="card-title">Project Overview</div>
                        <div class="card-subtitle">Active projects and ticket status</div>
                    </div>
                    <a href="<?= base_url('admin/projects') ?>" class="view-all-btn">
                        <span>View All Projects</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <div class="p-6">
                    <?php if (empty($projectOverview)): ?>
                        <div class="text-center py-12">
                            <div class="w-20 h-20 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-project-diagram text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-500">No active projects</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($projectOverview as $project): ?>
                                <a href="<?= base_url('admin/projects/edit/' . ($project['project_id'] ?? '')) ?>" class="project-row">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                                            <i class="fas fa-project-diagram text-blue-600 text-lg"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="font-semibold text-gray-800 truncate"><?= $project['project_name'] ?? 'Unnamed Project' ?></div>
                                            <div class="text-gray-500 text-sm"><?= $project['project_code'] ?? '' ?></div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-6">
                                        <div class="text-center">
                                            <div class="font-bold text-gray-800 text-lg"><?= $project['total_tickets'] ?? 0 ?></div>
                                            <div class="text-gray-500 text-xs">Tickets</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="font-bold text-gray-800 text-lg"><?= $project['open_tickets'] ?? 0 ?></div>
                                            <div class="text-gray-500 text-xs">Open</div>
                                        </div>
                                        <div class="text-gray-400">
                                            <i class="fas fa-chevron-right"></i>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Quick Access & Notifications -->
        <div class="space-y-8">
            <!-- Quick Access -->
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <div class="card-title">Quick Access</div>
                    <div class="card-subtitle">Frequently used actions</div>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        <?php 
                        // Define quick links with the structure from original code
                        $quickLinks = [
                            [
                                'url' => base_url('admin/tickets'),
                                'icon' => 'ticket-alt',
                                'title' => 'Manage Tickets',
                                'color' => 'bg-[rgba(255,180,0,0.37)]'
                            ],
                            [
                                'url' => base_url('admin/users'),
                                'icon' => 'users',
                                'title' => 'User Management',
                                'color' => 'bg-[rgba(145,85,253,0.30)]'
                            ],
                            [
                                'url' => base_url('admin/projects'),
                                'icon' => 'project-diagram',
                                'title' => 'Projects',
                                'color' => 'bg-[rgba(51.59,185.49,252.87,0.27)]'
                            ],
                            [
                                'url' => base_url('admin/settings'),
                                'icon' => 'cog',
                                'title' => 'Settings',
                                'color' => 'bg-[rgba(192.65,69.87,73.30,0.51)]'
                            ]
                        ];
                        ?>
                        
                        <?php foreach ($quickLinks as $link): ?>
                            <a href="<?= $link['url'] ?>" class="quick-access-item">
                                <div class="quick-access-icon <?= $link['color'] ?>">
                                    <i class="fas fa-<?= $link['icon'] ?>"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-semibold text-gray-800 truncate"><?= $link['title'] ?></div>
                                    <div class="text-gray-500 text-sm truncate">Quick access to <?= strtolower($link['title']) ?></div>
                                </div>
                                <div class="text-gray-400">
                                    <i class="fas fa-chevron-right"></i>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- System Notifications -->
            <div class="dashboard-card">
                <div class="dashboard-card-header flex justify-between items-center">
                    <div>
                        <div class="card-title">System Notifications</div>
                        <div class="card-subtitle">Latest system alerts</div>
                    </div>
                    <button onclick="markAllAsRead()" class="view-all-btn">
                        <span>Mark All Read</span>
                    </button>
                </div>

                <div class="p-6 max-h-[300px] overflow-y-auto custom-scrollbar">
                    <?php 
                    // Define notifications with the structure from original code
                    $systemNotifications = [
                        [
                            'title' => 'System Update',
                            'message' => 'System maintenance scheduled for tonight',
                            'time' => '2 hours ago',
                            'color' => '#3B82F6',
                            'icon' => 'info-circle',
                            'type' => 'info'
                        ],
                        [
                            'title' => 'New Tickets',
                            'message' => '5 new tickets require attention',
                            'time' => '4 hours ago',
                            'color' => '#F59E0B',
                            'icon' => 'exclamation-triangle',
                            'type' => 'warning'
                        ],
                        [
                            'title' => 'User Registration',
                            'message' => 'New user registered in the system',
                            'time' => '6 hours ago',
                            'color' => '#10B981',
                            'icon' => 'user-plus',
                            'type' => 'success'
                        ]
                    ];
                    ?>
                    
                    <?php if (empty($systemNotifications)): ?>
                        <div class="text-center py-8">
                            <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-bell-slash text-gray-400 text-xl"></i>
                            </div>
                            <p class="text-gray-500">No notifications</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($systemNotifications as $notification): ?>
                                <div class="notification-item <?= $notification['type'] ?>" style="border-left-color: <?= $notification['color'] ?>;">
                                    <div class="flex items-start gap-4">
                                        <div class="text-lg mt-1" style="color: <?= $notification['color'] ?>">
                                            <i class="fas fa-<?= $notification['icon'] ?>"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-gray-500 text-xs mb-1"><?= $notification['time'] ?></div>
                                            <div class="text-gray-800 text-sm">
                                                <strong><?= $notification['title'] ?>:</strong> <?= $notification['message'] ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize animations
        initDashboardAnimations();
        
        // Auto-refresh dashboard every 30 seconds
        setInterval(refreshDashboard, 30000);
    });
    
    function initDashboardAnimations() {
        setTimeout(() => {
            const elements = document.querySelectorAll('.stat-card, .dashboard-card');
            elements.forEach((element, index) => {
                element.style.animationDelay = `${index * 0.1}s`;
                element.classList.add('animate-fadeInUp');
            });
        }, 100);
    }
    
    function showStatModal(statType) {
        const statData = {
            'Total Users': {
                count: '<?= getStatValue($stats ?? [], 'total_users') ?>',
                trend: '<?= getTrendValue($stats ?? [], 'user_trend') ?>',
                description: 'Total number of registered users in the system'
            },
            'Total Tickets': {
                count: '<?= getStatValue($stats ?? [], 'total_tickets') ?>',
                trend: '<?= getTrendValue($stats ?? [], 'ticket_trend') ?>',
                description: 'Total number of support tickets created'
            },
            'Total Projects': {
                count: '<?= getStatValue($stats ?? [], 'total_projects') ?>',
                trend: 'Active',
                description: 'Number of active projects in the system'
            },
            'Open Tickets': {
                count: '<?= getStatValue($stats ?? [], 'open_tickets') ?>',
                trend: 'Requiring attention',
                description: 'Tickets that need immediate review and action'
            }
        };
        
        const data = statData[statType];
        if (!data) return;
        
        // Create modal
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4';
        modal.innerHTML = `
            <div class="bg-white rounded-2xl w-full max-w-md animate-fadeInUp">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800">${statType}</h3>
                        <p class="text-gray-600 text-sm mt-1">${data.description}</p>
                    </div>
                    <button onclick="this.closest('.fixed').remove()" 
                            class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                
                <div class="p-6">
                    <div class="text-center mb-6">
                        <div class="text-5xl font-bold text-secondary mb-3">${data.count}</div>
                        <div class="text-gray-500">${data.trend}</div>
                    </div>
                    
                    <div class="text-center">
                        <p class="text-gray-500 text-sm">
                            Detailed analytics and breakdown will be available in the next update.
                        </p>
                    </div>
                </div>
                
                <div class="p-6 border-t border-gray-200">
                    <button onclick="this.closest('.fixed').remove()" 
                            class="w-full py-3 bg-secondary text-white rounded-xl hover:bg-[#665C9E] transition-colors font-medium">
                        Close
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
    }
    
    function refreshDashboard() {
        const refreshBtn = document.querySelector('button[onclick="refreshDashboard()"]');
        const originalContent = refreshBtn.innerHTML;
        
        refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
        refreshBtn.disabled = true;
        
        // Simulate API call
        setTimeout(() => {
            // Update timestamp
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { 
                hour: 'numeric', 
                minute: '2-digit',
                hour12: true 
            });
            
            const timeElement = document.querySelector('.text-gray-600.text-sm .fa-clock')?.parentElement;
            if (timeElement) {
                timeElement.innerHTML = `<i class="fas fa-clock mr-2"></i>Last updated: ${timeString}`;
            }
            
            showToast('Dashboard refreshed successfully', 'success');
            
            setTimeout(() => {
                refreshBtn.innerHTML = originalContent;
                refreshBtn.disabled = false;
            }, 1000);
        }, 1500);
    }
    
    function markAllAsRead() {
        document.querySelectorAll('.notification-item').forEach(item => {
            item.style.opacity = '0.6';
            item.style.transform = 'scale(0.98)';
        });
        
        // Show toast
        showToast('All notifications marked as read', 'success');
        
        // Reset after 2 seconds
        setTimeout(() => {
            document.querySelectorAll('.notification-item').forEach(item => {
                item.style.opacity = '';
                item.style.transform = '';
            });
        }, 2000);
    }
    
    function showToast(message, type = 'info') {
        // Remove existing toast
        const existingToast = document.querySelector('.custom-toast');
        if (existingToast) {
            existingToast.remove();
        }
        
        const toast = document.createElement('div');
        toast.className = `custom-toast fixed top-6 right-6 px-4 py-3 rounded-xl shadow-xl z-50 animate-fadeInUp ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        toast.innerHTML = `
            <div class="flex items-center gap-3">
                <i class="fas ${
                    type === 'success' ? 'fa-check-circle' :
                    type === 'error' ? 'fa-exclamation-circle' :
                    'fa-info-circle'
                }"></i>
                <span class="text-sm font-medium">${message}</span>
            </div>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }
</script>
<?= $this->endSection() ?>