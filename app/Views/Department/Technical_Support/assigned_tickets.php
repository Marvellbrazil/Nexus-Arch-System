<?= $this->extend('layouts/technical_support_layout') ?>

<?= $this->section('title') ?>Assigned Technical Issues - Technical Support Dashboard<?= $this->endSection() ?>

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
                <h1 class="text-2xl md:text-[32px] font-semibold mb-1 md:mb-[5px] text-text-dark">Assigned Technical
                    Issues</h1>
                <p class="text-sm md:text-[15px] font-light text-[#666]">Handle hardware, server, and infrastructure
                    technical problems</p>
            </div>

            <!-- Search and Stats -->
            <div class="flex flex-col sm:flex-row gap-3 md:gap-[15px]">
                <div class="relative">
                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                    <input type="text" placeholder="Search technical issues..."
                        class="w-full h-10 md:h-[44px] pl-10 pr-4 bg-white border border-gray-300 rounded-xl text-gray-700 text-sm md:text-[14px] focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20"
                        id="ticketSearch">
                </div>

                <!-- Stats Badge -->
                <div class="px-4 py-2 bg-secondary/10 text-secondary rounded-xl flex items-center justify-center gap-2">
                    <i class="fas fa-tools"></i>
                    <span class="font-medium">
                        <span class="font-bold">6</span> Critical
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
                    <i class="fas fa-server text-blue-600 text-sm md:text-base"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-xs md:text-sm">Total Assigned</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800">18</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-3 md:p-5 shadow-sm border border-gray-200">
            <div class="flex items-center gap-2 md:gap-3">
                <div class="w-8 h-8 md:w-12 md:h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-wrench text-purple-600 text-sm md:text-base"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-xs md:text-sm">In Repair</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800">8</p>
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
                    <p class="text-xl md:text-2xl font-bold text-gray-800">5</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-3 md:p-5 shadow-sm border border-gray-200">
            <div class="flex items-center gap-2 md:gap-3">
                <div class="w-8 h-8 md:w-12 md:h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-sm md:text-base"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-xs md:text-sm">Emergency</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800">4</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Technical Issues Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6 md:mb-8">
        <!-- Table Header -->
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h2 class="text-lg md:text-[20px] font-semibold text-gray-800">Technical Support Issues</h2>

                <!-- Filter & Sort Options -->
                <div class="flex flex-wrap gap-2 md:gap-3">
                    <!-- Sort by -->
                    <div class="relative w-full md:w-auto">
                        <select id="sortBy"
                            class="w-full md:w-auto border border-gray-300 rounded-lg px-3 md:px-4 py-2 text-xs md:text-sm focus:outline-none focus:border-secondary appearance-none bg-white pr-8">
                            <option value="date-desc">Sort by: Newest First</option>
                            <option value="date-asc">Sort by: Oldest First</option>
                            <option value="priority-desc">Sort by: Priority (High to Low)</option>
                            <option value="priority-asc">Sort by: Priority (Low to High)</option>
                        </select>
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </div>
                    </div>

                    <!-- Filter Options (Hidden on mobile, shown in dropdown) -->
                    <div class="hidden md:flex gap-2">
                        <select id="filterSystem"
                            class="border border-gray-300 rounded-lg px-3 md:px-4 py-2 text-xs md:text-sm focus:outline-none focus:border-secondary">
                            <option value="all">All Systems</option>
                            <option value="server">Servers</option>
                            <option value="network">Network</option>
                            <option value="database">Database</option>
                            <option value="hardware">Hardware</option>
                            <option value="security">Security</option>
                        </select>

                        <select id="filterStatus"
                            class="border border-gray-300 rounded-lg px-3 md:px-4 py-2 text-xs md:text-sm focus:outline-none focus:border-secondary">
                            <option value="all">All Status</option>
                            <option value="emergency">Emergency</option>
                            <option value="in-repair">In Repair</option>
                            <option value="resolved">Resolved</option>
                            <option value="pending-parts">Pending Parts</option>
                            <option value="scheduled">Scheduled</option>
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
                <select id="filterSystemMobile"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-secondary">
                    <option value="all">All Systems</option>
                    <option value="server">Servers</option>
                    <option value="network">Network</option>
                    <option value="database">Database</option>
                    <option value="hardware">Hardware</option>
                    <option value="security">Security</option>
                </select>

                <select id="filterStatusMobile"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-secondary">
                    <option value="all">All Status</option>
                    <option value="emergency">Emergency</option>
                    <option value="in-repair">In Repair</option>
                    <option value="resolved">Resolved</option>
                    <option value="pending-parts">Pending Parts</option>
                    <option value="scheduled">Scheduled</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full min-w-max">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700 cursor-pointer hover:bg-gray-100 sort-header"
                            data-sort="id">
                            <div class="flex items-center gap-1">
                                <span class="hidden sm:inline">Issue ID</span>
                                <span class="sm:hidden">ID</span>
                                <i class="fas fa-sort text-gray-400 ml-1 text-xs"></i>
                            </div>
                        </th>
                        <th class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700 cursor-pointer hover:bg-gray-100 sort-header"
                            data-sort="subject">
                            <div class="flex items-center gap-1">
                                <span>Problem Description</span>
                                <i class="fas fa-sort text-gray-400 ml-1 text-xs"></i>
                            </div>
                        </th>
                        <th class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700 cursor-pointer hover:bg-gray-100 sort-header hidden md:table-cell"
                            data-sort="system">
                            <div class="flex items-center gap-1">
                                <span>System/Component</span>
                                <i class="fas fa-sort text-gray-400 ml-1 text-xs"></i>
                            </div>
                        </th>
                        <th class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700 cursor-pointer hover:bg-gray-100 sort-header"
                            data-sort="priority">
                            <div class="flex items-center gap-1">
                                <span class="hidden xs:inline">Priority</span>
                                <span class="xs:hidden">Pri</span>
                                <i class="fas fa-sort text-gray-400 ml-1 text-xs"></i>
                            </div>
                        </th>
                        <th class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700 cursor-pointer hover:bg-gray-100 sort-header hidden sm:table-cell"
                            data-sort="status">
                            <div class="flex items-center gap-1">
                                <span>Status</span>
                                <i class="fas fa-sort text-gray-400 ml-1 text-xs"></i>
                            </div>
                        </th>
                        <th class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700 cursor-pointer hover:bg-gray-100 sort-header hidden md:table-cell"
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
                    <?php
                    $assigned_tickets = [
                        [
                            'id' => '#TS10421',
                            'id_num' => 10421,
                            'subject' => 'Server load balancer failure',
                            'system' => 'Production Server',
                            'priority' => 'Critical',
                            'priority_value' => 4,
                            'priorityColor' => 'bg-red-100 text-red-800',
                            'status' => 'Emergency',
                            'statusColor' => 'bg-red-100 text-red-800',
                            'time' => '5 minutes ago',
                            'timestamp' => strtotime('-5 minutes'),
                        ],
                        [
                            'id' => '#TS10422',
                            'id_num' => 10422,
                            'subject' => 'Database connection timeout',
                            'system' => 'MySQL Cluster',
                            'priority' => 'High',
                            'priority_value' => 3,
                            'priorityColor' => 'bg-orange-100 text-orange-800',
                            'status' => 'In Repair',
                            'statusColor' => 'bg-purple-100 text-purple-800',
                            'time' => '1 hour ago',
                            'timestamp' => strtotime('-1 hour'),
                        ],
                        [
                            'id' => '#TS10423',
                            'id_num' => 10423,
                            'subject' => 'Network switch configuration',
                            'system' => 'Network Infrastructure',
                            'priority' => 'Medium',
                            'priority_value' => 2,
                            'priorityColor' => 'bg-yellow-100 text-yellow-800',
                            'status' => 'Pending Parts',
                            'statusColor' => 'bg-gray-200 text-gray-800',
                            'time' => '2 hours ago',
                            'timestamp' => strtotime('-2 hours'),
                        ],
                        [
                            'id' => '#TS10424',
                            'id_num' => 10424,
                            'subject' => 'Backup system verification',
                            'system' => 'Backup Server',
                            'priority' => 'Low',
                            'priority_value' => 1,
                            'priorityColor' => 'bg-blue-100 text-blue-800',
                            'status' => 'Resolved',
                            'statusColor' => 'bg-green-100 text-green-800',
                            'time' => '3 hours ago',
                            'timestamp' => strtotime('-3 hours'),
                        ],
                        [
                            'id' => '#TS10425',
                            'id_num' => 10425,
                            'subject' => 'Firewall rule optimization',
                            'system' => 'Security System',
                            'priority' => 'Medium',
                            'priority_value' => 2,
                            'priorityColor' => 'bg-yellow-100 text-yellow-800',
                            'status' => 'Scheduled',
                            'statusColor' => 'bg-gray-200 text-gray-800',
                            'time' => '4 hours ago',
                            'timestamp' => strtotime('-4 hours'),
                        ],
                        [
                            'id' => '#TS10426',
                            'id_num' => 10426,
                            'subject' => 'Hard drive replacement in server rack',
                            'system' => 'Data Center A',
                            'priority' => 'High',
                            'priority_value' => 3,
                            'priorityColor' => 'bg-orange-100 text-orange-800',
                            'status' => 'In Repair',
                            'statusColor' => 'bg-purple-100 text-purple-800',
                            'time' => '5 hours ago',
                            'timestamp' => strtotime('-5 hours'),
                        ],
                    ];
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 md:p-6 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="text-gray-600 text-xs md:text-sm">
                    Showing <span id="showingCount">6</span> of <span id="totalCount">18</span> entries
                </div>
                <div class="flex items-center gap-1 md:gap-2">
                    <button id="prevPage"
                        class="px-2 md:px-3 py-1 md:py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed text-xs"
                        disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button
                        class="px-2 md:px-3 py-1 md:py-2 bg-secondary text-white rounded-lg text-xs md:text-sm">1</button>
                    <button
                        class="px-2 md:px-3 py-1 md:py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 text-xs md:text-sm">2</button>
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
        <!-- Troubleshooting Guidelines -->
        <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3 md:gap-[15px] mb-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-stethoscope text-blue-600 text-lg md:text-xl"></i>
                </div>
                <h3 class="text-lg md:text-[20px] font-semibold text-gray-800">Technical Troubleshooting Guidelines</h3>
            </div>
            <p class="text-gray-600 text-sm md:text-base mb-4 md:mb-6">
                As a Technical Support specialist, follow systematic troubleshooting:
            </p>
            <ol class="text-gray-600 text-sm md:text-base space-y-2 list-decimal pl-5">
                <li>Check server logs and system metrics</li>
                <li>Verify network connectivity and latency</li>
                <li>Test database connections and performance</li>
                <li>Check hardware diagnostics and alerts</li>
                <li>Review security logs and firewall rules</li>
                <li>Document all steps for knowledge base</li>
            </ol>
        </div>

        <!-- Quick Actions Card -->
        <div class="bg-gradient-to-r from-secondary to-[#8A84C6] rounded-xl p-4 md:p-6 text-white">
            <h3 class="text-lg md:text-[20px] font-semibold mb-3 md:mb-4">Quick Actions</h3>
            <p class="text-white/80 text-sm md:text-base mb-4 md:mb-6">
                Common tasks for technical support
            </p>

            <div class="flex flex-col sm:flex-row gap-3">
                <a href="<?= base_url('department/technical-support/ticket_detail/10421') ?>"
                    class="px-4 md:px-6 py-2 md:py-3 bg-white text-secondary rounded-lg hover:bg-gray-100 transition-colors font-medium text-sm md:text-base flex items-center justify-center gap-2">
                    <i class="fas fa-server"></i>
                    <span>Fix Critical Server</span>
                </a>

                <button onclick="generateDiagnosticReport()"
                    class="px-4 md:px-6 py-2 md:py-3 bg-white/20 text-white rounded-lg hover:bg-white/30 transition-colors font-medium text-sm md:text-base flex items-center justify-center gap-2">
                    <i class="fas fa-file-medical-alt"></i>
                    <span>System Report</span>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom styles for table */
    .hover-row:hover {
        background-color: #f9fafb;
    }

    /* Animation for new issues */
    @keyframes highlightNew {
        0% {
            background-color: rgba(117, 110, 164, 0.1);
        }

        100% {
            background-color: transparent;
        }
    }

    .new-ticket {
        animation: highlightNew 2s ease-out;
    }

    /* Critical priority pulse */
    @keyframes pulse-critical {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.7;
        }
    }

    .critical-pulse {
        animation: pulse-critical 2s infinite;
    }
</style>

<script>
    // Ticket data
    const ticketsData = <?= json_encode($assigned_tickets) ?>;

    // Sorting state
    let currentSort = { column: 'date', direction: 'desc' };
    let currentFilters = {
        system: 'all',
        status: 'all'
    };

    // Initialize
    document.addEventListener('DOMContentLoaded', function () {
        // Render initial table
        renderTable(ticketsData);

        // Search functionality
        const ticketSearch = document.getElementById('ticketSearch');
        ticketSearch.addEventListener('input', (e) => {
            filterAndSortTickets();
        });

        // Sort by dropdown
        document.getElementById('sortBy').addEventListener('change', function () {
            const value = this.value.split('-');
            currentSort.column = value[0];
            currentSort.direction = value[1];
            filterAndSortTickets();
        });

        // Filter dropdowns (Desktop)
        document.getElementById('filterSystem').addEventListener('change', function () {
            currentFilters.system = this.value;
            filterAndSortTickets();
        });

        document.getElementById('filterStatus').addEventListener('change', function () {
            currentFilters.status = this.value;
            filterAndSortTickets();
        });

        // Mobile filter dropdowns
        document.getElementById('filterSystemMobile').addEventListener('change', function () {
            currentFilters.system = this.value;
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
                    currentSort.direction = 'desc';
                }

                // Update dropdown to match
                updateSortDropdown();

                // Sort and render
                filterAndSortTickets();

                // Update sort icons
                updateSortIcons();
            });
        });

        // Pagination
        document.getElementById('prevPage').addEventListener('click', function () {
            showToast('Previous page clicked', 'info');
        });

        document.getElementById('nextPage').addEventListener('click', function () {
            showToast('Next page clicked', 'info');
        });

        // Initialize sort icons
        updateSortIcons();

        // Add critical pulse animation
        setTimeout(() => {
            document.querySelectorAll('.bg-red-100').forEach(badge => {
                badge.classList.add('critical-pulse');
            });
        }, 500);
    });

    function filterAndSortTickets() {
        let filteredTickets = [...ticketsData];

        // Apply search filter
        const searchTerm = document.getElementById('ticketSearch').value.toLowerCase();
        if (searchTerm) {
            filteredTickets = filteredTickets.filter(ticket =>
                ticket.subject.toLowerCase().includes(searchTerm) ||
                ticket.id.toLowerCase().includes(searchTerm) ||
                ticket.system.toLowerCase().includes(searchTerm) ||
                ticket.status.toLowerCase().includes(searchTerm)
            );
        }

        // Apply system filter
        if (currentFilters.system !== 'all') {
            filteredTickets = filteredTickets.filter(ticket =>
                ticket.system.toLowerCase().includes(currentFilters.system)
            );
        }

        // Apply status filter
        if (currentFilters.status !== 'all') {
            const statusMap = {
                'emergency': 'Emergency',
                'in-repair': 'In Repair',
                'resolved': 'Resolved',
                'pending-parts': 'Pending Parts',
                'scheduled': 'Scheduled'
            };
            filteredTickets = filteredTickets.filter(ticket =>
                ticket.status === statusMap[currentFilters.status]
            );
        }

        // Sort tickets
        filteredTickets.sort((a, b) => {
            let aValue, bValue;

            switch (currentSort.column) {
                case 'id':
                    aValue = a.id_num;
                    bValue = b.id_num;
                    break;
                case 'subject':
                    aValue = a.subject.toLowerCase();
                    bValue = b.subject.toLowerCase();
                    break;
                case 'system':
                    aValue = a.system.toLowerCase();
                    bValue = b.system.toLowerCase();
                    break;
                case 'priority':
                    aValue = a.priority_value;
                    bValue = b.priority_value;
                    break;
                case 'status':
                    aValue = a.status.toLowerCase();
                    bValue = b.status.toLowerCase();
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
        document.getElementById('showingCount').textContent = filteredTickets.length;
        document.getElementById('totalCount').textContent = ticketsData.length;
    }

    function renderTable(tickets) {
        const tbody = document.getElementById('ticketsTable');
        tbody.innerHTML = '';

        tickets.forEach((ticket, index) => {
            const rowClass = index % 2 === 0 ? 'bg-white' : 'bg-gray-50';
            const row = document.createElement('tr');
            row.className = `${rowClass} hover-row transition-colors`;
            row.innerHTML = `
                <td class="py-3 px-3 md:py-4 md:px-6">
                    <span class="font-bold text-gray-800 text-sm md:text-base">${ticket.id}</span>
                </td>
                <td class="py-3 px-3 md:py-4 md:px-6">
                    <div>
                        <p class="font-medium text-gray-800 text-sm truncate max-w-[150px] md:max-w-none">${ticket.subject}</p>
                        <p class="text-gray-500 text-xs mt-1 hidden md:block">${ticket.system}</p>
                    </div>
                </td>
                <td class="py-3 px-3 md:py-4 md:px-6 hidden md:table-cell">
                    <span class="text-gray-700 text-sm">${ticket.system}</span>
                </td>
                <td class="py-3 px-3 md:py-4 md:px-6">
                    <span class="px-2 py-1 text-xs rounded-full ${ticket.priorityColor} font-medium whitespace-nowrap">
                        ${ticket.priority}
                    </span>
                </td>
                <td class="py-3 px-3 md:py-4 md:px-6 hidden sm:table-cell">
                    <span class="px-2 py-1 text-xs rounded-full ${ticket.statusColor} font-medium whitespace-nowrap">
                        ${ticket.status}
                    </span>
                </td>
                <td class="py-3 px-3 md:py-4 md:px-6 hidden md:table-cell">
                    <span class="text-gray-600 text-sm">${ticket.time}</span>
                </td>
                <td class="py-3 px-3 md:py-4 md:px-6">
                    <div class="flex items-center gap-2">
                        <a href="<?= base_url('department/technical-support/ticket_detail/') ?>${ticket.id_num}" 
                           class="px-3 py-1 md:px-3 md:py-2 bg-blue-100 text-blue-700 text-xs md:text-sm rounded-lg hover:bg-blue-200 transition-colors whitespace-nowrap flex items-center gap-1">
                            <i class="fas fa-tools text-xs"></i>
                            <span>Repair</span>
                        </a>
                        <a href="<?= base_url('department/technical-support/ticket_summary/') ?>${ticket.id_num}" 
                           class="px-3 py-1 md:px-3 md:py-2 bg-gray-100 text-gray-700 text-xs md:text-sm rounded-lg hover:bg-gray-200 transition-colors whitespace-nowrap flex items-center gap-1">
                            <i class="fas fa-file-alt text-xs"></i>
                            <span>Logs</span>
                        </a>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });

        // If no tickets match filters
        if (tickets.length === 0) {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td colspan="7" class="py-8 px-4 md:px-6 text-center text-gray-500">
                    <div class="flex flex-col items-center justify-center">
                        <i class="fas fa-server text-2xl md:text-3xl text-gray-300 mb-3"></i>
                        <p class="text-base md:text-lg font-medium text-gray-400 mb-1">No technical issues found</p>
                        <p class="text-xs md:text-sm text-gray-500">Try adjusting your search or filters</p>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        }
    }

    function updateSortDropdown() {
        const dropdown = document.getElementById('sortBy');
        const value = `${currentSort.column}-${currentSort.direction}`;
        dropdown.value = value;
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

    // Generate diagnostic report function
    function generateDiagnosticReport() {
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;

        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
        btn.disabled = true;

        // Simulate export process
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;

            // Show success message
            showToast('Diagnostic report generated successfully!', 'success');
        }, 1500);
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
<?= $this->endSection() ?>