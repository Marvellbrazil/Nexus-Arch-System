<?= $this->extend('layouts/customer_layout') ?>

<?= $this->section('title') ?>My Tickets - NEXUS<?= $this->endSection() ?>

<?= $this->section('background_effects') ?>
<!-- Background Effects -->
<div class="fixed w-[40vw] h-[40vw] -right-[10%] -bottom-[10%] rotate-[149deg] bg-gradient-to-r from-[rgba(56.96,44.47,127.72,0.15)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-80 z-0"></div>
<div class="fixed w-[35vw] h-[25vw] -left-[5%] top-[10%] rotate-[8deg] bg-gradient-to-r from-[rgba(65.04,45.10,137.14,0.20)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-70 z-0"></div>
<div class="fixed w-[15vw] h-[20vw] right-[5%] -top-[5%] rotate-[8deg] bg-gradient-to-r from-[rgba(16.56,8.41,46.05,0.12)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-60 z-0"></div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mt-[77px] p-[30px] relative z-10">
    <!-- Page Header -->
    <div class="mb-[25px] relative">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-[32px] font-semibold mb-[5px] text-text-dark">My Tickets</h1>
                <p class="text-[15px] font-light text-[#666]">Manage and track your support requests</p>
            </div>
            
            <!-- Search and Add Ticket -->
            <div class="flex flex-col sm:flex-row gap-[15px]">
                <div class="relative">
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                    <input 
                        type="text" 
                        placeholder="Search tickets..." 
                        class="w-full sm:w-[256px] h-[44px] pl-12 pr-4 bg-white border border-gray-300 rounded-xl text-gray-700 text-[14px] focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20"
                        id="ticketSearch"
                    >
                </div>
                <a href="<?= base_url('dashboard/create_ticket') ?>" 
                   class="h-[44px] px-[20px] bg-secondary text-white rounded-xl flex items-center justify-center gap-[8px] hover:bg-[#817CB2] transition-colors font-medium">
                    <i class="fas fa-plus"></i>
                    <span>New Ticket</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-ticket-alt text-blue-600"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Total Tickets</p>
                    <p class="text-2xl font-bold text-gray-800">12</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Open</p>
                    <p class="text-2xl font-bold text-gray-800">5</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Resolved</p>
                    <p class="text-2xl font-bold text-gray-800">7</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-purple-600"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">This Month</p>
                    <p class="text-2xl font-bold text-gray-800">3</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
        <!-- Table Header -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h2 class="text-[20px] font-semibold text-gray-800">Recent Tickets</h2>
                
                <!-- Filter & Sort Options -->
                <div class="flex flex-wrap gap-3">
                    <!-- Sort by -->
                    <div class="relative">
                        <select id="sortBy" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-secondary appearance-none bg-white pr-8">
                            <option value="date-desc">Sort by: Date (Newest)</option>
                            <option value="date-asc">Sort by: Date (Oldest)</option>
                            <option value="priority-desc">Sort by: Priority (High to Low)</option>
                            <option value="priority-asc">Sort by: Priority (Low to High)</option>
                            <option value="id-desc">Sort by: Ticket ID (Desc)</option>
                            <option value="id-asc">Sort by: Ticket ID (Asc)</option>
                        </select>
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </div>
                    </div>
                    
                    <!-- Filter Options -->
                    <select id="filterProject" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-secondary">
                        <option value="all">All Projects</option>
                        <option value="alpha">Project Alpha</option>
                        <option value="beta">Project Beta</option>
                        <option value="gamma">Project Gamma</option>
                    </select>
                    
                    <select id="filterStatus" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-secondary">
                        <option value="all">All Status</option>
                        <option value="open">Open</option>
                        <option value="in-progress">In Progress</option>
                        <option value="resolved">Resolved</option>
                        <option value="closed">Closed</option>
                    </select>
                    
                    <select id="filterPriority" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-secondary">
                        <option value="all">All Priority</option>
                        <option value="urgent">Urgent</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-4 px-6 text-left text-sm font-semibold text-gray-700 cursor-pointer hover:bg-gray-100 sort-header" data-sort="id">
                            <div class="flex items-center gap-1">
                                <span>Ticket ID</span>
                                <i class="fas fa-sort text-gray-400 ml-1"></i>
                            </div>
                        </th>
                        <th class="py-4 px-6 text-left text-sm font-semibold text-gray-700 cursor-pointer hover:bg-gray-100 sort-header" data-sort="subject">
                            <div class="flex items-center gap-1">
                                <span>Subject</span>
                                <i class="fas fa-sort text-gray-400 ml-1"></i>
                            </div>
                        </th>
                        <th class="py-4 px-6 text-left text-sm font-semibold text-gray-700 cursor-pointer hover:bg-gray-100 sort-header" data-sort="project">
                            <div class="flex items-center gap-1">
                                <span>Project</span>
                                <i class="fas fa-sort text-gray-400 ml-1"></i>
                            </div>
                        </th>
                        <th class="py-4 px-6 text-left text-sm font-semibold text-gray-700 cursor-pointer hover:bg-gray-100 sort-header" data-sort="priority">
                            <div class="flex items-center gap-1">
                                <span>Priority</span>
                                <i class="fas fa-sort text-gray-400 ml-1"></i>
                            </div>
                        </th>
                        <th class="py-4 px-6 text-left text-sm font-semibold text-gray-700 cursor-pointer hover:bg-gray-100 sort-header" data-sort="date">
                            <div class="flex items-center gap-1">
                                <span>Created</span>
                                <i class="fas fa-sort text-gray-400 ml-1"></i>
                            </div>
                        </th>
                        <th class="py-4 px-6 text-left text-sm font-semibold text-gray-700 cursor-pointer hover:bg-gray-100 sort-header" data-sort="status">
                            <div class="flex items-center gap-1">
                                <span>Status</span>
                                <i class="fas fa-sort text-gray-400 ml-1"></i>
                            </div>
                        </th>
                        <th class="py-4 px-6 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody id="ticketsTable" class="divide-y divide-gray-200">
                    <?php 
                    $tickets = [
                        [
                            'id' => '#10425', 
                            'id_num' => 10425,
                            'subject' => 'Fix mixizading app updates', 
                            'project' => 'Project Alpha', 
                            'project_key' => 'alpha',
                            'priority' => 'Urgent', 
                            'priority_value' => 4,
                            'priorityColor' => 'bg-red-100 text-red-800', 
                            'time' => 'Yesterday, 9:50 PM', 
                            'timestamp' => strtotime('-1 day -2 hours'),
                            'status' => 'Closed', 
                            'status_key' => 'closed',
                            'statusColor' => 'bg-gray-100 text-gray-800'
                        ],
                        [
                            'id' => '#10421', 
                            'id_num' => 10421,
                            'subject' => 'Login issue causing error message', 
                            'project' => 'Project Alpha', 
                            'project_key' => 'alpha',
                            'priority' => 'Medium', 
                            'priority_value' => 2,
                            'priorityColor' => 'bg-yellow-100 text-yellow-800', 
                            'time' => 'Today, 10:30 AM', 
                            'timestamp' => strtotime('today 10:30'),
                            'status' => 'In Progress', 
                            'status_key' => 'in-progress',
                            'statusColor' => 'bg-blue-100 text-blue-800'
                        ],
                        [
                            'id' => '#10422', 
                            'id_num' => 10422,
                            'subject' => 'Feature request for new export option', 
                            'project' => 'Project Alpha', 
                            'project_key' => 'alpha',
                            'priority' => 'Low', 
                            'priority_value' => 1,
                            'priorityColor' => 'bg-blue-100 text-blue-800', 
                            'time' => 'Yesterday, 4:50 PM', 
                            'timestamp' => strtotime('-1 day 16:50'),
                            'status' => 'Open', 
                            'status_key' => 'open',
                            'statusColor' => 'bg-gray-100 text-gray-800'
                        ],
                        [
                            'id' => '#10423', 
                            'id_num' => 10423,
                            'subject' => 'Fix firestorx issues neat issues', 
                            'project' => 'Project Beta', 
                            'project_key' => 'beta',
                            'priority' => 'High', 
                            'priority_value' => 3,
                            'priorityColor' => 'bg-red-100 text-red-800', 
                            'time' => 'Today, 8:30 AM', 
                            'timestamp' => strtotime('today 8:30'),
                            'status' => 'In Progress', 
                            'status_key' => 'in-progress',
                            'statusColor' => 'bg-blue-100 text-blue-800'
                        ],
                        [
                            'id' => '#10424', 
                            'id_num' => 10424,
                            'subject' => 'Fix safissax issues source log iss', 
                            'project' => 'Project Beta', 
                            'project_key' => 'beta',
                            'priority' => 'Low', 
                            'priority_value' => 1,
                            'priorityColor' => 'bg-blue-100 text-blue-800', 
                            'time' => 'Jan. 22, 7:45 PM', 
                            'timestamp' => strtotime('-2 days 19:45'),
                            'status' => 'Resolved', 
                            'status_key' => 'resolved',
                            'statusColor' => 'bg-green-100 text-green-800'
                        ],
                    ];
                    ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-6 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="text-gray-600 text-sm">
                    Showing <span id="showingCount">5</span> of <span id="totalCount">12</span> entries
                </div>
                <div class="flex items-center gap-2">
                    <button id="prevPage" class="px-3 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="px-3 py-2 bg-secondary text-white rounded-lg">1</button>
                    <button class="px-3 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">2</button>
                    <button class="px-3 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">3</button>
                    <button id="nextPage" class="px-3 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Help Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Need Help Card -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center gap-[15px] mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-question-circle text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-[20px] font-semibold text-gray-800">Need Help?</h3>
            </div>
            <p class="text-gray-600 mb-6">
                If you need assistance, please open a new ticket or search our knowledge base.
            </p>
            <div class="flex gap-3">
                <a href="<?= base_url('dashboard/create_ticket') ?>" 
                   class="px-6 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium">
                    Open Ticket
                </a>
                <button class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Knowledge Base
                </button>
            </div>
        </div>
        
        <!-- Quick Actions Card -->
        <div class="bg-gradient-to-r from-secondary to-[#8A84C6] rounded-xl p-6 text-white">
            <h3 class="text-[20px] font-semibold mb-4">Quick Actions</h3>
            <p class="text-white/80 mb-6">
                Quick links for ordering and addons
            </p>
            
            <div class="flex flex-col sm:flex-row gap-3">
                <button class="px-6 py-3 bg-white text-secondary rounded-lg hover:bg-gray-100 transition-colors font-medium flex items-center justify-center gap-2">
                    <i class="fas fa-plus"></i>
                    <span>Place New Order</span>
                </button>
                
                <button class="px-6 py-3 bg-white/20 text-white rounded-lg hover:bg-white/30 transition-colors font-medium flex items-center justify-center gap-2">
                    <i class="fas fa-cube"></i>
                    <span>View Addons</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Ticket data
    const ticketsData = <?= json_encode($tickets) ?>;
    
    // Sorting state
    let currentSort = { column: 'date', direction: 'desc' };
    let currentFilters = {
        project: 'all',
        status: 'all',
        priority: 'all'
    };
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Render initial table
        renderTable(ticketsData);
        
        // Search functionality
        const ticketSearch = document.getElementById('ticketSearch');
        ticketSearch.addEventListener('input', (e) => {
            filterAndSortTickets();
        });
        
        // Sort by dropdown
        document.getElementById('sortBy').addEventListener('change', function() {
            const value = this.value.split('-');
            currentSort.column = value[0];
            currentSort.direction = value[1];
            filterAndSortTickets();
        });
        
        // Filter dropdowns
        document.getElementById('filterProject').addEventListener('change', function() {
            currentFilters.project = this.value;
            filterAndSortTickets();
        });
        
        document.getElementById('filterStatus').addEventListener('change', function() {
            currentFilters.status = this.value;
            filterAndSortTickets();
        });
        
        document.getElementById('filterPriority').addEventListener('change', function() {
            currentFilters.priority = this.value;
            filterAndSortTickets();
        });
        
        // Header click sorting
        document.querySelectorAll('.sort-header').forEach(header => {
            header.addEventListener('click', function() {
                const column = this.dataset.sort;
                
                // Toggle direction if same column
                if (currentSort.column === column) {
                    currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
                } else {
                    currentSort.column = column;
                    currentSort.direction = 'desc'; // Default to desc
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
        document.getElementById('prevPage').addEventListener('click', function() {
            // In a real app, this would fetch previous page
            console.log('Previous page');
        });
        
        document.getElementById('nextPage').addEventListener('click', function() {
            // In a real app, this would fetch next page
            console.log('Next page');
        });
        
        // Initialize sort icons
        updateSortIcons();
    });
    
    function filterAndSortTickets() {
        let filteredTickets = [...ticketsData];
        
        // Apply search filter
        const searchTerm = document.getElementById('ticketSearch').value.toLowerCase();
        if (searchTerm) {
            filteredTickets = filteredTickets.filter(ticket => 
                ticket.subject.toLowerCase().includes(searchTerm) ||
                ticket.id.toLowerCase().includes(searchTerm) ||
                ticket.project.toLowerCase().includes(searchTerm)
            );
        }
        
        // Apply project filter
        if (currentFilters.project !== 'all') {
            filteredTickets = filteredTickets.filter(ticket => 
                ticket.project_key === currentFilters.project
            );
        }
        
        // Apply status filter
        if (currentFilters.status !== 'all') {
            filteredTickets = filteredTickets.filter(ticket => 
                ticket.status_key === currentFilters.status
            );
        }
        
        // Apply priority filter
        if (currentFilters.priority !== 'all') {
            const priorityMap = {
                'urgent': 4,
                'high': 3,
                'medium': 2,
                'low': 1
            };
            filteredTickets = filteredTickets.filter(ticket => 
                ticket.priority_value === priorityMap[currentFilters.priority]
            );
        }
        
        // Sort tickets
        filteredTickets.sort((a, b) => {
            let aValue, bValue;
            
            switch(currentSort.column) {
                case 'id':
                    aValue = a.id_num;
                    bValue = b.id_num;
                    break;
                case 'subject':
                    aValue = a.subject.toLowerCase();
                    bValue = b.subject.toLowerCase();
                    break;
                case 'project':
                    aValue = a.project.toLowerCase();
                    bValue = b.project.toLowerCase();
                    break;
                case 'priority':
                    aValue = a.priority_value;
                    bValue = b.priority_value;
                    break;
                case 'date':
                    aValue = a.timestamp;
                    bValue = b.timestamp;
                    break;
                case 'status':
                    aValue = a.status.toLowerCase();
                    bValue = b.status.toLowerCase();
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
            row.className = `${rowClass} hover:bg-gray-100 transition-colors`;
            row.innerHTML = `
                <td class="py-4 px-6">
                    <span class="font-bold text-gray-800">${ticket.id}</span>
                </td>
                <td class="py-4 px-6">
                    <div>
                        <p class="font-medium text-gray-800 text-sm">${ticket.subject}</p>
                        <p class="text-gray-500 text-xs mt-1">Last updated: ${getRelativeTime(ticket.timestamp)}</p>
                    </div>
                </td>
                <td class="py-4 px-6">
                    <span class="text-gray-700 text-sm">${ticket.project}</span>
                </td>
                <td class="py-4 px-6">
                    <span class="px-3 py-1 text-xs rounded-full ${ticket.priorityColor} font-medium">
                        ${ticket.priority}
                    </span>
                </td>
                <td class="py-4 px-6">
                    <span class="text-gray-600 text-sm">${ticket.time}</span>
                </td>
                <td class="py-4 px-6">
                    <span class="px-3 py-1 text-xs rounded-full ${ticket.statusColor} font-medium">
                        ${ticket.status}
                    </span>
                </td>
                <td class="py-4 px-6">
                    <div class="flex items-center gap-2">
                        <a href="<?= base_url('dashboard/ticket_detail/') ?>${ticket.id_num}" 
                           class="px-4 py-2 bg-secondary text-white text-sm rounded-lg hover:bg-[#817CB2] transition-colors">
                            View
                        </a>
                        <button class="p-2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });
        
        // If no tickets match filters
        if (tickets.length === 0) {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td colspan="7" class="py-8 px-6 text-center text-gray-500">
                    <div class="flex flex-col items-center justify-center">
                        <i class="fas fa-ticket-alt text-3xl text-gray-300 mb-3"></i>
                        <p class="text-lg font-medium text-gray-400 mb-1">No tickets found</p>
                        <p class="text-sm text-gray-500">Try adjusting your search or filters</p>
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
            icon.className = 'fas fa-sort text-gray-400 ml-1';
        });
        
        // Set active sort icon
        const activeHeader = document.querySelector(`.sort-header[data-sort="${currentSort.column}"] i`);
        if (activeHeader) {
            activeHeader.className = `fas fa-sort-${currentSort.direction === 'asc' ? 'up' : 'down'} text-secondary ml-1`;
        }
    }
    
    function getRelativeTime(timestamp) {
        const now = Math.floor(Date.now() / 1000);
        const diff = now - timestamp;
        
        if (diff < 60) return 'just now';
        if (diff < 3600) return Math.floor(diff / 60) + ' min ago';
        if (diff < 86400) return Math.floor(diff / 3600) + ' hours ago';
        if (diff < 604800) return Math.floor(diff / 86400) + ' days ago';
        return Math.floor(diff / 604800) + ' weeks ago';
    }
</script>
<?= $this->endSection() ?>