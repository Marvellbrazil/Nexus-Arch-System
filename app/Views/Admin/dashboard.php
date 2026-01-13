<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>Admin Dashboard - NEXUS<?= $this->endSection() ?>

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
<div class="relative z-10">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-[34.77px] font-semibold mb-2 text-text-dark">Admin Dashboard</h1>
        <p class="text-[15.45px] font-light text-text-dark">System overview and monitoring</p>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users Card -->
        <div class="stat-card">
            <div class="text-white/80 text-xs uppercase mb-1">Total Users</div>
            <div class="text-white text-2xl font-normal mb-2"><?= number_format($stats['total_users']) ?></div>
            <div class="stat-icon" style="background: rgba(145, 85, 253, 0.30);">
                <i class="fas fa-users text-[#C7A8FF] text-base"></i>
            </div>
        </div>

        <!-- Total Tickets Card -->
        <div class="stat-card">
            <div class="text-white/80 text-xs uppercase mb-1">Total Tickets</div>
            <div class="text-white text-2xl font-normal mb-2"><?= number_format($stats['total_tickets']) ?></div>
            <div class="stat-icon" style="background: rgba(255, 180, 0, 0.37);">
                <i class="fas fa-ticket-alt text-[#FEE29E] text-base"></i>
            </div>
        </div>

        <!-- Total Projects Card -->
        <div class="stat-card">
            <div class="text-white/80 text-xs uppercase mb-1">Total Projects</div>
            <div class="text-white text-2xl font-normal mb-2"><?= number_format($stats['total_projects']) ?></div>
            <div class="stat-icon" style="background: rgba(51.59, 185.49, 252.87, 0.27);">
                <i class="fas fa-project-diagram text-[#74CAF5] text-base"></i>
            </div>
        </div>

        <!-- Open Tickets Card -->
        <div class="stat-card">
            <div class="text-white/80 text-xs uppercase mb-1">Open Tickets</div>
            <div class="text-white text-2xl font-normal mb-2"><?= number_format($stats['open_tickets']) ?></div>
            <div class="stat-icon" style="background: rgba(192.65, 69.87, 73.30, 0.51);">
                <i class="fas fa-exclamation-circle text-[#FF4C51] text-base"></i>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Left Column: Ticket Status Overview -->
        <div class="lg:col-span-2">
            <div class="dashboard-card h-full">
                <div class="dashboard-card-header flex justify-between items-center">
                    <div class="text-text-dark/85 text-base font-medium">Ticket Status Overview</div>
                    <a href="<?= base_url('admin/tickets') ?>" class="view-all-btn">
                        <span>View All</span>
                        <i class="fas fa-chevron-right ml-1 text-sm"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4">
                    <!-- Donut Chart Area -->
                    <div class="relative flex items-center justify-center">
                        <div class="relative w-40 h-40">
                            <!-- Donut Chart Background -->
                            <div class="absolute inset-0 rounded-full bg-gray-200"></div>

                            <!-- Donut Chart Segments -->
                            <?php if (!empty($ticket_status) && $total_tickets_for_chart > 0): ?>
                                <div class="absolute inset-0 rounded-full" id="ticketDonutChart" style="background: conic-gradient(
                             <?php
                             $totalDeg = 0;
                             foreach ($ticket_status as $index => $status):
                                 $percentage = $status['percentage'];
                                 $deg = ($percentage / 100) * 360;
                                 $startDeg = $totalDeg;
                                 $endDeg = $totalDeg + $deg;
                                 $totalDeg = $endDeg;

                                 echo "{$status['color']} {$startDeg}deg {$endDeg}deg";
                                 if ($index < count($ticket_status) - 1)
                                     echo ", ";
                             endforeach;
                             ?>
                         );">
                                </div>
                            <?php else: ?>
                                <div class="absolute inset-0 rounded-full bg-gray-300 flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="text-gray-500 text-sm">No Data</div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Inner Circle -->
                            <div class="absolute inset-8 rounded-full bg-[#EAE8F3] flex items-center justify-center">
                                <div class="text-center">
                                    <div class="text-[21px] font-semibold text-[#302C5B]/70">
                                        <?= $total_tickets_for_chart ?>
                                    </div>
                                    <div class="text-[#5A5774] text-xs">Total Tickets</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Legend -->
                    <div class="space-y-4 pt-4">
                        <?php if (!empty($ticket_status)): ?>
                            <?php foreach ($ticket_status as $status): ?>
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-2">
                                        <div class="w-3 h-3 rounded-full" style="background: <?= $status['color'] ?>"></div>
                                        <span class="text-text-muted/80 text-sm"><?= $status['status_name'] ?></span>
                                    </div>
                                    <div class="text-text-muted/80 text-sm">
                                        <?= $status['count'] ?> (<?= $status['percentage'] ?>%)
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-4 text-gray-400">
                                <i class="fas fa-chart-pie text-xl mb-2"></i>
                                <p class="text-sm">No ticket data available</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Recent Activity -->
        <div class="lg:col-span-1">
            <div class="dashboard-card h-full">
                <div class="dashboard-card-header">
                    <div class="text-text-dark/85 text-base font-medium">Recent Activity</div>
                </div>

                <div class="space-y-6 p-4">
                    <?php if (empty($recentActivities)): ?>
                        <div class="text-center py-8 text-gray-400">
                            <i class="fas fa-history text-2xl mb-2"></i>
                            <p class="text-sm">No recent activities</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($recentActivities as $activity): ?>
                            <div class="activity-item">
                                <div class="activity-avatar" style="background: <?= $activity['avatar_color'] ?>;">
                                    <i class="fas fa-user text-gray-600 text-xs"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-gray-500 text-xs mb-1"><?= $activity['time'] ?></div>
                                    <div class="text-text-dark text-sm">
                                        <span class="font-medium"><?= $activity['user'] ?></span>
                                        (<?= $activity['role'] ?>)
                                        <?= $activity['action'] ?>
                                        <?php if ($activity['project']): ?>
                                            in <span class="font-medium"><?= $activity['project'] ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Project Overview -->
        <div class="lg:col-span-2">
            <div class="dashboard-card h-full">
                <div class="dashboard-card-header flex justify-between items-center">
                    <div class="text-text-dark/85 text-base font-medium">Project Overview</div>
                    <a href="<?= base_url('admin/projects') ?>" class="view-all-btn">
                        <span>View All</span>
                        <i class="fas fa-chevron-right ml-1 text-sm"></i>
                    </a>
                </div>

                <div class="mt-4 space-y-1 p-4">
                    <!-- Project Row Header -->
                    <div class="grid grid-cols-12 gap-4 py-2 px-2 text-text-dark/70 text-sm font-medium">
                        <div class="col-span-6">Project Name</div>
                        <div class="col-span-3 text-right">Total Tickets</div>
                        <div class="col-span-2 text-right">Open</div>
                        <div class="col-span-1"></div>
                    </div>

                    <?php if (empty($projectOverview)): ?>
                        <div class="text-center py-8 text-gray-400">
                            <i class="fas fa-project-diagram text-2xl mb-2"></i>
                            <p class="text-sm">No active projects</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($projectOverview as $project): ?>
                            <a href="<?= base_url('admin/projects/edit/' . $project['project_id']) ?>" class="project-row">
                                <div class="text-gray-700 text-sm font-medium"><?= $project['project_name'] ?></div>
                                <div class="flex items-center gap-6">
                                    <div class="text-[#817CB2] text-sm w-6 text-right"><?= $project['total_tickets'] ?? 0 ?>
                                    </div>
                                    <div class="text-[#403E6B] text-sm w-6 text-right"><?= $project['open_tickets'] ?? 0 ?>
                                    </div>
                                    <div class="text-gray-400 text-xs">
                                        <i class="fas fa-chevron-right"></i>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right Column: Quick Access & Notifications -->
        <div class="space-y-6">
            <!-- Quick Access -->
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <div class="text-text-dark/85 text-base font-medium">Quick Access</div>
                </div>

                <div class="mt-4 space-y-3 p-4">
                    <?php foreach ($quickLinks as $link): ?>
                        <a href="<?= $link['url'] ?>" class="quick-access-item">
                            <div class="quick-access-icon <?= $link['color'] ?>">
                                <i class="fas fa-<?= $link['icon'] ?>"></i>
                            </div>
                            <div class="text-[#434264] text-sm font-medium"><?= $link['title'] ?></div>
                            <div class="text-gray-400 ml-auto">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- System Notifications -->
            <div class="dashboard-card">
                <div class="dashboard-card-header flex justify-between items-center">
                    <div class="text-text-dark/85 text-base font-medium">System Notifications</div>
                    <button class="view-all-btn" onclick="markAllAsRead()">
                        <span>Mark All Read</span>
                    </button>
                </div>

                <div class="mt-4 space-y-4 p-4">
                    <?php if (empty($systemNotifications)): ?>
                        <div class="text-center py-8 text-gray-400">
                            <i class="fas fa-bell-slash text-2xl mb-2"></i>
                            <p class="text-sm">No notifications</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($systemNotifications as $notification): ?>
                            <div class="notification-item <?= $notification['type'] ?>">
                                <div class="flex items-start gap-3">
                                    <div class="text-lg mt-1" style="color: <?= $notification['color'] ?>">
                                        <i class="fas fa-<?= $notification['icon'] ?>"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-gray-500 text-xs mb-1"><?= $notification['time'] ?></div>
                                        <div class="text-[#6B624E] text-xs">
                                            <strong><?= $notification['title'] ?>:</strong> <?= $notification['message'] ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Interactive Features -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize animations
        initDashboardAnimations();

        // Initialize click handlers
        initClickHandlers();

        // Auto-refresh dashboard every 60 seconds
        setInterval(refreshDashboard, 60000);
    });

    function initDashboardAnimations() {
        setTimeout(() => {
            document.querySelectorAll('.stat-card, .dashboard-card').forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('animate-fadeInUp');
            });
        }, 100);
    }

    function initClickHandlers() {
        // Stat cards click
        document.querySelectorAll('.stat-card').forEach(card => {
            card.addEventListener('click', function () {
                const statType = this.querySelector('.text-xs').textContent;
                showStatModal(statType);
            });
        });

        // Project row hover effects
        document.querySelectorAll('.project-row').forEach(row => {
            row.addEventListener('mouseenter', function () {
                this.style.transform = 'translateX(4px)';
            });

            row.addEventListener('mouseleave', function () {
                this.style.transform = '';
            });
        });
    }

    function showStatModal(statType) {
        const statData = {
            'Total Users': {
                count: '<?= number_format($stats['total_users']) ?>',
                trend: '<?= $stats['user_trend'] ?? "0%" ?>',
                breakdown: <?= json_encode($stats['users_by_role'] ?? []) ?>
            },
            'Total Tickets': {
                count: '<?= number_format($stats['total_tickets']) ?>',
                trend: '<?= $stats['ticket_trend'] ?? "0%" ?>',
                breakdown: <?= json_encode($ticketStatus['data'] ?? []) ?>
            },
            'Total Projects': {
                count: '<?= number_format($stats['total_projects']) ?>',
                trend: 'Active',
                breakdown: <?= json_encode($projectOverview ?? []) ?>
            },
            'Open Tickets': {
                count: '<?= number_format($stats['open_tickets']) ?>',
                trend: 'Requiring attention',
                breakdown: []
            }
        };

        const data = statData[statType];
        if (!data) return;

        // Create modal
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4';
        modal.innerHTML = `
        <div class="bg-white rounded-xl w-full max-w-md animate-fadeInUp">
            <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">${statType} Details</h3>
                <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="p-4">
                <div class="text-center mb-4">
                    <div class="text-3xl font-bold text-secondary">${data.count}</div>
                    <div class="text-gray-500 mt-1">${data.trend}</div>
                </div>
                
                ${getStatBreakdownHTML(statType, data.breakdown)}
            </div>
        </div>
    `;

        document.body.appendChild(modal);
    }

    function getStatBreakdownHTML(statType, breakdown) {
        if (!breakdown || breakdown.length === 0) {
            return '<p class="text-gray-500 text-center py-4">No breakdown data available</p>';
        }

        let html = '<div class="space-y-2">';

        if (statType === 'Total Users') {
            breakdown.forEach(item => {
                html += `
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">${item.role_name}</span>
                    <span class="font-medium">${item.count}</span>
                </div>
            `;
            });
        } else if (statType === 'Total Tickets') {
            breakdown.forEach(item => {
                html += `
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full" style="background: ${item.color}"></div>
                        <span class="text-sm text-gray-600">${item.status_name}</span>
                    </div>
                    <span class="font-medium">${item.count} (${item.percentage}%)</span>
                </div>
            `;
            });
        }

        html += '</div>';
        return html;
    }

    function refreshDashboard() {
        fetch('<?= base_url("admin/dashboard/refresh") ?>')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update stats dynamically
                    updateDashboardStats(data.data);
                }
            })
            .catch(error => console.error('Error refreshing dashboard:', error));
    }

    function updateDashboardStats(data) {
        // Update stat cards
        const statCards = document.querySelectorAll('.stat-card');
        if (data.stats) {
            statCards[0].querySelector('.text-2xl').textContent = data.stats.total_users.toLocaleString();
            statCards[1].querySelector('.text-2xl').textContent = data.stats.total_tickets.toLocaleString();
            statCards[2].querySelector('.text-2xl').textContent = data.stats.total_projects.toLocaleString();
            statCards[3].querySelector('.text-2xl').textContent = data.stats.open_tickets.toLocaleString();
        }
    }

    function markAllAsRead() {
        document.querySelectorAll('.notification-item').forEach(item => {
            item.style.opacity = '0.6';
        });

        // Show toast
        showToast('All notifications marked as read', 'success');
    }

    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed top-6 right-6 px-4 py-2 rounded-lg shadow-lg z-50 ${type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                    'bg-blue-500 text-white'
            }`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
</script>

<style>
    /* Keep all existing styles from your original dashboard.php */
    /* Add any additional styles you need */

    .quick-access-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .notification-item.info {
        background: #F4F5FA;
        border: 1px solid #F3F4F6;
    }

    .notification-item.warning {
        background: #FFF4E5;
        border: 1px solid #FFE5BF;
    }

    .notification-item.danger {
        background: #FEE2E2;
        border: 1px solid #FECACA;
    }

    .notification-item.success {
        background: #ECFDF5;
        border: 1px solid #D1FAE5;
    }
</style>
<?= $this->endSection() ?>