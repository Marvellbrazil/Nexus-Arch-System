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
                            <i class="fas fa-trash mr-2"></i>
                            Delete User
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

    .delete-warning {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
        }

        50% {
            box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
        }
    }

    /* Loading animation */
    .fa-spinner {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* Button states */
    .btn-disabled {
        opacity: 0.5;
        cursor: not-allowed !important;
    }

    /* Modal transitions */
    .modal-enter {
        animation: modalEnter 0.3s ease-out;
    }

    @keyframes modalEnter {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(-10px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .status-modal-icon {
        animation: bounceIn 0.6s ease-out;
    }

    @keyframes bounceIn {
        0% {
            transform: scale(0);
            opacity: 0;
        }

        60% {
            transform: scale(1.1);
            opacity: 1;
        }

        100% {
            transform: scale(1);
        }
    }

    /* Status badges with animation */
    .status-badge {
        transition: all 0.3s ease;
    }

    .status-active {
        background: linear-gradient(135deg, #C4E3AC, #A8D08D);
        color: #15803D;
        box-shadow: 0 2px 4px rgba(21, 128, 61, 0.1);
    }

    .status-inactive {
        background: linear-gradient(135deg, #ECDCD3, #E0C9BC);
        color: #93867E;
        box-shadow: 0 2px 4px rgba(147, 134, 126, 0.1);
    }

    /* Button animations */
    .btn-pulse {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(234, 179, 8, 0.4);
        }

        50% {
            box-shadow: 0 0 0 8px rgba(234, 179, 8, 0);
        }
    }

    .btn-pulse-green {
        animation: pulse-green 2s infinite;
    }

    @keyframes pulse-green {

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4);
        }

        50% {
            box-shadow: 0 0 0 8px rgba(34, 197, 94, 0);
        }
    }

    /* Smooth transitions */
    .modal-transition {
        animation: modalFadeIn 0.3s ease-out;
    }

    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px) scale(0.95);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* Loading spinner */
    .fa-spinner {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
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

                // Validasi client-side sederhana
                const requiredFields = ['username', 'full_name', 'email', 'role_id'];
                let isValid = true;

                requiredFields.forEach(field => {
                    const $field = $form.find(`[name="${field}"]`);
                    const value = $field.val();

                    if (!value || value.trim() === '') {
                        $field.addClass('border-red-500 bg-red-50');
                        isValid = false;
                    } else {
                        $field.removeClass('border-red-500 bg-red-50');
                    }
                });

                if (!isValid) {
                    this.showToast('Please fill all required fields', 'error');
                    return;
                }

                // Siapkan form data
                const formData = $form.serialize();

                // Debug
                console.log('Form data:', formData);

                // Send request dengan error handling yang lebih baik
                const response = await $.ajax({
                    url: '<?= base_url("admin/users/update") ?>',
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).fail((jqXHR, textStatus, errorThrown) => {
                    console.error('AJAX Error:', {
                        status: jqXHR.status,
                        statusText: jqXHR.statusText,
                        responseText: jqXHR.responseText
                    });

                    let errorMessage = 'Request failed';
                    if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                        errorMessage = jqXHR.responseJSON.message;
                    } else if (jqXHR.statusText) {
                        errorMessage = jqXHR.statusText;
                    }

                    throw new Error(errorMessage);
                });

                console.log('Response:', response);

                if (response.success) {
                    this.showToast(response.message, 'success');

                    // Close modal
                    $modal.remove();

                    // Refresh user list
                    await this.loadUsers();

                    // Reload user details if this user is selected
                    if (this.selectedUserId === userId) {
                        await this.loadUserDetails(userId);
                    }

                } else {
                    let errorMessage = response.message || 'Failed to update user';

                    // Show validation errors if available
                    if (response.errors) {
                        const errors = Object.values(response.errors);
                        errorMessage = errors.join(', ');

                        // Highlight error fields
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

                    this.showToast(errorMessage, 'error');
                }

            } catch (error) {
                console.error('Error updating user:', error);
                this.showToast('Error: ' + error.message, 'error');
            }
        }

        async resetPassword(userId) {
            try {
                // Show reset password modal
                const modalHTML = `
            <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4" id="resetPasswordModal">
                <div class="bg-white rounded-2xl w-full max-w-md animate-slideInUp">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-gray-800">Reset Password</h3>
                            <button type="button" class="close-modal text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Enter new password for user</p>
                    </div>
                    
                    <div class="p-6">
                        <form id="resetPasswordForm" novalidate>
                            <input type="hidden" name="user_id" value="${userId}">
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-gray-600 text-sm mb-2">
                                        New Password <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="password" 
                                        name="new_password" 
                                        required 
                                        minlength="6"
                                        class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all"
                                        placeholder="••••••••"
                                        autocomplete="new-password">
                                    <p class="text-xs text-gray-500 mt-1">Minimum 6 characters</p>
                                    <div class="error-message" id="new_password_error"></div>
                                </div>
                                
                                <div>
                                    <label class="block text-gray-600 text-sm mb-2">
                                        Confirm Password <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="password" 
                                        name="confirm_password" 
                                        required
                                        class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all"
                                        placeholder="••••••••"
                                        autocomplete="new-password">
                                    <div class="error-message" id="confirm_password_error"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div class="p-6 border-t border-gray-200 flex gap-3">
                        <button type="button" class="close-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                            Cancel
                        </button>
                        <button type="button" id="savePasswordBtn" class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#665C9E] transition-colors font-medium">
                            <i class="fas fa-key mr-2"></i>
                            Reset Password
                        </button>
                    </div>
                </div>
            </div>
        `;

                $('body').append(modalHTML);
                const $modal = $('#resetPasswordModal');

                // Add event listeners
                $modal.find('.close-modal').on('click', () => {
                    $modal.remove();
                    $(document).off('keydown.resetPassword');
                });

                $modal.find('#savePasswordBtn').on('click', async () => {
                    await this.processResetPassword($modal, userId);
                });

                // Submit form dengan Enter key
                $modal.find('input').on('keypress', (e) => {
                    if (e.which === 13) {
                        e.preventDefault();
                        $modal.find('#savePasswordBtn').click();
                    }
                });

                // Close on ESC key
                $(document).on('keydown.resetPassword', (e) => {
                    if (e.key === 'Escape') {
                        $modal.remove();
                        $(document).off('keydown.resetPassword');
                    }
                });

                // Clear errors on input
                $modal.find('input').on('input', function() {
                    $(this).removeClass('border-red-500 bg-red-50');
                    const fieldName = $(this).attr('name');
                    $(`#${fieldName}_error`).text('');
                });

            } catch (error) {
                console.error('Error in resetPassword:', error);
                this.showToast('Failed to open reset password form', 'error');
            }
        }

        async processResetPassword($modal, userId) {
            try {
                const $form = $modal.find('#resetPasswordForm');

                // Clear previous errors
                $form.find('input').removeClass('border-red-500 bg-red-50');
                $form.find('.error-message').text('');

                // Get form data
                const formDataArray = $form.serializeArray();
                const formDataObj = {};
                formDataArray.forEach(item => {
                    formDataObj[item.name] = item.value;
                });

                // Client-side validation
                let isValid = true;

                if (!formDataObj.new_password || formDataObj.new_password.length < 6) {
                    $modal.find('[name="new_password"]').addClass('border-red-500 bg-red-50');
                    $('#new_password_error').text('Password must be at least 6 characters');
                    isValid = false;
                }

                if (!formDataObj.confirm_password) {
                    $modal.find('[name="confirm_password"]').addClass('border-red-500 bg-red-50');
                    $('#confirm_password_error').text('Please confirm your password');
                    isValid = false;
                }

                if (formDataObj.new_password && formDataObj.confirm_password &&
                    formDataObj.new_password !== formDataObj.confirm_password) {
                    $modal.find('[name="confirm_password"]').addClass('border-red-500 bg-red-50');
                    $('#confirm_password_error').text('Passwords do not match');
                    isValid = false;
                }

                if (!isValid) {
                    return;
                }

                // Show loading state
                const $saveBtn = $modal.find('#savePasswordBtn');
                const originalText = $saveBtn.html();
                $saveBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i> Processing...');
                $saveBtn.prop('disabled', true);

                // Send AJAX request ke endpoint AJAX yang baru
                const response = await $.ajax({
                    url: `<?= base_url("admin/users/ajax-reset-password") ?>/${userId}`,
                    method: 'POST',
                    data: {
                        new_password: formDataObj.new_password,
                        confirm_password: formDataObj.confirm_password
                    },
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).fail((jqXHR, textStatus, errorThrown) => {
                    console.error('AJAX Error Details:', {
                        status: jqXHR.status,
                        statusText: jqXHR.statusText,
                        responseText: jqXHR.responseText,
                        responseJSON: jqXHR.responseJSON
                    });

                    let errorMessage = 'Request failed';
                    if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                        errorMessage = jqXHR.responseJSON.message;
                    } else if (jqXHR.responseText) {
                        try {
                            const parsedError = JSON.parse(jqXHR.responseText);
                            errorMessage = parsedError.message || errorMessage;
                        } catch (e) {
                            errorMessage = jqXHR.responseText || errorMessage;
                        }
                    }

                    throw new Error(errorMessage);
                });

                // Restore button state
                $saveBtn.html(originalText);
                $saveBtn.prop('disabled', false);

                if (response.success) {
                    this.showToast(response.message, 'success');
                    $modal.remove();

                    // Optionally, log the user out from all devices (if needed)
                    // this.forceLogoutUser(userId);

                } else {
                    // Handle server-side validation errors
                    let errorMessage = response.message || 'Failed to reset password';

                    if (response.errors) {
                        // Show field-specific errors
                        Object.keys(response.errors).forEach(fieldName => {
                            const $input = $modal.find(`[name="${fieldName}"]`);
                            if ($input.length) {
                                $input.addClass('border-red-500 bg-red-50');
                                $(`#${fieldName}_error`).text(response.errors[fieldName]);
                            }
                        });

                        // Show general error message
                        const errors = Object.values(response.errors);
                        errorMessage = errors.join(', ');
                    }

                    this.showToast(errorMessage, 'error');
                }

            } catch (error) {
                console.error('Error resetting password:', error);

                // Restore button state
                const $saveBtn = $modal.find('#savePasswordBtn');
                $saveBtn.html('<i class="fas fa-key mr-2"></i> Reset Password');
                $saveBtn.prop('disabled', false);

                // Show user-friendly error message
                let errorMessage = 'Failed to reset password';
                if (error.message && error.message !== 'OK') {
                    errorMessage = error.message;
                }

                this.showToast(errorMessage, 'error');

                // If it's a network error, show more details
                if (error.status === 0) {
                    this.showToast('Network error. Please check your connection.', 'error');
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
                        <span class="text-text-dark font-medium">${user.department_name || '-'}</span>
                    </div>
                    <div class="user-info-item">
                        <span class="text-text-dark/70 text-sm">Phone:</span>
                        <span class="text-text-dark font-medium">${user.phone_number || '-'}</span>
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
            const userId = user.user_id;

            const html = `
    <div class="space-y-2 animate-fadeIn">
        <!-- 1. Edit User Button -->
        <button class="edit-user-btn w-full py-3 bg-secondary text-white rounded-xl hover:bg-[#665C9E] transition-colors font-medium flex items-center justify-center gap-2" 
                data-user-id="${userId}">
            <i class="fas fa-edit"></i>
            Edit User
        </button>
        
        <!-- 2. Reset Password Button -->
        <button class="reset-password-btn w-full py-3 bg-white text-text-dark border border-text-dark/20 rounded-xl hover:bg-gray-50 transition-colors font-medium flex items-center justify-center gap-2" 
                data-user-id="${userId}">
            <i class="fas fa-key"></i>
            Reset Password
        </button>
        
        <!-- 3. Delete User Button -->
        <button class="delete-user-btn w-full py-3 bg-red-50 text-red-600 border border-red-200 rounded-xl hover:bg-red-100 transition-colors font-medium flex items-center justify-center gap-2" 
                data-user-id="${userId}">
            <i class="fas fa-trash"></i>
            Delete User
        </button>
        
        <!-- 4. Toggle Status Button -->
        <button class="toggle-status-btn w-full py-3 ${isActive ? 'bg-yellow-50 text-yellow-600 border border-yellow-200' : 'bg-green-50 text-green-600 border border-green-200'} rounded-xl hover:${isActive ? 'bg-yellow-100' : 'bg-green-100'} transition-colors font-medium flex items-center justify-center gap-2" 
                data-user-id="${userId}">
            <i class="fas fa-power-off"></i>
            ${isActive ? 'Deactivate Account' : 'Activate Account'}
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
                this.$showingInfo.text(`${data.total} users`);
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

                this.$userActions.find('.delete-user-btn').off('click').on('click', (e) => {
                    const userId = parseInt($(e.currentTarget).data('user-id'));
                    this.deleteUser(userId);
                });

                this.$userActions.find('.toggle-status-btn').off('click').on('click', async (e) => {
                    const userId = parseInt($(e.currentTarget).data('user-id'));
                    await this.toggleUserStatus(userId);
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

                // Send request ke endpoint ADD langsung (TANPA loading state)
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
            }
        }

        async toggleUserStatus(userId) {
            try {
                // Get user details first for confirmation message
                const userResponse = await $.ajax({
                    url: `<?= base_url("admin/users/ajax-details") ?>/${userId}`,
                    method: 'GET',
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!userResponse.success || !userResponse.user) {
                    this.showToast('Failed to load user details', 'error');
                    return;
                }

                const user = userResponse.user;
                const isCurrentlyActive = user.is_active;
                const action = isCurrentlyActive ? 'deactivate' : 'activate';
                const userName = user.full_name;

                // Show confirmation modal with better UI
                const modalHTML = `
            <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4" id="toggleStatusModal">
                <div class="bg-white rounded-2xl w-full max-w-md animate-slideInUp">
                    <div class="p-6 text-center">
                        <div class="w-16 h-16 ${isCurrentlyActive ? 'bg-yellow-100' : 'bg-green-100'} rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas ${isCurrentlyActive ? 'fa-user-slash text-yellow-600' : 'fa-user-check text-green-600'} text-2xl"></i>
                        </div>
                        
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">
                            ${isCurrentlyActive ? 'Deactivate Account' : 'Activate Account'}
                        </h3>
                        
                        <p class="text-gray-600 mb-6">
                            Are you sure you want to <span class="font-semibold">${action}</span> 
                            <span class="font-semibold text-${isCurrentlyActive ? 'yellow' : 'green'}-600">${userName}</span>'s account?
                        </p>
                        
                        <div class="${isCurrentlyActive ? 'bg-yellow-50 border-yellow-200' : 'bg-green-50 border-green-200'} border rounded-lg p-4 mb-6 text-left">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle ${isCurrentlyActive ? 'text-yellow-500' : 'text-green-500'} mt-1 mr-2"></i>
                                <div>
                                    <p class="text-sm ${isCurrentlyActive ? 'text-yellow-800' : 'text-green-800'} font-medium">
                                        ${isCurrentlyActive ? 'Deactivation Effects:' : 'Activation Effects:'}
                                    </p>
                                    <ul class="text-sm ${isCurrentlyActive ? 'text-yellow-700' : 'text-green-700'} mt-1 list-disc list-inside space-y-1">
                                        ${isCurrentlyActive ? 
                                            `<li>User will not be able to login</li>
                                             <li>Account will appear as inactive</li>
                                             <li>Can be reactivated anytime</li>
                                             <li>Existing data is preserved</li>` :
                                            `<li>User will be able to login again</li>
                                             <li>Account will appear as active</li>
                                             <li>All permissions restored</li>
                                             <li>User can resume activities</li>`
                                        }
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex gap-3">
                            <button type="button" 
                                    class="close-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                                Cancel
                            </button>
                            <button type="button" 
                                    id="confirmToggleBtn" 
                                    class="flex-1 py-3 ${isCurrentlyActive ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600'} text-white rounded-lg transition-colors font-medium flex items-center justify-center gap-2">
                                <i class="fas ${isCurrentlyActive ? 'fa-user-slash' : 'fa-user-check'}"></i>
                                ${isCurrentlyActive ? 'Deactivate Account' : 'Activate Account'}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

                $('body').append(modalHTML);
                const $modal = $('#toggleStatusModal');
                const $confirmBtn = $modal.find('#confirmToggleBtn');

                // Add event listeners
                $modal.find('.close-modal').on('click', () => {
                    $modal.remove();
                    $(document).off('keydown.toggleStatus');
                });

                $confirmBtn.on('click', async () => {
                    await this.processToggleStatus($modal, userId, isCurrentlyActive);
                });

                // Close on ESC key
                $(document).on('keydown.toggleStatus', (e) => {
                    if (e.key === 'Escape') {
                        $modal.remove();
                        $(document).off('keydown.toggleStatus');
                    }
                });

                // Submit with Enter key
                $(document).on('keydown.toggleStatusEnter', (e) => {
                    if (e.key === 'Enter' && !$confirmBtn.is(':disabled')) {
                        e.preventDefault();
                        $confirmBtn.click();
                    }
                });

            } catch (error) {
                console.error('Error in toggleUserStatus:', error);
                this.showToast('Failed to open status change confirmation', 'error');
            }
        }

        async processToggleStatus($modal, userId, isCurrentlyActive) {
            try {
                // Show loading state
                const $confirmBtn = $modal.find('#confirmToggleBtn');
                const originalText = $confirmBtn.html();
                const action = isCurrentlyActive ? 'Deactivating...' : 'Activating...';
                $confirmBtn.html(`<i class="fas fa-spinner fa-spin mr-2"></i> ${action}`);
                $confirmBtn.prop('disabled', true);

                // Send AJAX request ke endpoint AJAX yang baru
                const response = await $.ajax({
                    url: `<?= base_url("admin/users/ajax-change-status") ?>/${userId}`,
                    method: 'POST',
                    data: {
                        user_id: userId
                    },
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).fail((jqXHR, textStatus, errorThrown) => {
                    console.error('AJAX Toggle Status Error Details:', {
                        status: jqXHR.status,
                        statusText: jqXHR.statusText,
                        responseText: jqXHR.responseText,
                        responseJSON: jqXHR.responseJSON
                    });

                    let errorMessage = 'Request failed';
                    if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                        errorMessage = jqXHR.responseJSON.message;
                    } else if (jqXHR.responseText) {
                        try {
                            const parsedError = JSON.parse(jqXHR.responseText);
                            errorMessage = parsedError.message || errorMessage;
                        } catch (e) {
                            errorMessage = jqXHR.responseText || errorMessage;
                        }
                    }

                    throw new Error(errorMessage);
                });

                // Restore button state
                $confirmBtn.html(originalText);
                $confirmBtn.prop('disabled', false);

                if (response.success) {
                    const newStatus = response.new_status;
                    const statusText = response.status_text;
                    const action = response.action;

                    this.showToast(response.message, 'success');
                    $modal.remove();

                    // Update UI immediately
                    this.updateStatusUI(userId, newStatus, statusText, action);

                    // Refresh user list
                    await this.loadUsers();

                    // Reload user details if this user is selected
                    if (this.selectedUserId === userId) {
                        await this.loadUserDetails(userId);
                    }

                } else {
                    let errorMessage = response.message || 'Failed to update user status';

                    // Show specific error messages
                    if (response.message.includes('cannot change your own')) {
                        errorMessage = 'You cannot change your own account status';
                        $modal.remove(); // Close modal for self-status change
                    } else if (response.message.includes('User not found')) {
                        errorMessage = 'User not found';
                        $modal.remove();
                    }

                    this.showToast(errorMessage, 'error');
                }

            } catch (error) {
                console.error('Error toggling user status:', error);

                // Restore button state
                const $confirmBtn = $modal.find('#confirmToggleBtn');
                $confirmBtn.html(originalText);
                $confirmBtn.prop('disabled', false);

                // Show user-friendly error message
                let errorMessage = 'Failed to update user status';
                if (error.message && error.message !== 'OK') {
                    errorMessage = error.message;
                }

                this.showToast(errorMessage, 'error');

                // If it's a network error
                if (error.status === 0) {
                    this.showToast('Network error. Please check your connection.', 'error');
                }
            }
        }

        // Method to update UI after status change
        updateStatusUI(userId, newStatus, statusText, action) {
            // Update status badge in table if user is visible
            const $userRow = this.$usersTableBody.find(`tr[data-user-id="${userId}"]`);
            if ($userRow.length) {
                const $statusCell = $userRow.find('.status-badge');
                if ($statusCell.length) {
                    $statusCell.text(statusText);
                    $statusCell.removeClass().addClass(newStatus ? 'status-active status-badge' : 'status-inactive status-badge');
                }
            }

            // Update button in actions panel
            const newButtonText = newStatus ? 'Deactivate Account' : 'Activate Account';
            const newButtonClass = newStatus ?
                'bg-yellow-50 text-yellow-600 border border-yellow-200 hover:bg-yellow-100' :
                'bg-green-50 text-green-600 border border-green-200 hover:bg-green-100';

            const $toggleBtn = this.$userActions.find('.toggle-status-btn');
            if ($toggleBtn.length) {
                $toggleBtn.html(`<i class="fas fa-power-off"></i> ${newButtonText}`);
                $toggleBtn.removeClass().addClass(`toggle-status-btn w-full py-3 ${newButtonClass} rounded-xl transition-colors font-medium flex items-center justify-center gap-2`);
            }

            // Show success message with details
            setTimeout(() => {
                this.showToast(`User account ${action} successfully`, 'success');
            }, 300);
        }

        async deleteUser(userId) {
            try {
                // Get user details first for confirmation message
                const userResponse = await $.ajax({
                    url: `<?= base_url("admin/users/ajax-details") ?>/${userId}`,
                    method: 'GET',
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).fail(() => {
                    // If can't get user details, still proceed with generic message
                    return null;
                });

                const userName = userResponse?.success ? userResponse.user.full_name : 'this user';

                // Show confirmation modal with better UI
                const modalHTML = `
            <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4" id="deleteUserModal">
                <div class="bg-white rounded-2xl w-full max-w-md animate-slideInUp">
                    <div class="p-6 text-center">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                        </div>
                        
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Delete User</h3>
                        <p class="text-gray-600 mb-4">
                            Are you sure you want to delete <span class="font-semibold">${userName}</span>?
                            This action cannot be undone.
                        </p>
                        
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6 text-left">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-red-500 mt-1 mr-2"></i>
                                <div>
                                    <p class="text-sm text-red-800 font-medium">Warning:</p>
                                    <ul class="text-sm text-red-700 mt-1 list-disc list-inside space-y-1">
                                        <li>All user data will be permanently deleted</li>
                                        <li>Associated tickets will need reassignment</li>
                                        <li>Project assignments will be removed</li>
                                        <li>This action cannot be reversed</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex gap-3">
                            <button type="button" 
                                    class="close-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                                Cancel
                            </button>
                            <button type="button" 
                                    id="confirmDeleteBtn" 
                                    class="flex-1 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium flex items-center justify-center gap-2">
                                <i class="fas fa-trash"></i>
                                Delete User
                            </button>
                        </div>
                        
                        <div class="mt-4">
                            <label class="flex items-center text-sm text-gray-600">
                                <input type="checkbox" 
                                       id="confirmCheckbox" 
                                       class="mr-2 h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                I understand this action is permanent
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        `;

                $('body').append(modalHTML);
                const $modal = $('#deleteUserModal');
                const $confirmCheckbox = $modal.find('#confirmCheckbox');
                const $deleteBtn = $modal.find('#confirmDeleteBtn');

                // Disable delete button initially
                $deleteBtn.prop('disabled', true);
                $deleteBtn.addClass('opacity-50 cursor-not-allowed');

                // Enable/disable delete button based on checkbox
                $confirmCheckbox.on('change', function() {
                    if ($(this).is(':checked')) {
                        $deleteBtn.prop('disabled', false);
                        $deleteBtn.removeClass('opacity-50 cursor-not-allowed');
                    } else {
                        $deleteBtn.prop('disabled', true);
                        $deleteBtn.addClass('opacity-50 cursor-not-allowed');
                    }
                });

                // Add event listeners
                $modal.find('.close-modal').on('click', () => {
                    $modal.remove();
                    $(document).off('keydown.deleteUser');
                });

                $deleteBtn.on('click', async () => {
                    await this.processDeleteUser($modal, userId);
                });

                // Close on ESC key
                $(document).on('keydown.deleteUser', (e) => {
                    if (e.key === 'Escape') {
                        $modal.remove();
                        $(document).off('keydown.deleteUser');
                    }
                });

            } catch (error) {
                console.error('Error in deleteUser:', error);
                this.showToast('Failed to open delete confirmation', 'error');
            }
        }

        async processDeleteUser($modal, userId) {
            try {
                // Show loading state
                const $deleteBtn = $modal.find('#confirmDeleteBtn');
                const originalText = $deleteBtn.html();
                $deleteBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i> Deleting...');
                $deleteBtn.prop('disabled', true);

                // Send AJAX request ke endpoint AJAX yang baru
                const response = await $.ajax({
                    url: `<?= base_url("admin/users/ajax-delete") ?>/${userId}`,
                    method: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).fail((jqXHR, textStatus, errorThrown) => {
                    console.error('AJAX Delete Error Details:', {
                        status: jqXHR.status,
                        statusText: jqXHR.statusText,
                        responseText: jqXHR.responseText,
                        responseJSON: jqXHR.responseJSON
                    });

                    let errorMessage = 'Request failed';
                    if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                        errorMessage = jqXHR.responseJSON.message;
                    } else if (jqXHR.responseText) {
                        try {
                            const parsedError = JSON.parse(jqXHR.responseText);
                            errorMessage = parsedError.message || errorMessage;
                        } catch (e) {
                            errorMessage = jqXHR.responseText || errorMessage;
                        }
                    }

                    throw new Error(errorMessage);
                });

                // Restore button state
                $deleteBtn.html(originalText);
                $deleteBtn.prop('disabled', false);

                if (response.success) {
                    this.showToast(response.message, 'success');
                    $modal.remove();

                    // Refresh user list
                    await this.loadUsers();

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
                            <i class="fas fa-trash mr-2"></i>
                            Delete User
                        </button>
                        
                        <button class="w-full py-3 bg-gray-100 text-gray-400 rounded-xl cursor-not-allowed" disabled>
                            <i class="fas fa-power-off mr-2"></i>
                            Deactivate Account
                        </button>
                    </div>
                `);
                    }

                } else {
                    let errorMessage = response.message || 'Failed to delete user';

                    // Show specific error messages
                    if (response.message.includes('Cannot delete your own account')) {
                        errorMessage = 'You cannot delete your own account';
                    } else if (response.message.includes('associated tickets')) {
                        errorMessage = 'Cannot delete user with associated tickets. Please reassign tickets first.';
                    } else if (response.message.includes('User not found')) {
                        errorMessage = 'User not found. It may have been already deleted.';
                    }

                    this.showToast(errorMessage, 'error');

                    // Close modal on certain errors
                    if (response.message.includes('User not found')) {
                        $modal.remove();
                    }
                }

            } catch (error) {
                console.error('Error deleting user:', error);

                // Restore button state
                const $deleteBtn = $modal.find('#confirmDeleteBtn');
                $deleteBtn.html('<i class="fas fa-trash mr-2"></i> Delete User');
                $deleteBtn.prop('disabled', false);

                // Show user-friendly error message
                let errorMessage = 'Failed to delete user';
                if (error.message && error.message !== 'OK') {
                    errorMessage = error.message;
                }

                this.showToast(errorMessage, 'error');

                // If it's a network error
                if (error.status === 0) {
                    this.showToast('Network error. Please check your connection.', 'error');
                }
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