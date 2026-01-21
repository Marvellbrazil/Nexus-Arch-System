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

        // DEBUG: Tampilkan department dari session untuk troubleshooting
        // Jika ada masalah UI/UX Support masuk ke IT Support, uncomment baris berikut:
        // echo "DEBUG - Session Department Name: " . $this->departmentName;
        // echo " | User ID: " . $this->userId;
        // die();

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

    // Method untuk get deptType dari URL
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

    public function dashboard($deptType = null)
    {
        // Jika $deptType tidak diberikan, ambil dari URL
        if ($deptType === null) {
            $deptType = $this->getDeptTypeFromUrl();
        }

        // DEBUG: Untuk troubleshooting masalah UI/UX Support masuk ke IT Support
        // echo "DEBUG dashboard()<br>";
        // echo "Dept Type from URL: " . htmlspecialchars($deptType) . "<br>";
        // echo "Department Name from Session: " . htmlspecialchars($this->departmentName) . "<br>";
        
        // Get mapping untuk debugging
        $departmentMap = [
            'it-support' => 'IT Support',
            'technical-support' => 'Technical Support',
            'uiux-support' => 'UI/UX Support',
            'feature-request' => 'Feature Request'
        ];
        
        $expectedDeptName = $departmentMap[$deptType] ?? 'Unknown';
        // echo "Expected Dept from URL: " . htmlspecialchars($expectedDeptName) . "<br>";
        // die();

        // Validasi department
        $departmentName = $this->validateDepartment($deptType);
        if (!$departmentName) {
            $errorMsg = "Unauthorized department access. ";
            $errorMsg .= "You are logged in as <strong>" . htmlspecialchars($this->departmentName) . "</strong>, ";
            $errorMsg .= "but trying to access <strong>" . htmlspecialchars($expectedDeptName) . "</strong> dashboard.";
            
            return redirect()->to('/login')->with('error', $errorMsg);
        }

        $data = $this->loadCommonData();

        $userId = session()->get('user_id');
        $db = db_connect();

        // Get department ID
        $department = $this->deptDepartmentModel->findByName($departmentName);

        if (!$department) {
            return redirect()->to('/login')->with('error', 'Department not found in database: ' . $departmentName);
        }

        $departmentId = $department['department_id'];

        // Get department statistics
        $stats = $this->deptDepartmentModel->getDepartmentTicketsStatistics($departmentId);
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

        // Debug view path
        // echo "View Path: " . $data['view'];
        // die();

        return view($data['view'], $data);
    }

    public function assignedTickets($deptType = null)
    {
        // Jika $deptType tidak diberikan, ambil dari URL
        if ($deptType === null) {
            $deptType = $this->getDeptTypeFromUrl();
        }

        // Department validation
        $departmentName = $this->validateDepartment($deptType);
        if (!$departmentName) {
            $departmentMap = [
                'it-support' => 'IT Support',
                'technical-support' => 'Technical Support',
                'uiux-support' => 'UI/UX Support',
                'feature-request' => 'Feature Request'
            ];
            
            $expectedDeptName = $departmentMap[$deptType] ?? 'Unknown';
            $errorMsg = "Unauthorized access. You are <strong>" . htmlspecialchars($this->departmentName) . "</strong>, ";
            $errorMsg .= "cannot access <strong>" . htmlspecialchars($expectedDeptName) . "</strong> assigned tickets.";
            
            return redirect()->to('/login')->with('error', $errorMsg);
        }

        $data = $this->loadCommonData();

        $userId = session()->get('user_id');
        $db = db_connect();

        // Get department ID
        $department = $this->deptDepartmentModel->findByName($departmentName);
        
        if (!$department) {
            return redirect()->to('/login')->with('error', 'Department not found: ' . $departmentName);
        }
        
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
            $deptType = $this->getDeptTypeFromUrl();
        }

        // Department validation
        $departmentName = $this->validateDepartment($deptType);
        if (!$departmentName) {
            $departmentMap = [
                'it-support' => 'IT Support',
                'technical-support' => 'Technical Support',
                'uiux-support' => 'UI/UX Support',
                'feature-request' => 'Feature Request'
            ];
            
            $expectedDeptName = $departmentMap[$deptType] ?? 'Unknown';
            $errorMsg = "Unauthorized access. You are <strong>" . htmlspecialchars($this->departmentName) . "</strong>, ";
            $errorMsg .= "cannot access <strong>" . htmlspecialchars($expectedDeptName) . "</strong> profile.";
            
            return redirect()->to('/login')->with('error', $errorMsg);
        }

        $data = $this->loadCommonData();

        $userId = session()->get('user_id');

        // Get user details
        $data['user_details'] = $this->deptUserModel->getUserDetails($userId);

        // Format view path
        $viewPath = "Department/{$this->formatDepartmentView($departmentName)}/profile";

        // Jika file view tidak ada, gunakan default
        if (!file_exists(APPPATH . "Views/{$viewPath}.php")) {
            // Coba alternatif
            $altViewPath = "Department/{$this->formatDepartmentView($departmentName)}/profile";
            if (!file_exists(APPPATH . "Views/{$altViewPath}.php")) {
                $viewPath = "Department/IT_Support/profile_it_support";
            }
        }

        return view($viewPath, $data);
    }

    public function notifications($deptType = null)
    {
        // Jika $deptType tidak diberikan, ambil dari URL
        if ($deptType === null) {
            $deptType = $this->getDeptTypeFromUrl();
        }

        // Department validation
        $departmentName = $this->validateDepartment($deptType);
        if (!$departmentName) {
            $departmentMap = [
                'it-support' => 'IT Support',
                'technical-support' => 'Technical Support',
                'uiux-support' => 'UI/UX Support',
                'feature-request' => 'Feature Request'
            ];
            
            $expectedDeptName = $departmentMap[$deptType] ?? 'Unknown';
            $errorMsg = "Unauthorized access. You are <strong>" . htmlspecialchars($this->departmentName) . "</strong>, ";
            $errorMsg .= "cannot access <strong>" . htmlspecialchars($expectedDeptName) . "</strong> notifications.";
            
            return redirect()->to('/login')->with('error', $errorMsg);
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

    // Tambahkan method untuk debugging session
    public function debugSession()
    {
        if (!session()->get('isLoggedIn')) {
            return "Not logged in";
        }
        
        $debugInfo = [
            'user_id' => session()->get('user_id'),
            'role_name' => session()->get('role_name'),
            'department_name' => session()->get('department_name'),
            'isLoggedIn' => session()->get('isLoggedIn'),
            'all_session_data' => session()->get()
        ];
        
        return "<pre>" . print_r($debugInfo, true) . "</pre>";
    }

    private function validateDepartment($deptType)
    {
        $departmentMap = [
            'it-support' => 'IT Support',
            'technical-support' => 'Technical Support',
            'uiux-support' => 'UI/UX Support',
            'feature-request' => 'Feature Request'
        ];

        $departmentNameFromUrl = $departmentMap[$deptType] ?? null;

        if (!$departmentNameFromUrl) {
            // Debug: Tampilkan mapping yang tidak ditemukan
            // echo "DEBUG validateDepartment: DeptType '{$deptType}' not found in map<br>";
            return null;
        }

        // Debug: Bandingkan department dari URL dengan session
        // echo "DEBUG validateDepartment:<br>";
        // echo "Department from URL: '{$departmentNameFromUrl}'<br>";
        // echo "Department from Session: '{$this->departmentName}'<br>";
        // echo "Match? " . ($departmentNameFromUrl === $this->departmentName ? 'YES' : 'NO') . "<br>";

        if ($departmentNameFromUrl === $this->departmentName) {
            return $departmentNameFromUrl;
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

    // Method untuk load data umum yang digunakan di semua halaman
    protected function loadCommonData()
    {
        $data = [
            'title' => 'Department Dashboard',
            'user_id' => $this->userId,
            'role_name' => session()->get('role_name'),
            'department_name' => $this->departmentName,
            'user_details' => $this->deptUserModel->getUserDetails($this->userId),
            'active_menu' => 'dashboard'
        ];

        return $data;
    }
}