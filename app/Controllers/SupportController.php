<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;
use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\TicketModel;
use App\Models\NotificationModel;
use App\Models\SupportStatsModel;
use App\Models\DepartmentModel;
use DateTime;
use Exception;

class SupportController extends BaseController
{
    private $userId;
    protected $supportUserModel;
    private $supportRoleModel;
    private $supportTicketModel;
    private $supportNotificationModel;
    private $supportStatsModel;
    protected $db;

    public function __construct()
    {
        $this->db = db_connect();

        // Check if user is logged in
        if (!session()->get('is_logged_in')) {
            return redirect()->to('support/login');
        }

        // Check if user has support role
        $userRole = session()->get('role');
        if (!in_array($userRole, ['Support', 'Admin'])) {
            throw PageNotFoundException::forPageNotFound();
        }

        $this->userId = session()->get('user_id');

        // Load models
        $this->supportUserModel = new UserModel();
        $this->supportRoleModel = new RoleModel();
        $this->supportTicketModel = new TicketModel();
        $this->supportNotificationModel = new NotificationModel();
        $this->supportStatsModel = new SupportStatsModel();
    }

    protected function loadCommonData()
    {
        $userId = session()->get('user_id');
        $db = db_connect();

        // Get user details
        $user = [];
        if ($userId) {
            $user = $db->table('users u')
                ->select('u.*, r.role_name, d.department_name')
                ->join('roles r', 'r.role_id = u.role_id', 'left')
                ->join('departments d', 'd.department_id = u.department_id', 'left')
                ->where('u.user_id', $userId)
                ->get()
                ->getRowArray();
        }

        // Get notification count
        $notificationCount = 0;
        if ($db->tableExists('notifications')) {
            try {
                $fields = $db->getFieldNames('notifications');
                $readColumn = 'is_read';
                if (in_array('read_status', $fields)) {
                    $readColumn = 'read_status';
                }

                $notificationCount = $db->table('notifications')
                    ->where('user_id', $userId)
                    ->where($readColumn, 0)
                    ->countAllResults();
            } catch (Exception $e) {
                $notificationCount = 5; // Default fallback
            }
        }

        return [
            'title' => 'Support Dashboard',
            'user' => $user,
            'notification_count' => $notificationCount,
            'current_url' => current_url()
        ];
    }

    public function dashboard()
    {
        $data = $this->loadCommonData();

        $userId = session()->get('user_id');
        $db = db_connect();

        // Load models
        $userModel = new UserModel();
        $roleModel = new RoleModel();

        // Get user details dengan departments
        $data['user_details'] = $userModel->getUserDetails($userId);

        // Hitung active duration (logika sederhana)
        $activeDuration = "8h 24m";

        // Get user departments
        if ($data['user_details'] && $data['user_details']['department_id']) {
            $departmentModel = new DepartmentModel();
            $department = $departmentModel->find($data['user_details']['department_id']);
            if ($department) {
                $data['user_details']['department_name'] = $department['department_name'];
            }
        }

        // ==================== STATISTIK DINAMIS ====================

        $this->supportStatsModel = new SupportStatsModel();

        // 1. Tickets in Progress
        $ticketsInProgress = $this->supportStatsModel->getInProgressTicketsCount($userId);

        // Tickets need attention (priority tinggi)
        $needsAttention = $this->supportStatsModel->getNeedsAttentionCount($userId);

        // 2. Waiting Customer Reply
        $waitingCustomerReply = $this->supportStatsModel->getWaitingCustomerReplyCount($userId);

        // 3. Incoming Tickets (belum diassign)
        $incomingTickets = $this->supportStatsModel->getIncomingTicketsCount();

        // New today
        $newToday = $this->supportStatsModel->getNewTodayCount();

        // ==================== TEAM UPDATES ====================

        // Agents online
        $agentsOnline = 0;
        try {
            $agentsOnlineQuery = $db->table('users u')
                ->join('roles r', 'r.role_id = u.role_id')
                ->where('r.role_name', 'Support');

            if ($db->fieldExists('is_active', 'users')) {
                $agentsOnlineQuery->where('u.is_active', true);
            }

            $agentsOnline = $agentsOnlineQuery->countAllResults();
        } catch (Exception $e) {
            $agentsOnline = 8;
        }

        // Agents in meeting
        $agentsInMeeting = 0;
        try {
            if ($db->fieldExists('in_meeting', 'users')) {
                $agentsInMeeting = $db->table('users u')
                    ->join('roles r', 'r.role_id = u.role_id')
                    ->where('r.role_name', 'Support')
                    ->where('u.in_meeting', true)
                    ->countAllResults();
            }
        } catch (Exception $e) {
            $agentsInMeeting = 2;
        }

        // ==================== RECENT TICKETS ====================

        $data['recent_tickets'] = $db->table('tickets t')
            ->select('t.ticket_id, t.ticket_number, t.subject,
                 p.priority_name,
                 s.status_name,
                 c.category_name,
                 u.full_name as customer_name,
                 proj.project_name,
                 t.created_at')
            ->join('priorities p', 'p.priority_id = t.priority_id', 'left')
            ->join('statuses s', 's.status_id = t.status_id', 'left')
            ->join('categories c', 'c.category_id = t.category_id', 'left')
            ->join('users u', 'u.user_id = t.customer_id', 'left')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->where('t.assigned_to IS NULL')
            ->where('s.status_name', 'Open')
            ->orderBy('t.created_at', 'DESC')
            ->limit(3)
            ->get()
            ->getResultArray();

        // ==================== RECENT NOTIFICATIONS ====================

        $data['recent_notifications'] = [];
        if ($db->tableExists('notifications')) {
            try {
                $data['recent_notifications'] = $db->table('notifications n')
                    ->select('n.*')
                    ->where('n.user_id', $userId)
                    ->where('n.read_status', 0)
                    ->orderBy('n.created_at', 'DESC')
                    ->limit(3)
                    ->get()
                    ->getResultArray();
            } catch (Exception $e) {
                $data['recent_notifications'] = [
                    ['title' => 'New ticket assigned #10425', 'created_at' => date('Y-m-d H:i:s', strtotime('-10 minutes')), 'type' => 'assignment'],
                    ['title' => 'Ticket #10422 needs follow up', 'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours')), 'type' => 'warning'],
                    ['title' => 'Customer replied to #10421', 'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')), 'type' => 'message']
                ];
            }
        }

        // ==================== PASS DATA KE VIEW ====================
        $data['stats'] = [
            'tickets_in_progress' => $ticketsInProgress,
            'needs_attention' => $needsAttention,
            'waiting_customer_reply' => $waitingCustomerReply,
            'incoming_tickets' => $incomingTickets,
            'new_today' => $newToday,
            'agents_online' => $agentsOnline,
            'agents_in_meeting' => $agentsInMeeting,
            'active_duration' => $activeDuration
        ];

        return view('Support/dashboard', $data);
    }

public function updateInternalStatus($ticketId)
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }

    $status = $this->request->getPost('status');
    $notes = $this->request->getPost('notes');
    $reopenForDepartment = $this->request->getPost('reopen_for_department') === 'true';
    $userId = session()->get('user_id');
    $userName = session()->get('full_name') ?? 'Support Agent';

    log_message('debug', 'updateInternalStatus called for ticket: ' . $ticketId);
    log_message('debug', 'Status: ' . $status . ', Notes: ' . $notes . ', Reopen: ' . ($reopenForDepartment ? 'true' : 'false'));

    // Valid statuses
    $validStatuses = ['pending', 'review_needed', 'testing', 'approved', 'rejected', 'reopened'];

    if (!in_array($status, $validStatuses)) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid status: ' . $status]);
    }

    $db = $this->db;

    try {
        // Get current ticket data
        $ticket = $db->table('tickets')
            ->where('ticket_id', $ticketId)
            ->get()
            ->getRowArray();

        if (!$ticket) {
            return $this->response->setJSON(['success' => false, 'message' => 'Ticket not found']);
        }

        $oldInternalStatus = $ticket['internal_status'] ?? 'pending';
        $departmentId = $ticket['department_id'] ?? 0;

        // Update ticket data
        $updateData = [
            'internal_status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Status labels
        $statusLabels = [
            'pending' => 'Pending',
            'review_needed' => 'Review Needed',
            'testing' => 'Testing',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'reopened' => 'Reopened'
        ];

        $systemMessage = "";

        // 🆕 1. JIKA STATUS = APPROVED (Support menyetujui resolusi Department) → TICKET APPROVED (TETAP IN PROGRESS)
        if ($status === 'approved') {
            // 🔥 PERUBAHAN PENTING: Status tetap In Progress (2), BUKAN Resolved (3)
            $updateData['status_id'] = 2; // 🔥 TETAP In Progress
            $updateData['resolved_by'] = $userId; // Catat siapa yang approve
            $updateData['approved_at'] = date('Y-m-d H:i:s'); // Tambah timestamp approval
            
            if ($notes) {
                $updateData['internal_status_notes'] = $notes;
            }

            $systemMessage = "✅ **Ticket Approved by Support**\n" .
                "Support Agent: {$userName}\n" .
                "Status: Approved\n";
            if ($notes) {
                $systemMessage .= "Notes: {$notes}\n";
            }
            $systemMessage .= "✅ Ticket has been approved. It will remain visible for reference.";
        }

        // 2. JIKA STATUS = REOPENED (Support mengembalikan ke Department)
        elseif ($status === 'reopened') {
            $updateData['status_id'] = 2; // Kembali ke In Progress
            
            if ($reopenForDepartment) {
                $updateData['department_resolved_at'] = null;
                $updateData['department_resolved_by'] = null;
                $updateData['internal_status'] = 'reopened';
                $updateData['last_reopened_by'] = $userId;
                
                $systemMessage = "🔄 **Ticket Reopened by Support for Corrections**\n" .
                    "Support Agent: {$userName}\n" .
                    "Status: Reopened for Corrections\n";
                if ($notes) {
                    $systemMessage .= "Reason: {$notes}\n";
                }
                $systemMessage .= "⚠️ Department can now make corrections and mark as resolved again.";
            } else {
                $updateData['internal_status'] = 'reopened_no';
                $updateData['last_reopened_by'] = $userId;
                
                $systemMessage = "🔄 **Ticket Reopened by Support for Review**\n" .
                    "Support Agent: {$userName}\n" .
                    "Status: Reopened for Review Only\n";
                if ($notes) {
                    $systemMessage .= "Notes: {$notes}\n";
                }
                $systemMessage .= "📝 Department can discuss but cannot mark as resolved.";
            }
        }

        // 3. JIKA STATUS = REJECTED (Support menolak resolusi Department)
        elseif ($status === 'rejected') {
            $updateData['status_id'] = 2; // Tetap In Progress
            $updateData['department_resolved_at'] = null;
            $updateData['department_resolved_by'] = null;
            $updateData['last_rejected_by'] = $userId;
            $updateData['internal_status_notes'] = $notes;

            $systemMessage = "❌ **Ticket Rejected by Support**\n" .
                "Support Agent: {$userName}\n" .
                "Status: Rejected\n";
            if ($notes) {
                $systemMessage .= "Reason: {$notes}\n";
            }
            $systemMessage .= "⚠️ Department needs to review and make corrections.";
        }

        // 4. STATUS LAINNYA (pending, review_needed, testing)
        else {
            $updateData['status_id'] = 2; // Tetap In Progress
            $systemMessage = "🔄 **Internal Status Updated**\n" .
                "Updated by: {$userName} (Support)\n" .
                "Status: {$statusLabels[$status]}\n";
            if ($notes) {
                $systemMessage .= "Notes: {$notes}";
            }
        }

        // Update ticket
        $result = $db->table('tickets')
            ->where('ticket_id', $ticketId)
            ->update($updateData);

        if (!$result) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update ticket status'
            ]);
        }

        // Log status history - internal_status
        $historyStatus = $status;
        if ($status === 'reopened' && !$reopenForDepartment) {
            $historyStatus = 'reopened_no';
        }
        
        $db->table('ticket_status_history')->insert([
            'ticket_id' => $ticketId,
            'status_type' => 'internal_status',
            'old_value' => $oldInternalStatus,
            'new_value' => $historyStatus,
            'changed_by' => $userId,
            'change_reason' => $notes,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // Add internal message
        if (!empty($systemMessage) && $departmentId) {
            $messageData = [
                'ticket_id' => $ticketId,
                'sender_id' => $userId,
                'sender_role' => 'Support',
                'department_id' => $departmentId,
                'message' => $systemMessage,
                'is_internal' => true,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $db->table('internal_chat_messages')->insert($messageData);
        }

        // Create notifications untuk department
        if (($status === 'rejected' || $status === 'reopened') && $departmentId) {
            $notificationTitle = '';
            $notificationMsg = '';
            
            if ($status === 'rejected') {
                $notificationTitle = "Ticket #{$ticketId} Rejected by Support";
                $notificationMsg = "Support has rejected ticket #{$ticketId}. Please review the feedback and make corrections.";
            } elseif ($status === 'reopened') {
                if ($reopenForDepartment) {
                    $notificationTitle = "Ticket #{$ticketId} Reopened for Corrections";
                    $notificationMsg = "Support has reopened ticket #{$ticketId} for corrections. Please make the necessary changes.";
                } else {
                    $notificationTitle = "Ticket #{$ticketId} Reopened for Review";
                    $notificationMsg = "Support has reopened ticket #{$ticketId} for discussion. You can chat but cannot mark as resolved.";
                }
            }
            
            // Notify department members
            $departmentMembers = $db->table('users')
                ->where('department_id', $departmentId)
                ->where('is_active', true)
                ->where('user_id !=', $userId)
                ->get()
                ->getResultArray();

            foreach ($departmentMembers as $member) {
                $db->table('notifications')->insert([
                    'user_id' => $member['user_id'],
                    'ticket_id' => $ticketId,
                    'title' => $notificationTitle,
                    'message' => $notificationMsg,
                    'is_read' => false,
                    'notification_type' => 'ticket_' . $status,
                    'priority_id' => 2,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        // 🔥 TAMBAHKAN: Jika status approved, buat notifikasi untuk customer
        if ($status === 'approved') {
            // Notify customer
            $db->table('notifications')->insert([
                'user_id' => $ticket['customer_id'],
                'ticket_id' => $ticketId,
                'title' => 'Ticket Approved - #' . ($ticket['ticket_number'] ?? $ticketId),
                'message' => 'Your ticket has been approved by the support team.',
                'is_read' => false,
                'notification_type' => 'ticket_approved',
                'priority_id' => 2,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // 🔥 TAMBAHKAN: Tambahkan pesan untuk customer di ticket_messages
            $customerMessage = "✅ **Ticket Approved**\n" .
                "Your ticket has been approved by the support team.\n" .
                "Status: Approved\n";
            if ($notes) {
                $customerMessage .= "Notes from support: " . $notes;
            }

            $db->table('ticket_messages')->insert([
                'ticket_id' => $ticketId,
                'sender_id' => $userId,
                'message' => $customerMessage,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        // Get updated ticket data untuk response
        $updatedTicket = $db->table('tickets t')
            ->select('t.*, s.status_name, d.department_name')
            ->join('statuses s', 's.status_id = t.status_id', 'left')
            ->join('departments d', 'd.department_id = t.department_id', 'left')
            ->where('t.ticket_id', $ticketId)
            ->get()
            ->getRowArray();

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Internal status updated successfully',
            'status' => $status,
            'status_label' => $statusLabels[$status] ?? $status,
            'reopened_for_department' => $reopenForDepartment,
            'reopened_type' => $reopenForDepartment ? 'with_corrections' : 'review_only',
            'ticket' => $updatedTicket,
            'requires_ui_update' => true,
            'ticket_status' => $updatedTicket['status_name'] ?? '',
            'ticket_status_id' => $updatedTicket['status_id'] ?? 2,
            'approved_at' => $status === 'approved' ? date('Y-m-d H:i:s') : null,
            'resolved_by' => $userId
        ]);
    } catch (\Exception $e) {
        log_message('error', 'Error updating internal status: ' . $e->getMessage());

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error updating status: ' . $e->getMessage()
        ]);
    }
}

public function getInternalStatusInfo($ticketId)
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }

    $db = db_connect();

    try {
        // Get ticket data - HAPUS field yang tidak ada di database
        $ticket = $db->table('tickets t')
            ->select('t.*, 
                d.department_name,
                s.status_name')
            ->join('departments d', 'd.department_id = t.department_id', 'left')
            ->join('statuses s', 's.status_id = t.status_id', 'left')
            ->where('t.ticket_id', $ticketId)
            ->get()
            ->getRowArray();

        if (!$ticket) {
            return $this->response->setJSON(['success' => false, 'message' => 'Ticket not found']);
        }

        // Get status history
        $history = $db->table('ticket_status_history h')
            ->select('h.*, u.full_name as changed_by_name')
            ->join('users u', 'u.user_id = h.changed_by', 'left')
            ->where('h.ticket_id', $ticketId)
            ->where('h.status_type', 'internal_status')
            ->orderBy('h.created_at', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

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

        // Format response data - HAPUS field yang tidak ada
        $responseData = [
            'success' => true,
            'ticket' => $ticket,
            'history' => $history,
            'status_info' => $statusInfo,
            'current_status' => $currentStatus,
            'current_status_label' => $statusInfo['label']
        ];

        return $this->response->setJSON($responseData);
    } catch (\Exception $e) {
        log_message('error', 'Error getting internal status info: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

    public function incomingTickets()
    {
        $data = $this->loadCommonData();

        $db = db_connect();

        // ==================== STATISTIK DINAMIS ====================

        // Total incoming (semua ticket yang belum diassign)
        $totalIncoming = $db->table('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.assigned_to IS NULL')
            ->where('s.status_name', 'Open')
            ->countAllResults();

        // Pending review (yang masih Open)
        $pendingReview = $totalIncoming; // atau logika khusus jika ada

        // Forwarded today
        $forwardedToday = $db->table('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('s.status_name', 'Forwarded')
            ->where('DATE(t.updated_at)', date('Y-m-d'))
            ->countAllResults();

        // High priority (Urgent/High)
        $highPriority = $db->table('tickets t')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.assigned_to IS NULL')
            ->where('s.status_name', 'Open')
            ->whereIn('p.priority_name', ['Urgent', 'High'])
            ->countAllResults();

        $data['stats'] = [
            'total_incoming' => $totalIncoming,
            'pending_review' => $pendingReview,
            'forwarded_today' => $forwardedToday,
            'high_priority' => $highPriority
        ];

        // ==================== TIKET DINAMIS ====================

        // Get all incoming tickets dengan semua relasi yang diperlukan
        $data['tickets'] = $db->table('tickets t')
            ->select('t.*, 
                p.priority_name, p.priority_id,
                s.status_name, 
                cat.category_name, 
                u.full_name as customer_name, u.email as customer_email,
                proj.project_name, proj.project_id,
                d.department_name')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->join('categories cat', 'cat.category_id = t.category_id')
            ->join('users u', 'u.user_id = t.customer_id')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->join('departments d', 'd.department_id = t.department_id', 'left')
            ->where('t.assigned_to IS NULL')
            ->where('s.status_name', 'Open')
            ->orderBy('p.priority_id', 'DESC') // Urgent/High first
            ->orderBy('t.created_at', 'ASC') // Oldest first
            ->get()
            ->getResultArray();

        return view('Support/incoming_tickets', $data);
    }

    public function assignTicket($ticketId)
    {
        $userId = session()->get('user_id');
        $db = db_connect();

        $db->table('tickets')
            ->where('ticket_id', $ticketId)
            ->update([
                'assigned_to' => $userId,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        return redirect()->back()->with('success', 'Ticket assigned to you');
    }

    public function notifications()
    {
        $data = $this->loadCommonData();

        $userId = session()->get('user_id');
        $db = db_connect();

        // Cek apakah tabel notifications ada
        if (!$db->tableExists('notifications')) {
            $data['notifications'] = [];
            $data['stats'] = [
                'total' => 0,
                'unread' => 0,
                'this_week' => 0
            ];
            return view('Support/notifications', $data);
        }

        // Get notifications dengan filter dari request
        $filter = $this->request->getGet('filter');
        $priority = $this->request->getGet('priority');
        $search = $this->request->getGet('search');
        $query = $db->table('notifications n')
            ->select('n.*, 
            t.ticket_number, 
            t.subject as ticket_subject,
            p.priority_name as priority_name')
            ->join('tickets t', 't.ticket_id = n.ticket_id', 'left')
            ->join('priorities p', 'p.priority_id = n.priority_id', 'left')
            ->where('n.user_id', $userId);

        // Apply filters
        if ($filter && $filter !== 'all') {
            $query->where('n.notification_type', $filter);
        }

        if ($priority && $priority !== 'all') {
            if ($priority === 'urgent') {
                $query->where('p.priority_name', 'Urgent');
            } elseif ($priority === 'high') {
                $query->where('p.priority_name', 'High');
            } elseif ($priority === 'medium') {
                $query->where('p.priority_name', 'Medium');
            } elseif ($priority === 'low') {
                $query->where('p.priority_name', 'Low');
            }
        }

        if ($search) {
            $query->groupStart()
                ->like('n.title', $search)
                ->orLike('n.message', $search)
                ->orLike('t.ticket_number', $search)
                ->orLike('t.subject', $search)
                ->groupEnd();
        }

        // Order by unread first, then newest
        $data['notifications'] = $query->orderBy('n.is_read', 'ASC')
            ->orderBy('n.created_at', 'DESC')
            ->get()
            ->getResultArray();

        // Get stats dinamis
        $currentWeekStart = date('Y-m-d', strtotime('monday this week'));
        $currentWeekEnd = date('Y-m-d', strtotime('sunday this week'));
        $data['stats'] = [
            'total' => $db->table('notifications')
                ->where('user_id', $userId)
                ->countAllResults(),
            'unread' => $db->table('notifications')
                ->where('user_id', $userId)
                ->where('is_read', false)
                ->countAllResults(),
            'this_week' => $db->table('notifications')
                ->where('user_id', $userId)
                ->where("DATE(created_at) >= '{$currentWeekStart}'")
                ->where("DATE(created_at) <= '{$currentWeekEnd}'")
                ->countAllResults()
        ];

        // Get notification types count for filter
        $data['notification_types'] = $db->table('notifications')
            ->select('notification_type as type, COUNT(*) as count')
            ->where('user_id', $userId)
            ->groupBy('notification_type')
            ->get()
            ->getResultArray();

        // Debug log
        log_message('info', 'Loaded ' . count($data['notifications']) . ' notifications for user ' . $userId);

        return view('Support/notifications', $data);
    }

    public function loadMoreNotifications()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $userId = session()->get('user_id');
        $page = $this->request->getGet('page') ?? 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $db = db_connect();

        if (!$db->tableExists('notifications')) {
            return $this->response->setJSON([
                'success' => false,
                'html' => ''
            ]);
        }

        try {
            // Apply the same filters as main notifications page
            $filter = $this->request->getGet('filter');
            $priority = $this->request->getGet('priority');
            $search = $this->request->getGet('search');

            $query = $db->table('notifications n')
                ->select('n.*, 
                t.ticket_number, 
                t.subject as ticket_subject,
                p.priority_name,
                p.priority_id')
                ->join('tickets t', 't.ticket_id = n.ticket_id', 'left')
                ->join('priorities p', 'p.priority_id = t.priority_id', 'left')
                ->where('n.user_id', $userId);

            // Apply filters
            if ($filter && $filter !== 'all') {
                $query->where('n.notification_type', $filter);
            }

            if ($priority && $priority !== 'all') {
                if ($priority === 'urgent') {
                    $query->where('p.priority_name', 'Urgent');
                } elseif ($priority === 'high') {
                    $query->where('p.priority_name', 'High');
                } elseif ($priority === 'medium') {
                    $query->where('p.priority_name', 'Medium');
                } elseif ($priority === 'low') {
                    $query->where('p.priority_name', 'Low');
                }
            }

            if ($search) {
                $query->groupStart()
                    ->like('n.title', $search)
                    ->orLike('n.message', $search)
                    ->orLike('t.ticket_number', $search)
                    ->orLike('t.subject', $search)
                    ->groupEnd();
            }

            // Get notifications for this page
            $notifications = $query->orderBy('n.is_read', 'ASC')
                ->orderBy('n.created_at', 'DESC')
                ->limit($limit, $offset)
                ->get()
                ->getResultArray();

            if (empty($notifications)) {
                return $this->response->setJSON([
                    'success' => true,
                    'html' => '',
                    'has_more' => false
                ]);
            }

            // Generate HTML for notifications
            $html = '';
            foreach ($notifications as $notification) {
                // Determine priority
                $priority = strtolower($notification['priority_name'] ?? 'medium');
                $priorityClass = '';
                $priorityText = '';

                switch ($priority) {
                    case 'urgent':
                        $priorityClass = 'bg-[#E16D7F] text-white';
                        $priorityText = 'Urgent';
                        break;
                    case 'high':
                        $priorityClass = 'bg-[#FFD2D2] text-red-800';
                        $priorityText = 'High';
                        break;
                    case 'medium':
                        $priorityClass = 'bg-[#FED7AA] text-orange-800';
                        $priorityText = 'Medium';
                        break;
                    case 'low':
                        $priorityClass = 'bg-[#C7D2FE] text-blue-800';
                        $priorityText = 'Low';
                        break;
                    default:
                        $priorityClass = 'bg-gray-200 text-gray-800';
                        $priorityText = 'Normal';
                }

                // Determine type
                $type = strtolower($notification['notification_type'] ?? 'system');

                // Format time
                function formatNotificationTime($datetime)
                {
                    $time = strtotime($datetime);
                    $now = time();
                    $diff = $now - $time;

                    if ($diff < 60)
                        return 'Just now';
                    elseif ($diff < 3600)
                        return floor($diff / 60) . ' minutes ago';
                    elseif ($diff < 86400)
                        return floor($diff / 3600) . ' hours ago';
                    elseif ($diff < 604800)
                        return floor($diff / 86400) . ' days ago';
                    else
                        return date('M d, Y', $time);
                }

                $timeAgo = formatNotificationTime($notification['created_at']);

                // Get ticket link if exists
                $ticketLink = $notification['ticket_id'] ?
                    base_url('support/ticket_detail/' . $notification['ticket_id']) :
                    '#';

                $html .= '<div class="notification-item bg-white/10 rounded-xl p-4 md:p-5 hover:bg-white/15 transition-colors ' .
                    (!$notification['is_read'] ? 'notification-unread' : '') .
                    '" data-priority="' . $priority .
                    '" data-type="' . $type .
                    '" data-id="' . $notification['notification_id'] . '">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-2">
                                    <h3 class="text-base md:text-lg font-bold text-white">' .
                    esc($notification['title']) .
                    '</h3>
                                    <span class="text-xs md:text-sm text-white/60">' . $timeAgo . '</span>
                                </div>
                                <p class="text-sm md:text-[14px] text-white/80 mb-3">' .
                    esc($notification['message']);

                if ($notification['ticket_number']) {
                    $html .= '<br>
                         <span class="font-medium">Ticket: ' . esc($notification['ticket_number']) . ' - ' .
                        esc($notification['ticket_subject'] ?? '') . '</span>';
                }

                $html .= '</p>
                                <div class="flex items-center gap-2">
                                    <div class="priority-badge px-3 py-1 rounded-lg ' . $priorityClass . '">
                                        <span class="text-xs font-bold">' . $priorityText . '</span>
                                    </div>
                                    <span class="text-xs text-white/60 px-2 py-1 bg-white/10 rounded">
                                        ' . ucfirst($type) . '
                                    </span>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-3">';

                if (!$notification['is_read']) {
                    $html .= '<div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center unread-indicator">
                            <div class="w-2 h-2 bg-white rounded-full"></div>
                          </div>';
                } else {
                    $html .= '<div class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center">
                            <div class="w-2 h-2 bg-white/50 rounded-full"></div>
                          </div>';
                }

                if ($notification['ticket_id']) {
                    $html .= '<a href="' . $ticketLink . '" 
                           class="text-white/60 hover:text-white transition-colors text-sm px-3 py-1 bg-white/10 rounded-lg">
                            View Ticket
                          </a>';
                }

                $html .= '</div>
                        </div>
                    </div>';
            }

            return $this->response->setJSON([
                'success' => true,
                'html' => $html,
                'has_more' => count($notifications) >= $limit
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'html' => '',
                'message' => $e->getMessage()
            ]);
        }
    }

    // Helper method untuk membuat notifikasi otomatis
    public static function createNotification($userId, $title, $message, $type = 'system', $priority = 'medium', $ticketId = null)
    {
        $db = db_connect();

        if (!$db->tableExists('notifications')) {
            return false;
        }

        try {
            $db->table('notifications')->insert([
                'user_id' => $userId,
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'priority' => $priority,
                'ticket_id' => $ticketId,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return true;
        } catch (Exception $e) {
            log_message('error', 'Error creating notification: ' . $e->getMessage());
            return false;
        }
    }

    public function getUnreadCount()
    {
        $userId = session()->get('user_id');
        $db = db_connect();

        try {
            $count = $db->table('notifications')
                ->where('user_id', $userId)
                ->where('is_read', false)
                ->countAllResults();

            return $this->response->setJSON([
                'success' => true,
                'count' => $count
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'count' => 0,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function markNotificationRead()
    {
        $userId = session()->get('user_id');
        $db = db_connect();

        try {
            $db->table('notifications')
                ->where('user_id', $userId)
                ->update(['is_read' => 't']);

            return redirect()->back()->with('success', 'Notifications marked as read successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to mark notification as read: ' . $e->getMessage());
        }
    }

public function sendMessage()
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }

    $ticketId = $this->request->getPost('ticket_id');
    $message = $this->request->getPost('message');
    $userId = session()->get('user_id');
    $isInternal = $this->request->getPost('is_internal') === 'true'; // FLAG BARU untuk menentukan jenis pesan

    log_message('debug', 'Support sendMessage - Ticket: ' . $ticketId . 
               ', Internal: ' . ($isInternal ? 'Yes' : 'No') . 
               ', User: ' . $userId);

    if (empty($message)) {
        return $this->response->setJSON(['success' => false, 'message' => 'Message cannot be empty']);
    }

    $db = db_connect();

    // Validasi ticket
    $ticket = $db->table('tickets')
        ->where('ticket_id', $ticketId)
        ->get()
        ->getRowArray();

    if (!$ticket) {
        log_message('error', 'Ticket not found - Ticket: ' . $ticketId);
        return $this->response->setJSON(['success' => false, 'message' => 'Ticket not found']);
    }

    try {
        // ================================
        // **PERUBAHAN PENTING DI SINI**
        // ================================
        
        // TENTUKAN JENIS PESAN BERDASARKAN FLAG is_internal
        if ($isInternal && $ticket['department_id']) {
            // PESAN INTERNAL: Support → Department (disimpan di internal_chat_messages)
            log_message('debug', 'Saving INTERNAL message to internal_chat_messages');
            
            $messageData = [
                'ticket_id' => $ticketId,
                'sender_id' => $userId,
                'sender_role' => 'Support',
                'department_id' => $ticket['department_id'],
                'message' => $message,
                'is_internal' => true,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $db->table('internal_chat_messages')->insert($messageData);
            $messageId = $db->insertID();
            
            // Notifikasi ke department (INTERNAL ONLY)
            $this->createInternalNotification(
                $ticketId, 
                $ticket['department_id'], 
                $userId, 
                $message
            );
            
            $table = 'internal_chat_messages';
            
        } else {
            // PESAN CUSTOMER: Support → Customer (disimpan di ticket_messages)
            log_message('debug', 'Saving CUSTOMER message to ticket_messages');
            
            $messageData = [
                'ticket_id' => $ticketId,
                'sender_id' => $userId,
                'message' => $message,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $db->table('ticket_messages')->insert($messageData);
            $messageId = $db->insertID();
            
            // Notifikasi ke customer
            $this->sendSupportMessageNotification($ticketId, $userId, $message);
            
            $table = 'ticket_messages';
        }

        // Update ticket timestamp
        $db->table('tickets')
            ->where('ticket_id', $ticketId)
            ->update(['updated_at' => date('Y-m-d H:i:s')]);

        // Get message data for response
        if ($isInternal && $ticket['department_id']) {
            // Ambil dari internal_chat_messages
            $newMessage = $db->table('internal_chat_messages icm')
                ->select('icm.*, u.full_name as sender_name')
                ->join('users u', 'u.user_id = icm.sender_id', 'left')
                ->where('icm.message_id', $messageId)
                ->get()
                ->getRowArray();
                
            $newMessage['role_name'] = 'Support';
            $newMessage['is_internal'] = true;
            $newMessage['is_support_to_customer'] = false; // TIDAK untuk customer
        } else {
            // Ambil dari ticket_messages
            $newMessage = $db->table('ticket_messages tm')
                ->select('tm.*, u.full_name, r.role_name')
                ->join('users u', 'u.user_id = tm.sender_id', 'left')
                ->join('roles r', 'r.role_id = u.role_id', 'left')
                ->where('tm.message_id', $messageId)
                ->get()
                ->getRowArray();
                
            $newMessage['is_internal'] = false;
            $newMessage['is_support_to_customer'] = true; // UNTUK CUSTOMER
        }

        log_message('debug', 'Message saved successfully in table: ' . $table);
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Message sent successfully',
            'is_internal' => $isInternal,
            'data' => $newMessage
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error in sendMessage: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

/**
 * Mengambil pesan chat dengan CUSTOMER (External)
 */

/**
 * Mengambil pesan chat dengan DEPARTMENT (Internal)
 */

    public function getNewMessages()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $ticketId = $this->request->getGet('ticket_id');
        $lastMessageId = $this->request->getGet('last_message_id') ?? 0;

        $userId = session()->get('user_id');
        $db = db_connect();

        // Validasi ticket access (support bisa akses semua)
        $ticket = $db->table('tickets')
            ->where('ticket_id', $ticketId)
            ->get()
            ->getRowArray();

        if (!$ticket) {
            return $this->response->setJSON(['success' => false, 'message' => 'Ticket not found']);
        }

        $messageModel = new \App\Models\TicketMessageModel();
        $messages = $messageModel->getNewMessages($ticketId, $lastMessageId);

        // Format messages untuk response
        $formattedMessages = [];
        foreach ($messages as $message) {
            $formattedMessages[] = [
                'message_id' => $message['message_id'],
                'sender_id' => $message['sender_id'],
                'sender_name' => $message['full_name'],
                'sender_role' => $message['role_name'],
                'message' => $message['message'],
                'created_at' => $message['created_at'],
                'time_ago' => $this->formatTimeAgo($message['created_at']),
                'is_current_user' => $message['sender_id'] == $userId
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'messages' => $formattedMessages,
            'last_message_id' => !empty($messages) ? end($messages)['message_id'] : $lastMessageId
        ]);
    }

    private function sendSupportMessageNotification($ticketId, $senderId, $messageText)
    {
        $db = db_connect();

        // Get ticket info
        $ticket = $db->table('tickets')
            ->where('ticket_id', $ticketId)
            ->get()
            ->getRowArray();

        if (!$ticket) return;

        // Customer sebagai penerima notifikasi
        $customerId = $ticket['customer_id'];

        // Dapatkan nama support agent
        $sender = $db->table('users u')
            ->select('u.full_name, r.role_name')
            ->join('roles r', 'r.role_id = u.role_id')
            ->where('u.user_id', $senderId)
            ->get()
            ->getRowArray();

        $senderName = $sender ? $sender['full_name'] : 'Support Agent';
        $senderRole = $sender ? $sender['role_name'] : 'Support';

        // Buat notifikasi untuk customer
        if ($customerId) {
            $db->table('notifications')->insert([
                'user_id' => $customerId,
                'ticket_id' => $ticketId,
                'title' => $senderRole . ' replied to your ticket #' . ($ticket['ticket_number'] ?? $ticketId),
                'message' => $senderName . ': ' . substr($messageText, 0, 100) . (strlen($messageText) > 100 ? '...' : ''),
                'notification_type' => 'message',
                'is_read' => false,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

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

    public function departmentTicketDetail($ticketId)
    {
        $data = $this->loadCommonData();
        $db = db_connect();

        // Get ticket details
        $ticket = $db->table('tickets t')
            ->select('t.*, p.priority_name, s.status_name, cat.category_name, 
                 u.full_name as customer_name, u.email as customer_email,
                 proj.project_name, proj.project_id,
                 d.department_name, d.department_id,
                 a.full_name as assigned_to_name, a.email as assigned_to_email')
            ->join('priorities p', 'p.priority_id = t.priority_id', 'left')
            ->join('statuses s', 's.status_id = t.status_id', 'left')
            ->join('categories cat', 'cat.category_id = t.category_id', 'left')
            ->join('users u', 'u.user_id = t.customer_id', 'left')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->join('departments d', 'd.department_id = t.department_id', 'left')
            ->join('users a', 'a.user_id = t.assigned_to', 'left')
            ->where('t.ticket_id', $ticketId)
            ->get()
            ->getRowArray();

        if (!$ticket) {
            return redirect()->to('support/ticket_in_progress')->with('error', 'Ticket not found');
        }

        // Get INTERNAL CHAT MESSAGES dengan format yang benar
    $data['messages'] = [];

    if ($db->tableExists('internal_chat_messages')) {
        try {
            $messages = $db->table('internal_chat_messages icm')
                ->select('icm.*, 
                    u.full_name as sender_name, 
                    icm.sender_role')
                ->join('users u', 'u.user_id = icm.sender_id', 'left')
                ->where('icm.ticket_id', $ticketId)
                ->where('icm.department_id', $ticket['department_id'] ?? 0)
                ->orderBy('icm.created_at', 'ASC')
                ->get()
                ->getResultArray();

            // Format messages untuk view
            foreach ($messages as $message) {
                $data['messages'][] = [
                    'message_id' => $message['message_id'],
                    'sender_id' => $message['sender_id'],
                    'sender_name' => $message['sender_name'] ?? 'Unknown',
                    'role_name' => $message['sender_role'] ?? 'User',
                    'message' => $message['message'],
                    'created_at' => $message['created_at'],
                    'is_internal' => true // 🔥 SELALU true untuk internal chat
                ];
            }

            log_message('debug', 'Found ' . count($data['messages']) . ' internal messages');
        } catch (\Exception $e) {
            log_message('error', 'Error loading internal messages: ' . $e->getMessage());
        }
    } else {
        log_message('debug', 'Table internal_chat_messages does not exist');
    }

        // Get department users
        $data['department_users'] = [];
        if ($ticket['department_id']) {
            $data['department_users'] = $db->table('users u')
                ->select('u.user_id, u.full_name, u.email, u.role_id, r.role_name')
                ->join('roles r', 'r.role_id = u.role_id', 'left')
                ->where('u.department_id', $ticket['department_id'])
                ->where('u.is_active', true)
                ->get()
                ->getResultArray();
        }

        // Get support users
        $data['support_users'] = $db->table('users u')
            ->select('u.user_id, u.full_name, u.email, u.role_id, r.role_name')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->where('u.is_active', true)
            ->groupStart()
            ->where('u.role_id', 3) // Support role
            ->orWhere('u.role_id', 1) // Admin role
            ->groupEnd()
            ->get()
            ->getResultArray();

        $data['ticket'] = $ticket;
        $data['ticket_id'] = $ticketId;
        $data['department_id'] = $ticket['department_id'] ?? null;
        $data['title'] = 'Internal Chat - Ticket #' . ($ticket['ticket_number'] ?? $ticketId);

        return view('Support/department_ticket_detail', $data);
    }

    /**
 * Get customer messages (Support ↔ Customer)
 */
public function getCustomerMessages($ticketId)
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }
    
    $userId = session()->get('user_id');
    $db = db_connect();
    
    try {
        // 🔥 PERBAIKAN: HANYA ambil dari ticket_messages untuk percakapan dengan Customer
        $messages = $db->table('ticket_messages tm')
            ->select('tm.*, u.full_name as sender_name, r.role_name')
            ->join('users u', 'u.user_id = tm.sender_id', 'left')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->where('tm.ticket_id', $ticketId)
            ->orderBy('tm.created_at', 'ASC')
            ->get()
            ->getResultArray();
        
        // Format messages untuk response
        $formattedMessages = [];
        foreach ($messages as $msg) {
            $formattedMessages[] = [
                'message_id' => $msg['message_id'],
                'sender_id' => $msg['sender_id'],
                'sender_name' => $msg['sender_name'] ?? 'Unknown',
                'sender_role' => $msg['role_name'] ?? 'User',
                'message' => $msg['message'],
                'created_at' => $msg['created_at'],
                'time_ago' => $this->formatTimeAgo($msg['created_at']),
                'is_current_user' => $msg['sender_id'] == $userId,
                'is_support_to_customer' => ($msg['role_name'] == 'Support' || $msg['role_name'] == 'Admin')
            ];
        }
        
        return $this->response->setJSON([
            'success' => true,
            'messages' => $formattedMessages
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error getting customer messages: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

    public function sendInternalMessage($ticketId)
    {
        log_message('debug', 'sendInternalMessage called for ticket: ' . $ticketId);

        // Allow both AJAX and regular POST
        $message = $this->request->getPost('message');
        $userId = session()->get('user_id');
        $userRole = session()->get('role_name') ?? session()->get('role') ?? 'Support';

        log_message('debug', 'Message data: ' . print_r([
            'ticket_id' => $ticketId,
            'user_id' => $userId,
            'user_role' => $userRole,
            'message' => $message
        ], true));

        if (empty($message)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Message cannot be empty']);
        }

        $db = db_connect();

        try {
            // Get ticket info with department
            $ticket = $db->table('tickets')
                ->select('department_id, ticket_number')
                ->where('ticket_id', $ticketId)
                ->get()
                ->getRowArray();

            log_message('debug', 'Ticket found: ' . print_r($ticket, true));

            if (!$ticket) {
                return $this->response->setJSON(['success' => false, 'message' => 'Ticket not found']);
            }

            if (!$ticket['department_id']) {
                return $this->response->setJSON(['success' => false, 'message' => 'Ticket not assigned to department']);
            }

            // Get sender info sebelum insert
            $sender = $db->table('users u')
                ->select('u.full_name, u.photo_profile, r.role_name')
                ->join('roles r', 'r.role_id = u.role_id')
                ->where('u.user_id', $userId)
                ->get()
                ->getRowArray();

            log_message('debug', 'Sender info: ' . print_r($sender, true));

            $senderName = $sender['full_name'] ?? 'Unknown';
            $senderRoleFromDb = $sender['role_name'] ?? 'User';

            // Gunakan role dari session jika tidak ada di database
            $finalRole = !empty($userRole) ? $userRole : $senderRoleFromDb;

            // Simpan pesan
            $messageData = [
                'ticket_id' => $ticketId,
                'sender_id' => $userId,
                'sender_role' => $finalRole, // Simpan role yang benar
                'department_id' => $ticket['department_id'],
                'message' => $message,
                'is_internal' => true,
                'created_at' => date('Y-m-d H:i:s')
            ];

            log_message('debug', 'Inserting message: ' . print_r($messageData, true));

            $builder = $db->table('internal_chat_messages');
            $builder->insert($messageData);
            $messageId = $db->insertID();

            log_message('debug', 'Message inserted with ID: ' . $messageId);

            if (!$messageId) {
                throw new \Exception('Failed to save message to database');
            }

            // Create notification for other department members
            $this->createInternalNotification($ticketId, $ticket['department_id'], $userId, $message);

            // Format response dengan data yang benar
            $response = [
                'success' => true,
                'message' => 'Internal message sent',
                'data' => [
                    'message_id' => $messageId,
                    'sender_id' => $userId,
                    'sender_name' => $senderName,
                    'sender_role' => $finalRole, // Key yang penting
                    'role_name' => $finalRole, // Key alternatif untuk konsistensi
                    'message' => $message,
                    'created_at' => date('Y-m-d H:i:s'),
                    'time_ago' => 'Just now',
                    'is_current_user' => true,
                    'is_internal' => true
                ]
            ];

            log_message('debug', 'Response: ' . print_r($response, true));

            return $this->response->setJSON($response);
        } catch (\Exception $e) {
            log_message('error', 'Error sending internal message: ' . $e->getMessage());
            log_message('error', 'Trace: ' . $e->getTraceAsString());

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

public function getInternalMessages($ticketId)
{
    $db = db_connect();
    
    // HANYA mengambil dari tabel internal_chat_messages
    $messages = $db->table('internal_chat_messages m')
        ->select('m.*, u.full_name as sender_name, u.photo_profile as sender_photo')
        ->join('users u', 'u.user_id = m.sender_id')
        ->where('m.ticket_id', $ticketId)
        ->orderBy('m.created_at', 'ASC')
        ->get()
        ->getResultArray();

    return $this->response->setJSON([
        'success' => true,
        'messages' => $messages
    ]);
}

    public function getNewInternalMessages($ticketId)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $lastMessageId = $this->request->getGet('last_message_id') ?? 0;
        $userId = session()->get('user_id');
        $db = db_connect();

        try {
            $ticket = $db->table('tickets')
                ->select('department_id')
                ->where('ticket_id', $ticketId)
                ->get()
                ->getRowArray();

            if (!$ticket || !$ticket['department_id']) {
                return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
            }

            if (!$db->tableExists('internal_chat_messages')) {
                return $this->response->setJSON([
                    'success' => true,
                    'messages' => [],
                    'last_message_id' => $lastMessageId
                ]);
            }

            $messages = $db->table('internal_chat_messages icm')
                ->select('icm.*, 
                u.full_name as sender_name, 
                u.photo_profile,
                r.role_name as role_name_db,
                icm.sender_role')
                ->join('users u', 'u.user_id = icm.sender_id', 'left')
                ->join('roles r', 'r.role_id = u.role_id', 'left')
                ->where('icm.ticket_id', $ticketId)
                ->where('icm.department_id', $ticket['department_id'])
                ->where('icm.message_id >', $lastMessageId)
                ->orderBy('icm.created_at', 'ASC')
                ->get()
                ->getResultArray();

            $formattedMessages = [];
            $newLastMessageId = $lastMessageId;

            foreach ($messages as $message) {
                $formattedMessages[] = [
                    'message_id' => $message['message_id'],
                    'sender_id' => $message['sender_id'],
                    'sender_name' => $message['sender_name'] ?? 'Unknown',
                    'role_name' => !empty($message['sender_role'])
                        ? $message['sender_role']
                        : ($message['role_name_db'] ?? 'User'),
                    'message' => $message['message'],
                    'is_internal' => (bool)$message['is_internal'],
                    'created_at' => $message['created_at'],
                    'time_ago' => $this->formatTimeAgo($message['created_at']),
                    'is_current_user' => $message['sender_id'] == $userId
                ];

                if ($message['message_id'] > $newLastMessageId) {
                    $newLastMessageId = $message['message_id'];
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'messages' => $formattedMessages,
                'last_message_id' => $newLastMessageId
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error getting new internal messages: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Create notification for internal chat
     */
    private function createInternalNotification($ticketId, $departmentId, $senderId, $messageText)
    {
        $db = db_connect();

        // Get sender info
        $sender = $db->table('users u')
            ->select('u.full_name, r.role_name')
            ->join('roles r', 'r.role_id = u.role_id')
            ->where('u.user_id', $senderId)
            ->get()
            ->getRowArray();

        $senderName = $sender['full_name'] ?? 'Team Member';
        $senderRole = $sender['role_name'] ?? 'Support';

        // Get department members (exclude sender)
        $members = $db->table('users')
            ->select('user_id')
            ->where('department_id', $departmentId)
            ->where('user_id !=', $senderId)
            ->where('is_active', true)
            ->get()
            ->getResultArray();

        $messagePreview = substr($messageText, 0, 100) . (strlen($messageText) > 100 ? '...' : '');

        // Get ticket info
        $ticket = $db->table('tickets')
            ->select('ticket_number, subject')
            ->where('ticket_id', $ticketId)
            ->get()
            ->getRowArray();

        foreach ($members as $member) {
            $notificationData = [
                'user_id' => $member['user_id'],
                'ticket_id' => $ticketId,
                'title' => 'New Internal Message - Ticket #' . ($ticket['ticket_number'] ?? $ticketId),
                'message' => $senderName . ' (' . $senderRole . '): ' . $messagePreview,
                'notification_type' => 'internal_chat',
                'is_read' => false,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $db->table('notifications')->insert($notificationData);
        }
    }

    public function profile()
    {
        $data = $this->loadCommonData();

        $userId = session()->get('user_id');
        $db = db_connect();

        // Get user details dengan semua relasi
        $data['user_details'] = $db->table('users u')
            ->select('u.*, r.role_name, d.department_name')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->join('departments d', 'd.department_id = u.department_id', 'left')
            ->where('u.user_id', $userId)
            ->get()
            ->getRowArray();

        // Jika user_details tidak ada, gunakan data default
        if (!$data['user_details']) {
            $data['user_details'] = [
                'full_name' => 'Support Agent',
                'email' => 'agent@nexus.com',
                'role_name' => 'Support Agent',
                'department_name' => 'Support Department',
                'created_at' => '2025-01-10 00:00:00'
            ];
        }

        // Format tanggal join
        $joinDate = !empty($data['user_details']['created_at']) ?
            date('F j, Y', strtotime($data['user_details']['created_at'])) :
            'January 10, 2025';

        // Generate Support ID
        $supportId = 'SUP-' . date('Y') . '-' . str_pad($userId, 3, '0', STR_PAD_LEFT);

        // Hitung statistik performa
        $stats = $this->calculateAgentStats($userId);

        // Hitung metrics 30 hari terakhir
        $metrics = $this->calculateLast30DaysMetrics($userId);

        // Tambahkan data ke view
        $data['support_id'] = $supportId;
        $data['join_date'] = $joinDate;
        $data['stats'] = $stats;
        $data['metrics'] = $metrics;
        $data['last_login'] = $this->getLastLoginTime();
        $data['current_status'] = 'Available';

        return view('Support/profile_support', $data);
    }

    private function calculateAgentStats($userId)
    {
        $db = db_connect();
        $now = date('Y-m-d H:i:s');
        $thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));

        // Total tickets yang ditangani
        $totalTickets = $db->table('tickets')
            ->where('assigned_to', $userId)
            ->countAllResults();

        // Tickets resolved
        $resolvedTickets = $db->table('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.assigned_to', $userId)
            ->whereIn('s.status_name', ['Resolved', 'Closed', 'Completed'])
            ->countAllResults();

        // Hitung rata-rata response time (dalam menit)
        $avgResponseTime = '12m'; // Default, bisa dihitung dari ticket_messages

        // Hitung customer satisfaction
        $satisfactionRate = 94; // Default, bisa dihitung dari ratings jika ada

        // Hitung metrics 30 hari terakhir
        $ticketsLast30Days = $db->table('tickets')
            ->where('assigned_to', $userId)
            ->where('created_at >=', $thirtyDaysAgo)
            ->countAllResults();

        $resolvedLast30Days = $db->table('tickets t')
            ->join('statuses s', 's.status_id = t.status_id')
            ->where('t.assigned_to', $userId)
            ->whereIn('s.status_name', ['Resolved', 'Closed', 'Completed'])
            ->where('t.resolved_at >=', $thirtyDaysAgo)
            ->countAllResults();

        // Hitung first contact resolution rate
        $firstContactRate = 78; // Default percentage

        return [
            'total_tickets' => $totalTickets,
            'resolved_tickets' => $resolvedTickets,
            'avg_response_time' => $avgResponseTime,
            'satisfaction_rate' => $satisfactionRate,
            'tickets_last_30_days' => $ticketsLast30Days,
            'resolved_last_30_days' => $resolvedLast30Days,
            'first_contact_rate' => $firstContactRate,
            'resolution_rate' => $totalTickets > 0 ? round(($resolvedTickets / $totalTickets) * 100) : 0,
            'sla_compliance' => 96, // Default, bisa dihitung dari SLA logs
            'quality_score' => 88 // Default, bisa dihitung dari quality metrics
        ];
    }

    private function calculateLast30DaysMetrics($userId)
    {
        $db = db_connect();
        $thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));

        // Tickets handled in last 30 days
        $ticketsHandled = $db->table('tickets')
            ->where('assigned_to', $userId)
            ->where('created_at >=', $thirtyDaysAgo)
            ->countAllResults();

        // Tickets handled in previous 30 days (for comparison)
        $sixtyDaysAgo = date('Y-m-d H:i:s', strtotime('-60 days'));
        $prevTicketsHandled = $db->table('tickets')
            ->where('assigned_to', $userId)
            ->where('created_at >=', $sixtyDaysAgo)
            ->where('created_at <', $thirtyDaysAgo)
            ->countAllResults();

        // Calculate percentage change
        $ticketChangePercent = $prevTicketsHandled > 0 ?
            round((($ticketsHandled - $prevTicketsHandled) / $prevTicketsHandled) * 100) : 0;

        return [
            'tickets_handled' => $ticketsHandled,
            'ticket_change_percent' => $ticketChangePercent,
            'avg_response_time' => '12m',
            'first_contact_rate' => 78,
            'satisfaction_rate' => 94,
            'satisfaction_change_percent' => 3
        ];
    }

    private function getLastLoginTime()
    {
        $userId = session()->get('user_id');
        $db = db_connect();

        // Cari last login dari session atau database
        if (session()->has('last_login')) {
            $lastLogin = session()->get('last_login');
        } else {
            // Coba ambil dari database jika ada kolom last_login
            if ($db->fieldExists('last_login', 'users')) {
                $user = $db->table('users')
                    ->select('last_login')
                    ->where('user_id', $userId)
                    ->get()
                    ->getRowArray();
                $lastLogin = $user ? $user['last_login'] : null;
            } else {
                $lastLogin = null;
            }
        }

        // Format last login time
        if ($lastLogin) {
            $time = strtotime($lastLogin);
            $now = time();
            $diff = $now - $time;

            if ($diff < 60)
                return 'Just now';
            elseif ($diff < 3600)
                return floor($diff / 60) . ' minutes ago';
            elseif ($diff < 86400)
                return floor($diff / 3600) . ' hours ago';
            elseif ($diff < 604800)
                return floor($diff / 86400) . ' days ago';
            else
                return date('F j, Y, g:i A', $time);
        }

        return 'Today, ' . date('g:i A');
    }

    // Tambahkan method untuk update profile
    public function updateProfile()
    {
        $userId = session()->get('user_id');
        $db = db_connect();

        $data = [
            'full_name' => $this->request->getPost('full_name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Jika ada department yang dipilih
        if ($this->request->getPost('department_id')) {
            $data['department_id'] = $this->request->getPost('department_id');
        }

        try {
            $db->table('users')
                ->where('user_id', $userId)
                ->update($data);

            return redirect()->to('/support/profile')->with('success', 'Profile updated successfully');
        } catch (Exception $e) {
            return redirect()->to('/support/profile')->with('error', 'Error updating profile: ' . $e->getMessage());
        }
    }

    public function updateStatus()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $status = $this->request->getPost('status');
        $userId = session()->get('user_id');

        // Simpan status ke session (atau database jika ada kolom status)
        session()->set('agent_status', $status);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    }

    public function ticketDetail($ticketId)
    {
        $data = $this->loadCommonData();

        $db = db_connect();

        // Get ticket details
        $ticket = $db->table('tickets t')
            ->select('t.*, p.priority_name, s.status_name, cat.category_name, 
                 u.full_name as customer_name, u.email as customer_email,
                 proj.project_name, proj.project_id,
                 a.full_name as assigned_to_name, a.email as assigned_to_email')
            ->join('priorities p', 'p.priority_id = t.priority_id', 'left')
            ->join('statuses s', 's.status_id = t.status_id', 'left')
            ->join('categories cat', 'cat.category_id = t.category_id', 'left')
            ->join('users u', 'u.user_id = t.customer_id', 'left')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->join('users a', 'a.user_id = t.assigned_to', 'left')
            ->where('t.ticket_id', $ticketId)
            ->get()
            ->getRowArray();

        if (!$ticket) {
            // Jika tidak ada data di database, gunakan data dummy untuk testing
            $ticket = [
                'ticket_id' => $ticketId,
                'subject' => 'Login issue causing error message',
                'priority_name' => 'High',
                'status_name' => 'Open',
                'category_name' => 'Technical Issue',
                'customer_name' => 'John Smith',
                'customer_email' => 'john.smith@example.com',
                'project_name' => 'Project Alpha',
                'project_id' => 1,
                'assigned_to_name' => null,
                'assigned_to_email' => null,
                'created_at' => '2026-02-19 11:00:00',
                'updated_at' => '2026-02-20 10:30:00'
            ];
        }

        $data['ticket'] = $ticket;
        $data['ticket_id'] = $ticketId;

        try {
            // Get conversation messages
            // PERBAIKAN: Cek apakah tabel ticket_messages ada
            if (!$db->tableExists('ticket_messages')) {
                throw new Exception('Table ticket_messages does not exist');
            }

            // PERBAIKAN: Cek struktur tabel ticket_messages
            $fields = $db->getFieldNames('ticket_messages');

            // Tentukan kolom yang sesuai untuk join
            $userColumn = 'user_id';
            if (!in_array('user_id', $fields)) {
                // Coba cari kolom lain yang mungkin digunakan
                if (in_array('sender_id', $fields)) {
                    $userColumn = 'sender_id';
                } elseif (in_array('created_by', $fields)) {
                    $userColumn = 'created_by';
                } else {
                    // Jika tidak ada kolom user, gunakan data dummy
                    throw new Exception('No user column found in ticket_messages');
                }
            }

            $data['messages'] = $db->table('ticket_messages tm')
                ->select("tm.*, u.full_name, u.role_id, r.role_name")
                ->join('users u', "u.user_id = tm.{$userColumn}", 'left')
                ->join('roles r', 'r.role_id = u.role_id', 'left')
                ->where('tm.ticket_id', $ticketId)
                ->orderBy('tm.created_at', 'ASC')
                ->get()
                ->getResultArray();
        } catch (Exception $e) {
            // Jika ada error, gunakan data dummy
            $data['messages'] = [];
        }

        // Jika tidak ada messages di database, gunakan data dummy
        if (empty($data['messages'])) {
            $data['messages'] = [
                [
                    'message_id' => 1,
                    'message' => 'Hi team, I\'m having trouble accessing the ProjectX dashboard. Every time I try to log in, I receive an error message that says "Access Denied". I\'ve tried clearing my cache and using different browsers, but the issue persists.',
                    'full_name' => 'John Smith',
                    'role_name' => 'Customer',
                    'created_at' => '2026-02-19 11:00:00'
                ],
                [
                    'message_id' => 2,
                    'message' => 'Thank you for reporting this issue, John. We\'ve received your ticket and will look into it immediately. Could you please provide your browser version and operating system?',
                    'full_name' => 'Sarah Johnson',
                    'role_name' => 'Support Agent',
                    'created_at' => '2026-02-19 11:30:00'
                ],
                [
                    'message_id' => 3,
                    'message' => 'Thanks for the quick response! I\'m using Chrome version 120.0.6099.130 on Windows 11.',
                    'full_name' => 'John Smith',
                    'role_name' => 'Customer',
                    'created_at' => '2026-02-20 09:15:00'
                ],
                [
                    'message_id' => 4,
                    'message' => 'Issue identified and resolved. There was a permission configuration issue on our end. The dashboard should now be accessible.',
                    'full_name' => 'Support Team',
                    'role_name' => 'Support Lead',
                    'created_at' => '2026-02-20 10:30:00'
                ]
            ];
        }

        // Get attachments if any
        try {
            if ($db->tableExists('ticket_attachments')) {
                $data['attachments'] = $db->table('ticket_attachments')
                    ->where('ticket_id', $ticketId)
                    ->get()
                    ->getResultArray();
            } else {
                $data['attachments'] = [];
            }
        } catch (Exception $e) {
            $data['attachments'] = [];
        }

        return view('Support/ticket_detail', $data);
    }

    public function ticketSummary($ticketId)
    {
        $data = $this->loadCommonData();

        $db = db_connect();

        // Get ticket details for summary
        $ticket = $db->table('tickets t')
            ->select('t.*, p.priority_name, s.status_name, cat.category_name, 
                     u.full_name as customer_name, u.email as customer_email,
                     proj.project_name, proj.project_id,
                     d.department_name')
            ->join('priorities p', 'p.priority_id = t.priority_id', 'left')
            ->join('statuses s', 's.status_id = t.status_id', 'left')
            ->join('categories cat', 'cat.category_id = t.category_id', 'left')
            ->join('users u', 'u.user_id = t.customer_id', 'left')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->join('departments d', 'd.department_id = t.department_id', 'left')
            ->where('t.ticket_id', $ticketId)
            ->get()
            ->getRowArray();

        if (!$ticket) {
            // Jika tidak ada data di database, gunakan data dummy untuk testing
            $ticket = [
                'ticket_id' => $ticketId,
                'subject' => 'Login issue causing error message',
                'priority_name' => 'High',
                'status_name' => 'Pending Review',
                'category_name' => 'Login Issue',
                'customer_name' => 'John Smith',
                'customer_email' => 'john.smith@gmail.com',
                'project_name' => 'Project Alpha',
                'project_id' => 1,
                'department_name' => null,
                'created_at' => '2026-01-02 10:00:00',
                'updated_at' => '2026-01-02 10:30:00'
            ];
        }

        $data['ticket'] = $ticket;
        $data['ticket_id'] = $ticketId;

        return view('Support/ticket_summary', $data);
    }

public function ticketInProgress()
{
    $data = $this->loadCommonData();

    $userId = session()->get('user_id');
    $db = db_connect();

    // ==================== STATISTIK DINAMIS ====================

    // Get current date for PostgreSQL
    $currentDate = date('Y-m-d');
    $firstDayOfWeek = date('Y-m-d', strtotime('monday this week'));
    $lastDayOfWeek = date('Y-m-d', strtotime('sunday this week'));

    // 🔥 PERUBAHAN: Tickets dengan department (include approved)
    $inProgressCount = $db->table('tickets t')
        ->join('statuses s', 's.status_id = t.status_id')
        ->where('t.department_id IS NOT NULL')
        ->whereIn('s.status_name', ['In Progress']) // Hanya In Progress
        ->countAllResults();

    // Waiting for customer
    $waitingCount = $db->table('tickets t')
        ->join('statuses s', 's.status_id = t.status_id')
        ->where('t.department_id IS NOT NULL')
        ->where('s.status_name', 'Waiting Customer Reply')
        ->countAllResults();

    // 🔥 PERUBAHAN: Approved (this week) - Ticket yang di-approve oleh Support
    $approvedCount = $db->table('tickets t')
        ->where('t.department_id IS NOT NULL')
        ->where('t.internal_status', 'approved')
        ->where("DATE(t.updated_at) >= '{$firstDayOfWeek}'")
        ->where("DATE(t.updated_at) <= '{$lastDayOfWeek}'")
        ->countAllResults();

    $data['stats'] = [
        'in_progress' => $inProgressCount,
        'waiting_customer' => $waitingCount,
        'resolved_week' => $approvedCount // 🔥 Ubah label ke "Approved this week"
    ];

    // ==================== TICKETS DINAMIS ====================

    // 🔥 PERUBAHAN: Get semua ticket dengan department (termasuk yang approved)
    $data['tickets'] = $db->table('tickets t')
        ->select('t.*, 
        p.priority_name, p.priority_id,
        s.status_name, 
        cat.category_name, 
        u.full_name as customer_name, u.email as customer_email,
        proj.project_name, proj.project_id,
        d.department_name, d.department_id,
        a.full_name as assigned_to_name, a.email as assigned_to_email,
        t.internal_status, t.department_resolved_at, t.resolved_at, t.approved_at')
        ->join('priorities p', 'p.priority_id = t.priority_id', 'left')
        ->join('statuses s', 's.status_id = t.status_id', 'left')
        ->join('categories cat', 'cat.category_id = t.category_id', 'left')
        ->join('users u', 'u.user_id = t.customer_id', 'left')
        ->join('projects proj', 'proj.project_id = t.project_id', 'left')
        ->join('departments d', 'd.department_id = t.department_id', 'left')
        ->join('users a', 'a.user_id = t.assigned_to', 'left')
        ->where('t.department_id IS NOT NULL')
        ->whereIn('t.internal_status', ['pending', 'review_needed', 'testing', 'approved', 'rejected', 'reopened', 'reopened_no'])
        ->orderBy("
            CASE t.internal_status 
                WHEN 'pending' THEN 1
                WHEN 'review_needed' THEN 2
                WHEN 'testing' THEN 3
                WHEN 'rejected' THEN 4
                WHEN 'reopened' THEN 5
                WHEN 'reopened_no' THEN 6
                WHEN 'approved' THEN 7
                ELSE 8
            END", 'ASC', false)
        ->orderBy('p.priority_id', 'DESC')
        ->orderBy('t.created_at', 'DESC')
        ->get()
        ->getResultArray();

    // ==================== DEPARTMENT PERFORMANCE ====================

    // Get department statistics
    $departmentStats = $db->table('tickets t')
        ->select('d.department_name,
        COUNT(t.ticket_id) as total_tickets,
        SUM(CASE WHEN t.internal_status IN (\'pending\', \'review_needed\', \'testing\', \'rejected\', \'reopened\', \'reopened_no\') THEN 1 ELSE 0 END) as in_progress,
        SUM(CASE WHEN t.internal_status = \'approved\' THEN 1 ELSE 0 END) as approved,
        EXTRACT(EPOCH FROM AVG(t.approved_at - t.created_at)) / 3600 as avg_approval_hours')
        ->join('departments d', 'd.department_id = t.department_id', 'left')
        ->where('t.department_id IS NOT NULL')
        ->whereIn('t.internal_status', ['pending', 'review_needed', 'testing', 'approved', 'rejected', 'reopened', 'reopened_no'])
        ->groupBy('d.department_id, d.department_name')
        ->orderBy('total_tickets', 'DESC')
        ->limit(5)
        ->get()
        ->getResultArray();

    $data['department_stats'] = $departmentStats;

    return view('Support/ticket_in_progress', $data);
}

    // Add this method to SupportController.php
    public function loadMoreTickets()
    {
        $db = db_connect();

        // Get filter parameters
        $search = $this->request->getPost('search');
        $priority = $this->request->getPost('priority');
        $status = $this->request->getPost('status');
        $department = $this->request->getPost('department');
        $offset = $this->request->getPost('offset') ?? 0;

        $query = $db->table('tickets t')
            ->select('t.*, 
                p.priority_name,
                s.status_name,
                u.full_name as customer_name,
                proj.project_name,
                d.department_name,
                a.full_name as assigned_to_name')
            ->join('priorities p', 'p.priority_id = t.priority_id', 'left')
            ->join('statuses s', 's.status_id = t.status_id', 'left')
            ->join('users u', 'u.user_id = t.customer_id', 'left')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->join('departments d', 'd.department_id = t.department_id', 'left')
            ->join('users a', 'a.user_id = t.assigned_to', 'left')
            ->where('t.assigned_to IS NOT NULL')
            ->limit(10, $offset);

        // Apply filters
        if ($priority !== 'all' && $priority) {
            $query->where('p.priority_name', ucfirst($priority));
        }

        if ($status !== 'all' && $status) {
            $status = str_replace('-', ' ', $status);
            $query->where('s.status_name', ucfirst($status));
        }

        if ($department !== 'all' && $department) {
            $department = str_replace('-', ' ', $department);
            $query->where('d.department_name', ucfirst($department));
        }

        if ($search) {
            $query->groupStart()
                ->like('t.subject', $search)
                ->orLike('u.full_name', $search)
                ->orLike('d.department_name', $search)
                ->orLike('proj.project_name', $search)
                ->groupEnd();
        }

        $tickets = $query->get()->getResultArray();

        // Format tickets for frontend
        $formattedTickets = [];
        foreach ($tickets as $ticket) {
            // Calculate time ago
            $created = new DateTime($ticket['created_at']);
            $now = new DateTime();
            $interval = $now->diff($created);

            if ($interval->days == 0 && $interval->h == 0 && $interval->i < 1) {
                $timeAgo = 'Just now';
            } elseif ($interval->days == 0 && $interval->h == 0) {
                $timeAgo = $interval->i . ' minutes ago';
            } elseif ($interval->days == 0 && $interval->h == 1) {
                $timeAgo = '1 hour ago';
            } elseif ($interval->days == 0) {
                $timeAgo = $interval->h . ' hours ago';
            } elseif ($interval->days == 1) {
                $timeAgo = 'Yesterday';
            } else {
                $timeAgo = $created->format('M d');
            }

            $formattedTickets[] = [
                'ticket_id' => $ticket['ticket_id'],
                'subject' => $ticket['subject'],
                'priority' => strtolower($ticket['priority_name'] ?? 'medium'),
                'status' => strtolower(str_replace(' ', '-', $ticket['status_name'] ?? 'in-progress')),
                'customer_name' => $ticket['customer_name'],
                'project_name' => $ticket['project_name'],
                'department_name' => $ticket['department_name'],
                'assigned_to_name' => $ticket['assigned_to_name'],
                'department_id' => $ticket['department_id'],
                'time_ago' => $timeAgo
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'tickets' => $formattedTickets
        ]);
    }

public function forwardTicket($ticketId)
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }

    $departmentId = $this->request->getPost('department_id');
    $notes = $this->request->getPost('notes');
    $assignToUserId = $this->request->getPost('assign_to_user'); // Opsional: assign ke user tertentu

    $db = db_connect();
    $supportUserId = session()->get('user_id');

    if (!$departmentId) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Department is required'
        ]);
    }

    try {
        // ⚠️ PERBAIKAN UTAMA: Status tetap Open (1) bukan In Progress (2)
        $updateData = [
            'department_id' => $departmentId,
            'status_id' => 1, // ⚠️ TETAP Open, BUKAN In Progress
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Jika langsung assign ke user department tertentu
        if ($assignToUserId) {
            $updateData['assigned_to'] = $assignToUserId;
        } else {
            $updateData['assigned_to'] = null; // Biarkan department memilih
        }

        $db->table('tickets')
            ->where('ticket_id', $ticketId)
            ->update($updateData);

        // Simpan log assignment
        $assignmentData = [
            'ticket_id' => $ticketId,
            'department_id' => $departmentId,
            'assigned_by' => $supportUserId,
            'assigned_to' => $assignToUserId,
            'assignment_type' => $assignToUserId ? 'person' : 'department',
            'assignment_notes' => $notes,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $db->table('ticket_assignments')->insert($assignmentData);

        // Get department name for message
        $department = $db->table('departments')
            ->where('department_id', $departmentId)
            ->get()
            ->getRowArray();

        $deptName = $department ? $department['department_name'] : 'Department';

        // Add system message
        $message = "Ticket forwarded to " . $deptName . " department (Status: Open)";
        if ($notes) {
            $message .= " with notes: " . $notes;
        }

        $db->table('ticket_messages')->insert([
            'ticket_id' => $ticketId,
            'sender_id' => $supportUserId,
            'message' => $message,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // Create notification for department users
        $departmentUsers = $db->table('users')
            ->where('department_id', $departmentId)
            ->where('is_active', true)
            ->get()
            ->getResultArray();

        foreach ($departmentUsers as $user) {
            $db->table('notifications')->insert([
                'user_id' => $user['user_id'],
                'ticket_id' => $ticketId,
                'title' => 'New Ticket Assigned to ' . $deptName,
                'message' => 'Ticket #' . $ticketId . ' has been assigned to your department (Status: Open)',
                'is_read' => false,
                'notification_type' => 'department_assignment',
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Ticket forwarded to ' . $deptName . ' department successfully!',
            'redirect' => base_url('support/ticket_in_progress')
        ]);
    } catch (\Exception $e) {
        log_message('error', 'Error forwarding ticket: ' . $e->getMessage());

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

    public function markTicketResolved($ticketId)
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }

    $db = db_connect();
    $supportUserId = session()->get('user_id');

    log_message('debug', 'Mark resolved called for ticket: ' . $ticketId . ' by user: ' . $supportUserId);

    try {
        // 1. CARI STATUS "Closed" (gunakan nama status sesuai database)
        $status = $db->table('statuses')
            ->where('status_name', 'Closed')
            ->get()
            ->getRowArray();

        if (!$status) {
            // Jika tidak ada status "Closed", coba "Resolved"
            $status = $db->table('statuses')
                ->where('status_name', 'Resolved')
                ->get()
                ->getRowArray();

            if (!$status) {
                // Jika tidak ada juga, ambil status pertama yang bukan Open
                $status = $db->table('statuses')
                    ->where('status_name !=', 'Open')
                    ->orderBy('status_id', 'DESC')
                    ->get()
                    ->getRowArray();

                if (!$status) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'No appropriate status found in database'
                    ]);
                }
            }
        }

        $ticket = $db->table('tickets')
            ->select('tickets.*, statuses.status_name as current_status')
            ->join('statuses', 'statuses.status_id = tickets.status_id', 'left')
            ->where('tickets.ticket_id', $ticketId)
            ->get()
            ->getRowArray();

        if (!$ticket) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Ticket not found'
            ]);
        }

        // 2. UPDATE STATUS TIKET - HAPUS field yang tidak ada
        $updateResult = $db->table('tickets')
            ->where('ticket_id', $ticketId)
            ->update([
                'status_id' => $status['status_id'],
                'resolved_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'assigned_to' => $supportUserId // Pastikan ticket diassign ke support yang close
                // HAPUS 'resolved_by' karena tidak ada di database
            ]);

        if (!$updateResult) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update ticket status'
            ]);
        }

        // 3. TAMBAHKAN PESAN OTOMATIS KE CONVERSATION
        if ($db->tableExists('ticket_messages')) {
            $fields = $db->getFieldNames('ticket_messages');

            $messageData = [
                'ticket_id' => $ticketId,
                'message' => '✅ Ticket has been marked as ' . $status['status_name'] . ' by support team.',
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Add sender berdasarkan struktur tabel
            if (in_array('sender_id', $fields)) {
                $messageData['sender_id'] = $supportUserId;
            } elseif (in_array('user_id', $fields)) {
                $messageData['user_id'] = $supportUserId;
            } elseif (in_array('created_by', $fields)) {
                $messageData['created_by'] = $supportUserId;
            }

            $db->table('ticket_messages')->insert($messageData);
            log_message('debug', 'Auto-message added to ticket conversation');
        }

        // 4. LOG ACTIVITY (opsional)
        if ($db->tableExists('ticket_activities')) {
            $db->table('ticket_activities')->insert([
                'ticket_id' => $ticketId,
                'user_id' => $supportUserId,
                'activity_type' => 'ticket_closed',
                'description' => 'Ticket marked as ' . $status['status_name'],
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        // 5. RETURN SUCCESS
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Ticket successfully marked as ' . $status['status_name'],
            'status_name' => $status['status_name'], // Kirim nama status untuk update UI
            'redirect' => base_url('support/ticket_detail/' . $ticketId)
        ]);
    } catch (Exception $e) {
        log_message('error', 'Error in markTicketResolved: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}
}