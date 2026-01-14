<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>View Tickets - NEXUS Admin<?= $this->endSection() ?>

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
<div class="relative z-10">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-[34.77px] font-semibold mb-2 text-text-dark">View Tickets & Projects</h1>
                <p class="text-[15.45px] font-light text-text-dark">Track and manage all incoming support tickets and
                    projects</p>
            </div>
            <button id="viewProjectsBtn"
                class="h-12 px-6 bg-secondary text-white rounded-lg hover:bg-[#665C9E] transition-colors font-medium flex items-center gap-2">
                <i class="fas fa-project-diagram"></i>
                View Projects
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- All Tickets -->
        <div class="dashboard-card">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-text-dark/70 text-sm font-medium mb-2">All Tickets</div>
                    <div class="text-text-dark text-2xl font-bold">376</div>
                </div>
                <div class="w-12 h-12 bg-secondary/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-ticket-alt text-secondary text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Open Tickets -->
        <div class="dashboard-card">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-text-dark/70 text-sm font-medium mb-2">Open Tickets</div>
                    <div class="text-text-dark text-2xl font-bold">112</div>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Resolved Tickets -->
        <div class="dashboard-card">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-text-dark/70 text-sm font-medium mb-2">Resolved Tickets</div>
                    <div class="text-text-dark text-2xl font-bold">200</div>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Closed Tickets -->
        <div class="dashboard-card">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-text-dark/70 text-sm font-medium mb-2">Closed Tickets</div>
                    <div class="text-text-dark text-2xl font-bold">64</div>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-times-circle text-gray-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="dashboard-card mb-6">
        <div class="dashboard-card-header">
            <div class="text-text-dark/85 text-base font-medium">Search & Filter Tickets</div>
        </div>

        <div class="p-6">
            <!-- Search Input -->
            <div class="relative mb-4">
                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-text-muted">
                    <i class="fas fa-search"></i>
                </div>
                <input type="text" placeholder="Search by ticket ID, title, or customer..." id="ticketSearch"
                    class="w-full h-12 pl-12 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
            </div>

            <!-- Filter Options -->
            <div class="flex flex-wrap gap-4 items-center">
                <!-- Priority Filter -->
                <select id="priorityFilter"
                    class="h-10 pl-4 pr-4 bg-white rounded-lg border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary">
                    <option value="">All Priority</option>
                    <option value="urgent">Urgent</option>
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>

                <!-- Department Filter -->
                <select id="departmentFilter"
                    class="h-10 pl-4 pr-4 bg-white rounded-lg border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary">
                    <option value="">All Departments</option>
                    <option value="it-support">IT Support</option>
                    <option value="technical-support">Technical Support</option>
                    <option value="uiux-support">UI/UX Support</option>
                    <option value="feature-request">Feature Request</option>
                    <option value="qa">QA Team</option>
                </select>

                <!-- Status Filter -->
                <select id="statusFilter"
                    class="h-10 pl-4 pr-4 bg-white rounded-lg border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary">
                    <option value="">All Status</option>
                    <option value="open">Open</option>
                    <option value="in-progress">On Progress</option>
                    <option value="resolved">Resolved</option>
                    <option value="closed">Closed</option>
                    <option value="need-info">Need Info</option>
                </select>

                <!-- Reset Button -->
                <button id="resetFilters"
                    class="h-10 px-4 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                    Reset Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="dashboard-card">
        <div class="dashboard-card-header flex justify-between items-center">
            <div class="text-text-dark/85 text-base font-medium">Recent Tickets</div>
            <div class="text-sm text-secondary font-medium">
                <span id="showingCount">Showing 1-6</span> of <span id="totalCount">376</span> tickets
            </div>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto">
            <!-- Table Header -->
            <div
                class="grid grid-cols-12 gap-4 py-4 px-6 bg-[#E3DAEE] rounded-lg text-sm font-semibold text-text-dark/80">
                <div class="col-span-1 text-center">ID</div>
                <div class="col-span-3">Title</div>
                <div class="col-span-2 text-center">Priority</div>
                <div class="col-span-2 text-center">Department</div>
                <div class="col-span-2 text-center">Customer</div>
                <div class="col-span-1 text-center">Status</div>
                <div class="col-span-1 text-center">Last Update</div>
            </div>

            <!-- Tickets List -->
            <div id="ticketsList" class="divide-y divide-white/30">
                <!-- Tickets will be populated here -->
            </div>
        </div>

        <!-- Pagination -->
        <div class="border-t border-white/30 mt-4 pt-4">
            <div class="flex justify-between items-center px-6">
                <div class="text-text-dark/70 text-sm">
                    <span id="paginationInfo">Page 1 of 63</span>
                </div>
                <div class="flex items-center gap-2">
                    <button id="prevPage"
                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/20 hover:bg-secondary/20 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-chevron-left text-sm"></i>
                    </button>
                    <div id="pageNumbers" class="flex items-center gap-1">
                        <button
                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-secondary text-white">1</button>
                        <button
                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/20 hover:bg-secondary/20 transition-colors">2</button>
                        <button
                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/20 hover:bg-secondary/20 transition-colors">3</button>
                        <button
                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/20 hover:bg-secondary/20 transition-colors">4</button>
                    </div>
                    <button id="nextPage"
                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/20 hover:bg-secondary/20 transition-colors">
                        <i class="fas fa-chevron-right text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Card -->
    <div class="dashboard-card mt-6">
        <div class="dashboard-card-header">
            <div class="text-text-dark/85 text-base font-medium">Quick Actions</div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 p-6">
            <!-- Export Tickets -->
            <button id="exportTickets" class="quick-action-btn">
                <i class="fas fa-file-export text-secondary"></i>
                <span>Export Tickets</span>
            </button>

            <!-- Bulk Assign -->
            <button id="bulkAssign" class="quick-action-btn">
                <i class="fas fa-users text-secondary"></i>
                <span>Bulk Assign</span>
            </button>

            <!-- Generate Report -->
            <button id="generateReport" class="quick-action-btn">
                <i class="fas fa-chart-bar text-secondary"></i>
                <span>Generate Report</span>
            </button>

            <!-- View Analytics -->
            <button id="viewAnalytics" class="quick-action-btn">
                <i class="fas fa-chart-line text-secondary"></i>
                <span>View Analytics</span>
            </button>
        </div>
    </div>
</div>

<!-- Modal Templates -->
<template id="ticketDetailModalTemplate">
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4">
        <div class="bg-white rounded-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-800">Ticket Details</h3>
                    <button class="close-modal text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column: Ticket Info -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Title -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-2" id="modalTicketTitle"></h4>
                            <p class="text-gray-600 text-sm" id="modalTicketDescription"></p>
                        </div>

                        <!-- Description -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Detailed Description</h4>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-600 text-sm" id="modalTicketDetails"></p>
                            </div>
                        </div>

                        <!-- Activity Log -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Activity Log</h4>
                            <div class="space-y-3" id="activityLog">
                                <!-- Activity log will be populated here -->
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Ticket Metadata -->
                    <div class="space-y-6">
                        <!-- Status & Priority -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-gray-500 text-xs mb-1">Status</label>
                                    <span id="modalTicketStatus" class="status-badge"></span>
                                </div>
                                <div>
                                    <label class="block text-gray-500 text-xs mb-1">Priority</label>
                                    <span id="modalTicketPriority" class="priority-badge"></span>
                                </div>
                                <div>
                                    <label class="block text-gray-500 text-xs mb-1">Department</label>
                                    <span id="modalTicketDepartment" class="text-gray-700 text-sm"></span>
                                </div>
                                <div>
                                    <label class="block text-gray-500 text-xs mb-1">Customer</label>
                                    <span id="modalTicketCustomer" class="text-gray-700 text-sm"></span>
                                </div>
                                <div>
                                    <label class="block text-gray-500 text-xs mb-1">Assigned To</label>
                                    <span id="modalTicketAssigned" class="text-gray-700 text-sm"></span>
                                </div>
                                <div>
                                    <label class="block text-gray-500 text-xs mb-1">Created</label>
                                    <span id="modalTicketCreated" class="text-gray-700 text-sm"></span>
                                </div>
                                <div>
                                    <label class="block text-gray-500 text-xs mb-1">Last Update</label>
                                    <span id="modalTicketUpdated" class="text-gray-700 text-sm"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="space-y-2">
                            <button
                                class="w-full py-2 bg-secondary text-white rounded-lg hover:bg-[#665C9E] transition-colors text-sm font-medium">
                                <i class="fas fa-edit mr-2"></i>Edit Ticket
                            </button>
                            <button
                                class="w-full py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium">
                                <i class="fas fa-comment mr-2"></i>Add Comment
                            </button>
                            <button
                                class="w-full py-2 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition-colors text-sm font-medium">
                                <i class="fas fa-check mr-2"></i>Mark as Resolved
                            </button>
                            <button
                                class="w-full py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors text-sm font-medium">
                                <i class="fas fa-times mr-2"></i>Close Ticket
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-gray-200">
                <button
                    class="close-modal px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Close
                </button>
            </div>
        </div>
    </div>
</template>

<style>
    /* Custom animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }

    .animate-slideInUp {
        animation: slideInUp 0.3s ease-out;
    }

    /* Ticket row styling */
    .ticket-row {
        display: grid;
        grid-template-columns: repeat(12, minmax(0, 1fr));
        gap: 1rem;
        padding: 1rem 1.5rem;
        transition: all 0.2s ease;
        align-items: center;
    }

    .ticket-row:hover {
        background: rgba(117, 110, 164, 0.05);
        cursor: pointer;
    }

    /* Priority badges */
    .priority-badge {
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
    }

    .priority-urgent {
        background: #E16D7F;
        color: #873134;
    }

    .priority-high {
        background: #FFD2D2;
        color: #991B1B;
    }

    .priority-medium {
        background: #FED7AA;
        color: #9A3412;
    }

    .priority-low {
        background: #C7D2FE;
        color: #3730A3;
    }

    /* Status badges */
    .status-badge {
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
    }

    .status-open {
        background: #ACCBE3;
        color: #3E566B;
    }

    .status-in-progress {
        background: #BBACE3;
        color: #403E6B;
    }

    .status-resolved {
        background: #C4E3AC;
        color: #3E6B57;
    }

    .status-closed {
        background: #E3DDAC;
        color: #6B553E;
    }

    .status-need-info {
        background: #C7C5C8;
        color: #403E6B;
    }

    /* Quick action buttons */
    .quick-action-btn {
        background: white;
        border-radius: 8px;
        padding: 16px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        border: 1px solid #F3F4F6;
    }

    .quick-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-color: #E5E7EB;
    }

    .quick-action-btn i {
        font-size: 20px;
    }

    .quick-action-btn span {
        font-size: 12px;
        color: #6B7280;
        font-weight: 500;
    }

    /* Activity log */
    .activity-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .activity-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-top: 6px;
        flex-shrink: 0;
    }

    .activity-dot.created {
        background: #3B82F6;
    }

    .activity-dot.assigned {
        background: #10B981;
    }

    .activity-dot.updated {
        background: #F59E0B;
    }

    .activity-dot.resolved {
        background: #8B5CF6;
    }

    /* Loading skeleton */
    .skeleton-loader {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
        border-radius: 4px;
    }

    @keyframes loading {
        0% {
            background-position: 200% 0;
        }

        100% {
            background-position: -200% 0;
        }
    }

    /* Responsive adjustments */
    @media (max-width: 1024px) {
        .ticket-row {
            grid-template-columns: repeat(8, minmax(0, 1fr));
            gap: 0.75rem;
            padding: 0.75rem 1rem;
        }

        .ticket-row>div:nth-child(1) {
            grid-column: span 1;
        }

        .ticket-row>div:nth-child(2) {
            grid-column: span 3;
        }

        .ticket-row>div:nth-child(3) {
            grid-column: span 1;
        }

        .ticket-row>div:nth-child(4) {
            grid-column: span 1;
        }

        .ticket-row>div:nth-child(5) {
            grid-column: span 1;
        }

        .ticket-row>div:nth-child(6) {
            grid-column: span 1;
        }

        .ticket-row>div:nth-child(7) {
            grid-column: span 1;
        }
    }

    @media (max-width: 768px) {
        .ticket-row {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }

        .ticket-row>div {
            grid-column: span 1 !important;
            text-align: left !important;
        }

        .quick-action-btn {
            padding: 12px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initial data
        let ticketsData = [
            {
                id: 2482,
                title: "ERP system login not working",
                description: "Users are unable to login to the ERP system",
                details: "When trying to login to the ERP portal, users receive an 'Authentication Failed' error. This issue started approximately 30 minutes ago. Affects all users across different departments.\n\nError message: 'Unable to authenticate user credentials. Please contact system administrator.'\n\nTried clearing cache and cookies, but issue persists. System was working fine yesterday.",
                priority: "high",
                department: "it-support",
                customer: "Deni Darmawan",
                status: "open",
                created: "Today, 08:30 AM",
                updated: "5 minutes ago",
                assignedTo: "IT Support Team",
                activity: [
                    { type: "created", text: "Ticket created by Deni Darmawan", time: "5 minutes ago" },
                    { type: "assigned", text: "Assigned to IT Support department", time: "3 minutes ago" }
                ]
            },
            {
                id: 2472,
                title: "Core network outage affecting multiple services",
                description: "Network connectivity issues across multiple systems",
                details: "Complete network outage affecting all services. Core router failure detected in main data center. Multiple services including email, file sharing, and database access are impacted.\n\nEmergency maintenance required. Backup systems not engaging properly.",
                priority: "urgent",
                department: "it-support",
                customer: "Chelish Wijaya",
                status: "resolved",
                created: "Yesterday, 14:20",
                updated: "30 minutes ago",
                assignedTo: "Network Team",
                activity: [
                    { type: "created", text: "Ticket created by Chelish Wijaya", time: "2 hours ago" },
                    { type: "assigned", text: "Escalated to Network Team", time: "1 hour ago" },
                    { type: "resolved", text: "Network restored, router replaced", time: "30 minutes ago" }
                ]
            },
            {
                id: 2461,
                title: "VPN connection unstable",
                description: "VPN drops connection frequently",
                details: "VPN connection becomes unstable after 10-15 minutes of use. Connection drops randomly and requires re-authentication. Issue occurs with both desktop and mobile VPN clients.\n\nTried different networks (office, home, mobile data) with same result. VPN logs show authentication timeouts.",
                priority: "medium",
                department: "feature-request",
                customer: "Alex",
                status: "closed",
                created: "Apr 28, 2024",
                updated: "1 hour ago",
                assignedTo: "Security Team",
                activity: [
                    { type: "created", text: "Ticket created by Alex", time: "2 days ago" },
                    { type: "assigned", text: "Assigned to Security Team", time: "1 day ago" },
                    { type: "updated", text: "VPN configuration updated", time: "3 hours ago" },
                    { type: "closed", text: "Issue resolved, ticket closed", time: "1 hour ago" }
                ]
            },
            {
                id: 2452,
                title: "UI alignment issue in dashboard",
                description: "Visual misalignment in main dashboard",
                details: "Dashboard elements misaligned on screens larger than 1920px. Grid system not scaling properly. Affects all dashboard widgets and navigation panels.\n\nIssue observed in Chrome and Firefox. Safari displays correctly. CSS grid calculations appear incorrect at higher resolutions.",
                priority: "medium",
                department: "uiux-support",
                customer: "Sheyl Darmanto",
                status: "need-info",
                created: "Apr 27, 2024",
                updated: "Today, 09:18",
                assignedTo: "UI/UX Team",
                activity: [
                    { type: "created", text: "Ticket created by Sheyl Darmanto", time: "3 days ago" },
                    { type: "assigned", text: "Assigned to UI/UX Team", time: "2 days ago" },
                    { type: "updated", text: "Requested additional information", time: "Today, 09:18" }
                ]
            },
            {
                id: 2440,
                title: "Request for new email account creation",
                description: "New employee email account needed",
                details: "New employee starting May 1st requires email account setup. Needs standard corporate email address with full access to shared mailboxes and distribution lists.\n\nAlso requires access to Teams, SharePoint, and other collaboration tools. User will be in Marketing department.",
                priority: "low",
                department: "technical-support",
                customer: "Astrid Aurel",
                status: "in-progress",
                created: "Apr 26, 2024",
                updated: "Today, 08:10",
                assignedTo: "Email Admin",
                activity: [
                    { type: "created", text: "Ticket created by Astrid Aurel", time: "4 days ago" },
                    { type: "assigned", text: "Assigned to Email Admin", time: "3 days ago" },
                    { type: "updated", text: "Account creation in progress", time: "Today, 08:10" }
                ]
            },
            {
                id: 2438,
                title: "Database performance slow",
                description: "Database queries taking too long",
                details: "Production database experiencing slow query performance. Response times increased from 200ms to 5+ seconds. Affecting customer-facing applications and internal reporting tools.\n\nCPU usage spiking to 95% during peak hours. Disk I/O also showing high latency. Need performance tuning and index optimization.",
                priority: "high",
                department: "it-support",
                customer: "John Smith",
                status: "open",
                created: "Apr 25, 2024",
                updated: "Yesterday, 16:30",
                assignedTo: "Database Team",
                activity: [
                    { type: "created", text: "Ticket created by John Smith", time: "5 days ago" },
                    { type: "assigned", text: "Escalated to Database Team", time: "4 days ago" },
                    { type: "updated", text: "Performance analysis in progress", time: "Yesterday, 16:30" }
                ]
            }
        ];

        let filteredTickets = [...ticketsData];
        let currentPage = 1;
        let itemsPerPage = 6;
        let totalPages = Math.ceil(ticketsData.length / itemsPerPage);

        // DOM Elements
        const ticketSearch = document.getElementById('ticketSearch');
        const priorityFilter = document.getElementById('priorityFilter');
        const departmentFilter = document.getElementById('departmentFilter');
        const statusFilter = document.getElementById('statusFilter');
        const resetFilters = document.getElementById('resetFilters');
        const ticketsList = document.getElementById('ticketsList');
        const showingCount = document.getElementById('showingCount');
        const totalCount = document.getElementById('totalCount');
        const paginationInfo = document.getElementById('paginationInfo');
        const pageNumbers = document.getElementById('pageNumbers');
        const prevPage = document.getElementById('prevPage');
        const nextPage = document.getElementById('nextPage');
        const viewProjectsBtn = document.getElementById('viewProjectsBtn');
        const exportTicketsBtn = document.getElementById('exportTickets');
        const bulkAssignBtn = document.getElementById('bulkAssign');
        const generateReportBtn = document.getElementById('generateReport');
        const viewAnalyticsBtn = document.getElementById('viewAnalytics');

        // Initialize
        init();

        function init() {
            renderTickets();
            updatePagination();
            updateShowingCount();

            // Event listeners
            setupEventListeners();
        }

        function setupEventListeners() {
            // Search input
            if (ticketSearch) {
                ticketSearch.addEventListener('input', debounce(() => {
                    filterTickets();
                }, 300));
            }

            // Filter changes
            if (priorityFilter) {
                priorityFilter.addEventListener('change', filterTickets);
            }

            if (departmentFilter) {
                departmentFilter.addEventListener('change', filterTickets);
            }

            if (statusFilter) {
                statusFilter.addEventListener('change', filterTickets);
            }

            // Reset filters
            if (resetFilters) {
                resetFilters.addEventListener('click', resetAllFilters);
            }

            // Pagination
            if (prevPage) {
                prevPage.addEventListener('click', () => changePage(currentPage - 1));
            }

            if (nextPage) {
                nextPage.addEventListener('click', () => changePage(currentPage + 1));
            }

            // Action buttons
            if (viewProjectsBtn) {
                viewProjectsBtn.addEventListener('click', showProjects);
            }

            if (exportTicketsBtn) {
                exportTicketsBtn.addEventListener('click', exportTickets);
            }

            if (bulkAssignBtn) {
                bulkAssignBtn.addEventListener('click', bulkAssignTickets);
            }

            if (generateReportBtn) {
                generateReportBtn.addEventListener('click', generateReport);
            }

            if (viewAnalyticsBtn) {
                viewAnalyticsBtn.addEventListener('click', viewAnalytics);
            }
        }

        function renderTickets() {
            if (!ticketsList) return;

            ticketsList.innerHTML = '';

            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const pageTickets = filteredTickets.slice(startIndex, endIndex);

            if (pageTickets.length === 0) {
                ticketsList.innerHTML = `
                    <div class="py-12 text-center">
                        <i class="fas fa-ticket-alt text-gray-300 text-4xl mb-4"></i>
                        <p class="text-gray-500">No tickets found</p>
                        <p class="text-gray-400 text-sm mt-2">Try adjusting your filters</p>
                    </div>
                `;
                return;
            }

            pageTickets.forEach(ticket => {
                const ticketRow = document.createElement('div');
                ticketRow.className = 'ticket-row';
                ticketRow.dataset.ticketId = ticket.id;

                ticketRow.innerHTML = `
                    <div class="col-span-1 text-center text-[#475569] font-medium">#${ticket.id}</div>
                    <div class="col-span-3">
                        <div class="text-text-dark text-sm font-medium truncate">${ticket.title}</div>
                        <div class="text-text-dark/50 text-xs truncate">${ticket.description}</div>
                    </div>
                    <div class="col-span-2 text-center">
                        <span class="${getPriorityClass(ticket.priority)} priority-badge">
                            ${getPriorityName(ticket.priority)}
                        </span>
                    </div>
                    <div class="col-span-2 text-center text-text-dark text-sm">${getDepartmentName(ticket.department)}</div>
                    <div class="col-span-2 text-center text-text-dark text-sm truncate">${ticket.customer}</div>
                    <div class="col-span-1 text-center">
                        <span class="${getStatusClass(ticket.status)} status-badge">
                            ${getStatusName(ticket.status)}
                        </span>
                    </div>
                    <div class="col-span-1 text-center text-text-dark text-sm">${ticket.updated}</div>
                `;

                // Add click event for viewing ticket details
                ticketRow.addEventListener('click', () => {
                    viewTicketDetails(ticket.id);
                });

                ticketsList.appendChild(ticketRow);
            });

            updateShowingCount();
        }

        function filterTickets() {
            const searchTerm = ticketSearch ? ticketSearch.value.toLowerCase().trim() : '';
            const priorityValue = priorityFilter ? priorityFilter.value : '';
            const departmentValue = departmentFilter ? departmentFilter.value : '';
            const statusValue = statusFilter ? statusFilter.value : '';

            filteredTickets = ticketsData.filter(ticket => {
                // Apply search filter
                if (searchTerm) {
                    const searchableText = [
                        ticket.id.toString(),
                        ticket.title.toLowerCase(),
                        ticket.description.toLowerCase(),
                        ticket.customer.toLowerCase(),
                        ticket.details.toLowerCase()
                    ].join(' ');

                    if (!searchableText.includes(searchTerm)) {
                        return false;
                    }
                }

                // Apply priority filter
                if (priorityValue && ticket.priority !== priorityValue) {
                    return false;
                }

                // Apply department filter
                if (departmentValue && ticket.department !== departmentValue) {
                    return false;
                }

                // Apply status filter
                if (statusValue && ticket.status !== statusValue) {
                    return false;
                }

                return true;
            });

            currentPage = 1;
            renderTickets();
            updatePagination();
        }

        function resetAllFilters() {
            if (ticketSearch) ticketSearch.value = '';
            if (priorityFilter) priorityFilter.value = '';
            if (departmentFilter) departmentFilter.value = '';
            if (statusFilter) statusFilter.value = '';

            currentPage = 1;
            filteredTickets = [...ticketsData];
            renderTickets();
            updatePagination();

            showToast('Filters reset', 'info');
        }

        function viewTicketDetails(ticketId) {
            const ticket = ticketsData.find(t => t.id === ticketId);
            if (!ticket) return;

            // Create modal from template
            const template = document.getElementById('ticketDetailModalTemplate');
            const modal = document.importNode(template.content, true);

            // Fill modal data
            modal.querySelector('#modalTicketTitle').textContent = ticket.title;
            modal.querySelector('#modalTicketDescription').textContent = ticket.description;
            modal.querySelector('#modalTicketDetails').textContent = ticket.details;
            modal.querySelector('#modalTicketStatus').textContent = getStatusName(ticket.status);
            modal.querySelector('#modalTicketStatus').className = `${getStatusClass(ticket.status)} status-badge`;
            modal.querySelector('#modalTicketPriority').textContent = getPriorityName(ticket.priority);
            modal.querySelector('#modalTicketPriority').className = `${getPriorityClass(ticket.priority)} priority-badge`;
            modal.querySelector('#modalTicketDepartment').textContent = getDepartmentName(ticket.department);
            modal.querySelector('#modalTicketCustomer').textContent = ticket.customer;
            modal.querySelector('#modalTicketAssigned').textContent = ticket.assignedTo;
            modal.querySelector('#modalTicketCreated').textContent = ticket.created;
            modal.querySelector('#modalTicketUpdated').textContent = ticket.updated;

            // Fill activity log
            const activityLog = modal.querySelector('#activityLog');
            activityLog.innerHTML = ticket.activity.map(activity => `
                <div class="activity-item">
                    <div class="activity-dot ${activity.type}"></div>
                    <div>
                        <p class="text-gray-600 text-sm">${activity.text}</p>
                        <p class="text-gray-400 text-xs">${activity.time}</p>
                    </div>
                </div>
            `).join('');

            document.body.appendChild(modal);
            document.body.style.overflow = 'hidden';

            // Add event listeners
            const closeButtons = modal.querySelectorAll('.close-modal');
            closeButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    document.body.removeChild(btn.closest('.fixed'));
                    document.body.style.overflow = 'auto';
                });
            });
        }

        function changePage(page) {
            if (page < 1 || page > totalPages) return;

            currentPage = page;
            renderTickets();
            updatePagination();

            // Scroll to top of tickets list
            if (ticketsList) {
                ticketsList.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        function updatePagination() {
            totalPages = Math.max(1, Math.ceil(filteredTickets.length / itemsPerPage));

            if (currentPage > totalPages) {
                currentPage = totalPages;
            }

            // Update pagination info
            if (paginationInfo) {
                paginationInfo.textContent = `Page ${currentPage} of ${totalPages}`;
            }

            // Update page numbers
            if (pageNumbers) {
                pageNumbers.innerHTML = '';

                // Always show first page
                addPageButton(1);

                // Show ellipsis if needed
                if (currentPage > 3) {
                    const ellipsis = document.createElement('span');
                    ellipsis.className = 'px-2 text-gray-400';
                    ellipsis.textContent = '...';
                    pageNumbers.appendChild(ellipsis);
                }

                // Show pages around current page
                const startPage = Math.max(2, currentPage - 1);
                const endPage = Math.min(totalPages - 1, currentPage + 1);

                for (let i = startPage; i <= endPage; i++) {
                    addPageButton(i);
                }

                // Show ellipsis if needed
                if (currentPage < totalPages - 2) {
                    const ellipsis = document.createElement('span');
                    ellipsis.className = 'px-2 text-gray-400';
                    ellipsis.textContent = '...';
                    pageNumbers.appendChild(ellipsis);
                }

                // Always show last page if not first
                if (totalPages > 1) {
                    addPageButton(totalPages);
                }
            }

            // Update prev/next buttons
            if (prevPage) {
                prevPage.disabled = currentPage === 1;
            }

            if (nextPage) {
                nextPage.disabled = currentPage === totalPages;
            }
        }

        function addPageButton(page) {
            const button = document.createElement('button');
            button.className = `w-8 h-8 flex items-center justify-center rounded-lg ${currentPage === page ? 'bg-secondary text-white' : 'bg-white/20 hover:bg-secondary/20'} transition-colors`;
            button.textContent = page;
            button.addEventListener('click', () => changePage(page));
            pageNumbers.appendChild(button);
        }

        function updateShowingCount() {
            const startIndex = (currentPage - 1) * itemsPerPage + 1;
            const endIndex = Math.min(startIndex + itemsPerPage - 1, filteredTickets.length);

            if (showingCount) {
                showingCount.textContent = `Showing ${startIndex}-${endIndex}`;
            }

            if (totalCount) {
                totalCount.textContent = filteredTickets.length;
            }
        }

        function showProjects() {
            showToast('Redirecting to projects page...', 'info');
            // In real app: window.location.href = '/admin/projects';
        }

        function exportTickets() {
            const csvContent = convertToCSV(filteredTickets);
            downloadCSV(csvContent, 'tickets.csv');
            showToast('Tickets exported successfully!', 'success');
        }

        function bulkAssignTickets() {
            showToast('Opening bulk assign interface...', 'info');
            // In real app: open bulk assign modal
        }

        function generateReport() {
            showToast('Generating monthly report...', 'info');
            // In real app: generate and download report
        }

        function viewAnalytics() {
            showToast('Loading analytics dashboard...', 'info');
            // In real app: redirect to analytics page
        }

        // Utility functions
        function getPriorityName(priority) {
            const priorities = {
                'urgent': 'Urgent',
                'high': 'High',
                'medium': 'Medium',
                'low': 'Low'
            };
            return priorities[priority] || priority;
        }

        function getStatusName(status) {
            const statuses = {
                'open': 'Open',
                'in-progress': 'On Progress',
                'resolved': 'Resolved',
                'closed': 'Closed',
                'need-info': 'Need Info'
            };
            return statuses[status] || status;
        }

        function getDepartmentName(department) {
            const departments = {
                'it-support': 'IT Support',
                'technical-support': 'Technical Support',
                'uiux-support': 'UI/UX Support',
                'feature-request': 'Feature Request',
                'qa': 'QA Team'
            };
            return departments[department] || department;
        }

        function getPriorityClass(priority) {
            const classes = {
                'urgent': 'priority-urgent',
                'high': 'priority-high',
                'medium': 'priority-medium',
                'low': 'priority-low'
            };
            return classes[priority] || 'priority-medium';
        }

        function getStatusClass(status) {
            const classes = {
                'open': 'status-open',
                'in-progress': 'status-in-progress',
                'resolved': 'status-resolved',
                'closed': 'status-closed',
                'need-info': 'status-need-info'
            };
            return classes[status] || 'status-open';
        }

        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        function convertToCSV(data) {
            const headers = ['ID', 'Title', 'Description', 'Priority', 'Department', 'Customer', 'Status', 'Created', 'Updated'];
            const rows = data.map(ticket => [
                ticket.id,
                `"${ticket.title}"`,
                `"${ticket.description}"`,
                getPriorityName(ticket.priority),
                getDepartmentName(ticket.department),
                ticket.customer,
                getStatusName(ticket.status),
                ticket.created,
                ticket.updated
            ]);

            return [headers.join(','), ...rows.map(row => row.join(','))].join('\n');
        }

        function downloadCSV(content, filename) {
            const blob = new Blob([content], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            a.click();
            window.URL.revokeObjectURL(url);
        }

        function showToast(message, type = 'info') {
            // Remove existing toasts
            document.querySelectorAll('.custom-toast').forEach(toast => toast.remove());

            const toast = document.createElement('div');
            toast.className = `custom-toast fixed top-24 right-6 p-4 rounded-lg shadow-lg z-[1000] max-w-sm animate-slideInUp ${type === 'error' ? 'bg-red-500 text-white' :
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
                toast.style.transform = 'translateY(-10px)';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    });
</script>
<?= $this->endSection() ?>