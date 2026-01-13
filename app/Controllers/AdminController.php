<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ProjectModel;
use App\Models\ProjectAssignmentModel;
use App\Models\TicketModel;
use App\Models\RoleModel;
use App\Models\DepartmentModel;

class AdminController extends BaseController
{
    protected $userModel;
    protected $projectModel;
    protected $projectAssignmentModel;
    protected $ticketModel;
    protected $roleModel;
    protected $departmentModel;

    public function __construct()
    {
        // Initialize models
        $this->userModel = new UserModel();
        $this->projectModel = new ProjectModel();
        $this->projectAssignmentModel = new ProjectAssignmentModel();
        $this->ticketModel = new TicketModel();
        $this->roleModel = new RoleModel();
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

    public function manageUsers()
    {
        $data = $this->loadCommonData();

        // Get all users with roles and departments
        $data['users'] = $this->userModel->getUsersWithRole();

        // Get roles and departments for filters
        $db = db_connect();
        $data['roles'] = $db->table('roles')->get()->getResultArray();
        $data['departments'] = $db->table('departments')->get()->getResultArray();

        return view('Admin/manage_users', $data);
    }

    public function addUser()
    {
        if ($this->request->getMethod() === 'post') {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
                'full_name' => 'required|min_length[3]|max_length[100]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[6]',
                'role_id' => 'required|integer',
                'department_id' => 'permit_empty|integer'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }

            $userData = [
                'username' => $this->request->getPost('username'),
                'full_name' => $this->request->getPost('full_name'),
                'email' => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'role_id' => $this->request->getPost('role_id'),
                'department_id' => $this->request->getPost('department_id') ?: null,
                'phone_number' => $this->request->getPost('phone_number'),
                'is_active' => $this->request->getPost('is_active') ? 1 : 0
            ];

            if ($this->userModel->save($userData)) {
                return redirect()->to('/admin/users')->with('success', 'User added successfully!');
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to add user');
            }
        }

        return redirect()->to('/admin/users');
    }

    public function editUser($id)
    {
        if ($this->request->getMethod() === 'post') {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'username' => "required|min_length[3]|max_length[50]|is_unique[users.username,user_id,$id]",
                'full_name' => 'required|min_length[3]|max_length[100]',
                'email' => "required|valid_email|is_unique[users.email,user_id,$id]",
                'role_id' => 'required|integer',
                'department_id' => 'permit_empty|integer'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }

            $userData = [
                'user_id' => $id,
                'username' => $this->request->getPost('username'),
                'full_name' => $this->request->getPost('full_name'),
                'email' => $this->request->getPost('email'),
                'role_id' => $this->request->getPost('role_id'),
                'department_id' => $this->request->getPost('department_id') ?: null,
                'phone_number' => $this->request->getPost('phone_number'),
                'is_active' => $this->request->getPost('is_active') ? 1 : 0
            ];

            // Only update password if provided
            if ($this->request->getPost('password')) {
                $userData['password'] = $this->request->getPost('password');
            }

            if ($this->userModel->save($userData)) {
                return redirect()->to('/admin/users')->with('success', 'User updated successfully!');
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to update user');
            }
        }

        return redirect()->to('/admin/users');
    }

    public function resetPassword($id)
    {
        if ($this->request->getMethod() === 'post') {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'new_password' => 'required|min_length[6]',
                'confirm_password' => 'required|matches[new_password]'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }

            $newPassword = $this->request->getPost('new_password');

            if ($this->userModel->update($id, ['password' => $newPassword])) {
                return redirect()->to('/admin/users')->with('success', 'Password reset successfully!');
            } else {
                return redirect()->back()->with('error', 'Failed to reset password');
            }
        }

        return redirect()->to('/admin/users');
    }

    public function changeStatus($id)
    {
        $status = $this->request->getPost('status') === 'active' ? 1 : 0;

        if ($this->userModel->update($id, ['is_active' => $status])) {
            return redirect()->to('/admin/users')->with('success', "User status updated successfully!");
        } else {
            return redirect()->back()->with('error', 'Failed to update status');
        }
    }

    public function deleteUser($id)
    {
        // Prevent deleting yourself
        if ($id == session()->get('user_id')) {
            return redirect()->to('/admin/users')->with('error', 'Cannot delete your own account');
        }

        if ($this->userModel->delete($id)) {
            return redirect()->to('/admin/users')->with('success', 'User deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to delete user');
        }
    }

    public function manageRoles()
    {
        $data = $this->loadCommonData();

        $db = db_connect();
        $data['roles'] = $db->table('roles')->get()->getResultArray();

        return view('Admin/manage_roles', $data);
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

    public function manageProjects()
    {
        $data = $this->loadCommonData();

        // Get all projects with ticket counts
        $db = db_connect();

        $data['projects'] = $db->table('projects p')
            ->select('p.*, 
                (SELECT COUNT(*) FROM tickets t WHERE t.project_id = p.project_id) as total_tickets,
                (SELECT COUNT(*) FROM tickets t WHERE t.project_id = p.project_id AND t.status_id IN (1,2)) as open_tickets,
                (SELECT COUNT(*) FROM project_assignments pa WHERE pa.project_id = p.project_id) as assigned_users')
            ->orderBy('p.created_at', 'DESC')
            ->get()
            ->getResultArray();

        // Get all users for assignment
        $data['all_users'] = $db->table('users u')
            ->select('u.user_id, u.username, u.full_name, u.email, r.role_name')
            ->join('roles r', 'r.role_id = u.role_id')
            ->where('u.is_active', true)
            ->orderBy('u.full_name', 'ASC')
            ->get()
            ->getResultArray();

        // Get project assignments
        $data['assignments'] = [];
        $assignments = $db->table('project_assignments pa')
            ->select('pa.project_id, pa.user_id, u.full_name')
            ->join('users u', 'u.user_id = pa.user_id')
            ->get()
            ->getResultArray();

        foreach ($assignments as $assignment) {
            if (!isset($data['assignments'][$assignment['project_id']])) {
                $data['assignments'][$assignment['project_id']] = [];
            }
            $data['assignments'][$assignment['project_id']][] = $assignment['user_id'];
        }

        return view('Admin/manage_projects', $data);
    }

    public function addProject()
    {
        if ($this->request->getMethod() === 'post') {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'project_code' => 'required|min_length[3]|max_length[20]|is_unique[projects.project_code]',
                'project_name' => 'required|min_length[3]|max_length[100]',
                'description' => 'permit_empty'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }

            $projectData = [
                'project_code' => strtoupper($this->request->getPost('project_code')),
                'project_name' => $this->request->getPost('project_name'),
                'description' => $this->request->getPost('description'),
                'is_active' => $this->request->getPost('is_active') ? 1 : 0,
                'created_at' => date('Y-m-d H:i:s')
            ];

            if ($this->projectModel->save($projectData)) {
                return redirect()->to('/admin/projects')->with('success', 'Project added successfully!');
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to add project');
            }
        }

        return redirect()->to('/admin/projects');
    }

    public function editProject($id)
    {
        if ($this->request->getMethod() === 'post') {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'project_code' => "required|min_length[3]|max_length[20]|is_unique[projects.project_code,project_id,$id]",
                'project_name' => 'required|min_length[3]|max_length[100]',
                'description' => 'permit_empty'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }

            $projectData = [
                'project_id' => $id,
                'project_code' => strtoupper($this->request->getPost('project_code')),
                'project_name' => $this->request->getPost('project_name'),
                'description' => $this->request->getPost('description'),
                'is_active' => $this->request->getPost('is_active') ? 1 : 0,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($this->projectModel->save($projectData)) {
                return redirect()->to('/admin/projects')->with('success', 'Project updated successfully!');
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to update project');
            }
        }

        return redirect()->to('/admin/projects');
    }

    public function deleteProject($id)
    {
        // Check if project has tickets
        $hasTickets = $this->ticketModel->where('project_id', $id)->countAllResults();

        if ($hasTickets > 0) {
            return redirect()->to('/admin/projects')->with('error', 'Cannot delete project with existing tickets. Please reassign tickets first.');
        }

        // Delete project assignments first
        $this->projectAssignmentModel->where('project_id', $id)->delete();

        if ($this->projectModel->delete($id)) {
            return redirect()->to('/admin/projects')->with('success', 'Project deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to delete project');
        }
    }

    public function assignUsersToProject($projectId)
    {
        if ($this->request->getMethod() === 'post') {
            $userIds = $this->request->getPost('user_ids') ?: [];

            // Delete existing assignments
            $this->projectAssignmentModel->where('project_id', $projectId)->delete();

            // Add new assignments
            if (!empty($userIds)) {
                $assignmentData = [];
                foreach ($userIds as $userId) {
                    $assignmentData[] = [
                        'project_id' => $projectId,
                        'user_id' => $userId,
                        'assigned_at' => date('Y-m-d H:i:s')
                    ];
                }

                if (!empty($assignmentData)) {
                    $this->projectAssignmentModel->insertBatch($assignmentData);
                }
            }

            return redirect()->to('/admin/projects')->with('success', 'Users assigned to project successfully!');
        }

        return redirect()->to('/admin/projects');
    }

    public function changeProjectStatus($id)
    {
        $status = $this->request->getPost('status') === 'active' ? 1 : 0;

        if ($this->projectModel->update($id, ['is_active' => $status])) {
            return redirect()->to('/admin/projects')->with('success', "Project status updated successfully!");
        } else {
            return redirect()->back()->with('error', 'Failed to update project status');
        }
    }

    /**
     * Refresh dashboard data via AJAX
     */
    public function refreshDashboardData()
    {
        // Only allow AJAX requests
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $data = [
            'stats' => $this->getDashboardStats(),
            'ticketStatus' => $this->getTicketStatusData(),
            'timestamp' => date('Y-m-d H:i:s')
        ];

        return $this->response->setJSON([
            'success' => true,
            'data' => $data
        ]);
    }
}