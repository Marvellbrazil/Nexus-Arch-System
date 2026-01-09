<?= $this->extend('layouts/it_support_layout') ?>

<?= $this->section('title') ?>Profile - IT Support Department<?= $this->endSection() ?>

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
                <h1 class="text-[32px] font-semibold mb-2 text-text-dark">IT Support Engineer Profile</h1>
                <p class="text-[15px] font-light text-[#666]">Manage your IT Support account and technical expertise</p>
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
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-2">
        <!-- Profile Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                <!-- Profile Header -->
                <div class="flex flex-col md:flex-row md:items-center gap-6 mb-8">
                    <!-- Avatar -->
                    <div class="relative">
                        <div class="w-24 h-24 bg-gradient-to-br from-secondary to-[#8A84C6] rounded-full flex items-center justify-center text-white text-2xl font-bold">
                            IT
                        </div>
                        <div class="absolute bottom-2 right-2 w-6 h-6 bg-green-500 border-2 border-white rounded-full"></div>
                    </div>
                    
                    <!-- IT Support Engineer Info -->
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3 mb-3">
                            <h2 class="text-2xl font-bold text-gray-800">IT Support Engineer</h2>
                            <span class="px-3 py-1 bg-secondary/10 text-secondary text-sm rounded-full">Level 2</span>
                        </div>
                        
                        <div class="space-y-2">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-envelope text-gray-400"></i>
                                <span class="text-gray-700">engineer@itsupport.com</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-id-badge text-gray-400"></i>
                                <span class="text-gray-700">ID: IT-2025-001</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-calendar-alt text-gray-400"></i>
                                <span class="text-gray-700">Joined: February 15, 2025</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Engineer Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-user-tie text-secondary"></i>
                        Engineer Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Full Name</label>
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-gray-800">Michael Chen</span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Specialization</label>
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-gray-800">Server Infrastructure</span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Email Address</label>
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-gray-800">engineer@itsupport.com</span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Role</label>
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-gray-800">Senior IT Support Engineer</span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Shift Hours</label>
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-gray-800">09:00 - 18:00 (Mon-Fri)</span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-gray-600 text-sm mb-1">On-call Rotation</label>
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-gray-800">Week 1 & 3 of Month</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Technical Expertise -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-cogs text-secondary"></i>
                        Technical Expertise
                    </h3>
                    
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">Linux Administration</span>
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">Docker & Kubernetes</span>
                        <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm">AWS Cloud</span>
                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm">Network Security</span>
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">Database Management</span>
                        <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm">Monitoring Tools</span>
                        <span class="px-3 py-1 bg-pink-100 text-pink-800 rounded-full text-sm">Scripting (Bash/Python)</span>
                        <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">CI/CD Pipelines</span>
                    </div>
                </div>
                
                <!-- Edit Button -->
                <div class="border-t border-gray-200 pt-6">
                    <button id="editProfileBtn" class="w-full py-3 bg-secondary text-white rounded-xl hover:bg-[#665C9E] transition-colors font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-edit"></i>
                        Edit Engineer Profile
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Stats -->
        <div class="space-y-6">
            <!-- Performance Stats -->
            <div class="bg-gradient-to-br from-secondary to-[#8A84C6] rounded-2xl p-6 text-white">
                <h3 class="text-lg font-semibold mb-4">Technical Performance</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-server"></i>
                            </div>
                            <div>
                                <p class="text-white/80 text-sm">Servers Managed</p>
                                <p class="text-2xl font-bold">42</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                            <div>
                                <p class="text-white/80 text-sm">Tickets Resolved</p>
                                <p class="text-2xl font-bold">156</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <p class="text-white/80 text-sm">Avg. Resolution</p>
                                <p class="text-2xl font-bold">4.2h</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div>
                                <p class="text-white/80 text-sm">Uptime</p>
                                <p class="text-2xl font-bold">99.8%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Activity & Availability -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Activity & Availability</h3>
                
                <div class="space-y-3">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-sign-in-alt text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">Last Login</p>
                            <p class="text-xs text-gray-600" id="lastLoginTime">Today, 08:30 AM</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-check text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">Member Since</p>
                            <p class="text-xs text-gray-600">February 15, 2025</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-clock text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">Current Status</p>
                            <p class="text-xs text-green-600 font-medium cursor-pointer" id="statusToggle">● Available</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Assignments -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Assignments</h3>
                
                <div class="space-y-3">
                    <div class="p-3 bg-blue-50 rounded-lg">
                        <p class="text-sm font-medium text-gray-800">Server SRV-ALPHA-01</p>
                        <p class="text-xs text-gray-600">Memory leak issue • High Priority</p>
                    </div>
                    
                    <div class="p-3 bg-green-50 rounded-lg">
                        <p class="text-sm font-medium text-gray-800">Database Cluster</p>
                        <p class="text-xs text-gray-600">Performance tuning • Medium Priority</p>
                    </div>
                    
                    <div class="p-3 bg-yellow-50 rounded-lg">
                        <p class="text-sm font-medium text-gray-800">Network Security</p>
                        <p class="text-xs text-gray-600">Firewall rules update • Low Priority</p>
                    </div>
                    
                    <button class="w-full mt-3 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                        View All Assignments
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="-mt-6 mb-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-chart-line text-secondary"></i>
                            Performance Metrics (Last 30 Days)
                        </h3>
                        <span class="px-3 py-1 bg-secondary/10 text-secondary text-sm rounded-full">
                            Updated: Today
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-gray-50 rounded-xl p-4 text-center">
                            <p class="text-gray-600 text-sm mb-2">Tickets Handled</p>
                            <p class="text-3xl font-bold text-gray-800">28</p>
                            <p class="text-xs text-green-600 mt-1">
                                <i class="fas fa-arrow-up mr-1"></i>12% increase
                            </p>
                        </div>
                        
                        <div class="bg-blue-50 rounded-xl p-4 text-center">
                            <p class="text-blue-600 text-sm mb-2">Avg. Resolution Time</p>
                            <p class="text-3xl font-bold text-blue-700">4.2h</p>
                            <p class="text-xs text-green-600 mt-1">
                                <i class="fas fa-arrow-down mr-1"></i>0.8h faster
                            </p>
                        </div>
                        
                        <div class="bg-yellow-50 rounded-xl p-4 text-center">
                            <p class="text-yellow-600 text-sm mb-2">Server Uptime</p>
                            <p class="text-3xl font-bold text-yellow-700">99.8%</p>
                            <p class="text-xs text-green-600 mt-1">
                                <i class="fas fa-arrow-up mr-1"></i>0.2% increase
                            </p>
                        </div>
                        
                        <div class="bg-green-50 rounded-xl p-4 text-center">
                            <p class="text-green-600 text-sm mb-2">SLA Compliance</p>
                            <p class="text-3xl font-bold text-green-700">98%</p>
                            <p class="text-xs text-green-600 mt-1">
                                <i class="fas fa-arrow-up mr-1"></i>3% increase
                            </p>
                        </div>
                    </div>
                    
                    <!-- Performance Breakdown -->
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-gray-700 font-medium">Incident Resolution Rate</span>
                                <span class="text-gray-600">94%</span>
                            </div>
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-green-500 rounded-full" style="width: 94%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-gray-700 font-medium">System Stability</span>
                                <span class="text-gray-600">96%</span>
                            </div>
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-500 rounded-full" style="width: 96%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-gray-700 font-medium">Technical Quality Score</span>
                                <span class="text-gray-600">92%</span>
                            </div>
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-purple-500 rounded-full" style="width: 92%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hidden lg:block"></div>
        </div>
    </div>

    
</div>

<style>
    /* Animations for progress bars */
    @keyframes fillProgress {
        from { width: 0; }
        to { width: var(--target-width); }
    }
    
    .progress-bar-animated {
        animation: fillProgress 1.5s ease-out forwards;
    }
</style>

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
        document.getElementById('editProfileBtn').addEventListener('click', function() {
            // Simulate opening edit modal
            showEditProfileModal();
        });
        
        // Animate progress bars on load
        setTimeout(() => {
            document.querySelectorAll('.h-2.bg-gray-200 > div').forEach(bar => {
                const width = bar.style.width;
                bar.style.setProperty('--target-width', width);
                bar.style.width = '0';
                bar.classList.add('progress-bar-animated');
            });
        }, 500);
        
        // Status toggle functionality
        const statusElement = document.getElementById('statusToggle');
        if (statusElement) {
            statusElement.addEventListener('click', function() {
                const currentStatus = this.textContent.includes('Available') ? 'Available' : 'Away';
                const newStatus = currentStatus === 'Available' ? 'Away' : 'Available';
                const newColor = newStatus === 'Available' ? 'green' : 'yellow';
                
                this.textContent = `● ${newStatus}`;
                this.className = `text-xs text-${newColor}-600 font-medium cursor-pointer`;
                
                showToast(`Status changed to ${newStatus}`, 'info');
            });
        }
        
        // Skills click functionality
        document.querySelectorAll('.px-3.py-1.rounded-full').forEach(skill => {
            skill.addEventListener('click', function() {
                const skillName = this.textContent;
                showToast(`Expertise: ${skillName}`, 'info');
            });
        });
        
        // Recent assignments click
        document.querySelectorAll('.p-3.rounded-lg').forEach(assignment => {
            assignment.addEventListener('click', function() {
                const title = this.querySelector('.font-medium').textContent;
                const description = this.querySelector('.text-gray-600').textContent;
                showToast(`Assignment: ${title} - ${description}`, 'info');
            });
        });
        
        // View all assignments button
        const viewAllBtn = document.querySelector('button:contains("View All Assignments")');
        if (viewAllBtn) {
            viewAllBtn.addEventListener('click', function() {
                showToast('Opening all assignments...', 'info');
                setTimeout(() => {
                    window.location.href = '<?= base_url('department/it-support/assigned_tickets') ?>';
                }, 1000);
            });
        }
    });
    
    function showEditProfileModal() {
        // Create modal
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4';
        modal.innerHTML = `
            <div class="bg-white rounded-2xl w-full max-w-md animate-fadeInUp">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-gray-800">Edit Engineer Profile</h3>
                        <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-600 text-sm mb-2">Full Name</label>
                            <input type="text" value="Michael Chen" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                        </div>
                        
                        <div>
                            <label class="block text-gray-600 text-sm mb-2">Email Address</label>
                            <input type="email" value="engineer@itsupport.com" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                        </div>
                        
                        <div>
                            <label class="block text-gray-600 text-sm mb-2">Specialization</label>
                            <select class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                                <option>Server Infrastructure</option>
                                <option>Network Security</option>
                                <option>Cloud Management</option>
                                <option>Database Administration</option>
                                <option>DevOps Engineering</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-gray-600 text-sm mb-2">Shift Hours</label>
                            <select class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                                <option>09:00 - 18:00 (Mon-Fri)</option>
                                <option>08:00 - 17:00 (Mon-Fri)</option>
                                <option>Flexible Hours</option>
                                <option>24/7 On-call</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 border-t border-gray-200 flex gap-3">
                    <button onclick="this.closest('.fixed').remove()" class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                        Cancel
                    </button>
                    <button onclick="saveProfileChanges()" class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2]">
                        Save Changes
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        document.body.style.overflow = 'hidden';
    }
    
    function saveProfileChanges() {
        // Simulate saving
        const saveBtn = document.querySelector('button:contains("Save Changes")');
        const originalText = saveBtn.textContent;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
        saveBtn.disabled = true;
        
        setTimeout(() => {
            // Close modal
            document.querySelector('.fixed.inset-0').remove();
            document.body.style.overflow = 'auto';
            
            // Show success message
            showToast('Engineer profile updated successfully!', 'success');
            
            // In a real app, you would update the UI with new data
            // For now, just reset the button
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
        }, 1500);
    }
    
    function showToast(message, type = 'info') {
        // Remove existing toasts
        document.querySelectorAll('.toast-notification').forEach(toast => toast.remove());
        
        const toast = document.createElement('div');
        toast.className = `toast-notification fixed top-24 right-6 p-4 rounded-xl shadow-xl z-[9999] max-w-sm animate-fadeInUp ${type === 'error' ? 'bg-red-500 text-white' : type === 'success' ? 'bg-green-500 text-white' : 'bg-blue-500 text-white'}`;
        toast.innerHTML = `
            <div class="flex items-center gap-3">
                <i class="fas ${type === 'error' ? 'fa-exclamation-circle text-xl' : type === 'success' ? 'fa-check-circle text-xl' : 'fa-info-circle text-xl'}"></i>
                <div class="flex-1">
                    <p class="font-medium">${message}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-white/80 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (toast.parentElement) {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => toast.remove(), 300);
            }
        }, 3000);
    }
    
    // Add CSS animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fadeInUp {
            animation: fadeInUp 0.5s ease-out;
        }
    `;
    document.head.appendChild(style);
</script>
<?= $this->endSection() ?>