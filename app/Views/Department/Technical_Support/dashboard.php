<?= $this->extend('layouts/it_support_layout') ?>

<?= $this->section('title') ?>Technical Support Dashboard - NEXUS<?= $this->endSection() ?>

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
        <div class="flex flex-col">
            <h1 class="text-2xl md:text-[35px] font-semibold mb-1 md:mb-[5px] text-text-dark">IT Support Dashboard</h1>
            <p class="text-sm md:text-[15px] font-light text-[#666]">Assigned tickets & progress overview</p>
        </div>

        <!-- Action Buttons -->
        <div class="mt-4 md:mt-0 md:absolute md:right-0 md:top-0 flex flex-col sm:flex-row gap-3 md:gap-[15px]">
            <a href="<?= base_url('department/it-support/assigned_tickets') ?>"
                class="px-4 md:px-[20px] py-2 md:py-[10px] rounded-lg bg-secondary text-white text-sm md:text-[14px] font-medium cursor-pointer flex items-center justify-center gap-2 transition-all duration-300 hover:bg-[#817CB2] hover:shadow-md no-underline">
                <i class="fas fa-ticket-alt"></i>
                <span>View All Assigned Tickets</span>
            </a>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-8 md:mb-12">
        <!-- Assigned Tickets Card -->
        <div
            class="bg-gradient-to-br from-[#3D3C5E] to-[#4A4570] rounded-xl p-5 md:p-6 text-white shadow-sm flex flex-col items-center justify-center text-center">
            <div
                class="w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-[#8CEAC7] to-[#6BD4B4] rounded-full flex items-center justify-center mb-3">
                <i class="fas fa-tasks text-white text-lg md:text-xl"></i>
            </div>
            <div class="text-3xl md:text-4xl font-bold mb-2">12</div>
            <div class="text-white/80 text-sm md:text-[15px] font-medium mb-2">Assigned Tickets</div>
            <div class="text-white/60 text-xs md:text-[13px] flex items-center justify-center">
                <i class="fas fa-info-circle mr-1"></i>
                <span>10 On Progress | 2 Need Info</span>
            </div>
        </div>

        <!-- High Priority Tickets Card -->
        <div
            class="bg-gradient-to-br from-[#3D3C5E] to-[#4A4570] rounded-xl p-5 md:p-6 text-white shadow-sm flex flex-col items-center justify-center text-center">
            <div
                class="w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-[#FF8BA7] to-[#FF6B8B] rounded-full flex items-center justify-center mb-3">
                <i class="fas fa-exclamation-triangle text-white text-lg md:text-xl"></i>
            </div>
            <div class="text-3xl md:text-4xl font-bold mb-2">5</div>
            <div class="text-white/80 text-sm md:text-[15px] font-medium mb-2">High Priority Tickets</div>
            <div class="text-white/60 text-xs md:text-[13px]">4 High | 1 Urgent</div>
        </div>

        <!-- Waiting for Customer Reply Card -->
        <div
            class="bg-gradient-to-br from-[#3D3C5E] to-[#4A4570] rounded-xl p-5 md:p-6 text-white shadow-sm flex flex-col items-center justify-center text-center">
            <div
                class="w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-[#FCD685] to-[#F9C052] rounded-full flex items-center justify-center mb-3">
                <i class="fas fa-clock text-white text-lg md:text-xl"></i>
            </div>
            <div class="text-3xl md:text-4xl font-bold mb-2">3</div>
            <div class="text-white/80 text-sm md:text-[15px] font-medium mb-2">Waiting Customer Reply</div>
            <div class="text-white/60 text-xs md:text-[13px]">Need info status</div>
        </div>

        <!-- Resolved Tickets Card -->
        <div
            class="bg-gradient-to-br from-[#3D3C5E] to-[#4A4570] rounded-xl p-5 md:p-6 text-white shadow-sm flex flex-col items-center justify-center text-center">
            <div
                class="w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-[#82B4FF] to-[#5D9CFF] rounded-full flex items-center justify-center mb-3">
                <i class="fas fa-check-circle text-white text-lg md:text-xl"></i>
            </div>
            <div class="text-3xl md:text-4xl font-bold mb-2">20</div>
            <div class="text-white/80 text-sm md:text-[15px] font-medium mb-2">Resolved Tickets</div>
            <div class="text-white/60 text-xs md:text-[13px]">4 Today | 16 This Week</div>
        </div>
    </div>

    <!-- Recent Assigned Tickets Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
        <!-- Section Header -->
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex-1">
                    <h2 class="text-lg md:text-[20px] font-semibold text-gray-800">Recent Assigned Tickets</h2>
                    <p class="text-sm text-gray-600 mt-1">Latest tickets assigned to IT Support department</p>
                </div>

                <!-- Search Bar -->
                <div class="relative">
                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="fas fa-search text-sm"></i>
                    </div>
                    <input type="text" placeholder="Search tickets..."
                        class="w-full md:w-64 h-10 pl-10 pr-4 bg-white border border-gray-300 rounded-lg text-gray-700 text-sm focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20"
                        id="recentSearch">
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full min-w-max">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3 px-4 text-left text-xs md:text-sm font-semibold text-gray-700 cursor-pointer sort-header"
                            data-sort="id">
                            <div class="flex items-center gap-1">
                                <span class="hidden sm:inline">Ticket ID</span>
                                <span class="sm:hidden">ID</span>
                                <i class="fas fa-sort text-gray-400 ml-1 text-xs"></i>
                            </div>
                        </th>
                        <th class="py-3 px-4 text-left text-xs md:text-sm font-semibold text-gray-700 cursor-pointer sort-header"
                            data-sort="subject">
                            <div class="flex items-center gap-1">
                                <span>Subject</span>
                                <i class="fas fa-sort text-gray-400 ml-1 text-xs"></i>
                            </div>
                        </th>
                        <th class="py-3 px-4 text-left text-xs md:text-sm font-semibold text-gray-700 cursor-pointer sort-header hidden md:table-cell"
                            data-sort="project">
                            <div class="flex items-center gap-1">
                                <span>Project</span>
                                <i class="fas fa-sort text-gray-400 ml-1 text-xs"></i>
                            </div>
                        </th>
                        <th class="py-3 px-4 text-left text-xs md:text-sm font-semibold text-gray-700 cursor-pointer sort-header"
                            data-sort="priority">
                            <div class="flex items-center gap-1">
                                <span class="hidden xs:inline">Priority</span>
                                <span class="xs:hidden">Pri</span>
                                <i class="fas fa-sort text-gray-400 ml-1 text-xs"></i>
                            </div>
                        </th>
                        <th class="py-3 px-4 text-left text-xs md:text-sm font-semibold text-gray-700 cursor-pointer sort-header hidden sm:table-cell"
                            data-sort="status">
                            <div class="flex items-center gap-1">
                                <span>Status</span>
                                <i class="fas fa-sort text-gray-400 ml-1 text-xs"></i>
                            </div>
                        </th>
                        <th class="py-3 px-4 text-left text-xs md:text-sm font-semibold text-gray-700 cursor-pointer sort-header hidden lg:table-cell"
                            data-sort="updated">
                            <div class="flex items-center gap-1">
                                <span>Last Updated</span>
                                <i class="fas fa-sort text-gray-400 ml-1 text-xs"></i>
                            </div>
                        </th>
                        <th class="py-3 px-4 text-left text-xs md:text-sm font-semibold text-gray-700">Action</th>
                    </tr>
                </thead>
                <tbody id="recentTicketsTable" class="divide-y divide-gray-200">
                    <?php
                    $recent_tickets = [
                        [
                            'id' => '#10421',
                            'id_num' => 10421,
                            'subject' => 'Login issue causing error message',
                            'project' => 'Project Alpha',
                            'priority' => 'High',
                            'priority_value' => 3,
                            'priorityColor' => 'bg-orange-100 text-orange-800 border border-orange-200',
                            'status' => 'Open',
                            'statusColor' => 'bg-blue-100 text-blue-800 border border-blue-200',
                            'time' => '5 minutes ago',
                            'timestamp' => strtotime('-5 minutes'),
                        ],
                        [
                            'id' => '#10422',
                            'id_num' => 10422,
                            'subject' => 'Feature request for new export option',
                            'project' => 'Project Alpha',
                            'priority' => 'Low',
                            'priority_value' => 1,
                            'priorityColor' => 'bg-blue-100 text-blue-800 border border-blue-200',
                            'status' => 'Resolved',
                            'statusColor' => 'bg-green-100 text-green-800 border border-green-200',
                            'time' => '1 hour ago',
                            'timestamp' => strtotime('-1 hour'),
                        ],
                        [
                            'id' => '#10423',
                            'id_num' => 10423,
                            'subject' => 'Fix firestorx issues neat issues',
                            'project' => 'Project Beta',
                            'priority' => 'High',
                            'priority_value' => 3,
                            'priorityColor' => 'bg-orange-100 text-orange-800 border border-orange-200',
                            'status' => 'Need Info',
                            'statusColor' => 'bg-gray-100 text-gray-800 border border-gray-200',
                            'time' => '2 hours ago',
                            'timestamp' => strtotime('-2 hours'),
                        ],
                        [
                            'id' => '#10424',
                            'id_num' => 10424,
                            'subject' => 'Fix safissax issues source log iss',
                            'project' => 'Project Beta',
                            'priority' => 'Low',
                            'priority_value' => 1,
                            'priorityColor' => 'bg-blue-100 text-blue-800 border border-blue-200',
                            'status' => 'Closed',
                            'statusColor' => 'bg-gray-200 text-gray-800 border border-gray-300',
                            'time' => '3 hours ago',
                            'timestamp' => strtotime('-3 hours'),
                        ],
                        [
                            'id' => '#10425',
                            'id_num' => 10425,
                            'subject' => 'Fix mixizading app updates',
                            'project' => 'Project Alpha',
                            'priority' => 'Urgent',
                            'priority_value' => 4,
                            'priorityColor' => 'bg-red-100 text-red-800 border border-red-200',
                            'status' => 'In Progress',
                            'statusColor' => 'bg-purple-100 text-purple-800 border border-purple-200',
                            'time' => '4 hours ago',
                            'timestamp' => strtotime('-4 hours'),
                        ],
                    ];
                    ?>
                </tbody>
            </table>
        </div>

        <!-- View All Footer -->
        <div class="p-4 md:p-6 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="text-gray-600 text-sm">
                    Showing <span id="recentShowingCount">5</span> recent tickets
                </div>
                <a href="<?= base_url('department/it-support/assigned_tickets') ?>"
                    class="px-4 py-2 bg-secondary text-white text-sm font-medium rounded-lg hover:bg-[#817CB2] transition-colors flex items-center gap-2 no-underline">
                    <span>View All Tickets</span>
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </div>


</div>

<style>
    /* Custom animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
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

    .urgent-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    .hover-row:hover {
        background-color: #f9fafb;
    }

    /* Table row animation */
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

    /* Card hover effects */
    .card-hover {
        transition: all 0.2s ease;
    }

    .card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Render initial recent tickets table
        renderRecentTable(<?= json_encode($recent_tickets) ?>);

        // Add urgent pulse animation to urgent tickets
        setTimeout(() => {
            document.querySelectorAll('.bg-red-100').forEach(badge => {
                badge.classList.add('urgent-pulse');
            });
        }, 500);

        // Search functionality for recent tickets
        const recentSearch = document.getElementById('recentSearch');
        recentSearch.addEventListener('input', (e) => {
            filterRecentTickets(e.target.value);
        });

        // Header click sorting for recent tickets
        document.querySelectorAll('.sort-header').forEach(header => {
            header.addEventListener('click', function () {
                const column = this.dataset.sort;
                sortRecentTickets(column);

                // Update sort icons
                updateRecentSortIcons(column);
            });
        });

        // Initialize sort icons for recent tickets
        updateRecentSortIcons('id');

        // Add hover effects to cards
        document.querySelectorAll('.bg-card-bg.rounded-xl').forEach(card => {
            card.classList.add('card-hover');
        });

        // Quick tools buttons
        document.querySelectorAll('.bg-white\\/10.rounded-lg').forEach(button => {
            button.addEventListener('click', function () {
                const toolName = this.querySelector('span').textContent;
                showToast(`Opening ${toolName} tool...`, 'info');
            });
        });

        // Table row click for recent tickets
        document.querySelectorAll('#recentTicketsTable tr').forEach(row => {
            row.addEventListener('click', function (e) {
                if (!e.target.closest('a') && !e.target.closest('button')) {
                    const ticketId = this.querySelector('td:first-child span')?.textContent;
                    if (ticketId) {
                        window.location.href = `<?= base_url('department/it-support/ticket_detail/') ?>${ticketId.replace('#', '')}`;
                    }
                }
            });
        });
    });

    // Recent tickets data
    const recentTicketsData = <?= json_encode($recent_tickets) ?>;

    function renderRecentTable(tickets) {
        const tbody = document.getElementById('recentTicketsTable');
        tbody.innerHTML = '';

        tickets.forEach((ticket, index) => {
            const rowClass = index % 2 === 0 ? 'bg-white' : 'bg-gray-50';
            const row = document.createElement('tr');
            row.className = `${rowClass} hover-row transition-colors cursor-pointer`;

            // Single View button for all tickets
            const actionButtons = `
                <a href="<?= base_url('department/it-support/ticket_detail/') ?>${ticket.id_num}" 
                   class="px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded-lg hover:bg-blue-200 transition-colors whitespace-nowrap flex items-center gap-1">
                    <i class="fas fa-eye text-xs"></i>
                    <span>View</span>
                </a>
            `;

            row.innerHTML = `
                <td class="py-3 px-4">
                    <span class="font-bold text-gray-800 text-sm md:text-base">${ticket.id}</span>
                </td>
                <td class="py-3 px-4">
                    <div>
                        <p class="font-medium text-gray-800 text-sm truncate max-w-[150px] md:max-w-xs">${ticket.subject}</p>
                        <p class="text-gray-500 text-xs mt-1 md:hidden">${ticket.project}</p>
                    </div>
                </td>
                <td class="py-3 px-4 hidden md:table-cell">
                    <span class="text-gray-700 text-sm">${ticket.project}</span>
                </td>
                <td class="py-3 px-4">
                    <span class="px-2 py-1 text-xs rounded-full ${ticket.priorityColor} font-medium whitespace-nowrap">
                        ${ticket.priority}
                    </span>
                </td>
                <td class="py-3 px-4 hidden sm:table-cell">
                    <span class="px-2 py-1 text-xs rounded-full ${ticket.statusColor} font-medium whitespace-nowrap">
                        ${ticket.status}
                    </span>
                </td>
                <td class="py-3 px-4 hidden lg:table-cell">
                    <span class="text-gray-600 text-sm">${ticket.time}</span>
                </td>
                <td class="py-3 px-4">
                    <div class="flex items-center gap-2">
                        ${actionButtons}
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });

        // Update showing count
        document.getElementById('recentShowingCount').textContent = tickets.length;
    }

    function filterRecentTickets(searchTerm) {
        const filtered = recentTicketsData.filter(ticket => {
            const term = searchTerm.toLowerCase();
            return (
                ticket.subject.toLowerCase().includes(term) ||
                ticket.id.toLowerCase().includes(term) ||
                ticket.project.toLowerCase().includes(term) ||
                ticket.status.toLowerCase().includes(term) ||
                ticket.priority.toLowerCase().includes(term)
            );
        });
        renderRecentTable(filtered);
    }

    let recentSortDirection = 'desc';
    let recentSortColumn = 'id';

    function sortRecentTickets(column) {
        if (recentSortColumn === column) {
            recentSortDirection = recentSortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            recentSortColumn = column;
            recentSortDirection = 'desc';
        }

        const sorted = [...recentTicketsData].sort((a, b) => {
            let aValue, bValue;

            switch (column) {
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

            if (recentSortDirection === 'asc') {
                return aValue > bValue ? 1 : -1;
            } else {
                return aValue < bValue ? 1 : -1;
            }
        });

        renderRecentTable(sorted);
    }

    function updateRecentSortIcons(activeColumn) {
        // Reset all icons
        document.querySelectorAll('.sort-header i').forEach(icon => {
            icon.className = 'fas fa-sort text-gray-400 ml-1 text-xs';
        });

        // Set active sort icon
        const activeHeader = document.querySelector(`.sort-header[data-sort="${activeColumn}"] i`);
        if (activeHeader) {
            activeHeader.className = `fas fa-sort-${recentSortDirection === 'asc' ? 'up' : 'down'} text-secondary ml-1 text-xs`;
        }
    }

    // Toast notification function
    function showToast(message, type = 'info') {
        // Remove existing toasts
        document.querySelectorAll('.toast-notification').forEach(toast => toast.remove());

        const toast = document.createElement('div');
        toast.className = `toast-notification fixed top-24 right-6 p-4 rounded-xl shadow-xl z-[9999] max-w-sm animate-fadeInUp ${type === 'error' ? 'bg-red-500 text-white border-l-4 border-red-600' : type === 'success' ? 'bg-green-500 text-white border-l-4 border-green-600' : 'bg-blue-500 text-white border-l-4 border-blue-600'}`;
        toast.innerHTML = `
            <div class="flex items-center gap-3">
                <i class="fas ${type === 'error' ? 'fa-exclamation-circle text-xl' : type === 'success' ? 'fa-check-circle text-xl' : 'fa-info-circle text-xl'}"></i>
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