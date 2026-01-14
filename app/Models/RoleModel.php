<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'role_id';
    protected $allowedFields = [
        'role_name', 
        'description', 
        'access_level', 
        'color_class', 
        'is_core', 
        'permissions',
        'user_count'
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
     * Get permissions for a role
     */
    public function getRolePermissions($roleId)
    {
        $db = db_connect();
        
        return $db->table('role_permissions')
            ->where('role_id', $roleId)
            ->get()
            ->getResultArray();
    }
    
    /**
     * Update role permissions
     */
    public function updateRolePermissions($roleId, $permissions)
    {
        $db = db_connect();
        
        // Delete existing permissions
        $db->table('role_permissions')->where('role_id', $roleId)->delete();
        
        // Insert new permissions
        $permissionData = [];
        foreach ($permissions as $permission) {
            $permissionData[] = [
                'role_id' => $roleId,
                'permission_key' => $permission['key'],
                'permission_name' => $permission['name'],
                'module' => $permission['module'],
                'is_allowed' => $permission['is_allowed'],
                'created_at' => date('Y-m-d H:i:s')
            ];
        }
        
        if (!empty($permissionData)) {
            return $db->table('role_permissions')->insertBatch($permissionData);
        }
        
        return true;
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
     * Get all permissions grouped by module
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
}