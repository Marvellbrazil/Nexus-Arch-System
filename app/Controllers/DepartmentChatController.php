<?php

namespace App\Controllers;

use App\Models\TicketModel;
use App\Models\DepartmentMessageModel;
use App\Models\NotificationModel;

class DepartmentChatController extends BaseController
{
    protected $ticketModel;
    protected $messageModel;
    protected $notificationModel;
    
    public function __construct()
    {
        helper(['url', 'form']);
        $this->ticketModel = new TicketModel();
        $this->messageModel = new DepartmentMessageModel();
        $this->notificationModel = new NotificationModel();
        
        // Check authentication
        if (!session()->get('is_logged_in') && !session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
    }
    
    /**
     * Send message to department
     */
    public function sendDepartmentMessage()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        
        $ticketId = $this->request->getPost('ticket_id');
        $departmentId = $this->request->getPost('department_id');
        $message = $this->request->getPost('message');
        $isInternal = $this->request->getPost('is_internal') == 'true';
        $userId = session()->get('user_id');
        $userRole = session()->get('role_name') ?: session()->get('role');
        
        log_message('debug', 'sendDepartmentMessage called: ' . json_encode([
            'ticketId' => $ticketId,
            'departmentId' => $departmentId,
            'userId' => $userId,
            'userRole' => $userRole,
            'messageLength' => strlen($message)
        ]));
        
        // Validation
        if (empty($ticketId) || empty($departmentId) || empty($message)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing required fields']);
        }
        
        // Check ticket exists
        $ticket = $this->ticketModel->find($ticketId);
        if (!$ticket) {
            return $this->response->setJSON(['success' => false, 'message' => 'Ticket not found']);
        }
        
        // Check access permission
        if (!$this->messageModel->canAccessDepartmentChat($userId, $ticketId, $departmentId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized access']);
        }
        
        try {
            // Save message
            $messageData = [
                'ticket_id' => $ticketId,
                'sender_id' => $userId,
                'message' => $message,
                'is_department_chat' => true,
                'department_id' => $departmentId,
                'is_internal' => $isInternal,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $messageId = $this->messageModel->insert($messageData);
            
            if (!$messageId) {
                throw new \Exception('Failed to save message');
            }
            
            log_message('debug', 'Department message saved with ID: ' . $messageId);
            
            // Update ticket timestamp
            $this->ticketModel->update($ticketId, [
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            // Get complete message data for response
            $newMessage = $this->getMessageData($messageId);
            
            // Send notifications
            $this->sendDepartmentNotification($ticketId, $departmentId, $userId, $message, $isInternal);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Message sent to department',
                'data' => $newMessage,
                'message_id' => $messageId
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in sendDepartmentMessage: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get department messages for a ticket
     */
    public function getDepartmentMessages()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        
        $ticketId = $this->request->getGet('ticket_id');
        $departmentId = $this->request->getGet('department_id');
        $lastMessageId = $this->request->getGet('last_message_id') ?? 0;
        $userId = session()->get('user_id');
        
        // Check access permission
        if (!$this->messageModel->canAccessDepartmentChat($userId, $ticketId, $departmentId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized access']);
        }
        
        // Get messages
        $messages = $this->messageModel->getDepartmentMessages($ticketId, $departmentId);
        
        // Filter by last message ID if provided
        if ($lastMessageId > 0) {
            $messages = array_filter($messages, function($msg) use ($lastMessageId) {
                return $msg['message_id'] > $lastMessageId;
            });
        }
        
        // Format messages for response
        $formattedMessages = [];
        foreach ($messages as $message) {
            $formattedMessages[] = $this->formatMessageForResponse($message, $userId);
        }
        
        // Get last message ID
        $newLastMessageId = !empty($messages) ? max(array_column($messages, 'message_id')) : $lastMessageId;
        
        return $this->response->setJSON([
            'success' => true,
            'messages' => $formattedMessages,
            'last_message_id' => $newLastMessageId,
            'has_more' => false // For pagination
        ]);
    }
    
    /**
     * Get participants in department chat
     */
    public function getDepartmentParticipants()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        
        $ticketId = $this->request->getGet('ticket_id');
        $departmentId = $this->request->getGet('department_id');
        $userId = session()->get('user_id');
        
        // Check access
        if (!$this->messageModel->canAccessDepartmentChat($userId, $ticketId, $departmentId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized access']);
        }
        
        $participants = $this->messageModel->getDepartmentParticipants($ticketId, $departmentId);
        
        return $this->response->setJSON([
            'success' => true,
            'participants' => $participants
        ]);
    }
    
    /**
     * Get recent department conversations for current user
     */
    public function getRecentConversations()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        
        $userId = session()->get('user_id');
        $limit = $this->request->getGet('limit') ?? 10;
        
        $conversations = $this->messageModel->getRecentDepartmentConversations($userId, $limit);
        
        return $this->response->setJSON([
            'success' => true,
            'conversations' => $conversations
        ]);
    }
    
    /**
     * Send notification to department members
     */
    private function sendDepartmentNotification($ticketId, $departmentId, $senderId, $message, $isInternal = false)
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
        $sender = $db->table('users u')
            ->select('u.full_name, r.role_name')
            ->join('roles r', 'r.role_id = u.role_id')
            ->where('u.user_id', $senderId)
            ->get()
            ->getRowArray();
        
        $senderName = $sender ? $sender['full_name'] : 'User';
        $senderRole = $sender ? $sender['role_name'] : 'User';
        
        // Get all department members (exclude sender)
        $departmentMembers = $db->table('users')
            ->select('user_id')
            ->where('department_id', $departmentId)
            ->where('user_id !=', $senderId)
            ->where('is_active', true)
            ->get()
            ->getResultArray();
        
        $messagePreview = substr($message, 0, 100) . (strlen($message) > 100 ? '...' : '');
        $notificationType = $isInternal ? 'internal_department_chat' : 'department_chat';
        
        foreach ($departmentMembers as $member) {
            $notificationData = [
                'user_id' => $member['user_id'],
                'ticket_id' => $ticketId,
                'title' => 'New Department Message',
                'message' => $senderName . ' (' . $senderRole . '): ' . $messagePreview,
                'notification_type' => $notificationType,
                'is_read' => false,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $db->table('notifications')->insert($notificationData);
        }
        
        log_message('debug', 'Notifications sent to ' . count($departmentMembers) . ' department members');
    }
    
    /**
     * Get complete message data
     */
    private function getMessageData($messageId)
    {
        $db = db_connect();
        
        return $db->table('ticket_messages tm')
            ->select('tm.*, 
                u.full_name, 
                u.role_id,
                r.role_name,
                d.department_name,
                d.department_id as sender_department_id')
            ->join('users u', 'u.user_id = tm.sender_id')
            ->join('roles r', 'r.role_id = u.role_id')
            ->join('departments d', 'd.department_id = u.department_id', 'left')
            ->where('tm.message_id', $messageId)
            ->get()
            ->getRowArray();
    }
    
    /**
     * Format message for API response
     */
    private function formatMessageForResponse($message, $currentUserId)
    {
        return [
            'message_id' => $message['message_id'],
            'sender_id' => $message['sender_id'],
            'sender_name' => $message['full_name'] ?? 'Unknown',
            'sender_role' => $message['role_name'] ?? 'User',
            'sender_department' => $message['department_name'] ?? null,
            'message' => $message['message'],
            'is_department_chat' => $message['is_department_chat'] ?? false,
            'department_id' => $message['department_id'] ?? null,
            'is_internal' => $message['is_internal'] ?? false,
            'created_at' => $message['created_at'],
            'time_ago' => $this->formatTimeAgo($message['created_at']),
            'is_current_user' => $message['sender_id'] == $currentUserId
        ];
    }
    
    /**
     * Format time ago string
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