<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>View Tickets - NEXUS Admin<?= $this->endSection() ?>

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
<div class="relative z-10">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-[34.77px] font-semibold mb-2 text-text-dark">View Tickets & Projects</h1>
                <p class="text-[15.45px] font-light text-text-dark">Track and manage all incoming support tickets and
                    projects</p>
            </div>
            <button id="viewProjectsBtn"
                class="h-12 px-6 bg-secondary text-white rounded-lg hover:bg-[#665C9E] transition-colors font-medium flex items-center gap-2">
                <i class="fas fa-project-diagram"></i>
                View Projects
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- All Tickets -->
        <div class="dashboard-card stat-card" data-stat-type="total_tickets">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-text-dark/70 text-sm font-medium mb-2">All Tickets</div>
                    <div class="text-text-dark text-2xl font-bold stat-value">
                        <?= esc($ticketStats['total_tickets'] ?? 0) ?>
                    </div>
                </div>
                <div class="w-12 h-12 bg-secondary/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-ticket-alt text-secondary text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Open Tickets -->
        <div class="dashboard-card stat-card" data-stat-type="open_tickets">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-text-dark/70 text-sm font-medium mb-2">Open Tickets</div>
                    <div class="text-text-dark text-2xl font-bold stat-value">
                        <?= esc($ticketStats['open_tickets'] ?? 0) ?>
                    </div>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        <!-- Resolved Tickets -->
        <div class="dashboard-card stat-card" data-stat-type="resolved_tickets">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-text-dark/70 text-sm font-medium mb-2">Resolved Tickets</div>
                    <div class="text-text-dark text-2xl font-bold stat-value">
                        <?= esc($ticketStats['resolved_tickets'] ?? 0) ?>
                    </div>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Closed Tickets -->
        <div class="dashboard-card stat-card" data-stat-type="closed_tickets">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-text-dark/70 text-sm font-medium mb-2">Closed Tickets</div>
                    <div class="text-text-dark text-2xl font-bold stat-value">
                        <?= esc($ticketStats['closed_tickets'] ?? 0) ?>
                    </div>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-times-circle text-gray-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="dashboard-card mb-6">
        <div class="dashboard-card-header">
            <div class="text-text-dark/85 text-base font-medium">Search & Filter Tickets</div>
        </div>

        <div class="p-6">
            <!-- Search Input -->
            <div class="relative mb-4">
                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-text-muted">
                    <i class="fas fa-search"></i>
                </div>
                <input type="text" placeholder="Search by ticket ID, title, or customer..." id="ticketSearch"
                    class="w-full h-12 pl-12 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
            </div>

            <!-- Filter Options -->
            <div class="flex flex-wrap gap-4 items-center">
                <!-- Priority Filter -->
                <select id="priorityFilter"
                    class="h-10 pl-4 pr-4 bg-white rounded-lg border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary">
                    <option value="">All Priority</option>
                    <?php foreach ($priorities as $priority): ?>
                        <option value="<?= strtolower($priority['priority_name'] ?? '') ?>">
                            <?= esc($priority['priority_name'] ?? 'Unknown') ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Department Filter -->
                <select id="departmentFilter"
                    class="h-10 pl-4 pr-4 bg-white rounded-lg border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary">
                    <option value="">All Departments</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?= strtolower(str_replace(' ', '-', $dept['department_name'] ?? '')) ?>">
                            <?= esc($dept['department_name'] ?? 'Unknown') ?>
                        </option>
                    <?php endforeach; ?>
                </select>


                <!-- Status Filter -->
                <select id="statusFilter"
                    class="h-10 pl-4 pr-4 bg-white rounded-lg border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary">
                    <option value="">All Status</option>
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?= strtolower(str_replace(' ', '-', $status['status_name'] ?? '')) ?>">
                            <?= esc($status['status_name'] ?? 'Unknown') ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Reset Button -->
                <button id="resetFilters"
                    class="h-10 px-4 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                    Reset Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="dashboard-card">
        <div class="dashboard-card-header flex justify-between items-center">
            <div class="text-text-dark/85 text-base font-medium">Recent Tickets</div>
            <div class="text-sm text-secondary font-medium">
                <span id="showingCount">Showing 1-6</span> of <span id="totalCount">376</span> tickets
            </div>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto">
            <!-- Table Header -->
            <div
                class="grid grid-cols-12 gap-4 py-4 px-6 bg-[#E3DAEE] rounded-lg text-sm font-semibold text-text-dark/80">
                <div class="col-span-1 text-center">ID</div>
                <div class="col-span-3">Title</div>
                <div class="col-span-2 text-center">Priority</div>
                <div class="col-span-2 text-center">Department</div>
                <div class="col-span-2 text-center">Customer</div>
                <div class="col-span-1 text-center">Status</div>
                <div class="col-span-1 text-center">Last Update</div>
            </div>

            <!-- Tickets List -->
            <div id="ticketsList" class="divide-y divide-white/30">
                <!-- Tickets will be populated here -->
            </div>
        </div>

        <!-- Pagination -->
        <div class="border-t border-white/30 mt-4 pt-4">
            <div class="flex justify-between items-center px-6">
                <div class="text-text-dark/70 text-sm">
                    <span id="paginationInfo">Page 1 of 63</span>
                </div>
                <div class="flex items-center gap-2">
                    <button id="prevPage"
                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/20 hover:bg-secondary/20 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-chevron-left text-sm"></i>
                    </button>
                    <div id="pageNumbers" class="flex items-center gap-1">
                        <button
                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-secondary text-white">1</button>
                        <button
                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/20 hover:bg-secondary/20 transition-colors">2</button>
                        <button
                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/20 hover:bg-secondary/20 transition-colors">3</button>
                        <button
                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/20 hover:bg-secondary/20 transition-colors">4</button>
                    </div>
                    <button id="nextPage"
                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/20 hover:bg-secondary/20 transition-colors">
                        <i class="fas fa-chevron-right text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Card -->
    <div class="dashboard-card mt-6">
        <div class="dashboard-card-header">
            <div class="text-text-dark/85 text-base font-medium">Quick Actions</div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 p-6">
            <!-- Export Tickets -->
            <button id="exportTickets" class="quick-action-btn">
                <i class="fas fa-file-export text-secondary"></i>
                <span>Export Tickets</span>
            </button>

            <!-- Bulk Assign -->
            <button id="bulkAssign" class="quick-action-btn">
                <i class="fas fa-users text-secondary"></i>
                <span>Bulk Assign</span>
            </button>

            <!-- Generate Report -->
            <button id="generateReport" class="quick-action-btn">
                <i class="fas fa-chart-bar text-secondary"></i>
                <span>Generate Report</span>
            </button>

            <!-- View Analytics -->
            <button id="viewAnalytics" class="quick-action-btn">
                <i class="fas fa-chart-line text-secondary"></i>
                <span>View Analytics</span>
            </button>
        </div>
    </div>
</div>

<!-- Modal Templates -->
<template id="ticketDetailModalTemplate">
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4">
        <div class="bg-white rounded-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-800">Ticket Details</h3>
                    <button class="close-modal text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column: Ticket Info -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Title -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-2" id="modalTicketTitle"></h4>
                            <p class="text-gray-600 text-sm" id="modalTicketDescription"></p>
                        </div>

                        <!-- Description -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Detailed Description</h4>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-600 text-sm" id="modalTicketDetails"></p>
                            </div>
                        </div>

                        <!-- Activity Log -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Activity Log</h4>
                            <div class="space-y-3" id="activityLog">
                                <!-- Activity log will be populated here -->
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Ticket Metadata -->
                    <div class="space-y-6">
                        <!-- Status & Priority -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-gray-500 text-xs mb-1">Status</label>
                                    <span id="modalTicketStatus" class="status-badge"></span>
                                </div>
                                <div>
                                    <label class="block text-gray-500 text-xs mb-1">Priority</label>
                                    <span id="modalTicketPriority" class="priority-badge"></span>
                                </div>
                                <div>
                                    <label class="block text-gray-500 text-xs mb-1">Department</label>
                                    <span id="modalTicketDepartment" class="text-gray-700 text-sm"></span>
                                </div>
                                <div>
                                    <label class="block text-gray-500 text-xs mb-1">Customer</label>
                                    <span id="modalTicketCustomer" class="text-gray-700 text-sm"></span>
                                </div>
                                <div>
                                    <label class="block text-gray-500 text-xs mb-1">Assigned To</label>
                                    <span id="modalTicketAssigned" class="text-gray-700 text-sm"></span>
                                </div>
                                <div>
                                    <label class="block text-gray-500 text-xs mb-1">Created</label>
                                    <span id="modalTicketCreated" class="text-gray-700 text-sm"></span>
                                </div>
                                <div>
                                    <label class="block text-gray-500 text-xs mb-1">Last Update</label>
                                    <span id="modalTicketUpdated" class="text-gray-700 text-sm"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="space-y-2">
                            <button
                                class="w-full py-2 bg-secondary text-white rounded-lg hover:bg-[#665C9E] transition-colors text-sm font-medium">
                                <i class="fas fa-edit mr-2"></i>Edit Ticket
                            </button>
                            <button
                                class="w-full py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium">
                                <i class="fas fa-comment mr-2"></i>Add Comment
                            </button>
                            <button
                                class="w-full py-2 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition-colors text-sm font-medium">
                                <i class="fas fa-check mr-2"></i>Mark as Resolved
                            </button>
                            <button
                                class="w-full py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors text-sm font-medium">
                                <i class="fas fa-times mr-2"></i>Close Ticket
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-gray-200">
                <button
                    class="close-modal px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Close
                </button>
            </div>
        </div>
    </div>
</template>

<style>
    /* Custom animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }

    .animate-slideInUp {
        animation: slideInUp 0.3s ease-out;
    }

    /* Ticket row styling */
    .ticket-row {
        display: grid;
        grid-template-columns: repeat(12, minmax(0, 1fr));
        gap: 1rem;
        padding: 1rem 1.5rem;
        transition: all 0.2s ease;
        align-items: center;
    }

    .ticket-row:hover {
        background: rgba(117, 110, 164, 0.05);
        cursor: pointer;
    }

    /* Priority badges */
    .priority-badge {
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
    }

    .priority-urgent {
        background: #E16D7F;
        color: #873134;
    }

    .priority-high {
        background: #FFD2D2;
        color: #991B1B;
    }

    .priority-medium {
        background: #FED7AA;
        color: #9A3412;
    }

    .priority-low {
        background: #C7D2FE;
        color: #3730A3;
    }

    /* Status badges */
    .status-badge {
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
    }

    .status-open {
        background: #ACCBE3;
        color: #3E566B;
    }

    .status-in-progress {
        background: #BBACE3;
        color: #403E6B;
    }

    .status-resolved {
        background: #C4E3AC;
        color: #3E6B57;
    }

    .status-closed {
        background: #E3DDAC;
        color: #6B553E;
    }

    .status-need-info {
        background: #C7C5C8;
        color: #403E6B;
    }

    /* Quick action buttons */
    .quick-action-btn {
        background: white;
        border-radius: 8px;
        padding: 16px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        border: 1px solid #F3F4F6;
    }

    .quick-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-color: #E5E7EB;
    }

    .quick-action-btn i {
        font-size: 20px;
    }

    .quick-action-btn span {
        font-size: 12px;
        color: #6B7280;
        font-weight: 500;
    }

    /* Activity log */
    .activity-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .activity-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-top: 6px;
        flex-shrink: 0;
    }

    .activity-dot.created {
        background: #3B82F6;
    }

    .activity-dot.assigned {
        background: #10B981;
    }

    .activity-dot.updated {
        background: #F59E0B;
    }

    .activity-dot.resolved {
        background: #8B5CF6;
    }

    /* Loading skeleton */
    .skeleton-loader {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
        border-radius: 4px;
    }

    @keyframes loading {
        0% {
            background-position: 200% 0;
        }

        100% {
            background-position: -200% 0;
        }
    }

    /* Responsive adjustments */
    @media (max-width: 1024px) {
        .ticket-row {
            grid-template-columns: repeat(8, minmax(0, 1fr));
            gap: 0.75rem;
            padding: 0.75rem 1rem;
        }

        .ticket-row>div:nth-child(1) {
            grid-column: span 1;
        }

        .ticket-row>div:nth-child(2) {
            grid-column: span 3;
        }

        .ticket-row>div:nth-child(3) {
            grid-column: span 1;
        }

        .ticket-row>div:nth-child(4) {
            grid-column: span 1;
        }

        .ticket-row>div:nth-child(5) {
            grid-column: span 1;
        }

        .ticket-row>div:nth-child(6) {
            grid-column: span 1;
        }

        .ticket-row>div:nth-child(7) {
            grid-column: span 1;
        }
    }

    @media (max-width: 768px) {
        .ticket-row {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }

        .ticket-row>div {
            grid-column: span 1 !important;
            text-align: left !important;
        }

        .quick-action-btn {
            padding: 12px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // DOM Elements
        const ticketSearch = document.getElementById('ticketSearch');
        const priorityFilter = document.getElementById('priorityFilter');
        const departmentFilter = document.getElementById('departmentFilter');
        const statusFilter = document.getElementById('statusFilter');
        const resetFilters = document.getElementById('resetFilters');
        const ticketsList = document.getElementById('ticketsList');
        const showingCount = document.getElementById('showingCount');
        const totalCount = document.getElementById('totalCount');
        const paginationInfo = document.getElementById('paginationInfo');
        const pageNumbers = document.getElementById('pageNumbers');
        const prevPage = document.getElementById('prevPage');
        const nextPage = document.getElementById('nextPage');
        const viewProjectsBtn = document.getElementById('viewProjectsBtn');
        const exportTicketsBtn = document.getElementById('exportTickets');
        const bulkAssignBtn = document.getElementById('bulkAssign');
        const generateReportBtn = document.getElementById('generateReport');
        const viewAnalyticsBtn = document.getElementById('viewAnalytics');

        // State
        let currentPage = 1;
        let totalPages = 1;
        let totalTickets = 0;
        let currentFilters = {};

        // Initialize
        init();

        function init() {
            loadTickets();
            updateStatisticsCards();
            setupEventListeners();
        }

        function setupEventListeners() {
            // Search input
            if (ticketSearch) {
                ticketSearch.addEventListener('input', debounce(() => {
                    currentPage = 1;
                    loadTickets();
                }, 500));
            }

            // Filter changes
            [priorityFilter, departmentFilter, statusFilter].forEach(filter => {
                if (filter) {
                    filter.addEventListener('change', () => {
                        currentPage = 1;
                        loadTickets();
                    });
                }
            });

            // Reset filters
            if (resetFilters) {
                resetFilters.addEventListener('click', resetAllFilters);
            }

            // Pagination
            if (prevPage) {
                prevPage.addEventListener('click', () => changePage(currentPage - 1));
            }

            if (nextPage) {
                nextPage.addEventListener('click', () => changePage(currentPage + 1));
            }

            // Action buttons
            if (viewProjectsBtn) {
                viewProjectsBtn.addEventListener('click', showProjects);
            }

            if (exportTicketsBtn) {
                exportTicketsBtn.addEventListener('click', exportTickets);
            }

            if (bulkAssignBtn) {
                bulkAssignBtn.addEventListener('click', bulkAssignTickets);
            }

            if (generateReportBtn) {
                generateReportBtn.addEventListener('click', generateReport);
            }

            if (viewAnalyticsBtn) {
                viewAnalyticsBtn.addEventListener('click', viewAnalytics);
            }
        }

        async function loadTickets() {
            try {
                showLoading();

                // Build filters
                const filters = {
                    search: ticketSearch ? ticketSearch.value.trim() : '',
                    priority: priorityFilter ? priorityFilter.value : '',
                    department: departmentFilter ? departmentFilter.value : '',
                    status: statusFilter ? statusFilter.value : ''
                };

                currentFilters = filters;

                // Make API call
                const response = await fetch('/admin/tickets/ajax', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new URLSearchParams({
                        action: 'get_tickets_data',
                        ...filters,
                        page: currentPage,
                        limit: 6
                    })
                });

                const data = await response.json();

                if (data.success) {
                    renderTickets(data.tickets);
                    updatePagination(data.pagination);
                    updateShowingCount(data.pagination);
                    updateTotalCount(data.pagination.total);
                } else {
                    showToast(data.message || 'Failed to load tickets', 'error');
                    renderEmptyState();
                }
            } catch (error) {
                console.error('Error loading tickets:', error);
                showToast('Network error. Please try again.', 'error');
                renderEmptyState();
            }
        }

        function renderTickets(tickets) {
            if (!ticketsList) return;

            ticketsList.innerHTML = '';

            if (tickets.length === 0) {
                ticketsList.innerHTML = `
                <div class="py-12 text-center">
                    <i class="fas fa-ticket-alt text-gray-300 text-4xl mb-4"></i>
                    <p class="text-gray-500">No tickets found</p>
                    <p class="text-gray-400 text-sm mt-2">Try adjusting your filters</p>
                </div>
            `;
                return;
            }

            tickets.forEach(ticket => {
                const ticketRow = document.createElement('div');
                ticketRow.className = 'ticket-row';
                ticketRow.dataset.ticketId = ticket.id;

                ticketRow.innerHTML = `
                <div class="col-span-1 text-center text-[#475569] font-medium">#${ticket.id}</div>
                <div class="col-span-3">
                    <div class="text-text-dark text-sm font-medium truncate">${ticket.title}</div>
                    <div class="text-text-dark/50 text-xs truncate">${ticket.description}</div>
                </div>
                <div class="col-span-2 text-center">
                    <span class="${getPriorityClass(ticket.priority_value)} priority-badge">
                        ${ticket.priority}
                    </span>
                </div>
                <div class="col-span-2 text-center text-text-dark text-sm">${ticket.department}</div>
                <div class="col-span-2 text-center text-text-dark text-sm truncate">${ticket.customer}</div>
                <div class="col-span-1 text-center">
                    <span class="${getStatusClass(ticket.status_value)} status-badge">
                        ${ticket.status}
                    </span>
                </div>
                <div class="col-span-1 text-center text-text-dark text-sm">${ticket.updated}</div>
            `;

                // Add click event for viewing ticket details
                ticketRow.addEventListener('click', () => {
                    viewTicketDetails(ticket.id);
                });

                ticketsList.appendChild(ticketRow);
            });
        }

        async function viewTicketDetails(ticketId) {
            try {
                showLoadingOverlay();

                const response = await fetch('/admin/tickets/ajax', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new URLSearchParams({
                        action: 'get_ticket_details',
                        ticket_id: ticketId
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showTicketDetailsModal(data.ticket, data.activity);
                } else {
                    showToast(data.message || 'Failed to load ticket details', 'error');
                }
            } catch (error) {
                console.error('Error loading ticket details:', error);
                showToast('Network error. Please try again.', 'error');
            } finally {
                hideLoadingOverlay();
            }
        }

        function showTicketDetailsModal(ticket, activity) {
            // Create modal from template
            const template = document.getElementById('ticketDetailModalTemplate');
            const modal = document.importNode(template.content, true);

            // Fill modal data
            modal.querySelector('#modalTicketTitle').textContent = ticket.title;
            modal.querySelector('#modalTicketDescription').textContent = ticket.description;
            modal.querySelector('#modalTicketDetails').textContent = ticket.details;
            modal.querySelector('#modalTicketStatus').textContent = ticket.status;
            modal.querySelector('#modalTicketStatus').className = `${getStatusClass(ticket.status_value)} status-badge`;
            modal.querySelector('#modalTicketPriority').textContent = ticket.priority;
            modal.querySelector('#modalTicketPriority').className = `${getPriorityClass(ticket.priority_value)} priority-badge`;
            modal.querySelector('#modalTicketDepartment').textContent = ticket.department;
            modal.querySelector('#modalTicketCustomer').textContent = `${ticket.customer} (${ticket.customer_email})`;
            modal.querySelector('#modalTicketAssigned').textContent = ticket.assigned_to;
            modal.querySelector('#modalTicketCreated').textContent = ticket.created;
            modal.querySelector('#modalTicketUpdated').textContent = ticket.updated;

            // Fill activity log
            const activityLog = modal.querySelector('#activityLog');
            if (activity && activity.length > 0) {
                activityLog.innerHTML = activity.map(act => `
                <div class="activity-item">
                    <div class="activity-dot ${act.type}"></div>
                    <div>
                        <p class="text-gray-600 text-sm">${act.user}: ${act.text}</p>
                        <p class="text-gray-400 text-xs">${act.time}</p>
                    </div>
                </div>
            `).join('');
            } else {
                activityLog.innerHTML = '<p class="text-gray-500 text-sm">No activity recorded</p>';
            }

            document.body.appendChild(modal);
            document.body.style.overflow = 'hidden';

            // Add event listeners
            const closeButtons = modal.querySelectorAll('.close-modal');
            closeButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    document.body.removeChild(btn.closest('.fixed'));
                    document.body.style.overflow = 'auto';
                });
            });
        }

        async function updateStatisticsCards() {
            try {
                const response = await fetch('/admin/tickets/ajax', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new URLSearchParams({
                        action: 'get_ticket_statistics'
                    })
                });

                const data = await response.json();

                if (data.success) {
                    updateStatisticsUI(data.statistics);
                }
            } catch (error) {
                console.error('Error loading statistics:', error);
            }
        }

        function updateStatisticsUI(stats) {
            // Update the statistics cards with real data
            // You'll need to add IDs to your stat cards in the HTML
            document.querySelectorAll('.stat-card').forEach(card => {
                const statType = card.dataset.statType;
                if (stats[statType] !== undefined) {
                    card.querySelector('.stat-value').textContent = stats[statType];
                }
            });
        }

        async function exportTickets() {
            try {
                showToast('Preparing export...', 'info');

                // Build download URL with filters
                const params = new URLSearchParams(currentFilters);
                window.location.href = `/admin/tickets/export?${params.toString()}`;

            } catch (error) {
                console.error('Error exporting tickets:', error);
                showToast('Failed to export tickets', 'error');
            }
        }

        function bulkAssignTickets() {
            showToast('Bulk assign feature coming soon', 'info');
        }

        function generateReport() {
            showToast('Report generation feature coming soon', 'info');
        }

        function viewAnalytics() {
            showToast('Analytics dashboard coming soon', 'info');
        }

        function showProjects() {
            window.location.href = '/admin/projects';
        }

        function resetAllFilters() {
            if (ticketSearch) ticketSearch.value = '';
            if (priorityFilter) priorityFilter.value = '';
            if (departmentFilter) departmentFilter.value = '';
            if (statusFilter) statusFilter.value = '';

            currentPage = 1;
            loadTickets();

            showToast('Filters reset', 'info');
        }

        function changePage(page) {
            if (page < 1 || page > totalPages) return;

            currentPage = page;
            loadTickets();

            // Scroll to top of tickets list
            if (ticketsList) {
                ticketsList.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }

        function updatePagination(pagination) {
            totalPages = pagination.total_pages;

            if (currentPage > totalPages) {
                currentPage = totalPages;
            }

            // Update pagination info
            if (paginationInfo) {
                paginationInfo.textContent = `Page ${currentPage} of ${totalPages}`;
            }

            // Update page numbers
            if (pageNumbers) {
                pageNumbers.innerHTML = '';

                // Always show first page
                addPageButton(1);

                // Show ellipsis if needed
                if (currentPage > 3) {
                    const ellipsis = document.createElement('span');
                    ellipsis.className = 'px-2 text-gray-400';
                    ellipsis.textContent = '...';
                    pageNumbers.appendChild(ellipsis);
                }

                // Show pages around current page
                const startPage = Math.max(2, currentPage - 1);
                const endPage = Math.min(totalPages - 1, currentPage + 1);

                for (let i = startPage; i <= endPage; i++) {
                    addPageButton(i);
                }

                // Show ellipsis if needed
                if (currentPage < totalPages - 2) {
                    const ellipsis = document.createElement('span');
                    ellipsis.className = 'px-2 text-gray-400';
                    ellipsis.textContent = '...';
                    pageNumbers.appendChild(ellipsis);
                }

                // Always show last page if not first
                if (totalPages > 1) {
                    addPageButton(totalPages);
                }
            }

            // Update prev/next buttons
            if (prevPage) {
                prevPage.disabled = currentPage === 1;
            }

            if (nextPage) {
                nextPage.disabled = currentPage === totalPages;
            }
        }

        function addPageButton(page) {
            const button = document.createElement('button');
            button.className = `w-8 h-8 flex items-center justify-center rounded-lg ${currentPage === page ? 'bg-secondary text-white' : 'bg-white/20 hover:bg-secondary/20'} transition-colors`;
            button.textContent = page;
            button.addEventListener('click', () => changePage(page));
            pageNumbers.appendChild(button);
        }

        function updateShowingCount(pagination) {
            const startIndex = (currentPage - 1) * pagination.limit + 1;
            const endIndex = Math.min(startIndex + pagination.limit - 1, pagination.total);

            if (showingCount) {
                showingCount.textContent = `Showing ${startIndex}-${endIndex}`;
            }
        }

        function updateTotalCount(count) {
            if (totalCount) {
                totalCount.textContent = count;
            }
        }

        function renderEmptyState() {
            if (ticketsList) {
                ticketsList.innerHTML = `
                <div class="py-12 text-center">
                    <i class="fas fa-ticket-alt text-gray-300 text-4xl mb-4"></i>
                    <p class="text-gray-500">No tickets found</p>
                    <p class="text-gray-400 text-sm mt-2">Try adjusting your filters</p>
                </div>
            `;
            }
        }

        function showLoading() {
            if (ticketsList) {
                ticketsList.innerHTML = `
                <div class="space-y-4 py-4">
                    ${Array(3).fill().map(() => `
                        <div class="skeleton-loader h-16 rounded-lg"></div>
                    `).join('')}
                </div>
            `;
            }
        }

        function showLoadingOverlay() {
            const overlay = document.createElement('div');
            overlay.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-[2000]';
            overlay.innerHTML = `
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 border-2 border-secondary border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-gray-700">Loading...</span>
                </div>
            </div>
        `;
            overlay.id = 'loading-overlay';
            document.body.appendChild(overlay);
        }

        function hideLoadingOverlay() {
            const overlay = document.getElementById('loading-overlay');
            if (overlay) {
                document.body.removeChild(overlay);
            }
        }

        // Utility functions
        function getPriorityClass(priority) {
            const classes = {
                'urgent': 'priority-urgent',
                'high': 'priority-high',
                'medium': 'priority-medium',
                'low': 'priority-low'
            };
            return classes[priority] || 'priority-medium';
        }

        function getStatusClass(status) {
            const classes = {
                'open': 'status-open',
                'in-progress': 'status-in-progress',
                'resolved': 'status-resolved',
                'closed': 'status-closed',
                'need-info': 'status-need-info',
                'waiting-customer-reply': 'status-need-info'
            };
            return classes[status] || 'status-open';
        }

        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        function showToast(message, type = 'info') {
            // Remove existing toasts
            document.querySelectorAll('.custom-toast').forEach(toast => toast.remove());

            const toast = document.createElement('div');
            toast.className = `custom-toast fixed top-24 right-6 p-4 rounded-lg shadow-lg z-[1000] max-w-sm animate-slideInUp ${type === 'error' ? 'bg-red-500 text-white' :
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
                toast.style.transform = 'translateY(-10px)';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    });
</script>
<?= $this->endSection() ?>