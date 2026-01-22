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
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-[#665C9E]">
                            <i class="fas fa-search"></i>
                        </div>
                        <input
                            type="text"
                            placeholder="Search users by name, email, role..."
                            id="userSearch"
                            class="w-full h-12 pl-12 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary transition-all duration-300 placeholder:text-gray-400">
                        <!-- Clear button -->
                        <button
                            id="clearSearch"
                            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 hidden"
                            title="Clear search">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Filter Options -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Role Filter -->
                        <div class="relative">
                            <label class="block text-text-dark/70 text-sm mb-2 font-medium">Role</label>
                            <select id="roleFilter"
                                class="w-full h-12 pl-4 pr-10 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary transition-all duration-300 appearance-none cursor-pointer">
                                <option value="">All Roles</option>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role['role_id'] ?>"><?= htmlspecialchars($role['role_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="absolute right-3 top-9 transform -translate-y-1/2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                            </div>
                        </div>

                        <!-- Department Filter -->
                        <div class="relative">
                            <label class="block text-text-dark/70 text-sm mb-2 font-medium">Department</label>
                            <select id="departmentFilter"
                                class="w-full h-12 pl-4 pr-10 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary transition-all duration-300 appearance-none cursor-pointer">
                                <option value="">All Departments</option>
                                <?php foreach ($departments as $department): ?>
                                    <option value="<?= $department['department_id'] ?>">
                                        <?= htmlspecialchars($department['department_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="absolute right-3 top-9 transform -translate-y-1/2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div class="relative">
                            <label class="block text-text-dark/70 text-sm mb-2 font-medium">Status</label>
                            <select id="statusFilter"
                                class="w-full h-12 pl-4 pr-10 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary transition-all duration-300 appearance-none cursor-pointer">
                                <option value="">All Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <div class="absolute right-3 top-9 transform -translate-y-1/2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Date Range Filters -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="relative">
                            <label class="block text-text-dark/70 text-sm mb-2 font-medium">Date From</label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-[#665C9E]">
                                    <i class="fas fa-calendar-alt text-sm"></i>
                                </div>
                                <input type="date" id="dateFrom"
                                    class="w-full h-12 pl-12 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary transition-all duration-300 cursor-pointer">
                            </div>
                        </div>
                        <div class="relative">
                            <label class="block text-text-dark/70 text-sm mb-2 font-medium">Date To</label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-[#665C9E]">
                                    <i class="fas fa-calendar-alt text-sm"></i>
                                </div>
                                <input type="date" id="dateTo"
                                    class="w-full h-12 pl-12 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary transition-all duration-300 cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="pt-2">
                        <button id="resetFilters"
                            class="w-full h-12 bg-secondary text-white rounded-xl hover:bg-[#665C9E] transition-colors font-medium">
                            Reset Filters
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
                                        No.
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
                        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                            <div class="text-text-dark/70 text-sm">
                                <span id="paginationInfo">Page 1 of 1</span>
                            </div>

                            <div class="flex items-center gap-1">


                                <div id="pageNumbers" class="flex items-center gap-1">
                                    <!-- Page numbers will be populated here -->
                                </div>


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

    /* Tambahkan di bagian <style> */
    .pagination-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .pagination-info {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .pagination-buttons {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .page-btn {
        min-width: 2rem;
        height: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s;
    }

    .page-btn:hover {
        transform: translateY(-1px);
    }

    .page-btn.active {
        box-shadow: 0 2px 4px rgba(102, 92, 158, 0.2);
    }

    .pagination-ellipsis {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        color: #9ca3af;
    }

    /* Responsive pagination */
    @media (max-width: 768px) {
        .pagination-container {
            flex-direction: column;
            gap: 0.75rem;
        }

        .pagination-info {
            text-align: center;
            width: 100%;
        }

        .pagination-buttons {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    class UserManager {
        constructor() {
            this.currentPage = 1;
            this.pageSize = 5; // Ubah menjadi 5 user per halaman
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
            this.$usersTable = $('#usersTable');
            this.$usersTableBody = $('#usersTableBody');
            this.$emptyState = $('#emptyState');
            this.$showingInfo = $('#showingInfo');
            this.$paginationInfo = $('#paginationInfo');
            this.$pageNumbers = $('#pageNumbers');
            this.$prevPage = $('#prevPage');
            this.$nextPage = $('#nextPage');

            // Filter elements
            this.$userSearch = $('#userSearch');
            this.$clearSearch = $('#clearSearch');
            this.$roleFilter = $('#roleFilter');
            this.$departmentFilter = $('#departmentFilter');
            this.$statusFilter = $('#statusFilter');
            this.$dateFrom = $('#dateFrom');
            this.$dateTo = $('#dateTo');
            this.$resetFilters = $('#resetFilters');

            // Action elements
            this.$addUserBtn = $('#addUserBtn');
            this.$userDetails = $('#userDetails');
            this.$userActions = $('#userActions');
        }

        bindEvents() {
            // Search and filter events
            this.$userSearch.on('input', this.debounce(() => this.handleFilterChange(), 300));
            this.$roleFilter.on('change', () => this.handleFilterChange());
            this.$departmentFilter.on('change', () => this.handleFilterChange());
            this.$statusFilter.on('change', () => this.handleFilterChange());
            this.$dateFrom.on('change', () => this.handleFilterChange());
            this.$dateTo.on('change', () => this.handleFilterChange());
            this.$resetFilters.on('click', () => this.resetAllFilters());

            // Pagination events
            this.$prevPage.on('click', () => this.changePage(this.currentPage - 1));
            this.$nextPage.on('click', () => this.changePage(this.currentPage + 1));

            // Action events
            this.$addUserBtn.on('click', () => this.showAddUserModal());

            // Table row click events (delegated)
            this.$usersTableBody.on('click', (e) => this.handleTableClick(e));
        }

        // Tambah method untuk handle search input dan clear
        handleSearchInput() {
            const searchValue = this.$userSearch.val();

            // Show/hide clear button
            if (searchValue.length > 0) {
                this.$clearSearch.removeClass('hidden');
            } else {
                this.$clearSearch.addClass('hidden');
            }

            // Trigger filter change
            this.handleFilterChange();
        }

        clearSearch() {
            this.$userSearch.val('');
            this.$clearSearch.addClass('hidden');
            this.handleFilterChange();
            this.showToast('Search cleared', 'info');
        }

        async editUser(userId) {
            try {
                const response = await $.ajax({
                    url: `<?= base_url("admin/users/ajax-details") ?>/${userId}`,
                    method: 'GET',
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.success && response.user) {
                    this.showEditUserModal(response.user);
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
            const modalHTML = `
                <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4" id="editUserModal">
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
                </div>
            `;

            $('body').append(modalHTML);
            const $modal = $('#editUserModal');

            // Set select values
            $modal.find('select[name="role_id"]').val(user.role_id);
            $modal.find('select[name="department_id"]').val(user.department_id || '');

            // Add event listeners
            $modal.find('.close-modal').on('click', () => $modal.remove());

            $modal.find('#updateUserBtn').on('click', async () => {
                await this.updateUser($modal, user.user_id);
            });

            // Close on ESC key
            $(document).on('keydown.editUser', (e) => {
                if (e.key === 'Escape') {
                    $modal.remove();
                    $(document).off('keydown.editUser');
                }
            });

            // Prevent modal close when clicking inside modal
            $modal.find('.bg-white').on('click', (e) => {
                e.stopPropagation();
            });
        }

        async updateUser($modal, userId) {
            try {
                const $form = $modal.find('#editUserForm');
                const formData = $form.serializeArray();

                // Convert to object for validation
                const formDataObj = {};
                formData.forEach(item => {
                    formDataObj[item.name] = item.value;
                });

                // Validasi password jika diisi
                const password = formDataObj.password;
                const confirmPassword = formDataObj.confirm_password;

                if (password && password.length < 6) {
                    this.showToast('Password must be at least 6 characters', 'error');
                    return;
                }

                if (password && password !== confirmPassword) {
                    this.showToast('Passwords do not match', 'error');
                    return;
                }

                // Validasi required fields
                const requiredFields = ['username', 'full_name', 'email', 'role_id'];
                for (const field of requiredFields) {
                    const value = formDataObj[field];
                    if (!value || value.trim() === '') {
                        this.showToast(`${field.replace('_', ' ')} is required`, 'error');
                        return;
                    }
                }

                // Show loading state
                const $updateBtn = $modal.find('#updateUserBtn');
                const originalText = $updateBtn.html();
                $updateBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Updating...');
                $updateBtn.prop('disabled', true);

                // Send request
                const response = await $.ajax({
                    url: '<?= base_url("admin/users/update") ?>',
                    method: 'POST',
                    data: $form.serialize(),
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                // Restore button state
                $updateBtn.html(originalText);
                $updateBtn.prop('disabled', false);

                if (response.success) {
                    this.showToast(response.message, 'success');

                    // Close modal
                    $modal.remove();

                    // Refresh user list
                    this.loadUsers();

                    // Reload user details if this user is selected
                    if (this.selectedUserId === userId) {
                        this.loadUserDetails(userId);
                    }

                } else {
                    let errorMessage = response.message || 'Failed to update user';

                    // Show validation errors if available
                    if (response.errors) {
                        const errors = Object.values(response.errors).join(', ');
                        errorMessage = errors;
                    }

                    this.showToast(errorMessage, 'error');

                    // Highlight error fields
                    if (response.errors) {
                        Object.keys(response.errors).forEach(fieldName => {
                            const $input = $modal.find(`[name="${fieldName}"]`);
                            if ($input.length) {
                                $input.addClass('border-red-500 bg-red-50');
                                $input.one('input', function() {
                                    $(this).removeClass('border-red-500 bg-red-50');
                                });
                            }
                        });
                    }
                }

            } catch (error) {
                console.error('Error updating user:', error);
                this.showToast('Error: ' + error.responseJSON?.message || error.statusText || 'Update failed', 'error');

                // Restore button state
                const $updateBtn = $modal.find('#updateUserBtn');
                if ($updateBtn.length) {
                    $updateBtn.html('<i class="fas fa-save mr-2"></i>Update User');
                    $updateBtn.prop('disabled', false);
                }
            }
        }

        async loadUsers() {
            try {
                // Create query parameters
                const params = {
                    page: this.currentPage,
                    limit: this.pageSize,
                    ...this.filters
                };

                // Remove undefined values
                Object.keys(params).forEach(key => params[key] === undefined && delete params[key]);

                const response = await $.ajax({
                    url: `<?= base_url("admin/users/ajax-list") ?>`,
                    method: 'GET',
                    data: params,
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                this.renderUsers(response);

            } catch (error) {
                console.error('Error loading users:', error);
                this.showError('Failed to load users. Please try again.');
            }
        }

        // Update bagian renderUsers() pada class UserManager di manage_users.php

        renderUsers(data) {
            if (!data.users || data.users.length === 0) {
                this.$usersTableBody.empty();
                this.$emptyState.removeClass('hidden');
                this.$usersTable.addClass('hidden');
                this.$showingInfo.text(`Total: 0 users`);
                return;
            }

            this.$emptyState.addClass('hidden');
            this.$usersTable.removeClass('hidden');

            let html = '';

            // Gunakan row_number dari server untuk nomor urut yang konsisten
            data.users.forEach(user => {
                const statusClass = user.is_active ? 'status-active' : 'status-inactive';
                const statusText = user.is_active ? 'Active' : 'Inactive';
                const avatarInitials = this.getInitials(user.full_name);
                const roleClass = this.getRoleClass(user.role_name);

                // === ID MENJADI NOMOR URUT DARI SERVER ===
                const rowNumber = user.row_number || user.user_id;

                html += `
        <tr class="${this.selectedUserId === user.user_id ? 'selected' : ''}" data-user-id="${user.user_id}">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                ${rowNumber}
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
            });

            this.$usersTableBody.html(html);
            this.$showingInfo.text(`Total: ${data.total} users`);
            this.updatePaginationInfo(data);

            // Re-bind action buttons
            this.bindActionButtons();
        }

        async loadUserDetails(userId) {
            console.log('=== loadUserDetails START ===');
            console.log('User ID to load:', userId);

            try {
                const response = await $.ajax({
                    url: `<?= base_url("admin/users/ajax-details") ?>/${userId}`,
                    method: 'GET',
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                console.log('Response data:', response);

                if (response.success && response.user) {
                    console.log('User data received:', response.user);
                    this.renderUserDetails(response.user);
                    this.selectedUserId = userId;
                    this.updateSelectedRow();
                } else {
                    console.error('API returned error:', response.message);
                    this.showToast(response.message || 'Failed to load user details', 'error');
                }

            } catch (error) {
                console.error('Error in loadUserDetails:', error);
                console.error('Error response:', error.responseText);
                this.showToast('Failed to load user details: ' + (error.responseJSON?.message || error.statusText), 'error');
            }

            console.log('=== loadUserDetails END ===');
        }

        renderUserDetails(user) {
            const avatarInitials = this.getInitials(user.full_name);
            const statusClass = user.is_active ? 'status-active' : 'status-inactive';
            const statusText = user.is_active ? 'Active' : 'Inactive';

            const lastLogin = user.last_login ?
                new Date(user.last_login).toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                }) :
                'Never';

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

            this.$userDetails.html(html);
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

            this.$userActions.html(html);
            this.bindActionButtons();
        }

        handleFilterChange() {
            this.filters = {
                search: this.$userSearch.val(),
                role_id: this.$roleFilter.val(),
                department_id: this.$departmentFilter.val(),
                is_active: this.$statusFilter.val(),
                date_from: this.$dateFrom.val(),
                date_to: this.$dateTo.val()
            };

            this.currentPage = 1;
            this.loadUsers();
        }

        resetAllFilters() {
            this.$userSearch.val('');
            this.$roleFilter.val('');
            this.$departmentFilter.val('');
            this.$statusFilter.val('');
            this.$dateFrom.val('');
            this.$dateTo.val('');

            this.filters = {};
            this.currentPage = 1;
            this.loadUsers();

            this.showToast('Filters reset', 'info');
        }

        changePage(page) {
            if (page < 1 || page > this.totalPages) return;

            this.currentPage = page;
            this.loadUsers();

            // Scroll to top of table dengan animasi smooth
            $('html, body').animate({
                scrollTop: this.$usersTable.offset().top - 100
            }, 300);

            // Update URL tanpa reload (optional)
            this.updateURL();
        }

        updateURL() {
            const url = new URL(window.location);
            const params = new URLSearchParams(url.search);

            // Update atau tambah parameter page
            params.set('page', this.currentPage);

            // Update URL tanpa reload page
            const newUrl = `${url.pathname}?${params.toString()}`;
            window.history.replaceState({}, '', newUrl);
        }

        updatePaginationInfo(data) {
            const start = Math.max(1, (this.currentPage - 1) * this.pageSize + 1);
            const end = Math.min(start + data.users.length - 1, data.total);
            const totalPages = Math.ceil(data.total / this.pageSize);

            // Update showing info
            if (data.total > 0) {
                this.$showingInfo.text(`Showing ${start}-${end} of ${data.total} users`);
            } else {
                this.$showingInfo.text(`Total: 0 users`);
            }

            this.$paginationInfo.text(`Page ${this.currentPage} of ${totalPages}`);

            this.totalPages = totalPages;

            // Update page numbers
            this.renderPageNumbers();

            // Update prev/next buttons (simple)
            this.$prevPage.prop('disabled', this.currentPage === 1);
            this.$nextPage.prop('disabled', this.currentPage === this.totalPages || this.totalPages === 0);

            // Update extended buttons di renderPageNumbers sudah handle
        }

        renderPageNumbers() {
            let html = '';
            const totalPages = this.totalPages;
            const currentPage = this.currentPage;

            // Jumlah maksimum tombol yang ditampilkan
            const maxVisibleButtons = 2;

            // Hitung range tombol yang akan ditampilkan
            let startPage = Math.max(1, currentPage - Math.floor(maxVisibleButtons / 2));
            let endPage = Math.min(totalPages, startPage + maxVisibleButtons - 1);

            // Sesuaikan startPage jika endPage mencapai totalPages
            if (endPage - startPage + 1 < maxVisibleButtons) {
                startPage = Math.max(1, endPage - maxVisibleButtons + 1);
            }

            // Tombol Previous (khusus untuk mobile/desktop berbeda)
            html += `
        <button class="prev-btn-extended w-10 h-8 flex items-center justify-center rounded-lg bg-white/20 text-text-dark hover:bg-secondary/20 disabled:opacity-50 disabled:cursor-not-allowed transition-colors ${currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''}"
                ${currentPage === 1 ? 'disabled' : ''}
                data-page="${currentPage - 1}"
                title="Previous Page">
            <i class="fas fa-chevron-left text-xs"></i>
        </button>
    `;

            // Tombol halaman pertama
            if (startPage > 1) {
                html += this.createPageButton(1);
                if (startPage > 2) {
                    html += '<span class="px-2 text-gray-400">...</span>';
                }
            }

            // Tombol halaman tengah
            for (let i = startPage; i <= endPage; i++) {
                html += this.createPageButton(i);
            }

            // Tombol halaman terakhir
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    html += '<span class="px-2 text-gray-400">...</span>';
                }
                if (totalPages > 1) {
                    html += this.createPageButton(totalPages);
                }
            }

            // Tombol Next
            html += `
        <button class="next-btn-extended w-10 h-8 flex items-center justify-center rounded-lg bg-white/20 text-text-dark hover:bg-secondary/20 disabled:opacity-50 disabled:cursor-not-allowed transition-colors ${currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''}"
                ${currentPage === totalPages ? 'disabled' : ''}
                data-page="${currentPage + 1}"
                title="Next Page">
            <i class="fas fa-chevron-right text-xs"></i>
        </button>
    `;

            this.$pageNumbers.html(html);

            // Add event listeners to page buttons
            this.$pageNumbers.find('.page-btn').off('click').on('click', (e) => {
                e.preventDefault();
                const page = parseInt($(e.currentTarget).data('page'));
                if (page >= 1 && page <= this.totalPages) {
                    this.changePage(page);
                }
            });

            // Add event listeners untuk extended buttons
            this.$pageNumbers.find('.prev-btn-extended').off('click').on('click', (e) => {
                e.preventDefault();
                if (this.currentPage > 1) {
                    this.changePage(this.currentPage - 1);
                }
            });

            this.$pageNumbers.find('.next-btn-extended').off('click').on('click', (e) => {
                e.preventDefault();
                if (this.currentPage < this.totalPages) {
                    this.changePage(this.currentPage + 1);
                }
            });
        }

        createPageButton(page) {
            const isActive = page === this.currentPage;
            return `
    <button class="page-btn min-w-8 h-8 px-3 flex items-center justify-center rounded-lg font-medium transition-all duration-200 ${isActive ? 'bg-secondary text-white shadow-sm' : 'bg-white/20 text-text-dark hover:bg-secondary/20 hover:text-secondary'}" 
            data-page="${page}"
            title="Page ${page}">
        ${page}
    </button>
    `;
        }

        showError(message) {
            this.$emptyState.html(`
                <i class="fas fa-exclamation-triangle text-red-300 text-4xl mb-4"></i>
                <p class="text-red-500">${message}</p>
                <button class="mt-4 px-4 py-2 bg-secondary text-white rounded-lg hover:bg-[#665C9E] transition-colors" onclick="userManager.loadUsers()">
                    Retry
                </button>
            `);
            this.$emptyState.removeClass('hidden');
            this.$usersTable.addClass('hidden');
        }

        handleTableClick(event) {
            const $row = $(event.target).closest('tr[data-user-id]');
            if (!$row.length) return;

            const userId = parseInt($row.data('user-id'));

            // Check if click was on an action button
            const $actionBtn = $(event.target).closest('.btn-view-user, .btn-edit-user, .btn-reset-password, .btn-delete-user');
            if ($actionBtn.length) {
                event.stopPropagation();

                const action = $actionBtn.hasClass('btn-view-user') ? 'view' :
                    $actionBtn.hasClass('btn-edit-user') ? 'edit' :
                    $actionBtn.hasClass('btn-reset-password') ? 'reset' : 'delete';

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
            this.$usersTableBody.find('.btn-view-user').off('click').on('click', (e) => {
                const userId = parseInt($(e.currentTarget).data('user-id'));
                this.loadUserDetails(userId);
            });

            this.$usersTableBody.find('.btn-edit-user').off('click').on('click', (e) => {
                const userId = parseInt($(e.currentTarget).data('user-id'));
                this.editUser(userId);
            });

            this.$usersTableBody.find('.btn-reset-password').off('click').on('click', (e) => {
                const userId = parseInt($(e.currentTarget).data('user-id'));
                this.resetPassword(userId);
            });

            this.$usersTableBody.find('.btn-delete-user').off('click').on('click', (e) => {
                const userId = parseInt($(e.currentTarget).data('user-id'));
                this.deleteUser(userId);
            });

            // Bind detail action buttons
            if (this.$userActions.length) {
                this.$userActions.find('.edit-user-btn').off('click').on('click', (e) => {
                    const userId = parseInt($(e.currentTarget).data('user-id'));
                    this.editUser(userId);
                });

                this.$userActions.find('.reset-password-btn').off('click').on('click', (e) => {
                    const userId = parseInt($(e.currentTarget).data('user-id'));
                    this.resetPassword(userId);
                });

                this.$userActions.find('.toggle-status-btn').off('click').on('click', async (e) => {
                    const userId = parseInt($(e.currentTarget).data('user-id'));
                    await this.toggleUserStatus(userId);
                });

                this.$userActions.find('.delete-user-btn').off('click').on('click', (e) => {
                    const userId = parseInt($(e.currentTarget).data('user-id'));
                    this.deleteUser(userId);
                });
            }
        }

        showAddUserModal() {
            // Create modal HTML
            const modalHTML = `
                <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4" id="addUserModal">
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
                </div>
            `;

            $('body').append(modalHTML);
            const $modal = $('#addUserModal');

            // Add event listeners
            $modal.find('.close-modal').on('click', () => $modal.remove());

            $modal.find('#saveUserBtn').on('click', async () => {
                await this.saveNewUser($modal);
            });

            // Close on ESC key
            $(document).on('keydown.addUser', (e) => {
                if (e.key === 'Escape') {
                    $modal.remove();
                    $(document).off('keydown.addUser');
                }
            });

            // Prevent modal close when clicking inside modal
            $modal.find('.bg-white').on('click', (e) => {
                e.stopPropagation();
            });
        }

        async saveNewUser($modal) {
            try {
                const $form = $modal.find('#addUserForm');
                const formData = $form.serializeArray();

                // Convert to object for validation
                const formDataObj = {};
                formData.forEach(item => {
                    formDataObj[item.name] = item.value;
                });

                // Handle is_active
                const isActiveCheckbox = $modal.find('#is_active');
                if (isActiveCheckbox.length) {
                    formDataObj.is_active = isActiveCheckbox.prop('checked') ? '1' : '0';
                }

                // Debug: Tampilkan semua form data
                console.log('=== FORM DATA DEBUG ===');
                console.log('Full FormData object:', formDataObj);

                // Validasi client-side
                const password = formDataObj.password;
                const confirmPassword = formDataObj.confirm_password;

                if (password && password.length < 6) {
                    this.showToast('Password must be at least 6 characters', 'error');
                    return;
                }

                if (password !== confirmPassword) {
                    this.showToast('Passwords do not match', 'error');
                    return;
                }

                // Show loading state
                const $saveBtn = $modal.find('#saveUserBtn');
                const originalText = $saveBtn.html();
                $saveBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Saving...');
                $saveBtn.prop('disabled', true);

                // Send request ke endpoint ADD langsung
                const response = await $.ajax({
                    url: '<?= base_url("admin/users/ajax-add") ?>',
                    method: 'POST',
                    data: $form.serialize(),
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                console.log('Parsed response:', response);

                // Restore button state
                $saveBtn.html(originalText);
                $saveBtn.prop('disabled', false);

                if (response.success) {
                    this.showToast(response.message, 'success');

                    // Close modal
                    $modal.remove();

                    // Refresh user list
                    this.loadUsers();

                    // Show new user in the list
                    if (response.user_id) {
                        setTimeout(() => {
                            this.loadUserDetails(response.user_id);
                        }, 500);
                    }

                } else {
                    let errorMessage = response.message || 'Failed to add user';
                    console.error('Error details:', response);

                    this.showToast(errorMessage, 'error');

                    // Highlight error fields
                    if (response.errors) {
                        Object.keys(response.errors).forEach(fieldName => {
                            const $input = $modal.find(`[name="${fieldName}"]`);
                            if ($input.length) {
                                $input.addClass('border-red-500 bg-red-50');
                                $input.one('input', function() {
                                    $(this).removeClass('border-red-500 bg-red-50');
                                });
                            }
                        });
                    }
                }

            } catch (error) {
                console.error('Error saving user:', error);
                this.showToast('Error: ' + (error.responseJSON?.message || error.statusText || 'Save failed'), 'error');

                // Restore button state
                const $saveBtn = $modal.find('#saveUserBtn');
                if ($saveBtn.length) {
                    $saveBtn.html('<i class="fas fa-plus mr-2"></i>Add User');
                    $saveBtn.prop('disabled', false);
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
                const $toggleBtn = this.$userActions.find('.toggle-status-btn');
                const originalHtml = $toggleBtn.html();
                $toggleBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Updating...');
                $toggleBtn.prop('disabled', true);

                // Gunakan endpoint yang sesuai
                const endpoint = `<?= base_url("admin/users/change-status") ?>/${userId}`;
                console.log('Endpoint:', endpoint);

                const response = await $.ajax({
                    url: endpoint,
                    method: 'POST',
                    data: {
                        status: 'toggle',
                        user_id: userId
                    },
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                console.log('Response data:', response);

                // Restore button state
                $toggleBtn.html(originalHtml);
                $toggleBtn.prop('disabled', false);

                if (response.success) {
                    console.log('Success! Message:', response.message);
                    console.log('New status from server:', response.new_status);
                    console.log('Status text:', response.status_text);

                    this.showToast(response.message, 'success');

                    // Update button text based on new status from server
                    const newStatus = response.new_status;
                    const statusText = newStatus ? 'Deactivate Account' : 'Activate Account';
                    const btnClass = newStatus ?
                        'bg-red-50 text-red-600 border border-red-200 hover:bg-red-100' :
                        'bg-green-50 text-green-600 border border-green-200 hover:bg-green-100';

                    // Update button appearance
                    $toggleBtn.html(`<i class="fas fa-power-off"></i> ${statusText}`);
                    $toggleBtn.removeClass().addClass(`toggle-status-btn w-full py-3 ${btnClass} rounded-xl transition-colors font-medium flex items-center justify-center gap-2`);

                    // Refresh user list
                    await this.loadUsers();

                    // Reload user details if this user is selected
                    if (this.selectedUserId === userId) {
                        await this.loadUserDetails(userId);
                    }

                    // Update status badge di table jika user sedang ditampilkan
                    const $userRow = this.$usersTableBody.find(`tr[data-user-id="${userId}"]`);
                    if ($userRow.length) {
                        const $statusCell = $userRow.find('.status-badge');
                        if ($statusCell.length) {
                            $statusCell.text(newStatus ? 'Active' : 'Inactive');
                            $statusCell.removeClass().addClass(newStatus ? 'status-active status-badge' : 'status-inactive status-badge');
                        }
                    }

                } else {
                    console.error('API error:', response.message);
                    this.showToast(response.message, 'error');

                    // Kembalikan ke state semula jika error
                    $toggleBtn.html(originalHtml);
                    $toggleBtn.prop('disabled', false);
                }

            } catch (error) {
                console.error('Error in toggleUserStatus:', error);
                this.showToast('Failed to update user status: ' + (error.responseJSON?.message || error.statusText), 'error');

                // Restore button state
                const $toggleBtn = this.$userActions.find('.toggle-status-btn');
                if ($toggleBtn.length) {
                    $toggleBtn.html(originalHtml);
                    $toggleBtn.prop('disabled', false);
                }
            }

            console.log('=== DEBUG toggleUserStatus END ===');
        }

        async deleteUser(userId) {
            try {
                if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                    return;
                }

                const response = await $.ajax({
                    url: `<?= base_url("admin/users/delete") ?>/${userId}`,
                    method: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.success) {
                    this.showToast(response.message, 'success');
                    this.loadUsers();

                    // Clear details if deleted user was selected
                    if (this.selectedUserId === userId) {
                        this.selectedUserId = null;
                        this.$userDetails.html(`
                            <div class="flex flex-col items-center justify-center py-8 text-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-user text-gray-400 text-xl"></i>
                                </div>
                                <p class="text-text-dark/60 text-sm">Select a user to view details</p>
                            </div>
                        `);

                        this.$userActions.html(`
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
                        `);
                    }
                } else {
                    this.showToast(response.message, 'error');
                }

            } catch (error) {
                console.error('Error deleting user:', error);
                this.showToast('Failed to delete user: ' + (error.responseJSON?.message || error.statusText), 'error');
            }
        }

        updateSelectedRow() {
            // Remove selected class from all rows
            this.$usersTableBody.find('tr').removeClass('selected');

            // Add selected class to current row
            const $selectedRow = this.$usersTableBody.find(`tr[data-user-id="${this.selectedUserId}"]`);
            if ($selectedRow.length) {
                $selectedRow.addClass('selected');
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
            $('.custom-toast').remove();

            const toast = $(`
                <div class="custom-toast fixed top-24 right-6 p-4 rounded-lg shadow-lg z-[1000] max-w-sm animate-slideInUp ${type === 'error' ? 'bg-red-500 text-white' :
                        type === 'success' ? 'bg-green-500 text-white' :
                            'bg-blue-500 text-white'
                    }">
                    <div class="flex items-center gap-2">
                        <i class="fas ${type === 'error' ? 'fa-exclamation-circle' :
                        type === 'success' ? 'fa-check-circle' :
                            'fa-info-circle'
                    }"></i>
                        <span class="text-sm">${message}</span>
                    </div>
                </div>
            `);

            $('body').append(toast);

            setTimeout(() => {
                toast.css({
                    'opacity': '0',
                    'transform': 'translateY(-10px)'
                });
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
    $(document).ready(() => {
        window.userManager = new UserManager();
    });
</script>
<?= $this->endSection() ?>