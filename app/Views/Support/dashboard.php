<?= $this->extend('layouts/support_layout') ?>

<?= $this->section('title') ?>Support Dashboard - NEXUS<?= $this->endSection() ?>

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
            <h1 class="text-2xl md:text-[35px] font-semibold mb-1 md:mb-[5px] text-text-dark">Support Agent's Dashboard</h1>
            <p class="text-sm md:text-[15px] font-light text-[#666]">Ticket overview & assignment monitoring</p>
        </div>
        
        <!-- Action Buttons -->
        <div class="mt-4 md:mt-0 md:absolute md:right-0 md:top-0 flex flex-col sm:flex-row gap-3 md:gap-[15px]">
            <a href="<?= base_url('support/incoming') ?>" 
               class="px-4 md:px-[20px] py-2 md:py-[10px] rounded-lg bg-secondary text-white text-sm md:text-[14px] font-medium cursor-pointer flex items-center justify-center gap-2 transition-all duration-300 hover:bg-[#817CB2] hover:shadow-md no-underline">
                <i class="fas fa-inbox"></i>
                <span>View Incoming</span>
            </a>
        </div>
    </div>

    <!-- Dashboard Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-4 md:gap-[20px] mb-6 md:mb-[30px]">
        <!-- User Profile Card -->
        <div class="lg:col-span-3 bg-white rounded-xl p-4 md:p-[25px] shadow-sm border border-gray-200">
            <div class="flex items-center gap-3 md:gap-[15px] mb-4 md:mb-[20px]">
                <div class="w-12 h-12 md:w-[50px] md:h-[50px] bg-secondary rounded-full flex items-center justify-center text-white text-lg md:text-[20px] font-bold">
                    <?= isset($user_details['full_name']) ? strtoupper(substr($user_details['full_name'], 0, 2)) : 'SA' ?>
                </div>
                <div>
                    <h2 class="text-text-dark text-base md:text-[18px] font-semibold">
                        <?= $user_details['full_name'] ?? 'Support Agent' ?>
                    </h2>
                    <div class="text-text-muted text-xs md:text-[12px]">
                        <?= $user_details['role_name'] ?? 'Technical Support' ?>
                    </div>
                </div>
            </div>
            
            <div class="text-text-muted text-sm md:text-[14px] mb-4 md:mb-[20px]">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-envelope text-gray-400"></i>
                    <span class="truncate"><?= $user_details['email'] ?? 'agent@nexus.com' ?></span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-clock text-gray-400"></i>
                    <span>Active: <?= $stats['active_duration'] ?? '8h 24m' ?></span>
                </div>
            </div>
            
            <div class="mb-4 md:mb-[25px]">
                <h3 class="text-text-dark text-sm md:text-[14px] font-semibold mb-2">Departments:</h3>
                <ul class="text-text-muted text-xs md:text-[12px] space-y-1">
                    <?php if(isset($user_details['department_name']) && !empty($user_details['department_name'])): ?>
                        <li class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                            <?= $user_details['department_name'] ?>
                        </li>
                    <?php else: ?>
                        <!-- Default departments jika tidak ada data -->
                        <li class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                            Frontend Support
                        </li>
                        <li class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            Backend Support
                        </li>
                        <li class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                            Technical Support
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-2 md:gap-[12px]">
                <a href="<?= base_url('support/profile') ?>" 
                   class="px-4 md:px-[16px] py-2 md:py-[8px] rounded-lg bg-gray-100 text-gray-700 text-xs md:text-[12px] font-medium cursor-pointer text-center transition-colors duration-300 hover:bg-gray-200 no-underline">
                    Update Profile
                </a>
                <a href="<?= base_url('auth/logout') ?>" 
                   class="px-4 md:px-[16px] py-2 md:py-[8px] rounded-lg bg-secondary text-white text-xs md:text-[12px] font-medium cursor-pointer text-center transition-colors duration-300 hover:bg-[#656099] no-underline">
                    Logout
                </a>
            </div>
        </div>

        <!-- Stat Cards - Menggunakan data dari $stats -->
        <!-- Tickets in Progress -->
        <div class="lg:col-span-3 bg-gradient-to-br from-[#3D3C5E] to-[#4A4570] rounded-xl p-5 md:p-6 text-white shadow-sm flex flex-col items-center justify-center text-center">
            <div class="text-4xl md:text-5xl font-bold mb-2"><?= $stats['tickets_in_progress'] ?? '7' ?></div>
            <div class="text-white/80 text-sm md:text-[15px] font-medium mb-3">Tickets in Progress</div>
            <?php if(isset($stats['needs_attention']) && $stats['needs_attention'] > 0): ?>
                <div class="text-white/60 text-xs md:text-[13px] flex items-center justify-center mb-4">
                    <i class="fas fa-arrow-up text-green-400 mr-1"></i>
                    <span><?= $stats['needs_attention'] ?> need attention</span>
                </div>
            <?php else: ?>
                <div class="mb-4"></div>
            <?php endif; ?>
            <div class="w-10 h-10 md:w-12 md:h-12 bg-white/10 rounded-full flex items-center justify-center mt-2">
                <i class="fas fa-tasks text-lg md:text-xl"></i>
            </div>
        </div>

        <!-- Waiting Customer Reply -->
        <div class="lg:col-span-3 bg-gradient-to-br from-[#3D3C5E] to-[#4A4570] rounded-xl p-5 md:p-6 text-white shadow-sm flex flex-col items-center justify-center text-center">
            <div class="text-4xl md:text-5xl font-bold mb-2"><?= $stats['waiting_customer_reply'] ?? '5' ?></div>
            <div class="text-white/80 text-sm md:text-[15px] font-medium mb-3">Waiting Customer Reply</div>
            <div class="text-white/60 text-xs md:text-[13px] mb-4">
                Avg. wait time: 2 days
            </div>
            <div class="w-10 h-10 md:w-12 md:h-12 bg-white/10 rounded-full flex items-center justify-center mt-2">
                <i class="fas fa-clock text-lg md:text-xl"></i>
            </div>
        </div>

        <!-- Incoming Tickets -->
        <div class="lg:col-span-3 bg-gradient-to-br from-[#3D3C5E] to-[#4A4570] rounded-xl p-5 md:p-6 text-white shadow-sm flex flex-col items-center justify-center text-center">
            <div class="text-4xl md:text-5xl font-bold mb-2"><?= $stats['incoming_tickets'] ?? '5' ?></div>
            <div class="text-white/80 text-sm md:text-[15px] font-medium mb-3">Incoming Tickets</div>
            <?php if(isset($stats['new_today']) && $stats['new_today'] > 0): ?>
                <div class="text-white/60 text-xs md:text-[13px] flex items-center justify-center mb-4">
                    <i class="fas fa-arrow-up text-green-400 mr-1"></i>
                    <span><?= $stats['new_today'] ?> new today</span>
                </div>
            <?php else: ?>
                <div class="mb-4"></div>
            <?php endif; ?>
            <div class="w-10 h-10 md:w-12 md:h-12 bg-white/10 rounded-full flex items-center justify-center mt-2">
                <i class="fas fa-inbox text-lg md:text-xl"></i>
            </div>
        </div>

        <!-- Team Updates Card -->
        <div class="md:col-span-2 lg:col-span-3 bg-white rounded-xl p-4 md:p-[25px] shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-3 md:mb-[15px]">
                <h3 class="text-text-dark text-base md:text-[18px] font-semibold">Team Updates</h3>
                <div class="w-6 h-6 bg-secondary rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-white text-xs"></i>
                </div>
            </div>
            <div class="text-gray-500 text-sm md:text-[14px] mb-4">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-circle text-green-500 text-xs"></i>
                    <span><?= $stats['agents_online'] ?? '8' ?> agents online</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-circle text-yellow-500 text-xs"></i>
                    <span><?= $stats['agents_in_meeting'] ?? '2' ?> in meeting</span>
                </div>
            </div>
            <a href="<?= base_url('support/reports') ?>" 
               class="w-full px-4 md:px-[16px] py-2 md:py-[10px] rounded-lg bg-secondary text-white text-xs md:text-[12px] font-medium cursor-pointer text-center transition-colors duration-300 hover:bg-[#656099] no-underline block">
                View Team Reports
            </a>
        </div>

        <!-- Recent Incoming Tickets Section -->
        <div class="md:col-span-2 lg:col-span-9 bg-gradient-to-r from-[#3C3B5D] to-[#48466B] rounded-xl p-4 md:p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4 md:mb-6">
                <h3 class="text-white text-lg md:text-[20px] font-semibold">Recent Incoming Tickets</h3>
                <a href="<?= base_url('support/incoming') ?>" class="text-white/80 text-sm md:text-[14px] hover:text-white transition-colors">
                    View All
                </a>
            </div>
            
            <div class="space-y-3">
                <?php if(!empty($recent_tickets)): ?>
                    <?php foreach($recent_tickets as $ticket): ?>
                        <div class="bg-white/10 rounded-lg p-3 md:p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <span class="font-bold text-white text-sm md:text-base">
                                        <?= isset($ticket['ticket_number']) ? '#' . $ticket['ticket_number'] : '#' . ($ticket['ticket_id'] ?? 'N/A') ?>
                                    </span>
                                    <?php if(isset($ticket['priority_name'])): ?>
                                        <span class="px-2 py-1 <?= 
                                            $ticket['priority_name'] == 'Urgent' ? 'bg-red-500/20 text-red-300' : 
                                            ($ticket['priority_name'] == 'High' ? 'bg-orange-500/20 text-orange-300' : 
                                            'bg-yellow-500/20 text-yellow-300') 
                                        ?> text-xs md:text-[10px] rounded">
                                            <?= $ticket['priority_name'] ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if(isset($ticket['project_name'])): ?>
                                        <span class="text-white/60 text-xs"><?= $ticket['project_name'] ?></span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-white/80 text-sm md:text-[14px] truncate">
                                    <?= $ticket['subject'] ?? 'No subject' ?>
                                </p>
                                <?php if(isset($ticket['customer_name'])): ?>
                                    <p class="text-white/60 text-xs mt-1">From: <?= $ticket['customer_name'] ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="flex items-center justify-between sm:justify-end gap-4">
                                <?php if(isset($ticket['created_at'])): ?>
                                    <?php
                                    // Format waktu relative
                                    $date = new DateTime($ticket['created_at']);
                                    $now = new DateTime();
                                    $diff = $now->getTimestamp() - $date->getTimestamp();
                                    
                                    $timeAgo = '';
                                    if ($diff < 3600) {
                                        $timeAgo = floor($diff / 60) . ' min ago';
                                    } elseif ($diff < 86400) {
                                        $timeAgo = floor($diff / 3600) . ' hours ago';
                                    } elseif ($diff < 172800) {
                                        $timeAgo = 'Yesterday';
                                    } else {
                                        $timeAgo = floor($diff / 86400) . ' days ago';
                                    }
                                    ?>
                                    <span class="text-white/60 text-xs md:text-[12px]"><?= $timeAgo ?></span>
                                <?php else: ?>
                                    <span class="text-white/60 text-xs md:text-[12px]">No time info</span>
                                <?php endif; ?>
                                <a href="<?= base_url('support/ticket_detail/' . ($ticket['ticket_id'] ?? '')) ?>" 
                                   class="px-3 py-1 bg-white/20 text-white text-xs md:text-[12px] rounded hover:bg-white/30 transition-colors whitespace-nowrap">
                                    Review
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Fallback jika tidak ada tickets -->
                    <div class="text-white/60 text-center py-6">
                        <i class="fas fa-inbox text-2xl mb-2"></i>
                        <p>No incoming tickets</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Ticket Search Card -->
        <div class="md:col-span-2 lg:col-span-4 bg-gradient-to-r from-secondary to-[#8A84C6] rounded-xl p-4 md:p-[25px]">
            <h3 class="text-white text-base md:text-[18px] font-semibold text-center mb-4 md:mb-6">Quick Ticket Search</h3>
            <form id="searchForm" action="<?= base_url('support/search') ?>" method="GET" class="w-full">
                <div class="relative mb-4 md:mb-6">
                    <input type="text" name="search_term" 
                           class="w-full h-12 md:h-[50px] bg-white/10 border border-white/20 rounded-lg px-4 pl-12 text-white placeholder-white/60 focus:outline-none focus:border-white/40 text-sm md:text-base"
                           placeholder="Search tickets...">
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-white/60"></i>
                </div>
                <button type="submit" 
                        class="w-full px-4 md:px-[13px] py-2 md:py-[10px] rounded-lg bg-white text-secondary text-sm md:text-[14px] font-semibold transition-colors duration-300 hover:bg-gray-100">
                    Search Tickets
                </button>
            </form>
        </div>

        <!-- Recent Notifications Card -->
        <div class="md:col-span-2 lg:col-span-8 bg-white rounded-xl p-4 md:p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-4 md:mb-6">
                <h3 class="text-text-dark text-lg md:text-[20px] font-semibold">Recent Notifications</h3>
                <a href="<?= base_url('support/notifications') ?>" class="text-secondary text-sm md:text-[14px] font-medium hover:text-[#665C9E]">
                    View All
                </a>
            </div>
            
            <div class="space-y-3 md:space-y-4">
                <?php if(!empty($recent_notifications)): ?>
                    <?php foreach($recent_notifications as $notification): ?>
                        <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex-shrink-0 mt-1">
                                <?php 
                                $isUnread = isset($notification['read_status']) ? $notification['read_status'] == 0 : true;
                                if(isset($notification['is_read'])) {
                                    $isUnread = !$notification['is_read'];
                                }
                                ?>
                                <?php if($isUnread): ?>
                                    <div class="w-2 h-2 bg-secondary rounded-full"></div>
                                <?php else: ?>
                                    <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <p class="text-text-dark text-sm md:text-[14px] font-medium <?= $isUnread ? 'font-semibold' : '' ?> truncate">
                                        <?= $notification['title'] ?? $notification['message'] ?? 'New Notification' ?>
                                    </p>
                                    <?php if(isset($notification['type'])): ?>
                                        <span class="px-1.5 py-0.5 <?= 
                                            $notification['type'] == 'assignment' ? 'bg-purple-100 text-purple-700' : 
                                            ($notification['type'] == 'warning' ? 'bg-yellow-100 text-yellow-700' : 
                                            'bg-blue-100 text-blue-700') 
                                        ?> text-xs rounded">
                                            <?= ucfirst($notification['type']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <?php if(isset($notification['created_at'])): ?>
                                    <?php
                                    $date = new DateTime($notification['created_at']);
                                    $now = new DateTime();
                                    $diff = $now->getTimestamp() - $date->getTimestamp();
                                    
                                    $timeAgo = '';
                                    if ($diff < 3600) {
                                        $timeAgo = floor($diff / 60) . ' min ago';
                                    } elseif ($diff < 86400) {
                                        $timeAgo = floor($diff / 3600) . ' hours ago';
                                    } else {
                                        $timeAgo = floor($diff / 86400) . ' days ago';
                                    }
                                    ?>
                                    <p class="text-gray-500 text-xs md:text-[12px] mt-1"><?= $timeAgo ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Fallback jika tidak ada notifications -->
                    <div class="text-gray-400 text-center py-6">
                        <i class="fas fa-bell-slash text-xl mb-2"></i>
                        <p>No new notifications</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="mt-4 md:mt-6 pt-4 md:pt-6 border-t border-gray-200">
                <button onclick="markAllNotificationsRead()" class="w-full py-2 md:py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm md:text-[14px] font-medium">
                    Mark All as Read
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search form validation
        const searchForm = document.getElementById('searchForm');
        if(searchForm) {
            searchForm.addEventListener('submit', function(e) {
                const searchInput = this.querySelector('input[name="search_term"]');
                if (!searchInput.value.trim()) {
                    e.preventDefault();
                    showToast('Please enter a search term.', 'warning');
                    searchInput.focus();
                }
            });
        }
        
        // Toast notification function
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            const bgColor = type === 'warning' ? 'bg-yellow-500' : 'bg-secondary';
            const icon = type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle';
            
            toast.className = `fixed top-24 right-4 p-4 rounded-lg shadow-lg z-50 ${bgColor} text-white`;
            toast.innerHTML = `
                <div class="flex items-center gap-2">
                    <i class="fas ${icon}"></i>
                    <span>${message}</span>
                </div>
            `;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    });

    function markAllNotificationsRead() {
        fetch('<?= base_url("support/mark_notifications_read") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update UI
                document.querySelectorAll('.w-2.h-2.bg-secondary').forEach(dot => {
                    dot.classList.remove('bg-secondary');
                    dot.classList.add('bg-gray-300');
                });
                
                document.querySelectorAll('.font-semibold').forEach(text => {
                    text.classList.remove('font-semibold');
                });
                
                // Show success message
                const toast = document.createElement('div');
                toast.className = 'fixed top-24 right-4 p-4 rounded-lg shadow-lg z-50 bg-green-500 text-white';
                toast.innerHTML = `
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle"></i>
                        <span>All notifications marked as read</span>
                    </div>
                `;
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(100%)';
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
<?= $this->endSection() ?>