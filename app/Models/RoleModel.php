<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Database\Postgre\Builder;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class RoleModel extends Model
{
    public $db;
    protected $table = 'roles';
    protected $primaryKey = 'role_id';
    protected $allowedFields = [
        'role_name',
        'description'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    /**
     * Get all roles with user count
     */
    public function getAllRolesWithCount()
    {
        $db = db_connect();

        return $db->table('roles r')
            ->select('r.*, COUNT(u.user_id) as user_count')
            ->join('users u', 'u.role_id = r.role_id', 'left')
            ->groupBy('r.role_id')
            ->orderBy('r.role_name', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get role name by ID
     */
    public function getRoleName($roleId)
    {
        return $this->where('role_id', $roleId)->first();
    }

    /**
     * Get role by ID with user count
     */
    public function getRoleById($roleId)
    {
        $db = db_connect();

        return $db->table('roles r')
            ->select('r.*, COUNT(u.user_id) as user_count')
            ->join('users u', 'u.role_id = r.role_id', 'left')
            ->where('r.role_id', $roleId)
            ->groupBy('r.role_id')
            ->get()
            ->getRowArray();
    }

    /**
     * Delete role
     */
    public function deleteRole($roleId)
    {
        try {
            // Cek apakah ada user yang menggunakan role ini
            $db = db_connect();
            $userCount = $db->table('users')
                ->where('role_id', $roleId)
                ->countAllResults();

            if ($userCount > 0) {
                return [
                    'success' => false,
                    'message' => "Cannot delete role. There are {$userCount} users assigned to this role."
                ];
            }

            // Hapus role
            if ($this->delete($roleId)) {
                return [
                    'success' => true,
                    'message' => 'Role deleted successfully'
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to delete role'
            ];
        } catch (\Exception $e) {
            log_message('error', 'Delete role error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Duplicate role
     */
    public function duplicateRole($roleId, $newRoleName, $newDescription)
    {
        try {
            // Get existing role
            $existingRole = $this->find($roleId);
            if (!$existingRole) {
                return [
                    'success' => false,
                    'message' => 'Source role not found'
                ];
            }

            // Cek duplikat nama
            $duplicate = $this->where('role_name', $newRoleName)->first();
            if ($duplicate) {
                return [
                    'success' => false,
                    'message' => 'Role name already exists'
                ];
            }

            // Create new role
            $newRoleData = [
                'role_name' => $newRoleName,
                'description' => $newDescription ?: $existingRole['description'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($this->insert($newRoleData)) {
                $newRoleId = $this->getInsertID();
                return [
                    'success' => true,
                    'message' => 'Role duplicated successfully',
                    'new_role_id' => $newRoleId
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to duplicate role'
            ];
        } catch (\Exception $e) {
            log_message('error', 'Duplicate role error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get role templates (core roles)
     */
    public function getRoleTemplates()
    {
        return $this->where('is_core', true)
            ->orderBy('role_name', 'ASC')
            ->findAll();
    }

    /**
     * Get role rules
     */
    public function getRoleRules($roleId)
    {
        $rules = [];
        $role = $this->find($roleId);

        if ($role) {
            if ($role['is_core']) {
                $rules[] = 'This is a core system role';
                $rules[] = 'Cannot be deleted';
                $rules[] = 'Some permissions are locked';
            }

            switch ($role['access_level']) {
                case 'external':
                    $rules[] = 'Access limited to own data only';
                    $rules[] = 'Cannot access internal systems';
                    break;
                case 'internal':
                    $rules[] = 'Can access department-specific data';
                    $rules[] = 'Limited system access';
                    break;
                case 'full':
                    $rules[] = 'Full system access';
                    $rules[] = 'Can manage all aspects of the system';
                    break;
            }

            $rules[] = 'Changes affect all users with this role';
        }

        return $rules;
    }

    /**
     * Get core responsibilities
     */
    public function getCoreResponsibilities($roleName)
    {
        $responsibilities = [
            'Customer' => [
                'Submit problem reports or requests',
                'Monitor ticket progress',
                'Communicate with support team',
                'Confirm issue resolution'
            ],
            'Support' => [
                'Handle incoming support requests',
                'Assign tickets to appropriate departments',
                'Communicate with customers',
                'Resolve basic technical issues'
            ],
            'Department' => [
                'Handle department-specific tickets',
                'Collaborate with support team',
                'Provide technical expertise',
                'Update ticket status and notes'
            ],
            'Admin' => [
                'System configuration and management',
                'User and role management',
                'Monitor system performance',
                'Generate reports and analytics'
            ]
        ];

        return $responsibilities[$roleName] ?? ['Custom responsibilities based on role needs'];
    }

    /**
     * Generate color class based on access level
     */
    public function generateColorClass($accessLevel)
    {
        $colors = [
            'external' => 'role-color-external',
            'internal' => 'role-color-internal',
            'full' => 'role-color-full',
            'technical' => 'role-color-technical'
        ];

        return $colors[$accessLevel] ?? 'role-color-internal';
    }

    /**
     * Before insert callback
     */
    protected function beforeInsert(array $data)
    {
        if (!isset($data['data']['color_class'])) {
            $data['data']['color_class'] = $this->generateColorClass($data['data']['access_level'] ?? 'internal');
        }

        if (!isset($data['data']['is_core'])) {
            $data['data']['is_core'] = false;
        }

        return $data;
    }

    /**
     * Before update callback
     */
    protected function beforeUpdate(array $data)
    {
        if (isset($data['data']['access_level']) && !isset($data['data']['color_class'])) {
            $data['data']['color_class'] = $this->generateColorClass($data['data']['access_level']);
        }

        return $data;
    }

    /**
     * Get access level name
     */
    public function getAccessLevelName($level)
    {
        $levels = [
            'external' => 'External Access',
            'internal' => 'Internal Access',
            'full' => 'Full Access',
            'technical' => 'Technical Access'
        ];

        return $levels[$level] ?? $level;
    }

    public function getSystemNotifications(TicketModel $ticketModel): array
    {
        $notifications = [];

        // Check for high priority tickets
        $highPriorityTickets = $ticketModel->getHighPriorityTicketsCount();

        if ($highPriorityTickets > 5) {
            $notifications[] = [
                'type' => 'warning',
                'icon' => 'exclamation-triangle',
                'color' => '#FFB400',
                'title' => 'High Priority Tickets',
                'message' => "There are {$highPriorityTickets} high priority tickets requiring attention",
                'time' => 'Just now'
            ];
        }

        // Check for SLA violations
        $slaViolations = $ticketModel->getSLAViolationsCount();

        if ($slaViolations > 0) {
            $notifications[] = [
                'type' => 'danger',
                'icon' => 'clock',
                'color' => '#FF4C51',
                'title' => 'SLA Violations',
                'message' => "{$slaViolations} tickets have exceeded SLA time",
                'time' => '1h ago'
            ];
        }

        // System info
        $notifications[] = [
            'type' => 'info',
            'icon' => 'info-circle',
            'color' => '#9155FD',
            'title' => 'System Update',
            'message' => 'Scheduled maintenance tonight at 10:00 PM',
            'time' => '2h ago'
        ];

        return $notifications;
    }

    // ==================== PERMISSION MANAGEMENT METHODS ====================

    /**
     * Get role permissions from database
     */
    public function getRolePermissions($roleId, $groupByModule = false)
    {
        $permissions = $this->db->table('role_permissions')
            ->where('role_id', $roleId)
            ->orderBy('module', 'ASC')
            ->orderBy('permission_name', 'ASC')
            ->get()
            ->getResultArray();

        if ($groupByModule) {
            return $this->groupPermissionsByModule($permissions);
        }

        return $permissions;
    }

    /**
     * Get all available permissions grouped by module
     */
    public function getAllPermissions()
    {
        return [
            'user' => [
                ['key' => 'view_own_profile', 'name' => 'View own profile', 'description' => 'Can view their own profile information'],
                ['key' => 'update_own_profile', 'name' => 'Update own profile', 'description' => 'Can update their own profile information'],
                ['key' => 'change_password', 'name' => 'Change Password', 'description' => 'Can change their own password'],
                ['key' => 'view_all_profiles', 'name' => 'View all profiles', 'description' => 'Can view profiles of all users'],
                ['key' => 'update_all_profiles', 'name' => 'Update all profiles', 'description' => 'Can update profiles of all users'],
                ['key' => 'reset_passwords', 'name' => 'Reset passwords', 'description' => 'Can reset passwords for other users'],
                ['key' => 'manage_users', 'name' => 'Manage users', 'description' => 'Can create, edit, and delete users']
            ],
            'communication' => [
                ['key' => 'send_messages', 'name' => 'Send messages in ticket conversation', 'description' => 'Can send messages in ticket conversations'],
                ['key' => 'view_replies', 'name' => 'View replies from Support & Departments', 'description' => 'Can view replies from support team and departments'],
                ['key' => 'internal_messages', 'name' => 'Send internal-only messages', 'description' => 'Can send internal messages visible only to staff'],
                ['key' => 'system_messages', 'name' => 'Send system messages', 'description' => 'Can send system-wide messages and announcements']
            ],
            'ticket' => [
                ['key' => 'view_own_tickets', 'name' => 'View own tickets only', 'description' => 'Can view only tickets they created'],
                ['key' => 'view_department_tickets', 'name' => 'View department tickets', 'description' => 'Can view tickets assigned to their department'],
                ['key' => 'view_all_tickets', 'name' => 'View all tickets', 'description' => 'Can view all tickets in the system'],
                ['key' => 'create_ticket', 'name' => 'Create ticket', 'description' => 'Can create new tickets'],
                ['key' => 'reply_ticket', 'name' => 'Reply to ticket', 'description' => 'Can reply to existing tickets'],
                ['key' => 'upload_attachments', 'name' => 'Upload attachments', 'description' => 'Can upload attachments to tickets'],
                ['key' => 'view_status', 'name' => 'View ticket status & progress', 'description' => 'Can view ticket status and progress'],
                ['key' => 'assign_ticket', 'name' => 'Assign ticket', 'description' => 'Can assign tickets to other users'],
                ['key' => 'change_priority', 'name' => 'Change ticket priority', 'description' => 'Can change ticket priority'],
                ['key' => 'change_status', 'name' => 'Change ticket status', 'description' => 'Can change ticket status'],
                ['key' => 'change_notes', 'name' => 'Change ticket notes', 'description' => 'Can edit internal ticket notes']
            ],
            'system' => [
                ['key' => 'access_admin_dashboard', 'name' => 'Access admin dashboard', 'description' => 'Can access the admin dashboard'],
                ['key' => 'access_support_dashboard', 'name' => 'Access support dashboard', 'description' => 'Can access the support dashboard'],
                ['key' => 'access_department_dashboard', 'name' => 'Access department dashboard', 'description' => 'Can access department-specific dashboards'],
                ['key' => 'access_reports', 'name' => 'Access reports', 'description' => 'Can access system reports'],
                ['key' => 'access_sla_data', 'name' => 'Access SLA data', 'description' => 'Can access SLA (Service Level Agreement) data']
            ]
        ];
    }

    /**
     * Update role permissions
     */
    public function updateRolePermissions($roleId, $permissions)
    {
        try {
            $this->db->transStart();

            // Delete existing permissions
            $this->db->table('role_permissions')->where('role_id', $roleId)->delete();

            // Insert new permissions
            $permissionData = [];
            foreach ($permissions as $permission) {
                $permissionData[] = [
                    'role_id' => $roleId,
                    'permission_key' => $permission['key'],
                    'permission_name' => $permission['name'],
                    'module' => $permission['module'],
                    'is_allowed' => $permission['is_allowed'] ?? true,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
            }

            if (!empty($permissionData)) {
                $this->db->table('role_permissions')->insertBatch($permissionData);
            }

            $this->db->transComplete();

            return $this->db->transStatus();
        } catch (\Exception $e) {
            log_message('error', 'Update role permissions error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Initialize default permissions for a role
     */
    public function initializeDefaultPermissions($roleId, $roleName)
    {
        try {
            $defaultPermissions = $this->getDefaultPermissionsByRole($roleName);
            $allPermissions = $this->getAllPermissions();

            $permissionData = [];
            foreach ($allPermissions as $module => $permissions) {
                foreach ($permissions as $perm) {
                    $isAllowed = in_array($perm['key'], $defaultPermissions[$module] ?? []);

                    $permissionData[] = [
                        'role_id' => $roleId,
                        'permission_key' => $perm['key'],
                        'permission_name' => $perm['name'],
                        'module' => $module,
                        'is_allowed' => $isAllowed,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }
            }

            if (!empty($permissionData)) {
                return $this->db->table('role_permissions')->insertBatch($permissionData);
            }

            return false;
        } catch (\Exception $e) {
            log_message('error', 'Initialize default permissions error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if role has specific permission
     */
    public function hasPermission($roleId, $permissionKey)
    {
        $result = $this->db->table('role_permissions')
            ->where('role_id', $roleId)
            ->where('permission_key', $permissionKey)
            ->where('is_allowed', true)
            ->countAllResults();

        return $result > 0;
    }

    /**
     * Get permissions with status for a role
     */
    public function getPermissionsWithStatus($roleId)
    {
        $allPermissions = $this->getAllPermissions();
        $rolePermissions = $this->getRolePermissions($roleId);

        // Create a lookup array for role permissions
        $rolePermissionLookup = [];
        foreach ($rolePermissions as $perm) {
            $rolePermissionLookup[$perm['permission_key']] = $perm['is_allowed'];
        }

        // Combine all permissions with role's permission status
        $result = [];
        foreach ($allPermissions as $module => $permissions) {
            $result[$module] = [];
            foreach ($permissions as $perm) {
                $perm['is_allowed'] = $rolePermissionLookup[$perm['key']] ?? false;
                $result[$module][] = $perm;
            }
        }

        return $result;
    }

    /**
     * Reset role permissions to defaults
     */
    public function resetRolePermissions($roleId)
    {
        try {
            $role = $this->find($roleId);
            if (!$role) {
                return [
                    'success' => false,
                    'message' => 'Role not found'
                ];
            }

            if ($this->initializeDefaultPermissions($roleId, $role['role_name'])) {
                return [
                    'success' => true,
                    'message' => 'Role permissions reset to defaults successfully'
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to reset permissions'
            ];
        } catch (\Exception $e) {
            log_message('error', 'Reset role permissions error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get permission summary for a role
     */
    public function getPermissionSummary($roleId)
    {
        $permissions = $this->getRolePermissions($roleId);

        $total = count($permissions);
        $allowed = 0;
        foreach ($permissions as $perm) {
            if ($perm['is_allowed']) {
                $allowed++;
            }
        }

        return [
            'total' => $total,
            'allowed' => $allowed,
            'denied' => $total - $allowed,
            'percentage' => $total > 0 ? round(($allowed / $total) * 100) : 0
        ];
    }

    // ==================== PERMISSION BULK OPERATIONS ====================

    /**
     * Update multiple permissions at once
     */
    public function updateBulkPermissions($roleId, array $permissionUpdates)
    {
        try {
            $this->db->transStart();

            foreach ($permissionUpdates as $update) {
                $this->db->table('role_permissions')
                    ->where('role_id', $roleId)
                    ->where('permission_key', $update['key'])
                    ->update([
                        'is_allowed' => $update['is_allowed'],
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
            }

            $this->db->transComplete();

            return $this->db->transStatus();
        } catch (\Exception $e) {
            log_message('error', 'Update bulk permissions error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Copy permissions from one role to another
     */
    public function copyPermissions($sourceRoleId, $targetRoleId)
    {
        try {
            $sourcePermissions = $this->getRolePermissions($sourceRoleId);

            if (empty($sourcePermissions)) {
                return [
                    'success' => false,
                    'message' => 'Source role has no permissions to copy'
                ];
            }

            $this->db->transStart();

            // Delete existing permissions from target role
            $this->db->table('role_permissions')->where('role_id', $targetRoleId)->delete();

            // Insert copied permissions
            $permissionData = [];
            foreach ($sourcePermissions as $perm) {
                $permissionData[] = [
                    'role_id' => $targetRoleId,
                    'permission_key' => $perm['permission_key'],
                    'permission_name' => $perm['permission_name'],
                    'module' => $perm['module'],
                    'is_allowed' => $perm['is_allowed'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
            }

            $this->db->table('role_permissions')->insertBatch($permissionData);

            $this->db->transComplete();

            if ($this->db->transStatus()) {
                return [
                    'success' => true,
                    'message' => 'Permissions copied successfully',
                    'copied_count' => count($permissionData)
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to copy permissions'
            ];
        } catch (\Exception $e) {
            log_message('error', 'Copy permissions error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ];
        }
    }

    // ==================== PERMISSION VALIDATION ====================

    /**
     * Validate if user has permission
     */
    public function validateUserPermission($userId, $permissionKey)
    {
        try {
            $user = $this->db->table('users')
                ->select('role_id')
                ->where('user_id', $userId)
                ->get()
                ->getRowArray();

            if (!$user) {
                return false;
            }

            return $this->hasPermission($user['role_id'], $permissionKey);
        } catch (\Exception $e) {
            log_message('error', 'Validate user permission error: ' . $e->getMessage());
            return false;
        }
    }
}
