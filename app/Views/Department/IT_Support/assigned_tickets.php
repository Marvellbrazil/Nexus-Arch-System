<?= $this->extend('layouts/it_support_layout') ?>

<?= $this->section('title') ?>Assigned Tickets - IT Support Dashboard<?= $this->endSection() ?>

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
            <div class="flex-1">
                <h1 class="text-2xl md:text-[32px] font-semibold mb-1 md:mb-[5px] text-text-dark">Assigned Tickets</h1>
                <p class="text-sm md:text-[15px] font-light text-[#666]">Review and manage tickets assigned to IT Support department</p>
            </div>

            <!-- Search and Stats -->
            <div class="flex flex-col sm:flex-row gap-3 md:gap-[15px]">
                <div class="relative">
                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                    <input type="text" placeholder="Search tickets..." id="ticketSearch"
                        class="w-full h-10 md:h-[44px] pl-10 pr-4 bg-white border border-gray-300 rounded-xl text-gray-700 text-sm md:text-[14px] focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20">
                </div>

                <!-- Stats Badge -->
                <div class="px-4 py-2 bg-secondary/10 text-secondary rounded-xl flex items-center justify-center gap-2">
                    <i class="fas fa-tasks"></i>
                    <span class="font-medium">
                        <span class="font-bold" id="inProgressCount"><?= $stats['in_progress'] ?? 0 ?></span> In Progress
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6 md:mb-8">
        <div class="bg-white rounded-xl p-3 md:p-5 shadow-sm border border-gray-200">
            <div class="flex items-center gap-2 md:gap-3">
                <div class="w-8 h-8 md:w-12 md:h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-inbox text-blue-600 text-sm md:text-base"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-xs md:text-sm">Total Assigned</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800" id="totalCount"><?= $stats['total_tickets'] ?? 0 ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-3 md:p-5 shadow-sm border border-gray-200">
            <div class="flex items-center gap-2 md:gap-3">
                <div class="w-8 h-8 md:w-12 md:h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-sync-alt text-purple-600 text-sm md:text-base"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-xs md:text-sm">In Progress</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800" id="inProgressStat"><?= $stats['in_progress'] ?? 0 ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-3 md:p-5 shadow-sm border border-gray-200">
            <div class="flex items-center gap-2 md:gap-3">
                <div class="w-8 h-8 md:w-12 md:h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-sm md:text-base"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-xs md:text-sm">Resolved Today</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800"><?= $stats['resolved_today'] ?? 0 ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-3 md:p-5 shadow-sm border border-gray-200">
            <div class="flex items-center gap-2 md:gap-3">
                <div class="w-8 h-8 md:w-12 md:h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-sm md:text-base"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-xs md:text-sm">High Priority</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800" id="highPriorityCount"><?= $stats['high_priority'] ?? 0 ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6 md:mb-8">
        <!-- Table Header -->
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h2 class="text-lg md:text-[20px] font-semibold text-gray-800">IT Support Tickets</h2>

                <!-- Filter & Sort Options -->
                <div class="flex flex-wrap gap-2 md:gap-3">
                    
                    <!-- Sort by -->
                    <div class="flex items-center">
                        <select id="sortBy" class="border border-gray-300 rounded-lg px-3 py-2 text-xs md:text-sm focus:outline-none focus:border-secondary">
                            <option value="updated_desc">Newest First</option>
                            <option value="updated_asc">Oldest First</option>
                            <option value="priority_desc">Priority: High to Low</option>
                            <option value="priority_asc">Priority: Low to High</option>
                            <option value="subject_asc">Subject A-Z</option>
                            <option value="subject_desc">Subject Z-A</option>
                        </select>
                    </div>

                    <!-- Filter Options -->
                    <div class="hidden md:flex gap-2">
                        <select id="filterPriority"
                            class="border border-gray-300 rounded-lg px-3 md:px-4 py-2 text-xs md:text-sm focus:outline-none focus:border-secondary">
                            <option value="all">All Priorities</option>
                            <option value="1">Low</option>
                            <option value="2">Medium</option>
                            <option value="3">High</option>
                            <option value="4">Critical</option>
                        </select>

                        <select id="filterStatus"
                            class="border border-gray-300 rounded-lg px-3 md:px-4 py-2 text-xs md:text-sm focus:outline-none focus:border-secondary">
                            <option value="all">All Status</option>
                            <option value="1">Open</option>
                            <option value="2">In Progress</option>
                            <option value="3">Resolved</option>
                        </select>
                    </div>

                    <!-- Mobile Filter Button -->
                    <button id="mobileFilterBtn"
                        class="md:hidden px-3 py-2 bg-gray-100 text-gray-700 rounded-lg text-xs flex items-center gap-1">
                        <i class="fas fa-filter"></i>
                        Filters
                    </button>
                </div>
            </div>

            <!-- Mobile Filter Dropdown -->
            <div id="mobileFilters" class="mt-3 md:hidden space-y-2 hidden">
                <select id="filterPriorityMobile"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-secondary">
                    <option value="all">All Priorities</option>
                    <option value="1">Low</option>
                    <option value="2">Medium</option>
                    <option value="3">High</option>
                    <option value="4">Critical</option>
                </select>

                <select id="filterStatusMobile"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-secondary">
                    <option value="all">All Status</option>
                    <option value="1">Open</option>
                    <option value="2">In Progress</option>
                    <option value="3">Resolved</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full min-w-max">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700 cursor-pointer sort-header"
                            data-sort="id">
                            <div class="flex items-center gap-1">
                                <span class="hidden sm:inline">Ticket ID</span>
                                <span class="sm:hidden">ID</span>
                                <i class="fas fa-sort text-gray-400 ml-1 text-xs"></i>
                            </div>
                        </th>
                        <th class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700 cursor-pointer sort-header"
                            data-sort="subject">
                            <div class="flex items-center gap-1">
                                <span>Subject</span>
                                <i class="fas fa-sort text-gray-400 ml-1 text-xs"></i>
                            </div>
                        </th>
                        <th class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700 cursor-pointer sort-header hidden md:table-cell"
                            data-sort="customer">
                            <div class="flex items-center gap-1">
                                <span>Customer</span>
                                <i class="fas fa-sort text-gray-400 ml-1 text-xs"></i>
                            </div>
                        </th>
                        <th class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700 cursor-pointer sort-header"
                            data-sort="priority">
                            <div class="flex items-center gap-1">
                                <span class="hidden xs:inline">Priority</span>
                                <span class="xs:hidden">Pri</span>
                                <i class="fas fa-sort text-gray-400 ml-1 text-xs"></i>
                            </div>
                        </th>
                        <th class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700 cursor-pointer sort-header hidden sm:table-cell"
                            data-sort="status">
                            <div class="flex items-center gap-1">
                                <span>Status</span>
                                <i class="fas fa-sort text-gray-400 ml-1 text-xs"></i>
                            </div>
                        </th>
                        <th class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700 cursor-pointer sort-header hidden md:table-cell"
                            data-sort="updated">
                            <div class="flex items-center gap-1">
                                <span>Last Updated</span>
                                <i class="fas fa-sort text-gray-400 ml-1 text-xs"></i>
                            </div>
                        </th>
                        <th class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700">
                            Actions</th>
                    </tr>
                </thead>
                <tbody id="ticketsTable" class="divide-y divide-gray-200">
                    <?php if (!empty($tickets)): ?>
                        <?php foreach ($tickets as $ticket): ?>
                            <tr class="bg-white hover:bg-gray-50 transition-colors ticket-row" 
                                data-id="<?= $ticket['ticket_id'] ?>"
                                data-priority="<?= $ticket['priority_id'] ?>"
                                data-status="<?= $ticket['status_id'] ?>"
                                data-internal-status="<?= $ticket['internal_status'] ?>"
                                data-resolved="<?= !empty($ticket['department_resolved_at']) ? '1' : '0' ?>">
                                <td class="py-3 px-3 md:py-4 md:px-6">
                                    <span class="font-bold text-gray-800 text-sm md:text-base"><?= esc($ticket['ticket_number']) ?></span>
                                </td>
                                <td class="py-3 px-3 md:py-4 md:px-6">
                                    <div>
                                        <p class="font-medium text-gray-800 text-sm truncate max-w-[150px] md:max-w-none"><?= esc($ticket['subject']) ?></p>
                                        <p class="text-gray-500 text-xs mt-1 hidden md:block">Project: <?= esc($ticket['project_name'] ?? 'No Project') ?></p>
                                    </div>
                                </td>
                                <td class="py-3 px-3 md:py-4 md:px-6 hidden md:table-cell">
                                    <span class="text-gray-700 text-sm truncate max-w-[120px]"><?= esc($ticket['customer_name']) ?></span>
                                </td>
                                <td class="py-3 px-3 md:py-4 md:px-6">
                                    <span class="px-2 py-1 text-xs rounded-full <?= $ticket['priorityColor'] ?> font-medium whitespace-nowrap">
                                        <?= esc($ticket['priority_name']) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-3 md:py-4 md:px-6 hidden sm:table-cell">
                                    <span class="px-2 py-1 text-xs rounded-full <?= $ticket['statusColor'] ?> font-medium whitespace-nowrap">
                                        <?= esc($ticket['status_name']) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-3 md:py-4 md:px-6 hidden md:table-cell">
                                    <span class="text-gray-600 text-sm"><?= esc($ticket['time']) ?></span>
                                </td>
                                <td class="py-3 px-3 md:py-4 md:px-6">
                                    <div class="flex items-center gap-2">
                                        <a href="<?= base_url('department/it-support/ticket_detail/') . $ticket['ticket_id'] ?>" 
                                           class="px-3 py-1 md:px-3 md:py-2 bg-blue-100 text-blue-700 text-xs md:text-sm rounded-lg hover:bg-blue-200 transition-colors whitespace-nowrap flex items-center gap-1">
                                            <i class="fas fa-eye text-xs"></i>
                                            <span>View</span>
                                        </a>
                                        <?php if (empty($ticket['department_resolved_at'])): ?>
                                        <a href="<?= base_url('department/it-support/ticket_summary/') . $ticket['ticket_id'] ?>" 
                                           class="px-3 py-1 md:px-3 md:py-2 bg-gray-100 text-gray-700 text-xs md:text-sm rounded-lg hover:bg-gray-200 transition-colors whitespace-nowrap flex items-center gap-1">
                                            <i class="fas fa-file-alt text-xs"></i>
                                            <span>Summary</span>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="py-8 px-4 md:px-6 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-inbox text-2xl md:text-3xl text-gray-300 mb-3"></i>
                                    <p class="text-base md:text-lg font-medium text-gray-400 mb-1">No tickets found</p>
                                    <p class="text-xs md:text-sm text-gray-500">You don't have any assigned tickets yet</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 md:p-6 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="text-gray-600 text-xs md:text-sm">
                    Showing <span id="showingCount"><?= !empty($tickets) ? count($tickets) : 0 ?></span> of <span id="totalFilteredCount"><?= !empty($tickets) ? count($tickets) : 0 ?></span> entries
                </div>
                <div class="flex items-center gap-1 md:gap-2">
                    <button id="prevPage"
                        class="px-2 md:px-3 py-1 md:py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed text-xs"
                        disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <span class="px-2 md:px-3 py-1 md:py-2 text-gray-600 text-xs md:text-sm">Page</span>
                    <button id="nextPage"
                        class="px-2 md:px-3 py-1 md:py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 text-xs md:text-sm">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Help Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
        <!-- Processing Guidelines -->
        <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3 md:gap-[15px] mb-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-server text-blue-600 text-lg md:text-xl"></i>
                </div>
                <h3 class="text-lg md:text-[20px] font-semibold text-gray-800">IT Support Guidelines</h3>
            </div>
            <p class="text-gray-600 text-sm md:text-base mb-4 md:mb-6">
                As an IT Support specialist, your role is to handle infrastructure and system-related issues. Follow
                these best practices:
            </p>
            <ol class="text-gray-600 text-sm md:text-base space-y-2 list-decimal pl-5">
                <li>Check server logs and system metrics first</li>
                <li>Verify network connectivity and firewall rules</li>
                <li>Test database connections and performance</li>
                <li>Document all troubleshooting steps</li>
                <li>Update ticket status regularly</li>
                <li>Escalate to Development team if code-level issue</li>
            </ol>
        </div>

        <!-- Quick Actions Card -->
        <div class="bg-gradient-to-r from-secondary to-[#8A84C6] rounded-xl p-4 md:p-6 text-white">
            <h3 class="text-lg md:text-[20px] font-semibold mb-3 md:mb-4">Quick Actions</h3>
            <p class="text-white/80 text-sm md:text-base mb-4 md:mb-6">
                Common tasks for IT Support team
            </p>

            <div class="flex flex-col sm:flex-row gap-3">
                <a href="<?= base_url('department/it-support/dashboard') ?>"
                    class="px-4 md:px-6 py-2 md:py-3 bg-white text-secondary rounded-lg hover:bg-gray-100 transition-colors font-medium text-sm md:text-base flex items-center justify-center gap-2">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <button onclick="assignToMe()"
                    class="px-4 md:px-6 py-2 md:py-3 bg-white/20 text-white rounded-lg hover:bg-white/30 transition-colors font-medium text-sm md:text-base flex items-center justify-center gap-2">
                    <i class="fas fa-user-plus"></i>
                    <span>Assign to Me</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Ticket data from PHP
    const ticketsData = <?= json_encode($tickets) ?>;
    let currentFilter = 'active'; // Default filter
    let currentSort = { column: 'updated', direction: 'desc' };
    let currentFilters = {
        priority: 'all',
        status: 'all'
    };

    // Initialize
    document.addEventListener('DOMContentLoaded', function () {
        // Set initial counts
        updateCounts(ticketsData);
        
        // Filter tabs
        document.querySelectorAll('.tab-filter').forEach(tab => {
            tab.addEventListener('click', function() {
                // Update active tab
                document.querySelectorAll('.tab-filter').forEach(t => {
                    t.classList.remove('border-b-2', 'border-secondary', 'text-secondary');
                    t.classList.add('text-gray-500', 'hover:text-gray-700');
                });
                this.classList.add('border-b-2', 'border-secondary', 'text-secondary');
                this.classList.remove('text-gray-500', 'hover:text-gray-700');
                
                // Apply filter
                currentFilter = this.dataset.filter;
                filterAndSortTickets();
            });
        });

        // Search functionality
        const ticketSearch = document.getElementById('ticketSearch');
        ticketSearch.addEventListener('input', (e) => {
            filterAndSortTickets();
        });

        // Sort by dropdown
        document.getElementById('sortBy').addEventListener('change', function () {
            const value = this.value.split('_');
            currentSort.column = value[0];
            currentSort.direction = value[1];
            filterAndSortTickets();
        });

        // Filter dropdowns (Desktop)
        document.getElementById('filterPriority').addEventListener('change', function () {
            currentFilters.priority = this.value;
            filterAndSortTickets();
        });

        document.getElementById('filterStatus').addEventListener('change', function () {
            currentFilters.status = this.value;
            filterAndSortTickets();
        });

        // Mobile filter dropdowns
        document.getElementById('filterPriorityMobile').addEventListener('change', function () {
            currentFilters.priority = this.value;
            filterAndSortTickets();
        });

        document.getElementById('filterStatusMobile').addEventListener('change', function () {
            currentFilters.status = this.value;
            filterAndSortTickets();
        });

        // Mobile filter button
        document.getElementById('mobileFilterBtn').addEventListener('click', function () {
            const filters = document.getElementById('mobileFilters');
            filters.classList.toggle('hidden');
        });

        // Header click sorting
        document.querySelectorAll('.sort-header').forEach(header => {
            header.addEventListener('click', function () {
                const column = this.dataset.sort;
                
                // Toggle direction if same column
                if (currentSort.column === column) {
                    currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
                } else {
                    currentSort.column = column;
                    currentSort.direction = 'asc';
                }
                
                // Sort and render
                filterAndSortTickets();
                
                // Update sort icons
                updateSortIcons();
            });
        });
    });

    function filterAndSortTickets() {
        let filteredTickets = [...ticketsData];

        // Apply tab filter first
        filteredTickets = filteredTickets.filter(ticket => {
            const isResolved = ticket.department_resolved_at !== null;
            const isActive = !isResolved && (ticket.status_id == 1 || ticket.status_id == 2);
            const isUnderReview = isResolved && ['review_needed', 'testing'].includes(ticket.internal_status);
            
            switch(currentFilter) {
                case 'active':
                    return isActive;
                case 'under_review':
                    return isUnderReview;
                case 'all':
                    return true;
                default:
                    return true;
            }
        });

        // Apply search filter
        const searchTerm = document.getElementById('ticketSearch').value.toLowerCase();
        if (searchTerm) {
            filteredTickets = filteredTickets.filter(ticket =>
                ticket.subject.toLowerCase().includes(searchTerm) ||
                ticket.ticket_number.toLowerCase().includes(searchTerm) ||
                ticket.customer_name.toLowerCase().includes(searchTerm) ||
                (ticket.project_name && ticket.project_name.toLowerCase().includes(searchTerm))
            );
        }

        // Apply priority filter
        if (currentFilters.priority !== 'all') {
            filteredTickets = filteredTickets.filter(ticket =>
                ticket.priority_id == currentFilters.priority
            );
        }

        // Apply status filter
        if (currentFilters.status !== 'all') {
            filteredTickets = filteredTickets.filter(ticket =>
                ticket.status_id == currentFilters.status
            );
        }

        // Sort tickets
        filteredTickets.sort((a, b) => {
            let aValue, bValue;

            switch (currentSort.column) {
                case 'id':
                    aValue = a.ticket_id;
                    bValue = b.ticket_id;
                    break;
                case 'subject':
                    aValue = a.subject.toLowerCase();
                    bValue = b.subject.toLowerCase();
                    break;
                case 'customer':
                    aValue = a.customer_name.toLowerCase();
                    bValue = b.customer_name.toLowerCase();
                    break;
                case 'priority':
                    aValue = a.priority_id;
                    bValue = b.priority_id;
                    break;
                case 'status':
                    aValue = a.status_name.toLowerCase();
                    bValue = b.status_name.toLowerCase();
                    break;
                case 'updated':
                    aValue = a.timestamp;
                    bValue = b.timestamp;
                    break;
                default:
                    aValue = a.timestamp;
                    bValue = b.timestamp;
            }

            if (currentSort.direction === 'asc') {
                return aValue > bValue ? 1 : -1;
            } else {
                return aValue < bValue ? 1 : -1;
            }
        });

        // Render filtered and sorted tickets
        renderTable(filteredTickets);
        
        // Update counts
        updateCounts(filteredTickets);
    }

    function renderTable(tickets) {
        const tbody = document.getElementById('ticketsTable');
        tbody.innerHTML = '';

        if (tickets.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="py-8 px-4 md:px-6 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-inbox text-2xl md:text-3xl text-gray-300 mb-3"></i>
                            <p class="text-base md:text-lg font-medium text-gray-400 mb-1">No tickets found</p>
                            <p class="text-xs md:text-sm text-gray-500">Try adjusting your search or filters</p>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        tickets.forEach((ticket, index) => {
            const rowClass = index % 2 === 0 ? 'bg-white' : 'bg-gray-50';
            const row = document.createElement('tr');
            row.className = `${rowClass} hover:bg-gray-50 transition-colors ticket-row`;
            row.setAttribute('data-id', ticket.ticket_id);
            row.setAttribute('data-priority', ticket.priority_id);
            row.setAttribute('data-status', ticket.status_id);
            row.setAttribute('data-internal-status', ticket.internal_status || '');
            row.setAttribute('data-resolved', ticket.department_resolved_at ? '1' : '0');
            
            row.innerHTML = `
                <td class="py-3 px-3 md:py-4 md:px-6">
                    <span class="font-bold text-gray-800 text-sm md:text-base">${ticket.ticket_number}</span>
                </td>
                <td class="py-3 px-3 md:py-4 md:px-6">
                    <div>
                        <p class="font-medium text-gray-800 text-sm truncate max-w-[150px] md:max-w-none">${ticket.subject}</p>
                        <p class="text-gray-500 text-xs mt-1 hidden md:block">Project: ${ticket.project_name || 'No Project'}</p>
                    </div>
                </td>
                <td class="py-3 px-3 md:py-4 md:px-6 hidden md:table-cell">
                    <span class="text-gray-700 text-sm truncate max-w-[120px]">${ticket.customer_name}</span>
                </td>
                <td class="py-3 px-3 md:py-4 md:px-6">
                    <span class="px-2 py-1 text-xs rounded-full ${ticket.priorityColor} font-medium whitespace-nowrap">
                        ${ticket.priority_name}
                    </span>
                </td>
                <td class="py-3 px-3 md:py-4 md:px-6 hidden sm:table-cell">
                    <span class="px-2 py-1 text-xs rounded-full ${ticket.statusColor} font-medium whitespace-nowrap">
                        ${ticket.status_name}
                    </span>
                </td>
                <td class="py-3 px-3 md:py-4 md:px-6 hidden md:table-cell">
                    <span class="text-gray-600 text-sm">${ticket.time}</span>
                </td>
                <td class="py-3 px-3 md:py-4 md:px-6">
                    <div class="flex items-center gap-2">
                        <a href="<?= base_url('department/it-support/ticket_detail/') ?>${ticket.ticket_id}" 
                           class="px-3 py-1 md:px-3 md:py-2 bg-blue-100 text-blue-700 text-xs md:text-sm rounded-lg hover:bg-blue-200 transition-colors whitespace-nowrap flex items-center gap-1">
                            <i class="fas fa-eye text-xs"></i>
                            <span>View</span>
                        </a>
                        ${!ticket.department_resolved_at ? `
                        <a href="<?= base_url('department/it-support/ticket_summary/') ?>${ticket.ticket_id}" 
                           class="px-3 py-1 md:px-3 md:py-2 bg-gray-100 text-gray-700 text-xs md:text-sm rounded-lg hover:bg-gray-200 transition-colors whitespace-nowrap flex items-center gap-1">
                            <i class="fas fa-file-alt text-xs"></i>
                            <span>Summary</span>
                        </a>
                        ` : ''}
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    function updateCounts(filteredTickets) {
        // Update showing count
        document.getElementById('showingCount').textContent = filteredTickets.length;
        document.getElementById('totalFilteredCount').textContent = filteredTickets.length;
        
        // Calculate counts for current filter
        const activeCount = filteredTickets.filter(t => 
            !t.department_resolved_at && (t.status_id == 1 || t.status_id == 2)
        ).length;
        
        const underReviewCount = filteredTickets.filter(t => 
            t.department_resolved_at && ['review_needed', 'testing'].includes(t.internal_status)
        ).length;
        
        const highPriorityCount = filteredTickets.filter(t => t.priority_id >= 3).length;
        const inProgressCount = filteredTickets.filter(t => t.status_id == 2 && !t.department_resolved_at).length;
        
        // Update UI counts
        document.getElementById('totalCount').textContent = filteredTickets.length;
        document.getElementById('inProgressCount').textContent = inProgressCount;
        document.getElementById('inProgressStat').textContent = inProgressCount;
        document.getElementById('highPriorityCount').textContent = highPriorityCount;
        
        // Update tab counts
        document.getElementById('activeCount').textContent = activeCount;
        document.getElementById('underReviewCount').textContent = underReviewCount;
        document.getElementById('allCount').textContent = filteredTickets.length;
    }

    function updateSortIcons() {
        // Reset all icons
        document.querySelectorAll('.sort-header i').forEach(icon => {
            icon.className = 'fas fa-sort text-gray-400 ml-1 text-xs';
        });

        // Set active sort icon
        const activeHeader = document.querySelector(`.sort-header[data-sort="${currentSort.column}"] i`);
        if (activeHeader) {
            activeHeader.className = `fas fa-sort-${currentSort.direction === 'asc' ? 'up' : 'down'} text-secondary ml-1 text-xs`;
        }
    }

    // 🔥 PERUBAHAN: Update function handleSendMessage untuk auto-update status
function handleSendMessage() {
    const messageInput = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendMessageBtn');
    
    if (!messageInput || !sendBtn) return;
    
    const message = messageInput.value.trim();
    
    if (!message) {
        showToast('Please enter a message', 'error');
        return;
    }
    
    const originalText = sendBtn.innerHTML;
    sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
    sendBtn.disabled = true;
    
    // Kirim ke endpoint department chat (internal)
    fetch(`<?= base_url('department/chat/send/') ?>${ticketId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `message=${encodeURIComponent(message)}`
    })
    .then(response => response.json())
    .then(data => {
        console.log('Send message response:', data);
        
        if (data.success) {
            messageInput.value = '';
            messageInput.style.height = 'auto';
            
            // Tambahkan message user ke UI
            if (data.data) {
                // Tandai sebagai current user
                data.data.is_current_user = true;
                addInternalMessageToUI(data.data, true);
                lastMessageId = data.data.message_id;
            }
            
            // 🔥 PERUBAHAN: Jika ini response pertama, update UI status
            if (data.is_first_department_response && data.status_updated) {
                // Update status badge di header
                updateTicketStatusInUI('In Progress');
                
                // Tambahkan system message ke chat
                addSystemMessageToChat('Ticket status updated to In Progress');
                
                // Update status di table assigned tickets (jika ada di halaman yang sama)
                updateTicketStatusInAssignedTickets(ticketId, 2); // Status ID 2 = In Progress
                
                showToast('First response sent! Ticket status updated to In Progress', 'success');
            } else if (data.is_first_department_response) {
                showToast('First response sent!', 'success');
            } else {
                showToast('Message sent successfully', 'success');
            }
            
            scrollToBottom();
        } else {
            showToast(data.message || 'Failed to send message', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Network error. Please try again.', 'error');
    })
    .finally(() => {
        sendBtn.innerHTML = originalText;
        sendBtn.disabled = false;
    });
}

// 🔥 FUNCTION BARU: Update status ticket di UI
function updateTicketStatusInUI(statusName) {
    console.log('Updating UI status to:', statusName);
    
    // Update status badge di Ticket Info Bar
    const infoBar = document.querySelector('.bg-gradient-to-r.from-secondary');
    if (infoBar) {
        const statusElements = infoBar.querySelectorAll('div.text-white\\/80');
        statusElements.forEach(element => {
            if (element.textContent.includes('Status') && element.nextElementSibling) {
                const statusBadge = element.nextElementSibling;
                statusBadge.textContent = statusName.toUpperCase();
                
                // Update warna berdasarkan status
                if (statusName === 'In Progress') {
                    statusBadge.className = 'px-3 py-1 bg-purple-500/20 rounded-full text-sm font-semibold inline-block';
                }
            }
        });
    }
    
    // Update button "Update Status"
    const updateStatusBtn = document.getElementById('updateStatusBtn');
    if (updateStatusBtn) {
        if (statusName === 'In Progress') {
            updateStatusBtn.innerHTML = `
                <i class="fas fa-sync-alt"></i>
                <span>In Progress</span>
            `;
            updateStatusBtn.classList.remove('bg-secondary', 'hover:bg-[#817CB2]');
            updateStatusBtn.classList.add('bg-purple-500', 'hover:bg-purple-600');
        }
    }
}

// 🔥 FUNCTION BARU: Tambah system message ke chat
function addSystemMessageToChat(message) {
    const container = document.getElementById('conversationContainer');
    if (!container) return;
    
    const messageDiv = document.createElement('div');
    messageDiv.className = 'flex justify-center my-4';
    messageDiv.innerHTML = `
        <div class="bg-purple-50 border border-purple-200 rounded-lg px-4 py-3 max-w-md text-center">
            <div class="flex items-center justify-center gap-2">
                <i class="fas fa-bullhorn text-purple-600"></i>
                <span class="text-purple-800 font-medium text-sm">${escapeHtml(message)}</span>
            </div>
        </div>
    `;
    container.appendChild(messageDiv);
    scrollToBottom();
}

// 🔥 FUNCTION BARU: Update status di assigned tickets table
function updateTicketStatusInAssignedTickets(ticketId, newStatusId) {
    // Cari row ticket di assigned tickets table
    const ticketRow = document.querySelector(`.ticket-row[data-id="${ticketId}"]`);
    if (ticketRow) {
        // Update data attribute
        ticketRow.setAttribute('data-status', newStatusId);
        
        // Update status badge di table
        const statusBadge = ticketRow.querySelector('td:nth-child(5) span'); // Status column
        if (statusBadge) {
            if (newStatusId == 2) { // In Progress
                statusBadge.textContent = 'In Progress';
                statusBadge.className = 'px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800 border border-purple-200 font-medium whitespace-nowrap';
            }
        }
        
        // Update counter di header
        updateInProgressCount();
    }
}

// 🔥 FUNCTION BARU: Update In Progress counter
function updateInProgressCount() {
    const inProgressTickets = document.querySelectorAll('.ticket-row[data-status="2"]').length;
    
    // Update counter di header
    const inProgressCount = document.getElementById('inProgressCount');
    const inProgressStat = document.getElementById('inProgressStat');
    
    if (inProgressCount) inProgressCount.textContent = inProgressTickets;
    if (inProgressStat) inProgressStat.textContent = inProgressTickets;
}

    // Assign ticket to me function
    function assignToMe() {
        // Find first high priority active ticket
        const highPriorityTicket = ticketsData.find(ticket => 
            ticket.priority_id >= 3 && 
            !ticket.department_resolved_at && 
            (ticket.status_id == 1 || ticket.status_id == 2)
        );
        
        if (highPriorityTicket) {
            window.location.href = `<?= base_url('department/it-support/ticket_detail/') ?>${highPriorityTicket.ticket_id}`;
        } else {
            // Find any active ticket
            const activeTicket = ticketsData.find(ticket => 
                !ticket.department_resolved_at && 
                (ticket.status_id == 1 || ticket.status_id == 2)
            );
            
            if (activeTicket) {
                window.location.href = `<?= base_url('department/it-support/ticket_detail/') ?>${activeTicket.ticket_id}`;
            } else {
                showToast('No active tickets available to assign', 'info');
            }
        }
    }

    // Toast notification function
    function showToast(message, type = 'info') {
        // Remove existing toasts
        document.querySelectorAll('.toast-notification').forEach(toast => toast.remove());

        const toast = document.createElement('div');
        toast.className = `toast-notification fixed top-24 right-4 p-4 rounded-lg shadow-lg z-50 ${type === 'error' ? 'bg-red-500 text-white' :
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
</script>

<style>
    /* Tab styles */
    .tab-filter {
        transition: all 0.2s ease;
    }
    
    .tab-filter:hover {
        color: #6B7280;
    }
    
    /* Sort header hover */
    .sort-header:hover {
        background-color: #f9fafb;
    }
    
    /* Toast animation */
    .toast-notification {
        transition: all 0.3s ease;
        animation: slideIn 0.3s ease;
    }
    
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>
<?= $this->endSection() ?>