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
     * Dashboard dengan data dinamis
     */
    public function dashboard()
    {
        $data = $this->loadCommonData();
        $data['title'] = 'Admin Dashboard - NEXUS';

        try {
            // Get statistics from each model
            $userStats = $this->userModel->getDashboardStatistics();
            $ticketStats = $this->ticketModel->getDashboardStatistics();
            $projectStats = $this->projectModel->getDashboardStatistics();

            // Combine all stats
            $data['stats'] = array_merge(
                $userStats,
                [
                    'total_tickets' => $ticketStats['total_tickets'],
                    'open_tickets' => $ticketStats['open_tickets'],
                    'ticket_trend' => $ticketStats['ticket_trend'],
                    'total_projects' => $projectStats['total_projects']
                ]
            );

            // Get formatted data
            $data['ticket_status'] = $ticketStats['ticket_status_data']['data'];
            $data['total_tickets_for_chart'] = $ticketStats['ticket_status_data']['total'];
            $data['recent_projects'] = $projectStats['recent_projects'];
            $data['recent_users'] = $userStats['recent_users'];

            // Format recent activities
            $recentActivities = $ticketStats['recent_activities_raw'];
            $data['recentActivities'] = $this->ticketModel->formatRecentActivities($recentActivities, $this->userModel);

            // Get dashboard components
            $data['quickLinks'] = $this->projectModel->getQuickLinks();
            $data['systemNotifications'] = $this->roleModel->getSystemNotifications($this->ticketModel);
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
            $data['quickLinks'] = [];
            $data['recentActivities'] = [];
            $data['systemNotifications'] = [];
        }

        return view('Admin/dashboard', $data);
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

        // Get user statistics from model
        $data['userStats'] = $this->userModel->getUserStatistics();

        return view('Admin/manage_users', $data);
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

        // Check for AJAX requests
        if ($this->request->isAJAX()) {
            return $this->handleProjectsAjax();
        }

        return view('Admin/manage_projects', $data);
    }

    /**
     * Get project assignments
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
                case 'get_unassigned_users':
                    return $this->getUnassignedUsersAjax();
                // case 'bulk_assign_projects':
                //     return $this->bulkAssignProjectsAjax();
                case 'get_assignment_statistics':
                    return $this->getAssignmentStatisticsAjax();
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

        // Get project from model
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

        // Get assigned users for this project from ProjectAssignmentModel
        $assignedUsers = $this->projectAssignmentModel->getAssignedUsersForProject($projectId);

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
                'user' => $project['user_full_name'] ?? null,
                'total_tickets' => $ticketStats['total_tickets'] ?? 0,
                'open_tickets' => $ticketStats['open_tickets'] ?? 0,
                'closed_tickets' => $ticketStats['closed_tickets'] ?? 0
            ],
            'assigned_users' => $assignedUsers
        ];

        return $this->response->setJSON($responseData);
    }

    /**
     * Manage project users via AJAX - using ProjectAssignmentModel
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
        $validUserIds = array_filter($userIds, function ($id) {
            return is_numeric($id) && $id > 0;
        });

        // Get current user ID from session
        $assignedBy = session()->get('user_id');

        // Call ProjectAssignmentModel method
        $result = $this->projectAssignmentModel->assignUsersToProject($projectId, $validUserIds, $assignedBy);

        return $this->response->setJSON($result);
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
     * Get unassigned users for a project via AJAX
     */
    private function getUnassignedUsersAjax()
    {
        $projectId = $this->request->getPost('project_id');

        if (!$projectId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Project ID is required'
            ]);
        }

        // Get unassigned users from ProjectAssignmentModel
        $unassignedUsers = $this->projectAssignmentModel->getUnassignedUsers($projectId);

        return $this->response->setJSON([
            'success' => true,
            'users' => $unassignedUsers
        ]);
    }

    // /**
    //  * Bulk assign users to multiple projects via AJAX
    //  */
    // private function bulkAssignProjectsAjax()
    // {
    //     $projectIds = $this->request->getPost('project_ids');
    //     $userIds = $this->request->getPost('user_ids');
    //     $assignedBy = session()->get('user_id');

    //     if (empty($projectIds) || empty($userIds)) {
    //         return $this->response->setJSON([
    //             'success' => false,
    //             'message' => 'Please select at least one project and one user'
    //         ]);
    //     }

    //     // Call ProjectAssignmentModel method
    //     $result = $this->projectAssignmentModel->bulkAssignUsers($projectIds, $userIds, $assignedBy);

    //     return $this->response->setJSON($result);
    // }

    /**
     * Get assignment statistics via AJAX
     */
    private function getAssignmentStatisticsAjax()
    {
        // Get assignment statistics from ProjectAssignmentModel
        $stats = $this->projectAssignmentModel->getAssignmentStatistics();

        return $this->response->setJSON([
            'success' => true,
            'statistics' => $stats
        ]);
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

        // Get recent assignments from model
        $data['recentAssignments'] = $this->projectAssignmentModel->getRecentAssignments(20);

        // Get assignment statistics from model
        $data['assignmentStats'] = $this->projectAssignmentModel->getAssignmentStatistics();

        // Get top assigned users
        $data['topAssignedUsers'] = $this->projectAssignmentModel->getTopAssignedUsers(10);

        // Get projects without assignments
        $data['projectsWithoutAssignments'] = $this->projectAssignmentModel->getProjectsWithoutAssignments();

        return view('Admin/view_assignments', $data);
    }

    /**
     * Remove user from project (AJAX)
     */
    public function removeUserFromProject()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/admin/projects');
        }

        try {
            $projectId = $this->request->getPost('project_id');
            $userId = $this->request->getPost('user_id');

            if (!$projectId || !$userId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Project ID and User ID are required'
                ]);
            }

            // Call ProjectAssignmentModel method
            $result = $this->projectAssignmentModel->removeUserFromProject($projectId, $userId);

            return $this->response->setJSON($result);
        } catch (\Exception $e) {
            log_message('error', 'Remove user from project error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Clear all assignments for a project (AJAX)
     */
    public function clearProjectAssignments()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/admin/projects');
        }

        try {
            $projectId = $this->request->getPost('project_id');

            if (!$projectId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Project ID is required'
                ]);
            }

            // Call ProjectAssignmentModel method
            $result = $this->projectAssignmentModel->clearProjectAssignments($projectId);

            return $this->response->setJSON($result);
        } catch (\Exception $e) {
            log_message('error', 'Clear project assignments error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    // /**
    //  * Get projects for bulk assignment (AJAX)
    //  */
    // public function getProjectsForBulkAssign()
    // {
    //     if (!$this->request->isAJAX()) {
    //         return redirect()->to('/admin/projects');
    //     }

    //     try {
    //         $search = $this->request->getPost('search') ?? '';

    //         $db = db_connect();

    //         $query = $db->table('projects p')
    //             ->select('p.project_id, p.project_code, p.project_name, p.is_active')
    //             ->where('p.is_active', true);

    //         if (!empty($search)) {
    //             $query->groupStart()
    //                 ->like('p.project_name', $search)
    //                 ->orLike('p.project_code', $search)
    //                 ->groupEnd();
    //         }

    //         $query->orderBy('p.project_name', 'ASC');

    //         $projects = $query->get()->getResultArray();

    //         return $this->response->setJSON([
    //             'success' => true,
    //             'projects' => $projects
    //         ]);
    //     } catch (\Exception $e) {
    //         log_message('error', 'Get projects for bulk assign error: ' . $e->getMessage());
    //         return $this->response->setJSON([
    //             'success' => false,
    //             'message' => 'Failed to load projects'
    //         ]);
    //     }
    // }

    /**
     * Add new project via AJAX
     */
    public function addProjectAjax()
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
                'project_code' => 'required|min_length[2]|max_length[20]|is_unique[projects.project_code]',
                'description' => 'permit_empty|max_length[500]'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $validation->getErrors()
                ]);
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

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Project added successfully',
                    'project_id' => $projectId,
                    'project' => [
                        'project_id' => $projectId,
                        'project_code' => $projectData['project_code'],
                        'project_name' => $projectData['project_name'],
                        'description' => $projectData['description'],
                        'is_active' => true
                    ]
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to add project'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Add project error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Edit project via AJAX - FIXED VERSION
     */
    public function editProjectAjax()
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

            // Validasi input
            $validation = \Config\Services::validation();
            $validation->setRules([
                'project_name' => 'required|min_length[3]|max_length[100]',
                'project_code' => "required|min_length[2]|max_length[20]|is_unique[projects.project_code,project_id,{$projectId}]",
                'description' => 'permit_empty|max_length[500]'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $validation->getErrors()
                ]);
            }

            // Siapkan data dengan minimal field yang diperlukan
            $projectData = [
                'project_id' => $projectId,
                'project_name' => trim($this->request->getPost('project_name')),
                'project_code' => strtoupper(trim($this->request->getPost('project_code'))),
                'is_active' => $this->request->getPost('is_active') ? 1 : 0,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Tambahkan description hanya jika tidak null/empty
            $description = trim($this->request->getPost('description', FILTER_SANITIZE_STRING));
            if (!empty($description)) {
                $projectData['description'] = $description;
            }

            // Gunakan query builder langsung untuk menghindari error empty dataset
            $db = db_connect();
            $result = $db->table('projects')
                ->where('project_id', $projectId)
                ->update($projectData);

            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Project updated successfully',
                    'project' => $projectData
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update project'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Edit project error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Delete project via AJAX
     */
    public function deleteProjectAjax()
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
                    'message' => 'Cannot delete project with existing tickets. Please reassign tickets first.'
                ]);
            }

            // Delete project assignments first
            $this->projectAssignmentModel->where('project_id', $projectId)->delete();

            // Delete project
            if ($this->projectModel->delete($projectId)) {
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

    /**
     * Change project status via AJAX - FIXED VERSION
     */
    public function changeProjectStatusAjax()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        try {
            $projectId = $this->request->getPost('project_id');
            $status = $this->request->getPost('status');

            if (!$projectId || $status === null) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Project ID and status are required'
                ]);
            }

            $isActive = ($status === 'active' || $status === '1' || $status === true) ? 1 : 0;

            // Update dengan minimal data yang diperlukan
            $updateData = [
                'is_active' => $isActive,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Gunakan query builder langsung
            $db = db_connect();
            $result = $db->table('projects')
                ->where('project_id', $projectId)
                ->update($updateData);

            if ($result) {
                $statusText = $isActive ? 'activated' : 'deactivated';
                return $this->response->setJSON([
                    'success' => true,
                    'message' => "Project {$statusText} successfully",
                    'is_active' => $isActive
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to change project status'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Change project status error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get projects table data via AJAX (for DataTables)
     */
    public function getProjectsTableAjax()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'error' => 'Invalid request method'
            ]);
        }

        try {
            // Get DataTables parameters
            $draw = $this->request->getPost('draw');
            $start = $this->request->getPost('start');
            $length = $this->request->getPost('length');
            $searchValue = $this->request->getPost('search')['value'] ?? '';

            // Build query
            $db = db_connect();
            $builder = $db->table('projects p')
                ->select('p.*, 
                u.username as created_by_username,
                (SELECT COUNT(*) FROM tickets t WHERE t.project_id = p.project_id) as total_tickets,
                (SELECT COUNT(*) FROM tickets t WHERE t.project_id = p.project_id AND t.status_id IN (1,2)) as open_tickets')
                ->join('users u', 'u.user_id = p.user_id', 'left');

            // Apply search filter
            if (!empty($searchValue)) {
                $builder->groupStart()
                    ->like('p.project_code', $searchValue)
                    ->orLike('p.project_name', $searchValue)
                    ->orLike('p.description', $searchValue)
                    ->orLike('u.username', $searchValue)
                    ->groupEnd();
            }

            // Get total records
            $totalRecords = $builder->countAllResults(false);

            // Apply pagination
            $builder->limit($length, $start);

            // Apply ordering
            $orderColumn = $this->request->getPost('order')[0]['column'] ?? 0;
            $orderDir = $this->request->getPost('order')[0]['dir'] ?? 'asc';

            $columns = ['p.project_code', 'p.project_name', 'total_tickets', 'open_tickets', 'p.is_active', 'p.created_at'];
            $orderColumnName = $columns[$orderColumn] ?? 'p.created_at';
            $builder->orderBy($orderColumnName, $orderDir);

            // Get filtered data
            $projects = $builder->get()->getResultArray();

            // Format data for DataTables
            $formattedData = [];
            foreach ($projects as $project) {
                // Get assigned users count
                $assignedUsersCount = $db->table('project_assignments')
                    ->where('project_id', $project['project_id'])
                    ->countAllResults();

                $formattedData[] = [
                    'project_id' => $project['project_id'],
                    'project_code' => $project['project_code'],
                    'project_name' => $project['project_name'],
                    'description' => $project['description'] ?? '-',
                    'total_tickets' => (int)$project['total_tickets'],
                    'open_tickets' => (int)$project['open_tickets'],
                    'assigned_users' => $assignedUsersCount,
                    'is_active' => (bool)$project['is_active'],
                    'created_at' => date('d M Y', strtotime($project['created_at'])),
                    'created_by' => $project['created_by_username'] ?? '-',
                ];
            }

            return $this->response->setJSON([
                'draw' => $draw,
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $formattedData
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Get projects table error: ' . $e->getMessage());
            return $this->response->setJSON([
                'draw' => $this->request->getPost('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Failed to load projects'
            ]);
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

    // ==================== USER MANAGEMENT AJAX METHODS ====================

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
                        $this->ticketModel->formatTimeAgo($user['last_login']) : 'Never logged in',
                    'total_tickets' => $user['total_tickets'] ?? 0,
                    'total_projects' => $user['total_projects'] ?? 0,
                    'avatar_initials' => $this->userModel->getAvatarInitials($user['full_name']),
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
     * Edit user (AJAX) - FIXED VERSION
     */
    public function editUser($id)
    {
        if (!$this->request->isAJAX()) {
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
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $validation->getErrors()
                ]);
            }

            // Siapkan data dengan field yang ada di database
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

            // Gunakan query builder langsung untuk menghindari error empty dataset
            $db = db_connect();
            $result = $db->table('users')
                ->where('user_id', $id)
                ->update($userData);

            if ($result) {
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

    // ==================== ROLE MANAGEMENT METHODS ====================

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
                case 'get_role_permissions':
                    return $this->getRolePermissionsAjax();
                case 'save_role':
                    return $this->saveRoleAjax();
                case 'update_role_permissions':
                    return $this->updateRolePermissionsAjax();
                case 'delete_role':
                    return $this->deleteRoleAjax();
                case 'duplicate_role':
                    return $this->duplicateRoleAjax();
                case 'reset_role':
                    return $this->resetRoleAjax();
                case 'copy_permissions':
                    return $this->copyPermissionsAjax();
                case 'get_permission_summary':
                    return $this->getPermissionSummaryAjax();
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
     * Get role permissions for AJAX
     */
    private function getRolePermissionsAjax()
    {
        $roleId = $this->request->getPost('role_id');

        if (!$roleId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Role ID is required'
            ]);
        }

        // Get permissions with status from model
        $permissionsWithStatus = $this->roleModel->getPermissionsWithStatus($roleId);

        // Get permission summary from model
        $permissionSummary = $this->roleModel->getPermissionSummary($roleId);

        return $this->response->setJSON([
            'success' => true,
            'permissions' => $permissionsWithStatus,
            'summary' => $permissionSummary
        ]);
    }

    /**
     * Update role permissions via AJAX
     */
    private function updateRolePermissionsAjax()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        try {
            $roleId = $this->request->getPost('role_id');
            $permissions = $this->request->getPost('permissions');

            if (!$roleId || !$permissions) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Role ID and permissions are required'
                ]);
            }

            // Call model method
            $result = $this->roleModel->updateRolePermissions($roleId, $permissions);

            if ($result) {
                // Get updated permission summary
                $summary = $this->roleModel->getPermissionSummary($roleId);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Permissions updated successfully',
                    'summary' => $summary
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update permissions'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Update role permissions error: ' . $e->getMessage());
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

        // Get role details from model
        $role = $this->roleModel->getRoleById($roleId);

        if (!$role) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Role not found'
            ]);
        }

        // Get permission summary
        $permissionSummary = $this->roleModel->getPermissionSummary($roleId);

        return $this->response->setJSON([
            'success' => true,
            'role' => $role,
            'rules' => $this->getRoleRules($role['role_name']),
            'responsibilities' => $this->getRoleResponsibilities($role['role_name']),
            'permission_summary' => $permissionSummary
        ]);
    }

    /**
     * Reset role permissions via AJAX
     */
    private function resetRoleAjax()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        try {
            $roleId = $this->request->getPost('role_id');

            if (!$roleId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Role ID is required'
                ]);
            }

            // Call model method
            $result = $this->roleModel->resetRolePermissions($roleId);

            return $this->response->setJSON($result);
        } catch (\Exception $e) {
            log_message('error', 'Reset role error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Copy permissions from one role to another via AJAX
     */
    private function copyPermissionsAjax()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        try {
            $sourceRoleId = $this->request->getPost('source_role_id');
            $targetRoleId = $this->request->getPost('target_role_id');

            if (!$sourceRoleId || !$targetRoleId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Source and target role IDs are required'
                ]);
            }

            // Call model method
            $result = $this->roleModel->copyPermissions($sourceRoleId, $targetRoleId);

            return $this->response->setJSON($result);
        } catch (\Exception $e) {
            log_message('error', 'Copy permissions error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get permission summary for AJAX
     */
    private function getPermissionSummaryAjax()
    {
        $roleId = $this->request->getPost('role_id');

        if (!$roleId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Role ID is required'
            ]);
        }

        // Get permission summary from model
        $summary = $this->roleModel->getPermissionSummary($roleId);

        return $this->response->setJSON([
            'success' => true,
            'summary' => $summary
        ]);
    }

    /**
     * Save/update role via AJAX
     */
    private function saveRoleAjax()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        try {
            $roleId = $this->request->getPost('role_id');
            $roleName = trim($this->request->getPost('role_name'));
            $description = trim($this->request->getPost('description'));

            if (!$roleName) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Role name is required'
                ]);
            }

            // Prepare role data
            $roleData = [
                'role_name' => $roleName,
                'description' => $description ?: null,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($roleId) {
                // Update existing role
                $roleData['role_id'] = $roleId;

                // Check for duplicate role name
                $existing = $this->roleModel->where('role_name', $roleName)
                    ->where('role_id !=', $roleId)
                    ->first();

                if ($existing) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Role name already exists'
                    ]);
                }

                $result = $this->roleModel->save($roleData);
                $savedRoleId = $roleId;
                $isNew = false;
            } else {
                // Create new role
                $roleData['created_at'] = date('Y-m-d H:i:s');

                // Check for duplicate role name
                $existing = $this->roleModel->where('role_name', $roleName)->first();
                if ($existing) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Role name already exists'
                    ]);
                }

                $result = $this->roleModel->insert($roleData);
                $savedRoleId = $this->roleModel->getInsertID();
                $isNew = true;
            }

            if ($result) {
                // If new role, initialize default permissions
                if ($isNew) {
                    $this->roleModel->initializeDefaultPermissions($savedRoleId, $roleName);
                }

                // Get updated role details
                $role = $this->roleModel->getRoleById($savedRoleId);
                $permissionSummary = $this->roleModel->getPermissionSummary($savedRoleId);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => $isNew ? 'Role created successfully' : 'Role updated successfully',
                    'role_id' => $savedRoleId,
                    'role' => $role,
                    'permission_summary' => $permissionSummary,
                    'is_new' => $isNew
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to save role'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Save role error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

// ==================== HELPER METHODS ====================

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
    /**
     * Delete role via AJAX
     */
    private function deleteRoleAjax()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        try {
            $roleId = $this->request->getPost('role_id');

            if (!$roleId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Role ID is required'
                ]);
            }

            // Delete role using model
            $result = $this->roleModel->deleteRole($roleId);

            return $this->response->setJSON($result);
        } catch (\Exception $e) {
            log_message('error', 'Delete role error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Duplicate role via AJAX
     */
    private function duplicateRoleAjax()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        try {
            $roleId = $this->request->getPost('role_id');
            $newRoleName = $this->request->getPost('new_role_name');
            $newDescription = $this->request->getPost('new_description');

            if (!$roleId || !$newRoleName) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Role ID and new role name are required'
                ]);
            }

            // Duplicate role using model
            $result = $this->roleModel->duplicateRole($roleId, $newRoleName, $newDescription);

            return $this->response->setJSON($result);
        } catch (\Exception $e) {
            log_message('error', 'Duplicate role error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

   // ==================== DEPARTMENT MANAGEMENT ====================

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

        // Check for AJAX requests
        if ($this->request->isAJAX()) {
            return $this->handleDepartmentsAjax();
        }

        return view('Admin/manage_departments', $data);
    }

    /**
     * Handle AJAX requests for departments
     */
    private function handleDepartmentsAjax()
    {
        try {
            $action = $this->request->getPost('action');

            switch ($action) {
                case 'get_departments_data':
                    return $this->getDepartmentsDataAjax();
                case 'get_department_details':
                    return $this->getDepartmentDetailsAjax();
                case 'add_department':
                    return $this->addDepartmentAjax();
                case 'edit_department':
                    return $this->editDepartmentAjax();
                case 'delete_department':
                    return $this->deleteDepartmentAjax();
                case 'get_department_statistics':
                    return $this->getDepartmentStatisticsAjax();
                default:
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Invalid action'
                    ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Departments AJAX error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get departments data for AJAX (with filtering)
     */
    private function getDepartmentsDataAjax()
    {
        try {
            $search = $this->request->getPost('search');
            $status = $this->request->getPost('status');
            $page = $this->request->getPost('page') ?: 1;
            $limit = $this->request->getPost('limit') ?: 10;
            $offset = ($page - 1) * $limit;

            $filters = [
                'search' => $search,
                'status' => $status
            ];

            // Get filtered departments
            $departments = $this->departmentModel->getDepartmentsForDataTables($filters);

            // Apply pagination
            $totalDepartments = count($departments);
            $paginatedDepartments = array_slice($departments, $offset, $limit);

            // Format response
            $formattedDepartments = array_map(function ($dept) {
                // Determine status based on active users
                $status = $dept['active_user_count'] > 0 ? 'active' : 'inactive';

                return [
                    'id' => $dept['department_id'],
                    'name' => $dept['department_name'],
                    'description' => $dept['description'] ?? 'No description',
                    'detailed_description' => $dept['description'] ?? 'No detailed description available',
                    'members' => $dept['user_count'] ?? 0,
                    'active_tickets' => $dept['active_ticket_count'] ?? 0,
                    'resolved_tickets' => $dept['ticket_count'] - ($dept['active_ticket_count'] ?? 0),
                    'ticket_count' => $dept['ticket_count'] ?? 0,
                    'status' => $status,
                    'icon' => $this->getDepartmentIcon($dept['department_name']),
                    'head' => 'Not assigned', // Will be filled in details
                    'created_at' => date('M d, Y', strtotime($dept['created_at']))
                ];
            }, $paginatedDepartments);

            return $this->response->setJSON([
                'success' => true,
                'departments' => $formattedDepartments,
                'pagination' => [
                    'total' => $totalDepartments,
                    'page' => $page,
                    'limit' => $limit,
                    'total_pages' => ceil($totalDepartments / $limit)
                ]
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Get departments data error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load departments'
            ]);
        }
    }

    /**
     * Get department details for AJAX
     */
    private function getDepartmentDetailsAjax()
    {
        try {
            $departmentId = $this->request->getPost('department_id');

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

            return $this->response->setJSON([
                'success' => true,
                'department' => [
                    'id' => $department['department_id'],
                    'name' => $department['department_name'],
                    'description' => $department['description'] ?? 'No description',
                    'member_count' => $department['member_count'],
                    'ticket_count' => $department['ticket_count'],
                    'active_tickets' => $department['active_tickets'] ?? 0,
                    'resolved_tickets' => $department['resolved_tickets'] ?? 0,
                    'status' => $status,
                    'icon' => $icon,
                    'head' => $department['head'],
                    'categories' => $department['categories'] ?? [],
                    'created_at' => date('M d, Y', strtotime($department['created_at'])),
                    'recent_members' => $recentMembers,
                    'member_names' => $department['member_names'] ?? 'No members assigned'
                ]
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Get department details error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load department details'
            ]);
        }
    }

    /**
     * Add department via AJAX
     */
    private function addDepartmentAjax()
    {
        try {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'department_name' => 'required|min_length[2]|max_length[100]|is_unique[departments.department_name]',
                'description' => 'permit_empty|max_length[500]'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $validation->getErrors()
                ]);
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

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Department added successfully',
                    'department_id' => $departmentId
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to add department'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Add department error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Edit department via AJAX
     */
    private function editDepartmentAjax()
    {
        try {
            $departmentId = $this->request->getPost('department_id');

            if (!$departmentId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Department ID is required'
                ]);
            }

            $validation = \Config\Services::validation();
            $validation->setRules([
                'department_name' => "required|min_length[2]|max_length[100]|is_unique[departments.department_name,department_id,{$departmentId}]",
                'description' => 'permit_empty|max_length[500]'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $validation->getErrors()
                ]);
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

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Department updated successfully'
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update department'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Edit department error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Delete department via AJAX
     */
    private function deleteDepartmentAjax()
    {
        try {
            $departmentId = $this->request->getPost('department_id');

            if (!$departmentId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Department ID is required'
                ]);
            }

            // Check if department has users
            $userCount = $this->departmentModel->getDepartmentActiveUsersCount($departmentId);

            if ($userCount > 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => "Cannot delete department with {$userCount} active users. Please reassign users first."
                ]);
            }

            // Check if department has tickets
            $ticketCount = $this->departmentModel->getDepartmentTicketCount($departmentId);

            if ($ticketCount > 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => "Cannot delete department with {$ticketCount} tickets. Please reassign tickets first."
                ]);
            }

            // Delete category mappings first
            $db = db_connect();
            $db->table('category_department_mapping')
                ->where('department_id', $departmentId)
                ->delete();

            if ($this->departmentModel->delete($departmentId)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Department deleted successfully'
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete department'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Delete department error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get department statistics for AJAX
     */
    private function getDepartmentStatisticsAjax()
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

    /**
     * Get department users via AJAX
     */
    private function getDepartmentUsersAjax()
    {
        $departmentId = $this->request->getPost('department_id');

        if (!$departmentId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Department ID is required'
            ]);
        }

        // Get department users from model
        $users = $this->departmentModel->getDepartmentUsers($departmentId);

        return $this->response->setJSON([
            'success' => true,
            'users' => $users
        ]);
    }

    // /**
    //  * Bulk assign users to department via AJAX
    //  */
    // private function bulkAssignUsersAjax()
    // {
    //     $userIds = $this->request->getPost('user_ids');
    //     $departmentId = $this->request->getPost('department_id');

    //     if (empty($userIds) || !$departmentId) {
    //         return $this->response->setJSON([
    //             'success' => false,
    //             'message' => 'User IDs and Department ID are required'
    //         ]);
    //     }

    //     // Call model method
    //     $result = $this->departmentModel->bulkAssignUsers($userIds, $departmentId);

    //     return $this->response->setJSON($result);
    // }

    /**
     * Remove users from department via AJAX
     */
    private function removeUsersFromDepartmentAjax()
    {
        $userIds = $this->request->getPost('user_ids');

        if (empty($userIds)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User IDs are required'
            ]);
        }

        // Call model method
        $result = $this->departmentModel->removeUsersFromDepartment($userIds);

        return $this->response->setJSON($result);
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
     * Get department dropdown for forms (AJAX)
     */
    public function getDepartmentDropdown()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/admin/departments');
        }

        try {
            // Get dropdown options from model
            $options = $this->departmentModel->getDepartmentDropdown();

            return $this->response->setJSON([
                'success' => true,
                'options' => $options
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Department dropdown error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load department options'
            ]);
        }
    }

    // ==================== TICKET MANAGEMENT ====================

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

            // Check for AJAX requests
            if ($this->request->isAJAX()) {
                return $this->handleTicketsAjax();
            }

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

            return view('Admin/view_tickets', $data);
        }
    }

    /**
     * Handle AJAX requests for tickets
     */
    private function handleTicketsAjax()
    {
        try {
            $action = $this->request->getPost('action');

            switch ($action) {
                case 'get_tickets_data':
                    return $this->getTicketsDataAjax();
                case 'get_ticket_details':
                    return $this->getTicketDetailsAjax();
                case 'export_tickets':
                    return $this->exportTicketsAjax();
                case 'get_ticket_statistics':
                    return $this->getTicketStatisticsAjax();
                default:
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Invalid action'
                    ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Tickets AJAX error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get tickets data for AJAX (with pagination)
     */
    private function getTicketsDataAjax()
    {
        try {
            // Get filters
            $filters = [
                'search' => $this->request->getPost('search'),
                'priority' => $this->request->getPost('priority'),
                'department' => $this->request->getPost('department'),
                'status' => $this->request->getPost('status'),
                'date_from' => $this->request->getPost('date_from'),
                'date_to' => $this->request->getPost('date_to')
            ];

            // Get pagination parameters
            $page = $this->request->getPost('page') ?: 1;
            $limit = $this->request->getPost('limit') ?: 10;
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

            return $this->response->setJSON([
                'success' => true,
                'tickets' => $formattedTickets,
                'pagination' => [
                    'total' => $totalTickets,
                    'page' => $page,
                    'limit' => $limit,
                    'total_pages' => ceil($totalTickets / $limit)
                ]
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Get tickets data error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load tickets data'
            ]);
        }
    }

    /**
     * Get ticket details for AJAX
     */
    private function getTicketDetailsAjax()
    {
        try {
            $ticketId = $this->request->getPost('ticket_id');

            if (!$ticketId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Ticket ID is required'
                ]);
            }

            // Get ticket details from model
            $ticket = $this->ticketModel->getTicketDetailsForAdmin($ticketId);

            if (!$ticket) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Ticket not found'
                ]);
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

            return $this->response->setJSON([
                'success' => true,
                'ticket' => [
                    'id' => $ticket['ticket_number'] ?: $ticket['ticket_id'],
                    'title' => $ticket['subject'],
                    'description' => $ticket['description'],
                    'details' => $ticket['description'], // In real app, you might have separate detailed_description field
                    'priority' => $ticket['priority_name'] ?? 'Medium',
                    'priority_value' => strtolower($ticket['priority_name'] ?? 'medium'),
                    'department' => $ticket['department_name'] ?? 'Not assigned',
                    'customer' => $ticket['customer_name'] ?? 'Unknown',
                    'customer_email' => $ticket['customer_email'] ?? '',
                    'customer_phone' => $ticket['customer_phone'] ?? '',
                    'status' => $ticket['status_name'] ?? 'Open',
                    'status_value' => strtolower(str_replace(' ', '-', $ticket['status_name'] ?? 'open')),
                    'assigned_to' => $ticket['assigned_to_name'] ?? 'Not assigned',
                    'project' => $ticket['project_name'] ?? 'Not assigned',
                    'project_code' => $ticket['project_code'] ?? '',
                    'created' => $this->formatDate($ticket['created_at']),
                    'updated' => $this->formatTimeAgo($ticket['updated_at']),
                    'category' => $ticket['category_name'] ?? 'Uncategorized',
                    'due_date' => $ticket['due_date'] ? date('M d, Y', strtotime($ticket['due_date'])) : 'Not set'
                ],
                'activity' => $activityLog
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Get ticket details error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load ticket details'
            ]);
        }
    }

    /**
     * Export tickets to CSV via AJAX
     */
    private function exportTicketsAjax()
    {
        try {
            // Get filters
            $filters = [
                'search' => $this->request->getPost('search'),
                'priority' => $this->request->getPost('priority'),
                'department' => $this->request->getPost('department'),
                'status' => $this->request->getPost('status'),
                'date_from' => $this->request->getPost('date_from'),
                'date_to' => $this->request->getPost('date_to')
            ];

            // Get tickets data from model
            $tickets = $this->ticketModel->exportTicketsForAdmin($filters);

            return $this->response->setJSON([
                'success' => true,
                'data' => $tickets,
                'count' => count($tickets)
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Export tickets error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to export tickets'
            ]);
        }
    }

    /**
     * Get ticket statistics for AJAX
     */
    private function getTicketStatisticsAjax()
    {
        try {
            $stats = $this->ticketModel->getAdminTicketStatistics();

            return $this->response->setJSON([
                'success' => true,
                'statistics' => $stats
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Get ticket statistics error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load statistics'
            ]);
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

// ==================== HELPER METHODS ====================

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

    /**
     * Format time ago
     */
    private function formatTimeAgo($datetime): string
    {
        return $this->formatDate($datetime); // Reuse formatDate for now
    }

    // ==================== SYSTEM SETTINGS ====================

    public function systemSettings()
    {
        $data = $this->loadCommonData();
        $data['title'] = 'System Settings - NEXUS Admin';

        return view('Admin/system_settings', $data);
    }
}
