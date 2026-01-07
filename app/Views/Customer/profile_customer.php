<?= $this->extend('layouts/customer_layout') ?>

<?= $this->section('title') ?>Profile - NEXUS<?= $this->endSection() ?>

<?= $this->section('background_effects') ?>
<!-- Background Effects -->
<div class="fixed w-[40vw] h-[40vw] -right-[10%] -bottom-[10%] rotate-[149deg] bg-gradient-to-r from-[rgba(56.96,44.47,127.72,0.26)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-80 z-0"></div>
<div class="fixed w-[35vw] h-[25vw] -left-[5%] top-[10%] rotate-[8deg] bg-gradient-to-r from-[rgba(65.04,45.10,137.14,0.31)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-70 z-0"></div>
<div class="fixed w-[15vw] h-[20vw] right-[5%] -top-[5%] rotate-[8deg] bg-gradient-to-r from-[rgba(16.56,8.41,46.05,0.97)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-60 z-0"></div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mt-[77px] p-[30px] relative z-10">
    <!-- Page Header -->
    <div class="mb-[25px]">
        <h1 class="text-[36px] font-semibold mb-[15px] text-text-dark">Hello, username</h1>
        <p class="text-[15px] font-light text-text-dark">Here is your profile</p>
        
        <!-- Logout Button -->
        <div class="absolute right-[30px] top-0 flex items-center gap-2 cursor-pointer hover:opacity-80 transition-opacity" onclick="confirmLogout()">
            <div class="w-[18px] h-[18px] bg-text-dark"></div>
            <span class="text-text-dark text-[18px] font-semibold font-mulish">Logout</span>
        </div>
    </div>

    <!-- Profile Card -->
    <div class="relative mb-8">
        <!-- Profile Image and Info -->
        <div class="bg-gradient-to-r from-[#302B48] to-[#948BC0] rounded-[9px] border-[3px] border-white backdrop-blur-[20px] p-8 flex flex-col items-center">
            <!-- Avatar -->
            <div class="relative mb-6">
                <div class="w-[118px] h-[118px] bg-white rounded-full absolute inset-0"></div>
                <div class="w-[118px] h-[118px] bg-[#948BC0] rounded-full relative"></div>
            </div>
            
            <!-- User Info -->
            <div class="text-center">
                <h2 class="text-white text-[21px] font-bold mb-2 font-mulish">Username</h2>
                
                <div class="flex justify-center gap-4 mb-3">
                    <div class="bg-[#948BC0] rounded-[7px] px-4 py-1">
                        <span class="text-white text-[14px] font-medium font-mulish">Customer</span>
                    </div>
                    <div class="bg-[#67B881] rounded-[7px] px-4 py-1">
                        <span class="text-white text-[14px] font-medium font-mulish">Active</span>
                    </div>
                </div>
                
                <p class="text-white text-[14px] font-medium font-mulish">Last Login : Today, 08.00</p>
            </div>
        </div>
    </div>

    <!-- User Details -->
    <div class="bg-gradient-to-r from-[#302B48] to-[#948BC0] rounded-[9px] border-[3px] border-white backdrop-blur-[20px] p-6 mb-8">
        <div class="grid grid-cols-4 gap-4">
            <div>
                <p class="text-[#D6D3EE] text-[14px] font-medium mb-1 font-mulish">Username</p>
                <p class="text-white text-[16px] font-semibold">Username</p>
            </div>
            <div>
                <p class="text-[#D6D3EE] text-[14px] font-medium mb-1 font-mulish">Phone</p>
                <p class="text-white text-[16px] font-semibold">+62 123-4567-8901</p>
            </div>
            <div>
                <p class="text-[#D6D3EE] text-[14px] font-medium mb-1 font-mulish">Role</p>
                <p class="text-white text-[16px] font-semibold">Customer</p>
            </div>
            <div>
                <p class="text-[#D6D3EE] text-[14px] font-medium mb-1 font-mulish">Email</p>
                <a href="mailto:username@gmail.com" class="text-white text-[16px] font-semibold underline hover:text-[#D6D3EE] transition-colors">
                    username@gmail.com
                </a>
            </div>
        </div>
        
        <div class="mt-6 pt-6 border-t border-white/30">
            <p class="text-[#D6D3EE] text-[14px] font-medium mb-1 font-mulish">Joined Date</p>
            <p class="text-white text-[16px] font-semibold">December 15, 2025</p>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- My Projects Section -->
        <div class="bg-dark-bg rounded-[8px] p-6 overflow-hidden">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-[#D3CBE0] text-[17px] font-bold font-mulish">My Project</h3>
                <div class="w-[7px] h-[170px] bg-[#817CB2] rounded-t-full"></div>
            </div>
            
            <div class="space-y-4">
                <!-- Project 1 -->
                <div class="bg-[#D4CFE5] rounded-[3px] p-4">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h4 class="text-text-dark text-[14px] font-semibold mb-1 font-mulish">Nexus ERP - NXS-ERP</h4>
                            <p class="text-text-dark text-[10px] font-light font-mulish">
                                Enterprise resource planning system for internal operations and reporting.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-text-dark text-[12px] font-light font-mulish">
                        <span>Tickets: 12</span>
                        <span class="text-green-600">Status: Active</span>
                    </div>
                </div>
                
                <!-- Project 2 -->
                <div class="bg-[#D4CFE5] rounded-[3px] p-4">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h4 class="text-text-dark text-[14px] font-semibold mb-1 font-mulish">Nova E-Commerce - NVC-ECOM</h4>
                            <p class="text-text-dark text-[10px] font-light font-mulish">
                                Online shopping platform with payment gateway integration and order management.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-text-dark text-[12px] font-light font-mulish">
                        <span>Tickets: 12</span>
                        <span class="text-green-600">Status: Active</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ticket Statistics -->
        <div class="bg-[#8078A8] rounded-[8px] p-6">
            <div class="text-center mb-6">
                <h3 class="text-white text-[22px] font-bold mb-1 font-mulish">Ticket Statistics</h3>
                <p class="text-[#D6D3EE] text-[12px] font-light font-roboto">Overview of handled tickets</p>
            </div>
            
            <div class="border-t border-white/30 my-6"></div>
            
            <!-- Total Tickets -->
            <div class="mb-6">
                <div class="bg-[#F7F6FF] rounded-[8px] p-3 text-center mb-3">
                    <p class="text-text-dark text-[12px] font-bold mb-2 font-mulish">Total Tickets</p>
                    <div class="border border-[#817CB2] rounded-[8px] p-4">
                        <p class="text-primary text-[33px] font-medium font-mulish">30</p>
                    </div>
                </div>
                
                <!-- Status Breakdown -->
                <div class="grid grid-cols-4 gap-4">
                    <div class="bg-[#D6D3EE] rounded-[8px] p-3 text-center">
                        <p class="text-primary text-[10px] font-bold mb-2 font-mulish">Open</p>
                        <div class="border border-[#817CB2] rounded-[8px] p-3">
                            <p class="text-primary text-[33px] font-medium font-mulish">7</p>
                        </div>
                    </div>
                    
                    <div class="bg-[#D6D3EE] rounded-[8px] p-3 text-center">
                        <p class="text-primary text-[10px] font-bold mb-2 font-mulish">In Progress</p>
                        <div class="border border-[#817CB2] rounded-[8px] p-3">
                            <p class="text-primary text-[33px] font-medium font-mulish">10</p>
                        </div>
                    </div>
                    
                    <div class="bg-[#D6D3EE] rounded-[8px] p-3 text-center">
                        <p class="text-primary text-[10px] font-bold mb-2 font-mulish">Resolved</p>
                        <div class="border border-[#817CB2] rounded-[8px] p-3">
                            <p class="text-primary text-[33px] font-medium font-mulish">8</p>
                        </div>
                    </div>
                    
                    <div class="bg-[#D6D3EE] rounded-[8px] p-3 text-center">
                        <p class="text-primary text-[10px] font-bold mb-2 font-mulish">Closed</p>
                        <div class="border border-[#817CB2] rounded-[8px] p-3">
                            <p class="text-primary text-[33px] font-medium font-mulish">5</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-white/30 mt-6"></div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Logout confirmation
        window.confirmLogout = function() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '<?= base_url('dashboard/logout') ?>';
            }
        };

        // Update navigation active state
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('nav a');
        
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            // Profile page doesn't have direct link in nav, but we can highlight Home
            if (currentPath.includes('profile')) {
                link.classList.remove('bg-secondary/10', 'text-secondary');
            } else if (currentPath.includes(href)) {
                link.classList.add('bg-secondary/10', 'text-secondary');
            } else {
                link.classList.remove('bg-secondary/10', 'text-secondary');
            }
        });

        // Update last login time
        function updateLastLogin() {
            const now = new Date();
            const options = { 
                hour: '2-digit', 
                minute: '2-digit',
                hour12: false 
            };
            const timeString = now.toLocaleTimeString('en-US', options);
            
            const lastLoginElement = document.querySelector('.text-white.text-\\[14px\\].font-mulish:contains("Last Login")');
            if (lastLoginElement) {
                lastLoginElement.textContent = `Last Login : Today, ${timeString}`;
            }
        }

        // Update time every minute
        updateLastLogin();
        setInterval(updateLastLogin, 60000);
    });
</script>
<?= $this->endSection() ?>