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
                <h1 class="text-[34.77px] font-semibold mb-2 text-text-dark">View Tickets</h1>
                <p class="text-[15.45px] font-light text-text-dark">Track and manage all incoming tickets</p>
            </div>
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
                    <?php foreach ($priorities as $priority):
                        $priorityValue = strtolower(str_replace(' ', '-', $priority['priority_name'] ?? ''));
                    ?>
                        <option value="<?= $priorityValue ?>">
                            <?= esc($priority['priority_name'] ?? 'Unknown') ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Department Filter -->
                <select id="departmentFilter"
                    class="h-10 pl-4 pr-4 bg-white rounded-lg border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary">
                    <option value="">All Departments</option>
                    <?php foreach ($departments as $dept):
                        $deptValue = strtolower(str_replace(' ', '-', $dept['department_name'] ?? ''));
                    ?>
                        <option value="<?= $deptValue ?>">
                            <?= esc($dept['department_name'] ?? 'Unknown') ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Status Filter -->
                <select id="statusFilter"
                    class="h-10 pl-4 pr-4 bg-white rounded-lg border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary">
                    <option value="">All Status</option>
                    <?php foreach ($statuses as $status):
                        $statusValue = strtolower(str_replace(' ', '-', $status['status_name'] ?? ''));
                    ?>
                        <option value="<?= $statusValue ?>">
                            <?= esc($status['status_name'] ?? 'Unknown') ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Reset Button -->
                <button id="resetFilters"
                    class="h-10 px-4 bg-secondary text-white rounded-lg hover:bg-[#665C9E] transition-colors font-medium">
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
                <span id="showingCount">Showing 1-<?= min(10, count($recent_tickets)) ?></span> of <span id="totalCount"><?= $ticketStats['total_tickets'] ?? 0 ?></span> tickets
            </div>
        </div>

        <!-- Table Container with horizontal scroll -->
        <div class="overflow-x-auto">
            <!-- Table Header -->
            <div
                class="grid grid-cols-12 gap-4 py-4 px-6 bg-[#E3DAEE] rounded-lg text-sm font-semibold text-text-dark/80 min-w-[1000px]">
                <div class="col-span-1 text-center">No.</div>
                <div class="col-span-1 text-center">Project</div>
                <div class="col-span-1 text-center">Code</div>
                <div class="col-span-2">Title</div>
                <div class="col-span-1 text-center">Priority</div>
                <div class="col-span-1 text-center">Department</div>
                <div class="col-span-2 text-center">Customer</div>
                <div class="col-span-1 text-center">Status</div>
                <div class="col-span-2 text-center">Last Update</div>
            </div>

            <!-- Tickets List -->
            <div id="ticketsList" class="divide-y divide-white/30 min-w-[1000px]">
                <!-- Tickets will be populated here via JavaScript -->
            </div>
        </div>

        <!-- Pagination -->
        <div class="border-t border-white/30 mt-4 pt-4">
            <div class="flex justify-between items-center px-6">
                <div class="text-text-dark/70 text-sm">
                    <span id="paginationInfo">Page 1 of 1</span>
                </div>
                <div class="flex items-center gap-2">
                    <button id="prevPage"
                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/20 hover:bg-secondary/20 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                        <i class="fas fa-chevron-left text-sm"></i>
                    </button>
                    <div id="pageNumbers" class="flex items-center gap-1">
                        <button
                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-secondary text-white">1</button>
                    </div>
                    <button id="nextPage"
                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/20 hover:bg-secondary/20 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
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
            <!-- Delete Button -->
            <button onclick="openDeleteModal()" class="quick-action-btn">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mb-2">
                    <i class="fas fa-trash text-red-600"></i>
                </div>
                <span>Delete Ticket</span>
            </button>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 p-6">
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
                                    <label class="block text-gray-500 text-xs mb-1">Created</label>
                                    <span id="modalTicketCreated" class="text-gray-700 text-sm"></span>
                                </div>
                                <div>
                                    <label class="block text-gray-500 text-xs mb-1">Last Update</label>
                                    <span id="modalTicketUpdated" class="text-gray-700 text-sm"></span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


    </div>
</template>

<!-- Modal for Delete Ticket -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4 hidden">
    <div class="bg-white rounded-2xl w-full max-w-md">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-800">Delete Ticket</h3>
                <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <div class="p-6">
            <div class="mb-6">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h4 class="text-lg font-semibold text-center text-gray-800 mb-2">Delete Selected Ticket?</h4>
                <p class="text-gray-600 text-sm text-center mb-4">
                    This action cannot be undone. All ticket data will be permanently deleted.
                </p>
                <p class="text-gray-700 text-sm text-center" id="selectedTicketInfo"></p>
            </div>

            <div class="flex gap-3">
                <button onclick="closeDeleteModal()"
                    class="flex-1 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Cancel
                </button>
                <button onclick="confirmDelete()"
                    class="flex-1 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Ticket row styling */
    .ticket-row {
        display: grid;
        grid-template-columns: repeat(12, minmax(0, 1fr));
        gap: 1rem;
        padding: 1rem 1.5rem;
        transition: all 0.2s ease;
        align-items: center;
        min-width: 1000px;
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

    /* Responsive adjustments */
    @media (max-width: 1024px) {
        .ticket-row {
            grid-template-columns: repeat(12, minmax(0, 1fr));
            gap: 0.75rem;
            padding: 0.75rem 1rem;
        }

        .ticket-row>div:nth-child(1) {
            grid-column: span 1;
        }

        .ticket-row>div:nth-child(2) {
            grid-column: span 1;
        }

        .ticket-row>div:nth-child(3) {
            grid-column: span 1;
        }

        .ticket-row>div:nth-child(4) {
            grid-column: span 2;
        }

        .ticket-row>div:nth-child(5) {
            grid-column: span 1;
        }

        .ticket-row>div:nth-child(6) {
            grid-column: span 1;
        }

        .ticket-row>div:nth-child(7) {
            grid-column: span 2;
        }

        .ticket-row>div:nth-child(8) {
            grid-column: span 1;
        }

        .ticket-row>div:nth-child(9) {
            grid-column: span 2;
        }
    }

    @media (max-width: 768px) {
        .ticket-row {
            grid-template-columns: 1fr;
            gap: 0.5rem;
            min-width: auto;
        }

        .ticket-row>div {
            grid-column: span 1 !important;
            text-align: left !important;
        }

        .quick-action-btn {
            padding: 12px;
        }
    }

    /* Ticket Detail Modal Styles */
    #ticketDetailModal {
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    /* Form field styles */
    .modal-field {
        margin-bottom: 1.5rem;
    }

    .modal-field label {
        display: block;
        margin-bottom: 0.5rem;
        color: #4b5563;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .modal-field .value {
        padding: 0.75rem;
        background-color: #f9fafb;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
        color: #1f2937;
        font-size: 0.875rem;
        min-height: 2.5rem;
        display: flex;
        align-items: center;
    }

    /* Responsive modal */
    @media (max-width: 768px) {
        #ticketDetailModal .max-w-4xl {
            max-width: 95%;
        }

        #ticketDetailModal .grid-cols-2 {
            grid-template-columns: 1fr;
        }
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // State
        let currentPage = 1;
        let totalPages = 1;
        let totalTickets = <?= $ticketStats['total_tickets'] ?? 0 ?>;
        let currentFilters = {};
        let searchTimeout = null;
        let selectedTicketId = null;
        let selectedTicketNumber = null;
        let selectedTicketTitle = null;
        const limit = 10; // 10 tickets per page

        // Initialize
        init();

        function init() {
            loadTickets();
            setupEventListeners();
        }

        function setupEventListeners() {
            // Search input dengan debounce manual
            $('#ticketSearch').on('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    currentPage = 1;
                    loadTickets();
                }, 500); // 500ms delay
            });

            // Filter changes
            $('#priorityFilter, #departmentFilter, #statusFilter').on('change', function() {
                currentPage = 1;
                loadTickets();
            });

            // Reset filters
            $('#resetFilters').on('click', resetAllFilters);

            // Pagination
            $('#prevPage').on('click', function() {
                changePage(currentPage - 1);
            });

            $('#nextPage').on('click', function() {
                changePage(currentPage + 1);
            });

            // Ticket row click
            $(document).on('click', '.ticket-row', function() {
                const ticketId = $(this).data('ticket-id');
                if (ticketId) {
                    viewTicketDetails(ticketId);
                }
            });
        }

        function loadTickets() {
            // Show loading state
            showLoadingState();

            // Build filters - TAMBAHKAN SORT ORDER untuk menampilkan terbaru di awal
            const filters = {
                search: $('#ticketSearch').val().trim(),
                priority: $('#priorityFilter').val() || '',
                department: $('#departmentFilter').val() || '',
                status: $('#statusFilter').val() || '',
                page: currentPage,
                limit: limit,
                sort_by: 'created_at', // Sort by creation date
                sort_order: 'DESC' // Descending order: newest first
            };

            currentFilters = filters;

            // Make AJAX call
            $.ajax({
                url: '/admin/tickets/ajax',
                type: 'POST',
                data: {
                    action: 'get_tickets_data',
                    ...filters
                },
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        renderTickets(data.tickets);
                        updatePagination(data.pagination);
                        updateShowingCount(data.pagination);
                        updateTotalCount(data.pagination.total);
                    } else {
                        showToast(data.message || 'Failed to load tickets', 'error');
                        renderEmptyState();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading tickets:', error);
                    showToast('Network error. Please try again.', 'error');
                    renderEmptyState();
                }
            });
        }

        function showLoadingState() {
            const $ticketsList = $('#ticketsList');
            $ticketsList.html(`
                <div class="ticket-row">
                    <div class="col-span-12 text-center py-8">
                        <div class="inline-block">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-secondary mx-auto"></div>
                            <p class="text-text-dark/50 text-sm mt-2">Loading tickets...</p>
                        </div>
                    </div>
                </div>
            `);
        }

        function renderTickets(tickets) {
            const $ticketsList = $('#ticketsList');
            $ticketsList.empty();

            if (!tickets || tickets.length === 0) {
                $ticketsList.html(`
                    <div class="ticket-row">
                        <div class="col-span-12 text-center py-8 text-gray-500">
                            No tickets found
                        </div>
                    </div>
                `);
                return;
            }

            // Calculate starting number based on current page
            const startNumber = ((currentPage - 1) * limit) + 1;

            // Sort tickets by last update date (newest first) if not already sorted by backend
            tickets.sort(function(a, b) {
                // Parse the date strings to Date objects
                const dateA = new Date(a.updated || a.created || 0);
                const dateB = new Date(b.updated || b.created || 0);
                return dateB - dateA; // Descending order (newest first)
            });

            tickets.forEach(function(ticket, index) {
                const ticketRow = $(`
                    <div class="ticket-row" data-ticket-id="${ticket.id}">
                        <div class="col-span-1 text-center text-[#475569] font-medium">${startNumber + index}</div>
                        <div class="col-span-1 text-center text-text-dark text-sm">${ticket.project || 'N/A'}</div>
                        <div class="col-span-1 text-center text-text-dark text-sm">${ticket.ticket_number || 'N/A'}</div>
                        <div class="col-span-2">
                            <div class="text-text-dark text-sm font-medium truncate">${ticket.title}</div>
                            <div class="text-text-dark/50 text-xs truncate">${ticket.description}</div>
                        </div>
                        <div class="col-span-1 text-center">
                            <span class="${getPriorityClass(ticket.priority_value)} priority-badge">
                                ${ticket.priority}
                            </span>
                        </div>
                        <div class="col-span-1 text-center text-text-dark text-sm">${ticket.department}</div>
                        <div class="col-span-2 text-center text-text-dark text-sm truncate">${ticket.customer}</div>
                        <div class="col-span-1 text-center">
                            <span class="${getStatusClass(ticket.status_value)} status-badge">
                                ${ticket.status}
                            </span>
                        </div>
                        <div class="col-span-2 text-center text-text-dark text-sm">${ticket.updated}</div>
                    </div>
                `);

                $ticketsList.append(ticketRow);
            });
        }

        function viewTicketDetails(ticketId) {
            $.ajax({
                url: '/admin/tickets/ajax',
                type: 'POST',
                data: {
                    action: 'get_ticket_details',
                    ticket_id: ticketId
                },
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        showTicketDetailsModal(data.ticket);
                    } else {
                        showToast(data.message || 'Failed to load ticket details', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading ticket details:', error);
                    showToast('Network error. Please try again.', 'error');
                }
            });
        }

        function showTicketDetailsModal(ticket) {
            // Buat modal HTML
            const modalHTML = `
        <div id="ticketDetailModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4">
            <div class="bg-white rounded-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-gray-800">Ticket Details</h3>
                        <button onclick="closeTicketModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Left Column: Basic Info -->
                        <div class="space-y-6">
                            <!-- Ticket Number -->
                            <div>
                                <label class="block text-gray-500 text-xs mb-1">Ticket Number</label>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-gray-800 font-medium" id="modalTicketNumber">${ticket.ticket_number || 'N/A'}</p>
                                </div>
                            </div>

                            <!-- Title -->
                            <div>
                                <label class="block text-gray-500 text-xs mb-1">Title</label>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-gray-800 font-medium" id="modalTitle">${ticket.title || 'N/A'}</p>
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-gray-500 text-xs mb-1">Description</label>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-gray-600 text-sm" id="modalDescription">${ticket.description || 'No description provided'}</p>
                                </div>
                            </div>

                            <!-- Project -->
                            <div>
                                <label class="block text-gray-500 text-xs mb-1">Project</label>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-gray-800" id="modalProject">
                                        ${ticket.project || 'Unassigned'} ${ticket.project_code ? `(${ticket.project_code})` : ''}
                                    </p>
                                </div>
                            </div>

                            <!-- Customer -->
                            <div>
                                <label class="block text-gray-500 text-xs mb-1">Customer</label>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-gray-800" id="modalCustomer">${ticket.customer || 'Unknown'}</p>
                                    ${ticket.customer_email ? `<p class="text-gray-500 text-xs mt-1">${ticket.customer_email}</p>` : ''}
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Status & Priority -->
                        <div class="space-y-6">
                            <!-- Department -->
                            <div>
                                <label class="block text-gray-500 text-xs mb-1">Department</label>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <span class="${getPriorityClass(ticket.priority_value)} priority-badge inline-block mb-2">${ticket.department || 'Unassigned'}</span>
                                </div>
                            </div>

                            <!-- Priority -->
                            <div>
                                <label class="block text-gray-500 text-xs mb-1">Priority</label>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <span class="${getPriorityClass(ticket.priority_value)} priority-badge">${ticket.priority || 'Unknown'}</span>
                                </div>
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-gray-500 text-xs mb-1">Status</label>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <span class="${getStatusClass(ticket.status_value)} status-badge">${ticket.status || 'Unknown'}</span>
                                </div>
                            </div>

                            <!-- Last Update -->
                            <div>
                                <label class="block text-gray-500 text-xs mb-1">Last Update</label>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-gray-800" id="modalLastUpdate">${ticket.updated || 'N/A'}</p>
                                    ${ticket.created ? `<p class="text-gray-500 text-xs mt-1">Created: ${ticket.created}</p>` : ''}
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div>
                                <label class="block text-gray-500 text-xs mb-1">Additional Information</label>
                                <div class="bg-gray-50 rounded-lg p-3 space-y-2">
                                    ${ticket.assigned_to ? `<p class="text-gray-700 text-sm"><span class="font-medium">Assigned to:</span> ${ticket.assigned_to}</p>` : ''}
                                    ${ticket.due_date ? `<p class="text-gray-700 text-sm"><span class="font-medium">Due Date:</span> ${ticket.due_date}</p>` : ''}
                                </div>
                            </div>
                        </div>
                    </div>

                   
                    
                            
                        </div>
                    </div>
                </div>

                <div class="p-6 border-t border-gray-200">
                    <button onclick="closeTicketModal()" 
                            class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                        Close
                    </button>
                </div>
            </div>
        </div>
    `;

            // Tambahkan modal ke body
            $('body').append(modalHTML);
            $('body').css('overflow', 'hidden');
        }

        function closeTicketModal() {
            $('#ticketDetailModal').remove();
            $('body').css('overflow', 'auto');
        }

        // Fungsi untuk mengubah status ticket
        function changeTicketStatus(ticketId, status) {
            $.ajax({
                url: '/admin/tickets/ajax',
                type: 'POST',
                data: {
                    action: 'update_ticket_status',
                    ticket_id: ticketId,
                    status: status
                },
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        showToast('Ticket status updated successfully', 'success');
                        loadTickets(); // Reload daftar ticket
                        closeTicketModal();
                    } else {
                        showToast(data.message || 'Failed to update status', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error updating ticket status:', error);
                    showToast('Network error. Please try again.', 'error');
                }
            });
        }

        // Fungsi untuk assign ticket
        function assignTicket(ticketId) {
            // Implementasi assign ticket (bisa menggunakan modal user selection)
            alert(`Assign ticket ${ticketId} functionality would open here`);
        }


        function showTicketDetailsModal(ticket, activity) {
            // Create modal from template
            const template = $('#ticketDetailModalTemplate')[0];
            const modal = document.importNode(template.content, true);

            // Fill modal data
            $(modal).find('#modalTicketTitle').text(ticket.title);
            $(modal).find('#modalTicketDescription').text(ticket.description);
            $(modal).find('#modalTicketDetails').text(ticket.details);
            $(modal).find('#modalTicketStatus').text(ticket.status);
            $(modal).find('#modalTicketStatus').addClass(getStatusClass(ticket.status_value) + ' status-badge');
            $(modal).find('#modalTicketPriority').text(ticket.priority);
            $(modal).find('#modalTicketPriority').addClass(getPriorityClass(ticket.priority_value) + ' priority-badge');
            $(modal).find('#modalTicketDepartment').text(ticket.department);
            $(modal).find('#modalTicketCustomer').text(`${ticket.customer} (${ticket.customer_email})`);
            $(modal).find('#modalTicketAssigned').text(ticket.assigned_to);
            $(modal).find('#modalTicketCreated').text(ticket.created);
            $(modal).find('#modalTicketUpdated').text(ticket.updated);

            // Fill activity log
            const activityLog = $(modal).find('#activityLog');
            if (activity && activity.length > 0) {
                const activityHtml = activity.map(act => `
                    <div class="activity-item">
                        <div class="activity-dot ${act.type}"></div>
                        <div>
                            <p class="text-gray-600 text-sm">${act.user}: ${act.text}</p>
                            <p class="text-gray-400 text-xs">${act.time}</p>
                        </div>
                    </div>
                `).join('');
                activityLog.html(activityHtml);
            } else {
                activityLog.html('<p class="text-gray-500 text-sm">No activity recorded</p>');
            }

            $('body').append(modal);
            $('body').css('overflow', 'hidden');

            // Add event listeners
            $(modal).find('.close-modal').on('click', function() {
                $(this).closest('.fixed').remove();
                $('body').css('overflow', 'auto');
            });
        }

        function resetAllFilters() {
            $('#ticketSearch').val('');
            $('#priorityFilter').val('');
            $('#departmentFilter').val('');
            $('#statusFilter').val('');

            currentPage = 1;
            loadTickets();

            showToast('Filters reset', 'info');
        }

        function changePage(page) {
            if (page < 1 || page > totalPages) return;

            currentPage = page;
            loadTickets();
        }

        function updatePagination(pagination) {
            totalPages = pagination.total_pages;

            if (currentPage > totalPages) {
                currentPage = totalPages;
            }

            // Update pagination info
            $('#paginationInfo').text(`Page ${currentPage} of ${totalPages}`);

            // Update page numbers
            const $pageNumbers = $('#pageNumbers');
            $pageNumbers.empty();

            // Always show first page
            addPageButton(1);

            // Show ellipsis if needed
            if (currentPage > 3) {
                const ellipsis = $('<span class="px-2 text-gray-400">...</span>');
                $pageNumbers.append(ellipsis);
            }

            // Show pages around current page
            const startPage = Math.max(2, currentPage - 1);
            const endPage = Math.min(totalPages - 1, currentPage + 1);

            for (let i = startPage; i <= endPage; i++) {
                addPageButton(i);
            }

            // Show ellipsis if needed
            if (currentPage < totalPages - 2) {
                const ellipsis = $('<span class="px-2 text-gray-400">...</span>');
                $pageNumbers.append(ellipsis);
            }

            // Always show last page if not first
            if (totalPages > 1) {
                addPageButton(totalPages);
            }

            // Update prev/next buttons
            const $prevPage = $('#prevPage');
            const $nextPage = $('#nextPage');

            $prevPage.prop('disabled', currentPage === 1);
            $nextPage.prop('disabled', currentPage === totalPages);
        }

        function addPageButton(page) {
            const button = $(`
                <button class="w-8 h-8 flex items-center justify-center rounded-lg ${currentPage === page ? 'bg-secondary text-white' : 'bg-white/20 hover:bg-secondary/20'} transition-colors">
                    ${page}
                </button>
            `);

            button.on('click', function() {
                changePage(page);
            });

            $('#pageNumbers').append(button);
        }

        function updateShowingCount(pagination) {
            const startIndex = ((currentPage - 1) * limit) + 1;
            const endIndex = Math.min(startIndex + pagination.limit - 1, pagination.total);
            $('#showingCount').text(`Showing ${startIndex}-${endIndex}`);
        }

        function updateTotalCount(count) {
            $('#totalCount').text(count);
        }

        function renderEmptyState() {
            const $ticketsList = $('#ticketsList');
            $ticketsList.html(`
                <div class="ticket-row">
                    <div class="col-span-12 text-center py-8 text-gray-500">
                        No tickets found
                    </div>
                </div>
            `);
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

        function showToast(message, type = 'info') {
            // Remove existing toasts
            $('.custom-toast').remove();

            const toast = $(`
                <div class="custom-toast fixed top-24 right-6 p-4 rounded-lg shadow-lg z-[1000] max-w-sm animate-slideInUp ${type === 'error' ? 'bg-red-500 text-white' :
                    type === 'success' ? 'bg-green-500 text-white' :
                        'bg-blue-500 text-white'}">
                    <div class="flex items-center gap-2">
                        <i class="fas ${type === 'error' ? 'fa-exclamation-circle' :
                        type === 'success' ? 'fa-check-circle' :
                            'fa-info-circle'}"></i>
                        <span class="text-sm">${message}</span>
                    </div>
                </div>
            `);

            $('body').append(toast);

            setTimeout(function() {
                toast.css({
                    'opacity': '0',
                    'transform': 'translateY(-10px)'
                });
                setTimeout(function() {
                    toast.remove();
                }, 300);
            }, 3000);
        }
    });

    function openDeleteModal(ticketId = null, ticketNumber = null, ticketTitle = null) {
        if (ticketId) {
            selectedTicketId = ticketId;
            selectedTicketNumber = ticketNumber || `TKT${ticketId}`;
            selectedTicketTitle = ticketTitle || 'Untitled';

            // Tampilkan info ticket di modal
            $('#selectedTicketInfo').text(`Ticket: ${selectedTicketNumber} - ${selectedTicketTitle}`);
        } else {
            // Jika tidak ada parameter, cari ticket yang di-check
            const checkedRows = $('.ticket-checkbox:checked');
            if (checkedRows.length === 0) {
                showToast('Please select a ticket first', 'error');
                return;
            }

            const ticketRow = checkedRows.closest('.ticket-row');
            selectedTicketId = ticketRow.data('ticket-id');
            selectedTicketNumber = ticketRow.find('.ticket-number').text() || `TKT${selectedTicketId}`;
            selectedTicketTitle = ticketRow.find('.ticket-title').text() || 'Untitled';

            $('#selectedTicketInfo').text(`Ticket: ${selectedTicketNumber} - ${selectedTicketTitle}`);
        }

        $('#deleteModal').removeClass('hidden');
        $('body').css('overflow', 'hidden');
    }

    function closeDeleteModal() {
        $('#deleteModal').addClass('hidden');
        $('body').css('overflow', 'auto');
        selectedTicketId = null;
        selectedTicketNumber = null;
        selectedTicketTitle = null;
    }

    // Fungsi untuk konfirmasi delete
    function confirmDelete() {
        if (!selectedTicketId) {
            showToast('No ticket selected', 'error');
            return;
        }

        // Konfirmasi akhir
        if (!confirm(`Are you sure you want to delete ticket ${selectedTicketNumber}?`)) {
            return;
        }

        // Kirim request delete
        $.ajax({
            url: '/admin/tickets/delete',
            type: 'POST',
            data: {
                ticket_id: selectedTicketId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    showToast('Ticket deleted successfully', 'success');
                    closeDeleteModal();
                    loadTickets(); // Refresh list
                } else {
                    showToast(data.message || 'Failed to delete ticket', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error deleting ticket:', error);
                showToast('Network error. Please try again.', 'error');
            }
        });
    }
</script>
<?= $this->endSection() ?>