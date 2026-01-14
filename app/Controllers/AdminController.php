<?php

namespace App\Controllers;

use App\Models\TicketModel;
use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\DepartmentModel;
use App\Models\ProjectModel;

use PhpOffice\PhpSpreadsheet\IOFactory as SpreadsheetIOFactory;

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
    }
    
    /**
     * Dashboard dengan data dinamis
     */
    public function dashboard()
    {
        $data = $this->loadCommonData();
        $data['title'] = 'Admin Dashboard - NEXUS';

        // Get statistics for dashboard
        $db = db_connect();

        try {
            // Total users count
            $totalUsers = $db->table('users')->countAll();

            // Total projects count
            $totalProjects = $db->table('projects')->where('is_active', true)->countAllResults();

            // Total tickets count
            $totalTickets = $db->table('tickets')->countAll();

            // Open tickets count (status_id 1 = Open, 2 = In Progress)
            $openTickets = $db->table('tickets')
                ->where('status_id', 1)
                ->orWhere('status_id', 2)
                ->countAllResults();

            // Users by role
            $usersByRole = $db->table('users u')
                ->select('r.role_name, COUNT(*) as count')
                ->join('roles r', 'r.role_id = u.role_id')
                ->groupBy('r.role_name')
                ->get()
                ->getResultArray();

            // Recent users
            $recentUsers = $db->table('users u')
                ->select('u.*, r.role_name, d.department_name')
                ->join('roles r', 'r.role_id = u.role_id', 'left')
                ->join('departments d', 'd.department_id = u.department_id', 'left')
                ->orderBy('u.created_at', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();

            // Recent projects
            $recentProjects = $db->table('projects')
                ->select('projects.*')
                ->where('is_active', true)
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();

            // Add ticket counts to recent projects
            foreach ($recentProjects as &$project) {
                $project['total_tickets'] = $db->table('tickets')
                    ->where('project_id', $project['project_id'])
                    ->countAllResults();

                $project['open_tickets'] = $db->table('tickets')
                    ->where('project_id', $project['project_id'])
                    ->groupStart()
                    ->where('status_id', 1)
                    ->orWhere('status_id', 2)
                    ->groupEnd()
                    ->countAllResults();
            }

            // PERBAIKAN: Cek struktur tabel statuses terlebih dahulu
            $statusTableInfo = $db->query("
                SELECT column_name 
                FROM information_schema.columns 
                WHERE table_name = 'statuses' 
                AND table_schema = 'public'
            ")->getResultArray();

            $statusColumns = array_column($statusTableInfo, 'column_name');
            $hasColorColumn = in_array('color', $statusColumns);

            // Get ticket status breakdown - dengan pengecekan kolom
            if ($hasColorColumn) {
                // Jika kolom color ada
                $ticketStatus = $db->table('tickets t')
                    ->select('s.status_id, s.status_name, s.color, COUNT(t.ticket_id) as count')
                    ->join('statuses s', 's.status_id = t.status_id')
                    ->groupBy('s.status_id, s.status_name, s.color')
                    ->orderBy('s.status_id')
                    ->get()
                    ->getResultArray();
            } else {
                // Jika kolom color tidak ada, gunakan default colors
                $ticketStatus = $db->table('tickets t')
                    ->select('s.status_id, s.status_name, COUNT(t.ticket_id) as count')
                    ->join('statuses s', 's.status_id = t.status_id')
                    ->groupBy('s.status_id, s.status_name')
                    ->orderBy('s.status_id')
                    ->get()
                    ->getResultArray();

                // Tambahkan warna default
                $defaultColors = [
                    1 => '#635A91', // Open
                    2 => '#AFB9D4', // In Progress
                    3 => '#EDE1C7', // Need Info
                    4 => '#89A6CE', // Resolved
                    5 => '#BDB7D9'  // Closed
                ];

                foreach ($ticketStatus as &$status) {
                    $statusId = $status['status_id'];
                    $status['color'] = $defaultColors[$statusId] ?? '#6B7280';
                }
            }

            // Calculate percentages
            $totalTicketsForPercentage = array_sum(array_column($ticketStatus, 'count'));
            foreach ($ticketStatus as &$status) {
                $status['percentage'] = $totalTicketsForPercentage > 0
                    ? round(($status['count'] / $totalTicketsForPercentage) * 100, 1)
                    : 0;
            }

            $data['stats'] = [
                'total_users' => $totalUsers,
                'total_projects' => $totalProjects,
                'total_tickets' => $totalTickets,
                'open_tickets' => $openTickets,
                'users_by_role' => $usersByRole,
                'recent_users' => $recentUsers,
                'recent_projects' => $recentProjects
            ];

            $data['ticket_status'] = $ticketStatus;
            $data['total_tickets_for_chart'] = $totalTicketsForPercentage;

            $data['quickLinks'] = $this->getQuickLinks();

            $data['recentActivities'] = $this->getRecentActivities();

            $data['systemNotifications'] = $this->getSystemNotifications();

        } catch (\Exception $e) {
            log_message('error', 'Dashboard error: ' . $e->getMessage());

            // Fallback data jika error
            $data['stats'] = [
                'total_users' => 0,
                'total_projects' => 0,
                'total_tickets' => 0,
                'open_tickets' => 0,
                'users_by_role' => [],
                'recent_users' => [],
                'recent_projects' => []
            ];

            $data['ticket_status'] = [];
            $data['total_tickets_for_chart'] = 0;
        }

        return view('Admin/dashboard', $data);
    }

    // ==================== PRIVATE METHODS ====================

    /**
     * Get all dashboard data
     */
    private function getDashboardData(): array
    {
        return [
            'stats' => $this->getDashboardStats(),
            'ticketStatus' => $this->getTicketStatusData(),
            'recentActivities' => $this->getRecentActivities(),
            'projectOverview' => $this->getProjectOverview(),
            'quickLinks' => $this->getQuickLinks(),
            'systemNotifications' => $this->getSystemNotifications()
        ];
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats(): array
    {
        $db = db_connect();

        $stats = [
            'total_users' => $this->userModel->countAll(),
            'total_projects' => $this->projectModel->where('is_active', 1)->countAllResults(),
            'total_tickets' => $this->ticketModel->countAll(),
            'open_tickets' => $this->ticketModel->whereIn('status_id', [1, 2])->countAllResults(),
        ];

        // Get trend data
        $stats['user_trend'] = $this->getUserTrend();
        $stats['ticket_trend'] = $this->getTicketTrend();
        $stats['users_by_role'] = $this->userModel->countUsersByRole();

        return $stats;
    }

    /**
     * Get ticket status breakdown
     */
    private function getTicketStatusData(): array
    {
        $db = db_connect();

        $statuses = $db->table('statuses')->get()->getResultArray();
        $ticketData = [];
        $totalTickets = $this->ticketModel->countAll();

        foreach ($statuses as $status) {
            $count = $this->ticketModel->where('status_id', $status['status_id'])->countAllResults();
            $percentage = $totalTickets > 0 ? round(($count / $totalTickets) * 100, 1) : 0;

            $ticketData[] = [
                'status_name' => $status['status_name'],
                'color' => $status['color'] ?? $this->getDefaultStatusColor($status['status_id']),
                'count' => $count,
                'percentage' => $percentage
            ];
        }

        return [
            'data' => $ticketData,
            'total_tickets' => $totalTickets
        ];
    }

    /**
     * Get recent activities from tickets and users
     */
    private function getRecentActivities(int $limit = 5): array
    {
        $db = db_connect();

        // Get recent tickets with user info
        $recentTickets = $db->table('tickets t')
            ->select('t.ticket_id, t.ticket_number, t.title, t.created_at, 
                     u.username, u.full_name, r.role_name,
                     s.status_name, p.project_name')
            ->join('users u', 'u.user_id = t.created_by')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->join('statuses s', 's.status_id = t.status_id', 'left')
            ->join('projects p', 'p.project_id = t.project_id', 'left')
            ->orderBy('t.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        // Format activities
        $activities = [];
        foreach ($recentTickets as $ticket) {
            $activities[] = [
                'time' => $this->formatTimeAgo($ticket['created_at']),
                'user' => $ticket['full_name'] ?: $ticket['username'],
                'role' => $ticket['role_name'] ?? 'Customer',
                'action' => "created ticket #{$ticket['ticket_number']}",
                'description' => $ticket['title'],
                'project' => $ticket['project_name'] ?? null,
                'avatar_color' => $this->getAvatarColor($ticket['role_name'] ?? 'customer')
            ];
        }

        return $activities;
    }

    /**
     * Get project overview with ticket counts
     */
    private function getProjectOverview(int $limit = 5): array
    {
        $db = db_connect();

        $projects = $db->table('projects p')
            ->select('p.*, 
                (SELECT COUNT(*) FROM tickets t WHERE t.project_id = p.project_id) as total_tickets,
                (SELECT COUNT(*) FROM tickets t WHERE t.project_id = p.project_id AND t.status_id IN (1,2)) as open_tickets')
            ->where('p.is_active', 1)
            ->orderBy('p.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        return $projects;
    }

    /**
     * Get system notifications
     */
    private function getSystemNotifications(): array
    {
        $notifications = [];

        // Check for high priority tickets
        $highPriorityTickets = $this->ticketModel
            ->where('priority_id', 1) // Assuming 1 = High Priority
            ->whereIn('status_id', [1, 2]) // Open/In Progress
            ->countAllResults();

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
        $slaViolations = $this->getSLAViolations();
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

    /**
     * Get quick access links
     */
    private function getQuickLinks(): array
    {
        return [
            [
                'title' => 'Manage Users',
                'url' => base_url('admin/users'),
                'icon' => 'users',
                'color' => 'bg-purple-100 text-purple-600'
            ],
            [
                'title' => 'Manage Roles',
                'url' => base_url('admin/roles'),
                'icon' => 'user-tag',
                'color' => 'bg-blue-100 text-blue-600'
            ],
            [
                'title' => 'Manage Departments',
                'url' => base_url('admin/departments'),
                'icon' => 'sitemap',
                'color' => 'bg-green-100 text-green-600'
            ],
            [
                'title' => 'View Tickets',
                'url' => base_url('admin/tickets'),
                'icon' => 'ticket-alt',
                'color' => 'bg-yellow-100 text-yellow-600'
            ],
            [
                'title' => 'Manage Projects',
                'url' => base_url('admin/projects'),
                'icon' => 'project-diagram',
                'color' => 'bg-indigo-100 text-indigo-600'
            ]
        ];
    }

    /**
     * Get user trend (last 7 days)
     */
    private function getUserTrend(): string
    {
        $db = db_connect();
        $lastWeek = date('Y-m-d', strtotime('-7 days'));

        $currentWeekUsers = $db->table('users')
            ->where('created_at >=', $lastWeek)
            ->countAllResults();

        $previousWeekUsers = $db->table('users')
            ->where('created_at >=', date('Y-m-d', strtotime('-14 days')))
            ->where('created_at <', $lastWeek)
            ->countAllResults();

        if ($previousWeekUsers == 0) {
            return $currentWeekUsers > 0 ? '+100%' : '0%';
        }

        $trend = (($currentWeekUsers - $previousWeekUsers) / $previousWeekUsers) * 100;
        return ($trend >= 0 ? '+' : '') . round($trend, 1) . '%';
    }

    /**
     * Get ticket trend (last 7 days)
     */
    private function getTicketTrend(): string
    {
        $db = db_connect();
        $lastWeek = date('Y-m-d', strtotime('-7 days'));

        $currentWeekTickets = $db->table('tickets')
            ->where('created_at >=', $lastWeek)
            ->countAllResults();

        $previousWeekTickets = $db->table('tickets')
            ->where('created_at >=', date('Y-m-d', strtotime('-14 days')))
            ->where('created_at <', $lastWeek)
            ->countAllResults();

        if ($previousWeekTickets == 0) {
            return $currentWeekTickets > 0 ? '+100%' : '0%';
        }

        $trend = (($currentWeekTickets - $previousWeekTickets) / $previousWeekTickets) * 100;
        return ($trend >= 0 ? '+' : '') . round($trend, 1) . '%';
    }

    /**
     * Get SLA violations count
     */
    private function getSLAViolations(): int
    {
        $db = db_connect();

        // Assuming SLA is 72 hours (3 days)
        $slaTime = date('Y-m-d H:i:s', strtotime('-3 days'));

        return $db->table('tickets')
            ->where('created_at <=', $slaTime)
            ->whereIn('status_id', [1, 2]) // Open/In Progress
            ->countAllResults();
    }

    /**
     * Get default status color
     */
    private function getDefaultStatusColor(int $statusId): string
    {
        $colors = [
            1 => '#635A91', // Open
            2 => '#AFB9D4', // In Progress
            3 => '#EDE1C7', // Need Info
            4 => '#89A6CE', // Resolved
            5 => '#BDB7D9'  // Closed
        ];

        return $colors[$statusId] ?? '#6B7280';
    }

    /**
     * Get avatar color based on role
     */
    private function getAvatarColor(string $role): string
    {
        $colors = [
            'Admin' => '#F3E8FF',
            'Support' => '#DBEAFE',
            'Developer' => '#E0E7FF',
            'Customer' => '#FEF3C7'
        ];

        return $colors[$role] ?? '#F3F4F6';
    }

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

        // Get user statistics
        $data['userStats'] = $this->userModel->getUserStatistics();

        // Check if AJAX request for table data
        if ($this->request->isAJAX() && $this->request->getMethod() === 'post') {
            return $this->getUsersTableData();
        }

        return view('Admin/manage_users', $data);
    }

    /**
     * Get users data for DataTable (AJAX)
     */
    private function getUsersTableData()
    {
        try {
            // Get DataTable parameters
            $draw = $this->request->getPost('draw');
            $start = $this->request->getPost('start') ?? 0;
            $length = $this->request->getPost('length') ?? 10;
            $search = $this->request->getPost('search')['value'] ?? '';

            // Get filters
            $filters = [
                'search' => $search,
                'role_id' => $this->request->getPost('role_id'),
                'department_id' => $this->request->getPost('department_id'),
                'is_active' => $this->request->getPost('is_active'),
                'date_from' => $this->request->getPost('date_from'),
                'date_to' => $this->request->getPost('date_to'),
                'sort' => $this->request->getPost('order')[0]['column'] ?? 0,
                'order' => $this->request->getPost('order')[0]['dir'] ?? 'desc'
            ];

            // Get users data with pagination
            $users = $this->userModel->getUsersWithRole($filters, $length, $start);
            $totalRecords = $this->userModel->countAll();
            $filteredRecords = $this->userModel->countFilteredUsers($filters);

            // Format response for DataTable
            $data = [];
            foreach ($users as $user) {
                $data[] = [
                    'DT_RowId' => 'user_' . $user['user_id'],
                    'user_id' => $user['user_id'],
                    'full_name' => $user['full_name'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'role_name' => $user['role_name'] ?? 'N/A',
                    'department_name' => $user['department_name'] ?? 'N/A',
                    'is_active' => (bool) $user['is_active'],
                    'created_at' => date('M d, Y', strtotime($user['created_at'])),
                    'actions' => $this->getUserActionsHtml($user['user_id'])
                ];
            }

            return $this->response->setJSON([
                'draw' => $draw,
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Users AJAX error: ' . $e->getMessage());
            return $this->response->setJSON([
                'draw' => $this->request->getPost('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Failed to load users data'
            ]);
        }
    }

    /**
     * Get user details for AJAX
     */
    public function getUserDetails($id = null)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/admin/users');
        }

        try {
            $userId = $id ?: $this->request->getPost('user_id');

            if (!$userId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User ID is required'
                ]);
            }

            $user = $this->userModel->getUserWithDetails($userId);

            if (!$user) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User not found'
                ]);
            }

            // Get user's assigned projects
            $projects = $this->userModel->getUserProjects($userId);
            $projectNames = array_column($projects, 'project_name');

            // Format response
            $response = [
                'success' => true,
                'user' => [
                    'user_id' => $user['user_id'],
                    'full_name' => $user['full_name'],
                    'email' => $user['email'],
                    'username' => $user['username'],
                    'role_name' => $user['role_name'] ?? 'N/A',
                    'department_name' => $user['department_name'] ?? 'N/A',
                    'phone_number' => $user['phone_number'] ?? 'N/A',
                    'is_active' => (bool) $user['is_active'],
                    'created_at' => date('F d, Y', strtotime($user['created_at'])),
                    'last_login' => $user['last_login'] ?
                        $this->formatTimeAgo($user['last_login']) : 'Never logged in',
                    'total_tickets' => $user['total_tickets'] ?? 0,
                    'total_projects' => $user['total_projects'] ?? 0,
                    'avatar_initials' => $this->getAvatarInitials($user['full_name']),
                    'projects' => $projectNames
                ]
            ];

            return $this->response->setJSON($response);

        } catch (\Exception $e) {
            log_message('error', 'User details error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load user details'
            ]);
        }
    }

    /**
     * Add new user (AJAX)
     */
    public function addUser()
    {
        if (!$this->request->isAJAX()) {
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
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $validation->getErrors()
                ]);
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
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'User added successfully',
                    'user_id' => $this->userModel->getInsertID()
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to add user'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Add user error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Edit user (AJAX)
     */
    public function editUser($id)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/admin/users');
        }

        try {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'username' => 'required|min_length[3]|max_length[50]',
                'full_name' => 'required|min_length[3]|max_length[100]',
                'email' => 'required|valid_email',
                'role_id' => 'required|integer',
                'department_id' => 'permit_empty|integer',
                'phone_number' => 'permit_empty|max_length[20]'
            ]);

            // Custom validation for unique fields (excluding current user)
            $validation->setRule('username', 'Username', "is_unique[users.username,user_id,{$id}]");
            $validation->setRule('email', 'Email', "is_unique[users.email,user_id,{$id}]");

            if (!$validation->withRequest($this->request)->run()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $validation->getErrors()
                ]);
            }

            $userData = [
                'user_id' => $id,
                'username' => $this->request->getPost('username'),
                'full_name' => $this->request->getPost('full_name'),
                'email' => $this->request->getPost('email'),
                'role_id' => $this->request->getPost('role_id'),
                'department_id' => $this->request->getPost('department_id') ?: null,
                'phone_number' => $this->request->getPost('phone_number'),
                'is_active' => $this->request->getPost('is_active') ? true : false
            ];

            // Update password only if provided
            if ($this->request->getPost('password')) {
                $userData['password'] = $this->request->getPost('password');
            }

            if ($this->userModel->save($userData)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'User updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update user'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Edit user error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Reset password (AJAX)
     */
    public function resetPassword($id)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/admin/users');
        }

        try {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'new_password' => 'required|min_length[6]',
                'confirm_password' => 'required|matches[new_password]'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $validation->getErrors()
                ]);
            }

            $newPassword = $this->request->getPost('new_password');

            if ($this->userModel->update($id, ['password' => $newPassword])) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Password reset successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to reset password'
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
     * Change user status (AJAX)
     */
    public function changeStatus($id)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/admin/users');
        }

        try {
            $status = $this->request->getPost('status');
            $isActive = ($status === 'active' || $status === '1') ? true : false;

            if ($this->userModel->changeStatus($id, $isActive)) {
                $statusText = $isActive ? 'activated' : 'deactivated';
                return $this->response->setJSON([
                    'success' => true,
                    'message' => "User {$statusText} successfully"
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to change user status'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Change status error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Delete user (AJAX)
     */
    public function deleteUser($id)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/admin/users');
        }

        try {
            // Prevent deleting yourself
            if ($id == session()->get('user_id')) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Cannot delete your own account'
                ]);
            }

            if ($this->userModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'User deleted successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete user'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Delete user error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
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

            // Get all users (no pagination for export)
            $users = $this->userModel->getUsersWithRole($filters, 0, 0);

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

    /**
     * Get user actions HTML for DataTable
     */
    private function getUserActionsHtml(int $userId): string
    {
        return '
        <div class="flex items-center gap-2">
            <button class="btn-view-user p-2 text-blue-600 hover:text-blue-800 transition-colors" 
                    data-user-id="' . $userId . '" 
                    title="View Details">
                <i class="fas fa-eye"></i>
            </button>
            <button class="btn-edit-user p-2 text-secondary hover:text-[#817CB2] transition-colors" 
                    data-user-id="' . $userId . '" 
                    title="Edit User">
                <i class="fas fa-edit"></i>
            </button>
            <button class="btn-reset-password p-2 text-green-600 hover:text-green-800 transition-colors" 
                    data-user-id="' . $userId . '" 
                    title="Reset Password">
                <i class="fas fa-key"></i>
            </button>
            <button class="btn-delete-user p-2 text-red-600 hover:text-red-800 transition-colors" 
                    data-user-id="' . $userId . '" 
                    title="Delete User">
                <i class="fas fa-trash"></i>
            </button>
        </div>';
    }

    /**
     * Get avatar initials from full name
     */
    private function getAvatarInitials(string $fullName): string
    {
        $initials = '';
        $names = explode(' ', $fullName);

        foreach ($names as $name) {
            if (strlen($initials) < 2) {
                $initials .= strtoupper(substr($name, 0, 1));
            }
        }

        return $initials;
    }

    /**
     * Format time ago
     */
    private function formatTimeAgo(string $datetime): string
    {
        $time = strtotime($datetime);
        $now = time();
        $diff = $now - $time;

        if ($diff < 60)
            return 'Just now';
        if ($diff < 3600)
            return floor($diff / 60) . ' minutes ago';
        if ($diff < 86400)
            return floor($diff / 3600) . ' hours ago';
        if ($diff < 604800)
            return floor($diff / 86400) . ' days ago';

        return date('M d, Y', $time);
    }

    /**
     * Handle AJAX requests for users data
     */
    private function getUsersAjax()
    {
        try {
            // Get request parameters
            $draw = $this->request->getPost('draw');
            $start = $this->request->getPost('start') ?? 0;
            $length = $this->request->getPost('length') ?? 10;
            $search = $this->request->getPost('search')['value'] ?? '';

            // Get filters
            $filters = [
                'search' => $search,
                'role_id' => $this->request->getPost('role_id'),
                'department_id' => $this->request->getPost('department_id'),
                'is_active' => $this->request->getPost('is_active'),
                'date_from' => $this->request->getPost('date_from'),
                'date_to' => $this->request->getPost('date_to'),
                'sort' => $this->request->getPost('sort') ?? 'created_at',
                'order' => $this->request->getPost('order') ?? 'DESC'
            ];

            // Get users data
            $users = $this->userModel->getUsersWithRole($filters, $length, $start);
            $totalRecords = $this->userModel->countAll();
            $filteredRecords = $this->userModel->countFilteredUsers($filters);

            // Format response data
            $data = [];
            foreach ($users as $user) {
                $data[] = [
                    'user_id' => $user['user_id'],
                    'username' => $user['username'],
                    'full_name' => $user['full_name'],
                    'email' => $user['email'],
                    'role_name' => $user['role_name'] ?? 'N/A',
                    'department_name' => $user['department_name'] ?? 'N/A',
                    'is_active' => (bool) $user['is_active'],
                    'created_at' => date('M d, Y', strtotime($user['created_at'])),
                    'last_login' => $user['last_login'] ? date('M d, Y H:i', strtotime($user['last_login'])) : 'Never',
                    'actions' => $this->getUserActionsHtml($user['user_id'])
                ];
            }

            return $this->response->setJSON([
                'draw' => $draw,
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Users AJAX error: ' . $e->getMessage());
            return $this->response->setJSON([
                'draw' => $this->request->getPost('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Failed to load users data'
            ]);
        }
    }

    // Tambahkan di class AdminController, setelah method manageUsers()

    /**
     * Manage Roles - Main method
     */
    public function manageRoles()
    {
        $data = $this->loadCommonData();
        $data['title'] = 'Manage Roles - NEXUS Admin';

        // Load roles data
        $data['roles'] = $this->roleModel->getAllRolesWithCount();

        // Load available permissions
        $data['allPermissions'] = $this->roleModel->getAllPermissions();

        // Get default selected role (first role)
        $data['selectedRole'] = !empty($data['roles']) ? $data['roles'][0] : null;

        if ($data['selectedRole']) {
            $data['rolePermissions'] = $this->roleModel->getRolePermissions($data['selectedRole']['role_id']);
            $data['roleRules'] = $this->roleModel->getRoleRules($data['selectedRole']['role_id']);
            $data['coreResponsibilities'] = $this->roleModel->getCoreResponsibilities($data['selectedRole']['role_name']);
        }

        // Check for AJAX requests
        if ($this->request->isAJAX()) {
            return $this->handleRolesAjax();
        }

        return view('Admin/manage_roles', $data);
    }

    /**
     * Handle AJAX requests for roles
     */
    private function handleRolesAjax()
    {
        try {
            $action = $this->request->getPost('action');

            switch ($action) {
                case 'get_role_details':
                    return $this->getRoleDetailsAjax();
                case 'save_role':
                    return $this->saveRoleAjax();
                case 'update_permission':
                    return $this->updatePermissionAjax();
                case 'delete_role':
                    return $this->deleteRoleAjax();
                case 'duplicate_role':
                    return $this->duplicateRoleAjax();
                case 'reset_role':
                    return $this->resetRoleAjax();
                default:
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Invalid action'
                    ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Roles AJAX error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get role details for AJAX
     */
    private function getRoleDetailsAjax()
    {
        $roleId = $this->request->getPost('role_id');

        if (!$roleId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Role ID is required'
            ]);
        }

        $role = $this->roleModel->getRoleById($roleId);

        if (!$role) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Role not found'
            ]);
        }

        // Get role permissions grouped by module
        $permissions = $this->roleModel->getRolePermissions($roleId);
        $permissionsByModule = [];

        foreach ($permissions as $permission) {
            $module = $permission['module'];
            if (!isset($permissionsByModule[$module])) {
                $permissionsByModule[$module] = [];
            }
            $permissionsByModule[$module][] = $permission;
        }

        // Get role rules and responsibilities
        $rules = $this->roleModel->getRoleRules($roleId);
        $responsibilities = $this->roleModel->getCoreResponsibilities($role['role_name']);

        return $this->response->setJSON([
            'success' => true,
            'role' => $role,
            'permissions' => $permissionsByModule,
            'rules' => $rules,
            'responsibilities' => $responsibilities,
            'accessLevelName' => $this->roleModel->getAccessLevelName($role['access_level'])
        ]);
    }

    /**
     * Save/update role via AJAX
     */
    private function saveRoleAjax()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'role_name' => 'required|min_length[2]|max_length[100]',
            'description' => 'required|min_length[10]|max_length[500]',
            'access_level' => 'required|in_list[external,internal,full,technical]'
        ]);

        $roleId = $this->request->getPost('role_id');

        // For new roles, check uniqueness
        if (!$roleId) {
            $validation->setRule('role_name', 'Role Name', 'is_unique[roles.role_name]');
        } else {
            $validation->setRule('role_name', 'Role Name', "is_unique[roles.role_name,role_id,{$roleId}]");
        }

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $validation->getErrors()
            ]);
        }

        $roleData = [
            'role_name' => $this->request->getPost('role_name'),
            'description' => $this->request->getPost('description'),
            'access_level' => $this->request->getPost('access_level'),
            'color_class' => $this->roleModel->generateColorClass($this->request->getPost('access_level'))
        ];

        // If editing existing role
        if ($roleId) {
            $roleData['role_id'] = $roleId;

            // Don't allow editing core roles' name and access level
            $existingRole = $this->roleModel->find($roleId);
            if ($existingRole && $existingRole['is_core']) {
                unset($roleData['role_name']);
                unset($roleData['access_level']);
            }
        }

        // Save role
        if ($this->roleModel->save($roleData)) {
            $newRoleId = $roleId ?: $this->roleModel->getInsertID();

            // Save permissions if provided
            $permissions = $this->request->getPost('permissions');
            if ($permissions) {
                $permissionData = [];
                $allPermissions = $this->roleModel->getAllPermissions();

                foreach ($allPermissions as $module => $modulePermissions) {
                    foreach ($modulePermissions as $perm) {
                        $isAllowed = in_array($perm['key'], $permissions);
                        $permissionData[] = [
                            'key' => $perm['key'],
                            'name' => $perm['name'],
                            'module' => $module,
                            'is_allowed' => $isAllowed
                        ];
                    }
                }

                $this->roleModel->updateRolePermissions($newRoleId, $permissionData);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Role saved successfully',
                'role_id' => $newRoleId
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to save role'
        ]);
    }

    /**
     * Update single permission via AJAX
     */
    private function updatePermissionAjax()
    {
        $roleId = $this->request->getPost('role_id');
        $permissionKey = $this->request->getPost('permission_key');
        $isAllowed = $this->request->getPost('is_allowed') === 'true';

        if (!$roleId || !$permissionKey) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Role ID and permission key are required'
            ]);
        }

        $db = db_connect();

        // Update the specific permission
        $db->table('role_permissions')
            ->where('role_id', $roleId)
            ->where('permission_key', $permissionKey)
            ->update(['is_allowed' => $isAllowed]);

        // Log the permission change
        $role = $this->roleModel->find($roleId);
        $allPermissions = $this->roleModel->getAllPermissions();
        $permissionName = '';

        foreach ($allPermissions as $modulePermissions) {
            foreach ($modulePermissions as $perm) {
                if ($perm['key'] === $permissionKey) {
                    $permissionName = $perm['name'];
                    break 2;
                }
            }
        }

        log_message('info', "Permission updated: {$permissionName} for role {$role['role_name']} set to " . ($isAllowed ? 'allowed' : 'denied'));

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Permission updated successfully'
        ]);
    }

    /**
     * Delete role via AJAX
     */
    private function deleteRoleAjax()
    {
        $roleId = $this->request->getPost('role_id');

        if (!$roleId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Role ID is required'
            ]);
        }

        $role = $this->roleModel->find($roleId);

        if (!$role) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Role not found'
            ]);
        }

        // Check if it's a core role
        if ($role['is_core']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Core system roles cannot be deleted'
            ]);
        }

        // Check if role has assigned users
        $db = db_connect();
        $userCount = $db->table('users')
            ->where('role_id', $roleId)
            ->countAllResults();

        if ($userCount > 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "Cannot delete role with {$userCount} assigned users"
            ]);
        }

        // Delete role permissions first
        $db->table('role_permissions')->where('role_id', $roleId)->delete();

        // Delete role
        if ($this->roleModel->delete($roleId)) {
            log_message('info', "Role deleted: {$role['role_name']}");

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Role deleted successfully'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to delete role'
        ]);
    }

    /**
     * Duplicate role via AJAX
     */
    private function duplicateRoleAjax()
    {
        $roleId = $this->request->getPost('role_id');
        $newRoleName = $this->request->getPost('new_role_name');
        $newDescription = $this->request->getPost('new_description');

        if (!$roleId || !$newRoleName) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Role ID and new role name are required'
            ]);
        }

        $originalRole = $this->roleModel->find($roleId);

        if (!$originalRole) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Original role not found'
            ]);
        }

        // Check if new role name is unique
        $existingRole = $this->roleModel->where('role_name', $newRoleName)->first();
        if ($existingRole) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Role name already exists'
            ]);
        }

        // Create new role based on original
        $newRoleData = [
            'role_name' => $newRoleName,
            'description' => $newDescription ?: $originalRole['description'],
            'access_level' => $originalRole['access_level'],
            'color_class' => $originalRole['color_class'],
            'is_core' => false
        ];

        if ($this->roleModel->save($newRoleData)) {
            $newRoleId = $this->roleModel->getInsertID();

            // Duplicate permissions
            $permissions = $this->roleModel->getRolePermissions($roleId);
            if (!empty($permissions)) {
                $newPermissions = [];
                foreach ($permissions as $permission) {
                    $newPermissions[] = [
                        'role_id' => $newRoleId,
                        'permission_key' => $permission['permission_key'],
                        'permission_name' => $permission['permission_name'],
                        'module' => $permission['module'],
                        'is_allowed' => $permission['is_allowed']
                    ];
                }

                $db = db_connect();
                $db->table('role_permissions')->insertBatch($newPermissions);
            }

            log_message('info', "Role duplicated: {$originalRole['role_name']} -> {$newRoleName}");

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Role duplicated successfully',
                'new_role_id' => $newRoleId
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to duplicate role'
        ]);
    }

    /**
     * Reset role permissions to default via AJAX
     */
    private function resetRoleAjax()
    {
        $roleId = $this->request->getPost('role_id');

        if (!$roleId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Role ID is required'
            ]);
        }

        $role = $this->roleModel->find($roleId);

        if (!$role) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Role not found'
            ]);
        }

        // Get default permissions based on role template
        $allPermissions = $this->roleModel->getAllPermissions();
        $permissionData = [];

        // Define default permission sets for common roles
        $defaultPermissions = [
            'Admin' => [ // All permissions allowed
                'user' => [
                    'view_own_profile',
                    'update_own_profile',
                    'change_password',
                    'view_all_profiles',
                    'update_all_profiles',
                    'reset_passwords',
                    'manage_users'
                ],
                'communication' => ['send_messages', 'view_replies', 'internal_messages', 'system_messages'],
                'ticket' => [
                    'view_all_tickets',
                    'create_ticket',
                    'reply_ticket',
                    'upload_attachments',
                    'view_status',
                    'assign_ticket',
                    'change_priority',
                    'change_status',
                    'change_notes'
                ],
                'system' => [
                    'access_admin_dashboard',
                    'access_support_dashboard',
                    'access_department_dashboard',
                    'access_reports',
                    'access_sla_data'
                ]
            ],
            'Support' => [
                'user' => ['view_own_profile', 'update_own_profile', 'change_password'],
                'communication' => ['send_messages', 'view_replies', 'internal_messages'],
                'ticket' => [
                    'view_all_tickets',
                    'create_ticket',
                    'reply_ticket',
                    'upload_attachments',
                    'view_status',
                    'assign_ticket',
                    'change_priority'
                ],
                'system' => []
            ],
            'Customer' => [
                'user' => ['view_own_profile', 'update_own_profile', 'change_password'],
                'communication' => ['send_messages', 'view_replies'],
                'ticket' => ['view_own_tickets', 'create_ticket', 'reply_ticket', 'upload_attachments', 'view_status'],
                'system' => []
            ]
        ];

        // Get default permission set for this role
        $roleDefaults = $defaultPermissions[$role['role_name']] ?? $defaultPermissions['Support'];

        // Build permission data
        foreach ($allPermissions as $module => $modulePermissions) {
            foreach ($modulePermissions as $perm) {
                $isAllowed = in_array($perm['key'], $roleDefaults[$module] ?? []);
                $permissionData[] = [
                    'key' => $perm['key'],
                    'name' => $perm['name'],
                    'module' => $module,
                    'is_allowed' => $isAllowed
                ];
            }
        }

        // Update permissions
        if ($this->roleModel->updateRolePermissions($roleId, $permissionData)) {
            log_message('info', "Role permissions reset: {$role['role_name']}");

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Role permissions reset to default'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to reset role permissions'
        ]);
    }

    public function manageDepartments()
    {
        $data = $this->loadCommonData();

        $db = db_connect();
        $data['departments'] = $db->table('departments')->get()->getResultArray();

        return view('Admin/manage_departments', $data);
    }

    public function viewTickets()
    {
        $data = $this->loadCommonData();
        return view('Admin/view_tickets', $data);
    }

    public function systemSettings()
    {
        $data = $this->loadCommonData();
        return view('Admin/system_settings', $data);
    }

 // ==================== PROJECT MANAGEMENT METHODS ====================

/**
 * Manage Projects - Main method
 */
public function manageProjects()
{
    $data = $this->loadCommonData();
    $data['title'] = 'Manage Projects - NEXUS Admin';

    // Load projects data with ticket counts
    $data['projects'] = $this->projectModel->getProjectsWithTicketCounts();
    
    // Load all users for assignment (active users only)
    $data['all_users'] = $this->userModel->getActiveUsersWithRoles();
    
    // Load project assignments
    $data['assignments'] = $this->getProjectAssignments();

    // Check for AJAX requests
    if ($this->request->isAJAX()) {
        return $this->handleProjectsAjax();
    }

    return view('Admin/manage_projects', $data);
}

/**
 * Handle AJAX requests for projects
 */
private function handleProjectsAjax()
{
    try {
        $action = $this->request->getPost('action');

        switch ($action) {
            case 'get_project_details':
                return $this->getProjectDetailsAjax();
            case 'add_project':
                return $this->addProjectAjax();
            case 'edit_project':
                return $this->editProjectAjax();
            case 'delete_project':
                return $this->deleteProjectAjax();
            case 'change_project_status':
                return $this->changeProjectStatusAjax();
            case 'manage_users':
                return $this->manageProjectUsersAjax();
            case 'get_projects_table':
                return $this->getProjectsTableAjax();
            default:
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid action'
                ]);
        }
    } catch (\Exception $e) {
        log_message('error', 'Projects AJAX error: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Server error: ' . $e->getMessage()
        ]);
    }
}

/**
 * Get project details for AJAX
 */
private function getProjectDetailsAjax()
{
    $projectId = $this->request->getPost('project_id');

    if (!$projectId) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Project ID is required'
        ]);
    }

    $project = $this->projectModel->getProjectWithUser($projectId);

    if (!$project) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Project not found'
        ]);
    }

    // Get ticket statistics for this project
    $db = db_connect();
    $ticketStats = $db->table('tickets')
        ->select("
            COUNT(*) as total_tickets,
            COUNT(CASE WHEN status_id IN (1,2) THEN 1 END) as open_tickets,
            COUNT(CASE WHEN status_id IN (4,5) THEN 1 END) as closed_tickets
        ")
        ->where('project_id', $projectId)
        ->get()
        ->getRowArray();

    // Get assigned users for this project
    $assignedUsers = $this->getAssignedUsersForProject($projectId);

    $responseData = [
        'success' => true,
        'project' => [
            'project_id' => $project['project_id'],
            'project_code' => $project['project_code'],
            'project_name' => $project['project_name'],
            'description' => $project['description'] ?? 'No description',
            'is_active' => (bool) $project['is_active'],
            'created_at' => date('M d, Y', strtotime($project['created_at'])),
            'updated_at' => $project['updated_at'] ? date('M d, Y', strtotime($project['updated_at'])) : null,
            'user' => $project['user'] ?? null,
            'total_tickets' => $ticketStats['total_tickets'] ?? 0,
            'open_tickets' => $ticketStats['open_tickets'] ?? 0,
            'closed_tickets' => $ticketStats['closed_tickets'] ?? 0
        ],
        'assigned_users' => $assignedUsers
    ];

    return $this->response->setJSON($responseData);
}

/**
 * Get assigned users for a project
 */
private function getAssignedUsersForProject($projectId): array
{
    $db = db_connect();
    
    return $db->table('project_assignments pa')
        ->select('u.user_id, u.username, u.full_name, u.email, r.role_name')
        ->join('users u', 'u.user_id = pa.user_id')
        ->join('roles r', 'r.role_id = u.role_id', 'left')
        ->where('pa.project_id', $projectId)
        ->where('u.is_active', true)
        ->orderBy('u.full_name', 'ASC')
        ->get()
        ->getResultArray();
}

/**
 * Get all project assignments
 */
private function getProjectAssignments(): array
{
    $db = db_connect();
    
    $assignments = [];
    $results = $db->table('project_assignments pa')
        ->select('pa.project_id, pa.user_id, u.full_name')
        ->join('users u', 'u.user_id = pa.user_id')
        ->where('u.is_active', true)
        ->get()
        ->getResultArray();

    foreach ($results as $assignment) {
        if (!isset($assignments[$assignment['project_id']])) {
            $assignments[$assignment['project_id']] = [];
        }
        $assignments[$assignment['project_id']][] = $assignment['user_id'];
    }

    return $assignments;
}

/**
 * Add project via AJAX
 */
private function addProjectAjax()
{
    $validation = \Config\Services::validation();
    $validation->setRules([
        'project_name' => 'required|min_length[3]|max_length[100]',
        'project_code' => 'required|min_length[3]|max_length[20]|is_unique[projects.project_code]',
        'description' => 'permit_empty|max_length[500]',
        'is_active' => 'permit_empty|in_list[true,false,1,0]'
    ]);

    if (!$validation->withRequest($this->request)->run()) {
        return $this->response->setJSON([
            'success' => false,
            'errors' => $validation->getErrors()
        ]);
    }

    $projectData = [
        'project_name' => $this->request->getPost('project_name'),
        'project_code' => strtoupper($this->request->getPost('project_code')),
        'description' => $this->request->getPost('description') ?: null,
        'is_active' => $this->request->getPost('is_active') ? true : false,
        'user_id' => session()->get('user_id') // Default to current user as creator
    ];

    // Check if project code already exists
    if ($this->projectModel->projectCodeExists($projectData['project_code'])) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Project code already exists'
        ]);
    }

    if ($this->projectModel->save($projectData)) {
        $projectId = $this->projectModel->getInsertID();

        // Log activity
        log_message('info', "Project created: {$projectData['project_name']} (ID: {$projectId}) by user " . session()->get('user_id'));

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Project added successfully',
            'project_id' => $projectId,
            'project_data' => [
                'id' => $projectId,
                'project_code' => $projectData['project_code'],
                'project_name' => $projectData['project_name'],
                'is_active' => $projectData['is_active'],
                'created_at' => date('M d, Y')
            ]
        ]);
    }

    return $this->response->setJSON([
        'success' => false,
        'message' => 'Failed to add project'
    ]);
}

/**
 * Edit project via AJAX
 */
private function editProjectAjax()
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
        'project_code' => "required|min_length[3]|max_length[20]|is_unique[projects.project_code,project_id,{$projectId}]",
        'description' => 'permit_empty|max_length[500]',
        'is_active' => 'permit_empty|in_list[true,false,1,0]',
        'user_id' => 'permit_empty|integer'
    ]);

    if (!$validation->withRequest($this->request)->run()) {
        return $this->response->setJSON([
            'success' => false,
            'errors' => $validation->getErrors()
        ]);
    }

    $projectData = [
        'project_id' => $projectId,
        'project_name' => $this->request->getPost('project_name'),
        'project_code' => strtoupper($this->request->getPost('project_code')),
        'description' => $this->request->getPost('description') ?: null,
        'is_active' => $this->request->getPost('is_active') ? true : false,
        'user_id' => $this->request->getPost('user_id') ?: null
    ];

    // Check if project code already exists (excluding current project)
    if ($this->projectModel->projectCodeExists($projectData['project_code'], $projectId)) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Project code already exists'
        ]);
    }

    if ($this->projectModel->save($projectData)) {
        // Log activity
        log_message('info', "Project updated: ID {$projectId} by user " . session()->get('user_id'));

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Project updated successfully',
            'project_data' => $projectData
        ]);
    }

    return $this->response->setJSON([
        'success' => false,
        'message' => 'Failed to update project'
    ]);
}

/**
 * Delete project via AJAX
 */
private function deleteProjectAjax()
{
    $projectId = $this->request->getPost('project_id');

    if (!$projectId) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Project ID is required'
        ]);
    }

    // Check if project has tickets
    $hasTickets = $this->ticketModel->where('project_id', $projectId)->countAllResults();

    if ($hasTickets > 0) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Cannot delete project with existing tickets. Please reassign tickets first.'
        ]);
    }

    // Check if project has assignments
    $db = db_connect();
    $hasAssignments = $db->table('project_assignments')
        ->where('project_id', $projectId)
        ->countAllResults();

    if ($hasAssignments > 0) {
        // Delete project assignments first
        $db->table('project_assignments')->where('project_id', $projectId)->delete();
    }

    if ($this->projectModel->delete($projectId)) {
        // Log activity
        log_message('info', "Project deleted: ID {$projectId} by user " . session()->get('user_id'));

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Project deleted successfully'
        ]);
    }

    return $this->response->setJSON([
        'success' => false,
        'message' => 'Failed to delete project'
    ]);
}

/**
 * Change project status via AJAX
 */
private function changeProjectStatusAjax()
{
    $projectId = $this->request->getPost('project_id');
    $isActive = $this->request->getPost('is_active');

    if (!$projectId || $isActive === null) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Project ID and status are required'
        ]);
    }

    $isActiveBool = filter_var($isActive, FILTER_VALIDATE_BOOLEAN);
    $statusText = $isActiveBool ? 'activated' : 'deactivated';

    if ($this->projectModel->changeStatus($projectId, $isActiveBool)) {
        // Log activity
        log_message('info', "Project status changed: ID {$projectId} {$statusText} by user " . session()->get('user_id'));

        return $this->response->setJSON([
            'success' => true,
            'message' => "Project {$statusText} successfully",
            'is_active' => $isActiveBool
        ]);
    }

    return $this->response->setJSON([
        'success' => false,
        'message' => 'Failed to change project status'
    ]);
}

/**
 * Manage project users via AJAX
 */
private function manageProjectUsersAjax()
{
    $projectId = $this->request->getPost('project_id');
    $userIds = $this->request->getPost('user_ids') ?: [];

    if (!$projectId) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Project ID is required'
        ]);
    }

    // Validate user IDs are integers
    $validUserIds = array_filter($userIds, function($id) {
        return is_numeric($id) && $id > 0;
    });

    if ($this->updateProjectAssignments($projectId, $validUserIds)) {
        // Log activity
        log_message('info', "Project assignments updated: Project ID {$projectId} with " . count($validUserIds) . " users by user " . session()->get('user_id'));

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Project users updated successfully',
            'user_count' => count($validUserIds)
        ]);
    }

    return $this->response->setJSON([
        'success' => false,
        'message' => 'Failed to update project users'
    ]);
}

/**
 * Update project assignments
 */
private function updateProjectAssignments($projectId, $userIds): bool
{
    $db = db_connect();
    
    // Start transaction
    $db->transStart();
    
    try {
        // Delete existing assignments
        $db->table('project_assignments')->where('project_id', $projectId)->delete();
        
        // Insert new assignments
        if (!empty($userIds)) {
            $assignmentData = [];
            foreach ($userIds as $userId) {
                $assignmentData[] = [
                    'project_id' => $projectId,
                    'user_id' => $userId,
                    'assigned_by' => session()->get('user_id'),
                    'assigned_at' => date('Y-m-d H:i:s')
                ];
            }
            
            if (!empty($assignmentData)) {
                $db->table('project_assignments')->insertBatch($assignmentData);
            }
        }
        
        $db->transComplete();
        
        return $db->transStatus();
        
    } catch (\Exception $e) {
        $db->transRollback();
        log_message('error', 'Update project assignments error: ' . $e->getMessage());
        return false;
    }
}

/**
 * Get projects data for table via AJAX
 */
private function getProjectsTableAjax()
{
    try {
        $draw = $this->request->getPost('draw');
        $start = $this->request->getPost('start') ?? 0;
        $length = $this->request->getPost('length') ?? 10;
        $search = $this->request->getPost('search')['value'] ?? '';

        // Get filters
        $filters = [
            'search' => $search,
            'status' => $this->request->getPost('status'),
            'sort_by' => $this->request->getPost('sort_by') ?? 'created_at',
            'sort_order' => $this->request->getPost('sort_order') ?? 'desc',
            'start' => $start,
            'length' => $length
        ];

        // Get projects data
        $projects = $this->getFilteredProjects($filters);
        $totalRecords = $this->projectModel->countAll();
        $filteredRecords = $this->countFilteredProjects($filters);

        // Format response data
        $data = [];
        foreach ($projects as $project) {
            // Get assigned users count
            $assignedUsersCount = $this->countAssignedUsers($project['project_id']);
            
            $data[] = [
                'id' => $project['project_id'],
                'project_code' => $project['project_code'],
                'name' => $project['project_name'],
                'description' => $project['description'] ?: 'No description',
                'status' => $project['is_active'] ? 'active' : 'inactive',
                'created_at' => date('M d, Y', strtotime($project['created_at'])),
                'total_tickets' => $project['total_tickets'] ?? 0,
                'open_tickets' => $project['open_tickets'] ?? 0,
                'assigned_users' => $assignedUsersCount,
                'is_active' => (bool) $project['is_active']
            ];
        }

        return $this->response->setJSON([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);

    } catch (\Exception $e) {
        log_message('error', 'Projects table AJAX error: ' . $e->getMessage());
        return $this->response->setJSON([
            'draw' => $this->request->getPost('draw'),
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => [],
            'error' => 'Failed to load projects data'
        ]);
    }
}

/**
 * Get filtered projects
 */
private function getFilteredProjects($filters = []): array
{
    $db = db_connect();
    
    $query = $db->table('projects p')
        ->select('p.*, 
            (SELECT COUNT(*) FROM tickets t WHERE t.project_id = p.project_id) as total_tickets,
            (SELECT COUNT(*) FROM tickets t WHERE t.project_id = p.project_id AND t.status_id IN (1,2)) as open_tickets');
    
    // Apply search filter
    if (!empty($filters['search'])) {
        $query->groupStart()
            ->like('p.project_name', $filters['search'])
            ->orLike('p.project_code', $filters['search'])
            ->orLike('p.description', $filters['search'])
            ->groupEnd();
    }
    
    // Apply status filter
    if (!empty($filters['status']) && $filters['status'] !== 'all') {
        if ($filters['status'] === 'active') {
            $query->where('p.is_active', true);
        } elseif ($filters['status'] === 'inactive') {
            $query->where('p.is_active', false);
        }
    }
    
    // Apply sorting
    $sortColumn = $filters['sort_by'] ?? 'created_at';
    $sortOrder = $filters['sort_order'] ?? 'desc';
    
    // Map sort column names
    $sortColumnMap = [
        'id' => 'project_id',
        'name' => 'project_name',
        'code' => 'project_code',
        'tickets' => 'total_tickets',
        'status' => 'is_active'
    ];
    
    $actualSortColumn = $sortColumnMap[$sortColumn] ?? $sortColumn;
    $query->orderBy($actualSortColumn, $sortOrder);
    
    // Apply pagination
    if (isset($filters['length']) && $filters['length'] > 0) {
        $query->limit($filters['length'], $filters['start'] ?? 0);
    }
    
    return $query->get()->getResultArray();
}

/**
 * Count filtered projects
 */
private function countFilteredProjects($filters = []): int
{
    $db = db_connect();
    
    $query = $db->table('projects p');
    
    // Apply search filter
    if (!empty($filters['search'])) {
        $query->groupStart()
            ->like('p.project_name', $filters['search'])
            ->orLike('p.project_code', $filters['search'])
            ->orLike('p.description', $filters['search'])
            ->groupEnd();
    }
    
    // Apply status filter
    if (!empty($filters['status']) && $filters['status'] !== 'all') {
        if ($filters['status'] === 'active') {
            $query->where('p.is_active', true);
        } elseif ($filters['status'] === 'inactive') {
            $query->where('p.is_active', false);
        }
    }
    
    return $query->countAllResults();
}

/**
 * Count assigned users for a project
 */
private function countAssignedUsers($projectId): int
{
    $db = db_connect();
    
    return $db->table('project_assignments pa')
        ->join('users u', 'u.user_id = pa.user_id')
        ->where('pa.project_id', $projectId)
        ->where('u.is_active', true)
        ->countAllResults();
}

/**
 * Get all users for AJAX
 */
public function getAllUsers()
{
    if (!$this->request->isAJAX()) {
        return redirect()->to('/admin/projects');
    }

    try {
        $search = $this->request->getPost('search') ?? '';
        
        $db = db_connect();
        
        $query = $db->table('users u')
            ->select('u.user_id, u.username, u.full_name, u.email, r.role_name')
            ->join('roles r', 'r.role_id = u.role_id')
            ->where('u.is_active', true);
        
        if (!empty($search)) {
            $query->groupStart()
                ->like('u.full_name', $search)
                ->orLike('u.username', $search)
                ->orLike('u.email', $search)
                ->groupEnd();
        }
        
        $query->orderBy('u.full_name', 'ASC');
        
        $users = $query->get()->getResultArray();
        
        return $this->response->setJSON([
            'success' => true,
            'users' => $users
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Get users AJAX error: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to load users'
        ]);
    }
}

// AdminController.php - Tambahkan method-method ini

/**
 * Handle bulk user assignment
 */
public function bulkAssignUsers()
{
    if (!$this->request->isAJAX()) {
        return redirect()->to('/admin/projects');
    }

    try {
        $projectIds = $this->request->getPost('project_ids');
        $userIds = $this->request->getPost('user_ids');

        if (empty($projectIds) || empty($userIds)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please select at least one project and one user'
            ]);
        }

        $db = db_connect();
        $successCount = 0;

        $db->transStart();

        foreach ($projectIds as $projectId) {
            foreach ($userIds as $userId) {
                // Check if assignment already exists
                $exists = $db->table('project_assignments')
                    ->where('project_id', $projectId)
                    ->where('user_id', $userId)
                    ->countAllResults();

                if (!$exists) {
                    $db->table('project_assignments')->insert([
                        'project_id' => $projectId,
                        'user_id' => $userId,
                        'assigned_by' => session()->get('user_id'),
                        'assigned_at' => date('Y-m-d H:i:s')
                    ]);
                    $successCount++;
                }
            }
        }

        $db->transComplete();

        if ($db->transStatus()) {
            log_message('info', "Bulk assignment completed: {$successCount} assignments made by user " . session()->get('user_id'));
            
            return $this->response->setJSON([
                'success' => true,
                'message' => "Successfully assigned {$successCount} users to selected projects"
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to process bulk assignment'
        ]);

    } catch (\Exception $e) {
        log_message('error', 'Bulk assign users error: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Server error: ' . $e->getMessage()
        ]);
    }
}

/**
 * Handle project import
 */
public function importProjects()
{
    if (!$this->request->isAJAX()) {
        return redirect()->to('/admin/projects');
    }

    try {
        $file = $this->request->getFile('projects_file');
        
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please upload a valid file'
            ]);
        }

        // Validate file type
        $allowedExtensions = ['csv', 'xlsx', 'xls'];
        $extension = $file->getClientExtension();
        
        if (!in_array($extension, $allowedExtensions)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Only CSV and Excel files are allowed'
            ]);
        }

        // Process based on file type
        if ($extension === 'csv') {
            $result = $this->processCSVImport($file);
        } else {
            $result = $this->processExcelImport($file);
        }

        return $this->response->setJSON($result);

    } catch (\Exception $e) {
        log_message('error', 'Import projects error: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Import failed: ' . $e->getMessage()
        ]);
    }
}

/**
 * Process CSV file import
 */
private function processCSVImport($file): array
{
    $path = $file->getTempName();
    $handle = fopen($path, 'r');
    
    if (!$handle) {
        return ['success' => false, 'message' => 'Failed to read CSV file'];
    }

    $headers = fgetcsv($handle); // Get column headers
    $importedCount = 0;
    $errorRows = [];

    // Validate required headers
    $requiredHeaders = ['project_name', 'project_code'];
    $missingHeaders = array_diff($requiredHeaders, array_map('strtolower', $headers));
    
    if (!empty($missingHeaders)) {
        fclose($handle);
        return [
            'success' => false,
            'message' => 'Missing required columns: ' . implode(', ', $missingHeaders)
        ];
    }

    $db = db_connect();
    $db->transStart();

    $rowNum = 1;
    while (($row = fgetcsv($handle)) !== false) {
        $rowNum++;
        
        if (count($row) < 2) {
            $errorRows[] = ['row' => $rowNum, 'error' => 'Insufficient columns'];
            continue;
        }

        // Map row to associative array
        $projectData = [];
        foreach ($headers as $index => $header) {
            $projectData[strtolower($header)] = $row[$index] ?? null;
        }

        // Validate data
        if (empty($projectData['project_name']) || empty($projectData['project_code'])) {
            $errorRows[] = ['row' => $rowNum, 'error' => 'Missing required fields'];
            continue;
        }

        // Check if project code already exists
        $exists = $db->table('projects')
            ->where('project_code', strtoupper($projectData['project_code']))
            ->countAllResults();

        if ($exists) {
            $errorRows[] = ['row' => $rowNum, 'error' => 'Project code already exists'];
            continue;
        }

        // Prepare data for insertion
        $insertData = [
            'project_name' => $projectData['project_name'],
            'project_code' => strtoupper($projectData['project_code']),
            'description' => $projectData['description'] ?? null,
            'is_active' => isset($projectData['is_active']) 
                ? filter_var($projectData['is_active'], FILTER_VALIDATE_BOOLEAN)
                : true,
            'user_id' => session()->get('user_id'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($db->table('projects')->insert($insertData)) {
            $importedCount++;
        }
    }

    fclose($handle);
    $db->transComplete();

    if ($db->transStatus()) {
        return [
            'success' => true,
            'message' => "Imported {$importedCount} projects successfully",
            'imported_count' => $importedCount,
            'error_count' => count($errorRows),
            'errors' => $errorRows
        ];
    }

    return [
        'success' => false,
        'message' => 'Database transaction failed'
    ];
}

/**
 * Process Excel file import (requires PhpSpreadsheet library)
 */
private function processExcelImport($file): array
{
    // Check if PhpSpreadsheet is available
    if (!class_exists('\PhpOffice\PhpSpreadsheet\IOFactory')) {
        return [
            'success' => false,
            'message' => 'Excel processing requires PhpSpreadsheet library'
        ];
    }

    try {
        $reader = SpreadsheetIOFactory::createReaderForFile($file->getTempName());
        $spreadsheet = $reader->load($file->getTempName());
        $worksheet = $spreadsheet->getActiveSheet();
        
        $importedCount = 0;
        $errorRows = [];
        $db = db_connect();
        
        $db->transStart();

        foreach ($worksheet->getRowIterator(2) as $row) { // Start from row 2 (skip header)
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            
            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue();
            }

            // Validate row
            if (empty($rowData[0]) || empty($rowData[1])) {
                $errorRows[] = ['row' => $row->getRowIndex(), 'error' => 'Missing required fields'];
                continue;
            }

            $projectData = [
                'project_name' => $rowData[0],
                'project_code' => strtoupper($rowData[1]),
                'description' => $rowData[2] ?? null,
                'is_active' => isset($rowData[3]) 
                    ? filter_var($rowData[3], FILTER_VALIDATE_BOOLEAN)
                    : true,
                'user_id' => session()->get('user_id'),
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Check if project code exists
            $exists = $db->table('projects')
                ->where('project_code', $projectData['project_code'])
                ->countAllResults();

            if ($exists) {
                $errorRows[] = ['row' => $row->getRowIndex(), 'error' => 'Project code already exists'];
                continue;
            }

            if ($db->table('projects')->insert($projectData)) {
                $importedCount++;
            }
        }

        $db->transComplete();

        if ($db->transStatus()) {
            return [
                'success' => true,
                'message' => "Imported {$importedCount} projects successfully",
                'imported_count' => $importedCount,
                'error_count' => count($errorRows),
                'errors' => $errorRows
            ];
        }

        return [
            'success' => false,
            'message' => 'Database transaction failed'
        ];

    } catch (\Exception $e) {
        return [
            'success' => false,
            'message' => 'Excel processing error: ' . $e->getMessage()
        ];
    }
}

/**
 * Get projects for bulk assignment (AJAX)
 */
public function getProjectsForBulkAssign()
{
    if (!$this->request->isAJAX()) {
        return redirect()->to('/admin/projects');
    }

    try {
        $search = $this->request->getPost('search') ?? '';
        
        $db = db_connect();
        
        $query = $db->table('projects p')
            ->select('p.project_id, p.project_code, p.project_name, p.is_active')
            ->where('p.is_active', true);
        
        if (!empty($search)) {
            $query->groupStart()
                ->like('p.project_name', $search)
                ->orLike('p.project_code', $search)
                ->groupEnd();
        }
        
        $query->orderBy('p.project_name', 'ASC');
        
        $projects = $query->get()->getResultArray();
        
        return $this->response->setJSON([
            'success' => true,
            'projects' => $projects
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Get projects for bulk assign error: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to load projects'
        ]);
    }
}

}