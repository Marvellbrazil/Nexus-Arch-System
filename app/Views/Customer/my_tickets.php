<?= $this->extend('layouts/customer_layout') ?>

<?= $this->section('title') ?>My Tickets - NEXUS<?= $this->endSection() ?>

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
                    <p class="text-xl md:text-2xl font-bold text-gray-800"><?= $data['stats']['total_tickets'] ?></p>
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
                    <p class="text-xl md:text-2xl font-bold text-gray-800"><?= $data['stats']['open_tickets'] ?></p>
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
                    <p class="text-xl md:text-2xl font-bold text-gray-800"><?= $data['stats']['resolved_tickets'] ?></p>
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
                    <p class="text-xl md:text-2xl font-bold text-gray-800"><?= $data['stats']['tickets_this_month'] ?>
                    </p>
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
                <!-- GANTI semua link sorting dengan kode berikut: -->

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
                <tbody id="ticketsTable" class="divide-y divide-gray-200">
                    <?php if (!empty($data['tickets'])): ?>
                        <?php foreach ($data['tickets'] as $ticket): ?>
                            <tr class="bg-white hover:bg-gray-100 transition-colors">
                                <td class="py-3 px-3 md:py-4 md:px-6">
                                    <span class="font-bold text-gray-800 text-sm md:text-base"><?= esc($ticket['id']) ?></span>
                                </td>
                                <td class="py-3 px-3 md:py-4 md:px-6">
                                    <div>
                                        <p class="font-medium text-gray-800 text-sm truncate max-w-[150px] md:max-w-none">
                                            <?= esc($ticket['subject']) ?>
                                        </p>
                                        <p class="text-gray-500 text-xs mt-1 hidden md:block">
                                            Last updated: <?= getRelativeTime($ticket['timestamp']) ?>
                                        </p>
                                    </div>
                                </td>
                                <td class="py-3 px-3 md:py-4 md:px-6 hidden md:table-cell">
                                    <span class="text-gray-700 text-sm"><?= esc($ticket['project_name'] ?? 'N/A') ?></span>
                                </td>
                                <td class="py-3 px-3 md:py-4 md:px-6">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full <?= $ticket['priorityColor'] ?> font-medium whitespace-nowrap">
                                        <?= esc($ticket['priority_name']) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-3 md:py-4 md:px-6 hidden sm:table-cell">
                                    <span class="text-gray-600 text-sm"><?= $ticket['time'] ?></span>
                                </td>
                                <td class="py-3 px-3 md:py-4 md:px-6">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full <?= $ticket['statusColor'] ?> font-medium whitespace-nowrap">
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

<script>
    // Helper function to build query string
    function buildQueryString(excludeParams = []) {
        const params = new URLSearchParams(window.location.search);
        excludeParams.forEach(param => params.delete(param));
        return params.toString() ? '&' + params.toString() : '';
    }

    // Get relative time for last updated
    function getRelativeTime(timestamp) {
        const now = Math.floor(Date.now() / 1000);
        const diff = now - timestamp;

        if (diff < 60) return 'just now';
        if (diff < 3600) return Math.floor(diff / 60) + ' min ago';
        if (diff < 86400) return Math.floor(diff / 3600) + ' hours ago';
        if (diff < 604800) return Math.floor(diff / 86400) + ' days ago';
        return Math.floor(diff / 604800) + ' weeks ago';
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Mobile filter toggle
        document.getElementById('mobileFilterBtn').addEventListener('click', function () {
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
                                location.reload();
                            } else {
                                alert(data.message || 'Failed to cancel ticket');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred');
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

        // Update every minute
        updateLastUpdatedTimes();
        setInterval(updateLastUpdatedTimes, 60000);
    });
</script>

<?= $this->endSection() ?>

<?php
// Helper function to build query string for links
helper('url');
function buildQueryString($excludeParams = [])
{
    $request = \Config\Services::request();
    $queryParams = $request->getGet();

    foreach ($excludeParams as $param) {
        unset($queryParams[$param]);
    }

    return $queryParams ? '&' . http_build_query($queryParams) : '';
}

// Helper function for relative time
function getRelativeTime($timestamp)
{
    $now = time();
    $diff = $now - $timestamp;

    if ($diff < 60)
        return 'just now';
    if ($diff < 3600)
        return floor($diff / 60) . ' min ago';
    if ($diff < 86400)
        return floor($diff / 3600) . ' hours ago';
    if ($diff < 604800)
        return floor($diff / 86400) . ' days ago';
    return floor($diff / 604800) . ' weeks ago';
}
?>