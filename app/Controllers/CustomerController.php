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

class CustomerController extends BaseController
{
    private $userId;
    private $userModel;
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

    public function __construct()
    {
        $this->checkRole(['Customer']);
        $this->userId = session()->get('user_id');

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

    // Helper method for relative time
    private function formatTimeAgo($timestamp)
    {
        $now = time();
        $diff = $now - $timestamp;

        if ($diff < 60)
            return 'Just now';
        if ($diff < 3600)
            return floor($diff / 60) . ' min ago';
        if ($diff < 86400)
            return floor($diff / 3600) . ' hours ago';
        if ($diff < 604800)
            return floor($diff / 86400) . ' days ago';

        return date('M d, Y, h:i A', $timestamp);
    }

    public function ticketDetail($id)
    {
        $data = $this->loadCommonData();
        $ticket = $this->ticketModel->getTicketDetails($id, $this->userId);

        if (!$ticket) {
            return redirect()->to('/customer/my_tickets')->with('error', 'Ticket not found');
        }

        $data['ticket'] = $ticket;
        $data['messages'] = $this->ticketMessageModel->getMessagesForTicket($id);
        $data['attachments'] = $this->ticketAttachmentModel->getAttachmentsForTicket($id);

        return view('Customer/ticket_detail', $data);
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

        return $projects->getAssignedProjects($this->userId);
    }

    // Update method createTicket():
    public function createTicket()
    {
        $data = $this->loadCommonData();

        // Get projects assigned to this customer
        $data['projects'] = $this->getAssignedProjects();

$data['categories'] = $this->categoryModel->getAllCategories();
        $data['priorities'] = $this->priorityModel->getAllPriorities();
        $data['departments'] = $this->departmentModel->getAllDepartments();

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

$ticketId = $this->ticketModel->insert($ticketData);

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