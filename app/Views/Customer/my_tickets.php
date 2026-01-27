<?= $this->extend('layouts/customer_layout') ?>

<?= $this->section('title') ?>My Tickets - NEXUS<?= $this->endSection() ?>

<?= $this->section('head') ?>
<!-- Tambahkan script untuk WebSocket -->
<script src="https://cdn.socket.io/4.5.0/socket.io.min.js"></script>
<?= $this->endSection() ?>

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

<?php
// Helper function untuk relative time - DIPINDAHKAN KE ATAS
function getRelativeTimeView($timestamp) {
    $now = time();
    $diff = $now - $timestamp;
    
    if ($diff < 60) return 'just now';
    if ($diff < 3600) return floor($diff / 60) . ' min ago';
    if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
    if ($diff < 604800) return floor($diff / 86400) . ' days ago';
    if ($diff < 2592000) return floor($diff / 604800) . ' weeks ago';
    if ($diff < 31536000) return floor($diff / 2592000) . ' months ago';
    return floor($diff / 31536000) . ' years ago';
}
?>

<?= $this->section('content') ?>
<div class="mt-4 md:mt-[77px] p-4 md:p-[30px] relative z-10">
    <!-- WebSocket Status Indicator -->
    <div id="wsStatus" class="fixed top-4 right-4 z-50 hidden">
        <div class="px-3 py-1 rounded-full text-xs font-medium bg-gray-800 text-white shadow-md flex items-center gap-2">
            <span class="status-dot w-2 h-2 rounded-full"></span>
            <span class="status-text">Connecting...</span>
        </div>
    </div>

    <!-- Notification Toast Container -->
    <div id="notificationContainer" class="fixed top-20 right-4 z-50 space-y-2"></div>

    <!-- Real-time Ticket Updates Indicator -->
    <div id="ticketUpdateIndicator" class="fixed bottom-4 right-4 z-50 hidden">
        <div class="bg-secondary text-white px-4 py-2 rounded-lg shadow-lg flex items-center gap-2">
            <i class="fas fa-sync-alt animate-spin"></i>
            <span class="text-sm">Updating tickets...</span>
        </div>
    </div>

    <!-- Page Header -->
    <div class="mb-6 md:mb-[25px] relative">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex-1">
                <h1 class="text-2xl md:text-[32px] font-semibold mb-1 md:mb-[5px] text-text-dark">My Tickets</h1>
                <p class="text-sm md:text-[15px] font-light text-[#666]">Manage and track your support requests</p>
            </div>

            <!-- Search and Add Ticket -->
            <div class="flex flex-col sm:flex-row gap-3 md:gap-[15px]">
                <form method="get" action="<?= base_url('customer/my_tickets') ?>" class="relative">
                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                    <input type="text" name="search" placeholder="Search tickets..."
                        value="<?= esc($data['current_filters']['search'] ?? '') ?>"
                        class="w-full h-10 md:h-[44px] pl-10 pr-4 bg-white border border-gray-300 rounded-xl text-gray-700 text-sm md:text-[14px] focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20"
                        id="ticketSearch">
                    <!-- Hidden inputs to preserve other filters -->
                    <?php if (isset($data['current_filters']['status']) && $data['current_filters']['status'] !== ''): ?>
                        <input type="hidden" name="status" value="<?= esc($data['current_filters']['status']) ?>">
                    <?php endif; ?>
                    <?php if (isset($data['current_filters']['priority']) && $data['current_filters']['priority'] !== ''): ?>
                        <input type="hidden" name="priority" value="<?= esc($data['current_filters']['priority']) ?>">
                    <?php endif; ?>
                    <?php if (isset($data['current_filters']['project']) && $data['current_filters']['project'] !== ''): ?>
                        <input type="hidden" name="project" value="<?= esc($data['current_filters']['project']) ?>">
                    <?php endif; ?>
                    <?php if (isset($data['current_filters']['sort']) && $data['current_filters']['sort'] !== ''): ?>
                        <input type="hidden" name="sort" value="<?= esc($data['current_filters']['sort']) ?>">
                    <?php endif; ?>
                </form>
                <a href="<?= base_url('customer/create_ticket') ?>"
                    class="h-10 md:h-[44px] px-4 md:px-[20px] bg-secondary text-white rounded-xl flex items-center justify-center gap-2 hover:bg-[#817CB2] transition-colors font-medium text-sm md:text-base">
                    <i class="fas fa-plus"></i>
                    <span>New Ticket</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6 md:mb-8">
        <div class="bg-white rounded-xl p-3 md:p-5 shadow-sm border border-gray-200">
            <div class="flex items-center gap-2 md:gap-3">
                <div class="w-8 h-8 md:w-12 md:h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-ticket-alt text-blue-600 text-sm md:text-base"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-xs md:text-sm">Total Tickets</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800" id="totalTickets"><?= $data['stats']['total_tickets'] ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-3 md:p-5 shadow-sm border border-gray-200">
            <div class="flex items-center gap-2 md:gap-3">
                <div class="w-8 h-8 md:w-12 md:h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-sm md:text-base"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-xs md:text-sm">Open</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800" id="openTickets"><?= $data['stats']['open_tickets'] ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-3 md:p-5 shadow-sm border border-gray-200">
            <div class="flex items-center gap-2 md:gap-3">
                <div class="w-8 h-8 md:w-12 md:h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-sm md:text-base"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-xs md:text-sm">Resolved</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800" id="resolvedTickets"><?= $data['stats']['resolved_tickets'] ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-3 md:p-5 shadow-sm border border-gray-200">
            <div class="flex items-center gap-2 md:gap-3">
                <div class="w-8 h-8 md:w-12 md:h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-purple-600 text-sm md:text-base"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-xs md:text-sm">This Month</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800" id="monthTickets"><?= $data['stats']['tickets_this_month'] ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6 md:mb-8">
        <!-- Table Header -->
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h2 class="text-lg md:text-[20px] font-semibold text-gray-800">Recent Tickets</h2>

                <!-- Filter & Sort Options -->
                <div class="flex flex-wrap gap-2 md:gap-3">
                    <!-- Sort by -->
                    <form method="get" action="<?= base_url('customer/my_tickets') ?>" class="relative" id="sortForm">
                        <select name="sort" onchange="this.form.submit()"
                            class="w-full md:w-auto border border-gray-300 rounded-lg px-3 md:px-4 py-2 text-xs md:text-sm focus:outline-none focus:border-secondary appearance-none bg-white pr-8">
                            <option value="date-desc" <?= ($data['current_filters']['sort'] ?? 'date-desc') == 'date-desc' ? 'selected' : '' ?>>Sort by: Date (Newest)</option>
                            <option value="date-asc" <?= ($data['current_filters']['sort'] ?? '') == 'date-asc' ? 'selected' : '' ?>>Sort by: Date (Oldest)</option>
                            <option value="priority-desc" <?= ($data['current_filters']['sort'] ?? '') == 'priority-desc' ? 'selected' : '' ?>>Sort by: Priority (High to Low)</option>
                            <option value="priority-asc" <?= ($data['current_filters']['sort'] ?? '') == 'priority-asc' ? 'selected' : '' ?>>Sort by: Priority (Low to High)</option>
                            <option value="id-desc" <?= ($data['current_filters']['sort'] ?? '') == 'id-desc' ? 'selected' : '' ?>>Sort by: Ticket ID (Desc)</option>
                            <option value="id-asc" <?= ($data['current_filters']['sort'] ?? '') == 'id-asc' ? 'selected' : '' ?>>Sort by: Ticket ID (Asc)</option>
                        </select>
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </div>
                        <!-- Preserve other filter parameters -->
                        <?php if (isset($data['current_filters']['search']) && $data['current_filters']['search'] !== ''): ?>
                            <input type="hidden" name="search" value="<?= esc($data['current_filters']['search']) ?>">
                        <?php endif; ?>
                        <?php if (isset($data['current_filters']['status']) && $data['current_filters']['status'] !== ''): ?>
                            <input type="hidden" name="status" value="<?= esc($data['current_filters']['status']) ?>">
                        <?php endif; ?>
                        <?php if (isset($data['current_filters']['priority']) && $data['current_filters']['priority'] !== ''): ?>
                            <input type="hidden" name="priority" value="<?= esc($data['current_filters']['priority']) ?>">
                        <?php endif; ?>
                        <?php if (isset($data['current_filters']['project']) && $data['current_filters']['project'] !== ''): ?>
                            <input type="hidden" name="project" value="<?= esc($data['current_filters']['project']) ?>">
                        <?php endif; ?>
                    </form>

                    <!-- Filter Options (Hidden on mobile, shown in dropdown) -->
                    <div class="hidden md:flex gap-2">
                        <form method="get" action="<?= base_url('customer/my_tickets') ?>" id="filterForm">
                            <select name="project" onchange="this.form.submit()"
                                class="border border-gray-300 rounded-lg px-3 md:px-4 py-2 text-xs md:text-sm focus:outline-none focus:border-secondary">
                                <option value="all" <?= ($data['current_filters']['project'] ?? 'all') == 'all' ? 'selected' : '' ?>>All Projects</option>
                                <?php foreach ($data['projects'] as $project): ?>
                                    <option value="<?= $project['project_id'] ?>" <?= ($data['current_filters']['project'] ?? '') == $project['project_id'] ? 'selected' : '' ?>>
                                        <?= esc($project['project_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <select name="status" onchange="this.form.submit()"
                                class="border border-gray-300 rounded-lg px-3 md:px-4 py-2 text-xs md:text-sm focus:outline-none focus:border-secondary">
                                <option value="all" <?= ($data['current_filters']['status'] ?? 'all') == 'all' ? 'selected' : '' ?>>All Status</option>
                                <?php foreach ($data['statuses'] as $status): ?>
                                    <option value="<?= $status['status_name'] ?>" <?= ($data['current_filters']['status'] ?? '') == $status['status_name'] ? 'selected' : '' ?>>
                                        <?= esc($status['status_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <select name="priority" onchange="this.form.submit()"
                                class="border border-gray-300 rounded-lg px-3 md:px-4 py-2 text-xs md:text-sm focus:outline-none focus:border-secondary">
                                <option value="all" <?= ($data['current_filters']['priority'] ?? 'all') == 'all' ? 'selected' : '' ?>>All Priority</option>
                                <?php foreach ($data['priorities'] as $priority): ?>
                                    <option value="<?= $priority['priority_name'] ?>"
                                        <?= ($data['current_filters']['priority'] ?? '') == $priority['priority_name'] ? 'selected' : '' ?>>
                                        <?= esc($priority['priority_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <!-- Preserve other filter parameters -->
                            <?php if (isset($data['current_filters']['search']) && $data['current_filters']['search'] !== ''): ?>
                                <input type="hidden" name="search" value="<?= esc($data['current_filters']['search']) ?>">
                            <?php endif; ?>
                            <?php if (isset($data['current_filters']['sort']) && $data['current_filters']['sort'] !== ''): ?>
                                <input type="hidden" name="sort" value="<?= esc($data['current_filters']['sort']) ?>">
                            <?php endif; ?>
                        </form>
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
                <form method="get" action="<?= base_url('customer/my_tickets') ?>" id="mobileFilterForm">
                    <select name="project" onchange="this.form.submit()"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-secondary">
                        <option value="all" <?= ($data['current_filters']['project'] ?? 'all') == 'all' ? 'selected' : '' ?>>All Projects</option>
                        <?php foreach ($data['projects'] as $project): ?>
                            <option value="<?= $project['project_id'] ?>" <?= ($data['current_filters']['project'] ?? '') == $project['project_id'] ? 'selected' : '' ?>>
                                <?= esc($project['project_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="status" onchange="this.form.submit()"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-secondary">
                        <option value="all" <?= ($data['current_filters']['status'] ?? 'all') == 'all' ? 'selected' : '' ?>>All Status</option>
                        <?php foreach ($data['statuses'] as $status): ?>
                            <option value="<?= $status['status_name'] ?>" <?= ($data['current_filters']['status'] ?? '') == $status['status_name'] ? 'selected' : '' ?>>
                                <?= esc($status['status_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="priority" onchange="this.form.submit()"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-secondary">
                        <option value="all" <?= ($data['current_filters']['priority'] ?? 'all') == 'all' ? 'selected' : '' ?>>All Priority</option>
                        <?php foreach ($data['priorities'] as $priority): ?>
                            <option value="<?= $priority['priority_name'] ?>" <?= ($data['current_filters']['priority'] ?? '') == $priority['priority_name'] ? 'selected' : '' ?>>
                                <?= esc($priority['priority_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <!-- Preserve other filter parameters -->
                    <?php if (isset($data['current_filters']['search']) && $data['current_filters']['search'] !== ''): ?>
                        <input type="hidden" name="search" value="<?= esc($data['current_filters']['search']) ?>">
                    <?php endif; ?>
                    <?php if (isset($data['current_filters']['sort']) && $data['current_filters']['sort'] !== ''): ?>
                        <input type="hidden" name="sort" value="<?= esc($data['current_filters']['sort']) ?>">
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full min-w-max">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700">
                            <?php
                            $currentSort = $data['current_filters']['sort'] ?? '';
                            $newSort = (strpos($currentSort, 'id-asc') !== false) ? 'id-desc' : 'id-asc';
                            $queryString = http_build_query(array_merge(
                                $data['current_filters'],
                                ['sort' => $newSort]
                            ));
                            ?>
                            <a href="<?= base_url('customer/my_tickets?' . $queryString) ?>"
                                class="flex items-center gap-1 no-underline text-gray-700 hover:text-secondary">
                                <span class="hidden sm:inline">Ticket ID</span>
                                <span class="sm:hidden">ID</span>
                                <i class="fas fa-sort text-gray-400 ml-1 text-xs"></i>
                            </a>
                        </th>

                        <th class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700">
                            <?php
                            $newSort = (strpos($currentSort, 'subject-asc') !== false) ? 'subject-desc' : 'subject-asc';
                            $queryString = http_build_query(array_merge(
                                $data['current_filters'],
                                ['sort' => $newSort]
                            ));
                            ?>
                            <a href="<?= base_url('customer/my_tickets?' . $queryString) ?>"
                                class="flex items-center gap-1 no-underline text-gray-700 hover:text-secondary">
                                <span>Subject</span>
                                <i class="fas fa-sort text-gray-400 ml-1 text-xs"></i>
                            </a>
                        </th>

                        <th
                            class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700 hidden md:table-cell">
                            <?php
                            $newSort = (strpos($currentSort, 'project-asc') !== false) ? 'project-desc' : 'project-asc';
                            $queryString = http_build_query(array_merge(
                                $data['current_filters'],
                                ['sort' => $newSort]
                            ));
                            ?>
                            <a href="<?= base_url('customer/my_tickets?' . $queryString) ?>"
                                class="flex items-center gap-1 no-underline text-gray-700 hover:text-secondary">
                                <span>Project</span>
                                <i class="fas fa-sort text-gray-400 ml-1 text-xs"></i>
                            </a>
                        </th>

                        <th class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700">
                            <?php
                            $newSort = (strpos($currentSort, 'priority-asc') !== false) ? 'priority-desc' : 'priority-asc';
                            $queryString = http_build_query(array_merge(
                                $data['current_filters'],
                                ['sort' => $newSort]
                            ));
                            ?>
                            <a href="<?= base_url('customer/my_tickets?' . $queryString) ?>"
                                class="flex items-center gap-1 no-underline text-gray-700 hover:text-secondary">
                                <span class="hidden xs:inline">Priority</span>
                                <span class="xs:hidden">Pri</span>
                                <i class="fas fa-sort text-gray-400 ml-1 text-xs"></i>
                            </a>
                        </th>

                        <th
                            class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700 hidden sm:table-cell">
                            <?php
                            $newSort = (strpos($currentSort, 'date-asc') !== false) ? 'date-desc' : 'date-asc';
                            $queryString = http_build_query(array_merge(
                                $data['current_filters'],
                                ['sort' => $newSort]
                            ));
                            ?>
                            <a href="<?= base_url('customer/my_tickets?' . $queryString) ?>"
                                class="flex items-center gap-1 no-underline text-gray-700 hover:text-secondary">
                                <span>Created</span>
                                <i class="fas fa-sort text-gray-400 ml-1 text-xs"></i>
                            </a>
                        </th>

                        <th class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700">
                            <?php
                            $newSort = (strpos($currentSort, 'status-asc') !== false) ? 'status-desc' : 'status-asc';
                            $queryString = http_build_query(array_merge(
                                $data['current_filters'],
                                ['sort' => $newSort]
                            ));
                            ?>
                            <a href="<?= base_url('customer/my_tickets?' . $queryString) ?>"
                                class="flex items-center gap-1 no-underline text-gray-700 hover:text-secondary">
                                <span>Status</span>
                                <i class="fas fa-sort text-gray-400 ml-1 text-xs"></i>
                            </a>
                        </th>

                        <th class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700">
                            Actions</th>
                    </tr>
                </thead>
                <tbody id="ticketsTableBody" class="divide-y divide-gray-200">
                    <?php if (!empty($data['tickets'])): ?>
                        <?php foreach ($data['tickets'] as $ticket): ?>
                            <tr class="bg-white hover:bg-gray-100 transition-colors" 
                                data-ticket-id="<?= $ticket['ticket_id'] ?>"
                                data-status="<?= $ticket['status_name'] ?>"
                                data-priority="<?= $ticket['priority_name'] ?>">
                                <td class="py-3 px-3 md:py-4 md:px-6">
                                    <span class="font-bold text-gray-800 text-sm md:text-base"><?= esc($ticket['id']) ?></span>
                                </td>
                                <td class="py-3 px-3 md:py-4 md:px-6">
                                    <div>
                                        <p class="font-medium text-gray-800 text-sm truncate max-w-[150px] md:max-w-none">
                                            <?= esc($ticket['subject']) ?>
                                        </p>
                                        <p class="text-gray-500 text-xs mt-1 hidden md:block last-updated" 
                                           data-timestamp="<?= strtotime($ticket['updated_at']) ?>">
                                            Last updated: <?= getRelativeTimeView(strtotime($ticket['updated_at'])) ?>
                                        </p>
                                    </div>
                                </td>
                                <td class="py-3 px-3 md:py-4 md:px-6 hidden md:table-cell">
                                    <span class="text-gray-700 text-sm"><?= esc($ticket['project_name'] ?? 'N/A') ?></span>
                                </td>
                                <td class="py-3 px-3 md:py-4 md:px-6">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full <?= $ticket['priorityColor'] ?> font-medium whitespace-nowrap priority-badge">
                                        <?= esc($ticket['priority_name']) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-3 md:py-4 md:px-6 hidden sm:table-cell">
                                    <span class="text-gray-600 text-sm"><?= getRelativeTimeView($ticket['timestamp']) ?></span>
                                </td>
                                <td class="py-3 px-3 md:py-4 md:px-6">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full <?= $ticket['statusColor'] ?> font-medium whitespace-nowrap status-badge">
                                        <?= esc($ticket['status_name']) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-3 md:py-4 md:px-6">
                                    <div class="flex items-center gap-1 md:gap-2">
                                        <a href="<?= base_url('customer/ticket_detail/' . $ticket['ticket_id']) ?>"
                                            class="px-3 py-1 md:px-4 md:py-2 bg-secondary text-white text-xs md:text-sm rounded-lg hover:bg-[#817CB2] transition-colors whitespace-nowrap">
                                            View
                                        </a>
                                        <div class="relative">
                                            <button class="p-1 md:p-2 text-gray-400 hover:text-gray-600 dropdown-toggle"
                                                data-ticket-id="<?= $ticket['ticket_id'] ?>">
                                                <i class="fas fa-ellipsis-v text-xs"></i>
                                            </button>
                                            <div
                                                class="absolute right-0 mt-1 w-32 bg-white rounded-lg shadow-lg border border-gray-200 z-10 hidden dropdown-menu">
                                                <a href="<?= base_url('customer/ticket_detail/' . $ticket['ticket_id']) ?>"
                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    <i class="fas fa-eye mr-2"></i>View Details
                                                </a>
                                                <?php if ($ticket['status_name'] == 'Open'): ?>
                                                    <a href="#"
                                                        class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 cancel-ticket"
                                                        data-ticket-id="<?= $ticket['ticket_id'] ?>">
                                                        <i class="fas fa-times mr-2"></i>Cancel Ticket
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="py-8 px-4 md:px-6 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-ticket-alt text-2xl md:text-3xl text-gray-300 mb-3"></i>
                                    <p class="text-base md:text-lg font-medium text-gray-400 mb-1">No tickets found</p>
                                    <p class="text-xs md:text-sm text-gray-500">Try adjusting your search or filters</p>
                                    <a href="<?= base_url('customer/create_ticket') ?>"
                                        class="mt-4 px-4 py-2 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors text-sm">
                                        Create Your First Ticket
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if (!empty($data['tickets']) && $data['total_pages'] > 1): ?>
            <div class="p-4 md:p-6 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="text-gray-600 text-xs md:text-sm">
                        Showing <?= (($data['pagination']['current_page'] - 1) * $data['pagination']['per_page']) + 1 ?>
                        to
                        <?= min($data['pagination']['current_page'] * $data['pagination']['per_page'], $data['pagination']['total_items']) ?>
                        of <?= $data['pagination']['total_items'] ?> entries
                    </div>
                    <div class="flex items-center gap-1 md:gap-2">
                        <!-- Previous -->
                        <?php if ($data['pagination']['has_previous']):
                            $prevQuery = array_merge($data['current_filters'], ['page' => $data['pagination']['previous_page']]);
                            ?>
                            <a href="<?= base_url('customer/my_tickets?' . http_build_query($prevQuery)) ?>"
                                class="px-2 md:px-3 py-1 md:py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 text-xs">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        <?php else: ?>
                            <button
                                class="px-2 md:px-3 py-1 md:py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed text-xs"
                                disabled>
                                <i class="fas fa-chevron-left"></i>
                            </button>
                        <?php endif; ?>

                        <!-- Page Numbers -->
                        <?php
                        $startPage = max(1, $data['pagination']['current_page'] - 2);
                        $endPage = min($data['pagination']['total_pages'], $data['pagination']['current_page'] + 2);

                        for ($i = $startPage; $i <= $endPage; $i++):
                            $pageQuery = array_merge($data['current_filters'], ['page' => $i]);
                            ?>
                            <a href="<?= base_url('customer/my_tickets?' . http_build_query($pageQuery)) ?>"
                                class="px-2 md:px-3 py-1 md:py-2 border <?= $data['pagination']['current_page'] == $i ? 'bg-secondary text-white border-secondary' : 'border-gray-300 text-gray-600 hover:bg-gray-50' ?> rounded-lg text-xs md:text-sm">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <!-- Next -->
                        <?php if ($data['pagination']['has_next']):
                            $nextQuery = array_merge($data['current_filters'], ['page' => $data['pagination']['next_page']]);
                            ?>
                            <a href="<?= base_url('customer/my_tickets?' . http_build_query($nextQuery)) ?>"
                                class="px-2 md:px-3 py-1 md:py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 text-xs">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php else: ?>
                            <button
                                class="px-2 md:px-3 py-1 md:py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed text-xs"
                                disabled>
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Help Cards -->
    <div class="grid grid-cols-1">
        <!-- Need Help Card -->
        <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3 md:gap-[15px] mb-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-question-circle text-blue-600 text-lg md:text-xl"></i>
                </div>
                <h3 class="text-lg md:text-[20px] font-semibold text-gray-800">Need Help?</h3>
            </div>
            <p class="text-gray-600 text-sm md:text-base mb-4 md:mb-6">
                If you need assistance, please open a new ticket or search our knowledge base.
            </p>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="<?= base_url('customer/create_ticket') ?>"
                    class="px-4 md:px-6 py-2 md:py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors font-medium text-sm md:text-base text-center">
                    Open Ticket
                </a>
                <button
                    class="px-4 md:px-6 py-2 md:py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm md:text-base"
                    onclick="window.open('https://github.com/Yohan9822/Nexus-Arch-System/blob/production/README.md', '_blank')">
                    Knowledge Base
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* WebSocket Status Styles */
    #wsStatus {
        transition: all 0.3s ease;
    }
    
    #wsStatus.connected .status-dot {
        background-color: #10B981;
        box-shadow: 0 0 10px #10B981;
        animation: pulse 2s infinite;
    }
    
    #wsStatus.disconnected .status-dot {
        background-color: #EF4444;
        box-shadow: 0 0 10px #EF4444;
    }
    
    #wsStatus.connecting .status-dot {
        background-color: #F59E0B;
        box-shadow: 0 0 10px #F59E0B;
        animation: pulse 1s infinite;
    }
    
    /* Notification Toast Styles */
    .notification-toast {
        animation: slideInRight 0.3s ease-out;
        max-width: 400px;
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    /* Ticket update animation */
    .ticket-updated {
        animation: highlight 2s ease-out;
    }
    
    @keyframes highlight {
        0% {
            background-color: rgba(59, 130, 246, 0.1);
        }
        100% {
            background-color: transparent;
        }
    }
    
    /* Status badge colors */
    .status-badge {
        transition: all 0.3s ease;
    }
    
    /* Real-time update indicator */
    #ticketUpdateIndicator {
        animation: slideInUp 0.3s ease-out;
    }
    
    @keyframes slideInUp {
        from {
            transform: translateY(100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ==================== WEBSOCKET INITIALIZATION ====================
    const wsStatus = document.getElementById('wsStatus');
    const ticketUpdateIndicator = document.getElementById('ticketUpdateIndicator');
    let socket = null;
    let reconnectAttempts = 0;
    const maxReconnectAttempts = 5;
    
    // Get user data from PHP session
    const userId = '<?= session()->get('user_id') ?>';
    const userRole = '<?= session()->get('role') ?? 'Customer' ?>';
    const departmentId = '<?= session()->get('department_id') ?? null ?>';
    
    function initializeWebSocket() {
        if (!userId) {
            console.warn('User ID not found, skipping WebSocket connection');
            return;
        }
        
        // WebSocket server URL
        const socketUrl = '<?= env('WS_SERVER_URL', 'http://localhost:3000') ?>';
        
        // Initialize Socket.IO
        socket = io(socketUrl, {
            transports: ['websocket', 'polling'],
            reconnection: true,
            reconnectionAttempts: maxReconnectAttempts,
            reconnectionDelay: 1000,
            reconnectionDelayMax: 5000,
            timeout: 20000,
            auth: {
                user_id: userId,
                role: userRole,
                department_id: departmentId
            }
        });
        
        // Connection established
        socket.on('connect', () => {
            console.log('✅ WebSocket connected with ID:', socket.id);
            updateConnectionStatus('connected', 'Connected');
            reconnectAttempts = 0;
            
            // Send authentication data
            socket.emit('authenticate', {
                user_id: userId,
                role: userRole,
                department_id: departmentId
            });
        });
        
        // Authentication successful
        socket.on('authenticated', (data) => {
            console.log('✅ Authenticated:', data);
        });
        
        // New notification received
        socket.on('new_notification', (notification) => {
            console.log('📢 New notification:', notification);
            handleNewNotification(notification);
        });
        
        // Ticket status updated
        socket.on('ticket_status_changed', (data) => {
            console.log('🔄 Ticket status changed:', data);
            handleTicketStatusUpdate(data);
        });
        
        // New chat message for a ticket
        socket.on('new_message', (message) => {
            console.log('💬 New message for ticket:', message.ticket_id);
            handleNewTicketMessage(message);
        });
        
        // Connection error
        socket.on('connect_error', (error) => {
            console.error('❌ Connection error:', error);
            updateConnectionStatus('disconnected', 'Connection Error');
        });
        
        // Disconnected
        socket.on('disconnect', (reason) => {
            console.log('🔌 Disconnected:', reason);
            updateConnectionStatus('disconnected', 'Disconnected');
            
            // Attempt to reconnect
            if (reason === 'io server disconnect') {
                setTimeout(() => {
                    if (reconnectAttempts < maxReconnectAttempts) {
                        reconnectAttempts++;
                        console.log(`🔄 Attempting to reconnect (${reconnectAttempts}/${maxReconnectAttempts})...`);
                        socket.connect();
                    }
                }, 3000);
            }
        });
        
        // Reconnecting
        socket.on('reconnecting', (attemptNumber) => {
            console.log(`🔄 Reconnecting (${attemptNumber}/${maxReconnectAttempts})...`);
            updateConnectionStatus('connecting', `Reconnecting (${attemptNumber})`);
        });
        
        // Show status after 1 second
        setTimeout(() => {
            wsStatus.classList.remove('hidden');
        }, 1000);
    }
    
    function updateConnectionStatus(status, text) {
        // Remove all status classes
        wsStatus.classList.remove('connected', 'disconnected', 'connecting');
        
        // Add current status class
        wsStatus.classList.add(status);
        
        // Update text
        const statusText = wsStatus.querySelector('.status-text');
        if (statusText) {
            statusText.textContent = text;
        }
    }
    
    // ==================== TICKET STATUS UPDATE HANDLING ====================
    function handleTicketStatusUpdate(data) {
        const { ticket_id, status, updated_by, updated_at } = data;
        
        // Find the ticket row in the table
        const ticketRow = document.querySelector(`tr[data-ticket-id="${ticket_id}"]`);
        
        if (ticketRow) {
            // Update status badge
            const statusBadge = ticketRow.querySelector('.status-badge');
            if (statusBadge) {
                // Remove all existing status classes
                statusBadge.className = 'px-2 py-1 text-xs rounded-full font-medium whitespace-nowrap status-badge';
                
                // Add appropriate color class based on status
                const statusColors = {
                    'Open': 'bg-gray-100 text-gray-800',
                    'In Progress': 'bg-blue-100 text-blue-800',
                    'Resolved': 'bg-green-100 text-green-800',
                    'Closed': 'bg-purple-100 text-purple-800',
                    'Cancelled': 'bg-red-100 text-red-800'
                };
                
                const colorClasses = statusColors[status]?.split(' ') || ['bg-gray-100', 'text-gray-800'];
                colorClasses.forEach(className => {
                    statusBadge.classList.add(className);
                });
                
                statusBadge.textContent = status;
                
                // Update data attribute
                ticketRow.setAttribute('data-status', status);
            }
            
            // Update last updated time
            const lastUpdatedElement = ticketRow.querySelector('.last-updated');
            if (lastUpdatedElement) {
                const timestamp = Math.floor(new Date(updated_at).getTime() / 1000);
                lastUpdatedElement.textContent = `Last updated: ${formatTimeAgo(new Date(updated_at))}`;
                lastUpdatedElement.setAttribute('data-timestamp', timestamp);
            }
            
            // Highlight the row to show update
            ticketRow.classList.add('ticket-updated');
            setTimeout(() => {
                ticketRow.classList.remove('ticket-updated');
            }, 2000);
            
            // Update statistics
            updateTicketStatistics(status);
            
            // Show update indicator
            showTicketUpdateIndicator(`Ticket #${ticket_id} status updated to ${status}`);
        } else {
            // Ticket not in current view, update stats only
            updateTicketStatistics(status);
        }
    }
    
    function handleNewTicketMessage(message) {
        const { ticket_id, sender_id, sender_role, created_at } = message;
        
        // Find the ticket row in the table
        const ticketRow = document.querySelector(`tr[data-ticket-id="${ticket_id}"]`);
        
        if (ticketRow) {
            // Update last updated time
            const lastUpdatedElement = ticketRow.querySelector('.last-updated');
            if (lastUpdatedElement) {
                const timestamp = Math.floor(new Date(created_at).getTime() / 1000);
                lastUpdatedElement.textContent = `New message: ${formatTimeAgo(new Date(created_at))}`;
                lastUpdatedElement.setAttribute('data-timestamp', timestamp);
            }
            
            // Highlight the row
            ticketRow.classList.add('ticket-updated');
            setTimeout(() => {
                ticketRow.classList.remove('ticket-updated');
            }, 2000);
            
            // Show notification
            showTicketUpdateIndicator(`New message in Ticket #${ticket_id}`);
        }
    }
    
    function updateTicketStatistics(newStatus) {
        // Update the statistics cards based on status changes
        const stats = {
            'total_tickets': document.getElementById('totalTickets'),
            'open_tickets': document.getElementById('openTickets'),
            'resolved_tickets': document.getElementById('resolvedTickets'),
            'month_tickets': document.getElementById('monthTickets')
        };
        
        // Get current values
        const currentOpen = parseInt(stats.open_tickets?.textContent || 0);
        const currentResolved = parseInt(stats.resolved_tickets?.textContent || 0);
        const currentTotal = parseInt(stats.total_tickets?.textContent || 0);
        
        // Update based on status change
        if (newStatus === 'Resolved' || newStatus === 'Closed') {
            if (stats.open_tickets && currentOpen > 0) {
                stats.open_tickets.textContent = currentOpen - 1;
            }
            if (stats.resolved_tickets) {
                stats.resolved_tickets.textContent = currentResolved + 1;
            }
        } else if (newStatus === 'Cancelled') {
            if (stats.open_tickets && currentOpen > 0) {
                stats.open_tickets.textContent = currentOpen - 1;
            }
        }
        
        // Animate the update
        Object.values(stats).forEach(element => {
            if (element) {
                element.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    element.style.transform = 'scale(1)';
                }, 300);
            }
        });
    }
    
    function showTicketUpdateIndicator(message) {
        // Show update indicator
        ticketUpdateIndicator.classList.remove('hidden');
        ticketUpdateIndicator.querySelector('span').textContent = message;
        
        // Hide after 3 seconds
        setTimeout(() => {
            ticketUpdateIndicator.classList.add('hidden');
        }, 3000);
    }
    
    // ==================== NOTIFICATION HANDLING ====================
    function handleNewNotification(notification) {
        // Show toast notification
        showNotificationToast(notification);
        
        // Play notification sound
        playNotificationSound();
    }
    
    function showNotificationToast(notification) {
        const container = document.getElementById('notificationContainer');
        if (!container) return;
        
        const toastId = 'toast-' + Date.now();
        const toast = document.createElement('div');
        toast.id = toastId;
        toast.className = 'notification-toast bg-white rounded-lg shadow-lg border border-gray-200 p-4';
        toast.innerHTML = `
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 mt-1">
                    <div class="w-3 h-3 bg-secondary rounded-full"></div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-1">
                        <h4 class="text-sm font-semibold text-gray-800 truncate">${notification.title}</h4>
                        <button onclick="closeToast('${toastId}')" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>
                    <p class="text-xs text-gray-600 mb-2">${notification.message}</p>
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <span>${formatTimeAgo(new Date(notification.created_at))}</span>
                        ${notification.ticket_id ? 
                            `<a href="${baseUrl}/customer/ticket_detail/${notification.ticket_id}" class="text-secondary hover:underline">
                                View Ticket
                            </a>` : ''
                        }
                    </div>
                </div>
            </div>
        `;
        
        container.appendChild(toast);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            closeToast(toastId);
        }, 5000);
    }
    
    function closeToast(toastId) {
        const toast = document.getElementById(toastId);
        if (toast) {
            toast.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => {
                toast.remove();
            }, 300);
        }
    }
    
    function playNotificationSound() {
        // Simple notification sound
        try {
            const audio = new Audio('data:audio/wav;base64,UklGRigAAABXQVZFZm10IBIAAAABAAEAQB8AAEAfAAABAAgAZGF0YQ');
            audio.volume = 0.3;
            audio.play().catch(() => {
                // Ignore errors if audio cannot play
            });
        } catch (error) {
            console.log('Audio playback not supported');
        }
    }
    
    // ==================== HELPER FUNCTIONS ====================
    function formatTimeAgo(date) {
        const now = new Date();
        const diffMs = now - date;
        const diffSec = Math.floor(diffMs / 1000);
        const diffMin = Math.floor(diffSec / 60);
        const diffHour = Math.floor(diffMin / 60);
        const diffDay = Math.floor(diffHour / 24);
        
        if (diffSec < 60) return 'Just now';
        if (diffMin < 60) return `${diffMin} minute${diffMin > 1 ? 's' : ''} ago`;
        if (diffHour < 24) return `${diffHour} hour${diffHour > 1 ? 's' : ''} ago`;
        if (diffDay < 7) return `${diffDay} day${diffDay > 1 ? 's' : ''} ago`;
        if (diffDay < 30) return `${Math.floor(diffDay / 7)} week${Math.floor(diffDay / 7) > 1 ? 's' : ''} ago`;
        if (diffDay < 365) return `${Math.floor(diffDay / 30)} month${Math.floor(diffDay / 30) > 1 ? 's' : ''} ago`;
        return `${Math.floor(diffDay / 365)} year${Math.floor(diffDay / 365) > 1 ? 's' : ''} ago`;
    }
    
    function getRelativeTime(timestamp) {
        const now = Math.floor(Date.now() / 1000);
        const diff = now - timestamp;
        
        if (diff < 60) return 'just now';
        if (diff < 3600) return Math.floor(diff / 60) + ' min ago';
        if (diff < 86400) return Math.floor(diff / 3600) + ' hours ago';
        if (diff < 604800) return Math.floor(diff / 86400) + ' days ago';
        if (diff < 2592000) return Math.floor(diff / 604800) + ' weeks ago';
        if (diff < 31536000) return Math.floor(diff / 2592000) + ' months ago';
        return Math.floor(diff / 31536000) + ' years ago';
    }
    
    // Base URL helper
    const baseUrl = '<?= base_url() ?>';
    
    // Make closeToast globally available
    window.closeToast = closeToast;
    
    // ==================== EVENT LISTENERS ====================
    // Mobile filter toggle
    document.getElementById('mobileFilterBtn')?.addEventListener('click', function () {
        const filters = document.getElementById('mobileFilters');
        filters.classList.toggle('hidden');
    });
    
    // Dropdown menu for ticket actions
    document.querySelectorAll('.dropdown-toggle').forEach(button => {
        button.addEventListener('click', function (e) {
            e.stopPropagation();
            const dropdown = this.nextElementSibling;
            const isVisible = !dropdown.classList.contains('hidden');
            
            // Close all other dropdowns
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
            
            if (!isVisible) {
                dropdown.classList.remove('hidden');
            }
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function () {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.add('hidden');
        });
    });
    
    // Cancel ticket functionality
    document.querySelectorAll('.cancel-ticket').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const ticketId = this.dataset.ticketId;
            
            if (confirm('Are you sure you want to cancel this ticket?')) {
                fetch('<?= base_url("customer/cancel_ticket/") ?>' + ticketId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update status via WebSocket
                        if (socket && socket.connected) {
                            socket.emit('ticket_status_changed', {
                                ticket_id: ticketId,
                                status: 'Cancelled',
                                updated_by: userId
                            });
                        }
                        
                        // Update UI immediately
                        const ticketRow = document.querySelector(`tr[data-ticket-id="${ticketId}"]`);
                        if (ticketRow) {
                            const statusBadge = ticketRow.querySelector('.status-badge');
                            if (statusBadge) {
                                statusBadge.className = 'px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 font-medium whitespace-nowrap status-badge';
                                statusBadge.textContent = 'Cancelled';
                            }
                        }
                        
                        showToast('Ticket cancelled successfully', 'success');
                    } else {
                        showToast(data.message || 'Failed to cancel ticket', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('An error occurred', 'error');
                });
            }
        });
    });
    
    // Real-time search with debounce
    let searchTimeout;
    const searchInput = document.getElementById('ticketSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 500);
        });
    }
    
    // Update last updated times dynamically
    function updateLastUpdatedTimes() {
        document.querySelectorAll('.last-updated').forEach(element => {
            const timestamp = element.dataset.timestamp;
            if (timestamp) {
                element.textContent = 'Last updated: ' + getRelativeTime(parseInt(timestamp));
            }
        });
    }
    
    // Toast function
    function showToast(message, type = 'info') {
        const container = document.getElementById('notificationContainer');
        if (!container) return;
        
        const toastId = 'alert-toast-' + Date.now();
        const toast = document.createElement('div');
        toast.id = toastId;
        
        const colors = {
            success: 'bg-green-500 text-white',
            error: 'bg-red-500 text-white',
            warning: 'bg-yellow-500 text-white',
            info: 'bg-blue-500 text-white'
        };
        
        toast.className = `notification-toast ${colors[type]} rounded-lg shadow-lg p-4`;
        toast.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 
                                  type === 'error' ? 'fa-exclamation-circle' : 
                                  type === 'warning' ? 'fa-exclamation-triangle' : 
                                  'fa-info-circle'}"></i>
                    <span class="text-sm font-medium">${message}</span>
                </div>
                <button onclick="closeToast('${toastId}')" class="text-white/80 hover:text-white">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        `;
        
        container.appendChild(toast);
        
        setTimeout(() => {
            closeToast(toastId);
        }, 3000);
    }
    
    // Initialize WebSocket connection
    initializeWebSocket();
    
    // Initialize time updates
    updateLastUpdatedTimes();
    setInterval(updateLastUpdatedTimes, 60000);
    
    // Handle page visibility change
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden && socket && !socket.connected) {
            console.log('Page visible, reconnecting WebSocket...');
            socket.connect();
        }
    });
});
</script>
<?= $this->endSection() ?>