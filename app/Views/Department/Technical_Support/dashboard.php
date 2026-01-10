<?= $this->extend('layouts/technical_support_layout') ?>

<?= $this->section('title') ?>Technical Support Dashboard - NEXUS<?= $this->endSection() ?>

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
<div class="mt-4 md:mt-[77px] p-4 md:p-[30px] relative z-10">
    <!-- Page Header -->
    <div class="mb-6 md:mb-[25px] relative">
        <div class="flex flex-col">
            <h1 class="text-2xl md:text-[35px] font-semibold mb-1 md:mb-[5px] text-text-dark">Technical Support
                Dashboard</h1>
            <p class="text-sm md:text-[15px] font-light text-[#666]">Hardware & infrastructure technical issues</p>
        </div>

        <!-- Action Buttons -->
        <div class="mt-4 md:mt-0 md:absolute md:right-0 md:top-0 flex flex-col sm:flex-row gap-3 md:gap-[15px]">
            <a href="<?= base_url('department/technical-support/assigned_tickets') ?>"
                class="px-4 md:px-[20px] py-2 md:py-[10px] rounded-lg bg-secondary text-white text-sm md:text-[14px] font-medium cursor-pointer flex items-center justify-center gap-2 transition-all duration-300 hover:bg-[#817CB2] hover:shadow-md no-underline">
                <i class="fas fa-tools"></i>
                <span>View Technical Issues</span>
            </a>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-8 md:mb-12">
        <!-- Critical Issues Card -->
        <div
            class="bg-gradient-to-br from-[#3D3C5E] to-[#4A4570] rounded-xl p-5 md:p-6 text-white shadow-sm flex flex-col items-center justify-center text-center">
            <div
                class="w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-[#8CEAC7] to-[#6BD4B4] rounded-full flex items-center justify-center mb-3">
                <i class="fas fa-exclamation-triangle text-white text-lg md:text-xl"></i>
            </div>
            <div class="text-3xl md:text-4xl font-bold mb-2"><?= $stats['critical_issues'] ?? '6' ?></div>
            <div class="text-white/80 text-sm md:text-[15px] font-medium mb-2">Critical Issues</div>
            <div class="text-white/60 text-xs md:text-[13px] flex items-center justify-center">
                <i class="fas fa-info-circle mr-1"></i>
                <span>Server & hardware failures</span>
            </div>
        </div>

        <!-- Pending Troubleshooting Card -->
        <div
            class="bg-gradient-to-br from-[#3D3C5E] to-[#4A4570] rounded-xl p-5 md:p-6 text-white shadow-sm flex flex-col items-center justify-center text-center">
            <div
                class="w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-[#FF8BA7] to-[#FF6B8B] rounded-full flex items-center justify-center mb-3">
                <i class="fas fa-wrench text-white text-lg md:text-xl"></i>
            </div>
            <div class="text-3xl md:text-4xl font-bold mb-2"><?= $stats['pending_troubleshooting'] ?? '14' ?></div>
            <div class="text-white/80 text-sm md:text-[15px] font-medium mb-2">Pending Troubleshooting</div>
            <div class="text-white/60 text-xs md:text-[13px]">Need technical diagnosis</div>
        </div>

        <!-- In Repair Card -->
        <div
            class="bg-gradient-to-br from-[#3D3C5E] to-[#4A4570] rounded-xl p-5 md:p-6 text-white shadow-sm flex flex-col items-center justify-center text-center">
            <div
                class="w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-[#FCD685] to-[#F9C052] rounded-full flex items-center justify-center mb-3">
                <i class="fas fa-tools text-white text-lg md:text-xl"></i>
            </div>
            <div class="text-3xl md:text-4xl font-bold mb-2"><?= $stats['in_repair'] ?? '8' ?></div>
            <div class="text-white/80 text-sm md:text-[15px] font-medium mb-2">In Repair</div>
            <div class="text-white/60 text-xs md:text-[13px]">Hardware/software fixes ongoing</div>
        </div>

        <!-- Resolved Technical Issues Card -->
        <div
            class="bg-gradient-to-br from-[#3D3C5E] to-[#4A4570] rounded-xl p-5 md:p-6 text-white shadow-sm flex flex-col items-center justify-center text-center">
            <div
                class="w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-[#82B4FF] to-[#5D9CFF] rounded-full flex items-center justify-center mb-3">
                <i class="fas fa-check-circle text-white text-lg md:text-xl"></i>
            </div>
            <div class="text-3xl md:text-4xl font-bold mb-2"><?= $stats['resolved'] ?? '32' ?></div>
            <div class="text-white/80 text-sm md:text-[15px] font-medium mb-2">Resolved Issues</div>
            <div class="text-white/60 text-xs md:text-[13px]">15 Today | 42 This Week</div>
        </div>
    </div>

    <!-- Recent Technical Issues Section -->
    <div class="bg-card-bg/50 rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-8">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h2 class="text-xl md:text-2xl font-semibold text-text-dark">Recent Technical Issues</h2>
                <a href="<?= base_url('department/technical-support/assigned_tickets') ?>"
                    class="px-4 md:px-6 py-2 md:py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium flex items-center gap-2">
                    <span>View All Issues</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="p-4 md:p-6 bg-card-bg">
            <div class="relative">
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                    <i class="fas fa-search"></i>
                </div>
                <input type="text" placeholder="Search technical issues..."
                    class="w-full pl-10 pr-4 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20">
            </div>
        </div>

        <!-- Technical Issues Table -->
        <div class="overflow-x-auto">
            <table class="w-full min-w-max">
                <thead>
                    <tr class="bg-gray-100/50 border-b border-gray-200">
                        <th class="py-3 px-4 text-left text-xs md:text-sm font-semibold text-gray-700">
                            <div class="flex items-center gap-1">
                                <span>Issue ID</span>
                                <i class="fas fa-sort text-gray-400"></i>
                            </div>
                        </th>
                        <th class="py-3 px-4 text-left text-xs md:text-sm font-semibold text-gray-700">
                            <span>Problem Description</span>
                        </th>
                        <th class="py-3 px-4 text-left text-xs md:text-sm font-semibold text-gray-700">
                            <span>System/Component</span>
                        </th>
                        <th class="py-3 px-4 text-left text-xs md:text-sm font-semibold text-gray-700">
                            <span>Priority</span>
                        </th>
                        <th class="py-3 px-4 text-left text-xs md:text-sm font-semibold text-gray-700">
                            <span>Status</span>
                        </th>
                        <th class="py-3 px-4 text-left text-xs md:text-sm font-semibold text-gray-700">
                            <span>Action</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Issue 1 -->
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4">
                            <span class="text-text-dark font-semibold">#TS10421</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="max-w-xs truncate">
                                <span class="text-gray-700">Server load balancer failure</span>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-gray-600">Production Server</span>
                        </td>
                        <td class="py-3 px-4">
                            <span
                                class="px-2 py-1 bg-red-500/20 text-red-300 text-xs rounded pulse-critical">Critical</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded">Emergency</span>
                        </td>
                        <td class="py-3 px-4">
                            <a href="<?= base_url('department/technical-support/ticket/TS10421') ?>"
                                class="px-3 py-1 bg-secondary text-white text-xs rounded hover:bg-[#817CB2] transition-colors no-underline">
                                Fix Now
                            </a>
                        </td>
                    </tr>

                    <!-- Issue 2 -->
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4">
                            <span class="text-text-dark font-semibold">#TS10422</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="max-w-xs truncate">
                                <span class="text-gray-700">Database connection timeout</span>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-gray-600">MySQL Cluster</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded">High</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">In Progress</span>
                        </td>
                        <td class="py-3 px-4">
                            <a href="<?= base_url('department/technical-support/ticket/TS10422') ?>"
                                class="px-3 py-1 bg-secondary text-white text-xs rounded hover:bg-[#817CB2] transition-colors no-underline">
                                Diagnose
                            </a>
                        </td>
                    </tr>

                    <!-- Issue 3 -->
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4">
                            <span class="text-text-dark font-semibold">#TS10423</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="max-w-xs truncate">
                                <span class="text-gray-700">Network switch configuration</span>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-gray-600">Network Infrastructure</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded">Medium</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded">Pending Parts</span>
                        </td>
                        <td class="py-3 px-4">
                            <a href="<?= base_url('department/technical-support/ticket/TS10423') ?>"
                                class="px-3 py-1 bg-secondary text-white text-xs rounded hover:bg-[#817CB2] transition-colors no-underline">
                                Track Parts
                            </a>
                        </td>
                    </tr>

                    <!-- Issue 4 -->
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4">
                            <span class="text-text-dark font-semibold">#TS10424</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="max-w-xs truncate">
                                <span class="text-gray-700">Backup system verification</span>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-gray-600">Backup Server</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 bg-indigo-100 text-indigo-800 text-xs rounded">Low</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Resolved</span>
                        </td>
                        <td class="py-3 px-4">
                            <a href="<?= base_url('department/technical-support/ticket/TS10424') ?>"
                                class="px-3 py-1 bg-secondary text-white text-xs rounded hover:bg-[#817CB2] transition-colors no-underline">
                                View Report
                            </a>
                        </td>
                    </tr>

                    <!-- Issue 5 -->
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4">
                            <span class="text-text-dark font-semibold">#TS10425</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="max-w-xs truncate">
                                <span class="text-gray-700">Firewall rule optimization</span>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-gray-600">Security System</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded">Medium</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 bg-gray-200 text-gray-800 text-xs rounded">Scheduled</span>
                        </td>
                        <td class="py-3 px-4">
                            <a href="<?= base_url('department/technical-support/ticket/TS10425') ?>"
                                class="px-3 py-1 bg-secondary text-white text-xs rounded hover:bg-[#817CB2] transition-colors no-underline">
                                Schedule
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Technical Tools -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Diagnostics Tool -->
        <div class="bg-card-bg rounded-xl p-5 md:p-6 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-secondary/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-stethoscope text-secondary text-lg"></i>
                </div>
                <h3 class="text-lg font-semibold text-text-dark">System Diagnostics</h3>
            </div>
            <p class="text-gray-600 text-sm mb-4">Run comprehensive system diagnostics and health checks</p>
            <button
                class="w-full py-2 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium">
                Run Diagnostics
            </button>
        </div>

        <!-- Monitoring Dashboard -->
        <div class="bg-card-bg rounded-xl p-5 md:p-6 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-secondary/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-desktop text-secondary text-lg"></i>
                </div>
                <h3 class="text-lg font-semibold text-text-dark">Live Monitoring</h3>
            </div>
            <p class="text-gray-600 text-sm mb-4">Monitor server performance and network status in real-time</p>
            <button
                class="w-full py-2 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium">
                Open Dashboard
            </button>
        </div>

        <!-- Inventory Management -->
        <div class="bg-card-bg rounded-xl p-5 md:p-6 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-secondary/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-boxes text-secondary text-lg"></i>
                </div>
                <h3 class="text-lg font-semibold text-text-dark">Hardware Inventory</h3>
            </div>
            <p class="text-gray-600 text-sm mb-4">Manage hardware inventory and track equipment status</p>
            <button
                class="w-full py-2 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium">
                View Inventory
            </button>
        </div>
    </div>
</div>

<style>
    /* Custom animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.7;
        }
    }

    .pulse-critical {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    .card-hover {
        transition: all 0.2s ease;
    }

    .card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    /* Custom styles for stats cards */
    .stats-card {
        position: relative;
        overflow: hidden;
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(to right, #8CEAC7, #FF8BA7, #FCD685, #82B4FF);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Add pulse animation to critical priority badges
        document.querySelectorAll('.bg-red-500\\/20').forEach(badge => {
            badge.classList.add('pulse-critical');
        });

        // Add hover effects to cards
        document.querySelectorAll('.bg-card-bg.rounded-xl').forEach(card => {
            card.classList.add('card-hover');
        });

        // Technical tools buttons
        document.querySelectorAll('.bg-card-bg .bg-secondary').forEach(button => {
            button.addEventListener('click', function () {
                const toolName = this.closest('.bg-card-bg').querySelector('h3').textContent;
                showToast(`Opening ${toolName.toLowerCase()}...`, 'success');
            });
        });

        // Technical issues table row click
        document.querySelectorAll('tbody tr').forEach(row => {
            row.addEventListener('click', function (e) {
                if (!e.target.closest('button') && !e.target.closest('a')) {
                    const issueId = this.querySelector('td:first-child span').textContent;
                    showToast(`Opening technical issue ${issueId}`, 'info');
                }
            });
        });
    });

    // Toast notification function
    function showToast(message, type = 'info') {
        // Remove existing toasts
        document.querySelectorAll('.toast-notification').forEach(toast => toast.remove());

        const toast = document.createElement('div');
        toast.className = `toast-notification fixed top-24 right-6 p-4 rounded-xl shadow-xl z-[9999] max-w-sm animate-fadeInUp ${type === 'error' ? 'bg-red-500 text-white border-l-4 border-red-600' : type === 'success' ? 'bg-green-500 text-white border-l-4 border-green-600' : 'bg-blue-500 text-white border-l-4 border-blue-600'}`;
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
</script>
<?= $this->endSection() ?>