<?= $this->extend('layouts/support_layout') ?>

<?= $this->section('title') ?>Ticket in Progress - NEXUS Support<?= $this->endSection() ?>

<?= $this->section('background_effects') ?>
<!-- Background Effects -->
<div class="fixed w-[40vw] h-[40vw] -right-[10%] -bottom-[10%] rotate-[149deg] bg-gradient-to-r from-[rgba(56.96,44.47,127.72,0.15)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-80 z-0"></div>
<div class="fixed w-[35vw] h-[25vw] -left-[5%] top-[10%] rotate-[8deg] bg-gradient-to-r from-[rgba(65.04,45.10,137.14,0.20)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-70 z-0"></div>
<div class="fixed w-[15vw] h-[20vw] right-[5%] -top-[5%] rotate-[8deg] bg-gradient-to-r from-[rgba(16.56,8.41,46.05,0.12)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-60 z-0"></div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mt-4 md:mt-[77px] p-4 md:p-[30px] relative z-10">
    <!-- Page Header -->
    <div class="mb-6 md:mb-8">
        <div class="flex flex-col">
            <h1 class="text-2xl md:text-[35px] font-semibold mb-1 md:mb-[5px] text-text-dark">Ticket in Progress</h1>
            <p class="text-sm md:text-[15px] font-light text-[#666]">Monitor and track all tickets currently being processed by departments</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
        <!-- In Progress Card -->
        <div class="bg-gradient-to-r from-[#434264] to-[#56517D] rounded-2xl p-4 md:p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <div class="text-white/80 text-sm md:text-base mb-1">In Progress</div>
                    <div class="text-3xl md:text-5xl font-bold"><?= $inProgressCount ?? 6 ?></div>
                </div>
                <div class="w-12 h-12 md:w-16 md:h-16 bg-white/10 rounded-xl flex items-center justify-center">
                    <i class="fas fa-sync-alt text-white text-xl md:text-2xl"></i>
                </div>
            </div>
            <div class="text-white/60 text-xs md:text-sm">
                Actively being worked on by departments
            </div>
        </div>

        <!-- Waiting for Customer Card -->
        <div class="bg-gradient-to-r from-[#817CB2] to-[#9A94D1] rounded-2xl p-4 md:p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <div class="text-white/80 text-sm md:text-base mb-1">Waiting for Customer</div>
                    <div class="text-3xl md:text-5xl font-bold"><?= $waitingCount ?? 3 ?></div>
                </div>
                <div class="w-12 h-12 md:w-16 md:h-16 bg-white/10 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-white text-xl md:text-2xl"></i>
                </div>
            </div>
            <div class="text-white/60 text-xs md:text-sm">
                Awaiting customer response or feedback
            </div>
        </div>

        <!-- Resolved Card -->
        <div class="bg-gradient-to-r from-[#AEA2CA] to-[#C4B8E0] rounded-2xl p-4 md:p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <div class="text-white/80 text-sm md:text-base mb-1">Resolved</div>
                    <div class="text-3xl md:text-5xl font-bold"><?= $resolvedCount ?? 2 ?></div>
                </div>
                <div class="w-12 h-12 md:w-16 md:h-16 bg-white/10 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-white text-xl md:text-2xl"></i>
                </div>
            </div>
            <div class="text-white/60 text-xs md:text-sm">
                Successfully resolved this week
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm border border-gray-200 mb-6 md:mb-8">
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Search Input -->
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                    <input 
                        type="text" 
                        placeholder="Search tickets..."
                        class="w-full h-12 pl-10 pr-4 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 text-gray-700"
                        id="searchTickets"
                    >
                </div>
            </div>

            <!-- Priority Filter -->
            <div class="w-full md:w-48">
                <select id="priorityFilter" class="w-full h-12 bg-gray-50 border border-gray-300 rounded-lg px-4 focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 text-gray-700 appearance-none">
                    <option value="all">All Priority</option>
                    <option value="urgent">Urgent</option>
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div class="w-full md:w-48">
                <select id="statusFilter" class="w-full h-12 bg-gray-50 border border-gray-300 rounded-lg px-4 focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 text-gray-700 appearance-none">
                    <option value="all">All Status</option>
                    <option value="in-progress">In Progress</option>
                    <option value="waiting">Waiting for Customer</option>
                    <option value="resolved">Resolved</option>
                    <option value="pending">Pending Review</option>
                </select>
            </div>

            <!-- Department Filter -->
            <div class="w-full md:w-56">
                <select id="departmentFilter" class="w-full h-12 bg-gray-100 border border-gray-300 rounded-lg px-4 focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 text-gray-700 appearance-none">
                    <option value="all">All Departments</option>
                    <option value="technical">Technical Support</option>
                    <option value="it">IT Infrastructure</option>
                    <option value="development">Development</option>
                    <option value="qa">Quality Assurance</option>
                    <option value="uiux">UI/UX Design</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tickets List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
        <!-- Table Header -->
        <div class="p-4 md:p-6 border-b border-gray-200 bg-gray-50">
            <div class="flex flex-col md:flex-row md:items-center justify-between">
                <h2 class="text-lg md:text-xl font-semibold text-gray-800">Active Tickets</h2>
                <div class="mt-2 md:mt-0 text-sm text-gray-600">
                    <i class="fas fa-filter mr-1"></i>
                    <span id="filterCount"><?= count($tickets ?? []) ?> tickets</span>
                </div>
            </div>
        </div>

        <!-- Tickets Container -->
        <div class="divide-y divide-gray-200" id="ticketsContainer">
            <?php if (!empty($tickets)): ?>
                <?php foreach ($tickets as $ticket): ?>
                    <div class="p-4 md:p-6 hover:bg-gray-50 transition-colors ticket-card" 
                         data-priority="<?= strtolower($ticket['priority_name'] ?? 'medium') ?>"
                         data-status="<?= strtolower($ticket['status_name'] ?? 'in-progress') ?>"
                         data-department="<?= strtolower($ticket['department_name'] ?? 'technical') ?>"
                         data-search="<?= htmlspecialchars(strtolower($ticket['subject'] . ' ' . $ticket['customer_name'] . ' ' . $ticket['department_name'])) ?>">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <!-- Left Section -->
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-2">
                                    <span class="font-bold text-gray-800 text-base md:text-lg">Ticket #<?= $ticket['ticket_id'] ?? '2341' ?></span>
                                    <?php
                                    $priorityColor = 'bg-gray-100 text-gray-800';
                                    $priorityText = $ticket['priority_name'] ?? 'Medium';
                                    switch(strtolower($ticket['priority_name'] ?? 'medium')) {
                                        case 'urgent':
                                            $priorityColor = 'bg-red-100 text-red-800';
                                            break;
                                        case 'high':
                                            $priorityColor = 'bg-orange-100 text-orange-800';
                                            break;
                                        case 'medium':
                                            $priorityColor = 'bg-yellow-100 text-yellow-800';
                                            break;
                                        case 'low':
                                            $priorityColor = 'bg-blue-100 text-blue-800';
                                            break;
                                    }
                                    ?>
                                    <span class="px-2 py-1 <?= $priorityColor ?> text-xs rounded-full font-medium">
                                        <?= $priorityText ?>
                                    </span>
                                    <?php
                                    $statusColor = 'bg-gray-100 text-gray-800';
                                    $statusText = $ticket['status_name'] ?? 'In Progress';
                                    switch(strtolower($ticket['status_name'] ?? 'in-progress')) {
                                        case 'in progress':
                                            $statusColor = 'bg-[#434264] text-white';
                                            break;
                                        case 'waiting for customer':
                                            $statusColor = 'bg-[#817CB2] text-white';
                                            break;
                                        case 'resolved':
                                            $statusColor = 'bg-[#ABA0C8] text-white';
                                            break;
                                        case 'pending':
                                            $statusColor = 'bg-yellow-100 text-yellow-800';
                                            break;
                                    }
                                    ?>
                                    <span class="px-3 py-1 <?= $statusColor ?> text-xs rounded-full font-medium">
                                        <?= $statusText ?>
                                    </span>
                                </div>
                                <h3 class="text-gray-800 font-semibold text-base md:text-lg mb-1">
                                    <?= $ticket['subject'] ?? 'Database Connection Error' ?>
                                </h3>
                                <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600">
                                    <span>
                                        <i class="fas fa-user mr-1"></i>
                                        <?= $ticket['customer_name'] ?? 'John Smith' ?>
                                    </span>
                                    <span class="hidden md:inline">•</span>
                                    <span>
                                        <i class="fas fa-project-diagram mr-1"></i>
                                        <?= $ticket['project_name'] ?? 'Project Alpha' ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Middle Section -->
                            <div class="flex flex-col md:items-center gap-2">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-clock text-gray-400 text-sm"></i>
                                    <span class="text-gray-600 text-sm">
                                        <?php
                                        $created = $ticket['created_at'] ?? date('Y-m-d H:i:s');
                                        echo date('M d, Y', strtotime($created));
                                        ?>
                                    </span>
                                </div>
                                <div class="text-gray-500 text-xs">
                                    <i class="fas fa-building mr-1"></i>
                                    <?= $ticket['department_name'] ?? 'Technical Support' ?>
                                </div>
                            </div>

                            <!-- Right Section -->
                            <div class="flex gap-3">
                                <!-- PERUBAHAN: Link ke halaman conversation antara Support dan Department -->
                                <a href="<?= base_url('support/department_conversation/' . ($ticket['ticket_id'] ?? '2341')) ?>" 
                                   class="px-4 py-2 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium text-sm flex items-center gap-2">
                                    <i class="fas fa-comments"></i>
                                    Department Chat
                                </a>
                                <a href="<?= base_url('support/ticket_detail/' . ($ticket['ticket_id'] ?? '2341')) ?>" 
                                   class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm flex items-center gap-2">
                                    <i class="fas fa-eye"></i>
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Default tickets jika tidak ada data dari controller -->
                <!-- Ticket 1 -->
                <div class="p-4 md:p-6 hover:bg-gray-50 transition-colors ticket-card" 
                     data-priority="urgent" data-status="in-progress" data-department="technical"
                     data-search="new ticket assigned to you database connection error technical support">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-2">
                                <span class="font-bold text-gray-800 text-base md:text-lg">Ticket #2341</span>
                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full font-medium">Urgent</span>
                                <span class="px-3 py-1 bg-[#434264] text-white text-xs rounded-full font-medium">In Progress</span>
                            </div>
                            <h3 class="text-gray-800 font-semibold text-base md:text-lg mb-1">New ticket assigned to Technical Support</h3>
                            <p class="text-gray-600 text-sm md:text-base">Database Connection Error</p>
                        </div>
                        <div class="flex flex-col md:items-center gap-2">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-clock text-gray-400 text-sm"></i>
                                <span class="text-gray-600 text-sm">5 minutes ago</span>
                            </div>
                            <div class="text-gray-500 text-xs">Technical Support</div>
                        </div>
                        <div class="flex gap-3">
                            <a href="<?= base_url('support/department_conversation/2341') ?>" 
                               class="px-4 py-2 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium text-sm flex items-center gap-2">
                                <i class="fas fa-comments"></i>
                                Department Chat
                            </a>
                            <a href="<?= base_url('support/ticket_detail/2341') ?>" 
                               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm flex items-center gap-2">
                                <i class="fas fa-eye"></i>
                                View Details
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Ticket 2 -->
                <div class="p-4 md:p-6 hover:bg-gray-50 transition-colors ticket-card" 
                     data-priority="high" data-status="waiting" data-department="uiux"
                     data-search="ticket updated by customer ui broken on mobile ui/ux design">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-2">
                                <span class="font-bold text-gray-800 text-base md:text-lg">Ticket #2338</span>
                                <span class="px-2 py-1 bg-orange-100 text-orange-800 text-xs rounded-full font-medium">High</span>
                                <span class="px-3 py-1 bg-[#817CB2] text-white text-xs rounded-full font-medium">Waiting for Customer</span>
                            </div>
                            <h3 class="text-gray-800 font-semibold text-base md:text-lg mb-1">Ticket updated by customer</h3>
                            <p class="text-gray-600 text-sm md:text-base">UI broken on mobile</p>
                        </div>
                        <div class="flex flex-col md:items-center gap-2">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-clock text-gray-400 text-sm"></i>
                                <span class="text-gray-600 text-sm">30 minutes ago</span>
                            </div>
                            <div class="text-gray-500 text-xs">UI/UX Design</div>
                        </div>
                        <div class="flex gap-3">
                            <a href="<?= base_url('support/department_conversation/2338') ?>" 
                               class="px-4 py-2 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium text-sm flex items-center gap-2">
                                <i class="fas fa-comments"></i>
                                Department Chat
                            </a>
                            <a href="<?= base_url('support/ticket_detail/2338') ?>" 
                               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm flex items-center gap-2">
                                <i class="fas fa-eye"></i>
                                View Details
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Ticket 3 -->
                <div class="p-4 md:p-6 hover:bg-gray-50 transition-colors ticket-card" 
                     data-priority="medium" data-status="in-progress" data-department="development"
                     data-search="ticket waiting for your response payment page slow development">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-2">
                                <span class="font-bold text-gray-800 text-base md:text-lg">Ticket #2329</span>
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full font-medium">Medium</span>
                                <span class="px-3 py-1 bg-[#434264] text-white text-xs rounded-full font-medium">In Progress</span>
                            </div>
                            <h3 class="text-gray-800 font-semibold text-base md:text-lg mb-1">Ticket waiting for Development response</h3>
                            <p class="text-gray-600 text-sm md:text-base">Payment page slow</p>
                        </div>
                        <div class="flex flex-col md:items-center gap-2">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-clock text-gray-400 text-sm"></i>
                                <span class="text-gray-600 text-sm">1 hour ago</span>
                            </div>
                            <div class="text-gray-500 text-xs">Development</div>
                        </div>
                        <div class="flex gap-3">
                            <a href="<?= base_url('support/department_conversation/2329') ?>" 
                               class="px-4 py-2 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium text-sm flex items-center gap-2">
                                <i class="fas fa-comments"></i>
                                Department Chat
                            </a>
                            <a href="<?= base_url('support/ticket_detail/2329') ?>" 
                               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm flex items-center gap-2">
                                <i class="fas fa-eye"></i>
                                View Details
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Ticket 4 -->
                <div class="p-4 md:p-6 hover:bg-gray-50 transition-colors ticket-card" 
                     data-priority="low" data-status="resolved" data-department="qa"
                     data-search="ticket marked as resolved typo on dashboard page quality assurance">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-2">
                                <span class="font-bold text-gray-800 text-base md:text-lg">Ticket #2315</span>
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full font-medium">Low</span>
                                <span class="px-3 py-1 bg-[#ABA0C8] text-white text-xs rounded-full font-medium">Resolved</span>
                            </div>
                            <h3 class="text-gray-800 font-semibold text-base md:text-lg mb-1">Ticket marked as Resolved by QA</h3>
                            <p class="text-gray-600 text-sm md:text-base">Typo on dashboard page</p>
                        </div>
                        <div class="flex flex-col md:items-center gap-2">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-clock text-gray-400 text-sm"></i>
                                <span class="text-gray-600 text-sm">Today, 09:15</span>
                            </div>
                            <div class="text-gray-500 text-xs">Quality Assurance</div>
                        </div>
                        <div class="flex gap-3">
                            <a href="<?= base_url('support/department_conversation/2315') ?>" 
                               class="px-4 py-2 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium text-sm flex items-center gap-2">
                                <i class="fas fa-comments"></i>
                                Department Chat
                            </a>
                            <a href="<?= base_url('support/ticket_detail/2315') ?>" 
                               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm flex items-center gap-2">
                                <i class="fas fa-eye"></i>
                                View Details
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Ticket 5 -->
                <div class="p-4 md:p-6 hover:bg-gray-50 transition-colors ticket-card" 
                     data-priority="urgent" data-status="in-progress" data-department="it"
                     data-search="server downtime issue api service not responding it infrastructure">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-2">
                                <span class="font-bold text-gray-800 text-base md:text-lg">Ticket #2345</span>
                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full font-medium">Urgent</span>
                                <span class="px-3 py-1 bg-[#434264] text-white text-xs rounded-full font-medium">In Progress</span>
                            </div>
                            <h3 class="text-gray-800 font-semibold text-base md:text-lg mb-1">Server downtime issue with IT</h3>
                            <p class="text-gray-600 text-sm md:text-base">API service not responding</p>
                        </div>
                        <div class="flex flex-col md:items-center gap-2">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-clock text-gray-400 text-sm"></i>
                                <span class="text-gray-600 text-sm">2 hours ago</span>
                            </div>
                            <div class="text-gray-500 text-xs">IT Infrastructure</div>
                        </div>
                        <div class="flex gap-3">
                            <a href="<?= base_url('support/department_conversation/2345') ?>" 
                               class="px-4 py-2 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium text-sm flex items-center gap-2">
                                <i class="fas fa-comments"></i>
                                Department Chat
                            </a>
                            <a href="<?= base_url('support/ticket_detail/2345') ?>" 
                               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm flex items-center gap-2">
                                <i class="fas fa-eye"></i>
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Load More -->
        <div class="p-4 md:p-6 border-t border-gray-200 text-center">
            <button id="loadMoreBtn" class="px-6 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium">
                Load More Tickets
            </button>
        </div>
    </div>

    <!-- Department Performance -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <h2 class="text-lg md:text-xl font-semibold text-gray-800">Department Performance</h2>
        </div>
        
        <div class="p-4 md:p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Technical Support -->
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-semibold text-gray-800">Technical Support</h3>
                        <span class="px-2 py-1 bg-blue-500 text-white text-xs rounded-full">8 Tickets</span>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">In Progress</span>
                            <span class="font-medium">4</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Resolved</span>
                            <span class="font-medium text-green-600">3</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Avg. Time</span>
                            <span class="font-medium">2.5 hours</span>
                        </div>
                    </div>
                </div>

                <!-- Development -->
                <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-semibold text-gray-800">Development</h3>
                        <span class="px-2 py-1 bg-purple-500 text-white text-xs rounded-full">6 Tickets</h3>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">In Progress</span>
                            <span class="font-medium">3</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Resolved</span>
                            <span class="font-medium text-green-600">2</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Avg. Time</span>
                            <span class="font-medium">4 hours</span>
                        </div>
                    </div>
                </div>

                <!-- IT Infrastructure -->
                <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-semibold text-gray-800">IT Infrastructure</h3>
                        <span class="px-2 py-1 bg-green-500 text-white text-xs rounded-full">5 Tickets</span>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">In Progress</span>
                            <span class="font-medium">2</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Resolved</span>
                            <span class="font-medium text-green-600">3</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Avg. Time</span>
                            <span class="font-medium">1.5 hours</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }
    
    .pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    /* Custom scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    
    /* Card hover effects */
    .ticket-card:hover {
        background-color: #f9fafb;
        transform: translateY(-2px);
        transition: all 0.2s ease;
    }
    
    /* Highlight urgent tickets */
    .ticket-card[data-priority="urgent"] {
        border-left: 4px solid #ef4444;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add animations to cards
    const statsCards = document.querySelectorAll('.bg-gradient-to-r');
    statsCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.classList.add('animate-fade-in');
    });
    
    // Add pulse animation to urgent tickets
    document.querySelectorAll('[data-priority="urgent"]').forEach(ticket => {
        const badge = ticket.querySelector('.bg-red-100');
        if (badge) badge.classList.add('pulse');
    });
    
    // Filter functionality
    const searchInput = document.getElementById('searchTickets');
    const priorityFilter = document.getElementById('priorityFilter');
    const statusFilter = document.getElementById('statusFilter');
    const departmentFilter = document.getElementById('departmentFilter');
    const ticketCards = document.querySelectorAll('.ticket-card');
    const filterCount = document.getElementById('filterCount');
    
    function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const priorityValue = priorityFilter.value;
        const statusValue = statusFilter.value;
        const departmentValue = departmentFilter.value;
        
        let visibleCount = 0;
        
        ticketCards.forEach(card => {
            const matchesSearch = searchTerm === '' || 
                card.dataset.search.includes(searchTerm);
            const matchesPriority = priorityValue === 'all' || 
                card.dataset.priority === priorityValue;
            const matchesStatus = statusValue === 'all' || 
                card.dataset.status === statusValue.replace(' ', '-');
            const matchesDepartment = departmentValue === 'all' || 
                card.dataset.department === departmentValue;
            
            if (matchesSearch && matchesPriority && matchesStatus && matchesDepartment) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Update filter count
        if (filterCount) {
            filterCount.textContent = `${visibleCount} tickets`;
        }
        
        // Show/hide load more button based on visible tickets
        const loadMoreBtn = document.getElementById('loadMoreBtn');
        if (loadMoreBtn) {
            loadMoreBtn.style.display = visibleCount === 0 ? 'none' : 'inline-flex';
        }
    }
    
    // Add event listeners for filters
    searchInput.addEventListener('input', applyFilters);
    priorityFilter.addEventListener('change', applyFilters);
    statusFilter.addEventListener('change', applyFilters);
    departmentFilter.addEventListener('change', applyFilters);
    
    // Load more button
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Loading...';
            this.disabled = true;
            
            // Simulate loading more tickets
            setTimeout(() => {
                const ticketsContainer = document.getElementById('ticketsContainer');
                
                // Sample additional tickets
                const sampleTickets = [
                    {
                        id: 2346,
                        subject: 'New feature request',
                        description: 'Export reports as PDF',
                        priority: 'medium',
                        status: 'in-progress',
                        department: 'development',
                        time: 'Just now'
                    },
                    {
                        id: 2347,
                        subject: 'Bug in login system',
                        description: 'Two-factor authentication failing',
                        priority: 'high',
                        status: 'in-progress',
                        department: 'technical',
                        time: '10 minutes ago'
                    },
                    {
                        id: 2348,
                        subject: 'Design system update',
                        description: 'Update color palette',
                        priority: 'low',
                        status: 'waiting',
                        department: 'uiux',
                        time: '45 minutes ago'
                    }
                ];
                
                // Add sample tickets
                sampleTickets.forEach(ticket => {
                    const priorityColor = ticket.priority === 'urgent' ? 'bg-red-100 text-red-800' :
                                         ticket.priority === 'high' ? 'bg-orange-100 text-orange-800' :
                                         ticket.priority === 'medium' ? 'bg-yellow-100 text-yellow-800' :
                                         'bg-blue-100 text-blue-800';
                    
                    const statusColor = ticket.status === 'in-progress' ? 'bg-[#434264] text-white' :
                                        ticket.status === 'waiting' ? 'bg-[#817CB2] text-white' :
                                        ticket.status === 'resolved' ? 'bg-[#ABA0C8] text-white' :
                                        'bg-yellow-100 text-yellow-800';
                    
                    const statusText = ticket.status === 'in-progress' ? 'In Progress' :
                                       ticket.status === 'waiting' ? 'Waiting for Customer' :
                                       ticket.status === 'resolved' ? 'Resolved' : 'Pending';
                    
                    const departmentText = ticket.department === 'technical' ? 'Technical Support' :
                                           ticket.department === 'it' ? 'IT Infrastructure' :
                                           ticket.department === 'development' ? 'Development' :
                                           ticket.department === 'qa' ? 'Quality Assurance' :
                                           ticket.department === 'uiux' ? 'UI/UX Design' : 'General';
                    
                    const newTicket = document.createElement('div');
                    newTicket.className = 'p-4 md:p-6 hover:bg-gray-50 transition-colors ticket-card';
                    newTicket.dataset.priority = ticket.priority;
                    newTicket.dataset.status = ticket.status;
                    newTicket.dataset.department = ticket.department;
                    newTicket.dataset.search = `${ticket.subject.toLowerCase()} ${ticket.description.toLowerCase()} ${departmentText.toLowerCase()}`;
                    
                    newTicket.innerHTML = `
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-2">
                                    <span class="font-bold text-gray-800 text-base md:text-lg">Ticket #${ticket.id}</span>
                                    <span class="px-2 py-1 ${priorityColor} text-xs rounded-full font-medium">
                                        ${ticket.priority.charAt(0).toUpperCase() + ticket.priority.slice(1)}
                                    </span>
                                    <span class="px-3 py-1 ${statusColor} text-xs rounded-full font-medium">
                                        ${statusText}
                                    </span>
                                </div>
                                <h3 class="text-gray-800 font-semibold text-base md:text-lg mb-1">${ticket.subject}</h3>
                                <p class="text-gray-600 text-sm md:text-base">${ticket.description}</p>
                            </div>
                            <div class="flex flex-col md:items-center gap-2">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-clock text-gray-400 text-sm"></i>
                                    <span class="text-gray-600 text-sm">${ticket.time}</span>
                                </div>
                                <div class="text-gray-500 text-xs">${departmentText}</div>
                            </div>
                            <div class="flex gap-3">
                                <a href="<?= base_url('support/department_conversation/') ?>${ticket.id}" 
                                   class="px-4 py-2 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium text-sm flex items-center gap-2">
                                    <i class="fas fa-comments"></i>
                                    Department Chat
                                </a>
                                <a href="<?= base_url('support/ticket_detail/') ?>${ticket.id}" 
                                   class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm flex items-center gap-2">
                                    <i class="fas fa-eye"></i>
                                    View Details
                                </a>
                            </div>
                        </div>
                    `;
                    
                    ticketsContainer.appendChild(newTicket);
                });
                
                // Re-apply filters to new tickets
                applyFilters();
                
                // Reset button
                this.innerHTML = 'Load More Tickets';
                this.disabled = false;
                
                showToast('More tickets loaded successfully!', 'success');
            }, 1000);
        });
    }
    
    // Initialize filter count
    applyFilters();
});

function showToast(message, type = 'info') {
    // Remove existing toasts
    document.querySelectorAll('.toast-notification').forEach(toast => toast.remove());
    
    const toast = document.createElement('div');
    toast.className = `toast-notification fixed top-24 right-6 p-4 rounded-xl shadow-xl z-[9999] max-w-sm animate-fade-in ${
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