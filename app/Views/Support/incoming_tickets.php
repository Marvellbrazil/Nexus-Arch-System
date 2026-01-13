<?= $this->extend('layouts/support_layout') ?>

<?= $this->section('title') ?>Incoming Tickets - NEXUS Support<?= $this->endSection() ?>

<?= $this->section('background_effects') ?>
<!-- Background Effects (tetap sama) -->
<div class="fixed w-[40vw] h-[40vw] -right-[10%] -bottom-[10%] rotate-[149deg] bg-gradient-to-r from-[rgba(56.96,44.47,127.72,0.15)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-80 z-0"></div>
<div class="fixed w-[35vw] h-[25vw] -left-[5%] top-[10%] rotate-[8deg] bg-gradient-to-r from-[rgba(65.04,45.10,137.14,0.20)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-70 z-0"></div>
<div class="fixed w-[15vw] h-[20vw] right-[5%] -top-[5%] rotate-[8deg] bg-gradient-to-r from-[rgba(16.56,8.41,46.05,0.12)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-60 z-0"></div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mt-4 md:mt-[77px] p-4 md:p-[30px] relative z-10">
    <!-- Page Header -->
    <div class="mb-6 md:mb-[25px] relative">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex-1">
                <h1 class="text-2xl md:text-[32px] font-semibold mb-1 md:mb-[5px] text-text-dark">Incoming Tickets</h1>
                <p class="text-sm md:text-[15px] font-light text-[#666]">Review and forward tickets to appropriate departments</p>
            </div>
            
            <!-- Search and Stats -->
            <div class="flex flex-col sm:flex-row gap-3 md:gap-[15px]">
                <div class="relative">
                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                    <input 
                        type="text" 
                        placeholder="Search tickets..." 
                        class="w-full h-10 md:h-[44px] pl-10 pr-4 bg-white border border-gray-300 rounded-xl text-gray-700 text-sm md:text-[14px] focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20"
                        id="ticketSearch"
                    >
                </div>
                
                <!-- Stats Badge -->
                <div class="px-4 py-2 bg-secondary/10 text-secondary rounded-xl flex items-center justify-center gap-2">
                    <i class="fas fa-inbox"></i>
                    <span class="font-medium">
                        <span class="font-bold"><?= $stats['pending_review'] ?? 0 ?></span> Pending
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
                    <p class="text-gray-600 text-xs md:text-sm">Total Incoming</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800"><?= $stats['total_incoming'] ?? 0 ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-3 md:p-5 shadow-sm border border-gray-200">
            <div class="flex items-center gap-2 md:gap-3">
                <div class="w-8 h-8 md:w-12 md:h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-sm md:text-base"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-xs md:text-sm">Pending Review</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800"><?= $stats['pending_review'] ?? 0 ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-3 md:p-5 shadow-sm border border-gray-200">
            <div class="flex items-center gap-2 md:gap-3">
                <div class="w-8 h-8 md:w-12 md:h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-share-alt text-green-600 text-sm md:text-base"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-xs md:text-sm">Forwarded Today</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800"><?= $stats['forwarded_today'] ?? 0 ?></p>
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
                    <p class="text-xl md:text-2xl font-bold text-gray-800"><?= $stats['high_priority'] ?? 0 ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6 md:mb-8">
        <!-- Table Header -->
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h2 class="text-lg md:text-[20px] font-semibold text-gray-800">Pending Review Tickets</h2>
                
                <!-- Filter & Sort Options -->
                <div class="flex flex-wrap gap-2 md:gap-3">
                    <!-- Sort by -->
                    <div class="relative w-full md:w-auto">
                        <select id="sortBy" class="w-full md:w-auto border border-gray-300 rounded-lg px-3 md:px-4 py-2 text-xs md:text-sm focus:outline-none focus:border-secondary appearance-none bg-white pr-8">
                            <option value="priority-desc">Sort by: Priority (High to Low)</option>
                            <option value="priority-asc">Sort by: Priority (Low to High)</option>
                            <option value="date-desc">Sort by: Newest First</option>
                            <option value="date-asc">Sort by: Oldest First</option>
                            <option value="subject-asc">Sort by: Subject A-Z</option>
                            <option value="subject-desc">Sort by: Subject Z-A</option>
                        </select>
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </div>
                    </div>
                    
                    <!-- Filter Options -->
                    <div class="hidden md:flex gap-2">
                        <select id="filterProject" class="border border-gray-300 rounded-lg px-3 md:px-4 py-2 text-xs md:text-sm focus:outline-none focus:border-secondary">
                            <option value="all">All Projects</option>
                            <?php if (isset($projects) && !empty($projects)): ?>
                                <?php foreach ($projects as $project): ?>
                                    <option value="<?= strtolower(str_replace(' ', '-', $project['project_name'])) ?>">
                                        <?= $project['project_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        
                        <select id="filterPriority" class="border border-gray-300 rounded-lg px-3 md:px-4 py-2 text-xs md:text-sm focus:outline-none focus:border-secondary">
                            <option value="all">All Priority</option>
                            <option value="urgent">Urgent</option>
                            <option value="high">High</option>
                            <option value="medium">Medium</option>
                            <option value="low">Low</option>
                        </select>
                    </div>
                    
                    <!-- Mobile Filter Button -->
                    <button id="mobileFilterBtn" class="md:hidden px-3 py-2 bg-gray-100 text-gray-700 rounded-lg text-xs flex items-center gap-1">
                        <i class="fas fa-filter"></i>
                        Filters
                    </button>
                </div>
            </div>
            
            <!-- Mobile Filter Dropdown -->
            <div id="mobileFilters" class="mt-3 md:hidden space-y-2 hidden">
                <select id="filterProjectMobile" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-secondary">
                    <option value="all">All Projects</option>
                    <?php if (isset($projects) && !empty($projects)): ?>
                        <?php foreach ($projects as $project): ?>
                            <option value="<?= strtolower(str_replace(' ', '-', $project['project_name'])) ?>">
                                <?= $project['project_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                
                <select id="filterPriorityMobile" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-secondary">
                    <option value="all">All Priority</option>
                    <option value="urgent">Urgent</option>
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>
            </div>
        </div>
        
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full min-w-max">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700 cursor-pointer hover:bg-gray-100 sort-header" data-sort="id">
                            <div class="flex items-center gap-1">
                                <span class="hidden sm:inline">Ticket ID</span>
                                <span class="sm:hidden">ID</span>
                                <i class="fas fa-sort text-gray-400 ml-1 text-xs"></i>
                            </div>
                        </th>
                        <th class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700 cursor-pointer hover:bg-gray-100 sort-header" data-sort="subject">
                            <div class="flex items-center gap-1">
                                <span>Subject</span>
                                <i class="fas fa-sort text-gray-400 ml-1 text-xs"></i>
                            </div>
                        </th>
                        <th class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700 cursor-pointer hover:bg-gray-100 sort-header hidden md:table-cell" data-sort="customer">
                            <div class="flex items-center gap-1">
                                <span>Customer</span>
                                <i class="fas fa-sort text-gray-400 ml-1 text-xs"></i>
                            </div>
                        </th>
                        <th class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700 cursor-pointer hover:bg-gray-100 sort-header" data-sort="priority">
                            <div class="flex items-center gap-1">
                                <span class="hidden xs:inline">Priority</span>
                                <span class="xs:hidden">Pri</span>
                                <i class="fas fa-sort text-gray-400 ml-1 text-xs"></i>
                            </div>
                        </th>
                        <th class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700 cursor-pointer hover:bg-gray-100 sort-header hidden sm:table-cell" data-sort="date">
                            <div class="flex items-center gap-1">
                                <span>Created</span>
                                <i class="fas fa-sort text-gray-400 ml-1 text-xs"></i>
                            </div>
                        </th>
                        <th class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700 cursor-pointer hover:bg-gray-100 sort-header hidden md:table-cell" data-sort="project">
                            <div class="flex items-center gap-1">
                                <span>Project</span>
                                <i class="fas fa-sort text-gray-400 ml-1 text-xs"></i>
                            </div>
                        </th>
                        <th class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody id="ticketsTable" class="divide-y divide-gray-200">
                    <?php if (!empty($tickets)): ?>
                        <?php foreach ($tickets as $ticket): ?>
                            <?php
                            // Tentukan warna priority
                            $priorityColor = '';
                            switch ($ticket['priority_name']) {
                                case 'Urgent':
                                    $priorityColor = 'bg-red-100 text-red-800';
                                    break;
                                case 'High':
                                    $priorityColor = 'bg-orange-100 text-orange-800';
                                    break;
                                case 'Medium':
                                    $priorityColor = 'bg-yellow-100 text-yellow-800';
                                    break;
                                case 'Low':
                                    $priorityColor = 'bg-blue-100 text-blue-800';
                                    break;
                                default:
                                    $priorityColor = 'bg-gray-100 text-gray-800';
                            }
                            
                            // Format tanggal
                            $createdDate = new DateTime($ticket['created_at']);
                            $now = new DateTime();
                            $interval = $now->diff($createdDate);
                            
                            if ($interval->days == 0) {
                                $timeText = 'Today, ' . $createdDate->format('g:i A');
                            } elseif ($interval->days == 1) {
                                $timeText = 'Yesterday, ' . $createdDate->format('g:i A');
                            } else {
                                $timeText = $createdDate->format('M. d, g:i A');
                            }
                            
                            // Initials customer
                            $initials = '';
                            $nameParts = explode(' ', $ticket['customer_name']);
                            if (count($nameParts) >= 2) {
                                $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                            } else {
                                $initials = strtoupper(substr($ticket['customer_name'], 0, 2));
                            }
                            
                            // Priority value untuk sorting
                            $priorityValue = 0;
                            switch ($ticket['priority_name']) {
                                case 'Urgent': $priorityValue = 4; break;
                                case 'High': $priorityValue = 3; break;
                                case 'Medium': $priorityValue = 2; break;
                                case 'Low': $priorityValue = 1; break;
                            }
                            ?>
                            <tr class="bg-white hover-row transition-colors" 
                                data-id="<?= $ticket['ticket_id'] ?>"
                                data-subject="<?= htmlspecialchars($ticket['subject']) ?>"
                                data-customer="<?= htmlspecialchars($ticket['customer_name']) ?>"
                                data-priority="<?= $ticket['priority_name'] ?>"
                                data-priority-value="<?= $priorityValue ?>"
                                data-date="<?= strtotime($ticket['created_at']) ?>"
                                data-project="<?= htmlspecialchars($ticket['project_name'] ?? 'No Project') ?>">
                                <td class="py-3 px-3 md:py-4 md:px-6">
                                    <span class="font-bold text-gray-800 text-sm md:text-base">#<?= $ticket['ticket_id'] ?></span>
                                </td>
                                <td class="py-3 px-3 md:py-4 md:px-6">
                                    <div>
                                        <p class="font-medium text-gray-800 text-sm truncate max-w-[150px] md:max-w-none" title="<?= htmlspecialchars($ticket['subject']) ?>">
                                            <?= htmlspecialchars($ticket['subject']) ?>
                                        </p>
                                        <p class="text-gray-500 text-xs mt-1 hidden md:block"><?= htmlspecialchars($ticket['customer_name']) ?></p>
                                    </div>
                                </td>
                                <td class="py-3 px-3 md:py-4 md:px-6 hidden md:table-cell">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center text-blue-800 text-xs font-bold">
                                            <?= $initials ?>
                                        </div>
                                        <span class="text-gray-700 text-sm"><?= htmlspecialchars($ticket['customer_name']) ?></span>
                                    </div>
                                </td>
                                <td class="py-3 px-3 md:py-4 md:px-6">
                                    <span class="px-2 py-1 text-xs rounded-full <?= $priorityColor ?> font-medium whitespace-nowrap">
                                        <?= $ticket['priority_name'] ?>
                                    </span>
                                </td>
                                <td class="py-3 px-3 md:py-4 md:px-6 hidden sm:table-cell">
                                    <span class="text-gray-600 text-sm" title="<?= $ticket['created_at'] ?>">
                                        <?= $timeText ?>
                                    </span>
                                </td>
                                <td class="py-3 px-3 md:py-4 md:px-6 hidden md:table-cell">
                                    <span class="text-gray-700 text-sm"><?= $ticket['project_name'] ?? 'No Project' ?></span>
                                </td>
                                <td class="py-3 px-3 md:py-4 md:px-6">
                                    <div class="flex items-center gap-2">
                                        <a href="<?= site_url('support/ticket_detail/' . $ticket['ticket_id']) ?>" 
                                           class="px-3 py-1 md:px-3 md:py-2 bg-blue-100 text-blue-700 text-xs md:text-sm rounded-lg hover:bg-blue-200 transition-colors whitespace-nowrap flex items-center gap-1">
                                            <i class="fas fa-eye text-xs"></i>
                                            <span>View</span>
                                        </a>
                                        <a href="<?= site_url('support/ticket_summary/' . $ticket['ticket_id']) ?>" 
                                           class="px-3 py-1 md:px-3 md:py-2 bg-secondary text-white text-xs md:text-sm rounded-lg hover:bg-[#817CB2] transition-colors whitespace-nowrap flex items-center gap-1">
                                            <i class="fas fa-file-alt text-xs"></i>
                                            <span>Summary</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="py-8 px-4 md:px-6 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-inbox text-2xl md:text-3xl text-gray-300 mb-3"></i>
                                    <p class="text-base md:text-lg font-medium text-gray-400 mb-1">No incoming tickets</p>
                                    <p class="text-xs md:text-sm text-gray-500">All tickets have been processed</p>
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
                    Showing <span id="showingCount"><?= count($tickets) ?></span> of <span id="totalCount"><?= $stats['total_incoming'] ?? 0 ?></span> entries
                </div>
                <!-- Pagination akan diimplementasikan nanti jika perlu -->
                <div class="flex items-center gap-1 md:gap-2">
                    <button id="prevPage" class="px-2 md:px-3 py-1 md:py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed text-xs" disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="px-2 md:px-3 py-1 md:py-2 bg-secondary text-white rounded-lg text-xs md:text-sm">1</button>
                    <button id="nextPage" class="px-2 md:px-3 py-1 md:py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 text-xs md:text-sm">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Help Cards (tetap sama) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
        <!-- Processing Guidelines -->
        <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3 md:gap-[15px] mb-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clipboard-check text-blue-600 text-lg md:text-xl"></i>
                </div>
                <h3 class="text-lg md:text-[20px] font-semibold text-gray-800">Processing Guidelines</h3>
            </div>
            <p class="text-gray-600 text-sm md:text-base mb-4 md:mb-6">
                As a support agent, your role is to review incoming tickets and forward them to the appropriate department. Follow these steps:
            </p>
            <ol class="text-gray-600 text-sm md:text-base space-y-2 list-decimal pl-5">
                <li>Review the ticket details and customer information</li>
                <li>Determine the appropriate department (Technical, IT, Development, etc.)</li>
                <li>Add any relevant notes or observations</li>
                <li>Forward the ticket using the "Summary" view</li>
                <li>Update the ticket status to "Forwarded"</li>
            </ol>
        </div>
        
        <!-- Quick Actions Card -->
        <div class="bg-gradient-to-r from-secondary to-[#8A84C6] rounded-xl p-4 md:p-6 text-white">
            <h3 class="text-lg md:text-[20px] font-semibold mb-3 md:mb-4">Quick Actions</h3>
            <p class="text-white/80 text-sm md:text-base mb-4 md:mb-6">
                Common tasks for incoming ticket review
            </p>
            
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="<?= site_url('support/ticket_summary/10421') ?>" 
                   class="px-4 md:px-6 py-2 md:py-3 bg-white text-secondary rounded-lg hover:bg-gray-100 transition-colors font-medium text-sm md:text-base flex items-center justify-center gap-2">
                    <i class="fas fa-file-alt"></i>
                    <span>View Sample Summary</span>
                </a>
                
                <button onclick="exportReport()" 
                        class="px-4 md:px-6 py-2 md:py-3 bg-white/20 text-white rounded-lg hover:bg-white/30 transition-colors font-medium text-sm md:text-base flex items-center justify-center gap-2">
                    <i class="fas fa-download"></i>
                    <span>Export Report</span>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-row:hover {
        background-color: #f9fafb;
    }
    
    @keyframes highlightNew {
        0% { background-color: rgba(117, 110, 164, 0.1); }
        100% { background-color: transparent; }
    }
    
    .new-ticket {
        animation: highlightNew 2s ease-out;
    }
</style>

<script>
    // Ticket data dari PHP
    const ticketsData = <?= !empty($tickets) ? json_encode($tickets) : '[]' ?>;
    
    // Fungsi untuk mendapatkan priority value
    function getPriorityValue(priorityName) {
        const priorityMap = {
            'Urgent': 4,
            'High': 3,
            'Medium': 2,
            'Low': 1
        };
        return priorityMap[priorityName] || 0;
    }
    
    // Fungsi untuk mendapatkan priority color class
    function getPriorityColor(priorityName) {
        const colorMap = {
            'Urgent': 'bg-red-100 text-red-800',
            'High': 'bg-orange-100 text-orange-800',
            'Medium': 'bg-yellow-100 text-yellow-800',
            'Low': 'bg-blue-100 text-blue-800'
        };
        return colorMap[priorityName] || 'bg-gray-100 text-gray-800';
    }
    
    // Fungsi untuk format tanggal
    function formatDate(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffTime = Math.abs(now - date);
        const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
        
        const timeOptions = { hour: 'numeric', minute: '2-digit' };
        const timeStr = date.toLocaleTimeString('en-US', timeOptions);
        
        if (diffDays === 0) {
            return `Today, ${timeStr}`;
        } else if (diffDays === 1) {
            return `Yesterday, ${timeStr}`;
        } else {
            const month = date.toLocaleString('en-US', { month: 'short' });
            return `${month}. ${date.getDate()}, ${timeStr}`;
        }
    }
    
    // Fungsi untuk mendapatkan initials
    function getInitials(name) {
        const parts = name.split(' ');
        if (parts.length >= 2) {
            return (parts[0][0] + parts[1][0]).toUpperCase();
        }
        return name.substring(0, 2).toUpperCase();
    }
    
    // Sorting state
    let currentSort = { column: 'priority', direction: 'desc' };
    let currentFilters = {
        project: 'all',
        priority: 'all'
    };
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
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
        
        // Filter dropdowns (Desktop)
        document.getElementById('filterProject').addEventListener('change', function() {
            currentFilters.project = this.value;
            filterAndSortTickets();
        });
        
        document.getElementById('filterPriority').addEventListener('change', function() {
            currentFilters.priority = this.value;
            filterAndSortTickets();
        });
        
        // Mobile filter dropdowns
        document.getElementById('filterProjectMobile').addEventListener('change', function() {
            currentFilters.project = this.value;
            filterAndSortTickets();
        });
        
        document.getElementById('filterPriorityMobile').addEventListener('change', function() {
            currentFilters.priority = this.value;
            filterAndSortTickets();
        });
        
        // Mobile filter button
        document.getElementById('mobileFilterBtn').addEventListener('click', function() {
            const filters = document.getElementById('mobileFilters');
            filters.classList.toggle('hidden');
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
        
        // Initialize sort icons
        updateSortIcons();
    });
    
    function filterAndSortTickets() {
        // Dapatkan semua baris tabel
        const rows = Array.from(document.querySelectorAll('#ticketsTable tr[data-id]'));
        
        // Terapkan filter pencarian
        const searchTerm = document.getElementById('ticketSearch').value.toLowerCase();
        
        rows.forEach(row => {
            const subject = row.getAttribute('data-subject').toLowerCase();
            const customer = row.getAttribute('data-customer').toLowerCase();
            const project = row.getAttribute('data-project').toLowerCase();
            const priority = row.getAttribute('data-priority').toLowerCase();
            const ticketId = row.querySelector('td:first-child span').textContent.toLowerCase();
            
            // Filter pencarian
            let searchMatch = true;
            if (searchTerm) {
                searchMatch = subject.includes(searchTerm) || 
                             customer.includes(searchTerm) || 
                             project.includes(searchTerm) ||
                             ticketId.includes(searchTerm);
            }
            
            // Filter project
            let projectMatch = true;
            if (currentFilters.project !== 'all') {
                const projectSlug = project.replace(/ /g, '-');
                projectMatch = projectSlug.includes(currentFilters.project);
            }
            
            // Filter priority
            let priorityMatch = true;
            if (currentFilters.priority !== 'all') {
                priorityMatch = priority.includes(currentFilters.priority);
            }
            
            // Tampilkan/sembunyikan baris
            row.style.display = (searchMatch && projectMatch && priorityMatch) ? '' : 'none';
        });
        
        // Sortir baris yang terlihat
        sortVisibleRows();
        
        // Update count
        updateRowCount();
    }
    
    function sortVisibleRows() {
        const tbody = document.getElementById('ticketsTable');
        const rows = Array.from(tbody.querySelectorAll('tr[data-id]'));
        
        rows.sort((a, b) => {
            let aValue, bValue;
            
            switch(currentSort.column) {
                case 'id':
                    aValue = parseInt(a.getAttribute('data-id'));
                    bValue = parseInt(b.getAttribute('data-id'));
                    break;
                case 'subject':
                    aValue = a.getAttribute('data-subject').toLowerCase();
                    bValue = b.getAttribute('data-subject').toLowerCase();
                    break;
                case 'customer':
                    aValue = a.getAttribute('data-customer').toLowerCase();
                    bValue = b.getAttribute('data-customer').toLowerCase();
                    break;
                case 'priority':
                    aValue = parseInt(a.getAttribute('data-priority-value'));
                    bValue = parseInt(b.getAttribute('data-priority-value'));
                    break;
                case 'date':
                    aValue = parseInt(a.getAttribute('data-date'));
                    bValue = parseInt(b.getAttribute('data-date'));
                    break;
                case 'project':
                    aValue = a.getAttribute('data-project').toLowerCase();
                    bValue = b.getAttribute('data-project').toLowerCase();
                    break;
                default:
                    aValue = parseInt(a.getAttribute('data-priority-value'));
                    bValue = parseInt(b.getAttribute('data-priority-value'));
            }
            
            if (currentSort.direction === 'asc') {
                return aValue > bValue ? 1 : -1;
            } else {
                return aValue < bValue ? 1 : -1;
            }
        });
        
        // Reorder rows
        rows.forEach(row => tbody.appendChild(row));
    }
    
    function updateRowCount() {
        const visibleRows = document.querySelectorAll('#ticketsTable tr[data-id][style=""]').length;
        const allRows = document.querySelectorAll('#ticketsTable tr[data-id]').length;
        
        document.getElementById('showingCount').textContent = visibleRows;
        document.getElementById('totalCount').textContent = allRows;
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
    
    function exportReport() {
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Exporting...';
        btn.disabled = true;
        
        // Simulate export process
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            
            // Show success message
            showToast('Report exported successfully!', 'success');
        }, 1500);
    }
    
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed top-24 right-4 p-4 rounded-lg shadow-lg z-50 ${
            type === 'error' ? 'bg-red-500 text-white' : 
            type === 'success' ? 'bg-green-500 text-white' : 
            'bg-blue-500 text-white'
        }`;
        toast.innerHTML = `
            <div class="flex items-center gap-2">
                <i class="fas ${
                    type === 'error' ? 'fa-exclamation-circle' : 
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