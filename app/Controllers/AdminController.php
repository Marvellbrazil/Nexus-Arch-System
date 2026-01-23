<?php

namespace App\Controllers;

use App\Helpers\Datatables\Datatables;
use App\Models\CategoryModel;
use App\Models\TicketModel;
use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\DepartmentModel;
use App\Models\ProjectAssignmentModel;
use App\Models\ProjectModel;
use App\Models\CategoryDepartmentMappingModel;

class AdminController extends BaseController
{
    protected $userModel;
    protected $roleModel;
    protected $ticketModel;
    protected $projectModel;
    protected $departmentModel;
    protected $projectAssignmentModel;
    protected $categoryModel;
    protected $cdm;

    public function __construct()
    {
        // Initialize models
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
        $this->ticketModel = new TicketModel();
        $this->projectModel = new ProjectModel();
        $this->departmentModel = new DepartmentModel();
        $this->projectAssignmentModel = new ProjectAssignmentModel();
        $this->categoryModel = new CategoryModel();
        $this->cdm = new CategoryDepartmentMappingModel();
    }

    /**
     * Load common data for all views
     */
    protected function loadCommonData(): array
    {
        return [
            'title' => 'NEXUS Admin',
            'user_id' => session()->get('user_id'),
            'full_name' => session()->get('full_name'),
            'username' => session()->get('username'),
            'email' => session()->get('email'),
            'role_name' => session()->get('role_name'),
            'is_admin' => session()->get('is_admin')
        ];
    }

    // ==================== DASHBOARD ===========================
    /**
     * Dashboard
     */
    public function dashboard()
    {
        $data = $this->loadCommonData();
        $data['title'] = 'Admin Dashboard - NEXUS';

        try {
            // ==================== STATISTIK REAL-TIME ====================
            // 1. Total Users (aktif dan inaktif)
            $db = db_connect();

            // Total semua user
            $totalUsers = $db->table('users')->countAllResults();

            // User aktif (boolean PostgreSQL: true/false)
            $activeUsers = $db->table('users')
                ->where('is_active', true)
                ->countAllResults();

            $inactiveUsers = $totalUsers - $activeUsers;

            // 2. Total Tickets dari database
            $totalTickets = $db->table('tickets')->countAllResults();

            // Tickets dengan status Open (status_id = 1) dan In Progress (status_id = 2)
            $openTickets = $db->table('tickets')
                ->whereIn('status_id', [1, 2])
                ->countAllResults();

            // 3. Total Projects aktif
            $totalProjects = $db->table('projects')
                ->where('is_active', true)
                ->countAllResults();

            // 4. Hitung trend ticket (minggu ini vs minggu lalu)
            $lastWeek = date('Y-m-d', strtotime('-7 days'));
            $twoWeeksAgo = date('Y-m-d', strtotime('-14 days'));

            $ticketsThisWeek = $db->table('tickets')
                ->where('created_at >=', $lastWeek)
                ->countAllResults();

            $ticketsLastWeek = $db->table('tickets')
                ->where('created_at >=', $twoWeeksAgo)
                ->where('created_at <', $lastWeek)
                ->countAllResults();

            $ticketTrend = $ticketsLastWeek > 0
                ? round((($ticketsThisWeek - $ticketsLastWeek) / $ticketsLastWeek) * 100, 1)
                : ($ticketsThisWeek > 0 ? 100 : 0);

            $ticketTrendText = ($ticketTrend >= 0 ? '+' : '') . $ticketTrend . '%';

            // 5. Hitung trend user
            $usersThisWeek = $db->table('users')
                ->where('created_at >=', $lastWeek)
                ->countAllResults();

            $usersLastWeek = $db->table('users')
                ->where('created_at >=', $twoWeeksAgo)
                ->where('created_at <', $lastWeek)
                ->countAllResults();

            $userTrend = $usersLastWeek > 0
                ? round((($usersThisWeek - $usersLastWeek) / $usersLastWeek) * 100, 1)
                : ($usersThisWeek > 0 ? 100 : 0);

            $userTrendText = ($userTrend >= 0 ? '+' : '') . $userTrend . '%';

            // ==================== DATA TICKET STATUS ====================
            $ticketStatusData = $db->table('tickets t')
                ->select('s.status_id, s.status_name, COUNT(t.ticket_id) as count')
                ->join('statuses s', 's.status_id = t.status_id')
                ->groupBy('s.status_id, s.status_name')
                ->orderBy('s.status_id')
                ->get()
                ->getResultArray();

            // Tambahkan warna untuk setiap status
            $statusColors = [
                1 => '#635A91', // Open - Ungu
                2 => '#AFB9D4', // In Progress - Biru Muda
                3 => '#EDE1C7', // Resolved - Krem
                4 => '#BDB7D9'  // Closed - Ungu Muda
            ];

            foreach ($ticketStatusData as &$status) {
                $status['color'] = $statusColors[$status['status_id']] ?? '#6B7280';
                $status['percentage'] = $totalTickets > 0
                    ? round(($status['count'] / $totalTickets) * 100, 1)
                    : 0;
            }

            // ==================== PROJECT OVERVIEW ====================
            $recentProjects = $db->table('projects p')
                ->select('p.*, 
                (SELECT COUNT(*) FROM tickets t WHERE t.project_id = p.project_id) as total_tickets,
                (SELECT COUNT(*) FROM tickets t WHERE t.project_id = p.project_id AND t.status_id IN (1,2)) as open_tickets')
                ->where('p.is_active', true)
                ->orderBy('p.created_at', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();

            // ==================== RECENT ACTIVITIES ====================
            $recentActivities = $db->table('tickets t')
                ->select('t.*, u.full_name as user_name, s.status_name, p.project_name')
                ->join('users u', 'u.user_id = t.customer_id')
                ->join('statuses s', 's.status_id = t.status_id', 'left')
                ->join('projects p', 'p.project_id = t.project_id', 'left')
                ->orderBy('t.created_at', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();

            // Format recent activities
            $formattedActivities = [];
            foreach ($recentActivities as $activity) {
                $formattedActivities[] = [
                    'time' => $this->formatTimeAgo($activity['created_at']),
                    'user' => $activity['user_name'] ?? 'Customer',
                    'action' => "created ticket #" . ($activity['ticket_number'] ?? $activity['ticket_id']),
                    'project' => $activity['project_name'] ?? null,
                    'avatar_color' => '#F0E9F9' // Warna default
                ];
            }

            // ==================== USERS BY ROLE ====================
            $usersByRole = $db->table('users u')
                ->select('r.role_name, COUNT(u.user_id) as count')
                ->join('roles r', 'r.role_id = u.role_id')
                ->where('u.is_active', true)
                ->groupBy('r.role_name')
                ->get()
                ->getResultArray();

            // ==================== SYSTEM NOTIFICATIONS ====================
            // Notifikasi untuk tickets high priority
            $highPriorityTickets = $db->table('tickets t')
                ->join('priorities p', 'p.priority_id = t.priority_id')
                ->whereIn('p.priority_name', ['High', 'Critical', 'Urgent'])
                ->whereIn('t.status_id', [1, 2]) // Open & In Progress
                ->countAllResults();

            // Tickets yang sudah melewati due date
            $overdueTickets = $db->table('tickets')
                ->where('due_date <', date('Y-m-d H:i:s'))
                ->whereIn('status_id', [1, 2])
                ->countAllResults();

            $systemNotifications = [];

            if ($highPriorityTickets > 0) {
                $systemNotifications[] = [
                    'title' => 'High Priority Tickets',
                    'message' => "{$highPriorityTickets} tickets require immediate attention",
                    'time' => 'Just now',
                    'color' => '#FF4C51',
                    'icon' => 'exclamation-triangle',
                    'type' => 'warning'
                ];
            }

            if ($overdueTickets > 0) {
                $systemNotifications[] = [
                    'title' => 'Overdue Tickets',
                    'message' => "{$overdueTickets} tickets have passed their due date",
                    'time' => '1 hour ago',
                    'color' => '#F59E0B',
                    'icon' => 'clock',
                    'type' => 'warning'
                ];
            }

            // ==================== PREPARE DATA UNTUK VIEW ====================
            $data['stats'] = [
                'total_users' => $totalUsers,
                'active_users' => $activeUsers,
                'inactive_users' => $inactiveUsers,
                'total_tickets' => $totalTickets,
                'open_tickets' => $openTickets,
                'total_projects' => $totalProjects,
                'user_trend' => $userTrendText,
                'ticket_trend' => $ticketTrendText
            ];

            $data['ticket_status'] = $ticketStatusData;
            $data['total_tickets_for_chart'] = $totalTickets;
            $data['recent_projects'] = $recentProjects;
            $data['recentActivities'] = $formattedActivities;
            $data['users_by_role'] = $usersByRole;
            $data['systemNotifications'] = $systemNotifications;

            // Quick links (tetap sama)
            $data['quickLinks'] = [
                [
                    'title' => 'Manage Users',
                    'icon' => 'fas fa-users',
                    'url' => '/admin/users',
                    'description' => 'Add, edit or remove users',
                    'color' => 'bg-blue-100 text-blue-600'
                ],
                [
                    'title' => 'Manage Roles',
                    'icon' => 'fas fa-user-tag',
                    'url' => '/admin/roles',
                    'description' => 'Configure user permissions',
                    'color' => 'bg-purple-100 text-purple-600'
                ],
                [
                    'title' => 'Manage Projects',
                    'icon' => 'fas fa-project-diagram',
                    'url' => '/admin/projects',
                    'description' => 'Create and manage projects',
                    'color' => 'bg-green-100 text-green-600'
                ],
                [
                    'title' => 'View Tickets',
                    'icon' => 'fas fa-ticket-alt',
                    'url' => '/admin/tickets',
                    'description' => 'Monitor all support tickets',
                    'color' => 'bg-yellow-100 text-yellow-600'
                ]
            ];
        } catch (\Exception $e) {
            log_message('error', 'Dashboard error: ' . $e->getMessage());

            // Fallback data
            $data['stats'] = [
                'total_users' => 0,
                'active_users' => 0,
                'inactive_users' => 0,
                'total_tickets' => 0,
                'open_tickets' => 0,
                'total_projects' => 0,
                'user_trend' => '0%',
                'ticket_trend' => '0%'
            ];

            $data['ticket_status'] = [];
            $data['total_tickets_for_chart'] = 0;
            $data['recent_projects'] = [];
            $data['recentActivities'] = [];
            $data['users_by_role'] = [];
            $data['systemNotifications'] = [];
            $data['quickLinks'] = [];
        }

        return view('Admin/dashboard', $data);
    }

    /**
     * Format time ago
     */
    private function formatTimeAgo(string $datetime): string
    {
        $time = strtotime($datetime);
        $now = time();
        $diff = $now - $time;

        if ($diff < 60) return 'Just now';
        if ($diff < 3600) return floor($diff / 60) . ' minutes ago';
        if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
        if ($diff < 604800) return floor($diff / 86400) . ' days ago';

        return date('M d, Y', $time);
    }


    // ==================== MANAGE USERS ===========================
    /**
     * Manage Users - Main method
     */
    public function manageUsers()
    {
        $data = $this->loadCommonData();
        $data['title'] = 'Manage Users - NEXUS Admin';

        // Get roles and departments for filters
        $data['roles'] = $this->roleModel->findAll();
        $data['departments'] = $this->departmentModel->findAll();

        // Get user statistics from model
        $data['userStats'] = $this->userModel->getUserStatistics();

        // Handle form submissions (non-AJAX)
        if ($this->request->getMethod() === 'post') {
            $action = $this->request->getPost('action');

            switch ($action) {
                case 'add_user':
                    return $this->addUser();
                case 'edit_user':
                    $id = $this->request->getPost('user_id');
                    return $this->editUser($id);
                case 'delete_user':
                    $id = $this->request->getPost('user_id');
                    return $this->deleteUser($id);
                case 'change_status':
                    $id = $this->request->getPost('user_id');
                    return $this->changeStatus($id);
                case 'reset_password':
                    $id = $this->request->getPost('user_id');
                    return $this->resetPassword($id);
            }
        }

        return view('Admin/manage_users', $data);
    }

    /**
     * Get user details (non-AJAX)
     */
    public function getUserDetails($id = null)
    {
        $data = $this->loadCommonData();
        $data['title'] = 'User Details - NEXUS Admin';

        $userId = $id ?: $this->request->getGet('user_id');

        if (!$userId) {
            return redirect()->to('/admin/users')->with('error', 'User ID is required');
        }

        try {
            $user = $this->userModel->getUserWithDetails($userId);

            if (!$user) {
                return redirect()->to('/admin/users')->with('error', 'User not found');
            }

            // Get user's assigned projects
            $projects = $this->userModel->getUserProjects($userId);
            $projectNames = array_column($projects, 'project_name');

            $data['user'] = $user;
            $data['user']['projects'] = $projectNames;

            return view('Admin/user_details', $data);
        } catch (\Exception $e) {
            log_message('error', 'User details error: ' . $e->getMessage());
            return redirect()->to('/admin/users')->with('error', 'Failed to load user details');
        }
    }

    /**
     * Add new user (non-AJAX)
     */
    public function addUser()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/admin/users');
        }

        try {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'username' => 'required|min_length[3]|max_length[50]',
                'full_name' => 'required|min_length[3]|max_length[100]',
                'email' => 'required|valid_email',
                'password' => 'required|min_length[6]',
                'role_id' => 'required|integer',
                'department_id' => 'permit_empty|integer',
                'phone_number' => 'permit_empty|max_length[20]'
            ]);

            // Custom validation for unique fields
            $validation->setRule('username', 'Username', 'is_unique[users.username]');
            $validation->setRule('email', 'Email', 'is_unique[users.email]');

            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->back()
                    ->withInput()
                    ->with('errors', $validation->getErrors());
            }

            $userData = [
                'username' => $this->request->getPost('username'),
                'full_name' => $this->request->getPost('full_name'),
                'email' => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'role_id' => $this->request->getPost('role_id'),
                'department_id' => $this->request->getPost('department_id') ?: null,
                'phone_number' => $this->request->getPost('phone_number'),
                'is_active' => $this->request->getPost('is_active') ? true : false
            ];

            if ($this->userModel->save($userData)) {
                return redirect()->to('/admin/users')
                    ->with('success', 'User added successfully')
                    ->with('user_id', $this->userModel->getInsertID());
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Failed to add user');
            }
        } catch (\Exception $e) {
            log_message('error', 'Add user error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    /**
     * Edit user (non-AJAX) - FIXED VERSION
     */
    public function editUser($id)
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/admin/users');
        }

        try {
            // Validasi minimal
            $validation = \Config\Services::validation();
            $validation->setRules([
                'username' => 'required|min_length[3]|max_length[50]',
                'full_name' => 'required|min_length[3]|max_length[100]',
                'email' => 'required|valid_email',
                'role_id' => 'required|integer',
            ]);

            // Custom validation untuk unique fields
            $validation->setRule('username', 'Username', "is_unique[users.username,user_id,{$id}]");
            $validation->setRule('email', 'Email', "is_unique[users.email,user_id,{$id}]");

            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->back()
                    ->withInput()
                    ->with('errors', $validation->getErrors());
            }

            // Siapkan data
            $userData = [
                'user_id' => $id,
                'username' => trim($this->request->getPost('username')),
                'full_name' => trim($this->request->getPost('full_name')),
                'email' => trim($this->request->getPost('email')),
                'role_id' => (int)$this->request->getPost('role_id'),
                'is_active' => $this->request->getPost('is_active') ? true : false,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Tambahkan optional fields jika ada
            $departmentId = $this->request->getPost('department_id');
            if ($departmentId !== null && $departmentId !== '') {
                $userData['department_id'] = (int)$departmentId;
            }

            $phoneNumber = $this->request->getPost('phone_number');
            if ($phoneNumber !== null && $phoneNumber !== '') {
                $userData['phone_number'] = trim($phoneNumber);
            }

            // Update password hanya jika disediakan dan tidak kosong
            $password = $this->request->getPost('password');
            if (!empty($password) && $password !== '') {
                $userData['password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            // Update user
            if ($this->userModel->save($userData)) {
                return redirect()->to('/admin/users')
                    ->with('success', 'User updated successfully');
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Failed to update user');
            }
        } catch (\Exception $e) {
            log_message('error', 'Edit user error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    /**
     * Reset password (non-AJAX)
     */
    public function resetPassword($id)
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/admin/users');
        }

        try {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'new_password' => 'required|min_length[6]',
                'confirm_password' => 'required|matches[new_password]'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->back()
                    ->withInput()
                    ->with('errors', $validation->getErrors());
            }

            $newPassword = $this->request->getPost('new_password');

            if ($this->userModel->update($id, ['password' => $newPassword])) {
                return redirect()->to('/admin/users')
                    ->with('success', 'Password reset successfully');
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Failed to reset password');
            }
        } catch (\Exception $e) {
            log_message('error', 'Reset password error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    /**
     * Change user status (non-AJAX)
     */
    public function changeStatus($id)
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/admin/users');
        }

        try {
            $status = $this->request->getPost('status');
            $isActive = ($status === 'active' || $status === '1') ? true : false;

            if ($this->userModel->changeStatus($id, $isActive)) {
                $statusText = $isActive ? 'activated' : 'deactivated';
                return redirect()->to('/admin/users')
                    ->with('success', "User {$statusText} successfully");
            } else {
                return redirect()->back()
                    ->with('error', 'Failed to change user status');
            }
        } catch (\Exception $e) {
            log_message('error', 'Change status error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    /**
     * Delete user (non-AJAX)
     */
    public function deleteUser($id)
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/admin/users');
        }

        try {
            // Prevent deleting yourself
            if ($id == session()->get('user_id')) {
                return redirect()->back()
                    ->with('error', 'Cannot delete your own account');
            }

            if ($this->userModel->delete($id)) {
                return redirect()->to('/admin/users')
                    ->with('success', 'User deleted successfully');
            } else {
                return redirect()->back()
                    ->with('error', 'Failed to delete user');
            }
        } catch (\Exception $e) {
            log_message('error', 'Delete user error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    /**
     * Export users to CSV
     */
    public function exportUsers()
    {
        try {
            // Get filters from query string
            $filters = [
                'search' => $this->request->getGet('search'),
                'role_id' => $this->request->getGet('role_id'),
                'department_id' => $this->request->getGet('department_id'),
                'is_active' => $this->request->getGet('is_active'),
                'date_from' => $this->request->getGet('date_from'),
                'date_to' => $this->request->getGet('date_to')
            ];

            // Get users data from model
            $users = $this->userModel->exportUsers($filters);

            // Set headers for CSV download
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="users_' . date('Y-m-d') . '.csv"');

            $output = fopen('php://output', 'w');

            // CSV headers
            fputcsv($output, ['ID', 'Username', 'Full Name', 'Email', 'Role', 'Department', 'Status', 'Created At', 'Last Login']);

            // CSV data
            foreach ($users as $user) {
                fputcsv($output, [
                    $user['user_id'],
                    $user['username'],
                    $user['full_name'],
                    $user['email'],
                    $user['role_name'] ?? 'N/A',
                    $user['department_name'] ?? 'N/A',
                    $user['is_active'] ? 'Active' : 'Inactive',
                    date('Y-m-d H:i:s', strtotime($user['created_at'])),
                    $user['last_login'] ? date('Y-m-d H:i:s', strtotime($user['last_login'])) : 'Never'
                ]);
            }

            fclose($output);
            exit;
        } catch (\Exception $e) {
            log_message('error', 'Export users error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to export users');
        }
    }

    // ==================== MANAGE ROLES ===========================
    /**
     * Manage Roles - Main method
     */
    public function manageRoles()
    {
        $data = $this->loadCommonData();
        $data['title'] = 'Manage Roles - NEXUS Admin';

        // Load all data from models
        $data['roles'] = $this->roleModel->getAllRolesWithCount();
        $data['allPermissions'] = $this->roleModel->getAllPermissions();

        // Set default selected role
        $data['selectedRole'] = !empty($data['roles']) ? $data['roles'][0] : null;

        if ($data['selectedRole']) {
            // Get permissions with status
            $data['permissionsWithStatus'] = $this->roleModel->getPermissionsWithStatus($data['selectedRole']['role_id']);

            // Get permission summary
            $data['permissionSummary'] = $this->roleModel->getPermissionSummary($data['selectedRole']['role_id']);

            // Get other role info
            $data['roleRules'] = $this->getRoleRules($data['selectedRole']['role_name']);
            $data['coreResponsibilities'] = $this->getRoleResponsibilities($data['selectedRole']['role_name']);
        }

        // Handle form submissions 
        if ($this->request->getMethod() === 'post') {
            $action = $this->request->getPost('action');

            switch ($action) {
                case 'save_role':
                    return $this->saveRole();
                case 'update_role_permissions':
                    return $this->updateRolePermissions();
                case 'delete_role':
                    return $this->deleteRole();
                case 'duplicate_role':
                    return $this->duplicateRole();
                case 'reset_role':
                    return $this->resetRole();
                case 'copy_permissions':
                    return $this->copyPermissions();
            }
        }

        return view('Admin/manage_roles', $data);
    }

    /**
     * Get role details 
     */
    public function getRoleDetails($id = null)
    {
        $data = $this->loadCommonData();
        $data['title'] = 'Role Details - NEXUS Admin';

        $roleId = $id ?: $this->request->getGet('role_id');

        if (!$roleId) {
            return redirect()->to('/admin/roles')->with('error', 'Role ID is required');
        }

        // Get role details from model
        $role = $this->roleModel->getRoleById($roleId);

        if (!$role) {
            return redirect()->to('/admin/roles')->with('error', 'Role not found');
        }

        // Get permission summary
        $permissionSummary = $this->roleModel->getPermissionSummary($roleId);

        $data['role'] = $role;
        $data['rules'] = $this->getRoleRules($role['role_name']);
        $data['responsibilities'] = $this->getRoleResponsibilities($role['role_name']);
        $data['permission_summary'] = $permissionSummary;

        return view('Admin/role_details', $data);
    }

    /**
     * Save/update role
     */
    public function saveRole()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/admin/roles');
        }

        try {
            $roleId = $this->request->getPost('role_id');
            $roleName = trim($this->request->getPost('role_name'));
            $description = trim($this->request->getPost('description'));

            if (!$roleName) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Role name is required');
            }

            $result = $this->roleModel->saveRole($roleId, $roleName, $description);

            if ($result['success']) {
                $message = isset($result['is_new']) && $result['is_new'] ? 'Role created successfully' : 'Role updated successfully';
                return redirect()->to('/admin/roles')
                    ->with('success', $message)
                    ->with('role_id', $result['role_id'] ?? $roleId);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $result['message'] ?? 'Failed to save role');
        } catch (\Exception $e) {
            log_message('error', 'Save role error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    /**
     * Update role permissions
     */
    public function updateRolePermissions()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/admin/roles');
        }

        try {
            $roleId = $this->request->getPost('role_id');
            $permissions = $this->request->getPost('permissions');

            if (!$roleId || !$permissions) {
                return redirect()->back()
                    ->with('error', 'Role ID and permissions are required');
            }

            // Call model method
            $result = $this->roleModel->updateRolePermissions($roleId, $permissions);

            if ($result['success']) {
                return redirect()->to('/admin/roles')
                    ->with('success', 'Permissions updated successfully')
                    ->with('role_id', $roleId);
            }

            return redirect()->back()
                ->with('error', $result['message'] ?? 'Failed to update permissions');
        } catch (\Exception $e) {
            log_message('error', 'Update role permissions error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    /**
     * Delete role (non-AJAX)
     */
    public function deleteRole()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/admin/roles');
        }

        try {
            $roleId = $this->request->getPost('role_id');

            if (!$roleId) {
                return redirect()->back()->with('error', 'Role ID is required');
            }

            // Delete role using model
            $result = $this->roleModel->deleteRole($roleId);

            if ($result['success']) {
                return redirect()->to('/admin/roles')
                    ->with('success', $result['message']);
            } else {
                return redirect()->back()
                    ->with('error', $result['message']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Delete role error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    /**
     * Duplicate role (non-AJAX)
     */
    public function duplicateRole()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/admin/roles');
        }

        try {
            $roleId = $this->request->getPost('role_id');
            $newRoleName = $this->request->getPost('new_role_name');
            $newDescription = $this->request->getPost('new_description');

            if (!$roleId || !$newRoleName) {
                return redirect()->back()
                    ->with('error', 'Role ID and new role name are required');
            }

            // Duplicate role using model
            $result = $this->roleModel->duplicateRole($roleId, $newRoleName, $newDescription);

            if ($result['success']) {
                return redirect()->to('/admin/roles')
                    ->with('success', $result['message'])
                    ->with('new_role_id', $result['role_id'] ?? null);
            } else {
                return redirect()->back()
                    ->with('error', $result['message']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Duplicate role error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    /**
     * Reset role permissions (non-AJAX)
     */
    public function resetRole()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/admin/roles');
        }

        try {
            $roleId = $this->request->getPost('role_id');

            if (!$roleId) {
                return redirect()->back()->with('error', 'Role ID is required');
            }

            // Call model method
            $result = $this->roleModel->resetRolePermissions($roleId);

            if ($result['success']) {
                return redirect()->to('/admin/roles')
                    ->with('success', $result['message'])
                    ->with('role_id', $roleId);
            } else {
                return redirect()->back()
                    ->with('error', $result['message']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Reset role error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    /**
     * Copy permissions from one role to another (non-AJAX)
     */
    public function copyPermissions()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/admin/roles');
        }

        try {
            $sourceRoleId = $this->request->getPost('source_role_id');
            $targetRoleId = $this->request->getPost('target_role_id');

            if (!$sourceRoleId || !$targetRoleId) {
                return redirect()->back()
                    ->with('error', 'Source and target role IDs are required');
            }

            // Call model method
            $result = $this->roleModel->copyPermissions($sourceRoleId, $targetRoleId);

            if ($result['success']) {
                return redirect()->to('/admin/roles')
                    ->with('success', $result['message'])
                    ->with('target_role_id', $targetRoleId);
            } else {
                return redirect()->back()
                    ->with('error', $result['message']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Copy permissions error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    /**
     * Get role permissions (non-AJAX)
     */
    public function getRolePermissions($roleId)
    {
        $data = $this->loadCommonData();
        $data['title'] = 'Role Permissions - NEXUS Admin';

        if (!$roleId) {
            return redirect()->to('/admin/roles')->with('error', 'Role ID is required');
        }

        // Get permissions with status from model
        $data['permissionsWithStatus'] = $this->roleModel->getPermissionsWithStatus($roleId);

        // Get permission summary from model
        $data['permissionSummary'] = $this->roleModel->getPermissionSummary($roleId);

        // Get role info
        $role = $this->roleModel->find($roleId);
        $data['role'] = $role;

        return view('Admin/role_permissions', $data);
    }

    // ==================== HELPER METHODS ROLES ====================
    /**
     * Get role rules based on role name
     */
    private function getRoleRules($roleName)
    {
        $rules = [];

        switch ($roleName) {
            case 'Admin':
                $rules = [
                    'Full system administrator access',
                    'Can manage all users and settings',
                    'Unrestricted access to all features',
                    'Can create, edit, and delete roles'
                ];
                break;
            case 'Support':
                $rules = [
                    'Can view and respond to tickets',
                    'Access to support dashboard',
                    'Cannot modify system settings',
                    'Limited user management capabilities'
                ];
                break;
            case 'Customer':
                $rules = [
                    'Can create and view own tickets',
                    'Cannot access admin features',
                    'Limited to own data only',
                    'Cannot view other users\' tickets'
                ];
                break;
            case 'Department':
                $rules = [
                    'Department-specific access',
                    'Can handle assigned tickets',
                    'Limited to department scope',
                    'Can collaborate with support team'
                ];
                break;
            default:
                $rules = [
                    'Custom role - rules defined by administrator',
                    'Permissions can be customized',
                    'Affects all users assigned to this role'
                ];
        }

        return $rules;
    }

    /**
     * Get role responsibilities based on role name
     */
    private function getRoleResponsibilities($roleName)
    {
        $responsibilities = [
            'Admin' => [
                'System configuration and management',
                'User and role management',
                'Monitor system performance',
                'Generate reports and analytics',
                'Troubleshoot system issues'
            ],
            'Support' => [
                'Handle incoming support requests',
                'Assign tickets to appropriate departments',
                'Communicate with customers',
                'Resolve basic technical issues',
                'Escalate complex issues to relevant departments'
            ],
            'Customer' => [
                'Submit problem reports or requests',
                'Monitor ticket progress',
                'Communicate with support team',
                'Confirm issue resolution',
                'Provide feedback on support quality'
            ],
            'Department' => [
                'Handle department-specific tickets',
                'Collaborate with support team',
                'Provide technical expertise',
                'Update ticket status and notes',
                'Ensure SLA compliance for department tickets'
            ]
        ];

        return $responsibilities[$roleName] ?? ['Custom role - responsibilities defined by administrator'];
    }

    // ==================== MANAGE DEPARTMENTS ===========================
    /**
     * Manage Departments - Main method
     */
    public function manageDepartments()
    {
        $data = $this->loadCommonData();
        $data['title'] = 'Manage Departments - NEXUS Admin';

        // Get departments with statistics
        $data['departments'] = $this->departmentModel->getAllDepartmentsForAdmin();

        // Get dashboard statistics
        $data['departmentStats'] = $this->departmentModel->getDepartmentDashboardStats();

        // Handle form submissions (non-AJAX)
        if ($this->request->getMethod() === 'post') {
            $action = $this->request->getPost('action');

            switch ($action) {
                case 'add_department':
                    return $this->addDepartment();
                case 'edit_department':
                    return $this->editDepartment();
                case 'delete_department':
                    return $this->deleteDepartment();
                case 'bulk_assign_users':
                    return $this->bulkAssignUsersToDepartment();
                case 'remove_users':
                    return $this->removeUsersFromDepartment();
            }
        }

        return view('Admin/manage_departments', $data);
    }

    /**
     * Get department details (non-AJAX)
     */
    public function getDepartmentDetails($id = null)
    {
        $data = $this->loadCommonData();
        $data['title'] = 'Department Details - NEXUS Admin';

        $departmentId = $id ?: $this->request->getGet('department_id');

        if (!$departmentId) {
            return redirect()->to('/admin/departments')->with('error', 'Department ID is required');
        }

        try {
            $department = $this->departmentModel->getDepartmentDetailsForAdmin($departmentId);

            if (!$department) {
                return redirect()->to('/admin/departments')->with('error', 'Department not found');
            }

            // Get department icon
            $icon = $this->getDepartmentIcon($department['department_name']);

            // Determine status
            $status = $department['member_count'] > 0 ? 'active' : 'inactive';

            // Format recent members
            $recentMembers = array_map(function ($member) {
                return [
                    'id' => $member['user_id'],
                    'name' => $member['full_name'],
                    'email' => $member['email'],
                    'role' => $member['role_name'],
                    'avatar_initials' => $this->getAvatarInitials($member['full_name'])
                ];
            }, $department['recent_members']);

            $data['department'] = $department;
            $data['department']['status'] = $status;
            $data['department']['icon'] = $icon;
            $data['department']['recent_members'] = $recentMembers;

            return view('Admin/department_details', $data);
        } catch (\Exception $e) {
            log_message('error', 'Get department details error: ' . $e->getMessage());
            return redirect()->to('/admin/departments')->with('error', 'Failed to load department details');
        }
    }

    public function datatable()
    {
        $status = $this->request->getPost('status');
        $table = Datatables::method([DepartmentModel::class, 'queryDatatable'], 'searchable')
        ->setParams($status)
        ->make();

        $table->updateRow(function ($db, $no){
            $btn_edit = "<button type='button' class='btn btn-sm bg-yellow-200 margin-r-2' onclick=\"modalForm('Update Department - " . $db->department_name . "', 'modal-lg', '" . getURL('admin/departments/edit/' . ($db->department_id)) . "', {'identifier': this})\"><i class='fas fa-edit'></i></i></button>";
            $btn_hapus = "<button type='button' class='btn btn-sm bg-red-200' onclick=\"modalDelete('Delete Department - " . $db->department_name . "', {'link':'" . getURL('admin/departments/delete') . "', 'id':'" . ($db->department_id) . "', {'pagetype':'table'})\"><i class='fas fa-trash'></i></button>";
            return [
                    $no,
                    $db->department_name,
                    $db->description,
                    $db->department_head,
                    implode(' ', [$btn_edit, $btn_hapus])
            ];
        });

        $table->toJson();
    }

    /**
     * Add department (non-AJAX)
     */
    public function addDepartment()
    {
        $name = $this->request->getPost('department_name');
        $desc = $this->request->getPost('department_description');
        $status = $this->request->getPost('department_status');
        $head = $this->request->getPost('department_head');
        $cat = $this->request->getPost('arr_categories');

        $this->db->transBegin();

        try {

            if (empty($name))
                throw new \Exception('Department name is required');
            if (empty($status))
                throw new \Exception('Department status is required');
            if (empty($cat) && count($cat) <= 0)
                throw new \Exception('Department categories are required');

            $this->departmentModel->store([
                'department_name' => $name,
                'description' => $desc,
                'department_head' => $head,
                'status' => $status,
            ]);

            // var_dump(db_connect()->error());die;
            $departId = db_connect()->insertID();
            $arr_cat = [];
            foreach($cat as $c) {
                $arr_cat[] = [
                    'department_id' => $departId,
                    'category_id' => $c,
                ];
            }

            $this->cdm->storeBatch($arr_cat);

            $this->db->transCommit();
            return $this->formatResponse(1, 'Department is added successfully');

        } catch (\Exception $e) {
            $this->db->transRollback();
            return $this->formatResponse(0, $e->getMessage(), $e->getTraceAsString());
        }
    }

    /**
     * Edit department (non-AJAX)
     */
    public function editDepartment()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/admin/departments');
        }

        try {
            $departmentId = $this->request->getPost('department_id');

            if (!$departmentId) {
                return redirect()->back()->with('error', 'Department ID is required');
            }

            $validation = \Config\Services::validation();
            $validation->setRules([
                'department_name' => "required|min_length[2]|max_length[100]|is_unique[departments.department_name,department_id,{$departmentId}]",
                'description' => 'permit_empty|max_length[500]'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->back()
                    ->withInput()
                    ->with('errors', $validation->getErrors());
            }

            $departmentData = [
                'department_name' => $this->request->getPost('department_name'),
                'description' => $this->request->getPost('description')
            ];

            // Get category mappings if provided
            $categories = $this->request->getPost('categories') ?: [];

            if ($this->departmentModel->update($departmentId, $departmentData)) {
                // Update category mappings
                $this->updateCategoryMappings($departmentId, $categories);

                return redirect()->to('/admin/departments')
                    ->with('success', 'Department updated successfully');
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update department');
        } catch (\Exception $e) {
            log_message('error', 'Edit department error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    /**
     * Delete department (non-AJAX)
     */
    public function deleteDepartment()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/admin/departments');
        }

        try {
            $departmentId = $this->request->getPost('department_id');

            if (!$departmentId) {
                return redirect()->back()->with('error', 'Department ID is required');
            }

            // Call model method to delete department
            $result = $this->departmentModel->deleteDepartment($departmentId);

            if ($result['success']) {
                return redirect()->to('/admin/departments')
                    ->with('success', $result['message']);
            } else {
                return redirect()->back()
                    ->with('error', $result['message']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Delete department error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    /**
     * Get department statistics (non-AJAX)
     */
    public function getDepartmentStatistics()
    {
        $data = $this->loadCommonData();
        $data['title'] = 'Department Statistics - NEXUS Admin';

        try {
            $stats = $this->departmentModel->getDepartmentDashboardStats();

            $data['statistics'] = $stats;

            return view('Admin/department_statistics', $data);
        } catch (\Exception $e) {
            log_message('error', 'Get department statistics error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load statistics');
        }
    }

    /**
     * Get department users (non-AJAX)
     */
    public function getDepartmentUsers($departmentId)
    {
        $data = $this->loadCommonData();
        $data['title'] = 'Department Users - NEXUS Admin';

        if (!$departmentId) {
            return redirect()->to('/admin/departments')->with('error', 'Department ID is required');
        }

        // Get department users from model
        $users = $this->departmentModel->getDepartmentUsers($departmentId);

        $data['users'] = $users;
        $data['department_id'] = $departmentId;

        // Get department info
        $department = $this->departmentModel->find($departmentId);
        $data['department'] = $department;

        return view('Admin/department_users', $data);
    }

    /**
     * Bulk assign users to department (non-AJAX)
     */
    public function bulkAssignUsersToDepartment()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/admin/departments');
        }

        $userIds = $this->request->getPost('user_ids');
        $departmentId = $this->request->getPost('department_id');

        if (empty($userIds) || !$departmentId) {
            return redirect()->back()->with('error', 'User IDs and Department ID are required');
        }

        // Call model method
        $result = $this->departmentModel->bulkAssignUsers($userIds, $departmentId);

        if ($result['success']) {
            return redirect()->to('/admin/departments')
                ->with('success', $result['message']);
        } else {
            return redirect()->back()
                ->with('error', $result['message']);
        }
    }

    /**
     * Remove users from department (non-AJAX)
     */
    public function removeUsersFromDepartment()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/admin/departments');
        }

        $userIds = $this->request->getPost('user_ids');

        if (empty($userIds)) {
            return redirect()->back()->with('error', 'User IDs are required');
        }

        // Call model method
        $result = $this->departmentModel->removeUsersFromDepartment($userIds);

        if ($result['success']) {
            return redirect()->back()
                ->with('success', $result['message']);
        } else {
            return redirect()->back()
                ->with('error', $result['message']);
        }
    }

    /**
     * Export departments to CSV
     */
    public function exportDepartments()
    {
        try {
            // Get departments data from model
            $departments = $this->departmentModel->exportDepartments();

            // Set headers for CSV download
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="departments_' . date('Y-m-d') . '.csv"');

            $output = fopen('php://output', 'w');

            // CSV headers
            fputcsv($output, ['ID', 'Department Name', 'Description', 'User Count', 'Ticket Count', 'Created At']);

            // CSV data
            foreach ($departments as $dept) {
                fputcsv($output, [
                    $dept['department_id'],
                    $dept['department_name'],
                    $dept['description'] ?? '',
                    $dept['user_count'] ?? 0,
                    $dept['ticket_count'] ?? 0,
                    date('Y-m-d H:i:s', strtotime($dept['created_at']))
                ]);
            }

            fclose($output);
            exit;
        } catch (\Exception $e) {
            log_message('error', 'Export departments error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to export departments');
        }
    }

    /**
     * Get department dropdown for forms (non-AJAX)
     */
    public function getDepartmentDropdown()
    {
        $data = $this->loadCommonData();
        $data['title'] = 'Departments - NEXUS Admin';

        // Get dropdown options from model
        $options = $this->departmentModel->getDepartmentDropdown();

        $data['options'] = $options;

        return view('Admin/department_dropdown', $data);
    }

    // ==================== HELPER METHODS DEPARTMENTS ====================
    /**
     * Helper method to get department icon
     */
    private function getDepartmentIcon(string $departmentName): string
    {
        $iconMap = [
            'it' => 'fa-server',
            'support' => 'fa-headset',
            'technical' => 'fa-tools',
            'ui' => 'fa-paint-brush',
            'ux' => 'fa-paint-brush',
            'feature' => 'fa-lightbulb',
            'qa' => 'fa-clipboard-check',
            'security' => 'fa-shield-alt',
            'network' => 'fa-network-wired',
            'database' => 'fa-database',
            'mobile' => 'fa-mobile-alt',
            'web' => 'fa-globe',
            'cloud' => 'fa-cloud'
        ];

        $nameLower = strtolower($departmentName);

        foreach ($iconMap as $keyword => $icon) {
            if (strpos($nameLower, $keyword) !== false) {
                return $icon;
            }
        }

        return 'fa-building';
    }

    /**
     * Helper method to get avatar initials
     */
    private function getAvatarInitials(string $fullName): string
    {
        $names = explode(' ', $fullName);
        $initials = '';

        foreach ($names as $name) {
            if (strlen($initials) >= 2) break;
            $initials .= strtoupper(substr($name, 0, 1));
        }

        return $initials;
    }

    /**
     * Save category mappings for a department
     */
    private function saveCategoryMappings(int $departmentId, array $categories): void
    {
        $db = db_connect();

        foreach ($categories as $categoryId) {
            $db->table('category_department_mapping')->insert([
                'department_id' => $departmentId,
                'category_id' => $categoryId
            ]);
        }
    }

    /**
     * Update category mappings for a department
     */
    private function updateCategoryMappings(int $departmentId, array $categories): void
    {
        $db = db_connect();

        // Delete existing mappings
        $db->table('category_department_mapping')
            ->where('department_id', $departmentId)
            ->delete();

        // Insert new mappings
        $this->saveCategoryMappings($departmentId, $categories);
    }

    // ==================== VIEW TICKETS ===========================
    /**
     * View Tickets - Main method
     */
    public function viewTickets()
    {
        $data = $this->loadCommonData();
        $data['title'] = 'View Tickets - NEXUS Admin';

        try {
            // Get ticket statistics
            $data['ticketStats'] = $this->ticketModel->getAdminTicketStatistics();

            // Get departments for filter dropdown
            $data['departments'] = $this->departmentModel->findAll();

            // Get priorities for filter dropdown
            $data['priorities'] = $this->getPriorities();

            // Get statuses for filter dropdown
            $data['statuses'] = $this->getStatuses();

            // Handle filters and pagination
            $filters = [
                'search' => $this->request->getGet('search'),
                'priority' => $this->request->getGet('priority'),
                'department' => $this->request->getGet('department'),
                'status' => $this->request->getGet('status'),
                'date_from' => $this->request->getGet('date_from'),
                'date_to' => $this->request->getGet('date_to')
            ];

            // Get pagination parameters
            $page = $this->request->getGet('page') ?: 1;
            $limit = $this->request->getGet('limit') ?: 10;
            $offset = ($page - 1) * $limit;

            // Get tickets from model
            $tickets = $this->ticketModel->getTicketsForAdmin($filters, $limit, $offset);
            $totalTickets = $this->ticketModel->countTicketsForAdmin($filters);

            // Format tickets for display
            $formattedTickets = array_map(function ($ticket) {
                return [
                    'id' => $ticket['ticket_number'] ?: $ticket['ticket_id'],
                    'title' => $ticket['subject'],
                    'description' => $ticket['description'] ?? 'No description',
                    'priority' => $ticket['priority_name'] ?? 'Medium',
                    'priority_value' => strtolower($ticket['priority_name'] ?? 'medium'),
                    'department' => $ticket['department_name'] ?? 'Not assigned',
                    'department_value' => strtolower(str_replace(' ', '-', $ticket['department_name'] ?? '')),
                    'customer' => $ticket['customer_name'] ?? 'Unknown',
                    'status' => $ticket['status_name'] ?? 'Open',
                    'status_value' => strtolower(str_replace(' ', '-', $ticket['status_name'] ?? 'open')),
                    'created' => $this->formatDate($ticket['created_at']),
                    'updated' => $this->formatTimeAgo($ticket['updated_at']),
                    'project' => $ticket['project_name'] ?? null,
                    'due_date' => $ticket['due_date'] ? date('M d, Y', strtotime($ticket['due_date'])) : null
                ];
            }, $tickets);

            $data['tickets'] = $formattedTickets;
            $data['filters'] = $filters;
            $data['pagination'] = [
                'total' => $totalTickets,
                'page' => $page,
                'limit' => $limit,
                'total_pages' => ceil($totalTickets / $limit)
            ];

            return view('Admin/view_tickets', $data);
        } catch (\Exception $e) {
            log_message('error', 'View tickets error: ' . $e->getMessage());
            // Fallback data
            $data['ticketStats'] = [
                'total_tickets' => 0,
                'open_tickets' => 0,
                'resolved_tickets' => 0,
                'closed_tickets' => 0
            ];
            $data['departments'] = [];
            $data['priorities'] = [];
            $data['statuses'] = [];
            $data['tickets'] = [];
            $data['pagination'] = [
                'total' => 0,
                'page' => 1,
                'limit' => 10,
                'total_pages' => 0
            ];

            return view('Admin/view_tickets', $data);
        }
    }

    /**
     * Get ticket details (non-AJAX)
     */
    public function getTicketDetails($id = null)
    {
        $data = $this->loadCommonData();
        $data['title'] = 'Ticket Details - NEXUS Admin';

        $ticketId = $id ?: $this->request->getGet('ticket_id');

        if (!$ticketId) {
            return redirect()->to('/admin/tickets')->with('error', 'Ticket ID is required');
        }

        try {
            // Get ticket details from model
            $ticket = $this->ticketModel->getTicketDetailsForAdmin($ticketId);

            if (!$ticket) {
                return redirect()->to('/admin/tickets')->with('error', 'Ticket not found');
            }

            // Format activity log
            $activityLog = array_map(function ($activity) {
                return [
                    'type' => strtolower(str_replace(' ', '-', $activity['message_type'])),
                    'text' => $activity['message'],
                    'time' => $this->formatTimeAgo($activity['created_at']),
                    'user' => $activity['full_name']
                ];
            }, $ticket['activity'] ?? []);

            $data['ticket'] = $ticket;
            $data['activity'] = $activityLog;

            return view('Admin/ticket_details', $data);
        } catch (\Exception $e) {
            log_message('error', 'Get ticket details error: ' . $e->getMessage());
            return redirect()->to('/admin/tickets')->with('error', 'Failed to load ticket details');
        }
    }

    /**
     * Export tickets to CSV (direct download)
     */
    public function exportTickets()
    {
        try {
            // Get filters from query string
            $filters = [
                'search' => $this->request->getGet('search'),
                'priority' => $this->request->getGet('priority'),
                'department' => $this->request->getGet('department'),
                'status' => $this->request->getGet('status'),
                'date_from' => $this->request->getGet('date_from'),
                'date_to' => $this->request->getGet('date_to')
            ];

            // Get tickets data from model
            $tickets = $this->ticketModel->exportTicketsForAdmin($filters);

            // Set headers for CSV download
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="tickets_' . date('Y-m-d') . '.csv"');

            $output = fopen('php://output', 'w');

            // CSV headers
            fputcsv($output, [
                'Ticket ID',
                'Ticket Number',
                'Subject',
                'Description',
                'Priority',
                'Status',
                'Category',
                'Department',
                'Customer Name',
                'Customer Email',
                'Assigned To',
                'Project',
                'Project Code',
                'Created At',
                'Updated At',
                'Due Date'
            ]);

            // CSV data
            foreach ($tickets as $ticket) {
                fputcsv($output, [
                    $ticket['ticket_id'],
                    $ticket['ticket_number'],
                    $ticket['subject'],
                    $ticket['description'],
                    $ticket['priority_name'],
                    $ticket['status_name'],
                    $ticket['category_name'],
                    $ticket['department_name'],
                    $ticket['customer_name'],
                    $ticket['customer_email'],
                    $ticket['assigned_to'],
                    $ticket['project_name'],
                    $ticket['project_code'],
                    date('Y-m-d H:i:s', strtotime($ticket['created_at'])),
                    date('Y-m-d H:i:s', strtotime($ticket['updated_at'])),
                    $ticket['due_date'] ? date('Y-m-d', strtotime($ticket['due_date'])) : ''
                ]);
            }

            fclose($output);
            exit;
        } catch (\Exception $e) {
            log_message('error', 'Export tickets error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to export tickets');
        }
    }

    /**
     * Get ticket statistics (non-AJAX)
     */
    public function getTicketStatistics()
    {
        $data = $this->loadCommonData();
        $data['title'] = 'Ticket Statistics - NEXUS Admin';

        try {
            $stats = $this->ticketModel->getAdminTicketStatistics();

            $data['statistics'] = $stats;

            return view('Admin/ticket_statistics', $data);
        } catch (\Exception $e) {
            log_message('error', 'Get ticket statistics error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load statistics');
        }
    }

    // ==================== HELPER METHODS TICKETS ====================
    /**
     * Get priorities for dropdown
     */
    private function getPriorities(): array
    {
        $priorityModel = new \App\Models\PriorityModel();
        return $priorityModel->findAll();
    }

    /**
     * Get statuses for dropdown
     */
    private function getStatuses(): array
    {
        $statusModel = new \App\Models\StatusModel();
        return $statusModel->findAll();
    }

    /**
     * Format date for display
     */
    private function formatDate($datetime): string
    {
        if (!$datetime) return 'N/A';

        $time = strtotime($datetime);
        $now = time();
        $diff = $now - $time;

        if ($diff < 86400) { // Less than 24 hours
            if ($diff < 60) return 'Just now';
            if ($diff < 3600) return floor($diff / 60) . ' minutes ago';
            return floor($diff / 3600) . ' hours ago';
        }

        return date('M d, Y', $time);
    }

    // ==================== MANAGE PROJECTS ===========================
    /**
     * Manage Projects - Main method
     */
    public function manageProjects()
    {
        $data = $this->loadCommonData();
        $data['title'] = 'Manage Projects - NEXUS Admin';

        // Load projects data with ticket counts from model
        $data['projects'] = $this->projectModel->getProjectsWithTicketCounts();

        // Load all users for assignment (active users only)
        $data['all_users'] = $this->userModel->getActiveUsersWithRoles();

        // Load project assignments from model
        $data['assignments'] = $this->projectAssignmentModel->getAssignmentsGroupedByProject();

        // Handle form submissions (non-AJAX)
        if ($this->request->getMethod() === 'post') {
            $action = $this->request->getPost('action');

            switch ($action) {
                case 'add_project':
                    return $this->addProject();
                case 'edit_project':
                    return $this->editProject();
                case 'delete_project':
                    return $this->deleteProject();
                case 'change_project_status':
                    return $this->changeProjectStatus();
                case 'manage_project_users':
                    return $this->manageProjectUsers();
                case 'import_projects':
                    return $this->importProjects();
                case 'bulk_assign_projects':
                    return $this->bulkAssignProjects();
            }
        }

        return view('Admin/manage_projects', $data);
    }

    /**
     * Get project details (non-AJAX)
     */
    public function getProjectDetails($id = null)
    {
        $data = $this->loadCommonData();
        $data['title'] = 'Project Details - NEXUS Admin';

        $projectId = $id ?: $this->request->getGet('project_id');

        if (!$projectId) {
            return redirect()->to('/admin/projects')->with('error', 'Project ID is required');
        }

        // Get project details from model
        $project = $this->projectModel->getProjectWithUser($projectId);

        if (!$project) {
            return redirect()->to('/admin/projects')->with('error', 'Project not found');
        }

        // Get assigned users for this project
        $assignedUsers = $this->projectAssignmentModel->getAssignedUsersForProject($projectId);

        $data['project'] = $project;
        $data['assigned_users'] = $assignedUsers;

        return view('Admin/project_details', $data);
    }

    /**
     * Add new project (non-AJAX)
     */
    public function addProject()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/admin/projects');
        }

        try {
            // Validasi input
            $validation = \Config\Services::validation();
            $validation->setRules([
                'project_name' => 'required|min_length[3]|max_length[100]',
                'project_code' => 'required|min_length[2]|max_length[20]|is_unique[projects.project_code]',
                'description' => 'permit_empty|max_length[500]'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->back()
                    ->withInput()
                    ->with('errors', $validation->getErrors());
            }

            // Prepare data
            $projectData = [
                'project_name' => $this->request->getPost('project_name'),
                'project_code' => strtoupper($this->request->getPost('project_code')),
                'description' => $this->request->getPost('description'),
                'user_id' => session()->get('user_id'),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Save to database
            if ($this->projectModel->save($projectData)) {
                $projectId = $this->projectModel->getInsertID();

                return redirect()->to('/admin/projects')
                    ->with('success', 'Project added successfully')
                    ->with('project_id', $projectId);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to add project');
        } catch (\Exception $e) {
            log_message('error', 'Add project error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    /**
     * Edit project (non-AJAX) - FIXED VERSION
     */
    public function editProject()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/admin/projects');
        }

        try {
            $projectId = $this->request->getPost('project_id');

            if (!$projectId) {
                return redirect()->back()->with('error', 'Project ID is required');
            }

            // Validasi input
            $validation = \Config\Services::validation();
            $validation->setRules([
                'project_name' => 'required|min_length[3]|max_length[100]',
                'project_code' => "required|min_length[2]|max_length[20]|is_unique[projects.project_code,project_id,{$projectId}]",
                'description' => 'permit_empty|max_length[500]'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->back()
                    ->withInput()
                    ->with('errors', $validation->getErrors());
            }

            // Siapkan data
            $projectData = [
                'project_id' => $projectId,
                'project_name' => trim($this->request->getPost('project_name')),
                'project_code' => strtoupper(trim($this->request->getPost('project_code'))),
                'is_active' => $this->request->getPost('is_active') ? 1 : 0,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Tambahkan description jika ada
            $description = trim($this->request->getPost('description', FILTER_SANITIZE_STRING));
            if (!empty($description)) {
                $projectData['description'] = $description;
            }

            // Update project
            if ($this->projectModel->save($projectData)) {
                return redirect()->to('/admin/projects')
                    ->with('success', 'Project updated successfully');
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Failed to update project');
            }
        } catch (\Exception $e) {
            log_message('error', 'Edit project error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    /**
     * Delete project (non-AJAX)
     */
    public function deleteProject()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/admin/projects');
        }

        try {
            $projectId = $this->request->getPost('project_id');

            if (!$projectId) {
                return redirect()->back()->with('error', 'Project ID is required');
            }

            // Check if project has tickets
            $ticketCount = $this->ticketModel->where('project_id', $projectId)->countAllResults();

            if ($ticketCount > 0) {
                return redirect()->back()->with('error', 'Cannot delete project with existing tickets. Please reassign tickets first.');
            }

            // Delete project assignments first
            $this->projectAssignmentModel->where('project_id', $projectId)->delete();

            // Delete project
            if ($this->projectModel->delete($projectId)) {
                return redirect()->to('/admin/projects')
                    ->with('success', 'Project deleted successfully');
            }

            return redirect()->back()->with('error', 'Failed to delete project');
        } catch (\Exception $e) {
            log_message('error', 'Delete project error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    /**
     * Change project status (non-AJAX)
     */
    public function changeProjectStatus()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/admin/projects');
        }

        try {
            $projectId = $this->request->getPost('project_id');
            $status = $this->request->getPost('status');

            if (!$projectId || $status === null) {
                return redirect()->back()->with('error', 'Project ID and status are required');
            }

            $isActive = ($status === 'active' || $status === '1' || $status === true) ? 1 : 0;

            $result = $this->projectModel->changeStatus($projectId, $isActive);

            if ($result) {
                $statusText = $isActive ? 'activated' : 'deactivated';
                return redirect()->to('/admin/projects')
                    ->with('success', "Project {$statusText} successfully");
            } else {
                return redirect()->back()->with('error', 'Failed to change project status');
            }
        } catch (\Exception $e) {
            log_message('error', 'Change project status error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    /**
     * Manage project users (non-AJAX)
     */
    public function manageProjectUsers()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/admin/projects');
        }

        $projectId = $this->request->getPost('project_id');
        $userIds = $this->request->getPost('user_ids') ?: [];

        if (!$projectId) {
            return redirect()->back()->with('error', 'Project ID is required');
        }

        // Validate user IDs
        $validUserIds = array_filter($userIds, function ($id) {
            return is_numeric($id) && $id > 0;
        });

        // Get current user ID from session
        $assignedBy = session()->get('user_id');

        // Call ProjectAssignmentModel method
        $result = $this->projectAssignmentModel->assignUsersToProject($projectId, $validUserIds, $assignedBy);

        if ($result['success']) {
            return redirect()->to('/admin/projects')
                ->with('success', $result['message']);
        } else {
            return redirect()->back()
                ->with('error', $result['message']);
        }
    }

    /**
     * Get unassigned users for a project (non-AJAX)
     */
    public function getUnassignedUsers($projectId)
    {
        $data = $this->loadCommonData();
        $data['title'] = 'Assign Users - NEXUS Admin';

        if (!$projectId) {
            return redirect()->to('/admin/projects')->with('error', 'Project ID is required');
        }

        // Get unassigned users
        $data['unassignedUsers'] = $this->projectAssignmentModel->getUnassignedUsers($projectId);
        $data['project_id'] = $projectId;

        // Get project info
        $project = $this->projectModel->find($projectId);
        $data['project'] = $project;

        return view('Admin/assign_users', $data);
    }

    /**
     * Bulk assign users to multiple projects (non-AJAX)
     */
    public function bulkAssignProjects()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/admin/projects');
        }

        $projectIds = $this->request->getPost('project_ids');
        $userIds = $this->request->getPost('user_ids');
        $assignedBy = session()->get('user_id');

        if (empty($projectIds) || empty($userIds)) {
            return redirect()->back()->with('error', 'Please select at least one project and one user');
        }

        // Call ProjectAssignmentModel method
        $result = $this->projectAssignmentModel->bulkAssignUsers($projectIds, $userIds, $assignedBy);

        if ($result['success']) {
            return redirect()->to('/admin/projects')
                ->with('success', $result['message']);
        } else {
            return redirect()->back()
                ->with('error', $result['message']);
        }
    }

    /**
     * Export assignments to CSV
     */
    public function exportAssignments()
    {
        try {
            // Get assignments data from model
            $assignments = $this->projectAssignmentModel->exportAssignments();

            // Set headers for CSV download
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="project_assignments_' . date('Y-m-d') . '.csv"');

            $output = fopen('php://output', 'w');

            // CSV headers
            fputcsv($output, [
                'Assignment ID',
                'Project ID',
                'Project Code',
                'Project Name',
                'User ID',
                'Username',
                'Full Name',
                'Email',
                'Assigned By',
                'Assigned At'
            ]);

            // CSV data
            foreach ($assignments as $assignment) {
                fputcsv($output, [
                    $assignment['assignment_id'],
                    $assignment['project_id'],
                    $assignment['project_code'],
                    $assignment['project_name'],
                    $assignment['user_id'],
                    $assignment['username'],
                    $assignment['full_name'],
                    $assignment['email'],
                    $assignment['assigned_by_name'] ?? 'System',
                    date('Y-m-d H:i:s', strtotime($assignment['assigned_at']))
                ]);
            }

            fclose($output);
            exit;
        } catch (\Exception $e) {
            log_message('error', 'Export assignments error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to export assignments');
        }
    }

    /**
     * View project assignments page
     */
    public function viewAssignments()
    {
        $data = $this->loadCommonData();
        $data['title'] = 'Project Assignments - NEXUS Admin';

        // Get data from models
        $data['recentAssignments'] = $this->projectAssignmentModel->getRecentAssignments(20);
        $data['assignmentStats'] = $this->projectAssignmentModel->getAssignmentStatistics();
        $data['topAssignedUsers'] = $this->projectAssignmentModel->getTopAssignedUsers(10);
        $data['projectsWithoutAssignments'] = $this->projectAssignmentModel->getProjectsWithoutAssignments();

        return view('Admin/view_assignments', $data);
    }

    /**
     * Remove user from project (non-AJAX)
     */
    public function removeUserFromProject()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/admin/projects');
        }

        try {
            $projectId = $this->request->getPost('project_id');
            $userId = $this->request->getPost('user_id');

            if (!$projectId || !$userId) {
                return redirect()->back()->with('error', 'Project ID and User ID are required');
            }

            // Call ProjectAssignmentModel method
            $result = $this->projectAssignmentModel->removeUserFromProject($projectId, $userId);

            if ($result['success']) {
                return redirect()->back()->with('success', $result['message']);
            } else {
                return redirect()->back()->with('error', $result['message']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Remove user from project error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    /**
     * Clear all assignments for a project (non-AJAX)
     */
    public function clearProjectAssignments()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/admin/projects');
        }

        try {
            $projectId = $this->request->getPost('project_id');

            if (!$projectId) {
                return redirect()->back()->with('error', 'Project ID is required');
            }

            // Call ProjectAssignmentModel method
            $result = $this->projectAssignmentModel->clearProjectAssignments($projectId);

            if ($result['success']) {
                return redirect()->back()->with('success', $result['message']);
            } else {
                return redirect()->back()->with('error', $result['message']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Clear project assignments error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }

    /**
     * Get projects for bulk assignment (non-AJAX)
     */
    public function getProjectsForBulkAssign()
    {
        $data = $this->loadCommonData();
        $data['title'] = 'Bulk Assign Projects - NEXUS Admin';

        $search = $this->request->getGet('search') ?? '';

        // Get projects from model
        $data['projects'] = $this->projectModel->getProjectsForBulkAssign($search);
        $data['all_users'] = $this->userModel->getActiveUsersWithRoles();

        return view('Admin/bulk_assign_projects', $data);
    }

    /**
     * Import projects from CSV (non-AJAX)
     */
    public function importProjects()
    {
        $data = $this->loadCommonData();
        $data['title'] = 'Import Projects - NEXUS Admin';

        if ($this->request->getMethod() === 'post') {
            try {
                $file = $this->request->getFile('projects_file');

                if (!$file || !$file->isValid()) {
                    return redirect()->back()->with('error', 'Please select a valid file');
                }

                // Validate file type
                $allowedTypes = ['csv'];
                $extension = $file->getExtension();

                if (!in_array($extension, $allowedTypes)) {
                    return redirect()->back()->with('error', 'File type not supported. Please upload CSV files.');
                }

                // Process CSV file using model method
                $result = $this->projectModel->importProjectsFromCSV($file, session()->get('user_id'));

                if ($result['success']) {
                    return redirect()->to('/admin/projects')
                        ->with('success', $result['message'])
                        ->with('imported_count', $result['imported_count'])
                        ->with('error_count', $result['error_count']);
                } else {
                    return redirect()->back()->with('error', $result['message']);
                }
            } catch (\Exception $e) {
                log_message('error', 'Import projects error: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Server error: ' . $e->getMessage());
            }
        }

        return view('Admin/import_projects', $data);
    }

    /**
     * Export projects to CSV
     */
    public function exportProjects()
    {
        try {
            // Get filters from query string
            $filters = [
                'search' => $this->request->getGet('search'),
                'status' => $this->request->getGet('status'),
                'sort_by' => $this->request->getGet('sort_by')
            ];

            // Get projects data with filters from model
            $projects = $this->projectModel->exportProjects($filters);

            // Set headers for CSV download
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="projects_' . date('Y-m-d') . '.csv"');

            $output = fopen('php://output', 'w');

            // CSV headers
            fputcsv($output, [
                'Project ID',
                'Project Code',
                'Project Name',
                'Description',
                'Total Tickets',
                'Open Tickets',
                'Assigned Users',
                'Status',
                'Created At',
                'Last Updated'
            ]);

            // CSV data
            foreach ($projects as $project) {
                fputcsv($output, [
                    $project['project_id'],
                    $project['project_code'],
                    $project['project_name'],
                    $project['description'] ?? '',
                    $project['total_tickets'] ?? 0,
                    $project['open_tickets'] ?? 0,
                    $project['assigned_users'] ?? 0,
                    $project['is_active'] ? 'Active' : 'Inactive',
                    date('Y-m-d H:i:s', strtotime($project['created_at'])),
                    $project['updated_at'] ? date('Y-m-d H:i:s', strtotime($project['updated_at'])) : 'Never'
                ]);
            }

            fclose($output);
            exit;
        } catch (\Exception $e) {
            log_message('error', 'Export projects error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to export projects');
        }
    }

    /**
     * Download project template
     */
    public function downloadProjectTemplate()
    {
        $templateContent = "project_name,project_code,description,is_active,created_at\n" .
            "Website Redesign,PROJ001,Complete website overhaul,true," . date('Y-m-d') . "\n" .
            "Mobile App,PROJ002,New mobile application,true," . date('Y-m-d') . "\n" .
            "Database Migration,PROJ003,Migrate to new database,false," . date('Y-m-d');

        return $this->response->download('project_import_template.csv', $templateContent);
    }

    /**
     * Search projects (non-AJAX)
     */
    public function searchProjects()
    {
        $data = $this->loadCommonData();
        $data['title'] = 'Search Projects - NEXUS Admin';

        $keyword = $this->request->getGet('search') ?? '';
        $limit = $this->request->getGet('limit') ?? 10;

        // Get projects from model
        $projects = $this->projectModel->searchProjects($keyword, $limit);

        $data['projects'] = $projects;
        $data['keyword'] = $keyword;
        $data['count'] = count($projects);

        return view('Admin/search_projects', $data);
    }

    /**
     * Get project overview statistics (non-AJAX)
     */
    public function getProjectOverviewStats()
    {
        $data = $this->loadCommonData();
        $data['title'] = 'Project Statistics - NEXUS Admin';

        try {
            $stats = $this->projectModel->getProjectStatistics();

            $data['statistics'] = $stats;

            return view('Admin/project_statistics', $data);
        } catch (\Exception $e) {
            log_message('error', 'Get project stats error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load statistics');
        }
    }

    /**
     * Validate project code uniqueness (non-AJAX)
     */
    public function validateProjectCode()
    {
        $projectCode = $this->request->getGet('project_code');
        $projectId = $this->request->getGet('project_id');

        if (!$projectCode) {
            return redirect()->back()->with('error', 'Project code is required');
        }

        $isUnique = $this->projectModel->projectCodeExists($projectCode, $projectId);

        if ($isUnique) {
            return redirect()->back()->with('error', 'Project code already exists');
        } else {
            return redirect()->back()->with('success', 'Project code is available');
        }
    }

    /**
     * Get recent projects for dashboard (non-AJAX)
     */
    public function getRecentProjects()
    {
        $data = $this->loadCommonData();
        $data['title'] = 'Recent Projects - NEXUS Admin';

        $limit = $this->request->getGet('limit') ?? 5;

        $recentProjects = $this->projectModel->getRecentProjectsWithTicketCounts($limit);

        $data['projects'] = $recentProjects;
        $data['limit'] = $limit;

        return view('Admin/recent_projects', $data);
    }

    // ==================== AJAX METHODS PROJECTS ====================
    /**
     * Handle AJAX requests for project management
     */
    public function ajaxManageProjects()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        $action = $this->request->getPost('action');
        $userId = session()->get('user_id');

        switch ($action) {
            case 'create_project':
                return $this->ajaxCreateProject($userId);
            case 'update_project':
                return $this->ajaxUpdateProject();
            case 'bulk_assign_projects':
                return $this->ajaxBulkAssignProjects($userId);
            case 'import_projects':
                return $this->ajaxImportProjects($userId);
            case 'get_projects_for_bulk':
                return $this->ajaxGetProjectsForBulk();
            case 'get_all_users':
                return $this->ajaxGetAllUsers();
            case 'get_project_details':
                return $this->ajaxGetProjectDetails();
            case 'export_projects_csv':
                return $this->ajaxExportProjects();
            default:
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid action'
                ]);
        }
    }

    /**
     * AJAX: Create new project
     */
    private function ajaxCreateProject(int $userId)
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'project_name' => 'required|min_length[3]|max_length[100]',
            'project_code' => 'required|min_length[2]|max_length[20]',
            'description' => 'permit_empty|max_length[500]',
            'is_active' => 'permit_empty|in_list[true,false,1,0]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validation->getErrors()
            ]);
        }

        $projectData = [
            'project_name' => $this->request->getPost('project_name'),
            'project_code' => $this->request->getPost('project_code'),
            'description' => $this->request->getPost('description'),
            'is_active' => $this->request->getPost('is_active') ? true : false
        ];

        $result = $this->projectModel->createProject($projectData, $userId);

        return $this->response->setJSON($result);
    }

    /**
     * AJAX: Update existing project
     */
    private function ajaxUpdateProject()
    {
        $projectId = $this->request->getPost('project_id');

        if (!$projectId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Project ID is required'
            ]);
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'project_name' => 'required|min_length[3]|max_length[100]',
            'project_code' => "required|min_length[2]|max_length[20]",
            'description' => 'permit_empty|max_length[500]',
            'is_active' => 'permit_empty|in_list[true,false,1,0]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validation->getErrors()
            ]);
        }

        $projectData = [
            'project_name' => $this->request->getPost('project_name'),
            'project_code' => $this->request->getPost('project_code'),
            'description' => $this->request->getPost('description'),
            'is_active' => $this->request->getPost('is_active') ? true : false
        ];

        $result = $this->projectModel->updateProject($projectId, $projectData);

        return $this->response->setJSON($result);
    }

    /**
     * AJAX: Bulk assign users to projects
     */
    private function ajaxBulkAssignProjects(int $userId)
    {
        $projectIds = json_decode($this->request->getPost('project_ids'), true) ?? [];
        $userIds = json_decode($this->request->getPost('user_ids'), true) ?? [];

        if (empty($projectIds) || empty($userIds)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please select at least one project and one user'
            ]);
        }

        // Validate input
        $projectIds = array_filter($projectIds, 'is_numeric');
        $userIds = array_filter($userIds, 'is_numeric');

        if (empty($projectIds) || empty($userIds)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid project or user IDs'
            ]);
        }

        $result = $this->projectAssignmentModel->bulkAssignUsersToProjects($projectIds, $userIds, $userId);

        return $this->response->setJSON($result);
    }

    /**
     * AJAX: Import projects from CSV
     */
    private function ajaxImportProjects(int $userId)
    {
        $file = $this->request->getFile('projects_file');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please select a valid file'
            ]);
        }

        // Validate file type
        $allowedTypes = ['csv', 'xlsx', 'xls'];
        $extension = $file->getExtension();

        if (!in_array(strtolower($extension), $allowedTypes)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'File type not supported. Please upload CSV, XLSX, or XLS files.'
            ]);
        }

        try {
            // Process CSV file
            if ($extension === 'csv') {
                // $projectsData = $this->processCSVFile($file);
            } else {
                // For Excel files, you'll need to install and use PhpSpreadsheet
                // $projectsData = $this->processExcelFile($file);
            }

            if (empty($projectsData)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No valid data found in file'
                ]);
            }

            // Call model to bulk import
            $result = $this->projectModel->bulkImportProjects($projectsData, $userId);

            return $this->response->setJSON($result);
        } catch (\Exception $e) {
            log_message('error', 'Import projects error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * AJAX: Get projects for bulk assignment
     */
    private function ajaxGetProjectsForBulk()
    {
        $search = $this->request->getPost('search') ?? '';

        $projects = $this->projectModel->getProjectsForBulkAssignment($search);

        return $this->response->setJSON([
            'success' => true,
            'projects' => $projects
        ]);
    }

    /**
     * AJAX: Get all active users
     */
    private function ajaxGetAllUsers()
    {
        $users = $this->userModel->getActiveUsersWithRoles();

        return $this->response->setJSON([
            'success' => true,
            'users' => $users
        ]);
    }

    /**
     * AJAX: Get project details
     */
    private function ajaxGetProjectDetails()
    {
        $projectId = $this->request->getPost('project_id');

        if (!$projectId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Project ID is required'
            ]);
        }

        $project = $this->projectModel->getProjectWithUser($projectId);
        $assignedUsers = $this->projectAssignmentModel->getAssignedUsersForProject($projectId);

        if (!$project) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Project not found'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'project' => $project,
            'assigned_users' => $assignedUsers
        ]);
    }

    /**
     * AJAX: Export projects to CSV
     */
    private function ajaxExportProjects()
    {
        $filters = [
            'search' => $this->request->getPost('search'),
            'status' => $this->request->getPost('status'),
            'sort_by' => $this->request->getPost('sort_by')
        ];

        $projects = $this->projectModel->exportProjects($filters);

        if (empty($projects)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No projects found to export'
            ]);
        }

        // Format data for CSV
        $csvData = [];
        $headers = ['ID', 'Project Code', 'Project Name', 'Description', 'Status', 'Total Tickets', 'Open Tickets', 'Created At', 'Last Updated'];

        $csvData[] = $headers;

        foreach ($projects as $project) {
            $csvData[] = [
                $project['project_id'],
                $project['project_code'],
                $project['project_name'],
                $project['description'] ?? '',
                $project['is_active'] ? 'Active' : 'Inactive',
                $project['total_tickets'] ?? 0,
                $project['open_tickets'] ?? 0,
                $project['created_at'],
                $project['updated_at'] ?? 'Never'
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $csvData,
            'count' => count($projects),
            'filename' => 'projects_' . date('Y-m-d_H-i-s') . '.csv'
        ]);
    }

// ==================== AJAX METHODS DEPARTMENTS ====================
    /**
     * Handle AJAX requests for department management
     */
    public function ajaxDepartments()
    {
        // Get raw JSON input
        $jsonInput = file_get_contents('php://input');
        $jsonData = json_decode($jsonInput, true) ?: [];

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        // Try to get action from both POST and JSON
        $action = $this->request->getPost('action') ?: ($jsonData['action'] ?? null);

        switch ($action) {
            case 'get_departments_data':
                return $this->ajaxGetDepartmentsData();
            case 'get_department_statistics':
                return $this->ajaxGetDepartmentStatistics();
            case 'get_department_details':
                return $this->ajaxGetDepartmentDetails();
            case 'create_department':
                return $this->ajaxCreateDepartment();
            case 'update_department':
                return $this->ajaxUpdateDepartment();
            case 'delete_department':
                return $this->ajaxDeleteDepartment();
            case 'toggle_department_status':
                return $this->ajaxToggleDepartmentStatus();
            case 'get_categories':
                return $this->ajaxGetCategories();
            default:
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid action'
                ]);
        }
    }

    /**
     * AJAX: Get departments data with pagination and filtering
     */
    private function ajaxGetDepartmentsData()
    {
        try {
            $search = $this->request->getPost('search') ?? '';
            $status = $this->request->getPost('status') ?? '';
            $page = (int)($this->request->getPost('page') ?? 1);
            $limit = (int)($this->request->getPost('limit') ?? 10);

            $offset = ($page - 1) * $limit;

            // Build query
            $db = db_connect();
            $builder = $db->table('departments d')
                ->select('d.*, 
                    COUNT(DISTINCT u.user_id) as members,
                    COUNT(DISTINCT t.ticket_id) as tickets,
                    d.description as detailed_description')
                ->join('users u', 'u.department_id = d.department_id', 'left')
                ->join('tickets t', 't.department_id = d.department_id', 'left')
                ->groupBy('d.department_id');

            // Apply search filter
            if (!empty($search)) {
                $builder->groupStart()
                    ->like('d.department_name', $search)
                    ->orLike('d.description', $search)
                    ->groupEnd();
            }

            // Apply status filter
            if (!empty($status) && $status !== 'all') {
                if ($status === 'active') {
                    $builder->having('COUNT(DISTINCT u.user_id) >', 0);
                } elseif ($status === 'inactive') {
                    $builder->having('COUNT(DISTINCT u.user_id)', 0);
                }
            }

            // Get total count
            $totalBuilder = clone $builder;
            $total = $totalBuilder->countAllResults();

            // Get paginated results
            $departments = $builder
                ->orderBy('d.department_name', 'ASC')
                ->limit($limit, $offset)
                ->get()
                ->getResultArray();

            // Format departments for frontend
            $formattedDepartments = array_map(function ($dept) {
                return [
                    'id' => $dept['department_id'],
                    'name' => $dept['department_name'],
                    'description' => $dept['description'] ?? '',
                    'detailed_description' => $dept['detailed_description'] ?? '',
                    'members' => (int)$dept['members'],
                    'tickets' => (int)$dept['tickets'],
                    'status' => ((int)$dept['members'] > 0) ? 'active' : 'inactive',
                    'created_at' => date('M d, Y', strtotime($dept['created_at']))
                ];
            }, $departments);

            return $this->response->setJSON([
                'success' => true,
                'departments' => $formattedDepartments,
                'pagination' => [
                    'total' => $total,
                    'page' => $page,
                    'limit' => $limit,
                    'total_pages' => ceil($total / $limit)
                ]
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Get departments data error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load departments data'
            ]);
        }
    }

    /**
     * AJAX: Get department statistics
     */
    private function ajaxGetDepartmentStatistics()
    {
        try {
            $stats = $this->departmentModel->getDepartmentDashboardStats();

            return $this->response->setJSON([
                'success' => true,
                'statistics' => $stats
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Get department statistics error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load statistics'
            ]);
        }
    }

    /**
     * AJAX: Get department details
     */
    private function ajaxGetDepartmentDetails()
    {
        try {
            // Get department_id from both POST and JSON data
            $jsonInput = file_get_contents('php://input');
            $jsonData = json_decode($jsonInput, true) ?: [];
            $departmentId = $this->request->getPost('department_id') ?: ($jsonData['department_id'] ?? null);

            if (!$departmentId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Department ID is required'
                ]);
            }

            $department = $this->departmentModel->getDepartmentDetailsForAdmin($departmentId);

            if (!$department) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Department not found'
                ]);
            }

            // Format department for frontend
            $formattedDepartment = [
                'id' => $department['department_id'],
                'name' => $department['department_name'],
                'description' => $department['description'] ?? '',
                'member_count' => (int)$department['member_count'],
                'active_tickets' => (int)($department['active_tickets'] ?? 0),
                'resolved_tickets' => (int)($department['resolved_tickets'] ?? 0),
                'categories' => $department['categories'] ?? [],
                'status' => ((int)$department['member_count'] > 0) ? 'active' : 'inactive',
                'created_at' => date('M d, Y', strtotime($department['created_at'])),
                'icon' => $this->getDepartmentIcon($department['department_name']),
                'recent_members' => array_map(function ($member) {
                    return [
                        'name' => $member['full_name'],
                        'role' => $member['role_name'],
                        'avatar_initials' => $this->getAvatarInitials($member['full_name'])
                    ];
                }, $department['recent_members'] ?? [])
            ];

            return $this->response->setJSON([
                'success' => true,
                'department' => $formattedDepartment
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Get department details error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load department details'
            ]);
        }
    }

    public function form($id = null)
    {
        $formType = 'add';
        $btnSubmit = 'Save';
        $row = null;
        if (!empty($id)) {
            $formType = 'edit';
            $btnSubmit = 'Update';
            $row = $this->departmentModel->find($id);
        }
        $categories = $this->categoryModel->findAll();
        echo json_encode([
            'view' => view('Admin/Modals/form_department', ['row' => $row, 'categories' => $categories, 'formType' => $formType]),
            'btnSubmit' => $btnSubmit,
        ]);
    }

    /**
     * AJAX: Create new department
     */
    private function ajaxCreateDepartment()
    {
        try {
            // Get JSON data
            $jsonInput = file_get_contents('php://input');
            $jsonData = json_decode($jsonInput, true) ?: [];
            
            $validation = \Config\Services::validation();
            $validation->setRules([
                'department_name' => 'required|min_length[2]|max_length[100]|is_unique[departments.department_name]',
                'description' => 'permit_empty|max_length[500]',
                'status' => 'permit_empty|in_list[active,inactive]',
                'head' => 'permit_empty|max_length[100]'
            ]);

            // Manually validate JSON data
            $departmentName = $jsonData['department_name'] ?? '';
            $description = $jsonData['description'] ?? '';
            
            if (empty($departmentName) || strlen($departmentName) < 2 || strlen($departmentName) > 100) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Department name is required and must be between 2-100 characters'
                ]);
            }

            $departmentData = [
                'department_name' => $departmentName,
                'description' => $description
            ];

            $result = $this->departmentModel->addDepartment($departmentData);

            if ($result['success']) {
                // Handle category mappings if provided
                $categories = $jsonData['categories'] ?? [];
                if (!empty($categories) && is_array($categories)) {
                    $this->saveCategoryMappings($result['department_id'], $categories);
                }

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Department created successfully',
                    'department_id' => $result['department_id']
                ]);
            } else {
                return $this->response->setJSON($result);
            }
        } catch (\Exception $e) {
            log_message('error', 'Create department error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to create department'
            ]);
        }
    }

    /**
     * AJAX: Update department
     */
    private function ajaxUpdateDepartment()
    {
        try {
            // Get JSON data
            $jsonInput = file_get_contents('php://input');
            $jsonData = json_decode($jsonInput, true) ?: [];
            
            $departmentId = $this->request->getPost('department_id') ?: ($jsonData['department_id'] ?? null);

            if (!$departmentId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Department ID is required'
                ]);
            }

            $validation = \Config\Services::validation();
            $validation->setRules([
                'department_name' => "required|min_length[2]|max_length[100]|is_unique[departments.department_name,department_id,{$departmentId}]",
                'description' => 'permit_empty|max_length[500]',
                'status' => 'permit_empty|in_list[active,inactive]',
                'head' => 'permit_empty|max_length[100]'
            ]);

            // Manually validate JSON data
            $departmentName = $jsonData['department_name'] ?? '';
            $description = $jsonData['description'] ?? '';
            
            if (empty($departmentName) || strlen($departmentName) < 2 || strlen($departmentName) > 100) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Department name is required and must be between 2-100 characters'
                ]);
            }

            $departmentData = [
                'department_name' => $departmentName,
                'description' => $description
            ];

            $result = $this->departmentModel->updateDepartment($departmentId, $departmentData);

            if ($result['success']) {
                // Update category mappings if provided
                $categories = $jsonData['categories'] ?? [];
                if (is_array($categories)) {
                    $this->updateCategoryMappings($departmentId, $categories);
                }

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Department updated successfully'
                ]);
            } else {
                return $this->response->setJSON($result);
            }
        } catch (\Exception $e) {
            log_message('error', 'Update department error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update department'
            ]);
        }
    }

    /**
     * AJAX: Toggle department status
     */
    private function ajaxToggleDepartmentStatus()
    {
        try {
            $departmentId = $this->request->getPost('department_id');

            if (!$departmentId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Department ID is required'
                ]);
            }

            // Get current member count
            $memberCount = $this->departmentModel->getDepartmentActiveUsersCount($departmentId);

            if ($memberCount > 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Cannot deactivate department with active members. Please reassign members first.'
                ]);
            }

            // For departments, status is based on member count, so we don't actually toggle
            // We just return the current status
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Department status updated successfully',
                'status' => 'inactive'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Toggle department status error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to toggle department status'
            ]);
        }
    }

    /**
     * AJAX: Delete department
     */
    private function ajaxDeleteDepartment()
    {
        try {
            $jsonInput = file_get_contents('php://input');
            $jsonData = json_decode($jsonInput, true) ?: [];
            $departmentId = $this->request->getPost('department_id') ?: ($jsonData['department_id'] ?? null);

            if (!$departmentId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Department ID is required'
                ]);
            }

            $result = $this->departmentModel->deleteDepartment($departmentId);

            return $this->response->setJSON($result);
        } catch (\Exception $e) {
            log_message('error', 'Delete department error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete department'
            ]);
        }
    }

    /**
     * AJAX: Get categories for department mapping
     */
    private function ajaxGetCategories()
    {
        try {
            $db = db_connect();
            $categories = $db->table('categories')
                ->select('category_id, category_name')
                ->orderBy('category_name', 'ASC')
                ->get()
                ->getResultArray();

            return $this->response->setJSON([
                'success' => true,
                'categories' => $categories
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Get categories error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load categories'
            ]);
        }
    }

    // ==================== SYSTEM SETTINGS ====================
    public function systemSettings()
    {
        $data = $this->loadCommonData();
        $data['title'] = 'System Settings - NEXUS Admin';

        return view('Admin/system_settings', $data);
    }

    /**
     * Format time ago
     */
    // private function formatTimeAgo($datetime): string
    // {
    //     return $this->formatDate($datetime); // Reuse formatDate for now
    // }
}
