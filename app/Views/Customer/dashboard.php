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
            <h1 class="text-2xl md:text-[35px] font-semibold mb-1 md:mb-[5px] text-text-dark">Good <?= $data['current_time'] ?>, <?= $data['user']['full_name'] ?>!</h1>
            <p class="text-sm md:text-[15px] font-light text-[#666]">Welcome Back to the Dashboard Area</p>
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
                    <?= strtoupper(substr($data['user']['full_name'], 0, 1)) ?>
                </div>
                <div>
                    <h2 class="text-text-dark text-base md:text-[18px] font-semibold"><?= $data['user']['full_name'] ?></h2>
                    <div class="text-text-muted text-xs md:text-[12px]"><?= $data['user']['role'] ?></div>
                </div>
            </div>
            
            <div class="text-text-muted text-sm md:text-[14px] mb-4 md:mb-[20px]">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-envelope text-gray-400"></i>
                    <span class="truncate"><?= $data['user']['email'] ?></span>
                </div>
            </div>
            
            <!-- <div class="mb-4 md:mb-[25px]">
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
            </div> -->
            
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
            <div class="text-4xl md:text-5xl font-bold mb-2"><?= $data['stats']['total_tickets'] ?></div>
            <div class="text-white/80 text-sm md:text-[15px] font-medium mb-3">Total Tickets</div>
            <div class="text-white/60 text-xs md:text-[13px] flex items-center justify-center mb-4">
                <i class="fas fa-arrow-up text-green-400 mr-1"></i>
                <span><?= $data['stats']['total_tickets_per_week'] ?> new this week</span>
            </div>
            <div class="w-10 h-10 md:w-12 md:h-12 bg-white/10 rounded-full flex items-center justify-center mt-2">
                <i class="fas fa-ticket-alt text-lg md:text-xl"></i>
            </div>
        </div>

        <!-- Active Tickets -->
        <div class="lg:col-span-3 bg-gradient-to-br from-[#3D3C5E] to-[#4A4570] rounded-xl p-5 md:p-6 text-white shadow-sm flex flex-col items-center justify-center text-center">
            <div class="text-4xl md:text-5xl font-bold mb-2"><?= $data['stats']['open_tickets'] ?></div>
            <div class="text-white/80 text-sm md:text-[15px] font-medium mb-3">Open Tickets</div>
            <div class="text-white/60 text-xs md:text-[13px] mb-4">
                <?= $data['stats']['in_progress_tickets'] ?> tickets In Progress
            </div>
            <div class="w-10 h-10 md:w-12 md:h-12 bg-white/10 rounded-full flex items-center justify-center mt-2">
                <i class="fas fa-clock text-lg md:text-xl"></i>
            </div>
        </div>

        <!-- Resolved Tickets -->
        <div class="lg:col-span-3 bg-gradient-to-br from-[#3D3C5E] to-[#4A4570] rounded-xl p-5 md:p-6 text-white shadow-sm flex flex-col items-center justify-center text-center">
            <div class="text-4xl md:text-5xl font-bold mb-2"><?= $data['stats']['resolved_tickets'] ?></div>
            <div class="text-white/80 text-sm md:text-[15px] font-medium mb-3">Resolved Tickets</div>
            <div class="text-white/60 text-xs md:text-[13px] mb-4">
                <?= $data['stats']['cancelled_tickets'] ?> tickets cancelled
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
                <?php if (!empty($data['projects'])): ?>
                    <?php foreach($data['projects'] as $project): ?>
                        <div class="relative group">
                            <!-- Project Card -->
                            <div class="bg-white border-2 border-gray-300 rounded-xl p-4 md:p-5 hover:border-secondary hover:shadow-lg transition-all duration-300 h-full flex flex-col">
                                <!-- Header -->
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center gap-2 md:gap-3">
                                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                            <i class="fas fa-sheet-plastic text-white text-sm md:text-base"></i>
                                        </div>
                                        <div>
                                            <div class="flex items-center mb-1">
                                                <span class="text-xs md:text-sm font-medium px-2 py-0.5">
                                                    <?= ucfirst($project['project_name']) ?>
                                                </span>
                                                <span class="text-xs md:text-sm font-medium px-2 py-0.5 rounded-full <?= ($project['is_active'] == 't') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                                    <?= ($project['is_active'] == 't') ? 'Active' : 'Inactive' ?>
                                                </span>
                                            </div>
                                            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full font-medium">
                                                <?= $project['ticket_count'] ?> tickets
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
                                        <?= $project['project_name'] ?>
                                    </h4>
                                    <p class="text-gray-600 text-xs md:text-sm mb-2 line-clamp-2">
                                        <?= $project['description'] ?>
                                    </p>
                                    <div class="flex items-center text-gray-500 text-xs mt-2">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        <span>Created: <?= date('F d, Y H:i', strtotime($project['created_at'])) ?></span>
                                    </div>
                                </div>

                                <!-- Stats -->
                                <div class="mb-4">
                                    <div class="flex items-center justify-between text-xs mb-2">
                                        <div class="flex items-center gap-1">
                                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                            <span class="text-gray-500">Open:</span>
                                            <span class="font-medium text-gray-700"><?= $project['open_tickets'] ?? 0 ?></span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                            <span class="text-gray-500">Resolved:</span>
                                            <span class="font-medium text-gray-700"><?= $project['resolved_tickets'] ?? 0 ?></span>
                                        </div>
                                    </div>
                                    <!-- Progress Bar -->
                                    <?php 
                                    $total = $project['ticket_count'] ?? 0;
                                    $resolved = $project['resolved_tickets'] ?? 0;
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
                                    <a href="<?= base_url('customer/project_detail/' . $project['project_id']) ?>" 
                                        class="flex-1 px-3 py-1.5 bg-gray-100 text-gray-700 text-xs md:text-sm rounded-lg hover:bg-gray-200 transition-colors font-medium text-center flex items-center justify-center gap-1 no-underline">
                                        <i class="fas fa-eye text-xs"></i>
                                        <span>View</span>
                                    </a>
                                    <a href="<?= base_url('customer/create_ticket?project=' . $project['project_id']) ?>" 
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
                                        <span class="text-gray-400">Created At:</span>
                                        <span class="font-medium"><?= date('d-m-Y H:i:s', strtotime($project['created_at'])) ?></span>
                                    </div>
                                </div>
                                <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2 rotate-45 w-2 h-2 bg-gray-900"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full py-20 flex flex-col items-center justify-center text-center">
                        <div class="bg-gray-100 p-4 rounded-full mb-4">
                            <i class="fas fa-list-check text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-500 text-lg">There is no project yet</p>
                    </div>
                <?php endif; ?>
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
            <div class="space-y-3 md:space-y-4">
                <?php if (!empty($data['notifications'])): ?>
                    <?php foreach($data['notifications'] as $notification): ?>
                    <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="flex-shrink-0 mt-1">
                            <?php if($notification['is_read']): ?>
                                <div class="w-2 h-2 bg-secondary rounded-full unread-indicator"></div>
                            <?php else: ?>
                                <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-text-dark text-sm md:text-[14px] font-medium <?= $notification['is_read'] ? 'font-semibold' : '' ?> truncate">
                                <?= $notification['title'] ?>
                            </p>
                            <p class="text-gray-500 text-xs md:text-[12px] mt-1"><?= date('F d, Y H:i', strtotime($notification['created_at'])) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="flex flex-col items-center justify-center py-6 md:py-8">
                        <div class="w-12 h-12 md:w-16 md:h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3 md:mb-4">
                            <i class="fas fa-bell text-gray-400 text-xl md:text-2xl"></i>
                        </div>
                        <div class="text-gray-500 text-sm md:text-[16px]">No recent notifications</div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="mt-4 md:mt-6 pt-4 md:pt-6 border-t border-gray-200">
                <form action="<?= base_url('customer/notifications/mark_read') ?>" method="POST" class="inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="w-full py-2 md:py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm md:text-[14px] font-medium">
                        Mark All as Read
                    </button>
                </form>
            </div>
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