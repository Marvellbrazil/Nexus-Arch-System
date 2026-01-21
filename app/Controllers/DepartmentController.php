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
    private $db;

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

        // Get recent tickets (last 5)
        $recentTickets = $this->db->table('tickets t')
            ->select('t.*, p.priority_name, s.status_name, cat.category_name, u.full_name as customer_name, proj.project_name, proj.project_code')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->join('categories cat', 'cat.category_id = t.category_id')
            ->join('users u', 'u.user_id = t.customer_id')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->where('t.department_id', $departmentId)
            ->orderBy('t.created_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

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
        
        // Get assigned tickets with all details
        $assignedTickets = $this->db->table('tickets t')
            ->select('t.*, p.priority_name, s.status_name, cat.category_name, u.full_name as customer_name, 
                     proj.project_name, proj.project_code, u2.full_name as assigned_to_name')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->join('categories cat', 'cat.category_id = t.category_id')
            ->join('users u', 'u.user_id = t.customer_id')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->join('users u2', 'u2.user_id = t.assigned_to', 'left')
            ->where('t.department_id', $departmentId)
            ->where('t.assigned_to', $userId)
            ->orderBy('t.created_at', 'DESC')
            ->get()
            ->getResultArray();

        // Format tickets data
        foreach ($assignedTickets as &$ticket) {
            // Priority colors
            $priorityColors = [
                1 => 'bg-blue-100 text-blue-800',
                2 => 'bg-yellow-100 text-yellow-800',
                3 => 'bg-orange-100 text-orange-800',
                4 => 'bg-red-100 text-red-800'
            ];
            $ticket['priorityColor'] = $priorityColors[$ticket['priority_id']] ?? 'bg-gray-100 text-gray-800';
            $ticket['priority_value'] = $ticket['priority_id'];
            
            // Status colors
            $statusColors = [
                1 => 'bg-blue-100 text-blue-800',
                2 => 'bg-purple-100 text-purple-800',
                3 => 'bg-green-100 text-green-800',
                4 => 'bg-gray-100 text-gray-800'
            ];
            $ticket['statusColor'] = $statusColors[$ticket['status_id']] ?? 'bg-gray-100 text-gray-800';
            
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

        // Calculate statistics
        $totalTickets = count($assignedTickets);
        $inProgress = array_filter($assignedTickets, function($ticket) {
            return $ticket['status_id'] == 2; // In Progress
        });
        $highPriority = array_filter($assignedTickets, function($ticket) {
            return $ticket['priority_id'] >= 3; // High or Critical
        });
        
        // Get tickets resolved today
        $today = date('Y-m-d');
        $resolvedToday = array_filter($assignedTickets, function($ticket) use ($today) {
            if (!$ticket['resolved_at']) return false;
            $resolvedDate = date('Y-m-d', strtotime($ticket['resolved_at']));
            return $resolvedDate == $today && $ticket['status_id'] == 3;
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
        if ($deptType === null) {
            $deptType = $this->getDeptTypeFromUrl();
        }

        $departmentName = $this->validateDepartment($deptType);
        if (!$departmentName) {
            return redirect()->to('/login')->with('error', 'Unauthorized access');
        }

        $data = $this->loadCommonData();
        $userId = session()->get('user_id');

        // Get ticket details
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

        // Get ticket messages
        $messages = $this->db->table('ticket_messages tm')
            ->select('tm.*, u.full_name as sender_name, u.photo_profile')
            ->join('users u', 'u.user_id = tm.sender_id')
            ->where('tm.ticket_id', $ticketId)
            ->orderBy('tm.created_at', 'ASC')
            ->get()
            ->getResultArray();

        // Get ticket attachments
        $attachments = $this->db->table('ticket_attachments ta')
            ->select('ta.*, u.full_name as uploaded_by_name')
            ->join('users u', 'u.user_id = ta.uploaded_by')
            ->where('ta.ticket_id', $ticketId)
            ->orderBy('ta.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $data['ticket'] = $ticket;
        $data['messages'] = $messages;
        $data['attachments'] = $attachments;
        $data['ticket_id'] = $ticketId;

        return view("Department/{$this->formatDepartmentView($departmentName)}/ticket_detail", $data);
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

    public function markNotificationRead($notificationId)
    {
        $this->db->table('notifications')
            ->where('notification_id', $notificationId)
            ->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Notification marked as read');
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