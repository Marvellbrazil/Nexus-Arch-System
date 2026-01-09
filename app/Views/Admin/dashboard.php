<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>Admin Dashboard - NEXUS<?= $this->endSection() ?>

<?= $this->section('background_effects') ?>
<!-- Background Effects -->
<div class="fixed w-[40vw] h-[40vw] -right-[10%] -bottom-[10%] rotate-[149deg] bg-gradient-to-r from-[rgba(56.96,44.47,127.72,0.15)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-80 z-0"></div>
<div class="fixed w-[35vw] h-[25vw] -left-[5%] top-[10%] rotate-[8deg] bg-gradient-to-r from-[rgba(65.04,45.10,137.14,0.20)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-70 z-0"></div>
<div class="fixed w-[15vw] h-[20vw] right-[5%] -top-[5%] rotate-[8deg] bg-gradient-to-r from-[rgba(16.56,8.41,46.05,0.12)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-60 z-0"></div>
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
            <div class="text-white text-2xl font-normal mb-2">1,203</div>
            <div class="stat-icon" style="background: rgba(145, 85, 253, 0.30);">
                <i class="fas fa-users text-[#C7A8FF] text-base"></i>
            </div>
        </div>
        
        <!-- Total Tickets Card -->
        <div class="stat-card">
            <div class="text-white/80 text-xs uppercase mb-1">Total Tickets</div>
            <div class="text-white text-2xl font-normal mb-2">376</div>
            <div class="stat-icon" style="background: rgba(255, 180, 0, 0.37);">
                <i class="fas fa-ticket-alt text-[#FEE29E] text-base"></i>
            </div>
        </div>
        
        <!-- Total Projects Card -->
        <div class="stat-card">
            <div class="text-white/80 text-xs uppercase mb-1">Total Projects</div>
            <div class="text-white text-2xl font-normal mb-2">28</div>
            <div class="stat-icon" style="background: rgba(51.59, 185.49, 252.87, 0.27);">
                <i class="fas fa-project-diagram text-[#74CAF5] text-base"></i>
            </div>
        </div>
        
        <!-- Open Tickets Card -->
        <div class="stat-card">
            <div class="text-white/80 text-xs uppercase mb-1">Open Tickets</div>
            <div class="text-white text-2xl font-normal mb-2">112</div>
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
                    <button class="view-all-btn">
                        <span>View All</span>
                        <i class="fas fa-chevron-right ml-1 text-sm"></i>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4">
                    <!-- Donut Chart Area -->
                    <div class="relative flex items-center justify-center">
                        <div class="relative w-40 h-40">
                            <!-- Donut Chart Background -->
                            <div class="absolute inset-0 rounded-full bg-gray-200"></div>
                            
                            <!-- Donut Chart Segments -->
                            <div class="absolute inset-0 rounded-full" 
                                 style="background: conic-gradient(
                                     #635A91 19%, 
                                     #AFB9D4 0 60%, 
                                     #EDE1C7 0 66%, 
                                     #89A6CE 0 83%, 
                                     #BDB7D9 0 100%
                                 );">
                            </div>
                            
                            <!-- Inner Circle -->
                            <div class="absolute inset-8 rounded-full bg-[#EAE8F3] flex items-center justify-center">
                                <div class="text-center">
                                    <div class="text-[21px] font-semibold text-[#302C5B]/70">100</div>
                                    <div class="text-[#5A5774] text-xs">Total Tickets</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status Legend -->
                    <div class="space-y-4 pt-4">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-[#635A91]"></div>
                                <span class="text-text-muted/80 text-sm">Open</span>
                            </div>
                            <div class="text-text-muted/80 text-sm">19 (19%)</div>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-[#AFB9D4]"></div>
                                <span class="text-text-muted/80 text-sm">On Progress</span>
                            </div>
                            <div class="text-text-muted/80 text-sm">41 (41%)</div>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-[#EDE1C7]"></div>
                                <span class="text-text-muted/80 text-sm">Need Info</span>
                            </div>
                            <div class="text-text-muted/80 text-sm">6 (6%)</div>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-[#89A6CE]"></div>
                                <span class="text-text-muted/80 text-sm">Resolved</span>
                            </div>
                            <div class="text-text-muted/80 text-sm">17 (17%)</div>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-[#BDB7D9]"></div>
                                <span class="text-text-muted/80 text-sm">Closed</span>
                            </div>
                            <div class="text-text-muted/80 text-sm">17 (17%)</div>
                        </div>
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
                    <!-- Activity Item 1 -->
                    <div class="activity-item">
                        <div class="activity-avatar" style="background: #F3E8FF;">
                            <i class="fas fa-user text-[#9333EA] text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-gray-500 text-xs mb-1">10 minutes ago</div>
                            <div class="text-text-dark text-sm">
                                <span class="text-[#4F46E5] font-medium">Iput</span> (Support) assigned #1491 to Developer
                            </div>
                        </div>
                    </div>
                    
                    <!-- Activity Item 2 -->
                    <div class="activity-item">
                        <div class="activity-avatar" style="background: #DBEAFE;">
                            <i class="fas fa-user text-[#2563EB] text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-gray-500 text-xs mb-1">25 minutes ago</div>
                            <div class="text-text-dark text-sm">
                                <span class="text-[#2563EB] font-medium">Dani</span> (Customer) created ticket #1478: "Bug: Unable to login"
                            </div>
                        </div>
                    </div>
                    
                    <!-- Activity Item 3 -->
                    <div class="activity-item">
                        <div class="activity-avatar" style="background: #E0E7FF;">
                            <i class="fas fa-user-shield text-[#4F46E5] text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-gray-500 text-xs mb-1">1 hour ago</div>
                            <div class="text-text-dark text-sm">
                                <span class="text-[#4F46E5] font-medium">Bigmo Nasihuy</span> (Admin) marked ticket #1432 as Resolved
                            </div>
                        </div>
                    </div>
                    
                    <!-- Activity Item 4 -->
                    <div class="activity-item">
                        <div class="activity-avatar" style="background: #F3E8FF;">
                            <i class="fas fa-headset text-[#9333EA] text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-gray-500 text-xs mb-1">2 hours ago</div>
                            <div class="text-text-dark text-sm">
                                <span class="text-[#9333EA] font-medium">Support</span> assigned ticket #1428 to Project A
                            </div>
                        </div>
                    </div>
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
                    <button class="view-all-btn">
                        <span>View All</span>
                        <i class="fas fa-chevron-right ml-1 text-sm"></i>
                    </button>
                </div>
                
                <div class="mt-4 space-y-1 p-4">
                    <!-- Project Row Header -->
                    <div class="grid grid-cols-12 gap-4 py-2 px-2 text-text-dark/70 text-sm font-medium">
                        <div class="col-span-6">Project Name</div>
                        <div class="col-span-3 text-right">Total Tickets</div>
                        <div class="col-span-2 text-right">Open</div>
                        <div class="col-span-1"></div>
                    </div>
                    
                    <!-- Project Row 1 -->
                    <div class="project-row">
                        <div class="text-gray-700 text-sm font-medium">Project A</div>
                        <div class="flex items-center gap-6">
                            <div class="text-[#817CB2] text-sm w-6 text-right">45</div>
                            <div class="text-[#403E6B] text-sm w-6 text-right">8</div>
                            <div class="text-gray-400 text-xs">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Project Row 2 -->
                    <div class="project-row">
                        <div class="text-gray-700 text-sm font-medium">Project B</div>
                        <div class="flex items-center gap-6">
                            <div class="text-[#665C9E] text-sm w-6 text-right">32</div>
                            <div class="text-[#302B48] text-sm w-6 text-right">14</div>
                            <div class="text-gray-400 text-xs">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Project Row 3 -->
                    <div class="project-row">
                        <div class="text-gray-700 text-sm font-medium">Project C</div>
                        <div class="flex items-center gap-6">
                            <div class="text-[#817CB2] text-sm w-6 text-right">28</div>
                            <div class="text-[#302B48] text-sm w-6 text-right">9</div>
                            <div class="text-gray-400 text-xs">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Project Row 4 -->
                    <div class="project-row">
                        <div class="text-gray-700 text-sm font-medium">Project D</div>
                        <div class="flex items-center gap-6">
                            <div class="text-[#817CB2] text-sm w-6 text-right">26</div>
                            <div class="text-[#403E6B] text-sm w-6 text-right">7</div>
                            <div class="text-gray-400 text-xs">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Project Row 5 -->
                    <div class="project-row">
                        <div class="text-gray-700 text-sm font-medium">Project E</div>
                        <div class="flex items-center gap-6">
                            <div class="text-gray-500 text-sm w-6 text-right">23</div>
                            <div class="text-gray-500 text-sm w-6 text-right">3</div>
                            <div class="text-gray-300 text-xs">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </div>
                    </div>
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
                    <!-- Quick Access Item 1 -->
                    <a href="<?= base_url('admin/users') ?>" class="quick-access-item">
                        <div class="quick-access-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="text-[#434264] text-sm font-medium">Manage Users</div>
                        <div class="text-gray-400 ml-auto">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                    
                    <!-- Quick Access Item 2 -->
                    <a href="<?= base_url('admin/roles') ?>" class="quick-access-item">
                        <div class="quick-access-icon">
                            <i class="fas fa-user-tag"></i>
                        </div>
                        <div class="text-[#434264] text-sm font-medium">Manage Roles</div>
                        <div class="text-gray-400 ml-auto">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                    
                    <!-- Quick Access Item 3 -->
                    <a href="<?= base_url('admin/departments') ?>" class="quick-access-item">
                        <div class="quick-access-icon">
                            <i class="fas fa-sitemap"></i>
                        </div>
                        <div class="text-[#434264] text-sm font-medium">Manage Departments</div>
                        <div class="text-gray-400 ml-auto">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                    
                    <!-- Quick Access Item 4 -->
                    <a href="<?= base_url('admin/tickets') ?>" class="quick-access-item">
                        <div class="quick-access-icon">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <div class="text-gray-600 text-sm font-medium">View Tickets</div>
                        <div class="text-gray-400 ml-auto">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                </div>
            </div>
            
            <!-- System Notifications -->
            <div class="dashboard-card">
                <div class="dashboard-card-header flex justify-between items-center">
                    <div class="text-text-dark/85 text-base font-medium">System Notifications</div>
                    <button class="view-all-btn">
                        <span>View All</span>
                        <i class="fas fa-chevron-right ml-1 text-sm"></i>
                    </button>
                </div>
                
                <div class="mt-4 space-y-4 p-4">
                    <!-- Notification 1 -->
                    <div class="notification-item warning">
                        <div class="flex items-start gap-3">
                            <div class="text-[#FFB400] text-lg mt-1">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-gray-500 text-xs mb-1">15 minutes ago</div>
                                <div class="text-[#6B624E] text-xs">
                                    Project Alpha has 12 unresolved tickets nearing the SLA limit. Immediate attention is required.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notification 2 -->
                    <div class="notification-item info">
                        <div class="flex items-start gap-3">
                            <div class="text-[#9155FD] text-lg mt-1">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-gray-500 text-xs mb-1">1h ago</div>
                                <div class="text-text-dark/65 text-xs">
                                    Scheduled system maintenance will occur tonight at 10:00 PM. Some services may be temporarily unavailable.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notification 3 -->
                    <div class="notification-item success">
                        <div class="flex items-start gap-3">
                            <div class="text-[#10B981] text-lg mt-1">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-gray-500 text-xs mb-1">3h ago</div>
                                <div class="text-text-dark/65 text-xs">
                                    Database backup completed successfully. Backup file size: 2.4GB
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom animations for dashboard elements */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fadeInUp {
        animation: fadeInUp 0.6s ease-out;
    }
    
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(20px); }
        to { opacity: 1; transform: translateX(0); }
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
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
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
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .dashboard-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    }
    
    .dashboard-card-header {
        background: #E3DAEE;
        border-radius: 7.73px 7.73px 0 0;
        padding: 16px 20px;
        margin: -1px -1px 0 -1px;
    }
    
    /* View All Button */
    .view-all-btn {
        display: flex;
        align-items: center;
        color: rgba(102, 92, 158, 0.70);
        font-size: 12px;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .view-all-btn:hover {
        color: #665C9E;
    }
    
    /* Quick Access Item */
    .quick-access-item {
        background: white;
        border-radius: 8px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.3s ease;
        text-decoration: none;
        border: 1px solid #F3F4F6;
    }
    
    .quick-access-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-color: #E5E7EB;
        text-decoration: none;
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
        padding: 12px;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .project-row:hover {
        background: rgba(117, 110, 164, 0.08);
    }
    
    /* Notification Item */
    .notification-item {
        padding: 12px;
        border-radius: 8px;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .notification-item.warning {
        background: #FFF4E5;
        border: 1px solid #FFE5BF;
    }
    
    .notification-item.info {
        background: #F4F5FA;
        border: 1px solid #F3F4F6;
    }
    
    .notification-item.success {
        background: #ECFDF5;
        border: 1px solid #D1FAE5;
    }
    
    .notification-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .dashboard-card-header {
            padding: 12px 16px;
        }
        
        .stat-card {
            height: 80px;
            padding: 16px;
        }
        
        .stat-icon {
            width: 36px;
            height: 36px;
        }
        
        .project-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add animation classes on load
        setTimeout(() => {
            document.querySelectorAll('.stat-card, .dashboard-card').forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('animate-fadeInUp');
            });
        }, 100);
        
        // Quick access item click handlers
        document.querySelectorAll('.quick-access-item').forEach(item => {
            item.addEventListener('click', function(e) {
                // Add click animation
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
                
                // In real application, this would navigate to the page
                const pageName = this.querySelector('.text-sm').textContent;
                console.log(`Navigating to: ${pageName}`);
            });
        });
        
        // Project row click handlers
        document.querySelectorAll('.project-row').forEach(row => {
            row.addEventListener('click', function() {
                const projectName = this.querySelector('.text-sm').textContent;
                showProjectDetails(projectName);
            });
        });
        
        // Notification click handlers
        document.querySelectorAll('.notification-item').forEach(card => {
            card.addEventListener('click', function() {
                const time = this.querySelector('.text-gray-500').textContent;
                showNotificationDetails(this);
            });
        });
        
        // View all buttons
        document.querySelectorAll('.view-all-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const section = this.closest('.dashboard-card').querySelector('.text-base').textContent;
                showViewAll(section);
            });
        });
        
        // Stat cards click handlers
        document.querySelectorAll('.stat-card').forEach(card => {
            card.addEventListener('click', function() {
                const statType = this.querySelector('.text-xs').textContent;
                showStatDetails(statType);
            });
        });
        
        // Functions
        function showProjectDetails(projectName) {
            // In real app, this would navigate to project details
            // For demo, show a toast
            showToast(`Viewing details for: ${projectName}`, 'info');
            
            // Add visual feedback
            const projectRow = document.querySelector(`.project-row:has(.text-sm:contains("${projectName}"))`);
            if (projectRow) {
                projectRow.style.backgroundColor = 'rgba(117, 110, 164, 0.12)';
                setTimeout(() => {
                    projectRow.style.backgroundColor = '';
                }, 1000);
            }
        }
        
        function showNotificationDetails(notification) {
            const time = notification.querySelector('.text-gray-500').textContent;
            const content = notification.querySelector('.text-xs:last-child').textContent;
            
            // In real app, this would open notification details
            // For demo, show a modal
            showNotificationModal(time, content);
        }
        
        function showViewAll(section) {
            // In real app, this would navigate to the full page
            // For demo, show a toast
            showToast(`Loading all ${section.toLowerCase()}...`, 'info');
            
            // Simulate navigation delay
            setTimeout(() => {
                // Determine URL based on section
                let url = '#';
                switch(section) {
                    case 'Ticket Status Overview':
                        url = '<?= base_url('admin/tickets') ?>';
                        break;
                    case 'Project Overview':
                        url = '<?= base_url('admin/projects') ?>';
                        break;
                    case 'System Notifications':
                        url = '<?= base_url('admin/notifications') ?>';
                        break;
                }
                
                // In real app: window.location.href = url;
                console.log(`Navigating to: ${url}`);
            }, 500);
        }
        
        function showStatDetails(statType) {
            // In real app, this would show detailed statistics
            // For demo, show a modal
            const statCards = {
                'Total Users': { count: '1,203', trend: '+12%', description: 'Active users in the system' },
                'Total Tickets': { count: '376', trend: '-5%', description: 'All tickets created' },
                'Total Projects': { count: '28', trend: '+3', description: 'Active projects' },
                'Open Tickets': { count: '112', trend: '+8%', description: 'Tickets requiring attention' }
            };
            
            const stat = statCards[statType];
            if (stat) {
                showStatModal(statType, stat);
            }
        }
        
        function showNotificationModal(time, content) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4';
            modal.innerHTML = `
                <div class="bg-white rounded-2xl w-full max-w-md animate-fadeInUp">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-gray-800">Notification Details</h3>
                            <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-500 text-sm mb-1">Time</label>
                                <p class="text-gray-700">${time}</p>
                            </div>
                            
                            <div>
                                <label class="block text-gray-500 text-sm mb-1">Message</label>
                                <p class="text-gray-700">${content}</p>
                            </div>
                            
                            <div>
                                <label class="block text-gray-500 text-sm mb-1">Priority</label>
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded">
                                    Important
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6 border-t border-gray-200">
                        <button onclick="this.closest('.fixed').remove()" class="w-full py-3 bg-secondary text-white rounded-lg hover:bg-[#665C9E] transition-colors">
                            Mark as Read
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            document.body.style.overflow = 'hidden';
        }
        
        function showStatModal(statType, statData) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4';
            modal.innerHTML = `
                <div class="bg-white rounded-2xl w-full max-w-md animate-fadeInUp">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-gray-800">${statType} Details</h3>
                            <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="text-center mb-6">
                            <div class="text-4xl font-bold text-secondary mb-2">${statData.count}</div>
                            <div class="text-gray-500">${statData.description}</div>
                            <div class="mt-2 text-sm ${statData.trend.includes('+') ? 'text-green-600' : 'text-red-600'}">
                                <i class="fas ${statData.trend.includes('+') ? 'fa-arrow-up' : 'fa-arrow-down'} mr-1"></i>
                                ${statData.trend} from last week
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <h4 class="font-medium text-gray-700">Breakdown</h4>
                            ${getStatBreakdown(statType)}
                        </div>
                    </div>
                    
                    <div class="p-6 border-t border-gray-200">
                        <button onclick="this.closest('.fixed').remove()" class="w-full py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Close
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            document.body.style.overflow = 'hidden';
        }
        
        function getStatBreakdown(statType) {
            const breakdowns = {
                'Total Users': `
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Customers</span>
                            <span class="font-medium">845</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Support Staff</span>
                            <span class="font-medium">278</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Admins</span>
                            <span class="font-medium">15</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Department Staff</span>
                            <span class="font-medium">65</span>
                        </div>
                    </div>
                `,
                'Total Tickets': `
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Open</span>
                            <span class="font-medium">112</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">In Progress</span>
                            <span class="font-medium">89</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Resolved</span>
                            <span class="font-medium">123</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Closed</span>
                            <span class="font-medium">52</span>
                        </div>
                    </div>
                `,
                'Total Projects': `
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Active</span>
                            <span class="font-medium">18</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Completed</span>
                            <span class="font-medium">7</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">On Hold</span>
                            <span class="font-medium">3</span>
                        </div>
                    </div>
                `,
                'Open Tickets': `
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">High Priority</span>
                            <span class="font-medium">28</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Medium Priority</span>
                            <span class="font-medium">64</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Low Priority</span>
                            <span class="font-medium">20</span>
                        </div>
                    </div>
                `
            };
            
            return breakdowns[statType] || '<p class="text-gray-500 text-sm">No breakdown available.</p>';
        }
        
        function showToast(message, type = 'info') {
            // Remove existing toasts
            document.querySelectorAll('.custom-toast').forEach(toast => toast.remove());
            
            const toast = document.createElement('div');
            toast.className = `custom-toast fixed top-24 right-6 p-4 rounded-lg shadow-lg z-[1000] max-w-sm animate-slide-in ${
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
        
        // Initialize tooltips
        initializeTooltips();
        
        function initializeTooltips() {
            // Add tooltips to stat cards
            document.querySelectorAll('.stat-card').forEach(card => {
                const statType = card.querySelector('.text-xs').textContent;
                card.title = `Click to view ${statType.toLowerCase()} details`;
            });
            
            // Add tooltips to quick access items
            document.querySelectorAll('.quick-access-item').forEach(item => {
                const pageName = item.querySelector('.text-sm').textContent;
                item.title = `Go to ${pageName}`;
            });
            
            // Add tooltips to view all buttons
            document.querySelectorAll('.view-all-btn').forEach(btn => {
                btn.title = 'View all items in this section';
            });
        }
    });
</script>
<?= $this->endSection() ?>