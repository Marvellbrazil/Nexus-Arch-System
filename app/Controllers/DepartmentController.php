<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\DepartmentModel;
use App\Models\TicketModel;

class DepartmentController extends BaseController
{
    protected $departmentName = '';
    private $userId;
    private $deptUserModel;
    private $deptDepartmentModel;
    private $deptTicketModel;

    // DepartmentController.php - PERBAIKAN constructor dan validation
    public function __construct()
    {
        helper('url');

        // Check authentication
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Check role
        $roleName = session()->get('role_name');
        if ($roleName !== 'Department') {
            return redirect()->to('/login')->with('error', 'Unauthorized access');
        }

        // Get department name from session
        $this->departmentName = session()->get('department_name');
        $this->userId = session()->get('user_id');

        // Jika tidak ada department_name di session, force logout
        if (!$this->departmentName) {
            session()->destroy();
            return redirect()->to('/login')->with('error', 'Session department missing. Please login again.');
        }

        // Load models
        $this->deptUserModel = new UserModel();
        $this->deptDepartmentModel = new DepartmentModel();
        $this->deptTicketModel = new TicketModel();
    }

    // Tambahkan method untuk get deptType dari URL
    private function getDeptTypeFromUrl()
    {
        $uri = service('uri');
        $segments = $uri->getSegments();

        // Cari index 'department'
        $deptIndex = array_search('department', $segments);

        if ($deptIndex !== false && isset($segments[$deptIndex + 1])) {
            return $segments[$deptIndex + 1]; // 'it-support', 'technical-support', dll
        }

        return null;
    }

    // Perbaiki method dashboard
    public function dashboard($deptType = null)
    {
        // Jika $deptType tidak diberikan, ambil dari URL
        if ($deptType === null) {
            $deptType = $this->getDeptTypeFromUrl();
        }

        // DEBUG: Cek nilai
        // echo "Dept Type: " . $deptType;
        // echo " | Dept Name: " . $this->departmentName;
        // die();

        // Validasi department
        $departmentName = $this->validateDepartment($deptType);
        if (!$departmentName) {
            return redirect()->to('/login')->with('error', 'Unauthorized department access. Expected: ' . $this->departmentName);
        }

        $data = $this->loadCommonData();

        $userId = session()->get('user_id');
        $db = db_connect();

// Get department ID
        $department = $this->deptDepartmentModel->findByName($departmentName);

        if (!$department) {
            return redirect()->to('/login')->with('error', 'Department not found');
        }

        $departmentId = $department['department_id'];

        // Get department statistics
        $stats = $this->deptDepartmentModel->getDepartmentStatistics($departmentId);
        $assignedToMe = $this->deptTicketModel->where('department_id', $departmentId)
            ->where('assigned_to', $userId)
            ->countAllResults();

        $data['stats'] = [
            'total_tickets' => $stats['total_tickets'],
            'open_tickets' => $stats['open_tickets'],
            'assigned_to_me' => $assignedToMe
        ];

        // Get tickets assigned to this department
        $data['department_tickets'] = $this->deptDepartmentModel->getDepartmentTickets($departmentId);

        // Get tickets assigned to me
        $data['my_tickets'] = $this->deptDepartmentModel->getAssignedTicketsForUser($departmentId, $userId);

        $data['view'] = "Department/{$this->formatDepartmentView($departmentName)}/dashboard";

        return view($data['view'], $data);
    }

    public function assignedTickets($deptType = null)
    {
        // Jika $deptType tidak diberikan, ambil dari URL
        if ($deptType === null) {
            // Dapatkan bagian URL setelah 'department/'
            $uri = service('uri');
            $segments = $uri->getSegments();

            // Cari index 'department'
            $deptIndex = array_search('department', $segments);

            if ($deptIndex !== false && isset($segments[$deptIndex + 1])) {
                $deptType = $segments[$deptIndex + 1]; // 'it-support', 'technical-support', dll
            }
        }

        // Department validation
        $departmentName = $this->validateDepartment($deptType);
        if (!$departmentName) {
            return redirect()->to('/login')->with('error', 'Unauthorized department access');
        }

        $data = $this->loadCommonData();

        $userId = session()->get('user_id');
        $db = db_connect();

        // Get department ID
        $department = $db->table('departments')
            ->where('department_name', $departmentName)
            ->get()
            ->getRowArray();

        $departmentId = $department['department_id'];

        // Get tickets assigned to me
        $data['tickets'] = $db->table('tickets t')
            ->select('t.*, p.priority_name, s.status_name, cat.category_name, u.full_name as customer_name, proj.project_name')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->join('categories cat', 'cat.category_id = t.category_id')
            ->join('users u', 'u.user_id = t.customer_id')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->where('t.department_id', $departmentId)
            ->where('t.assigned_to', $userId)
            ->orderBy('t.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $data['view'] = "Department/{$this->formatDepartmentView($departmentName)}/assigned_tickets";

        return view($data['view'], $data);
    }
    public function profile($deptType = null)
    {
        // Jika $deptType tidak diberikan, ambil dari URL
        if ($deptType === null) {
            $uri = service('uri');
            $segments = $uri->getSegments();
            $deptIndex = array_search('department', $segments);

            if ($deptIndex !== false && isset($segments[$deptIndex + 1])) {
                $deptType = $segments[$deptIndex + 1];
            }
        }

        // Department validation
        $departmentName = $this->validateDepartment($deptType);
        if (!$departmentName) {
            return redirect()->to('/login')->with('error', 'Unauthorized department access');
        }

        $data = $this->loadCommonData();

        $userId = session()->get('user_id');
        $db = db_connect();

// Get user details
        $data['user_details'] = $this->deptUserModel->getUserDetails($userId);

        // Format view path
        $viewPath = "Department/{$this->formatDepartmentView($departmentName)}/profile";

        // Jika file view tidak ada, gunakan default
        if (!file_exists(APPPATH . "Views/{$viewPath}.php")) {
            $viewPath = "Department/IT_Support/profile_it_support";
        }

        return view($viewPath, $data);
    }

    public function notifications($deptType = null)
    {
        // Jika $deptType tidak diberikan, ambil dari URL
        if ($deptType === null) {
            $uri = service('uri');
            $segments = $uri->getSegments();
            $deptIndex = array_search('department', $segments);

            if ($deptIndex !== false && isset($segments[$deptIndex + 1])) {
                $deptType = $segments[$deptIndex + 1];
            }
        }

        // Department validation
        $departmentName = $this->validateDepartment($deptType);
        if (!$departmentName) {
            return redirect()->to('/login')->with('error', 'Unauthorized department access');
        }

        $data = $this->loadCommonData();

        $viewPath = "Department/{$this->formatDepartmentView($departmentName)}/notifications";

        // Jika file view tidak ada, gunakan default
        if (!file_exists(APPPATH . "Views/{$viewPath}.php")) {
            $viewPath = "Department/notifications";
        }

        return view($viewPath, $data);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Logged out successfully');
    }

    private function validateDepartment($deptType)
    {
        $departmentMap = [
            'it-support' => 'IT Support',
            'technical-support' => 'Technical Support',
            'uiux-support' => 'UI/UX Support',
            'feature-request' => 'Feature Request'
        ];

        $departmentName = $departmentMap[$deptType] ?? null;

        if ($departmentName && $departmentName === $this->departmentName) {
            return $departmentName;
        }

        return null;
    }

    private function formatDepartmentView($departmentName)
    {
        $mapping = [
            'IT Support' => 'IT_Support',
            'Technical Support' => 'Technical_Support',
            'UI/UX Support' => 'UIUX_Support',
            'Feature Request' => 'Feature_Request'
        ];

        return $mapping[$departmentName] ?? str_replace(' ', '_', $departmentName);
    }
}