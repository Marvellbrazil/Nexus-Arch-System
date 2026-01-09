<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>Manage Departments - NEXUS Admin<?= $this->endSection() ?>

<?= $this->section('background_effects') ?>
<!-- Background Effects -->
<div class="fixed w-[40vw] h-[40vw] -right-[10%] -bottom-[10%] rotate-[149deg] bg-gradient-to-r from-[rgba(56.96,44.47,127.72,0.15)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-80 z-0"></div>
<div class="fixed w-[35vw] h-[25vw] -left-[5%] top-[10%] rotate-[8deg] bg-gradient-to-r from-[rgba(65.04,45.10,137.14,0.20)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-70 z-0"></div>
<div class="fixed w-[15vw] h-[20vw] right-[5%] -top-[5%] rotate-[8deg] bg-gradient-to-r from-[rgba(16.56,8.41,46.05,0.12)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-60 z-0"></div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="relative z-10">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-[34.77px] font-semibold mb-2 text-text-dark">Manage Departments</h1>
        <p class="text-[15.45px] font-light text-text-dark">Technical department configuration and management</p>
    </div>

    <!-- Action Bar -->
    <div class="flex flex-col md:flex-row gap-4 justify-between items-start md:items-center mb-6">
        <!-- Search Box -->
        <div class="w-full md:w-96">
            <div class="relative">
                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-text-muted">
                    <i class="fas fa-search"></i>
                </div>
                <input type="text" 
                       placeholder="Search by department name" 
                       id="departmentSearch"
                       class="w-full h-12 pl-12 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
            </div>
        </div>

        <!-- Status Filter Tabs -->
        <div class="bg-[#F3F4F6] rounded-lg p-1 flex items-center">
            <button class="department-filter px-4 py-2 rounded-md font-medium text-sm transition-all active" data-filter="all">
                All
            </button>
            <button class="department-filter px-4 py-2 rounded-md font-medium text-sm transition-all" data-filter="active">
                Active
            </button>
            <button class="department-filter px-4 py-2 rounded-md font-medium text-sm transition-all" data-filter="inactive">
                Inactive
            </button>
        </div>

        <!-- Add New Department Button -->
        <button id="addDepartmentBtn" class="w-full md:w-auto h-12 px-6 bg-secondary text-white rounded-lg hover:bg-[#665C9E] transition-colors font-medium flex items-center justify-center gap-2">
            <i class="fas fa-plus text-lg"></i>
            Add New Department
        </button>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Departments List -->
        <div class="lg:col-span-2">
            <div class="dashboard-card h-full">
                <div class="dashboard-card-header flex justify-between items-center">
                    <div class="text-text-dark/85 text-base font-medium">Departments</div>
                    <div class="text-sm text-secondary font-medium" id="departmentCount">
                        <span id="showingCount">0</span> of <span id="totalCount">0</span> departments
                    </div>
                </div>

                <!-- Departments Table -->
                <div class="overflow-x-auto">
                    <!-- Table Header -->
                    <div class="grid grid-cols-12 gap-4 py-4 px-6 bg-[#E3DAEE] rounded-lg text-sm font-semibold text-text-dark/80">
                        <div class="col-span-3">Department Name</div>
                        <div class="col-span-4">Description</div>
                        <div class="col-span-2 text-center">Total Members</div>
                        <div class="col-span-2 text-center">Status</div>
                        <div class="col-span-1"></div>
                    </div>

                    <!-- Departments List -->
                    <div id="departmentsList" class="divide-y divide-white/30">
                        <!-- Departments will be loaded here -->
                    </div>
                </div>
                
                <!-- Empty State -->
                <div id="emptyState" class="hidden py-12 text-center">
                    <i class="fas fa-building text-gray-300 text-4xl mb-4"></i>
                    <p class="text-gray-500 text-lg mb-2">No departments found</p>
                    <p class="text-gray-400 text-sm">Try adjusting your search or filters</p>
                </div>

                <!-- Pagination -->
                <div class="border-t border-white/30 mt-4 pt-4">
                    <div class="flex justify-between items-center px-6">
                        <div class="text-text-dark/70 text-sm">
                            <span id="paginationInfo">Page 1 of 1</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button id="prevPage" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/20 hover:bg-secondary/20 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-chevron-left text-sm"></i>
                            </button>
                            <div id="pageNumbers" class="flex items-center gap-1">
                                <!-- Page numbers will be populated here -->
                            </div>
                            <button id="nextPage" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/20 hover:bg-secondary/20 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-chevron-right text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Department Details -->
        <div class="space-y-6">
            <!-- Department Info Card -->
            <div class="dashboard-card">
                <div class="dashboard-card-header flex justify-between items-center">
                    <div class="text-text-dark/85 text-base font-medium">Department Details</div>
                    <button id="closeDetails" class="text-text-dark/60 hover:text-secondary transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div id="departmentDetails" class="p-4">
                    <!-- Default state when no department is selected -->
                    <div class="flex flex-col items-center justify-center py-8 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-building text-gray-400 text-xl"></i>
                        </div>
                        <p class="text-text-dark/60 text-sm">Select a department to view details</p>
                    </div>
                    
                    <!-- Department details will be loaded here -->
                </div>
            </div>

            <!-- Quick Stats Card -->
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <div class="text-text-dark/85 text-base font-medium">Quick Stats</div>
                </div>
                <div class="space-y-4 p-4">
                    <div class="flex justify-between items-center">
                        <span class="text-text-dark/70 text-sm">Total Departments</span>
                        <span class="text-text-dark font-semibold" id="totalDepartments">0</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-text-dark/70 text-sm">Active Departments</span>
                        <span class="text-[#15803D] font-semibold" id="activeDepartments">0</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-text-dark/70 text-sm">Inactive Departments</span>
                        <span class="text-text-dark/50 font-semibold" id="inactiveDepartments">0</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-text-dark/70 text-sm">Total Members</span>
                        <span class="text-text-dark font-semibold" id="totalMembers">0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Templates -->
<template id="addDepartmentModalTemplate">
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4">
        <div class="bg-white rounded-2xl w-full max-w-md animate-slideInUp">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-800">Add New Department</h3>
                    <button class="close-modal text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Department Name</label>
                        <input type="text" id="departmentName" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary" placeholder="e.g., IT Support">
                    </div>
                    
                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Description</label>
                        <textarea id="departmentDescription" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary" rows="3" placeholder="Describe the department's purpose and responsibilities"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Status</label>
                        <select id="departmentStatus" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Department Head</label>
                        <input type="text" id="departmentHead" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary" placeholder="Optional">
                    </div>
                    
                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Default Ticket Categories</label>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="catBug" class="rounded" checked>
                                <label for="catBug" class="text-sm text-gray-700">Bug</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="catTechnical" class="rounded" checked>
                                <label for="catTechnical" class="text-sm text-gray-700">Technical Support</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="catFeature" class="rounded">
                                <label for="catFeature" class="text-sm text-gray-700">Feature Request</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="p-6 border-t border-gray-200 flex gap-3">
                <button class="close-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button id="confirmAddDepartment" class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors">
                    Add Department
                </button>
            </div>
        </div>
    </div>
</template>

<template id="editDepartmentModalTemplate">
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4">
        <div class="bg-white rounded-2xl w-full max-w-md animate-slideInUp">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-800">Edit Department</h3>
                    <button class="close-modal text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Department Name</label>
                        <input type="text" id="editDepartmentName" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                    </div>
                    
                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Description</label>
                        <textarea id="editDepartmentDescription" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary" rows="3"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Status</label>
                        <select id="editDepartmentStatus" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Department Head</label>
                        <input type="text" id="editDepartmentHead" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                    </div>
                    
                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Ticket Categories</label>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="editCatBug" class="rounded">
                                <label for="editCatBug" class="text-sm text-gray-700">Bug</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="editCatTechnical" class="rounded">
                                <label for="editCatTechnical" class="text-sm text-gray-700">Technical Support</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="editCatFeature" class="rounded">
                                <label for="editCatFeature" class="text-sm text-gray-700">Feature Request</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="p-6 border-t border-gray-200 flex gap-3">
                <button class="close-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button id="confirmEditDepartment" class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</template>

<template id="departmentActionsMenuTemplate">
    <div class="department-actions-menu absolute bg-white rounded-xl shadow-xl border border-gray-200 z-50 w-48">
        <div class="py-2">
            <button class="menu-item view-details">
                <i class="fas fa-eye text-gray-600"></i>
                View Details
            </button>
            <button class="menu-item edit-department">
                <i class="fas fa-edit text-secondary"></i>
                Edit Department
            </button>
            <button class="menu-item manage-members">
                <i class="fas fa-users text-blue-600"></i>
                Manage Members
            </button>
            <div class="border-t border-gray-200 my-1"></div>
            <button class="menu-item toggle-status">
                <i class="fas fa-power-off text-red-600"></i>
                <span class="toggle-status-text">Deactivate</span>
            </button>
        </div>
    </div>
</template>

<style>
    /* Custom animations */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
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
    
    /* Department item styling */
    .department-item {
        display: grid;
        grid-template-columns: repeat(12, minmax(0, 1fr));
        gap: 1rem;
        padding: 1rem 1.5rem;
        transition: all 0.2s ease;
        align-items: center;
    }
    
    .department-item:hover {
        background: rgba(117, 110, 164, 0.05);
        cursor: pointer;
    }
    
    .department-item.selected {
        background: rgba(102, 92, 158, 0.1);
        border-left: 3px solid #665C9E;
    }
    
    /* Status badges */
    .status-badge {
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
    }
    
    .status-active {
        background: #C4E3AC;
        color: #15803D;
    }
    
    .status-inactive {
        background: #ECDCD3;
        color: #93867E;
    }
    
    /* Filter tabs */
    .department-filter {
        transition: all 0.2s ease;
    }
    
    .department-filter.active {
        background: #DEDBF8;
        color: #7E22CE;
        box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.05);
    }
    
    /* Department details */
    .department-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        color: white;
        margin: 0 auto 16px;
        background: linear-gradient(135deg, #5952A3, #8A84C6);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        padding: 1rem;
        background: #E3DAEE;
        border-radius: 12px;
        margin-bottom: 1rem;
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-value {
        font-size: 20px;
        font-weight: 400;
        color: #374151;
        line-height: 1.2;
    }
    
    .stat-label {
        font-size: 10px;
        color: #434264;
        text-transform: uppercase;
        margin-top: 2px;
    }
    
    .member-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 0;
    }
    
    .member-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .member-avatar {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        background: #5952A3;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
        font-weight: bold;
    }
    
    .view-badge {
        padding: 2px 8px;
        background: #C1E0A9;
        color: #145C2F;
        border-radius: 9999px;
        font-size: 10px;
        text-transform: uppercase;
    }
    
    .category-item {
        padding: 4px 0;
        font-size: 12px;
        color: #6B7280;
    }
    
    /* Action menu */
    .department-actions-menu {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .menu-item {
        width: 100%;
        padding: 12px 16px;
        text-align: left;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        background: none;
        border: none;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }
    
    .menu-item:hover {
        background: #F9FAFB;
    }
    
    /* Action buttons */
    .action-buttons {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 1rem;
    }
    
    .action-btn {
        padding: 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        text-align: center;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }
    
    .action-btn.primary {
        background: #6D4ACA;
        color: white;
    }
    
    .action-btn.primary:hover {
        background: #5A3FB8;
        transform: translateY(-1px);
    }
    
    .action-btn.secondary {
        background: white;
        color: #EF4444;
        border-color: #EF4444;
    }
    
    .action-btn.secondary:hover {
        background: #FEF2F2;
        transform: translateY(-1px);
    }
    
    /* Responsive adjustments */
    @media (max-width: 1024px) {
        .department-item {
            grid-template-columns: repeat(8, minmax(0, 1fr));
            gap: 0.75rem;
            padding: 0.75rem 1rem;
        }
        
        .department-item > div:nth-child(1) { grid-column: span 2; }
        .department-item > div:nth-child(2) { grid-column: span 3; }
        .department-item > div:nth-child(3) { grid-column: span 1; }
        .department-item > div:nth-child(4) { grid-column: span 1; }
        .department-item > div:nth-child(5) { grid-column: span 1; }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .department-item {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }
        
        .department-item > div {
            grid-column: span 1 !important;
        }
        
        .action-buttons {
            grid-template-columns: 1fr;
        }
    }
    
    /* Loading skeleton */
    .skeleton-loader {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
        border-radius: 4px;
    }
    
    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    
    /* Scrollbar styling */
    .departments-container {
        max-height: 500px;
        overflow-y: auto;
    }
    
    .departments-container::-webkit-scrollbar {
        width: 6px;
    }
    
    .departments-container::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 3px;
    }
    
    .departments-container::-webkit-scrollbar-thumb {
        background: rgba(102, 92, 158, 0.4);
        border-radius: 3px;
    }
    
    .departments-container::-webkit-scrollbar-thumb:hover {
        background: rgba(102, 92, 158, 0.6);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initial data
        let departmentsData = [
            {
                id: 1,
                name: "QA Team",
                description: "Quality assurance, testing, and issue validation.",
                detailedDescription: "Responsible for testing application quality and ensuring features meet requirements.",
                members: 19,
                activeTickets: 12,
                resolvedTickets: 45,
                status: "active",
                categories: ["Testing", "Quality", "Validation"],
                icon: "fa-clipboard-check",
                head: "John Doe",
                created: "Jan 15, 2024"
            },
            {
                id: 2,
                name: "IT Support",
                description: "Handles technical support, access, and system issues.",
                detailedDescription: "Handles technical issues, system support, network, and user access.",
                members: 21,
                activeTickets: 8,
                resolvedTickets: 34,
                status: "active",
                categories: ["Bug", "Technical Support", "Feature Request"],
                icon: "fa-headset",
                head: "Jane Smith",
                created: "Feb 10, 2024"
            },
            {
                id: 3,
                name: "Backend Team",
                description: "Manages APIs, databases, and server-side logic.",
                detailedDescription: "Manages system logic, databases, APIs, and server-side processes.",
                members: 13,
                activeTickets: 5,
                resolvedTickets: 28,
                status: "active",
                categories: ["API", "Database", "Server"],
                icon: "fa-server",
                head: "Alex Johnson",
                created: "Mar 5, 2024"
            },
            {
                id: 4,
                name: "Frontend Team",
                description: "Handles UI, UX, and client-side functionality.",
                detailedDescription: "Responsible for user interface design and overall user experience.",
                members: 11,
                activeTickets: 7,
                resolvedTickets: 31,
                status: "active",
                categories: ["UI", "UX", "Frontend"],
                icon: "fa-paint-brush",
                head: "Sarah Williams",
                created: "Apr 20, 2024"
            },
            {
                id: 5,
                name: "Security Team",
                description: "Manages system security and access controls.",
                detailedDescription: "Responsible for security monitoring, access controls, and compliance.",
                members: 8,
                activeTickets: 3,
                resolvedTickets: 12,
                status: "inactive",
                categories: ["Security", "Compliance", "Access"],
                icon: "fa-shield-alt",
                head: "Michael Brown",
                created: "May 15, 2024"
            }
        ];
        
        let filteredDepartments = [...departmentsData];
        let selectedDepartmentId = null;
        let currentFilter = "all";
        let currentPage = 1;
        let itemsPerPage = 5;
        
        // DOM Elements
        const departmentSearch = document.getElementById('departmentSearch');
        const filterButtons = document.querySelectorAll('.department-filter');
        const addDepartmentBtn = document.getElementById('addDepartmentBtn');
        const closeDetailsBtn = document.getElementById('closeDetails');
        const departmentsList = document.getElementById('departmentsList');
        const emptyState = document.getElementById('emptyState');
        const departmentDetails = document.getElementById('departmentDetails');
        const departmentCount = document.getElementById('departmentCount');
        const showingCount = document.getElementById('showingCount');
        const totalCount = document.getElementById('totalCount');
        const paginationInfo = document.getElementById('paginationInfo');
        const pageNumbers = document.getElementById('pageNumbers');
        const prevPage = document.getElementById('prevPage');
        const nextPage = document.getElementById('nextPage');
        const totalDepartments = document.getElementById('totalDepartments');
        const activeDepartments = document.getElementById('activeDepartments');
        const inactiveDepartments = document.getElementById('inactiveDepartments');
        const totalMembers = document.getElementById('totalMembers');
        
        // Initialize
        init();
        
        function init() {
            renderDepartments();
            updateQuickStats();
            setupEventListeners();
        }
        
        function setupEventListeners() {
            // Search functionality
            if (departmentSearch) {
                departmentSearch.addEventListener('input', debounce(() => {
                    filterDepartments();
                }, 300));
            }
            
            // Filter buttons
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    // Add active class to clicked button
                    this.classList.add('active');
                    // Update filter
                    currentFilter = this.dataset.filter;
                    filterDepartments();
                });
            });
            
            // Add department button
            if (addDepartmentBtn) {
                addDepartmentBtn.addEventListener('click', showAddDepartmentModal);
            }
            
            // Close details button
            if (closeDetailsBtn) {
                closeDetailsBtn.addEventListener('click', clearSelectedDepartment);
            }
            
            // Pagination
            if (prevPage) {
                prevPage.addEventListener('click', () => changePage(currentPage - 1));
            }
            
            if (nextPage) {
                nextPage.addEventListener('click', () => changePage(currentPage + 1));
            }
        }
        
        function renderDepartments() {
            if (!departmentsList) return;
            
            departmentsList.innerHTML = '';
            
            // Get current page departments
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const pageDepartments = filteredDepartments.slice(startIndex, endIndex);
            
            if (pageDepartments.length === 0) {
                emptyState.classList.remove('hidden');
                departmentsList.classList.add('hidden');
                updatePagination();
                updateShowingCount();
                return;
            }
            
            emptyState.classList.add('hidden');
            departmentsList.classList.remove('hidden');
            
            pageDepartments.forEach(dept => {
                const deptElement = document.createElement('div');
                deptElement.className = `department-item ${selectedDepartmentId === dept.id ? 'selected' : ''}`;
                deptElement.dataset.departmentId = dept.id;
                
                deptElement.innerHTML = `
                    <div class="col-span-3">
                        <div class="font-medium text-[#5A516B]">${dept.name}</div>
                        <div class="text-xs text-text-dark/50 truncate">${dept.description}</div>
                    </div>
                    <div class="col-span-4">
                        <p class="text-xs text-text-dark/70 truncate">${dept.detailedDescription}</p>
                    </div>
                    <div class="col-span-2 text-center">
                        <span class="text-[#5A516B] text-sm font-semibold">${dept.members}</span>
                    </div>
                    <div class="col-span-2 text-center">
                        <span class="${dept.status === 'active' ? 'status-active' : 'status-inactive'} status-badge">
                            ${dept.status === 'active' ? 'Active' : 'Inactive'}
                        </span>
                    </div>
                    <div class="col-span-1 text-right">
                        <button class="department-action-btn text-text-dark/60 hover:text-secondary transition-colors">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                `;
                
                // Add click event for selecting department
                deptElement.addEventListener('click', (e) => {
                    if (!e.target.closest('.department-action-btn')) {
                        selectDepartment(dept.id);
                    }
                });
                
                // Add action menu event
                const actionBtn = deptElement.querySelector('.department-action-btn');
                actionBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    showDepartmentActionsMenu(e.target.closest('button'), dept);
                });
                
                departmentsList.appendChild(deptElement);
            });
            
            updatePagination();
            updateShowingCount();
        }
        
        function filterDepartments() {
            const searchTerm = departmentSearch ? departmentSearch.value.toLowerCase().trim() : '';
            
            filteredDepartments = departmentsData.filter(dept => {
                // Apply search filter
                if (searchTerm) {
                    if (!dept.name.toLowerCase().includes(searchTerm) && 
                        !dept.description.toLowerCase().includes(searchTerm) &&
                        !dept.detailedDescription.toLowerCase().includes(searchTerm)) {
                        return false;
                    }
                }
                
                // Apply status filter
                if (currentFilter !== 'all' && dept.status !== currentFilter) {
                    return false;
                }
                
                return true;
            });
            
            currentPage = 1;
            renderDepartments();
            updateQuickStats();
        }
        
        function selectDepartment(departmentId) {
            selectedDepartmentId = departmentId;
            
            // Update selected row styling
            document.querySelectorAll('.department-item').forEach(row => {
                row.classList.remove('selected');
                if (parseInt(row.dataset.departmentId) === departmentId) {
                    row.classList.add('selected');
                }
            });
            
            // Load department details
            loadDepartmentDetails(departmentId);
        }
        
        function clearSelectedDepartment() {
            selectedDepartmentId = null;
            
            // Clear selected styling
            document.querySelectorAll('.department-item').forEach(row => {
                row.classList.remove('selected');
            });
            
            // Clear details panel
            departmentDetails.innerHTML = `
                <div class="flex flex-col items-center justify-center py-8 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-building text-gray-400 text-xl"></i>
                    </div>
                    <p class="text-text-dark/60 text-sm">Select a department to view details</p>
                </div>
            `;
        }
        
        function loadDepartmentDetails(departmentId) {
            const dept = departmentsData.find(d => d.id === departmentId);
            if (!dept) return;
            
            const detailsHtml = `
                <div class="animate-fadeIn">
                    <!-- Department Header -->
                    <div class="flex items-start gap-3 mb-6">
                        <div class="department-avatar">
                            <i class="fas ${dept.icon}"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-text-dark">${dept.name}</h3>
                            <p class="text-text-dark/60 text-sm">Department</p>
                        </div>
                    </div>
                    
                    <!-- Status -->
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-text-dark/80 text-sm font-medium">Status</span>
                        <span class="px-3 py-1 ${dept.status === 'active' ? 'bg-[#C1E0A9] text-[#145C2F]' : 'bg-[#FECACA] text-[#991B1B]'} rounded-full text-xs font-medium uppercase">
                            <i class="fas fa-circle text-[6px] mr-1"></i>
                            ${dept.status === 'active' ? 'Active' : 'Inactive'}
                        </span>
                    </div>
                    
                    <!-- Statistics -->
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-value">${dept.members}</div>
                            <div class="stat-label">Members</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">${dept.activeTickets}</div>
                            <div class="stat-label">Active Tickets</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">${dept.resolvedTickets}</div>
                            <div class="stat-label">Resolved</div>
                        </div>
                    </div>
                    
                    <!-- Assigned Members -->
                    <div class="mb-6">
                        <h4 class="text-text-dark/80 text-sm font-medium mb-3">Assigned Members</h4>
                        <div class="space-y-3">
                            <div class="member-item">
                                <div class="member-info">
                                    <div class="member-avatar">JD</div>
                                    <span class="text-sm text-text-dark">John Doe</span>
                                </div>
                                <span class="view-badge">View</span>
                            </div>
                            <div class="member-item">
                                <div class="member-info">
                                    <div class="member-avatar">JS</div>
                                    <span class="text-sm text-text-dark">Jane Smith</span>
                                </div>
                                <span class="view-badge">View</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Ticket Categories -->
                    <div class="mb-6">
                        <h4 class="text-text-dark/80 text-sm font-medium mb-3 flex items-center gap-2">
                            <i class="fas fa-info-circle text-[#8B7EBB]"></i>
                            Ticket Categories
                        </h4>
                        <div class="pl-6">
                            ${dept.categories.map(cat => `
                                <div class="category-item">${cat}</div>
                            `).join('')}
                        </div>
                    </div>
                    
                    <!-- Additional Info -->
                    <div class="bg-[#E3DAEE] rounded-lg p-3 mb-6">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-sticky-note text-[#434264] mt-1"></i>
                            <p class="text-[10px] text-text-dark/70 leading-tight">
                                ${dept.name === 'IT Support' 
                                    ? 'New Technical Support tickets are assigned to this department by default.'
                                    : `${dept.name} handles specialized tickets related to their expertise.`
                                }
                            </p>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button class="action-btn primary edit-department-btn" data-department-id="${dept.id}">
                            Edit Department
                        </button>
                        <button class="action-btn secondary toggle-status-btn" data-department-id="${dept.id}">
                            ${dept.status === 'active' ? 'Deactivate' : 'Activate'} Department
                        </button>
                    </div>
                </div>
            `;
            
            departmentDetails.innerHTML = detailsHtml;
            
            // Add event listeners to action buttons
            setTimeout(() => {
                const editBtn = departmentDetails.querySelector('.edit-department-btn');
                const toggleBtn = departmentDetails.querySelector('.toggle-status-btn');
                
                if (editBtn) {
                    editBtn.addEventListener('click', () => editDepartment(dept.id));
                }
                
                if (toggleBtn) {
                    toggleBtn.addEventListener('click', () => toggleDepartmentStatus(dept.id));
                }
            }, 100);
        }
        
        function showDepartmentActionsMenu(button, department) {
            // Remove existing menus
            document.querySelectorAll('.department-actions-menu').forEach(menu => menu.remove());
            
            // Create menu from template
            const template = document.getElementById('departmentActionsMenuTemplate');
            const menu = template.content.cloneNode(true);
            const menuElement = menu.querySelector('.department-actions-menu');
            
            // Update toggle status text
            const toggleText = menuElement.querySelector('.toggle-status-text');
            if (toggleText) {
                toggleText.textContent = department.status === 'active' ? 'Deactivate' : 'Activate';
            }
            
            // Position menu
            const rect = button.getBoundingClientRect();
            menuElement.style.position = 'fixed';
            menuElement.style.top = `${rect.bottom + window.scrollY + 5}px`;
            menuElement.style.left = `${rect.left + window.scrollX - 180}px`;
            
            // Add event listeners to menu items
            const menuItems = menuElement.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                item.addEventListener('click', () => {
                    menuElement.remove();
                    handleDepartmentAction(department, item.classList);
                });
            });
            
            document.body.appendChild(menuElement);
            
            // Close menu when clicking outside
            setTimeout(() => {
                const closeMenu = (e) => {
                    if (!menuElement.contains(e.target) && !button.contains(e.target)) {
                        menuElement.remove();
                        document.removeEventListener('click', closeMenu);
                    }
                };
                document.addEventListener('click', closeMenu);
            });
        }
        
        function handleDepartmentAction(department, classList) {
            if (classList.contains('view-details')) {
                selectDepartment(department.id);
            } else if (classList.contains('edit-department')) {
                editDepartment(department.id);
            } else if (classList.contains('manage-members')) {
                manageDepartmentMembers(department.id);
            } else if (classList.contains('toggle-status')) {
                toggleDepartmentStatus(department.id);
            }
        }
        
        function showAddDepartmentModal() {
            // Create modal from template
            const template = document.getElementById('addDepartmentModalTemplate');
            const modal = document.importNode(template.content, true);
            
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
            
            const confirmBtn = modal.querySelector('#confirmAddDepartment');
            confirmBtn.addEventListener('click', () => {
                createNewDepartment(modal);
            });
        }
        
        function editDepartment(departmentId) {
            const dept = departmentsData.find(d => d.id === departmentId);
            if (!dept) return;
            
            // Create modal from template
            const template = document.getElementById('editDepartmentModalTemplate');
            const modal = document.importNode(template.content, true);
            
            // Fill modal data
            modal.querySelector('#editDepartmentName').value = dept.name;
            modal.querySelector('#editDepartmentDescription').value = dept.description;
            modal.querySelector('#editDepartmentStatus').value = dept.status;
            modal.querySelector('#editDepartmentHead').value = dept.head || '';
            
            // Set category checkboxes
            dept.categories.forEach(cat => {
                const checkboxId = `editCat${cat.replace(/\s+/g, '')}`;
                const checkbox = modal.querySelector(`#${checkboxId}`);
                if (checkbox) {
                    checkbox.checked = true;
                }
            });
            
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
            
            const confirmBtn = modal.querySelector('#confirmEditDepartment');
            confirmBtn.addEventListener('click', () => {
                updateDepartment(departmentId, modal);
            });
        }
        
        function createNewDepartment(modal) {
            const name = modal.querySelector('#departmentName').value.trim();
            const description = modal.querySelector('#departmentDescription').value.trim();
            const status = modal.querySelector('#departmentStatus').value;
            const head = modal.querySelector('#departmentHead').value.trim();
            
            if (!name || !description) {
                alert('Please fill in all required fields');
                return;
            }
            
            // Get selected categories
            const categories = [];
            if (modal.querySelector('#catBug').checked) categories.push('Bug');
            if (modal.querySelector('#catTechnical').checked) categories.push('Technical Support');
            if (modal.querySelector('#catFeature').checked) categories.push('Feature Request');
            
            // Create new department
            const newDept = {
                id: Math.max(...departmentsData.map(d => d.id)) + 1,
                name: name,
                description: description,
                detailedDescription: description,
                members: 0,
                activeTickets: 0,
                resolvedTickets: 0,
                status: status,
                categories: categories.length > 0 ? categories : ['General'],
                icon: getRandomIcon(),
                head: head || 'Not assigned',
                created: new Date().toLocaleDateString('en-US', { 
                    month: 'short', 
                    day: 'numeric', 
                    year: 'numeric' 
                })
            };
            
            // Add to departments data
            departmentsData.push(newDept);
            
            // Close modal
            document.body.removeChild(modal.querySelector('.fixed'));
            document.body.style.overflow = 'auto';
            
            // Update UI
            filterDepartments();
            selectDepartment(newDept.id);
            
            showToast(`Department "${name}" created successfully!`, 'success');
        }
        
        function updateDepartment(departmentId, modal) {
            const dept = departmentsData.find(d => d.id === departmentId);
            if (!dept) return;
            
            const name = modal.querySelector('#editDepartmentName').value.trim();
            const description = modal.querySelector('#editDepartmentDescription').value.trim();
            const status = modal.querySelector('#editDepartmentStatus').value;
            const head = modal.querySelector('#editDepartmentHead').value.trim();
            
            if (!name || !description) {
                alert('Please fill in all required fields');
                return;
            }
            
            // Get selected categories
            const categories = [];
            if (modal.querySelector('#editCatBug').checked) categories.push('Bug');
            if (modal.querySelector('#editCatTechnical').checked) categories.push('Technical Support');
            if (modal.querySelector('#editCatFeature').checked) categories.push('Feature Request');
            
            // Update department data
            dept.name = name;
            dept.description = description;
            dept.detailedDescription = description;
            dept.status = status;
            dept.head = head || 'Not assigned';
            dept.categories = categories.length > 0 ? categories : ['General'];
            
            // Close modal
            document.body.removeChild(modal.querySelector('.fixed'));
            document.body.style.overflow = 'auto';
            
            // Update UI
            renderDepartments();
            if (selectedDepartmentId === departmentId) {
                loadDepartmentDetails(departmentId);
            }
            
            showToast('Department updated successfully!', 'success');
        }
        
        function toggleDepartmentStatus(departmentId) {
            const dept = departmentsData.find(d => d.id === departmentId);
            if (!dept) return;
            
            const newStatus = dept.status === 'active' ? 'inactive' : 'active';
            const action = newStatus === 'active' ? 'activate' : 'deactivate';
            
            if (confirm(`Are you sure you want to ${action} this department?`)) {
                // Update department status
                dept.status = newStatus;
                
                // Update UI
                renderDepartments();
                if (selectedDepartmentId === departmentId) {
                    loadDepartmentDetails(departmentId);
                }
                
                updateQuickStats();
                
                showToast(`Department ${action}d successfully`, 'success');
            }
        }
        
        function manageDepartmentMembers(departmentId) {
            const dept = departmentsData.find(d => d.id === departmentId);
            if (!dept) return;
            
            showToast(`Manage members for ${dept.name} department`, 'info');
            // In a real app, this would open a member management interface
        }
        
        function updateQuickStats() {
            const total = departmentsData.length;
            const active = departmentsData.filter(d => d.status === 'active').length;
            const inactive = total - active;
            const members = departmentsData.reduce((sum, dept) => sum + dept.members, 0);
            
            totalDepartments.textContent = total;
            activeDepartments.textContent = active;
            inactiveDepartments.textContent = inactive;
            totalMembers.textContent = members;
        }
        
        function updatePagination() {
            const totalPages = Math.max(1, Math.ceil(filteredDepartments.length / itemsPerPage));
            
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
        
        function changePage(page) {
            if (page < 1 || page > Math.ceil(filteredDepartments.length / itemsPerPage)) return;
            
            currentPage = page;
            renderDepartments();
            
            // Scroll to top of list
            if (departmentsList) {
                departmentsList.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
        
        function updateShowingCount() {
            const startIndex = (currentPage - 1) * itemsPerPage + 1;
            const endIndex = Math.min(startIndex + itemsPerPage - 1, filteredDepartments.length);
            
            if (showingCount) {
                showingCount.textContent = `${startIndex}-${endIndex}`;
            }
            
            if (totalCount) {
                totalCount.textContent = filteredDepartments.length;
            }
        }
        
        // Utility functions
        function getRandomIcon() {
            const icons = [
                'fa-clipboard-check',
                'fa-headset',
                'fa-server',
                'fa-paint-brush',
                'fa-shield-alt',
                'fa-code',
                'fa-database',
                'fa-network-wired',
                'fa-cloud',
                'fa-mobile-alt'
            ];
            return icons[Math.floor(Math.random() * icons.length)];
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
        
        function showToast(message, type = 'info') {
            // Remove existing toasts
            document.querySelectorAll('.custom-toast').forEach(toast => toast.remove());
            
            const toast = document.createElement('div');
            toast.className = `custom-toast fixed top-24 right-6 p-4 rounded-lg shadow-lg z-[1000] max-w-sm animate-slideInUp ${
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
                toast.style.transform = 'translateY(-10px)';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
        
        // Auto-select first department on load
        setTimeout(() => {
            if (departmentsData.length > 0) {
                selectDepartment(departmentsData[0].id);
            }
        }, 100);
    });
</script>
<?= $this->endSection() ?>