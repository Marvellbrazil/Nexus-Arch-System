<?= $this->extend('layouts/technical_support_layout') ?>

<?= $this->section('title') ?>Notifications - Technical Support Department<?= $this->endSection() ?>

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
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-[35px] font-semibold mb-1 md:mb-[5px] text-text-dark">Technical Support
                    Notifications</h1>
                <p class="text-sm md:text-[15px] font-light text-[#666]">Customer support tickets and technical issue
                    alerts</p>
            </div>

            <!-- Notification Actions -->
            <div class="flex items-center gap-3">
                <form action="<?= base_url('department/technical-support/notifications/mark_read') ?>" method="POST"
                    class="inline">
                    <?= csrf_field() ?>
                    <button type="submit"
                        class="px-4 py-2 bg-white text-secondary border border-secondary rounded-lg hover:bg-secondary/5 transition-all text-sm font-medium flex items-center gap-2">
                        <i class="fas fa-check-double"></i>
                        Mark All as Read
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="mb-6 md:mb-8">
        <div class="flex flex-col md:flex-row gap-4 md:gap-6">
            <!-- Search Bar -->
            <div class="flex-1 relative">
                <div class="relative">
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-text-muted">
                        <i class="fas fa-search"></i>
                    </div>
                    <input type="text" placeholder="Search technical support notifications..." id="searchInput"
                        class="w-full h-12 md:h-[50px] pl-12 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm md:text-[14px] focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
                </div>
            </div>

            <!-- Filter Buttons -->
            <div class="flex gap-3">
                <!-- Filter by Type -->
                <div class="flex gap-2">
                    <button data-filter="all"
                        class="h-12 md:h-[50px] px-4 md:px-6 bg-secondary text-white border border-secondary rounded-xl text-sm md:text-[14px] font-medium hover:bg-[#817CB2] transition-colors">
                        All
                    </button>
                    <button data-filter="ticket"
                        class="h-12 md:h-[50px] px-4 md:px-6 bg-[#AEA3CA] text-text-dark border border-[#D1D1E9] rounded-xl text-sm md:text-[14px] font-medium hover:bg-[#9F95C0] transition-colors">
                        Tickets
                    </button>
                    <button data-filter="escalation"
                        class="h-12 md:h-[50px] px-4 md:px-6 bg-[#AEA3CA] text-text-dark border border-[#D1D1E9] rounded-xl text-sm md:text-[14px] font-medium hover:bg-[#9F95C0] transition-colors">
                        Escalations
                    </button>
                </div>

                <!-- Priority Dropdown -->
                <div class="relative">
                    <button id="priorityFilterBtn"
                        class="h-12 md:h-[50px] px-4 md:px-6 bg-[#AEA3CA] text-text-dark border border-[#D1D1E9] rounded-xl text-sm md:text-[14px] font-medium flex items-center gap-2 hover:bg-[#9F95C0] transition-colors">
                        <span>Priority</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="priorityDropdown"
                        class="absolute top-full right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 z-50 hidden">
                        <div class="py-2">
                            <button data-priority="all"
                                class="w-full px-4 py-3 text-left text-sm hover:bg-gray-50 transition-colors flex items-center gap-2">
                                <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                                All Priorities
                            </button>
                            <button data-priority="critical"
                                class="w-full px-4 py-3 text-left text-sm hover:bg-gray-50 transition-colors flex items-center gap-2">
                                <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                                Critical
                            </button>
                            <button data-priority="urgent"
                                class="w-full px-4 py-3 text-left text-sm hover:bg-gray-50 transition-colors flex items-center gap-2">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                Urgent
                            </button>
                            <button data-priority="high"
                                class="w-full px-4 py-3 text-left text-sm hover:bg-gray-50 transition-colors flex items-center gap-2">
                                <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                                High
                            </button>
                            <button data-priority="medium"
                                class="w-full px-4 py-3 text-left text-sm hover:bg-gray-50 transition-colors flex items-center gap-2">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                Medium
                            </button>
                            <button data-priority="low"
                                class="w-full px-4 py-3 text-left text-sm hover:bg-gray-50 transition-colors flex items-center gap-2">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                Low
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Title -->
    <div class="mb-4">
        <h2 class="text-lg md:text-[20px] font-medium text-text-dark">Support Ticket Updates</h2>
    </div>

    <!-- Notifications Container -->
    <div
        class="bg-gradient-to-r from-[#3D3C5E] to-[#48466B] rounded-2xl shadow-sm border border-[#AEA3CA] overflow-hidden mb-8">
        <!-- Notifications List -->
        <div class="p-4 md:p-6 space-y-3 md:space-y-4 max-h-[500px] overflow-y-auto custom-scrollbar">
            <!-- Notification 1 - Critical Ticket -->
            <div class="notification-item bg-white/10 rounded-xl p-4 md:p-5 hover:bg-white/15 transition-colors"
                data-priority="critical" data-type="escalation">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-2">
                            <div class="flex items-center gap-2">
                                <h3 class="text-base md:text-lg font-bold text-white">Critical: System Outage for Client
                                    "Alpha Corp"</h3>
                                <div class="unread-indicator">
                                    <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                                </div>
                            </div>
                            <span class="text-xs md:text-sm text-white/60">5 minutes ago</span>
                        </div>
                        <p class="text-sm md:text-[14px] text-white/80 mb-3">Multiple users reporting login failures •
                            SLA violation imminent</p>
                        <div class="flex items-center gap-2">
                            <div class="priority-badge px-3 py-1 bg-red-500/20 rounded-lg">
                                <span class="text-xs font-bold text-red-300">Critical</span>
                            </div>
                            <span class="text-xs text-white/60">Escalated to Engineering</span>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-3">
                        <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-white/60"></i>
                        </div>
                        <button class="text-white/40 hover:text-white/60 transition-colors">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Notification 2 - New Ticket Assignment -->
            <div class="notification-item bg-white/10 rounded-xl p-4 md:p-5 hover:bg-white/15 transition-colors"
                data-priority="high" data-type="ticket">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-2">
                            <div class="flex items-center gap-2">
                                <h3 class="text-base md:text-lg font-bold text-white">New Ticket Assigned: Database
                                    Performance</h3>
                                <div class="unread-indicator">
                                    <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
                                </div>
                            </div>
                            <span class="text-xs md:text-sm text-white/60">25 minutes ago</span>
                        </div>
                        <p class="text-sm md:text-[14px] text-white/80 mb-3">Customer: TechSolutions Inc • Response time
                            exceeded</p>
                        <div class="flex items-center gap-2">
                            <div class="priority-badge px-3 py-1 bg-orange-500/20 rounded-lg">
                                <span class="text-xs font-bold text-orange-300">High Priority</span>
                            </div>
                            <span class="text-xs text-white/60">Assigned to you</span>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-3">
                        <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">
                            <i class="fas fa-user-check text-white/60"></i>
                        </div>
                        <button class="text-white/40 hover:text-white/60 transition-colors">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Notification 3 - Escalation Required -->
            <div class="notification-item bg-white/10 rounded-xl p-4 md:p-5 hover:bg-white/15 transition-colors"
                data-priority="urgent" data-type="escalation">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-2">
                            <div class="flex items-center gap-2">
                                <h3 class="text-base md:text-lg font-bold text-white">Escalation Required: API
                                    Integration Issue</h3>
                                <div class="unread-indicator">
                                    <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                </div>
                            </div>
                            <span class="text-xs md:text-sm text-white/60">1 hour ago</span>
                        </div>
                        <p class="text-sm md:text-[14px] text-white/80 mb-3">Customer: DataFlow Systems • Third-party
                            API timeout</p>
                        <div class="flex items-center gap-2">
                            <div class="priority-badge px-3 py-1 bg-red-500/20 rounded-lg">
                                <span class="text-xs font-bold text-red-300">Urgent</span>
                            </div>
                            <span class="text-xs text-white/60">Requires Senior Engineer</span>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-3">
                        <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">
                            <i class="fas fa-arrow-up text-white/60"></i>
                        </div>
                        <button class="text-white/40 hover:text-white/60 transition-colors">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Notification 4 - Customer Follow-up -->
            <div class="notification-item bg-white/10 rounded-xl p-4 md:p-5 hover:bg-white/15 transition-colors"
                data-priority="medium" data-type="ticket">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-2">
                            <div class="flex items-center gap-2">
                                <h3 class="text-base md:text-lg font-bold text-white">Customer Follow-up Required</h3>
                            </div>
                            <span class="text-xs md:text-sm text-white/60">Today, 10:30</span>
                        </div>
                        <p class="text-sm md:text-[14px] text-white/80 mb-3">Ticket #TS-4582 • CreativeWorks Studio •
                            Solution provided</p>
                        <div class="flex items-center gap-2">
                            <div class="priority-badge px-3 py-1 bg-yellow-500/20 rounded-lg">
                                <span class="text-xs font-bold text-yellow-300">Medium</span>
                            </div>
                            <span class="text-xs text-white/60">Awaiting Confirmation</span>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-3">
                        <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">
                            <i class="fas fa-headset text-white/60"></i>
                        </div>
                        <button class="text-white/40 hover:text-white/60 transition-colors">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Notification 5 - Ticket Update -->
            <div class="notification-item bg-white/10 rounded-xl p-4 md:p-5 hover:bg-white/15 transition-colors"
                data-priority="low" data-type="ticket">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-2">
                            <div class="flex items-center gap-2">
                                <h3 class="text-base md:text-lg font-bold text-white">Ticket Status Updated</h3>
                            </div>
                            <span class="text-xs md:text-sm text-white/60">Yesterday, 18:20</span>
                        </div>
                        <p class="text-sm md:text-[14px] text-white/80 mb-3">Ticket #TS-4578 • User training completed •
                            Ready for closure</p>
                        <div class="flex items-center gap-2">
                            <div class="priority-badge px-3 py-1 bg-blue-500/20 rounded-lg">
                                <span class="text-xs font-bold text-blue-300">Low Priority</span>
                            </div>
                            <span class="text-xs text-white/60">Resolved</span>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-3">
                        <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">
                            <i class="fas fa-clipboard-check text-white/60"></i>
                        </div>
                        <button class="text-white/40 hover:text-white/60 transition-colors">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Notification 6 - SLA Warning -->
            <div class="notification-item bg-white/10 rounded-xl p-4 md:p-5 hover:bg-white/15 transition-colors"
                data-priority="high" data-type="escalation">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-2">
                            <div class="flex items-center gap-2">
                                <h3 class="text-base md:text-lg font-bold text-white">SLA Response Time Warning</h3>
                                <div class="unread-indicator">
                                    <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
                                </div>
                            </div>
                            <span class="text-xs md:text-sm text-white/60">Yesterday, 16:45</span>
                        </div>
                        <p class="text-sm md:text-[14px] text-white/80 mb-3">Ticket #TS-4581 • 1 hour remaining for
                            initial response</p>
                        <div class="flex items-center gap-2">
                            <div class="priority-badge px-3 py-1 bg-orange-500/20 rounded-lg">
                                <span class="text-xs font-bold text-orange-300">High</span>
                            </div>
                            <span class="text-xs text-white/60">SLA Compliance</span>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-3">
                        <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">
                            <i class="fas fa-clock text-white/60"></i>
                        </div>
                        <button class="text-white/40 hover:text-white/60 transition-colors">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="hidden p-8 md:p-12 text-center">
            <div
                class="w-16 h-16 md:w-20 md:h-20 mx-auto mb-4 bg-white/10 rounded-full flex items-center justify-center">
                <i class="fas fa-headset text-white/40 text-2xl md:text-3xl"></i>
            </div>
            <h3 class="text-lg md:text-xl font-semibold text-white mb-2">No Technical Support notifications</h3>
            <p class="text-white/60 max-w-md mx-auto">
                All support tickets are being handled. You'll receive alerts for new tickets, escalations, and SLA
                warnings.
            </p>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
        <div class="bg-gradient-to-r from-secondary to-[#8A84C6] rounded-2xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-white/80 text-sm mb-1">Active Tickets</div>
                    <div class="text-3xl font-bold">28</div>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-ticket-alt text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-gray-600 text-sm mb-1">Escalations</div>
                    <div class="text-3xl font-bold text-gray-800">5</div>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-gray-600 text-sm mb-1">Avg. Response Time</div>
                    <div class="text-3xl font-bold text-gray-800">23m</div>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-stopwatch text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- SLA Performance Overview -->
    <div class="mt-8 bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-chart-line text-secondary"></i>
            SLA Performance
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-green-50 rounded-xl p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-700 text-sm font-medium">Within SLA</span>
                    <span class="text-green-600 font-bold">92%</span>
                </div>
                <div class="h-2 bg-green-200 rounded-full overflow-hidden">
                    <div class="h-full bg-green-500 rounded-full w-[92%]"></div>
                </div>
            </div>

            <div class="bg-yellow-50 rounded-xl p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-700 text-sm font-medium">Warning (1h)</span>
                    <span class="text-yellow-600 font-bold">5%</span>
                </div>
                <div class="h-2 bg-yellow-200 rounded-full overflow-hidden">
                    <div class="h-full bg-yellow-500 rounded-full w-[5%]"></div>
                </div>
            </div>

            <div class="bg-orange-50 rounded-xl p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-700 text-sm font-medium">At Risk (30m)</span>
                    <span class="text-orange-600 font-bold">2%</span>
                </div>
                <div class="h-2 bg-orange-200 rounded-full overflow-hidden">
                    <div class="h-full bg-orange-500 rounded-full w-[2%]"></div>
                </div>
            </div>

            <div class="bg-red-50 rounded-xl p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-700 text-sm font-medium">Breached</span>
                    <span class="text-red-600 font-bold">1%</span>
                </div>
                <div class="h-2 bg-red-200 rounded-full overflow-hidden">
                    <div class="h-full bg-red-500 rounded-full w-[1%]"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS and JS remain the same as IT Support -->
<style>
    /* Copy all CSS styles from IT Support */
    @keyframes slideIn {
        from {
            transform: translateY(10px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .animate-slide-in {
        animation: slideIn 0.3s ease-out;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.6;
        }
    }

    .unread-indicator {
        animation: pulse 2s ease-in-out infinite;
    }

    .priority-badge {
        transition: all 0.2s ease;
    }

    .priority-badge:hover {
        transform: scale(1.05);
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 3px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 3px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.4);
    }

    @keyframes criticalPulse {

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
        }

        70% {
            box-shadow: 0 0 0 6px rgba(239, 68, 68, 0);
        }
    }

    .animate-pulse {
        animation: criticalPulse 2s infinite;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Mark all as read button
        const markAllReadBtn = document.querySelector('button[type="submit"]');
        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', function (e) {
                e.preventDefault();

                // Update UI immediately for better UX
                document.querySelectorAll('.unread-indicator').forEach(indicator => {
                    indicator.style.animation = 'none';
                    const dot = indicator.querySelector('.w-2.h-2');
                    if (dot) {
                        dot.style.opacity = '0.5';
                    }
                });

                // Change button text and style temporarily
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
                this.disabled = true;

                // Simulate API call
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-check mr-2"></i> All Marked as Read';
                    this.classList.remove('bg-white', 'text-secondary', 'border-secondary');
                    this.classList.add('bg-green-500', 'text-white', 'border-green-500');

                    // Show success toast
                    showToast('All Technical Support notifications marked as read', 'success');
                }, 1000);
            });
        }

        // Notification click to mark as read
        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function (e) {
                // Don't trigger if clicking the ellipsis button or priority badge
                if (e.target.closest('button') ||
                    e.target.closest('.fas.fa-ellipsis-v') ||
                    e.target.closest('.priority-badge')) {
                    return;
                }

                const indicator = this.querySelector('.unread-indicator');
                if (indicator) {
                    indicator.style.animation = 'none';
                    const dot = indicator.querySelector('.w-2.h-2');
                    if (dot) {
                        dot.style.opacity = '0.5';
                    }

                    // Update escalations count
                    const escalationElement = document.querySelector('.text-3xl.font-bold.text-gray-800');
                    if (escalationElement && escalationElement.textContent === '5') {
                        const type = this.getAttribute('data-type');
                        if (type === 'escalation') {
                            escalationElement.textContent = '4';
                        }
                        showToast('Notification marked as read', 'info');
                    }
                }
            });
        });

        // Priority dropdown toggle
        const priorityBtn = document.getElementById('priorityFilterBtn');
        const priorityDropdown = document.getElementById('priorityDropdown');

        if (priorityBtn && priorityDropdown) {
            priorityBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                priorityDropdown.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function (e) {
                if (!priorityBtn.contains(e.target) && !priorityDropdown.contains(e.target)) {
                    priorityDropdown.classList.add('hidden');
                }
            });

            // Handle priority selection
            priorityDropdown.querySelectorAll('button[data-priority]').forEach(btn => {
                btn.addEventListener('click', function () {
                    const priorityValue = this.getAttribute('data-priority');
                    const priorityText = this.textContent.trim();

                    // Update button text
                    priorityBtn.querySelector('span').textContent = 'Priority: ' + priorityText.split(' ')[0];

                    // Filter notifications based on priority
                    filterNotificationsByPriority(priorityValue);

                    priorityDropdown.classList.add('hidden');
                });
            });
        }

        // Filter by type buttons
        document.querySelectorAll('button[data-filter]').forEach(btn => {
            btn.addEventListener('click', function () {
                const filterType = this.getAttribute('data-filter');

                // Update active state
                document.querySelectorAll('button[data-filter]').forEach(b => {
                    b.classList.remove('bg-secondary', 'text-white', 'border-secondary');
                    b.classList.add('bg-[#AEA3CA]', 'text-text-dark', 'border-[#D1D1E9]');
                });

                this.classList.remove('bg-[#AEA3CA]', 'text-text-dark', 'border-[#D1D1E9]');
                this.classList.add('bg-secondary', 'text-white', 'border-secondary');

                // Filter notifications
                if (filterType === 'all') {
                    document.querySelectorAll('.notification-item').forEach(item => {
                        item.style.display = 'flex';
                    });
                } else {
                    document.querySelectorAll('.notification-item').forEach(item => {
                        const itemType = item.getAttribute('data-type');
                        if (itemType === filterType) {
                            item.style.display = 'flex';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                }

                // Show/hide empty state
                checkEmptyState();
            });
        });

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const searchTerm = this.value.toLowerCase().trim();
                    filterNotificationsBySearch(searchTerm);
                    checkEmptyState();
                }, 300);
            });
        }

        // Function to filter notifications by priority
        function filterNotificationsByPriority(priorityValue) {
            if (priorityValue === 'all') {
                document.querySelectorAll('.notification-item').forEach(item => {
                    item.style.display = 'flex';
                });
            } else {
                document.querySelectorAll('.notification-item').forEach(item => {
                    const itemPriority = item.getAttribute('data-priority');
                    if (itemPriority === priorityValue) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }

            // Show/hide empty state
            checkEmptyState();
        }

        // Function to filter notifications by search term
        function filterNotificationsBySearch(searchTerm) {
            const notifications = document.querySelectorAll('.notification-item');

            notifications.forEach(notification => {
                const title = notification.querySelector('h3')?.textContent.toLowerCase() || '';
                const content = notification.querySelector('.text-white\\/80')?.textContent.toLowerCase() || '';
                const time = notification.querySelector('.text-white\\/60')?.textContent.toLowerCase() || '';
                const priority = notification.querySelector('.priority-badge span')?.textContent.toLowerCase() || '';
                const status = notification.querySelector('.text-white\\/60.text-xs:last-child')?.textContent.toLowerCase() || '';

                if (searchTerm === '' ||
                    title.includes(searchTerm) ||
                    content.includes(searchTerm) ||
                    time.includes(searchTerm) ||
                    priority.includes(searchTerm) ||
                    status.includes(searchTerm)) {
                    notification.style.display = 'flex';
                } else {
                    notification.style.display = 'none';
                }
            });
        }

        // Function to check if empty state should be shown
        function checkEmptyState() {
            const visibleNotifications = Array.from(document.querySelectorAll('.notification-item'))
                .filter(item => item.style.display !== 'none').length;

            const emptyState = document.getElementById('emptyState');
            const notificationsContainer = document.querySelector('.space-y-3');

            if (visibleNotifications === 0) {
                emptyState.classList.remove('hidden');
                if (notificationsContainer) {
                    notificationsContainer.classList.add('hidden');
                }
            } else {
                emptyState.classList.add('hidden');
                if (notificationsContainer) {
                    notificationsContainer.classList.remove('hidden');
                }
            }
        }

        // Toast notification function
        function showToast(message, type = 'info') {
            // Remove existing toasts
            document.querySelectorAll('.custom-toast').forEach(toast => toast.remove());

            const toast = document.createElement('div');
            toast.className = `custom-toast fixed top-24 right-4 md:right-6 p-4 rounded-lg shadow-lg z-[1000] max-w-sm animate-slide-in ${type === 'error' ? 'bg-red-500 text-white' :
                    type === 'success' ? 'bg-green-500 text-white' :
                        'bg-blue-500 text-white'
                }`;
            toast.innerHTML = `
                <div class="flex items-center gap-2">
                    <i class="fas ${type === 'error' ? 'fa-exclamation-circle' :
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
    });
</script>
<?= $this->endSection() ?>