<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>Manage Roles - NEXUS Admin<?= $this->endSection() ?>

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
        <h1 class="text-[34.77px] font-semibold mb-2 text-text-dark">Manage Roles</h1>
        <p class="text-[15.45px] font-light text-text-dark">Role-based access and permission management</p>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Left Column: Roles List -->
        <div class="lg:col-span-1">
            <div class="dashboard-card h-full">
                <div class="dashboard-card-header flex justify-between items-center">
                    <div class="text-text-dark/85 text-base font-medium">Roles</div>
                    <button id="addRoleBtn" class="text-text-dark/60 hover:text-secondary transition-colors">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                
                <!-- Search Roles -->
                <div class="mb-4 p-4">
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-text-muted">
                            <i class="fas fa-search"></i>
                        </div>
                        <input type="text" 
                               placeholder="Search roles" 
                               id="roleSearch"
                               class="w-full h-12 pl-12 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
                    </div>
                </div>
                
                <!-- Roles List -->
                <div id="rolesList" class="space-y-2 p-2">
                    <!-- Roles will be loaded here -->
                </div>
                
                <!-- Instruction Text -->
                <div class="mt-6 p-4 bg-white/30 rounded-lg border border-white/50">
                    <p class="text-text-dark/60 text-sm text-center">
                        Select a role to view and manage permissions
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Right Column: Role Details & Permissions -->
        <div class="lg:col-span-3">
            <!-- Role Details Card -->
            <div class="dashboard-card mb-6">
                <div class="dashboard-card-header flex justify-between items-center">
                    <div class="text-text-dark/85 text-base font-medium">
                        Role: <span id="selectedRoleName" class="text-secondary">Select a Role</span>
                    </div>
                    <div id="userCount" class="text-sm text-secondary">
                        <i class="fas fa-users mr-1"></i> <span>0</span> users assigned
                    </div>
                </div>
                
                <!-- Permissions Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 p-6">
                    <!-- Left Permissions Column -->
                    <div class="space-y-6">
                        <!-- Access Scope -->
                        <div class="permission-section">
                            <div class="permission-header">
                                <h4 class="text-text-dark/80 font-medium text-sm">Access Scope</h4>
                            </div>
                            <div class="bg-white rounded-lg p-4 space-y-3">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-user text-text-dark/40"></i>
                                    <span class="text-text-dark/70 text-sm">Access limited to:</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-user-circle text-text-dark/40"></i>
                                    <span class="text-text-dark/70 text-sm">Own account</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-ticket-alt text-text-dark/40"></i>
                                    <span class="text-text-dark/70 text-sm">Own tickets</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-project-diagram text-text-dark/40"></i>
                                    <span class="text-text-dark/70 text-sm">Assigned projects only</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Module Permissions -->
                        <div class="permission-section">
                            <div class="permission-header">
                                <h4 class="text-text-dark/80 font-medium text-sm">User Module Permissions</h4>
                            </div>
                            <div class="bg-white rounded-lg p-4 space-y-4" id="userPermissions">
                                <!-- Permissions will be loaded here -->
                            </div>
                        </div>
                        
                        <!-- Communication Permissions -->
                        <div class="permission-section">
                            <div class="permission-header">
                                <h4 class="text-text-dark/80 font-medium text-sm">Communication Permissions</h4>
                            </div>
                            <div class="bg-white rounded-lg p-4 space-y-4" id="communicationPermissions">
                                <!-- Permissions will be loaded here -->
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Permissions Column -->
                    <div class="space-y-6">
                        <!-- Ticket Module Permissions -->
                        <div class="permission-section">
                            <div class="permission-header">
                                <h4 class="text-text-dark/80 font-medium text-sm">Ticket Module Permissions</h4>
                            </div>
                            <div class="bg-white rounded-lg p-4 space-y-4" id="ticketPermissions">
                                <!-- Permissions will be loaded here -->
                            </div>
                        </div>
                        
                        <!-- Ticket Restrictions -->
                        <div class="permission-section">
                            <div class="permission-header">
                                <h4 class="text-text-dark/80 font-medium text-sm">Ticket Restrictions</h4>
                            </div>
                            <div class="bg-white rounded-lg p-4 space-y-4" id="ticketRestrictions">
                                <!-- Restrictions will be loaded here -->
                            </div>
                        </div>
                        
                        <!-- System Access Rules -->
                        <div class="permission-section">
                            <div class="permission-header">
                                <h4 class="text-text-dark/80 font-medium text-sm">System Access Rules</h4>
                            </div>
                            <div class="bg-white rounded-lg p-4 space-y-4" id="systemAccessRules">
                                <!-- Rules will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Core Responsibility -->
                <div class="mt-6 p-6 border-t border-white/30">
                    <div class="permission-header mb-4">
                        <h4 class="text-text-dark/80 font-medium text-sm">Core Responsibility</h4>
                    </div>
                    <div class="bg-white rounded-lg p-4" id="coreResponsibilities">
                        <!-- Responsibilities will be loaded here -->
                    </div>
                </div>
            </div>
            
            <!-- Role Rules & Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Role Rules & Notes -->
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <div class="text-text-dark/85 text-base font-medium">Role Rules & Notes</div>
                    </div>
                    
                    <div id="roleRules" class="mt-4 space-y-4 p-4">
                        <!-- Rules will be loaded here -->
                    </div>
                    
                    <!-- Warning Alert -->
                    <div class="mt-6 p-4 bg-[#FFF4E5] border border-[#FFE5BF] rounded-lg">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-exclamation-triangle text-[#FFB400] mt-1"></i>
                            <p class="text-text-dark/70 text-sm">
                                Changes to role permissions affect all users with this role
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Role Actions -->
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <div class="text-text-dark/85 text-base font-medium">Role Actions</div>
                    </div>
                    
                    <div id="roleActions" class="mt-4 space-y-4 p-4">
                        <!-- Actions will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Templates -->
<template id="editRoleModalTemplate">
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4">
        <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-800">Edit Role: <span id="modalRoleName"></span></h3>
                    <button class="close-modal text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                <div class="space-y-6">
                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Role Name</label>
                        <input type="text" id="editRoleName" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                    </div>
                    
                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Description</label>
                        <textarea id="editRoleDescription" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary" rows="3"></textarea>
                    </div>
                    
                    <div>
                        <h4 class="text-gray-700 font-medium mb-4">Permissions</h4>
                        <div class="grid grid-cols-2 gap-4" id="permissionCheckboxes">
                            <!-- Permissions will be loaded here -->
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Access Level</label>
                        <select id="editAccessLevel" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                            <option value="external">External Access</option>
                            <option value="internal">Internal Access</option>
                            <option value="full">Full Access</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="p-6 border-t border-gray-200 flex gap-3">
                <button class="close-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                    Cancel
                </button>
                <button id="saveRoleChanges" class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2]">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</template>

<template id="duplicateRoleModalTemplate">
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4">
        <div class="bg-white rounded-2xl w-full max-w-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-800">Duplicate Role</h3>
                    <button class="close-modal text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-600 text-sm mb-2">New Role Name</label>
                        <input type="text" id="newRoleName" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                    </div>
                    
                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Description</label>
                        <textarea id="newRoleDescription" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary" rows="3"></textarea>
                    </div>
                </div>
            </div>
            
            <div class="p-6 border-t border-gray-200 flex gap-3">
                <button class="close-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                    Cancel
                </button>
                <button id="confirmDuplicateRole" class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2]">
                    Duplicate Role
                </button>
            </div>
        </div>
    </div>
</template>

<template id="addRoleModalTemplate">
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4">
        <div class="bg-white rounded-2xl w-full max-w-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-800">Add New Role</h3>
                    <button class="close-modal text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Role Name</label>
                        <input type="text" id="addRoleName" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary" placeholder="e.g., Moderator">
                    </div>
                    
                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Description</label>
                        <textarea id="addRoleDescription" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary" rows="3" placeholder="Describe the role's purpose and responsibilities"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-gray-600 text-sm mb-2">Based on Template</label>
                        <select id="roleTemplate" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                            <option value="">Start from scratch</option>
                            <option value="customer">Customer Role</option>
                            <option value="support">Support Role</option>
                            <option value="admin">Admin Role</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="p-6 border-t border-gray-200 flex gap-3">
                <button class="close-modal flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                    Cancel
                </button>
                <button id="confirmAddRole" class="flex-1 py-3 bg-secondary text-white rounded-lg hover:bg-[#817CB2]">
                    Create Role
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
    
    @keyframes slideIn {
        from { 
            opacity: 0;
            transform: translateY(20px);
        }
        to { 
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
    
    .animate-slideIn {
        animation: slideIn 0.3s ease-out;
    }
    
    /* Role item styling */
    .role-item {
        border-radius: 12px;
        padding: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .role-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .role-item.selected {
        box-shadow: 0 0 0 2px #665C9E;
    }
    
    .role-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    
    .role-badge {
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 12px;
        font-weight: 500;
    }
    
    /* Permission section styling */
    .permission-header {
        background: #EFE6FA;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 12px;
    }
    
    .permission-section {
        transition: all 0.3s ease;
    }
    
    .permission-section:hover {
        transform: translateY(-2px);
    }
    
    /* Permission toggle switch */
    .permission-toggle {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 22px;
    }
    
    .permission-toggle input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .permission-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }
    
    .permission-slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    
    .permission-toggle input:checked + .permission-slider {
        background-color: #97CF82;
    }
    
    .permission-toggle input:checked + .permission-slider:before {
        transform: translateX(22px);
    }
    
    /* Permission item */
    .permission-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .permission-item:last-child {
        border-bottom: none;
    }
    
    .permission-info {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
    }
    
    .permission-icon {
        color: #6B7280;
        font-size: 14px;
        width: 20px;
        text-align: center;
    }
    
    .permission-label {
        font-size: 13px;
        color: #374151;
    }
    
    /* Role action buttons */
    .role-action-btn {
        width: 100%;
        padding: 12px;
        border-radius: 12px;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }
    
    .role-action-btn.primary {
        background: #665C9E;
        color: white;
    }
    
    .role-action-btn.primary:hover {
        background: #5A5190;
        transform: translateY(-1px);
    }
    
    .role-action-btn.secondary {
        background: white;
        color: #374151;
        border-color: #E5E7EB;
    }
    
    .role-action-btn.secondary:hover {
        background: #F9FAFB;
        transform: translateY(-1px);
    }
    
    .role-action-btn.danger {
        background: #FEF2F2;
        color: #DC2626;
        border-color: #FECACA;
    }
    
    .role-action-btn.danger:hover {
        background: #FEE2E2;
        transform: translateY(-1px);
    }
    
    /* Role indicator colors */
    .role-color-external {
        background: linear-gradient(135deg, #3B82F6, #60A5FA);
    }
    
    .role-color-internal {
        background: linear-gradient(135deg, #8B5CF6, #A78BFA);
    }
    
    .role-color-full {
        background: linear-gradient(135deg, #10B981, #34D399);
    }
    
    .role-color-technical {
        background: linear-gradient(135deg, #F59E0B, #FBBF24);
    }
    
    /* Responsive adjustments */
    @media (max-width: 1024px) {
        .permission-grid {
            grid-template-columns: 1fr !important;
        }
    }
    
    @media (max-width: 768px) {
        .role-item {
            padding: 12px;
        }
        
        .permission-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
        
        .permission-toggle {
            align-self: flex-end;
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
    .permissions-container {
        max-height: 500px;
        overflow-y: auto;
    }
    
    .permissions-container::-webkit-scrollbar {
        width: 6px;
    }
    
    .permissions-container::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 3px;
    }
    
    .permissions-container::-webkit-scrollbar-thumb {
        background: rgba(102, 92, 158, 0.4);
        border-radius: 3px;
    }
    
    .permissions-container::-webkit-scrollbar-thumb:hover {
        background: rgba(102, 92, 158, 0.6);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initial data
        let rolesData = [
            {
                id: 'customer',
                name: 'Customer',
                description: 'External user role with limited access to own tickets and profile',
                accessLevel: 'external',
                userCount: 378,
                colorClass: 'role-color-external',
                permissions: {
                    user: ['view_own_profile', 'update_own_profile', 'change_password'],
                    communication: ['send_messages', 'view_replies'],
                    ticket: ['view_own_tickets', 'create_ticket', 'reply_ticket', 'upload_attachments', 'view_status'],
                    restrictions: ['assign_ticket', 'change_priority', 'change_status', 'change_notes'],
                    systemAccess: ['admin_dashboard', 'support_dashboard', 'department_dashboard', 'reports', 'sla_data'],
                    coreResponsibilities: [
                        'Submit problem reports or requests',
                        'Monitor ticket progress',
                        'Communicate with support team',
                        'Confirm issue resolution'
                    ]
                },
                rules: [
                    'Customer role is a core system role',
                    'Cannot be deleted',
                    'Permissions are mostly locked',
                    'Data visibility is strictly isolated per user'
                ],
                isCore: true
            },
            {
                id: 'support',
                name: 'Support',
                description: 'Internal support role with access to assigned tickets and basic management',
                accessLevel: 'internal',
                userCount: 378,
                colorClass: 'role-color-internal',
                permissions: {
                    user: ['view_own_profile', 'update_own_profile', 'change_password'],
                    communication: ['send_messages', 'view_replies', 'internal_messages'],
                    ticket: ['view_all_tickets', 'create_ticket', 'reply_ticket', 'upload_attachments', 'view_status', 'assign_ticket', 'change_priority'],
                    restrictions: ['change_system_settings', 'manage_users', 'manage_roles'],
                    systemAccess: ['admin_dashboard', 'department_dashboard'],
                    coreResponsibilities: [
                        'Handle incoming support requests',
                        'Assign tickets to appropriate departments',
                        'Communicate with customers',
                        'Resolve basic technical issues'
                    ]
                },
                rules: [
                    'Support role can be customized',
                    'Can be duplicated for specific departments',
                    'Permissions can be adjusted as needed'
                ],
                isCore: false
            },
            {
                id: 'department',
                name: 'Department',
                description: 'Department-specific role with access to departmental tickets',
                accessLevel: 'internal',
                userCount: 21,
                colorClass: 'role-color-internal',
                permissions: {
                    user: ['view_own_profile', 'update_own_profile', 'change_password'],
                    communication: ['send_messages', 'view_replies', 'internal_messages'],
                    ticket: ['view_department_tickets', 'reply_ticket', 'upload_attachments', 'view_status', 'change_status'],
                    restrictions: ['assign_ticket', 'change_priority', 'manage_users', 'manage_roles'],
                    systemAccess: ['admin_dashboard', 'support_dashboard', 'reports', 'sla_data'],
                    coreResponsibilities: [
                        'Handle department-specific tickets',
                        'Collaborate with support team',
                        'Provide technical expertise',
                        'Update ticket status and notes'
                    ]
                },
                rules: [
                    'Department roles are created per department',
                    'Can be customized for specific needs',
                    'Permissions vary by department type'
                ],
                isCore: false
            },
            {
                id: 'admin',
                name: 'Admin',
                description: 'Administrator role with full system access and management capabilities',
                accessLevel: 'full',
                userCount: 15,
                colorClass: 'role-color-full',
                permissions: {
                    user: ['view_all_profiles', 'update_all_profiles', 'change_password', 'reset_passwords', 'manage_users'],
                    communication: ['send_messages', 'view_replies', 'internal_messages', 'system_messages'],
                    ticket: ['view_all_tickets', 'create_ticket', 'reply_ticket', 'upload_attachments', 'view_status', 'assign_ticket', 'change_priority', 'change_status', 'change_notes'],
                    restrictions: [],
                    systemAccess: [],
                    coreResponsibilities: [
                        'System configuration and management',
                        'User and role management',
                        'Monitor system performance',
                        'Generate reports and analytics'
                    ]
                },
                rules: [
                    'Admin role has full system access',
                    'Cannot be deleted',
                    'Should be assigned to trusted personnel',
                    'All permissions are enabled by default'
                ],
                isCore: true
            }
        ];
        
        let selectedRoleId = 'customer';
        let filteredRoles = [...rolesData];
        
        // DOM Elements
        const roleSearch = document.getElementById('roleSearch');
        const rolesList = document.getElementById('rolesList');
        const addRoleBtn = document.getElementById('addRoleBtn');
        const selectedRoleName = document.getElementById('selectedRoleName');
        const userCount = document.getElementById('userCount');
        const userPermissions = document.getElementById('userPermissions');
        const communicationPermissions = document.getElementById('communicationPermissions');
        const ticketPermissions = document.getElementById('ticketPermissions');
        const ticketRestrictions = document.getElementById('ticketRestrictions');
        const systemAccessRules = document.getElementById('systemAccessRules');
        const coreResponsibilities = document.getElementById('coreResponsibilities');
        const roleRules = document.getElementById('roleRules');
        const roleActions = document.getElementById('roleActions');
        
        // Initialize
        init();
        
        function init() {
            renderRolesList();
            loadRoleDetails(selectedRoleId);
            
            // Event listeners
            setupEventListeners();
        }
        
        function setupEventListeners() {
            // Search functionality
            if (roleSearch) {
                roleSearch.addEventListener('input', debounce(() => {
                    filterRoles();
                }, 300));
            }
            
            // Add role button
            if (addRoleBtn) {
                addRoleBtn.addEventListener('click', showAddRoleModal);
            }
        }
        
        function renderRolesList() {
            if (!rolesList) return;
            
            rolesList.innerHTML = '';
            
            if (filteredRoles.length === 0) {
                rolesList.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-search text-gray-300 text-3xl mb-3"></i>
                        <p class="text-gray-500 text-sm">No roles found</p>
                    </div>
                `;
                return;
            }
            
            filteredRoles.forEach(role => {
                const roleElement = document.createElement('div');
                roleElement.className = `role-item ${selectedRoleId === role.id ? 'selected bg-secondary text-white' : 'bg-white border border-gray-200'}`;
                roleElement.dataset.roleId = role.id;
                
                roleElement.innerHTML = `
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-3">
                            <div class="role-dot ${role.colorClass}"></div>
                            <span class="font-medium ${selectedRoleId === role.id ? 'text-white' : 'text-text-dark'}">${role.name}</span>
                        </div>
                        <span class="role-badge ${selectedRoleId === role.id ? 'bg-white/20 text-white' : 'bg-gray-100 text-text-dark'}">
                            ${role.userCount}
                        </span>
                    </div>
                    <div class="${selectedRoleId === role.id ? 'text-white/70' : 'text-text-dark/50'} text-xs ml-5">
                        ${getAccessLevelName(role.accessLevel)}
                    </div>
                `;
                
                roleElement.addEventListener('click', () => {
                    selectRole(role.id);
                });
                
                rolesList.appendChild(roleElement);
            });
        }
        
        function filterRoles() {
            const searchTerm = roleSearch ? roleSearch.value.toLowerCase().trim() : '';
            
            if (!searchTerm) {
                filteredRoles = [...rolesData];
            } else {
                filteredRoles = rolesData.filter(role => 
                    role.name.toLowerCase().includes(searchTerm) ||
                    role.description.toLowerCase().includes(searchTerm) ||
                    role.accessLevel.toLowerCase().includes(searchTerm)
                );
            }
            
            renderRolesList();
        }
        
        function selectRole(roleId) {
            selectedRoleId = roleId;
            renderRolesList();
            loadRoleDetails(roleId);
        }
        
        function loadRoleDetails(roleId) {
            const role = rolesData.find(r => r.id === roleId);
            if (!role) return;
            
            // Update header
            selectedRoleName.textContent = role.name;
            userCount.innerHTML = `<i class="fas fa-users mr-1"></i> ${role.userCount} users assigned`;
            
            // Load permissions
            loadPermissions(role);
            
            // Load core responsibilities
            loadCoreResponsibilities(role);
            
            // Load role rules
            loadRoleRules(role);
            
            // Load role actions
            loadRoleActions(role);
        }
        
        function loadPermissions(role) {
            // User Permissions
            userPermissions.innerHTML = `
                ${createPermissionItem('View own profile', 'fa-eye', role.permissions.user.includes('view_own_profile'))}
                ${createPermissionItem('Update own profile', 'fa-edit', role.permissions.user.includes('update_own_profile'))}
                ${createPermissionItem('Change Password', 'fa-key', role.permissions.user.includes('change_password'))}
                ${role.permissions.user.includes('view_all_profiles') ? createPermissionItem('View all profiles', 'fa-users', true) : ''}
                ${role.permissions.user.includes('update_all_profiles') ? createPermissionItem('Update all profiles', 'fa-user-edit', true) : ''}
                ${role.permissions.user.includes('reset_passwords') ? createPermissionItem('Reset passwords', 'fa-unlock-alt', true) : ''}
                ${role.permissions.user.includes('manage_users') ? createPermissionItem('Manage users', 'fa-user-cog', true) : ''}
            `;
            
            // Communication Permissions
            communicationPermissions.innerHTML = `
                ${createPermissionItem('Send messages in ticket conversation', 'fa-comment', role.permissions.communication.includes('send_messages'))}
                ${createPermissionItem('View replies from Support & Departments', 'fa-eye', role.permissions.communication.includes('view_replies'))}
                ${createPermissionItem('Send internal-only messages', 'fa-comments', role.permissions.communication.includes('internal_messages'))}
                ${role.permissions.communication.includes('system_messages') ? createPermissionItem('Send system messages', 'fa-bullhorn', true) : ''}
            `;
            
            // Ticket Permissions
            ticketPermissions.innerHTML = `
                ${createPermissionItem('View tickets', 'fa-eye', true, role.permissions.ticket.includes('view_all_tickets') ? 'All tickets' : role.permissions.ticket.includes('view_department_tickets') ? 'Department tickets' : 'Own tickets only')}
                ${createPermissionItem('Create ticket', 'fa-plus-circle', role.permissions.ticket.includes('create_ticket'))}
                ${createPermissionItem('Reply to ticket', 'fa-reply', role.permissions.ticket.includes('reply_ticket'))}
                ${createPermissionItem('Upload attachments', 'fa-paperclip', role.permissions.ticket.includes('upload_attachments'))}
                ${createPermissionItem('View ticket status & progress', 'fa-chart-line', role.permissions.ticket.includes('view_status'))}
                ${role.permissions.ticket.includes('assign_ticket') ? createPermissionItem('Assign ticket', 'fa-user-plus', true) : ''}
                ${role.permissions.ticket.includes('change_priority') ? createPermissionItem('Change ticket priority', 'fa-flag', true) : ''}
                ${role.permissions.ticket.includes('change_status') ? createPermissionItem('Change ticket status', 'fa-exchange-alt', true) : ''}
            `;
            
            // Ticket Restrictions
            ticketRestrictions.innerHTML = `
                ${createRestrictionItem('Assign Ticket', !role.permissions.ticket.includes('assign_ticket'))}
                ${createRestrictionItem('Change ticket priority', !role.permissions.ticket.includes('change_priority'))}
                ${createRestrictionItem('Change ticket status', !role.permissions.ticket.includes('change_status'))}
                ${createRestrictionItem('Change ticket notes', !role.permissions.ticket.includes('change_notes'))}
            `;
            
            // System Access Rules
            systemAccessRules.innerHTML = `
                ${createRestrictionItem('Cannot access admin dashboard', role.permissions.systemAccess.includes('admin_dashboard'))}
                ${createRestrictionItem('Cannot access support dashboard', role.permissions.systemAccess.includes('support_dashboard'))}
                ${createRestrictionItem('Cannot access department dashboard', role.permissions.systemAccess.includes('department_dashboard'))}
                ${createRestrictionItem('Cannot access reports', role.permissions.systemAccess.includes('reports'))}
                ${createRestrictionItem('Cannot access SLA data', role.permissions.systemAccess.includes('sla_data'))}
            `;
            
            // Add event listeners to permission toggles
            setTimeout(() => {
                document.querySelectorAll('.permission-toggle input').forEach(toggle => {
                    toggle.addEventListener('change', function() {
                        const permissionName = this.dataset.permission;
                        const isEnabled = this.checked;
                        updatePermission(role.id, permissionName, isEnabled);
                    });
                });
            }, 100);
        }
        
        function createPermissionItem(label, icon, enabled, description = '') {
            return `
                <div class="permission-item">
                    <div class="permission-info">
                        <i class="fas ${icon} permission-icon"></i>
                        <div>
                            <div class="permission-label">${label}</div>
                            ${description ? `<div class="text-xs text-gray-500 mt-1">${description}</div>` : ''}
                        </div>
                    </div>
                    <label class="permission-toggle">
                        <input type="checkbox" ${enabled ? 'checked' : ''} data-permission="${label.toLowerCase().replace(/ /g, '_')}">
                        <span class="permission-slider"></span>
                    </label>
                </div>
            `;
        }
        
        function createRestrictionItem(label, restricted) {
            return `
                <div class="flex items-center gap-3 py-2">
                    <i class="fas fa-ban ${restricted ? 'text-red-400' : 'text-gray-300'}"></i>
                    <span class="text-text-dark/60 text-sm">${label}</span>
                </div>
            `;
        }
        
        function loadCoreResponsibilities(role) {
            coreResponsibilities.innerHTML = role.permissions.coreResponsibilities
                .map(responsibility => `
                    <div class="flex items-start gap-3 mb-3 last:mb-0">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <span class="text-text-dark/70 text-sm">${responsibility}</span>
                    </div>
                `).join('');
        }
        
        function loadRoleRules(role) {
            roleRules.innerHTML = role.rules
                .map(rule => `
                    <div class="flex items-start gap-3">
                        <i class="fas ${role.isCore ? 'fa-lock' : 'fa-info-circle'} text-text-dark/40 mt-1"></i>
                        <span class="text-text-dark/70 text-sm">${rule}</span>
                    </div>
                `).join('');
        }
        
        function loadRoleActions(role) {
            roleActions.innerHTML = `
                <button class="role-action-btn primary edit-role-btn" data-role-id="${role.id}">
                    <i class="fas fa-edit"></i>
                    Edit Role Permissions
                </button>
                
                <button class="role-action-btn secondary reset-role-btn" data-role-id="${role.id}">
                    <i class="fas fa-undo-alt"></i>
                    Reset to Default
                </button>
                
                <button class="role-action-btn secondary duplicate-role-btn" data-role-id="${role.id}">
                    <i class="fas fa-copy"></i>
                    Duplicate Role
                </button>
                
                ${!role.isCore ? `
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <h4 class="text-red-600 font-medium text-sm mb-3">Danger Zone</h4>
                        <button class="role-action-btn danger delete-role-btn" data-role-id="${role.id}">
                            <i class="fas fa-trash-alt"></i>
                            Delete Role
                        </button>
                    </div>
                ` : ''}
            `;
            
            // Add event listeners to action buttons
            setTimeout(() => {
                document.querySelectorAll('.edit-role-btn').forEach(btn => {
                    btn.addEventListener('click', () => showEditRoleModal(btn.dataset.roleId));
                });
                
                document.querySelectorAll('.reset-role-btn').forEach(btn => {
                    btn.addEventListener('click', () => resetRolePermissions(btn.dataset.roleId));
                });
                
                document.querySelectorAll('.duplicate-role-btn').forEach(btn => {
                    btn.addEventListener('click', () => showDuplicateRoleModal(btn.dataset.roleId));
                });
                
                document.querySelectorAll('.delete-role-btn').forEach(btn => {
                    btn.addEventListener('click', () => deleteRole(btn.dataset.roleId));
                });
            }, 100);
        }
        
        function updatePermission(roleId, permissionName, enabled) {
            const role = rolesData.find(r => r.id === roleId);
            if (!role) return;
            
            // In a real application, this would be an API call
            console.log(`Updating permission ${permissionName} for role ${roleId} to ${enabled}`);
            
            showToast(`Permission updated: ${permissionName} ${enabled ? 'enabled' : 'disabled'}`, 'success');
        }
        
        function showEditRoleModal(roleId) {
            const role = rolesData.find(r => r.id === roleId);
            if (!role) return;
            
            // Create modal from template
            const template = document.getElementById('editRoleModalTemplate');
            const modal = document.importNode(template.content, true);
            
            // Fill modal data
            modal.querySelector('#modalRoleName').textContent = role.name;
            modal.querySelector('#editRoleName').value = role.name;
            modal.querySelector('#editRoleDescription').value = role.description;
            modal.querySelector('#editAccessLevel').value = role.accessLevel;
            
            // Load permission checkboxes
            const permissionCheckboxes = modal.querySelector('#permissionCheckboxes');
            const allPermissions = getAllPermissions();
            
            permissionCheckboxes.innerHTML = allPermissions
                .map(permission => `
                    <div class="flex items-center gap-3">
                        <label class="permission-toggle">
                            <input type="checkbox" ${isPermissionEnabled(role, permission.id) ? 'checked' : ''}>
                            <span class="permission-slider"></span>
                        </label>
                        <span class="text-sm text-gray-700">${permission.name}</span>
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
            
            const saveBtn = modal.querySelector('#saveRoleChanges');
            saveBtn.addEventListener('click', () => {
                saveRoleChanges(roleId, modal);
            });
        }
        
        function showDuplicateRoleModal(roleId) {
            const role = rolesData.find(r => r.id === roleId);
            if (!role) return;
            
            // Create modal from template
            const template = document.getElementById('duplicateRoleModalTemplate');
            const modal = document.importNode(template.content, true);
            
            // Fill modal data
            modal.querySelector('#newRoleName').value = `${role.name} - Copy`;
            modal.querySelector('#newRoleDescription').value = role.description;
            
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
            
            const confirmBtn = modal.querySelector('#confirmDuplicateRole');
            confirmBtn.addEventListener('click', () => {
                duplicateRole(roleId, modal);
            });
        }
        
        function showAddRoleModal() {
            // Create modal from template
            const template = document.getElementById('addRoleModalTemplate');
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
            
            const confirmBtn = modal.querySelector('#confirmAddRole');
            confirmBtn.addEventListener('click', () => {
                createNewRole(modal);
            });
            
            // Template selection
            const templateSelect = modal.querySelector('#roleTemplate');
            templateSelect.addEventListener('change', function() {
                const templateId = this.value;
                if (templateId) {
                    const templateRole = rolesData.find(r => r.id === templateId);
                    if (templateRole) {
                        modal.querySelector('#addRoleDescription').value = templateRole.description;
                    }
                }
            });
        }
        
        function saveRoleChanges(roleId, modal) {
            const role = rolesData.find(r => r.id === roleId);
            if (!role) return;
            
            const newName = modal.querySelector('#editRoleName').value.trim();
            const newDescription = modal.querySelector('#editRoleDescription').value.trim();
            const newAccessLevel = modal.querySelector('#editAccessLevel').value;
            
            if (!newName) {
                alert('Please enter a role name');
                return;
            }
            
            // Update role data
            role.name = newName;
            role.description = newDescription;
            role.accessLevel = newAccessLevel;
            
            // Update permissions (in real app, this would be from checkboxes)
            
            // Close modal
            document.body.removeChild(modal.querySelector('.fixed'));
            document.body.style.overflow = 'auto';
            
            // Update UI
            selectRole(roleId);
            
            showToast('Role updated successfully!', 'success');
        }
        
        function duplicateRole(roleId, modal) {
            const role = rolesData.find(r => r.id === roleId);
            if (!role) return;
            
            const newName = modal.querySelector('#newRoleName').value.trim();
            const newDescription = modal.querySelector('#newRoleDescription').value.trim();
            
            if (!newName) {
                alert('Please enter a name for the new role');
                return;
            }
            
            // Create new role
            const newRole = {
                ...JSON.parse(JSON.stringify(role)), // Deep clone
                id: `${role.id}-copy-${Date.now()}`,
                name: newName,
                description: newDescription || role.description,
                userCount: 0,
                isCore: false,
                colorClass: getRandomColorClass()
            };
            
            // Add to roles data
            rolesData.push(newRole);
            
            // Close modal
            document.body.removeChild(modal.querySelector('.fixed'));
            document.body.style.overflow = 'auto';
            
            // Update UI
            filterRoles();
            selectRole(newRole.id);
            
            showToast(`Role "${newName}" created successfully!`, 'success');
        }
        
        function createNewRole(modal) {
            const name = modal.querySelector('#addRoleName').value.trim();
            const description = modal.querySelector('#addRoleDescription').value.trim();
            const templateId = modal.querySelector('#roleTemplate').value;
            
            if (!name) {
                alert('Please enter a role name');
                return;
            }
            
            // Base role data
            let newRole = {
                id: name.toLowerCase().replace(/ /g, '-'),
                name: name,
                description: description || 'Custom role with specific permissions',
                accessLevel: 'internal',
                userCount: 0,
                colorClass: getRandomColorClass(),
                isCore: false,
                permissions: {
                    user: ['view_own_profile', 'update_own_profile', 'change_password'],
                    communication: ['send_messages', 'view_replies'],
                    ticket: ['view_own_tickets', 'create_ticket', 'reply_ticket', 'upload_attachments', 'view_status'],
                    restrictions: ['assign_ticket', 'change_priority', 'change_status', 'change_notes'],
                    systemAccess: ['admin_dashboard', 'support_dashboard', 'department_dashboard', 'reports', 'sla_data'],
                    coreResponsibilities: [
                        'Custom responsibilities based on role needs'
                    ]
                },
                rules: [
                    'This is a custom role',
                    'Permissions can be customized as needed',
                    'Can be assigned to specific users or departments'
                ]
            };
            
            // If template selected, copy permissions
            if (templateId) {
                const templateRole = rolesData.find(r => r.id === templateId);
                if (templateRole) {
                    newRole = {
                        ...newRole,
                        permissions: JSON.parse(JSON.stringify(templateRole.permissions)),
                        accessLevel: templateRole.accessLevel
                    };
                }
            }
            
            // Add to roles data
            rolesData.push(newRole);
            
            // Close modal
            document.body.removeChild(modal.querySelector('.fixed'));
            document.body.style.overflow = 'auto';
            
            // Update UI
            filterRoles();
            selectRole(newRole.id);
            
            showToast(`Role "${name}" created successfully!`, 'success');
        }
        
        function resetRolePermissions(roleId) {
            const role = rolesData.find(r => r.id === roleId);
            if (!role) return;
            
            if (!confirm('Are you sure you want to reset this role to default permissions?')) {
                return;
            }
            
            // In a real app, this would reset to default permissions
            // For demo, we'll just show a toast
            showToast(`Resetting ${role.name} permissions to default...`, 'info');
            
            setTimeout(() => {
                showToast(`${role.name} permissions reset successfully`, 'success');
                loadRoleDetails(roleId);
            }, 1500);
        }
        
        function deleteRole(roleId) {
            const role = rolesData.find(r => r.id === roleId);
            if (!role) return;
            
            if (role.isCore) {
                alert('Core system roles cannot be deleted');
                return;
            }
            
            if (!confirm(`Are you sure you want to delete the "${role.name}" role? This action cannot be undone.`)) {
                return;
            }
            
            // Remove role from data
            rolesData = rolesData.filter(r => r.id !== roleId);
            
            // Update UI
            filterRoles();
            
            // Select another role if available
            if (rolesData.length > 0) {
                selectRole(rolesData[0].id);
            } else {
                // Clear details if no roles left
                selectedRoleName.textContent = 'Select a Role';
                userCount.innerHTML = '<i class="fas fa-users mr-1"></i> 0 users assigned';
                userPermissions.innerHTML = '';
                communicationPermissions.innerHTML = '';
                ticketPermissions.innerHTML = '';
                ticketRestrictions.innerHTML = '';
                systemAccessRules.innerHTML = '';
                coreResponsibilities.innerHTML = '';
                roleRules.innerHTML = '';
                roleActions.innerHTML = '';
            }
            
            showToast(`Role "${role.name}" deleted successfully`, 'success');
        }
        
        // Utility functions
        function getAccessLevelName(level) {
            const levels = {
                'external': 'External Access',
                'internal': 'Internal Access',
                'full': 'Full Access',
                'technical': 'Technical Access'
            };
            return levels[level] || level;
        }
        
        function getAllPermissions() {
            return [
                { id: 'view_own_profile', name: 'View own profile' },
                { id: 'update_own_profile', name: 'Update own profile' },
                { id: 'change_password', name: 'Change Password' },
                { id: 'view_all_profiles', name: 'View all profiles' },
                { id: 'update_all_profiles', name: 'Update all profiles' },
                { id: 'reset_passwords', name: 'Reset passwords' },
                { id: 'manage_users', name: 'Manage users' },
                { id: 'send_messages', name: 'Send messages' },
                { id: 'view_replies', name: 'View replies' },
                { id: 'internal_messages', name: 'Internal messages' },
                { id: 'system_messages', name: 'System messages' },
                { id: 'view_tickets', name: 'View tickets' },
                { id: 'create_ticket', name: 'Create ticket' },
                { id: 'reply_ticket', name: 'Reply to ticket' },
                { id: 'upload_attachments', name: 'Upload attachments' },
                { id: 'view_status', name: 'View ticket status' },
                { id: 'assign_ticket', name: 'Assign ticket' },
                { id: 'change_priority', name: 'Change ticket priority' },
                { id: 'change_status', name: 'Change ticket status' },
                { id: 'change_notes', name: 'Change ticket notes' }
            ];
        }
        
        function isPermissionEnabled(role, permissionId) {
            // Check all permission categories
            for (const category in role.permissions) {
                if (Array.isArray(role.permissions[category])) {
                    if (role.permissions[category].includes(permissionId)) {
                        return true;
                    }
                }
            }
            return false;
        }
        
        function getRandomColorClass() {
            const colors = [
                'role-color-external',
                'role-color-internal',
                'role-color-full',
                'role-color-technical'
            ];
            return colors[Math.floor(Math.random() * colors.length)];
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
            toast.className = `custom-toast fixed top-24 right-6 p-4 rounded-lg shadow-lg z-[1000] max-w-sm animate-slideIn ${
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
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
        
        // Initialize with first role selected
        setTimeout(() => {
            selectRole(selectedRoleId);
        }, 100);
    });
</script>
<?= $this->endSection() ?>