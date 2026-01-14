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
                        Loading...
                    </div>
                </div>

                <!-- Table Container -->
                <div class="p-4">
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
                                        Created
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-text-dark/80 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="usersTableBody">
                                <!-- Data will be loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Loading State -->
                    <div id="loadingState" class="py-8 text-center">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-secondary"></div>
                        <p class="mt-2 text-gray-500">Loading users...</p>
                    </div>

                    <!-- Empty State -->
                    <div id="emptyState" class="hidden py-12 text-center">
                        <i class="fas fa-users text-gray-300 text-4xl mb-4"></i>
                        <p class="text-gray-500">No users found</p>
                        <p class="text-gray-400 text-sm mt-2">Try adjusting your filters</p>
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

                    <button id="bulkActionsBtn"
                        class="w-full py-3 bg-white text-text-dark border border-text-dark/20 rounded-xl hover:bg-gray-50 transition-colors font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-users"></i>
                        Bulk Actions
                    </button>

                    <button id="importUsersBtn"
                        class="w-full py-3 bg-white text-text-dark border border-text-dark/20 rounded-xl hover:bg-gray-50 transition-colors font-medium flex items-center justify-center gap-2">
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

<!-- Modals -->
<?= $this->include('Admin/modals/user_modal') ?>
<?= $this->include('Admin/modals/reset_password_modal') ?>

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

            this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            this.csrfHeader = document.querySelector('meta[name="csrf-header"]')?.getAttribute('content');

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
            this.bulkActionsBtn = document.getElementById('bulkActionsBtn');
            this.importUsersBtn = document.getElementById('importUsersBtn');
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
            this.bulkActionsBtn.addEventListener('click', () => this.showBulkActionsModal());
            this.importUsersBtn.addEventListener('click', () => this.showImportUsersModal());

            // Table row click events (delegated)
            this.usersTableBody.addEventListener('click', (e) => this.handleTableClick(e));
        }

        async loadUsers() {
            try {
                this.showLoading();

                const formData = new FormData();
                formData.append('draw', 1);
                formData.append('start', (this.currentPage - 1) * this.pageSize);
                formData.append('length', this.pageSize);
                formData.append('search[value]', this.filters.search || '');
                formData.append('role_id', this.filters.role_id || '');
                formData.append('department_id', this.filters.department_id || '');
                formData.append('is_active', this.filters.is_active || '');
                formData.append('date_from', this.filters.date_from || '');
                formData.append('date_to', this.filters.date_to || '');
                formData.append('order[0][column]', 5); // Created at column
                formData.append('order[0][dir]', 'desc');

                // Add CSRF token for CI4
                if (this.csrfToken) {
                    formData.append(this.csrfHeader || 'X-CSRF-TOKEN', this.csrfToken);
                }

                const response = await fetch('<?= base_url("admin/users") ?>', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.error) {
                    throw new Error(data.error);
                }

                this.renderUsers(data.data);
                this.updatePaginationInfo(data);
                this.hideLoading();

            } catch (error) {
                console.error('Error loading users:', error);
                this.showError('Failed to load users. Please try again.');
            }
        }

        renderUsers(users) {
            if (users.length === 0) {
                this.usersTableBody.innerHTML = '';
                this.emptyState.classList.remove('hidden');
                this.usersTable.classList.add('hidden');
                return;
            }

            this.emptyState.classList.add('hidden');
            this.usersTable.classList.remove('hidden');

            let html = '';

            users.forEach(user => {
                const statusClass = user.is_active ? 'status-active' : 'status-inactive';
                const statusText = user.is_active ? 'Active' : 'Inactive';

                html += `
                <tr class="${this.selectedUserId === user.user_id ? 'selected' : ''}" data-user-id="${user.user_id}">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        #${user.user_id}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-r from-secondary to-[#8A84C6] rounded-full flex items-center justify-center text-white font-bold">
                                ${this.getInitials(user.full_name)}
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">${user.full_name}</div>
                                <div class="text-sm text-gray-500">${user.username}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${user.email}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="${this.getRoleClass(user.role_name)} role-badge">
                            ${user.role_name}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="${statusClass} status-badge">
                            ${statusText}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${user.created_at}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        ${user.actions}
                    </td>
                </tr>
                `;
            });

            this.usersTableBody.innerHTML = html;

            // Re-bind action buttons
            this.bindActionButtons();
        }

        async loadUserDetails(userId) {
            try {
                const formData = new FormData();
                formData.append('user_id', userId);

                // Add CSRF token for CI4
                if (this.csrfToken) {
                    formData.append(this.csrfHeader || 'X-CSRF-TOKEN', this.csrfToken);
                }

                const response = await fetch(`<?= base_url("admin/users/details") ?>`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    this.renderUserDetails(data.user);
                    this.selectedUserId = userId;
                    this.updateSelectedRow();
                } else {
                    this.showToast(data.message, 'error');
                }

            } catch (error) {
                console.error('Error loading user details:', error);
                this.showToast('Failed to load user details', 'error');
            }
        }

        renderUserDetails(user) {
            const html = `
            <div class="animate-fadeIn">
                <div class="user-avatar">${user.avatar_initials}</div>
                <div class="text-center mb-6">
                    <h3 class="text-lg font-semibold text-text-dark">${user.full_name}</h3>
                    <p class="text-text-dark/60 text-sm">${user.email}</p>
                    <span class="inline-block mt-2 ${user.is_active ? 'status-active' : 'status-inactive'} status-badge">
                        ${user.is_active ? 'Active' : 'Inactive'}
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
                        <span class="text-text-dark font-medium">${user.created_at}</span>
                    </div>
                    <div class="user-info-item">
                        <span class="text-text-dark/70 text-sm">Last Login:</span>
                        <span class="text-text-dark font-medium">${user.last_login}</span>
                    </div>
                    <div class="user-info-item">
                        <span class="text-text-dark/70 text-sm">Total Tickets:</span>
                        <span class="text-text-dark font-medium">${user.total_tickets}</span>
                    </div>
                    <div class="user-info-item">
                        <span class="text-text-dark/70 text-sm">Assigned Projects:</span>
                        <span class="text-text-dark font-medium">${user.total_projects}</span>
                    </div>
                    ${user.projects.length > 0 ? `
                    <div class="user-info-item">
                        <span class="text-text-dark/70 text-sm">Projects:</span>
                        <span class="text-text-dark font-medium text-xs">${user.projects.join(', ')}</span>
                    </div>
                    ` : ''}
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
                <button class="edit-user-btn w-full py-3 bg-secondary text-white rounded-xl hover:bg-[#665C9E] transition-colors font-medium flex items-center justify-center gap-2" data-user-id="${user.user_id}">
                    <i class="fas fa-edit"></i>
                    Edit User
                </button>
                
                <button class="reset-password-btn w-full py-3 bg-white text-text-dark border border-text-dark/20 rounded-xl hover:bg-gray-50 transition-colors font-medium flex items-center justify-center gap-2" data-user-id="${user.user_id}">
                    <i class="fas fa-key"></i>
                    Reset Password
                </button>
                
                <button class="toggle-status-btn w-full py-3 ${isActive ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-green-50 text-green-600 border border-green-200'} rounded-xl hover:${isActive ? 'bg-red-100' : 'bg-green-100'} transition-colors font-medium flex items-center justify-center gap-2" data-user-id="${user.user_id}">
                    <i class="fas fa-power-off"></i>
                    ${isActive ? 'Deactivate Account' : 'Activate Account'}
                </button>
                
                <button class="delete-user-btn w-full py-3 bg-gray-50 text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-100 transition-colors font-medium flex items-center justify-center gap-2" data-user-id="${user.user_id}">
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
            const end = Math.min(start + this.pageSize - 1, data.recordsFiltered);

            this.showingInfo.textContent = `Showing ${start}-${end} of ${data.recordsFiltered} users`;

            this.totalPages = Math.ceil(data.recordsFiltered / this.pageSize);
            this.paginationInfo.textContent = `Page ${this.currentPage} of ${this.totalPages}`;

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
            <button class="page-btn w-8 h-8 flex items-center justify-center rounded-lg font-medium ${isActive ? 'bg-secondary text-white' : 'bg-white/20 text-text-dark hover:bg-secondary/20'}" data-page="${page}">
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

        showAddUserModal() {
            // Implementation for add user modal
            this.showToast('Add user feature coming soon!', 'info');
        }

        showBulkActionsModal() {
            this.showToast('Bulk actions feature coming soon!', 'info');
        }

        showImportUsersModal() {
            this.showToast('Import users feature coming soon!', 'info');
        }

        async editUser(userId) {
            try {
                // Load user data for editing
                const formData = new FormData();
                formData.append('user_id', userId);

                if (this.csrfToken) {
                    formData.append(this.csrfHeader || 'X-CSRF-TOKEN', this.csrfToken);
                }

                const response = await fetch(`<?= base_url("admin/users/details") ?>`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    // Show edit modal with user data
                    this.showEditModal(data.user);
                } else {
                    this.showToast(data.message, 'error');
                }

            } catch (error) {
                console.error('Error loading user for edit:', error);
                this.showToast('Failed to load user data', 'error');
            }
        }

        showEditModal(user) {
            // Create and show edit modal
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
                        <form id="editUserForm">
                            <input type="hidden" name="user_id" value="${user.user_id}">
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-gray-600 text-sm mb-2">Full Name</label>
                                    <input type="text" name="full_name" value="${user.full_name}" 
                                           class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                                </div>
                                
                                <div>
                                    <label class="block text-gray-600 text-sm mb-2">Email</label>
                                    <input type="email" name="email" value="${user.email}" 
                                           class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                                </div>
                                
                                <div>
                                    <label class="block text-gray-600 text-sm mb-2">Status</label>
                                    <select name="is_active" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                                        <option value="1" ${user.is_active ? 'selected' : ''}>Active</option>
                                        <option value="0" ${!user.is_active ? 'selected' : ''}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div class="p-6 border-t border-gray-200 flex gap-3">
                        <button class="close-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button id="saveEdit" class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors">
                            Save Changes
                        </button>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);

            // Add event listeners
            modal.querySelectorAll('.close-modal').forEach(btn => {
                btn.addEventListener('click', () => modal.remove());
            });

            modal.querySelector('#saveEdit').addEventListener('click', async () => {
                await this.saveEditUser(user.user_id, modal);
            });
        }

        async saveEditUser(userId, modal) {
            try {
                const formData = new FormData(modal.querySelector('#editUserForm'));

                if (this.csrfToken) {
                    formData.append(this.csrfHeader || 'X-CSRF-TOKEN', this.csrfToken);
                }

                const response = await fetch(`<?= base_url("admin/users/edit") ?>/${userId}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    this.showToast(data.message, 'success');
                    modal.remove();
                    this.loadUsers();
                    if (this.selectedUserId === userId) {
                        this.loadUserDetails(userId);
                    }
                } else {
                    this.showToast(data.message || 'Failed to update user', 'error');
                }

            } catch (error) {
                console.error('Error saving user:', error);
                this.showToast('Failed to save changes', 'error');
            }
        }

        async resetPassword(userId) {
            // Show reset password modal
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
                        <form id="resetPasswordForm">
                            <input type="hidden" name="user_id" value="${userId}">
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-gray-600 text-sm mb-2">New Password</label>
                                    <input type="password" name="new_password" 
                                           class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary" required>
                                </div>
                                
                                <div>
                                    <label class="block text-gray-600 text-sm mb-2">Confirm Password</label>
                                    <input type="password" name="confirm_password" 
                                           class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary" required>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div class="p-6 border-t border-gray-200 flex gap-3">
                        <button class="close-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button id="confirmReset" class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2] transition-colors">
                            Reset Password
                        </button>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);

            // Add event listeners
            modal.querySelectorAll('.close-modal').forEach(btn => {
                btn.addEventListener('click', () => modal.remove());
            });

            modal.querySelector('#confirmReset').addEventListener('click', async () => {
                await this.confirmResetPassword(userId, modal);
            });
        }

        async confirmResetPassword(userId, modal) {
            try {
                const formData = new FormData(modal.querySelector('#resetPasswordForm'));

                if (this.csrfToken) {
                    formData.append(this.csrfHeader || 'X-CSRF-TOKEN', this.csrfToken);
                }

                const response = await fetch(`<?= base_url("admin/users/reset-password") ?>/${userId}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    this.showToast(data.message, 'success');
                    modal.remove();
                } else {
                    this.showToast(data.message || 'Failed to reset password', 'error');
                }

            } catch (error) {
                console.error('Error resetting password:', error);
                this.showToast('Failed to reset password', 'error');
            }
        }

        async toggleUserStatus(userId) {
            try {
                // Get current user details first
                const formData = new FormData();
                formData.append('user_id', userId);

                if (this.csrfToken) {
                    formData.append(this.csrfHeader || 'X-CSRF-TOKEN', this.csrfToken);
                }

                const detailsResponse = await fetch(`<?= base_url("admin/users/details") ?>`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const detailsData = await detailsResponse.json();

                if (!detailsData.success) {
                    throw new Error('Failed to get user details');
                }

                const currentStatus = detailsData.user.is_active;
                const newStatus = !currentStatus;
                const action = newStatus ? 'activate' : 'deactivate';

                if (!confirm(`Are you sure you want to ${action} this user?`)) {
                    return;
                }

                // Prepare status update form
                const updateFormData = new FormData();
                updateFormData.append('status', newStatus ? 'active' : 'inactive');

                if (this.csrfToken) {
                    updateFormData.append(this.csrfHeader || 'X-CSRF-TOKEN', this.csrfToken);
                }

                const updateResponse = await fetch(`<?= base_url("admin/users/change-status") ?>/${userId}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: updateFormData
                });

                const updateData = await updateResponse.json();

                if (updateData.success) {
                    this.showToast(`User ${action}d successfully`, 'success');
                    this.loadUsers();

                    // Reload details if this user is selected
                    if (this.selectedUserId === userId) {
                        this.loadUserDetails(userId);
                    }
                } else {
                    this.showToast(updateData.message, 'error');
                }

            } catch (error) {
                console.error('Error toggling user status:', error);
                this.showToast('Failed to update user status', 'error');
            }
        }

        async deleteUser(userId) {
            if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                return;
            }

            try {
                const formData = new FormData();
                formData.append('user_id', userId);

                if (this.csrfToken) {
                    formData.append(this.csrfHeader || 'X-CSRF-TOKEN', this.csrfToken);
                }

                const response = await fetch(`<?= base_url("admin/users/delete") ?>/${userId}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    this.showToast('User deleted successfully', 'success');
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