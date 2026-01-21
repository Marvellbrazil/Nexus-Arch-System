<?php

namespace App\Controllers;

use App\Models\ProjectModel;
use App\Models\UserModel;
use App\Models\TicketModel;
use App\Models\NotificationModel;
use App\Models\PriorityModel;
use App\Models\StatusModel;
use App\Models\TicketMessageModel;
use App\Models\TicketAttachmentModel;
use App\Models\CategoryModel;
use App\Models\DepartmentModel;
use App\Models\CategoryDepartmentMapping;
use CodeIgniter\I18n\Time;

class CustomerController extends BaseController
{
    private $userId;
    protected $userModel;
    private $ticketModel;
    private $projectModel;
    private $notificationModel;
    private $priorityModel;
    private $statusModel;
    private $ticketMessageModel;
    private $ticketAttachmentModel;
    private $categoryModel;
    private $departmentModel;
    private $categoryDepartmentMapping;
    private $db;
    private $currentTime;

    public function __construct()
    {
        $this->checkRole(['Customer']);
        $this->userId = session()->get('user_id');
        $this->db = db_connect();

        // Load the models
        $this->userModel = new UserModel();
        $this->ticketModel = new TicketModel();
        $this->projectModel = new ProjectModel();
        $this->notificationModel = new NotificationModel();
        $this->priorityModel = new PriorityModel();
        $this->statusModel = new StatusModel();
        $this->ticketMessageModel = new TicketMessageModel();
        $this->ticketAttachmentModel = new TicketAttachmentModel();
        $this->categoryModel = new CategoryModel();
        $this->departmentModel = new DepartmentModel();
        $this->categoryDepartmentMapping = new CategoryDepartmentMapping();

        // load common data
        $this->currentTime = Time::now(env('app.timezone'))->toDateTimeString();
    }

    private function getTicketStats()
    {
        return [
            'total_tickets' => $this->ticketModel->getTotalTickets($this->userId),
            'total_tickets_per_week' => $this->ticketModel->getTicketsPerWeek($this->userId),
            'open_tickets' => $this->ticketModel->getTicketCountByStatus($this->userId, 'Open'),
            'in_progress_tickets' => $this->ticketModel->getTicketCountByStatus($this->userId, 'In Progress'),
            'resolved_tickets' => $this->ticketModel->getTicketCountByStatus($this->userId, 'Resolved'),
            'cancelled_tickets' => $this->ticketModel->getTicketCountByStatus($this->userId, 'Cancelled'),
            'tickets_this_month' => $this->ticketModel->getTicketsThisMonth($this->userId),
        ];
    }

    private function getTicketCountByStatus($statusName)
    {
        // This function is now part of the TicketModel, named getTicketCountByStatus.
        // It is kept here to avoid breaking other parts of the controller during incremental refactoring.
        // It will be removed once the refactoring is complete.
        return $this->ticketModel->getTicketCountByStatus($this->userId, $statusName);
    }
// Di dalam class CustomerController, tambahkan:

public function sendMessage()
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }
    
    $ticketId = $this->request->getPost('ticket_id');
    $message = $this->request->getPost('message');
    $userId = session()->get('user_id');
    
    log_message('debug', 'sendMessage called - Ticket: ' . $ticketId . ', User: ' . $userId);
    
    if (empty($message)) {
        return $this->response->setJSON(['success' => false, 'message' => 'Message cannot be empty']);
    }
    
    $db = db_connect();
    
    // Validasi ticket ownership
    $ticket = $db->table('tickets')
        ->where('ticket_id', $ticketId)
        ->where('customer_id', $userId)
        ->get()
        ->getRowArray();
    
    if (!$ticket) {
        log_message('error', 'Ticket not found or unauthorized - Ticket: ' . $ticketId . ', User: ' . $userId);
        return $this->response->setJSON(['success' => false, 'message' => 'Ticket not found or unauthorized']);
    }
    
    try {
        // Simpan pesan - PERBAIKAN DISINI
        log_message('debug', 'Inserting message into ticket_messages');
        
        // Gunakan query builder untuk menghindari SQL syntax error
        $db->table('ticket_messages')->insert([
            'ticket_id' => $ticketId,
            'sender_id' => $userId,
            'message' => $message,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        $messageId = $db->insertID();
        
        if (!$messageId) {
            log_message('error', 'Failed to get insert ID');
            throw new \Exception('Failed to save message - no insert ID returned');
        }
        
        log_message('debug', 'Message saved with ID: ' . $messageId);
        
        // Update ticket timestamp
        $db->table('tickets')
            ->where('ticket_id', $ticketId)
            ->update(['updated_at' => date('Y-m-d H:i:s')]);
        
        // Get complete message data
        $newMessage = $db->table('ticket_messages tm')
            ->select('tm.*, u.full_name, u.photo_profile, r.role_name')
            ->join('users u', 'u.user_id = tm.sender_id', 'left')
            ->join('roles r', 'r.role_id = u.role_id', 'left')
            ->where('tm.message_id', $messageId)
            ->get()
            ->getRowArray();
        
        // Buat notifikasi untuk support/department
        $this->sendCustomerMessageNotification($ticketId, $userId, $message);
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => $newMessage
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error in sendMessage: ' . $e->getMessage());
        log_message('error', 'Error trace: ' . $e->getTraceAsString());
        
        // Dapatkan error database jika ada
        $dbError = $db->error();
        if ($dbError) {
            log_message('error', 'Database error: ' . print_r($dbError, true));
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage() . ' - DB Error: ' . ($dbError['message'] ?? 'Unknown')
        ]);
    }
}

public function getNewMessages()
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }
    
    $ticketId = $this->request->getGet('ticket_id');
    $lastMessageId = $this->request->getGet('last_message_id') ?? 0;
    
    // Validasi ticket ownership
    $userId = session()->get('user_id');
    $db = db_connect();
    
    $ticket = $db->table('tickets')
        ->where('ticket_id', $ticketId)
        ->where('customer_id', $userId)
        ->get()
        ->getRowArray();
    
    if (!$ticket) {
        return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
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

private function sendCustomerMessageNotification($ticketId, $senderId, $messageText)
{
    $db = db_connect();
    
    // Get ticket info
    $ticket = $db->table('tickets')
        ->where('ticket_id', $ticketId)
        ->get()
        ->getRowArray();
    
    if (!$ticket) return;
    
    // Tentukan penerima notifikasi (Support/Department)
    $recipients = [];
    
    // Support yang assign (jika ada)
    if ($ticket['assigned_to'] && $ticket['assigned_to'] != $senderId) {
        $recipients[] = $ticket['assigned_to'];
    }
    
    // Department (jika ada)
    if ($ticket['department_id']) {
        $deptUsers = $db->table('users')
            ->select('user_id')
            ->where('department_id', $ticket['department_id'])
            ->where('user_id !=', $senderId)
            ->get()
            ->getResultArray();
        
        foreach ($deptUsers as $user) {
            $recipients[] = $user['user_id'];
        }
    }
    
    // Jika tidak ada yang diassign, notifikasi ke semua support
    if (empty($recipients)) {
        $supportUsers = $db->table('users u')
            ->select('u.user_id')
            ->join('roles r', 'r.role_id = u.role_id')
            ->where('r.role_name', 'Support')
            ->where('u.user_id !=', $senderId)
            ->where('u.is_active', true)
            ->get()
            ->getResultArray();
        
        foreach ($supportUsers as $user) {
            $recipients[] = $user['user_id'];
        }
    }
    
    // Buat notifikasi
    $sender = $db->table('users')
        ->select('full_name')
        ->where('user_id', $senderId)
        ->get()
        ->getRowArray();
    
    $senderName = $sender ? $sender['full_name'] : 'Customer';
    
    foreach (array_unique($recipients) as $recipientId) {
        $db->table('notifications')->insert([
            'user_id' => $recipientId,
            'ticket_id' => $ticketId,
            'title' => 'Customer replied to ticket #' . ($ticket['ticket_number'] ?? $ticketId),
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
    
    if ($diff < 60) {
        return 'Just now';
    } elseif ($diff < 3600) {
        $minutes = floor($diff / 60);
        return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } else {
        return date('M d, Y', $time);
    }
}
// Di SupportController.php dan CustomerController.php
public function uploadAttachment()
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
    }
    
    $ticketId = $this->request->getPost('ticket_id');
    $file = $this->request->getFile('file');
    $userId = session()->get('user_id');
    
    if (!$file || !$file->isValid()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid file']);
    }
    
    // Validasi file size (max 10MB)
    if ($file->getSize() > 10 * 1024 * 1024) {
        return $this->response->setJSON(['success' => false, 'message' => 'File size exceeds 10MB limit']);
    }
    
    // Validasi file type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 
                    'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'text/plain'];
    
    if (!in_array($file->getClientMimeType(), $allowedTypes)) {
        return $this->response->setJSON(['success' => false, 'message' => 'File type not allowed']);
    }
    
    $uploadPath = WRITEPATH . 'uploads/tickets/' . $ticketId . '/';
    
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }
    
    $newName = $file->getRandomName();
    
    if ($file->move($uploadPath, $newName)) {
        $db = db_connect();
        
        // Save to ticket_attachments
        $db->table('ticket_attachments')->insert([
            'ticket_id' => $ticketId,
            'uploaded_by' => $userId,
            'file_name' => $file->getClientName(),
            'file_path' => 'uploads/tickets/' . $ticketId . '/' . $newName,
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'File uploaded successfully',
            'file_name' => $file->getClientName()
        ]);
    }
    
    return $this->response->setJSON(['success' => false, 'message' => 'Failed to upload file']);
}
    public function dashboard()
    {
        $data = $this->loadCommonData();
        $data['stats'] = $this->getTicketStats();
        $data['recent_tickets'] = $this->ticketModel->getRecentTickets($this->userId, 5);
        $data['projects'] = $this->projectModel->getProjectsForCustomerDashboard($this->userId);
        $data['notifications'] = $this->notificationModel->getNotifications($this->userId);

        $user = $this->userModel->getBasicUserDetails($this->userId);
        $data['user'] = [
            'full_name' => $user['full_name'],
            'email' => $user['email'],
            'role' => session()->get('role_name'),
        ];

        $hour = date('H');
        if ($hour >= 5 && $hour < 12) {
            $time = "Morning";
        } elseif ($hour >= 12 && $hour < 17) {
            $time = "Afternoon";
        } elseif ($hour >= 17 && $hour < 21) {
            $time = "Evening";
        } else {
            $time = "Night";
        }
        $data['current_time'] = $time;

        return view('Customer/dashboard', ['data' => $data]);
    }

    private function getQueryString($excludeParams = [])
    {
        $request = \Config\Services::request();
        $queryParams = $request->getGet();

        foreach ($excludeParams as $param) {
            unset($queryParams[$param]);
        }

        return $queryParams ? '&' . http_build_query($queryParams) : '';
    }

    public function myTickets()
    {
        $data = $this->loadCommonData();
        $data['stats'] = $this->getTicketStats();

        // Get tickets with pagination and dynamic filters
        $perPage = 10; // Items per page
        $page = $this->request->getGet('page') ?? 1;
        $offset = ($page - 1) * $perPage;

        $filters = [
            'search' => $this->request->getGet('search'),
            'status' => $this->request->getGet('status'),
            'priority' => $this->request->getGet('priority'),
            'project' => $this->request->getGet('project'),
            'sort' => $this->request->getGet('sort'),
        ];

        $totalRows = $this->ticketModel->countPaginatedTickets($this->userId, $filters);
        $data['tickets'] = $this->ticketModel->getPaginatedTickets($this->userId, $filters, $perPage, $offset);

        // Format tickets for frontend
        foreach ($data['tickets'] as &$ticket) {
            $ticket = $this->formatTicketForDisplay($ticket);
        }

        // Pagination data
        $pager = service('pager');
        $data['pager'] = $pager->makeLinks($page, $perPage, $totalRows, 'default_full');
        $data['total_tickets'] = $totalRows;
        $data['showing_count'] = count($data['tickets']);
        $data['current_page'] = $page;
        $data['total_pages'] = ceil($totalRows / $perPage);

        // Get filter options
        $data['projects'] = $this->projectModel->getActiveProjectsForCustomer($this->userId);
        $data['priorities'] = $this->priorityModel->getPriorities();
        $data['statuses'] = $this->statusModel->getStatuses();

        // Pass filter values to view
        $data['current_filters'] = $filters;

        $data['query_string_helper'] = function ($excludeParams = []) {
            return $this->getQueryString($excludeParams);
        };

        return view('Customer/my_tickets', ['data' => $data]);
    }

    // Helper method to format ticket data
    private function formatTicketForDisplay($ticket)
    {
        // Format ID
        $ticket['id'] = '#' . $ticket['display_id'] ?? $ticket['ticket_number'];

        // Map priority to colors
        $priorityColors = [
            'Urgent' => 'bg-red-100 text-red-800',
            'High' => 'bg-orange-100 text-orange-800',
            'Medium' => 'bg-yellow-100 text-yellow-800',
            'Low' => 'bg-blue-100 text-blue-800',
            'Normal' => 'bg-gray-100 text-gray-800'
        ];

        $ticket['priorityColor'] = $priorityColors[$ticket['priority_name']] ?? 'bg-gray-100 text-gray-800';
        $ticket['priority_value'] = array_search(
            $ticket['priority_name'],
            ['Low', 'Medium', 'High', 'Urgent']
        ) + 1;

        // Map status to colors
        $statusColors = [
            'Open' => 'bg-gray-100 text-gray-800',
            'In Progress' => 'bg-blue-100 text-blue-800',
            'Resolved' => 'bg-green-100 text-green-800',
            'Closed' => 'bg-purple-100 text-purple-800',
            'Cancelled' => 'bg-red-100 text-red-800'
        ];

        $ticket['statusColor'] = $statusColors[$ticket['status_name']] ?? 'bg-gray-100 text-gray-800';
        $ticket['status_key'] = strtolower(str_replace(' ', '-', $ticket['status_name']));

        // Format time
        $createdTime = strtotime($ticket['timestamp']);
        $ticket['time'] = $this->formatTimeAgo($createdTime);
        $ticket['timestamp'] = $createdTime;

        // Project key for filtering
        $ticket['project_key'] = strtolower(str_replace(' ', '-', $ticket['project_name'] ?? ''));

        return $ticket;
    }

    
    
    public function ticketDetail($id)
{
    $data = $this->loadCommonData();
    $ticket = $this->ticketModel->getTicketDetails($id, $this->userId);

    if (!$ticket) {
        return redirect()->to('/customer/my_tickets')->with('error', 'Ticket not found');
    }

    $data['ticket'] = $ticket;
    $data['ticket_id'] = $id; // Tambahkan ini
    $data['messages'] = $this->ticketMessageModel->getMessagesForTicket($id);
    $data['attachments'] = $this->ticketAttachmentModel->getAttachmentsForTicket($id);
    
    // Tambahkan role ke data yang dikirim ke view
    $data['user_role'] = session()->get('role'); // <-- INI PENTING

    return view('Customer/ticket_detail', ['data' => $data]);
}

    public function projectDetail($projectId)
    {
        $data = $this->loadCommonData();

        $data['project'] = $this->projectModel->getProjectDetailsForCustomer($projectId, $this->userId);

        if (!$data['project']) {
            return redirect()->to('/customer/dashboard')->with('error', 'Project not found');
        }

        $data['tickets'] = $this->ticketModel->getTicketsByProject($projectId, $this->userId);
        $data['recent_activity'] = $this->ticketMessageModel->getRecentActivityForProject($projectId, $this->userId);
        $data['team_members'] = $this->userModel->getProjectTeamMembers($projectId, $this->userId);

        return view('Customer/project_detail', $data);
    }

    // Di method updateProfile() - REPLACE dengan ini:

    public function updateProfile()
    {
        // Debug: Log semua input
        $postData = $this->request->getPost();
        log_message('debug', 'POST Data: ' . print_r($postData, true));

        // Validation rules
        $validationRules = [
            'full_name' => 'required|min_length[3]|max_length[100]',
            'phone_number' => 'permit_empty|min_length[10]|max_length[20]',
            'current_password' => 'permit_empty',
            'new_password' => 'permit_empty|min_length[6]',
            'confirm_password' => 'matches[new_password]',
        ];

        // Validate
        if (!$this->validate($validationRules)) {
            $errors = $this->validator->getErrors();
            log_message('error', 'Validation errors: ' . print_r($errors, true));
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        // Get current user
        $userModel = new UserModel();
        $currentUser = $userModel->find($this->userId);

        if (!$currentUser) {
            return redirect()->back()->with('error', 'User not found');
        }

        // Prepare update data - PERHATIKAN NAMA FIELD DATABASE
        $updateData = [
            'full_name' => $postData['full_name'],
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Add phone number if provided
        if (!empty($postData['phone_number'])) {
            $updateData['phone_number'] = $postData['phone_number'];
        } else {
            $updateData['phone_number'] = null; // Set to null if empty
        }

        // Handle password change
        if (!empty($postData['current_password'])) {
            if (!password_verify($postData['current_password'], $currentUser['password'])) {
                log_message('debug', 'Password verification failed');
                return redirect()->back()->with('error', 'Current password is incorrect');
            }

            // Check new password
            if (empty($postData['new_password'])) {
                return redirect()->back()->with('error', 'New password is required');
            }

            if ($postData['new_password'] !== $postData['confirm_password']) {
                return redirect()->back()->with('error', 'New password and confirmation do not match');
            }

            // Hash new password
            $updateData['password'] = password_hash($postData['new_password'], PASSWORD_DEFAULT);
            log_message('debug', 'Password will be updated');
        }

        // Handle file upload
        $photo = $this->request->getFile('photo_profile');
        log_message('debug', 'File upload check: ' . ($photo ? 'File exists' : 'No file'));

        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            log_message('debug', 'File is valid: ' . $photo->getName());

            // Define upload directory
            $uploadDir = FCPATH . 'uploads/profile/'; // FCPATH = public/

            // Create directory if not exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Delete old photo if exists
            if (!empty($currentUser['photo_profile'])) {
                $oldPhotoPath = WRITEPATH . $currentUser['photo_profile'];
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                    log_message('debug', 'Old photo deleted: ' . $oldPhotoPath);
                }
            }

            // Generate new filename
            $newName = $photo->getRandomName();

            // Move file
            if ($photo->move($uploadDir, $newName)) {
                $updateData['photo_profile'] = 'uploads/profile/' . $newName;
                log_message('debug', 'File uploaded: ' . $updateData['photo_profile']);
            } else {
                log_message('error', 'File move failed: ' . $photo->getErrorString());
                return redirect()->back()->with('error', 'Failed to upload profile photo');
            }
        }

        // Debug before update
        log_message('debug', 'Final update data: ' . print_r($updateData, true));

        // Update database
        try {
            $result = $userModel->update($this->userId, $updateData);
            log_message('debug', 'Update result: ' . ($result ? 'true' : 'false'));

            if ($result) {
                // Get updated user data
                $updatedUser = $userModel->find($this->userId);
                log_message('debug', 'Updated user phone: ' . ($updatedUser['phone_number'] ?? 'null'));

                // Update session
                session()->set([
                    'full_name' => $updatedUser['full_name'],
                    'photo_profile' => $updatedUser['photo_profile'] ?? null
                ]);

                // Also update phone in session if needed
                if (isset($updatedUser['phone_number'])) {
                    session()->set('phone_number', $updatedUser['phone_number']);
                }

                log_message('debug', 'Profile updated successfully');
                return redirect()->to('/customer/profile')->with('success', 'Profile updated successfully!');
            } else {
                $error = $userModel->errors();
                log_message('error', 'Model errors: ' . print_r($error, true));

                // Check database error
                $dbError = $this->db->error();
                if ($dbError) {
                    log_message('error', 'Database error: ' . print_r($dbError, true));
                }

                return redirect()->back()->with('error', 'Failed to update profile. Please try again.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception: ' . $e->getMessage());
            log_message('error', 'Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function profile()
    {
        $data = $this->loadCommonData();
        $data['stats'] = $this->getTicketStats();

        // Get user details
        $data['user_details'] = $this->userModel->getUserDetails($this->userId);

        // Get assigned projects with ticket counts
        $data['assigned_projects'] = $this->projectModel->getAssignedProjectsWithTicketCount($this->userId);

        // Calculate ticket statistics for progress bars
        $totalTickets = $data['stats']['total_tickets'];
        $data['ticket_percentages'] = [
            'open' => $totalTickets > 0 ? round(($data['stats']['open_tickets'] / $totalTickets) * 100, 1) : 0,
            'in_progress' => $totalTickets > 0 ? round(($data['stats']['in_progress_tickets'] / $totalTickets) * 100, 1) : 0,
            'resolved' => $totalTickets > 0 ? round(($data['stats']['resolved_tickets'] / $totalTickets) * 100, 1) : 0,
            'closed' => $totalTickets > 0 ? round(($data['stats']['cancelled_tickets'] / $totalTickets) * 100, 1) : 0,
        ];

        // Format last login time
        if (!empty($data['user_details']['last_login'])) {
            $lastLogin = new \DateTime($data['user_details']['last_login']);
            $now = new \DateTime();
            $interval = $lastLogin->diff($now);

            if ($interval->days == 0) {
                $data['last_login_text'] = 'Today, ' . $lastLogin->format('h:i A');
            } elseif ($interval->days == 1) {
                $data['last_login_text'] = 'Yesterday, ' . $lastLogin->format('h:i A');
            } else {
                $data['last_login_text'] = $lastLogin->format('M d, Y, h:i A');
            }
        } else {
            $data['last_login_text'] = 'Never logged in';
        }

        return view('Customer/profile_customer', ['data' => $data]);
    }
    public function notifications()
    {
        $data = $this->loadCommonData();

        $data['notifications'] = $this->notificationModel->getNotificationsForCustomer($this->userId);
        $data['stats'] = $this->notificationModel->getNotificationStatsForCustomer($this->userId);

        return view('Customer/notifications', ['data' => $data]);
    }

    // Di dalam CustomerController.php, tambahkan method ini:

    private function getAssignedProjects()
    {
        // return $this->db->table('projects p')
        //     ->select('p.project_id, p.project_code, p.project_name, p.description, p.created_at, 
        //         COUNT(t.ticket_id) as ticket_count')
        //     ->join('tickets t', 't.project_id = p.project_id', 'left')
        //     ->join('project_assignments pa', 'pa.project_id = p.project_id', 'left')
        //     ->where('pa.user_id', $this->userId)
        //     ->where('p.is_active', value: true)
        //     ->groupBy('p.project_id, p.project_code, p.project_name, p.description, p.created_at')
        //     ->orderBy('p.project_id', 'ASC')
        //     ->get()
        //     ->getResultArray();

        $projects = new ProjectModel();

        return $projects->getAssignedProjectsWithTicketCount($this->userId);
    }

    // Update method createTicket():
    public function createTicket()
    {
        $data = $this->loadCommonData();

        // Get projects assigned to this customer
        $data['projects'] = $this->getAssignedProjects();

        $data['categories'] = $this->categoryModel->getAllCategories();
        $data['priorities'] = $this->priorityModel->getAllPriorities();
        // $data['departments'] = $this->departmentModel->getAllDepartments();

        return view('Customer/create_ticket', ['data' => $data]);
    }

    // Tambahkan method untuk menangani form submission:
    public function processCreateTicket()
    {
        // Ambil data sesuai atribut 'name' di view create_ticket.php
        $projectId = $this->request->getPost('project_id'); // Sesuai view
        $title = $this->request->getPost('title');
        $description = $this->request->getPost('description');
        $priorityId = $this->request->getPost('priority_id'); // Sesuai view
        $categoryId = $this->request->getPost('category_id'); // Sesuai view

        // Validasi
        if (empty($projectId) || empty($title) || empty($description) || empty($priorityId) || empty($categoryId)) {
            return redirect()->back()->withInput()->with('error', 'All required fields must be filled');
        }

        // Cek project (Gunakan tabel 'projects' sesuai instruksi sebelumnya)
        $project = $this->projectModel->getProjectForCustomer($projectId, $this->userId);

        if (!$project) {
            return redirect()->back()->with('error', 'Project not found or access denied');
        }

        // Nomor Tiket & Mapping Departemen
        $ticketCount = $this->ticketModel->countTicketsByProject($projectId);
        $ticketNumber = $project['project_code'] . '-' . str_pad($ticketCount + 1, 3, '0', STR_PAD_LEFT);
        

        $departmentMapping = $this->categoryDepartmentMapping->getDepartmentByCategory($categoryId);
        $departmentId = $departmentMapping ? $departmentMapping['department_id'] : null;

        $ticketData = [
            'ticket_number' => $ticketNumber,
            'project_id' => $projectId,
            'customer_id' => $this->userId,
            'category_id' => $categoryId,
            'priority_id' => $priorityId,
            'status_id' => 1, // Open
            'department_id' => $departmentId,
            'subject' => $title,
            'description' => $description,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // var_dump(db_connect()->error());die();

        $ticketId = $this->ticketModel->store($ticketData);

        $this->handleAttachments($ticketId);
        // $this->createTicketNotification($ticketId);

        // Sekarang redirect akan bekerja dengan benar karena dikirim via Form HTML, bukan AJAX
        return redirect()->to('/customer/my_tickets')->with('success', 'Ticket created successfully!');
    }

    private function handleAttachments($ticketId)
    {
        $files = $this->request->getFiles();

        if (!empty($files['attachments'])) {
            foreach ($files['attachments'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(WRITEPATH . 'uploads/tickets', $newName);

                    $attachmentData = [
                        'ticket_id' => $ticketId,
                        'uploaded_by' => $this->userId,
                        'file_name' => $file->getName(),
                        'file_path' => 'uploads/tickets/' . $newName,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                        'created_at' => date('Y-m-d H:i:s')
                    ];

                    $this->ticketAttachmentModel->insert($attachmentData);
                }
            }
        }
    }

    private function createTicketNotification($ticketId)
    {
        $ticket = $this->db->table('tickets')
            ->select('t.*, p.project_name, u.full_name as customer_name')
            ->join('projects p', 'p.project_id = t.project_id')
            ->join('users u', 'u.user_id = t.customer_id')
            ->where('t.ticket_id', $ticketId)
            ->get()
            ->getRowArray();

        // Create notification for support team
        $notificationData = [
            'user_id' => 3, // Support team user ID (adjust as needed)
            'ticket_id' => $ticketId,
            'title' => 'New Ticket Created',
            'message' => $ticket['customer_name'] . ' created a new ticket in ' . $ticket['project_name'],
            'notification_type' => 'ticket_created',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->table('notifications')->insert($notificationData);
    }

    public function markAllRead()
    {
        $this->notificationModel->markAllAsRead($this->userId);

        return redirect()->back()->with('success', 'All notifications marked as read');
    }
}
