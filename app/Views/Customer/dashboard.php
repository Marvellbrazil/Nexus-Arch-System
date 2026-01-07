<?= $this->extend('layouts/customer_layout') ?>

<?= $this->section('title') ?>Customer Dashboard - NEXUS<?= $this->endSection() ?>

<?= $this->section('background_effects') ?>
<!-- Background Effects -->
<div class="fixed w-[40vw] h-[40vw] -right-[10%] -bottom-[10%] rotate-[149deg] bg-gradient-to-r from-[rgba(56.96,44.47,127.72,0.15)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-80 z-0"></div>
<div class="fixed w-[35vw] h-[25vw] -left-[5%] top-[10%] rotate-[8deg] bg-gradient-to-r from-[rgba(65.04,45.10,137.14,0.20)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-70 z-0"></div>
<div class="fixed w-[15vw] h-[20vw] right-[5%] -top-[5%] rotate-[8deg] bg-gradient-to-r from-[rgba(16.56,8.41,46.05,0.12)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-60 z-0"></div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mt-[77px] p-[30px] relative z-10">
    <!-- Page Header -->
    <div class="mb-[25px] relative">
        <h1 class="text-[35px] font-semibold mb-[5px] text-text-dark">Username's Dashboard</h1>
        <p class="text-[15px] font-light text-[#666]">Dashboard Area</p>
        
        <!-- Action Buttons -->
        <div class="absolute right-0 top-0 flex gap-[15px]">
            <a href="<?= base_url('dashboard/create_ticket') ?>" 
               class="px-[20px] py-[10px] rounded-lg bg-secondary text-white text-[14px] font-medium cursor-pointer flex items-center gap-[8px] transition-all duration-300 hover:bg-[#817CB2] hover:shadow-md no-underline">
                <i class="fas fa-plus"></i>
                Create New Ticket
            </a>
            <a href="<?= base_url('dashboard/my_tickets') ?>" 
               class="px-[20px] py-[10px] rounded-lg bg-secondary text-white text-[14px] font-medium cursor-pointer flex items-center gap-[8px] transition-all duration-300 hover:bg-[#817CB2] hover:shadow-md no-underline">
                <i class="fas fa-ticket-alt"></i>
                My Tickets
            </a>
        </div>
    </div>

    <!-- Dashboard Grid -->
    <div class="grid grid-cols-12 gap-[20px] mb-[30px]">
        <!-- User Profile Card -->
        <div class="col-span-3 bg-white rounded-xl p-[25px] shadow-sm border border-gray-200">
            <div class="flex items-center gap-[15px] mb-[20px]">
                <div class="w-[50px] h-[50px] bg-secondary rounded-full flex items-center justify-center text-white text-[20px] font-bold">
                    U
                </div>
                <div>
                    <h2 class="text-text-dark text-[18px] font-semibold">Username</h2>
                    <div class="text-text-muted text-[12px]">Customer</div>
                </div>
            </div>
            
            <div class="text-text-muted text-[14px] mb-[20px]">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-envelope text-gray-400"></i>
                    <span>username@example.com</span>
                </div>
            </div>
            
            <div class="mb-[25px]">
                <h3 class="text-text-dark text-[14px] font-semibold mb-2">Projects:</h3>
                <ul class="text-text-muted text-[12px] space-y-1">
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
            
            <div class="flex gap-[12px]">
                <a href="<?= base_url('dashboard/profile') ?>" 
                   class="flex-1 px-[16px] py-[8px] rounded-lg bg-gray-100 text-gray-700 text-[12px] font-medium cursor-pointer text-center transition-colors duration-300 hover:bg-gray-200 no-underline">
                    Update Profile
                </a>
                <a href="<?= base_url('dashboard/logout') ?>" 
                   class="flex-1 px-[16px] py-[8px] rounded-lg bg-secondary text-white text-[12px] font-medium cursor-pointer text-center transition-colors duration-300 hover:bg-[#656099] no-underline">
                    Logout
                </a>
            </div>
        </div>

        <!-- Stat Cards -->
        <div class="col-span-3 bg-gradient-to-br from-[#3D3C5E] to-[#4A4570] rounded-xl p-[25px] text-white shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div class="w-[50px] h-[44px] bg-white/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-ticket-alt text-xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-[32px] font-bold">12</div>
                    <div class="text-white/80 text-[14px]">Total Tickets</div>
                </div>
            </div>
            <div class="text-white/60 text-[12px]">
                <i class="fas fa-arrow-up text-green-400 mr-1"></i>
                2 new this week
            </div>
        </div>

        <div class="col-span-3 bg-gradient-to-br from-[#3D3C5E] to-[#4A4570] rounded-xl p-[25px] text-white shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div class="w-[50px] h-[44px] bg-white/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-[32px] font-bold">5</div>
                    <div class="text-white/80 text-[14px]">Active Tickets</div>
                </div>
            </div>
            <div class="text-white/60 text-[12px]">
                3 need attention
            </div>
        </div>

        <div class="col-span-3 bg-gradient-to-br from-[#3D3C5E] to-[#4A4570] rounded-xl p-[25px] text-white shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div class="w-[50px] h-[44px] bg-white/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-[32px] font-bold">7</div>
                    <div class="text-white/80 text-[14px]">Resolved Tickets</div>
                </div>
            </div>
            <div class="text-white/60 text-[12px]">
                94% satisfaction
            </div>
        </div>

        <!-- Support Messages Card -->
        <div class="col-span-3 bg-white rounded-xl p-[25px] shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-[15px]">
                <h3 class="text-text-dark text-[18px] font-semibold">Support Messages</h3>
                <div class="w-6 h-6 bg-secondary rounded-full flex items-center justify-center">
                    <i class="fas fa-comment text-white text-xs"></i>
                </div>
            </div>
            <div class="text-gray-500 text-[14px] mb-4">No new messages</div>
            <a href="<?= base_url('dashboard/view_messages') ?>" 
               class="w-full px-[16px] py-[10px] rounded-lg bg-secondary text-white text-[12px] font-medium cursor-pointer text-center transition-colors duration-300 hover:bg-[#656099] no-underline block">
                View Messages
            </a>
        </div>

        <!-- Active Tickets Section -->
        <div class="col-span-9 bg-gradient-to-r from-[#3C3B5D] to-[#48466B] rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-white text-[20px] font-semibold">Your Active Tickets</h3>
                <a href="<?= base_url('dashboard/my_tickets') ?>" class="text-white/80 text-[14px] hover:text-white transition-colors">
                    View All
                </a>
            </div>
            
            <?php if(false): // Simulate no active tickets ?>
            <div class="flex flex-col items-center justify-center py-8">
                <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-ticket-alt text-white/60 text-2xl"></i>
                </div>
                <div class="text-white/70 text-[16px]">No active tickets found</div>
            </div>
            <?php else: ?>
            <div class="space-y-3">
                <?php 
                $active_tickets = [
                    ['id' => '#10421', 'subject' => 'Login issue', 'priority' => 'High', 'time' => '2 hours ago'],
                    ['id' => '#10423', 'subject' => 'API error', 'priority' => 'Medium', 'time' => '1 day ago'],
                ];
                ?>
                
                <?php foreach($active_tickets as $ticket): ?>
                <div class="bg-white/10 rounded-lg p-4 flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="font-bold text-white"><?= $ticket['id'] ?></span>
                            <span class="px-2 py-1 bg-red-500/20 text-red-300 text-[10px] rounded">
                                <?= $ticket['priority'] ?>
                            </span>
                        </div>
                        <p class="text-white/80 text-[14px]"><?= $ticket['subject'] ?></p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-white/60 text-[12px]"><?= $ticket['time'] ?></span>
                        <a href="<?= base_url('dashboard/ticket_detail/' . substr($ticket['id'], 1)) ?>" 
                           class="px-3 py-1 bg-white/20 text-white text-[12px] rounded hover:bg-white/30 transition-colors">
                            View
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Search Tickets Card -->
        <div class="col-span-4 bg-gradient-to-r from-secondary to-[#8A84C6] rounded-xl p-[25px]">
            <h3 class="text-white text-[18px] font-semibold text-center mb-6">Search Your Tickets</h3>
            <form id="searchForm" action="<?= base_url('dashboard/search_tickets') ?>" method="POST" class="w-full">
                <?= csrf_field() ?>
                <div class="relative mb-6">
                    <input type="text" name="search_term" 
                           class="w-full h-[50px] bg-white/10 border border-white/20 rounded-lg px-4 pl-12 text-white placeholder-white/60 focus:outline-none focus:border-white/40"
                           placeholder="Find your tickets...">
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-white/60"></i>
                </div>
                <button type="submit" 
                        class="w-full px-[13px] py-[10px] rounded-lg bg-white text-secondary text-[14px] font-semibold transition-colors duration-300 hover:bg-gray-100">
                    Search Tickets
                </button>
            </form>
        </div>

        <!-- Notifications Card -->
        <div class="col-span-8 bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-text-dark text-[20px] font-semibold">Recent Notifications</h3>
                <a href="<?= base_url('dashboard/notifications') ?>" class="text-secondary text-[14px] font-medium hover:text-[#665C9E]">
                    View All
                </a>
            </div>
            
            <?php if(false): // Simulate no notifications ?>
            <div class="flex flex-col items-center justify-center py-8">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-bell text-gray-400 text-2xl"></i>
                </div>
                <div class="text-gray-500 text-[16px]">No recent notifications</div>
            </div>
            <?php else: ?>
            <div class="space-y-4">
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
                            <div class="w-2 h-2 bg-secondary rounded-full"></div>
                        <?php else: ?>
                            <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1">
                        <p class="text-text-dark text-[14px] font-medium <?= $notification['unread'] ? 'font-semibold' : '' ?>">
                            <?= $notification['title'] ?>
                        </p>
                        <p class="text-gray-500 text-[12px] mt-1"><?= $notification['time'] ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="mt-6 pt-6 border-t border-gray-200">
                <button class="w-full py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-[14px] font-medium">
                    Mark All as Read
                </button>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search form validation
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            const searchInput = this.querySelector('input[name="search_term"]');
            if (!searchInput.value.trim()) {
                e.preventDefault();
                alert('Please enter a search term.');
                searchInput.focus();
            }
        });
        
        // Mark all notifications as read
        const markAllReadBtn = document.querySelector('button:contains("Mark All as Read")');
        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', function() {
                // Update UI
                document.querySelectorAll('.w-2.h-2.bg-secondary').forEach(dot => {
                    dot.classList.remove('bg-secondary');
                    dot.classList.add('bg-gray-300');
                });
                
                document.querySelectorAll('.font-semibold').forEach(text => {
                    text.classList.remove('font-semibold');
                });
                
                // Show message
                showToast('All notifications marked as read');
            });
        }
        
        // Toast notification function
        function showToast(message) {
            const toast = document.createElement('div');
            toast.className = 'fixed top-24 right-4 p-4 rounded-lg shadow-lg z-50 bg-secondary text-white';
            toast.innerHTML = `
                <div class="flex items-center gap-2">
                    <i class="fas fa-check-circle"></i>
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
</script>
<?= $this->endSection() ?>