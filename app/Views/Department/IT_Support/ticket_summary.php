<?= $this->extend('layouts/it_support_layout') ?>

<?= $this->section('title') ?>Ticket Summary #<?= $ticket_id ?? '10421' ?> - IT Support<?= $this->endSection() ?>

<?= $this->section('background_effects') ?>
<!-- Background Effects -->
<div class="fixed w-[40vw] h-[40vw] -right-[10%] -bottom-[10%] rotate-[149deg] bg-gradient-to-r from-[rgba(56.96,44.47,127.72,0.15)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-80 z-0"></div>
<div class="fixed w-[35vw] h-[25vw] -left-[5%] top-[10%] rotate-[8deg] bg-gradient-to-r from-[rgba(65.04,45.10,137.14,0.20)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-70 z-0"></div>
<div class="fixed w-[15vw] h-[20vw] right-[5%] -top-[5%] rotate-[8deg] bg-gradient-to-r from-[rgba(16.56,8.41,46.05,0.12)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-60 z-0"></div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mt-16 md:mt-[77px] p-4 md:p-6 relative z-10">
    <!-- Page Header -->
    <div class="mb-6 md:mb-8">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
            <a href="<?= base_url('department/it-support/dashboard') ?>" class="hover:text-secondary transition-colors">Dashboard</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <a href="<?= base_url('department/it-support/assigned_tickets') ?>" class="hover:text-secondary transition-colors">Assigned Tickets</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-secondary font-medium">Ticket Summary</span>
        </div>
        
        <!-- Main Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Ticket Summary</h1>
                    <div class="px-3 py-1 bg-secondary/10 text-secondary text-sm font-semibold rounded-full">
                        #T<?= $ticket_id ?? '10421' ?>
                    </div>
                </div>
                <p class="text-gray-600">Technical summary for server maintenance ticket</p>
            </div>
            
            <div class="flex flex-wrap gap-3">
                <a href="<?= base_url('department/it-support/ticket_detail/' . ($ticket_id ?? '10421')) ?>" 
                   class="px-4 py-2 bg-white text-secondary border border-secondary rounded-lg hover:bg-secondary/5 transition-all font-medium flex items-center gap-2 text-sm">
                    <i class="fas fa-external-link-alt"></i>
                    Open Full View
                </a>
                <button onclick="window.print()" 
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all font-medium flex items-center gap-2 text-sm">
                    <i class="fas fa-print"></i>
                    Print Summary
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Stats Bar -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6 md:mb-8">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-server text-blue-600"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Server</p>
                    <p class="text-gray-800 font-bold">SRV-ALPHA-01</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-project-diagram text-purple-600"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Project</p>
                    <p class="text-gray-800 font-bold">Project Alpha</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-flag text-red-600"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Priority</p>
                    <p class="text-red-600 font-bold">High</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-green-600"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Status</p>
                    <p class="text-green-600 font-bold">In Progress</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Left Column - Problem Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Problem Description Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-server text-secondary"></i>
                            Technical Issue Details
                        </h2>
                        <span class="text-gray-500 text-sm">
                            <i class="far fa-calendar mr-1"></i>
                            Assigned: Today, 9:30 AM
                        </span>
                    </div>
                </div>
                
                <div class="p-6">
                    <!-- Subject -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Issue Summary</h3>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <p class="text-gray-800 font-medium">Server memory leak causing performance degradation</p>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Technical Analysis</h3>
                        <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                            <div class="text-gray-700 leading-relaxed space-y-4">
                                <p><strong>Issue Identified:</strong> Memory leak in application server deployment v2.4.1</p>
                                <p><strong>Root Cause:</strong> The memory leak appears to be related to the latest deployment that occurred on February 18, 2026. The application server is not properly releasing memory after processing requests, leading to gradual memory consumption increase until system performance is critically impacted.</p>
                                <p><strong>Affected Components:</strong></p>
                                <ul class="list-disc pl-5 space-y-2">
                                    <li>Web Service (Port 8080)</li>
                                    <li>Database Connection Pool</li>
                                    <li>API Gateway Service</li>
                                    <li>Cache Management System</li>
                                </ul>
                                <p><strong>Current Impact:</strong></p>
                                <ul class="list-disc pl-5 space-y-2">
                                    <li>Response time increased by 300%</li>
                                    <li>Memory usage at 95% capacity</li>
                                    <li>Automatic scaling triggered 5 times in last 24 hours</li>
                                    <li>Customer dashboard access intermittently unavailable</li>
                                </ul>
                                <p><strong>Immediate Action Taken:</strong> Rollback to previous stable version (v2.3.8) completed successfully. Memory usage has stabilized at 45% capacity. Monitoring ongoing for next 24 hours.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
        </div>

        <!-- Right Column - Sidebar -->
        <div class="space-y-6">
            <!-- Server Information Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-server text-blue-600"></i>
                        Server Information
                    </h2>
                </div>
                
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                            S01
                        </div>
                        <div>
                            <h3 class="text-gray-800 font-bold">SRV-ALPHA-01</h3>
                            <p class="text-gray-600 text-sm">Production Server</p>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-microchip text-gray-400 w-5"></i>
                            <span class="text-gray-700">16 vCPU, 64GB RAM</span>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <i class="fas fa-hdd text-gray-400 w-5"></i>
                            <span class="text-gray-700">1TB SSD Storage</span>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <i class="fas fa-network-wired text-gray-400 w-5"></i>
                            <span class="text-gray-700">Data Center A, Rack 42</span>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <i class="fas fa-shield-alt text-gray-400 w-5"></i>
                            <span class="text-gray-700">High Availability Cluster</span>
                        </div>
                        
                        <div class="pt-3 border-t border-gray-200">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-gray-600">Current Load</span>
                                <span class="text-green-600 font-medium">45%</span>
                            </div>
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-green-500 rounded-full w-[45%]"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Technical Details Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-secondary/10 to-secondary/5">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-info-circle text-secondary"></i>
                        Technical Details
                    </h2>
                </div>
                
                <div class="p-6">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Environment</span>
                            <span class="px-3 py-1 bg-red-100 text-red-800 text-sm rounded-full">Production</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Status</span>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">In Progress</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Priority</span>
                            <span class="px-3 py-1 bg-red-100 text-red-800 text-sm rounded-full">High</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Category</span>
                            <span class="px-3 py-1 bg-purple-100 text-purple-800 text-sm rounded-full">Server Infrastructure</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Assigned</span>
                            <span class="font-medium">Today, 9:30 AM</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Last Updated</span>
                            <span class="font-medium">Today, 11:15 AM</span>
                        </div>
                        
                        <div class="pt-3 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">SLA Status</span>
                                <span class="text-green-600 font-medium">✓ On Track</span>
                            </div>
                            <div class="text-gray-500 text-xs mt-1">12h remaining for resolution</div>
                        </div>
                    </div>
                </div>
            </div>

            
        </div>
    </div>

    <!-- Bottom Actions -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Technical Summary Complete</h3>
                <p class="text-gray-600">Review complete details or return to assigned tickets</p>
            </div>
            
            <div class="flex gap-3">
                <a href="<?= base_url('department/it-support/assigned_tickets') ?>" 
                   class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Back to Assigned
                </a>
                <a href="<?= base_url('department/it-support/ticket_detail/' . ($ticket_id ?? '10421')) ?>" 
                   class="px-6 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium flex items-center gap-2">
                    <i class="fas fa-comments"></i>
                    Open Conversation
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Smooth animations */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Card hover effects */
    .card-hover {
        transition: all 0.2s ease;
    }
    
    .card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    /* Status badge pulse */
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    /* Timeline styling */
    .relative.pl-8.border-l-2::before {
        content: '';
        position: absolute;
        left: -2px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, #10b981, #3b82f6, #8b5cf6, #f59e0b);
    }
    
    /* Service status dots animation */
    @keyframes servicePulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    
    .bg-red-500.rounded-full {
        animation: servicePulse 2s ease-in-out infinite;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects to cards
    document.querySelectorAll('.bg-white.rounded-2xl, .bg-gradient-to-r').forEach(card => {
        card.classList.add('card-hover');
    });
    
    // Update current time for timeline
    function updateCurrentTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { 
            hour: '2-digit', 
            minute: '2-digit',
            hour12: true 
        });
        
        const currentTimeElements = document.querySelectorAll('.text-gray-500.text-xs:contains("Current")');
        currentTimeElements.forEach(el => {
            if (el.textContent.includes('Current')) {
                el.textContent = `Today, ${timeString} • Current`;
            }
        });
    }
    
    // Update every minute
    updateCurrentTime();
    setInterval(updateCurrentTime, 60000);
    
    // Service status updates
    const serviceStatuses = document.querySelectorAll('.w-3.h-3.rounded-full');
    serviceStatuses.forEach(status => {
        status.addEventListener('click', function() {
            const serviceName = this.closest('.flex.items-center.justify-between').querySelector('.text-gray-700').textContent;
            const currentColor = this.classList[1];
            let newColor, statusText;
            
            if (currentColor === 'bg-red-500') {
                newColor = 'bg-yellow-500';
                statusText = 'Degraded';
            } else if (currentColor === 'bg-yellow-500') {
                newColor = 'bg-green-500';
                statusText = 'Healthy';
            } else {
                newColor = 'bg-red-500';
                statusText = 'Critical';
            }
            
            this.className = `w-3 h-3 ${newColor} rounded-full`;
            
            // Update service health percentage
            const healthBar = document.querySelector('.h-full.bg-green-500.rounded-full');
            const healthText = document.querySelector('.text-gray-700.text-sm.font-medium');
            
            if (healthBar && healthText) {
                let currentHealth = parseInt(healthText.textContent);
                if (statusText === 'Healthy' && currentColor === 'bg-red-500') {
                    currentHealth += 20;
                } else if (statusText === 'Critical' && currentColor === 'bg-green-500') {
                    currentHealth -= 20;
                }
                
                currentHealth = Math.max(0, Math.min(100, currentHealth));
                healthBar.style.width = `${currentHealth}%`;
                healthText.textContent = `${currentHealth}%`;
            }
            
            showToast(`${serviceName} status updated to ${statusText}`, 'info');
        });
    });
    
    // Print button functionality
    const printBtn = document.querySelector('button:contains("Print")');
    if (printBtn) {
        printBtn.addEventListener('click', function() {
            // Show print preview
            showToast('Preparing print preview...', 'info');
            
            setTimeout(() => {
                // In a real app, this would open the print dialog
                // window.print();
                showToast('Print preview ready', 'success');
            }, 1000);
        });
    }
    
    // Team contact click handlers
    document.querySelectorAll('.bg-white\\/10.rounded-lg').forEach(contact => {
        contact.addEventListener('click', function() {
            const personName = this.querySelector('.font-medium').textContent;
            const role = this.querySelector('.text-white\\/80').textContent;
            
            showToast(`Contact: ${personName} (${role})`, 'info');
            
            // Simulate opening contact details
            if (personName.includes('Michael Chen')) {
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4';
                modal.innerHTML = `
                    <div class="bg-white rounded-2xl w-full max-w-md animate-fadeInUp">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-semibold text-gray-800">Contact Details</h3>
                                <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-xl font-bold">
                                    MC
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-gray-800">Michael Chen</h4>
                                    <p class="text-gray-600">Senior IT Engineer</p>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                    <span class="text-gray-700">michael.chen@company.com</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-phone text-gray-400"></i>
                                    <span class="text-gray-700">+1 (555) 123-4567</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-slack text-gray-400"></i>
                                    <span class="text-gray-700">@mchen-it</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6 border-t border-gray-200 flex gap-3">
                            <button onclick="this.closest('.fixed').remove()" class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                                Close
                            </button>
                            <button onclick="window.location.href='mailto:michael.chen@company.com'" class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2]">
                                Send Email
                            </button>
                        </div>
                    </div>
                `;
                
                document.body.appendChild(modal);
                document.body.style.overflow = 'hidden';
            }
        });
    });
});

function showToast(message, type = 'info') {
    // Remove existing toasts
    document.querySelectorAll('.toast-notification').forEach(toast => toast.remove());
    
    const toast = document.createElement('div');
    toast.className = `toast-notification fixed top-24 right-6 p-4 rounded-xl shadow-xl z-[9999] max-w-sm animate-fadeInUp ${
        type === 'error' ? 'bg-red-500 text-white border-l-4 border-red-600' : 
        type === 'success' ? 'bg-green-500 text-white border-l-4 border-green-600' : 
        'bg-blue-500 text-white border-l-4 border-blue-600'
    }`;
    toast.innerHTML = `
        <div class="flex items-center gap-3">
            <i class="fas ${
                type === 'error' ? 'fa-exclamation-circle text-xl' : 
                type === 'success' ? 'fa-check-circle text-xl' : 
                'fa-info-circle text-xl'
            }"></i>
            <div class="flex-1">
                <p class="font-medium">${message}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-white/80 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (toast.parentElement) {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => toast.remove(), 300);
        }
    }, 5000);
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