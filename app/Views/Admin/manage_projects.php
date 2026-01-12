<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>Manage Projects - NEXUS Admin<?= $this->endSection() ?>

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
                        <input type="text" 
                               placeholder="Search projects..." 
                               id="projectSearch"
                               class="w-full h-12 pl-12 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
                    </div>
                    
                    <!-- Filter Options -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Status Filter -->
                        <div>
                            <label class="block text-text-dark/70 text-sm mb-2">Status</label>
                            <select id="statusFilter" class="w-full h-12 pl-4 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary">
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
                            <select id="sortFilter" class="w-full h-12 pl-4 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary">
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
                        <button id="resetFilters" class="flex-1 h-12 bg-secondary text-white rounded-xl hover:bg-[#665C9E] transition-colors font-medium">
                            Reset Filters
                        </button>
                        <button id="exportProjects" class="flex-1 h-12 bg-white text-secondary border border-secondary rounded-xl hover:bg-secondary/5 transition-colors font-medium">
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
                    <div class="grid grid-cols-12 gap-4 py-4 px-6 bg-[#E3DAEE] rounded-lg text-sm font-semibold text-text-dark/80">
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
        
        <!-- Right Column: Project Details & Actions -->
        <div class="space-y-6">
            <!-- Add Project Card -->
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <div class="text-text-dark/85 text-base font-medium">Quick Actions</div>
                </div>
                
                <div class="p-4 space-y-3">
                    <button id="addProjectBtn" class="w-full py-3 bg-secondary text-white rounded-xl hover:bg-[#665C9E] transition-colors font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-plus text-lg"></i>
                        Add New Project
                    </button>
                    
                    <button id="bulkAssignBtn" class="w-full py-3 bg-white text-text-dark border border-text-dark/20 rounded-xl hover:bg-gray-50 transition-colors font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-user-plus"></i>
                        Bulk Assign Users
                    </button>
                    
                    <button id="importProjectsBtn" class="w-full py-3 bg-white text-text-dark border border-text-dark/20 rounded-xl hover:bg-gray-50 transition-colors font-medium flex items-center justify-center gap-2">
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
                        <label class="block text-gray-600 text-sm mb-2">Project Name <span class="text-red-500">*</span></label>
                        <input type="text" id="projectName" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                    </div>
                    
                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Project Code <span class="text-red-500">*</span></label>
                        <input type="text" id="projectCode" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary" placeholder="e.g., PROJ001">
                    </div>
                    
                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Description</label>
                        <textarea id="projectDescription" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary" rows="3"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Status</label>
                        <select id="projectStatus" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="completed">Completed</option>
                            <option value="on-hold">On Hold</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="p-6 border-t border-gray-200 flex gap-3">
                <button class="close-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button id="confirmAddProject" class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors">
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
                        <label class="block text-gray-600 text-sm mb-2">Project Name <span class="text-red-500">*</span></label>
                        <input type="text" id="editProjectName" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                    </div>
                    
                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Project Code <span class="text-red-500">*</span></label>
                        <input type="text" id="editProjectCode" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                    </div>
                    
                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Description</label>
                        <textarea id="editProjectDescription" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary" rows="3"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Status</label>
                        <select id="editProjectStatus" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="completed">Completed</option>
                            <option value="on-hold">On Hold</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Created Date</label>
                        <input type="text" id="editProjectCreated" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary" disabled>
                    </div>
                </div>
            </div>
            
            <div class="p-6 border-t border-gray-200 flex gap-3">
                <button class="close-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button id="confirmEditProject" class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors">
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
                        <input type="text" placeholder="Search users..." id="searchUsersInput" class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
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
                <button class="close-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button id="confirmManageUsers" class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors">
                    Save Assignments
                </button>
            </div>
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
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    
    /* Responsive adjustments */
    @media (max-width: 1024px) {
        .project-row {
            grid-template-columns: repeat(8, minmax(0, 1fr));
            gap: 0.75rem;
            padding: 0.75rem 1rem;
        }
        
        .project-row > div:nth-child(1) { grid-column: span 2; }
        .project-row > div:nth-child(2) { grid-column: span 3; }
        .project-row > div:nth-child(3) { grid-column: span 2; }
        .project-row > div:nth-child(4) { grid-column: span 2; }
        .project-row > div:nth-child(5) { grid-column: span 1; }
        .project-row > div:nth-child(6) { grid-column: span 1; }
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
        
        .project-row > div {
            grid-column: span 1 !important;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initial data
        let projectsData = [
            {
                id: 1,
                projectCode: "PROJ001",
                name: "NEXUS System",
                description: "Main NEXUS support system for ticket management and user support",
                status: "active",
                createdAt: "Jan 9, 2026",
                totalTickets: 45,
                openTickets: 8,
                assignedUsers: 12,
                assignedUsersList: [
                    { id: 1, name: "Admin User", email: "admin@nexus.com", avatar: "AU" },
                    { id: 3, name: "Jane Support", email: "support@nexus.com", avatar: "JS" },
                    { id: 4, name: "IT Support", email: "itsupport@nexus.com", avatar: "IS" }
                ]
            },
            {
                id: 2,
                projectCode: "PROJ002",
                name: "Mobile App",
                description: "Mobile application support and development",
                status: "active",
                createdAt: "Jan 8, 2026",
                totalTickets: 32,
                openTickets: 14,
                assignedUsers: 8,
                assignedUsersList: [
                    { id: 3, name: "Jane Support", email: "support@nexus.com", avatar: "JS" },
                    { id: 5, name: "UI/UX Designer", email: "uiux@nexus.com", avatar: "UD" }
                ]
            },
            {
                id: 3,
                projectCode: "PROJ003",
                name: "Web Portal",
                description: "Web portal development and maintenance",
                status: "active",
                createdAt: "Jan 7, 2026",
                totalTickets: 28,
                openTickets: 9,
                assignedUsers: 6,
                assignedUsersList: [
                    { id: 4, name: "IT Support", email: "itsupport@nexus.com", avatar: "IS" },
                    { id: 6, name: "Technical Support", email: "techsupport@nexus.com", avatar: "TS" }
                ]
            },
            {
                id: 4,
                projectCode: "PROJ004",
                name: "Database Migration",
                description: "Legacy database migration to new system",
                status: "completed",
                createdAt: "Dec 15, 2025",
                totalTickets: 18,
                openTickets: 0,
                assignedUsers: 5,
                assignedUsersList: [
                    { id: 4, name: "IT Support", email: "itsupport@nexus.com", avatar: "IS" },
                    { id: 7, name: "Feature Developer", email: "feature@nexus.com", avatar: "FD" }
                ]
            },
            {
                id: 5,
                projectCode: "PROJ005",
                name: "API Integration",
                description: "Third-party API integration project",
                status: "on-hold",
                createdAt: "Dec 10, 2025",
                totalTickets: 12,
                openTickets: 3,
                assignedUsers: 4,
                assignedUsersList: [
                    { id: 6, name: "Technical Support", email: "techsupport@nexus.com", avatar: "TS" }
                ]
            }
        ];
        
        // Available users for assignment
        let availableUsers = [
            { id: 1, name: "Admin User", email: "admin@nexus.com", avatar: "AU", role: "Admin" },
            { id: 2, name: "John Customer", email: "customer@nexus.com", avatar: "JC", role: "Customer" },
            { id: 3, name: "Jane Support", email: "support@nexus.com", avatar: "JS", role: "Support" },
            { id: 4, name: "IT Support", email: "itsupport@nexus.com", avatar: "IS", role: "Department" },
            { id: 5, name: "UI/UX Designer", email: "uiux@nexus.com", avatar: "UD", role: "Department" },
            { id: 6, name: "Technical Support", email: "techsupport@nexus.com", avatar: "TS", role: "Department" },
            { id: 7, name: "Feature Developer", email: "feature@nexus.com", avatar: "FD", role: "Department" }
        ];
        
        let currentPage = 1;
        let itemsPerPage = 5;
        let totalPages = Math.ceil(projectsData.length / itemsPerPage);
        let selectedProjectId = null;
        let currentSort = { field: 'id', direction: 'asc' };
        
        // DOM Elements
        const projectSearch = document.getElementById('projectSearch');
        const statusFilter = document.getElementById('statusFilter');
        const sortFilter = document.getElementById('sortFilter');
        const resetFilters = document.getElementById('resetFilters');
        const exportProjects = document.getElementById('exportProjects');
        const projectsList = document.getElementById('projectsList');
        const projectDetails = document.getElementById('projectDetails');
        const projectActions = document.getElementById('projectActions');
        const showingCount = document.getElementById('showingCount');
        const totalCount = document.getElementById('totalCount');
        const paginationInfo = document.getElementById('paginationInfo');
        const pageNumbers = document.getElementById('pageNumbers');
        const prevPage = document.getElementById('prevPage');
        const nextPage = document.getElementById('nextPage');
        const sortableHeaders = document.querySelectorAll('.sortable');
        const addProjectBtn = document.getElementById('addProjectBtn');
        const bulkAssignBtn = document.getElementById('bulkAssignBtn');
        const importProjectsBtn = document.getElementById('importProjectsBtn');
        
        // Initialize
        init();
        
        function init() {
            renderProjects();
            updatePagination();
            updateShowingCount();
            
            // Event listeners
            setupEventListeners();
        }
        
        function setupEventListeners() {
            // Search input
            if (projectSearch) {
                projectSearch.addEventListener('input', debounce(() => {
                    filterProjects();
                }, 300));
            }
            
            // Filter changes
            if (statusFilter) {
                statusFilter.addEventListener('change', filterProjects);
            }
            
            if (sortFilter) {
                sortFilter.addEventListener('change', filterProjects);
            }
            
            // Reset filters
            if (resetFilters) {
                resetFilters.addEventListener('click', resetAllFilters);
            }
            
            // Export projects
            if (exportProjects) {
                exportProjects.addEventListener('click', exportProjectsData);
            }
            
            // Pagination
            if (prevPage) {
                prevPage.addEventListener('click', () => changePage(currentPage - 1));
            }
            
            if (nextPage) {
                nextPage.addEventListener('click', () => changePage(currentPage + 1));
            }
            
            // Sort headers
            sortableHeaders.forEach(header => {
                header.addEventListener('click', () => {
                    const field = header.dataset.sort;
                    sortProjects(field);
                });
            });
            
            // Action buttons
            if (addProjectBtn) {
                addProjectBtn.addEventListener('click', showAddProjectModal);
            }
            
            if (bulkAssignBtn) {
                bulkAssignBtn.addEventListener('click', showBulkAssignModal);
            }
            
            if (importProjectsBtn) {
                importProjectsBtn.addEventListener('click', showImportProjectsModal);
            }
        }
        
        function renderProjects() {
            if (!projectsList) return;
            
            projectsList.innerHTML = '';
            
            const filteredProjects = getFilteredProjects();
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const pageProjects = filteredProjects.slice(startIndex, endIndex);
            
            if (pageProjects.length === 0) {
                projectsList.innerHTML = `
                    <div class="py-12 text-center">
                        <i class="fas fa-project-diagram text-gray-300 text-4xl mb-4"></i>
                        <p class="text-gray-500">No projects found</p>
                        <p class="text-gray-400 text-sm mt-2">Try adjusting your filters or add a new project</p>
                    </div>
                `;
                return;
            }
            
            pageProjects.forEach(project => {
                const projectRow = document.createElement('div');
                projectRow.className = `project-row ${selectedProjectId === project.id ? 'selected' : ''}`;
                projectRow.dataset.projectId = project.id;
                
                projectRow.innerHTML = `
                    <div class="col-span-2 text-text-dark/60 font-medium">#${project.id}</div>
                    <div class="col-span-3">
                        <div class="font-medium text-text-dark">${project.name}</div>
                        <div class="text-xs text-text-dark/50">${project.projectCode}</div>
                    </div>
                    <div class="col-span-2">
                        <div class="font-medium text-text-dark">${project.projectCode}</div>
                    </div>
                    <div class="col-span-2">
                        <div class="flex items-center gap-2">
                            <span class="font-medium text-text-dark">${project.totalTickets}</span>
                            <span class="text-xs text-text-dark/50">(${project.openTickets} open)</span>
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
                `;
                
                // Add click event for selecting project
                projectRow.addEventListener('click', (e) => {
                    if (!e.target.closest('.project-action-btn')) {
                        selectProject(project.id);
                    }
                });
                
                // Add action menu event
                const actionBtn = projectRow.querySelector('.project-action-btn');
                actionBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    showProjectActionsMenu(e.target.closest('button'), project);
                });
                
                projectsList.appendChild(projectRow);
            });
            
            // Update showing count
            updateShowingCount();
        }
        
        function getFilteredProjects() {
            let filtered = [...projectsData];
            
            // Apply search filter
            const searchTerm = projectSearch ? projectSearch.value.toLowerCase().trim() : '';
            if (searchTerm) {
                filtered = filtered.filter(project => 
                    project.name.toLowerCase().includes(searchTerm) ||
                    project.projectCode.toLowerCase().includes(searchTerm) ||
                    project.description.toLowerCase().includes(searchTerm)
                );
            }
            
            // Apply status filter
            const statusValue = statusFilter ? statusFilter.value : '';
            if (statusValue) {
                filtered = filtered.filter(project => project.status === statusValue);
            }
            
            // Apply sorting
            const sortValue = sortFilter ? sortFilter.value : 'name_asc';
            switch(sortValue) {
                case 'name_asc':
                    filtered.sort((a, b) => a.name.localeCompare(b.name));
                    break;
                case 'name_desc':
                    filtered.sort((a, b) => b.name.localeCompare(a.name));
                    break;
                case 'date_desc':
                    filtered.sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt));
                    break;
                case 'date_asc':
                    filtered.sort((a, b) => new Date(a.createdAt) - new Date(b.createdAt));
                    break;
                case 'tickets_desc':
                    filtered.sort((a, b) => b.totalTickets - a.totalTickets);
                    break;
                case 'tickets_asc':
                    filtered.sort((a, b) => a.totalTickets - b.totalTickets);
                    break;
            }
            
            return filtered;
        }
        
        function selectProject(projectId) {
            selectedProjectId = projectId;
            
            // Update selected row styling
            document.querySelectorAll('.project-row').forEach(row => {
                row.classList.remove('selected');
                if (parseInt(row.dataset.projectId) === projectId) {
                    row.classList.add('selected');
                }
            });
            
            // Load project details
            loadProjectDetails(projectId);
            
            // Update project actions
            updateProjectActions(projectId);
        }
        
        function loadProjectDetails(projectId) {
            const project = projectsData.find(p => p.id === projectId);
            if (!project) return;
            
            const detailsHtml = `
                <div class="animate-fadeIn">
                    <div class="project-avatar">${project.projectCode.substring(0, 2)}</div>
                    <div class="text-center mb-6">
                        <h3 class="text-lg font-semibold text-text-dark">${project.name}</h3>
                        <p class="text-text-dark/60 text-sm">${project.projectCode}</p>
                        <span class="inline-block mt-2 ${getStatusClass(project.status)} status-badge">
                            ${getStatusName(project.status)}
                        </span>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="project-info-item">
                            <span class="text-text-dark/70 text-sm">Description:</span>
                            <span class="text-text-dark font-medium text-right text-xs">${project.description}</span>
                        </div>
                        <div class="project-info-item">
                            <span class="text-text-dark/70 text-sm">Created:</span>
                            <span class="text-text-dark font-medium">${project.createdAt}</span>
                        </div>
                        <div class="project-info-item">
                            <span class="text-text-dark/70 text-sm">Assigned Users:</span>
                            <span class="text-text-dark font-medium">${project.assignedUsers} users</span>
                        </div>
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-item">
                            <span class="stat-value">${project.totalTickets}</span>
                            <span class="stat-label">Total Tickets</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">${project.openTickets}</span>
                            <span class="stat-label">Open Tickets</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">${project.totalTickets - project.openTickets}</span>
                            <span class="stat-label">Resolved</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">${project.assignedUsers}</span>
                            <span class="stat-label">Assigned Users</span>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-white/30">
                        <h4 class="text-text-dark/80 text-sm font-medium mb-2">Assigned Users</h4>
                        <div class="space-y-2">
                            ${project.assignedUsersList.map(user => `
                                <div class="flex items-center gap-2 p-2 bg-white/30 rounded">
                                    <div class="w-8 h-8 rounded-full bg-secondary text-white flex items-center justify-center text-xs">
                                        ${user.avatar}
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-text-dark text-sm">${user.name}</div>
                                        <div class="text-text-dark/50 text-xs">${user.email}</div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            `;
            
            projectDetails.innerHTML = detailsHtml;
        }
        
        function updateProjectActions(projectId) {
            const project = projectsData.find(p => p.id === projectId);
            if (!project) return;
            
            const isActive = project.status === 'active';
            
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
            
            projectActions.innerHTML = actionsHtml;
            
            // Add event listeners to action buttons
            setTimeout(() => {
                const editBtn = projectActions.querySelector('.edit-project-btn');
                const manageBtn = projectActions.querySelector('.manage-users-btn');
                const toggleBtn = projectActions.querySelector('.toggle-status-btn');
                const deleteBtn = projectActions.querySelector('.delete-project-btn');
                
                if (editBtn) {
                    editBtn.addEventListener('click', () => editProject(projectId));
                }
                
                if (manageBtn) {
                    manageBtn.addEventListener('click', () => manageProjectUsers(projectId));
                }
                
                if (toggleBtn) {
                    toggleBtn.addEventListener('click', () => toggleProjectStatus(projectId));
                }
                
                if (deleteBtn) {
                    deleteBtn.addEventListener('click', () => deleteProject(projectId));
                }
            }, 100);
        }
        
        function showProjectActionsMenu(button, project) {
            // Remove existing menus
            document.querySelectorAll('.project-actions-menu').forEach(menu => menu.remove());
            
            // Create menu from template
            const template = document.getElementById('projectActionsMenuTemplate');
            const menu = template.content.cloneNode(true);
            const menuElement = menu.querySelector('.project-actions-menu');
            
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
                    handleProjectAction(project, item.classList);
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
        
        function handleProjectAction(project, classList) {
            if (classList.contains('view-details')) {
                selectProject(project.id);
            } else if (classList.contains('edit-project')) {
                editProject(project.id);
            } else if (classList.contains('manage-users')) {
                manageProjectUsers(project.id);
            } else if (classList.contains('archive-project')) {
                archiveProject(project.id);
            }
        }
        
        function editProject(projectId) {
            const project = projectsData.find(p => p.id === projectId);
            if (!project) return;
            
            showEditProjectModal(project);
        }
        
        function manageProjectUsers(projectId) {
            const project = projectsData.find(p => p.id === projectId);
            if (!project) return;
            
            showManageUsersModal(project);
        }
        
        function toggleProjectStatus(projectId) {
            const project = projectsData.find(p => p.id === projectId);
            if (!project) return;
            
            const newStatus = project.status === 'active' ? 'inactive' : 'active';
            const action = newStatus === 'active' ? 'activate' : 'deactivate';
            
            if (confirm(`Are you sure you want to ${action} this project?`)) {
                // Update project status
                project.status = newStatus;
                
                // Update UI
                renderProjects();
                if (selectedProjectId === projectId) {
                    loadProjectDetails(projectId);
                    updateProjectActions(projectId);
                }
                
                showToast(`Project ${action}d successfully`, 'success');
            }
        }
        
        function archiveProject(projectId) {
            const project = projectsData.find(p => p.id === projectId);
            if (!project) return;
            
            if (confirm(`Are you sure you want to archive this project? This will move it to inactive status.`)) {
                project.status = 'inactive';
                
                renderProjects();
                if (selectedProjectId === projectId) {
                    loadProjectDetails(projectId);
                    updateProjectActions(projectId);
                }
                
                showToast('Project archived successfully', 'success');
            }
        }
        
        function deleteProject(projectId) {
            const project = projectsData.find(p => p.id === projectId);
            if (!project) return;
            
            if (confirm(`Are you sure you want to delete "${project.name}"? This action cannot be undone and all associated tickets will be affected.`)) {
                // Remove project from data
                projectsData = projectsData.filter(p => p.id !== projectId);
                
                // Update UI
                filterProjects();
                
                // Select another project if available
                if (projectsData.length > 0) {
                    selectProject(projectsData[0].id);
                } else {
                    // Clear details if no projects left
                    projectDetails.innerHTML = `
                        <div class="flex flex-col items-center justify-center py-8 text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-project-diagram text-gray-400 text-xl"></i>
                            </div>
                            <p class="text-text-dark/60 text-sm">Select a project to view details</p>
                        </div>
                    `;
                    projectActions.innerHTML = `
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
                    `;
                }
                
                showToast(`Project "${project.name}" deleted successfully`, 'success');
            }
        }
        
        function filterProjects() {
            currentPage = 1;
            renderProjects();
            updatePagination();
        }
        
        function resetAllFilters() {
            if (projectSearch) projectSearch.value = '';
            if (statusFilter) statusFilter.value = '';
            if (sortFilter) sortFilter.value = 'name_asc';
            
            currentPage = 1;
            renderProjects();
            updatePagination();
            
            showToast('Filters reset', 'info');
        }
        
        function sortProjects(field) {
            // Update sort indicator
            sortableHeaders.forEach(header => {
                header.classList.remove('active');
                const icon = header.querySelector('.fa-sort');
                if (icon) {
                    icon.className = 'fas fa-sort text-xs opacity-50';
                }
            });
            
            const header = document.querySelector(`[data-sort="${field}"]`);
            if (header) {
                header.classList.add('active');
                const icon = header.querySelector('.fa-sort');
                if (icon) {
                    if (currentSort.field === field) {
                        currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
                        icon.className = `fas fa-sort-${currentSort.direction === 'asc' ? 'up' : 'down'} text-xs`;
                    } else {
                        currentSort.field = field;
                        currentSort.direction = 'asc';
                        icon.className = 'fas fa-sort-up text-xs';
                    }
                }
            }
            
            renderProjects();
        }
        
        function changePage(page) {
            if (page < 1 || page > totalPages) return;
            
            currentPage = page;
            renderProjects();
            updatePagination();
            
            // Scroll to top of projects list
            if (projectsList) {
                projectsList.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
        
        function updatePagination() {
            const filteredProjects = getFilteredProjects();
            totalPages = Math.max(1, Math.ceil(filteredProjects.length / itemsPerPage));
            
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
            button.className = `page-btn ${currentPage === page ? 'active' : ''}`;
            button.textContent = page;
            button.addEventListener('click', () => changePage(page));
            pageNumbers.appendChild(button);
        }
        
        function updateShowingCount() {
            const filteredProjects = getFilteredProjects();
            const startIndex = (currentPage - 1) * itemsPerPage + 1;
            const endIndex = Math.min(startIndex + itemsPerPage - 1, filteredProjects.length);
            
            if (showingCount) {
                showingCount.textContent = `Showing ${startIndex}-${endIndex}`;
            }
            
            if (totalCount) {
                totalCount.textContent = filteredProjects.length;
            }
        }
        
        // Utility functions
        function getStatusName(status) {
            const statuses = {
                'active': 'Active',
                'inactive': 'Inactive',
                'completed': 'Completed',
                'on-hold': 'On Hold'
            };
            return statuses[status] || status;
        }
        
        function getStatusClass(status) {
            const classes = {
                'active': 'status-active',
                'inactive': 'status-inactive',
                'completed': 'status-completed',
                'on-hold': 'status-onhold'
            };
            return classes[status] || 'status-inactive';
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
        
        // Modal functions
        function showAddProjectModal() {
            // Create modal from template
            const template = document.getElementById('addProjectModalTemplate');
            const modal = document.importNode(template.content, true);
            const modalElement = modal.querySelector('.fixed');
            
            document.body.appendChild(modalElement);
            document.body.style.overflow = 'hidden';
            
            // Add event listeners
            const closeButtons = modalElement.querySelectorAll('.close-modal');
            closeButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    document.body.removeChild(modalElement);
                    document.body.style.overflow = 'auto';
                });
            });
            
            const confirmBtn = modalElement.querySelector('#confirmAddProject');
            confirmBtn.addEventListener('click', () => {
                const name = modalElement.querySelector('#projectName').value.trim();
                const code = modalElement.querySelector('#projectCode').value.trim();
                const description = modalElement.querySelector('#projectDescription').value.trim();
                const status = modalElement.querySelector('#projectStatus').value;
                
                if (!name || !code) {
                    alert('Please fill in all required fields');
                    return;
                }
                
                // Check if project code already exists
                if (projectsData.some(p => p.projectCode === code)) {
                    alert('Project code already exists. Please use a different code.');
                    return;
                }
                
                // Create new project
                const newProject = {
                    id: Math.max(...projectsData.map(p => p.id)) + 1,
                    projectCode: code.toUpperCase(),
                    name: name,
                    description: description || 'No description provided',
                    status: status,
                    createdAt: new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
                    totalTickets: 0,
                    openTickets: 0,
                    assignedUsers: 0,
                    assignedUsersList: []
                };
                
                projectsData.unshift(newProject);
                document.body.removeChild(modalElement);
                document.body.style.overflow = 'auto';
                
                // Reset to first page and select new project
                currentPage = 1;
                renderProjects();
                updatePagination();
                selectProject(newProject.id);
                
                showToast('Project added successfully!', 'success');
            });
        }
        
        function showEditProjectModal(project) {
            // Create modal from template
            const template = document.getElementById('editProjectModalTemplate');
            const modal = document.importNode(template.content, true);
            const modalElement = modal.querySelector('.fixed');
            
            // Fill modal data
            modalElement.querySelector('#editProjectName').value = project.name;
            modalElement.querySelector('#editProjectCode').value = project.projectCode;
            modalElement.querySelector('#editProjectDescription').value = project.description;
            modalElement.querySelector('#editProjectStatus').value = project.status;
            modalElement.querySelector('#editProjectCreated').value = project.createdAt;
            
            document.body.appendChild(modalElement);
            document.body.style.overflow = 'hidden';
            
            // Add event listeners
            const closeButtons = modalElement.querySelectorAll('.close-modal');
            closeButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    document.body.removeChild(modalElement);
                    document.body.style.overflow = 'auto';
                });
            });
            
            const confirmBtn = modalElement.querySelector('#confirmEditProject');
            confirmBtn.addEventListener('click', () => {
                const name = modalElement.querySelector('#editProjectName').value.trim();
                const code = modalElement.querySelector('#editProjectCode').value.trim();
                const description = modalElement.querySelector('#editProjectDescription').value.trim();
                const status = modalElement.querySelector('#editProjectStatus').value;
                
                if (!name || !code) {
                    alert('Please fill in all required fields');
                    return;
                }
                
                // Check if project code already exists (excluding current project)
                if (projectsData.some(p => p.projectCode === code && p.id !== project.id)) {
                    alert('Project code already exists. Please use a different code.');
                    return;
                }
                
                // Update project data
                const projectIndex = projectsData.findIndex(p => p.id === project.id);
                if (projectIndex !== -1) {
                    projectsData[projectIndex] = {
                        ...projectsData[projectIndex],
                        name: name,
                        projectCode: code.toUpperCase(),
                        description: description,
                        status: status
                    };
                }
                
                document.body.removeChild(modalElement);
                document.body.style.overflow = 'auto';
                
                // Update UI
                renderProjects();
                if (selectedProjectId === project.id) {
                    loadProjectDetails(project.id);
                    updateProjectActions(project.id);
                }
                
                showToast('Project updated successfully!', 'success');
            });
        }
        
        function showManageUsersModal(project) {
            // Create modal from template
            const template = document.getElementById('manageUsersModalTemplate');
            const modal = document.importNode(template.content, true);
            const modalElement = modal.querySelector('.fixed');
            
            // Fill modal data
            modalElement.querySelector('#modalProjectName').textContent = project.name;
            
            document.body.appendChild(modalElement);
            document.body.style.overflow = 'hidden';
            
            // Initialize selected users
            let selectedUsers = [...project.assignedUsersList];
            
            // Render users list
            function renderUsersList() {
                const usersListContainer = modalElement.querySelector('#usersListContainer');
                const searchTerm = modalElement.querySelector('#searchUsersInput').value.toLowerCase();
                
                const filteredUsers = availableUsers.filter(user => 
                    user.name.toLowerCase().includes(searchTerm) ||
                    user.email.toLowerCase().includes(searchTerm) ||
                    user.role.toLowerCase().includes(searchTerm)
                );
                
                usersListContainer.innerHTML = filteredUsers.map(user => {
                    const isSelected = selectedUsers.some(selected => selected.id === user.id);
                    return `
                        <div class="user-item ${isSelected ? 'selected' : ''}" data-user-id="${user.id}">
                            <div class="user-avatar-small">${user.avatar}</div>
                            <div class="flex-1">
                                <div class="text-gray-800 text-sm">${user.name}</div>
                                <div class="text-gray-500 text-xs">${user.email} • ${user.role}</div>
                            </div>
                            <div class="text-secondary">
                                <i class="fas fa-${isSelected ? 'check-circle' : 'plus-circle'}"></i>
                            </div>
                        </div>
                    `;
                }).join('');
                
                // Add click events
                usersListContainer.querySelectorAll('.user-item').forEach(item => {
                    item.addEventListener('click', () => {
                        const userId = parseInt(item.dataset.userId);
                        const user = availableUsers.find(u => u.id === userId);
                        
                        if (selectedUsers.some(u => u.id === userId)) {
                            selectedUsers = selectedUsers.filter(u => u.id !== userId);
                        } else {
                            selectedUsers.push(user);
                        }
                        
                        renderUsersList();
                        renderSelectedUsers();
                    });
                });
            }
            
            // Render selected users
            function renderSelectedUsers() {
                const selectedUsersContainer = modalElement.querySelector('#selectedUsers');
                const selectedCount = modalElement.querySelector('#selectedCount');
                
                selectedCount.textContent = selectedUsers.length;
                selectedUsersContainer.innerHTML = selectedUsers.map(user => `
                    <div class="selected-user-tag">
                        <span>${user.name}</span>
                        <button class="remove-user-btn" data-user-id="${user.id}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `).join('');
                
                // Add remove events
                selectedUsersContainer.querySelectorAll('.remove-user-btn').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const userId = parseInt(btn.dataset.userId);
                        selectedUsers = selectedUsers.filter(u => u.id !== userId);
                        renderUsersList();
                        renderSelectedUsers();
                    });
                });
            }
            
            // Initialize
            renderUsersList();
            renderSelectedUsers();
            
            // Add event listeners
            const closeButtons = modalElement.querySelectorAll('.close-modal');
            closeButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    document.body.removeChild(modalElement);
                    document.body.style.overflow = 'auto';
                });
            });
            
            // Search functionality
            const searchInput = modalElement.querySelector('#searchUsersInput');
            searchInput.addEventListener('input', debounce(() => {
                renderUsersList();
            }, 300));
            
            const confirmBtn = modalElement.querySelector('#confirmManageUsers');
            confirmBtn.addEventListener('click', () => {
                // Update project data
                const projectIndex = projectsData.findIndex(p => p.id === project.id);
                if (projectIndex !== -1) {
                    projectsData[projectIndex].assignedUsersList = [...selectedUsers];
                    projectsData[projectIndex].assignedUsers = selectedUsers.length;
                }
                
                document.body.removeChild(modalElement);
                document.body.style.overflow = 'auto';
                
                // Update UI
                if (selectedProjectId === project.id) {
                    loadProjectDetails(project.id);
                    updateProjectActions(project.id);
                }
                
                showToast('Project users updated successfully!', 'success');
            });
        }
        
        function showBulkAssignModal() {
            showToast('Bulk assign feature coming soon!', 'info');
        }
        
        function showImportProjectsModal() {
            showToast('Import projects feature coming soon!', 'info');
        }
        
        function exportProjectsData() {
            const filteredProjects = getFilteredProjects();
            const csvContent = convertToCSV(filteredProjects);
            downloadCSV(csvContent, 'projects.csv');
            showToast('Projects exported successfully!', 'success');
        }
        
        function convertToCSV(data) {
            const headers = ['ID', 'Project Code', 'Name', 'Description', 'Status', 'Created At', 'Total Tickets', 'Open Tickets', 'Assigned Users'];
            const rows = data.map(project => [
                project.id,
                `"${project.projectCode}"`,
                `"${project.name}"`,
                `"${project.description}"`,
                getStatusName(project.status),
                project.createdAt,
                project.totalTickets,
                project.openTickets,
                project.assignedUsers
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
            toast.className = `custom-toast fixed top-24 right-6 p-4 rounded-lg shadow-lg z-[1000] max-w-sm animate-slideInUp ${type === 'error' ? 'bg-red-500 text-white' : type === 'success' ? 'bg-green-500 text-white' : 'bg-blue-500 text-white'}`;
            toast.innerHTML = `
                <div class="flex items-center gap-2">
                    <i class="fas ${type === 'error' ? 'fa-exclamation-circle' : type === 'success' ? 'fa-check-circle' : 'fa-info-circle'}"></i>
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
        
        // Auto-select first project on load
        setTimeout(() => {
            if (projectsData.length > 0) {
                selectProject(projectsData[0].id);
            }
        }, 100);
    });
</script>
<?= $this->endSection() ?>