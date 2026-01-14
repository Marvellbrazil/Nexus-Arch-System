<?= $this->extend('layouts/customer_layout') ?>

<?= $this->section('title') ?>Project Detail - <?= $project['project_name'] ?> - NEXUS<?= $this->endSection() ?>

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
                <div class="flex items-center gap-3 md:gap-4 mb-2">
                    <!-- Project Icon -->
                    <div class="w-12 h-12 md:w-16 md:h-16 rounded-xl bg-gradient-to-br 
                        <?= $project['project_id'] % 6 == 1 ? 'from-blue-500 to-blue-600' : '' ?>
                        <?= $project['project_id'] % 6 == 2 ? 'from-green-500 to-green-600' : '' ?>
                        <?= $project['project_id'] % 6 == 3 ? 'from-purple-500 to-purple-600' : '' ?>
                        <?= $project['project_id'] % 6 == 4 ? 'from-orange-500 to-orange-600' : '' ?>
                        <?= $project['project_id'] % 6 == 5 ? 'from-pink-500 to-pink-600' : '' ?>
                        <?= $project['project_id'] % 6 == 0 ? 'from-red-500 to-red-600' : '' ?>
                        flex items-center justify-center">
                        <i class="fas fa-project-diagram text-white text-xl md:text-2xl"></i>
                    </div>

                    <div>
                        <h1 class="text-2xl md:text-[32px] font-semibold text-text-dark"><?= $project['project_name'] ?>
                        </h1>
                        <p class="text-sm md:text-[15px] font-light text-[#666]"><?= $project['description'] ?></p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 md:gap-[15px]">
                <a href="<?= base_url('customer/create_ticket?project=' . $project['project_id']) ?>"
                    class="h-10 md:h-[44px] px-4 md:px-[20px] bg-secondary text-white rounded-xl flex items-center justify-center gap-2 hover:bg-[#817CB2] transition-colors font-medium text-sm md:text-base">
                    <i class="fas fa-plus"></i>
                    <span>New Ticket</span>
                </a>
                <a href="<?= base_url('customer/dashboard') ?>"
                    class="h-10 md:h-[44px] px-4 md:px-[20px] bg-gray-100 text-gray-700 rounded-xl flex items-center justify-center gap-2 hover:bg-gray-200 transition-colors font-medium text-sm md:text-base">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Dashboard</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Project Stats -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 md:gap-4 mb-6 md:mb-8">
        <!-- Total Tickets -->
        <div class="bg-white rounded-xl p-3 md:p-5 shadow-sm border border-gray-200">
            <div class="flex items-center gap-2 md:gap-3">
                <div class="w-8 h-8 md:w-12 md:h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-ticket-alt text-blue-600 text-sm md:text-base"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-xs md:text-sm">Total Tickets</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800"><?= $project['total_tickets'] ?? 0 ?></p>
                </div>
            </div>
        </div>

        <!-- Open Tickets -->
        <div class="bg-white rounded-xl p-3 md:p-5 shadow-sm border border-gray-200">
            <div class="flex items-center gap-2 md:gap-3">
                <div class="w-8 h-8 md:w-12 md:h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-gray-600 text-sm md:text-base"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-xs md:text-sm">Open</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800"><?= $project['open_tickets'] ?? 0 ?></p>
                </div>
            </div>
        </div>

        <!-- In Progress -->
        <div class="bg-white rounded-xl p-3 md:p-5 shadow-sm border border-gray-200">
            <div class="flex items-center gap-2 md:gap-3">
                <div class="w-8 h-8 md:w-12 md:h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-spinner text-blue-600 text-sm md:text-base"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-xs md:text-sm">In Progress</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800"><?= $project['in_progress_tickets'] ?? 0 ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Resolved -->
        <div class="bg-white rounded-xl p-3 md:p-5 shadow-sm border border-gray-200">
            <div class="flex items-center gap-2 md:gap-3">
                <div class="w-8 h-8 md:w-12 md:h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-sm md:text-base"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-xs md:text-sm">Resolved</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800"><?= $project['resolved_tickets'] ?? 0 ?></p>
                </div>
            </div>
        </div>

        <!-- Closed -->
        <div class="bg-white rounded-xl p-3 md:p-5 shadow-sm border border-gray-200">
            <div class="flex items-center gap-2 md:gap-3">
                <div class="w-8 h-8 md:w-12 md:h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-archive text-purple-600 text-sm md:text-base"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-xs md:text-sm">Closed</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800"><?= $project['closed_tickets'] ?? 0 ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
        <!-- Tickets List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Table Header -->
                <div class="p-4 md:p-6 border-b border-gray-200">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <h2 class="text-lg md:text-[20px] font-semibold text-gray-800">Project Tickets</h2>

                        <!-- Filter Options -->
                        <div class="flex gap-2 md:gap-3">
                            <select id="filterStatus"
                                class="border border-gray-300 rounded-lg px-3 md:px-4 py-2 text-xs md:text-sm focus:outline-none focus:border-secondary">
                                <option value="all">All Status</option>
                                <option value="open">Open</option>
                                <option value="in-progress">In Progress</option>
                                <option value="resolved">Resolved</option>
                                <option value="closed">Closed</option>
                            </select>

                            <select id="filterPriority"
                                class="border border-gray-300 rounded-lg px-3 md:px-4 py-2 text-xs md:text-sm focus:outline-none focus:border-secondary">
                                <option value="all">All Priority</option>
                                <option value="urgent">Urgent</option>
                                <option value="high">High</option>
                                <option value="medium">Medium</option>
                                <option value="low">Low</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Tickets Table -->
                <div class="overflow-x-auto">
                    <table class="w-full min-w-max">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700">
                                    Ticket ID
                                </th>
                                <th
                                    class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700">
                                    Subject
                                </th>
                                <th
                                    class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700 hidden md:table-cell">
                                    Priority
                                </th>
                                <th
                                    class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700">
                                    Status
                                </th>
                                <th
                                    class="py-3 px-3 md:py-4 md:px-6 text-left text-xs md:text-sm font-semibold text-gray-700">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody id="ticketsTableBody">
                            <?php if (empty($tickets)): ?>
                                <tr>
                                    <td colspan="5" class="py-8 px-4 md:px-6 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-ticket-alt text-2xl md:text-3xl text-gray-300 mb-3"></i>
                                            <p class="text-base md:text-lg font-medium text-gray-400 mb-1">No tickets found
                                            </p>
                                            <p class="text-xs md:text-sm text-gray-500">Create your first ticket for this
                                                project</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($tickets as $ticket): ?>
                                    <?php
                                    // Determine priority color
                                    $priorityColor = 'bg-gray-100 text-gray-800';
                                    if ($ticket['priority_name'] == 'Urgent' || $ticket['priority_name'] == 'High') {
                                        $priorityColor = 'bg-red-100 text-red-800';
                                    } elseif ($ticket['priority_name'] == 'Medium') {
                                        $priorityColor = 'bg-yellow-100 text-yellow-800';
                                    } elseif ($ticket['priority_name'] == 'Low') {
                                        $priorityColor = 'bg-blue-100 text-blue-800';
                                    }

                                    // Determine status color
                                    $statusColor = 'bg-gray-100 text-gray-800';
                                    if ($ticket['status_name'] == 'Open') {
                                        $statusColor = 'bg-gray-100 text-gray-800';
                                    } elseif ($ticket['status_name'] == 'In Progress') {
                                        $statusColor = 'bg-blue-100 text-blue-800';
                                    } elseif ($ticket['status_name'] == 'Resolved') {
                                        $statusColor = 'bg-green-100 text-green-800';
                                    } elseif ($ticket['status_name'] == 'Closed') {
                                        $statusColor = 'bg-purple-100 text-purple-800';
                                    }
                                    ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="py-3 px-3 md:py-4 md:px-6">
                                            <span
                                                class="font-bold text-gray-800 text-sm md:text-base">#<?= $ticket['ticket_id'] ?></span>
                                        </td>
                                        <td class="py-3 px-3 md:py-4 md:px-6">
                                            <div>
                                                <p
                                                    class="font-medium text-gray-800 text-sm truncate max-w-[150px] md:max-w-none">
                                                    <?= $ticket['subject'] ?></p>
                                                <p class="text-gray-500 text-xs mt-1">
                                                    <?php
                                                    $createdAt = new DateTime($ticket['created_at']);
                                                    echo $createdAt->format('M d, Y');
                                                    ?>
                                                </p>
                                            </div>
                                        </td>
                                        <td class="py-3 px-3 md:py-4 md:px-6 hidden md:table-cell">
                                            <span class="px-2 py-1 text-xs rounded-full <?= $priorityColor ?> font-medium">
                                                <?= $ticket['priority_name'] ?>
                                            </span>
                                        </td>
                                        <td class="py-3 px-3 md:py-4 md:px-6">
                                            <span class="px-2 py-1 text-xs rounded-full <?= $statusColor ?> font-medium">
                                                <?= $ticket['status_name'] ?>
                                            </span>
                                        </td>
                                        <td class="py-3 px-3 md:py-4 md:px-6">
                                            <div class="flex items-center gap-1 md:gap-2">
                                                <a href="<?= base_url('customer/ticket_detail/' . $ticket['ticket_id']) ?>"
                                                    class="px-3 py-1 md:px-4 md:py-2 bg-secondary text-white text-xs md:text-sm rounded-lg hover:bg-[#817CB2] transition-colors whitespace-nowrap">
                                                    View
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Project Info Sidebar -->
        <div class="space-y-4 md:space-y-6">
            <!-- Project Details Card -->
            <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm border border-gray-200">
                <h3 class="text-lg md:text-[18px] font-semibold text-gray-800 mb-3 md:mb-4">Project Details</h3>

                <div class="space-y-3 md:space-y-4">
                    <div class="flex items-start gap-2 md:gap-3">
                        <div
                            class="w-6 h-6 md:w-8 md:h-8 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-calendar text-gray-600 text-xs md:text-sm"></i>
                        </div>
                        <div>
                            <p class="text-gray-600 text-xs md:text-sm mb-1">Created On</p>
                            <p class="text-gray-800 text-sm md:text-base font-medium">
                                <?php
                                $createdAt = new DateTime($project['created_at'] ?? date('Y-m-d H:i:s'));
                                echo $createdAt->format('F d, Y');
                                ?>
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-2 md:gap-3">
                        <div
                            class="w-6 h-6 md:w-8 md:h-8 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user text-gray-600 text-xs md:text-sm"></i>
                        </div>
                        <div>
                            <p class="text-gray-600 text-xs md:text-sm mb-1">Project Owner</p>
                            <p class="text-gray-800 text-sm md:text-base font-medium">Admin Team</p>
                        </div>
                    </div>

                    <?php if (!empty($project['project_code'])): ?>
                        <div class="flex items-start gap-2 md:gap-3">
                            <div
                                class="w-6 h-6 md:w-8 md:h-8 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-code text-gray-600 text-xs md:text-sm"></i>
                            </div>
                            <div>
                                <p class="text-gray-600 text-xs md:text-sm mb-1">Project Code</p>
                                <p class="text-gray-800 text-sm md:text-base font-medium"><?= $project['project_code'] ?>
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Team Members Card -->
            <?php if (!empty($team_members)): ?>
                <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm border border-gray-200">
                    <h3 class="text-lg md:text-[18px] font-semibold text-gray-800 mb-3 md:mb-4">Team Members</h3>

                    <div class="space-y-3 md:space-y-4">
                        <?php foreach ($team_members as $member): ?>
                            <div
                                class="flex items-center gap-2 md:gap-3 p-2 md:p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <div
                                    class="w-8 h-8 md:w-10 md:h-10 bg-secondary rounded-full flex items-center justify-center text-white text-sm md:text-base font-bold">
                                    <?= substr($member['full_name'], 0, 1) ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-gray-800 text-sm md:text-base font-medium truncate">
                                        <?= $member['full_name'] ?></p>
                                    <p class="text-gray-500 text-xs md:text-sm"><?= $member['role_name'] ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Recent Activity Card -->
            <?php if (!empty($recent_activity)): ?>
                <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm border border-gray-200">
                    <h3 class="text-lg md:text-[18px] font-semibold text-gray-800 mb-3 md:mb-4">Recent Activity</h3>

                    <div class="space-y-3 md:space-y-4">
                        <?php foreach ($recent_activity as $activity): ?>
                            <div
                                class="flex items-start gap-2 md:gap-3 p-2 md:p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <div
                                    class="w-6 h-6 md:w-8 md:h-8 bg-secondary/10 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-comment text-secondary text-xs md:text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-gray-800 text-xs md:text-sm font-medium truncate">
                                        <?= $activity['full_name'] ?> commented</p>
                                    <p class="text-gray-500 text-xs mt-1"><?= time_ago($activity['created_at']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-gradient-to-r from-secondary to-[#8A84C6] rounded-xl p-4 md:p-6 text-white">
        <h3 class="text-lg md:text-[20px] font-semibold mb-3 md:mb-4">Quick Actions</h3>
        <div class="flex flex-col sm:flex-row gap-3 md:gap-4">
            <a href="<?= base_url('customer/create_ticket?project=' . $project['project_id']) ?>"
                class="px-4 md:px-6 py-2 md:py-3 bg-white text-secondary rounded-lg hover:bg-gray-100 transition-colors font-medium text-sm md:text-base flex items-center justify-center gap-2 flex-1">
                <i class="fas fa-plus"></i>
                <span>New Ticket</span>
            </a>

            <a href="<?= base_url('customer/my_tickets') ?>"
                class="px-4 md:px-6 py-2 md:py-3 bg-white/20 text-white rounded-lg hover:bg-white/30 transition-colors font-medium text-sm md:text-base flex items-center justify-center gap-2 flex-1">
                <i class="fas fa-ticket-alt"></i>
                <span>All My Tickets</span>
            </a>
        </div>
    </div>
</div>

<style>
    /* Custom scrollbar for tables */
    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }

    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Filter tickets by status
        document.getElementById('filterStatus').addEventListener('change', function () {
            filterTickets();
        });

        // Filter tickets by priority
        document.getElementById('filterPriority').addEventListener('change', function () {
            filterTickets();
        });

        function filterTickets() {
            const statusFilter = document.getElementById('filterStatus').value;
            const priorityFilter = document.getElementById('filterPriority').value;
            const rows = document.querySelectorAll('#ticketsTableBody tr');

            let visibleCount = 0;

            rows.forEach(row => {
                if (row.cells.length === 1) return; // Skip "no tickets" row

                const statusElement = row.cells[3].querySelector('span');
                const priorityElement = row.cells[2].querySelector('span');

                const status = statusElement ? statusElement.textContent.trim().toLowerCase() : '';
                const priority = priorityElement ? priorityElement.textContent.trim().toLowerCase() : '';

                let showRow = true;

                // Apply status filter
                if (statusFilter !== 'all') {
                    const statusMap = {
                        'open': 'open',
                        'in-progress': 'in progress',
                        'resolved': 'resolved',
                        'closed': 'closed'
                    };
                    showRow = showRow && status === statusMap[statusFilter];
                }

                // Apply priority filter
                if (priorityFilter !== 'all') {
                    const priorityMap = {
                        'urgent': 'urgent',
                        'high': 'high',
                        'medium': 'medium',
                        'low': 'low'
                    };
                    showRow = showRow && priority === priorityMap[priorityFilter];
                }

                row.style.display = showRow ? '' : 'none';
                if (showRow) visibleCount++;
            });

            // Show "no tickets" message if all filtered out
            const noTicketsRow = document.querySelector('#ticketsTableBody tr[colspan]');
            if (noTicketsRow) {
                noTicketsRow.style.display = visibleCount === 0 ? '' : 'none';
            }
        }

        // Helper function to format date
        function formatDate(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffTime = Math.abs(now - date);
            const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));

            if (diffDays === 0) {
                return 'Today, ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            } else if (diffDays === 1) {
                return 'Yesterday, ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            } else if (diffDays < 7) {
                return diffDays + ' days ago';
            } else {
                return date.toLocaleDateString([], { month: 'short', day: 'numeric', year: 'numeric' });
            }
        }
    });
</script>

<?php
// Helper function for time ago
if (!function_exists('time_ago')) {
    function time_ago($datetime, $full = false)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );

        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full)
            $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
}
?>
<?= $this->endSection() ?>