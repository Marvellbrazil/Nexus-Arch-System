<?= $this->extend('layouts/customer_layout') ?>

<?= $this->section('title') ?>Profile - NEXUS<?= $this->endSection() ?>

<?= $this->section('background_effects') ?>
<!-- Background Effects -->
<div class="fixed w-[40vw] h-[40vw] -right-[10%] -bottom-[10%] rotate-[149deg] bg-gradient-to-r from-[rgba(56.96,44.47,127.72,0.15)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-80 z-0"></div>
<div class="fixed w-[35vw] h-[25vw] -left-[5%] top-[10%] rotate-[8deg] bg-gradient-to-r from-[rgba(65.04,45.10,137.14,0.20)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-70 z-0"></div>
<div class="fixed w-[15vw] h-[20vw] right-[5%] -top-[5%] rotate-[8deg] bg-gradient-to-r from-[rgba(16.56,8.41,46.05,0.12)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-60 z-0"></div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mt-[77px] p-[30px] relative z-10">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-[32px] font-semibold mb-2 text-text-dark">My Profile</h1>
                <p class="text-[15px] font-light text-[#666]">Manage your account information and settings</p>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex items-center gap-3">
                <button onclick="confirmLogout()" 
                        class="px-4 py-2 bg-red-50 text-red-600 border border-red-200 rounded-lg hover:bg-red-100 transition-colors text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </button>
            </div>
        </div>
    </div>

    <!-- Profile Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Profile Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                <!-- Profile Header -->
                <div class="flex flex-col md:flex-row md:items-center gap-6 mb-8">
                    <!-- Avatar -->
                    <div class="relative">
                        <div class="w-24 h-24 bg-gradient-to-br from-secondary to-[#8A84C6] rounded-full flex items-center justify-center text-white text-2xl font-bold">
                            UJ
                        </div>
                        <div class="absolute bottom-2 right-2 w-6 h-6 bg-green-500 border-2 border-white rounded-full"></div>
                    </div>
                    
                    <!-- User Info -->
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3 mb-3">
                            <h2 class="text-2xl font-bold text-gray-800">Username</h2>
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 bg-secondary/10 text-secondary text-xs font-semibold rounded-full">
                                    Customer
                                </span>
                                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                    Active
                                </span>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-envelope text-gray-400"></i>
                                <span class="text-gray-700">username@gmail.com</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-phone text-gray-400"></i>
                                <span class="text-gray-700">+62 123-4567-8901</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-calendar-alt text-gray-400"></i>
                                <span class="text-gray-700">Joined: December 15, 2025</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Personal Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-user text-secondary"></i>
                        Personal Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Full Name</label>
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-gray-800">Username</span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Username</label>
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-gray-800">username</span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Email Address</label>
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-gray-800">username@gmail.com</span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Phone Number</label>
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-gray-800">+62 123-4567-8901</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Edit Button -->
                <div class="border-t border-gray-200 pt-6">
                    <button class="w-full py-3 bg-secondary text-white rounded-xl hover:bg-[#665C9E] transition-colors font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-edit"></i>
                        Edit Profile Information
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Stats -->
        <div class="space-y-6">
            <!-- Account Stats -->
            <div class="bg-gradient-to-br from-secondary to-[#8A84C6] rounded-2xl p-6 text-white">
                <h3 class="text-lg font-semibold mb-4">Account Statistics</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                            <div>
                                <p class="text-white/80 text-sm">Total Tickets</p>
                                <p class="text-2xl font-bold">30</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <p class="text-white/80 text-sm">Active</p>
                                <p class="text-2xl font-bold">7</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div>
                                <p class="text-white/80 text-sm">Resolved</p>
                                <p class="text-2xl font-bold">8</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Last Login -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Activity</h3>
                
                <div class="space-y-3">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-sign-in-alt text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">Last Login</p>
                            <p class="text-xs text-gray-600" id="lastLoginTime">Today, 08:00 AM</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-check text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">Member Since</p>
                            <p class="text-xs text-gray-600">December 15, 2025</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Projects Section -->
    <div class="mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-project-diagram text-secondary"></i>
                    My Projects
                </h3>
                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full">
                    2 Projects
                </span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Project 1 -->
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-5 border border-blue-200">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-1">Nexus ERP - NXS-ERP</h4>
                            <p class="text-gray-600 text-sm">Enterprise resource planning system for internal operations and reporting.</p>
                        </div>
                        <div class="w-10 h-10 bg-blue-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-cogs text-blue-600"></i>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-ticket-alt text-gray-500"></i>
                            <span class="text-gray-700">12 tickets</span>
                        </div>
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded">
                            Active
                        </span>
                    </div>
                </div>
                
                <!-- Project 2 -->
                <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-5 border border-purple-200">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-1">Nova E-Commerce - NVC-ECOM</h4>
                            <p class="text-gray-600 text-sm">Online shopping platform with payment gateway integration and order management.</p>
                        </div>
                        <div class="w-10 h-10 bg-purple-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-purple-600"></i>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-ticket-alt text-gray-500"></i>
                            <span class="text-gray-700">8 tickets</span>
                        </div>
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded">
                            Active
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket Statistics -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Ticket Statistics</h3>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gray-50 rounded-xl p-4 text-center">
                <p class="text-gray-600 text-sm mb-2">Total Tickets</p>
                <p class="text-3xl font-bold text-gray-800">30</p>
            </div>
            
            <div class="bg-blue-50 rounded-xl p-4 text-center">
                <p class="text-blue-600 text-sm mb-2">Open</p>
                <p class="text-3xl font-bold text-blue-700">7</p>
            </div>
            
            <div class="bg-yellow-50 rounded-xl p-4 text-center">
                <p class="text-yellow-600 text-sm mb-2">In Progress</p>
                <p class="text-3xl font-bold text-yellow-700">10</p>
            </div>
            
            <div class="bg-green-50 rounded-xl p-4 text-center">
                <p class="text-green-600 text-sm mb-2">Resolved</p>
                <p class="text-3xl font-bold text-green-700">8</p>
            </div>
        </div>
        
        <!-- Progress Bars -->
        <div class="space-y-3">
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-700">Open Tickets</span>
                    <span class="text-gray-600">7 (23%)</span>
                </div>
                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500 rounded-full" style="width: 23%"></div>
                </div>
            </div>
            
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-700">In Progress</span>
                    <span class="text-gray-600">10 (33%)</span>
                </div>
                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-yellow-500 rounded-full" style="width: 33%"></div>
                </div>
            </div>
            
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-700">Resolved</span>
                    <span class="text-gray-600">8 (27%)</span>
                </div>
                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-green-500 rounded-full" style="width: 27%"></div>
                </div>
            </div>
            
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-700">Closed</span>
                    <span class="text-gray-600">5 (17%)</span>
                </div>
                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-gray-400 rounded-full" style="width: 17%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Logout confirmation
        window.confirmLogout = function() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '<?= base_url('/logout') ?>';
            }
        };
        
        // Update last login time
        function updateLastLogin() {
            const now = new Date();
            const options = { 
                hour: '2-digit', 
                minute: '2-digit',
                hour12: true 
            };
            const timeString = now.toLocaleTimeString('en-US', options);
            const dateString = now.toLocaleDateString('en-US', { 
                weekday: 'long',
                month: 'short', 
                day: 'numeric' 
            });
            
            const lastLoginElement = document.getElementById('lastLoginTime');
            if (lastLoginElement) {
                lastLoginElement.textContent = `${dateString}, ${timeString}`;
            }
        }
        
        // Update on load
        updateLastLogin();
        
        // Edit profile button
        document.querySelector('button:contains("Edit Profile Information")').addEventListener('click', function() {
            // Simulate opening edit modal
            alert('Edit profile feature would open here.');
            
            // In a real app, this would open a modal or redirect to edit page
            // window.location.href = '<?= base_url('dashboard/profile/edit') ?>';
        });
        
        // Animate progress bars on load
        setTimeout(() => {
            document.querySelectorAll('.h-2.bg-gray-200 > div').forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0';
                setTimeout(() => {
                    bar.style.transition = 'width 1s ease-in-out';
                    bar.style.width = width;
                }, 100);
            });
        }, 500);
    });
</script>
<?= $this->endSection() ?>