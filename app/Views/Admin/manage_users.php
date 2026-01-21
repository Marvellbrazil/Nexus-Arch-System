<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>Manage Users - NEXUS Admin<?= $this->endSection() ?>

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
        <h1 class="text-[34.77px] font-semibold mb-2 text-text-dark">Manage Users</h1>
        <p class="text-[15.45px] font-light text-text-dark">User account and access management</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="dashboard-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-text-dark/70 text-sm">Total Users</p>
                    <p class="text-2xl font-semibold text-text-dark">
                        <?= number_format($userStats['total_users'] ?? 0) ?></p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-text-dark/70 text-sm">Active Users</p>
                    <p class="text-2xl font-semibold text-text-dark">
                        <?= number_format($userStats['active_users'] ?? 0) ?></p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-check text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-text-dark/70 text-sm">Inactive Users</p>
                    <p class="text-2xl font-semibold text-text-dark">
                        <?= number_format($userStats['inactive_users'] ?? 0) ?></p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-slash text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-text-dark/70 text-sm">Unique Roles</p>
                    <p class="text-2xl font-semibold text-text-dark">
                        <?= number_format($userStats['unique_roles'] ?? 0) ?></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-tag text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
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
                        <input type="text" placeholder="Search users by name, email, role..." id="userSearch"
                            class="w-full h-12 pl-12 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
                    </div>

                    <!-- Filter Options -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Role Filter -->
                        <div>
                            <label class="block text-text-dark/70 text-sm mb-2">Role</label>
                            <select id="roleFilter"
                                class="w-full h-12 pl-4 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary">
                                <option value="">All Roles</option>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role['role_id'] ?>"><?= htmlspecialchars($role['role_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Department Filter -->
                        <div>
                            <label class="block text-text-dark/70 text-sm mb-2">Department</label>
                            <select id="departmentFilter"
                                class="w-full h-12 pl-4 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary">
                                <option value="">All Departments</option>
                                <?php foreach ($departments as $department): ?>
                                    <option value="<?= $department['department_id'] ?>">
                                        <?= htmlspecialchars($department['department_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label class="block text-text-dark/70 text-sm mb-2">Status</label>
                            <select id="statusFilter"
                                class="w-full h-12 pl-4 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary">
                                <option value="">All Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <!-- Date Range Filters -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-text-dark/70 text-sm mb-2">Date From</label>
                            <input type="date" id="dateFrom"
                                class="w-full h-12 pl-4 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary">
                        </div>
                        <div>
                            <label class="block text-text-dark/70 text-sm mb-2">Date To</label>
                            <input type="date" id="dateTo"
                                class="w-full h-12 pl-4 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary">
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3">
                        <button id="resetFilters"
                            class="flex-1 h-12 bg-secondary text-white rounded-xl hover:bg-[#665C9E] transition-colors font-medium">
                            Reset Filters
                        </button>
                        <button id="exportUsers"
                            class="flex-1 h-12 bg-white text-secondary border border-secondary rounded-xl hover:bg-secondary/5 transition-colors font-medium">
                            Export Users
                        </button>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="dashboard-card">
                <div class="dashboard-card-header flex justify-between items-center">
                    <div class="text-text-dark/85 text-base font-medium">User Management</div>
                    <div class="text-sm text-secondary font-medium" id="showingInfo">
                        Total: <?= number_format($userStats['total_users'] ?? 0) ?> users
                    </div>
                </div>

                <!-- Table Container -->
                <div class="p-4">
                    <!-- Loading State -->
                    <div id="loadingState" class="hidden text-center py-8">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-secondary mb-4"></div>
                        <p class="text-gray-500">Loading users...</p>
                    </div>

                    <!-- Empty State -->
                    <div id="emptyState" class="hidden text-center py-8">
                        <i class="fas fa-users text-gray-300 text-4xl mb-4"></i>
                        <p class="text-gray-500">No users found</p>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table id="usersTable" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-[#E3DAEE]">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-text-dark/80 uppercase tracking-wider">
                                        ID
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-text-dark/80 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-text-dark/80 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-text-dark/80 uppercase tracking-wider">
                                        Role
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-text-dark/80 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-text-dark/80 uppercase tracking-wider">
                                        Created At
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-text-dark/80 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="usersTableBody">
                                <!-- Data akan di-load via AJAX -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="border-t border-gray-200 mt-4 pt-4">
                        <div class="flex justify-between items-center">
                            <div class="text-text-dark/70 text-sm">
                                <span id="paginationInfo">Page 1 of 1</span>
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
        </div>

        <!-- Right Column: User Details & Actions -->
        <div class="space-y-6">
            <!-- Add User Card -->
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <div class="text-text-dark/85 text-base font-medium">Quick Actions</div>
                </div>

                <div class="p-4 space-y-3">
                    <button id="addUserBtn"
                        class="w-full py-3 bg-secondary text-white rounded-xl hover:bg-[#665C9E] transition-colors font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-plus text-lg"></i>
                        Add New User
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

<!-- Styles -->
<style>
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

    /* Table styling */
    #usersTable {
        border-collapse: separate;
        border-spacing: 0;
    }

    #usersTable thead th {
        position: sticky;
        top: 0;
        z-index: 10;
        border-bottom: 2px solid #e5e7eb;
    }

    #usersTable tbody tr:hover {
        background-color: rgba(117, 110, 164, 0.05);
    }

    #usersTable tbody tr.selected {
        background-color: rgba(102, 92, 158, 0.1);
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

    /* User info items */
    .user-info-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #f1f1f1;
    }

    .user-info-item:last-child {
        border-bottom: none;
    }

    /* Loading animations */
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .animate-spin {
        animation: spin 1s linear infinite;
    }

    /* Modal animations */
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-slideInUp {
        animation: slideInUp 0.3s ease-out;
    }

    /* Fade in animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }

    /* Toast notification */
    .custom-toast {
        animation: slideInUp 0.3s ease-out, fadeIn 0.3s ease-out;
        transition: all 0.3s ease;
    }

    /* Error state for form fields */
    .border-red-500 {
        border-color: #EF4444 !important;
    }

    .bg-red-50 {
        background-color: #FEF2F2 !important;
    }
</style>

<!-- JavaScript -->
<script>
    class UserManager {
        constructor() {
            this.currentPage = 1;
            this.pageSize = 10;
            this.totalPages = 1;
            this.selectedUserId = null;
            this.filters = {};

            this.init();
        }

        init() {
            this.cacheElements();
            this.bindEvents();
            this.loadUsers();
        }

        cacheElements() {
            // Table elements
            this.usersTable = document.getElementById('usersTable');
            this.usersTableBody = document.getElementById('usersTableBody');
            this.loadingState = document.getElementById('loadingState');
            this.emptyState = document.getElementById('emptyState');
            this.showingInfo = document.getElementById('showingInfo');
            this.paginationInfo = document.getElementById('paginationInfo');
            this.pageNumbers = document.getElementById('pageNumbers');
            this.prevPage = document.getElementById('prevPage');
            this.nextPage = document.getElementById('nextPage');

            // Filter elements
            this.userSearch = document.getElementById('userSearch');
            this.roleFilter = document.getElementById('roleFilter');
            this.departmentFilter = document.getElementById('departmentFilter');
            this.statusFilter = document.getElementById('statusFilter');
            this.dateFrom = document.getElementById('dateFrom');
            this.dateTo = document.getElementById('dateTo');
            this.resetFilters = document.getElementById('resetFilters');
            this.exportUsers = document.getElementById('exportUsers');

            // Action elements
            this.addUserBtn = document.getElementById('addUserBtn');
            this.userDetails = document.getElementById('userDetails');
            this.userActions = document.getElementById('userActions');
        }

        bindEvents() {
            // Search and filter events
            this.userSearch.addEventListener('input', this.debounce(() => this.handleFilterChange(), 300));
            this.roleFilter.addEventListener('change', () => this.handleFilterChange());
            this.departmentFilter.addEventListener('change', () => this.handleFilterChange());
            this.statusFilter.addEventListener('change', () => this.handleFilterChange());
            this.dateFrom.addEventListener('change', () => this.handleFilterChange());
            this.dateTo.addEventListener('change', () => this.handleFilterChange());
            this.resetFilters.addEventListener('click', () => this.resetAllFilters());
            this.exportUsers.addEventListener('click', () => this.exportUsersData());

            // Pagination events
            this.prevPage.addEventListener('click', () => this.changePage(this.currentPage - 1));
            this.nextPage.addEventListener('click', () => this.changePage(this.currentPage + 1));

            // Action events
            this.addUserBtn.addEventListener('click', () => this.showAddUserModal());

            // Table row click events (delegated)
            this.usersTableBody.addEventListener('click', (e) => this.handleTableClick(e));
        }

async editUser(userId) {
    try {
        // Load user data first
        const response = await fetch(`<?= base_url("admin/users/ajax-details") ?>/${userId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await response.json();

        if (data.success && data.user) {
            this.showEditUserModal(data.user);
        } else {
            this.showToast('Failed to load user data for editing', 'error');
        }
    } catch (error) {
        console.error('Error loading user for edit:', error);
        this.showToast('Failed to load user data', 'error');
    }
}
showEditUserModal(user) {
    // Create modal HTML
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4';
    modal.innerHTML = `
        <div class="bg-white rounded-2xl w-full max-w-2xl animate-slideInUp max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-800">Edit User: ${user.full_name}</h3>
                    <button class="close-modal text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                <form id="editUserForm">
                    <input type="hidden" name="user_id" value="${user.user_id}">
                    
                    <div class="space-y-6">
                        <!-- Basic Information Section -->
                        <div>
                            <h4 class="text-lg font-medium text-text-dark mb-4">Basic Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-600 text-sm mb-2">Username *</label>
                                    <input type="text" name="username" value="${user.username}" required
                                           class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20"
                                           placeholder="johndoe">
                                    <p class="text-xs text-gray-500 mt-1">Must be unique</p>
                                </div>
                                
                                <div>
                                    <label class="block text-gray-600 text-sm mb-2">Full Name *</label>
                                    <input type="text" name="full_name" value="${user.full_name}" required
                                           class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20"
                                           placeholder="John Doe">
                                </div>
                                
                                <div>
                                    <label class="block text-gray-600 text-sm mb-2">Email *</label>
                                    <input type="email" name="email" value="${user.email}" required
                                           class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20"
                                           placeholder="john@example.com">
                                </div>
                                
                                <div>
                                    <label class="block text-gray-600 text-sm mb-2">Phone Number</label>
                                    <input type="text" name="phone_number" value="${user.phone_number || ''}"
                                           class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20"
                                           placeholder="+1234567890">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Password Section -->
                        <div>
                            <h4 class="text-lg font-medium text-text-dark mb-4">Password (Leave blank to keep current)</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-600 text-sm mb-2">New Password</label>
                                    <input type="password" name="password" minlength="6"
                                           class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20"
                                           placeholder="••••••••">
                                    <p class="text-xs text-gray-500 mt-1">Minimum 6 characters</p>
                                </div>
                                
                                <div>
                                    <label class="block text-gray-600 text-sm mb-2">Confirm Password</label>
                                    <input type="password" name="confirm_password"
                                           class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20"
                                           placeholder="••••••••">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Role & Department Section -->
                        <div>
                            <h4 class="text-lg font-medium text-text-dark mb-4">Role & Department</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-600 text-sm mb-2">Role *</label>
                                    <select name="role_id" required
                                            class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20">
                                        <option value="">Select Role</option>
                                        <?php foreach ($roles as $role): ?>
                                            <option value="<?= $role['role_id'] ?>" ${user.role_id == <?= $role['role_id'] ?> ? 'selected' : ''}>
                                                <?= htmlspecialchars($role['role_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-gray-600 text-sm mb-2">Department</label>
                                    <select name="department_id"
                                            class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20">
                                        <option value="">No Department</option>
                                        <?php foreach ($departments as $department): ?>
                                            <option value="<?= $department['department_id'] ?>" ${user.department_id == <?= $department['department_id'] ?> ? 'selected' : ''}>
                                                <?= htmlspecialchars($department['department_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Account Status -->
                        <div>
                            <h4 class="text-lg font-medium text-text-dark mb-4">Account Status</h4>
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" id="is_active_edit" name="is_active" value="1" ${user.is_active ? 'checked' : ''}
                                       class="w-4 h-4 text-secondary border-gray-300 rounded focus:ring-secondary">
                                <label for="is_active_edit" class="text-gray-700 text-sm">
                                    Account is active (user can login)
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="p-6 border-t border-gray-200 flex gap-3">
                <button class="close-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button id="updateUserBtn" class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#665C9E] transition-colors font-medium">
                    <i class="fas fa-save mr-2"></i>
                    Update User
                </button>
            </div>
        </div>
    `;

    document.body.appendChild(modal);

    // Add event listeners
    modal.querySelectorAll('.close-modal').forEach(btn => {
        btn.addEventListener('click', () => modal.remove());
    });

    modal.querySelector('#updateUserBtn').addEventListener('click', async () => {
        await this.updateUser(modal, user.user_id);
    });

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            modal.remove();
        }
    }, { once: true });

    // Prevent modal close when clicking inside modal
    modal.querySelector('.bg-white').addEventListener('click', (e) => {
        e.stopPropagation();
    });
}
async updateUser(modal, userId) {
    try {
        const form = modal.querySelector('#editUserForm');
        const formData = new FormData(form);
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const csrfHeader = document.querySelector('meta[name="csrf-header"]')?.getAttribute('content') || 'X-CSRF-TOKEN';
        
        // Validasi password jika diisi
        const password = formData.get('password');
        const confirmPassword = formData.get('confirm_password');
        
        if (password && password.length < 6) {
            this.showToast('Password must be at least 6 characters', 'error');
            return;
        }
        
        if (password && password !== confirmPassword) {
            this.showToast('Passwords do not match', 'error');
            return;
        }

        // Jika password tidak diisi, hapus dari formData
        if (!password) {
            formData.delete('password');
            formData.delete('confirm_password');
        }

        // Validasi required fields
        const requiredFields = ['username', 'full_name', 'email', 'role_id'];
        for (const field of requiredFields) {
            const value = formData.get(field);
            if (!value || value.trim() === '') {
                this.showToast(`${field.replace('_', ' ')} is required`, 'error');
                return;
            }
        }

        // Show loading state
        const updateBtn = modal.querySelector('#updateUserBtn');
        const originalText = updateBtn.innerHTML;
        updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';
        updateBtn.disabled = true;

        // Send request
        const headers = {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        };
        
        // Add CSRF token header
        if (csrfToken && csrfHeader) {
            headers[csrfHeader] = csrfToken;
        }
        
        const response = await fetch('<?= base_url("admin/users/update") ?>', {
            method: 'POST',
            headers: headers,
            body: formData
        });

        const data = await response.json();

        // Restore button state
        updateBtn.innerHTML = originalText;
        updateBtn.disabled = false;

        if (data.success) {
            this.showToast(data.message, 'success');
            
            // Close modal
            modal.remove();
            
            // Refresh user list
            this.loadUsers();
            
            // Reload user details if this user is selected
            if (this.selectedUserId === userId) {
                this.loadUserDetails(userId);
            }
            
        } else {
            let errorMessage = data.message || 'Failed to update user';
            
            // Show validation errors if available
            if (data.errors) {
                const errors = Object.values(data.errors).join(', ');
                errorMessage = errors;
            }
            
            this.showToast(errorMessage, 'error');
            
            // Highlight error fields
            if (data.errors) {
                Object.keys(data.errors).forEach(fieldName => {
                    const input = modal.querySelector(`[name="${fieldName}"]`);
                    if (input) {
                        input.classList.add('border-red-500', 'bg-red-50');
                        input.addEventListener('input', function() {
                            this.classList.remove('border-red-500', 'bg-red-50');
                        }, { once: true });
                    }
                });
            }
        }

    } catch (error) {
        console.error('Error updating user:', error);
        this.showToast('Error: ' + error.message, 'error');
        
        // Restore button state
        const updateBtn = modal.querySelector('#updateUserBtn');
        if (updateBtn) {
            updateBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Update User';
            updateBtn.disabled = false;
        }
    }
}

async loadUsers() {
    try {
        this.showLoading();

        // Create query parameters
        const params = new URLSearchParams();
        params.append('page', this.currentPage);
        params.append('limit', this.pageSize);
        
        if (this.filters.search) params.append('search', this.filters.search);
        if (this.filters.role_id) params.append('role_id', this.filters.role_id);
        if (this.filters.department_id) params.append('department_id', this.filters.department_id);
        if (this.filters.is_active !== undefined && this.filters.is_active !== '') params.append('is_active', this.filters.is_active);
        if (this.filters.date_from) params.append('date_from', this.filters.date_from);
        if (this.filters.date_to) params.append('date_to', this.filters.date_to);

        // GUNAKAN ENDPOINT AJAX BARU
        const response = await fetch(`<?= base_url("admin/users/ajax-list") ?>?${params.toString()}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        this.renderUsers(data);
        this.hideLoading();

    } catch (error) {
        console.error('Error loading users:', error);
        this.showError('Failed to load users. Please try again.');
    }
}

        renderUsers(data) {
            if (!data.users || data.users.length === 0) {
                this.usersTableBody.innerHTML = '';
                this.emptyState.classList.remove('hidden');
                this.usersTable.classList.add('hidden');
                this.showingInfo.textContent = `Total: 0 users`;
                return;
            }

            this.emptyState.classList.add('hidden');
            this.usersTable.classList.remove('hidden');

            let html = '';
            let counter = 1;

            data.users.forEach(user => {
                const statusClass = user.is_active ? 'status-active' : 'status-inactive';
                const statusText = user.is_active ? 'Active' : 'Inactive';
                const avatarInitials = this.getInitials(user.full_name);
                const roleClass = this.getRoleClass(user.role_name);

                html += `
                <tr class="${this.selectedUserId === user.user_id ? 'selected' : ''}" data-user-id="${user.user_id}">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        #${user.user_id}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-r from-secondary to-[#8A84C6] rounded-full flex items-center justify-center text-white font-bold">
                                ${avatarInitials}
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">${user.full_name}</div>
                                <div class="text-sm text-gray-500">@${user.username}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${user.email}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="${roleClass} role-badge">
                            ${user.role_name}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="${statusClass} status-badge">
                            ${statusText}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${new Date(user.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button class="btn-view-user text-secondary hover:text-[#665C9E] mr-3" 
                                data-user-id="${user.user_id}"
                                title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-edit-user text-blue-600 hover:text-blue-800 mr-3" 
                                data-user-id="${user.user_id}"
                                title="Edit User">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-reset-password text-yellow-600 hover:text-yellow-800 mr-3" 
                                data-user-id="${user.user_id}"
                                title="Reset Password">
                            <i class="fas fa-key"></i>
                        </button>
                        <button class="btn-delete-user text-red-600 hover:text-red-800" 
                                data-user-id="${user.user_id}"
                                title="Delete User">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                `;
                counter++;
            });

            this.usersTableBody.innerHTML = html;
            this.showingInfo.textContent = `Total: ${data.total} users`;
            this.updatePaginationInfo(data);

            // Re-bind action buttons
            this.bindActionButtons();
        }

async loadUserDetails(userId) {
    console.log('=== loadUserDetails START ===');
    console.log('User ID to load:', userId);
    
    try {
        // Gunakan endpoint AJAX baru yang sudah kita buat
        const url = `<?= base_url("admin/users/ajax-details") ?>/${userId}`;
        console.log('Request URL:', url);
        
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        console.log('Response status:', response.status);
        console.log('Response ok:', response.ok);
        
        // Cek jika response bukan JSON
        const contentType = response.headers.get('content-type');
        console.log('Content-Type:', contentType);
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error('Response error text:', errorText);
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        // Parse response
        const responseText = await response.text();
        console.log('Raw response:', responseText);
        
        let data;
        try {
            data = JSON.parse(responseText);
            console.log('Parsed JSON data:', data);
        } catch (jsonError) {
            console.error('JSON parse error:', jsonError);
            console.error('Response that failed to parse:', responseText);
            throw new Error('Invalid JSON response from server');
        }

        if (data.success && data.user) {
            console.log('User data received:', data.user);
            this.renderUserDetails(data.user);
            this.selectedUserId = userId;
            this.updateSelectedRow();
        } else {
            console.error('API returned error:', data.message);
            this.showToast(data.message || 'Failed to load user details', 'error');
        }

    } catch (error) {
        console.error('Error in loadUserDetails:', error);
        console.error('Error stack:', error.stack);
        this.showToast('Failed to load user details: ' + error.message, 'error');
    }
    
    console.log('=== loadUserDetails END ===');
}

        renderUserDetails(user) {
            const avatarInitials = this.getInitials(user.full_name);
            const statusClass = user.is_active ? 'status-active' : 'status-inactive';
            const statusText = user.is_active ? 'Active' : 'Inactive';
            
            const lastLogin = user.last_login 
                ? new Date(user.last_login).toLocaleDateString('en-US', { 
                    month: 'short', 
                    day: 'numeric', 
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                }) 
                : 'Never';

            const html = `
            <div class="animate-fadeIn">
                <div class="user-avatar">${avatarInitials}</div>
                <div class="text-center mb-6">
                    <h3 class="text-lg font-semibold text-text-dark">${user.full_name}</h3>
                    <p class="text-text-dark/60 text-sm">${user.email}</p>
                    <span class="inline-block mt-2 ${statusClass} status-badge">
                        ${statusText}
                    </span>
                </div>
                
                <div class="space-y-2">
                    <div class="user-info-item">
                        <span class="text-text-dark/70 text-sm">Username:</span>
                        <span class="text-text-dark font-medium">${user.username}</span>
                    </div>
                    <div class="user-info-item">
                        <span class="text-text-dark/70 text-sm">Role:</span>
                        <span class="text-text-dark font-medium">${user.role_name}</span>
                    </div>
                    <div class="user-info-item">
                        <span class="text-text-dark/70 text-sm">Department:</span>
                        <span class="text-text-dark font-medium">${user.department_name || 'N/A'}</span>
                    </div>
                    <div class="user-info-item">
                        <span class="text-text-dark/70 text-sm">Phone:</span>
                        <span class="text-text-dark font-medium">${user.phone_number || 'N/A'}</span>
                    </div>
                    <div class="user-info-item">
                        <span class="text-text-dark/70 text-sm">Joined:</span>
                        <span class="text-text-dark font-medium">
                            ${new Date(user.created_at).toLocaleDateString('en-US', { 
                                month: 'short', 
                                day: 'numeric', 
                                year: 'numeric' 
                            })}
                        </span>
                    </div>
                    <div class="user-info-item">
                        <span class="text-text-dark/70 text-sm">Last Login:</span>
                        <span class="text-text-dark font-medium">${lastLogin}</span>
                    </div>
                </div>
            </div>
            `;

            this.userDetails.innerHTML = html;
            this.updateUserActions(user);
        }

        updateUserActions(user) {
            const isActive = user.is_active;

            const html = `
            <div class="space-y-2 animate-fadeIn">
                <button class="edit-user-btn w-full py-3 bg-secondary text-white rounded-xl hover:bg-[#665C9E] transition-colors font-medium flex items-center justify-center gap-2" 
                        data-user-id="${user.user_id}">
                    <i class="fas fa-edit"></i>
                    Edit User
                </button>
                
                <button class="reset-password-btn w-full py-3 bg-white text-text-dark border border-text-dark/20 rounded-xl hover:bg-gray-50 transition-colors font-medium flex items-center justify-center gap-2" 
                        data-user-id="${user.user_id}">
                    <i class="fas fa-key"></i>
                    Reset Password
                </button>
                
                <button class="toggle-status-btn w-full py-3 ${isActive ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-green-50 text-green-600 border border-green-200'} rounded-xl hover:${isActive ? 'bg-red-100' : 'bg-green-100'} transition-colors font-medium flex items-center justify-center gap-2" 
                        data-user-id="${user.user_id}">
                    <i class="fas fa-power-off"></i>
                    ${isActive ? 'Deactivate Account' : 'Activate Account'}
                </button>
                
                <button class="delete-user-btn w-full py-3 bg-gray-50 text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-100 transition-colors font-medium flex items-center justify-center gap-2" 
                        data-user-id="${user.user_id}">
                    <i class="fas fa-trash"></i>
                    Delete User
                </button>
            </div>
            `;

            this.userActions.innerHTML = html;
            this.bindActionButtons();
        }

        handleFilterChange() {
            this.filters = {
                search: this.userSearch.value,
                role_id: this.roleFilter.value,
                department_id: this.departmentFilter.value,
                is_active: this.statusFilter.value,
                date_from: this.dateFrom.value,
                date_to: this.dateTo.value
            };

            this.currentPage = 1;
            this.loadUsers();
        }

        resetAllFilters() {
            this.userSearch.value = '';
            this.roleFilter.value = '';
            this.departmentFilter.value = '';
            this.statusFilter.value = '';
            this.dateFrom.value = '';
            this.dateTo.value = '';

            this.filters = {};
            this.currentPage = 1;
            this.loadUsers();

            this.showToast('Filters reset', 'info');
        }

        changePage(page) {
            if (page < 1 || page > this.totalPages) return;

            this.currentPage = page;
            this.loadUsers();

            // Scroll to top of table
            this.usersTable.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        updatePaginationInfo(data) {
            const start = (this.currentPage - 1) * this.pageSize + 1;
            const end = Math.min(start + this.pageSize - 1, data.total);
            const totalPages = Math.ceil(data.total / this.pageSize);

            this.showingInfo.textContent = `Showing ${start}-${end} of ${data.total} users`;
            this.paginationInfo.textContent = `Page ${this.currentPage} of ${totalPages}`;

            this.totalPages = totalPages;

            // Update page numbers
            this.renderPageNumbers();

            // Update prev/next buttons
            this.prevPage.disabled = this.currentPage === 1;
            this.nextPage.disabled = this.currentPage === this.totalPages;
        }

        renderPageNumbers() {
            let html = '';

            // Always show first page
            html += this.createPageButton(1);

            // Show ellipsis if needed
            if (this.currentPage > 3) {
                html += '<span class="px-2 text-gray-400">...</span>';
            }

            // Show pages around current page
            const startPage = Math.max(2, this.currentPage - 1);
            const endPage = Math.min(this.totalPages - 1, this.currentPage + 1);

            for (let i = startPage; i <= endPage; i++) {
                html += this.createPageButton(i);
            }

            // Show ellipsis if needed
            if (this.currentPage < this.totalPages - 2) {
                html += '<span class="px-2 text-gray-400">...</span>';
            }

            // Always show last page if not first
            if (this.totalPages > 1) {
                html += this.createPageButton(this.totalPages);
            }

            this.pageNumbers.innerHTML = html;

            // Add event listeners to page buttons
            this.pageNumbers.querySelectorAll('.page-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const page = parseInt(btn.dataset.page);
                    this.changePage(page);
                });
            });
        }

        createPageButton(page) {
            const isActive = page === this.currentPage;
            return `
            <button class="page-btn w-8 h-8 flex items-center justify-center rounded-lg font-medium ${isActive ? 'bg-secondary text-white' : 'bg-white/20 text-text-dark hover:bg-secondary/20'}" 
                    data-page="${page}">
                ${page}
            </button>
            `;
        }

        showLoading() {
            this.loadingState.classList.remove('hidden');
            this.usersTable.classList.add('hidden');
            this.emptyState.classList.add('hidden');
        }

        hideLoading() {
            this.loadingState.classList.add('hidden');
            this.usersTable.classList.remove('hidden');
        }

        showError(message) {
            this.emptyState.innerHTML = `
                <i class="fas fa-exclamation-triangle text-red-300 text-4xl mb-4"></i>
                <p class="text-red-500">${message}</p>
                <button class="mt-4 px-4 py-2 bg-secondary text-white rounded-lg hover:bg-[#665C9E] transition-colors" onclick="userManager.loadUsers()">
                    Retry
                </button>
            `;
            this.emptyState.classList.remove('hidden');
            this.usersTable.classList.add('hidden');
            this.loadingState.classList.add('hidden');
        }

        handleTableClick(event) {
            const row = event.target.closest('tr[data-user-id]');
            if (!row) return;

            const userId = parseInt(row.dataset.userId);

            // Check if click was on an action button
            const actionBtn = event.target.closest('.btn-view-user, .btn-edit-user, .btn-reset-password, .btn-delete-user');
            if (actionBtn) {
                event.stopPropagation();

                const action = actionBtn.classList.contains('btn-view-user') ? 'view' :
                    actionBtn.classList.contains('btn-edit-user') ? 'edit' :
                        actionBtn.classList.contains('btn-reset-password') ? 'reset' : 'delete';

                this.handleAction(action, userId);
                return;
            }

            // Otherwise, load user details
            this.loadUserDetails(userId);
        }

        handleAction(action, userId) {
            switch (action) {
                case 'view':
                    this.loadUserDetails(userId);
                    break;
                case 'edit':
                    this.editUser(userId);
                    break;
                case 'reset':
                    this.resetPassword(userId);
                    break;
                case 'delete':
                    this.deleteUser(userId);
                    break;
            }
        }

        bindActionButtons() {
            // Bind table action buttons
            this.usersTableBody.querySelectorAll('.btn-view-user').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const userId = parseInt(e.target.closest('button').dataset.userId);
                    this.loadUserDetails(userId);
                });
            });

    this.usersTableBody.querySelectorAll('.btn-edit-user').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const userId = parseInt(e.target.closest('button').dataset.userId);
            this.editUser(userId);
        });
    });

            this.usersTableBody.querySelectorAll('.btn-reset-password').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const userId = parseInt(e.target.closest('button').dataset.userId);
                    this.resetPassword(userId);
                });
            });

            this.usersTableBody.querySelectorAll('.btn-delete-user').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const userId = parseInt(e.target.closest('button').dataset.userId);
                    this.deleteUser(userId);
                });
            });

            // Bind detail action buttons
if (this.userActions) {
    this.userActions.querySelectorAll('.edit-user-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const userId = parseInt(e.target.closest('button').dataset.userId);
            this.editUser(userId);
        });
    });

                this.userActions.querySelectorAll('.reset-password-btn').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        const userId = parseInt(e.target.closest('button').dataset.userId);
                        this.resetPassword(userId);
                    });
                });

this.userActions.querySelectorAll('.toggle-status-btn').forEach(btn => {
    btn.addEventListener('click', async (e) => {
        const userId = parseInt(e.target.closest('button').dataset.userId);
        await this.toggleUserStatus(userId);
    });
});

                this.userActions.querySelectorAll('.delete-user-btn').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        const userId = parseInt(e.target.closest('button').dataset.userId);
                        this.deleteUser(userId);
                    });
                });
            }
        }

        showAddUserModal() {
            // Create modal HTML
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4';
            modal.innerHTML = `
                <div class="bg-white rounded-2xl w-full max-w-2xl animate-slideInUp max-h-[90vh] overflow-y-auto">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-gray-800">Add New User</h3>
                            <button class="close-modal text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <form id="addUserForm">
                            <!-- CSRF token will be added via fetch headers -->
                            
                            <div class="space-y-6">
                                <!-- Basic Information Section -->
                                <div>
                                    <h4 class="text-lg font-medium text-text-dark mb-4">Basic Information</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-gray-600 text-sm mb-2">Username *</label>
                                            <input type="text" name="username" required
                                                   class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20"
                                                   placeholder="johndoe">
                                            <p class="text-xs text-gray-500 mt-1">Must be unique</p>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-gray-600 text-sm mb-2">Full Name *</label>
                                            <input type="text" name="full_name" required
                                                   class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20"
                                                   placeholder="John Doe">
                                        </div>
                                        
                                        <div>
                                            <label class="block text-gray-600 text-sm mb-2">Email *</label>
                                            <input type="email" name="email" required
                                                   class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20"
                                                   placeholder="john@example.com">
                                        </div>
                                        
                                        <div>
                                            <label class="block text-gray-600 text-sm mb-2">Phone Number</label>
                                            <input type="text" name="phone_number"
                                                   class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20"
                                                   placeholder="+1234567890">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Password Section -->
                                <div>
                                    <h4 class="text-lg font-medium text-text-dark mb-4">Password</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-gray-600 text-sm mb-2">Password *</label>
                                            <input type="password" name="password" required minlength="6"
                                                   class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20"
                                                   placeholder="••••••••">
                                            <p class="text-xs text-gray-500 mt-1">Minimum 6 characters</p>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-gray-600 text-sm mb-2">Confirm Password *</label>
                                            <input type="password" name="confirm_password" required
                                                   class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20"
                                                   placeholder="••••••••">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Role & Department Section -->
                                <div>
                                    <h4 class="text-lg font-medium text-text-dark mb-4">Role & Department</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-gray-600 text-sm mb-2">Role *</label>
                                            <select name="role_id" required
                                                    class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20">
                                                <option value="">Select Role</option>
                                                <?php foreach ($roles as $role): ?>
                                                    <option value="<?= $role['role_id'] ?>"><?= htmlspecialchars($role['role_name']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-gray-600 text-sm mb-2">Department</label>
                                            <select name="department_id"
                                                    class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20">
                                                <option value="">No Department</option>
                                                <?php foreach ($departments as $department): ?>
                                                    <option value="<?= $department['department_id'] ?>"><?= htmlspecialchars($department['department_name']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Account Status -->
                                <div>
                                    <h4 class="text-lg font-medium text-text-dark mb-4">Account Status</h4>
                                    <div class="flex items-center space-x-3">
                                        <input type="checkbox" id="is_active" name="is_active" value="1" checked
                                               class="w-4 h-4 text-secondary border-gray-300 rounded focus:ring-secondary">
                                        <label for="is_active" class="text-gray-700 text-sm">
                                            Account is active (user can login)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div class="p-6 border-t border-gray-200 flex gap-3">
                        <button class="close-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button id="saveUserBtn" class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#665C9E] transition-colors font-medium">
                            <i class="fas fa-plus mr-2"></i>
                            Add User
                        </button>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);

            // Add event listeners
            modal.querySelectorAll('.close-modal').forEach(btn => {
                btn.addEventListener('click', () => modal.remove());
            });

            modal.querySelector('#saveUserBtn').addEventListener('click', async () => {
                await this.saveNewUser(modal);
            });

            // Close on ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    modal.remove();
                }
            }, { once: true });

            // Prevent modal close when clicking inside modal
            modal.querySelector('.bg-white').addEventListener('click', (e) => {
                e.stopPropagation();
            });
        }

        async saveNewUser(modal) {
    try {
        const form = modal.querySelector('#addUserForm');
        const formData = new FormData(form);
        
        // Handle is_active
        const isActiveCheckbox = modal.querySelector('#is_active');
        if (isActiveCheckbox) {
            formData.set('is_active', isActiveCheckbox.checked ? '1' : '0');
        }
        
        // Debug: Tampilkan semua form data
        console.log('=== FORM DATA DEBUG ===');
        const formDataObj = {};
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
            formDataObj[pair[0]] = pair[1];
        }
        console.log('Full FormData object:', formDataObj);
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const csrfHeader = document.querySelector('meta[name="csrf-header"]')?.getAttribute('content') || 'X-CSRF-TOKEN';
        
        console.log('CSRF Token:', csrfToken);
        console.log('CSRF Header:', csrfHeader);
        
        // Validasi client-side
        const password = formData.get('password');
        const confirmPassword = formData.get('confirm_password');
        
        if (password.length < 6) {
            this.showToast('Password must be at least 6 characters', 'error');
            return;
        }
        
        if (password !== confirmPassword) {
            this.showToast('Passwords do not match', 'error');
            return;
        }

        // Show loading state
        const saveBtn = modal.querySelector('#saveUserBtn');
        const originalText = saveBtn.innerHTML;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
        saveBtn.disabled = true;

        // Send request ke endpoint ADD langsung
        const headers = {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        };
        
        // Add CSRF token header
        if (csrfToken && csrfHeader) {
            headers[csrfHeader] = csrfToken;
        }
        
        console.log('Headers:', headers);
        console.log('Endpoint:', '<?= base_url("admin/users/add") ?>');
        
const response = await fetch('<?= base_url("admin/users/ajax-add") ?>', {
    method: 'POST',
    headers: headers,
    body: formData
});

        console.log('Response status:', response.status);
        console.log('Response headers:', [...response.headers.entries()]);
        
        const responseText = await response.text();
        console.log('Response text:', responseText);
        
        // Coba parse JSON
        let data;
        try {
            data = JSON.parse(responseText);
        } catch (e) {
            console.error('Failed to parse JSON:', e);
            data = { success: false, message: 'Invalid server response: ' + responseText };
        }

        // Restore button state
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;

        console.log('Parsed response:', data);

        if (data.success) {
            this.showToast(data.message, 'success');
            
            // Close modal
            modal.remove();
            
            // Refresh user list
            this.loadUsers();
            
            // Show new user in the list
            if (data.user_id) {
                setTimeout(() => {
                    this.loadUserDetails(data.user_id);
                }, 500);
            }
            
        } else {
            let errorMessage = data.message || 'Failed to add user';
            console.error('Error details:', data);
            
            this.showToast(errorMessage, 'error');
            
            // Highlight error fields
            if (data.errors) {
                Object.keys(data.errors).forEach(fieldName => {
                    const input = modal.querySelector(`[name="${fieldName}"]`);
                    if (input) {
                        input.classList.add('border-red-500', 'bg-red-50');
                        input.addEventListener('input', function() {
                            this.classList.remove('border-red-500', 'bg-red-50');
                        }, { once: true });
                    }
                });
            }
        }

    } catch (error) {
        console.error('Error saving user:', error);
        this.showToast('Error: ' + error.message, 'error');
        
        // Restore button state
        const saveBtn = modal.querySelector('#saveUserBtn');
        if (saveBtn) {
            saveBtn.innerHTML = '<i class="fas fa-plus mr-2"></i>Add User';
            saveBtn.disabled = false;
        }
    }
}
async toggleUserStatus(userId) {
    console.log('=== DEBUG toggleUserStatus START ===');
    console.log('User ID:', userId);
    
    try {
        if (!confirm('Are you sure you want to change this user\'s account status?')) {
            console.log('User cancelled');
            return;
        }

        // Show loading
        const toggleBtn = this.userActions.querySelector('.toggle-status-btn');
        const originalText = toggleBtn.innerHTML;
        const originalHtml = toggleBtn.innerHTML; // Simpan HTML asli
        toggleBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';
        toggleBtn.disabled = true;

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const csrfHeader = document.querySelector('meta[name="csrf-header"]')?.getAttribute('content') || 'X-CSRF-TOKEN';

        // Gunakan FormData untuk POST request
        const formData = new FormData();
        formData.append('status', 'toggle');
        formData.append('user_id', userId);

        // Gunakan endpoint yang sesuai
        const endpoint = `<?= base_url("admin/users/change-status") ?>/${userId}`;
        console.log('Endpoint:', endpoint);

        const headers = {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        };
        
        // Tambahkan CSRF token jika ada
        if (csrfToken && csrfHeader) {
            headers[csrfHeader] = csrfToken;
        }

        const response = await fetch(endpoint, {
            method: 'POST',
            headers: headers,
            body: formData
        });

        console.log('Response status:', response.status);

        // Cek jika response OK
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();
        console.log('Response data:', result);

        // Restore button state
        toggleBtn.innerHTML = originalText;
        toggleBtn.disabled = false;

        if (result.success) {
            console.log('Success! Message:', result.message);
            console.log('New status from server:', result.new_status);
            console.log('Status text:', result.status_text);
            
            this.showToast(result.message, 'success');
            
            // Update button text based on new status from server
            const newStatus = result.new_status;
            const statusText = newStatus ? 'Deactivate Account' : 'Activate Account';
            const btnClass = newStatus ? 
                'bg-red-50 text-red-600 border border-red-200 hover:bg-red-100' : 
                'bg-green-50 text-green-600 border border-green-200 hover:bg-green-100';
            
            // Update button appearance
            toggleBtn.innerHTML = `<i class="fas fa-power-off"></i> ${statusText}`;
            toggleBtn.className = `w-full py-3 ${btnClass} rounded-xl transition-colors font-medium flex items-center justify-center gap-2`;
            
            // Refresh user list
            await this.loadUsers();
            
            // Reload user details if this user is selected
            if (this.selectedUserId === userId) {
                await this.loadUserDetails(userId);
            }
            
            // Update status badge di table jika user sedang ditampilkan
            const userRow = this.usersTableBody.querySelector(`tr[data-user-id="${userId}"]`);
            if (userRow) {
                const statusCell = userRow.querySelector('.status-badge');
                if (statusCell) {
                    statusCell.textContent = newStatus ? 'Active' : 'Inactive';
                    statusCell.className = newStatus ? 'status-active status-badge' : 'status-inactive status-badge';
                }
            }
            
        } else {
            console.error('API error:', result.message);
            this.showToast(result.message, 'error');
            
            // Kembalikan ke state semula jika error
            toggleBtn.innerHTML = originalHtml;
            toggleBtn.disabled = false;
        }

    } catch (error) {
        console.error('Error in toggleUserStatus:', error);
        this.showToast('Failed to update user status: ' + error.message, 'error');
        
        // Restore button state
        const toggleBtn = this.userActions.querySelector('.toggle-status-btn');
        if (toggleBtn) {
            toggleBtn.innerHTML = originalText;
            toggleBtn.disabled = false;
        }
    }
    
    console.log('=== DEBUG toggleUserStatus END ===');
}

        async deleteUser(userId) {
            try {
                if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                    return;
                }

                const response = await fetch(`<?= base_url("admin/users/delete") ?>/${userId}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.showToast(data.message, 'success');
                    this.loadUsers();
                    
                    // Clear details if deleted user was selected
                    if (this.selectedUserId === userId) {
                        this.selectedUserId = null;
                        this.userDetails.innerHTML = `
                            <div class="flex flex-col items-center justify-center py-8 text-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-user text-gray-400 text-xl"></i>
                                </div>
                                <p class="text-text-dark/60 text-sm">Select a user to view details</p>
                            </div>
                        `;

                        this.userActions.innerHTML = `
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
                        `;
                    }
                } else {
                    this.showToast(data.message, 'error');
                }

            } catch (error) {
                console.error('Error deleting user:', error);
                this.showToast('Failed to delete user', 'error');
            }
        }

        exportUsersData() {
            // Build export URL with current filters
            const params = new URLSearchParams();

            if (this.filters.search) params.append('search', this.filters.search);
            if (this.filters.role_id) params.append('role_id', this.filters.role_id);
            if (this.filters.department_id) params.append('department_id', this.filters.department_id);
            if (this.filters.is_active !== undefined) params.append('is_active', this.filters.is_active);
            if (this.filters.date_from) params.append('date_from', this.filters.date_from);
            if (this.filters.date_to) params.append('date_to', this.filters.date_to);

            const url = `<?= base_url("admin/users/export") ?>?${params.toString()}`;
            window.location.href = url;
        }

        updateSelectedRow() {
            // Remove selected class from all rows
            this.usersTableBody.querySelectorAll('tr').forEach(row => {
                row.classList.remove('selected');
            });

            // Add selected class to current row
            const selectedRow = this.usersTableBody.querySelector(`tr[data-user-id="${this.selectedUserId}"]`);
            if (selectedRow) {
                selectedRow.classList.add('selected');
            }
        }

        getInitials(fullName) {
            const names = fullName.split(' ');
            let initials = '';

            for (const name of names) {
                if (initials.length < 2) {
                    initials += name.charAt(0).toUpperCase();
                }
            }

            return initials;
        }

        getRoleClass(roleName) {
            const roleClasses = {
                'Admin': 'role-admin',
                'Support': 'role-support',
                'Customer': 'role-customer',
                'Developer': 'role-developer',
                'Manager': 'role-manager'
            };

            return roleClasses[roleName] || 'role-customer';
        }

        showToast(message, type = 'info') {
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

        debounce(func, wait) {
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
    }

    // Initialize UserManager when DOM is loaded
    document.addEventListener('DOMContentLoaded', () => {
        window.userManager = new UserManager();
    });
</script>
<?= $this->endSection() ?>