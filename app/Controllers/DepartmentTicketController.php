<?php

namespace App\Controllers;

use App\Models\TicketModel;
use App\Models\DepartmentMessageModel;
use App\Models\DepartmentModel;
use App\Models\UserModel;

class DepartmentTicketController extends BaseController
{
    protected $ticketModel;
    protected $messageModel;
    protected $departmentModel;
    protected $userModel;
    
    public function __construct()
    {
        helper('url');
        
        // Check authentication
        if (!session()->get('is_logged_in') && !session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        
        // Check if user has Support role
        $userRole = session()->get('role') ?? session()->get('role_name');
        if (!in_array($userRole, ['Support', 'Admin'])) {
            return redirect()->to('/login')->with('error', 'Unauthorized access');
        }
        
        $this->ticketModel = new TicketModel();
        $this->messageModel = new DepartmentMessageModel();
        $this->departmentModel = new DepartmentModel();
        $this->userModel = new UserModel();
    }
    
    /**
     * Detail ticket dengan chat department
     */
    public function departmentTicketDetail($ticketId)
    {
        $db = db_connect();
        $userId = session()->get('user_id');
        
        // Get ticket details
        $ticket = $db->table('tickets t')
            ->select('t.*, 
                p.priority_name, p.priority_id,
                s.status_name, s.status_id,
                cat.category_name,
                u.full_name as customer_name, u.email as customer_email,
                proj.project_name, proj.project_code,
                d.department_name, d.department_id,
                d.description as department_description,
                a.full_name as assigned_to_name, a.email as assigned_to_email')
            ->join('priorities p', 'p.priority_id = t.priority_id')
            ->join('statuses s', 's.status_id = t.status_id')
            ->join('categories cat', 'cat.category_id = t.category_id')
            ->join('users u', 'u.user_id = t.customer_id')
            ->join('projects proj', 'proj.project_id = t.project_id', 'left')
            ->join('departments d', 'd.department_id = t.department_id', 'left')
            ->join('users a', 'a.user_id = t.assigned_to', 'left')
            ->where('t.ticket_id', $ticketId)
            ->where('t.assigned_to', $userId) // Hanya ticket yang diassign ke support ini
            ->get()
            ->getRowArray();
        
        if (!$ticket) {
            return redirect()->to('/support/ticket_in_progress')->with('error', 'Ticket not found or unauthorized');
        }
        
        if (!$ticket['department_id']) {
            return redirect()->to('/support/ticket_detail/' . $ticketId)->with('error', 'This ticket is not assigned to any department');
        }
        
        // Load common data
        $data = $this->loadCommonData();
        
        // Get department chat messages
        $messages = $this->messageModel->getDepartmentMessages($ticketId, $ticket['department_id']);
        
        // Format messages
        foreach ($messages as &$message) {
            $created = new \DateTime($message['created_at']);
            $now = new \DateTime();
            $interval = $created->diff($now);
            
            if ($interval->days > 0) {
                $message['time_ago'] = $interval->days . ' days ago';
            } elseif ($interval->h > 0) {
                $message['time_ago'] = $interval->h . ' hours ago';
            } elseif ($interval->i > 0) {
                $message['time_ago'] = $interval->i . ' minutes ago';
            } else {
                $message['time_ago'] = 'Just now';
            }
        }
        
        // Get department members
        $departmentMembers = $db->table('users u')
            ->select('u.user_id, u.full_name, u.email, u.role_id, r.role_name, u.photo_profile')
            ->join('roles r', 'r.role_id = u.role_id')
            ->where('u.department_id', $ticket['department_id'])
            ->where('u.is_active', true)
            ->orderBy('u.full_name')
            ->get()
            ->getResultArray();
        
        // Get department statistics
        $departmentStats = $db->table('tickets')
            ->select('
                COUNT(*) as total_tickets,
                SUM(CASE WHEN status_id = 2 THEN 1 ELSE 0 END) as in_progress,
                SUM(CASE WHEN status_id = 3 THEN 1 ELSE 0 END) as resolved,
                AVG(EXTRACT(EPOCH FROM (resolved_at - created_at))/3600) as avg_resolution_hours
            ')
            ->where('department_id', $ticket['department_id'])
            ->where('resolved_at IS NOT NULL')
            ->get()
            ->getRowArray();
        
        $data['ticket'] = $ticket;
        $data['messages'] = $messages;
        $data['department_members'] = $departmentMembers;
        $data['department_stats'] = $departmentStats;
        $data['ticket_id'] = $ticketId;
        $data['department_id'] = $ticket['department_id'];
        $data['title'] = 'Department Chat - Ticket #' . ($ticket['ticket_number'] ?? $ticketId);
        
        return view('Support/department_ticket_detail', $data);
    }
    
    /**
     * Send message to department
     */
    public function sendDepartmentMessage($ticketId)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        
        $message = $this->request->getPost('message');
        $isInternal = $this->request->getPost('is_internal') == 'true';
        $userId = session()->get('user_id');
        
        // Get ticket info
        $db = db_connect();
        $ticket = $db->table('tickets')
            ->select('department_id, assigned_to')
            ->where('ticket_id', $ticketId)
            ->where('assigned_to', $userId)
            ->get()
            ->getRowArray();
        
        if (!$ticket) {
            return $this->response->setJSON(['success' => false, 'message' => 'Ticket not found or unauthorized']);
        }
        
        if (!$ticket['department_id']) {
            return $this->response->setJSON(['success' => false, 'message' => 'Ticket not assigned to department']);
        }
        
        try {
            // Save message
            $messageData = [
                'ticket_id' => $ticketId,
                'sender_id' => $userId,
                'message' => $message,
                'is_department_chat' => 1,
                'department_id' => $ticket['department_id'],
                'is_internal' => $isInternal,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $db->table('ticket_messages')->insert($messageData);
            $messageId = $db->insertID();
            
            // Update ticket timestamp
            $db->table('tickets')
                ->where('ticket_id', $ticketId)
                ->update(['updated_at' => date('Y-m-d H:i:s')]);
            
            // Get complete message data
            $newMessage = $db->table('ticket_messages tm')
                ->select('tm.*, u.full_name, u.role_id, r.role_name')
                ->join('users u', 'u.user_id = tm.sender_id')
                ->join('roles r', 'r.role_id = u.role_id')
                ->where('tm.message_id', $messageId)
                ->get()
                ->getRowArray();
            
            // Create notification for department members
            $this->createDepartmentNotification($ticketId, $ticket['department_id'], $userId, $message, $isInternal);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Message sent to department',
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
     * Get new department messages
     */
    public function getNewDepartmentMessages($ticketId)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        
        $lastMessageId = $this->request->getGet('last_message_id') ?? 0;
        $userId = session()->get('user_id');
        
        // Verify ticket access
        $db = db_connect();
        $ticket = $db->table('tickets')
            ->select('department_id')
            ->where('ticket_id', $ticketId)
            ->where('assigned_to', $userId)
            ->get()
            ->getRowArray();
        
        if (!$ticket || !$ticket['department_id']) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
        
        // Get new messages
        $messages = $db->table('ticket_messages tm')
            ->select('tm.*, u.full_name, u.role_id, r.role_name')
            ->join('users u', 'u.user_id = tm.sender_id')
            ->join('roles r', 'r.role_id = u.role_id')
            ->where('tm.ticket_id', $ticketId)
            ->where('tm.is_department_chat', 1)
            ->where('tm.department_id', $ticket['department_id'])
            ->where('tm.message_id >', $lastMessageId)
            ->orderBy('tm.created_at', 'ASC')
            ->get()
            ->getResultArray();
        
        // Format messages for response
        $formattedMessages = [];
        foreach ($messages as $msg) {
            $formattedMessages[] = [
                'message_id' => $msg['message_id'],
                'sender_id' => $msg['sender_id'],
                'sender_name' => $msg['full_name'],
                'sender_role' => $msg['role_name'],
                'message' => $msg['message'],
                'is_internal' => $msg['is_internal'],
                'created_at' => $msg['created_at'],
                'time_ago' => $this->formatTimeAgo($msg['created_at']),
                'is_current_user' => $msg['sender_id'] == $userId
            ];
        }
        
        $newLastMessageId = !empty($messages) ? end($messages)['message_id'] : $lastMessageId;
        
        return $this->response->setJSON([
            'success' => true,
            'messages' => $formattedMessages,
            'last_message_id' => $newLastMessageId
        ]);
    }
    
    /**
     * Update ticket status from department chat
     */
    public function updateDepartmentTicketStatus($ticketId)
    {
        $statusId = $this->request->getPost('status_id');
        $notes = $this->request->getPost('notes');
        $userId = session()->get('user_id');
        
        // Verify access
        $db = db_connect();
        $ticket = $db->table('tickets')
            ->where('ticket_id', $ticketId)
            ->where('assigned_to', $userId)
            ->get()
            ->getRowArray();
        
        if (!$ticket) {
            return redirect()->back()->with('error', 'Ticket not found or unauthorized');
        }
        
        // Update status
        $db->table('tickets')
            ->where('ticket_id', $ticketId)
            ->update([
                'status_id' => $statusId,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        
        // Add message about status change
        $statusName = $db->table('statuses')
            ->where('status_id', $statusId)
            ->get()
            ->getRowArray();
        
        $message = "Ticket status updated to " . ($statusName['status_name'] ?? 'Unknown');
        if ($notes) {
            $message .= ": " . $notes;
        }
        
        $db->table('ticket_messages')->insert([
            'ticket_id' => $ticketId,
            'sender_id' => $userId,
            'message' => $message,
            'is_department_chat' => 1,
            'department_id' => $ticket['department_id'],
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        return redirect()->back()->with('success', 'Ticket status updated');
    }
    
    /**
     * Load common data for views
     */
    private function loadCommonData()
    {
        $userId = session()->get('user_id');
        $db = db_connect();
        
        // Get user details
        $user = $db->table('users u')
            ->select('u.*, r.role_name, d.department_name')
            ->join('roles r', 'r.role_id = u.role_id')
            ->join('departments d', 'd.department_id = u.department_id', 'left')
            ->where('u.user_id', $userId)
            ->get()
            ->getRowArray();
        
        // Get unread notifications count
        $unreadCount = $db->table('notifications')
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->countAllResults();
        
        return [
            'title' => 'Department Ticket Detail',
            'user_id' => $userId,
            'role_name' => session()->get('role_name') ?? session()->get('role'),
            'user_details' => $user,
            'unread_notifications' => $unreadCount
        ];
    }
    
    /**
     * Create notification for department members
     */
    private function createDepartmentNotification($ticketId, $departmentId, $senderId, $messageText, $isInternal = false)
    {
        $db = db_connect();
        
        // Get ticket info
        $ticket = $db->table('tickets')
            ->select('ticket_number, subject')
            ->where('ticket_id', $ticketId)
            ->get()
            ->getRowArray();
        
        if (!$ticket) return;
        
        // Get sender info
        $sender = $db->table('users')
            ->select('full_name')
            ->where('user_id', $senderId)
            ->get()
            ->getRowArray();
        
        $senderName = $sender['full_name'] ?? 'Support Agent';
        
        // Get department members (exclude sender)
        $members = $db->table('users')
            ->select('user_id')
            ->where('department_id', $departmentId)
            ->where('user_id !=', $senderId)
            ->where('is_active', true)
            ->get()
            ->getResultArray();
        
        $messagePreview = substr($messageText, 0, 100) . (strlen($messageText) > 100 ? '...' : '');
        
        foreach ($members as $member) {
            $notificationData = [
                'user_id' => $member['user_id'],
                'ticket_id' => $ticketId,
                'title' => 'New Message in Department Chat',
                'message' => $senderName . ': ' . $messagePreview,
                'notification_type' => $isInternal ? 'internal_department_chat' : 'department_chat',
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
}