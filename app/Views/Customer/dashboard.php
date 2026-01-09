<?= $this->extend('layouts/customer_layout') ?>

<?= $this->section('title') ?>Customer Dashboard - NEXUS<?= $this->endSection() ?>

<?= $this->section('background_effects') ?>
<!-- Background Effects -->
<div class="fixed w-[40vw] h-[40vw] -right-[10%] -bottom-[10%] rotate-[149deg] bg-gradient-to-r from-[rgba(56.96,44.47,127.72,0.15)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-80 z-0"></div>
<div class="fixed w-[35vw] h-[25vw] -left-[5%] top-[10%] rotate-[8deg] bg-gradient-to-r from-[rgba(65.04,45.10,137.14,0.20)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-70 z-0"></div>
<div class="fixed w-[15vw] h-[20vw] right-[5%] -top-[5%] rotate-[8deg] bg-gradient-to-r from-[rgba(16.56,8.41,46.05,0.12)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-60 z-0"></div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mt-4 md:mt-[77px] p-4 md:p-[30px] relative z-10">
    <!-- Page Header -->
    <div class="mb-6 md:mb-[25px] relative">
        <div class="flex flex-col">
            <h1 class="text-2xl md:text-[35px] font-semibold mb-1 md:mb-[5px] text-text-dark">Username's Dashboard</h1>
            <p class="text-sm md:text-[15px] font-light text-[#666]">Dashboard Area</p>
        </div>
        
        <!-- Action Buttons -->
        <div class="mt-4 md:mt-0 md:absolute md:right-0 md:top-0 flex flex-col sm:flex-row gap-3 md:gap-[15px]">
            <a href="<?= base_url('customer/create_ticket') ?>" 
               class="px-4 md:px-[20px] py-2 md:py-[10px] rounded-lg bg-secondary text-white text-sm md:text-[14px] font-medium cursor-pointer flex items-center justify-center gap-2 transition-all duration-300 hover:bg-[#817CB2] hover:shadow-md no-underline">
                <i class="fas fa-plus"></i>
                <span>Create New Ticket</span>
            </a>
            <a href="<?= base_url('customer/my_tickets') ?>" 
               class="px-4 md:px-[20px] py-2 md:py-[10px] rounded-lg bg-secondary text-white text-sm md:text-[14px] font-medium cursor-pointer flex items-center justify-center gap-2 transition-all duration-300 hover:bg-[#817CB2] hover:shadow-md no-underline">
                <i class="fas fa-ticket-alt"></i>
                <span>My Tickets</span>
            </a>
        </div>
    </div>

    <!-- Dashboard Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-4 md:gap-[20px] mb-6 md:mb-[30px]">
        <!-- User Profile Card -->
        <div class="lg:col-span-3 bg-white rounded-xl p-4 md:p-[25px] shadow-sm border border-gray-200">
            <div class="flex items-center gap-3 md:gap-[15px] mb-4 md:mb-[20px]">
                <div class="w-12 h-12 md:w-[50px] md:h-[50px] bg-secondary rounded-full flex items-center justify-center text-white text-lg md:text-[20px] font-bold">
                    U
                </div>
                <div>
                    <h2 class="text-text-dark text-base md:text-[18px] font-semibold">Username</h2>
                    <div class="text-text-muted text-xs md:text-[12px]">Customer</div>
                </div>
            </div>
            
            <div class="text-text-muted text-sm md:text-[14px] mb-4 md:mb-[20px]">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-envelope text-gray-400"></i>
                    <span class="truncate">username@example.com</span>
                </div>
            </div>
            
            <div class="mb-4 md:mb-[25px]">
                <h3 class="text-text-dark text-sm md:text-[14px] font-semibold mb-2">Projects:</h3>
                <ul class="text-text-muted text-xs md:text-[12px] space-y-1">
                    <li class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        Project 1
                    </li>
                    <li class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        Project 2
                    </li>
                    <li class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                        Project 3
                    </li>
                </ul>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-2 md:gap-[12px]">
                <a href="<?= base_url('customer/profile') ?>" 
                   class="px-4 md:px-[16px] py-2 md:py-[8px] rounded-lg bg-gray-100 text-gray-700 text-xs md:text-[12px] font-medium cursor-pointer text-center transition-colors duration-300 hover:bg-gray-200 no-underline">
                    Update Profile
                </a>
                <a href="<?= base_url('customer/logout') ?>" 
                   class="px-4 md:px-[16px] py-2 md:py-[8px] rounded-lg bg-secondary text-white text-xs md:text-[12px] font-medium cursor-pointer text-center transition-colors duration-300 hover:bg-[#656099] no-underline">
                    Logout
                </a>
            </div>
        </div>

        <!-- Stat Cards -->
        <!-- Total Tickets -->
        <div class="lg:col-span-3 bg-gradient-to-br from-[#3D3C5E] to-[#4A4570] rounded-xl p-5 md:p-6 text-white shadow-sm flex flex-col items-center justify-center text-center">
            <div class="text-4xl md:text-5xl font-bold mb-2">12</div>
            <div class="text-white/80 text-sm md:text-[15px] font-medium mb-3">Total Tickets</div>
            <div class="text-white/60 text-xs md:text-[13px] flex items-center justify-center mb-4">
                <i class="fas fa-arrow-up text-green-400 mr-1"></i>
                <span>2 new this week</span>
            </div>
            <div class="w-10 h-10 md:w-12 md:h-12 bg-white/10 rounded-full flex items-center justify-center mt-2">
                <i class="fas fa-ticket-alt text-lg md:text-xl"></i>
            </div>
        </div>

        <!-- Active Tickets -->
        <div class="lg:col-span-3 bg-gradient-to-br from-[#3D3C5E] to-[#4A4570] rounded-xl p-5 md:p-6 text-white shadow-sm flex flex-col items-center justify-center text-center">
            <div class="text-4xl md:text-5xl font-bold mb-2">5</div>
            <div class="text-white/80 text-sm md:text-[15px] font-medium mb-3">Active Tickets</div>
            <div class="text-white/60 text-xs md:text-[13px] mb-4">
                3 need attention
            </div>
            <div class="w-10 h-10 md:w-12 md:h-12 bg-white/10 rounded-full flex items-center justify-center mt-2">
                <i class="fas fa-clock text-lg md:text-xl"></i>
            </div>
        </div>

        <!-- Resolved Tickets -->
        <div class="lg:col-span-3 bg-gradient-to-br from-[#3D3C5E] to-[#4A4570] rounded-xl p-5 md:p-6 text-white shadow-sm flex flex-col items-center justify-center text-center">
            <div class="text-4xl md:text-5xl font-bold mb-2">7</div>
            <div class="text-white/80 text-sm md:text-[15px] font-medium mb-3">Resolved Tickets</div>
            <div class="text-white/60 text-xs md:text-[13px] mb-4">
                94% satisfaction
            </div>
            <div class="w-10 h-10 md:w-12 md:h-12 bg-white/10 rounded-full flex items-center justify-center mt-2">
                <i class="fas fa-check-circle text-lg md:text-xl"></i>
            </div>
        </div>

        <!-- Support Messages Card -->
        <div class="md:col-span-2 lg:col-span-3 bg-white rounded-xl p-4 md:p-[25px] shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-3 md:mb-[15px]">
                <h3 class="text-text-dark text-base md:text-[18px] font-semibold">Support Messages</h3>
                <div class="w-6 h-6 bg-secondary rounded-full flex items-center justify-center">
                    <i class="fas fa-comment text-white text-xs"></i>
                </div>
            </div>
            <div class="text-gray-500 text-sm md:text-[14px] mb-4">No new messages</div>
            <a href="<?= base_url('customer/view_messages') ?>" 
               class="w-full px-4 md:px-[16px] py-2 md:py-[10px] rounded-lg bg-secondary text-white text-xs md:text-[12px] font-medium cursor-pointer text-center transition-colors duration-300 hover:bg-[#656099] no-underline block">
                View Messages
            </a>
        </div>

        <!-- My Projects Section -->
        <div class="md:col-span-2 lg:col-span-9 bg-white rounded-xl p-4 md:p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-4 md:mb-6">
                <h3 class="text-text-dark text-lg md:text-[20px] font-semibold">My Projects</h3>
                <span class="text-gray-500 text-sm md:text-[14px]">Click a project to view details or create ticket</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
                <?php 
                $user_projects = [
                    [
                        'id' => 1,
                        'name' => 'Project Alpha',
                        'description' => 'Main enterprise project with multiple modules',
                        'tickets_count' => 12,
                        'active_tickets' => 3,
                        'resolved_tickets' => 9,
                        'color' => 'from-blue-500 to-blue-600',
                        'icon' => 'fa-project-diagram',
                        'project_code' => 'PRJ-ALPHA-2024',
                        'created_date' => 'Jan 15, 2024',
                        'status' => 'active',
                        'owner' => 'Admin Team',
                        'last_activity' => '2 hours ago'
                    ],
                    [
                        'id' => 2,
                        'name' => 'Project Beta',
                        'description' => 'E-commerce platform development',
                        'tickets_count' => 8,
                        'active_tickets' => 2,
                        'resolved_tickets' => 6,
                        'color' => 'from-green-500 to-green-600',
                        'icon' => 'fa-shopping-cart',
                        'project_code' => 'PRJ-BETA-2024',
                        'created_date' => 'Feb 10, 2024',
                        'status' => 'active',
                        'owner' => 'Product Team',
                        'last_activity' => '1 day ago'
                    ],
                    [
                        'id' => 3,
                        'name' => 'Project Gamma',
                        'description' => 'Mobile application for iOS & Android',
                        'tickets_count' => 5,
                        'active_tickets' => 1,
                        'resolved_tickets' => 4,
                        'color' => 'from-purple-500 to-purple-600',
                        'icon' => 'fa-mobile-alt',
                        'project_code' => 'PRJ-GAMMA-2024',
                        'created_date' => 'Mar 5, 2024',
                        'status' => 'active',
                        'owner' => 'Mobile Team',
                        'last_activity' => '3 hours ago'
                    ],
                    [
                        'id' => 4,
                        'name' => 'Project Delta',
                        'description' => 'Database migration and optimization',
                        'tickets_count' => 3,
                        'active_tickets' => 0,
                        'resolved_tickets' => 3,
                        'color' => 'from-orange-500 to-orange-600',
                        'icon' => 'fa-database',
                        'project_code' => 'PRJ-DELTA-2024',
                        'created_date' => 'Dec 20, 2023',
                        'status' => 'completed',
                        'owner' => 'DevOps Team',
                        'last_activity' => '1 week ago'
                    ],
                    [
                        'id' => 5,
                        'name' => 'Project Epsilon',
                        'description' => 'API development and integration',
                        'tickets_count' => 7,
                        'active_tickets' => 2,
                        'resolved_tickets' => 5,
                        'color' => 'from-pink-500 to-pink-600',
                        'icon' => 'fa-code',
                        'project_code' => 'PRJ-EPSILON-2024',
                        'created_date' => 'Jan 30, 2024',
                        'status' => 'active',
                        'owner' => 'Backend Team',
                        'last_activity' => '5 hours ago'
                    ],
                    [
                        'id' => 6,
                        'name' => 'Project Zeta',
                        'description' => 'Security audit and compliance',
                        'tickets_count' => 4,
                        'active_tickets' => 1,
                        'resolved_tickets' => 3,
                        'color' => 'from-red-500 to-red-600',
                        'icon' => 'fa-shield-alt',
                        'project_code' => 'PRJ-ZETA-2024',
                        'created_date' => 'Feb 25, 2024',
                        'status' => 'in-review',
                        'owner' => 'Security Team',
                        'last_activity' => 'Yesterday'
                    ],
                ];
                ?>
                
                <?php foreach($user_projects as $project): ?>
                <div class="relative group">
                    <!-- Project Card -->
                    <div class="bg-white border-2 border-gray-300 rounded-xl p-4 md:p-5 hover:border-secondary hover:shadow-lg transition-all duration-300 h-full flex flex-col">
                        <!-- Header -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-2 md:gap-3">
                                <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg bg-gradient-to-br <?= $project['color'] ?> flex items-center justify-center">
                                    <i class="fas <?= $project['icon'] ?> text-white text-sm md:text-base"></i>
                                </div>
                                <div>
                                    <div class="flex items-center gap-1 mb-1">
                                        <span class="text-xs md:text-sm font-medium px-2 py-0.5 rounded-full
                                            <?= $project['status'] == 'active' ? 'bg-green-100 text-green-800' : '' ?>
                                            <?= $project['status'] == 'completed' ? 'bg-blue-100 text-blue-800' : '' ?>
                                            <?= $project['status'] == 'in-review' ? 'bg-yellow-100 text-yellow-800' : '' ?>">
                                            <?= ucfirst($project['status']) ?>
                                        </span>
                                    </div>
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full font-medium">
                                        <?= $project['tickets_count'] ?> tickets
                                    </span>
                                </div>
                            </div>
                            <button class="text-gray-400 hover:text-gray-600 p-1">
                                <i class="fas fa-ellipsis-v text-xs"></i>
                            </button>
                        </div>
                        
                        <!-- Project Name & Description -->
                        <div class="mb-3 flex-1">
                            <h4 class="text-text-dark text-base md:text-lg font-semibold mb-1 line-clamp-1">
                                <?= $project['name'] ?>
                            </h4>
                            <p class="text-gray-600 text-xs md:text-sm mb-2 line-clamp-2">
                                <?= $project['description'] ?>
                            </p>
                            <div class="flex items-center text-gray-500 text-xs mt-2">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                <span>Created: <?= $project['created_date'] ?></span>
                            </div>
                        </div>
                        
                        <!-- Stats -->
                        <div class="mb-4">
                            <div class="flex items-center justify-between text-xs mb-2">
                                <div class="flex items-center gap-1">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                    <span class="text-gray-500">Active:</span>
                                    <span class="font-medium text-gray-700"><?= $project['active_tickets'] ?></span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    <span class="text-gray-500">Resolved:</span>
                                    <span class="font-medium text-gray-700"><?= $project['resolved_tickets'] ?></span>
                                </div>
                            </div>
                            <!-- Progress Bar -->
                            <?php 
                            $total = $project['tickets_count'];
                            $resolved = $project['resolved_tickets'];
                            $progress = $total > 0 ? round(($resolved / $total) * 100) : 0;
                            ?>
                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                <div class="bg-secondary h-1.5 rounded-full" style="width: <?= $progress ?>%"></div>
                            </div>
                            <div class="flex justify-between text-gray-500 text-xs mt-1">
                                <span>Progress</span>
                                <span><?= $progress ?>%</span>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex gap-2 pt-3 border-t border-gray-200">
                            <a href="<?= base_url('customer/project_detail/' . $project['id']) ?>" 
                               class="flex-1 px-3 py-1.5 bg-gray-100 text-gray-700 text-xs md:text-sm rounded-lg hover:bg-gray-200 transition-colors font-medium text-center flex items-center justify-center gap-1 no-underline">
                                <i class="fas fa-eye text-xs"></i>
                                <span>View</span>
                            </a>
                            <a href="<?= base_url('customer/create_ticket?project=' . $project['id']) ?>" 
                               class="flex-1 px-3 py-1.5 bg-secondary text-white text-xs md:text-sm rounded-lg hover:bg-[#817CB2] transition-colors font-medium text-center flex items-center justify-center gap-1 no-underline">
                                <i class="fas fa-plus text-xs"></i>
                                <span>Ticket</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Quick Info Hover Card -->
                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 bg-gray-900 text-white rounded-lg p-3 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 pointer-events-none">
                        <div class="text-xs mb-2 font-medium text-gray-300">Quick Info</div>
                        <div class="space-y-1.5 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Code:</span>
                                <span class="font-medium"><?= $project['project_code'] ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Owner:</span>
                                <span class="font-medium"><?= $project['owner'] ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Last Activity:</span>
                                <span class="font-medium"><?= $project['last_activity'] ?></span>
                            </div>
                        </div>
                        <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2 rotate-45 w-2 h-2 bg-gray-900"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            

        </div>

        <!-- Search Tickets Card -->
        <div class="md:col-span-2 lg:col-span-3 bg-gradient-to-r from-secondary to-[#8A84C6] rounded-xl p-4 md:p-[25px]">
            <h3 class="text-white text-base md:text-[18px] font-semibold text-center mb-4 md:mb-6">Search Your Tickets</h3>
            <form id="searchForm" action="<?= base_url('customer/search_tickets') ?>" method="POST" class="w-full">
                <?= csrf_field() ?>
                <div class="relative mb-4 md:mb-6">
                    <input type="text" name="search_term" 
                           class="w-full h-12 md:h-[50px] bg-white/10 border border-white/20 rounded-lg px-4 pl-12 text-white placeholder-white/60 focus:outline-none focus:border-white/40 text-sm md:text-base"
                           placeholder="Find your tickets...">
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-white/60"></i>
                </div>
                <button type="submit" 
                        class="w-full px-4 md:px-[13px] py-2 md:py-[10px] rounded-lg bg-white text-secondary text-sm md:text-[14px] font-semibold transition-colors duration-300 hover:bg-gray-100">
                    Search Tickets
                </button>
            </form>
        </div>

        <!-- Notifications Card -->
        <div class="md:col-span-2 lg:col-span-9 bg-white rounded-xl p-4 md:p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-4 md:mb-6">
                <h3 class="text-text-dark text-lg md:text-[20px] font-semibold">Recent Notifications</h3>
                <a href="<?= base_url('customer/notifications') ?>" class="text-secondary text-sm md:text-[14px] font-medium hover:text-[#665C9E]">
                    View All
                </a>
            </div>
            
            <?php if(false): // Simulate no notifications ?>
            <div class="flex flex-col items-center justify-center py-6 md:py-8">
                <div class="w-12 h-12 md:w-16 md:h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3 md:mb-4">
                    <i class="fas fa-bell text-gray-400 text-xl md:text-2xl"></i>
                </div>
                <div class="text-gray-500 text-sm md:text-[16px]">No recent notifications</div>
            </div>
            <?php else: ?>
            <div class="space-y-3 md:space-y-4">
                <?php 
                $notifications = [
                    ['title' => 'New reply on ticket #10421', 'time' => '10 min ago', 'unread' => true],
                    ['title' => 'Ticket #10422 resolved', 'time' => '2 hours ago', 'unread' => false],
                    ['title' => 'System update completed', 'time' => '1 day ago', 'unread' => false],
                ];
                ?>
                
                <?php foreach($notifications as $notification): ?>
                <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex-shrink-0 mt-1">
                        <?php if($notification['unread']): ?>
                            <div class="w-2 h-2 bg-secondary rounded-full unread-indicator"></div>
                        <?php else: ?>
                            <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-text-dark text-sm md:text-[14px] font-medium <?= $notification['unread'] ? 'font-semibold' : '' ?> truncate">
                            <?= $notification['title'] ?>
                        </p>
                        <p class="text-gray-500 text-xs md:text-[12px] mt-1"><?= $notification['time'] ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="mt-4 md:mt-6 pt-4 md:pt-6 border-t border-gray-200">
                <form action="<?= base_url('customer/notifications/mark_read') ?>" method="POST" class="inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="w-full py-2 md:py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm md:text-[14px] font-medium">
                        Mark All as Read
                    </button>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    /* Unread indicator animation */
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
    }
    
    .unread-indicator {
        animation: pulse 2s ease-in-out infinite;
    }
    
    /* Line clamp for multi-line text */
    .line-clamp-1 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 1;
    }
    
    .line-clamp-2 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }
    
    /* Hover card arrow */
    .hover-card-arrow::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translate(-50%, 50%) rotate(45deg);
        width: 8px;
        height: 8px;
        background-color: #1f2937;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search form validation
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            const searchInput = this.querySelector('input[name="search_term"]');
            if (!searchInput.value.trim()) {
                e.preventDefault();
                showToast('Please enter a search term.', 'warning');
                searchInput.focus();
            }
        });
        
        // Mark all notifications as read
        const markAllReadBtn = document.querySelector('button[type="submit"]');
        if (markAllReadBtn && markAllReadBtn.textContent.includes('Mark All as Read')) {
            markAllReadBtn.addEventListener('click', function(e) {
                // Update UI immediately for better UX
                document.querySelectorAll('.unread-indicator').forEach(indicator => {
                    indicator.style.animation = 'none';
                    indicator.style.opacity = '0.5';
                });
                
                document.querySelectorAll('.font-semibold').forEach(text => {
                    text.classList.remove('font-semibold');
                });
                
                // Show success message
                showToast('All notifications marked as read', 'success');
            });
        }
        
        // Notification click to mark as read
        document.querySelectorAll('.flex.items-start.gap-3.p-3').forEach(item => {
            item.addEventListener('click', function(e) {
                const indicator = this.querySelector('.unread-indicator');
                if (indicator) {
                    indicator.style.animation = 'none';
                    indicator.style.opacity = '0.5';
                    
                    const title = this.querySelector('.font-semibold');
                    if (title) {
                        title.classList.remove('font-semibold');
                        title.classList.add('font-medium');
                    }
                    
                    showToast('Notification marked as read', 'info');
                }
            });
        });
        
        // Project card interaction
        document.querySelectorAll('.group').forEach(projectCard => {
            projectCard.addEventListener('mouseenter', function() {
                this.querySelector('.hover-card')?.classList.add('opacity-100', 'visible');
            });
            
            projectCard.addEventListener('mouseleave', function() {
                this.querySelector('.hover-card')?.classList.remove('opacity-100', 'visible');
            });
        });
        
        // Toast notification function
        function showToast(message, type = 'info') {
            // Remove existing toasts
            document.querySelectorAll('.custom-toast').forEach(toast => toast.remove());
            
            const toast = document.createElement('div');
            toast.className = `custom-toast fixed top-24 right-4 md:right-6 p-4 rounded-lg shadow-lg z-[1000] max-w-sm animate-slide-in ${
                type === 'warning' ? 'bg-yellow-500 text-white' : 
                type === 'error' ? 'bg-red-500 text-white' : 
                type === 'success' ? 'bg-green-500 text-white' : 
                'bg-blue-500 text-white'
            }`;
            toast.innerHTML = `
                <div class="flex items-center gap-2">
                    <i class="fas ${
                        type === 'warning' ? 'fa-exclamation-triangle' : 
                        type === 'error' ? 'fa-exclamation-circle' : 
                        type === 'success' ? 'fa-check-circle' : 
                        'fa-info-circle'
                    }"></i>
                    <span class="text-sm">${message}</span>
                </div>
            `;
            document.body.appendChild(toast);
            
            // Add animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                .animate-slide-in {
                    animation: slideIn 0.3s ease-out;
                }
            `;
            document.head.appendChild(style);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    toast.remove();
                    style.remove();
                }, 300);
            }, 3000);
        }
    });
</script>
<?= $this->endSection() ?>