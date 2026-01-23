<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>Manage Departments - NEXUS Admin<?= $this->endSection() ?>

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
                <input type="text" placeholder="Search by department name" id="departmentSearch"
                    class="w-full h-12 pl-12 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
            </div>
        </div>

        <!-- Status Filter Tabs -->
        <div class="bg-[#F3F4F6] rounded-lg p-1 flex items-center">
            <button class="department-filter px-4 py-2 rounded-md font-medium text-sm transition-all active"
                data-filter="all">
                All
            </button>
            <button class="department-filter px-4 py-2 rounded-md font-medium text-sm transition-all"
                data-filter="active">
                Active
            </button>
            <button class="department-filter px-4 py-2 rounded-md font-medium text-sm transition-all"
                data-filter="inactive">
                Inactive
            </button>
        </div>

        <!-- Add New Department Button -->
        <button id="addDepartmentBtnaaa"
            class="w-full md:w-auto h-12 px-6 bg-secondary text-white rounded-lg hover:bg-[#665C9E] transition-colors font-medium flex items-center justify-center gap-2"
            onclick="return modalForm('Add Department', 'modal-lg', '<?= getURL('admin/departments/form') ?>')">
            <i class="fas fa-plus text-lg"></i>
            Add New Department
        </button>
    </div>

    <!-- Main Content Grid -->
    <div class="table-responsive margin-t-18p">
        <table class="table table-bordered table-master fs-7 w-100" id="departmentsTable">
            <thead>
                <tr>
                    <td class="tableheader">No</td>
                    <td class="tableheader">Department Name</td>
                    <td class="tableheader">Description</td>
                    <td class="tableheader">Department Head</td>
                    <td class="tableheader" style="width: 75px !important;">Action</td>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

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
            <button class="menu-item delete-department">
                <i class="fas fa-trash text-red-600"></i>
                Delete Department
            </button>
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

    .department-item>div:nth-child(1) {
        grid-column: span 2;
    }

    .department-item>div:nth-child(2) {
        grid-column: span 3;
    }

    .department-item>div:nth-child(3) {
        grid-column: span 1;
    }

    .department-item>div:nth-child(4) {
        grid-column: span 1;
    }

    .department-item>div:nth-child(5) {
        grid-column: span 1;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .department-item {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }

    .department-item>div {
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
    0% {
        background-position: 200% 0;
    }

    100% {
        background-position: -200% 0;
    }
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
// Data will be loaded dynamically
let departmentsData = [];
let filteredDepartments = [];
let selectedDepartmentId = null;
let currentFilter = "all";
let currentPage = 1;
let itemsPerPage = 10;
let totalDepartments = 0;

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
const totalDepartmentsEl = document.getElementById('totalDepartments');
const activeDepartmentsEl = document.getElementById('activeDepartments');
const inactiveDepartmentsEl = document.getElementById('inactiveDepartments');
const totalMembersEl = document.getElementById('totalMembers');


$(document).ready(function() {
    $('#departmentsTable').DataTable({
        serverSide: true,
        destroy: true,
        autoWidth: false,
        processing: true,
        ajax: {
            url: '<?= current_url(true) ?>/table',
            type: 'post',
            dataType: 'json',
            data: function(param) {
                return param;
            },
            "deferRender": true,
            dataSrc: function(json) {
                return json.data
            }
        }
    });
});
</script>
<?= $this->endSection() ?>