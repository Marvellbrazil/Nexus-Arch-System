<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>Manage Users - NEXUS Admin<?= $this->endSection() ?>

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
        <h1 class="text-[34.77px] font-semibold mb-2 text-text-dark">Manage Users</h1>
        <p class="text-[15.45px] font-light text-text-dark">User account and access management</p>
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
                               placeholder="Search users..." 
                               id="userSearch"
                               class="w-full h-12 pl-12 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
                    </div>
                    
                    <!-- Filter Options -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Role Filter -->
                        <div>
                            <label class="block text-text-dark/70 text-sm mb-2">Role</label>
                            <select id="roleFilter" class="w-full h-12 pl-4 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary">
                                <option value="">All Roles</option>
                                <option value="admin">Admin</option>
                                <option value="support">Support</option>
                                <option value="customer">Customer</option>
                                <option value="developer">Developer</option>
                                <option value="manager">Manager</option>
                            </select>
                        </div>
                        
                        <!-- Status Filter -->
                        <div>
                            <label class="block text-text-dark/70 text-sm mb-2">Status</label>
                            <select id="statusFilter" class="w-full h-12 pl-4 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="pending">Pending</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex gap-3">
                        <button id="resetFilters" class="flex-1 h-12 bg-secondary text-white rounded-xl hover:bg-[#665C9E] transition-colors font-medium">
                            Reset Filters
                        </button>
                        <button id="exportUsers" class="flex-1 h-12 bg-white text-secondary border border-secondary rounded-xl hover:bg-secondary/5 transition-colors font-medium">
                            Export Users
                        </button>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="dashboard-card">
                <div class="dashboard-card-header flex justify-between items-center">
                    <div class="text-text-dark/85 text-base font-medium">User Management</div>
                    <div class="text-sm text-secondary font-medium">
                        <span id="showingCount">Showing 1-5</span> of <span id="totalCount">15</span> users
                    </div>
                </div>
                
                <!-- Table Container -->
                <div class="overflow-x-auto">
                    <!-- Table Header -->
                    <div class="grid grid-cols-12 gap-4 py-4 px-6 bg-[#E3DAEE] rounded-lg text-sm font-semibold text-text-dark/80">
                        <div class="col-span-2 flex items-center gap-2 cursor-pointer sortable" data-sort="id">
                            <span>User ID</span>
                            <i class="fas fa-sort text-xs opacity-50"></i>
                        </div>
                        <div class="col-span-3 cursor-pointer sortable" data-sort="name">
                            <span>Name</span>
                            <i class="fas fa-sort text-xs opacity-50 ml-1"></i>
                        </div>
                        <div class="col-span-3">Email</div>
                        <div class="col-span-2">Role</div>
                        <div class="col-span-1">Status</div>
                        <div class="col-span-1"></div>
                    </div>
                    
                    <!-- Users List -->
                    <div id="usersList" class="divide-y divide-white/30">
                        <!-- User rows will be populated here -->
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
        
        <!-- Right Column: User Details & Actions -->
        <div class="space-y-6">
            <!-- Add User Card -->
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <div class="text-text-dark/85 text-base font-medium">Quick Actions</div>
                </div>
                
                <div class="p-4 space-y-3">
                    <button id="addUserBtn" class="w-full py-3 bg-secondary text-white rounded-xl hover:bg-[#665C9E] transition-colors font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-plus text-lg"></i>
                        Add New User
                    </button>
                    
                    <button id="bulkActionsBtn" class="w-full py-3 bg-white text-text-dark border border-text-dark/20 rounded-xl hover:bg-gray-50 transition-colors font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-users"></i>
                        Bulk Actions
                    </button>
                    
                    <button id="importUsersBtn" class="w-full py-3 bg-white text-text-dark border border-text-dark/20 rounded-xl hover:bg-gray-50 transition-colors font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-file-import"></i>
                        Import Users
                    </button>
                </div>
            </div>
            
            <!-- User Detail Card -->
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <div class="text-text-dark/85 text-base font-medium">User Details</div>
                </div>
                
                <div id="userDetails" class="p-4">
                    <!-- Default state when no user is selected -->
                    <div class="flex flex-col items-center justify-center py-8 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-user text-gray-400 text-xl"></i>
                        </div>
                        <p class="text-text-dark/60 text-sm">Select a user to view details</p>
                    </div>
                    
                    <!-- User details will be loaded here -->
                </div>
            </div>
            
            <!-- User Actions Card -->
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <div class="text-text-dark/85 text-base font-medium">User Actions</div>
                </div>
                
                <div id="userActions" class="p-4 space-y-3">
                    <!-- Default state when no user is selected -->
                    <div class="space-y-2">
                        <button class="w-full py-3 bg-gray-100 text-gray-400 rounded-xl cursor-not-allowed" disabled>
                            <i class="fas fa-edit mr-2"></i>
                            Edit User
                        </button>
                        
                        <button class="w-full py-3 bg-gray-100 text-gray-400 rounded-xl cursor-not-allowed" disabled>
                            <i class="fas fa-key mr-2"></i>
                            Reset Password
                        </button>
                        
                        <button class="w-full py-3 bg-gray-100 text-gray-400 rounded-xl cursor-not-allowed" disabled>
                            <i class="fas fa-power-off mr-2"></i>
                            Deactivate Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Actions Menu Template -->
<template id="userActionsMenuTemplate">
    <div class="user-actions-menu absolute bg-white rounded-xl shadow-xl border border-gray-200 z-50 w-48">
        <div class="py-2">
            <button class="menu-item view-details">
                <i class="fas fa-eye text-gray-600"></i>
                View Details
            </button>
            <button class="menu-item edit-user">
                <i class="fas fa-edit text-secondary"></i>
                Edit User
            </button>
            <button class="menu-item reset-password">
                <i class="fas fa-key text-blue-600"></i>
                Reset Password
            </button>
            <div class="border-t border-gray-200 my-1"></div>
            <button class="menu-item deactivate text-red-600">
                <i class="fas fa-power-off"></i>
                Deactivate
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
    
    /* User row styling */
    .user-row {
        display: grid;
        grid-template-columns: repeat(12, minmax(0, 1fr));
        gap: 1rem;
        padding: 1rem 1.5rem;
        transition: all 0.2s ease;
        align-items: center;
    }
    
    .user-row:hover {
        background: rgba(117, 110, 164, 0.05);
    }
    
    .user-row.selected {
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
    
    .status-pending {
        background: #FEF3C7;
        color: #92400E;
    }
    
    .status-suspended {
        background: #FECACA;
        color: #991B1B;
    }
    
    /* Role badges */
    .role-badge {
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
    }
    
    .role-customer {
        background: #E1BEE7;
        color: #625975;
    }
    
    .role-support {
        background: #E4DBFA;
        color: #625975;
    }
    
    .role-admin {
        background: #C7D2FE;
        color: #3730A3;
    }
    
    .role-developer {
        background: #FEF3C7;
        color: #92400E;
    }
    
    /* Action menu */
    .user-actions-menu {
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
    
    /* User details styling */
    .user-avatar {
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
    
    .user-info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .user-info-item:last-child {
        border-bottom: none;
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
        .user-row {
            grid-template-columns: repeat(8, minmax(0, 1fr));
            gap: 0.75rem;
            padding: 0.75rem 1rem;
        }
        
        .user-row > div:nth-child(1) { grid-column: span 2; }
        .user-row > div:nth-child(2) { grid-column: span 3; }
        .user-row > div:nth-child(3) { display: none; }
        .user-row > div:nth-child(4) { grid-column: span 2; }
        .user-row > div:nth-child(5) { grid-column: span 1; }
        .user-row > div:nth-child(6) { grid-column: span 1; }
    }
    
    @media (max-width: 768px) {
        .dashboard-card-header {
            flex-direction: column;
            gap: 0.5rem;
            align-items: flex-start;
        }
        
        .user-row {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }
        
        .user-row > div {
            grid-column: span 1 !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initial data
        let usersData = [
            {
                id: 1452,
                name: "Deni Darmawan",
                email: "denydarmawan@gmail.com",
                role: "customer",
                status: "active",
                joined: "Apr 20, 2024",
                lastLogin: "Today, 08:30 AM",
                avatar: "DD",
                department: "IT Support",
                projects: ["Project A", "Project B"]
            },
            {
                id: 1423,
                name: "Nasihuy Mantuy",
                email: "nasihuyhuy@gmail.com",
                role: "customer",
                status: "active",
                joined: "Apr 22, 2024",
                lastLogin: "Today, 10:15 AM",
                avatar: "NM",
                department: "Technical Support",
                projects: ["Project B"]
            },
            {
                id: 1419,
                name: "Prabroro Pororo",
                email: "pororonabati@gmail.com",
                role: "support",
                status: "active",
                joined: "Apr 19, 2024",
                lastLogin: "Yesterday, 16:45",
                avatar: "PP",
                department: "Support",
                projects: ["Project A", "Project C"]
            },
            {
                id: 1490,
                name: "Miaw Aug",
                email: "miawauggg@gmail.com",
                role: "uiux-support",
                status: "active",
                joined: "Apr 13, 2024",
                lastLogin: "Today, 09:20 AM",
                avatar: "MA",
                department: "UI/UX Support",
                projects: ["Project D"]
            },
            {
                id: 1413,
                name: "Bangladesh",
                email: "bangladesh@gmail.com",
                role: "customer",
                status: "inactive",
                joined: "Apr 7, 2024",
                lastLogin: "Apr 10, 2024",
                avatar: "BD",
                department: "",
                projects: []
            }
        ];
        
        let currentPage = 1;
        let itemsPerPage = 5;
        let totalPages = Math.ceil(usersData.length / itemsPerPage);
        let selectedUserId = null;
        let currentSort = { field: 'id', direction: 'asc' };
        
        // DOM Elements
        const userSearch = document.getElementById('userSearch');
        const roleFilter = document.getElementById('roleFilter');
        const statusFilter = document.getElementById('statusFilter');
        const resetFilters = document.getElementById('resetFilters');
        const exportUsers = document.getElementById('exportUsers');
        const usersList = document.getElementById('usersList');
        const userDetails = document.getElementById('userDetails');
        const userActions = document.getElementById('userActions');
        const showingCount = document.getElementById('showingCount');
        const totalCount = document.getElementById('totalCount');
        const paginationInfo = document.getElementById('paginationInfo');
        const pageNumbers = document.getElementById('pageNumbers');
        const prevPage = document.getElementById('prevPage');
        const nextPage = document.getElementById('nextPage');
        const sortableHeaders = document.querySelectorAll('.sortable');
        const addUserBtn = document.getElementById('addUserBtn');
        const bulkActionsBtn = document.getElementById('bulkActionsBtn');
        const importUsersBtn = document.getElementById('importUsersBtn');
        
        // Initialize
        init();
        
        function init() {
            renderUsers();
            updatePagination();
            updateShowingCount();
            
            // Event listeners
            setupEventListeners();
        }
        
        function setupEventListeners() {
            // Search input
            if (userSearch) {
                userSearch.addEventListener('input', debounce(() => {
                    filterUsers();
                }, 300));
            }
            
            // Filter changes
            if (roleFilter) {
                roleFilter.addEventListener('change', filterUsers);
            }
            
            if (statusFilter) {
                statusFilter.addEventListener('change', filterUsers);
            }
            
            // Reset filters
            if (resetFilters) {
                resetFilters.addEventListener('click', resetAllFilters);
            }
            
            // Export users
            if (exportUsers) {
                exportUsers.addEventListener('click', exportUsersData);
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
                    sortUsers(field);
                });
            });
            
            // Action buttons
            if (addUserBtn) {
                addUserBtn.addEventListener('click', showAddUserModal);
            }
            
            if (bulkActionsBtn) {
                bulkActionsBtn.addEventListener('click', showBulkActionsModal);
            }
            
            if (importUsersBtn) {
                importUsersBtn.addEventListener('click', showImportUsersModal);
            }
        }
        
        function renderUsers() {
            if (!usersList) return;
            
            usersList.innerHTML = '';
            
            const filteredUsers = getFilteredUsers();
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const pageUsers = filteredUsers.slice(startIndex, endIndex);
            
            if (pageUsers.length === 0) {
                usersList.innerHTML = `
                    <div class="py-12 text-center">
                        <i class="fas fa-users text-gray-300 text-4xl mb-4"></i>
                        <p class="text-gray-500">No users found</p>
                        <p class="text-gray-400 text-sm mt-2">Try adjusting your filters</p>
                    </div>
                `;
                return;
            }
            
            pageUsers.forEach(user => {
                const userRow = document.createElement('div');
                userRow.className = `user-row ${selectedUserId === user.id ? 'selected' : ''}`;
                userRow.dataset.userId = user.id;
                
                userRow.innerHTML = `
                    <div class="col-span-2 text-text-dark/60 font-medium">#${user.id}</div>
                    <div class="col-span-3">
                        <div class="font-medium text-text-dark">${user.name}</div>
                        <div class="text-xs text-text-dark/50 truncate">${user.email}</div>
                    </div>
                    <div class="col-span-3">
                        <div class="font-medium text-text-dark truncate">${user.email}</div>
                    </div>
                    <div class="col-span-2">
                        <span class="${getRoleClass(user.role)} role-badge">
                            ${getRoleName(user.role)}
                        </span>
                    </div>
                    <div class="col-span-1">
                        <span class="${getStatusClass(user.status)} status-badge">
                            ${getStatusName(user.status)}
                        </span>
                    </div>
                    <div class="col-span-1 text-right">
                        <button class="user-action-btn text-text-dark/60 hover:text-secondary transition-colors">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                `;
                
                // Add click event for selecting user
                userRow.addEventListener('click', (e) => {
                    if (!e.target.closest('.user-action-btn')) {
                        selectUser(user.id);
                    }
                });
                
                // Add action menu event
                const actionBtn = userRow.querySelector('.user-action-btn');
                actionBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    showUserActionsMenu(e.target.closest('button'), user);
                });
                
                usersList.appendChild(userRow);
            });
            
            // Update showing count
            updateShowingCount();
        }
        
        function getFilteredUsers() {
            let filtered = [...usersData];
            
            // Apply search filter
            const searchTerm = userSearch ? userSearch.value.toLowerCase().trim() : '';
            if (searchTerm) {
                filtered = filtered.filter(user => 
                    user.name.toLowerCase().includes(searchTerm) ||
                    user.email.toLowerCase().includes(searchTerm) ||
                    user.id.toString().includes(searchTerm)
                );
            }
            
            // Apply role filter
            const roleValue = roleFilter ? roleFilter.value : '';
            if (roleValue) {
                filtered = filtered.filter(user => user.role === roleValue);
            }
            
            // Apply status filter
            const statusValue = statusFilter ? statusFilter.value : '';
            if (statusValue) {
                filtered = filtered.filter(user => user.status === statusValue);
            }
            
            // Apply sorting
            filtered.sort((a, b) => {
                let aValue = a[currentSort.field];
                let bValue = b[currentSort.field];
                
                // Handle numeric sorting for ID
                if (currentSort.field === 'id') {
                    aValue = parseInt(aValue);
                    bValue = parseInt(bValue);
                }
                
                if (currentSort.direction === 'asc') {
                    return aValue > bValue ? 1 : -1;
                } else {
                    return aValue < bValue ? 1 : -1;
                }
            });
            
            return filtered;
        }
        
        function selectUser(userId) {
            selectedUserId = userId;
            
            // Update selected row styling
            document.querySelectorAll('.user-row').forEach(row => {
                row.classList.remove('selected');
                if (parseInt(row.dataset.userId) === userId) {
                    row.classList.add('selected');
                }
            });
            
            // Load user details
            loadUserDetails(userId);
            
            // Update user actions
            updateUserActions(userId);
        }
        
        function loadUserDetails(userId) {
            const user = usersData.find(u => u.id === userId);
            if (!user) return;
            
            const detailsHtml = `
                <div class="animate-fadeIn">
                    <div class="user-avatar">${user.avatar}</div>
                    <div class="text-center mb-6">
                        <h3 class="text-lg font-semibold text-text-dark">${user.name}</h3>
                        <p class="text-text-dark/60 text-sm">${user.email}</p>
                        <span class="inline-block mt-2 ${getStatusClass(user.status)} status-badge">
                            ${getStatusName(user.status)}
                        </span>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="user-info-item">
                            <span class="text-text-dark/70 text-sm">Role:</span>
                            <span class="text-text-dark font-medium">${getRoleName(user.role)}</span>
                        </div>
                        <div class="user-info-item">
                            <span class="text-text-dark/70 text-sm">Joined:</span>
                            <span class="text-text-dark font-medium">${user.joined}</span>
                        </div>
                        <div class="user-info-item">
                            <span class="text-text-dark/70 text-sm">Last Login:</span>
                            <span class="text-text-dark font-medium">${user.lastLogin}</span>
                        </div>
                        ${user.department ? `
                        <div class="user-info-item">
                            <span class="text-text-dark/70 text-sm">Department:</span>
                            <span class="text-text-dark font-medium">${user.department}</span>
                        </div>
                        ` : ''}
                        ${user.projects.length > 0 ? `
                        <div class="user-info-item">
                            <span class="text-text-dark/70 text-sm">Projects:</span>
                            <span class="text-text-dark font-medium">${user.projects.join(', ')}</span>
                        </div>
                        ` : ''}
                    </div>
                </div>
            `;
            
            userDetails.innerHTML = detailsHtml;
        }
        
        function updateUserActions(userId) {
            const user = usersData.find(u => u.id === userId);
            if (!user) return;
            
            const isActive = user.status === 'active';
            
            const actionsHtml = `
                <div class="space-y-2 animate-fadeIn">
                    <button class="edit-user-btn w-full py-3 bg-secondary text-white rounded-xl hover:bg-[#665C9E] transition-colors font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-edit"></i>
                        Edit User
                    </button>
                    
                    <button class="reset-password-btn w-full py-3 bg-white text-text-dark border border-text-dark/20 rounded-xl hover:bg-gray-50 transition-colors font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-key"></i>
                        Reset Password
                    </button>
                    
                    <button class="toggle-status-btn w-full py-3 ${isActive ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-green-50 text-green-600 border border-green-200'} rounded-xl hover:${isActive ? 'bg-red-100' : 'bg-green-100'} transition-colors font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-power-off"></i>
                        ${isActive ? 'Deactivate Account' : 'Activate Account'}
                    </button>
                </div>
            `;
            
            userActions.innerHTML = actionsHtml;
            
            // Add event listeners to action buttons
            setTimeout(() => {
                const editBtn = userActions.querySelector('.edit-user-btn');
                const resetBtn = userActions.querySelector('.reset-password-btn');
                const toggleBtn = userActions.querySelector('.toggle-status-btn');
                
                if (editBtn) {
                    editBtn.addEventListener('click', () => editUser(userId));
                }
                
                if (resetBtn) {
                    resetBtn.addEventListener('click', () => resetUserPassword(userId));
                }
                
                if (toggleBtn) {
                    toggleBtn.addEventListener('click', () => toggleUserStatus(userId));
                }
            }, 100);
        }
        
        function showUserActionsMenu(button, user) {
            // Remove existing menus
            document.querySelectorAll('.user-actions-menu').forEach(menu => menu.remove());
            
            // Create menu from template
            const template = document.getElementById('userActionsMenuTemplate');
            const menu = template.content.cloneNode(true);
            const menuElement = menu.querySelector('.user-actions-menu');
            
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
                    handleUserAction(user, item.classList);
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
        
        function handleUserAction(user, classList) {
            if (classList.contains('view-details')) {
                selectUser(user.id);
            } else if (classList.contains('edit-user')) {
                editUser(user.id);
            } else if (classList.contains('reset-password')) {
                resetUserPassword(user.id);
            } else if (classList.contains('deactivate')) {
                toggleUserStatus(user.id);
            }
        }
        
        function editUser(userId) {
            const user = usersData.find(u => u.id === userId);
            if (!user) return;
            
            showEditUserModal(user);
        }
        
        function resetUserPassword(userId) {
            const user = usersData.find(u => u.id === userId);
            if (!user) return;
            
            showResetPasswordModal(user);
        }
        
        function toggleUserStatus(userId) {
            const user = usersData.find(u => u.id === userId);
            if (!user) return;
            
            const newStatus = user.status === 'active' ? 'inactive' : 'active';
            const action = newStatus === 'active' ? 'activate' : 'deactivate';
            
            if (confirm(`Are you sure you want to ${action} this user?`)) {
                // Update user status
                user.status = newStatus;
                
                // Update UI
                renderUsers();
                if (selectedUserId === userId) {
                    loadUserDetails(userId);
                    updateUserActions(userId);
                }
                
                showToast(`User ${action}d successfully`, 'success');
            }
        }
        
        function filterUsers() {
            currentPage = 1;
            renderUsers();
            updatePagination();
        }
        
        function resetAllFilters() {
            if (userSearch) userSearch.value = '';
            if (roleFilter) roleFilter.value = '';
            if (statusFilter) statusFilter.value = '';
            
            currentPage = 1;
            renderUsers();
            updatePagination();
            
            showToast('Filters reset', 'info');
        }
        
        function sortUsers(field) {
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
            
            renderUsers();
        }
        
        function changePage(page) {
            if (page < 1 || page > totalPages) return;
            
            currentPage = page;
            renderUsers();
            updatePagination();
            
            // Scroll to top of user list
            if (usersList) {
                usersList.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
        
        function updatePagination() {
            const filteredUsers = getFilteredUsers();
            totalPages = Math.max(1, Math.ceil(filteredUsers.length / itemsPerPage));
            
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
            const filteredUsers = getFilteredUsers();
            const startIndex = (currentPage - 1) * itemsPerPage + 1;
            const endIndex = Math.min(startIndex + itemsPerPage - 1, filteredUsers.length);
            
            if (showingCount) {
                showingCount.textContent = `Showing ${startIndex}-${endIndex}`;
            }
            
            if (totalCount) {
                totalCount.textContent = filteredUsers.length;
            }
        }
        
        // Utility functions
        function getRoleName(role) {
            const roles = {
                'customer': 'Customer',
                'support': 'Support',
                'admin': 'Admin',
                'developer': 'Developer',
                'manager': 'Manager',
                'uiux-support': 'UI/UX Support'
            };
            return roles[role] || role;
        }
        
        function getStatusName(status) {
            const statuses = {
                'active': 'Active',
                'inactive': 'Inactive',
                'pending': 'Pending',
                'suspended': 'Suspended'
            };
            return statuses[status] || status;
        }
        
        function getRoleClass(role) {
            const classes = {
                'customer': 'role-customer',
                'support': 'role-support',
                'admin': 'role-admin',
                'developer': 'role-developer',
                'manager': 'role-manager',
                'uiux-support': 'role-support'
            };
            return classes[role] || 'role-customer';
        }
        
        function getStatusClass(status) {
            const classes = {
                'active': 'status-active',
                'inactive': 'status-inactive',
                'pending': 'status-pending',
                'suspended': 'status-suspended'
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
        function showAddUserModal() {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4';
            modal.innerHTML = `
                <div class="bg-white rounded-2xl w-full max-w-md animate-slideInUp">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-gray-800">Add New User</h3>
                            <button class="close-modal text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-600 text-sm mb-2">Full Name</label>
                                <input type="text" id="newUserName" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                            </div>
                            
                            <div>
                                <label class="block text-gray-600 text-sm mb-2">Email Address</label>
                                <input type="email" id="newUserEmail" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                            </div>
                            
                            <div>
                                <label class="block text-gray-600 text-sm mb-2">Role</label>
                                <select id="newUserRole" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                                    <option value="customer">Customer</option>
                                    <option value="support">Support</option>
                                    <option value="admin">Admin</option>
                                    <option value="developer">Developer</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-gray-600 text-sm mb-2">Department</label>
                                <select id="newUserDepartment" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                                    <option value="">None</option>
                                    <option value="IT Support">IT Support</option>
                                    <option value="Technical Support">Technical Support</option>
                                    <option value="UI/UX Support">UI/UX Support</option>
                                    <option value="Feature Request">Feature Request</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6 border-t border-gray-200 flex gap-3">
                        <button class="close-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button id="confirmAddUser" class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors">
                            Add User
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            document.body.style.overflow = 'hidden';
            
            // Add event listeners
            modal.querySelectorAll('.close-modal').forEach(btn => {
                btn.addEventListener('click', () => modal.remove());
            });
            
            const confirmBtn = modal.querySelector('#confirmAddUser');
            confirmBtn.addEventListener('click', () => {
                const name = modal.querySelector('#newUserName').value.trim();
                const email = modal.querySelector('#newUserEmail').value.trim();
                const role = modal.querySelector('#newUserRole').value;
                const department = modal.querySelector('#newUserDepartment').value;
                
                if (!name || !email) {
                    alert('Please fill in all required fields');
                    return;
                }
                
                // Create new user
                const newUser = {
                    id: Math.max(...usersData.map(u => u.id)) + 1,
                    name: name,
                    email: email,
                    role: role,
                    status: 'active',
                    joined: new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
                    lastLogin: 'Just now',
                    avatar: name.split(' ').map(n => n[0]).join('').toUpperCase(),
                    department: department,
                    projects: []
                };
                
                usersData.unshift(newUser);
                modal.remove();
                document.body.style.overflow = 'auto';
                
                // Reset to first page and select new user
                currentPage = 1;
                renderUsers();
                updatePagination();
                selectUser(newUser.id);
                
                showToast('User added successfully!', 'success');
            });
        }
        
        function showEditUserModal(user) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4';
            modal.innerHTML = `
                <div class="bg-white rounded-2xl w-full max-w-md animate-slideInUp">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-gray-800">Edit User</h3>
                            <button class="close-modal text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-600 text-sm mb-2">Full Name</label>
                                <input type="text" value="${user.name}" id="editUserName" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                            </div>
                            
                            <div>
                                <label class="block text-gray-600 text-sm mb-2">Email Address</label>
                                <input type="email" value="${user.email}" id="editUserEmail" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                            </div>
                            
                            <div>
                                <label class="block text-gray-600 text-sm mb-2">Role</label>
                                <select id="editUserRole" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                                    <option value="customer" ${user.role === 'customer' ? 'selected' : ''}>Customer</option>
                                    <option value="support" ${user.role === 'support' ? 'selected' : ''}>Support</option>
                                    <option value="admin" ${user.role === 'admin' ? 'selected' : ''}>Admin</option>
                                    <option value="developer" ${user.role === 'developer' ? 'selected' : ''}>Developer</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-gray-600 text-sm mb-2">Status</label>
                                <select id="editUserStatus" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                                    <option value="active" ${user.status === 'active' ? 'selected' : ''}>Active</option>
                                    <option value="inactive" ${user.status === 'inactive' ? 'selected' : ''}>Inactive</option>
                                    <option value="pending" ${user.status === 'pending' ? 'selected' : ''}>Pending</option>
                                    <option value="suspended" ${user.status === 'suspended' ? 'selected' : ''}>Suspended</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-gray-600 text-sm mb-2">Department</label>
                                <select id="editUserDepartment" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                                    <option value="">None</option>
                                    <option value="IT Support" ${user.department === 'IT Support' ? 'selected' : ''}>IT Support</option>
                                    <option value="Technical Support" ${user.department === 'Technical Support' ? 'selected' : ''}>Technical Support</option>
                                    <option value="UI/UX Support" ${user.department === 'UI/UX Support' ? 'selected' : ''}>UI/UX Support</option>
                                    <option value="Feature Request" ${user.department === 'Feature Request' ? 'selected' : ''}>Feature Request</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6 border-t border-gray-200 flex gap-3">
                        <button class="close-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button id="confirmEditUser" class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors">
                            Save Changes
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            document.body.style.overflow = 'hidden';
            
            // Add event listeners
            modal.querySelectorAll('.close-modal').forEach(btn => {
                btn.addEventListener('click', () => modal.remove());
            });
            
            const confirmBtn = modal.querySelector('#confirmEditUser');
            confirmBtn.addEventListener('click', () => {
                const name = modal.querySelector('#editUserName').value.trim();
                const email = modal.querySelector('#editUserEmail').value.trim();
                const role = modal.querySelector('#editUserRole').value;
                const status = modal.querySelector('#editUserStatus').value;
                const department = modal.querySelector('#editUserDepartment').value;
                
                if (!name || !email) {
                    alert('Please fill in all required fields');
                    return;
                }
                
                // Update user data
                const userIndex = usersData.findIndex(u => u.id === user.id);
                if (userIndex !== -1) {
                    usersData[userIndex] = {
                        ...usersData[userIndex],
                        name: name,
                        email: email,
                        role: role,
                        status: status,
                        department: department
                    };
                }
                
                modal.remove();
                document.body.style.overflow = 'auto';
                
                // Update UI
                renderUsers();
                if (selectedUserId === user.id) {
                    loadUserDetails(user.id);
                    updateUserActions(user.id);
                }
                
                showToast('User updated successfully!', 'success');
            });
        }
        
        function showResetPasswordModal(user) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4';
            modal.innerHTML = `
                <div class="bg-white rounded-2xl w-full max-w-md animate-slideInUp">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-gray-800">Reset Password</h3>
                            <button class="close-modal text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-600 text-sm mb-2">User</label>
                                <input type="text" value="${user.name}" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary" disabled>
                            </div>
                            
                            <div>
                                <label class="block text-gray-600 text-sm mb-2">New Password</label>
                                <input type="password" id="newPassword" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                            </div>
                            
                            <div>
                                <label class="block text-gray-600 text-sm mb-2">Confirm Password</label>
                                <input type="password" id="confirmPassword" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6 border-t border-gray-200 flex gap-3">
                        <button class="close-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button id="confirmResetPassword" class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors">
                            Reset Password
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            document.body.style.overflow = 'hidden';
            
            // Add event listeners
            modal.querySelectorAll('.close-modal').forEach(btn => {
                btn.addEventListener('click', () => modal.remove());
            });
            
            const confirmBtn = modal.querySelector('#confirmResetPassword');
            confirmBtn.addEventListener('click', () => {
                const newPassword = modal.querySelector('#newPassword').value;
                const confirmPassword = modal.querySelector('#confirmPassword').value;
                
                if (!newPassword || !confirmPassword) {
                    alert('Please fill in both password fields');
                    return;
                }
                
                if (newPassword !== confirmPassword) {
                    alert('Passwords do not match');
                    return;
                }
                
                modal.remove();
                document.body.style.overflow = 'auto';
                showToast('Password reset successfully!', 'success');
            });
        }
        
        function showBulkActionsModal() {
            showToast('Bulk actions feature coming soon!', 'info');
        }
        
        function showImportUsersModal() {
            showToast('Import users feature coming soon!', 'info');
        }
        
        function exportUsersData() {
            const filteredUsers = getFilteredUsers();
            const csvContent = convertToCSV(filteredUsers);
            downloadCSV(csvContent, 'users.csv');
            showToast('Users exported successfully!', 'success');
        }
        
        function convertToCSV(data) {
            const headers = ['ID', 'Name', 'Email', 'Role', 'Status', 'Joined', 'Last Login'];
            const rows = data.map(user => [
                user.id,
                `"${user.name}"`,
                user.email,
                getRoleName(user.role),
                getStatusName(user.status),
                user.joined,
                user.lastLogin
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
        
        // Auto-select first user on load
        setTimeout(() => {
            if (usersData.length > 0) {
                selectUser(usersData[0].id);
            }
        }, 100);
    });
</script>
<?= $this->endSection() ?>