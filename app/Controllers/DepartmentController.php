<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\DepartmentModel;
use App\Models\TicketModel;
use App\Models\NotificationModel;
use App\Models\ProjectModel;
use App\Models\PriorityModel;
use App\Models\CategoryModel;
use App\Models\StatusModel;

class DepartmentController extends BaseController
{
    protected $departmentName = '';
    private $userId;
    private $deptUserModel;
    private $deptDepartmentModel;
    private $deptTicketModel;
    private $notificationModel;
    private $projectModel;
    private $priorityModel;
    private $categoryModel;
    private $statusModel;
    protected $db;

    public function __construct()
    {
        helper('url');
        $this->db = db_connect();

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
        $this->notificationModel = new NotificationModel();
        $this->projectModel = new ProjectModel();
        $this->priorityModel = new PriorityModel();
        $this->categoryModel = new CategoryModel();
        $this->statusModel = new StatusModel();
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

    /**
 * Check if ticket can be marked as resolved (untuk real-time polling)
 */
/**
 * Check if ticket can be marked as resolved (untuk real-time polling)
 */
public function checkTicketStatus($ticketId)
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }
    
    $userId = session()->get('user_id');
    $departmentName = session()->get('department_name');
    
    // Get department info
    $department = $this->deptDepartmentModel->findByName($departmentName);
    if (!$department) {
        return $this->response->setJSON(['success' => false, 'message' => 'Department not found']);
    }
    
    $db = $this->db;
    
    try {
        // Get ticket dengan status terbaru
        $ticket = $db->table('tickets t')
            ->select('t.*, s.status_name, d.department_name')
            ->join('statuses s', 's.status_id = t.status_id', 'left')
            ->join('departments d', 'd.department_id = t.department_id', 'left')
            ->where('t.ticket_id', $ticketId)
            ->where('t.department_id', $department['department_id'])
            ->get()
            ->getRowArray();
        
        if (!$ticket) {
            return $this->response->setJSON(['success' => false, 'message' => 'Ticket not found']);
        }
        
        $canMarkResolved = false;
        $reason = '';
        
        // 🔥 LOGIKA BISA/TIDAK BISA MARK RESOLVED:
        // 1. Jika status = 'rejected' DAN belum di-resolve → BISA
        if ($ticket['internal_status'] === 'rejected' && $ticket['department_resolved_at'] === null) {
            $canMarkResolved = true;
            $reason = 'Ticket was rejected and needs correction';
        } 
        // 2. Jika status = 'reopened' (dicentang for corrections) DAN belum di-resolve → BISA
        elseif ($ticket['internal_status'] === 'reopened' && $ticket['department_resolved_at'] === null) {
            $canMarkResolved = true;
            $reason = 'Ticket reopened for corrections';
        }
        // 3. Jika status = 'reopened_no' (TIDAK dicentang) → TIDAK BISA
        elseif ($ticket['internal_status'] === 'reopened_no') {
            $canMarkResolved = false;
            $reason = 'Ticket reopened for review only (no corrections needed)';
        }
        // 4. Jika status = 'approved' → TIDAK BISA
        elseif ($ticket['internal_status'] === 'approved') {
            $canMarkResolved = false;
            $reason = 'Ticket already approved by Support';
        }
        // 5. Jika masih pending (belum pernah di-resolve) → BISA
        elseif ($ticket['department_resolved_at'] === null && $ticket['internal_status'] === 'pending') {
            $canMarkResolved = true;
            $reason = 'Ticket has not been resolved yet';
        }
        // 6. Status lainnya (review_needed, testing) → TIDAK BISA
        else {
            $canMarkResolved = false;
            $reason = 'Ticket under Support review';
        }
        
        // Cek apakah ada notes dari Support
        $correctionNotes = '';
        if (!empty($ticket['internal_status_notes'])) {
            $correctionNotes = $ticket['internal_status_notes'];
        }
        
        // Status labels untuk display
        $statusLabels = [
            'pending' => ['label' => 'Pending', 'color' => 'bg-gray-100 text-gray-800', 'icon' => 'fa-clock'],
            'review_needed' => ['label' => 'Review Needed', 'color' => 'bg-yellow-100 text-yellow-800', 'icon' => 'fa-search'],
            'testing' => ['label' => 'Testing', 'color' => 'bg-blue-100 text-blue-800', 'icon' => 'fa-flask'],
            'approved' => ['label' => 'Approved', 'color' => 'bg-green-100 text-green-800', 'icon' => 'fa-check'],
            'rejected' => ['label' => 'Rejected', 'color' => 'bg-red-100 text-red-800', 'icon' => 'fa-times'],
            'reopened' => ['label' => 'Reopened for Corrections', 'color' => 'bg-purple-100 text-purple-800', 'icon' => 'fa-redo'],
            'reopened_no' => ['label' => 'Reopened for Review', 'color' => 'bg-indigo-100 text-indigo-800', 'icon' => 'fa-comments']
        ];
        
        $currentStatus = $ticket['internal_status'] ?? 'pending';
        $statusInfo = $statusLabels[$currentStatus] ?? $statusLabels['pending'];
        
        return $this->response->setJSON([
            'success' => true,
            'can_mark_resolved' => $canMarkResolved,
            'reason' => $reason,
            'ticket_status' => $ticket['internal_status'],
            'department_resolved_at' => $ticket['department_resolved_at'],
            'correction_notes' => $correctionNotes,
            'status_info' => $statusInfo,
            'current_status' => $currentStatus,
            'current_status_label' => $statusInfo['label'],
            'ticket_data' => [
                'ticket_id' => $ticketId,
                'ticket_number' => $ticket['ticket_number'],
                'subject' => $ticket['subject'],
                'internal_status' => $ticket['internal_status'],
                'status_name' => $ticket['status_name'],
                'internal_status_notes' => $ticket['internal_status_notes']
            ]
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error checking ticket status: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}
/**
 * Get ticket status info untuk real-time polling
 */
public function getTicketStatusInfo($ticketId)
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }
    
    $userId = session()->get('user_id');
    $departmentName = session()->get('department_name');
    
    // Get department info
    $department = $this->deptDepartmentModel->findByName($departmentName);
    if (!$department) {
        return $this->response->setJSON(['success' => false, 'message' => 'Department not found']);
    }
    
    $db = $this->db;
    
    try {
        $ticket = $db->table('tickets t')
            ->select('t.*, 
                s.status_name, 
                p.priority_name,
                d.department_name,
                u1.full_name as department_resolved_by_name,
                u2.full_name as last_reopened_by_name,
                u3.full_name as last_rejected_by_name')
            ->join('statuses s', 's.status_id = t.status_id', 'left')
            ->join('priorities p', 'p.priority_id = t.priority_id', 'left')
            ->join('departments d', 'd.department_id = t.department_id', 'left')
            ->join('users u1', 'u1.user_id = t.department_resolved_by', 'left')
            ->join('users u2', 'u2.user_id = t.last_reopened_by', 'left')
            ->join('users u3', 'u3.user_id = t.last_rejected_by', 'left')
            ->where('t.ticket_id', $ticketId)
            ->where('t.department_id', $department['department_id'])
            ->get()
            ->getRowArray();
        
        if (!$ticket) {
            return $this->response->setJSON(['success' => false, 'message' => 'Ticket not found']);
        }
        
        // Determine if can mark as resolved - SESUAI DATABASE
        $canMarkResolved = false;
        $reason = '';
        
        if (($ticket['internal_status'] === 'reopened' || $ticket['internal_status'] === 'rejected') 
            && $ticket['department_resolved_at'] === null) {
            $canMarkResolved = true;
            $reason = 'Ticket has been ' . $ticket['internal_status'] . ' by Support';
        } 
        elseif ($ticket['department_resolved_at'] === null && $ticket['internal_status'] === 'pending') {
            $canMarkResolved = true;
            $reason = 'Ticket not yet resolved';
        }
        // Jika sudah di-resolve tapi di-reopen (department_resolved_at direset ke null)
        elseif ($ticket['department_resolved_at'] === null && $ticket['internal_status'] === 'reopened') {
            $canMarkResolved = true;
            $reason = 'Ticket reopened by Support';
        }
        
        // Get notes dari internal_status_notes (bukan correction_notes)
        $correctionNotes = '';
        if (!empty($ticket['internal_status_notes'])) {
            $correctionNotes = $ticket['internal_status_notes'];
        }
        
        // Status labels
        $statusLabels = [
            'pending' => ['label' => 'Pending', 'color' => 'bg-gray-100 text-gray-800', 'icon' => 'fa-clock'],
            'review_needed' => ['label' => 'Review Needed', 'color' => 'bg-yellow-100 text-yellow-800', 'icon' => 'fa-search'],
            'testing' => ['label' => 'Testing', 'color' => 'bg-blue-100 text-blue-800', 'icon' => 'fa-flask'],
            'approved' => ['label' => 'Approved', 'color' => 'bg-green-100 text-green-800', 'icon' => 'fa-check'],
            'rejected' => ['label' => 'Rejected', 'color' => 'bg-red-100 text-red-800', 'icon' => 'fa-times'],
            'reopened' => ['label' => 'Reopened', 'color' => 'bg-purple-100 text-purple-800', 'icon' => 'fa-redo']
        ];
        
        $currentStatus = $ticket['internal_status'] ?? 'pending';
        $statusInfo = $statusLabels[$currentStatus] ?? $statusLabels['pending'];
        
        return $this->response->setJSON([
            'success' => true,
            'ticket' => $ticket,
            'status_info' => $statusInfo,
            'can_mark_resolved' => $canMarkResolved,
            'reason' => $reason,
            'correction_notes' => $correctionNotes,
            'current_status' => $currentStatus,
            'current_status_label' => $statusInfo['label']
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error getting ticket status info: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}
/**
 * Get latest ticket status untuk real-time update
 */
public function getTicketStatus($ticketId)
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }
    
    $userId = session()->get('user_id');
    $departmentName = session()->get('department_name');
    
    // Get department info
    $department = $this->deptDepartmentModel->findByName($departmentName);
    if (!$department) {
        return $this->response->setJSON(['success' => false, 'message' => 'Department not found']);
    }
    
    $db = $this->db;
    
    try {
        $ticket = $db->table('tickets t')
            ->select('t.*, s.status_name, d.department_name, 
                u1.full_name as department_resolved_by_name,
                u2.full_name as last_reopened_by_name,
                u3.full_name as last_rejected_by_name')
            ->join('statuses s', 's.status_id = t.status_id', 'left')
            ->join('departments d', 'd.department_id = t.department_id', 'left')
            ->join('users u1', 'u1.user_id = t.department_resolved_by', 'left')
            ->join('users u2', 'u2.user_id = t.last_reopened_by', 'left')
            ->join('users u3', 'u3.user_id = t.last_rejected_by', 'left')
            ->where('t.ticket_id', $ticketId)
            ->where('t.department_id', $department['department_id'])
            ->get()
            ->getRowArray();
        
        if (!$ticket) {
            return $this->response->setJSON(['success' => false, 'message' => 'Ticket not found']);
        }
        
        return $this->response->setJSON([
            'success' => true,
            'ticket' => $ticket
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error getting ticket status: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}
/**
 * Get ticket status info untuk real-time polling
 */

    public function dashboard($deptType = null)
    {
        // Jika $deptType tidak diberikan, ambil dari URL
        if ($deptType === null) {
            $deptType = $this->getDeptTypeFromUrl();
        }

        // Validasi department
        $departmentName = $this->validateDepartment($deptType);
        if (!$departmentName) {
            $departmentMap = [
                'it-support' => 'IT Support',
                'technical-support' => 'Technical Support',
                'uiux-support' => 'UI/UX Support',
                'feature-request' => 'Feature Request'
            ];
            
            $expectedDeptName = $departmentMap[$deptType] ?? 'Unknown';
            $errorMsg = "Unauthorized department access. ";
            $errorMsg .= "You are logged in as <strong>" . htmlspecialchars($this->departmentName) . "</strong>, ";
            $errorMsg .= "but trying to access <strong>" . htmlspecialchars($expectedDeptName) . "</strong> dashboard.";
            
            return redirect()->to('/login')->with('error', $errorMsg);
        }

        $data = $this->loadCommonData();

        $userId = session()->get('user_id');

        // Get department ID
        $department = $this->deptDepartmentModel->findByName($departmentName);

        if (!$department) {
            return redirect()->to('/login')->with('error', 'Department not found in database: ' . $departmentName);
        }

        $departmentId = $department['department_id'];

        // Get department statistics
        $stats = $this->deptDepartmentModel->getDepartmentTicketsStatistics($departmentId);
        
        // Get assigned to me count
        $assignedToMe = $this->deptTicketModel->where('department_id', $departmentId)
            ->where('assigned_to', $userId)
            ->countAllResults();

        // Di dalam method dashboard() di DepartmentController, update bagian get recent tickets:

    $recentTickets = $this->db->table('tickets t')
        ->select('t.*, p.priority_name, s.status_name, cat.category_name, 
                 u.full_name as customer_name, proj.project_name, proj.project_code,
                 u2.full_name as assigned_to_name, u3.full_name as support_assigner,
                 ta.assignment_notes, ta.created_at as forwarded_at')
        ->join('priorities p', 'p.priority_id = t.priority_id')
        ->join('statuses s', 's.status_id = t.status_id')
        ->join('categories cat', 'cat.category_id = t.category_id')
        ->join('users u', 'u.user_id = t.customer_id')
        ->join('projects proj', 'proj.project_id = t.project_id', 'left')
        ->join('users u2', 'u2.user_id = t.assigned_to', 'left')
        ->join('ticket_assignments ta', 'ta.ticket_id = t.ticket_id AND ta.department_id = t.department_id', 'left')
        ->join('users u3', 'u3.user_id = ta.assigned_by', 'left')
        ->where('t.department_id', $departmentId) // HANYA INI YANG PENTING
        ->where('t.department_resolved_at IS NULL') // Belum di-resolve oleh department
        ->orderBy('ta.created_at', 'DESC') // Urutkan berdasarkan kapan di-forward
        ->limit(5)
        ->get()
        ->getResultArray();


    // PERBAIKAN: Jika tabel ticket_assignments tidak ada
    if (empty($recentTickets) || !$this->db->tableExists('ticket_assignments')) {
        // Alternatif: Ambil semua ticket yang department_id = department ini
        $recentTickets = $this->db->table('tickets t')
            ->select('t.*, p.priority_name, s.status_name, cat.category_name, 
                     u.full_name as customer_name, proj.project_name, proj.project_code,
                     u2.full_name as assigned_to_name')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->join('categories cat', 'cat.category_id = t.category_id')
            ->join('users u', 'u.user_id = t.customer_id')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->join('users u2', 'u2.user_id = t.assigned_to', 'left')
            ->where('t.department_id', $departmentId) // INI KUNCI UTAMA
            ->where('t.department_resolved_at IS NULL') // Belum di-resolve
            ->whereIn('t.status_id', [1, 2]) // Open atau In Progress
            ->orderBy('t.updated_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();
    }

        // Format recent tickets data
        foreach ($recentTickets as &$ticket) {
            $ticket['ticket_number'] = $ticket['ticket_number'] ?? 'TKT-' . $ticket['ticket_id'];
            $ticket['priority_value'] = $ticket['priority_id'];
            
            // Map priority colors
            $priorityColors = [
                1 => 'bg-blue-100 text-blue-800 border border-blue-200',
                2 => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                3 => 'bg-orange-100 text-orange-800 border border-orange-200',
                4 => 'bg-red-100 text-red-800 border border-red-200'
            ];
            $ticket['priorityColor'] = $priorityColors[$ticket['priority_id']] ?? 'bg-gray-100 text-gray-800 border border-gray-200';
            
            // Map status colors
            $statusColors = [
                1 => 'bg-blue-100 text-blue-800 border border-blue-200',
                2 => 'bg-purple-100 text-purple-800 border border-purple-200',
                3 => 'bg-green-100 text-green-800 border border-green-200',
                4 => 'bg-gray-100 text-gray-800 border border-gray-200'
            ];
            $ticket['statusColor'] = $statusColors[$ticket['status_id']] ?? 'bg-gray-100 text-gray-800 border border-gray-200';
            
            // Time ago
            $created = new \DateTime($ticket['created_at']);
            $now = new \DateTime();
            $interval = $created->diff($now);
            
            if ($interval->d > 0) {
                $ticket['time'] = $interval->d . ' days ago';
            } elseif ($interval->h > 0) {
                $ticket['time'] = $interval->h . ' hours ago';
            } elseif ($interval->i > 0) {
                $ticket['time'] = $interval->i . ' minutes ago';
            } else {
                $ticket['time'] = 'Just now';
            }
            
            $ticket['timestamp'] = $created->getTimestamp();
        }

        $data['stats'] = [
            'total_tickets' => $stats['total_tickets'],
            'open_tickets' => $stats['open_tickets'],
            'assigned_to_me' => $assignedToMe,
            'high_priority' => $stats['high_priority'] ?? 0,
            'resolved_today' => $stats['resolved_today'] ?? 0
        ];

        $data['recent_tickets'] = $recentTickets;
        $data['department_tickets'] = $this->deptDepartmentModel->getDepartmentTickets($departmentId);
        $data['my_tickets'] = $this->deptDepartmentModel->getAssignedTicketsForUser($departmentId, $userId);

        $data['view'] = "Department/{$this->formatDepartmentView($departmentName)}/dashboard";

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

    // Get department ID
    $department = $this->deptDepartmentModel->findByName($departmentName);
    
    if (!$department) {
        return redirect()->to('/login')->with('error', 'Department not found: ' . $departmentName);
    }
    
    $departmentId = $department['department_id'];

    // Get all projects for filter
    $data['projects'] = $this->projectModel->findAll();
    
    // 🔥 PERBAIKAN UTAMA: Query untuk mengambil semua ticket yang di-assign ke department
    // Tampilkan ticket yang: 
    // 1. department_id = departmentId (milik department ini)
    // 2. status_id IN (1, 2) -> Open atau In Progress
    // 3. department_resolved_at IS NULL -> belum di-resolve oleh department
    // 4. internal_status NOT IN ('approved', 'reopened_no') -> bukan yang sudah approved atau review only
    
    $assignedTickets = $this->db->table('tickets t')
        ->select('t.*, p.priority_name, s.status_name, cat.category_name, 
                 u.full_name as customer_name, proj.project_name, proj.project_code,
                 u2.full_name as assigned_to_name, ta.assignment_notes,
                 ta.created_at as forwarded_at')
        ->join('priorities p', 'p.priority_id = t.priority_id')
        ->join('statuses s', 's.status_id = t.status_id')
        ->join('categories cat', 'cat.category_id = t.category_id')
        ->join('users u', 'u.user_id = t.customer_id')
        ->join('projects proj', 'proj.project_id = t.project_id', 'left')
        ->join('users u2', 'u2.user_id = t.assigned_to', 'left')
        ->join('ticket_assignments ta', 'ta.ticket_id = t.ticket_id AND ta.department_id = t.department_id', 'left')
        ->where('t.department_id', $departmentId) // Ticket milik department ini
        ->whereIn('t.status_id', [1, 2]) // 🔥 Open (1) atau In Progress (2)
        ->where('t.department_resolved_at IS NULL') // Belum di-resolve oleh department
        ->whereNotIn('t.internal_status', ['approved', 'reopened_no']) // 🔥 Bukan yang sudah approved atau review only
        ->orderBy('t.updated_at', 'DESC')
        ->get()
        ->getResultArray();

    // Debugging
    log_message('debug', '=== DEPARTMENT TICKETS DEBUG ===');
    log_message('debug', 'Department: ' . $departmentName . ' (ID: ' . $departmentId . ')');
    log_message('debug', 'User Role: Department');
    log_message('debug', 'Query condition: department_id=' . $departmentId . ', status_id IN (1,2), department_resolved_at IS NULL');
    log_message('debug', 'Tickets found: ' . count($assignedTickets));
    
    // Log detail tiap ticket untuk debugging
    foreach ($assignedTickets as $index => $ticket) {
        log_message('debug', "Ticket #{$index}: ID={$ticket['ticket_id']}, Subject={$ticket['subject']}, StatusID={$ticket['status_id']}, DeptID={$ticket['department_id']}, InternalStatus={$ticket['internal_status']}");
    }

    // Jika tidak ada data, coba alternatif query (tampilkan SEMUA ticket department kecuali Closed)
    if (empty($assignedTickets)) {
        log_message('debug', 'Trying alternative query (show all except Closed)...');
        
        $assignedTickets = $this->db->table('tickets t')
            ->select('t.*, p.priority_name, s.status_name, cat.category_name, 
                     u.full_name as customer_name, proj.project_name, proj.project_code,
                     u2.full_name as assigned_to_name, ta.assignment_notes,
                     ta.created_at as forwarded_at')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->join('categories cat', 'cat.category_id = t.category_id')
            ->join('users u', 'u.user_id = t.customer_id')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->join('users u2', 'u2.user_id = t.assigned_to', 'left')
            ->join('ticket_assignments ta', 'ta.ticket_id = t.ticket_id AND ta.department_id = t.department_id', 'left')
            ->where('t.department_id', $departmentId)
            ->where('t.status_id !=', 4) // Bukan status Closed (4)
            ->where('t.department_resolved_at IS NULL') // Belum di-resolve
            ->orderBy('t.updated_at', 'DESC')
            ->get()
            ->getResultArray();
            
        log_message('debug', 'Alternative query found: ' . count($assignedTickets) . ' tickets');
    }

    // Jika user filter "My Tickets" aktif, filter lagi
    $userFilter = $this->request->getGet('my_tickets') ? $userId : null;
    if ($userFilter) {
        $assignedTickets = array_filter($assignedTickets, function($ticket) use ($userFilter) {
            return $ticket['assigned_to'] == $userFilter;
        });
        $assignedTickets = array_values($assignedTickets); // Reset keys
    }

    // Format tickets data
    foreach ($assignedTickets as &$ticket) {
        // Priority colors
        $priorityColors = [
            1 => 'bg-blue-100 text-blue-800 border border-blue-200',
            2 => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
            3 => 'bg-orange-100 text-orange-800 border border-orange-200',
            4 => 'bg-red-100 text-red-800 border border-red-200'
        ];
        $ticket['priorityColor'] = $priorityColors[$ticket['priority_id']] ?? 'bg-gray-100 text-gray-800 border border-gray-200';
        $ticket['priority_value'] = $ticket['priority_id'];
        
        // Status colors
        $statusColors = [
            1 => 'bg-blue-100 text-blue-800 border border-blue-200',
            2 => 'bg-purple-100 text-purple-800 border border-purple-200',
            3 => 'bg-green-100 text-green-800 border border-green-200',
            4 => 'bg-gray-100 text-gray-800 border border-gray-200'
        ];
        $ticket['statusColor'] = $statusColors[$ticket['status_id']] ?? 'bg-gray-100 text-gray-800 border border-gray-200';
        
        // Time ago
        $created = new \DateTime($ticket['created_at']);
        $now = new \DateTime();
        $interval = $created->diff($now);
        
        if ($interval->d > 0) {
            $ticket['time'] = $interval->d . ' days ago';
        } elseif ($interval->h > 0) {
            $ticket['time'] = $interval->h . ' hours ago';
        } elseif ($interval->i > 0) {
            $ticket['time'] = $interval->i . ' minutes ago';
        } else {
            $ticket['time'] = 'Just now';
        }
        
        $ticket['timestamp'] = $created->getTimestamp();
        $ticket['id_num'] = $ticket['ticket_id'];
        $ticket['id'] = $ticket['ticket_number'];
    }

    // Reset array keys
    $assignedTickets = array_values($assignedTickets);

    // Calculate statistics
    $totalTickets = count($assignedTickets);
    $inProgress = array_filter($assignedTickets, function($ticket) {
        return $ticket['status_id'] == 2 && empty($ticket['department_resolved_at']); // In Progress dan belum di-resolve
    });
    $highPriority = array_filter($assignedTickets, function($ticket) {
        return $ticket['priority_id'] >= 3; // High or Critical
    });
    
    // Get tickets resolved today by department
    $today = date('Y-m-d');
    $resolvedToday = array_filter($assignedTickets, function($ticket) use ($today) {
        if (!$ticket['department_resolved_at']) return false;
        $resolvedDate = date('Y-m-d', strtotime($ticket['department_resolved_at']));
        return $resolvedDate == $today;
    });

    $data['tickets'] = $assignedTickets;
    $data['stats'] = [
        'total_tickets' => $totalTickets,
        'in_progress' => count($inProgress),
        'high_priority' => count($highPriority),
        'resolved_today' => count($resolvedToday)
    ];

    $data['view'] = "Department/{$this->formatDepartmentView($departmentName)}/assigned_tickets";

    return view($data['view'], $data);
}

/**
 * Update ticket status to In Progress when department first replies
 */
private function updateTicketToInProgress($ticketId)
{
    try {
        $db = $this->db;
        
        // Cek apakah status masih Open (1)
        $ticket = $db->table('tickets')
            ->where('ticket_id', $ticketId)
            ->get()
            ->getRowArray();
        
        if ($ticket && $ticket['status_id'] == 1) { // Jika status masih Open
            // Update ke In Progress (2)
            $db->table('tickets')
                ->where('ticket_id', $ticketId)
                ->update([
                    'status_id' => 2, // In Progress
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            
            log_message('debug', 'Ticket #' . $ticketId . ' status updated from Open to In Progress');
            
            return true;
        }
        
        return false;
    } catch (\Exception $e) {
        log_message('error', 'Error updating ticket to in progress: ' . $e->getMessage());
        return false;
    }
}

/**
 * Helper method: Get tickets assigned to department by Support
 */
private function getTicketsAssignedBySupport($departmentId, $limit = null, $onlyActive = true)
{
    $userId = session()->get('user_id');
    $db = $this->db;
    
    // Coba dengan tabel ticket_assignments
    if ($db->tableExists('ticket_assignments')) {
        $query = $db->table('tickets t')
            ->select('t.*, p.priority_name, s.status_name, cat.category_name, 
                     u.full_name as customer_name, proj.project_name, proj.project_code,
                     u2.full_name as assigned_to_name, sup.full_name as assigned_by_support_name,
                     ta.assignment_notes, ta.created_at as assigned_at')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->join('categories cat', 'cat.category_id = t.category_id')
            ->join('users u', 'u.user_id = t.customer_id')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->join('users u2', 'u2.user_id = t.assigned_to', 'left')
            ->join('ticket_assignments ta', 'ta.ticket_id = t.ticket_id')
            ->join('users sup', 'sup.user_id = ta.assigned_by')
            ->where('t.department_id', $departmentId)
            ->where('ta.department_id', $departmentId)
            ->where('sup.role_id', 3); // Pastikan yang assign adalah Support
        
        // Filter hanya yang aktif (belum di-resolve oleh department)
        if ($onlyActive) {
            $query->where('t.department_resolved_at IS NULL');
        }
        
        $query->orderBy('ta.created_at', 'DESC');
        
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->get()->getResultArray();
    }
    
    // Fallback: Cari dari ticket_messages
    $query = $db->table('tickets t')
        ->select('t.*, p.priority_name, s.status_name, cat.category_name, 
                 u.full_name as customer_name, proj.project_name, proj.project_code,
                 u2.full_name as assigned_to_name, u3.full_name as assigned_by_support_name,
                 tm.message as assignment_message, tm.created_at as assigned_at')
        ->join('priorities p', 'p.priority_id = t.priority_id')
        ->join('statuses s', 's.status_id = t.status_id')
        ->join('categories cat', 'cat.category_id = t.category_id')
        ->join('users u', 'u.user_id = t.customer_id')
        ->join('projects proj', 'proj.project_id = t.project_id', 'left')
        ->join('users u2', 'u2.user_id = t.assigned_to', 'left')
        ->join('ticket_messages tm', 'tm.ticket_id = t.ticket_id')
        ->join('users u3', 'u3.user_id = tm.sender_id')
        ->where('t.department_id', $departmentId)
        ->where('u3.role_id', 3) // Support role
        ->where("(tm.message LIKE '%assigned to department%' OR tm.message LIKE '%forwarded to department%')");
    
    // Filter hanya yang aktif
    if ($onlyActive) {
        $query->where('t.department_resolved_at IS NULL');
    }
    
    $query->where('tm.created_at = (
            SELECT MAX(tm2.created_at) 
            FROM ticket_messages tm2 
            WHERE tm2.ticket_id = t.ticket_id 
            AND (tm2.message LIKE "%assigned to department%" OR tm2.message LIKE "%forwarded to department%")
        )')
        ->groupBy('t.ticket_id')
        ->orderBy('tm.created_at', 'DESC');
    
    if ($limit) {
        $query->limit($limit);
    }
    
    return $query->get()->getResultArray();
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

        $userId = session()->get('user_id');

        // Get notifications for this user
        $notifications = $this->db->table('notifications n')
            ->select('n.*, p.priority_name, t.ticket_number, u.full_name as user_name')
            ->join('priorities p', 'p.priority_id = n.priority_id', 'left')
            ->join('tickets t', 't.ticket_id = n.ticket_id', 'left')
            ->join('users u', 'u.user_id = n.user_id')
            ->where('n.user_id', $userId)
            ->orderBy('n.created_at', 'DESC')
            ->get()
            ->getResultArray();

        // Format notifications
        foreach ($notifications as &$notification) {
            $created = new \DateTime($notification['created_at']);
            $now = new \DateTime();
            $interval = $created->diff($now);
            
            if ($interval->d > 0) {
                $notification['time_ago'] = $interval->d . ' days ago';
            } elseif ($interval->h > 0) {
                $notification['time_ago'] = $interval->h . ' hours ago';
            } elseif ($interval->i > 0) {
                $notification['time_ago'] = $interval->i . ' minutes ago';
            } else {
                $notification['time_ago'] = 'Just now';
            }
            
            // Determine priority color
            $priorityColors = [
                1 => 'bg-blue-500/20 text-blue-300',
                2 => 'bg-yellow-500/20 text-yellow-300',
                3 => 'bg-orange-500/20 text-orange-300',
                4 => 'bg-red-500/20 text-red-300'
            ];
            $notification['priorityColor'] = $priorityColors[$notification['priority_id']] ?? 'bg-gray-500/20 text-gray-300';
            $notification['priorityText'] = $notification['priority_name'] ?? 'Normal';
        }

        // Get notification statistics
        $totalNotifications = count($notifications);
        $unreadNotifications = array_filter($notifications, function($n) {
            return !$n['is_read'];
        });
        
        // This week notifications
        $weekStart = date('Y-m-d', strtotime('monday this week'));
        $thisWeekNotifications = array_filter($notifications, function($n) use ($weekStart) {
            return date('Y-m-d', strtotime($n['created_at'])) >= $weekStart;
        });

        $data['notifications'] = $notifications;
        $data['stats'] = [
            'total' => $totalNotifications,
            'unread' => count($unreadNotifications),
            'this_week' => count($thisWeekNotifications)
        ];

        $viewPath = "Department/{$this->formatDepartmentView($departmentName)}/notifications";

        // Jika file view tidak ada, gunakan default
        if (!file_exists(APPPATH . "Views/{$viewPath}.php")) {
            $viewPath = "Department/notifications";
        }

        return view($viewPath, $data);
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

        // Get user details with department info
        $user = $this->db->table('users u')
            ->select('u.*, r.role_name, d.department_name, d.description as department_description')
            ->join('roles r', 'r.role_id = u.role_id')
            ->join('departments d', 'd.department_id = u.department_id', 'left')
            ->where('u.user_id', $userId)
            ->get()
            ->getRowArray();

        // Get user performance metrics
        $performanceMetrics = $this->db->table('tickets t')
            ->select('
                COUNT(t.ticket_id) as total_tickets,
                AVG(EXTRACT(EPOCH FROM (t.resolved_at - t.created_at))/3600) as avg_resolution_hours,
                SUM(CASE WHEN t.status_id = 3 THEN 1 ELSE 0 END) as resolved_count,
                SUM(CASE WHEN t.priority_id >= 3 THEN 1 ELSE 0 END) as high_priority_count
            ')
            ->where('t.assigned_to', $userId)
            ->where('t.status_id', 3) // Resolved tickets
            ->where('t.resolved_at >=', date('Y-m-d', strtotime('-30 days')))
            ->get()
            ->getRowArray();

        // Get recent assignments
        $recentAssignments = $this->db->table('tickets t')
            ->select('t.ticket_id, t.ticket_number, t.subject, p.priority_name, p.priority_id, s.status_name')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.assigned_to', $userId)
            ->where('t.status_id IN (1,2)') // Open or In Progress
            ->orderBy('t.created_at', 'DESC')
            ->limit(3)
            ->get()
            ->getResultArray();

        // Format recent assignments
        foreach ($recentAssignments as &$assignment) {
            $assignment['priorityColor'] = match($assignment['priority_id']) {
                1 => 'bg-blue-50',
                2 => 'bg-yellow-50',
                3 => 'bg-orange-50',
                4 => 'bg-red-50',
                default => 'bg-gray-50'
            };
        }

        $data['user_details'] = $user;
        $data['performance_metrics'] = $performanceMetrics;
        $data['recent_assignments'] = $recentAssignments;

        // Calculate SLA compliance (simplified)
        $data['sla_compliance'] = $performanceMetrics['total_tickets'] > 0 ? 
            round(($performanceMetrics['resolved_count'] / $performanceMetrics['total_tickets']) * 100, 1) : 0;

        // Format view path
        $viewPath = "Department/{$this->formatDepartmentView($departmentName)}/profile";

        // Jika file view tidak ada, gunakan default
        if (!file_exists(APPPATH . "Views/{$viewPath}.php")) {
            $viewPath = "Department/IT_Support/profile_it_support";
        }

        return view($viewPath, $data);
    }

public function ticketDetail($ticketId, $deptType = null)
{
    // PERBAIKAN: Jika $deptType null, ambil dari session
    if ($deptType === null) {
        // Dapatkan department name dari session dan konversi ke URL slug
        $deptName = session()->get('department_name');
        $deptMap = [
            'IT Support' => 'it-support',
            'Technical Support' => 'technical-support',
            'UI/UX Support' => 'uiux-support',
            'Feature Request' => 'feature-request'
        ];
        $deptType = $deptMap[$deptName] ?? 'it-support';
    }

    // Validasi department
    $departmentName = $this->validateDepartment($deptType);
    if (!$departmentName) {
        return redirect()->to('/login')->with('error', 'Unauthorized access');
    }

    $data = $this->loadCommonData();
    $userId = session()->get('user_id');
    $departmentId = null;

    // Get department ID
    $dept = $this->deptDepartmentModel->findByName($departmentName);
    if ($dept) {
        $departmentId = $dept['department_id'];
    }

    // Get ticket details
    $ticket = $this->db->table('tickets t')
        ->select('t.*, p.priority_name, s.status_name, cat.category_name, 
                 u.full_name as customer_name, u.email as customer_email,
                 proj.project_name, proj.project_code, proj.description as project_description,
                 d.department_name, u2.full_name as assigned_to_name, u2.email as assigned_to_email,
                 sup.full_name as assigned_by_support_name, sup.email as assigned_by_support_email')
        ->join('priorities p', 'p.priority_id = t.priority_id')
        ->join('statuses s', 's.status_id = t.status_id')
        ->join('categories cat', 'cat.category_id = t.category_id')
        ->join('users u', 'u.user_id = t.customer_id')
        ->join('projects proj', 'proj.project_id = t.project_id', 'left')
        ->join('departments d', 'd.department_id = t.department_id', 'left')
        ->join('users u2', 'u2.user_id = t.assigned_to', 'left')
        ->join('ticket_assignments ta', 'ta.ticket_id = t.ticket_id AND ta.department_id = t.department_id', 'left')
        ->join('users sup', 'sup.user_id = ta.assigned_by', 'left')
        ->where('t.ticket_id', $ticketId)
        ->where('t.department_id', $departmentId)
        ->get()
        ->getRowArray();

    if (!$ticket) {
        return redirect()->to("department/{$deptType}/assigned_tickets")->with('error', 'Ticket not found');
    }
    
    // 🔥 PERBAIKAN: Department TETAP bisa akses meskipun sudah approved atau reopened
    // Hanya block jika status benar-benar Closed (4)
    if ($ticket['status_id'] == 4) { // Status Closed
        return redirect()->to("department/{$deptType}/assigned_tickets")
            ->with('error', 'This ticket has been closed. Access restricted.');
    }

    // 🔥 PERBAIKAN: Cek apakah department bisa mark as resolved
    $canMarkResolved = false;
    $reason = '';
    
    // 1. Jika status = 'rejected' DAN belum di-resolve → BISA
    if ($ticket['internal_status'] === 'rejected' && $ticket['department_resolved_at'] === null) {
        $canMarkResolved = true;
        $reason = 'Ticket was rejected and needs correction';
    } 
    // 2. Jika status = 'reopened' (dicentang for corrections) DAN belum di-resolve → BISA
    elseif ($ticket['internal_status'] === 'reopened' && $ticket['department_resolved_at'] === null) {
        $canMarkResolved = true;
        $reason = 'Ticket reopened for corrections';
    }
    // 3. Jika status = 'reopened_no' (TIDAK dicentang) → TIDAK BISA
    elseif ($ticket['internal_status'] === 'reopened_no') {
        $canMarkResolved = false;
        $reason = 'Ticket reopened for review only (no corrections needed)';
    }
    // 4. Jika status = 'approved' → TIDAK BISA
    elseif ($ticket['internal_status'] === 'approved') {
        $canMarkResolved = false;
        $reason = 'Ticket already approved by Support';
    }
    // 5. Jika masih pending (belum pernah di-resolve) → BISA
    elseif ($ticket['department_resolved_at'] === null && $ticket['internal_status'] === 'pending') {
        $canMarkResolved = true;
        $reason = 'Initial resolution';
    }
    // 6. Jika status = 'review_needed' atau 'testing' → TIDAK BISA
    elseif (in_array($ticket['internal_status'], ['review_needed', 'testing'])) {
        $canMarkResolved = false;
        $reason = 'Ticket under Support review';
    }

    // PERBAIKAN: Get ONLY Support-Department conversation messages
    $messages = [];
    
    if ($this->db->tableExists('internal_chat_messages')) {
        $messages = $this->db->table('internal_chat_messages icm')
            ->select('icm.*, u.full_name as sender_name, u.role_id, r.role_name')
            ->join('users u', 'u.user_id = icm.sender_id', 'left')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->where('icm.ticket_id', $ticketId)
            ->where('icm.department_id', $departmentId)
            ->orderBy('icm.created_at', 'ASC')
            ->get()
            ->getResultArray();
    }
    
    // Option 2: Jika tidak ada internal_chat_messages, filter dari ticket_messages
    if (empty($messages)) {
        $messages = $this->db->table('ticket_messages tm')
            ->select('tm.*, u.full_name as sender_name, u.role_id, r.role_name')
            ->join('users u', 'u.user_id = tm.sender_id', 'left')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->where('tm.ticket_id', $ticketId)
            ->whereIn('u.role_id', [3, 4]) // HANYA Support (3) dan Department (4)
            ->orderBy('tm.created_at', 'ASC')
            ->get()
            ->getResultArray();
    }

    // Get support users for this department (for sidebar)
    $supportUsers = $this->db->table('users u')
        ->select('u.user_id, u.full_name, u.email, u.role_id, r.role_name')
        ->join('roles r', 'r.role_id = u.role_id', 'left')
        ->where('u.role_id', 3) // Support role
        ->where('u.is_active', true)
        ->get()
        ->getResultArray();

    // Get department members
    $departmentMembers = [];
    if ($departmentId) {
        $departmentMembers = $this->db->table('users u')
            ->select('u.user_id, u.full_name, u.email, u.role_id, r.role_name')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->where('u.department_id', $departmentId)
            ->where('u.role_id', 4) // Department role
            ->where('u.is_active', true)
            ->get()
            ->getResultArray();
    }

    $data['ticket'] = $ticket;
    $data['messages'] = $messages;
    $data['support_users'] = $supportUsers;
    $data['department_members'] = $departmentMembers;
    $data['ticket_id'] = $ticketId;
    $data['department_id'] = $departmentId;
    $data['can_mark_resolved'] = $canMarkResolved;
    $data['mark_resolved_reason'] = $reason;

    return view("Department/{$this->formatDepartmentView($departmentName)}/ticket_detail", $data);
}

/**
 * Send message to support (Department to Support chat)
 */
/**
 * Send message from department to support
 */
/**
 * Send message from department to support
 */
/**
 * Send message from department to support
 */
/**
 * Send message from department to support
 */
public function sendChatMessage($ticketId)
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }
    
    $message = $this->request->getPost('message');
    $userId = session()->get('user_id');
    $departmentName = session()->get('department_name');
    
    if (empty($message)) {
        return $this->response->setJSON(['success' => false, 'message' => 'Message cannot be empty']);
    }
    
    // Get department info
    $department = $this->deptDepartmentModel->findByName($departmentName);
    if (!$department) {
        return $this->response->setJSON(['success' => false, 'message' => 'Department not found']);
    }
    
    $db = $this->db;
    
    try {
        // Verify ticket belongs to user's department
        $ticket = $db->table('tickets')
            ->where('ticket_id', $ticketId)
            ->where('department_id', $department['department_id'])
            ->get()
            ->getRowArray();
        
        if (!$ticket) {
            return $this->response->setJSON(['success' => false, 'message' => 'Ticket not found or unauthorized']);
        }
        
        // 🔥 PERUBAHAN UTAMA: Cek apakah ini pesan pertama dari department
        $isFirstMessage = $this->isFirstDepartmentMessage($ticketId, $department['department_id']);
        
        // Save message to internal chat
        $messageData = [
            'ticket_id' => $ticketId,
            'sender_id' => $userId,
            'sender_role' => 'Department',
            'department_id' => $department['department_id'],
            'message' => $message,
            'is_internal' => true,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $db->table('internal_chat_messages')->insert($messageData);
        $messageId = $db->insertID();
        
        // 🔥 PERUBAHAN UTAMA: Jika ini pesan pertama, update status ticket ke In Progress
        $statusUpdated = false;
        if ($isFirstMessage) {
            $statusUpdated = $this->updateTicketToInProgress($ticketId);
            
            // Tambahkan system message ke internal chat
            if ($statusUpdated) {
                $systemMessage = "🔄 **Ticket Status Updated**\n" .
                    "First department response received. Status changed to In Progress.";
                
                $db->table('internal_chat_messages')->insert([
                    'ticket_id' => $ticketId,
                    'sender_id' => 0, // System user
                    'sender_role' => 'System',
                    'department_id' => $department['department_id'],
                    'message' => $systemMessage,
                    'is_internal' => true,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
        
        // Update ticket timestamp
        $db->table('tickets')
            ->where('ticket_id', $ticketId)
            ->update(['updated_at' => date('Y-m-d H:i:s')]);
        
        // Get complete message data
        $newMessage = $db->table('internal_chat_messages icm')
            ->select('icm.*, u.full_name as sender_name')
            ->join('users u', 'u.user_id = icm.sender_id', 'left')
            ->where('icm.message_id', $messageId)
            ->get()
            ->getRowArray();
        
        // Create notification for support users
        $this->createSupportNotification($ticketId, $department['department_id'], $userId, $message);
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Message sent successfully',
            'is_internal' => true,
            'is_first_department_response' => $isFirstMessage,
            'status_updated' => $statusUpdated,
            'data' => $newMessage
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error sending department chat message: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

/**
 * Check if this is the first message from department for this ticket
 */
private function isFirstDepartmentMessage($ticketId, $departmentId)
{
    $db = $this->db;
    
    // Cek apakah sudah ada pesan dari department di internal chat
    if ($db->tableExists('internal_chat_messages')) {
        $count = $db->table('internal_chat_messages')
            ->where('ticket_id', $ticketId)
            ->where('department_id', $departmentId)
            ->where('sender_role', 'Department')
            ->countAllResults();
        
        return $count == 0; // Return true jika ini pesan pertama
    }
    
    return true; // Default anggap pesan pertama
}

/**
 * Update ticket status to In Progress
 */


/**
 * Mark ticket as In Progress (by Department)
 */
public function markAsInProgress($ticketId)
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }
    
    $userId = session()->get('user_id');
    $departmentName = session()->get('department_name');
    $userName = session()->get('full_name') ?? 'Department Member';
    
    // Get department info
    $department = $this->deptDepartmentModel->findByName($departmentName);
    if (!$department) {
        return $this->response->setJSON(['success' => false, 'message' => 'Department not found']);
    }
    
    $db = $this->db;
    
    try {
        // Verify ticket belongs to user's department
        $ticket = $db->table('tickets')
            ->where('ticket_id', $ticketId)
            ->where('department_id', $department['department_id'])
            ->get()
            ->getRowArray();
        
        if (!$ticket) {
            return $this->response->setJSON(['success' => false, 'message' => 'Ticket not found or unauthorized']);
        }
        
        // Cek jika status sudah In Progress
        if ($ticket['status_id'] == 2) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Ticket is already In Progress'
            ]);
        }
        
        // Cek jika ticket sudah di-resolve oleh department
        if ($ticket['department_resolved_at']) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Cannot change status. Ticket has been resolved.'
            ]);
        }
        
        // Update ticket status to In Progress
        $updateData = [
            'status_id' => 2, // In Progress
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $db->table('tickets')
            ->where('ticket_id', $ticketId)
            ->update($updateData);
        
        // Add system message to internal chat
        $systemMessage = "🔄 **Ticket Status Updated**\n" .
                        "Marked as In Progress by: {$userName}\n" .
                        "Department: {$departmentName}\n" .
                        "Status: In Progress";
        
        $db->table('internal_chat_messages')->insert([
            'ticket_id' => $ticketId,
            'sender_id' => $userId,
            'sender_role' => 'System',
            'department_id' => $department['department_id'],
            'message' => $systemMessage,
            'is_internal' => true,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        // Log status change
        $db->table('ticket_status_history')->insert([
            'ticket_id' => $ticketId,
            'status_type' => 'status',
            'old_value' => $ticket['status_id'],
            'new_value' => 2,
            'changed_by' => $userId,
            'change_reason' => 'Department started working on ticket',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        // Create notification for Support users
        $supportUsers = $db->table('users')
            ->where('role_id', 3) // Support role
            ->where('is_active', true)
            ->get()
            ->getResultArray();
        
        foreach ($supportUsers as $supportUser) {
            $db->table('notifications')->insert([
                'user_id' => $supportUser['user_id'],
                'ticket_id' => $ticketId,
                'title' => 'Ticket Status Changed - In Progress',
                'message' => "Ticket #{$ticket['ticket_number']} is now In Progress by {$departmentName} department.",
                'is_read' => false,
                'notification_type' => 'ticket_in_progress',
                'priority_id' => 2,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
        
        // Get updated ticket data
        $updatedTicket = $db->table('tickets')
            ->select('t.*, s.status_name')
            ->join('statuses s', 's.status_id = t.status_id', 'left')
            ->where('t.ticket_id', $ticketId)
            ->get()
            ->getRowArray();
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Ticket marked as In Progress',
            'ticket_data' => $updatedTicket,
            'new_status_id' => 2,
            'new_status_name' => 'In Progress'
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error marking ticket as in progress: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}


/**
 * Send message from support to department (Support side - untuk referensi)
 */
public function sendSupportMessage($ticketId)
{
    // Method untuk Support mengirim pesan ke Department
    // TIDAK update status ke In Progress di sini
    
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }
    
    $message = $this->request->getPost('message');
    $userId = session()->get('user_id');
    
    // Verifikasi role Support
    $roleName = session()->get('role_name');
    if ($roleName !== 'Support') {
        return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
    }
    
    $db = $this->db;
    
    try {
        // Save message - TIDAK update status
        $messageData = [
            'ticket_id' => $ticketId,
            'sender_id' => $userId,
            'sender_role' => 'Support',
            'department_id' => $this->getTicketDepartmentId($ticketId),
            'message' => $message,
            'is_internal' => true,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $db->table('internal_chat_messages')->insert($messageData);
        
        // 🔥 PERHATIAN: Support chat TIDAK mengubah status ticket
        // Hanya update timestamp
        $db->table('tickets')
            ->where('ticket_id', $ticketId)
            ->update(['updated_at' => date('Y-m-d H:i:s')]);
        
        // ... sisa kode untuk response ...
        
    } catch (\Exception $e) {
        log_message('error', 'Error sending support message: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

// Helper function di Controller
private function isFirstDepartmentResponse($ticketId, $departmentId)
{
    $db = $this->db;
    
    $count = $db->table('internal_chat_messages icm')
        ->join('users u', 'u.user_id = icm.sender_id')
        ->join('roles r', 'r.role_id = u.role_id')
        ->where('icm.ticket_id', $ticketId)
        ->where('icm.department_id', $departmentId)
        ->where('r.role_name', 'Department')
        ->countAllResults();
    
    return $count == 0;
}

/**
 * Get internal chat messages (Department ↔ Support)
 */
/**
 * Get internal chat messages (Department ↔ Support)
 */
/**
 * Mengambil pesan chat internal antara Support dan Department
 */
/**
 * Get internal chat messages (Department ↔ Support)
 */
public function getChatMessages($ticketId)
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }
    
    $userId = session()->get('user_id');
    $departmentName = session()->get('department_name');
    
    // Get department info
    $department = $this->deptDepartmentModel->findByName($departmentName);
    if (!$department) {
        return $this->response->setJSON(['success' => false, 'message' => 'Department not found']);
    }
    
    $db = $this->db;
    
    try {
        // 🔥 PERBAIKAN UTAMA: HANYA ambil dari internal_chat_messages
        $messages = $db->table('internal_chat_messages icm')
            ->select('icm.*, u.full_name as sender_name, u.photo_profile as sender_photo, 
                icm.sender_role as role_name_db')
            ->join('users u', 'u.user_id = icm.sender_id', 'left')
            ->where('icm.ticket_id', $ticketId)
            ->where('icm.department_id', $department['department_id']) // HANYA chat internal untuk department ini
            ->orderBy('icm.created_at', 'ASC')
            ->get()
            ->getResultArray();
        
        // Format messages untuk response
        $formattedMessages = [];
        foreach ($messages as $msg) {
            // Gunakan sender_role dari icm, bukan role dari users
            $roleName = !empty($msg['sender_role']) ? $msg['sender_role'] : 'User';
            
            $formattedMessages[] = [
                'message_id' => $msg['message_id'],
                'sender_id' => $msg['sender_id'],
                'sender_name' => $msg['sender_name'] ?? 'Unknown',
                'sender_role' => $roleName,
                'role_name' => $roleName,
                'message' => $msg['message'],
                'is_internal' => (bool)$msg['is_internal'],
                'created_at' => $msg['created_at'],
                'time_ago' => $this->formatTimeAgo($msg['created_at']),
                'is_current_user' => $msg['sender_id'] == $userId,
                'is_support_to_customer' => false // 🔥 SELALU false untuk internal chat
            ];
        }
        
        return $this->response->setJSON([
            'success' => true,
            'messages' => $formattedMessages
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error getting chat messages: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}
/**
 * Get new chat messages
 */
public function getNewChatMessages($ticketId)
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }
    
    $lastMessageId = $this->request->getGet('last_message_id') ?? 0;
    $userId = session()->get('user_id');
    $departmentName = session()->get('department_name');
    $db = $this->db;
    
    try {
        // Get department
        $department = $this->deptDepartmentModel->findByName($departmentName);
        if (!$department) {
            return $this->response->setJSON(['success' => false, 'message' => 'Department not found']);
        }
        
        // Get new messages
        $messages = $db->table('internal_chat_messages icm')
            ->select('icm.*, u.full_name, u.role_id, r.role_name')
            ->join('users u', 'u.user_id = icm.sender_id', 'left')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->where('icm.ticket_id', $ticketId)
            ->where('icm.department_id', $department['department_id'])
            ->where('icm.message_id >', $lastMessageId)
            ->orderBy('icm.created_at', 'ASC')
            ->get()
            ->getResultArray();
        
        $formattedMessages = [];
        $newLastMessageId = $lastMessageId;
        
        foreach ($messages as $msg) {
            $formattedMessages[] = [
                'message_id' => $msg['message_id'],
                'sender_id' => $msg['sender_id'],
                'sender_name' => $msg['full_name'] ?? 'Unknown',
                'sender_role' => $msg['role_name'] ?? $msg['sender_role'] ?? 'User',
                'message' => $msg['message'],
                'is_internal' => (bool)$msg['is_internal'],
                'created_at' => $msg['created_at'],
                'time_ago' => $this->formatTimeAgo($msg['created_at']),
                'is_current_user' => $msg['sender_id'] == $userId
            ];
            
            if ($msg['message_id'] > $newLastMessageId) {
                $newLastMessageId = $msg['message_id'];
            }
        }
        
        return $this->response->setJSON([
            'success' => true,
            'messages' => $formattedMessages,
            'last_message_id' => $newLastMessageId
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error getting new chat messages: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

/**
 * Get new messages (Department ↔ Support only)
 */
public function getNewSupportChatMessages($ticketId)
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }
    
    $lastMessageId = $this->request->getGet('last_message_id') ?? 0;
    $userId = session()->get('user_id');
    $departmentName = session()->get('department_name');
    $db = $this->db;
    
    try {
        // Get department
        $department = $this->deptDepartmentModel->findByName($departmentName);
        if (!$department) {
            return $this->response->setJSON(['success' => false, 'message' => 'Department not found']);
        }
        
        // Get new messages from internal_chat_messages if exists
        $messages = [];
        if ($db->tableExists('internal_chat_messages')) {
            $messages = $db->table('internal_chat_messages icm')
                ->select('icm.*, u.full_name, u.role_id, r.role_name')
                ->join('users u', 'u.user_id = icm.sender_id', 'left')
                ->join('roles r', 'r.role_id = u.role_id', 'left')
                ->where('icm.ticket_id', $ticketId)
                ->where('icm.department_id', $department['department_id'])
                ->where('icm.message_id >', $lastMessageId)
                ->orderBy('icm.created_at', 'ASC')
                ->get()
                ->getResultArray();
        }
        
        // Fallback to ticket_messages
        if (empty($messages)) {
            $messages = $db->table('ticket_messages tm')
                ->select('tm.*, u.full_name, u.role_id, r.role_name')
                ->join('users u', 'u.user_id = tm.sender_id', 'left')
                ->join('roles r', 'r.role_id = u.role_id', 'left')
                ->where('tm.ticket_id', $ticketId)
                ->whereIn('u.role_id', [3, 4]) // Only Support and Department
                ->where('tm.message_id >', $lastMessageId)
                ->orderBy('tm.created_at', 'ASC')
                ->get()
                ->getResultArray();
        }
        
        $formattedMessages = [];
        $newLastMessageId = $lastMessageId;
        
        foreach ($messages as $msg) {
            $formattedMessages[] = [
                'message_id' => $msg['message_id'],
                'sender_id' => $msg['sender_id'],
                'sender_name' => $msg['full_name'],
                'sender_role' => $msg['role_name'],
                'message' => $msg['message'],
                'created_at' => $msg['created_at'],
                'time_ago' => $this->formatTimeAgo($msg['created_at']),
                'is_current_user' => $msg['sender_id'] == $userId
            ];
            
            if ($msg['message_id'] > $newLastMessageId) {
                $newLastMessageId = $msg['message_id'];
            }
        }
        
        return $this->response->setJSON([
            'success' => true,
            'messages' => $formattedMessages,
            'last_message_id' => $newLastMessageId
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error getting new chat messages: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

/**
 * Send message from department to support
 */
public function sendMessage($ticketId)
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }
    
    $message = $this->request->getPost('message');
    $userId = session()->get('user_id');
    $departmentName = session()->get('department_name');
    
    if (empty($message)) {
        return $this->response->setJSON(['success' => false, 'message' => 'Message cannot be empty']);
    }
    
    $db = $this->db;
    
    try {
        // Verify ticket belongs to user's department
        $ticket = $db->table('tickets t')
            ->select('t.department_id, d.department_name')
            ->join('departments d', 'd.department_id = t.department_id', 'left')
            ->where('t.ticket_id', $ticketId)
            ->where('d.department_name', $departmentName)
            ->get()
            ->getRowArray();
        
        if (!$ticket) {
            return $this->response->setJSON(['success' => false, 'message' => 'Ticket not found or unauthorized']);
        }
        
        // Save message
        $db->table('ticket_messages')->insert([
            'ticket_id' => $ticketId,
            'sender_id' => $userId,
            'message' => $message,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        $messageId = $db->insertID();
        
        // Update ticket timestamp
        $db->table('tickets')
            ->where('ticket_id', $ticketId)
            ->update(['updated_at' => date('Y-m-d H:i:s')]);
        
        // Get complete message data
        $newMessage = $db->table('ticket_messages tm')
            ->select('tm.*, u.full_name, u.role_id, r.role_name')
            ->join('users u', 'u.user_id = tm.sender_id', 'left')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->where('tm.message_id', $messageId)
            ->get()
            ->getRowArray();
        
        // Create notification for support users
        $this->createSupportNotification($ticketId, $ticket['department_id'], $userId, $message);
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => $newMessage
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error sending department message: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

/**
 * Get chat messages
 */
public function getMessages($ticketId)
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }
    
    $userId = session()->get('user_id');
    $departmentName = session()->get('department_name');
    $db = $this->db;
    
    try {
        // Verify access
        $ticket = $db->table('tickets t')
            ->join('departments d', 'd.department_id = t.department_id', 'left')
            ->where('t.ticket_id', $ticketId)
            ->where('d.department_name', $departmentName)
            ->get()
            ->getRowArray();
        
        if (!$ticket) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }
        
        // Get ONLY Support-Department conversation messages
        $messages = $db->table('ticket_messages tm')
            ->select('tm.*, u.full_name as sender_name, u.role_id, r.role_name')
            ->join('users u', 'u.user_id = tm.sender_id', 'left')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->where('tm.ticket_id', $ticketId)
            ->whereIn('u.role_id', [3, 4]) // HANYA Support (3) dan Department (4)
            ->orderBy('tm.created_at', 'ASC')
            ->get()
            ->getResultArray();
        
        // Format messages
        $formattedMessages = [];
        foreach ($messages as $msg) {
            $formattedMessages[] = [
                'message_id' => $msg['message_id'],
                'sender_id' => $msg['sender_id'],
                'sender_name' => $msg['sender_name'],
                'sender_role' => $msg['role_name'],
                'message' => $msg['message'],
                'created_at' => $msg['created_at'],
                'time_ago' => $this->formatTimeAgo($msg['created_at']),
                'is_current_user' => $msg['sender_id'] == $userId
            ];
        }
        
        return $this->response->setJSON([
            'success' => true,
            'messages' => $formattedMessages
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error getting messages: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

/**
 * Get new messages
 */
public function getNewMessages($ticketId)
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }
    
    $lastMessageId = $this->request->getGet('last_message_id') ?? 0;
    $userId = session()->get('user_id');
    $departmentName = session()->get('department_name');
    $db = $this->db;
    
    try {
        // Verify access
        $ticket = $db->table('tickets t')
            ->join('departments d', 'd.department_id = t.department_id', 'left')
            ->where('t.ticket_id', $ticketId)
            ->where('d.department_name', $departmentName)
            ->get()
            ->getRowArray();
        
        if (!$ticket) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }
        
        // Get new messages (only Support-Department)
        $messages = $db->table('ticket_messages tm')
            ->select('tm.*, u.full_name as sender_name, u.role_id, r.role_name')
            ->join('users u', 'u.user_id = tm.sender_id', 'left')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->where('tm.ticket_id', $ticketId)
            ->where('tm.message_id >', $lastMessageId)
            ->whereIn('u.role_id', [3, 4]) // HANYA Support (3) dan Department (4)
            ->orderBy('tm.created_at', 'ASC')
            ->get()
            ->getResultArray();
        
        $formattedMessages = [];
        $newLastMessageId = $lastMessageId;
        
        foreach ($messages as $msg) {
            $formattedMessages[] = [
                'message_id' => $msg['message_id'],
                'sender_id' => $msg['sender_id'],
                'sender_name' => $msg['sender_name'],
                'sender_role' => $msg['role_name'],
                'message' => $msg['message'],
                'created_at' => $msg['created_at'],
                'time_ago' => $this->formatTimeAgo($msg['created_at']),
                'is_current_user' => $msg['sender_id'] == $userId
            ];
            
            if ($msg['message_id'] > $newLastMessageId) {
                $newLastMessageId = $msg['message_id'];
            }
        }
        
        return $this->response->setJSON([
            'success' => true,
            'messages' => $formattedMessages,
            'last_message_id' => $newLastMessageId
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error getting new messages: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

/**
 * Create notification for support users
 */
private function createSupportNotification($ticketId, $departmentId, $senderId, $messageText)
{
    $db = $this->db;
    
    // Get sender info
    $sender = $db->table('users u')
        ->select('u.full_name, r.role_name')
        ->join('roles r', 'r.role_id = u.role_id')
        ->where('u.user_id', $senderId)
        ->get()
        ->getRowArray();
    
    $senderName = $sender['full_name'] ?? 'Department Member';
    
    // Get all support users (for notification)
    $supportUsers = $db->table('users u')
        ->select('u.user_id')
        ->join('roles r', 'r.role_id = u.role_id')
        ->where('r.role_id', 3) // Support role
        ->where('u.is_active', true)
        ->get()
        ->getResultArray();
    
    $messagePreview = substr($messageText, 0, 100) . (strlen($messageText) > 100 ? '...' : '');
    
    // Get ticket info
    $ticket = $db->table('tickets')
        ->select('ticket_number, subject')
        ->where('ticket_id', $ticketId)
        ->get()
        ->getRowArray();
    
    foreach ($supportUsers as $user) {
        $notificationData = [
            'user_id' => $user['user_id'],
            'ticket_id' => $ticketId,
            'title' => 'New Department Message - Ticket #' . ($ticket['ticket_number'] ?? $ticketId),
            'message' => $senderName . ' (Department): ' . $messagePreview,
            'notification_type' => 'department_chat',
            'is_read' => false,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $db->table('notifications')->insert($notificationData);
    }
}

/**
 * Format time ago
 */
private function formatTimeAgo($datetime)
{
    $time = strtotime($datetime);
    $now = time();
    $diff = $now - $time;
    
    if ($diff < 60) return 'Just now';
    if ($diff < 3600) {
        $minutes = floor($diff / 60);
        return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
    }
    if ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    }
    if ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    }
    
    return date('M d, Y', $time);
}

    public function ticketSummary($ticketId, $deptType = null)
    {
        if ($deptType === null) {
            $deptType = $this->getDeptTypeFromUrl();
        }

        $departmentName = $this->validateDepartment($deptType);
        if (!$departmentName) {
            return redirect()->to('/login')->with('error', 'Unauthorized access');
        }

        $data = $this->loadCommonData();
        $userId = session()->get('user_id');

        // Get ticket details with all related information
        $ticket = $this->db->table('tickets t')
            ->select('t.*, p.priority_name, s.status_name, cat.category_name, 
                     u.full_name as customer_name, u.email as customer_email,
                     proj.project_name, proj.project_code, proj.description as project_description,
                     d.department_name, u2.full_name as assigned_to_name, u2.email as assigned_to_email')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->join('categories cat', 'cat.category_id = t.category_id')
            ->join('users u', 'u.user_id = t.customer_id')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->join('departments d', 'd.department_id = t.department_id', 'left')
            ->join('users u2', 'u2.user_id = t.assigned_to', 'left')
            ->where('t.ticket_id', $ticketId)
            ->where('t.department_id', function($builder) use ($departmentName) {
                $builder->select('department_id')
                       ->from('departments')
                       ->where('department_name', $departmentName);
            })
            ->get()
            ->getRowArray();

        if (!$ticket) {
            return redirect()->to("department/{$deptType}/assigned_tickets")->with('error', 'Ticket not found');
        }

        // Get timeline of status changes (from ticket_messages or create a separate log)
        $timeline = $this->db->table('ticket_messages tm')
            ->select('tm.*, u.full_name as sender_name')
            ->join('users u', 'u.user_id = tm.sender_id')
            ->where('tm.ticket_id', $ticketId)
            ->where('tm.message LIKE', '%status%')
            ->orderBy('tm.created_at', 'ASC')
            ->get()
            ->getResultArray();

        // Get related tickets (same customer or project)
        $relatedTickets = $this->db->table('tickets t')
            ->select('t.ticket_id, t.ticket_number, t.subject, s.status_name, p.priority_name')
            ->join('statuses s', 's.status_id = t.status_id')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->where('t.customer_id', $ticket['customer_id'])
            ->where('t.ticket_id !=', $ticketId)
            ->where('t.status_id IN (1,2)') // Open or In Progress
            ->orderBy('t.created_at', 'DESC')
            ->limit(3)
            ->get()
            ->getResultArray();

        $data['ticket'] = $ticket;
        $data['timeline'] = $timeline;
        $data['related_tickets'] = $relatedTickets;
        $data['ticket_id'] = $ticketId;

        return view("Department/{$this->formatDepartmentView($departmentName)}/ticket_summary", $data);
    }

    public function updateTicketStatus($ticketId)
    {
        $statusId = $this->request->getPost('status_id');
        $notes = $this->request->getPost('notes');

        // Update ticket status
        $this->deptTicketModel->update($ticketId, [
            'status_id' => $statusId,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Add message
        $message = "Ticket status updated to " . $this->getStatusName($statusId);
        if ($notes) {
            $message .= ": " . $notes;
        }

        $this->db->table('ticket_messages')->insert([
            'ticket_id' => $ticketId,
            'sender_id' => session()->get('user_id'),
            'message' => $message,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Ticket status updated successfully');
    }

    public function addMessage($ticketId)
    {
        $message = $this->request->getPost('message');
        $isInternal = $this->request->getPost('is_internal');

        $this->db->table('ticket_messages')->insert([
            'ticket_id' => $ticketId,
            'sender_id' => session()->get('user_id'),
            'message' => $message,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // Update ticket's updated_at
        $this->deptTicketModel->update($ticketId, [
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Message added successfully');
    }

public function assignTicketToMe($ticketId)
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }
    
    $userId = session()->get('user_id');
    $departmentName = session()->get('department_name');
    
    // Get department info
    $department = $this->deptDepartmentModel->findByName($departmentName);
    if (!$department) {
        return $this->response->setJSON(['success' => false, 'message' => 'Department not found']);
    }
    
    $db = db_connect();
    
    try {
        // Check if ticket belongs to this department
        $ticket = $db->table('tickets')
            ->where('ticket_id', $ticketId)
            ->where('department_id', $department['department_id'])
            ->get()
            ->getRowArray();
        
        if (!$ticket) {
            return $this->response->setJSON(['success' => false, 'message' => 'Ticket not found or not in your department']);
        }
        
        // Assign ticket to current user
        $db->table('tickets')
            ->where('ticket_id', $ticketId)
            ->update([
                'assigned_to' => $userId,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        
        // Add assignment log
        if ($db->tableExists('ticket_assignments')) {
            $db->table('ticket_assignments')->insert([
                'ticket_id' => $ticketId,
                'department_id' => $department['department_id'],
                'assigned_by' => $userId, // Self-assigned
                'assigned_to' => $userId,
                'assignment_type' => 'self',
                'assignment_notes' => 'Self-assigned by department member',
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
        
        // Add message
        $db->table('ticket_messages')->insert([
            'ticket_id' => $ticketId,
            'sender_id' => $userId,
            'message' => 'Ticket assigned to department member',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Ticket assigned to you successfully'
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error assigning ticket: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}
    public function markNotificationRead($notificationId)
    {
        $this->db->table('notifications')
            ->where('notification_id', $notificationId)
            ->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Notification marked as read');
    }

    /**
 * Department marks ticket as resolved
 */
/**
 * Department marks ticket as resolved
 */
public function markAsResolved($ticketId)
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }
    
    $notes = $this->request->getPost('notes');
    $userId = session()->get('user_id');
    $departmentName = session()->get('department_name');
    
    // Get department info
    $department = $this->deptDepartmentModel->findByName($departmentName);
    if (!$department) {
        return $this->response->setJSON([
            'success' => false, 
            'message' => 'Department not found'
        ]);
    }
    
    $db = $this->db;
    
    try {
        // Verify ticket belongs to user's department
        $ticket = $db->table('tickets')
            ->where('ticket_id', $ticketId)
            ->where('department_id', $department['department_id'])
            ->get()
            ->getRowArray();
        
        if (!$ticket) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Ticket not found or unauthorized'
            ]);
        }
        
        // 🔥 LOGIKA PENTING: Cek apakah boleh mark as resolved
        $canMarkResolved = false;
        $reason = '';
        
        // 1. Jika status = 'rejected' DAN belum di-resolve → BISA
        if ($ticket['internal_status'] === 'rejected' && $ticket['department_resolved_at'] === null) {
            $canMarkResolved = true;
            $reason = 'Ticket was rejected and needs correction';
        } 
        // 2. Jika status = 'reopened' (dicentang for corrections) DAN belum di-resolve → BISA
        elseif ($ticket['internal_status'] === 'reopened' && $ticket['department_resolved_at'] === null) {
            $canMarkResolved = true;
            $reason = 'Ticket reopened for corrections';
        }
        // 3. Jika status = 'reopened_no_corrections' (TIDAK dicentang) → TIDAK BISA
        elseif ($ticket['internal_status'] === 'reopened_no_corrections') {
            $canMarkResolved = false;
            $reason = 'Ticket reopened for review only (no corrections needed)';
        }
        // 4. Jika masih pending (belum pernah di-resolve) → BISA
        elseif ($ticket['department_resolved_at'] === null && $ticket['internal_status'] === 'pending') {
            $canMarkResolved = true;
            $reason = 'Initial resolution';
        }
        
        if (!$canMarkResolved) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Cannot mark as resolved. ' . $reason
            ]);
        }
        
        // Update ticket
        $updateData = [
            'department_resolved_at' => date('Y-m-d H:i:s'),
            'department_resolved_by' => $userId,
            'internal_status' => 'review_needed', // Status untuk review oleh Support
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $db->table('tickets')
            ->where('ticket_id', $ticketId)
            ->update($updateData);
        
        // Add internal message
        $userName = session()->get('full_name') ?? 'Department Member';
        $message = "✅ **Department Resolution**\n";
        $message .= "Marked as resolved by: {$userName}\n";
        $message .= "Department: {$departmentName}\n";
        
        if ($ticket['internal_status'] === 'rejected') {
            $message .= "📌 **This is a corrected resolution after rejection**\n";
        } elseif ($ticket['internal_status'] === 'reopened') {
            $message .= "📌 **Corrections completed after reopening**\n";
        }
        
        if ($notes) {
            $message .= "Notes: {$notes}";
        }
        
        // Tambahkan ke internal_chat_messages
        $db->table('internal_chat_messages')->insert([
            'ticket_id' => $ticketId,
            'sender_id' => $userId,
            'sender_role' => 'Department',
            'department_id' => $department['department_id'],
            'message' => $message,
            'is_internal' => true,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        // Create notification for Support users
        $supportUsers = $db->table('users')
            ->where('role_id', 3) // Support role
            ->where('is_active', true)
            ->get()
            ->getResultArray();
        
        foreach ($supportUsers as $supportUser) {
            $db->table('notifications')->insert([
                'user_id' => $supportUser['user_id'],
                'ticket_id' => $ticketId,
                'title' => 'Department Resolution - Review Needed',
                'message' => "Ticket #{$ticket['ticket_number']} marked as resolved by {$departmentName}. Please review.",
                'is_read' => false,
                'notification_type' => 'department_resolution',
                'priority_id' => 2,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
        
        // Get updated ticket data
        $updatedTicket = $db->table('tickets')
            ->where('ticket_id', $ticketId)
            ->get()
            ->getRowArray();
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Ticket marked as resolved. Waiting for Support review.',
            'ticket_data' => [
                'ticket_id' => $ticketId,
                'ticket_number' => $ticket['ticket_number'],
                'internal_status' => 'review_needed',
                'department_resolved_at' => date('Y-m-d H:i:s'),
                'department_resolved_by' => $userId,
                'status_name' => 'UNDER REVIEW'
            ],
            'reason' => $reason
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error marking ticket as resolved: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

    public function markAllNotificationsRead()
    {
        $userId = session()->get('user_id');
        
        $this->db->table('notifications')
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return redirect()->back()->with('success', 'All notifications marked as read');
    }

    private function getStatusName($statusId)
    {
        $status = $this->db->table('statuses')
            ->where('status_id', $statusId)
            ->get()
            ->getRowArray();
        
        return $status ? $status['status_name'] : 'Unknown';
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
            return null;
        }

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
        $userId = $this->userId;
        
        // Get user details
        $user = $this->db->table('users u')
            ->select('u.*, r.role_name, d.department_name')
            ->join('roles r', 'r.role_id = u.role_id')
            ->join('departments d', 'd.department_id = u.department_id', 'left')
            ->where('u.user_id', $userId)
            ->get()
            ->getRowArray();

        // Get unread notifications count
        $unreadCount = $this->db->table('notifications')
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->countAllResults();

        $data = [
            'title' => 'Department Dashboard',
            'user_id' => $userId,
            'role_name' => session()->get('role_name'),
            'department_name' => $this->departmentName,
            'user_details' => $user,
            'unread_notifications' => $unreadCount,
            'active_menu' => 'dashboard'
        ];

        return $data;
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Logged out successfully');
    }
}