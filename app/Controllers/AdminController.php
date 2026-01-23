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
}
