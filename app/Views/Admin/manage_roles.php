<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>Manage Roles - NEXUS Admin<?= $this->endSection() ?>

<?= $this->section('background_effects') ?>
<!-- Background Effects -->
<div class="fixed w-[40vw] h-[40vw] -right-[10%] -bottom-[10%] rotate-[149deg] bg-gradient-to-r from-[rgba(56.96,44.47,127.72,0.15)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-80 z-0"></div>
<div class="fixed w-[35vw] h-[25vw] -left-[5%] top-[10%] rotate-[8deg] bg-gradient-to-r from-[rgba(65.04,45.10,137.14,0.20)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-70 z-0"></div>
<div class="fixed w-[15vw] h-[20vw] right-[5%] -top-[5%] rotate-[8deg] bg-gradient-to-r from-[rgba(16.56,8.41,46.05,0.12)] via-[#D6D3EE] to-[#817CB2] blur-[100px] opacity-60 z-0"></div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
// Helper functions untuk view
function getAccessScope($roleName)
{
    $scopes = [
        'Customer' => [
            'Access limited to own account',
            'Can view and create own tickets',
            'Cannot access admin features',
            'Basic communication permissions'
        ],
        'Support' => [
            'Department access',
            'Can handle assigned tickets',
            'Internal communication access',
            'Limited user management'
        ],
        'Department' => [
            'Department-specific access',
            'Can handle department tickets',
            'Internal collaboration',
            'Technical expertise access'
        ],
        'Admin' => [
            'Full system access',
            'All management features',
            'Unrestricted permissions',
            'System configuration rights'
        ]
    ];

    return $scopes[$roleName] ?? [
        'Custom access scope',
        'Permissions defined by administrator'
    ];
}

function getAccessScopeIcon($roleName)
{
    $icons = [
        'Customer' => 'fa-user',
        'Support' => 'fa-headset',
        'Department' => 'fa-users',
        'Admin' => 'fa-crown'
    ];

    return $icons[$roleName] ?? 'fa-user-tag';
}

function createPermissionItem($label, $icon, $enabled, $permissionKey = '')
{
    return '
    <div class="permission-item">
        <div class="permission-info">
            <i class="fas ' . $icon . ' permission-icon"></i>
            <div>
                <div class="permission-label">' . esc($label) . '</div>
            </div>
        </div>
        <label class="permission-toggle">
            <input type="checkbox" ' . ($enabled ? 'checked' : '') . ' data-permission-key="' . $permissionKey . '" onchange="updatePermission(this)">
            <span class="permission-slider"></span>
        </label>
    </div>';
}

function createSystemAccessItem($label, $allowed)
{
    return '
    <div class="flex items-center gap-3 py-2">
        <i class="fas ' . ($allowed ? 'fa-check text-green-500' : 'fa-ban text-red-400') . '"></i>
        <span class="text-text-dark/60 text-sm">' . ($allowed ? 'Can access' : 'Cannot access') . ' ' . strtolower($label) . '</span>
    </div>';
}

function getRoleColorClass($roleName)
{
    $colors = [
        'Customer' => 'role-color-customer',
        'Support' => 'role-color-support',
        'Department' => 'role-color-department',
        'Admin' => 'role-color-admin'
    ];
    return $colors[$roleName] ?? 'role-color-custom';
}

// Fungsi untuk mengurutkan roles sesuai urutan yang diinginkan
function sortRolesByPriority($roles)
{
    $priorityOrder = [
        'Customer' => 1,
        'Support' => 2,
        'Department' => 3,
        'Admin' => 4
    ];

    usort($roles, function ($a, $b) use ($priorityOrder) {
        $aPriority = $priorityOrder[$a['role_name']] ?? 999;
        $bPriority = $priorityOrder[$b['role_name']] ?? 999;

        return $aPriority - $bPriority;
    });

    return $roles;
}

// Check if permissions data is available from controller
$permissionsData = $permissionsWithStatus ?? [];
$permissionSummary = $permissionSummary ?? ['total' => 0, 'allowed' => 0, 'denied' => 0, 'percentage' => 0];
$selectedRoleName = $selectedRole['role_name'] ?? '';

// Urutkan roles jika ada
if (!empty($roles)) {
    $roles = sortRolesByPriority($roles);
}
?>
<div class="relative z-10">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-[34.77px] font-semibold mb-2 text-text-dark">Manage Roles</h1>
        <p class="text-[15.45px] font-light text-text-dark">Role-based access and permission management</p>
    </div>

    <!-- Loading overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-white/80 flex items-center justify-center z-50 hidden">
        <div class="text-center">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-secondary"></div>
            <p class="mt-4 text-text-dark">Loading...</p>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Left Column: Roles List -->
        <div class="lg:col-span-1">
            <div class="dashboard-card h-full">
                <div class="dashboard-card-header flex justify-between items-center">
                    <div class="text-text-dark/85 text-base font-medium">Roles</div>
                    <button id="addRoleBtn" class="text-text-dark/60 hover:text-secondary transition-colors" title="Add New Role">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>

                <!-- Search Roles -->
                <div class="mb-4 p-4">
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-text-muted">
                            <i class="fas fa-search"></i>
                        </div>
                        <input type="text" placeholder="Search roles" id="roleSearch"
                            class="w-full h-12 pl-12 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
                    </div>
                </div>

                <!-- Roles List -->
                <div id="rolesList" class="space-y-2 p-2 max-h-[400px] overflow-y-auto">
                    <?php if (!empty($roles)): ?>
                        <?php foreach ($roles as $role): ?>
                            <div class="role-item <?= $role['role_id'] == ($selectedRole['role_id'] ?? 0) ? 'selected bg-secondary text-white' : 'bg-white border border-gray-200' ?>"
                                data-role-id="<?= $role['role_id'] ?>" onclick="selectRole(<?= $role['role_id'] ?>)">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-3">
                                        <div class="role-dot <?= getRoleColorClass($role['role_name']) ?>"></div>
                                        <span class="font-medium <?= $role['role_id'] == ($selectedRole['role_id'] ?? 0) ? 'text-white' : 'text-text-dark' ?>">
                                            <?= esc($role['role_name']) ?>
                                        </span>
                                    </div>
                                    <span class="role-badge <?= $role['role_id'] == ($selectedRole['role_id'] ?? 0) ? 'bg-white/20 text-white' : 'bg-gray-100 text-text-dark' ?>">
                                        <?= $role['user_count'] ?? 0 ?>
                                    </span>
                                </div>
                                <div class="<?= $role['role_id'] == ($selectedRole['role_id'] ?? 0) ? 'text-white/70' : 'text-text-dark/50' ?> text-xs ml-5">
                                    <?= $role['role_name'] ?> Access
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <i class="fas fa-users text-gray-300 text-3xl mb-3"></i>
                            <p class="text-gray-500 text-sm">No roles found</p>
                        </div>
                    <?php endif; ?>
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
                        Role: <span id="selectedRoleName" class="text-secondary"><?= esc($selectedRoleName) ?></span>
                    </div>
                    <div id="userCount" class="text-sm text-secondary">
                        <i class="fas fa-users mr-1"></i> <span id="selectedUserCount"><?= $selectedRole['user_count'] ?? 0 ?></span> users assigned
                    </div>
                </div>

                <!-- Permission Summary -->
                <div class="px-6 pt-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-text-dark/70 text-sm">Permission Summary</span>
                        <span class="text-sm font-medium" id="permissionPercentage"><?= $permissionSummary['percentage'] ?>%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                        <div id="permissionProgress" class="bg-secondary h-2 rounded-full transition-all duration-500" style="width: <?= $permissionSummary['percentage'] ?>%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-text-dark/60">
                        <span id="allowedCount"><?= $permissionSummary['allowed'] ?> allowed</span>
                        <span id="deniedCount"><?= $permissionSummary['denied'] ?> denied</span>
                        <span id="totalCount"><?= $permissionSummary['total'] ?> total</span>
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
                            <div class="bg-white rounded-lg p-4 space-y-3" id="accessScope">
                                <?php if ($selectedRole): ?>
                                    <?php $accessScope = getAccessScope($selectedRoleName); ?>
                                    <div class="flex items-center gap-3 mb-3">
                                        <i class="fas <?= getAccessScopeIcon($selectedRoleName) ?> text-secondary"></i>
                                        <span class="text-text-dark/70 text-sm font-medium"><?= $selectedRoleName ?> Access Level</span>
                                    </div>
                                    <?php foreach ($accessScope as $scope): ?>
                                        <div class="flex items-center gap-3 ml-2">
                                            <i class="fas fa-circle text-xs text-text-dark/30"></i>
                                            <span class="text-text-dark/70 text-sm"><?= $scope ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <p class="text-text-dark/50 text-sm">Select a role to view access scope</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- User Module Permissions -->
                        <div class="permission-section">
                            <div class="permission-header">
                                <h4 class="text-text-dark/80 font-medium text-sm">User Module Permissions</h4>
                            </div>
                            <div class="bg-white rounded-lg p-4 space-y-4" id="userPermissions">
                                <?php if (!empty($permissionsData['user'])): ?>
                                    <?php foreach ($permissionsData['user'] as $permission): ?>
                                        <?= createPermissionItem($permission['name'], 'fa-user', $permission['is_allowed'], $permission['key']) ?>
                                    <?php endforeach; ?>
                                <?php elseif ($selectedRole): ?>
                                    <div class="text-center py-4">
                                        <p class="text-text-dark/50 text-sm">No user permissions found</p>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <p class="text-text-dark/50 text-sm">Select a role to view permissions</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Communication Permissions -->
                        <div class="permission-section">
                            <div class="permission-header">
                                <h4 class="text-text-dark/80 font-medium text-sm">Communication Permissions</h4>
                            </div>
                            <div class="bg-white rounded-lg p-4 space-y-4" id="communicationPermissions">
                                <?php if (!empty($permissionsData['communication'])): ?>
                                    <?php foreach ($permissionsData['communication'] as $permission): ?>
                                        <?= createPermissionItem($permission['name'], 'fa-comments', $permission['is_allowed'], $permission['key']) ?>
                                    <?php endforeach; ?>
                                <?php elseif ($selectedRole): ?>
                                    <div class="text-center py-4">
                                        <p class="text-text-dark/50 text-sm">No communication permissions found</p>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <p class="text-text-dark/50 text-sm">Select a role to view permissions</p>
                                    </div>
                                <?php endif; ?>
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
                                <?php if (!empty($permissionsData['ticket'])): ?>
                                    <?php foreach ($permissionsData['ticket'] as $permission): ?>
                                        <?= createPermissionItem($permission['name'], 'fa-ticket-alt', $permission['is_allowed'], $permission['key']) ?>
                                    <?php endforeach; ?>
                                <?php elseif ($selectedRole): ?>
                                    <div class="text-center py-4">
                                        <p class="text-text-dark/50 text-sm">No ticket permissions found</p>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <p class="text-text-dark/50 text-sm">Select a role to view permissions</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- System Access Rules -->
                        <div class="permission-section">
                            <div class="permission-header">
                                <h4 class="text-text-dark/80 font-medium text-sm">System Access Rules</h4>
                            </div>
                            <div class="bg-white rounded-lg p-4 space-y-4" id="systemAccessRules">
                                <?php if (!empty($permissionsData['system'])): ?>
                                    <?php foreach ($permissionsData['system'] as $permission): ?>
                                        <?= createSystemAccessItem($permission['name'], $permission['is_allowed']) ?>
                                    <?php endforeach; ?>
                                <?php elseif ($selectedRole): ?>
                                    <div class="text-center py-4">
                                        <p class="text-text-dark/50 text-sm">No system permissions found</p>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <p class="text-text-dark/50 text-sm">Select a role to view system access</p>
                                    </div>
                                <?php endif; ?>
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
                        <?php if (!empty($coreResponsibilities)): ?>
                            <?php foreach ($coreResponsibilities as $responsibility): ?>
                                <div class="flex items-start gap-3 mb-3 last:mb-0">
                                    <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                    <span class="text-text-dark/70 text-sm"><?= esc($responsibility) ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php elseif ($selectedRole): ?>
                            <div class="text-center py-4">
                                <p class="text-text-dark/50 text-sm">No responsibilities defined</p>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <p class="text-text-dark/50 text-sm">Select a role to view responsibilities</p>
                            </div>
                        <?php endif; ?>
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
                        <?php if (!empty($roleRules)): ?>
                            <?php foreach ($roleRules as $rule): ?>
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-info-circle text-text-dark/40 mt-1"></i>
                                    <span class="text-text-dark/70 text-sm"><?= esc($rule) ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php elseif ($selectedRole): ?>
                            <div class="text-center py-4">
                                <p class="text-text-dark/50 text-sm">No rules defined for this role</p>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <p class="text-text-dark/50 text-sm">Select a role to view rules</p>
                            </div>
                        <?php endif; ?>
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
                        <?php if ($selectedRole): ?>
                            <button class="role-action-btn primary" onclick="saveRolePermissions()">
                                <i class="fas fa-save"></i>
                                Save All Permissions
                            </button>

                            <button class="role-action-btn secondary" onclick="resetRolePermissions(<?= $selectedRole['role_id'] ?>)">
                                <i class="fas fa-undo-alt"></i>
                                Reset to Default
                            </button>

                            <button class="role-action-btn secondary" onclick="showDuplicateModal(<?= $selectedRole['role_id'] ?>)">
                                <i class="fas fa-copy"></i>
                                Duplicate Role
                            </button>

                            <div class="mt-6 pt-4 border-t border-gray-200">
                                <h4 class="text-red-600 font-medium text-sm mb-3">Danger Zone</h4>
                                <button class="role-action-btn danger" onclick="deleteRole(<?= $selectedRole['role_id'] ?>)">
                                    <i class="fas fa-trash-alt"></i>
                                    Delete Role
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-8">
                                <p class="text-text-dark/50 text-sm">Select a role to perform actions</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<div id="addRoleModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4">Add New Role</h3>
        <form id="addRoleForm">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role Name</label>
                    <input type="text" name="role_name" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('addRoleModal')" class="px-4 py-2 border border-gray-300 rounded-md">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-secondary text-white rounded-md">Create Role</button>
            </div>
        </form>
    </div>
</div>

<div id="duplicateRoleModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4">Duplicate Role</h3>
        <form id="duplicateRoleForm">
            <input type="hidden" name="source_role_id" id="sourceRoleId">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Role Name</label>
                    <input type="text" name="new_role_name" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="new_description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('duplicateRoleModal')" class="px-4 py-2 border border-gray-300 rounded-md">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-secondary text-white rounded-md">Duplicate</button>
            </div>
        </form>
    </div>
</div>

<style>
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

    .permission-toggle input:checked+.permission-slider {
        background-color: #97CF82;
    }

    .permission-toggle input:checked+.permission-slider:before {
        transform: translateX(22px);
    }

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
        cursor: pointer;
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

    /* Role color classes */
    .role-color-customer {
        background: linear-gradient(135deg, #3B82F6, #60A5FA);
    }

    .role-color-support {
        background: linear-gradient(135deg, #8B5CF6, #A78BFA);
    }

    .role-color-department {
        background: linear-gradient(135deg, #10B981, #34D399);
    }

    .role-color-admin {
        background: linear-gradient(135deg, #F59E0B, #FBBF24);
    }

    .role-color-custom {
        background: linear-gradient(135deg, #6B7280, #9CA3AF);
    }
</style>

<script>
    // CSRF Token untuk AJAX requests
    const csrfToken = '<?= csrf_hash() ?>';
    const csrfName = '<?= csrf_token() ?>';
    const baseUrl = '<?= base_url() ?>';

    let currentRoleId = <?= json_encode($selectedRole['role_id'] ?? null) ?>;
    let isLoading = false;

    document.addEventListener('DOMContentLoaded', function() {
        initEventListeners();

        // Jika ada role yang dipilih, load detail via AJAX
        if (currentRoleId) {
            loadRoleDetails(currentRoleId);
        }
    });

    function initEventListeners() {
        // Search functionality
        const roleSearch = document.getElementById('roleSearch');
        if (roleSearch) {
            roleSearch.addEventListener('input', debounce(searchRoles, 300));
        }

        // Add role button
        const addRoleBtn = document.getElementById('addRoleBtn');
        if (addRoleBtn) {
            addRoleBtn.addEventListener('click', () => showModal('addRoleModal'));
        }

        // Add role form
        const addRoleForm = document.getElementById('addRoleForm');
        if (addRoleForm) {
            addRoleForm.addEventListener('submit', handleAddRole);
        }

        // Duplicate role form
        const duplicateRoleForm = document.getElementById('duplicateRoleForm');
        if (duplicateRoleForm) {
            duplicateRoleForm.addEventListener('submit', handleDuplicateRole);
        }
    }

    function selectRole(roleId) {
        if (isLoading || !roleId) return;

        // Update UI untuk selected state
        document.querySelectorAll('.role-item').forEach(item => {
            const itemRoleId = parseInt(item.dataset.roleId);
            if (itemRoleId === parseInt(roleId)) {
                item.classList.add('selected', 'bg-secondary', 'text-white');
                item.classList.remove('bg-white', 'border', 'border-gray-200');

                const badge = item.querySelector('.role-badge');
                if (badge) {
                    badge.classList.add('bg-white/20', 'text-white');
                    badge.classList.remove('bg-gray-100', 'text-text-dark');
                }

                const text = item.querySelector('.text-xs');
                if (text) {
                    text.classList.add('text-white/70');
                    text.classList.remove('text-text-dark/50');
                }
            } else {
                item.classList.remove('selected', 'bg-secondary', 'text-white');
                item.classList.add('bg-white', 'border', 'border-gray-200');

                const badge = item.querySelector('.role-badge');
                if (badge) {
                    badge.classList.remove('bg-white/20', 'text-white');
                    badge.classList.add('bg-gray-100', 'text-text-dark');
                }

                const text = item.querySelector('.text-xs');
                if (text) {
                    text.classList.remove('text-white/70');
                    text.classList.add('text-text-dark/50');
                }
            }
        });

        // Load role details
        currentRoleId = roleId;
        loadRoleDetails(roleId);
    }

    function loadRoleDetails(roleId) {
        if (isLoading) return;

        showLoading();

        fetch(`${baseUrl}/admin/manageRoles`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    [csrfName]: csrfToken,
                    action: 'get_role_details',
                    role_id: roleId
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    updateRoleDetailsUI(data);
                    showToast('Role loaded successfully', 'success');
                } else {
                    showToast(data.message || 'Failed to load role details', 'error');
                    revertToInitialState();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Network error occurred', 'error');
                revertToInitialState();
            })
            .finally(() => {
                hideLoading();
            });
    }

    function updateRoleDetailsUI(data) {
        const role = data.role;

        // Update basic info
        document.getElementById('selectedRoleName').textContent = role.role_name;
        document.getElementById('selectedUserCount').textContent = role.user_count || 0;

        // Update permission summary
        document.getElementById('permissionPercentage').textContent = data.permission_summary.percentage + '%';
        document.getElementById('permissionProgress').style.width = data.permission_summary.percentage + '%';
        document.getElementById('allowedCount').textContent = data.permission_summary.allowed + ' allowed';
        document.getElementById('deniedCount').textContent = data.permission_summary.denied + ' denied';
        document.getElementById('totalCount').textContent = data.permission_summary.total + ' total';

        // Get permissions via AJAX
        loadRolePermissions(role.role_id);
    }

    function loadRolePermissions(roleId) {
        fetch(`${baseUrl}/admin/manageRoles`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    [csrfName]: csrfToken,
                    action: 'get_role_permissions',
                    role_id: roleId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updatePermissionsUI(data.permissions, data.summary);
                }
            })
            .catch(error => {
                console.error('Error loading permissions:', error);
            });
    }

    function updatePermissionsUI(permissions, summary) {
        // Update permission summary
        if (summary) {
            document.getElementById('permissionPercentage').textContent = summary.percentage + '%';
            document.getElementById('permissionProgress').style.width = summary.percentage + '%';
            document.getElementById('allowedCount').textContent = summary.allowed + ' allowed';
            document.getElementById('deniedCount').textContent = summary.denied + ' denied';
            document.getElementById('totalCount').textContent = summary.total + ' total';
        }

        // Update each permission section
        updatePermissionSection('userPermissions', permissions.user || []);
        updatePermissionSection('communicationPermissions', permissions.communication || []);
        updatePermissionSection('ticketPermissions', permissions.ticket || []);
        updateSystemAccessSection('systemAccessRules', permissions.system || []);
    }

    function updatePermissionSection(sectionId, permissions) {
        const container = document.getElementById(sectionId);
        if (!container) return;

        let html = '';

        if (permissions.length > 0) {
            permissions.forEach(perm => {
                html += `
                    <div class="permission-item">
                        <div class="permission-info">
                            <i class="fas ${getPermissionIcon(perm.module)} permission-icon"></i>
                            <div>
                                <div class="permission-label">${perm.name}</div>
                            </div>
                        </div>
                        <label class="permission-toggle">
                            <input type="checkbox" ${perm.is_allowed ? 'checked' : ''} 
                                   data-permission-key="${perm.key}" data-module="${perm.module}">
                            <span class="permission-slider"></span>
                        </label>
                    </div>
                `;
            });
        } else {
            html = '<div class="text-center py-4"><p class="text-text-dark/50 text-sm">No permissions found</p></div>';
        }

        container.innerHTML = html;
    }

    function updateSystemAccessSection(sectionId, permissions) {
        const container = document.getElementById(sectionId);
        if (!container) return;

        let html = '';

        if (permissions.length > 0) {
            permissions.forEach(perm => {
                html += `
                    <div class="flex items-center gap-3 py-2">
                        <i class="fas ${perm.is_allowed ? 'fa-check text-green-500' : 'fa-ban text-red-400'}"></i>
                        <span class="text-text-dark/60 text-sm">
                            ${perm.is_allowed ? 'Can access' : 'Cannot access'} ${perm.name.toLowerCase()}
                        </span>
                    </div>
                `;
            });
        } else {
            html = '<div class="text-center py-4"><p class="text-text-dark/50 text-sm">No system access rules found</p></div>';
        }

        container.innerHTML = html;
    }

    function getPermissionIcon(module) {
        const icons = {
            'user': 'fa-user',
            'communication': 'fa-comments',
            'ticket': 'fa-ticket-alt',
            'system': 'fa-cog'
        };
        return icons[module] || 'fa-key';
    }

    function revertToInitialState() {
        document.getElementById('selectedRoleName').textContent = 'Select a Role';
        document.getElementById('selectedUserCount').textContent = '0';
        document.getElementById('permissionPercentage').textContent = '0%';
        document.getElementById('permissionProgress').style.width = '0%';
        document.getElementById('allowedCount').textContent = '0 allowed';
        document.getElementById('deniedCount').textContent = '0 denied';
        document.getElementById('totalCount').textContent = '0 total';

        // Clear all sections
        ['accessScope', 'userPermissions', 'communicationPermissions',
            'ticketPermissions', 'systemAccessRules', 'coreResponsibilities',
            'roleRules'
        ].forEach(sectionId => {
            const el = document.getElementById(sectionId);
            if (el) {
                el.innerHTML = '<div class="text-center py-4"><p class="text-text-dark/50 text-sm">Select a role to view details</p></div>';
            }
        });
    }

    function saveRolePermissions() {
        if (!currentRoleId) {
            showToast('Please select a role first', 'error');
            return;
        }

        // Collect all permission changes
        const permissions = [];
        document.querySelectorAll('.permission-toggle input').forEach(input => {
            permissions.push({
                key: input.dataset.permissionKey,
                is_allowed: input.checked
            });
        });

        showLoading();

        fetch(`${baseUrl}/admin/manageRoles`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    [csrfName]: csrfToken,
                    action: 'update_role_permissions',
                    role_id: currentRoleId,
                    permissions: permissions
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Permissions saved successfully', 'success');
                    // Reload role details
                    loadRoleDetails(currentRoleId);
                } else {
                    showToast(data.message || 'Failed to save permissions', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Network error occurred', 'error');
            })
            .finally(() => {
                hideLoading();
            });
    }

    function resetRolePermissions(roleId) {
        if (!confirm('Are you sure you want to reset this role to default permissions?')) {
            return;
        }

        showLoading();

        fetch(`${baseUrl}/admin/manageRoles`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    [csrfName]: csrfToken,
                    action: 'reset_role',
                    role_id: roleId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Role permissions reset to default', 'success');
                    loadRoleDetails(roleId);
                } else {
                    showToast(data.message || 'Failed to reset role', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Network error occurred', 'error');
            })
            .finally(() => {
                hideLoading();
            });
    }

    function showDuplicateModal(roleId) {
        document.getElementById('sourceRoleId').value = roleId;
        showModal('duplicateRoleModal');
    }

    function handleDuplicateRole(e) {
        e.preventDefault();

        const formData = new FormData(e.target);
        const data = {
            [csrfName]: csrfToken,
            action: 'duplicate_role',
            role_id: formData.get('source_role_id'),
            new_role_name: formData.get('new_role_name'),
            new_description: formData.get('new_description')
        };

        showLoading();
        closeModal('duplicateRoleModal');

        fetch(`${baseUrl}/admin/manageRoles`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Role duplicated successfully', 'success');
                    // Reload page to show new role
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message || 'Failed to duplicate role', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Network error occurred', 'error');
            })
            .finally(() => {
                hideLoading();
            });
    }

    function deleteRole(roleId) {
        if (!confirm('Are you sure you want to delete this role? This action cannot be undone.')) {
            return;
        }

        showLoading();

        fetch(`${baseUrl}/admin/manageRoles`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    [csrfName]: csrfToken,
                    action: 'delete_role',
                    role_id: roleId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Role deleted successfully', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message || 'Failed to delete role', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Network error occurred', 'error');
            })
            .finally(() => {
                hideLoading();
            });
    }

    function handleAddRole(e) {
        e.preventDefault();

        const formData = new FormData(e.target);
        const data = {
            [csrfName]: csrfToken,
            action: 'save_role',
            role_name: formData.get('role_name'),
            description: formData.get('description')
        };

        showLoading();
        closeModal('addRoleModal');

        fetch(`${baseUrl}/admin/manageRoles`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Role created successfully', 'success');
                    // Reload page to show new role
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message || 'Failed to create role', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Network error occurred', 'error');
            })
            .finally(() => {
                hideLoading();
            });
    }

    function searchRoles() {
        const searchTerm = document.getElementById('roleSearch').value.toLowerCase().trim();
        const roleItems = document.querySelectorAll('.role-item');

        roleItems.forEach(item => {
            const roleName = item.querySelector('span.font-medium')?.textContent.toLowerCase() || '';
            const display = roleName.includes(searchTerm) || searchTerm === '' ? 'block' : 'none';
            item.style.display = display;
        });
    }

    function showModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.getElementById(modalId + 'Form')?.reset();
    }

    // Utility functions
    function showLoading() {
        isLoading = true;
        document.getElementById('loadingOverlay').classList.remove('hidden');
    }

    function hideLoading() {
        isLoading = false;
        document.getElementById('loadingOverlay').classList.add('hidden');
    }

    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed top-6 right-6 px-4 py-3 rounded-lg shadow-lg z-50 animate-slideInRight ${type === 'error' ? 'bg-red-500' : type === 'success' ? 'bg-green-500' : 'bg-blue-500'} text-white`;
        toast.innerHTML = `
            <div class="flex items-center gap-2">
                <i class="fas ${type === 'error' ? 'fa-exclamation-circle' : type === 'success' ? 'fa-check-circle' : 'fa-info-circle'}"></i>
                <span>${message}</span>
            </div>
        `;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('animate-slideOutRight');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
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
</script>

<style>
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }

        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    .animate-slideInRight {
        animation: slideInRight 0.3s ease-out;
    }

    .animate-slideOutRight {
        animation: slideOutRight 0.3s ease-in;
    }
</style>
<?= $this->endSection() ?>