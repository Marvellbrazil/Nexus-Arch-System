<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>Manage Projects - NEXUS Admin<?= $this->endSection() ?>

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
        <h1 class="text-[34.77px] font-semibold mb-2 text-text-dark">Manage Projects</h1>
        <p class="text-[15.45px] font-light text-text-dark">Project management and configuration</p>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Content -->
        <div class="lg:col-span-2">
            <!-- Search and Filter Card -->
            <div class="dashboard-card mb-6">
                <div class="dashboard-card-header">
                    <div class="text-text-dark/85 text-base font-medium">Search & Filter</div>
                </div>

                <div class="p-4 space-y-4">
                    <!-- Search Bar -->
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-text-muted">
                            <i class="fas fa-search"></i>
                        </div>
                        <input type="text" placeholder="Search projects..." id="projectSearch"
                            class="w-full h-12 pl-12 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
                    </div>

                    <!-- Filter Options -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Status Filter -->
                        <div>
                            <label class="block text-text-dark/70 text-sm mb-2">Status</label>
                            <select id="statusFilter"
                                class="w-full h-12 pl-4 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="completed">Completed</option>
                                <option value="on-hold">On Hold</option>
                            </select>
                        </div>

                        <!-- Sort By -->
                        <div>
                            <label class="block text-text-dark/70 text-sm mb-2">Sort By</label>
                            <select id="sortFilter"
                                class="w-full h-12 pl-4 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary">
                                <option value="name_asc">Name (A-Z)</option>
                                <option value="name_desc">Name (Z-A)</option>
                                <option value="date_desc">Newest First</option>
                                <option value="date_asc">Oldest First</option>
                                <option value="tickets_desc">Most Tickets</option>
                                <option value="tickets_asc">Fewest Tickets</option>
                            </select>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3">
                        <button id="resetFilters"
                            class="flex-1 h-12 bg-secondary text-white rounded-xl hover:bg-[#665C9E] transition-colors font-medium">
                            Reset Filters
                        </button>
                        <button id="exportProjects"
                            class="flex-1 h-12 bg-white text-secondary border border-secondary rounded-xl hover:bg-secondary/5 transition-colors font-medium">
                            Export Projects
                        </button>
                    </div>
                </div>
            </div>

            <!-- Projects Table -->
            <div class="dashboard-card">
                <div class="dashboard-card-header flex justify-between items-center">
                    <div class="text-text-dark/85 text-base font-medium">Project Management</div>
                    <div class="text-sm text-secondary font-medium">
                        <span id="showingCount">Showing 1-5</span> of <span id="totalCount">15</span> projects
                    </div>
                </div>

                <!-- Table Container -->
                <div class="overflow-x-auto">
                    <!-- Table Header -->
                    <div
                        class="grid grid-cols-12 gap-4 py-4 px-6 bg-[#E3DAEE] rounded-lg text-sm font-semibold text-text-dark/80">
                        <div class="col-span-2 flex items-center gap-2 cursor-pointer sortable" data-sort="id">
                            <span>Project ID</span>
                            <i class="fas fa-sort text-xs opacity-50"></i>
                        </div>
                        <div class="col-span-3 cursor-pointer sortable" data-sort="name">
                            <span>Project Name</span>
                            <i class="fas fa-sort text-xs opacity-50 ml-1"></i>
                        </div>
                        <div class="col-span-2">Project Code</div>
                        <div class="col-span-2">Total Tickets</div>
                        <div class="col-span-2">Status</div>
                        <div class="col-span-1"></div>
                    </div>

                    <!-- Projects List -->
                    <div id="projectsList" class="divide-y divide-white/30">
                        <!-- Project rows will be populated here -->
                    </div>
                </div>

                <!-- Pagination -->
                <div class="border-t border-white/30 mt-4 pt-4">
                    <div class="flex justify-between items-center px-6">
                        <div class="text-text-dark/70 text-sm">
                            <span id="paginationInfo">Page 1 of 3</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button id="prevPage"
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/20 hover:bg-secondary/20 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-chevron-left text-sm"></i>
                            </button>
                            <div id="pageNumbers" class="flex items-center gap-1">
                                <!-- Page numbers will be populated here -->
                            </div>
                            <button id="nextPage"
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/20 hover:bg-secondary/20 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-chevron-right text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Project Details & Actions -->
        <div class="space-y-6">
            <!-- Add Project Card -->
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <div class="text-text-dark/85 text-base font-medium">Quick Actions</div>
                </div>

                <div class="p-4 space-y-3">
                    <button id="addProjectBtn"
                        class="w-full py-3 bg-secondary text-white rounded-xl hover:bg-[#665C9E] transition-colors font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-plus text-lg"></i>
                        Add New Project
                    </button>

                    <button id="bulkAssignBtn"
                        class="w-full py-3 bg-white text-text-dark border border-text-dark/20 rounded-xl hover:bg-gray-50 transition-colors font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-user-plus"></i>
                        Bulk Assign Users
                    </button>

                    <button id="importProjectsBtn"
                        class="w-full py-3 bg-white text-text-dark border border-text-dark/20 rounded-xl hover:bg-gray-50 transition-colors font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-file-import"></i>
                        Import Projects
                    </button>
                </div>
            </div>

            <!-- Project Detail Card -->
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <div class="text-text-dark/85 text-base font-medium">Project Details</div>
                </div>

                <div id="projectDetails" class="p-4">
                    <!-- Default state when no project is selected -->
                    <div class="flex flex-col items-center justify-center py-8 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-project-diagram text-gray-400 text-xl"></i>
                        </div>
                        <p class="text-text-dark/60 text-sm">Select a project to view details</p>
                    </div>

                    <!-- Project details will be loaded here -->
                </div>
            </div>

            <!-- Project Actions Card -->
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <div class="text-text-dark/85 text-base font-medium">Project Actions</div>
                </div>

                <div id="projectActions" class="p-4 space-y-3">
                    <!-- Default state when no project is selected -->
                    <div class="space-y-2">
                        <button class="w-full py-3 bg-gray-100 text-gray-400 rounded-xl cursor-not-allowed" disabled>
                            <i class="fas fa-edit mr-2"></i>
                            Edit Project
                        </button>

                        <button class="w-full py-3 bg-gray-100 text-gray-400 rounded-xl cursor-not-allowed" disabled>
                            <i class="fas fa-users mr-2"></i>
                            Manage Users
                        </button>

                        <button class="w-full py-3 bg-gray-100 text-gray-400 rounded-xl cursor-not-allowed" disabled>
                            <i class="fas fa-power-off mr-2"></i>
                            Change Status
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Project Actions Menu Template -->
<template id="projectActionsMenuTemplate">
    <div class="project-actions-menu absolute bg-white rounded-xl shadow-xl border border-gray-200 z-50 w-48">
        <div class="py-2">
            <button class="menu-item view-details">
                <i class="fas fa-eye text-gray-600"></i>
                View Details
            </button>
            <button class="menu-item edit-project">
                <i class="fas fa-edit text-secondary"></i>
                Edit Project
            </button>
            <button class="menu-item manage-users">
                <i class="fas fa-users text-blue-600"></i>
                Manage Users
            </button>
            <div class="border-t border-gray-200 my-1"></div>
            <button class="menu-item archive-project text-red-600">
                <i class="fas fa-archive"></i>
                Archive
            </button>
        </div>
    </div>
</template>

<!-- Add Project Modal Template -->
<template id="addProjectModalTemplate">
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4">
        <div class="bg-white rounded-2xl w-full max-w-md animate-slideInUp">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-800">Add New Project</h3>
                    <button class="close-modal text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Project Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="projectName"
                            class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                    </div>

                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Project Code <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="projectCode"
                            class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary"
                            placeholder="e.g., PROJ001">
                    </div>

                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Description</label>
                        <textarea id="projectDescription"
                            class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary"
                            rows="3"></textarea>
                    </div>

                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Status</label>
                        <select id="projectStatus"
                            class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="completed">Completed</option>
                            <option value="on-hold">On Hold</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-gray-200 flex gap-3">
                <button
                    class="close-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button id="confirmAddProject"
                    class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors">
                    Create Project
                </button>
            </div>
        </div>
    </div>
</template>

<!-- Edit Project Modal Template -->
<template id="editProjectModalTemplate">
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4">
        <div class="bg-white rounded-2xl w-full max-w-md animate-slideInUp">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-800">Edit Project</h3>
                    <button class="close-modal text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Project Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="editProjectName"
                            class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                    </div>

                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Project Code <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="editProjectCode"
                            class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                    </div>

                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Description</label>
                        <textarea id="editProjectDescription"
                            class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary"
                            rows="3"></textarea>
                    </div>

                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Status</label>
                        <select id="editProjectStatus"
                            class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="completed">Completed</option>
                            <option value="on-hold">On Hold</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Created Date</label>
                        <input type="text" id="editProjectCreated"
                            class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary"
                            disabled>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-gray-200 flex gap-3">
                <button
                    class="close-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button id="confirmEditProject"
                    class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</template>

<!-- Manage Users Modal Template -->
<template id="manageUsersModalTemplate">
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4">
        <div class="bg-white rounded-2xl w-full max-w-lg animate-slideInUp">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-800">Manage Project Users</h3>
                    <button class="close-modal text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <div class="mb-4">
                    <h4 class="text-gray-700 font-medium mb-2">Project: <span id="modalProjectName"></span></h4>
                    <p class="text-gray-500 text-sm">Assign users to this project</p>
                </div>

                <!-- Search Users -->
                <div class="mb-4">
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <i class="fas fa-search"></i>
                        </div>
                        <input type="text" placeholder="Search users..." id="searchUsersInput"
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                    </div>
                </div>

                <!-- Users List -->
                <div class="max-h-64 overflow-y-auto border border-gray-200 rounded-lg">
                    <div id="usersListContainer" class="divide-y divide-gray-100">
                        <!-- Users will be populated here -->
                    </div>
                </div>

                <!-- Selected Users -->
                <div class="mt-4">
                    <h5 class="text-gray-700 font-medium mb-2">Assigned Users (<span id="selectedCount">0</span>)</h5>
                    <div id="selectedUsers" class="flex flex-wrap gap-2">
                        <!-- Selected users will be shown here -->
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-gray-200 flex gap-3">
                <button
                    class="close-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button id="confirmManageUsers"
                    class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors">
                    Save Assignments
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

    /* Project row styling */
    .project-row {
        display: grid;
        grid-template-columns: repeat(12, minmax(0, 1fr));
        gap: 1rem;
        padding: 1rem 1.5rem;
        transition: all 0.2s ease;
        align-items: center;
    }

    .project-row:hover {
        background: rgba(117, 110, 164, 0.05);
    }

    .project-row.selected {
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

    .status-completed {
        background: #BFDBFE;
        color: #1E40AF;
    }

    .status-onhold {
        background: #FEF3C7;
        color: #92400E;
    }

    /* Action menu */
    .project-actions-menu {
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

    /* Pagination */
    .page-btn {
        min-width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .page-btn.active {
        background: #665C9E;
        color: white;
    }

    .page-btn:not(.active):hover {
        background: rgba(102, 92, 158, 0.1);
    }

    /* Sort indicators */
    .sortable {
        user-select: none;
    }

    .sortable:hover {
        color: #665C9E;
    }

    .sortable.active .fa-sort {
        opacity: 1 !important;
        color: #665C9E;
    }

    /* Project details styling */
    .project-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 24px;
        color: white;
        margin: 0 auto 16px;
        background: linear-gradient(135deg, #665C9E, #8A84C6);
    }

    .project-info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.3);
    }

    .project-info-item:last-child {
        border-bottom: none;
    }

    /* Stats grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-top: 16px;
    }

    .stat-item {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 8px;
        padding: 12px;
        text-align: center;
    }

    .stat-value {
        font-size: 20px;
        font-weight: 600;
        color: #665C9E;
        display: block;
    }

    .stat-label {
        font-size: 11px;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* User item in modal */
    .user-item {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .user-item:hover {
        background: #F9FAFB;
    }

    .user-item.selected {
        background: rgba(102, 92, 158, 0.1);
    }

    .user-avatar-small {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #665C9E;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
        font-size: 14px;
        margin-right: 12px;
    }

    /* Selected user tags */
    .selected-user-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #665C9E;
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
    }

    .remove-user-btn {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        font-size: 10px;
        padding: 0;
    }

    /* Loading state */
    .loading-skeleton {
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
        .project-row {
            grid-template-columns: repeat(8, minmax(0, 1fr));
            gap: 0.75rem;
            padding: 0.75rem 1rem;
        }

        .project-row>div:nth-child(1) {
            grid-column: span 2;
        }

        .project-row>div:nth-child(2) {
            grid-column: span 3;
        }

        .project-row>div:nth-child(3) {
            grid-column: span 2;
        }

        .project-row>div:nth-child(4) {
            grid-column: span 2;
        }

        .project-row>div:nth-child(5) {
            grid-column: span 1;
        }

        .project-row>div:nth-child(6) {
            grid-column: span 1;
        }
    }

    @media (max-width: 768px) {
        .dashboard-card-header {
            flex-direction: column;
            gap: 0.5rem;
            align-items: flex-start;
        }

        .project-row {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }

        .project-row>div {
            grid-column: span 1 !important;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // CSRF Token untuk AJAX requests
        const csrfToken = '<?= csrf_hash() ?>';
        const csrfName = '<?= csrf_token() ?>';
        const baseUrl = '<?= base_url() ?>';

        // State management
        let currentPage = 1;
        let itemsPerPage = 10;
        let selectedProjectId = null;
        let isLoading = false;
        let allUsers = <?= json_encode($all_users ?? []) ?>;

        // DOM Elements
        const elements = {
            projectSearch: document.getElementById('projectSearch'),
            statusFilter: document.getElementById('statusFilter'),
            sortFilter: document.getElementById('sortFilter'),
            resetFilters: document.getElementById('resetFilters'),
            exportProjects: document.getElementById('exportProjects'),
            projectsList: document.getElementById('projectsList'),
            projectDetails: document.getElementById('projectDetails'),
            projectActions: document.getElementById('projectActions'),
            showingCount: document.getElementById('showingCount'),
            totalCount: document.getElementById('totalCount'),
            paginationInfo: document.getElementById('paginationInfo'),
            pageNumbers: document.getElementById('pageNumbers'),
            prevPage: document.getElementById('prevPage'),
            nextPage: document.getElementById('nextPage'),
            addProjectBtn: document.getElementById('addProjectBtn'),
            bulkAssignBtn: document.getElementById('bulkAssignBtn'),
            importProjectsBtn: document.getElementById('importProjectsBtn')
        };

        // Modal templates sebagai string
        const bulkAssignModalHTML = `
<div class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4">
    <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden animate-slideInUp">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-800">Bulk Assign Users to Projects</h3>
                <button class="close-modal text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <div class="p-6 overflow-y-auto max-h-[60vh]">
            <!-- Step 1: Select Projects -->
            <div class="mb-6">
                <h4 class="text-gray-700 font-medium mb-4">Step 1: Select Projects</h4>
                <div class="mb-4">
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <i class="fas fa-search"></i>
                        </div>
                        <input type="text" placeholder="Search projects..." id="bulkSearchProjects"
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                    </div>
                </div>
                <div class="max-h-48 overflow-y-auto border border-gray-200 rounded-lg">
                    <div id="bulkProjectsList" class="divide-y divide-gray-100">
                        <!-- Projects will be populated here -->
                    </div>
                </div>
            </div>

            <!-- Step 2: Select Users -->
            <div>
                <h4 class="text-gray-700 font-medium mb-4">Step 2: Select Users</h4>
                <div class="mb-4">
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <i class="fas fa-search"></i>
                        </div>
                        <input type="text" placeholder="Search users..." id="bulkSearchUsers"
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                    </div>
                </div>
                <div class="max-h-48 overflow-y-auto border border-gray-200 rounded-lg">
                    <div id="bulkUsersList" class="divide-y divide-gray-100">
                        <!-- Users will be populated here -->
                    </div>
                </div>
            </div>

            <!-- Summary -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-gray-600">Selected Projects:</span>
                        <span id="bulkSelectedProjectsCount" class="ml-2 font-semibold">0</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Selected Users:</span>
                        <span id="bulkSelectedUsersCount" class="ml-2 font-semibold">0</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Total Assignments:</span>
                        <span id="bulkTotalAssignments" class="ml-2 font-semibold">0</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6 border-t border-gray-200 flex gap-3">
            <button class="close-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                Cancel
            </button>
            <button id="confirmBulkAssign" disabled
                class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                Assign Users
            </button>
        </div>
    </div>
</div>
`;

        const importProjectsModalHTML = `
<div class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4">
    <div class="bg-white rounded-2xl w-full max-w-md animate-slideInUp">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-800">Import Projects</h3>
                <button class="close-modal text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <div class="p-6">
            <!-- File Upload -->
            <div class="mb-6">
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-secondary transition-colors">
                    <div class="mb-4">
                        <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl"></i>
                    </div>
                    <h4 class="text-gray-700 font-medium mb-2">Drop file here or click to browse</h4>
                    <p class="text-gray-500 text-sm mb-4">Supports CSV, XLSX, XLS (Max 10MB)</p>
                    <input type="file" id="importFile" accept=".csv,.xlsx,.xls" class="hidden">
                    <button id="browseFileBtn" class="px-6 py-2 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors">
                        Browse Files
                    </button>
                </div>
                <div id="selectedFileInfo" class="mt-3 text-sm text-gray-600 hidden">
                    <i class="fas fa-file text-gray-400 mr-2"></i>
                    <span id="fileName"></span>
                    <button id="removeFileBtn" class="ml-2 text-red-500 hover:text-red-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Template Download -->
            <div class="mb-6">
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                        <div>
                            <h5 class="text-blue-800 font-medium mb-1">Need a template?</h5>
                            <p class="text-blue-600 text-sm mb-3">Download our CSV template to ensure proper formatting.</p>
                            <button id="downloadTemplateBtn" class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm">
                                <i class="fas fa-download mr-2"></i>Download CSV Template
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Required Columns -->
            <div>
                <h5 class="text-gray-700 font-medium mb-2">Required Columns:</h5>
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div class="bg-gray-50 p-2 rounded">
                        <span class="font-medium">project_name</span>
                        <span class="text-red-500 ml-1">*</span>
                    </div>
                    <div class="bg-gray-50 p-2 rounded">
                        <span class="font-medium">project_code</span>
                        <span class="text-red-500 ml-1">*</span>
                    </div>
                    <div class="bg-gray-50 p-2 rounded">
                        <span class="font-medium">description</span>
                        <span class="text-gray-500 ml-1">(optional)</span>
                    </div>
                    <div class="bg-gray-50 p-2 rounded">
                        <span class="font-medium">is_active</span>
                        <span class="text-gray-500 ml-1">(true/false)</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6 border-t border-gray-200 flex gap-3">
            <button class="close-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                Cancel
            </button>
            <button id="confirmImport" disabled
                class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                Import Projects
            </button>
        </div>
    </div>
</div>
`;

        // Initialize
        init();

        function init() {
            loadProjectsData();
            setupEventListeners();

            // Auto-select first project if available from initial data
            const initialProjects = <?= json_encode($projects ?? []) ?>;
            if (initialProjects && initialProjects.length > 0) {
                setTimeout(() => {
                    selectProject(initialProjects[0].project_id);
                }, 500);
            }
        }

        function setupEventListeners() {
            // Search input
            if (elements.projectSearch) {
                elements.projectSearch.addEventListener('input', debounce(() => {
                    currentPage = 1;
                    loadProjectsData();
                }, 300));
            }

            // Filter changes
            if (elements.statusFilter) {
                elements.statusFilter.addEventListener('change', () => {
                    currentPage = 1;
                    loadProjectsData();
                });
            }

            if (elements.sortFilter) {
                elements.sortFilter.addEventListener('change', () => {
                    currentPage = 1;
                    loadProjectsData();
                });
            }

            // Reset filters
            if (elements.resetFilters) {
                elements.resetFilters.addEventListener('click', resetAllFilters);
            }

            // Export projects
            if (elements.exportProjects) {
                elements.exportProjects.addEventListener('click', exportProjectsData);
            }

            // Pagination
            if (elements.prevPage) {
                elements.prevPage.addEventListener('click', () => changePage(currentPage - 1));
            }

            if (elements.nextPage) {
                elements.nextPage.addEventListener('click', () => changePage(currentPage + 1));
            }

            // Sort headers
            document.querySelectorAll('.sortable').forEach(header => {
                header.addEventListener('click', () => {
                    const field = header.dataset.sort;
                    const currentDirection = header.classList.contains('sorted-desc') ? 'asc' : 'desc';
                    loadProjectsData(field, currentDirection);
                });
            });

            // Action buttons
            if (elements.addProjectBtn) {
                elements.addProjectBtn.addEventListener('click', showAddProjectModal);
            }

            if (elements.bulkAssignBtn) {
                elements.bulkAssignBtn.addEventListener('click', showBulkAssignModal);
            }

            if (elements.importProjectsBtn) {
                elements.importProjectsBtn.addEventListener('click', showImportProjectsModal);
            }
        }

        async function loadProjectsData(sortBy = 'created_at', sortOrder = 'desc') {
            if (isLoading) return;

            isLoading = true;
            showLoading();

            try {
                const response = await fetch(`${baseUrl}/admin/manageProjects`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new URLSearchParams({
                        [csrfName]: csrfToken,
                        action: 'get_projects_table',
                        draw: 1,
                        start: (currentPage - 1) * itemsPerPage,
                        length: itemsPerPage,
                        'search[value]': elements.projectSearch?.value || '',
                        status: elements.statusFilter?.value || 'all',
                        sort_by: sortBy,
                        sort_order: sortOrder
                    })
                });

                const data = await response.json();

                if (data.data) {
                    renderProjects(data.data);
                    updatePagination(data.recordsTotal, data.recordsFiltered);
                    updateShowingCount(data.recordsFiltered);

                    // Select first project if none selected
                    if (!selectedProjectId && data.data.length > 0) {
                        selectProject(data.data[0].id);
                    }
                } else {
                    showToast('Failed to load projects', 'error');
                    // Fallback to initial data
                    fallbackToInitialData();
                }
            } catch (error) {
                console.error('Error loading projects:', error);
                showToast('Network error occurred', 'error');
                // Fallback to initial data
                fallbackToInitialData();
            } finally {
                isLoading = false;
                hideLoading();
            }
        }

        function fallbackToInitialData() {
            // Use initial data from server if available
            const initialProjects = <?= json_encode($projects ?? []) ?>;

            if (initialProjects && initialProjects.length > 0) {
                const formattedProjects = initialProjects.map(project => ({
                    id: project.project_id,
                    project_code: project.project_code,
                    name: project.project_name,
                    description: project.description,
                    status: project.is_active ? 'active' : 'inactive',
                    created_at: project.created_at ? new Date(project.created_at).toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric'
                    }) : 'Unknown',
                    total_tickets: project.total_tickets || 0,
                    open_tickets: project.open_tickets || 0,
                    assigned_users: project.assigned_users || 0,
                    is_active: project.is_active
                }));

                renderProjects(formattedProjects);
                updatePagination(formattedProjects.length, formattedProjects.length);
                updateShowingCount(formattedProjects.length);

                if (!selectedProjectId && formattedProjects.length > 0) {
                    selectProject(formattedProjects[0].id);
                }
            } else {
                showEmptyState();
            }
        }

        function showEmptyState() {
            if (elements.projectsList) {
                elements.projectsList.innerHTML = `
                    <div class="py-12 text-center">
                        <i class="fas fa-project-diagram text-gray-300 text-4xl mb-4"></i>
                        <p class="text-gray-500">No projects found</p>
                        <p class="text-gray-400 text-sm mt-2">Try adjusting your filters or add a new project</p>
                    </div>
                `;
            }
        }

        function renderProjects(projects) {
            if (!elements.projectsList) return;

            if (!projects || projects.length === 0) {
                showEmptyState();
                return;
            }

            let html = '';

            projects.forEach(project => {
                html += `
                    <div class="project-row ${selectedProjectId === project.id ? 'selected' : ''}" 
                        data-project-id="${project.id}">
                        <div class="col-span-2 text-text-dark/60 font-medium">#${project.id}</div>
                        <div class="col-span-3">
                            <div class="font-medium text-text-dark">${project.name}</div>
                            <div class="text-xs text-text-dark/50">${project.project_code}</div>
                        </div>
                        <div class="col-span-2">
                            <div class="font-medium text-text-dark">${project.project_code}</div>
                        </div>
                        <div class="col-span-2">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-text-dark">${project.total_tickets}</span>
                                <span class="text-xs text-text-dark/50">(${project.open_tickets} open)</span>
                            </div>
                        </div>
                        <div class="col-span-2">
                            <span class="${getStatusClass(project.status)} status-badge">
                                ${getStatusName(project.status)}
                            </span>
                        </div>
                        <div class="col-span-1 text-right">
                            <button class="project-action-btn text-text-dark/60 hover:text-secondary transition-colors">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </div>
                    </div>
                `;
            });

            elements.projectsList.innerHTML = html;

            // Add event listeners
            document.querySelectorAll('.project-row').forEach(row => {
                const projectId = parseInt(row.dataset.projectId);

                // Click event for selecting project
                row.addEventListener('click', (e) => {
                    if (!e.target.closest('.project-action-btn')) {
                        selectProject(projectId);
                    }
                });

                // Action menu event
                const actionBtn = row.querySelector('.project-action-btn');
                if (actionBtn) {
                    actionBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const project = projects.find(p => p.id === projectId);
                        if (project) {
                            showProjectActionsMenu(actionBtn, project);
                        }
                    });
                }
            });
        }

        async function selectProject(projectId) {
            selectedProjectId = projectId;

            // Update selected row styling
            document.querySelectorAll('.project-row').forEach(row => {
                row.classList.remove('selected');
                if (parseInt(row.dataset.projectId) === projectId) {
                    row.classList.add('selected');
                }
            });

            // Load project details
            await loadProjectDetails(projectId);

            // Update project actions
            updateProjectActions(projectId);
        }

        // Ganti fungsi loadProjectDetails() dengan:
        async function loadProjectDetails(projectId) {
            if (!projectId) return;

            showLoading('projectDetails');

            try {
                const formData = new FormData();
                formData.append(csrfName, csrfToken);
                formData.append('action', 'get_project_details');
                formData.append('project_id', projectId);

                const response = await fetch(`${baseUrl}/admin/ajaxManageProjects`, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    renderProjectDetails(data.project, data.assigned_users || []);
                } else {
                    showProjectDetailsError(data.message || 'Failed to load project details');
                }
            } catch (error) {
                console.error('Error loading project details:', error);
                showProjectDetailsError('Network error occurred');
            } finally {
                hideLoading('projectDetails');
            }
        }

        // Tambahkan fungsi untuk Toast warning:
        function showToast(message, type = 'info') {
            // Remove existing toasts
            document.querySelectorAll('.custom-toast').forEach(toast => toast.remove());

            const toast = document.createElement('div');
            toast.className = `custom-toast fixed top-24 right-6 p-4 rounded-lg shadow-lg z-[1000] max-w-sm animate-slideInUp ${type === 'error' ? 'bg-red-500 text-white' :
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'warning' ? 'bg-yellow-500 text-white' :
        'bg-blue-500 text-white'
    }`;
            toast.innerHTML = `
        <div class="flex items-center gap-2">
            <i class="fas ${type === 'error' ? 'fa-exclamation-circle' :
                type === 'success' ? 'fa-check-circle' :
                type === 'warning' ? 'fa-exclamation-triangle' :
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
            }, type === 'error' || type === 'warning' ? 5000 : 3000);
        }

        function renderProjectDetails(project, assignedUsers = []) {
            if (!elements.projectDetails) return;

            const initials = project.project_code ? project.project_code.substring(0, 2) : '??';
            const createdAt = project.created_at || 'Unknown';
            const updatedAt = project.updated_at || null;

            const detailsHtml = `
                <div class="animate-fadeIn">
                    <div class="project-avatar">${initials}</div>
                    <div class="text-center mb-6">
                        <h3 class="text-lg font-semibold text-text-dark">${project.project_name}</h3>
                        <p class="text-text-dark/60 text-sm">${project.project_code}</p>
                        <span class="inline-block mt-2 ${getStatusClass(project.is_active ? 'active' : 'inactive')} status-badge">
                            ${getStatusName(project.is_active ? 'active' : 'inactive')}
                        </span>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="project-info-item">
                            <span class="text-text-dark/70 text-sm">Description:</span>
                            <span class="text-text-dark font-medium text-right text-xs">${project.description || 'No description'}</span>
                        </div>
                        <div class="project-info-item">
                            <span class="text-text-dark/70 text-sm">Created:</span>
                            <span class="text-text-dark font-medium">${createdAt}</span>
                        </div>
                        ${updatedAt ? `
                        <div class="project-info-item">
                            <span class="text-text-dark/70 text-sm">Last Updated:</span>
                            <span class="text-text-dark font-medium">${updatedAt}</span>
                        </div>
                        ` : ''}
                        <div class="project-info-item">
                            <span class="text-text-dark/70 text-sm">Assigned Users:</span>
                            <span class="text-text-dark font-medium">${assignedUsers.length} users</span>
                        </div>
                        ${project.user ? `
                        <div class="project-info-item">
                            <span class="text-text-dark/70 text-sm">Project Manager:</span>
                            <span class="text-text-dark font-medium">${project.user.full_name || project.user.username}</span>
                        </div>
                        ` : ''}
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-item">
                            <span class="stat-value">${project.total_tickets || 0}</span>
                            <span class="stat-label">Total Tickets</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">${project.open_tickets || 0}</span>
                            <span class="stat-label">Open Tickets</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">${project.closed_tickets || 0}</span>
                            <span class="stat-label">Closed Tickets</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">${assignedUsers.length}</span>
                            <span class="stat-label">Assigned Users</span>
                        </div>
                    </div>
                    
                    ${assignedUsers.length > 0 ? `
                    <div class="mt-4 pt-4 border-t border-white/30">
                        <h4 class="text-text-dark/80 text-sm font-medium mb-2">Assigned Users</h4>
                        <div class="space-y-2">
                            ${assignedUsers.map(user => `
                                <div class="flex items-center gap-2 p-2 bg-white/30 rounded">
                                    <div class="w-8 h-8 rounded-full bg-secondary text-white flex items-center justify-center text-xs">
                                        ${getUserInitials(user.full_name || user.username)}
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-text-dark text-sm">${user.full_name || user.username}</div>
                                        <div class="text-text-dark/50 text-xs">${user.email || ''} ${user.role_name ? `• ${user.role_name}` : ''}</div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                    ` : ''}
                </div>
            `;

            elements.projectDetails.innerHTML = detailsHtml;
        }

        function showProjectDetailsError(message) {
            if (elements.projectDetails) {
                elements.projectDetails.innerHTML = `
                    <div class="flex flex-col items-center justify-center py-8 text-center">
                        <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-exclamation-circle text-red-400 text-xl"></i>
                        </div>
                        <p class="text-text-dark/70 text-sm">${message}</p>
                        <button onclick="loadProjectDetails(${selectedProjectId})" class="mt-4 text-secondary text-sm hover:underline">
                            Try Again
                        </button>
                    </div>
                `;
            }
        }

        function updateProjectActions(projectId) {
            if (!elements.projectActions) return;

            // Show loading state
            elements.projectActions.innerHTML = `
                <div class="space-y-2">
                    <button class="w-full py-3 bg-gray-100 text-gray-400 rounded-xl cursor-not-allowed" disabled>
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        Loading...
                    </button>
                </div>
            `;

            // Get project status from row
            const projectRow = document.querySelector(`[data-project-id="${projectId}"]`);
            const statusBadge = projectRow?.querySelector('.status-badge');
            const statusText = statusBadge?.textContent?.toLowerCase().trim() || 'active';
            const isActive = statusText === 'active';

            const actionsHtml = `
                <div class="space-y-2 animate-fadeIn">
                    <button class="edit-project-btn w-full py-3 bg-secondary text-white rounded-xl hover:bg-[#665C9E] transition-colors font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-edit"></i>
                        Edit Project
                    </button>
                    
                    <button class="manage-users-btn w-full py-3 bg-white text-text-dark border border-text-dark/20 rounded-xl hover:bg-gray-50 transition-colors font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-users"></i>
                        Manage Users
                    </button>
                    
                    <button class="toggle-status-btn w-full py-3 ${isActive ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-green-50 text-green-600 border border-green-200'} rounded-xl hover:${isActive ? 'bg-red-100' : 'bg-green-100'} transition-colors font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-power-off"></i>
                        ${isActive ? 'Deactivate Project' : 'Activate Project'}
                    </button>
                    
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <button class="delete-project-btn w-full py-3 bg-gray-50 text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-100 transition-colors font-medium flex items-center justify-center gap-2">
                            <i class="fas fa-trash-alt"></i>
                            Delete Project
                        </button>
                    </div>
                </div>
            `;

            elements.projectActions.innerHTML = actionsHtml;

            // Add event listeners
            setTimeout(() => {
                const editBtn = elements.projectActions.querySelector('.edit-project-btn');
                const manageBtn = elements.projectActions.querySelector('.manage-users-btn');
                const toggleBtn = elements.projectActions.querySelector('.toggle-status-btn');
                const deleteBtn = elements.projectActions.querySelector('.delete-project-btn');

                if (editBtn) {
                    editBtn.addEventListener('click', () => editProject(projectId));
                }

                if (manageBtn) {
                    manageBtn.addEventListener('click', () => manageProjectUsers(projectId));
                }

                if (toggleBtn) {
                    toggleBtn.addEventListener('click', () => toggleProjectStatus(projectId, !isActive));
                }

                if (deleteBtn) {
                    deleteBtn.addEventListener('click', () => deleteProject(projectId));
                }
            }, 100);
        }

        // Function untuk menampilkan modal Bulk Assign
        async function showBulkAssignModal() {
            // Remove existing modal
            const existingModal = document.querySelector('.bulk-assign-modal');
            if (existingModal) existingModal.remove();

            // Create and append modal
            const modalContainer = document.createElement('div');
            modalContainer.className = 'bulk-assign-modal';
            modalContainer.innerHTML = bulkAssignModalHTML;
            document.body.appendChild(modalContainer);

            // Setup event listeners
            setupBulkAssignModalEvents();

            // Load initial data
            await loadBulkProjects();
            await loadBulkUsers();

            // Update fetch URL untuk controller
            const response = await fetch(`${baseUrl}/admin/manageProjects`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    [csrfName]: csrfToken,
                    action: 'get_projects_for_bulk_assign',
                    search: searchTerm
                })
            });
        }

        function setupBulkAssignModalEvents() {
            const modal = document.querySelector('.bulk-assign-modal');

            // Close modal
            modal.querySelectorAll('.close-modal').forEach(btn => {
                btn.addEventListener('click', () => {
                    modal.style.opacity = '0';
                    modal.style.transform = 'translateY(10px)';
                    setTimeout(() => modal.remove(), 300);
                });
            });

            // Search projects
            const searchProjectsInput = modal.querySelector('#bulkSearchProjects');
            if (searchProjectsInput) {
                searchProjectsInput.addEventListener('input', debounce(async () => {
                    await loadBulkProjects(searchProjectsInput.value);
                }, 300));
            }

            // Search users
            const searchUsersInput = modal.querySelector('#bulkSearchUsers');
            if (searchUsersInput) {
                searchUsersInput.addEventListener('input', debounce(async () => {
                    await loadBulkUsers(searchUsersInput.value);
                }, 300));
            }

            // Confirm button
            const confirmBtn = modal.querySelector('#confirmBulkAssign');
            if (confirmBtn) {
                confirmBtn.addEventListener('click', processBulkAssign);
            }
        }

        // Function untuk menampilkan modal Import Projects
        function showImportProjectsModal() {
            // Remove existing modal
            const existingModal = document.querySelector('.import-projects-modal');
            if (existingModal) existingModal.remove();

            // Create and append modal
            const modalContainer = document.createElement('div');
            modalContainer.className = 'import-projects-modal';
            modalContainer.innerHTML = importProjectsModalHTML;
            document.body.appendChild(modalContainer);

            // Setup event listeners
            setupImportModalEvents();
        }

        function setupImportModalEvents() {
            const modal = document.querySelector('.import-projects-modal');

            // Close modal
            modal.querySelectorAll('.close-modal').forEach(btn => {
                btn.addEventListener('click', () => {
                    modal.style.opacity = '0';
                    modal.style.transform = 'translateY(10px)';
                    setTimeout(() => modal.remove(), 300);
                });
            });

            // File upload
            const fileInput = modal.querySelector('#importFile');
            const browseBtn = modal.querySelector('#browseFileBtn');
            const removeFileBtn = modal.querySelector('#removeFileBtn');
            const confirmBtn = modal.querySelector('#confirmImport');

            if (browseBtn) {
                browseBtn.addEventListener('click', () => fileInput.click());
            }

            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    if (this.files.length > 0) {
                        const file = this.files[0];
                        const fileName = modal.querySelector('#fileName');
                        const fileInfo = modal.querySelector('#selectedFileInfo');

                        if (fileName) fileName.textContent = file.name;
                        if (fileInfo) fileInfo.classList.remove('hidden');
                        if (confirmBtn) confirmBtn.disabled = false;
                    }
                });
            }

            if (removeFileBtn) {
                removeFileBtn.addEventListener('click', () => {
                    fileInput.value = '';
                    modal.querySelector('#selectedFileInfo').classList.add('hidden');
                    if (confirmBtn) confirmBtn.disabled = true;
                });
            }

            // Download template
            const downloadBtn = modal.querySelector('#downloadTemplateBtn');
            if (downloadBtn) {
                downloadBtn.addEventListener('click', downloadCSVTemplate);
            }

            // Confirm import
            if (confirmBtn) {
                confirmBtn.addEventListener('click', processImport);
            }
        }

        // Ganti fungsi processBulkAssign() dengan:
        async function processBulkAssign() {
            const modal = document.querySelector('.bulk-assign-modal');
            if (!modal) return;

            const selectedProjects = Array.from(modal.querySelectorAll('.project-checkbox:checked'))
                .map(cb => parseInt(cb.value));

            const selectedUsers = Array.from(modal.querySelectorAll('.user-checkbox:checked'))
                .map(cb => parseInt(cb.value));

            if (selectedProjects.length === 0) {
                showToast('Please select at least one project', 'error');
                return;
            }

            if (selectedUsers.length === 0) {
                showToast('Please select at least one user', 'error');
                return;
            }

            // Show loading
            const confirmBtn = modal.querySelector('#confirmBulkAssign');
            const originalText = confirmBtn.innerHTML;
            confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
            confirmBtn.disabled = true;

            try {
                const formData = new FormData();
                formData.append(csrfName, csrfToken);
                formData.append('action', 'bulk_assign_projects');
                formData.append('project_ids', JSON.stringify(selectedProjects));
                formData.append('user_ids', JSON.stringify(selectedUsers));

                const response = await fetch(`${baseUrl}/admin/ajaxManageProjects`, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    showToast(data.message, 'success');
                    modal.querySelector('.close-modal').click();

                    // Reload projects to update counts
                    loadProjectsData();

                    // Show warnings if any
                    if (data.warning) {
                        setTimeout(() => {
                            showToast(data.warning, 'warning');
                        }, 1000);
                    }
                } else {
                    showToast(data.message || 'Failed to process bulk assignment', 'error');
                }
            } catch (error) {
                console.error('Bulk assign error:', error);
                showToast('Failed to process bulk assignment', 'error');
            } finally {
                confirmBtn.innerHTML = originalText;
                confirmBtn.disabled = false;
            }
        }

        // Ganti fungsi processImport() dengan:
        async function processImport() {
            const modal = document.querySelector('.import-projects-modal');
            if (!modal) return;

            const fileInput = modal.querySelector('#importFile');
            if (!fileInput.files.length) {
                showToast('Please select a file to import', 'error');
                return;
            }

            const file = fileInput.files[0];

            // Validate file size (10MB)
            if (file.size > 10 * 1024 * 1024) {
                showToast('File size must be less than 10MB', 'error');
                return;
            }

            // Show loading
            const confirmBtn = modal.querySelector('#confirmImport');
            const originalText = confirmBtn.innerHTML;
            confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Importing...';
            confirmBtn.disabled = true;

            try {
                const formData = new FormData();
                formData.append(csrfName, csrfToken);
                formData.append('action', 'import_projects');
                formData.append('projects_file', file);

                const response = await fetch(`${baseUrl}/admin/ajaxManageProjects`, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    showToast(data.message, 'success');
                    modal.querySelector('.close-modal').click();

                    // Clear file input
                    fileInput.value = '';
                    modal.querySelector('#selectedFileInfo').classList.add('hidden');

                    // Reload projects
                    loadProjectsData();

                    // Show import summary if there were errors
                    if (data.error_count > 0) {
                        setTimeout(() => {
                            showImportSummary(data);
                        }, 1000);
                    }
                } else {
                    showToast(data.message || 'Failed to import projects', 'error');
                }
            } catch (error) {
                console.error('Import error:', error);
                showToast('Failed to import projects', 'error');
            } finally {
                confirmBtn.innerHTML = originalText;
                confirmBtn.disabled = false;
            }
        }

        // Function untuk menampilkan summary import
        function showImportSummary(data) {
            const summaryHTML = `
                <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4">
                    <div class="bg-white rounded-2xl w-full max-w-lg animate-slideInUp">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-semibold text-gray-800">Import Summary</h3>
                                <button class="close-summary text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="mb-6">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-green-50 p-4 rounded-xl text-center">
                                        <div class="text-green-600 text-2xl font-bold">${data.imported_count}</div>
                                        <div class="text-green-700 text-sm">Projects Imported</div>
                                    </div>
                                    <div class="bg-red-50 p-4 rounded-xl text-center">
                                        <div class="text-red-600 text-2xl font-bold">${data.error_count}</div>
                                        <div class="text-red-700 text-sm">Errors</div>
                                    </div>
                                </div>
                            </div>

                            ${data.errors && data.errors.length > 0 ? `
                            <div>
                                <h4 class="text-gray-700 font-medium mb-3">Error Details:</h4>
                                <div class="max-h-64 overflow-y-auto border border-gray-200 rounded-lg">
                                    <table class="w-full text-sm">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="p-3 text-left">Row</th>
                                                <th class="p-3 text-left">Error</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${data.errors.map(error => `
                                                <tr class="border-t border-gray-100">
                                                    <td class="p-3">${error.row}</td>
                                                    <td class="p-3 text-red-600">${error.error}</td>
                                                </tr>
                                            `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            ` : ''}
                        </div>

                        <div class="p-6 border-t border-gray-200">
                            <button class="close-summary w-full py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            `;

            const summaryContainer = document.createElement('div');
            summaryContainer.innerHTML = summaryHTML;
            document.body.appendChild(summaryContainer);

            summaryContainer.querySelectorAll('.close-summary').forEach(btn => {
                btn.addEventListener('click', () => {
                    summaryContainer.style.opacity = '0';
                    summaryContainer.style.transform = 'translateY(10px)';
                    setTimeout(() => summaryContainer.remove(), 300);
                });
            });
        }

        // Ganti fungsi loadBulkProjects() dengan:
        async function loadBulkProjects(search = '') {
            const modal = document.querySelector('.bulk-assign-modal');
            if (!modal) return;

            const projectsList = modal.querySelector('#bulkProjectsList');
            if (!projectsList) return;

            // Show loading
            projectsList.innerHTML = `
        <div class="p-4 text-center">
            <div class="inline-block animate-spin rounded-full h-6 w-6 border-t-2 border-b-2 border-secondary"></div>
        </div>
    `;

            try {
                const formData = new FormData();
                formData.append(csrfName, csrfToken);
                formData.append('action', 'get_projects_for_bulk');
                formData.append('search', search);

                const response = await fetch(`${baseUrl}/admin/ajaxManageProjects`, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success && data.projects) {
                    renderBulkProjects(data.projects, projectsList);
                    updateBulkSelectionCounts();
                } else {
                    projectsList.innerHTML = '<div class="p-4 text-center text-gray-500">No projects found</div>';
                }
            } catch (error) {
                console.error('Error loading bulk projects:', error);
                projectsList.innerHTML = '<div class="p-4 text-center text-gray-500 text-red-600">Failed to load projects</div>';
            }
        }

        // Ganti fungsi loadBulkUsers() dengan:
        async function loadBulkUsers(search = '') {
            const modal = document.querySelector('.bulk-assign-modal');
            if (!modal) return;

            const usersList = modal.querySelector('#bulkUsersList');
            if (!usersList) return;

            // Show loading
            usersList.innerHTML = `
        <div class="p-4 text-center">
            <div class="inline-block animate-spin rounded-full h-6 w-6 border-t-2 border-b-2 border-secondary"></div>
        </div>
    `;

            try {
                const formData = new FormData();
                formData.append(csrfName, csrfToken);
                formData.append('action', 'get_all_users');
                formData.append('search', search);

                const response = await fetch(`${baseUrl}/admin/ajaxManageProjects`, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success && data.users) {
                    renderBulkUsers(data.users, usersList);
                    updateBulkSelectionCounts();
                } else {
                    usersList.innerHTML = '<div class="p-4 text-center text-gray-500">No users found</div>';
                }
            } catch (error) {
                console.error('Error loading bulk users:', error);
                usersList.innerHTML = '<div class="p-4 text-center text-gray-500 text-red-600">Failed to load users</div>';
            }
        }

        // Function untuk load users untuk bulk assign
        async function loadBulkUsers(search = '') {
            const modal = document.querySelector('.bulk-assign-modal');
            if (!modal) return;

            const usersList = modal.querySelector('#bulkUsersList');
            if (!usersList) return;

            try {
                const formData = new FormData();
                formData.append(csrfName, csrfToken);
                formData.append('search', search);

                const response = await fetch(`${baseUrl}/admin/getAllUsers`, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success && data.users) {
                    renderBulkUsers(data.users, usersList);
                    updateBulkSelectionCounts();
                }
            } catch (error) {
                console.error('Error loading bulk users:', error);
                usersList.innerHTML = '<div class="p-4 text-center text-gray-500">Failed to load users</div>';
            }
        }

        // Function untuk render projects di bulk assign modal
        function renderBulkProjects(projects, container) {
            if (!projects || projects.length === 0) {
                container.innerHTML = '<div class="p-4 text-center text-gray-500">No projects found</div>';
                return;
            }

            const html = projects.map(project => `
                <div class="user-item">
                    <label class="flex items-center w-full cursor-pointer">
                        <input type="checkbox" class="project-checkbox mr-3" value="${project.project_id}">
                        <div class="flex-1">
                            <div class="font-medium text-gray-700">${project.project_name}</div>
                            <div class="text-gray-500 text-xs">${project.project_code}</div>
                        </div>
                        <span class="${project.is_active ? 'status-active' : 'status-inactive'} status-badge text-xs">
                            ${project.is_active ? 'Active' : 'Inactive'}
                        </span>
                    </label>
                </div>
            `).join('');

            container.innerHTML = html;

            // Add event listeners to checkboxes
            container.querySelectorAll('.project-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', updateBulkSelectionCounts);
            });
        }

        // Function untuk render users di bulk assign modal
        function renderBulkUsers(users, container) {
            if (!users || users.length === 0) {
                container.innerHTML = '<div class="p-4 text-center text-gray-500">No users found</div>';
                return;
            }

            const html = users.map(user => `
                <div class="user-item">
                    <label class="flex items-center w-full cursor-pointer">
                        <input type="checkbox" class="user-checkbox mr-3" value="${user.user_id}">
                        <div class="user-avatar-small">
                            ${getUserInitials(user.full_name || user.username)}
                        </div>
                        <div class="flex-1">
                            <div class="font-medium text-gray-700">${user.full_name || user.username}</div>
                            <div class="text-gray-500 text-xs">${user.email} • ${user.role_name}</div>
                        </div>
                    </label>
                </div>
            `).join('');

            container.innerHTML = html;

            // Add event listeners to checkboxes
            container.querySelectorAll('.user-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', updateBulkSelectionCounts);
            });
        }

        // Function untuk update selection counts
        function updateBulkSelectionCounts() {
            const modal = document.querySelector('.bulk-assign-modal');
            if (!modal) return;

            const selectedProjects = Array.from(modal.querySelectorAll('.project-checkbox:checked')).length;
            const selectedUsers = Array.from(modal.querySelectorAll('.user-checkbox:checked')).length;
            const totalAssignments = selectedProjects * selectedUsers;

            // Update counts
            modal.querySelector('#bulkSelectedProjectsCount').textContent = selectedProjects;
            modal.querySelector('#bulkSelectedUsersCount').textContent = selectedUsers;
            modal.querySelector('#bulkTotalAssignments').textContent = totalAssignments;

            // Update confirm button state
            const confirmBtn = modal.querySelector('#confirmBulkAssign');
            if (confirmBtn) {
                confirmBtn.disabled = selectedProjects === 0 || selectedUsers === 0;
            }
        }

        // Function untuk download CSV template
        function downloadCSVTemplate() {
            const csvContent = `project_name,project_code,description,is_active
Website Redesign,PROJ001,Complete website overhaul,true
Mobile App,PROJ002,New mobile application development,true
Database Migration,PROJ003,Migrate to new database system,false
API Integration,PROJ004,Third-party API integration,true`;

            const blob = new Blob([csvContent], {
                type: 'text/csv'
            });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'project_import_template.csv';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }

        // Function untuk menampilkan modal Add Project
        function showAddProjectModal() {
            // Get template
            const template = document.getElementById('addProjectModalTemplate');
            if (!template) return;

            // Remove existing modal
            const existingModal = document.querySelector('.add-project-modal');
            if (existingModal) existingModal.remove();

            // Clone and append modal
            const modal = template.content.cloneNode(true);
            const modalContainer = document.createElement('div');
            modalContainer.className = 'add-project-modal';
            modalContainer.appendChild(modal);
            document.body.appendChild(modalContainer);

            // Setup event listeners
            setupAddProjectModalEvents();
        }

        function setupAddProjectModalEvents() {
            const modal = document.querySelector('.add-project-modal');
            if (!modal) return;

            // Close modal
            modal.querySelectorAll('.close-modal').forEach(btn => {
                btn.addEventListener('click', () => {
                    modal.style.opacity = '0';
                    modal.style.transform = 'translateY(10px)';
                    setTimeout(() => modal.remove(), 300);
                });
            });

            // Confirm add project
            const confirmBtn = modal.querySelector('#confirmAddProject');
            if (confirmBtn) {
                confirmBtn.addEventListener('click', processAddProject);
            }
        }

        // Ganti fungsi processAddProject() dengan:
        async function processAddProject() {
            const modal = document.querySelector('.add-project-modal');
            if (!modal) return;

            const projectName = modal.querySelector('#projectName').value.trim();
            const projectCode = modal.querySelector('#projectCode').value.trim().toUpperCase();
            const description = modal.querySelector('#projectDescription').value.trim();
            const status = modal.querySelector('#projectStatus').value;

            // Validation
            if (!projectName) {
                showToast('Project name is required', 'error');
                modal.querySelector('#projectName').focus();
                return;
            }

            if (!projectCode) {
                showToast('Project code is required', 'error');
                modal.querySelector('#projectCode').focus();
                return;
            }

            // Show loading
            const confirmBtn = modal.querySelector('#confirmAddProject');
            const originalText = confirmBtn.innerHTML;
            confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating...';
            confirmBtn.disabled = true;

            try {
                const formData = new FormData();
                formData.append(csrfName, csrfToken);
                formData.append('action', 'create_project');
                formData.append('project_name', projectName);
                formData.append('project_code', projectCode);
                formData.append('description', description);
                formData.append('is_active', status === 'active');

                const response = await fetch(`${baseUrl}/admin/ajaxManageProjects`, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    showToast(data.message, 'success');
                    modal.querySelector('.close-modal').click();

                    // Clear form
                    modal.querySelector('#projectName').value = '';
                    modal.querySelector('#projectCode').value = '';
                    modal.querySelector('#projectDescription').value = '';
                    modal.querySelector('#projectStatus').value = 'active';

                    // Reload projects
                    loadProjectsData();

                    // Select the new project
                    if (data.project_id) {
                        setTimeout(() => {
                            selectProject(data.project_id);
                        }, 1000);
                    }
                } else {
                    if (data.errors) {
                        // Show validation errors
                        const errorMessages = Object.values(data.errors).join(', ');
                        showToast(errorMessages, 'error');
                    } else {
                        showToast(data.message || 'Failed to create project', 'error');
                    }
                }
            } catch (error) {
                console.error('Add project error:', error);
                showToast('Failed to create project', 'error');
            } finally {
                confirmBtn.innerHTML = originalText;
                confirmBtn.disabled = false;
            }
        }
        // Utility functions
        function getStatusName(status) {
            const statuses = {
                'active': 'Active',
                'inactive': 'Inactive',
                'completed': 'Completed',
                'on-hold': 'On Hold',
                'pending': 'Pending'
            };
            return statuses[status?.toLowerCase()] || status || 'Active';
        }

        function getStatusClass(status) {
            const classes = {
                'active': 'status-active',
                'inactive': 'status-inactive',
                'completed': 'status-completed',
                'on-hold': 'status-onhold',
                'pending': 'status-onhold'
            };
            return classes[status?.toLowerCase()] || 'status-inactive';
        }

        function getUserInitials(name) {
            if (!name) return '??';
            return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
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

        function showLoading(elementId = null) {
            if (elementId) {
                const element = document.getElementById(elementId);
                if (element) {
                    element.innerHTML = `
                        <div class="flex items-center justify-center py-8">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-secondary"></div>
                        </div>
                    `;
                }
            } else {
                // Show global loading overlay
                let overlay = document.getElementById('loadingOverlay');
                if (!overlay) {
                    overlay = document.createElement('div');
                    overlay.id = 'loadingOverlay';
                    overlay.className = 'fixed inset-0 bg-white/80 flex items-center justify-center z-50';
                    overlay.innerHTML = `
                        <div class="text-center">
                            <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-secondary"></div>
                            <p class="mt-4 text-text-dark">Loading...</p>
                        </div>
                    `;
                    document.body.appendChild(overlay);
                }
                overlay.classList.remove('hidden');
            }
        }

        function hideLoading(elementId = null) {
            if (elementId) {
                // Element-specific loading will be replaced by content
            } else {
                const overlay = document.getElementById('loadingOverlay');
                if (overlay) {
                    overlay.classList.add('hidden');
                }
            }
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

    // Ganti fungsi exportProjectsData() dengan:
    async function exportProjectsData() {
        try {
            showLoading();

            const filters = {
                search: elements.projectSearch?.value || '',
                status: elements.statusFilter?.value || '',
                sort_by: elements.sortFilter?.value || 'name_asc'
            };

            const formData = new FormData();
            formData.append(csrfName, csrfToken);
            formData.append('action', 'export_projects_csv');
            formData.append('search', filters.search);
            formData.append('status', filters.status);
            formData.append('sort_by', filters.sort_by);

            const response = await fetch(`${baseUrl}/admin/ajaxManageProjects`, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                // Convert data to CSV and download
                downloadCSV(data.data, data.filename || 'projects.csv');
                showToast(`Exported ${data.count} projects successfully`, 'success');
            } else {
                showToast(data.message || 'Failed to export projects', 'error');
            }
        } catch (error) {
            console.error('Export error:', error);
            showToast('Failed to export projects', 'error');
        } finally {
            hideLoading();
        }
    }

    // Function untuk download CSV
    function downloadCSV(data, filename) {
        const csvContent = data.map(row =>
            row.map(cell => `"${String(cell).replace(/"/g, '""')}"`).join(',')
        ).join('\n');

        const blob = new Blob([csvContent], {
            type: 'text/csv;charset=utf-8;'
        });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', filename);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
    }

    // Function untuk reset filter
    function resetAllFilters() {
        if (elements.projectSearch) elements.projectSearch.value = '';
        if (elements.statusFilter) elements.statusFilter.value = '';
        if (elements.sortFilter) elements.sortFilter.value = 'name_asc';

        currentPage = 1;
        loadProjectsData();
    }

    async function loadUsersData() {
    const response = await fetch(`${baseUrl}/admin/ajaxManageProjects`, {
        method: 'POST',
        body: new FormData('get_users_table') // Tambahkan parameter action: 'get_users_table'
    });
    const data = await response.json();
    renderUsersTable(data.users);
}
</script>
<?= $this->endSection() ?>