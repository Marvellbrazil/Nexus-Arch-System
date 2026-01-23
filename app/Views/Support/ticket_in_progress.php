<?= $this->extend('layouts/support_layout') ?>

<?= $this->section('title') ?>Ticket in Progress - NEXUS Support<?= $this->endSection() ?>

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
    <div class="mb-6 md:mb-8">
        <div class="flex flex-col">
            <h1 class="text-2xl md:text-[35px] font-semibold mb-1 md:mb-[5px] text-text-dark">Ticket in Progress</h1>
            <p class="text-sm md:text-[15px] font-light text-[#666]">Monitor and track all tickets currently being
                processed by departments</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
        <!-- In Progress Card -->
        <div class="bg-gradient-to-r from-[#434264] to-[#56517D] rounded-2xl p-4 md:p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <div class="text-white/80 text-sm md:text-base mb-1">In Progress</div>
                    <div class="text-3xl md:text-5xl font-bold"><?= $stats['in_progress'] ?? 0 ?></div>
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
                    <div class="text-3xl md:text-5xl font-bold"><?= $stats['waiting_customer'] ?? 0 ?></div>
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
                    <div class="text-3xl md:text-5xl font-bold"><?= $stats['resolved_week'] ?? 0 ?></div>
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
                    <input type="text" placeholder="Search tickets..."
                        class="w-full h-12 pl-10 pr-4 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 text-gray-700"
                        id="searchTickets">
                </div>
            </div>

            <!-- Priority Filter -->
            <div class="w-full md:w-48">
                <select id="priorityFilter"
                    class="w-full h-12 bg-gray-50 border border-gray-300 rounded-lg px-4 focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 text-gray-700 appearance-none">
                    <option value="all">All Priority</option>
                    <option value="urgent">Urgent</option>
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div class="w-full md:w-48">
                <select id="statusFilter"
                    class="w-full h-12 bg-gray-50 border border-gray-300 rounded-lg px-4 focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 text-gray-700 appearance-none">
                    <option value="all">All Status</option>
                    <option value="in-progress">In Progress</option>
                    <option value="waiting-customer-reply">Waiting for Customer</option>
                    <option value="resolved">Resolved</option>
                    <option value="pending">Pending</option>
                    <option value="forwarded">Forwarded</option>
                </select>
            </div>

            <!-- Department Filter -->
            <div class="w-full md:w-56">
                <select id="departmentFilter"
                    class="w-full h-12 bg-gray-100 border border-gray-300 rounded-lg px-4 focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 text-gray-700 appearance-none">
                    <option value="all">All Departments</option>
                    <?php
                    // Get unique departments from tickets
                    $departments = [];
                    if (!empty($tickets)) {
                        foreach ($tickets as $ticket) {
                            if (!empty($ticket['department_name']) && !in_array($ticket['department_name'], $departments)) {
                                $departments[] = $ticket['department_name'];
                            }
                        }
                    }
                    // Fallback departments if none found
                    if (empty($departments)) {
                        $departments = ['Technical Support', 'IT Infrastructure', 'Development', 'Quality Assurance', 'UI/UX Design'];
                    }
                    sort($departments);
                    ?>
                    <?php foreach ($departments as $dept): ?>
                        <?php $deptSlug = strtolower(str_replace(' ', '-', $dept)); ?>
                        <option value="<?= $deptSlug ?>"><?= $dept ?></option>
                    <?php endforeach; ?>
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
                    <?php
                    // Determine priority
                    $priority = strtolower($ticket['priority_name'] ?? 'medium');
                    $priorityColor = 'bg-gray-100 text-gray-800';
                    $priorityText = $ticket['priority_name'] ?? 'Medium';

                    switch ($priority) {
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

                    // Determine status
                    $status = strtolower(str_replace(' ', '-', $ticket['status_name'] ?? 'in-progress'));
                    $statusColor = 'bg-gray-100 text-gray-800';
                    $statusText = $ticket['status_name'] ?? 'In Progress';

                    switch ($status) {
                        case 'in-progress':
                        case 'processing':
                            $statusColor = 'bg-[#434264] text-white';
                            break;
                        case 'waiting-customer-reply':
                        case 'waiting-customer':
                            $statusColor = 'bg-[#817CB2] text-white';
                            $statusText = 'Waiting for Customer';
                            break;
                        case 'resolved':
                        case 'closed':
                            $statusColor = 'bg-[#ABA0C8] text-white';
                            break;
                        case 'pending':
                            $statusColor = 'bg-yellow-100 text-yellow-800';
                            break;
                        case 'forwarded':
                            $statusColor = 'bg-purple-100 text-purple-800';
                            break;
                    }

                    // Determine department slug
                    $department = !empty($ticket['department_name'])
                        ? strtolower(str_replace(' ', '-', $ticket['department_name']))
                        : 'technical';

                    // Format time ago
                    $created = new DateTime($ticket['created_at'] ?? date('Y-m-d H:i:s'));
                    $now = new DateTime();
                    $interval = $now->diff($created);

                    if ($interval->days == 0 && $interval->h == 0 && $interval->i < 1) {
                        $timeAgo = 'Just now';
                    } elseif ($interval->days == 0 && $interval->h == 0) {
                        $timeAgo = $interval->i . ' minutes ago';
                    } elseif ($interval->days == 0 && $interval->h == 1) {
                        $timeAgo = '1 hour ago';
                    } elseif ($interval->days == 0) {
                        $timeAgo = $interval->h . ' hours ago';
                    } elseif ($interval->days == 1) {
                        $timeAgo = 'Yesterday, ' . $created->format('H:i');
                    } else {
                        $timeAgo = $created->format('M d, H:i');
                    }
                    ?>
                    <div class="p-4 md:p-6 hover:bg-gray-50 transition-colors ticket-card" data-priority="<?= $priority ?>"
                        data-status="<?= $status ?>" data-department="<?= $department ?>" data-search="<?= htmlspecialchars(strtolower(
                                ($ticket['subject'] ?? '') . ' ' .
                                ($ticket['customer_name'] ?? '') . ' ' .
                                ($ticket['department_name'] ?? '') . ' ' .
                                ($ticket['project_name'] ?? '') . ' ' .
                                ($ticket['ticket_id'] ?? '')
                            )) ?>">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <!-- Left Section -->
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-2">
                                    <span class="font-bold text-gray-800 text-base md:text-lg">
                                        Ticket #<?= $ticket['ticket_id'] ?? '' ?>
                                    </span>
                                    <span class="px-2 py-1 <?= $priorityColor ?> text-xs rounded-full font-medium">
                                        <?= $priorityText ?>
                                    </span>
                                    <span class="px-3 py-1 <?= $statusColor ?> text-xs rounded-full font-medium">
                                        <?= $statusText ?>
                                    </span>
                                </div>
                                <h3 class="text-gray-800 font-semibold text-base md:text-lg mb-1">
                                    <?= htmlspecialchars($ticket['subject'] ?? 'No Subject') ?>
                                </h3>
                                <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600">
                                    <?php if (!empty($ticket['customer_name'])): ?>
                                        <span>
                                            <i class="fas fa-user mr-1"></i>
                                            <?= htmlspecialchars($ticket['customer_name']) ?>
                                        </span>
                                    <?php endif; ?>

                                    <?php if (!empty($ticket['project_name'])): ?>
                                        <span class="hidden md:inline">•</span>
                                        <span>
                                            <i class="fas fa-project-diagram mr-1"></i>
                                            <?= htmlspecialchars($ticket['project_name']) ?>
                                        </span>
                                    <?php endif; ?>

                                    <?php if (!empty($ticket['assigned_to_name'])): ?>
                                        <span class="hidden md:inline">•</span>
                                        <span>
                                            <i class="fas fa-user-check mr-1"></i>
                                            <?= htmlspecialchars($ticket['assigned_to_name']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Middle Section -->
                            <div class="flex flex-col md:items-center gap-2">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-clock text-gray-400 text-sm"></i>
                                    <span class="text-gray-600 text-sm" title="<?= $ticket['created_at'] ?? '' ?>">
                                        <?= $timeAgo ?>
                                    </span>
                                </div>
                                <?php if (!empty($ticket['department_name'])): ?>
                                    <div class="text-gray-500 text-xs">
                                        <i class="fas fa-building mr-1"></i>
                                        <?= htmlspecialchars($ticket['department_name']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Right Section -->
                            <div class="flex flex-col sm:flex-row gap-2">
<!-- Di file ticket_in_progress.php, update tombol Department Chat: -->
<?php if (!empty($ticket['department_id'])): ?>
    <a href="<?= base_url('support/department_ticket_detail/' . ($ticket['ticket_id'] ?? '')) ?>"
        class="px-3 py-2 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium text-sm flex items-center justify-center gap-2 min-w-[140px]">
        <i class="fas fa-comments"></i>
        <span>Department Chat</span>
    </a>
<?php else: ?>
    <button onclick="assignDepartment(<?= $ticket['ticket_id'] ?? 0 ?>)"
        class="px-3 py-2 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium text-sm flex items-center justify-center gap-2 min-w-[140px]">
        <i class="fas fa-share-alt"></i>
        <span>Assign Department</span>
    </button>
<?php endif; ?>

                                <a href="<?= base_url('support/ticket_detail/' . ($ticket['ticket_id'] ?? '')) ?>"
                                    class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm flex items-center justify-center gap-2 min-w-[120px]">
                                    <i class="fas fa-eye"></i>
                                    <span>View Details</span>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="p-8 md:p-12 text-center">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-4xl text-gray-300"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-800 mb-2">No tickets in progress</h3>
                    <p class="text-gray-600 mb-4">All tickets have been processed or there are no active tickets.</p>
                    <a href="<?= base_url('support/incomingTickets') ?>"
                        class="px-4 py-2 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium text-sm inline-flex items-center gap-2">
                        <i class="fas fa-inbox"></i>
                        Check incoming tickets
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Load More (will be handled by JavaScript) -->
        <?php if (!empty($tickets) && count($tickets) >= 10): ?>
            <div class="p-4 md:p-6 border-t border-gray-200 text-center">
                <button id="loadMoreBtn"
                    class="px-6 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium">
                    Load More Tickets
                </button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Department Performance -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <h2 class="text-lg md:text-xl font-semibold text-gray-800">Department Performance</h2>
        </div>

        <div class="p-4 md:p-6">
            <?php if (!empty($department_stats)): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php
                    $deptColors = [
                        'Technical Support' => ['from-blue-50', 'to-blue-100', 'border-blue-200', 'bg-blue-500'],
                        'Development' => ['from-purple-50', 'to-purple-100', 'border-purple-200', 'bg-purple-500'],
                        'IT Infrastructure' => ['from-green-50', 'to-green-100', 'border-green-200', 'bg-green-500'],
                        'Quality Assurance' => ['from-yellow-50', 'to-yellow-100', 'border-yellow-200', 'bg-yellow-500'],
                        'UI/UX Design' => ['from-pink-50', 'to-pink-100', 'border-pink-200', 'bg-pink-500'],
                        'Sales' => ['from-red-50', 'to-red-100', 'border-red-200', 'bg-red-500'],
                    ];
                    ?>
                    <?php foreach ($department_stats as $dept): ?>
                        <?php
                        $deptName = $dept['department_name'] ?? 'Unknown Department';
                        $color = $deptColors[$deptName] ?? ['from-gray-50', 'to-gray-100', 'border-gray-200', 'bg-gray-500'];
                        ?>
                        <div class="bg-gradient-to-r <?= $color[0] ?> <?= $color[1] ?> rounded-xl p-4 border <?= $color[2] ?>">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="font-semibold text-gray-800"><?= $deptName ?></h3>
                                <span class="px-2 py-1 <?= $color[3] ?> text-white text-xs rounded-full">
                                    <?= $dept['total_tickets'] ?? 0 ?> Tickets
                                </span>
                            </div>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">In Progress</span>
                                    <span class="font-medium"><?= $dept['in_progress'] ?? 0 ?></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Resolved</span>
                                    <span class="font-medium text-green-600"><?= $dept['resolved'] ?? 0 ?></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Avg. Time</span>
                                    <span class="font-medium">
                                        <?php
                                        $avgTime = $dept['avg_time_hours'] ?? 0;
                                        if ($avgTime == 0) {
                                            echo 'N/A';
                                        } elseif ($avgTime < 1) {
                                            echo round($avgTime * 60) . ' min';
                                        } elseif ($avgTime < 24) {
                                            echo round($avgTime, 1) . ' hours';
                                        } else {
                                            echo round($avgTime / 24, 1) . ' days';
                                        }
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-chart-line text-3xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">No department performance data available</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    /* Custom animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
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
    document.addEventListener('DOMContentLoaded', function () {
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

            // Show message if no tickets match filters
            if (visibleCount === 0 && ticketCards.length > 0) {
                const container = document.getElementById('ticketsContainer');
                const existingNoResults = container.querySelector('.no-results-message');

                if (!existingNoResults) {
                    const message = document.createElement('div');
                    message.className = 'no-results-message p-8 text-center';
                    message.innerHTML = `
                    <div class="mb-4">
                        <i class="fas fa-search text-3xl text-gray-300"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-800 mb-2">No tickets found</h3>
                    <p class="text-gray-600">Try adjusting your filters or search terms</p>
                `;

                    // Find the first visible element to insert before
                    let firstVisible = null;
                    for (let card of ticketCards) {
                        if (card.style.display !== 'none') {
                            firstVisible = card;
                            break;
                        }
                    }

                    if (firstVisible) {
                        firstVisible.parentNode.insertBefore(message, firstVisible);
                    } else {
                        container.appendChild(message);
                    }
                }
            } else {
                // Remove no results message if it exists
                const existingMessage = document.querySelector('.no-results-message');
                if (existingMessage) {
                    existingMessage.remove();
                }
            }
        }

        // Add event listeners for filters
        searchInput.addEventListener('input', applyFilters);
        priorityFilter.addEventListener('change', applyFilters);
        statusFilter.addEventListener('change', applyFilters);
        departmentFilter.addEventListener('change', applyFilters);

        // Load more button functionality (AJAX example)
        const loadMoreBtn = document.getElementById('loadMoreBtn');
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function () {
                loadMoreTickets();
            });
        }

        // Function to load more tickets via AJAX
        function loadMoreTickets() {
            const btn = loadMoreBtn;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Loading...';
            btn.disabled = true;

            // Get current filter values
            const filters = {
                search: searchInput.value,
                priority: priorityFilter.value,
                status: statusFilter.value,
                department: departmentFilter.value,
                offset: ticketCards.length
            };

            // Send AJAX request
            fetch('<?= base_url("support/loadMoreTickets") ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(filters)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.tickets && data.tickets.length > 0) {
                        // Add new tickets to container
                        const container = document.getElementById('ticketsContainer');

                        data.tickets.forEach(ticket => {
                            const newCard = createTicketCard(ticket);
                            container.appendChild(newCard);
                        });

                        // Re-apply filters to new tickets
                        applyFilters();

                        // Update ticket cards list
                        ticketCards = document.querySelectorAll('.ticket-card');

                        // Hide load more button if no more tickets
                        if (data.tickets.length < 10) {
                            btn.style.display = 'none';
                        }

                        showToast('Loaded ' + data.tickets.length + ' more tickets', 'success');
                    } else {
                        showToast('No more tickets to load', 'info');
                        btn.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error loading more tickets:', error);
                    showToast('Error loading more tickets', 'error');
                })
                .finally(() => {
                    btn.innerHTML = 'Load More Tickets';
                    btn.disabled = false;
                });
        }

        // Function to create ticket card HTML
        function createTicketCard(ticket) {
            const priorityColor = ticket.priority === 'urgent' ? 'bg-red-100 text-red-800' :
                ticket.priority === 'high' ? 'bg-orange-100 text-orange-800' :
                    ticket.priority === 'medium' ? 'bg-yellow-100 text-yellow-800' :
                        'bg-blue-100 text-blue-800';

            const statusColor = ticket.status === 'in-progress' || ticket.status === 'processing'
                ? 'bg-[#434264] text-white' :
                ticket.status === 'waiting-customer-reply' || ticket.status === 'waiting-customer'
                    ? 'bg-[#817CB2] text-white' :
                    ticket.status === 'resolved' || ticket.status === 'closed'
                        ? 'bg-[#ABA0C8] text-white' :
                        ticket.status === 'pending'
                            ? 'bg-yellow-100 text-yellow-800' :
                            'bg-gray-100 text-gray-800';

            const statusText = ticket.status === 'in-progress' || ticket.status === 'processing'
                ? 'In Progress' :
                ticket.status === 'waiting-customer-reply' || ticket.status === 'waiting-customer'
                    ? 'Waiting for Customer' :
                    ticket.status === 'resolved' || ticket.status === 'closed'
                        ? 'Resolved' :
                        ticket.status.charAt(0).toUpperCase() + ticket.status.slice(1);

            const departmentSlug = ticket.department ?
                ticket.department.toLowerCase().replace(/ /g, '-') : 'technical';

            const card = document.createElement('div');
            card.className = 'p-4 md:p-6 hover:bg-gray-50 transition-colors ticket-card';
            card.dataset.priority = ticket.priority;
            card.dataset.status = ticket.status;
            card.dataset.department = departmentSlug;
            card.dataset.search = (ticket.subject + ' ' + ticket.customer_name + ' ' +
                ticket.department_name + ' ' + ticket.project_name + ' ' +
                ticket.ticket_id).toLowerCase();

            card.innerHTML = `
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-2">
                        <span class="font-bold text-gray-800 text-base md:text-lg">Ticket #${ticket.ticket_id}</span>
                        <span class="px-2 py-1 ${priorityColor} text-xs rounded-full font-medium">
                            ${ticket.priority.charAt(0).toUpperCase() + ticket.priority.slice(1)}
                        </span>
                        <span class="px-3 py-1 ${statusColor} text-xs rounded-full font-medium">
                            ${statusText}
                        </span>
                    </div>
                    <h3 class="text-gray-800 font-semibold text-base md:text-lg mb-1">${ticket.subject}</h3>
                    <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600">
                        ${ticket.customer_name ? `<span><i class="fas fa-user mr-1"></i>${ticket.customer_name}</span>` : ''}
                        ${ticket.project_name ? `<span class="hidden md:inline">•</span><span><i class="fas fa-project-diagram mr-1"></i>${ticket.project_name}</span>` : ''}
                        ${ticket.assigned_to_name ? `<span class="hidden md:inline">•</span><span><i class="fas fa-user-check mr-1"></i>${ticket.assigned_to_name}</span>` : ''}
                    </div>
                </div>
                <div class="flex flex-col md:items-center gap-2">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-clock text-gray-400 text-sm"></i>
                        <span class="text-gray-600 text-sm">${ticket.time_ago}</span>
                    </div>
                    ${ticket.department_name ? `<div class="text-gray-500 text-xs"><i class="fas fa-building mr-1"></i>${ticket.department_name}</div>` : ''}
                </div>
                <div class="flex flex-col sm:flex-row gap-2">
                    ${ticket.department_id ?
                    `<a href="<?= base_url('support/department_conversation/') ?>${ticket.ticket_id}" 
                           class="px-3 py-2 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium text-sm flex items-center justify-center gap-2 min-w-[140px]">
                            <i class="fas fa-comments"></i>
                            <span>Department Chat</span>
                        </a>` :
                    `<button onclick="assignDepartment(${ticket.ticket_id})" 
                                class="px-3 py-2 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium text-sm flex items-center justify-center gap-2 min-w-[140px]">
                            <i class="fas fa-share-alt"></i>
                            <span>Assign Department</span>
                        </button>`
                }
                    <a href="<?= base_url('support/ticket_detail/') ?>${ticket.ticket_id}" 
                       class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm flex items-center justify-center gap-2 min-w-[120px]">
                        <i class="fas fa-eye"></i>
                        <span>View Details</span>
                    </a>
                </div>
            </div>
        `;

            return card;
        }

        // Function to assign department
        window.assignDepartment = function (ticketId) {
            showDepartmentModal(ticketId);
        };

        // Initialize filter count
        applyFilters();
    });

    // Department assignment modal
    function showDepartmentModal(ticketId) {
        // Create modal HTML
        const modal = document.createElement('div');
        modal.id = 'departmentModal';
        modal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4';
        modal.innerHTML = `
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md animate-fade-in">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Assign Department</h3>
                <p class="text-gray-600 text-sm mt-1">Select a department for Ticket #${ticketId}</p>
            </div>
            <div class="p-6">
                <select id="selectDepartment" class="w-full h-12 border border-gray-300 rounded-lg px-4 mb-4 focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20">
                    <option value="">Select Department</option>
                    <option value="1">Technical Support</option>
                    <option value="2">Development</option>
                    <option value="3">IT Infrastructure</option>
                    <option value="4">Quality Assurance</option>
                    <option value="5">UI/UX Design</option>
                    <option value="6">Sales</option>
                </select>
                <textarea id="assignmentNotes" 
                          placeholder="Add notes (optional)" 
                          class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20"
                          rows="3"></textarea>
            </div>
            <div class="p-6 border-t border-gray-200 flex justify-end gap-3">
                <button onclick="closeModal()" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    Cancel
                </button>
                <button onclick="submitDepartmentAssignment(${ticketId})" class="px-4 py-2 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors">
                    Assign Department
                </button>
            </div>
        </div>
    `;

        document.body.appendChild(modal);
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        const modal = document.getElementById('departmentModal');
        if (modal) {
            modal.remove();
            document.body.style.overflow = 'auto';
        }
    }

    function submitDepartmentAssignment(ticketId) {
        const departmentId = document.getElementById('selectDepartment').value;
        const notes = document.getElementById('assignmentNotes').value;

        if (!departmentId) {
            showToast('Please select a department', 'error');
            return;
        }

        fetch('<?= base_url("support/forwardTicket/") ?>' + ticketId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `department_id=${departmentId}&notes=${encodeURIComponent(notes)}`
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Ticket assigned to department successfully!', 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showToast(data.message || 'Error assigning department', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error assigning department', 'error');
            });

        closeModal();
    }

    function showToast(message, type = 'info') {
        // Remove existing toasts
        document.querySelectorAll('.toast-notification').forEach(toast => toast.remove());

        const toast = document.createElement('div');
        toast.className = `toast-notification fixed top-24 right-6 p-4 rounded-xl shadow-xl z-[9999] max-w-sm animate-fade-in ${type === 'error' ? 'bg-red-500 text-white border-l-4 border-red-600' :
            type === 'success' ? 'bg-green-500 text-white border-l-4 border-green-600' :
                'bg-blue-500 text-white border-l-4 border-blue-600'
            }`;
        toast.innerHTML = `
        <div class="flex items-center gap-3">
            <i class="fas ${type === 'error' ? 'fa-exclamation-circle text-xl' :
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