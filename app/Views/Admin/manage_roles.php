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
function getAccessLevelName($level) {
    $levels = [
        'external' => 'External Access',
        'internal' => 'Internal Access',
        'full' => 'Full Access',
        'technical' => 'Technical Access'
    ];
    return $levels[$level] ?? $level;
}

function createPermissionItem($label, $icon, $enabled, $permissionKey = '') {
    $html = '
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
    return $html;
}

function createSystemAccessItem($label, $allowed) {
    $html = '
    <div class="flex items-center gap-3 py-2">
        <i class="fas ' . ($allowed ? 'fa-check text-green-500' : 'fa-ban text-red-400') . '"></i>
        <span class="text-text-dark/60 text-sm">' . ($allowed ? 'Can access' : 'Cannot access') . ' ' . strtolower($label) . '</span>
    </div>';
    return $html;
}

function getColorClass($accessLevel) {
    $colors = [
        'external' => 'role-color-external',
        'internal' => 'role-color-internal',
        'full' => 'role-color-full',
        'technical' => 'role-color-technical'
    ];
    return $colors[$accessLevel] ?? 'role-color-internal';
}

// Fungsi untuk mengurutkan roles sesuai urutan yang diinginkan
function sortRolesByPriority($roles) {
    $priorityOrder = [
        'Customer' => 1,
        'Support' => 2,
        'Department' => 3,
        'Admin' => 4
    ];
    
    usort($roles, function($a, $b) use ($priorityOrder) {
        $aPriority = $priorityOrder[$a['role_name']] ?? 999;
        $bPriority = $priorityOrder[$b['role_name']] ?? 999;
        
        return $aPriority - $bPriority;
    });
    
    return $roles;
}

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
                        <input type="text" 
                               placeholder="Search roles" 
                               id="roleSearch"
                               class="w-full h-12 pl-12 pr-4 bg-white rounded-xl border border-[#D1D1E9] text-text-dark text-sm focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all">
                    </div>
                </div>
                
                <!-- Roles List -->
                <div id="rolesList" class="space-y-2 p-2 max-h-[400px] overflow-y-auto">
                    <?php if (!empty($roles)): ?>
                        <?php foreach ($roles as $role): ?>
                            <div class="role-item <?= $role['role_id'] == ($selectedRole['role_id'] ?? 0) ? 'selected bg-secondary text-white' : 'bg-white border border-gray-200' ?>"
                                 data-role-id="<?= $role['role_id'] ?>"
                                 onclick="selectRole(<?= $role['role_id'] ?>)">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-3">
                                        <div class="role-dot <?= getColorClass($role['access_level'] ?? 'internal') ?>"></div>
                                        <span class="font-medium <?= $role['role_id'] == ($selectedRole['role_id'] ?? 0) ? 'text-white' : 'text-text-dark' ?>">
                                            <?= esc($role['role_name']) ?>
                                        </span>
                                    </div>
                                    <span class="role-badge <?= $role['role_id'] == ($selectedRole['role_id'] ?? 0) ? 'bg-white/20 text-white' : 'bg-gray-100 text-text-dark' ?>">
                                        <?= $role['user_count'] ?? 0 ?>
                                    </span>
                                </div>
                                <div class="<?= $role['role_id'] == ($selectedRole['role_id'] ?? 0) ? 'text-white/70' : 'text-text-dark/50' ?> text-xs ml-5">
                                    <?= getAccessLevelName($role['access_level'] ?? 'internal') ?>
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
                        Role: <span id="selectedRoleName" class="text-secondary"><?= esc($selectedRole['role_name'] ?? 'Select a Role') ?></span>
                    </div>
                    <div id="userCount" class="text-sm text-secondary">
                        <i class="fas fa-users mr-1"></i> <span><?= $selectedRole['user_count'] ?? 0 ?></span> users assigned
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
                                    <?php if ($selectedRole['access_level'] === 'external'): ?>
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
                                    <?php elseif ($selectedRole['access_level'] === 'internal'): ?>
                                        <div class="flex items-center gap-3">
                                            <i class="fas fa-users text-text-dark/40"></i>
                                            <span class="text-text-dark/70 text-sm">Department access</span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <i class="fas fa-project-diagram text-text-dark/40"></i>
                                            <span class="text-text-dark/70 text-sm">Department tickets</span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <i class="fas fa-comments text-text-dark/40"></i>
                                            <span class="text-text-dark/70 text-sm">Internal communication</span>
                                        </div>
                                    <?php else: ?>
                                        <div class="flex items-center gap-3">
                                            <i class="fas fa-unlock text-text-dark/40"></i>
                                            <span class="text-text-dark/70 text-sm">Full system access</span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <i class="fas fa-cogs text-text-dark/40"></i>
                                            <span class="text-text-dark/70 text-sm">All management features</span>
                                        </div>
                                    <?php endif; ?>
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
                                <?php if (!empty($rolePermissions)): ?>
                                    <?php foreach ($rolePermissions as $permission): ?>
                                        <?php if ($permission['module'] === 'user'): ?>
                                            <?= createPermissionItem($permission['permission_name'], 'fa-user', $permission['is_allowed'], $permission['permission_key']) ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php elseif ($selectedRole): ?>
                                    <div class="text-center py-4">
                                        <p class="text-text-dark/50 text-sm">Loading permissions...</p>
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
                                <?php if (!empty($rolePermissions)): ?>
                                    <?php foreach ($rolePermissions as $permission): ?>
                                        <?php if ($permission['module'] === 'communication'): ?>
                                            <?= createPermissionItem($permission['permission_name'], 'fa-comments', $permission['is_allowed'], $permission['permission_key']) ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php elseif ($selectedRole): ?>
                                    <div class="text-center py-4">
                                        <p class="text-text-dark/50 text-sm">Loading permissions...</p>
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
                                <?php if (!empty($rolePermissions)): ?>
                                    <?php foreach ($rolePermissions as $permission): ?>
                                        <?php if ($permission['module'] === 'ticket'): ?>
                                            <?= createPermissionItem($permission['permission_name'], 'fa-ticket-alt', $permission['is_allowed'], $permission['permission_key']) ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php elseif ($selectedRole): ?>
                                    <div class="text-center py-4">
                                        <p class="text-text-dark/50 text-sm">Loading permissions...</p>
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
                                <?php if (!empty($rolePermissions)): ?>
                                    <?php foreach ($rolePermissions as $permission): ?>
                                        <?php if ($permission['module'] === 'system'): ?>
                                            <?= createSystemAccessItem($permission['permission_name'], $permission['is_allowed']) ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php elseif ($selectedRole): ?>
                                    <div class="text-center py-4">
                                        <p class="text-text-dark/50 text-sm">Loading system access...</p>
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
                                <p class="text-text-dark/50 text-sm">Loading responsibilities...</p>
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
                                    <i class="fas <?= ($selectedRole['is_core'] ?? false) ? 'fa-lock' : 'fa-info-circle' ?> text-text-dark/40 mt-1"></i>
                                    <span class="text-text-dark/70 text-sm"><?= esc($rule) ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php elseif ($selectedRole): ?>
                            <div class="text-center py-4">
                                <p class="text-text-dark/50 text-sm">Loading rules...</p>
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
                            <button class="role-action-btn primary edit-role-btn" data-role-id="<?= $selectedRole['role_id'] ?>">
                                <i class="fas fa-edit"></i>
                                Edit Role Permissions
                            </button>
                            
                            <button class="role-action-btn secondary reset-role-btn" data-role-id="<?= $selectedRole['role_id'] ?>">
                                <i class="fas fa-undo-alt"></i>
                                Reset to Default
                            </button>
                            
                            <button class="role-action-btn secondary duplicate-role-btn" data-role-id="<?= $selectedRole['role_id'] ?>">
                                <i class="fas fa-copy"></i>
                                Duplicate Role
                            </button>
                            
                            <?php if (!($selectedRole['is_core'] ?? false)): ?>
                                <div class="mt-6 pt-4 border-t border-gray-200">
                                    <h4 class="text-red-600 font-medium text-sm mb-3">Danger Zone</h4>
                                    <button class="role-action-btn danger delete-role-btn" data-role-id="<?= $selectedRole['role_id'] ?>">
                                        <i class="fas fa-trash-alt"></i>
                                        Delete Role
                                    </button>
                                </div>
                            <?php endif; ?>
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

<!-- Modal Templates -->
<template id="editRoleModalTemplate">
    <!-- Modal akan dibuat secara dinamis di JavaScript -->
</template>

<template id="duplicateRoleModalTemplate">
    <!-- Modal akan dibuat secara dinamis di JavaScript -->
</template>

<template id="addRoleModalTemplate">
    <!-- Modal akan dibuat secara dinamis di JavaScript -->
</template>

<style>
    /* Semua CSS dari file sebelumnya tetap sama */
    .role-item { border-radius: 12px; padding: 16px; cursor: pointer; transition: all 0.3s ease; position: relative; overflow: hidden; }
    .role-item:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); }
    .role-item.selected { box-shadow: 0 0 0 2px #665C9E; }
    .role-dot { width: 12px; height: 12px; border-radius: 50%; flex-shrink: 0; }
    .role-badge { font-size: 12px; padding: 4px 8px; border-radius: 12px; font-weight: 500; }
    .permission-header { background: #EFE6FA; padding: 12px 16px; border-radius: 8px; margin-bottom: 12px; }
    .permission-section { transition: all 0.3s ease; }
    .permission-section:hover { transform: translateY(-2px); }
    .permission-toggle { position: relative; display: inline-block; width: 44px; height: 22px; }
    .permission-toggle input { opacity: 0; width: 0; height: 0; }
    .permission-slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px; }
    .permission-slider:before { position: absolute; content: ""; height: 16px; width: 16px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
    .permission-toggle input:checked + .permission-slider { background-color: #97CF82; }
    .permission-toggle input:checked + .permission-slider:before { transform: translateX(22px); }
    .permission-item { display: flex; align-items: center; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid rgba(0, 0, 0, 0.05); }
    .permission-item:last-child { border-bottom: none; }
    .permission-info { display: flex; align-items: center; gap: 10px; flex: 1; }
    .permission-icon { color: #6B7280; font-size: 14px; width: 20px; text-align: center; }
    .permission-label { font-size: 13px; color: #374151; }
    .role-action-btn { width: 100%; padding: 12px; border-radius: 12px; font-weight: 500; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s ease; border: 1px solid transparent; }
    .role-action-btn.primary { background: #665C9E; color: white; }
    .role-action-btn.primary:hover { background: #5A5190; transform: translateY(-1px); }
    .role-action-btn.secondary { background: white; color: #374151; border-color: #E5E7EB; }
    .role-action-btn.secondary:hover { background: #F9FAFB; transform: translateY(-1px); }
    .role-action-btn.danger { background: #FEF2F2; color: #DC2626; border-color: #FECACA; }
    .role-action-btn.danger:hover { background: #FEE2E2; transform: translateY(-1px); }
    /* Update warna untuk urutan baru */
    .role-color-external { background: linear-gradient(135deg, #3B82F6, #60A5FA); } /* Biru - Customer */
    .role-color-internal { background: linear-gradient(135deg, #8B5CF6, #A78BFA); } /* Ungu - Support & Department */
    .role-color-full { background: linear-gradient(135deg, #10B981, #34D399); } /* Hijau - Admin */
    .role-color-technical { background: linear-gradient(135deg, #F59E0B, #FBBF24); } /* Kuning - Technical */
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
            addRoleBtn.addEventListener('click', showAddRoleModal);
        }
        
        // Event delegation untuk role items
        document.addEventListener('click', function(e) {
            // Edit role button
            if (e.target.closest('.edit-role-btn')) {
                const roleId = e.target.closest('.edit-role-btn').dataset.roleId;
                showEditRoleModal(roleId);
            }
            
            // Reset role button
            if (e.target.closest('.reset-role-btn')) {
                const roleId = e.target.closest('.reset-role-btn').dataset.roleId;
                resetRole(roleId);
            }
            
            // Duplicate role button
            if (e.target.closest('.duplicate-role-btn')) {
                const roleId = e.target.closest('.duplicate-role-btn').dataset.roleId;
                showDuplicateRoleModal(roleId);
            }
            
            // Delete role button
            if (e.target.closest('.delete-role-btn')) {
                const roleId = e.target.closest('.delete-role-btn').dataset.roleId;
                deleteRole(roleId);
            }
        });
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
                // Fallback ke data awal jika ada
                if (roleId === currentRoleId) {
                    revertToInitialState();
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Network error occurred', 'error');
            if (roleId === currentRoleId) {
                revertToInitialState();
            }
        })
        .finally(() => {
            hideLoading();
        });
    }
    
    function updateRoleDetailsUI(data) {
        const role = data.role;
        
        // Update basic info
        const roleNameEl = document.getElementById('selectedRoleName');
        const userCountEl = document.getElementById('userCount');
        
        if (roleNameEl) roleNameEl.textContent = role.role_name;
        if (userCountEl) userCountEl.innerHTML = `<i class="fas fa-users mr-1"></i> ${role.user_count} users assigned`;
        
        // Update access scope
        updateAccessScope(role.access_level);
        
        // Update permissions
        updatePermissions(data.permissions);
        
        // Update core responsibilities
        updateCoreResponsibilities(data.responsibilities);
        
        // Update role rules
        updateRoleRules(data.rules, role.is_core);
        
        // Update action buttons
        updateActionButtons(role);
    }
    
    function revertToInitialState() {
        // Kembalikan ke state awal jika AJAX gagal
        document.getElementById('selectedRoleName').textContent = 'Select a Role';
        document.getElementById('userCount').innerHTML = '<i class="fas fa-users mr-1"></i> 0 users assigned';
        
        // Kosongkan semua section
        ['accessScope', 'userPermissions', 'communicationPermissions', 
         'ticketPermissions', 'systemAccessRules', 'coreResponsibilities', 
         'roleRules', 'roleActions'].forEach(sectionId => {
            const el = document.getElementById(sectionId);
            if (el) {
                el.innerHTML = '<div class="text-center py-4"><p class="text-text-dark/50 text-sm">Failed to load data</p></div>';
            }
        });
    }
    
    function updateAccessScope(accessLevel) {
        const accessScope = document.getElementById('accessScope');
        if (!accessScope) return;
        
        let html = '';
        
        switch(accessLevel) {
            case 'external':
                html = `
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
                `;
                break;
            case 'internal':
                html = `
                    <div class="flex items-center gap-3">
                        <i class="fas fa-users text-text-dark/40"></i>
                        <span class="text-text-dark/70 text-sm">Department access</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-project-diagram text-text-dark/40"></i>
                        <span class="text-text-dark/70 text-sm">Department tickets</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-comments text-text-dark/40"></i>
                        <span class="text-text-dark/70 text-sm">Internal communication</span>
                    </div>
                `;
                break;
            case 'full':
                html = `
                    <div class="flex items-center gap-3">
                        <i class="fas fa-unlock text-text-dark/40"></i>
                        <span class="text-text-dark/70 text-sm">Full system access</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-cogs text-text-dark/40"></i>
                        <span class="text-text-dark/70 text-sm">All management features</span>
                    </div>
                `;
                break;
            default:
                html = '<div class="text-center py-4"><p class="text-text-dark/50 text-sm">Access scope information not available</p></div>';
        }
        
        accessScope.innerHTML = html;
    }
    
    function updatePermissions(permissions) {
        if (!permissions) return;
        
        // Group permissions by module and update each section
        const modules = [
            { id: 'userPermissions', module: 'user', icon: 'fa-user' },
            { id: 'communicationPermissions', module: 'communication', icon: 'fa-comments' },
            { id: 'ticketPermissions', module: 'ticket', icon: 'fa-ticket-alt' },
            { id: 'systemAccessRules', module: 'system', icon: 'fa-cog' }
        ];
        
        modules.forEach(({ id, module, icon }) => {
            const container = document.getElementById(id);
            if (!container) return;
            
            let html = '';
            
            if (permissions[module] && permissions[module].length > 0) {
                permissions[module].forEach(perm => {
                    if (module === 'system') {
                        html += createSystemAccessItemHTML(perm);
                    } else {
                        html += createPermissionItemHTML(perm, icon);
                    }
                });
            } else {
                html = '<div class="text-center py-4"><p class="text-text-dark/50 text-sm">No permissions found</p></div>';
            }
            
            container.innerHTML = html;
            
            // Re-attach event listeners for permission toggles
            if (module !== 'system') {
                container.querySelectorAll('.permission-toggle input').forEach(input => {
                    input.addEventListener('change', function() {
                        updatePermission(this);
                    });
                });
            }
        });
    }
    
    function createPermissionItemHTML(permission, icon) {
        return `
            <div class="permission-item">
                <div class="permission-info">
                    <i class="fas ${icon} permission-icon"></i>
                    <div>
                        <div class="permission-label">${permission.permission_name}</div>
                    </div>
                </div>
                <label class="permission-toggle">
                    <input type="checkbox" ${permission.is_allowed ? 'checked' : ''} 
                           data-permission-key="${permission.permission_key}">
                    <span class="permission-slider"></span>
                </label>
            </div>
        `;
    }
    
    function createSystemAccessItemHTML(permission) {
        return `
            <div class="flex items-center gap-3 py-2">
                <i class="fas ${permission.is_allowed ? 'fa-check text-green-500' : 'fa-ban text-red-400'}"></i>
                <span class="text-text-dark/60 text-sm">
                    ${permission.is_allowed ? 'Can access' : 'Cannot access'} ${permission.permission_name.toLowerCase()}
                </span>
            </div>
        `;
    }
    
    function updateCoreResponsibilities(responsibilities) {
        const container = document.getElementById('coreResponsibilities');
        if (!container) return;
        
        let html = '';
        
        if (responsibilities && responsibilities.length > 0) {
            responsibilities.forEach(responsibility => {
                html += `
                    <div class="flex items-start gap-3 mb-3 last:mb-0">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <span class="text-text-dark/70 text-sm">${responsibility}</span>
                    </div>
                `;
            });
        } else {
            html = '<div class="text-center py-4"><p class="text-text-dark/50 text-sm">No responsibilities defined</p></div>';
        }
        
        container.innerHTML = html;
    }
    
    function updateRoleRules(rules, isCore) {
        const container = document.getElementById('roleRules');
        if (!container) return;
        
        let html = '';
        
        if (rules && rules.length > 0) {
            rules.forEach(rule => {
                html += `
                    <div class="flex items-start gap-3">
                        <i class="fas ${isCore ? 'fa-lock' : 'fa-info-circle'} text-text-dark/40 mt-1"></i>
                        <span class="text-text-dark/70 text-sm">${rule}</span>
                    </div>
                `;
            });
        } else {
            html = '<div class="text-center py-4"><p class="text-text-dark/50 text-sm">No rules defined</p></div>';
        }
        
        container.innerHTML = html;
    }
    
    function updateActionButtons(role) {
        const container = document.getElementById('roleActions');
        if (!container) return;
        
        let html = `
            <button class="role-action-btn primary edit-role-btn" data-role-id="${role.role_id}">
                <i class="fas fa-edit"></i>
                Edit Role Permissions
            </button>
            
            <button class="role-action-btn secondary reset-role-btn" data-role-id="${role.role_id}">
                <i class="fas fa-undo-alt"></i>
                Reset to Default
            </button>
            
            <button class="role-action-btn secondary duplicate-role-btn" data-role-id="${role.role_id}">
                <i class="fas fa-copy"></i>
                Duplicate Role
            </button>
        `;
        
        if (!role.is_core) {
            html += `
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <h4 class="text-red-600 font-medium text-sm mb-3">Danger Zone</h4>
                    <button class="role-action-btn danger delete-role-btn" data-role-id="${role.role_id}">
                        <i class="fas fa-trash-alt"></i>
                        Delete Role
                    </button>
                </div>
            `;
        }
        
        container.innerHTML = html;
    }
    
    function updatePermission(element) {
        const permissionKey = element.dataset.permissionKey;
        const isAllowed = element.checked;
        
        if (!currentRoleId || !permissionKey) return;
        
        // Tampilkan loading state
        const originalState = element.checked;
        element.disabled = true;
        
        fetch(`${baseUrl}/admin/manageRoles`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({
                [csrfName]: csrfToken,
                action: 'update_permission',
                role_id: currentRoleId,
                permission_key: permissionKey,
                is_allowed: isAllowed
            })
        })
        .then(response => response.json())
        .then(data => {
            element.disabled = false;
            
            if (data.success) {
                showToast('Permission updated successfully', 'success');
                
                // Reload role details untuk update semua section
                loadRoleDetails(currentRoleId);
            } else {
                // Revert the toggle
                element.checked = originalState;
                showToast(data.message || 'Failed to update permission', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            element.disabled = false;
            element.checked = originalState;
            showToast('Network error occurred', 'error');
        });
    }
    
    function searchRoles() {
        const searchTerm = document.getElementById('roleSearch').value.toLowerCase().trim();
        const roleItems = document.querySelectorAll('.role-item');
        
        let visibleCount = 0;
        
        roleItems.forEach(item => {
            const roleName = item.querySelector('span.font-medium')?.textContent.toLowerCase() || '';
            const accessLevel = item.querySelector('div.text-xs')?.textContent.toLowerCase() || '';
            
            if (searchTerm === '' || 
                roleName.includes(searchTerm) || 
                accessLevel.includes(searchTerm)) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });
        
        // Tampilkan pesan jika tidak ada hasil
        const rolesList = document.getElementById('rolesList');
        const noResultsMsg = rolesList.querySelector('.no-results-message');
        
        if (visibleCount === 0 && searchTerm !== '') {
            if (!noResultsMsg) {
                const msg = document.createElement('div');
                msg.className = 'no-results-message text-center py-8';
                msg.innerHTML = `
                    <i class="fas fa-search text-gray-300 text-3xl mb-3"></i>
                    <p class="text-gray-500 text-sm">No roles found matching "${searchTerm}"</p>
                `;
                rolesList.appendChild(msg);
            }
        } else if (noResultsMsg) {
            noResultsMsg.remove();
        }
    }
    
    function showAddRoleModal() {
        // Implementasi modal add role
        alert('Add Role modal akan diimplementasi');
    }
    
    function showEditRoleModal(roleId) {
        // Implementasi modal edit role
        alert(`Edit Role modal untuk role ID: ${roleId} akan diimplementasi`);
    }
    
    function showDuplicateRoleModal(roleId) {
        // Implementasi modal duplicate role
        alert(`Duplicate Role modal untuk role ID: ${roleId} akan diimplementasi`);
    }
    
    function resetRole(roleId) {
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
                // Reload halaman untuk update list roles
                setTimeout(() => {
                    location.reload();
                }, 1000);
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
    
    // Utility functions
    function showLoading() {
        isLoading = true;
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) overlay.classList.remove('hidden');
    }
    
    function hideLoading() {
        isLoading = false;
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) overlay.classList.add('hidden');
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
<?= $this->endSection() ?>