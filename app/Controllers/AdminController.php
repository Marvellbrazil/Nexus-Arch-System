<?php

namespace App\Controllers;

use App\Models\TicketModel;
use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\DepartmentModel;
use App\Models\ProjectAssignmentModel;
use App\Models\ProjectModel;

class AdminController extends BaseController
{
    protected $userModel;
    protected $roleModel;
    protected $ticketModel;
    protected $projectModel;
    protected $departmentModel;
    protected $projectAssignmentModel;

    public function __construct()
    {
        // Initialize models
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
        $this->ticketModel = new TicketModel();
        $this->projectModel = new ProjectModel();
        $this->departmentModel = new DepartmentModel();
        $this->projectAssignmentModel = new ProjectAssignmentModel();
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
     *   - Main method
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
        // Get user ID
        $userId = $id ?: $this->request->getGet('user_id');

        if (!$userId) {
            // Jika AJAX request
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User ID is required'
                ]);
            }
            return redirect()->to('/admin/users')->with('error', 'User ID is required');
        }

        try {
            // Get user details with role and department
            $db = db_connect();
            $user = $db->table('users u')
                ->select('u.*, r.role_name, d.department_name')
                ->join('roles r', 'r.role_id = u.role_id', 'left')
                ->join('departments d', 'd.department_id = u.department_id', 'left')
                ->where('u.user_id', $userId)
                ->get()
                ->getRowArray();

            if (!$user) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'User not found'
                    ]);
                }
                return redirect()->to('/admin/users')->with('error', 'User not found');
            }

            // Format untuk AJAX response
            if ($this->request->isAJAX()) {
                // Format data untuk response
                $formattedUser = [
                    'user_id' => $user['user_id'],
                    'username' => $user['username'],
                    'full_name' => $user['full_name'],
                    'email' => $user['email'],
                    'phone_number' => $user['phone_number'] ?? 'N/A',
                    'role_name' => $user['role_name'],
                    'department_name' => $user['department_name'] ?? 'N/A',
                    'is_active' => (bool)$user['is_active'],
                    'created_at' => $user['created_at'],
                    'last_login' => $user['last_login'] ?? null
                ];

                return $this->response->setJSON([
                    'success' => true,
                    'user' => $formattedUser
                ]);
            }

            // Untuk non-AJAX (tampilkan view)
            $data = $this->loadCommonData();
            $data['title'] = 'User Details - NEXUS Admin';
            $data['user'] = $user;

            return view('Admin/user_details', $data);
        } catch (\Exception $e) {
            log_message('error', 'Get user details error: ' . $e->getMessage());

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to load user details: ' . $e->getMessage()
                ]);
            }
            return redirect()->to('/admin/users')->with('error', 'Failed to load user details');
        }
    }

    /**
     * Add new user - FIXED VERSION untuk handle AJAX dan regular POST
     */
    public function addUser()
    {
        // Debug: Tampilkan method dan headers
        log_message('debug', '=== ADD USER METHOD CALLED ===');
        log_message('debug', 'Request Method: ' . $this->request->getMethod());
        log_message('debug', 'Is AJAX: ' . ($this->request->isAJAX() ? 'YES' : 'NO'));

        // HAPUS pemeriksaan method yang salah
        // if ($this->request->getMethod() !== 'post') {
        //     log_message('debug', 'Invalid method: ' . $this->request->getMethod());
        //     return $this->response->setJSON([
        //         'success' => false,
        //         'message' => 'Invalid request method. Expected POST, got ' . $this->request->getMethod()
        //     ]);
        // }

        // Cukup periksa apakah ini AJAX request
        if (!$this->request->isAJAX()) {
            log_message('debug', 'Not an AJAX request');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'This endpoint requires AJAX request'
            ]);
        }

        try {
            // Debug: Tampilkan semua POST data
            $postData = $this->request->getPost();
            log_message('debug', 'POST Data received: ' . print_r($postData, true));

            // Cek jika data kosong
            if (empty($postData)) {
                log_message('debug', 'POST data is empty!');
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No form data received'
                ]);
            }

            // Validasi input
            $validation = \Config\Services::validation();

            $validationRules = [
                'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
                'full_name' => 'required|min_length[3]|max_length[100]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[6]',
                'role_id' => 'required|integer',
                'department_id' => 'permit_empty|integer',
                'phone_number' => 'permit_empty|max_length[20]',
                'is_active' => 'permit_empty|in_list[0,1]'
            ];

            $validation->setRules($validationRules);

            // Debug sebelum validasi
            log_message('debug', 'Validation rules set');
            log_message('debug', 'is_active value: ' . ($this->request->getPost('is_active') ?? 'NULL'));

            if (!$validation->withRequest($this->request)->run()) {
                $errors = $validation->getErrors();
                log_message('debug', 'Validation errors: ' . print_r($errors, true));
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $errors
                ]);
            }

            log_message('debug', 'Validation passed');

            // Siapkan data user
            $userData = [
                'username' => trim($this->request->getPost('username')),
                'full_name' => trim($this->request->getPost('full_name')),
                'email' => trim($this->request->getPost('email')),
                'password' => password_hash(trim($this->request->getPost('password')), PASSWORD_DEFAULT),
                'role_id' => (int)$this->request->getPost('role_id'),
                'is_active' => ($this->request->getPost('is_active') == '1') ? true : false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Tambahkan optional fields
            $departmentId = $this->request->getPost('department_id');
            if (!empty($departmentId) && $departmentId !== '') {
                $userData['department_id'] = (int)$departmentId;
            }

            $phoneNumber = $this->request->getPost('phone_number');
            if (!empty($phoneNumber) && $phoneNumber !== '') {
                $userData['phone_number'] = trim($phoneNumber);
            }

            log_message('debug', 'User data to insert: ' . print_r($userData, true));

            // // Coba insert menggunakan model
            // $this->userModel->setValidationRules(false); // Nonaktifkan validasi model sementara

            if ($this->userModel->insert($userData)) {
                $userId = $this->userModel->getInsertID();

                log_message('debug', 'User inserted successfully. ID: ' . $userId);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'User created successfully',
                    'user_id' => $userId
                ]);
            } else {
                $error = $this->userModel->errors();
                log_message('error', 'Model insert failed: ' . print_r($error, true));

                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create user in database',
                    'errors' => $error
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Add user error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
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

    // ==================== AJAX METHODS PROJECTS ====================
    /**
     * Handle AJAX user actions
     */
    public function ajaxManageUsers()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        $action = $this->request->getPost('action');

        switch ($action) {
            case 'add_user':
                return $this->ajaxAddUser();
                // case 'get_user':
                //     return $this->ajaxGetUser();
            default:
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid action'
                ]);
        }
    }

    /**
     * AJAX: Get user details
     */
    public function ajaxGetUserDetails($userId = null)
    {
        // Debug
        log_message('debug', '=== AJAX GET USER DETAILS CALLED ===');
        log_message('debug', 'User ID: ' . $userId);
        log_message('debug', 'Is AJAX: ' . ($this->request->isAJAX() ? 'YES' : 'NO'));

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        try {
            $userId = $userId ?: $this->request->getGet('user_id');

            if (!$userId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User ID is required'
                ]);
            }

            // Get user details
            $db = db_connect();
            $user = $db->table('users u')
                ->select('u.*, r.role_name, d.department_name')
                ->join('roles r', 'r.role_id = u.role_id', 'left')
                ->join('departments d', 'd.department_id = u.department_id', 'left')
                ->where('u.user_id', $userId)
                ->get()
                ->getRowArray();

            log_message('debug', 'User query result: ' . print_r($user, true));

            if (!$user) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User not found'
                ]);
            }

            // Format boolean untuk PostgreSQL
            $user['is_active'] = (bool)$user['is_active'];

            log_message('debug', 'Formatted user data: ' . print_r($user, true));

            return $this->response->setJSON([
                'success' => true,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            log_message('error', 'AJAX get user details error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    // Di dalam AdminController.php - perbarui method ajaxGetUsers()

    public function ajaxGetUsers()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        try {
            // Get parameters
            $page = $this->request->getGet('page') ?: 1;
            $limit = 5; // Ubah menjadi 5 user per halaman
            $offset = ($page - 1) * $limit;

            // Get filters
            $filters = [
                'search' => $this->request->getGet('search'),
                'role_id' => $this->request->getGet('role_id'),
                'department_id' => $this->request->getGet('department_id'),
                'is_active' => $this->request->getGet('is_active'),
                'date_from' => $this->request->getGet('date_from'),
                'date_to' => $this->request->getGet('date_to')
            ];

            $db = db_connect();
            $builder = $db->table('users u')
                ->select('u.*, r.role_name, d.department_name, r.role_priority')
                ->join('roles r', 'r.role_id = u.role_id', 'left')
                ->join('departments d', 'd.department_id = u.department_id', 'left');

            // Apply filters
            if (!empty($filters['search'])) {
                $searchTerm = $filters['search'];
                $builder->groupStart()
                    ->like('u.username', $searchTerm)
                    ->orLike('u.full_name', $searchTerm)
                    ->orLike('u.email', $searchTerm)
                    ->orLike('r.role_name', $searchTerm)
                    ->orLike('d.department_name', $searchTerm)
                    ->groupEnd();
            }

            if (!empty($filters['role_id'])) {
                $builder->where('u.role_id', $filters['role_id']);
            }

            if (!empty($filters['department_id'])) {
                $builder->where('u.department_id', $filters['department_id']);
            }

            if (isset($filters['is_active']) && $filters['is_active'] !== '') {
                $isActive = $filters['is_active'] == '1' ? true : false;
                $builder->where('u.is_active', $isActive);
            }

            if (!empty($filters['date_from'])) {
                $builder->where('DATE(u.created_at) >=', $filters['date_from']);
            }

            if (!empty($filters['date_to'])) {
                $builder->where('DATE(u.created_at) <=', $filters['date_to']);
            }

            // === ORDERING BERDASARKAN ROLE PRIORITY ===
            // Order berdasarkan role priority: Admin -> Department -> Support -> Customer
            $builder->orderBy("
            CASE 
                WHEN r.role_name = 'Admin' THEN 1
                WHEN r.role_name LIKE 'Department%' THEN 2
                WHEN r.role_name = 'Support' THEN 3
                WHEN r.role_name = 'Customer' THEN 4
                ELSE 5
            END", 'ASC')
                ->orderBy('d.department_name', 'ASC') // Untuk Department, urut berdasarkan nama department
                ->orderBy('u.created_at', 'ASC'); // User baru di bawah user lama

            // Count total
            $totalBuilder = clone $builder;
            $total = $totalBuilder->countAllResults();

            // Get paginated results
            $users = $builder->limit($limit, $offset)
                ->get()
                ->getResultArray();

            // === BERI NOMOR URUT BERDASARKAN ORDER YANG TELAH DITENTUKAN ===
            $startNumber = $offset + 1;
            $formattedUsers = [];

            foreach ($users as $index => $user) {
                $formattedUsers[] = [
                    'row_number' => $startNumber + $index,
                    'user_id' => $user['user_id'],
                    'username' => $user['username'],
                    'full_name' => $user['full_name'],
                    'email' => $user['email'],
                    'role_name' => $user['role_name'],
                    'department_name' => $user['department_name'] ?? null,
                    'is_active' => (bool)$user['is_active'],
                    'created_at' => $user['created_at'],
                    'phone_number' => $user['phone_number'] ?? null
                ];
            }

            return $this->response->setJSON([
                'success' => true,
                'users' => $formattedUsers,
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'total_pages' => ceil($total / $limit)
            ]);
        } catch (\Exception $e) {
            log_message('error', 'AJAX get users error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * AJAX: Get users for assignment modal
     */
    public function ajaxGetUsersForAssignment()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        try {
            $search = $this->request->getPost('search') ?? '';
            $role = $this->request->getPost('role') ?? 'all';
            $page = $this->request->getPost('page') ?? 1;
            $limit = $this->request->getPost('limit') ?? 20;

            $db = db_connect();
            $builder = $db->table('users u')
                ->select('u.user_id, u.username, u.full_name, u.email, r.role_name, u.is_active')
                ->join('roles r', 'r.role_id = u.role_id')
                ->where('u.is_active', true);

            if (!empty($search)) {
                $builder->groupStart()
                    ->like('u.full_name', $search)
                    ->orLike('u.username', $search)
                    ->orLike('u.email', $search)
                    ->orLike('r.role_name', $search)
                    ->groupEnd();
            }

            if ($role !== 'all') {
                $builder->where('r.role_name', $role);
            }

            // Count total
            $totalBuilder = clone $builder;
            $total = $totalBuilder->countAllResults();

            // Get paginated results
            $offset = ($page - 1) * $limit;
            $users = $builder->orderBy('u.full_name', 'ASC')
                ->limit($limit, $offset)
                ->get()
                ->getResultArray();

            // Format for response
            $formattedUsers = [];
            foreach ($users as $user) {
                $formattedUsers[] = [
                    'user_id' => $user['user_id'],
                    'username' => $user['username'],
                    'full_name' => $user['full_name'],
                    'email' => $user['email'],
                    'role_name' => $user['role_name'],
                    'avatar_initials' => $this->getAvatarInitials($user['full_name']),
                    'is_active' => (bool)$user['is_active']
                ];
            }

            return $this->response->setJSON([
                'success' => true,
                'users' => $formattedUsers,
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'total_pages' => ceil($total / $limit)
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Get users for assignment error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    public function ajaxAddUser()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'This endpoint requires AJAX request'
            ]);
        }

        try {
            $db = db_connect();

            // Prepare data
            $userData = [
                'username' => $this->request->getPost('username'),
                'full_name' => $this->request->getPost('full_name'),
                'email' => $this->request->getPost('email'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'role_id' => $this->request->getPost('role_id'),
                'is_active' => $this->request->getPost('is_active') == '1' ? true : false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Optional fields
            if ($this->request->getPost('department_id')) {
                $userData['department_id'] = $this->request->getPost('department_id');
            }

            if ($this->request->getPost('phone_number')) {
                $userData['phone_number'] = $this->request->getPost('phone_number');
            }

            // Gunakan query manual dengan nextval
            $sql = "INSERT INTO users (username, full_name, email, password, role_id, department_id, phone_number, is_active, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) 
                RETURNING user_id";

            $query = $db->query($sql, [
                $userData['username'],
                $userData['full_name'],
                $userData['email'],
                $userData['password'],
                $userData['role_id'],
                $userData['department_id'] ?? null,
                $userData['phone_number'] ?? null,
                $userData['is_active'] ? 't' : 'f', // PostgreSQL boolean
                $userData['created_at'],
                $userData['updated_at']
            ]);

            $result = $query->getRow();
            $userId = $result->user_id;

            return $this->response->setJSON([
                'success' => true,
                'message' => 'User added successfully',
                'user_id' => $userId
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Database error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * AJAX: Reset password
     */
    public function ajaxResetPassword($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        try {
            // Validasi input
            $validation = \Config\Services::validation();
            $validation->setRules([
                'new_password' => 'required|min_length[6]',
                'confirm_password' => 'required|matches[new_password]'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                $errors = $validation->getErrors();
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $errors
                ]);
            }

            $newPassword = $this->request->getPost('new_password');

            // Hash password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update password di database
            $db = db_connect();
            $updated = $db->table('users')
                ->where('user_id', $id)
                ->update([
                    'password' => $hashedPassword,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            if ($updated) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Password reset successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to reset password in database'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Reset password error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * AJAX: Delete user
     */
    public function ajaxDeleteUser($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        try {
            // Get user ID from parameter or POST data
            $userId = $id ?: $this->request->getPost('user_id');

            if (!$userId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User ID is required'
                ]);
            }

            // Prevent deleting yourself
            if ($userId == session()->get('user_id')) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Cannot delete your own account'
                ]);
            }

            // Check if user exists
            $db = db_connect();
            $userExists = $db->table('users')
                ->where('user_id', $userId)
                ->countAllResults();

            if (!$userExists) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User not found'
                ]);
            }

            // Check if user has related data (tickets, assignments, etc.)
            $hasTickets = $db->table('tickets')
                ->where('customer_id', $userId)
                ->orWhere('assigned_to', $userId)
                ->countAllResults();

            if ($hasTickets > 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Cannot delete user with associated tickets. Please reassign or delete the tickets first.'
                ]);
            }

            // Check project assignments
            $hasProjectAssignments = $db->table('project_assignments')
                ->where('user_id', $userId)
                ->countAllResults();

            if ($hasProjectAssignments > 0) {
                // Remove project assignments first
                $db->table('project_assignments')
                    ->where('user_id', $userId)
                    ->delete();
            }

            // Delete user
            $deleted = $db->table('users')
                ->where('user_id', $userId)
                ->delete();

            if ($deleted) {
                log_message('info', 'User deleted successfully. ID: ' . $userId);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'User deleted successfully',
                    'user_id' => $userId
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete user from database'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Delete user error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * AJAX: Change user status (activate/deactivate)
     */
    public function ajaxChangeStatus($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        try {
            // Get user ID from parameter or POST data
            $userId = $id ?: $this->request->getPost('user_id');

            log_message('debug', '=== AJAX CHANGE STATUS CALLED ===');
            log_message('debug', 'User ID: ' . $userId);
            log_message('debug', 'POST Data: ' . print_r($this->request->getPost(), true));

            if (!$userId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User ID is required'
                ]);
            }

            // Get current user status first
            $db = db_connect();
            $user = $db->table('users')
                ->select('user_id, username, full_name, is_active')
                ->where('user_id', $userId)
                ->get()
                ->getRowArray();

            if (!$user) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User not found'
                ]);
            }

            // Prevent deactivating yourself
            if ($userId == session()->get('user_id')) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'You cannot change your own account status'
                ]);
            }

            // Toggle status: if active, deactivate; if inactive, activate
            $currentStatus = ($user['is_active'] === 't' || $user['is_active'] === true);
            $newStatus = !$currentStatus;

            log_message('debug', 'Current status: ' . ($currentStatus ? 'Active' : 'Inactive'));
            log_message('debug', 'New status: ' . ($newStatus ? 'Active' : 'Inactive'));

            // Update user status
            $updated = $db->table('users')
                ->where('user_id', $userId)
                ->update([
                    'is_active' => $newStatus ? 't' : 'f', // PostgreSQL boolean
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            if ($updated) {
                $action = $newStatus ? 'activated' : 'deactivated';
                $statusText = $newStatus ? 'Active' : 'Inactive';

                log_message('info', "User {$action} successfully. ID: {$userId}");

                return $this->response->setJSON([
                    'success' => true,
                    'message' => "User account {$action} successfully",
                    'user_id' => $userId,
                    'new_status' => $newStatus,
                    'status_text' => $statusText,
                    'action' => $action
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update user status in database'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Change status error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get avatar initials from full name
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

    public function updateUser()
    {
        // Debug: log request
        log_message('debug', '=== UPDATE USER METHOD CALLED ===');
        log_message('debug', 'Is AJAX: ' . ($this->request->isAJAX() ? 'YES' : 'NO'));
        log_message('debug', 'POST Data: ' . print_r($this->request->getPost(), true));

        if (!$this->request->isAJAX()) {
            log_message('debug', 'Not an AJAX request');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        try {
            $userId = $this->request->getPost('user_id');

            log_message('debug', 'User ID to update: ' . $userId);

            if (!$userId) {
                log_message('debug', 'User ID is missing');
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User ID is required'
                ]);
            }

            // Validasi
            $validation = \Config\Services::validation();
            $validation->setRules([
                'username' => "required|min_length[3]|max_length[50]|is_unique[users.username,user_id,{$userId}]",
                'full_name' => 'required|min_length[3]|max_length[100]',
                'email' => "required|valid_email|is_unique[users.email,user_id,{$userId}]",
                'role_id' => 'required|integer'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                $errors = $validation->getErrors();
                log_message('debug', 'Validation errors: ' . print_r($errors, true));

                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $errors
                ]);
            }

            log_message('debug', 'Validation passed');

            // Siapkan data - PERBAIKAN: Handle boolean untuk PostgreSQL
            $isActive = $this->request->getPost('is_active') ? true : false;

            $userData = [
                'username' => trim($this->request->getPost('username')),
                'full_name' => trim($this->request->getPost('full_name')),
                'email' => trim($this->request->getPost('email')),
                'role_id' => (int)$this->request->getPost('role_id'),
                'is_active' => $isActive, // Sudah dikonversi ke boolean
                'updated_at' => date('Y-m-d H:i:s')
            ];

            log_message('debug', 'User data to update: ' . print_r($userData, true));

            // Tambahkan optional fields dengan handling null
            $departmentId = $this->request->getPost('department_id');
            if ($departmentId !== null && $departmentId !== '' && $departmentId !== 'null') {
                $userData['department_id'] = (int)$departmentId;
            } else {
                $userData['department_id'] = null;
            }

            $phoneNumber = $this->request->getPost('phone_number');
            if ($phoneNumber !== null && $phoneNumber !== '' && $phoneNumber !== 'null') {
                $userData['phone_number'] = trim($phoneNumber);
            } else {
                $userData['phone_number'] = null;
            }

            $userData['is_active'] = $isActive ? 't' : 'f';

            // Update user menggunakan query builder langsung untuk PostgreSQL compatibility
            $db = db_connect();

            // Build update data untuk PostgreSQL
            $updateData = [
                'username' => $userData['username'],
                'full_name' => $userData['full_name'],
                'email' => $userData['email'],
                'role_id' => $userData['role_id'],
                'is_active' => $userData['is_active'], // PostgreSQL boolean literal
                'updated_at' => $userData['updated_at']
            ];

            // Handle nullable fields
            if ($userData['department_id'] !== null) {
                $updateData['department_id'] = $userData['department_id'];
            } else {
                $updateData['department_id'] = null;
            }

            if ($userData['phone_number'] !== null) {
                $updateData['phone_number'] = $userData['phone_number'];
            } else {
                $updateData['phone_number'] = null;
            }

            log_message('debug', 'Update data for PostgreSQL: ' . print_r($updateData, true));

            // Update using query builder
            $builder = $db->table('users');
            $builder->where('user_id', $userId);
            $updated = $builder->update($updateData);

            if ($updated) {
                log_message('debug', 'User updated successfully');

                // Get updated user data untuk response
                $updatedUser = $db->table('users u')
                    ->select('u.*, r.role_name, d.department_name')
                    ->join('roles r', 'r.role_id = u.role_id', 'left')
                    ->join('departments d', 'd.department_id = u.department_id', 'left')
                    ->where('u.user_id', $userId)
                    ->get()
                    ->getRowArray();

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'User updated successfully',
                    'user_id' => $userId,
                    'user' => $updatedUser
                ]);
            } else {
                log_message('error', 'Database update failed');
                $error = $db->error();
                log_message('error', 'Database error: ' . print_r($error, true));

                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update user in database',
                    'error' => $error['message'] ?? 'Unknown database error'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Update user error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
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

    /**
     * Add department (non-AJAX)
     */
    public function addDepartment()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/admin/departments');
        }

        try {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'department_name' => 'required|min_length[2]|max_length[100]|is_unique[departments.department_name]',
                'description' => 'permit_empty|max_length[500]'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->back()
                    ->withInput()
                    ->with('errors', $validation->getErrors());
            }

            $departmentData = [
                'department_name' => $this->request->getPost('department_name'),
                'description' => $this->request->getPost('description'),
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Get category mappings if provided
            $categories = $this->request->getPost('categories') ?: [];

            if ($this->departmentModel->insert($departmentData)) {
                $departmentId = $this->departmentModel->getInsertID();

                // Save category mappings if any
                if (!empty($categories)) {
                    $this->saveCategoryMappings($departmentId, $categories);
                }

                return redirect()->to('/admin/departments')
                    ->with('success', 'Department added successfully')
                    ->with('department_id', $departmentId);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to add department');
        } catch (\Exception $e) {
            log_message('error', 'Add department error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Server error: ' . $e->getMessage());
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

    // ==================== MANAGE PROJECTS ===========================
    /**
     * Manage Projects - Main method
     */
    public function manageProjects()
    {
        $data = $this->loadCommonData();
        $data['title'] = 'Manage Projects - NEXUS Admin';

        // Load projects data dengan urutan konsisten
        $data['projects'] = $this->projectModel->getProjectsWithTicketCounts();
        $data['total_projects'] = count($data['projects']);

        // Load all users for assignment (active users only)
        $data['all_users'] = $this->userModel->getActiveUsersWithRoles();

        return view('Admin/manage_projects', $data);
    }

    /**
     * Get project details
     */
    public function getProjectDetails($id = null)
    {
        $data = $this->loadCommonData();
        $data['title'] = 'Project Details - NEXUS Admin';

        $projectId = $id ?: $this->request->getGet('project_id');

        if (!$projectId) {
            return redirect()->to('/admin/projects')->with('error', 'Project ID is required');
        }

        $project = $this->projectModel->getProjectWithUser($projectId);

        if (!$project) {
            return redirect()->to('/admin/projects')->with('error', 'Project not found');
        }

        $assignedUsers = $this->projectAssignmentModel->getAssignedUsersForProject($projectId);

        $data['project'] = $project;
        $data['assigned_users'] = $assignedUsers;

        return view('Admin/project_details', $data);
    }

    /**
     * Get project statistics (total tickets, open tickets, etc.)
     */
    private function getProjectStatistics(int $projectId): array
    {
        $db = db_connect();

        // Total tickets
        $totalTickets = $db->table('tickets')
            ->where('project_id', $projectId)
            ->countAllResults();

        // Open tickets (status_id 1 = Open, 2 = In Progress)
        $openTickets = $db->table('tickets')
            ->where('project_id', $projectId)
            ->whereIn('status_id', [1, 2])
            ->countAllResults();

        // Resolved tickets
        $resolvedTickets = $db->table('tickets')
            ->where('project_id', $projectId)
            ->where('status_id', 3)
            ->countAllResults();

        // Closed tickets
        $closedTickets = $db->table('tickets')
            ->where('project_id', $projectId)
            ->where('status_id', 4)
            ->countAllResults();

        return [
            'total_tickets' => $totalTickets,
            'open_tickets' => $openTickets,
            'resolved_tickets' => $resolvedTickets,
            'closed_tickets' => $closedTickets,
            'completion_rate' => $totalTickets > 0 ?
                round((($resolvedTickets + $closedTickets) / $totalTickets) * 100, 1) : 0
        ];
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
            $isActive = $this->request->getPost('is_active') == '1' ? true : false;

            if (!$projectId) {
                return redirect()->back()->with('error', 'Project ID is required');
            }

            if ($this->projectModel->update($projectId, ['is_active' => $isActive])) {
                $action = $isActive ? 'activated' : 'deactivated';
                return redirect()->to('/admin/projects')
                    ->with('success', "Project {$action} successfully");
            }

            return redirect()->back()->with('error', 'Failed to change project status');
        } catch (\Exception $e) {
            log_message('error', 'Change project status error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Server error: ' . $e->getMessage());
        }
    }
    
    // ==================== AJAX METHODS PROJECTS ====================
    /**
     * Handle all AJAX requests for project management
     */
    public function ajaxManageProjects()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        $action = $this->request->getPost('action');

        switch ($action) {
            case 'get_projects_table':
                return $this->ajaxGetProjectsTable();
            case 'create_project':
                return $this->ajaxCreateProject();
            case 'update_project':
                return $this->ajaxUpdateProject();
            case 'validate_project_code':
                return $this->ajaxValidateProjectCode();
            case 'get_project_details':
                return $this->ajaxGetProjectDetails();
            case 'get_projects_for_bulk':
                return $this->ajaxGetProjectsForBulk();
            case 'get_all_users':
                return $this->ajaxGetAllUsers();
            case 'bulk_assign_projects':
                return $this->ajaxAssignUsersToProject();
            case 'delete_project':
                return $this->ajaxDeleteProject();
            case 'change_project_status':
                return $this->ajaxChangeProjectStatus();
            default:
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid action specified',
                    'action' => $action
                ]);
        }
    }

    /**
     * AJAX: Get projects table with server-side processing
     */
    private function ajaxGetProjectsTable()
    {
        try {
            $draw = $this->request->getPost('draw') ?? 1;
            $start = $this->request->getPost('start') ?? 0;
            $length = $this->request->getPost('length') ?? 10;
            $searchValue = $this->request->getPost('search')['value'] ?? '';
            $statusFilter = $this->request->getPost('status') ?? '';

            // Debug
            log_message('info', "Get projects table - Start: {$start}, Length: {$length}, Search: {$searchValue}");

            // Use model method with PostgreSQL compatibility
            $projects = $this->projectModel->searchProjectsForTable([
                'search' => $searchValue,
                'status' => $statusFilter
            ], $start, $length);

            $totalRecords = $this->projectModel->countAll();
            $filteredRecords = $this->projectModel->countFilteredProjects([
                'search' => $searchValue,
                'status' => $statusFilter
            ]);

            // Format data for response
            $formattedData = [];
            $rowNumber = $start + 1;

            foreach ($projects as $project) {
                $formattedData[] = [
                    'row_number' => $rowNumber++,
                    'id' => $project['project_id'],
                    'project_code' => $project['project_code'] ?? '',
                    'name' => $project['project_name'] ?? '',
                    'description' => $project['description'] ?? '',
                    'status' => $project['is_active'] ? 'active' : 'inactive',
                    'created_at' => $project['created_at'] ? date('M d, Y', strtotime($project['created_at'])) : '',
                    'total_tickets' => (int)($project['total_tickets'] ?? 0),
                    'open_tickets' => (int)($project['open_tickets'] ?? 0),
                    'assigned_users' => (int)($project['assigned_users'] ?? 0),
                    'is_active' => (bool)($project['is_active'] ?? false)
                ];
            }

            return $this->response->setJSON([
                'draw' => intval($draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $formattedData
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Get projects table error: ' . $e->getMessage());
            return $this->response->setJSON([
                'draw' => $this->request->getPost('draw') ?? 1,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * AJAX: Create new project
     */
    public function ajaxCreateProject()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        try {
            // Validasi input
            $validation = \Config\Services::validation();
            $validation->setRules([
                'project_name' => 'required|min_length[3]|max_length[100]',
                'project_code' => 'required|min_length[2]|max_length[20]|regex_match[/^[A-Z0-9]+$/]',
                'description' => 'permit_empty|max_length[500]',
                'is_active' => 'permit_empty|in_list[0,1]'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validation->getErrors()
                ]);
            }

            // Prepare data
            $projectData = [
                'project_name' => trim($this->request->getPost('project_name')),
                'project_code' => strtoupper(trim($this->request->getPost('project_code'))),
                'description' => trim($this->request->getPost('description') ?? ''),
                'is_active' => $this->request->getPost('is_active') == '1' ? true : false,
                'user_id' => session()->get('user_id'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Check if project code exists
            if ($this->projectModel->projectCodeExists($projectData['project_code'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Project code already exists'
                ]);
            }

            // Insert project
            if ($this->projectModel->insert($projectData)) {
                $projectId = $this->projectModel->getInsertID();

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Project created successfully',
                    'project_id' => $projectId,
                    'project' => $projectData
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to create project'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'AJAX create project error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * AJAX: Validate project code
     */
    private function ajaxValidateProjectCode()
    {
        try {
            $projectCode = strtoupper(trim($this->request->getPost('project_code') ?? ''));
            $projectId = $this->request->getPost('project_id') ?? null;

            if (empty($projectCode)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Project code is required'
                ]);
            }

            // Validate format
            if (!preg_match('/^[A-Z0-9]{2,20}$/', $projectCode)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Project code must be 2-20 characters, uppercase letters and numbers only'
                ]);
            }

            // Check uniqueness
            $exists = $this->projectModel->projectCodeExists($projectCode, $projectId);

            if ($exists) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Project code already exists'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Project code is available'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Validate project code error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * AJAX: Update project - FIXED VERSION
     */
    private function ajaxUpdateProject()
    {
        try {
            $projectId = $this->request->getPost('project_id');

            if (!$projectId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Project ID is required'
                ]);
            }

            // Validate input
            $validation = \Config\Services::validation();
            $validation->setRules([
                'project_name' => 'required|min_length[3]|max_length[100]',
                'project_code' => 'required|min_length[2]|max_length[20]|regex_match[/^[A-Z0-9]+$/]',
                'description' => 'permit_empty|max_length[500]',
                'is_active' => 'required|in_list[0,1]' // Pastikan required dan valid
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validation->getErrors()
                ]);
            }

            // Get data
            $projectData = [
                'project_name' => trim($this->request->getPost('project_name')),
                'project_code' => strtoupper(trim($this->request->getPost('project_code'))),
                'description' => trim($this->request->getPost('description') ?? ''),
                'is_active' => $this->request->getPost('is_active') === '1' ? true : false,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Check if project code exists (excluding current)
            if ($this->projectModel->projectCodeExists($projectData['project_code'], $projectId)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Project code already exists'
                ]);
            }

            // Update project menggunakan metode save
            if ($this->projectModel->save(['project_id' => $projectId] + $projectData)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Project updated successfully'
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update project'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Update project error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * AJAX: Get project details with consistent ticket counts
     */
    public function ajaxGetProjectDetails()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        try {
            $projectId = $this->request->getPost('project_id');

            if (!$projectId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Project ID is required'
                ]);
            }

            // Get project with user details
            $project = $this->projectModel->getProjectWithUser($projectId);

            if (!$project) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Project not found'
                ]);
            }

            // Get consistent project statistics
            $projectStats = $this->getProjectStatistics($projectId);

            // Merge statistics with project data
            $project = array_merge($project, $projectStats);

            // Get assigned users
            $assignedUsers = $this->projectAssignmentModel->getAssignedUsersForProject($projectId);

            $project['is_active'] = (bool)$project['is_active'];

            return $this->response->setJSON([
                'success' => true,
                'project' => $project,
                'assigned_users' => $assignedUsers
            ]);
        } catch (\Exception $e) {
            log_message('error', 'AJAX get project details error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * AJAX: Get all users for bulk assignment
     */
    private function ajaxGetAllUsers()
    {
        try {
            $search = $this->request->getPost('search') ?? '';
            $role = $this->request->getPost('role') ?? 'all';

            $db = db_connect();
            $builder = $db->table('users u')
                ->select('u.user_id, u.username, u.full_name, u.email, r.role_name')
                ->join('roles r', 'r.role_id = u.role_id')
                ->where('u.is_active', true);

            if (!empty($search)) {
                $builder->groupStart()
                    ->like('u.full_name', $search)
                    ->orLike('u.username', $search)
                    ->orLike('u.email', $search)
                    ->groupEnd();
            }

            if ($role !== 'all') {
                $builder->where('r.role_name', $role);
            }

            $builder->orderBy('u.full_name', 'ASC');
            $users = $builder->get()->getResultArray();

            return $this->response->setJSON([
                'success' => true,
                'users' => $users
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Get all users error: ' . $e->getMessage());
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
        try {
            $search = $this->request->getPost('search') ?? '';

            $projects = $this->projectModel->getProjectsForBulkAssignment($search);

            return $this->response->setJSON([
                'success' => true,
                'projects' => $projects
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Get projects for bulk error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * AJAX: Assign users to project
     */
    public function ajaxAssignUsersToProject()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        try {
            $projectId = $this->request->getPost('project_id');
            $userIds = $this->request->getPost('user_ids');
            $assignedBy = session()->get('user_id');

            // // Validasi input
            // if (!$projectId || empty($userIds)) {
            //     return $this->response->setJSON([
            //         'success' => false,
            //         'message' => 'Project ID and user IDs are required'
            //     ]);
            // }

            // Parse user IDs (bisa array atau JSON string)
            if (!is_array($userIds)) {
                $userIds = json_decode($userIds, true);
            }

            if (!is_array($userIds) || empty($userIds)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No valid users selected'
                ]);
            }

            // Validasi project exists
            $project = $this->projectModel->find($projectId);
            if (!$project) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Project not found'
                ]);
            }

            // Assign users menggunakan model
            $result = $this->projectAssignmentModel->assignUsersToProject($projectId, $userIds, $assignedBy);

            return $this->response->setJSON($result);
        } catch (\Exception $e) {
            log_message('error', 'AJAX assign users error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * AJAX: Change project status
     */
    public function ajaxChangeProjectStatus()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        try {
            $projectId = $this->request->getPost('project_id');
            $isActive = $this->request->getPost('is_active') == '1' ? true : false;

            if (!$projectId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Project ID is required'
                ]);
            }

            // Update project status
            $updated = $this->projectModel->update($projectId, [
                'is_active' => $isActive,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            if ($updated) {
                $action = $isActive ? 'activated' : 'deactivated';
                return $this->response->setJSON([
                    'success' => true,
                    'message' => "Project {$action} successfully"
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update project status'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Change project status error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * AJAX: Delete project
     */
    public function ajaxDeleteProject()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        try {
            $projectId = $this->request->getPost('project_id');

            if (!$projectId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Project ID is required'
                ]);
            }

            // Check if project has tickets
            $ticketCount = $this->ticketModel->where('project_id', $projectId)->countAllResults();

            if ($ticketCount > 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => "Cannot delete project with {$ticketCount} ticket(s). Please reassign or delete the tickets first."
                ]);
            }

            // Delete project assignments first
            $this->projectAssignmentModel->where('project_id', $projectId)->delete();

            // Delete project
            $deleted = $this->projectModel->delete($projectId);

            if ($deleted) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Project deleted successfully'
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete project'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Delete project error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

// ==================== VIEW TICKETS (AJAX) ===========================
    /**
     * View Tickets - Main page (non-AJAX)
     */
    public function viewTickets()
    {
        $data = $this->loadCommonData();
        $data['title'] = 'View Tickets - NEXUS Admin';

        // Get filter options for dropdowns
        $data['departments'] = $this->departmentModel->findAll();
        $data['priorities'] = $this->getPriorities();
        $data['statuses'] = $this->getStatuses();

        return view('Admin/view_tickets', $data);
    }

    /**
     * AJAX: Get tickets data with filters and pagination
     */
    public function ajaxGetTickets()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        try {
            // Get filters from POST/GET
            $filters = [
                'search' => $this->request->getGetPost('search'),
                'priority' => $this->request->getGetPost('priority'),
                'department' => $this->request->getGetPost('department'),
                'status' => $this->request->getGetPost('status'),
                'date_from' => $this->request->getGetPost('date_from'),
                'date_to' => $this->request->getGetPost('date_to'),
                'customer_id' => $this->request->getGetPost('customer_id')
            ];

            // Get pagination parameters
            $page = $this->request->getGetPost('page') ?: 1;
            $limit = $this->request->getGetPost('limit') ?: 10;
            $offset = ($page - 1) * $limit;

            // Get tickets from model
            $tickets = $this->ticketModel->getTicketsForAdmin($filters, $limit, $offset);
            $totalTickets = $this->ticketModel->countTicketsForAdmin($filters);

            // Format tickets for display
            $formattedTickets = array_map(function ($ticket) {
                return [
                    'id' => $ticket['ticket_number'] ?: $ticket['ticket_id'],
                    'ticket_id' => $ticket['ticket_id'],
                    'title' => $ticket['subject'],
                    'description' => $ticket['description'] ?? 'No description',
                    'priority' => $ticket['priority_name'] ?? 'Medium',
                    'priority_value' => strtolower($ticket['priority_name'] ?? 'medium'),
                    'department' => $ticket['department_name'] ?? 'Not assigned',
                    'department_value' => strtolower(str_replace(' ', '-', $ticket['department_name'] ?? '')),
                    'customer' => $ticket['customer_name'] ?? 'Unknown',
                    'customer_email' => $ticket['customer_email'] ?? '',
                    'status' => $ticket['status_name'] ?? 'Open',
                    'status_value' => strtolower(str_replace(' ', '-', $ticket['status_name'] ?? 'open')),
                    'created' => $this->formatDate($ticket['created_at']),
                    'created_raw' => $ticket['created_at'],
                    'updated' => $this->formatTimeAgo($ticket['updated_at']),
                    'updated_raw' => $ticket['updated_at'],
                    'project' => $ticket['project_name'] ?? null,
                    'project_code' => $ticket['project_code'] ?? null,
                    'due_date' => $ticket['due_date'] ? date('M d, Y', strtotime($ticket['due_date'])) : null,
                    'due_date_raw' => $ticket['due_date'],
                    'assigned_to' => $ticket['assigned_to_name'] ?? 'Unassigned'
                ];
            }, $tickets);

            // Get statistics for the current filter
            $stats = $this->ticketModel->getAdminTicketStatistics();

            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'tickets' => $formattedTickets,
                    'statistics' => $stats,
                    'pagination' => [
                        'total' => $totalTickets,
                        'page' => (int)$page,
                        'limit' => (int)$limit,
                        'total_pages' => ceil($totalTickets / $limit),
                        'offset' => $offset
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            log_message('error', 'AJAX Get Tickets error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to load tickets: ' . $e->getMessage(),
                'data' => [
                    'tickets' => [],
                    'statistics' => [
                        'total_tickets' => 0,
                        'open_tickets' => 0,
                        'resolved_tickets' => 0,
                        'closed_tickets' => 0,
                        'today_tickets' => 0,
                        'high_priority_tickets' => 0
                    ],
                    'pagination' => [
                        'total' => 0,
                        'page' => 1,
                        'limit' => 10,
                        'total_pages' => 0
                    ]
                ]
            ]);
        }
    }

    /**
     * AJAX: Get ticket details
     */
    public function ajaxGetTicketDetails()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        $ticketId = $this->request->getGetPost('ticket_id');

        if (!$ticketId) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Ticket ID is required'
            ]);
        }

        try {
            // Get ticket details from model
            $ticket = $this->ticketModel->getTicketDetailsForAdmin($ticketId);

            if (!$ticket) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'message' => 'Ticket not found'
                ]);
            }

            // Get ticket messages/activity
            $ticketMessageModel = new \App\Models\TicketMessageModel();
            $messages = $ticketMessageModel->getMessagesForTicket($ticketId);

            // Get attachments
            $attachmentModel = new \App\Models\TicketAttachmentModel();
            $attachments = $attachmentModel->getAttachmentsForTicket($ticketId);

            // Format activity log
            $activityLog = array_map(function ($activity) {
                return [
                    'type' => strtolower(str_replace(' ', '-', $activity['message_type'] ?? 'message')),
                    'text' => $activity['message'],
                    'time' => $this->formatTimeAgo($activity['created_at']),
                    'time_raw' => $activity['created_at'],
                    'user' => $activity['full_name'],
                    'photo_profile' => $activity['photo_profile'] ?? null,
                    'role_name' => $activity['role_name'] ?? 'User'
                ];
            }, $ticket['activity'] ?? []);

            // Format messages
            $formattedMessages = array_map(function ($message) {
                return [
                    'id' => $message['message_id'],
                    'sender' => $message['full_name'] ?? 'Unknown',
                    'role' => $message['role_name'] ?? 'User',
                    'message' => $message['message'],
                    'time' => $this->formatTimeAgo($message['created_at']),
                    'time_raw' => $message['created_at'],
                    'photo_profile' => $message['photo_profile'] ?? null
                ];
            }, $messages);

            // Format attachments
            $formattedAttachments = array_map(function ($attachment) {
                $fileSize = $attachment['file_size'];
                $sizeFormatted = '';

                if ($fileSize < 1024) {
                    $sizeFormatted = $fileSize . ' B';
                } elseif ($fileSize < 1048576) {
                    $sizeFormatted = round($fileSize / 1024, 2) . ' KB';
                } else {
                    $sizeFormatted = round($fileSize / 1048576, 2) . ' MB';
                }

                return [
                    'id' => $attachment['attachment_id'],
                    'name' => $attachment['file_name'],
                    'path' => $attachment['file_path'],
                    'type' => $attachment['file_type'],
                    'size' => $sizeFormatted,
                    'uploaded_by' => $attachment['full_name'] ?? 'Unknown',
                    'uploaded_at' => $this->formatTimeAgo($attachment['created_at']),
                    'uploaded_at_raw' => $attachment['created_at']
                ];
            }, $attachments);

            // Get available statuses for dropdown
            $statusModel = new \App\Models\StatusModel();
            $statuses = $statusModel->findAll();

            // Get available priorities for dropdown
            $priorityModel = new \App\Models\PriorityModel();
            $priorities = $priorityModel->findAll();

            // Get available users for assignment
            $userModel = new \App\Models\UserModel();
            $agents = $userModel->where('role_id', 2)->orWhere('role_id', 3)->findAll(); // Assuming role_id 2 and 3 are agents/admins

            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'ticket' => $ticket,
                    'activity' => $activityLog,
                    'messages' => $formattedMessages,
                    'attachments' => $formattedAttachments,
                    'statuses' => $statuses,
                    'priorities' => $priorities,
                    'agents' => $agents
                ]
            ]);
        } catch (\Exception $e) {
            log_message('error', 'AJAX Get Ticket Details error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to load ticket details: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * AJAX: Update ticket status
     */
    public function ajaxUpdateTicketStatus()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        $ticketId = $this->request->getPost('ticket_id');
        $statusId = $this->request->getPost('status_id');

        if (!$ticketId || !$statusId) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Ticket ID and Status ID are required'
            ]);
        }

        try {
            // Get current user ID for resolved_by if needed
            $userId = session()->get('user_id');

            // Update ticket status
            $result = $this->ticketModel->updateTicketStatus($ticketId, $statusId, $userId);

            if ($result) {
                // Add activity log entry
                $ticketMessageModel = new \App\Models\TicketMessageModel();
                $statusModel = new \App\Models\StatusModel();
                $status = $statusModel->find($statusId);

                $message = "Ticket status changed to: " . ($status['status_name'] ?? 'Unknown');
                $ticketMessageModel->addMessage($ticketId, $userId, $message);

                // Get updated ticket
                $updatedTicket = $this->ticketModel->getTicketDetailsForAdmin($ticketId);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Ticket status updated successfully',
                    'data' => [
                        'ticket' => $updatedTicket
                    ]
                ]);
            } else {
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Failed to update ticket status'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'AJAX Update Ticket Status error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to update ticket status: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * AJAX: Update ticket priority
     */
    public function ajaxUpdateTicketPriority()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        $ticketId = $this->request->getPost('ticket_id');
        $priorityId = $this->request->getPost('priority_id');

        if (!$ticketId || !$priorityId) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Ticket ID and Priority ID are required'
            ]);
        }

        try {
            // Update ticket priority
            $result = $this->ticketModel->update($ticketId, [
                'priority_id' => $priorityId,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            if ($result) {
                // Add activity log entry
                $ticketMessageModel = new \App\Models\TicketMessageModel();
                $priorityModel = new \App\Models\PriorityModel();
                $priority = $priorityModel->find($priorityId);
                $userId = session()->get('user_id');

                $message = "Ticket priority changed to: " . ($priority['priority_name'] ?? 'Unknown');
                $ticketMessageModel->addMessage($ticketId, $userId, $message);

                // Get updated ticket
                $updatedTicket = $this->ticketModel->getTicketDetailsForAdmin($ticketId);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Ticket priority updated successfully',
                    'data' => [
                        'ticket' => $updatedTicket
                    ]
                ]);
            } else {
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Failed to update ticket priority'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'AJAX Update Ticket Priority error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to update ticket priority: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * AJAX: Assign ticket to agent
     */
    public function ajaxAssignTicket()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        $ticketId = $this->request->getPost('ticket_id');
        $agentId = $this->request->getPost('agent_id');

        if (!$ticketId) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Ticket ID is required'
            ]);
        }

        try {
            // Update ticket assignment
            $result = $this->ticketModel->update($ticketId, [
                'assigned_to' => $agentId ?: null,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            if ($result) {
                // Add activity log entry
                $ticketMessageModel = new \App\Models\TicketMessageModel();
                $userModel = new \App\Models\UserModel();
                $userId = session()->get('user_id');

                if ($agentId) {
                    $agent = $userModel->find($agentId);
                    $message = "Ticket assigned to: " . ($agent['full_name'] ?? 'Unknown');
                } else {
                    $message = "Ticket unassigned";
                }

                $ticketMessageModel->addMessage($ticketId, $userId, $message);

                // Get updated ticket
                $updatedTicket = $this->ticketModel->getTicketDetailsForAdmin($ticketId);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Ticket assignment updated successfully',
                    'data' => [
                        'ticket' => $updatedTicket
                    ]
                ]);
            } else {
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Failed to update ticket assignment'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'AJAX Assign Ticket error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to update ticket assignment: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * AJAX: Add message to ticket
     */
    public function ajaxAddTicketMessage()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        $ticketId = $this->request->getPost('ticket_id');
        $message = $this->request->getPost('message');

        if (!$ticketId || !$message) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Ticket ID and Message are required'
            ]);
        }

        try {
            $userId = session()->get('user_id');
            $ticketMessageModel = new \App\Models\TicketMessageModel();

            // Add message
            $messageId = $ticketMessageModel->addMessage($ticketId, $userId, $message);

            if ($messageId) {
                // Update ticket's updated_at timestamp
                $this->ticketModel->update($ticketId, [
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                // Get the newly added message with user details
                $userModel = new \App\Models\UserModel();
                $user = $userModel->find($userId);

                $newMessage = [
                    'id' => $messageId,
                    'sender' => $user['full_name'] ?? 'You',
                    'role' => $user['role_name'] ?? 'Admin',
                    'message' => $message,
                    'time' => 'Just now',
                    'time_raw' => date('Y-m-d H:i:s'),
                    'photo_profile' => $user['photo_profile'] ?? null
                ];

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Message added successfully',
                    'data' => [
                        'message' => $newMessage
                    ]
                ]);
            } else {
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Failed to add message'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'AJAX Add Ticket Message error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to add message: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * AJAX: Get ticket statistics
     */
    public function ajaxGetTicketStatistics()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        try {
            // Get filters if any
            $filters = [
                'date_from' => $this->request->getGetPost('date_from'),
                'date_to' => $this->request->getGetPost('date_to'),
                'department' => $this->request->getGetPost('department')
            ];

            // Get statistics from model
            $stats = $this->ticketModel->getAdminTicketStatistics();

            // If filters provided, get filtered statistics
            if (!empty($filters['date_from']) || !empty($filters['date_to']) || !empty($filters['department'])) {
                // Implement filtered statistics logic here if needed
                // For now, return the full statistics
            }

            // Get ticket trend data
            $trendData = $this->ticketModel->getTicketTrend();

            // Get ticket status data for charts
            $statusData = $this->ticketModel->getTicketStatusData();

            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'statistics' => $stats,
                    'trend' => $trendData,
                    'status_data' => $statusData
                ]
            ]);
        } catch (\Exception $e) {
            log_message('error', 'AJAX Get Ticket Statistics error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to load statistics: ' . $e->getMessage(),
                'data' => [
                    'statistics' => [
                        'total_tickets' => 0,
                        'open_tickets' => 0,
                        'resolved_tickets' => 0,
                        'closed_tickets' => 0,
                        'today_tickets' => 0,
                        'high_priority_tickets' => 0,
                        'tickets_by_department' => []
                    ],
                    'trend' => [
                        'current' => 0,
                        'previous' => 0,
                        'trend' => '0%'
                    ],
                    'status_data' => [
                        'data' => [],
                        'total' => 0
                    ]
                ]
            ]);
        }
    }

    /**
     * AJAX: Upload attachment to ticket
     */
    public function ajaxUploadAttachment()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        $ticketId = $this->request->getPost('ticket_id');

        if (!$ticketId) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Ticket ID is required'
            ]);
        }

        $file = $this->request->getFile('attachment');

        if (!$file || !$file->isValid()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'No valid file uploaded'
            ]);
        }

        try {
            // Validate file
            if ($file->getSize() > 10485760) { // 10MB limit
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'File size exceeds 10MB limit'
                ]);
            }

            // Allowed file types
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'zip'];
            $fileExt = $file->getClientExtension();

            if (!in_array(strtolower($fileExt), $allowedTypes)) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'File type not allowed. Allowed types: ' . implode(', ', $allowedTypes)
                ]);
            }

            // Create upload directory if not exists
            $uploadPath = WRITEPATH . 'uploads/tickets/' . date('Y/m');
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Generate unique filename
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);

            // Save to database
            $attachmentModel = new \App\Models\TicketAttachmentModel();
            $userId = session()->get('user_id');

            $attachmentData = [
                'ticket_id' => $ticketId,
                'uploaded_by' => $userId,
                'file_name' => $file->getClientName(),
                'file_path' => 'uploads/tickets/' . date('Y/m') . '/' . $newName,
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize()
            ];

            $attachmentId = $attachmentModel->createAttachment($attachmentData);

            if ($attachmentId) {
                // Add activity log
                $ticketMessageModel = new \App\Models\TicketMessageModel();
                $message = "File uploaded: " . $file->getClientName();
                $ticketMessageModel->addMessage($ticketId, $userId, $message);

                // Update ticket's updated_at timestamp
                $this->ticketModel->update($ticketId, [
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                // Get user info for display
                $userModel = new \App\Models\UserModel();
                $user = $userModel->find($userId);

                // Format file size
                $fileSize = $file->getSize();
                $sizeFormatted = '';

                if ($fileSize < 1024) {
                    $sizeFormatted = $fileSize . ' B';
                } elseif ($fileSize < 1048576) {
                    $sizeFormatted = round($fileSize / 1024, 2) . ' KB';
                } else {
                    $sizeFormatted = round($fileSize / 1048576, 2) . ' MB';
                }

                $attachmentInfo = [
                    'id' => $attachmentId,
                    'name' => $file->getClientName(),
                    'path' => 'uploads/tickets/' . date('Y/m') . '/' . $newName,
                    'type' => $file->getClientMimeType(),
                    'size' => $sizeFormatted,
                    'uploaded_by' => $user['full_name'] ?? 'You',
                    'uploaded_at' => 'Just now',
                    'uploaded_at_raw' => date('Y-m-d H:i:s')
                ];

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'File uploaded successfully',
                    'data' => [
                        'attachment' => $attachmentInfo
                    ]
                ]);
            } else {
                // Delete uploaded file if database save failed
                @unlink($uploadPath . '/' . $newName);

                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Failed to save attachment to database'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'AJAX Upload Attachment error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to upload file: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * AJAX: Delete attachment
     */
    public function ajaxDeleteAttachment()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        $attachmentId = $this->request->getPost('attachment_id');

        if (!$attachmentId) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Attachment ID is required'
            ]);
        }

        try {
            $attachmentModel = new \App\Models\TicketAttachmentModel();

            // Get attachment info
            $attachment = $attachmentModel->find($attachmentId);

            if (!$attachment) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'message' => 'Attachment not found'
                ]);
            }

            // Delete file from server
            $filePath = WRITEPATH . $attachment['file_path'];
            if (file_exists($filePath)) {
                @unlink($filePath);
            }

            // Delete from database
            $result = $attachmentModel->delete($attachmentId);

            if ($result) {
                // Add activity log
                $ticketMessageModel = new \App\Models\TicketMessageModel();
                $userId = session()->get('user_id');
                $message = "File deleted: " . $attachment['file_name'];
                $ticketMessageModel->addMessage($attachment['ticket_id'], $userId, $message);

                // Update ticket's updated_at timestamp
                $this->ticketModel->update($attachment['ticket_id'], [
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Attachment deleted successfully'
                ]);
            } else {
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete attachment from database'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'AJAX Delete Attachment error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to delete attachment: ' . $e->getMessage()
            ]);
        }
    }

// ==================== HELPER METHODS ====================
    /**
     * Escape CSV value
     */
    private function escapeCsv($value)
    {
        $value = str_replace('"', '""', $value);
        $value = str_replace(["\r", "\n"], ' ', $value);
        return $value;
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

    // ==================== SYSTEM SETTINGS ====================
    public function systemSettings()
    {
        $data = $this->loadCommonData();
        $data['title'] = 'System Settings - NEXUS Admin';

        return view('Admin/system_settings', $data);
    }
}
